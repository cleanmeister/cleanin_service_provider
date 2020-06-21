<template>
	<li class="nav-item">
        <a class="nav-link" v-bind:href="this.base_url+'/messaging'">
          Messages <i class="far fa-comments"></i>
          <span v-if="messages > 0" class="badge badge-danger navbar-badge">{{ messages > 99 ? messages+'+' : messages }}</span>
        </a>

      </li>
</template>
<script>
	export default {
		props: ['base_url'],
		data(){
			return{
				messages: [],
            	timer: ''
			}
		},
		methods:{
			get_new_messages(){
				axios.get(this.base_url+'/new_user_messages').then(({data}) => {
					this.messages = data;
				}).catch(() => {

				});
			},
	        cancelAutoUpdate () { clearInterval(this.timer) }
		},
		created(){
			this.get_new_messages();
        	this.timer = setInterval(this.get_new_messages, 3000);
		},
	    beforeDestroy () {
	    	clearInterval(this.timer)
	    },
		mounted(){

		}
	}
</script>