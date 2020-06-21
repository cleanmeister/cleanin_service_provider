<template>

<div class="login-box" style="margin-left: auto; margin-right: auto;">
    <div class="login-logo" style="margin-bottom: 0;">
        <a v-bind:href="this.base_url"><span style="font-weight: 1000;">Clean</span> Meister</a>
    </div>

    <div class="card">
        <div class="card-header bg-dark">
            <h4 style="margin: 0;"><p class="login-box-msg" style="padding: 0;">Reset Password</p></h4>
        </div>
        <div class="card-body login-card-body">

            <div v-show="success.email != ''" class="alert alert-success" role="alert">
                {{ success.email }}
            </div>
            <label v-show="disable" class="text-center text-success" style="width: 100%;">Please wait while we process your request</label>
            
            <form @submit="resetPassword">
                <input type="hidden" name="_token" :value="csrf">
                <label>E-mail </label>
                <div class="input-group">
                    <input id="email" type="text" class="form-control form-control-sm" :disabled="disable" :class="errors.email == '' ? '' : 'is-invalid'" v-model="email" placeholder="user@example.com" autocomplete="off" autofocus>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <p class="err-loc text-danger"><strong>{{ errors.email}}</strong></p>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-flat" :disabled="disable">
                        Send password reset link <i class="fa fa-spinner fa-pulse" v-show="disable"></i>
                    </button>
                </div>
            </form>

            <div class="row" style="margin: 0; margin-top: 10px;">
              <div class="col-lg-6 text-left" style="padding: 0;">
                <strong><a v-bind:href="this.base_url + '/login'">Login</a></strong>
              </div>
              <div class="col-lg-6 text-right" style="padding: 0;">
                <strong><a v-bind:href="this.base_url + '/register'">Register an Account</a></strong>
              </div>
            </div>
        </div>
    </div>

</div>


</template>
    <!-- <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
<script>
    export default{
        props: ['base_url'],
        data(){
            return {
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                errors:{
                    email: '',
                },
                success:{
                    email: '',
                },
                email: '',
                disable: false,
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

                if(ret){
                    this.disable = true;
                    axios.post(this.base_url + '/api/password/create', {
                        _token: this.csrf,
                        email: this.email, 
                    }).then(res => {
                        this.success.email = res.data.message;
                        this.disable = false;
                    }).catch(error => {
                        this.errors.email = error.response.data.message;
                        this.disable = false;
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

        }
    }
</script>

<style>
    
</style>