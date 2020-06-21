<template>
  <div class="container mt-2">
      <div class="row">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-header">
                    <h2 style="margin: 0;"><label style="margin: 0;">Booking Transactions</label></h2>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive-sm" style="overflow-x:auto; width: 100%;">
                      <table class="table table-sm table-hover table-striped table-condensed" style=" min-width: 810px;">
                        <thead>
                            <tr>
                              <th>Service</th>
                              <th>Cleaner</th>
                              <th>Schedule</th>
                              <th>Client</th>
                              <th>Status</th>
                              <th>Rate</th>
                              <th>Feedback</th>
                              <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="service_provider_bookings.length <= 0" class="text-center">
                              <td colspan="8">
                                  <i>No Data</i>
                              </td>
                            </tr>
                            <tr v-else="service_provider_bookings.length > 0" v-for="(spb, index) in service_provider_bookings" :key="index">
                              <td>{{ spb.service_name }}</td>
                              <td>{{ spb.cleaner_firstname }} {{ spb.cleaner_middlename }} {{ spb.cleaner_lastname }}</td>
                              <td>{{ spb.day_desc }}: {{ spb.formated_start_time }} - {{ spb.formated_end_time }}</td>
                              <td>{{ spb.client_firstname }} {{ spb.client_middlename }} {{ spb.client_lastname }}</td>
                              <td>
                                    <span v-if="spb.status == 1">Pending</span>
                                    <span v-if="spb.status == 2">Approved</span>
                                    <span v-if="spb.status == 3">Completed</span>
                                    <span v-if="spb.status == 4">Rejected</span>
                                    <span v-if="spb.status == 5">Cancelled</span>
                              </td>
                              <td>{{ spb.rating }}</td>
                              <td>{{ spb.feedback }}</td>
                              <td>
                                <div class="btn-group" style="width: 100%; min-width: 75px;">
                                    <button v-if="spb.status == 2 && spb.time_in == null" @click="new_time_in(spb.client_schedule_id)" class="btn btn-sm btn-success form-control form-control-sm ">
                                      <i class="fa fa-clock"></i> Time in
                                    </button>
                                    <button v-if="spb.status == 2 && spb.time_out == null && spb.time_in !== null" @click="new_time_out(spb.client_schedule_id)" class="btn btn-sm btn-success form-control form-control-sm ">
                                      <i class="fa fa-clock"></i> Time out
                                    </button>
                                    <a class="btn btn-sm btn-primary form-control form-control-sm " target="_blank" v-bind:href="base_url + '/messaging?email=' + spb.client_email">
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

  </div>
</template>

<script>
export default {
    props: ['base_url'],
    data(){
        return{
            time: 1,
            time_form: '',
            cs_id: '',
            service_provider_bookings: [],
        }
    },
    methods:{
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

          this.time_form = '';
          $('#timeModal').modal('hide');
          this.get_service_provider_bookings();
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
      store_time_out(){
        axios.put(this.base_url + '/time_out/'+this.cs_id,{
          time_out: this.time_form,
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

          this.time_form = '';
          this.cs_id = '';
          $('#timeModal').modal('hide');
          this.get_service_provider_bookings();
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
      get_service_provider_bookings(){
        axios.get(this.base_url + '/get_service_provider_booking').then(({data}) => {
          this.service_provider_bookings = data;
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
      this.get_service_provider_bookings();
    }
}
</script>

<style>

</style>