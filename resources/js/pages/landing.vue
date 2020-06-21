<template>
    <div id="page-top">
        <!-- navigation-->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="#page-top" @click="scrollTo('#page-top')">CleanMeister</a><button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto my-2 my-lg-0">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#about" @click="scrollTo('#about')">About</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#features" @click="scrollTo('#features')">Features</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="#contact" @click="scrollTo('#contact')">Contact</a></li>

                        <li v-if="isguest" class="nav-item"><a class="nav-link js-scroll-trigger" v-bind:href="base_url + '/login'">Login</a></li>
                        <li v-if="isguest" class="nav-item"><a class="nav-link js-scroll-trigger" v-bind:href="base_url + '/register'">Register</a></li>
                        <li v-if="!isguest" class="nav-item"><a class="nav-link js-scroll-trigger" v-bind:href="base_url + '/home'">Home</a></li>
                        <li v-if="!isguest"class="nav-item dropdown">
                            <a id="navbarDropdown_2" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                {{ user_email }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown_2">

                                <a v-bind:href="base_url + '/page_profile'" class="dropdown-item">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                                <a v-bind:href="base_url + '/page_change_password'" class="dropdown-item">
                                    <i class="fas fa-lock"></i> Change Password
                                </a>
                                <a class="dropdown-item" v-bind:href="logout_action" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> logout
                                </a>

                                <form id="logout-form" v-bind:action="logout_action" method="POST" style="display: none;">
                                    <input type="hidden" name="_token" v-model="csrf">
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Masthead-->
        <header class="masthead">
            <div class="container h-100">
                <div class="row h-100 align-items-center justify-content-center text-center">
                    <div class="col-lg-10 align-self-end">
                        <h1 class="text-uppercase text-white font-weight-bold">Clean Meister</h1>
                        <hr class="divider my-4" />
                    </div>
                    <div class="col-lg-8 align-self-baseline">
                        <p class="text-white-75 font-weight-light mb-5">Welcome to the Clean Meister Platform, where households and businesses rely on us to provide honest and reliable domestic independent cleaners in their areas.</p>
                        <a class="btn btn-primary btn-xl js-scroll-trigger" href="#about" @click="scrollTo('#about')">Find Out More</a>
                    </div>
                </div>
            </div>
        </header>
        <!-- About-->
        <section class="page-section bg-primary" id="about">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h2 class="text-white mt-0">About Us</h2>
                        <hr class="divider light my-4" />
                        <p class="text-white-50 mb-4">Clean Meister is a platform for those seeking the best reliable, dependable and trustworthy cleaners who can deliver quality service. Also the platform for individuals and other cleaning companies, who want to take their businesses to the next level. You may also sign up on the website.</p>
                        <a class="btn btn-light btn-xl js-scroll-trigger" href="#features" @click="scrollTo('#features')">Get Started!</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Services-->
        <section class="page-section" id="features">
            <div class="container">
                <h2 class="text-center mt-0">Features</h2>
                <hr class="divider my-4" />
                <div class="row">
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa-gem text-primary mb-4" @click="navbarCollapse()"></i>
                            <h3 class="h4 mb-2">Quality Cleaning</h3>
                            <p class="text-muted mb-0">Clean Meister has provided you the easiest way to getting your cleaning services booked or signing up as a service provider</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa-thumbs-up text-primary mb-4"></i>
                            <h3 class="h4 mb-2">Customer Satisfaction</h3>
                            <p class="text-muted mb-0">Create your account and get the service you've always wanted in minutes.We service all area in Baguio City</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa fa-credit-card text-primary mb-4"></i>
                            <h3 class="h4 mb-2">Affordable Prices</h3>
                            <p class="text-muted mb-0">At Clean Meister, users are being matched and connected to Cleaning Services at affordable rates.You have many to choose from.The decision is all yours.</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 text-center">
                        <div class="mt-5">
                            <i class="fas fa-4x fa fa-calendar text-primary mb-4"></i>
                            <h3 class="h4 mb-2">Book A Service</h3>
                            <p class="text-muted mb-0">Booking is very easy. Once you  register on our website, follow the instructions that will guide you to making your booking.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Contact-->
        <section class="page-section" id="contact">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 text-center">
                        <h2 class="mt-0">Let's Get In Touch!</h2>
                        <hr class="divider my-4" />
                        <p class="text-muted mb-5">Ready to start your next project with us? Give us a call or send us an email and we will get back to you as soon as possible!</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 ml-auto text-center mb-5 mb-lg-0">
                        <i class="fas fa-phone fa-3x mb-3 text-muted"></i>
                        <div>(074)424-6293</div>
                    </div>
                    <div class="col-lg-4 mr-auto text-center">
                        <i class="fas fa-envelope fa-3x mb-3 text-muted"></i>
                        <!-- Make sure to change the email address in BOTH the anchor text and the link target below!-->
                        <a class="d-block" href="mailto:florendo.lovelykate@gmail.com">cleanmeister@gmail.com</a>
                    </div>
                </div>
            </div>
        </section>
        <!-- Footer-->
        <footer class="bg-light py-5">
            <div class="container"><div class="small text-center text-muted">Copyright © 2020 - Clean Meister</div></div>
        </footer>
    </div>
</template>
<style>
    
</style>
<script>

    var VueScrollTo = require('vue-scrollto');
    import moment from 'moment';
    Vue.use(VueScrollTo);
    // Activate scrollspy to add active class to navbar items on scroll
    $('body').scrollspy({
        target: '#mainNav',
        offset: 75
    });
    $(document).scroll(function(){
    });

    export default {
        props: ['base_url', 'isguest', 'logout_action', 'logout', 'user_email', 'csrf'],
        data(){
            return{
            }
        },
        methods:{
            scrollTo: function(elemID){
                VueScrollTo.scrollTo(elemID);
            },
            navbarCollapse: function(){
                if ($('#mainNav').offset().top > 100) {
                    $("#mainNav").addClass("navbar-scrolled");
                } else {
                    $("#mainNav").removeClass("navbar-scrolled");
                }
            }
        },
        created () {
            window.addEventListener('scroll', this.navbarCollapse);
        },
        destroyed () {
            window.removeEventListener('scroll', this.navbarCollapse);
        },
    }
</script>