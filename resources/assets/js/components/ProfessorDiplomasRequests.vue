<template>
    <div class="form-group" v-if="data_ready">
        <div class="table-responsive">
            <table v-if="requests.length" class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ translations.labels.topic }}</th>
                        <th>{{ translations.labels.student }}</th>
                        <th>{{ translations.labels.group }}</th>
                        <th>{{ translations.labels.status }}</th>
                        <th>{{ translations.labels.created_at }}</th>
                        <th>{{ translations.labels.actions }}</th>
                    </tr>
                </thead>
                <tbody>
                    <professor-diplomas-requests-row v-for="request in requests" :key="request.id">
                        <template slot="col-topic"><a :href="openDiploma(request)">{{ request.task_title.length > 10 ?
                            request.task_title.substr(0,10) + '...' : request.task_title }}</a></template>
                        <template slot="col-student">{{ request.student }}</template>
                        <template slot="col-group">{{ request.group }}</template>
                        <template slot="col-status">{{ requestStatus(request) }}</template>
                        <template slot="col-cr_at">{{ request.created_at }}</template>
                        <template slot="col-actions">
                            <button class="btn btn-primary btn-sm" @click="acceptRequest(request)">{{ translations.buttons.accept }}</button>
                            <button class="btn btn-danger btn-sm" @click="declineRequest(request)">{{ translations.buttons.decline }}</button>
                        </template>
                    </professor-diplomas-requests-row>
                </tbody>
            </table>
            <div v-else class="form-group">
                <p>{{ translations.labels.no_requests }}</p>
            </div>
        </div>
    </div>
</template>

<script>

import ProfessorDiplomasRequestsRow from './ProfessorDiplomasRequestsRow'

export default {
    components: {
        ProfessorDiplomasRequestsRow
    },
    created() {
        this.getTranslations();
    },
    mounted() {
        var self = this;
        setTimeout(function() {
            self.getFilteredData();
        }, 450);
        console.log('Requests list mounted.');
    },
    methods: {
        getTranslations() {
            var self = this;
            $.ajax({
                url: '/translation/professor/requests/list',
                type: 'GET',
                dataType: 'json'
            })
            .done(function(response) {
                console.log("translations loaded");
                self.translations = response.translations;
                self.data_ready = true;
            })
            .fail(function() {
                console.log("no translations");
            });

        },
        getFilteredData() {
            var self = this;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/diplomas/professor/requests',
                type: 'GET',
                dataType: 'json',
            })
            .done(function(response) {
                console.log('requests list recieved');
                console.log(response);
                self.requests = response.requests;
                self.requests = self.requests.reverse();
            })
            .fail(function(response) {
                console.log('fail');
                console.log(response);
                if (response.hasOwnProperty('responseJSON')) {
                    if (response.responseJSON.hasOwnProperty('redirect')) {
                        window.location.replace(response.responseJSON.redirect);
                    }
                }
            });
        },
        openDiploma(request) {
            return '/diplomas/' + request.diploma_id;
        },
        acceptRequest(request) {
            var self = this;
            $.ajax({
                url: '/diplomas/professor/requests/accept/' + request.student_id + '/' + request.task_id,
                type: 'PATCH',
                dataType: 'json',
                data: request
            })
            .done(function(response) {
                console.log("success");
                self.requests.splice(self.requests.indexOf(request), 1);
            })
            .fail(function(response) {
                console.log("error");
                console.log(response);
            });

        },
        declineRequest(request) {
            var self = this;
            $.ajax({
                url: '/diplomas/professor/requests/decline/' + request.student_id + '/' + request.task_id,
                type: 'PATCH',
                dataType: 'json',
                data: request
            })
            .done(function(response) {
                console.log("success");
                self.requests.splice(self.requests.indexOf(request), 1);
            })
            .fail(function(response) {
                console.log("error");
                console.log(response);
            });
        },
        requestStatus(request) {
            var self = this;
            var statusWord = '';
            switch (request.status) {
                case '0':
                    statusWord = self.translations.labels.pending;
                    break;
                case '1':
                    statusWord = self.translations.labels.accepted;
                    break;
                case '2':
                    statusWord = self.translations.labels.declined;
            }
            console.log(statusWord);
            return statusWord;
        },
    },

    data() {
        return {
            requests: [],
            translations: [],
            data_ready: false,
        }
    }

}
</script>
