<template>
  <div class="container mt-2">
      <div class="row">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-header">
                    <h2 style="margin: 0;"><label style="margin: 0;">Cleaners</label></h2>
                  </div>
                  <div class="card-body">
                      <div class="table-responsive-sm">

                        <button class="btn btn-sm btn-primary" @click="new_cleaner()">
                            <span>
                                <i class="fa fa-plus"></i> Add Cleaners
                            </span>
                        </button>
                        <div class="input-group col-md-4 float-right" style="padding: 0; margin-bottom: 5px;">
                          <input type="text" class="form-control form-control-sm" v-model="word" @keyup="get_cleaners()" placeholder="Search">
                          <div class="input-group-append">
                            <button class="btn btn-sm btn-outline-secondary" @click="get_cleaners()" type="button"><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                        <div class="" style="overflow-x:auto; width: 100%;">
                          
                          <table class="table table-sm table-hover table-condensed table-striped" style=" min-width: 614px;">
                            <thead class="text-center">
                                <tr>
                                  <th>ID</th>
                                  <th>Name</th>
                                  <th>Age</th>
                                  <th>Address</th>
                                  <th>Mobile Number</th>
                                  <th></th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <tr v-if="cleaners.length <= 0" class="text-center">
                                    <td colspan="6"><span><i>No Data</i></span></td>
                                </tr>
                                <tr v-for="(c, index) in cleaners" :key="index">
                                    <td>{{ c.id }}</td>
                                    <td>{{ c.fullname }}</td>
                                    <td>{{ c.age }}</td>
                                    <td>{{ c.address }}</td>
                                    <td>{{ c.mobile_number }}</td>
                                    <td>
                                      <div class="btn-group" style="width: 100%; min-width: 210px;">
                                        <button class="btn btn-sm btn-success" @click="edit_cleaner(c.id)">
                                            <i class="fa fa-pen"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-danger" @click="delete_cleaner_confirm(c.id)">
                                            <i class="fas fa-user-slash"></i> Terminate
                                        </button>
                                        <a class="btn btn-sm btn-primary" target="_blank" v-bind:href="base_url + '/messaging?email=' + c.email">
                                            <i class="far fa-comment-dots"></i> Chat
                                        </a>
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
      </div>
      <div class="modal fade" id="cleanerModal" tabindex="-1" role="dialog" aria-labelledby="cleanerModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="cleanerModalLabel">
                      <span v-show="!editmode">New Cleaner</span>
                      <span v-show="editmode">Update Cleaner</span>
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <form @submit.prevent="editmode == false ? store_cleaner() : update_cleaner()">
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
                              <!-- <input type="date" class="form-control form-control-sm" v-model="form.date_of_birth"> -->

                              <div class="input-group">
                                <datepicker :disabled-dates="DatepickerSettings.disabledDates"
                                  v-model="form.date_of_birth" typeable  :bootstrap-styling="true"
                                  input-class="form-control form-control-sm"
                                  placeholder="" format="D, MMM d, yyyy">

                                  <div slot="afterDateInput" id="datepickericon" class="input-group-append">
                                    <div class="input-group-text">
                                      <i class="fas fa-calendar"></i>
                                    </div>
                                  </div>
                                </datepicker>
                              </div>
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
                              <input type="text" class="form-control form-control-sm" v-model="form.mobile_number">
                          </div>
                      </div>
                      <div class="form-group row">
                          <div class="col-3 text-right">
                              <label for="" class="form-label text-capitalize">
                                  Notes/Restrictions
                              </label>
                          </div>
                          <div class="col-9">
                              <textarea class="form-control form-control-sm" v-model="form.restrictions">
                                
                              </textarea>
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

      <div class="modal fade" id="TerminateCleanerModal" tabindex="-1" role="dialog" aria-labelledby="TerminateCleanerModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="TerminateCleanerModalLabel">
                      <span>Terminate Cleaner</span>
                  </h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  </button>
              </div>
                <div class="modal-body text-center">
                    Ar you sure you want to terminate this cleaner?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-primary" @click="delete_cleaner()">Yes</button>
                </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
  import datepicker from 'vuejs-datepicker';
  Vue.use(datepicker);

export default {
    props: ['base_url'],
    data(){
        return{
            editmode: false,
            cleaners: [],
            cleaner:{},
            word: '',
            delID: 0,
            form: {
                id: '',
                username: '',
                password: '',
                email: '',
                firstname: '',
                lastname: '',
                middlename: '',
                date_of_birth: '',
                address: '',
                mobile_number: '',
                restrictions: '',
            },
            DatepickerSettings: {
              disabledDates:{
                from: new Date(),
                to: new Date(1900, 12)
              }
            },
        }
    },
    components:{ datepicker },
    methods:{
        get_cleaners(){
            axios.get(this.base_url + '/cleaner?word='+this.word).then(({data}) => {
                this.cleaners = data;
            }).catch(() => {

            });
        },
        new_cleaner(){
            this.editmode = false;
            this.form.id = '';
            this.form.username = '';
            this.form.password = '';
            this.form.email = '';
            this.form.firstname = '';
            this.form.lastname = '';
            this.form.middlename = '';
            this.form.date_of_birth = '';
            this.form.address = '';
            this.mobile_number = '';
            this.form.restrictions = '';
            $('#cleanerModal').modal('show');
        },
        store_cleaner(){
            axios.post(this.base_url + '/cleaner',{
                username: this.form.username,
                password: this.form.password,
                email: this.form.email,
                firstname: this.form.firstname,
                lastname: this.form.lastname,
                middlename: this.form.middlename,
                date_of_birth: this.form.date_of_birth,
                address: this.form.address,
                mobile_number: this.form.mobile_number,
                restrictions: this.form.restrictions,
            }).then(res => {
                if(res.data.code == 200){
                  toast.fire({
                    position: 'middle',
                    icon: "success",
                    title: res.data.msg,
                  });
                }
                this.get_cleaners();
                $('#cleanerModal').modal('hide');
            }).catch(error => {

              if (error.response.status != 200){
                var ErrorUI = '<div><h2><b>' + error.response.data.message + '</b></h2>';
                $.each(error.response.data.errors, function (key, value){
                  ErrorUI += '<h5>' + value[0].replace(key.replace('_', ' '), '<b class="text text-danger">'+$.camelCase("-" + key).replace('_', ' ')+'</b>') + '</h5>';
                });
                ErrorUI += '</div>';
                toast.fire({
                  position: 'middle',
                  icon: "warning",
                  title: ErrorUI,
                });
              }
            });
        },
        edit_cleaner(c){
            this.editmode = true;
            var username = '';
            var email = '';
            var firstname = '';
            var lastname = '';
            var middlename = '';
            var date_of_birth = '';
            var address = '';
            var mobile_number = '';
            var restrictions = '';
            $.each(this.cleaners, function(key, value){
              if(value.id == c)
              {
                username = value.username;
                email = value.email;
                firstname = value.firstname;
                lastname = value.lastname;
                middlename = value.middlename;
                date_of_birth = value.date_of_birth;
                address = value.address;
                mobile_number = value.mobile_number;
                restrictions = value.restrictions;
                return false;
              }
            });
            this.form.id = c;
            this.form.restrictions = restrictions;
            this.form.username = username;
            this.form.email = email;
            this.form.firstname = firstname;
            this.form.lastname = lastname;
            this.form.middlename = middlename;
            this.form.date_of_birth = date_of_birth;
            this.form.address = address;
            this.form.mobile_number = mobile_number;
            $('#cleanerModal').modal('show');
        },
        update_cleaner(){
            axios.put(this.base_url + '/cleaner/'+this.form.id,{
                username: this.form.username,
                password: this.form.password,
                email: this.form.email,
                firstname: this.form.firstname,
                lastname: this.form.lastname,
                middlename: this.form.middlename,
                date_of_birth: this.form.date_of_birth,
                address: this.form.address,
                mobile_number: this.form.mobile_number,
                restrictions: this.form.restrictions,
            }).then(res => {
                this.get_cleaners();
                $('#cleanerModal').modal('hide');

                if(res.data.code == 200){
                  toast.fire({
                    position: 'middle',
                    icon: "success",
                    title: res.data.msg,
                  });
                }
            }).catch(error => {

              if (error.response.status != 200){
                var ErrorUI = '<div><h2><b>' + error.response.data.message + '</b></h2>';
                $.each(error.response.data.errors, function (key, value){
                  ErrorUI += '<h5>' + value[0].replace(key.replace('_', ' '), '<b class="text text-danger">'+$.camelCase("-" + key).replace('_', ' ')+'</b>') + '</h5>';
                });
                ErrorUI += '</div>';
                toast.fire({
                  position: 'middle',
                  icon: "warning",
                  title: ErrorUI,
                });
              }
            });
        },
        delete_cleaner(){
          axios.delete(this.base_url + '/cleaner/'+this.delID)
          .then(() => {
              this.get_cleaners();
              if(res.data.code == 200){
                toast.fire({
                  position: 'middle',
                  icon: "success",
                  title: res.data.msg,
                });
                $('#TerminateCleanerModal').modal('hide');
              }
        	}).catch(error => {

              if (error.response.status != 200){
                var ErrorUI = '<div><h2><b>' + error.response.data.message + '</b></h2>';
                $.each(error.response.data.errors, function (key, value){
                  ErrorUI += '<h5>' + value[0].replace(key.replace('_', ' '), '<b class="text text-danger">'+$.camelCase("-" + key).replace('_', ' ')+'</b>') + '</h5>';
                });
                ErrorUI += '</div>';
                toast.fire({
                  position: 'middle',
                  icon: "warning",
                  title: ErrorUI,
                });
              }
            });
        },
        delete_cleaner_confirm(id){
          this.delID = id;
          $('#TerminateCleanerModal').modal('show');
        }

    },
    created(){
        this.get_cleaners();
    },
    mounted(){[

    ]}
}
</script>
<style>

</style>