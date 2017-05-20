<template>
    <div class="form-group" v-if="data_ready">
        <div v-if="groups.length">
            <div class="form-group">
                <label for="group" class="control-label">{{ translations.labels.group }}</label>
                <select name="group" id="group-id" v-model="currGroup.id" class="form-control" @change="getFilteredData">
                    <option v-for="group in groups" :value="group.id">{{ group.name }}</option>
                </select>
            </div>
            <div class="form-group">
                <label>{{ translations.labels.status }}:</label>
                <!-- <div class="btn-group"  id="status_type">
                    <label class="btn btn-default" for="one">
                        <input type="radio"  id="one" value="3" v-model="status_type">{{ translations.buttons.all }}
                    </label>
                    <label class="btn btn-default" for="two">
                        <input type="radio"  id="two" value="0" v-model="status_type">{{ translations.buttons.pending }}
                    </label>
                    <label class="btn btn-default" for="three">
                        <input type="radio"  id="three" value="1" v-model="status_type">{{ translations.buttons.accepted }}
                    </label>
                    <label class="btn btn-default" for="four">
                        <input type="radio"  id="four" value="2" v-model="status_type">{{ translations.buttons.declined }}
                    </label>
                </div> -->
                <!-- <div class="btn-group" data-toggle="buttons" id="status_type" v-radio="status_type">
                    <label class="btn btn-primary active">
                        <input type="radio" name="status_type" id="option1" value="3" autocomplete="off">{{ translations.buttons.all }}
                    </label>
                    <label class="btn btn-primary">
                        <input type="radio" name="status_type" id="option2" value="0" autocomplete="off">{{ translations.buttons.pending }}
                    </label>
                    <label class="btn btn-primary">
                        <input type="radio" name="status_type" id="option3" value="1" autocomplete="off">{{ translations.buttons.accepted }}
                    </label>
                    <label class="btn btn-primary">
                        <input type="radio" name="status_type" id="option3" value="2" autocomplete="off">{{ translations.buttons.declined }}
                    </label>
                </div> -->
                <status
                    :values="statuses" :selected.sync="status_type" :default="'3'">
                </status>
            </div>
            <div class="table-responsive">
                <div v-if="requests.length">
                    <panel v-for="request in requests" :key="request.id" :class="setPanelsColour(request)">
                        <div slot="task-title" class="form-group">
                            <h1>{{ request.task_title }}</h1>
                        </div>
                        <div slot="request-student" class="form-group">
                            <label for="request-student">{{ translations.labels.student }}:</label>
                            <span name="request-student">{{ request.student }}</span>
                        </div>
                        <div slot="request-status" class="form-group">
                            <label for="request-status">{{ translations.labels.status }}:</label>
                            <span name="request-status">{{ requestStatus(request) }}</span>
                        </div>
                        <div slot="request-message" class="form-group">
                            <label for="request-message">{{ translations.labels.message }}:</label>
                            <span name="request-message">{{ request.message ? request.message : '-' }}</span>
                        </div>
                        <div slot="request-cr_at" class="form-group">
                            <label for="request-cr_at">{{ translations.labels.created_at }}:</label>
                            <span name="request-cr_at">{{ request.created_at }}</span>
                        </div>
                        <div slot="request-footer" class="panel-footer" v-if="request.status === '0'">
                            <button class="btn btn-primary btn-sm" @click="acceptRequest(request)">{{ translations.buttons.accept }}</button>
                            <button class="btn btn-danger btn-sm" @click="declineRequest(request)">{{ translations.buttons.decline }}</button>
                        </div>
                    </panel>
                </div>
                <div v-else class="form-group">
                    <p>{{ translations.labels.no_requests }}</p>
                </div>
            </div>
        </div>
        <div v-else class="form-group">
            <p>{{ translations.labels.no_requests }}</p>
        </div>
    </div>
</template>

<script>

import Panel from './Panel'
import Status from './Status'

export default {
    components: {
        Panel,
        Status
    },
    computed: {
        statuses() {
            return {
                3: this.translations.buttons.all,
                0: this.translations.buttons.pending,
                1: this.translations.buttons.accepted,
                2: this.translations.buttons.declined,
            }
        }
    },
    directives: {
        radio: {
            twoWay: true,
            bind: function() {
                var self = this;
                var btns = $('#status_type').find('.btn');
                btns.each(function() {
                    $(this).on('click', function() {
                        var v = $(this).find('input').get(0).value
                        self.set(v);
                    })
                });
            },
            update: function() {
                var value = this._watcher.value;
                if (value) {
                    this.set(value);
                    var btns = $(this.el).find('.btn')
                    btns.each(function() {
                        $(this).removeClass('active');
                        var v = $(this).find('input').get(0).value;

                        if (v === value) {
                            $(this).addClass('active');
                        }
                    });
                } else {
                    var input = $(this.el).find('.active input').get(0);
                    if (input) {
                        this.set(input.value);
                    }
                }
            }
        }
    },
    created() {
        this.getTranslations();
    },
    beforeMount() {
        this.getGroupList();
    },
    mounted() {
        // var self = this;
        // setTimeout(function() {
        //     self.getFilteredData();
        // }, 450);
        // console.log('Requests list mounted.');
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
            })
            .fail(function() {
                console.log("no translations");
            });

        },
        getFilteredData() {
            var self = this;
            var activeGroup = _.find(this.groups, {id: this.currGroup.id});
            if (typeof activeGroup !== 'undefined') {
                this.currGroup.name = activeGroup.name;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/diplomas/professor/requests',
                type: 'GET',
                dataType: 'json',
                data: {
                    group_id: self.currGroup.id,
                    status_type: self.status_type
                }
            })
            .done(function(response) {
                console.log('requests list recieved');
                console.log(response);
                self.requests = response.requests;
                if (self.requests.length) {
                    self.requests = self.requests.reverse();
                }
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
        getGroupList() {
            var self = this;
            $.ajax({
                url: '/diplomas/professor/requests/groups',
                type: 'get',
                dataType: 'json'
            })
            .done(function(response) {
                console.log('groups list recieved');
                console.log(response);
                self.groups = response;
                self.currGroup = {
                    'id': self.groups[0].id,
                    'name': self.groups[0].name,
                };
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
                self.requests[self.requests.indexOf(request)].status = '1';
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
                self.requests[self.requests.indexOf(request)].status = '2';
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
                    break;
            }
            return statusWord;
        },
        setPanelsColour(request) {
            var panel_class = '';
            switch (request.status) {
                case '0':
                    panel_class = 'panel panel-warning';
                    break;
                case '1':
                    panel_class = 'panel panel-success';
                    break;
                case '2':
                    panel_class = 'panel panel-danger';
            }
            return panel_class;
        }
    },

    data() {
        return {
            requests: [],
            translations: [],
            groups: [],
            data_ready: false,
            status_type: '',
            currGroup: {
                id: '',
                name: ''
            },
        }
    },
    watch: {
        status_type: function() {
            this.getFilteredData();
        },
        groups: function() {
            this.data_ready = true;
        },
    },

}
</script>
