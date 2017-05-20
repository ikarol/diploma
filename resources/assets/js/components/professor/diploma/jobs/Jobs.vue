<template>
    <div v-if="data_ready">
        <div class="table-responsive">
            <table v-if="jobs.length" class="table table-bordered">
                <thead>
                    <tr>
                        <th>{{ translations.labels.description }}</th>
                        <th>{{ translations.labels.created_at }}</th>
                        <th>{{ translations.labels.deadline }}</th>
                        <th>{{ translations.labels.actions }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="job in jobs" :key="job.id">
                        <td>{{ job.description }}</td>
                        <td>{{ job.created_at }}</td>
                        <td>{{ job.deadline }}</td>
                        <td>
                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#update-job-modal" @click="openUpdateModal(job)">{{ translations.buttons.edit }}</button>
                            <button class="btn btn-sm btn-danger" @click="deleteWithConfirm(job)">{{ translations.buttons.delete }}</button>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <div id="update-job-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" @click="clearUpdateJobInputs">&times;</button>
                                    <h4 class="modal-title">{{ translations.labels.update_job }}</h4>
                                </div>
                                <div class="modal-body">
                                    <div id="update-job-description-block" class="form-group">
                                        <label for="job-description">{{ translations.labels.description }}</label>
                                        <textarea id="description" class="form-control" v-model="curJob.description" :value="curJob.description" @keypress="clearErrorMessages('update-job', 'description')"></textarea>
                                        <span id="description-help-block" class="help-block" v-if="errors.hasOwnProperty('description')">
                                            <strong>{{ errors.description[0] }}</strong>
                                        </span>
                                    </div>
                                    <div id="update-job-deadline-block" class="form-group">
                                        <label for="job-deadline">{{ translations.labels.deadline }}</label>
                                        <br>
                                        <input type="date" :min="curJob.created_at" name="job-deadline" class="form-control" v-model="curJob.deadline" :value="curJob.deadline" @change="clearErrorMessages('update-job', 'deadline')">
                                        <span id="deadline-help-block" class="help-block" v-if="errors.hasOwnProperty('deadline')">
                                            <strong>{{ errors.deadline[0] }}</strong>
                                        </span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" @click="updateJob">{{ translations.buttons.update }}</button>
                                    <button id="close-update-job-modal" type="button" class="btn btn-default" data-dismiss="modal" @click="clearUpdateJobInputs">{{ translations.buttons.cancel }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </tfoot>
            </table>
            <div v-else class="form-group">
                <p>{{ translations.labels.no_jobs }}</p>
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-primary" data-toggle="modal" data-target="#new-job-modal">{{ translations.buttons.new_job }}</button>
            <div id="new-job-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" @click="clearNewJobInputs">&times;</button>
                            <h4 class="modal-title">{{ translations.buttons.new_job }}</h4>
                        </div>
                        <div class="modal-body">
                            <div id="new-job-description-block" class="form-group">
                                <label for="job-description">{{ translations.labels.description }}</label>
                                <textarea id="description" class="form-control" v-model="newJob.description" :value="newJob.description" @keypress="clearErrorMessages('new-job', 'description')"></textarea>
                                <span id="description-help-block" class="help-block" v-if="errors.hasOwnProperty('description')">
                                    <strong>{{ errors.description[0] }}</strong>
                                </span>
                            </div>
                            <div id="new-job-deadline-block" class="form-group">
                                <label for="job-deadline">{{ translations.labels.deadline }}</label>
                                <br>
                                <input type="date" :min="dateNow" name="job-deadline" class="form-control" v-model="newJob.deadline" :value="newJob.deadline" @change="clearErrorMessages('new-job', 'deadline')">
                                <span id="deadline-help-block" class="help-block" v-if="errors.hasOwnProperty('deadline')">
                                    <strong>{{ errors.deadline[0] }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" @click="publishJob">{{ translations.buttons.publish }}</button>
                            <button id="close-new-job-modal" type="button" class="btn btn-default" data-dismiss="modal" @click="clearNewJobInputs">{{ translations.buttons.cancel }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['diploma'],
    created() {
        this.getTranslations();
        this.diplomaId = this.diploma;
        var date = new Date();
        date.setHours(0, 0, 0, 0);
        this.dateNow = date.toISOString().substring(0, 10);
    },
    mounted() {
        this.getJobs();
    },
    methods: {
        getTranslations() {
            var self = this;
            $.ajax({
                url: '/translation/professor/diplomas/jobs',
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
        getJobs() {
            var self = this;
            $.ajax({
                url: '/diplomas/jobs/' + self.diplomaId,
                type: 'GET',
                dataType: 'json'
            })
            .done(function(response) {
                console.log("success");
                console.log(response);
                self.jobs = response.jobs;

            })
            .fail(function(response) {
                console.log("error");
            });

        },
        publishJob() {
            var self = this;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/diplomas/jobs/' + self.diplomaId,
                type: 'POST',
                dataType: 'json',
                data: self.newJob
            })
            .done(function(response) {
                console.log("success");
                console.log(response);
                self.jobs.push(response.job);
                self.clearNewJobInputs();
                $('#close-new-job-modal').click();
            })
            .fail(function(response) {
                console.log("error");
                console.log(response);
                self.errors = response.responseJSON;
                if (self.errors.hasOwnProperty('description')) {
                    $('#new-job-description-block').addClass('has-error');
                }
                if (self.errors.hasOwnProperty('deadline')) {
                    $('#new-job-deadline-block').addClass('has-error');
                }
            });

        },
        clearNewJobInputs() {
            var self = this;
            $.each(this.errors, function(index, value) {
                self.clearErrorMessages('new-job', index);
            });
            this.newJob = {
                description: '',
                deadline: '',
            };
        },
        clearErrorMessages(modalName, fieldName) {
            console.log('clearing..');
            var self = this;
            if (self.errors.hasOwnProperty(fieldName)) {
                console.log('#' + modalName + '-' + fieldName + '-block');
                $('#' + modalName + '-' + fieldName + '-block').removeClass('has-error');
                delete self.errors[fieldName];
            }
        },
        deleteWithConfirm(job) {
            var self = this;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            swal({
                title: self.translations.buttons.delete + ' ?',
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
                    url: '/diplomas/jobs/' + job.id,
                    type: 'DELETE',
                    dataType: 'json'
                })
                .done(function(response) {
                    console.log("success");
                    self.jobs.splice(self.jobs.indexOf(job), 1);
                })
                .fail(function(response) {
                    console.log("error");
                    console.log(response);
                });

            });
        },
        clearUpdateJobInputs() {
            var self = this;
            $.each(this.errors, function(index, value) {
                self.clearErrorMessages('update-job', index);
            });
            this.curJob = {
                description: '',
                deadline: '',
            };
        },
        updateJob() {
            var self = this;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/diplomas/jobs/' + self.curJob.id,
                type: 'PATCH',
                dataType: 'json',
                data: self.curJob
            })
            .done(function(response) {
                console.log("success");
                console.log(response);
                self.jobs[
                    self.jobs.map(function(job) {
                        return job.id;
                    }).indexOf(self.curJob.id)
                ] = response.updatedJob;
                self.clearUpdateJobInputs();
                $('#close-update-job-modal').click();
            })
            .fail(function(response) {
                console.log("error");
                console.log(response);
                self.errors = response.responseJSON;
                if (self.errors.hasOwnProperty('description')) {
                    $('#update-job-description-block').addClass('has-error');
                }
                if (self.errors.hasOwnProperty('deadline')) {
                    $('#update-job-deadline-block').addClass('has-error');
                }
            });

        },
        openUpdateModal(job) {
            this.curJob = {
                id: job.id,
                description: job.description,
                deadline: job.deadline.replace( /(\d{2}).(\d{2}).(\d{4})/, "$3-$2-$1"),
                created_at: job.created_at.replace( /(\d{2}).(\d{2}).(\d{4})/, "$3-$2-$1"),
            };
        }
    },
    data: function() {
        return {
            jobs: [],
            diplomaId: '',
            translations: {},
            data_ready: false,
            errors: [],
            newJob: {
                description: '',
                deadline: '',
            },
            dateNow: '',
            curJob: {

            }
        }
    },
    watch: {
        translations: function() {
            this.data_ready = true;
        }
    }
}
</script>
