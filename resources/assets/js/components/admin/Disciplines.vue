<template>
    <div v-if="data_ready">
        <table v-if="disciplines.length" class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ translations.labels.discipline }}</th>
                    <th>{{ translations.labels.actions }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="discipline in disciplines" :key="discipline.id">
                    <td>{{ discipline.name }}</td>
                    <td>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#update-discipline-modal" @click="openEditModal(discipline)">{{ translations.buttons.edit }}</button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <div id="update-discipline-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" @click="clearUpdatedisciplineInputs">&times;</button>
                                <h4 class="modal-title">{{ translations.labels.update_discipline }}</h4>
                            </div>
                            <div class="modal-body">
                                <div id="update-discipline-name-block" class="form-discipline">
                                    <label for="discipline-title">{{ translations.labels.discipline }}</label>
                                    <input id="discipline-title" type="text" class="form-control" v-model="curdiscipline.name" :value="curdiscipline.name" @keypress="clearErrorMessages('update-discipline', 'name')">
                                    <span id="discipline-help-block" class="help-block" v-if="errors.hasOwnProperty('name')">
                                        <strong>{{ errors.name[0] }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" @click="updatediscipline">{{ translations.buttons.update }}</button>
                                <button id="close-update-discipline-modal" type="button" class="btn btn-default" data-dismiss="modal" @click="clearUpdatedisciplineInputs">{{ translations.buttons.cancel }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </tfoot>
        </table>
        <div v-else>
            {{ translations.labels.no_disciplines }}
        </div>
        <div class="form-discipline">
            <button class="btn btn-primary" data-toggle="modal" data-target="#new-discipline-modal">{{ translations.labels.new_discipline }}</button>
            <div id="new-discipline-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" @click="clearCreatedisciplineInputs">&times;</button>
                            <h4 class="modal-title">{{ translations.labels.new_discipline }}</h4>
                        </div>
                        <div class="modal-body">
                            <div id="new-discipline-name-block" class="form-discipline">
                                <label for="discipline-title">{{ translations.labels.discipline }}</label>
                                <input id="discipline-title" type="text" class="form-control" v-model="newdiscipline.name" :value="newdiscipline.name" @keypress="clearErrorMessages('new-discipline', 'name')">
                                <span id="discipline-help-block" class="help-block" v-if="errors.hasOwnProperty('name')">
                                    <strong>{{ errors.name[0] }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" @click="creatediscipline">{{ translations.buttons.create_discipline }}</button>
                            <button id="close-new-discipline-modal" type="button" class="btn btn-default" data-dismiss="modal" @click="clearCreatedisciplineInputs">{{ translations.buttons.cancel }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        this.getTranslations();
    },
    methods: {
        getTranslations() {
            var self = this;
            $.ajax({
                url: '/translation/admin/disciplines/',
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
        getdisciplines() {
            var self = this;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/disciplines/',
                type: 'GET',
                dataType: 'json'
            })
            .done(function(response) {
                console.log('disciplines list recieved');
                console.log(response);
                self.disciplines = response;
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
        clearErrorMessages(modalName, fieldName) {
            console.log('clearing..');
            var self = this;
            if (self.errors.hasOwnProperty(fieldName)) {
                $('#' + modalName + '-' + fieldName + '-block').removeClass('has-error');
                delete self.errors[fieldName];
            }
        },
        clearCreatedisciplineInputs() {
            var self = this;
            $.each(this.errors, function(index, value) {
                self.clearErrorMessages('new-discipline', index);
            });
            this.newdiscipline = {};
        },
        clearUpdatedisciplineInputs() {
            var self = this;
            $.each(this.errors, function(index, value) {
                self.clearErrorMessages('update-discipline', index);
            });
            this.curdiscipline = {};
        },
        openEditModal(discipline) {
            this.curdiscipline = {
                id: discipline.id,
                name: discipline.name,
            }
        },
        creatediscipline() {
            var self = this;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/disciplines',
                type: 'POST',
                dataType: 'json',
                data: self.newdiscipline
            })
            .done(function(response) {
                console.log("success");
                console.log(response);
                self.disciplines.push(response);
                self.clearCreatedisciplineInputs();
                $('#close-new-discipline-modal').click();
            })
            .fail(function(response) {
                console.log("error");
                console.log(response);
                self.errors = response.responseJSON;
                if (self.errors.hasOwnProperty('name')) {
                    $('#new-discipline-name-block').addClass('has-error');
                }
            });

        },
        updatediscipline() {
            var self = this;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/disciplines/' + self.curdiscipline.id,
                type: 'PATCH',
                dataType: 'json',
                data: self.curdiscipline
            })
            .done(function(response) {
                console.log("discipline updated");
                console.log(response);
                self.disciplines[
                    self.disciplines.map(function(discipline) {
                        return discipline.id;
                    }).indexOf(self.curdiscipline.id)
                ] = response;
                self.clearUpdatedisciplineInputs();
                $('#close-update-discipline-modal').click();
            })
            .fail(function(response) {
                console.log("error");
                console.log(response);
                self.errors = response.responseJSON;
                if (self.errors.hasOwnProperty('name')) {
                    $('#update-discipline-name-block').addClass('has-error');
                }
            });
        }

    },
    watch: {
        translations: function() {
            this.getdisciplines();
        }
    },
    data: function() {
        return {
            disciplines: [],
            curdiscipline: {},
            newdiscipline: {
                name: '',
            },
            data_ready: false,
            translations: [],
            errors: [],
        }
    }
}
</script>
