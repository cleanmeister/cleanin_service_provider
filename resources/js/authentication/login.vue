<template>
  <div class="login-box" style="margin-left: auto; margin-right: auto;">
    <div class="login-logo" style="margin-bottom: 0;">
      <a v-bind:href="base_url"><span style="font-weight: 1000;">Clean</span> Meister</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-header bg-dark">
        <h4 style="margin: 0;"><p class="login-box-msg" style="padding: 0;">Sign in to start your session</p></h4>
        
      </div>
      <div class="card-body login-card-body">

        <form @submit="Login" method="post" novalidate="true">
          <input type="hidden" name="_token" :value="csrf">
          <div class="input-group">
            <input type="text" class="form-control" v-bind:class="errors.username == '' ? '' : 'is-invalid'" placeholder="Username" v-model="username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <p class="err-loc text-danger"><strong>{{ errors.username }}</strong></p>

          <div class="input-group">
            <input type="password" class="form-control" v-bind:class="errors.password == '' ? '' : 'is-invalid'" placeholder="Password" v-model="password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <p class="err-loc text-danger"><strong>{{ errors.password }}</strong></p>

          <div class="row" style="margin: 0; margin-top: 10px;">
            <div class="col-lg-8" style="padding: 0;">
              <div class="icheck-amethyst">
                  <input type="checkbox" id="remember" v-model="remember"/>
                  <label for="remember">Remember Me</label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-lg-4" style="padding: 0; margin-bottom: 10px;">
              <button type="submit" class="btn btn-sm bg-dark btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <div class="row" style="margin: 0;">
          <div class="col-lg-6 text-left" style="padding: 0;">
            <strong><a v-bind:href="base_url + '/password/reset'">Forgot Password?</a></strong>
          </div>
          <div class="col-lg-6 text-right" style="padding: 0;">
            <strong><a v-bind:href="base_url + '/register'">Register an Account</a></strong>
          </div>
        </div>
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>

</template>
  <!-- <div class="container">
    <div class="row justify-content-center">
      <div class="card shadow-sm">
          <div class="card-header bg-dark text-center"><h4>Login</h4></div>
          <div class="card-body" style="padding-top: 5px; padding-bottom: 5px;">
              <form>
                <input type="hidden" name="_token" value="">
                  <div class="form-group">
                      <label for="username">
                          Username
                      </label>
                      <div class="input-group mb-3">
                          <div class="input-group-prepend"><span class="input-group-text"><i class="fa fa-user"></i></span></div>
                          <input id="username" type="username" class="form-control form-control-sm" name="username" value="" required autocomplete="off" autofocus>
                      </div>
                      <span class="invalid-feedback" role="">
                          <strong>Login Error Here</strong>
                      </span>
                  </div>

                  <div class="form-group">
                      <label for="password" class="">Password</label>
                      <input style="" id="password" type="password" class="form-control form-control-sm" name="password" required autocomplete="off">
                      <span class="invalid-feedback" role="alert">
                          <strong>Password Error Here</strong>
                      </span>
                  </div>

          <div class="col-xs-4">
                          <button type="submit" class="btn btn-primary btn-sm btn-block btn-flat">
                              Sign In
                          </button>
                        </div>
                  <div class="form-group" style="margin: 0;">

                          <a class="btn btn-link" href="">Forgot Your Password?</a>
                  </div>
              </form>
          </div>
          <div class="card-footer bg-dark"></div>
      </div>
    </div>

  </div>-->
<script>
//require('icheck-1.x/icheck.min.js');

export default {
    props: ['base_url'],
    data(){
        return{
          csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          errors: {
            username: '',
            password: '',
          },
          username: '',
          password: '',
          remember: false,
        }
    },
    methods:{
      init(){
        /*$('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
        $('input').on('ifChecked', function (event){
            $(this).closest("input").attr('checked', true);
        });
        $('input').on('ifUnchecked', function (event) {
            $(this).closest("input").attr('checked', false);
        });*/
      },
      Login: function(e){
        e.preventDefault();
        this.errors.username = '';
        this.errors.password = '';
        this.remember = $("#remember").is(':checked');
        var ret = true;
        if(!this.username){
          this.errors.username = "Please enter your Username!";
          ret = false;
        }
        if(!this.password){
          this.errors.password = "Please enter your Password!";
          ret = false;
        }
        //return ret;
        if(ret == true){
          axios.post(this.base_url + '/vuelogin', {
            _token: this.csrf,
            username: this.username,
            password: this.password,
            remember: $("#remember").is(":checked")
          })
          .then(data => {
              toast.fire({
                position: 'middle',
                icon: "success",
                title: 'Success!',
                html: "Please wait while we redirect you.",
                timer: 0,
              });
            this.password = "";
            window.location = this.base_url + "/login";
          })
          .catch(error => {
              this.errors.password = error.response.data.message;
          });

        }
      },
    },
    created(){
    },
    mounted(){
      this.init();
    },
}
</script>

<style>
.icheck,
.icheck label{
  cursor: pointer;
}

.vertical-center {
  margin: 0;
  position: absolute;
  top: 50%;
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
}

.login-logo a:link,
.login-logo a:visited,
.login-logo a:hover,
.login-logo a:active{
  color: #495057;
}
a:link,
a:visited,
a:hover,
a:active {
  text-decoration: none;
}
.err-loc{
  text-align: center;
  margin-bottom: 0;
  min-height: 23px;
}

</style>