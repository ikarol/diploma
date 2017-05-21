<template>
    <div class="form-group" v-if="data_ready">
        <div v-if="groups.length">
            <div class="form-group">
                <label for="group" class="control-label">{{ translations.labels.group }}</label>
                <select name="group" id="group-id" v-model="currGroup.id" class="form-control" @change="getFilteredData">
                    <option v-for="group in groups" :value="group.id">{{ group.name }}</option>
                </select>
            </div>
            <div class="table-responsive">
                <table v-if="course_projects.length" class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="col-md-1" rowspan="2">{{ translations.labels.topic }}</th>
                            <th class="col-md-3" colspan="3">{{ translations.labels.number_of_requests }}
                            <th class="col-md-2" rowspan="2">{{ translations.labels.student }}</th>
                            <th class="col-md-2" rowspan="2">{{ translations.labels.disciplines }}</th>
                            <th class="col-md-1" rowspan="2">{{ translations.labels.created_at }}</th>
                            <th class="col-md-1" rowspan="2">{{ translations.labels.updated_at }}</th>
                            <th class="col-md-2" rowspan="2">{{ translations.labels.actions }}</th>
                        </tr>
                        <tr>
                            <th class="col-md-1">{{ translations.labels.accepted }}</th>
                            <th class="col-md-1">{{ translations.labels.pending }}</th>
                            <th class="col-md-1">{{ translations.labels.declined }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="course_project in course_projects" :key="course_project.id">
                            <td><a :href="openCourseProject(course_project)">{{ course_project.title.length > 10 ?
                                course_project.title.substr(0,10) + '...' : course_project.title }}</a></td>
                            <td>{{ course_project.requests.accepted }}</td>
                            <td>{{ course_project.requests.pending }}</td>
                            <td>{{ course_project.requests.declined }}</td>
                            <td>{{ course_project.student !== null ? course_project.student : '-' }}</td>
                            <td>
                                <span v-for="discipline in course_project.disciplines" :key="discipline.id">
                                    {{ discipline.name + ',' }}
                                    <br>
                                </span>
                            </td>
                            <td>{{ course_project.created_at }}</td>
                            <td>{{ course_project.updated_at !== null ? course_project.updated_at : '-' }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#update-course_project-modal" @click="openUpdateModal(course_project)">{{ translations.buttons.edit }}</button>
                                <button class="btn btn-danger btn-sm" @click="deleteWithConfirm(course_project)">{{ translations.buttons.delete }}</button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <div id="update-course_project-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" @click="clearNewProjectInputs">&times;</button>
                                        <h4 class="modal-title">{{ translations.labels.update_project }}</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="update-course_project-task-title-block" class="form-group">
                                            <label for="task-title">{{ translations.labels.topic }}</label>
                                            <input id="task-title" type="text" class="form-control" v-model="currProject.title" :value="currProject.title" @keypress="clearErrorMessages('update-course_project', 'title')">
                                            <span id="title-help-block" class="help-block" v-if="errors.hasOwnProperty('title')">
                                                <strong>{{ errors.title[0] }}</strong>
                                            </span>
                                        </div>
                                        <div id="update-course_project-task-disciplines-block" class="form-group">
                                            <label for="task-title">{{ translations.labels.disciplines }}</label>
                                            <multiselect v-model="currProject.disciplines" :options="disciplines" :multiple="true" :close-on-select="true" :clear-on-select="false" :hide-selected="true" :placeholder="translations.labels.select_discipline" label="name" track-by="name" @input="clearErrorMessages('update-course_project', 'disciplines')"></multiselect>
                                            <span id="discipline-help-block" class="help-block" v-if="errors.hasOwnProperty('disciplines')">
                                                <strong>{{ errors.disciplines[0] }}</strong>
                                            </span>
                                        </div>
                                        <div id="update-diploma-task-description-block" class="form-group">
                                            <label for="task-description">{{ translations.labels.description }}</label>
                                            <textarea id="task-description" class="form-control" v-model="currProject.description" :value="currProject.description" @keypress="clearErrorMessages('update-course_project', 'description')"></textarea>
                                            <span id="description-help-block" class="help-block" v-if="errors.hasOwnProperty('description')">
                                                <strong>{{ errors.description[0] }}</strong>
                                            </span>
                                        </div>
                                        <div id="update-diploma-task-technologies-block" class="form-group">
                                            <label for="task-technologies">{{ translations.labels.technologies }}</label>
                                            <input id="task-technologies" type="text" class="form-control" v-model="currProject.technologies" :value="currProject.technologies" @keypress="clearErrorMessages('update-diploma', 'technologies')">
                                            <span id="technologies-help-block" class="help-block" v-if="errors.hasOwnProperty('technologies')">
                                                <strong>{{ errors.technologies[0] }}</strong>
                                            </span>
                                        </div>
                                        <div id="update-diploma-task-group-block" class="form-group">
                                            <label for="task-group">{{ translations.labels.group }}</label>
                                            <br>
                                            <i>{{ currGroup.name }}</i>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" @click="updateTask">{{ translations.buttons.update }}</button>
                                        <button id="close-update-course_project-modal" type="button" class="btn btn-default" data-dismiss="modal" @click="clearUpdateProjectInputs">{{ translations.buttons.cancel }}</button>
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
                <button type="button"class="btn btn-primary" data-toggle="modal" data-target="#new-course_project-modal">{{ translations.buttons.new_project }}</button>
                <div id="new-course_project-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" @click="clearNewProjectInputs">&times;</button>
                                <h4 class="modal-title">{{ translations.buttons.new_project }}</h4>
                            </div>
                            <div class="modal-body">
                                <div id="new-course_project-task-title-block" class="form-group">
                                    <label for="task-title">{{ translations.labels.topic }}</label>
                                    <input id="task-title" type="text" class="form-control" v-model="newProject.title" :value="newProject.title" @keypress="clearErrorMessages('new-course_project', 'title')">
                                    <span id="title-help-block" class="help-block" v-if="errors.hasOwnProperty('title')">
                                        <strong>{{ errors.title[0] }}</strong>
                                    </span>
                                </div>
                                <div id="new-course_project-task-disciplines-block" class="form-group">
                                    <label for="task-title">{{ translations.labels.disciplines }}</label>
                                    <multiselect v-model="newProject.selectedDisciplines" :options="disciplines" :multiple="true" :close-on-select="true" :clear-on-select="false" :hide-selected="true" :placeholder="translations.labels.select_discipline" label="name" track-by="name" @input="clearErrorMessages('new-course_project', 'disciplines')"></multiselect>
                                    <span id="discipline-help-block" class="help-block" v-if="errors.hasOwnProperty('disciplines')">
                                        <strong>{{ errors.disciplines[0] }}</strong>
                                    </span>
                                </div>
                                <div id="new-course_project-task-description-block" class="form-group">
                                    <label for="task-description">{{ translations.labels.description }}</label>
                                    <textarea id="task-description" class="form-control" v-model="newProject.description" :value="newProject.description" @keypress="clearErrorMessages('new-course_project', 'description')"></textarea>
                                    <span id="description-help-block" class="help-block" v-if="errors.hasOwnProperty('description')">
                                        <strong>{{ errors.description[0] }}</strong>
                                    </span>
                                </div>
                                <div id="new-course_project-task-technologies-block" class="form-group">
                                    <label for="task-technologies">{{ translations.labels.technologies }}</label>
                                    <input id="task-technologies" type="text" class="form-control" v-model="newProject.technologies" :value="newProject.technologies" @keypress="clearErrorMessages('new-course_project', 'technologies')">
                                    <span id="technologies-help-block" class="help-block" v-if="errors.hasOwnProperty('technologies')">
                                        <strong>{{ errors.technologies[0] }}</strong>
                                    </span>
                                </div>
                                <div id="new-course_project-task-group-block" class="form-group">
                                    <label for="task-group">{{ translations.labels.group }}</label>
                                    <br>
                                    <i>{{ currGroup.name }}</i>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" @click="publishProject">{{ translations.buttons.publish }}</button>
                                <button id="close-course_project-modal" type="button" class="btn btn-default" data-dismiss="modal" @click="clearNewProjectInputs">{{ translations.buttons.cancel }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="form-group">
            <p>{{ translations.labels.no_groups }}</p>
        </div>
    </div>

</template>

<script>

import Multiselect from 'vue-multiselect'

    export default {
        components: {
            Multiselect
        },
        created() {
            this.getTranslations();
            this.getDisciplinesList();
        },
        beforeMount() {
            var self = this;
            this.getGroupList();
        },
        mounted() {
            // console.log('Diplomas list mounted.');
            // var self = this;
            // setTimeout(function() {
            //     self.getFilteredData();
            // }, 450);
        },
        methods: {
            getTranslations() {
                var self = this;
                $.ajax({
                    url: '/translation/professor/course_projects/list',
                    type: 'GET',
                    dataType: 'json'
                })
                .done(function(response) {
                    console.log("translations loaded");
                    self.translations = response.translations;
                })
                .fail(function() {
                    console.log("error");
                });

            },
            getFilteredData() {
                var self = this;
                // self.setCurrentGroup();
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
                    url: '/course_projects/professor/list',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        group_id: self.currGroup.id
                    }
                })
                .done(function(response) {
                    console.log('course_projects list recieved');
                    console.log(response);
                    self.course_projects = response.course_projects;
                    if (self.course_projects.length) {
                        self.course_projects = self.course_projects.reverse();
                    }
                    self.data_ready = true;
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
            getDisciplinesList() {
                var self = this;
                $.ajax({
                    url: '/disciplines/list',
                    type: 'GET',
                    dataType: 'json'
                })
                .done(function(response) {
                    console.log("success");
                    console.log(response);
                    self.disciplines = response.disciplines;
                })
                .fail(function(response) {
                    console.log("error");
                    console.log(response);
                });

            },
            openCourseProject(course_project) {
                return '/course_projects/' + course_project.id;
            },
            getGroupList() {
                var self = this;
                $.ajax({
                    url: '/diplomas/professor/groups',
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
            clearNewProjectInputs() {
                var self = this;
                $.each(this.errors, function(index, value) {
                    self.clearErrorMessages('new-course_project', index);
                });
                this.newProject = {
                    title: '',
                    description: '',
                    technologies: ''
                };
            },
            publishProject() {
                var self = this;
                $.ajax({
                    url: '/course_projects',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        title: self.newProject.title,
                        description: self.newProject.description,
                        technologies: self.newProject.technologies ? self.newProject.technologies : '',
                        disciplines: self.newProject.selectedDisciplines,
                        group_id : self.currGroup.id
                    }
                })
                .done(function(response) {
                    console.log("success");
                    console.log(response);
                    self.course_projects.unshift(response);
                    self.clearNewProjectInputs();
                    $('#close-course_project-modal').click();
                })
                .fail(function(response) {
                    console.log("error");
                    console.log(response);
                    self.errors = response.responseJSON;
                    if (self.errors.hasOwnProperty('title')) {
                        $('#new-course_project-task-title-block').addClass('has-error');
                    }
                    if (self.errors.hasOwnProperty('description')) {
                        $('#new-course_project-task-description-block').addClass('has-error');
                    }
                    if (self.errors.hasOwnProperty('disciplines')) {
                        $('#new-course_project-task-disciplines-block').addClass('has-error');
                    }
                    if (self.errors.hasOwnProperty('technologies')) {
                        $('#new-course_project-task-technologies-block').addClass('has-error');
                    }
                });
            },
            clearErrorMessages(modalName, fieldName) {
                console.log('clearing..');
                var self = this;
                if (self.errors.hasOwnProperty(fieldName)) {
                    $('#' + modalName + '-task-' + fieldName + '-block').removeClass('has-error');
                    delete self.errors[fieldName];
                }
            },
            deleteWithConfirm(course_project) {
                var self = this;
                swal({
                    title: self.translations.buttons.delete + ' ' + course_project.title + '?',
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
                        url: '/course_projects/' + course_project.id,
                        type: 'DELETE',
                        dataType: 'json'
                    })
                    .done(function(response) {
                        console.log("success");
                        self.course_projects.splice(self.course_projects.indexOf(course_project), 1);
                    })
                    .fail(function(response) {
                        console.log("error");
                        console.log("response");
                    });

                });
            },
            openUpdateModal(course_project) {
                this.currProject = {
                    created_at: course_project.created_at,
                    description: course_project.description,
                    group_id: course_project.group_id,
                    id: course_project.id,
                    professor_id: course_project.professor_id,
                    requests: course_project.requests,
                    technologies: course_project.technologies,
                    title: course_project.title,
                    type: course_project.type,
                    updated_at: course_project.updated_at,
                    disciplines: course_project.disciplines
                };
            },
            clearUpdateProjectInputs() {
                var self = this;
                $.each(this.errors, function(index, value) {
                    self.clearErrorMessages('update-course_project', index);
                });
                this.currProject = {};
            },
            updateTask() {
                var self = this;
                $.ajax({
                    url: '/course_projects/' + self.currProject.id,
                    type: 'PATCH',
                    dataType: 'json',
                    data: self.currProject
                })
                .done(function(response) {
                    console.log("task updated");
                    console.log(response);
                    self.course_projects[
                        self.course_projects.map(function(course_project) {
                            return course_project.id;
                        }).indexOf(self.currProject.id)
                    ] = response;
                    self.clearUpdateProjectInputs();
                    $('#close-update-course_project-modal').click();
                })
                .fail(function(response) {
                    console.log("error");
                    console.log(response);
                    self.errors = response.responseJSON;
                    if (self.errors.hasOwnProperty('title')) {
                        $('#update-course_project-task-title-block').addClass('has-error');
                    }
                    if (self.errors.hasOwnProperty('description')) {
                        $('#update-course_project-task-description-block').addClass('has-error');
                    }
                    if (self.errors.hasOwnProperty('disciplines')) {
                        $('#update-course_project-task-disciplines-block').addClass('has-error');
                    }
                    if (self.errors.hasOwnProperty('technologies')) {
                        $('#update-course_project-task-technologies-block').addClass('has-error');
                    }
                });

            }
        },
        data() {
            return {
                course_projects: [],
                translations : [],
                groups: [],
                disciplines: [],
                currGroup: {
                    id: '',
                    name: ''
                },
                newProject: {
                    title: '',
                    description: '',
                    technologies: '',
                    selectedDisciplines: [],
                },
                currProject: {
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
        },
        watch: {
            groups: function() {
                this.getFilteredData();
            }
        }
    }
</script>
