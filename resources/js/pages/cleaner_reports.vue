<template>
  <div class="container mt-2">
      <div class="row">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-header">
                      <div class="row">
                          <div class="col-auto">
                              <h4>Admin Report</h4>
                          </div>
                      </div>
                      <div class="row">
                        <div class="col-auto"></div>
                      </div>
                  </div>
                  <div class="card-body">
        
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
        }).then(() => {

        });
      },

    },
    created(){
      this.get_services();
    }
}
</script>

<style>

</style>