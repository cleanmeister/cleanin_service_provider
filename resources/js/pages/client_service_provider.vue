<template>
  <div class="mt-2">
      <div class="row">
          <div class="col-md-12">
              <div class="card card-default">
                  <div class="card-header">
                      <div class="row">
                          <div class="col-md-4">
                              <h3>Service Providers</h3>
                          </div>
                          <div class="col-md-4"></div>
                          <div class="col-md-4">
                              <div class="input-group">
                                  <input type="text" placeholder="Search..." class="form-control form-control-sm" @keyup="get_service_providers()" v-model="word">
                                  <div class="input-group-append">
                                      <button class="btn btn-sm btn-outline-secondary" @click="get_service_providers()" type="button"><i class="fa fa-search"></i></button>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="card-body" style="overflow: auto; padding: 0;">
                      <div class="table-responsive-sm">
                      </div>
                          <table class="table table-md table-hover table-striped">
                              <tbody>
                                  <tr v-if="service_providers.length == 0">
                                    <td class="text-center"><h5 class="text-muted"><i>No Data</i></h5></td>
                                  </tr>
                                  <tr v-for="(sp, index) in service_providers" :key="index" class="row" style="margin: 0;">
                                      <td class="col-md-3 text-center">
                                        <img :src="'img/service_providers/logos/'+sp.company_img" class="img-fluid" alt="img">
                                        <h3><strong>{{ sp.name }}</strong></h3>
                                        <hr>
                                            <div class="text-left">
                                              <b>Rating:</b>
                                                <span class="stars" v-html="starRating(sp.ratings.average, 5)" style="color: #DAA520;"></span>
                                            </div>
                                            <p class="container text-left" style="margin-bottom: 0;">{{ sp.ratings.average }} average based on {{ sp.ratings.totalRateCount }} reviews.</p>
                                        <hr>
                                        <button class="btn btn-primary" type="button" @click="book(sp.id)" style="width: 100%;"> Book </button>
                                      </td>
                                      <td class="col-md-9">
                                          <table class="table table-condensed table-striped">
                                            <tbody>
                                              <tr v-for="(s, index) in sp.services" :key="index" class="row" style="margin: 0;">
                                                  <td class="col-md-3"><strong><h4>{{ s[0].name }}</h4></strong></td>
                                                  <td class="col-md-6">{{ s[0].desc }}</td>
                                                  <td class="col-md-3">{{ s[0].price }}/{{ s[0].rate == 1 ? 'Per Hour' : 'per Day' }}</td>
                                              </tr>
                                            </tbody>
                                          </table>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                  </div>
              </div>
          </div>
      </div>
      <div class="modal fade" id="bookModal" tabindex="-1" role="dialog" aria-labelledby="bookModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookModalLabel">
                  <span>Book A Service</span>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form @submit.prevent="store_book()" name="client_booking_form" id="client_booking_form">
                <div class="modal-body">
                  <div class="form-group row">
                    <div class="text-left" :class="Cleaner_Name != '' ? 'col-md-1' : 'col-md-4'"></div>
                    <div class="" :class="isMobile() ? 'col-md-12' : 'col-md-4'">
                      <table class="table table-default" style="border: none !important;">
                        <tbody>
                          <tr class="row">
                            <td class="col-sm-12">
                              <label for="" class="form-label" style="margin: 0 !important;">Choose Service: <div class="spinner-border text-info" v-show="showLoadingServices == true" style="height: 20px; width: 20px; margin: 0 !important;"></div></label>
                              <select class="form-control form-control-sm" @change="get_cleaners()" v-model="form.service_id">
                                <option v-for="(s, index) in services" :key="index" :value="s.id">{{ s.name }} - {{ s.price }}/{{ s.rateStr }} - {{ s.desc | truncate( 25, '...')}}</option>
                              </select>
                            </td>
                          </tr>
                          <tr class="row">
                            <td class="col-sm-12">
                              <label for="" class="form-label" style="margin: 0 !important;">Choose Cleaner: <div class="spinner-border text-info" v-show="showLoadingCleaners == true" style="height: 20px; width: 20px; margin: 0 !important;"></div></label>
                              <select id="CleanerSelect" class="form-control form-control-sm" @change="get_schedules()" v-model="form.cleaner_id" :disabled="cleaners.length == 0">
                                <option value='0' style="display: none;"></option>
                                <option v-for="(c, index) in cleaners" :key="index" :value="c.id">{{ c.firstname }} {{ c.middlename }} {{ c.lastname }} - {{ c.ratings.rateAverage }}&#10030;</option>
                              </select>
                            </td>
                          </tr>
                          <tr class="row">
                            <td class="col-sm-12">
                              <label class="form-label">Choose Schedule: <div class="spinner-border text-info"  v-show="showLoadingSchedules == true" style="height: 20px; width: 20px; margin: 0 !important;"></div></label>
                              <select class="form-control form-control-sm"  v-model="form.schedule_id" :disabled="schedules.length == 0">
                                <option v-for="(s, index) in schedules" :key="index" :value="s.id" >
                                  {{ s.day_desc }}: {{ formatDate(s.schedule_date) }} {{ s.formated_start_time }} - {{ s.formated_end_time }}
                                </option>
                              </select>
                              <!-- <div class="colors text-center" :disabled="schedules.length == 0" v-if="!isMobile()">
                                <label class="btn btn-success" v-for="(s, index) in schedules" :class="{ 'active': form.schedule_id === s.id }" style="width: 100%; margin: 3px;">
                                  <h6 style="margin: 0;"><b><input type="radio" v-model="form.schedule_id"  name="omg" data-toggle="toggle" :value="s.id" style="display: none;">{{ s.day_desc }}: {{ s.formated_start_time }} - {{ s.formated_end_time }}</b></h6>
                                 <p style="margin: 0;"></p>
                                </label>
                              </div> -->
                            </td>
                          </tr>
                          <tr class="row">
                            <td class="col-sm-12">
                              <label for="" class="form-label">LandMark Address: </label>
                              <textarea rows="3" class="form-control form-control-sm" v-model="form.landmark_address"></textarea>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="table" :class="Cleaner_Name != '' ? 'col-md-6' : 'col-md-'">
                      <div class="row" v-show="Cleaner_Name != ''">
                        <div class="col-md-3 text-center">
                            <img :src="profile_picture_img" v-show="profile_picture_img != ''" style="width: 85px; height: 110px;object-fit: cover; border: thick solid #614084;">
                            <hr>
                        </div>
                        <div class="col-md-9">
                          <div class="row">
                              <p class="container" style="margin-bottom: 0;"><b>Name:</b> {{ Cleaner_Name }} 
                                <a class="btn btn-sm btn-primary" target="_blank" v-bind:href="base_url + '/messaging?email=' + Cleaner_Email">
                                    <i class="far fa-comment-dots"></i> Chat
                                </a>
                              </p>
                              <p class="container" style="margin-bottom: 0;"><b>Age: </b> {{ Cleaner_Age }}</p>
                              <p class="container" style="margin-bottom: 0;">
                              <label>
                                <b>Rating:</b>
                                <a href="#RatingBreakdown" class="" data-toggle="collapse">
                                  <span id="starRating" class="stars" data-num-stars="5" style="color: #DAA520;"></span>
                                </a>
                              </label>
                              <p class="container" style="margin-bottom: 0;">{{ ratings.rateAverage }} average based on {{ ratings.rateCtr }} reviews.</p>
                              <p class="container" style="margin-bottom: 0;" v-show="Cleaner_Restrictions != '' && Cleaner_Restrictions != null"><b>Restrictions: </b> {{ Cleaner_Restrictions }}</p>
                          </div>
                        </div>
                      </div>
                      <div class="row" v-show="Cleaner_Name != ''">
                        <div class="col-md-12">
                          <hr style="border:2px solid #f1f1f1; margin-bottom: 5px;">
                          <div class="collapse show" id="RatingBreakdown">
                            <div class="row" style="margin: 0px;">
                              <div class="side">
                                <div>5 star</div>
                              </div>
                              <div class="middle">
                                <div class="bar-container">
                                  <div class="bar-5"></div>
                                </div>
                              </div>
                              <div class="side right">
                                <div>{{ ratings.star5 }}</div>
                              </div>
                              <div class="side">
                                <div>4 star</div>
                              </div>
                              <div class="middle">
                                <div class="bar-container">
                                  <div class="bar-4"></div>
                                </div>
                              </div>
                              <div class="side right">
                                <div>{{ ratings.star4 }}</div>
                              </div>
                              <div class="side">
                                <div>3 star</div>
                              </div>
                              <div class="middle">
                                <div class="bar-container">
                                  <div class="bar-3"></div>
                                </div>
                              </div>
                              <div class="side right">
                                <div>{{ ratings.star3 }}</div>
                              </div>
                              <div class="side">
                                <div>2 star</div>
                              </div>
                              <div class="middle">
                                <div class="bar-container">
                                  <div class="bar-2"></div>
                                </div>
                              </div>
                              <div class="side right">
                                <div>{{ ratings.star2 }}</div>
                              </div>
                              <div class="side">
                                <div>1 star</div>
                              </div>
                              <div class="middle">
                                <div class="bar-container">
                                  <div class="bar-1"></div>
                                </div>
                              </div>
                              <div class="side right">
                                <div>{{ ratings.star1 }}</div>
                              </div>
                            </div>
                            <hr style="border:2px solid #f1f1f1; margin-top: 5px;">
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="":class="Cleaner_Name != '' ? 'col-md-1' : 'col-md-4'"></div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-sm btn-success">Submit</button>
                </div>
            </form>
            </div>
        </div>
      </div>
  </div>
</template>

<script>
$.fn.stars = function(value) {
  return $(this).each(function() {
      const rating = value;
      const numStars = $(this).data("numStars");
      const fullStar = '<i class="fas fa-star"></i>'.repeat(Math.floor(rating));
      const halfStar = (rating%1!== 0) ? '<i class="fas fa-star-half-alt"></i>': '';
      const noStar = '<i class="far fa-star"></i>'.repeat(Math.floor(numStars-rating));
      $(this).html(`${fullStar}${halfStar}${noStar}`);
  });
}

import moment from 'moment';
export default {
    props: ['base_url'],
    data(){
      return{
        services: [],
        cleaners: [],
        schedules: [],
        service_providers: [],
        word: '',
        form: {
          service_provider_id: '',
          service_id: '',
          cleaner_id: '',
          schedule_id: '',
          landmark_address: '',
        },
        reset_form: {
          service_provider_id: '',
          service_id: '',
          cleaner_id: '',
          schedule_id: '',
          landmark_address: '',
        },
        profile_picture_img: '',
        Cleaner_Name: '',
        Cleaner_Email: '',
        Cleaner_Age: 0,
        Cleaner_Restrictions: '',
        ratings: [],
        showLoadingServices: false,
        showLoadingCleaners: false,
        showLoadingSchedules: false,
      }
    },
    methods:{
        isMobile: function(){
          return (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));
        },
        starRating: function(rating, numStars){
            const fullStar = '<i class="fas fa-star"></i>'.repeat(Math.floor(rating));
            const halfStar = (rating%1!== 0) ? '<i class="fas fa-star-half-alt"></i>': '';
            const noStar = '<i class="far fa-star"></i>'.repeat(Math.floor(numStars-rating));
            return (`${fullStar}${halfStar}${noStar}`);
        },
        get_service_providers: function(){
            var Search ='';
            if(this.word.trim() != '') Search = this.base_url + '/service_provider/search/' + this.word;
            else Search = this.base_url + '/service_provider';
            axios.get(Search)
            .then(({data}) => {
                this.service_providers = data;
            }).catch(() => {
            });
        },
        get_services: function(id){
          this.showLoadingServices = true;
          axios.get(this.base_url + '/service_provider/'+this.form.service_provider_id).then(({data}) => {
            this.services = data.services;
            this.showLoadingServices = false;
          }).catch(() => {

          });
        },
        book: function(id){
          this.form.service_provider_id = '';
          this.form.service_id = '';
          this.form.cleaner_id = '';
          this.form.schedule_id = '';
          this.form.landmark_address = '';
          this.form.service_provider_id = id;
          this.services = [];
          this.cleaners = [];
          this.schedules = [];
          this.Ratings = [];
          this.Cleaner_Name = [];
          this.profile_picture_img = '';
          this.get_services();
          $('#bookModal').modal('show');
        },
        get_cleaners: function(){
          this.Cleaner_Name = '';
          this.showLoadingCleaners = true;
          this.cleaners = [];
          this.schedules = [];
          this.form.cleaner_id = 0;
          axios.get(this.base_url + '/service_cleaners/'+this.form.service_id)
          .then(({data}) => {
            this.cleaners = data;
          this.showLoadingCleaners = false;
          }).catch(() => {

          });
        },
        format_form: function(){
          this.form = this.reset_form;
        },
        get_schedules: function(){
          this.showLoadingSchedules = true;
          var the_ID = this.form.cleaner_id;
          var FullName = '';
          var email = '';
          var age = 0;
          var ProImage = '';
          var TheRatings = [];
          var restrictions = '';
          this.profile_picture_img = '';
          $.each(this.cleaners, function(i, item) {
              if(item.id == the_ID)
              {
                FullName = item.firstname + " " + item.middlename + " "  + item.lastname;
                ProImage = item.profile_pic;
                email = item.email;
                TheRatings = item.ratings;
                if(item.ratings.rateCtr == 0)TheRatings.rateAverage = 5;
                age = item.age;
                restrictions = item.cleaner_restrictions;
                return false;
              }
          });
          this.Cleaner_Name = FullName;
          this.Cleaner_Email = email;
          this.ratings = TheRatings;
          this.Cleaner_Age = age;
          this.Cleaner_Restrictions = restrictions;
          this.profile_picture_img = (ProImage != null && ProImage != '') ? this.base_url + "/img/profiles/" + ProImage : this.base_url + "/img/user-circle.png";
          $('#starRating').stars(TheRatings.rateAverage);

          $('div.bar-5').width(((TheRatings.star5 == 0 ? 0 : (TheRatings.star5/TheRatings.rateCtr))*100) + '%');
          $('div.bar-4').width(((TheRatings.star4 == 0 ? 0 : (TheRatings.star4/TheRatings.rateCtr))*100) + '%');
          $('div.bar-3').width(((TheRatings.star3 == 0 ? 0 : (TheRatings.star3/TheRatings.rateCtr))*100) + '%');
          $('div.bar-2').width(((TheRatings.star2 == 0 ? 0 : (TheRatings.star2/TheRatings.rateCtr))*100) + '%');
          $('div.bar-1').width(((TheRatings.star1 == 0 ? 0 : (TheRatings.star1/TheRatings.rateCtr))*100) + '%');

          axios.get(this.base_url + '/cleaner_schedules/'+this.form.cleaner_id+'/'+this.form.service_id)
          .then(({data}) => {
            if(data == ''){
              toast.fire({
                position: 'middle',
                icon: "warning",
                title: 'No Schedule Available',
              });
            }
            this.schedules = data;
            this.showLoadingSchedules = false;
          }).catch(() => {

          });
        },
        
        store_book: function(){
          axios.post(this.base_url + '/store_book', {
              schedule_id: this.form.schedule_id,
              landmark_address: this.form.landmark_address,
          }).then(res => {
            this.format_form;
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
            $('#bookModal').modal('hide');
            $("#client_booking_form")[0].reset();
            this.get_service_providers();
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
        formatDate: function(date){
            return moment(date).format('MMMM D');
        },

    },
    created(){
        this.get_service_providers();
    },
    mounted(){
    }
}
</script>

<style>

/* Three column layout */
.side {
  text-align: center;
  width: 10%;
  margin-top:4px;
  vertical-align: top;
}

.middle {
  margin-top:4px;
  float: left;
  width: 85%;
}

/* Place text to the right */
.right {
  width: 5%;
  text-align: center;
  padding-left: 5px;
  vertical-align: middle;
}

/* Clear floats after the columns
.row:after {
  content: "";
  display: table;
  clear: both;
} */
.row{
  margin-left: 15px;
  margin-right: 15px; 
  vertical-align: middle;
}

/* The bar container */
.bar-container {
  width: 100%;
  background-color: #f1f1f1;
  text-align: center;
  color: white;
}

/* Individual bars */
.bar-5 { width: 60%; height: 18px; background-color: #4CAF50; }
.bar-4 { width: 30%; height: 18px; background-color: #2196F3; }
.bar-3 { width: 10%; height: 18px; background-color: #00bcd4; }
.bar-2 { width: 40%; height: 18px; background-color: #ff9800; }
.bar-1 { width: 15%; height: 18px; background-color: #f44336; }

/* Responsive layout - make the columns stack on top of each other instead of next to each other */
@media (max-width: 400px) {
  .side, .middle {
    width: 100%;
  }
  .right {
    display: none;
  }
}
</style>