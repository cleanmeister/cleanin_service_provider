<template>
  <div class="container mt-2">
      <div class="row">
          <div class="col-md-12">
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
                        <table class="table table-sm table-hover table-striped" style=" min-width: 810px;">
                          <thead>
                              <tr>
                                  <th>Service</th>
                                  <th>Cleaner</th>
                                  <th>Schedule</th>
                                  <th>Status</th>
                                  <th>Time</th>
                                  <th>Rating</th>
                                  <th></th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-if="client_schedules.length == 0">
                                <td colspan="6" class="text-center">No Data</td>
                              </tr>
                              <tr class="text-capitalize" v-for="(cs, index) in client_schedules" :key="index">
                                  <td>{{ cs.service_name }}</td>
                                  <td>{{ cs.cleaner_firstname }} {{ cs.cleaner_middlename }} {{ cs.cleaner_lastname }}</td>
                                  <td>{{ cs.day_desc }}-{{ cs.formated_start_time }} - {{ cs.formated_end_time }}</td>
                                  <td>
                                    <span v-if="cs.status == 1">Pending</span>
                                    <span v-if="cs.status == 2">Approved</span>
                                    <span v-if="cs.status == 3">Completed</span>
                                    <span v-if="cs.status == 4">Rejected</span>
                                    <span v-if="cs.status == 5">Cancelled</span>
                                  </td>
                                  <td>
                                    <div>Time In: {{ cs.time_in }}</div>
                                    <div>Time Out: {{ cs.time_out }}</div>
                                  </td>
                                  <td>
                                    <div>{{ cs.client_rating }} <span class="fa fa-star" v-show="cs.client_rating != null" style="color: gold;"></span></div>
                                  </td>
                                  <td class="text-center">
                                    <div class="btn-group" style="width: 100%;">
                                      <button v-if="cs.status == 1" class="btn btn-sm btn-warning" @click="cancel_client_schedules(cs.id)">cancel</button>
                                      
                                      <button v-if="cs.status == 5 && removeThseseElements != true" class="btn btn-sm btn-danger" @click="remove_client_schedules(cs.id)">remove</button>

                                      <button type="button" v-if="cs.status == 2 && cs.time_in == null" @click="new_time_in(cs.id)" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> Time in</button>
                                      <button type="button" v-if="cs.status == 2 && cs.time_in != null && cs.time_out == null" @click="new_time_out(cs.id)" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> Time out</button>
                                      
                                      <button type="button" v-if="cs.status == 3 && cs.rating == null && cs.blocked != true" class="btn btn-sm btn-success" @click="new_rate(cs.id)">Rate</button>
                                      <button type="button" v-if="cs.status == 3 && cs.rating == null && cs.blocked != true" class="btn btn-sm btn-dark" @click="report_cleaner(cs)">Report Cleaner</button>

                                      <a class="btn btn-sm btn-primary" target="_blank" v-bind:href="base_url + '/messaging?email=' + cs.cleaner_email">
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
      <div class="modal fade" id="timeModal" tabindex="-1" role="dialog" aria-labelledby="timeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="timeModalLabel">
                <span v-show="time == 1">TIME IN</span>
                <span v-show="time == 2">TIME OUT</span>
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form @submit.prevent="time == 1 ? store_time_in() : store_time_out()">
            <div class="modal-body">
              <input type="time" class="form-control form-control-sm" v-model="time_form"required>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">close</button>
              <button type="submit" class="btn btn-primary btn-sm">save</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <div class="modal fade" id="rateModal" tabindex="-1" role="dialog" aria-labelledby="rateModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="rateModalLabel">
                <span v-show="time == 1">Rate</span>
              </h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form @submit.prevent="store_rate()">
            <div class="modal-body">
              <div class="form-group row">
                <div class="col-3 text-right">
                  Rate:
                </div>
                <div class="col">
                  <div class="rating">
                    <label>
                      <input type="radio" name="stars" value="1" v-model="rate"/>
                      <span class="icon">★</span>
                    </label>
                    <label>
                      <input type="radio" name="stars" value="2" v-model="rate"/>
                      <span class="icon">★</span>
                      <span class="icon">★</span>
                    </label>
                    <label>
                      <input type="radio" name="stars" value="3" v-model="rate"/>
                      <span class="icon">★</span>
                      <span class="icon">★</span>
                      <span class="icon">★</span>   
                    </label>
                    <label>
                      <input type="radio" name="stars" value="4" v-model="rate"/>
                      <span class="icon">★</span>
                      <span class="icon">★</span>
                      <span class="icon">★</span>
                      <span class="icon">★</span>
                    </label>
                    <label>
                      <input type="radio" name="stars" value="5" v-model="rate"/>
                      <span class="icon">★</span>
                      <span class="icon">★</span>
                      <span class="icon">★</span>
                      <span class="icon">★</span>
                      <span class="icon">★</span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-3 text-right">
                  Feedback
                </div>
                <div class="col">
                  <textarea rows="4" class="form-control" v-model="feedback"></textarea>
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
                <div class="row">
                  <div class="col-md-2"></div>
                  <div class="col-md-8">
                    <label>Reason</label>
                    <textarea class="form-control" v-model="reason"></textarea>
                  </div>
                  <div class="col-md-2"></div>
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
  </div>
</template>

<script>
  
  $(':radio').change(function() {
    console.log('New star rating: ' + this.value);
  });
export default {
    props: ['base_url'],
    data(){
        return{
            removeThseseElements: true,
            time: 1,
            client_schedules: [],
            time_form: '',
            cs_id: '',
            cleaner_id: '',
            rate: 1,
            feedback: '',
            CSid: '',
            block_name: '',
            reason: '',
        }
    },
    methods:{
      get_client_schedules(){
        axios.get(this.base_url + '/get_client_schedules')
        .then(({data}) => {
          this.client_schedules = data;
        }).catch(() => {

        });
      },
      cancel_client_schedules(id){
        axios.get(this.base_url + '/cancel_schedule/'+id)
        .then(() => {
          this.get_client_schedules();
        }).catch(() => {

        });
      },
      remove_client_schedules(id){
        axios.get(this.base_url + '/remove_schedule/'+id)
        .then(() => {
          this.get_client_schedules();
        }).catch(() => {

        });
      },
      new_time_in(id){
        this.time = 1;
        this.cs_id = id;
        $('#timeModal').modal('show');
      },  
      new_time_out(id){
        this.time = 2;
        this.cs_id = id;
        $('#timeModal').modal('show');
      },
      store_time_in(){
        axios.put(this.base_url + '/time_in/'+this.cs_id,{
          time_in: this.time_form,
        }).then(() => {
          this.get_client_schedules();
          this.time_form = '';
          $('#timeModal').modal('hide');
        }).catch(() => {

        });
      },
      store_time_out(){
        axios.put(this.base_url + '/time_out/'+this.cs_id,{
          time_out: this.time_form,
        }).then(() => {
          this.get_client_schedules();
          this.time_form = '';
          this.cs_id = '';
          $('#timeModal').modal('hide');
        }).catch(() => {

        });
      },
      new_rate(id){
        this.cs_id = id;
        this.rate = 0;
        $('#rateModal').modal('show');
      },
      store_rate(){
        axios.put(this.base_url + '/rate/'+this.cs_id, {
          rate: this.rate,
          feedback: this.feedback,
        }).then(() => {
          this.get_client_schedules();
          $('#rateModal').modal('hide');
          this.rate = '';
          this.feedback = '';
          this.cs_id = '';
        });
      },
      report_cleaner(cs){
        this.CSid = cs.id;
        this.cleaner_id = cs.cleaner_id;
        this.block_name = cs.cleaner_firstname+ ' '+ cs.cleaner_middlename + ' ' +cs.cleaner_lastname;
        $('#reportModal').modal('show');
      },
      store_report(){
        axios.post(this.base_url + '/complain',{
          CleanID: this.cleaner_id, 
          CSid: this.CSid,
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
          this.CSid = '';
          this.reason = '';
          this.block_name= '';
          this.get_client_schedules();
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
      }
    },
    created(){
      this.get_client_schedules();
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