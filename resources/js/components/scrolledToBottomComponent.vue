<template>
	<a id="back-to-top" v-bind:class="scrolledToBottom ? 'back-to-top-shown' : ''" v-show="scrolledToBottom" transition="fade" @click="scrollToTop()" class="btn btn-light btn-md back-to-top" role="button"><i class="fas fa-chevron-up"></i></a>
</template>
<style scoped>

	.back-to-top {
		background-color:#61408460 !important; 
		color: #FFFFFF;
	    position: fixed;
	    bottom: 25px;
	    right: 25px;
	    //display: none;

	    -webkit-transition: opacity 0.3s ease-in-out;
	    -moz-transition: opacity 0.3s ease-in-out;
	    -ms-transition: opacity 0.3s ease-in-out;
	    -o-transition: opacity 0.3s ease-in-out;
	     opacity: 0;
	}

	.back-to-top-shown{
	     opacity: 1;
	}
</style>
<script>
	var VueScrollTo = require('vue-scrollto');
    import moment from 'moment';
	Vue.use(VueScrollTo);

	export default {
	    components:{ VueScrollTo },
		data() {
			return{
            	scrolledToBottom: false,
			}
		},
		methods: {
		    scrollCheck () {
		      window.onscroll = () => {
		        //let bottomOfWindow = Math.max(window.pageYOffset, document.documentElement.scrollTop, document.body.scrollTop) + window.innerHeight === document.documentElement.offsetHeight;
		        let bottomOfWindow = $(window).scrollTop() > 150;
		        if (bottomOfWindow) {
		        	this.scrolledToBottom = true; // replace it with your code
		        }else{
		        	this.scrolledToBottom = false; // replace it with your code
		        }
		      }
		    },
			scrollToTop: function(){
				VueScrollTo.scrollTo($(this));
			},
		},
		mounted(){
			this.scrollCheck();
		},
        created () {
            window.addEventListener('scroll', this.scrollCheck);
        },
        destroyed () {
            window.removeEventListener('scroll', this.scrollCheck);
        },
	}
</script>