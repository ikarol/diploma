<template>
    <div v-if="data_ready">
        <table v-if="groups.length" class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ translations.labels.group }}</th>
                    <th>{{ translations.labels.actions }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="group in groups" :key="group.id">
                    <td>{{ group.name }}</td>
                    <td>
                        <button class="btn btn-warning" data-toggle="modal" data-target="#update-group-modal" @click="openEditModal(group)">{{ translations.buttons.edit }}</button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <div id="update-group-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" @click="clearUpdateGroupInputs">&times;</button>
                                <h4 class="modal-title">{{ translations.labels.update_group }}</h4>
                            </div>
                            <div class="modal-body">
                                <div id="update-group-name-block" class="form-group">
                                    <label for="group-title">{{ translations.labels.group }}</label>
                                    <input id="group-title" type="text" class="form-control" v-model="curGroup.name" :value="curGroup.name" @keypress="clearErrorMessages('update-group', 'name')">
                                    <span id="group-help-block" class="help-block" v-if="errors.hasOwnProperty('name')">
                                        <strong>{{ errors.name[0] }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" @click="updateGroup">{{ translations.buttons.update }}</button>
                                <button id="close-update-group-modal" type="button" class="btn btn-default" data-dismiss="modal" @click="clearUpdateGroupInputs">{{ translations.buttons.cancel }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </tfoot>
        </table>
        <div v-else>
            {{ translations.labels.no_groups }}
        </div>
        <div class="form-group">
            <button class="btn btn-primary" data-toggle="modal" data-target="#new-group-modal">{{ translations.labels.new_group }}</button>
            <div id="new-group-modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" @click="clearCreateGroupInputs">&times;</button>
                            <h4 class="modal-title">{{ translations.labels.new_group }}</h4>
                        </div>
                        <div class="modal-body">
                            <div id="new-group-name-block" class="form-group">
                                <label for="group-title">{{ translations.labels.group }}</label>
                                <input id="group-title" type="text" class="form-control" v-model="newGroup.name" :value="newGroup.name" @keypress="clearErrorMessages('new-group', 'name')">
                                <span id="group-help-block" class="help-block" v-if="errors.hasOwnProperty('name')">
                                    <strong>{{ errors.name[0] }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" @click="createGroup">{{ translations.buttons.create_group }}</button>
                            <button id="close-new-group-modal" type="button" class="btn btn-default" data-dismiss="modal" @click="clearCreateGroupInputs">{{ translations.buttons.cancel }}</button>
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
                url: '/translation/admin/groups/',
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
        getGroups() {
            var self = this;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/groups/',
                type: 'GET',
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
        clearErrorMessages(modalName, fieldName) {
            console.log('clearing..');
            console.log(this.errors);
            var self = this;
            if (self.errors.hasOwnProperty(fieldName)) {
                $('#' + modalName + '-' + fieldName + '-block').removeClass('has-error');
                delete self.errors[fieldName];
            }
        },
        clearCreateGroupInputs() {
            var self = this;
            $.each(this.errors, function(index, value) {
                self.clearErrorMessages('new-group', index);
            });
            this.newGroup = {};
        },
        clearUpdateGroupInputs() {
            var self = this;
            $.each(this.errors, function(index, value) {
                self.clearErrorMessages('update-group', index);
            });
            this.curGroup = {};
        },
        openEditModal(group) {
            this.curGroup = {
                id: group.id,
                name: group.name,
            }
        },
        createGroup() {
            var self = this;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/groups',
                type: 'POST',
                dataType: 'json',
                data: self.newGroup
            })
            .done(function(response) {
                console.log("success");
                console.log(response);
                self.groups.push(response);
                self.clearCreateGroupInputs();
                $('#close-new-group-modal').click();
            })
            .fail(function(response) {
                console.log("error");
                console.log(response);
                self.errors = response.responseJSON;
                if (self.errors.hasOwnProperty('name')) {
                    $('#new-group-name-block').addClass('has-error');
                }
            });

        },
        updateGroup() {
            var self = this;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '/admin/groups/' + self.curGroup.id,
                type: 'PATCH',
                dataType: 'json',
                data: self.curGroup
            })
            .done(function(response) {
                console.log("group updated");
                console.log(response);
                self.groups[
                    self.groups.map(function(group) {
                        return group.id;
                    }).indexOf(self.curGroup.id)
                ] = response;
                self.clearUpdateGroupInputs();
                $('#close-update-group-modal').click();
            })
            .fail(function(response) {
                console.log("error");
                console.log(response);
                self.errors = response.responseJSON;
                if (self.errors.hasOwnProperty('name')) {
                    $('#update-group-name-block').addClass('has-error');
                }
            });
        }

    },
    watch: {
        translations: function() {
            this.getGroups();
        }
    },
    data: function() {
        return {
            groups: [],
            curGroup: {},
            newGroup: {
                name: '',
            },
            data_ready: false,
            translations: [],
            errors: [],
        }
    }
}
</script>
