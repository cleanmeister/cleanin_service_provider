<template>
  <div class="container mt-2">
    <div class="card">
        <div class="card-header">
            <h2 style="margin: 0;"><label style="margin: 0;">Service Provider Services</label></h2>
        </div>
        <div class="card-body">
            <div class="table-responsive-sm">

            <button class="btn btn btn-sm btn-primary" @click="new_service()">
                <i class="fa fa-plus"></i> New Service
            </button>
            <div class="input-group col-md-4 float-right" style="padding: 0; margin-bottom: 5px;">
                <input type="text" class="form-control form-control-sm" @keyup="get_service_provider_services()" v-model="word" placeholder="Search">
                <div class="input-group-append">
                    <button class="btn btn-sm btn-outline-secondary" @click="get_complains()" type="button"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <div style="width: 100%; overflow: auto;">
              <table class="table table-sm table-hover table-striped table-condensed">
                <thead>
                    <tr class="row">
                        <th class="col-md-2">Service</th>
                        <th class="col-md-7 d-none d-md-block">Description</th>
                        <th class="col-md-1 d-none d-md-block">Price</th>
                        <th class="col-md-1 d-none d-md-block">Rate</th>
                        <th class="col-md-1 d-none d-md-block"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="services.length <= 0" class="text-center">
                        <td colspan="5"><span><i>No Data</i></span></td>
                    </tr>
                    <tr v-for="(s, index) in services" :key="index" class="row">
                        <td class="col-md-2"><strong>{{ s.name }}</strong></td>
                        <td class="col-md-7">{{ s.desc }}</td>
                        <td class="col-md-1">{{ s.price }}</td>
                        <td class="col-md-1">
                            <span v-if="s.rate == 1">Per Hour</span>
                            <span v-if="s.rate == 2">Per Day</span>
                        </td>
                        <td class="col-md-1">
                            <div class="btn-group" style="width: 100%;">
                                <button class="btn btn-sm btn-warning" @click="edit_service(s)">
                                    <i class="fa fa-pen"></i>
                                </button>
                                <button v-if="s.status == 1" class="btn btn-sm btn-danger" @click="conf_delete_service(s)">
                                    <i class="far fa-times-circle"></i>
                                </button>
                                <button v-else="s.status == 0" class="btn btn-sm btn-success" @click="enable_service(s.id)">
                                    <i class="far fa-check-circle"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
              </table>
            </div>
            </div>
        </div>
    </div>
	<div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="serviceModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="serviceModalLabel">
						<span v-show="!editmode">New Service</span>
						<span v-show="editmode">Edit Service</span>
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form @submit.prevent="editmode == false ? store_service() : update_service()">
					<div class="modal-body">
						<div class="form-group row">
							<div class="col-3 text-right">
								<label for="" class="form-label">Name</label>
							</div>
							<div class="col-9">
								<input type="text" class="form-control form-control-sm" v-model="form.name">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-3 text-right">
								<label for="" class="form-label">
									Description
								</label>
							</div>
							<div class="col-9">
								<textarea rows="3" class="form-control form-control" v-model="form.desc"></textarea>
							</div>
						</div>
						<div class="form-group row">
							<div class="col-3 text-right">
								<label for="" class="form-label">
									Price
								</label>
							</div>
							<div class="col-9">
								<input type="number" class="form-control form-control-sm" v-model="form.price">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-3 text-right">
								<label for="" class="form-label">
									Rate
								</label>
							</div>
							<div class="col-9">
								<select class="form-control form-control-sm" v-model="form.rate">
									<option value="1">Per Hour</option>
									<option value="2">Per Day</option>
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
    <div class="modal fade" id="deleteserviceModal" tabindex="-1" role="dialog" aria-labelledby="deleteserviceModalLabel" aria-hidden="true">
        <div class="modal-dialog col-md-4" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="deleteserviceModalLabel">
                        <strong><i class="fas fa-exclamation-triangle"></i> Disable Service?</strong>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    You are about to disable the following service: 
                    <br><strong>{{ disableSvc.name }}</strong><br>
                    Continue anyway?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-warning" style="min-width: 75px;" data-dismiss="modal" aria-label="Close">No</button>
                    <button class="btn btn-sm btn-success" style="min-width: 75px;" @click="delete_service(disableSvc.id)">Yes</button>
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
        	editmode: false,
            services: [],
            word: '',
            form: {
            	id: '',
            	name: '',
            	desc: '',
            	rate: '',
            	price: '',
            },
            disableSvc: {
                id: 0,
                name: '',
            }
        }
    },
    methods:{
        get_service_provider_services: function(){
            axios.get(this.base_url + '/service_provider_service?word='+this.word)
            .then(({data}) => {
                this.services = data;
            });
        },
        new_service: function(){
          this.form = [];
        	this.editmode = false;
        	$('#serviceModal').modal('show');
        },
        store_service: function(){
        	axios.post(this.base_url + '/service_provider_service', {
        		  name: this.form.name,
            	desc: this.form.desc,
            	rate: this.form.rate,
            	price: this.form.price,
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
            this.get_service_provider_services();
        		$('#serviceModal').modal('hide');
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
            this.get_service_provider_services();
        },
        edit_service: function(s){
          this.form = [];
        	this.form = s;
        	this.editmode = true;
        	$('#serviceModal').modal('show');
          this.get_service_provider_services();
        },
        update_service: function(){
        	axios.put(this.base_url + '/service_provider_service/'+this.form.id, {
            name: this.form.name,
            desc: this.form.desc,
            rate: this.form.rate,
            price: this.form.price
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
    		    $('#serviceModal').modal('hide');
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
          this.get_service_provider_services();
        },
        conf_delete_service: function(s){
            this.disableSvc.id = s.id;
            this.disableSvc.name = s.name;
            $('#deleteserviceModal').modal('show');
        },
        delete_service: function(id){
        	axios.delete(this.base_url + '/service_provider_service/'+id)
            .then(res => {
                toast.fire({
                    position: 'middle',
                    icon: "success",
                    title: res.data.message,
                });
        		this.get_service_provider_services();
        	}).catch(error => {
                toast.fire({
                    position: 'middle',
                    icon: "warning",
                    title: error.response.data.msg
                });
            });

            $('#deleteserviceModal').modal('hide');
        },
        enable_service: function(id){
            axios.get(this.base_url + '/enable_service/'+id)
            .then(res => {
                toast.fire({
                    position: 'middle',
                    icon: "success",
                    title: res.data.message,
                });
                this.get_service_provider_services();
            }).catch(error => {
                toast.fire({
                    position: 'middle',
                    icon: "warning",
                    title: error.response.data.msg
                });
            });
        }
    },
    created(){
        this.get_service_provider_services();
    },
    mounted(){[

    ]}
}
</script>
<style>

</style>