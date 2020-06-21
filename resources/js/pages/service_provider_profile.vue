<template>

    <div class="container" id="app" style="margin-top: 15px;">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="card mb-3" v-bind:class=" declinedrequest ? 'border-danger' : 'border-warning' " >
                    <div class="card-header">
                        {{ declinedrequest ? 'Request Denied' : 'Request Pending' }}
                    </div>
                    <div class="card-body">
                        <p v-if="declinedrequest" class="text-danger">
                            We regret to inform you that your request to add your business to our platform have been denied. After reviewing your request, we have determined that it would not be possible to accommodate your request at this time. 
                            <br/><br/>
                            You can <a href="#" @click="resetForm" data-toggle="modal" data-target="#exampleModal"> Click here </a> to submit another request.

                        </p>
                        <p v-else="!declinedrequest">
                            Your request to add your business is pending for approval. Please wait for our administrator to accept your request.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-dark">
                        <h5 class="modal-title" id="exampleModalLabel">Service Provider Request Form</h5>
                        <a class="close" data-dismiss="modal" aria-label="Close" style="cursor: pointer;">
                            <span aria-hidden="true">&times;</span>
                        </a>
                    </div>
                    <div class="modal-body login-card-body">

                        <div v-show="message != ''" class="form-group row">
                            <div class="col-md-12">
                                <div v-bind:class="returnsError ? 'alert-danger' : 'alert-success'" class="alert">
                                    <strong>{{ returnsError ? 'Failed!' : 'Success!' }}</strong><p v-html="message"></p>
                                </div>
                            </div>
                        </div>
                        <form @submit="register" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="_token" :value="csrf">
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
                            <div style="overflow:auto;">
                                <div style="float:right;">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            <div class="spanner" v-bind:class="showLoader ? 'show' : ''">
                                <div class="loader"></div>
                                <p style="background-color: #28282888">Your request is being submitted, please be patient...</p>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import ImageUploader from 'vue-image-upload-resize';

    Vue.use(ImageUploader);
export default {
    props: ['declinedrequest', 'base_url'],
    data(){
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

            logohasImage: false,
            attachementhasImage: false,
            showLoader: false,
            message: '',
            returnsError: false,
            type: 0,

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
        }
    },
    methods:{
        resetForm: function(){
            this.logohasImage = false;
            this.attachementhasImage = false;
            this.spform.name = '';
            this.spform.contact_person = '';
            this.spform.mobile_number = '';
            this.spform.business_permit_no = '';
            this.spform.address = '';
            this.spform.company_img = '';
            this.spform.attachmentimage = '';
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
        setLogoImage: function(output) {
            this.logohasImage = true;
            this.spform.company_img = output['dataUrl'];
        },
        setattachementImage: function(output) {
            this.attachementhasImage = true;
            this.spform.attachmentimage = output['dataUrl'];
        },
        register: function(e){
            e.preventDefault();

            let config = {
                header : {
                    'Content-Type' : 'multipart/form-data'
                }
            }
            if(this.validateSP()){
                this.disable = true;
                this.showLoader = true;
                axios.post(this.base_url + '/send_business_request', {
                    _token: this.csrf,
                    userprofile: this.form,
                    servicep: this.spform,
                    profile_pic: this.image,
                }, config).then(res => {
                    this.message = res.data.msg
                    this.disable = false;
                    this.showLoader = false;
                    this.returnsError = false;
                    window.location = "/home"
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
        }
    },
}
</script>

<style scoped>
</style>