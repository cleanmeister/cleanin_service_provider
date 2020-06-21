
<template>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                            <div class="col-auto">
                                <h2 style="margin: 0;"><label style="margin: 0;">Manage Accounts</label></h2>
                            </div>
                    </div>
                    <div class="card-body">
                        <button class="btn btn-sm btn-primary" type="button" @click="add_admin()">
                            <i class="fa fa-plus"></i> Add Account
                        </button>
                        <div class="col-md-4 float-right">
                            <div class="input-group">
                                <input type="text" placeholder="Search..." class="form-control form-control-sm" @keyup="get_users()" v-model="word">
                              <div class="input-group-append">
                                    <button class="btn btn-sm btn-outline-secondary" @click="get_users()" type="button"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </div>
                        <div class="table-responsive-sm">
                        <br>
                            <table class="table table-sm table-hover table-striped">
                                <thead>
                                    <tr class="row text-center">
                                        <th class="col-md-3">Name</th>
                                        <th class="col-md-3 d-none d-md-block">Role</th>
                                        <th class="col-md-3 d-none d-md-block">Status</th>
                                        <th class="col-md-3 d-none d-md-block">Action</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr class="row text-center" v-for="(u, index) in users" :key="index">
                                        <td class="col-md-3"><strong>{{ u.fullname }}</strong></td>
                                        <td class="col-md-3">
                                            <span v-if="u.role_id == 1">Admin</span>
                                            <span v-if="u.role_id == 2">Service Provider</span>
                                            <span v-if="u.role_id == 3">Cleaner</span>
                                            <span v-if="u.role_id == 4">Client</span>
                                        </td>
                                        <td class="col-md-3">

                                            <span class="badge badge-lg badge-success" v-if="u.is_active == 1">Activated</span>
                                            <span class="badge badge-lg badge-danger" v-else="u.is_active == 0">Deactivated</span>
                                        </td>
                                        <td class="col-md-3" v-show="u.role_id == 1 || u.role_id == 2">
                                            <div class="btn-group" style="width: 75%;">
                                                <button class="btn btn-sm btn-danger" v-if="u.is_active == 1" @click="deactivate(u.id)">Deactivate</button>
                                                <button class="btn btn-sm btn-success" v-else="u.is_active == 0" @click="activate(u.id)">Activate</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="adminModal" tabindex="-1" role="dialog" aria-labelledby="adminModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="adminModalLabel">
                            <span>New Admin</span>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode == false ? store_admin() : update_admin()">
                        <div class="modal-body">
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label for="" class="form-label text-capitalize">
                                        username
                                    </label>
                                </div>
                                <div class="col-9">
                                    <input type="text" class="form-control form-control-sm" v-model="form.username">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label for="" class="form-label text-capitalize">
                                        password
                                    </label>
                                </div>
                                <div class="col-9">
                                    <input type="password" class="form-control form-control-sm" v-model="form.password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label for="" class="form-label text-capitalize">
                                        email
                                    </label>
                                </div>
                                <div class="col-9">
                                    <input type="email" class="form-control form-control-sm" v-model="form.email">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label for="" class="form-label text-capitalize">
                                        firstname
                                    </label>
                                </div>
                                <div class="col-9">
                                    <input type="text" class="form-control form-control-sm" v-model="form.firstname">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label for="" class="form-label text-capitalize">
                                        middlename
                                    </label>
                                </div>
                                <div class="col-9">
                                    <input type="text" class="form-control form-control-sm" v-model="form.middlename">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label for="" class="form-label text-capitalize">
                                        lastname
                                    </label>
                                </div>
                                <div class="col-9">
                                    <input type="text" class="form-control form-control-sm" v-model="form.lastname">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label for="" class="form-label text-capitalize">
                                        date of birth
                                    </label>
                                </div>
                                <div class="col-9">
                                    <input type="date" class="form-control form-control-sm"
                                        v-model="form.date_of_birth">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label for="" class="form-label text-capitalize">
                                        address
                                    </label>
                                </div>
                                <div class="col-9">
                                    <input type="text" class="form-control form-control-sm" v-model="form.address">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-3 text-right">
                                    <label for="" class="form-label text-capitalize">
                                        mobile number
                                    </label>
                                </div>
                                <div class="col-9">
                                    <input type="text" class="form-control form-control-sm"
                                        v-model="form.mobile_number">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <deactivate-confirmation-modal></deactivate-confirmation-modal>
    </div>

</template>

<script>
    import DeactivateConfirmationModal from '../modal/deactivate_confirmation_modal.vue';
    export default {
        props: ['base_url'],
        components:{
            DeactivateConfirmationModal
        },
        data() {
            return {
                editmode: false,
                users: [],
                word: '',
                form: {
                    id: '',
                    username: '',
                    password: null,
                    email: '',
                    firstname: '',
                    lastname: '',
                    middlename: '',
                    date_of_birth: '',
                    address: '',
                },
                msg: ''
            }
        },
        methods: {
            get_users() {
                axios.get(this.base_url + '/user?word=' + this.word).then(({
                    data
                }) => {
                    console.log(data);
                    this.users = data;
                }).catch(() => {
                });
            },
            activate(id) {
                    axios.put(this.base_url + '/activate/' + id).then(({
                        data
                    }) => {
                        this.get_users();
                        toast.fire({
                            type: 'success',
                            title: 'Activate Successfuly'
                        });
                    }).catch(() => {
                    });
            },
            deactivate: function(id) {
               
                axios.put(this.base_url + '/deactivate/' + id).then(response => {
                    this.get_users();
                    toast.fire({
                        position: 'middle',
                        icon: 'success',
                        title: 'Deactivate Successful!'
                    });
                }).catch(error => {
                    toast.fire({
                        position: 'middle',
                        icon: 'warning',
                        title: error.response.data.title,
                        html: error.response.data.message
                    });
                });
            
            },
            add_admin() {
                this.editmode = false;
                $('#adminModal').modal('show');
            },
            store_admin() {
                axios.post(this.base_url + '/admin', {
                    username: this.form.username,
                    password: this.form.password,
                    email: this.form.email,
                    firstname: this.form.firstname,
                    lastname: this.form.lastname,
                    middlename: this.form.middlename,
                    date_of_birth: this.form.date_of_birth,
                    address: this.form.address,
                    mobile_number: this.form.mobile_number,
                }).then(({
                    data
                }) => {
                    this.get_users();
                    $('#adminModal').modal('hide');
                    toast.fire({
                        type: 'success',
                        title: 'Created Successfuly'
                    });
                }).catch(() => {
                });
            }
        },
        created() {
            this.get_users();
        }
    }
</script>
