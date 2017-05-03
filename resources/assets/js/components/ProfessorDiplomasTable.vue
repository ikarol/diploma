<template>
    <div class="table-responsive">
        <span id="button-edit" hidden="true"><slot name="button-edit"></slot></span>
        <span id="button-delete" hidden="true"><slot name="button-delete"></slot></span>
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
                    <template slot="col-topic"><a :href="openDiploma(diploma)">{{ diploma.title }}</a></template>
                    <template slot="col-requests">{{ diploma.requests }}</template>
                    <template slot="col-cr_at">{{ diploma.created_at }}</template>
                    <template slot="col-actions">
                        <button class="btn btn-primary btn-sm">{{ button_names.edit }}</button>
                        <button class="btn btn-danger btn-sm">{{ button_names.delete }}</button>
                    </template>
                </professor-diplomas-row>
            </tbody>
        </table>
        <div v-else class="col-md-4">
            <p><slot name="no-diplomas"></slot></p>
        </div>
    </div>
</template>

<script>

import ProfessorDiplomasRow from './ProfessorDiplomasRow.vue';

    export default {
        components: {
            ProfessorDiplomasRow
        },
        mounted() {
            console.log('Diplomas list mounted.');
            this.getFilteredData();
            this.assignButtonNames();
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
                    self.diplomas = response;
                })
                .fail(function(response) {
                    console.log('fail');
                    console.log(response);
                });
            },
            assignButtonNames() {
                this.button_names.edit = $('#button-edit').text();
                this.button_names.delete = $('#button-delete').text();
            },
            openDiploma(diploma) {
                return '/diplomas/' + diploma.id;
            }
        },
        data() {
            return {
                diplomas: [],
                button_names: {
                    edit: '',
                    delete: '',
                    cancel: '',
                    publish: ''
                }
            }
        }
    }
</script>
