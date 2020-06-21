<template>
  <div class="container mt-2">
      <div class="row">
          <div class="col-md-12">
              <div class="card">
                  <div class="card-header">
                    <h2 style="margin: 0;"><label style="margin: 0;">Clients</label></h2>
                  </div>
                  <div class="card-body">
                      <div class="table-responsive-sm">
                        <div class="input-group col-md-4 float-right" style="padding: 0; margin-bottom: 5px;">
                          <input type="text" class="form-control form-control-sm" v-model="word" @keyup="get_clients()" placeholder="Search" aria-label="Recipient's username" aria-describedby="basic-addon2">
                          <div class="input-group-append">
                            <button class="btn btn-sm btn-outline-secondary" @click="get_complains()" type="button"><i class="fa fa-search"></i></button>
                          </div>
                        </div>
                        <div class="" style="overflow-x:auto; width: 100%;">
                            <table class="table table-sm table-hover table-striped table-condensed" style=" min-width: 614px;">
                              <thead>
                                  <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>Address</th>
                                    <th>Mobile Number</th>
                                    <th></th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr v-if="clients.length <= 0" class="text-center">
                                    <td colspan="6"><i>No Data</i></td>
                                  </tr>
                                  <tr v-else="lients.length > 0" v-for="(c, index) in clients" :key="index">
                                      <td>{{ c.id }}</td>
                                      <td>{{ c.fullname }}</td>
                                      <td>{{ c.age }}</td>
                                      <td>{{ c.address }}</td>
                                      <td>{{ c.mobile_number }}</td>
                                      <td style="padding: 0; max-width: 100px;" >
                                        <div class="btn-group" style="width: 100%;">
                                          <button v-if="!c.Blocked" type="button" class="btn btn-sm btn-danger form-control form-control-sm" @click="confirmBlock(c)" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-ban"></i> Ban</button>
                                          <button v-else="c.Blocked" type="button" class="btn btn-sm btn-success form-control form-control-sm" @click="unblock_user(c.id)"><i class="fa fa-check-circle"></i> Unban</button>
                                          <a class="btn btn-sm btn-primary" target="_blank" v-bind:href="base_url + '/messaging?email=' + c.email">
                                            <i class="far fa-comment-dots"></i>
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
      </div>
    <div class="modal fade" id="BlockUserModal" tabindex="-1" role="dialog" aria-labelledby="BlockUserModalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h3><label id="BlockUserModalLabel"><i class="fa fa-warning text-danger"></i> Block {{ block_name }}</label></h3>
              </div>
              <div class="modal-body">
                <h4>Are you sure you want to block this user?</h4>
                <textarea v-model="reason" class="form-control form-control-sm" placeholder="Additional notes. (Optional)" style="height: 80px;">
                  
                </textarea>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                  <button type="button" class="btn btn-success btn-ok" @click="block_user()"><i class="fa fa-ban"></i> Yes</button>
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
            clients: [],
            word: '',
            reason: '',
            blocked_id: '',
            block_name: '',
            unblock_id: '',
        }
    },
    methods:{
        get_clients(){
            axios.get(this.base_url + '/client?word='+this.word).then(({data}) => {
                this.clients = data;
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
        block_user(){
            axios.post(this.base_url + '/block', {
              blocked_id: this.blocked_id,
              reason: this.reason,
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
              this.get_clients();
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
            $('#BlockUserModal').modal('hide');
        },
        unblock_user(id){
            axios.post(this.base_url + '/unblock', {
              unblocked_id: id
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
              this.get_clients();
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
            $('#BlockUserModal').modal('hide');
        },
        confirmBlock(cs){
            this.blocked_id = cs.id;
            this.block_name = cs.fullname;
            this.reason = '';
            $('#BlockUserModal').modal('show');
        }
    },
    created(){
        this.get_clients();
    },
    mounted(){[

    ]}
}
</script>
<style>

</style>