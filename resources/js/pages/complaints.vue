<template>
  <div class="container mt-0">
      <div class="row">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-header">
                    <h2 style="margin: 0;"><label style="margin: 0;">Complaints</label></h2>
                  </div>
                  <div class="card-body">
                      <div class="table-responsive-sm">

                        <div class="input-group col-md-4 float-right" style="padding: 0; margin-bottom: 5px;">
                          <input type="text" @ class="form-control form-control-sm" v-model="search" @keyup="get_complains()" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                          <div class="input-group-append">
                            <button class="btn btn-sm btn-outline-secondary" @click="get_complains()" type="button"><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                        <div style="overflow-x:auto; width: 100%;">
                          <table class="table table-sm table-hover table-striped table-condensed text-centered" style=" min-width: 810px;">
                            <thead>
                              <tr>
                                <th>Service</th>
                                <th>Complainant</th>
                                <th>Complainee</th>
                                <th>Schedule</th>
                                <th>Complaint</th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr v-show="complaints.length <= 0" class="text-center">
                                  <td colspan="5">
                                    <i style="margin: 10px;">No Data</i>
                                  </td>
                                </tr>
                                <tr v-show="complaints.length > 0" v-for="(q, index) in complaints" :key="index">
                                  <td>{{ q.ServiceName }}</td>
                                  <td>{{ q.complainant.fullname }}</td>
                                  <td>{{ q.complainee.fullname }}</td>
                                  <td>{{ q.day }}: {{ q.start_time }} - {{ q.end_time }}</td>
                                  <td>{{ q.complaint }}</td>
                                </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                  </div>
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
          complaints: [ ],
          search: '',
        }
    },
    methods:{
      get_complains(){
        axios.get(this.base_url + '/getcomplaints?word='+this.search)
        .then(({data}) => {
          this.complaints = data;
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
    },
    created(){
      this.get_complains();
    }
}
</script>

<style>

</style>