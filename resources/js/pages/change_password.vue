<template>
    <div class="container mt-2">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-auto">
                                <h2 style="margin: 0;"><label style="margin: 0;">Change Password</label></h2>
                            </div>
                        </div>
                    </div>
                    <form @submit.prevent="update_password()">
                        <div class="card-body">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="firstname pull-left">Old Password</label>
                                    <input type="password" class="form-control form-control-sm" v-model="old_password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="firstname pull-left">New Password</label>
                                    <input type="password" class="form-control form-control-sm" v-model="new_password">
                                    <span v-show="error" class="text-danger"><strong>Passwords do not match.</strong></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="firstname pull-left">Confirm Password</label>
                                    <input type="password" class="form-control form-control-sm" v-model="confirm_password">
                                </div>
                            </div>

                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-s btn-warning" type="reset"><i class="fas fa-ban"></i> Cancel</button>
                            <button class="btn btn-s btn-success" type="submit"><i class="fas fa-save"></i> Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: ['base_url'],
        data() {
            return {
                new_password: null,
                confirm_password: null,
                old_password: null,
                error: false,
            }
        },
        methods: {
            update_password() {
                if (this.new_password == this.confirm_password) {
                    axios.post(this.base_url + '/change_password', {
                        new_password: this.new_password,
                        confirm_password: this.confirm_password,
                        old_password: this.old_password,
                    }).then(res => {
                        if(res.data.code == 200){
                          toast.fire({
                            position: 'middle',
                            icon: "success",
                            title: res.data.msg,
                          });
                        }else{
                          toast.fire({
                            position: 'middle',
                            icon: "warning",
                            title: res.data.msg,
                          });
                        }
                        this.new_password = '';
                        this.confirm_password = '';
                        this.old_password = '';
                        this.error = false;
                    }).catch(error => {

                        if (error.response.status != 200){
                          var ErrorUI = '<div><h2><b>' + error.response.data.message + '</b></h2>';
                          $.each(error.response.data.errors, function (key, value){
                            $.each(value, function (a, b){
                                ErrorUI += '<h5>' + b.replace(key.replace('_', ' '), '<b class="text text-danger">'+$.camelCase("-" + key).replace('_', ' ')+'</b>') + '</h5>';

                            });
                          });
                          ErrorUI += '</div>';
                          toast.fire({
                            position: 'middle',
                            icon: "warning",
                            title: ErrorUI,
                          });
                        }
                    });
                } else {
                    this.error = true;
                }


            },
        },
        created() {

        }
    }

</script>

<style>

</style>
