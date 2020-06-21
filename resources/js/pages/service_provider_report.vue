<template>
    <div class="container mt-2">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2 style="margin: 0;"><label style="margin: 0;">Service Provider Report</label></h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <label style="margin-bottom: 0; margin-top: 8px;">Select</label>
                                <select class="form-control form-control-sm" v-model="user_role">
                                    <option v-for="val in role_data" :key="val.id" :value="val.id">
                                        {{ val.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label style="margin-bottom: 0; margin-top: 8px;">From</label>
                                <!-- <input type="date" class="form-control form-control-sm" v-model="date_from"> -->
                                <datepicker :disabled-dates="DatepickerSettings.disabledDates"
                                    v-model="date_from" typeable  :bootstrap-styling="true"
                                    input-class="form-control form-control-sm"
                                    placeholder="" format="D, MMM d, yyyy">

                                    <div slot="afterDateInput" id="datepickericon" class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </div>
                                    </div>
                                </datepicker>
                            </div>
                            <div class="col-md-3">
                                <label style="margin-bottom: 0; margin-top: 8px;">To</label>
                                <!-- <input type="date" class="form-control form-control-sm" v-model="date_to"> -->
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
                            <div class="col-md-2">
                                <label style="margin-bottom: 0; margin-top: 8px;">Status</label>
                                <select v-bind:disabled="user_role === 7" class="form-control form-control-sm" v-model="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label style="margin-bottom: 0; margin-top: 4px; width: 100%; ">&nbsp;</label>
                                <div class="btn-group" style="width: 100%;">
                                    <button class="btn btn-primary form-control text-left" @click.prevent="service_provider_create_report()"><i class="fa fa-file"></i> create</button>
                                    <button class="btn btn-secondary dropdown-toggle-split" @click.prevent="print()"><i class="fa fa-print"></i></button>
                                </div>
                                
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive-sm" id="printMe" style="overflow-x:auto; width: 100%;">
                            <table v-if="user_role != 7" class="table table-sm table-hover table-striped table-condensed" style=" min-width: 810px;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>Mobile Number</th>
                                        <th>Active</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="reports_data.length <= 0" class="text-center"><td colspan="5"> No Data </td></tr>
                                    <tr v-else v-for="(data,index) in reports_data" :key="index">
                                        <td>{{ data.id }}</td>
                                        <td>{{ data.firstname   +" " + data.lastname }}</td>
                                        <td>{{ data.address }}</td>
                                        <td>{{ data.mobile_number }}</td>
                                        <td v-if="data.is_active == 1">Yes</td>
                                        <td v-else>No</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table v-if="user_role === 7" class="table table-sm table-hover table-striped table-condensed" style=" min-width: 810px;">
                                <thead>
                                    <tr>
                                      <th>Service</th>
                                      <th>Cleaner</th>
                                      <th>Schedule</th>
                                      <th>Client</th>
                                      <th>Rate</th>
                                      <th>Feedback</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="service_provider_bookings.length <= 0" class="text-center"><td colspan="6"> No Data </td></tr>
                                    <tr v-for="(spb, index) in service_provider_bookings" :key="index">
                                      <td>{{ spb.service_name }}</td>
                                      <td>{{ spb.cleaner_firstname }} {{ spb.cleaner_middlename }} {{ spb.cleaner_lastname }}</td>
                                      <td>{{ getDate(spb.formated_start_time) }}<br/>{{ getTime(spb.formated_start_time) }} - {{ spb.formated_end_time }}</td>
                                      <td>{{ spb.client_firstname }} {{ spb.client_middlename }} {{ spb.client_lastname }}</td>
                                      <td>{{ spb.rating }} <span class="fa fa-star" v-show="spb.rating != null" style="color: gold;"></span></td>
                                      <td>{{ spb.feedback }}</td>
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
        data() {
            return {
                user_role: '',
                date_from: new Date((new Date()).valueOf() - 1000*60*60*24).toJSON().slice(0, 10),
                date_to: new Date().toJSON().slice(0, 10),
                status: '',
                role_data: [],
                reports_data: [],
                service_provider_bookings:[],
                DatepickerSettings: {
                    disabledDates:{
                        from: new Date(),
                        to: new Date(1970, 12)
                    }
                },
            }
        },
        methods: {
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
            service_provider_create_report() {
                axios.post(this.base_url + '/admin_create_report', {
                    role_id: this.user_role,
                    from: this.date_from,
                    to: this.date_to,
                    status: this.status
                }).then(res => {
                    if(this.user_role == 7){
                        this.service_provider_bookings = res.data;
                        this.reports_data = [];
                    }
                    else{
                        this.service_provider_bookings = [];
                        this.reports_data = res.data;
                    }
                }).catch(() => {

                })
            },
            fetchRole() {
                axios.get(this.base_url + '/get_roles')
                    .then((res) => {
                        for (var i = 0; i < res.data.length; i++) {
                            if (res.data[i].name == 'Client' || res.data[i].name == 'Cleaner' || res.data[i].name == 'Transactions') {
                                this.role_data.push(res.data[i]);
                            }
                        }
                        console.log(this.role_data);
                    }).catch(() => {

                    });
            },
            getDate: function(date){
                return moment(date).format('MMMM D, yyyy');
            },
            getTime: function(date){
                return moment(date).format('h:mm A');
            },
        },
        mounted() {
            this.fetchRole();
        }
    }

</script>

<style>

</style>
