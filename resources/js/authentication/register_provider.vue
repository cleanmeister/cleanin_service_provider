<template>
	<div style="margin: auto auto">
		
		<div class="login-box" style="margin-left: auto; margin-right: auto;">
			<div class="login-logo" style="margin-bottom: 0;">
				<a v-bind:href="base_url"><span style="font-weight: 1000;">Clean</span> Meister</a>
			</div>
			<!-- /.login-logo -->
		</div>
		<div class="card" style="max-width: 512px; margin-left: auto; margin-right: auto; border-color: #614084;">
			<div class="card-header bg-dark">
				<h4><p class="login-box-msg" style="padding: 0;">Service Provider Registration</p></h4>

				<div class="btn-group form-control form-control-sm" role="group" aria-label="First group" style="padding: 0; border: none; margin-bottom: -1rem;">
					<a v-bind:href="base_url + '/login'" type="button" class="btn btn-sm btn-secondary bg-dark">Login <i class="fa fa-login"></i></a>
					<a v-bind:href="base_url + '/register'" type="button" class="btn btn-sm btn-secondar bg-dark" disable>Register</a>
					<a href="#" type="button" class="btn btn-sm btn-secondar bg-dark active">Register Service Provider</a>
				</div>

			</div>
			<div class="card-body login-card-body" style="padding-left: 0; padding-right: 0;">
				<div v-show="message != ''" class="form-group row">
					<div class="col-md-12">
						<div v-bind:class="returnsError ? 'alert-danger' : 'alert-success'" class="alert">
							<strong>{{ returnsError ? 'Failed!' : 'Success!' }}</strong><p v-html="message"></p>
						</div>
					</div>
				</div>
                <form @submit="register" method="POST" enctype="multipart/form-data">
                	<input type="hidden" name="_token" :value="csrf">
                    <input type="hidden" name="role_id" :value="form.role_id">

                    <div class="tab" v-show="step == 0">

	                    <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
	                        <div class="col-md-6  ">
	                        	<label for="firstname pull-left">Profile picture</label>
								<div class="text-center" style="width: 100%;">
									<image-uploader
								        :preview="false"
								        :maxWidth="256"
								        :className="['fileinput hidden', { 'fileinput--loaded': hasImage }]"
								        capture="environment"
								        :debug="1"
								        doNotResize="gif"
								        :autoRotate="true"
								        outputFormat="verbose"
								        @input="setImage">

								        <label for="fileInput" slot="upload-label">
											<figure v-show="!hasImage">
											<i class="fas fa-camera fa-3x" ></i>
											</figure>
											<img :src="image" v-show="hasImage" class="img-preview rounded mx-auto" alt="Profile Image" style="width: 100px; height: 120px; display: block;">
											<span class="upload-caption">{{ hasImage ? "Click to Replace" : "Click to upload" }}</span>
								        </label>

									</image-uploader>
								</div>
	                            <p class="err-loc text-danger"><strong v-html="errors.image"></strong></p>
	                        </div>
	                        <div class="col-md-6">
	                        	<label for="username" class="">Username</label>
								<div class="input-group">
	                            	<input id="username" type="username" v-model="form.username" v-bind:class="errors.username == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="username" autocomplete="username">
									<div class="input-group-append">
										<div class="input-group-text">
											<i class="fas fa-user"></i>
										</div>
									</div>
								</div>
	                            <p class="err-loc text-danger"><strong v-html="errors.username"></strong></p>
	                        	<label for="email">E-Mail</label>
								<div class="input-group">
	                            	<input id="email" type="text" v-model="form.email" v-bind:class="errors.email == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="email" value="" autocomplete="email">
									<div class="input-group-append">
										<div class="input-group-text">
											<i class="fas fa-envelope"></i>
										</div>
									</div>
								</div>
	                            <p class="err-loc text-danger"><strong v-html="errors.email"></strong></p>
	                        </div>
	                    </div>
	                    
	                    <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
	                        <div class="col-md-6">
	                        	<label for="password">Password</label>
	                        	<div class="input-group">
		                            <input id="password" type="password" v-model="form.password" v-bind:class="errors.password == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="password" autocomplete="off">
		                            <div class="input-group-append">
										<div class="input-group-text">
											<i class="fas fa-user-lock"></i>
										</div>
									</div>
	                        	</div>
	                            <p class="err-loc text-danger"><strong v-html="errors.password"></strong></p>
	                        </div>
	                        <div class="col-md-6">
	                        	<label for="password-confirm">Confirm Password</label>
	                        	<div class="input-group">
		                            <input id="password_confirm" type="password"v-model="form.password_confirmation" v-bind:class="errors.password_confirmation == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="password_confirmation" autocomplete="off">
		                        	<div slot="afterDateInput" class="input-group-append">
										<div class="input-group-text">
											<i class="fas fa-lock"></i>
										</div>
									</div>
	                        	</div>
	                            <p class="err-loc text-danger"><strong v-html="errors.password_confirmation"></strong></p>
	                        </div>
	                    </div>

	                    <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
	                        <div class="col-md-4" style="">
	                        	<label for="firstname">Firstname</label>
	                            <input id="firstname" type="text" v-model="form.firstname" v-bind:class="errors.firstname == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="firstname" autocomplete="firstname">
	                        </div>
	                        <div class="col-md-4" style="">
	                        	<label for="middlename">Middlename</label>
	                            <input id="middlename" type="text" v-model="form.middlename" v-bind:class="errors.middlename == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="middlename" autocomplete="middlename">
	                        </div>
	                        <div class="col-md-4" style="">
	                        	<label for="lastname">Lastname</label>
	                            <input id="lastname" type="text" v-model="form.lastname" v-bind:class="errors.lastname == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="lastname" autocomplete="lastname">
	                        </div>
	                        <div class="col-md-12">
	                            <p class="err-loc text-danger"><strong v-html="errors.name"></strong></p>
	                        </div>
	                    </div>

	                    <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
	                        <div class="col-md-6">
	                        	<label for="date_of_birth">Date of Birth</label>
	                            <!-- <input id="date_of_birth" type="date" class="form-control form-control-sm is-invalid" name="date_of_birth" value="" required autocomplete="date_of_birth"> -->

								<div class="input-group">
	                            	<datepicker :disabled-dates="DatepickerSettings.disabledDates"
	                            		v-model="form.date_of_birth" typeable  :bootstrap-styling="true"
	                            		input-class="form-control form-control-sm"
	                            		placeholder="" format="D, MMM d, yyyy">

										<div slot="afterDateInput" id="datepickericon" class="input-group-append">
											<div class="input-group-text">
												<i class="fas fa-calendar"></i>
											</div>
										</div>
	                            	</datepicker>
								</div>
	                            <p class="err-loc text-danger"><strong v-html="errors.date_of_birth"></strong></p>
	                        </div>
	                        <div class="col-md-6">
	                        	<label for="mobile_number">Mobile Number</label>
	                        	<div class="input-group">
		                            <input id="mobile_number" type="text" v-model="form.mobile_number" v-bind:class="errors.mobile_number == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="mobile_number" value="" autocomplete="mobile_number">
		                            <div class="input-group-append">
										<div class="input-group-text">
											<i class="fas fa-mobile-alt"></i>
										</div>
									</div>
	                        	</div>

	                            <p class="err-loc text-danger"><strong v-html="errors.mobile_number"></strong></p>
	                        </div>
	                    </div>

	                    <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
	                        <div class="col-md-12">
	                        	<label for="address">Address</label>
	                        	<div class="input-group">
		                            <textarea id="address" type="text"v-model="form.address" v-bind:class="errors.address == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="address" value="" autocomplete="address" style="min-height: 29px; max-height: 120px; height: 29px;"></textarea> 
		                            <div class="input-group-append">
										<div class="input-group-text">
											<i class="fas fa-map-marker-alt"></i>
										</div>
									</div>
								</div>
	                            <p class="err-loc text-danger"><strong v-html="errors.address"></strong></p>
	                        </div>
	                    </div>

	                </div>

	                <div class="tab" v-show="step == 1">
	                	<div class="form-group row">
	                		<div class="col-md-4" v-bind:class="showLoader ? 'ablur' : ''">
	                        	<label for="firstname pull-left">Company Logo</label>
								<div class="text-center" style="width: 100%;">
									<image-uploader
										id="logoupload"
								        :preview="false"
								        :maxWidth="256"
								        :className="['fileinputs hidden', { 'fileinput--loaded': logohasImage }]"
								        capture="environment"
								        :debug="1"
								        doNotResize="gif"
								        :autoRotate="true"
								        outputFormat="verbose"
								        @input="setLogoImage">

								        <label for="logoupload" slot="upload-label">
											<figure v-show="!logohasImage">
												<i class="fas fa-camera fa-3x" ></i>
											</figure>
											<img :src="spform.company_img" v-show="logohasImage" class="img-preview rounded mx-auto" alt="Company Logo" style="width: 100px; height: 120px; display: block;">
											<span class="upload-caption">{{ logohasImage ? "Click to Replace" : "Click to upload" }}</span>
								        </label>

									</image-uploader>
								</div>
	                            <p class="err-loc text-danger"><strong v-html="spformError.company_img"></strong></p>
	                            <hr/>
	                        	<label for="firstname pull-left">Attachments (Optional)</label>
								<div class="text-center" style="width: 100%;">
									<image-uploader
										id="attachmentimage"
								        :preview="false"
								        :maxWidth="256"
								        :className="['fileinputs hidden', { 'fileinput--loaded': attachementhasImage }]"
								        capture="environment"
								        :debug="1"
								        doNotResize="gif"
								        :autoRotate="true"
								        outputFormat="verbose"
								        @input="setattachementImage">

								        <label for="attachmentimage" slot="upload-label">
											<figure v-show="!attachementhasImage">
												<i class="fas fa-file fa-3x" ></i>
											</figure>
											<img :src="spform.attachmentimage" v-show="attachementhasImage" class="img-preview rounded mx-auto" alt="Attachment" style="width: 100px; height: 120px; display: block;">
											<span class="upload-caption">{{ attachementhasImage ? "Click to Replace" : "Click to upload" }}</span>
								        </label>

									</image-uploader>
								</div>
	                            <p class="err-loc text-danger"><strong v-html="spformError.attachmentimage"></strong></p>

	                		</div>
	                		<div class="col-md-8">

						        <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
						            <div class="col-md-12">
						            	<label for="spname">Company Name</label>
			                        	<div class="input-group">
							                <input type="text" id="spname" placeholder="Company Name" v-bind:class="spformError.name == '' ? '' : 'is-invalid'" class="form-control form-control-sm" v-model="spform.name" autocomplete="spname">
							                <div class="input-group-append">
												<div class="input-group-text">
													<i class="fas fa-building"></i>
												</div>
											</div>
							            </div>
			                            <p class="err-loc text-danger"><strong v-html="spformError.name"></strong></p>
						            </div>
						        </div>

						        <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
						            <div class="col-md-12">
						            	<label for="spcontactperson">Contact Person</label>
			                        	<div class="input-group">
						                	<input type="text" id="spcontactperson" placeholder="Contact Person" v-bind:class="spformError.contact_person == '' ? '' : 'is-invalid'" class="form-control form-control-sm" v-model="spform.contact_person" autocomplete="spcontactperson">
						                	<div class="input-group-append">
												<div class="input-group-text">
													<i class="fas fa-id-card-alt"></i>
												</div>
											</div>
						            	</div>
			                            <p class="err-loc text-danger"><strong v-html="spformError.contact_person"></strong></p>
						            </div>
						        </div>

						        <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
						            <div class="col-md-12">
						            	<label for="spmobile">Contact No</label>
			                        	<div class="input-group">
							                <input type="text" id="spmobile" placeholder="Contact No" v-bind:class="spformError.mobile_number == '' ? '' : 'is-invalid'" class="form-control form-control-sm" v-model="spform.mobile_number" autocomplete="spmobile_number">
							                <div class="input-group-append">
												<div class="input-group-text">
													<i class="fas fa-phone"></i>
												</div>
											</div>
										</div>
			                            <p class="err-loc text-danger"><strong v-html="spformError.mobile_number"></strong></p>
						            </div>
						        </div>

						        <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
						            <div class="col-md-12">
						            	<label for="spbusiness_permit_no">Business Permit No.</label>
						            	<div class="input-group">
						               		<input type="text" id="spbusiness_permit_no" placeholder="Business Permit No." v-bind:class="spformError.business_permit_no == '' ? '' : 'is-invalid'" class="form-control form-control-sm" v-model="spform.business_permit_no" >
						               		<div class="input-group-append">
												<div class="input-group-text">
													<i class="fas fa-sticky-note"></i>
												</div>
											</div>
						            	</div>
			                            <p class="err-loc text-danger"><strong v-html="spformError.business_permit_no"></strong></p>
						            </div>
						        </div>

						        <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
						            <div class="col-md-12">
						            	<label for="spaddress">Address</label>
			                        	<div class="input-group">
				                            <textarea id="spaddress" type="text" v-model="spform.address" placeholder="Company Address" v-bind:class="spformError.address == '' ? '' : 'is-invalid'" class="form-control form-control-sm" autocomplete="spaddress" style="min-height: 29px; max-height: 120px; height: 29px;"></textarea> 
							                <div class="input-group-append">
												<div class="input-group-text">
													<i class="fas fa-map-marker-alt"></i>
												</div>
											</div>
										</div>
			                            <p class="err-loc text-danger"><strong v-html="spformError.address"></strong></p>
						            </div>
						        </div>

	                		</div>
	                	</div>
	                </div>
                    <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0; margin-top: 10px;">
                    	<div class="col-md-5"></div>
                    	<div class="col-md-2">
							<div style="text-align:center;margin-top: auto; margin-bottom: auto;">
								<span class="step"><i class="fa-circle" v-bind:class="step == 0 ? 'fas' : 'far'"></i></span>
								<span class="step"><i class="fa-circle" v-bind:class="step == 1 ? 'fas' : 'far'"></i></span>
							</div>
                    	</div>
                    	<div class="col-md-5">
							<div style="overflow:auto;">
								<div style="float:right;">
									<button type="button" v-show="step != 0" @click="prev" class="btn btn-default btn-flat">Previous</button>
		                            <button type="submit" class="btn bg-dark btn-flat"> {{ step > 0 ? 'Submit' : 'Next' }} </button>
								</div>
							</div>
                    	</div>
                    </div>


					<div class="spanner" v-bind:class="showLoader ? 'show' : ''">
						<div class="loader"></div>
						<p style="background-color: #28282888">Registering your account, please be patient...</p>
					</div>
                </form>
			</div>
		</div>
	</div>
</template>

<script>
	import datepicker from 'vuejs-datepicker';
	import ImageUploader from 'vue-image-upload-resize';

	Vue.use(datepicker);
	Vue.use(ImageUploader);

	export default {
		props: ['base_url'],
		data(){
			return{
				csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

				hasImage: false,
				logohasImage: false,
				attachementhasImage: false,
				image: null,
				errors:{
					username: '',
					email: '',
					password: '',
					password_confirmation: '',
					firstname: '',
					middlename: '',
					lastname: '',
					name:'',
					date_of_birth: '',
					mobile_number: '',
					address: '',

				},

				form:{
					role_id: 2,
					username: '',
					email: '',
					password: '',
					password_confirmation: '',
					firstname: '',
					middlename: '',
					lastname: '',
					date_of_birth: '',
					mobile_number: '',
					address: ''
				},

				spformError:{
					name: '',
					contact_person:'',
					mobile_number:'',
					address: '',
					business_permit_no: '',
					company_img: '',
					attachmentimage: '',
				},
				spform:{
					name: '',
					contact_person: '', 
					mobile_number: '',
					business_permit_no: '', 
					address: '',
					company_img: '',
					attachmentimage: '',
				},
				imageData: '', 
				DatepickerSettings: {
					disabledDates:{
						from: new Date(),
						to: new Date(1900, 12)
					}
				},
				showLoader: false,
				message: '',
				returnsError: false,
				step: 0,

			}
		},
	    components:{ datepicker, ImageUploader },
		methods:{
			validate: function(){
				var ret = true;
				this.errors.username = '';
				this.errors.email = '';
				this.errors.password = '';
				this.errors.password_confirmation = '';
				this.errors.firstname = '';
				this.errors.middlename = '';
				this.errors.lastname = '';
				this.errors.name = '';
				this.errors.date_of_birth = '';
				this.errors.mobile_number = '';
				this.errors.address = '';

				$("#datepickericon").parent().find('input').removeClass('is-invalid');

				if(!this.form.username){
					this.errors.username = "Please enter your username.";
					ret = false;
				}

				if(!this.validEmail(this.form.email)){
					this.errors.email = "Please enter a valid email address.";
					ret = false;
				}

				if(!this.form.email){
					this.errors.email = "Please enter your email address.";
					ret = false;
				}

				if(this.form.password.length < 8){
					this.errors.password = "Your password must be atleast 8 characters.";
					ret = false;
				}

				if(!this.form.password){
					this.errors.password = "Please enter your password.";
					ret = false;
				}
				
				if(this.form.password_confirmation != this.form.password){
					this.errors.password_confirmation = "Passwords do not match.";
					ret = false;
				}

				if(!this.form.password_confirmation){
					this.errors.password_confirmation = "Please repeat your password.";
					ret = false;
				}

				if(!this.form.firstname){
					this.errors.firstname = "1";
					ret = false;
				}

				if(!this.form.middlename){
					this.errors.middlename = "1";
					ret = false;
				}

				if(!this.form.lastname){
					this.errors.lastname = "1";
					ret = false;
				}

				if(!this.form.firstname || !this.form.middlename || !this.form.lastname){
					this.errors.name = "Please complete the name field.";
					ret = false;
				}

				if(!this.form.mobile_number){
					this.errors.mobile_number = "Please enter your Mobile Number.";
					ret = false;
				}
				if(!this.form.address){
					this.errors.address = "Please enter your address.";
					ret = false;
				}

				if(!this.form.date_of_birth){
					$("#datepickericon").parent().find('input').addClass('is-invalid');
					this.errors.date_of_birth = "Please enter your date of birth.";
					ret = false;
				}

				return ret;
			},
			validateSP: function(){
				var ret = true;
				this.spformError.name = '';
				this.spformError.contact_person = '';
				this.spformError.mobile_number = '';
				this.spformError.business_permit_no = '';
				this.spformError.address = '';
				this.spformError.company_img = '';

				if(!this.spform.name){
					this.spformError.name = 'Please enter your Company\'s Name';
					ret = false;
				}

				if(!this.spform.contact_person){
					this.spformError.contact_person = 'Please enter your Company\'s contact person.';
					ret = false;
				}

				if(!this.spform.mobile_number){
					this.spformError.mobile_number = 'Please enter your Company\'s contact number.';
					ret = false;
				}

				if(!this.spform.business_permit_no){
					this.spformError.business_permit_no = 'Please enter your Company\'s Business Permit No.';
					ret = false;
				}

				if(!this.spform.address){
					this.spformError.address = 'Please enter your Company\'s address.';
					ret = false;
				}

				if(!this.spform.company_img){
					this.spformError.company_img = 'Your Company\'s Logo is required.';
					ret = false;
				}

				return ret;
			},
			register: function(e){
        		e.preventDefault();
        		this.message = "";

				let config = {
					header : {
						'Content-Type' : 'multipart/form-data'
					}
				}
				if(this.step == 0 && this.validate()){
					this.step++;
					return false;
				}

				if(this.step == 1 && this.validateSP()){
                    this.disable = true;
					this.showLoader = true;
                    axios.post(this.base_url + '/register/service_provider', {
                        _token: this.csrf,
                        userprofile: this.form,
                        servicep: this.spform,
                        profile_pic: this.image,
                    }, config).then(res => {
                        this.message = res.data.msg + "<br/>Please wait while we redirect you to the login page.";
                        this.disable = false;
						this.showLoader = false;
						this.returnsError = false;
						window.location = this.base_url + "/login"
                    }).catch(error => {
                        this.message = error.response.data.message;
                        this.disable = false;
						this.showLoader = false;
						this.returnsError = true;

						var tmp = this.errors;
						var page1errors = 0;
						$.each(error.response.data.userprofile, function(key, value){
							tmp[key] = value;
							if(key == 'date_of_birth' ) $("#datepickericon").parent().find('input').addClass('is-invalid');
							page1errors++;
						});

						var tmp2 = this.spformError;
						$.each(error.response.data.servicep, function(a, b){
							tmp2[a] = b;
						});

						if(page1errors > 0) this.step = 0;
                    });
        		}
			},
			setImage: function(output) {
				this.hasImage = true;
				this.image = output['dataUrl'];
			},
			setLogoImage: function(output) {
				this.logohasImage = true;
				this.spform.company_img = output['dataUrl'];
			},
			setattachementImage: function(output) {
				this.attachementhasImage = true;
				this.spform.attachmentimage = output['dataUrl'];
			},
            validEmail: function (email) {
              var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
              return re.test(email);
            },
            next: function(){
            	this.step++;
            	if(this.step >= 1) this.step = 1;
            },
            prev: function(){
            	this.step--;
            	if(this.step <= 0) this.step = 0;
            }
		},
	    created(){
	    },
	    mounted(){
	    }

	}
</script>

<style>
.icheck,
.icheck label{
  cursor: pointer;
}

.vertical-center {
  margin: 0;
  position: absolute;
  top: 50%;
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
}

a.bg-dark{
	border: 0;
}
a.bg-dark:hover{
    background-color: #704a99 !important;
}
.bg-dark.btn:not(:disabled):not(.disabled):active,
.bg-dark.btn:not(:disabled):not(.disabled).active,
.bg-dark.btn:active,
.bg-dark.btn.active,
a.bg-dark:active,
a.bg-dark:focus,
button.bg-dark:focus {
    background-color: #573976 !important;
}

.login-logo a:link,
.login-logo a:visited,
.login-logo a:hover,
.login-logo a:active{
  color: #495057;
}

a:link,
a:visited,
a:hover,
a:active {
  text-decoration: none;
}
.err-loc{
  text-align: center;
  margin-bottom: 0;
  min-height: 20px;
  font-size: 0.85em;
}

.col-md-12,
.col-md-6,
.col-md-8,
.col-md-4{
	padding-left: 2px;
	padding-right: 2px;
}
.btn-file {
    position: relative;
    overflow: hidden;
}
.btn-file input[type=file] {
    position: absolute;
    top: 0;
    right: 0;
    min-width: 100%;
    min-height: 100%;
    font-size: 100px;
    text-align: right;
    filter: alpha(opacity=0);
    opacity: 0;
    outline: none;
    background: white;
    cursor: inherit;
    display: block;
}
.vertical-center {
  margin: 0;
  position: absolute;
  top: 50%;
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
}
.center {
  margin: 0;
  position: absolute;
  top: 50%;
  left: 50%;
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
}

.step{
	color: #614084;
}

.vdp-datepicker{
	width: 100%;
}
#fileInput,
#logoupload,
#attachmentimage {
  display: none;
}
.ablur{
 filter: blur(7px);
}

.spanner{
  position:absolute;
  left: 0;
  background: #2a2a2a55;
  width: 100%;
  display:block;
  text-align:center;
  height: 100%;
  color: #FFF;
  transform: translateY(-97%);
  z-index: 1000;
  visibility: hidden;
}

.overlay{
  position: fixed;
	width: 100%;
	height: 100%;
  background: rgba(2,2,2,.75);
  visibility: hidden;
}

.loader,
.loader:before,
.loader:after {
  border-radius: 50%;
  width: 2.5em;
  height: 2.5em;
  -webkit-animation-fill-mode: both;
  animation-fill-mode: both;
  -webkit-animation: load7 1.8s infinite ease-in-out;
  animation: load7 1.8s infinite ease-in-out;
}
.loader {
  color: #ffffff;
  font-size: 10px;
  margin: 80px auto;
  margin-top: 50%;
  position: relative;
  text-indent: -9999em;
  -webkit-transform: translateZ(0);
  -ms-transform: translateZ(0);
  transform: translateZ(0);

  -webkit-animation-delay: -0.16s;
  animation-delay: -0.16s;
}
.loader:before,
.loader:after {
  content: '';
  position: absolute;
  top: 0;
}
.loader:before {
  left: -3.5em;
  -webkit-animation-delay: -0.32s;
  animation-delay: -0.32s;
}
.loader:after {
  left: 3.5em;
}
@-webkit-keyframes load7 {
  0%,
  80%,
  100% {
    box-shadow: 0 2.5em 0 -1.3em;
  }
  40% {
    box-shadow: 0 2.5em 0 0;
  }
}
@keyframes load7 {
  0%,
  80%,
  100% {
    box-shadow: 0 2.5em 0 -1.3em;
  }
  40% {
    box-shadow: 0 2.5em 0 0;
  }
}

.show{
  visibility: visible;
}

.spanner, .overlay{
	opacity: 0;
	-webkit-transition: all 0.3s;
	-moz-transition: all 0.3s;
	transition: all 0.3s;
}

.spanner.show, .overlay.show {
	opacity: 1
}

</style>