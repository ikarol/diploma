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
                        <th class="col-md-1" rowspan="2">{{ translations.labels.topic }}</th>
                        <th class="col-md-3" colspan="3">{{ translations.labels.number_of_requests }}
                        <th class="col-md-3" rowspan="2">{{ translations.labels.student }}</th>
                        <th class="col-md-2" rowspan="2">{{ translations.labels.created_at }}</th>
                        <th class="col-md-3" rowspan="2">{{ translations.labels.actions }}</th>
                    </tr>
                    <tr>
                        <th class="col-md-1">{{ translations.labels.accepted }}</th>
                        <th class="col-md-1">{{ translations.labels.pending }}</th>
                        <th class="col-md-1">{{ translations.labels.declined }}</th>
                    </tr>
                </thead>
                <tbody>
                    <professor-diplomas-row v-for="diploma in diplomas" :key="diploma.id">
                        <template slot="col-topic"><a :href="openDiploma(diploma)">{{ diploma.title.length > 10 ?
                            diploma.title.substr(0,10) + '...' : diploma.title }}</a></template>
                        <template slot="col-requests-accepted">{{ diploma.requests.accepted }}</template>
                        <template slot="col-requests-pending">{{ diploma.requests.pending }}</template>
                        <template slot="col-requests-denied">{{ diploma.requests.declined }}</template>
                        <template slot="col-student">{{ diploma.student !== null ? diploma.student : translations.labels.empty }}</template>
                        <template slot="col-cr_at">{{ diploma.created_at }}</template>
                        <template slot="col-actions">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#update-diploma-modal" @click="openUpdateModal(diploma)">{{ translations.buttons.edit }}</button>
                            <button class="btn btn-danger btn-sm" @click="deleteWithConfirm(diploma)">{{ translations.buttons.delete }}</button>
                        </template>
                    </professor-diplomas-row>
                </tbody>
                <tfoot>
                    <div id="update-diploma-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" @click="clearNewTaskInputs">&times;</button>
                                    <h4 class="modal-title">{{ translations.labels.update_task }}</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="task-title-block" class="form-group">
                                        <label for="task-title">{{ translations.labels.topic }}</label>
                                        <input id="task-title" type="text" class="form-control" v-model="currTask.title" :value="currTask.title" @keypress="clearErrorMessages('title')">
                                        <span id="title-help-block" class="help-block" v-if="errors.hasOwnProperty('title')">
                                            <strong>{{ errors.title[0] }}</strong>
                                        </span>
                                    </div>
                                    <div id="task-description-block" class="form-group">
                                        <label for="task-description">{{ translations.labels.description }}</label>
                                        <textarea id="task-description" class="form-control" v-model="currTask.description" :value="currTask.description" @keypress="clearErrorMessages('description')"></textarea>
                                        <span id="description-help-block" class="help-block" v-if="errors.hasOwnProperty('description')">
                                            <strong>{{ errors.description[0] }}</strong>
                                        </span>
                                    </div>
                                    <div id="task-technologies-block" class="form-group">
                                        <label for="task-technologies">{{ translations.labels.technologies }}</label>
                                        <input id="task-technologies" type="text" class="form-control" v-model="currTask.technologies" :value="currTask.technologies" @keypress="clearErrorMessages('technologies')">
                                        <span id="technologies-help-block" class="help-block" v-if="errors.hasOwnProperty('technologies')">
                                            <strong>{{ errors.technologies[0] }}</strong>
                                        </span>
                                    </div>
                                    <div id="task-group-block" class="form-group">
                                        <label for="task-group">{{ translations.labels.group }}</label>
                                        <br>
                                        <i>{{ currGroup.name }}</i>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" @click="updateTask">{{ translations.buttons.update }}</button>
                                    <button id="close-update-task-modal" type="button" class="btn btn-default" data-dismiss="modal" @click="clearUpdateTaskInputs">{{ translations.buttons.cancel }}</button>
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
        <div class="form-group">
            <button type="button"class="btn btn-primary" data-toggle="modal" data-target="#new-diploma-modal">{{ translations.buttons.new_task }}</button>
            <div id="new-diploma-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" @click="clearNewTaskInputs">&times;</button>
                            <h4 class="modal-title">{{ translations.buttons.new_task }}</h4>
                        </div>
                        <div class="modal-body">
                            <div id="task-title-block" class="form-group">
                                <label for="task-title">{{ translations.labels.topic }}</label>
                                <input id="task-title" type="text" class="form-control" v-model="newTask.title" :value="newTask.title" @keypress="clearErrorMessages('title')">
                                <span id="title-help-block" class="help-block" v-if="errors.hasOwnProperty('title')">
                                    <strong>{{ errors.title[0] }}</strong>
                                </span>
                            </div>
                            <div id="task-description-block" class="form-group">
                                <label for="task-description">{{ translations.labels.description }}</label>
                                <textarea id="task-description" class="form-control" v-model="newTask.description" :value="newTask.description" @keypress="clearErrorMessages('description')"></textarea>
                                <span id="description-help-block" class="help-block" v-if="errors.hasOwnProperty('description')">
                                    <strong>{{ errors.description[0] }}</strong>
                                </span>
                            </div>
                            <div id="task-technologies-block" class="form-group">
                                <label for="task-technologies">{{ translations.labels.technologies }}</label>
                                <input id="task-technologies" type="text" class="form-control" v-model="newTask.technologies" :value="newTask.technologies" @keypress="clearErrorMessages('technologies')">
                                <span id="technologies-help-block" class="help-block" v-if="errors.hasOwnProperty('technologies')">
                                    <strong>{{ errors.technologies[0] }}</strong>
                                </span>
                            </div>
                            <div id="task-group-block" class="form-group">
                                <label for="task-group">{{ translations.labels.group }}</label>
                                <br>
                                <i>{{ currGroup.name }}</i>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" @click="publishTask">{{ translations.buttons.publish }}</button>
                            <button id="close-new-task-modal" type="button" class="btn btn-default" data-dismiss="modal" @click="clearNewTaskInputs">{{ translations.buttons.cancel }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

import ProfessorDiplomasRow from './ProfessorDiplomasRow.vue';

    export default {
        components: {
            ProfessorDiplomasRow
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
                    url: '/translation/professor/diplomas/list',
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
                    url: '/diplomas/professor/list',
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
            openDiploma(diploma) {
                return '/diplomas/' + diploma.id;
            },
            setCurrentGroup() {
                this.currGroup.id = $('#group-id').val();
                this.currGroup.name = $('#group-id option:selected').text();
            },
            getGroupList() {
                var self = this;
                $.ajax({
                    url: '/professor/groups',
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
            clearNewTaskInputs() {
                this.newTask = {
                    title: '',
                    description: '',
                    technologies: ''
                };
            },
            publishTask() {
                var self = this;
                $.ajax({
                    url: '/diplomas',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        title: self.newTask.title,
                        description: self.newTask.description,
                        technologies: self.newTask.technologies ? self.newTask.technologies : '',
                        group_id : self.currGroup.id
                    }
                })
                .done(function(response) {
                    console.log("success");
                    console.log(response);
                    self.diplomas.unshift(response);
                    self.clearNewTaskInputs();
                    $('#close-new-task-modal').click();
                })
                .fail(function(response) {
                    console.log("error");
                    console.log(response);
                    self.errors = response.responseJSON;
                    if (self.errors.hasOwnProperty('title')) {
                        $('#task-title-block').addClass('has-error');
                    }
                    if (self.errors.hasOwnProperty('description')) {
                        $('#task-description-block').addClass('has-error');
                    }
                    if (self.errors.hasOwnProperty('technologies')) {
                        $('#task-technologies-block').addClass('has-error');
                    }
                });
            },
            clearErrorMessages(fieldName) {
                console.log('clearing..');
                var self = this;
                if (self.errors.hasOwnProperty(fieldName)) {
                    $('#task-' + fieldName + '-block').removeClass('has-error');
                    delete self.errors[fieldName];
                }
            },
            deleteWithConfirm(diploma) {
                var self = this;
                swal({
                    title: self.translations.buttons.delete + ' ' + diploma.title + '?',
                    type: 'warning',
                    showCancelButton: true,
                    closeOnConfirm: true,
                    showLoaderOnConfirm: true,
                    cancelButtonText: self.translations.buttons.cancel,
                    confirmButtonText: "Ок",
                    confirmButtonColor: '#3085d6',
                    confirmLoadingButtonColor: '#DD6B55'
                }, function(){
                    $.ajax({
                        url: '/diplomas/' + diploma.id,
                        type: 'DELETE',
                        dataType: 'json'
                    })
                    .done(function(response) {
                        console.log("success");
                        self.diplomas.splice(self.diplomas.indexOf(diploma), 1);
                    })
                    .fail(function(response) {
                        console.log("error");
                        console.log("response");
                    });

                });
            },
            openUpdateModal(diploma) {
                this.currTask = {
                    created_at: diploma.created_at,
                    description: diploma.description,
                    group_id: diploma.group_id,
                    id: diploma.id,
                    professor_id: diploma.professor_id,
                    requests: diploma.requests,
                    technologies: diploma.technologies,
                    title: diploma.title,
                    type: diploma.type,
                    updated_at: diploma.updated_at
                };
            },
            clearUpdateTaskInputs() {
                this.currTask = {
                    created_at: '',
                    description: '',
                    group_id: '',
                    id: '',
                    professor_id: '',
                    requests: '',
                    technologies: '',
                    title: '',
                    type: '',
                    updated_at: ''
                };
            },
            updateTask() {
                var self = this;
                $.ajax({
                    url: '/diplomas/' + self.currTask.id,
                    type: 'PATCH',
                    dataType: 'json',
                    data: self.currTask
                })
                .done(function(response) {
                    console.log("task updated");
                    console.log(response);
                    self.diplomas[
                        self.diplomas.map(function(diploma) {
                            return diploma.id;
                        }).indexOf(self.currTask.id)
                    ] = response;
                    self.clearUpdateTaskInputs();
                    $('#close-update-task-modal').click();
                })
                .fail(function(response) {
                    console.log("error");
                    console.log(response);
                    self.errors = response.responseJSON;
                    if (self.errors.hasOwnProperty('title')) {
                        $('#task-title-block').addClass('has-error');
                    }
                    if (self.errors.hasOwnProperty('description')) {
                        $('#task-description-block').addClass('has-error');
                    }
                    if (self.errors.hasOwnProperty('technologies')) {
                        $('#task-technologies-block').addClass('has-error');
                    }
                });

            }
        },
        data() {
            return {
                diplomas: [],
                translations : [],
                groups: [],
                currGroup: {
                    id: '',
                    name: ''
                },
                newTask: {
                    title: '',
                    description: '',
                    technologies: ''
                },
                currTask: {
                    created_at: '',
                    description: '',
                    group_id: '',
                    id: '',
                    professor_id: '',
                    requests: '',
                    technologies: '',
                    title: '',
                    type: '',
                    updated_at: ''
                },
                data_ready: false,
                errors: {

                }
            }
        }
    }
</script>
