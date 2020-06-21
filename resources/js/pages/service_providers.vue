<template>
  <div class="container mt-2">
      <div class="row">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-header">
                      <div class="row">
                          <div class="col-auto">
                              <h4>Service Providers</h4>
                          </div>
                          <div class="col-auto">
                              Search    
                          </div>
                          <div class="col-4">
                              <input type="text" class="form-control form-control-sm" @keyup="get_service_providers()" v-model="word">
                          </div>
                      </div>
                  </div>
                  <div class="card-body">
                      <table class="table table-sm table-hover table-striped">
                          <thead>
                              <tr>
                                  <th>Name</th>
                                  <th>Role</th>
                                  <th>Mobile Number</th>
                                  <th>Address</th>
                                  <th></th>
                              </tr>
                          </thead>
                          <tbody>
                              <tr v-for="(sp, index) in service_providers" :key="index">
                                  <td>{{ sp.name }}</td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
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
            service_providers: [],
            service_provider:{},
            word: '',
        }
    },
    methods:{
        get_service_providers(){
            axios.get(this.base_url + '/service_provider').then(({data}) => {
                this.service_providers = data;
            }).catch(() => {

            });
        }
    },
    created(){
        this.get_service_providers();
    }
}
</script>

<style>

</style>