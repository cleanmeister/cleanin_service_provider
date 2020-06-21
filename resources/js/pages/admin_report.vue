<template>

    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2 style="margin: 0;"><label style="margin: 0;">Admin Report</label></h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                          <div class="col-sm-2">
                            <label style="margin-bottom: 0; margin-top: 8px;">Select</label>
                            <select class="form-control form-control-sm" v-model="user_role">
                              <option :value="role_data.id"> {{ role_data.name }}</option>
                            </select>
                          </div>
                          <div class="col-sm-3">
                            <!-- <input type="date" class="form-control form-control-sm" v-model="date_from"> -->
                            <label style="margin-bottom: 0; margin-top: 8px;">From</label>
                            <datepicker :disabled-dates="DatepickerSettings.disabledDates"
                              v-model="date_from" typeable  :bootstrap-styling="true"
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
                            <!-- <input type="date" class="form-control form-control-sm" v-model="date_to"> -->
                            <label style="margin-bottom: 0; margin-top: 8px;">To</label>
                            <datepicker :disabled-dates="DatepickerSettings.disabledDates"
                              v-model="date_to" typeable  :bootstrap-styling="true"
                              input-class="form-control form-control-sm"
                              placeholder="" format="D, MMM d, yyyy">

                              <div slot="afterDateInput" id="datepickericon" class="input-group-append">
                                <div class="input-group-text">
                                  <i class="fas fa-calendar-alt"></i>
                                </div>
                              </div>
                            </datepicker>
                          </div>
                          <div class="col-sm-2">
                            <label style="margin-bottom: 0; margin-top: 8px;">Status</label>
                            <select class="form-control form-control-sm" v-model="status">
                              <option value="1">Active</option>
                              <option value="0">Inactive</option>
                            </select>
                          </div>
                          <div class="col-sm-2">
                            <label style="margin-bottom: 0; margin-top: 8px;">&nbsp;</label>
                            <div class="btn-group" style="width: 100%;">
                              <button class="btn btn-sm btn-primary" @click.prevent="admin_create_report()"><i class="fas fa-file"></i> create</button>
                              <button class="btn btn-sm btn-secondary" @click.prevent="print()"><i class="fas fa-print"></i></button>
                            </div>
                          </div>
                        </div>
                        <br>

                        <div id="printMe" class="table-responsive-sm" style="overflow-x:auto; width: 100%;">
                          <table class="table table-sm table-hover table-striped table-condensed" style=" min-width: 810px;">
                            <thead>
                              <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Mobile Number</th>
                                <th>Contact Person</th>
                                <th>Business Permit No</th>
                                <th>Active</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr v-if="reports_data.length <= 0">
                                <td colspan="7" class="text-center">
                                  <h4 style="margin: 0;"><span><i>No Data</i></span></h4>
                                </td>
                              </tr>
                              <tr v-for="(data,index) in reports_data" :key="index">
                                <td>{{ data.id }}</td>
                                <td>{{ data.name }}</td>
                                <td>{{ data.address }}</td>
                                <td>{{ data.mobile_number }}</td>
                                <td>{{ data.contact_person }}</td>
                                <td>{{ data.business_permit_no }}</td>
                                <td v-if="data.owner.is_active == 1">Yes</td>
                                <td v-else>No</td>
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
<style>
@media print {
  body * {
    visibility: hidden;
  }
  #report_data_table, #report_data_table * {
    visibility: visible;
  }
  #report_data_table {
    position: absolute;
    left: 0;
    top: 0;
  }
}
</style>
<script>
    import datepicker from 'vuejs-datepicker';
    Vue.use(datepicker);

export default {
    props: ['base_url'],
    components:{ datepicker },
    data(){
        return{
            user_role: '',
            date_from: (new Date()).setDate((new Date()).getDate() - 1),
            date_to: new Date(),
            status:'',
            role_data: [],
            reports_data: [],

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
      admin_create_report(){
        axios.post(this.base_url + '/admin_create_report', {
          role_id: this.user_role,
          from: this.date_from,
          to:this.date_to,
          status: this.status
        }).then((res) => {
          this.reports_data = res.data;
        }).catch(() => {
          
        })
      },
      fetchRole(){
        axios.get(this.base_url + '/get_roles')
        .then((res) => {
          for ( var i = 1; i < res.data.length; i++ ) {
            console.log(res.data[i].name);
            if(res.data[i].name == 'Service Provider'){
              this.role_data = res.data[i];
              break;
            }
          }
        }).catch(() => {

        });
      },
    },
    created(){
      this.fetchRole();
    }
}
</script>