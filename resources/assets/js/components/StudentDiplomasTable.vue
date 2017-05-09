<template>
    <div class="form-group" v-if="data_ready">
        <div class="form-group">
            <label for="group" class="control-label">{{ translations.labels.group }}</label>
            <select name="group" id="group-id" class="form-control" @change="getFilteredData">
                <option v-for="group in groups" :value="group.id">{{ group.name }}</option>
            </select>
        </div>
        <div class="table-responsive">
            <table v-if="diplomas.length" class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ translations.labels.topic }}</th>
                        <th>{{ translations.labels.professor }}</th>
                        <th>{{ translations.labels.technologies }}</th>
                        <th>{{ translations.labels.created_at }}</th>
                        <th>{{ translations.labels.actions }}</th>
                    </tr>
                </thead>
                <tbody>
                    <student-diplomas-row v-for="diploma in diplomas" :key="diploma.id">
                        <template slot="col-topic"><a :href="openDiploma(diploma)">{{ diploma.title.length > 10 ?
                            diploma.title.substr(0,10) + '...' : diploma.title }}</a></template>
                        <template slot="col-professor">{{ diploma.professor }}</template>
                        <template slot="col-technologies">{{ diploma.technologies ? diploma.technologies : translations.labels.empty }}</template>
                        <template slot="col-cr_at">{{ diploma.created_at }}</template>
                        <template slot="col-actions">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#diploma-request-modal" @click="openRequestModal(diploma)">{{ translations.buttons.apply }}</button>
                        </template>
                    </student-diplomas-row>
                </tbody>
                <tfoot>
                    <div id="diploma-request-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" @click="clearRequestInputs">&times;</button>
                                    <h4 class="modal-title">{{ translations.labels.request }}</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="task-title-block" class="form-group">
                                        <label for="task-title">{{ translations.labels.topic }}</label>
                                        <br>
                                        <i>{{ currTask.title }}</i>
                                    </div>
                                    <div id="task-technologies-block" class="form-group">
                                        <label for="task-group">{{ translations.labels.technologies }}</label>
                                        <br>
                                        <i>{{ currTask.technologies }}</i>
                                    </div>
                                    <div id="task-professor-block" class="form-group">
                                        <label for="task-group">{{ translations.labels.professor }}</label>
                                        <br>
                                        <i>{{ currTask.professor }}</i>
                                    </div>
                                    <div id="task-group-block" class="form-group">
                                        <label for="task-group">{{ translations.labels.group }}</label>
                                        <br>
                                        <i>{{ currTask.group }}</i>
                                    </div>
                                    <div id="task-message-block" class="form-group">
                                        <label for="task-message">{{ translations.labels.message }}</label>
                                        <textarea id="task-message" class="form-control" v-model="currTask.message" :value="currTask.message" @keypress="clearErrorMessages('message')"></textarea>
                                        <span id="message-help-block" class="help-block" v-if="errors.hasOwnProperty('message')">
                                            <strong>{{ errors.message[0] }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" @click="sendRequest">{{ translations.buttons.apply }}</button>
                                    <button id="close-request-modal" type="button" class="btn btn-default" data-dismiss="modal" @click="clearRequestInputs">{{ translations.buttons.cancel }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </tfoot>
            </table>
            <div v-else class="form-group">
                <p>{{ translations.labels.no_tasks }}</p>
            </div>
        </div>
    </div>
</template>

<script>

import StudentDiplomasRow from './StudentDiplomasRow'

export default {
    components: {
        StudentDiplomasRow
    },
    created() {
        this.getTranslations();
    },
    beforeMount() {
        this.getGroupList();
    },
    mounted() {
        console.log('Diplomas list mounted.');
        var self = this;
        setTimeout(function() {
            self.getFilteredData();
        }, 450);
    },
    methods: {
        getTranslations() {
            var self = this;
            $.ajax({
                url: '/translation/student/diplomas/list',
                type: 'GET',
                dataType: 'json'
            })
            .done(function(response) {
                console.log("translations loaded");
                self.translations = response.translations;
                self.data_ready = true;
            })
            .fail(function() {
                console.log("error");
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
                url: '/diplomas/student/list',
                type: 'GET',
                dataType: 'json',
                data: {
                    group_id: $('#group-id').val()
                }
            })
            .done(function(response) {
                console.log('diplomas list recieved');
                console.log(response);
                self.setCurrentGroup();
                self.diplomas = response.diplomas;
                self.diplomas = self.diplomas.reverse();
                self.student_id = response.student_id;
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
        setCurrentGroup() {
            this.currGroup.id = $('#group-id').val();
            this.currGroup.name = $('#group-id option:selected').text();
        },
        openDiploma(diploma) {
            return '/diplomas/' + diploma.id;
        },
        getGroupList() {
            var self = this;
            $.ajax({
                url: 'student/groups',
                type: 'get',
                dataType: 'json'
            })
            .done(function(response) {
                console.log('groups list recieved');
                console.log(response);
                self.groups = response;
            })
            .fail(function(response) {
                console.log("error");
                console.log(response);
                if (response.hasOwnProperty('responseJSON')) {
                    if (response.responseJSON.hasOwnProperty('redirect')) {
                        window.location.replace(response.responseJSON.redirect);
                    }
                }
            });
        },
        clearRequestInputs() {
            this.currTask = {};
        },
        sendRequest() {
            var self = this;
            $.ajax({
                url: '/diplomas/requests/' + self.student_id,
                type: 'POST',
                dataType: 'json',
                data: self.currTask
            })
            .done(function() {
                console.log("success");
                self.diplomas.splice(self.currTask.index, 1);
                self.clearRequestInputs();
                $('#close-request-modal').click();
            })
            .fail(function(response) {
                console.log("error");
                self.errors = response.responseJSON;
                if (self.errors.hasOwnProperty('message')) {
                    $('#task-message-block').addClass('has-error');
                }
            });

        },
        openRequestModal(diploma) {
            var self = this;
            this.setCurrentGroup();
            this.currTask = {
                id: diploma.id,
                title: diploma.title,
                technologies: diploma.technologies ? diploma.technologies : self.translations.labels.empty,
                professor: diploma.professor,
                group: self.currGroup.name,
                message: '',
                index: self.diplomas.indexOf(diploma),
            };
        },
        clearErrorMessages(fieldName) {
            console.log('clearing..');
            var self = this;
            if (self.errors.hasOwnProperty(fieldName)) {
                $('#task-' + fieldName + '-block').removeClass('has-error');
                delete self.errors[fieldName];
            }
        },
    },
    data() {
        return {
            diplomas: [],
            student_int: '',
            translations: [],
            groups: [],
            errors: [],
            currGroup: {},
            currTask:{},
            data_ready: false,
        }
    }

}
</script>
