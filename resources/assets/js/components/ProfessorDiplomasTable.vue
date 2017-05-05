<template>
    <div class="form-group">
        <div class="form-group">
            <label for="group" class="control-label"><slot name="title-group"></slot></label>
            <select name="group" id="group-id" class="form-control" @change="getFilteredData">
                <option v-for="group in groups" :value="group.id">{{ group.name }}</option>
            </select>
        </div>
        <div class="table-responsive">
            <span id="button-edit" hidden="true"><slot name="button-edit"></slot></span>
            <span id="button-delete" hidden="true"><slot name="button-delete"></slot></span>
            <span id="button-cancel" hidden="true"><slot name="button-cancel"></slot></span>
            <span id="button-publish" hidden="true"><slot name="button-publish"></slot></span>
            <span id="button-update" hidden="true"><slot name="button-update"></slot></span>
            <span id="task-title" hidden="true"><slot name="task-title"></slot></span>
            <span id="task-description" hidden="true"><slot name="task-description"></slot></span>
            <span id="task-technologies" hidden="true"><slot name="task-technologies"></slot></span>
            <span id="task-group-title" hidden="true"><slot name="task-group-title"></slot></span>
            <table v-if="diplomas.length" class="table table-bordered">
                <thead>
                    <tr>
                        <th><slot name='title-topic'></slot></th>
                        <th><slot name='title-request'></slot></th>
                        <th><slot name='title-cr_at'></slot></th>
                        <th><slot name='title-actions'></slot></th>
                    </tr>
                </thead>
                <tbody>
                    <professor-diplomas-row v-for="diploma in diplomas" :key="diploma.id">
                        <template slot="col-topic"><a :href="openDiploma(diploma)">{{ diploma.title.length > 10 ?
                            diploma.title.substr(0,10) + '...' : diploma.title }}</a></template>
                        <template slot="col-requests">{{ diploma.requests }}</template>
                        <template slot="col-cr_at">{{ diploma.created_at }}</template>
                        <template slot="col-actions">
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#update-diploma-modal" @click="openUpdateModal(diploma)">{{ button_names.edit }}</button>
                            <button class="btn btn-danger btn-sm" @click="deleteWithConfirm(diploma)">{{ button_names.delete }}</button>
                        </template>
                    </professor-diplomas-row>
                </tbody>
                <tfoot>
                    <div id="update-diploma-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" @click="clearNewTaskInputs">&times;</button>
                                    <h4 class="modal-title"><slot name="update-diploma-modal-title"></slot></h4>
                                </div>
                                <div class="modal-body">
                                    <div id="task-title-block" class="form-group">
                                        <label for="task-title">{{ labels.title }}</label>
                                        <input id="task-title" type="text" class="form-control" v-model="currTask.title" :value="currTask.title" @keypress="clearErrorMessages('title')">
                                        <span id="title-help-block" class="help-block" v-if="errors.hasOwnProperty('title')">
                                            <strong>{{ errors.title[0] }}</strong>
                                        </span>
                                    </div>
                                    <div id="task-description-block" class="form-group">
                                        <label for="task-description">{{ labels.description }}</label>
                                        <textarea id="task-description" class="form-control" v-model="currTask.description" :value="currTask.description" @keypress="clearErrorMessages('description')"></textarea>
                                        <span id="description-help-block" class="help-block" v-if="errors.hasOwnProperty('description')">
                                            <strong>{{ errors.description[0] }}</strong>
                                        </span>
                                    </div>
                                    <div id="task-technologies-block" class="form-group">
                                        <label for="task-technologies">{{ labels.technologies }}</label>
                                        <input id="task-technologies" type="text" class="form-control" v-model="currTask.technologies" :value="currTask.technologies" @keypress="clearErrorMessages('technologies')">
                                        <span id="technologies-help-block" class="help-block" v-if="errors.hasOwnProperty('technologies')">
                                            <strong>{{ errors.technologies[0] }}</strong>
                                        </span>
                                    </div>
                                    <div id="task-group-block" class="form-group">
                                        <label for="task-group">{{ labels.group_name }}</label>
                                        <br>
                                        <i>{{ currGroup.name }}</i>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" @click="updateTask">{{ button_names.update }}</button>
                                    <button id="close-update-task-modal" type="button" class="btn btn-default" data-dismiss="modal" @click="clearUpdateTaskInputs">{{ button_names.cancel }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </tfoot>
            </table>
            <div v-else class="form-group">
                <p><slot name="no-diplomas"></slot></p>
            </div>
        </div>
        <div class="form-group">
            <button type="button"class="btn btn-primary" data-toggle="modal" data-target="#new-diploma-modal"><slot name="open-diploma-modal"></slot></button>
            <div id="new-diploma-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" @click="clearNewTaskInputs">&times;</button>
                            <h4 class="modal-title"><slot name="new-diploma-modal-title"></slot></h4>
                        </div>
                        <div class="modal-body">
                            <div id="task-title-block" class="form-group">
                                <label for="task-title">{{ labels.title }}</label>
                                <input id="task-title" type="text" class="form-control" v-model="newTask.title" :value="newTask.title" @keypress="clearErrorMessages('title')">
                                <span id="title-help-block" class="help-block" v-if="errors.hasOwnProperty('title')">
                                    <strong>{{ errors.title[0] }}</strong>
                                </span>
                            </div>
                            <div id="task-description-block" class="form-group">
                                <label for="task-description">{{ labels.description }}</label>
                                <textarea id="task-description" class="form-control" v-model="newTask.description" :value="newTask.description" @keypress="clearErrorMessages('description')"></textarea>
                                <span id="description-help-block" class="help-block" v-if="errors.hasOwnProperty('description')">
                                    <strong>{{ errors.description[0] }}</strong>
                                </span>
                            </div>
                            <div id="task-technologies-block" class="form-group">
                                <label for="task-technologies">{{ labels.technologies }}</label>
                                <input id="task-technologies" type="text" class="form-control" v-model="newTask.technologies" :value="newTask.technologies" @keypress="clearErrorMessages('technologies')">
                                <span id="technologies-help-block" class="help-block" v-if="errors.hasOwnProperty('technologies')">
                                    <strong>{{ errors.technologies[0] }}</strong>
                                </span>
                            </div>
                            <div id="task-group-block" class="form-group">
                                <label for="task-group">{{ labels.group_name }}</label>
                                <br>
                                <i>{{ currGroup.name }}</i>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" @click="publishTask">{{ button_names.publish }}</button>
                            <button id="close-new-task-modal" type="button" class="btn btn-default" data-dismiss="modal" @click="clearNewTaskInputs">{{ button_names.cancel }}</button>
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
        beforeMount() {
            this.getGroupList();
        },
        mounted() {
            console.log('Diplomas list mounted.');
            var self = this;
            this.assignButtonLabelNames();
            setTimeout(function() {
                self.getFilteredData();
            }, 400);
        },
        methods: {
            getFilteredData() {
                var self = this;
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/diplomas/data',
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
                    self.diplomas = response;
                    self.diplomas = self.diplomas.reverse();
                })
                .fail(function(response) {
                    console.log('fail');
                    console.log(response);
                });
            },
            assignButtonLabelNames() {
                this.button_names.edit = $('#button-edit').text();
                this.button_names.delete = $('#button-delete').text();
                this.button_names.cancel = $('#button-cancel').text();
                this.button_names.publish = $('#button-publish').text();
                this.button_names.update = $('#button-update').text();
                this.labels.title = $('#task-title').text();
                this.labels.description = $('#task-description').text();
                this.labels.technologies = $('#task-technologies').text();
                this.labels.group_name = $('#task-group-title').text();
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
                    url: '/groups',
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
                    title: self.button_names.delete + ' ' + diploma.title + '?',
                    type: 'warning',
                    showCancelButton: true,
                    closeOnConfirm: true,
                    showLoaderOnConfirm: true,
                    cancelButtonText: self.button_names.cancel,
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
                button_names: {
                    edit: '',
                    delete: '',
                    cancel: '',
                    publish: '',
                    update: ''
                },
                labels: {
                    title: '',
                    description: '',
                    technologies: '',
                    group_name: ''
                },
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
                errors: {

                }
            }
        }
    }
</script>
