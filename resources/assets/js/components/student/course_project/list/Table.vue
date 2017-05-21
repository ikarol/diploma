<template>
    <div class="form-group" v-if="data_ready">
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
                        <th>{{ translations.labels.topic }}</th>
                        <th>{{ translations.labels.professor }}</th>
                        <th>{{ translations.labels.technologies }}</th>
                        <th>{{ translations.labels.disciplines }}</th>
                        <th>{{ translations.labels.status }}</th>
                        <th>{{ translations.labels.created_at }}</th>
                        <th>{{ translations.labels.actions }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="course_project in course_projects" :key="course_project.id">
                        <td><a :href="opencourse_project(course_project)">{{ course_project.title.length > 10 ?
                            course_project.title.substr(0,10) + '...' : course_project.title }}</a></td>
                        <td>{{ course_project.professor }}</td>
                        <td>{{ course_project.technologies ? course_project.technologies : '-' }}</td>
                        <td>
                            <span v-for="discipline in course_project.disciplines" :key="discipline.id">
                                {{ discipline.name + ',' }}
                                <br>
                            </span>
                        </td>
                        <td>{{ course_project.status ? course_projectStatus(course_project) : '-' }}</td>
                        <td>{{ course_project.created_at }}</td>
                        <td>
                            <button v-if="course_project.status === '0'" class="btn btn-danger btn-sm" @click="deleteRequest(course_project)">{{ translations.buttons.delete_request }}</button>
                            <button v-else-if="course_project.status === '2'" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#course_project-resend-request-modal" @click="openRequestModal(course_project)">{{ translations.buttons.resend_request }}</button>
                            <a v-else-if="course_project.status === '1'" :href="showJobs(course_project)" class="btn btn-info btn-sm" >{{ translations.buttons.show_tasks }}</a>
                            <button v-else class="btn btn-primary btn-sm" data-toggle="modal" data-target="#course_project-send-request-modal" @click="openRequestModal(course_project)">{{ translations.buttons.apply }}</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <div id="course_project-send-request-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" @click="clearRequestInputs">&times;</button>
                                    <h4 class="modal-title">{{ translations.labels.request }}</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="course_project-send-request-task-title-block" class="form-group">
                                        <label for="course_project-send-request-task-title">{{ translations.labels.topic }}</label>
                                        <br>
                                        <i>{{ currProject.title }}</i>
                                    </div>
                                    <div id="course_project-send-request-task-professor-block" class="form-group">
                                        <label for="course_project-send-request-task-group">{{ translations.labels.professor }}</label>
                                        <br>
                                        <i>{{ currProject.professor }}</i>
                                    </div>
                                    <div id="course_project-send-request-task-disciplines-block" class="form-group">
                                        <label for="course_project-send-request-task-group">{{ translations.labels.disciplines }}</label>
                                        <br>
                                        <i v-for="discipline in currProject.disciplines">
                                            {{ discipline.name + ',' }}
                                            <br>
                                        </i>
                                    </div>
                                    <div id="course_project-send-request-task-description-block" class="form-group">
                                        <label for="course_project-send-request-task-group">{{ translations.labels.description }}</label>
                                        <br>
                                        <i>{{ currProject.description }}</i>
                                    </div>
                                    <div id="course_project-send-request-task-technologies-block" class="form-group">
                                        <label for="course_project-send-request-task-group">{{ translations.labels.technologies }}</label>
                                        <br>
                                        <i>{{ currProject.technologies }}</i>
                                    </div>
                                    <div id="course_project-send-request-task-group-block" class="form-group">
                                        <label for="course_project-send-request-task-group">{{ translations.labels.group }}</label>
                                        <br>
                                        <i>{{ currProject.group }}</i>
                                    </div>
                                    <div id="course_project-send-request-task-message-block" class="form-group">
                                        <label for="course_project-send-request-task-message">{{ translations.labels.message }}</label>
                                        <textarea id="course_project-send-request-task-message" class="form-control" v-model="currProject.message" :value="currProject.message" @keypress="clearErrorMessages('course_project-send-request', 'message')"></textarea>
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
                    <div id="course_project-resend-request-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" @click="clearRequestInputs">&times;</button>
                                    <h4 class="modal-title">{{ translations.labels.request }}</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="course_project-resend-request-task-title-block" class="form-group">
                                        <label for="course_project-resend-request-task-title">{{ translations.labels.topic }}</label>
                                        <br>
                                        <i>{{ currProject.title }}</i>
                                    </div>
                                    <div id="course_project-resend-request-task-professor-block" class="form-group">
                                        <label for="course_project-resend-request-task-group">{{ translations.labels.professor }}</label>
                                        <br>
                                        <i>{{ currProject.professor }}</i>
                                    </div>
                                    <div id="course_project-resend-request-task-disciplines-block" class="form-group">
                                        <label for="course_project-send-request-task-group">{{ translations.labels.disciplines }}</label>
                                        <br>
                                        <i v-for="discipline in currProject.disciplines">
                                            {{ discipline.name + ',' }}
                                            <br>
                                        </i>
                                    </div>
                                    <div id="course_project-resend-request-task-description-block" class="form-group">
                                        <label for="course_project-resend-request-task-group">{{ translations.labels.description }}</label>
                                        <br>
                                        <i>{{ currProject.description }}</i>
                                    </div>
                                    <div id="course_project-resend-request-task-technologies-block" class="form-group">
                                        <label for="course_project-resend-request-task-group">{{ translations.labels.technologies }}</label>
                                        <br>
                                        <i>{{ currProject.technologies }}</i>
                                    </div>
                                    <div id="course_project-resend-request-task-group-block" class="form-group">
                                        <label for="course_project-resend-request-task-group">{{ translations.labels.group }}</label>
                                        <br>
                                        <i>{{ currProject.group }}</i>
                                    </div>
                                    <div id="course_project-resend-request-task-message-block" class="form-group">
                                        <label for="course_project-resend-request-task-message">{{ translations.labels.message }}</label>
                                        <textarea id="course_project-resend-request-task-message" class="form-control" v-model="currProject.message" :value="currProject.message" @keypress="clearErrorMessages('course_project-resend-request', 'message')"></textarea>
                                        <span id="course_project-resend-request-message-help-block" class="help-block" v-if="errors.hasOwnProperty('message')">
                                            <strong>{{ errors.message[0] }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" @click="resendRequest">{{ translations.buttons.apply }}</button>
                                    <button id="close-resend-request-modal" type="button" class="btn btn-default" data-dismiss="modal" @click="clearRequestInputs">{{ translations.buttons.cancel }}</button>
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


export default {

    created() {
        this.getTranslations();
    },
    beforeMount() {
        this.getGroupList();
    },
    mounted() {
        // console.log('course_projects list mounted.');
        // var self = this;
        // setTimeout(function() {
        //     self.getFilteredData();
        // }, 450);
    },
    methods: {
        getTranslations() {
            var self = this;
            $.ajax({
                url: '/translation/student/course_projects/list',
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
                url: '/course_projects/student/list',
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
                self.student_id = response.student_id;
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
        opencourse_project(course_project) {
            return '/course_projects/' + course_project.id;
        },
        showJobs(course_project) {
            return '/course_projects/jobs/' + course_project.id;
        },
        getGroupList() {
            var self = this;
            $.ajax({
                url: '/diplomas/student/groups',
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
        clearRequestInputs() {
            var self = this;
            $.each(this.errors, function(index, value) {
                self.clearErrorMessages('course_project-send-request', index);
            });
            this.currProject = {};
        },
        sendRequest() {
            var self = this;
            $.ajax({
                url: '/course_projects/requests/' + self.student_id,
                type: 'POST',
                dataType: 'json',
                data: self.currProject
            })
            .done(function(response) {
                console.log("success");
                self.course_projects[
                    self.course_projects.map(function(course_project) {
                        return course_project.id;
                    }).indexOf(self.currProject.id)
                ].status = response.courseProjectStatus.toString();
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
        resendRequest() {
            var self = this;
            $.ajax({
                url: '/course_projects/requests/' + self.student_id,
                type: 'PATCH',
                dataType: 'json',
                data: self.currProject
            })
            .done(function(response) {
                console.log("success");
                self.course_projects[
                    self.course_projects.map(function(course_project) {
                        return course_project.id;
                    }).indexOf(self.currProject.id)
                ].status = response.courseProjectStatus.toString();
                self.clearRequestInputs();
                $('#close-resend-request-modal').click();
            })
            .fail(function(response) {
                console.log("error");
                self.errors = response.responseJSON;
                if (self.errors.hasOwnProperty('message')) {
                    $('#task-message-block').addClass('has-error');
                }
            });
        },
        openRequestModal(course_project) {
            var self = this;
            this.currProject = {
                id: course_project.id,
                title: course_project.title,
                technologies: course_project.technologies ? course_project.technologies : '-',
                professor: course_project.professor,
                group: self.currGroup.name,
                description: course_project.description,
                message: '',
                index: self.course_projects.indexOf(course_project),
                disciplines: course_project.disciplines,
            };
        },
        clearErrorMessages(fieldName) {
            console.log('clearing..');
            var self = this;
            if (self.errors.hasOwnProperty(fieldName)) {
                $('#' + modalName + '-task-' + fieldName + '-block').removeClass('has-error');
                delete self.errors[fieldName];
            }
        },
        course_projectStatus(course_project) {
            var self = this;
            var statusWord = '';
            switch (course_project.status) {
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
        deleteRequest(course_project) {
            var self = this;
            swal({
                title: self.translations.labels.delete_request + ' ' + course_project.title + '?',
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
                    url: '/course_projects/' + course_project.id + '/requests' ,
                    type: 'DELETE',
                    dataType: 'json'
                })
                .done(function(response) {
                    console.log("success");
                    course_project.status = null;
                })
                .fail(function(response) {
                    console.log("error");
                    console.log("response");
                });

            });
        },
    },
    data() {
        return {
            course_projects: [],
            translations: [],
            groups: [],
            errors: [],
            currGroup: {
                id: '',
                name: ''
            },
            currProject:{},
            data_ready: false,
        }
    },
    watch: {
        groups: function() {
            this.getFilteredData();
        }
    },

}
</script>
