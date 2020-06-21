<template>
  <div >
      <div class="row">
          <div class="col-md-1"></div>
          <div class="col-md-10">
              <div class="card">
                  <div class="card-header">
                      <h2 style="margin: 0;"><label style="margin: 0;">Pending Requests</label></h2>
                  </div>
                  <div class="card-body">
                    <center>
                      <h3><label>Companies</label></h3>
                      <div v-if="pending_requests.length <= 0">
                        <hr/>
                        <i>No Data</i>
                        <hr/>
                      </div>
                      <div v-else="pending_requests.length > 0" class="row">

                        <div class="col-md-3" v-for="(sp, index) in pending_requests" :key="index" >
                          <div class="card" style="">
                            <img class="card-img-top" style="height: 200px;" :src="base_url + '/img/service_providers/logos/' + sp.company_img" alt="Card image cap">
                            <button class="btn btn-sm btn-primary btn-flat form-control form-control-sm" @click="view_sp(sp)">
                              <i class="fa fa-eye"></i> View
                            </button>
                            <div class="card-body">
                              <p class="card-text"><strong>{{ sp.name }}</strong></p>
                            </div>
                          </div>
                        </div>

                      </div>
                    </center>
                  </div>
              </div>
          </div>
          <div class="col-md-1"></div>
      </div>
      <div class="modal fade" id="spModal" tabindex="-1" role="dialog" aria-labelledby="spModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

          <div class="modal-content" style="max-width: 512px; margin-left: auto; margin-right: auto;">
            <div class="modal-header" style="background-color: #614084 !important; color: white;">
              <h5 class="modal-title" id="spModalLabel">View Service Provider</h5>
              <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true" style="color: white;">&times;</span>
              </a>
            </div>
            <div class="modal-body">
              <table class="table table-sm table-striped">
                <tbody>
                  <tr>
                    <td colspan="2" class="text-center"><img class="card-img-top" :src="base_url + '/img/service_providers/logos/' + sp.company_img" alt="Card image cap" style="width: 100%;"></td>
                  </tr>
                  <tr>
                    <td class="text-right"><strong>Company:</strong></td>
                    <td>{{ sp.name }}</td>
                  </tr>
                  <tr>
                    <td class="text-right"><strong>Owner:</strong></td>
                    <td>{{ sp.owner }}</td>
                  </tr>
                  <tr>
                    <td class="text-right"><strong>Contact No:</strong></td>
                    <td>{{ sp.contact_no }}</td>
                  </tr>
                  <tr>
                    <td class="text-right"><strong>Contact Person:</strong></td>
                    <td>{{ sp.contact_person }}</td>
                  </tr>
                  <tr>
                    <td class="text-right"><strong>Address:</strong></td>
                    <td>{{ sp.address }}</td>
                  </tr>
                  <tr>
                    <td class="text-right"><strong>Business Permit No:</strong></td>
                    <td>{{ sp.business_permit_no }}</td>
                  </tr>
                </tbody>
              </table>

              <hr/>
              <div class="row">
                <div class="col-md-12 text-center">
                  <strong>Business Permit:</strong>
                  <br>
                  <img v-if="sp.permit_img" :src="base_url + '/img/service_providers/permits/'+sp.permit_img" alt="" style="max-width: 250px">
                  <label v-else="!sp.permit_img">Not Available!</label>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" @click="decline_sp()">Decline</button>
              <button type="button" class="btn btn-success" @click="approved_sp()">Approve</button>
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
            pending_requests: [],
            sp:{
              id: '',
              name: '',
              address: '',
              mobile_number: '',
            },
        }
    },
    methods:{
      get_pending_requests(){
        axios.get(this.base_url + '/pending_request').then(({data}) => {
          this.pending_requests = data;
        }).catch(() => {

        });
      },

      view_sp(sp){
        this.sp = sp;
        $('#spModal').modal('show');
      },

      approved_sp(){
        axios.put(this.base_url + '/approved_service_provider/'+this.sp.id).then(() => {
          this.get_pending_requests();
          $('#spModal').modal('hide');
        }).catch(() => {

        })
      },

      decline_sp(){
        axios.put(this.base_url + '/decline_service_provider/'+this.sp.id).then(() => {
          this.get_pending_requests();
          $('#spModal').modal('hide');
        }).catch(() => {

        });
      }
    },
    created(){
        this.get_pending_requests();
    }
}
</script>

<style>

</style>