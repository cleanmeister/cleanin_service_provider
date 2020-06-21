<template>
  <div class="container mt-2">
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
                      <table class="table table-sm table-hover table-striped">
                          <thead>
                              <tr>
                                  <th>Service</th>
                                  <th>Description</th>
                                  <th>Rate</th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="(s, index) in services" :key="index">
                                  <td>{{ s.name }}</td>
                                  <td>{{ s.desc }}</td>
                                  <td>{{ s.rate }}</td>
                              </tr>
                          </tbody>
                      </table>
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
            services: [],
        }
    },
    methods:{

        get_services(){
            axios.get(this.base_url + '/service').then(({data}) => {
                this.services = data;
            }).catch(() => {

            });
        },
    },
    created(){
        this.get_days();
        this.get_schedules();
        this.get_services();
    }
}
</script>

<style>

</style>