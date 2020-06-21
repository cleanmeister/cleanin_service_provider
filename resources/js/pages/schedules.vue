<template>
  <div>
      <div class="row">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-header">
                      <div class="row">
                          <div class="col-auto">
                              <h4>Schedules</h4>
                          </div>
                          <div class="col text-right">
                              <button class="btn btn-sm btn-primary" @click="new_schedule()">
                                  <i class="fa fa-plus"></i>
                            </button> 
                          </div>
 
                      </div>
                  </div>
                  <div class="card-body">
                      <div class="table-responsive-sm" style="overflow-x: auto;">
                        <table class="table table-sm table-bordered table-dark" style="">
                          <thead class="text-center">
                            <tr>
                              <th width="14%">Sunday</th>
                              <th width="14%">Monday</th>
                              <th width="14%">Tuesday</th>
                              <th width="14%">Wednesday</th>
                              <th width="14%">Thursday</th>
                              <th width="14%">Friday</th>
                              <th width="14%">Saturday</th>
                            </tr>
                          </thead>
                          <tbody v-if="sundays.length <= 0 && mondays.length <= 0 && tuesdays.length <= 0 && wednesdays.length <= 0 && thursdays.length <= 0 && fridays.length <= 0 && saturdays.length <= 0">
                            <tr>
                              <td colspan="7" class="text-center">
                                <i>No Data</i>
                              </td>
                            </tr>
                          </tbody>
                          <tbody  v-else="sundays.length > 0 && mondays.length > 0 && tuesdays.length > 0 && wednesdays.length > 0 && thursdays.length > 0 && fridays.length > 0 && saturdays.length > 0">
                            <tr class="text-center">
                              <td>
                                <h5 v-for="(s, index) in sundays" :key="index">
                                      
                                    <span class="badge badge-success sched" data-toggle="tooltip" :title="s.service_desc" style="width: 100% !important;" v-bind:class="s.available == true ? 'sched' : ''">
                                      <h4 style="margin: 0 !important;"><b>{{ s.service_name }}</b></h4>
                                      <hr style="height: 2px; margin-top: 2px; margin-bottom: 2px;" />
                                      <div class="w-100"></div>
                                      {{ s.service_desc | truncate(18, '...') }}
                                      <div class="w-100"></div>
                                      {{ s.formated_start_time }}-{{ s.formated_end_time }}
                                      <div class="w-100"></div>

                                      <div class="btn-group" v-if="s.available" role="group" aria-label="Basic example" style="width: 100% !important; margin-top: 5px;">
                                        <button type="button" @click="edit_schedule(s)" class="btn btn-secondary btn-warning schedEditBtn-L">
                                          <i class="fa fa-edit" style="" style="font-size: 0.6rem;"></i>
                                        </button>
                                        <button type="button" @click="delete_schedule(s.id)" class="btn btn-secondary btn-danger schedEditBtn-R">
                                          <i class="fa fa-trash" style="" style="font-size: 0.6rem;"></i>
                                        </button>
                                      </div>
                                      <div class="btn-group" v-else="!s.available" role="group" aria-label="Basic example" style="width: 100% !important; margin-top: 5px;">

                                        <button type="button" disabled class="btn btn-secondary btn-danger schedEditBtn-R schedEditBtn-L">
                                          {{ InUse_txt }}
                                        </button>
                                      </div>

                                    </span>
                                </h5>
                              </td>
                              <td>
                                <h5 v-for="(s, index) in mondays" :key="index">
                                      
                                    <span class="badge badge-success sched" data-toggle="tooltip" :title="s.service_desc" style="width: 100% !important;" v-bind:class="s.available == true ? 'sched' : ''">
                                      <h4 style="margin: 0 !important;"><b>{{ s.service_name }}</b></h4>
                                      <hr style="height: 2px; margin-top: 2px; margin-bottom: 2px;" />
                                      <div class="w-100"></div>
                                      {{ s.service_desc | truncate(18, '...') }}
                                      <div class="w-100"></div>
                                      {{ s.formated_start_time }}-{{ s.formated_end_time }}
                                      <div class="w-100"></div>

                                      <div class="btn-group" v-if="s.available" role="group" aria-label="Basic example" style="width: 100% !important; margin-top: 5px;">
                                        <button type="button" @click="edit_schedule(s)" class="btn btn-secondary btn-warning schedEditBtn-L">
                                          <i class="fa fa-edit" style="" style="font-size: 0.6rem;"></i>
                                        </button>
                                        <button type="button" @click="delete_schedule(s.id)" class="btn btn-secondary btn-danger schedEditBtn-R">
                                          <i class="fa fa-trash" style="" style="font-size: 0.6rem;"></i>
                                        </button>
                                      </div>
                                      <div class="btn-group" v-else="!s.available" role="group" aria-label="Basic example" style="width: 100% !important; margin-top: 5px;">

                                        <button type="button" disabled class="btn btn-secondary btn-danger schedEditBtn-R schedEditBtn-L">
                                          {{ InUse_txt }}
                                        </button>
                                      </div>

                                    </span>
                                </h5>
                              </td>
                              <td>
                                <h5 v-for="(s, index) in tuesdays" :key="index">
                                      
                                    <span class="badge badge-success sched" data-toggle="tooltip" :title="s.service_desc" style="width: 100% !important;" v-bind:class="s.available == true ? 'sched' : ''">
                                      <h4 style="margin: 0 !important;"><b>{{ s.service_name }}</b></h4>
                                      <hr style="height: 2px; margin-top: 2px; margin-bottom: 2px;" />
                                      <div class="w-100"></div>
                                      {{ s.service_desc | truncate(18, '...') }}
                                      <div class="w-100"></div>
                                      {{ s.formated_start_time }}-{{ s.formated_end_time }}
                                      <div class="w-100"></div>

                                      <div class="btn-group" v-if="s.available" role="group" aria-label="Basic example" style="width: 100% !important; margin-top: 5px;">
                                        <button type="button" @click="edit_schedule(s)" class="btn btn-secondary btn-warning schedEditBtn-L">
                                          <i class="fa fa-edit" style="" style="font-size: 0.6rem;"></i>
                                        </button>
                                        <button type="button" @click="delete_schedule(s.id)" class="btn btn-secondary btn-danger schedEditBtn-R">
                                          <i class="fa fa-trash" style="" style="font-size: 0.6rem;"></i>
                                        </button>
                                      </div>
                                      <div class="btn-group" v-else="!s.available" role="group" aria-label="Basic example" style="width: 100% !important; margin-top: 5px;">

                                        <button type="button" disabled class="btn btn-secondary btn-danger schedEditBtn-R schedEditBtn-L">
                                          {{ InUse_txt }}
                                        </button>
                                      </div>

                                    </span>
                                </h5>
                              </td>
                              <td>
                                <h5 v-for="(s, index) in wednesdays" :key="index">
                                      
                                    <span class="badge badge-success sched" data-toggle="tooltip" :title="s.service_desc" style="width: 100% !important;" v-bind:class="s.available == true ? 'sched' : ''">
                                      <h4 style="margin: 0 !important;"><b>{{ s.service_name }}</b></h4>
                                      <hr style="height: 2px; margin-top: 2px; margin-bottom: 2px;" />
                                      <div class="w-100"></div>
                                      {{ s.service_desc | truncate(18, '...') }}
                                      <div class="w-100"></div>
                                      {{ s.formated_start_time }}-{{ s.formated_end_time }}
                                      <div class="w-100"></div>

                                      <div class="btn-group" v-if="s.available" role="group" aria-label="Basic example" style="width: 100% !important; margin-top: 5px;">
                                        <button type="button" @click="edit_schedule(s)" class="btn btn-secondary btn-warning schedEditBtn-L">
                                          <i class="fa fa-edit" style="" style="font-size: 0.6rem;"></i>
                                        </button>
                                        <button type="button" @click="delete_schedule(s.id)" class="btn btn-secondary btn-danger schedEditBtn-R">
                                          <i class="fa fa-trash" style="" style="font-size: 0.6rem;"></i>
                                        </button>
                                      </div>
                                      <div class="btn-group" v-else="!s.available" role="group" aria-label="Basic example" style="width: 100% !important; margin-top: 5px;">

                                        <button type="button" disabled class="btn btn-secondary btn-danger schedEditBtn-R schedEditBtn-L">
                                          {{ InUse_txt }}
                                        </button>
                                      </div>

                                    </span>
                                </h5>
                              </td>
                              <td>
                                <h5 v-for="(s, index) in thursdays" :key="index">
                                      
                                    <span class="badge badge-success sched" data-toggle="tooltip" :title="s.service_desc" style="width: 100% !important;" v-bind:class="s.available == true ? 'sched' : ''">
                                      <h4 style="margin: 0 !important;"><b>{{ s.service_name }}</b></h4>
                                      <hr style="height: 2px; margin-top: 2px; margin-bottom: 2px;" />
                                      <div class="w-100"></div>
                                      {{ s.service_desc | truncate(18, '...') }}
                                      <div class="w-100"></div>
                                      {{ s.formated_start_time }}-{{ s.formated_end_time }}
                                      <div class="w-100"></div>

                                      <div class="btn-group" v-if="s.available" role="group" aria-label="Basic example" style="width: 100% !important; margin-top: 5px;">
                                        <button type="button" @click="edit_schedule(s)" class="btn btn-secondary btn-warning schedEditBtn-L">
                                          <i class="fa fa-edit" style="" style="font-size: 0.6rem;"></i>
                                        </button>
                                        <button type="button" @click="delete_schedule(s.id)" class="btn btn-secondary btn-danger schedEditBtn-R">
                                          <i class="fa fa-trash" style="" style="font-size: 0.6rem;"></i>
                                        </button>
                                      </div>
                                      <div class="btn-group" v-else="!s.available" role="group" aria-label="Basic example" style="width: 100% !important; margin-top: 5px;">

                                        <button type="button" disabled class="btn btn-secondary btn-danger schedEditBtn-R schedEditBtn-L">
                                          {{ InUse_txt }}
                                        </button>
                                      </div>

                                    </span>
                                </h5>
                              </td>
                              <td>
                                <h5 v-for="(s, index) in fridays" :key="index">
                                  
                                    <span class="badge badge-success sched" data-toggle="tooltip" :title="s.service_desc" style="width: 100% !important;" v-bind:class="s.available == true ? 'sched' : ''">
                                      <h4 style="margin: 0 !important;"><b>{{ s.service_name }}</b></h4>
                                      <hr style="height: 2px; margin-top: 2px; margin-bottom: 2px;" />
                                      <div class="w-100"></div>
                                      {{ s.service_desc | truncate(18, '...') }}
                                      <div class="w-100"></div>
                                      {{ s.formated_start_time }}-{{ s.formated_end_time }}
                                      <div class="w-100"></div>

                                      <div class="btn-group" v-if="s.available" role="group" aria-label="Basic example" style="width: 100% !important; margin-top: 5px;">
                                        <button type="button" @click="edit_schedule(s)" class="btn btn-secondary btn-warning schedEditBtn-L">
                                          <i class="fa fa-edit" style="" style="font-size: 0.6rem;"></i>
                                        </button>
                                        <button type="button" @click="delete_schedule(s.id)" class="btn btn-secondary btn-danger schedEditBtn-R">
                                          <i class="fa fa-trash" style="" style="font-size: 0.6rem;"></i>
                                        </button>
                                      </div>
                                      <div class="btn-group" v-else="!s.available" role="group" aria-label="Basic example" style="width: 100% !important; margin-top: 5px;">

                                        <button type="button" disabled class="btn btn-secondary btn-danger schedEditBtn-R schedEditBtn-L">
                                          {{ InUse_txt }}
                                        </button>
                                      </div>

                                    </span>
                                </h5>
                              </td>
                              <td>
                                <h5 v-for="(s, index) in saturdays" :key="index">
                                      
                                    <span class="badge badge-success sched" data-toggle="tooltip" :title="s.service_desc" style="width: 100% !important;" v-bind:class="s.available == true ? 'sched' : ''">
                                      <h4 style="margin: 0 !important;"><b>{{ s.service_name }}</b></h4>
                                      <hr style="height: 2px; margin-top: 2px; margin-bottom: 2px;" />
                                      <div class="w-100"></div>
                                      {{ s.service_desc | truncate(18, '...') }}
                                      <div class="w-100"></div>
                                      {{ s.formated_start_time }}-{{ s.formated_end_time }}
                                      <div class="w-100"></div>

                                      <div class="btn-group" v-if="s.available" role="group" aria-label="Basic example" style="width: 100% !important; margin-top: 5px;">
                                        <button type="button" @click="edit_schedule(s)" class="btn btn-secondary btn-warning schedEditBtn-L">
                                          <i class="fa fa-edit" style="" style="font-size: 0.6rem;"></i>
                                        </button>
                                        <button type="button" @click="delete_schedule(s.id)" class="btn btn-secondary btn-danger schedEditBtn-R">
                                          <i class="fa fa-trash" style="" style="font-size: 0.6rem;"></i>
                                        </button>
                                      </div>
                                      <div class="btn-group" v-else="!s.available" role="group" aria-label="Basic example" style="width: 100% !important; margin-top: 5px;">

                                        <button type="button" disabled class="btn btn-secondary btn-danger schedEditBtn-R schedEditBtn-L">
                                          {{ InUse_txt }}
                                        </button>
                                      </div>

                                    </span>
                                </h5>
                              </td>
                            </tr>
                          </tbody>
                      </table>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="scheduleModalLabel">
                    <strong>
                      <span v-show="!editmode">New Schedule</span>
                      <span v-show="editmode">Update Schedule</span>
                    </strong>
                </h2>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form @submit.prevent="editmode == false ? store_schedule() : update_schedule()">
                <div class="modal-body">
                    <center>
                      <div style="overflow: auto;">
                          <table class="table table-condensed table-sm text-center" style="max-width: 580px;">
                              <tbody>
                                  <tr>
                                    <td>
                                      <table class="table table-condensed table-sm text-center" style="margin: 0;">
                                        <thead>
                                            <tr>
                                                <th><strong for="" class="form-label">Service</strong></th>
                                                <th><strong for="" class="form-label">Start Time:</strong></th>
                                                <th><strong for="" class="form-label">End Time:</strong></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select name="" id="" class="form-control form-control-sm" v-model="form.service_id" required>
                                                        <option v-for="service in services" :key="service.id" :value="service.id">
                                                            {{ service.name }}<span v-if="service.status == 0"> - disabled</span>
                                                        </option>
                                                    </select>
                                                </td>
                                                <td><input type="time" class="form-control form-control-sm" v-model="form.start_time" required></td>
                                                <td><input type="time" class="form-control form-control-sm" v-model="form.end_time" required></td>
                                            </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <table class="table table-condensed table-sm text-center" style="margin: 0;">
                                          <thead>
                                              <tr><th colspan="7"><strong>Day</strong></th></tr>
                                              <tr>
                                                  <th>Sunday</th>
                                                  <th>Monday</th>
                                                  <th>Tuesday</th>
                                                  <th>Wednesday</th>
                                                  <th>Thursday</th>
                                                  <th>Friday</th>
                                                  <th>Saturday</th>
                                              </tr>
                                          </thead>
                                        <tbody>
                                            <tr v-show="!editmode">
                                                <td class="">
                                                  <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input hand" id="switch7" v-model="form.days" value="7">
                                                    <label class="custom-control-label hand" for="switch7"></label>
                                                  </div>
                                                </td>
                                                <td class="">
                                                  <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input hand" id="switch1" v-model="form.days" value="1">
                                                    <label class="custom-control-label hand" for="switch1"></label>
                                                  </div>
                                                </td>
                                                <td class="">
                                                  <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input hand" id="switch2" v-model="form.days" value="2">
                                                    <label class="custom-control-label hand" for="switch2"></label>
                                                  </div>
                                                </td>
                                                <td class="">
                                                  <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input hand" id="switch3" v-model="form.days" value="3">
                                                    <label class="custom-control-label hand" for="switch3"></label>
                                                  </div>
                                                </td>
                                                <td class="">
                                                  <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input hand" id="switch4" v-model="form.days" value="4">
                                                    <label class="custom-control-label hand" for="switch4"></label>
                                                  </div>
                                                </td>
                                                <td class="">
                                                  <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input hand" id="switch5" v-model="form.days" value="5">
                                                    <label class="custom-control-label hand" for="switch5"></label>
                                                  </div>
                                                </td>
                                                <td class="">
                                                  <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input hand" id="switch6" v-model="form.days" value="6">
                                                    <label class="custom-control-label hand" for="switch6"></label>
                                                  </div>
                                                </td>
                                            </tr>
                                            <tr v-show="editmode">
                                                <td class="">
                                                  <div class="custom-control custom-switch">
                                                    <input type="radio" class="custom-control-input hand" id="switch8" v-model="form.dayss" value="7">
                                                    <label class="custom-control-label hand" for="switch8"></label>
                                                  </div>
                                                </td>
                                                <td class="">
                                                  <div class="custom-control custom-switch">
                                                    <input type="radio" class="custom-control-input hand" id="switch9" v-model="form.dayss" value="1">
                                                    <label class="custom-control-label hand" for="switch9"></label>
                                                  </div>
                                                </td>
                                                <td class="">
                                                  <div class="custom-control custom-switch">
                                                    <input type="radio" class="custom-control-input hand" id="switch10" v-model="form.dayss" value="2">
                                                    <label class="custom-control-label hand" for="switch10"></label>
                                                  </div>
                                                </td>
                                                <td class="">
                                                  <div class="custom-control custom-switch">
                                                    <input type="radio" class="custom-control-input hand" id="switch11" v-model="form.dayss" value="3">
                                                    <label class="custom-control-label hand" for="switch11"></label>
                                                  </div>
                                                </td>
                                                <td class="">
                                                  <div class="custom-control custom-switch">
                                                    <input type="radio" class="custom-control-input hand" id="switch12" v-model="form.dayss" value="4">
                                                    <label class="custom-control-label hand" for="switch12"></label>
                                                  </div>
                                                </td>
                                                <td class="">
                                                  <div class="custom-control custom-switch">
                                                    <input type="radio" class="custom-control-input hand" id="switch13" v-model="form.dayss" value="5">
                                                    <label class="custom-control-label hand" for="switch13"></label>
                                                  </div>
                                                </td>
                                                <td class="">
                                                  <div class="custom-control custom-switch">
                                                    <input type="radio" class="custom-control-input hand" id="switch14" v-model="form.dayss" value="6">
                                                    <label class="custom-control-label hand" for="switch14"></label>
                                                  </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                      </table>
                                    </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                      <span v-show="error" class="text-danger">
                          Schedule Overlap
                      </span>
                    </center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
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
    data(){
        return{
            InUse_txt: "Active!",

            sundays: [],
            mondays: [],
            tuesdays: [],
            wednesdays: [],
            thursdays: [],
            fridays: [],
            saturdays: [],
            editmode: false,
            schedules: [],
            schedule:{},
            word: '',
            form:{
                id: '',
                days: [],
                dayss: 0,
                start_time: '',
                end_time: '',
                service_id: '',
            },
            days: [],
            services: [],
            error: false,
        }
    },
    methods:{
        get_sunday(){
          axios.get(this.base_url + '/get_sunday').then(({data}) => {
            this.sundays = data;
          }).catch(() => {

          });
        },
        get_monday(){
          axios.get(this.base_url + '/get_monday').then(({data}) => {
            this.mondays = data;
          }).catch(() => {

          });
        },
        get_tuesday(){
          axios.get(this.base_url + '/get_tuesday').then(({data}) => {
            this.tuesdays = data;
          }).catch(() => {

          });
        },
        get_wednesday(){
          axios.get(this.base_url + '/get_wednesday').then(({data}) => {
            this.wednesdays = data;
          }).catch(() => {

          });
        },
        get_thursday(){
          axios.get(this.base_url + '/get_thursday').then(({data}) => {
            this.thursdays = data;
          }).catch(() => {

          });
        },
        get_friday(){
          axios.get(this.base_url + '/get_friday').then(({data}) => {
            this.fridays = data;
          }).catch(() => {

          });
        },
        get_saturday(){
          axios.get(this.base_url + '/get_saturday').then(({data}) => {
            this.saturdays = data;
          }).catch(() => {

          });
        },
        get_services(){
            axios.get(this.base_url + '/service')
            .then(({data}) => {
                this.services = data;
            }).catch(() => {

            });
        },
        get_days(){
            axios.get(this.base_url + '//day').then(({data}) => {
                this.days = data;
            }).catch(() => {

            });
        },
        get_schedules(){
            axios.get(this.base_url + '/cleaner_service').then(({data}) => {
                this.schedules = data;
            }).catch(() => {

            });
        },
        new_schedule(){
            this.editmode = false;
            this.form.id = '';
            this.form.start_time = '';
            this.form.end_time = '';
            this.form.service_id = '';
            this.form.days = [];
            this.form.dayss = 0;
            $('#scheduleModal').modal('show');

        },
        store_schedule(){
            axios.post(this.base_url + '/schedule', {
                days: this.form.days,
                start_time: this.form.start_time,
                end_time: this.form.end_time,
                service_id: this.form.service_id,
            }).then(res => {
                res.data.forEach((el,index) =>{
                  if(el.code == 200){
                    toast.fire({
                      position: 'middle',
                      icon: "success",
                      title: el.msg,
                    });
                    $('#scheduleModal').modal('hide');
                    console.log(data);
                  }else{
                    var ErrorUI = '';
                    $.each(el.errors, function(key, value){
                      ErrorUI += '<h5><strong>' + value + '</strong></h5>';
                    });
                    toast.fire({
                      position: 'middle',
                      icon: "warning",
                      title: '<div><h3><b>' + el.msg + '</b></h3>',
                      html: ErrorUI,
                      timer: 0,
                    });
                    console.log(data);
                  }
                });

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
            this.get_all();
        },
        edit_schedule(s){
            this.editmode = true;
            this.form.id = s.id;
            this.form.start_time = s.start_time;
            this.form.end_time = s.end_time;
            this.form.service_id = s.service_id;
            this.form.days = [];
            this.form.dayss = s.day_id;
            $('#scheduleModal').modal('show');
        },
        update_schedule(){
            axios.put(this.base_url + '/schedule/'+this.form.id,{
                days: [this.form.dayss],
                start_time: this.form.start_time,
                end_time: this.form.end_time,
                service_id: this.form.service_id,
            }).then(res => {

                res.data.forEach((el,index) =>{
                  if(el.code == 200){
                    toast.fire({
                      position: 'middle',
                      icon: "success",
                      title: el.msg,
                    });
                    $('#scheduleModal').modal('hide');
                    console.log(data);
                  }else{
                    var ErrorUI = '';
                    $.each(el.errors, function(key, value){
                      ErrorUI += '<h5><strong>' + value + '</strong></h5>';
                    });
                    toast.fire({
                      position: 'middle',
                      icon: "warning",
                      title: '<div><h3><b>' + el.msg + '</b></h3>',
                      html: ErrorUI,
                      timer: 0,
                    });
                    console.log(data);
                  }
                });

            }).catch(error => {
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
            });
            this.get_all();
        },
        delete_schedule(id){
            axios.delete(this.base_url + '/schedule/'+id).then(res => {
                res.data.forEach((el,index) =>{
                  if(el.code == 500){
                    toast.fire({
                      position: 'middle',
                      icon: "warning",
                      title: el.msg,
                    });
                  }else if(el.code == 200){
                    toast.fire({
                      position: 'middle',
                      icon: "success",
                      title: el.msg,
                    });
                  }
                });
                this.get_all();
            }).catch(error => {
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
            });
            this.get_all();
        },
        get_all(){
          this.get_sunday();
          this.get_monday();
          this.get_tuesday();
          this.get_wednesday();
          this.get_thursday();
          this.get_friday();
          this.get_saturday()
        }
    },
    mounted(){
        this.get_days();
        // this.get_all();
        this.get_services();
        this.get_all();
    }
}
</script>

<style>
.schedEditBtn-R{
  border-top-right-radius: 0px !important;
  max-height: 23px;
  padding: 0;
}
.schedEditBtn-L{
  border-top-left-radius: 0px !important;
  max-height: 23px;
  padding: 0;
}

.hand {
  cursor: pointer;
}
.sched{
   padding: 0;
}
</style>