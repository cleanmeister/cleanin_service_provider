<template>
    <div class="wrapper" style="padding: 2%;">
        <div v-if="!editmode" class="card" style="max-width: 550px; margin-left: auto; margin-right: auto; border-color: #614084;">
            <div class="card-header bg-dark">
                <h2 class="card-title" style="margin: 0;">Profile</h2>
                <h2 class="card-title float-right" style="margin: 0; cursor: pointer;" @click="enter_edit_mode()"><i class="far fa-edit"></i></h2>
            </div>
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" :src="profile_form.profile_pic ? profile_form.profile_pic : base_url + '/img/user-circle.png'" @error="$event.target.src = imageError()" alt="User profile picture">
                </div>

                <h3 class="profile-username text-center">{{ profile_form.firstname }} {{ profile_form.middlename | truncate(1, '.') }} {{ profile_form.lastname }}</h3>

                <p class="text-muted text-center">{{ account_form.username }}</p>
                <div class=" list-group-unbordered">
                    <div class="list-group-item" style="padding-right: 15px; padding-left: 15px;">
                        <b>First Name</b> <a class="float-right">{{ profile_form.firstname }}</a>
                    </div>
                    <div class="list-group-item" style="padding-right: 15px; padding-left: 15px;">
                        <b>Middle Name</b> <a class="float-right">{{ profile_form.middlename }}</a>
                    </div>
                    <div class="list-group-item" style="padding-right: 15px; padding-left: 15px;">
                        <b>Last Name</b> <a class="float-right">{{ profile_form.lastname }}</a>
                    </div>
                    <div class="list-group-item" style="padding-right: 15px; padding-left: 15px;">
                        <b>Date of Birth</b> <a class="float-right">{{ profile_form.date_of_birth }}</a>
                    </div>
                    <div class="list-group-item" style="padding-right: 15px; padding-left: 15px;">
                        <b>Email</b> <a class="float-right">{{ account_form.email }}</a>
                    </div>
                    <div class="list-group-item" style="padding-right: 15px; padding-left: 15px;">
                        <b>Mobile No.</b> <a class="float-right">{{ profile_form.mobile_number }}</a>
                    </div>
                    <div class="list-group-item" style="padding-right: 15px; padding-left: 15px;">
                        <b>Address</b> <a class="float-right">{{ profile_form.address }}</a>
                    </div>
                    <div v-if="type == 3" class="list-group-item" style="padding-right: 15px; padding-left: 15px;">
                        <b>Cleaner Restrictions</b> <a class="float-right">{{ profile_form.restrictions }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div v-else="editmode" class="card" style="max-width: 550px; margin-left: auto; margin-right: auto; border-color: #614084;">
            <div class="card-header p-0 pt-1 bg-dark">
                <h2 class="card-title float-right" style="margin-right: 15px; margin-top: 10px; cursor: pointer;" @click="cancel_edit()"><i class="fa fa-times"></i></h2>
                <ul class="nav nav-tabs" id="custom-tabs-two-tab" role="tablist" style="border-bottom: none !important;">
                    <li class="pt-2 px-3"><h3 class="card-title">Update Information</h3></li>
                    <li class="nav-item">
                        <a class="nav-link bg-dark active" id="custom-tabs-two-home-tab" data-toggle="pill" href="#custom-tabs-two-home" role="tab" aria-controls="custom-tabs-two-home" aria-selected="true">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link bg-dark" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-profile" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Account</a>
                    </li>
                    <li class="nav-item" v-if="type == 2">
                        <a class="nav-link bg-dark" @click="get_sp()" id="custom-tabs-two-profile-tab" data-toggle="pill" href="#custom-tabs-two-business" role="tab" aria-controls="custom-tabs-two-profile" aria-selected="false">Business Profile</a>
                    </li>
                </ul>
            </div>
            <div class="card-body login-card-body">
                <div class="tab-content" id="custom-tabs-two-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-two-home" role="tabpanel" aria-labelledby="custom-tabs-two-home-tab">

                        <!-- <div class="form-group row">
                            <label for="profile_pic" class="col-md-4 col-form-label text-md-right">Profile Pic</label>
                            <div class="col-md-6">
                                <input type="file" name="profile_pic" class="form-control">
                            </div>
                        </div> -->

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
                                            <img :src="profile_form.profile_pic ? profile_form.profile_pic : base_url + '/img/user-circle.png'" v-show="hasImage" class="img-preview rounded mx-auto" alt="Profile Image" style="width: 100px; height: 120px; display: block;">
                                            <span class="upload-caption">{{ hasImage ? "Click to Replace" : "Click to upload" }}</span>
                                        </label>

                                    </image-uploader>
                                </div>
                                <p class="err-loc text-danger"><strong>{{ profile_errors.profile_pic }}</strong></p>
                            </div>
                            <div class="col-md-6">
                            <div class="col-md-12" style="">
                                <label for="firstname">Firstname</label>
                                <input id="firstname" type="text" v-model="profile_form.firstname" v-bind:class="profile_errors.firstname == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="firstname" autocomplete="firstname">
                                <p class="err-loc text-danger"><strong>{{ profile_errors.firstname }}</strong></p>
                            </div>
                            <div class="col-md-12" style="">
                                <label for="middlename">Middlename</label>
                                <input id="middlename" type="text" v-model="profile_form.middlename" v-bind:class="profile_errors.middlename == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="middlename" autocomplete="middlename">
                                <p class="err-loc text-danger"><strong>{{ profile_errors.middlename }}</strong></p>
                            </div>
                            <div class="col-md-12" style="">
                                <label for="lastname">Lastname</label>
                                <input id="lastname" type="text" v-model="profile_form.lastname" v-bind:class="profile_errors.lastname == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="lastname" autocomplete="lastname">
                                <p class="err-loc text-danger"><strong>{{ profile_errors.lastname }}</strong></p>
                            </div>
                                
                            </div>
                        </div>

                        <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <label for="date_of_birth">Date of Birth</label>
                                <!-- <input id="date_of_birth" type="date" class="form-control form-control-sm is-invalid" name="date_of_birth" value="" required autocomplete="date_of_birth"> -->

                                <div class="input-group">
                                    <datepicker :disabled-dates="DatepickerSettings.disabledDates"
                                        v-model="profile_form.date_of_birth" typeable  :bootstrap-styling="true"
                                        input-class="form-control form-control-sm"
                                        placeholder="" format="D, MMM d, yyyy">

                                        <div slot="afterDateInput" id="datepickericon" class="input-group-append">
                                            <div class="input-group-text">
                                                <i class="fas fa-calendar"></i>
                                            </div>
                                        </div>
                                    </datepicker>
                                </div>
                                <p class="err-loc text-danger"><strong>{{ profile_errors.date_of_birth }}</strong></p>
                            </div>
                            <div class="col-md-6">
                                <label for="mobile_number">Mobile Number</label>
                                <div class="input-group">
                                    <input id="mobile_number" type="text" v-model="profile_form.mobile_number" v-bind:class="profile_errors.mobile_number == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="mobile_number" value="" autocomplete="mobile_number">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-mobile-alt"></i>
                                        </div>
                                    </div>
                                </div>

                                <p class="err-loc text-danger"><strong>{{ profile_errors.mobile_number }}</strong></p>
                            </div>
                        </div>

                        <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
                            <div class="col-md-12">
                                <label for="address">Address</label>
                                <div class="input-group">
                                    <textarea id="address" type="text"v-model="profile_form.address" v-bind:class="profile_errors.address == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="address" value="" autocomplete="address" style="min-height: 29px; max-height: 120px; height: 29px;"></textarea> 
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="err-loc text-danger"><strong>{{ profile_errors.address }}</strong></p>
                            </div>
                        </div>
                        <div v-if="type == 3" class="form-group row justify-content-center" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
                            <div class="col-md-12">
                                <label>Cleaner Restrictions</label>
                                <div class="input-group">
                                    <textarea id="spaddress" type="text" placeholder="Address" v-model="profile_form.restrictions" v-bind:class="profile_errors.restrictions == '' ? '' : 'is-invalid'" class="form-control form-control-sm" autocomplete="spaddress" style="min-height: 29px; max-height: 120px; height: 57px;"></textarea> 
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="err-loc text-danger"><strong>{{ profile_errors.restrictions }}</strong></p>
                            </div>
                        </div>

                        <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-top: 10px; margin-bottom: 0;">
                            <div class="col-md-12 text-center">
                                <button type="button" @click="update_profile()" class="btn btn-primary btn-flat bg-dark" style="min-width: 100px;">
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-two-profile" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                
                        <div class="form-group row justify-content-center" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <label for="username" class="">Username</label>
                                <div class="input-group">
                                    <input id="username" type="username" v-model="account_form.username" v-bind:class="account_errors.username == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="username" autocomplete="username">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="err-loc text-danger"><strong>{{ account_errors.username }}</strong></p>
                            </div>
                        </div>

                        <div class="form-group row justify-content-center" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <label for="email">E-Mail</label>
                                <div class="input-group">
                                    <input id="email" type="text" v-model="account_form.email" v-bind:class="account_errors.email == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="email" value="" autocomplete="email">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="err-loc text-danger"><strong>{{ account_errors.email }}</strong></p>
                            </div>
                        </div>

                        <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-top: 10px; margin-bottom: 0;">
                            <div class="col-md-12 text-center">
                                <button type="button" @click="update_account()" class="btn btn-primary btn-sm btn-flat bg-dark" style="min-width: 100px;">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group row justify-content-center" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <label for="password">Old Password</label>
                                <div class="input-group">
                                    <input id="password" type="password" v-model="password_form.old_password" v-bind:class="password_errors.old_password == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="password" autocomplete="off">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-user-lock"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="err-loc text-danger"><strong>{{ password_errors.old_password }}</strong></p>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <label for="password-confirm">New Password</label>
                                <div class="input-group">
                                    <input id="new_password" type="password"v-model="password_form.new_password" v-bind:class="password_errors.new_password == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="password_confirmation" autocomplete="off">
                                    <div slot="afterDateInput" class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-lock-open"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="err-loc text-danger"><strong>{{ password_errors.new_password }}</strong></p>
                            </div>
                        </div>
                        <div class="form-group row justify-content-center" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
                            <div class="col-md-6">
                                <label for="password-confirm">Confirm Password</label>
                                <div class="input-group">
                                    <input id="password_confirm" type="password"v-model="password_form.confirm_password" v-bind:class="password_errors.confirm_password == '' ? '' : 'is-invalid'" class="form-control form-control-sm" name="password_confirmation" autocomplete="off">
                                    <div slot="afterDateInput" class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    </div>
                                </div>
                                <p class="err-loc text-danger"><strong>{{ password_errors.confirm_password }}</strong></p>
                            </div>
                        </div>
                        <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-top: 10px; margin-bottom: 0;">
                            <div class="col-md-12 text-center">
                                <button type="button" @click="update_password()" class="btn btn-primary btn-sm btn-flat bg-dark" style="min-width: 100px;">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-if="type == 2" class="tab-pane fade" id="custom-tabs-two-business" role="tabpanel" aria-labelledby="custom-tabs-two-profile-tab">
                        <div class="form-group row justify-content-center" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
                            <div class="col-md-4 text-center">
                                <label for="firstname float-left">Company Logo</label>
                                <br>
                                <img :src="base_url + '/img/service_providers/logos/' + business_form.company_img" class="img-fluid">
                                <hr>
                                <div v-if="business_form.permit_img != '' && business_form.permit_img != null">
                                    <label for="firstname float-left">Attachments</label>
                                    <br>
                                    <img :src="base_url + '/img/service_providers/permits/' + business_form.permit_img" class="img-fluid">
                                </div>
                                <hr>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group row justify-content-center" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
                                    <div class="col-md-12">
                                        <label>Company Name</label>
                                        <div class="input-group">
                                            <input id="password" type="text" disabled="" placeholder="Company Name" v-model="business_form.name" v-bind:class="business_errors.name == '' ? '' : 'is-invalid'" class="form-control form-control-sm" autocomplete="off">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <i class="fas fa-building"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="err-loc text-danger"><strong>{{ business_errors.name }}</strong></p>
                                    </div>
                                </div>
                                <div class="form-group row justify-content-center" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
                                    <div class="col-md-12">
                                        <label>Contact Person</label>
                                        <div class="input-group">
                                            <input id="password" type="text" placeholder="Contact Person" v-model="business_form.contact_person" v-bind:class="business_errors.contact_person == '' ? '' : 'is-invalid'" class="form-control form-control-sm" autocomplete="off">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <i class="fas fa-id-card-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="err-loc text-danger"><strong>{{ business_errors.contact_person }}</strong></p>
                                    </div>
                                </div>
                                <div class="form-group row justify-content-center" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
                                    <div class="col-md-12">
                                        <label>Contact No.</label>
                                        <div class="input-group">
                                            <input id="password" type="text" placeholder="Contact No." v-model="business_form.contact_no" v-bind:class="business_errors.contact_no == '' ? '' : 'is-invalid'" class="form-control form-control-sm" autocomplete="off">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <i class="fas fa-phone"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="err-loc text-danger"><strong>{{ business_errors.contact_no }}</strong></p>
                                    </div>
                                </div>

                                <div class="form-group row justify-content-center" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
                                    <div class="col-md-12">
                                        <label>Business Permit No.</label>
                                        <div class="input-group">
                                            <input id="password" type="text" disabled="" placeholder="Business Permit No." v-model="business_form.business_permit_no" v-bind:class="business_errors.business_permit_no == '' ? '' : 'is-invalid'" class="form-control form-control-sm" autocomplete="off">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <i class="fas fa-sticky-note"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="err-loc text-danger"><strong>{{ business_errors.business_permit_no }}</strong></p>
                                    </div>
                                </div>
                                <div class="form-group row justify-content-center" v-bind:class="showLoader ? 'ablur' : ''" style="margin-bottom: 0;">
                                    <div class="col-md-12">
                                        <label>Address</label>
                                        <div class="input-group">
                                            <textarea id="spaddress" type="text" placeholder="Address" v-model="business_form.address" v-bind:class="business_errors.address == '' ? '' : 'is-invalid'" class="form-control form-control-sm" autocomplete="spaddress" style="min-height: 29px; max-height: 120px; height: 29px;"></textarea> 
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <i class="fas fa-map-marker-alt"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="err-loc text-danger"><strong>{{ business_errors.address }}</strong></p>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="form-group row" v-bind:class="showLoader ? 'ablur' : ''" style="margin-top: 10px; margin-bottom: 0;">
                            <div class="col-md-12 text-center">
                                <button type="button" @click="update_business()" class="btn btn-primary btn-sm btn-flat bg-dark" style="min-width: 100px;">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="spanner" v-bind:class="showLoader ? 'show' : ''">
                    <div class="loader"></div>
                    <p style="background-color: #28282888">Updating your account, please be patient.</p>
                </div>
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
        components:{ datepicker, ImageUploader },
        data(){
            return{
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

                hasImage: false,
                image: null,
                type: 0,

                profile_errors:{
                    firstname: '',
                    middlename: '',
                    lastname: '',
                    date_of_birth: '',
                    mobile_number: '',
                    address: '',
                    restrictions: '',
                    profile_pic: ''
                },

                profile_form:{
                    user_id: '',
                    firstname: '',
                    middlename: '',
                    lastname: '',
                    date_of_birth: '',
                    mobile_number: '',
                    address: '',
                    restrictions: '',
                    profile_pic: null
                },

                account_form: {
                    username: '',
                    email: '',
                },

                account_errors: {
                    username: '',
                    email: '',
                },

                password_form: {
                    old_password: '',
                    new_password: '',
                    confirm_password: '',
                },

                password_errors:{
                    old_password: '',
                    new_password: '',
                    confirm_password: '',
                },

                business_form: {
                    name: '',
                    contact_person: '',
                    contact_no: '',
                    address: '',

                    business_permit_no: '',
                    company_img: '',
                    permit_img:''

                },
                business_errors: {
                    name: '',
                    contact_person: '',
                    contact_no: '',
                    address: '',

                    business_permit_no: '',
                    company_img: '',
                    permit_img:''
                },



                showLoader: false,
                message: '',
                returnsError: false,

                imageData: '', 
                DatepickerSettings: {
                    disabledDates:{
                        from: new Date(),
                        to: new Date(1900, 12)
                    }
                },

                editmode: false,
            }
        },
        methods:{
            get_profile: function(){
                axios.get(this.base_url + '/profile').then(({data}) => {
                    this.account_form.email = data["email"];
                    this.account_form.username = data["username"];

                    this.profile_form.user_id = data['user_id'];
                    this.profile_form.firstname = data['firstname'];
                    this.profile_form.middlename = data['middlename'];
                    this.profile_form.lastname = data['lastname'];
                    this.profile_form.date_of_birth = data['date_of_birth'];
                    this.profile_form.mobile_number = data['mobile_number'];
                    this.profile_form.address = data['address'];
                    this.type = data['type'];
                    this.profile_form.restrictions = data['restrictions'];
                    if(data['type'] == 2) this.get_sp();
                    this.profile_form.profile_pic = this.base_url + '/img/user-circle.png';
                    if(data["profile_pic"] !== null){
                        this.profile_form.profile_pic = this.base_url + '/img/profiles/' + data["profile_pic"];
                        this.hasImage = true;
                        var imager = this.setImage;
                        this.convertImgToBase64(this.profile_form.profile_pic, function(base64Img){
                            var output = {dataUrl: base64Img};
                            imager(output);
                        });
                    }

                }).catch(() => { });
            },
            get_sp: function(){
                axios.get(this.base_url + '/get_sp')
                .then(({data}) => {
                    $.extend(this.business_form, data);
                }).catch(() => { });
            },
            enter_edit_mode: function(){
                this.editmode = true;
            },
            cancel_edit: function(){
                this.editmode = false;
                this.get_profile();
            },
            validate_profile: function(){
                var ret = true;
                this.profile_errors.firstname = '';
                this.profile_errors.middlename = '';
                this.profile_errors.lastname = '';
                this.profile_errors.date_of_birth = '';
                this.profile_errors.mobile_number = '';
                this.profile_errors.address = '';

                $("#datepickericon").parent().find('input').removeClass('is-invalid');

                if(!this.profile_form.firstname){
                    this.profile_errors.firstname = "Please Enter Your Firstname.";
                    ret = false;
                }

                if(!this.profile_form.middlename){
                    this.profile_errors.middlename = "Please Enter Your Middlename.";
                    ret = false;
                }

                if(!this.profile_form.lastname){
                    this.profile_errors.lastname = "Please Enter Your Lastname.";
                    ret = false;
                }

                if(!this.profile_form.mobile_number){
                    this.profile_errors.mobile_number = "Please enter your Mobile Number.";
                    ret = false;
                }
                if(!this.profile_form.address){
                    this.profile_errors.address = "Please enter your address.";
                    ret = false;
                }
                if(this.type == 3 && !this.profile_form.restrictions){
                    this.profile_errors.restrictions = "Please enter your restrictions as a cleaner.";
                    ret = false;
                }

                if(!this.profile_form.date_of_birth){
                    $("#datepickericon").parent().find('input').addClass('is-invalid');
                    this.profile_errors.date_of_birth = "Please enter your date of birth.";
                    ret = false;
                }

                return ret;
            },
            update_profile: function(){

                if(this.validate_profile()){
                    this.disable = true;
                    this.showLoader = true;
                    let config = {
                        header : {
                            'Content-Type' : 'multipart/form-data'
                        }
                    }
                    if(this.profile_form.profile_pic == this.base_url + '/img/user-circle.png') this.profile_form.profile_pic = null;
                    axios.post(this.base_url + '/profile/'+this.profile_form.user_id, this.profile_form, config)
                    .then(res => {
                        this.message = res.data.message;
                        this.disable = false;
                        this.showLoader = false;
                        this.returnsError = false;
                        if(res.data.code == 200){
                            toast.fire({
                                position: 'middle',
                                icon: "success",
                                title: res.data.msg,
                            });
                        }
                        this.cancel_edit();
                    }).catch(error => {

                        this.message = error.response.data.message;
                        this.disable = false;
                        this.showLoader = false;
                        this.returnsError = true;
                        var tmp = [];
                        $.each(error.response.data.errors, function(key, val){
                            tmp[key] = val;
                        });
                        $.extend(this.profile_errors, tmp);

                        // if (error.response.status != 200){
                            //     var ErrorUI = '<div><h2><b>' + error.response.data.message + '</b></h2>';
                            //     $.each(error.response.data.errors, function (key, value){
                            //         ErrorUI += '<h5>' + value[0].replace(key.replace('_', ' '), '<b class="text text-danger">'+$.camelCase("-" + key).replace('_', ' ')+'</b>') + '</h5>';
                            //     });
                            //         ErrorUI += '</div>';
                            //         toast.fire({
                            //         position: 'middle',
                            //         icon: "warning",
                            //         title: ErrorUI,
                            //     });
                        // }

                    });
                }
            },
            validate_account: function(){
                var ret = true;
                this.account_errors.email = '';
                this.account_errors.username = '';

                if(!this.account_form.username){
                    this.account_errors.username = "Please Enter Your username.";
                    ret = false;
                }

                if(!this.validEmail(this.account_form.email)){
                    this.account_errors.email = "Please enter a valid email address.";
                    ret = false;
                }

                if(!this.account_form.email){
                    this.account_errors.email = "Please enter your email address.";
                    ret = false;
                }

                return ret;
            },
            update_account: function(){

                if(this.validate_account()){
                    this.message = '';
                    this.disable = true;
                    this.showLoader = true;
                    this.returnsError = false;
                    axios.post(this.base_url + '/update_account', this.account_form)
                    .then(res => {
                        this.message = res.data.message;
                        this.disable = false;
                        this.showLoader = false;
                        this.returnsError = false;
                        if(res.data.code == 200){
                            toast.fire({
                                position: 'middle',
                                icon: "success",
                                title: res.data.msg,
                            });

                            toast.fire({
                                position: 'middle',
                                icon: "success",
                                title: '<div><h3><b>' + res.data.msg + '</b></h3>',
                                html: res.data.message,
                                timer: 0,
                            });
                        }
                        this.cancel_edit();
                    }).catch(error => {

                        this.message = error.response.data.message;
                        this.disable = false;
                        this.showLoader = false;
                        this.returnsError = true;
                        var tmp = [];
                        $.each(error.response.data.errors, function(key, val){
                            tmp[key] = val;
                        });
                        $.extend(this.errors, tmp);

                        // if (error.response.status != 200){
                            //     var ErrorUI = '<div><h2><b>' + error.response.data.message + '</b></h2>';
                            //     $.each(error.response.data.errors, function (key, value){
                            //         ErrorUI += '<h5>' + value[0].replace(key.replace('_', ' '), '<b class="text text-danger">'+$.camelCase("-" + key).replace('_', ' ')+'</b>') + '</h5>';
                            //     });
                            //         ErrorUI += '</div>';
                            //         toast.fire({
                            //         position: 'middle',
                            //         icon: "warning",
                            //         title: ErrorUI,
                            //     });
                        // }

                    });
                }
            },
            validate_passwords: function(){
                var ret = true;
                this.password_errors.old_password = '';
                this.password_errors.new_password = '';
                this.password_errors.confirm_password = '';

                if(!this.password_form.old_password){
                    this.password_errors.old_password = "Please Enter Your current password.";
                    ret = false;
                }
                if(this.password_form.confirm_password != this.password_form.new_password){
                    this.password_errors.new_password = "Your new password do not match.";
                    ret = false;
                }

                if(!this.password_form.new_password){
                    this.password_errors.new_password = "Please enter your new password.";
                    ret = false;
                }

                if(!this.password_form.confirm_password){
                    this.password_errors.confirm_password = "Please enter confirm your new password.";
                    ret = false;
                }
                return ret;
            },
            update_password: function() {
                if (this.validate_passwords()) {
                    axios.post(this.base_url + '/change_password', this.password_form)
                    .then(res => {
                        this.message = '';
                        this.disable = true;
                        this.showLoader = true;
                        this.returnsError = false;
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
                        this.password_form.new_password = '';
                        this.password_form.confirm_password = '';
                        this.password_form.old_password = '';

                        this.password_errors.new_password = '';
                        this.password_errors.confirm_password = '';
                        this.password_errors.old_password = '';
                        window.location = this.base_url + "/login";
                    }).catch(error => {

                        if (error.response.status != 200){
                            var ErrorUI = '<div><h2><b>' + error.response.data.message + '</b></h2>';
                            $.each(error.response.data.errors, function (key, value){
                                $.each(value, function (a, b){
                                    ErrorUI += '<h5>' + b.replace(key.replace('_', ' '), '<b class="text text-danger">'+$.camelCase("-" + key).replace('_', ' ')+'</b>') + '</h5>';

                                });
                            });
                            ErrorUI += '</div>';
                            toast.fire({
                                position: 'middle',
                                icon: "warning",
                                title: ErrorUI,
                            });

                            this.message = error.response.data.message;
                            this.disable = false;
                            this.showLoader = false;
                            this.returnsError = true;
                        }
                    });
                } else {
                    this.error = true;
                }
            },
            validate_business: function(){
                var ret = true;

                this.business_errors.name = '';
                this.business_errors.contact_person = '';
                this.business_errors.contact_no = '';
                this.business_errors.address = '';

                if(!this.business_form.name){
                    this.business_errors.name = "Business/Company name is required.";
                    ret = false;
                }

                if(!this.business_form.contact_person){
                    this.business_errors.contact_person = "Please enter the contact person for your business.";
                    ret = false;
                }

                if(!this.business_form.contact_no){
                    this.business_errors.contact_no = "Please enter the contact number of your business.";
                    ret = false;
                }

                if(!this.business_form.address){
                    this.business_errors.address = "Please enter your business address.";
                    ret = false;
                }

                return ret;
            },
            update_business: function(){
                if(this.validate_business()){
                    axios.post(this.base_url + '/update_sp', this.business_form)
                    .then(res => {
                        this.message = '';
                        this.disable = true;
                        this.showLoader = false;
                        this.returnsError = false;
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

                        this.business_errors.name = '';
                        this.business_errors.contact_person = '';
                        this.business_errors.contact_no = '';
                        this.business_errors.address = '';
                    }).catch(error => {

                        if (error.response.status != 200){
                            var ErrorUI = '<div><h2><b>' + error.response.data.message + '</b></h2>';
                            $.each(error.response.data.errors, function (key, value){
                                $.each(value, function (a, b){
                                    ErrorUI += '<h5>' + b.replace(key.replace('_', ' '), '<b class="text text-danger">'+$.camelCase("-" + key).replace('_', ' ')+'</b>') + '</h5>';

                                });
                            });
                            ErrorUI += '</div>';
                            toast.fire({
                                position: 'middle',
                                icon: "warning",
                                title: ErrorUI,
                            });

                            this.message = error.response.data.message;
                            this.disable = false;
                            this.showLoader = false;
                            this.returnsError = true;
                        }
                    });
                }
            },

            setImage: function(output) {
                this.hasImage = true;
                this.profile_form.profile_pic = output['dataUrl'];
            },
            /**
            * convertImgToBase64
            * @param  {String}   url
            * @param  {Function} callback
            * @param  {String}   [outputFormat='image/png']
            * @author HaNdTriX
            * @example
                convertImgToBase64('http://goo.gl/AOxHAL', function(base64Img){
                    console.log('IMAGE:',base64Img);
                })
            */
            convertImgToBase64: function(url, callback, outputFormat){
                var canvas = document.createElement('CANVAS');
                var ctx = canvas.getContext('2d');
                var img = new Image;
                img.crossOrigin = 'Anonymous';
                img.onload = function(){
                    canvas.height = img.height;
                    canvas.width = img.width;
                    ctx.drawImage(img,0,0);
                    var dataURL = canvas.toDataURL(outputFormat || 'image/png');
                    callback.call(this, dataURL);
                    // Clean up
                    canvas = null; 
                };
                img.src = url;
            },

            validEmail: function (email) {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            imageError(){
            	this.profile_form.profile_pic = null;
            	return this.base_url+'/img/user-circle.png';
            }
        },
        created(){
            this.get_profile();
        }
}
</script>

<style scoped>
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
    .vdp-datepicker{
        width: 100%;
    }
    #fileInput {
        display: none;
    }
    .ablur{
        filter: blur(10px);
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