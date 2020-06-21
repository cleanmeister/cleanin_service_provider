<template>
  <div id="app" class="container mt-2">
      <div class="row d-print-none">
        <div class="col-sm-12">
          <div class="card">
            <div class="card-header">
              <h2 style="margin: 0;"><label style="margin: 0;">Reports</label></h2>
            </div>
            <div class="card-body">
                <form @submit.prevent="create_client_report()">
                  <div class="row d-print-none">
                    <div class="col-sm-3">
                      <label style="margin-bottom: 0; margin-top: 8px;">From</label>
                      <!-- <input type="date" class="form-control form-control-sm" v-model="from"> -->
                      <datepicker :disabled-dates="DatepickerSettings.disabledDates"
                        v-model="from" typeable  :bootstrap-styling="true"
                        input-class="form-control form-control-sm"
                        placeholder="" format="D, MMM d, yyyy">

                        <div slot="afterDateInput" id="datepickericon" class="input-group-append">
                          <div class="input-group-text">
                            <i class="fas fa-calendar"></i>
                          </div>
                        </div>
                      </datepicker>
                    </div>
                    <div class="col-sm-3">
                      <label style="margin-bottom: 0; margin-top: 8px;">To</label>
                      <!-- <input type="date" class="form-control form-control-sm" v-model="to"> -->
                      <datepicker :disabled-dates="DatepickerSettings.disabledDates"
                        v-model="to" typeable  :bootstrap-styling="true"
                        input-class="form-control form-control-sm"
                        placeholder="" format="D, MMM d, yyyy">

                        <div slot="afterDateInput" id="datepickericon" class="input-group-append">
                          <div class="input-group-text">
                            <i class="fas fa-calendar-alt"></i>
                          </div>
                        </div>
                      </datepicker>
                    </div>
                    <div class="col-md-2">
                        <label style="margin-bottom: 0; margin-top: 4px; width: 100%; ">&nbsp;</label>
                        <div class="btn-group" style="width: 100%;">
                            <button class="btn btn-primary form-control text-left" type="submit"><i class="fa fa-file"></i> create</button>
                            <button class="btn btn-secondary dropdown-toggle-split" name="print_report" @click.prevent="print()"><i class="fa fa-print"></i></button>
                        </div>
                    </div>
                  </div>
                </form>
                <br>
              <div id="printMe" class="table-responsive-sm printing" style="overflow: auto;">
                <table class="table table-sm table-bordered table-white" style="min-width: 760px;">
                  <thead>
                    <tr class="text-center">
                      <th>Service</th>
                      <th>Schedule</th>
                      <th>Status</th>
                      <th>Client</th>
                      <th>Time In</th>
                      <th>Time Out</th>
                      <th>Rating</th>
                      <th>Feedback</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="report_data.length == 0">
                      <td colspan="10" class="text-center">No Data</td>
                    </tr>
                    <tr v-else v-for="(d, index) in report_data" :key="index">
                      <td><strong>{{ d.service_name }}</strong></td>
                      <td>{{ getDate(d.formated_start_time) }}: {{ d.day_desc }}<br/>{{ getTime(d.formated_start_time) }}-{{ d.formated_end_time }}</td>
                      <td>
                        <span v-show="d.status == 1">Pending</span>
                        <span v-show="d.status == 2">Approved</span>
                        <span v-show="d.status == 3">Completed</span>
                        <span v-show="d.status == 4">Rejected</span>
                        <span v-show="d.status == 5">Cancel</span>
                      </td>
                      <td>{{ d.client_firstname }} {{ d.client_middlename }} {{ d.client_lastname }}</td>
                      <td>{{ d.time_in }}</td>
                      <td>{{ d.time_out }}</td>
                      <td>{{ d.rating }} <span class="fa fa-star" v-show="d.rating != null" style="color: gold;"></span></td>
                      <td>{{ d.feedback }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
</template>

<script>
import datepicker from 'vuejs-datepicker';
import moment from 'moment';
Vue.use(datepicker);
export default {
    props: ['base_url'],
    components:{ datepicker },
    data(){
        return{
            from: new Date((new Date()).valueOf() - 1000*60*60*24).toJSON().slice(0, 10),
            to: new Date().toJSON().slice(0, 10),
            report_data: [],
            DatepickerSettings: {
                disabledDates:{
                    from: new Date(),
                    to: new Date(1970, 12)
                }
            },
        }
    },
    methods:{
      print() {
        // Pass the element id here
        var options={
          name: '_blank',
          specs: [
            'fullscreen=yes',
            'titlebar=yes',
            'scrollbars=yes'
          ],
          styles: [
            this.base_url+'/css/app.css',
            'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
            'https://unpkg.com/kidlat-css/css/kidlat.css'
          ]
        }
        this.$htmlToPaper('printMe', options);
      },
      create_client_report(){
        axios.post(this.base_url + '/cleaner_create_report', {
          from: this.from,
          to: this.to,
        }).then(({data}) => {
          this.report_data = data;
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
      this.create_client_report();
    }
};
</script>

<style>

</style>