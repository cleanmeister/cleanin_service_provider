<template>
  <div class="container mt-2">
      <div class="row">
          <div class="col-sm-12">
              <div class="card">
                  <div class="card-header">
                      <div class="row">
                          <div class="col-auto">
                              <h4>Booking Transaction</h4>
                          </div>
                      </div>
                  </div>
                  <div class="card-body">
                      <div class="table-responsive-sm" style="overflow: auto;">
                        <table class="table table-sm table-condensed table-hover" style=" min-width: 1010px !important;">
                          <thead>
                            <tr>
                              <th>Client</th>
                              <th>Service</th>
                              <th>Date & Time</th>
                              <th>Landmarks</th>
                              <th>Status</th>
                              <th>Rating</th>
                              <th>Feedback</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-if="cleaner_schedules.length <= 0">
                              <td colspan="8" class="text-center">
                                <span><i>No Data</i></span>
                              </td>
                            </tr>
                            <tr class="text-capitalize" v-for="(cs, index) in cleaner_schedules" :key="index">
                              <td>{{ cs.client_firstname }} {{ cs.client_middlename }} {{ cs.client_lastname }}</td>
                              <td>{{ cs.service_name }}</td>
                              <td>{{ getDate(cs.formated_start_time) }}: {{ cs.day_desc }}<br/>{{ getTime(cs.formated_start_time) }} - {{ cs.formated_end_time }} </td>
                              <td>{{ cs.landmark }}</td>
                              <td>
                                <span v-show="cs.status == 1">Pending</span>
                                <span v-show="cs.status == 2">Approved</span>
                                <span v-show="cs.status == 3">Completed</span>
                                <span v-show="cs.status == 4">Rejected</span>
                                <span v-show="cs.status == 5">Cancelled</span>
                              </td>
                              <td>{{ cs.rating }} <span class="fa fa-star" v-show="cs.rating != null" style="color: gold;"></span></td>
                              <td>{{ cs.feedback }}</td>
                              <td>
                                <div class="btn-group" style="width: 100%; min-width: 75px;">
                                  <button v-show="cs.status == 1" type="button" class="btn btn-sm btn-success" @click="accept_cleaner_schedules(cs.id)">Accept</button>
                                  <button v-show="cs.status == 1" type="button" class="btn btn-sm btn-warning" @click="decline_cleaner_schedules(cs.id)">Decline</button>
                                  <button v-show="cs.status == 4 && removeTheseElements != true" type="button" class="btn btn-sm btn-danger" @click="remove_cleaner_schedules(cs.id)">Remove</button>
                                  <button v-show="cs.status == 3 && cs.ratingtoclient == null && cs.Reported == false" type="button" class="btn btn-sm btn-success" @click="rate_client(cs)">Rate Client</button>
                                  <button v-show="cs.status == 3 && cs.ratingtoclient == null && cs.Reported == false" type="button" class="btn btn-sm btn-dark" @click="report_client(cs)">Report Client</button>
                                
                                  <a class="btn btn-sm btn-primary" target="_blank" v-bind:href="base_url + '/messaging?email=' + cs.client_email">
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
      <center>
        <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">
                  <span class="text-capitalize">Report {{ block_name }}</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="store_report()">
              <div class="modal-body">
                <div class="form-group row">
                  <div class="col-3 text-right">
                    Reason
                  </div>
                  <div class="col">
                    <textarea rows="4" class="form-control" v-model="reason"></textarea>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">close</button>
                <button type="submit" class="btn btn-primary btn-sm">submit</button>
              </div>
              </form>
            </div>
          </div>
        </div>
        <div class="modal fade" id="rateModal" tabindex="-1" role="dialog" aria-labelledby="rateModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title" id="rateModalLabel">
                  <strong><span class="text-capitalize">Rate {{ cs.client_firstname }} {{ cs.client_middlename }} {{ cs.client_lastname }}</span></strong>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="store_rate()">
              <div class="modal-body">
                <h4><label style="margin: 0px;">Rating</label></h4><br/>
                <div class="rating">
                  <label>
                    <input type="radio" name="stars" value="1" v-model="rating"/>
                    <span class="icon">★</span>
                  </label>
                  <label>
                    <input type="radio" name="stars" value="2" v-model="rating"/>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                  </label>
                  <label>
                    <input type="radio" name="stars" value="3" v-model="rating"/>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>   
                  </label>
                  <label>
                    <input type="radio" name="stars" value="4" v-model="rating"/>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                  </label>
                  <label>
                    <input type="radio" name="stars" value="5" v-model="rating"/>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                    <span class="icon">★</span>
                  </label>
                </div>
                <!--<div class="form-group row">
                  <div class="col-3 text-right">
                    Feedback
                  </div>
                  <div class="col">
                    <textarea rows="4" class="form-control" v-model="feedback"></textarea>
                  </div>
                </div>-->
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">close</button>
                <button type="submit" class="btn btn-primary btn-sm">submit</button>
              </div>
              </form>
            </div>
          </div>
        </div>
      </center>
  </div>
</template>

<script>
  $(':radio').change(function() {
    console.log('New star rating: ' + this.value);
  });
import moment from 'moment';
export default {
    props: ['base_url'],
    data(){
        return{
            removeTheseElements: true,
            cleaner_schedules: [],
            block_id: '',
            CSID: '',
            block_name: '',
            reason: '',
            cs: '',
            feedback: '',
            rating: '',
        }
    },
    methods:{
      get_cleaner_schedules(){
        axios.get(this.base_url + '/get_cleaner_schedules').then(({data}) => {
          this.cleaner_schedules = data;
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
      accept_cleaner_schedules(id){
        axios.get(this.base_url + '/approve_schedule/'+id).then(res => {
            if(res.data.code == 200){
              toast.fire({
                position: 'middle',
                icon: "success",
                title: res.data.msg,
              });
            }else{
              toast.fire({
                position: 'middle',
                icon: "danger",
                title: res.data.msg,
              });
            }
          this.get_cleaner_schedules();

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
      decline_cleaner_schedules(id){
        axios.get(this.base_url + '/reject_schedule/'+id).then(() => {
          this.get_cleaner_schedules();
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
      remove_cleaner_schedules(id){
        axios.get(this.base_url + '/remove_schedule/'+id).then(() => {
          this.get_cleaner_schedules();
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
      report_client(cs){
        this.block_id = cs.client_id;
        this.CSID = cs.CSID;
        this.block_name = cs.client_firstname+ ' '+ cs.client_middlename + ' ' +cs.client_lastname;
        $('#reportModal').modal('show');
      },
      store_report(){
        axios.post(this.base_url + '/complain',{
          CSid: this.CSID,
          CleanID: this.block_id,
          reason: this.reason,
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
          this.block_id = '',
          this.block_name= '',
          $('#reportModal').modal('hide');
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
        this.get_cleaner_schedules();
      },
      rate_client(cs){
        this.cs = cs;
        $('#rateModal').modal('show');
      },
      store_rate(){
        axios.put(this.base_url + '/rate_client/'+this.cs.id, {
          
          feedback: this.feedback,
          rating: parseInt(this.rating) ,
        }).then(res => {
          if(res.data.code == 200){
            toast.fire({
              position: 'middle',
              icon: "success",
              title: res.data.msg,
            });
          }
          this.get_cleaner_schedules();
          $('#rateModal').modal('hide');
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
      getDate: function(date){
        return moment(date).format('MMMM D, yyyy');
      },
      getTime: function(date){
        return moment(date).format('h:mm A');
      },
    },
    created(){
      this.get_cleaner_schedules();
    }
}
</script>

<style>
.rating {
  display: inline-block;
  position: relative;
  height: 30px;
  line-height: 30px;
  font-size: 30px;
}

.rating label {
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  cursor: pointer;
}

.rating label:last-child {
  position: static;
}

.rating label:nth-child(1) {
  z-index: 5;
}

.rating label:nth-child(2) {
  z-index: 4;
}

.rating label:nth-child(3) {
  z-index: 3;
}

.rating label:nth-child(4) {
  z-index: 2;
}

.rating label:nth-child(5) {
  z-index: 1;
}

.rating label input {
  position: absolute;
  top: 0;
  left: 0;
  opacity: 0;
}

.rating label .icon {
  float: left;
  color: transparent;
}

.rating label:last-child .icon {
  color: #000;
}

.rating:not(:hover) label input:checked ~ .icon,
.rating:hover label:hover input ~ .icon {
  color: #f1c40f;
}

.rating label input:focus:not(:checked) ~ .icon:last-child {
  color: #000;
  text-shadow: 0 0 5px #09f;
}
</style>