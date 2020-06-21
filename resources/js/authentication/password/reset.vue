<template>


<div class="login-box" style="margin-left: auto; margin-right: auto;">
    <div class="login-logo" style="margin-bottom: 0;">
        <a v-Bind:href="base_url"><span style="font-weight: 1000;">Clean</span> Meister</a>
    </div>

    <div class="card">
        <div class="card-header bg-dark">
            <h4 style="margin: 0;"><p class="login-box-msg" style="padding: 0;">Change Password</p></h4>
        </div>
        <div class="card-body login-card-body">

            <form @submit="resetPassword">
                <input type="hidden" name="reset_token" :value="token">
                <label>E-mail</label>
                <div class="input-group">
                    <input id="email" type="text" class="form-control form-control-sm" :class="errors.email == '' ? '' : 'is-invalid'" v-model="email" placeholder="user@example.com" autocomplete="off" autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <p class="err-loc text-danger"><strong>{{ errors.email}}</strong></p>
                <label>New Password</label>
                <div class="input-group">
                    <input id="NPass" type="password" class="form-control form-control-sm" :class="errors.NPass == '' ? '' : 'is-invalid'" v-model="NPass" placeholder="New Password" autocomplete="off" autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <p class="err-loc text-danger"><strong>{{ errors.NPass}}</strong></p>
                <label>Confirm Password</label>
                <div class="input-group">
                    <input id="CPas" type="password" class="form-control form-control-sm" :class="errors.CPass == '' ? '' : 'is-invalid'" v-model="CPass" placeholder="Confirm Password" autocomplete="off" autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <p class="err-loc text-danger"><strong>{{ errors.CPass }}</strong></p>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-flat">
                        Change password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</template>

<script>
    export default{
    	props: ['emailadd', 'token', 'base_url'],
        data(){
            return {
                errors:{
                    email: '',
                    NPass: '',
                    CPass: ''
                },
                success:{
                    email: '',
                    NPass: '',
                    CPass: ''
                },
                email: '',
                NPass: '',
                CPass: ''

            }
        },
        methods: {
            resetPassword: function(e){
                e.preventDefault();
                this.errors.email = '';
                this.success.email = '';

                var ret = true;
                if(!this.validEmail(this.email)){
                    this.errors.email = "Please enter a valid email address.";
                    ret = false;
                }
                if(!this.email){
                    this.errors.email = "Please enter your email address.";
                    ret = false;
                }
                if(this.NPass.length < 8){
                    this.errors.NPass = "Your password must be atleast 8 characters long.";
                    ret = false;
                }
                if(!this.NPass){
                    this.errors.NPass = "Please enter your new password.";
                    ret = false;
                }
                if(this.CPass != this.NPass){
                    this.errors.CPass = "Password do not match.";
                    ret = false;
                }
                if(!this.CPass){
                    this.errors.CPass = "Please repeat your new password.";
                    ret = false;
                }

                if(ret){
                    axios.post(this.base_url + '/api/password/reset', {
                        token: this.token,
                        email: this.email, 
                        password: this.NPass,
                        password_confirmation: this.CPass
                    }).then(res => {
                        this.success.email = res.data.message;
                    }).catch(error => {
                        this.errors.email = error.response.data.message;
                    });
                }
            },
            validEmail: function (email) {
              var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
              return re.test(email);
            }
        },
        created(){

        },
        mounted(){
        	//console.log(this.emailadd)
        	this.email = this.emailadd;
        }
    }
</script>

<style>
	
</style>