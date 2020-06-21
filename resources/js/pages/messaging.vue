<template>
	<div class="container-fluid h-100 wrapper">
		<div class="row justify-content-center h-100">
			<div class="col-md-4 col-xl-4 chat">
				<div class="card mb-sm-3 mb-md-0 contacts_card">
					<div class="card-header">
						<div style="padding: 0.75rem;">
							<span style="font-size: 30px; font-weight: bold; color: white;">Messages</span>
							<span class="float-right add-contact" @click="add_contact()"><i class="fas fa-plus"></i></span>
						</div>
						<div class="input-group">
							<input type="text" placeholder="Search..." @keyup="get_contacts()" v-model="contactSearch" class="form-control search">
							<div class="input-group-prepend">
								<span class="input-group-text search_btn" @click="get_contacts()"><i class="fas fa-search"></i></span>
							</div>
						</div>
					</div>
					<div class="card-body contacts_body" style="max-height: 400px; overflow-y: auto;">

						<div v-if='LoadingContacts' class="text-center" style="color: #FFF;">
							<div class="spinner-border" role="status">
								<span class="sr-only">Loading...</span>
							</div>
						</div>

						<p v-if="contacts.length <= 0 && !LoadingContacts" class="text-center" style="width: 100%; color: #C0C0C0;">
							{{ contactSearch == '' ? 'No contacts' : 'Not found!'}}
							
						</p>
						<ul v-else="contacts.length > 0" class="contacts">
							<li v-for="(contact, index) in contacts" :key="index" @click="(select_user(contact), get_messages())" v-bind:class=" selected_user.contact_id == contact.contact_id ? 'active' : ''">
								<div class="d-flex bd-highlight">
									<div class="img_cont">
										<img v-bind:src="contact.profile_pic ? base_url+'/img/profiles/' +contact.profile_pic : base_url+'/img/user-circle.png'" @error="$event.target.src = base_url + '/img/user-circle.png'" class="rounded-circle user_img">
										<!-- <span class="online_icon"></span> -->
										<!-- <span class="online_icon offline"></span> -->
									</div>
									<div class="user_info">
										<span>{{ contact.fullname }}</span>
										<p v-if="contact.lastmessage != null" style="white-space: pre-wrap;">{{ decodeHtml(contact.lastmessage.message) }} &bull; {{ contact.lastmessage.date }}</p>
										<p v-else="contact.lastmessage == null">No message</p>
									</div>
								</div>
							</li>
						</ul>

					</div>
					<div class="card-footer"></div>
				</div>
			</div>
			<div class="col-md-8 col-xl-6 chat">
				<div class="card" id="msg_card_container">
					<div class="card-header msg_head">
						<div class="row" v-if="selected_user.length <= 0">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<div class="d-flex bd-highlight">
									<div  class="input-group">
										<input type="text" placeholder="Search..." v-model="emailcheck" @keyup.enter="check_email()" class="form-control search">
										<div class="input-group-prepend" style="border-left: 1px solid black;">
											<span class="input-group-text search_btn" @click="check_email()">Check</span>
										</div>
									</div>
								</div>
								<div style="max-height: 210px; overflow-y: auto;">
									<p v-if="emailcheckError != ''" class="text-center text-danger" style="width: 100%; margin: 0;">{{ emailcheckError }}</p>
									<div v-if="checkedEmailResult && searchNewContact.length <= 0" class="d-flex bd-highlight">
										<div class="img_cont">
											<img v-show="checkedEmailResult.fullname" v-bind:src="checkedEmailResult.profile_pic ? base_url+'/img/profiles/' + checkedEmailResult.profile_pic : base_url+'/img/user-circle.png'" @error="$event.target.src = base_url + '/img/user-circle.png'" class="rounded-circle user_img">
											<!-- <span class="online_icon offline"></span> -->
										</div>
											
										<div class="user_info">
											<span>{{ checkedEmailResult.fullname ? 'Chat with ' + checkedEmailResult.fullname : '' }}</span>
											<p style="margin: 0px !important;">{{ checkedEmailResult.email ? checkedEmailResult.email : ''}}</p>
											<!-- <p>1767 Messages</p> -->
										</div>
									</div>
									<div v-if="searchNewContact.length > 0"  v-for="(m,index) in searchNewContact" :key="m.count" class="d-flex bd-highlight" style="cursor: pointer;" @click="chatNewContact(m)">
										<div class="img_cont">
											<img v-show="m.fullname" v-bind:src="m.profile_pic ? base_url+'/img/profiles/' + m.profile_pic : base_url+'/img/user-circle.png'" @error="$event.target.src = base_url + '/img/user-circle.png'" class="rounded-circle user_img">
											<!-- <span class="online_icon offline"></span> -->
										</div>
											
										<div class="user_info">
											<span>{{ m.fullname ? 'Chat with ' + m.fullname : '' }}</span>
											<p style="margin: 0px !important;">{{ m.email ? m.email : ''}}</p>
											<!-- <p>1767 Messages</p> -->
										</div>
									</div>
								</div>

							</div>
							<div class="col-md-2"></div>
						</div>
						<div v-else="selected_user.length > 0 " class="d-flex bd-highlight">

							<div class="img_cont">
								<img v-show="selected_user.fullname" v-bind:src="selected_user.profile_pic ? base_url+'/img/profiles/' + selected_user.profile_pic : base_url+'/img/user-circle.png'" @error="$event.target.src = base_url + '/img/user-circle.png'" class="rounded-circle user_img">
								<!-- <span class="online_icon offline"></span> -->
							</div>
							<div class="user_info">
								<span>{{ selected_user.fullname ? 'Chat with ' + selected_user.fullname : '' }}</span>
								<p style="margin: 0px !important;">{{ selected_user.email ? selected_user.email : ''}}</p>
								<!-- <p>1767 Messages</p> -->
							</div>
							<!-- <div class="video_cam">
								<span><i class="fas fa-video"></i></span>
								<span><i class="fas fa-phone"></i></span>
							</div> -->
						</div>
						<!-- <span id="action_menu_btn"><i class="fas fa-ellipsis-v"></i></span>
						<div class="action_menu">
							<ul>
								<li><i class="fas fa-user-circle"></i> View profile</li>
								<li><i class="fas fa-users"></i> Add to close friends</li>
								<li><i class="fas fa-plus"></i> Add to group</li>
								<li><i class="fas fa-ban"></i> Block</li>
							</ul>
						</div> -->
					</div>
					<div class="card-body msg_card_body" @scroll="onScroll" ref="msgContainer">

						<div v-if='LoadingMessages' class="text-center" style="color: #FFF;">
							<div class="spinner-border" role="status">
								<span class="sr-only">Loading...</span>
							</div>
						</div>

						<div id="prevMessageContainer">
							<p v-if="messages.length <= 0 && !LoadingMessages" class="text-center" style="width: 100%; color: #C0C0C0;">No messages</p>
							<div v-else="messages.length > 0" class="d-flex mb-4" v-for="(m,index) in messages" :key="m.count" v-bind:class="m.sender_id == user_id ? 'justify-content-end' : 'justify-content-start'">
								<div v-if="m.sender_id != user_id" class="img_cont_msg">
									<img v-bind:src="selected_user.profile_pic ? base_url+'/img/profiles/' + selected_user.profile_pic : base_url+'/img/user-circle.png'" @error="$event.target.src = base_url + '/img/user-circle.png'" class="rounded-circle user_img_msg">
								</div>
								<div v-bind:class="m.sender_id == user_id ? 'msg_cotainer_send' : 'msg_cotainer'">
									<div v-html="decodeHtml(m.text_message)" style="white-space: pre-wrap;"></div>
									<span v-bind:class="m.sender_id == user_id ? 'msg_time_send' : 'msg_time'">{{ m.created_at | moment('hh:mm A') }} | {{ date(m.created_at) }}</span>
								</div>
								<div v-if="m.sender_id == user_id" class="img_cont_msg">
									<img v-bind:src="m.profile_pic ? '/img/profiles/' + m.profile_pic : base_url+'/img/user-circle.png'" @error="$event.target.src = base_url + '/img/user-circle.png'" class="rounded-circle user_img_msg">
								</div>
							</div>
						</div>

					</div>
					<div class="card-footer">
						<div class="input-group">
							<div class="input-group-append">
								<span class="input-group-text attach_btn"><!-- <i class="fas fa-paperclip"></i> --></span>
							</div>
							<textarea name="" v-model="message"
                                @keydown.enter.exact.prevent
                                @keyup.enter.exact="send_message()"
                                @keydown.enter.shift.exact="newline()" class="form-control type_msg" placeholder="Type your message..."></textarea>
							<div class="input-group-append">
								<span class="input-group-text send_btn" @click="send_message()"><i class="fas fa-location-arrow"></i></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	$(document).ready(function(){
		$('#action_menu_btn').click(function(){
			$('.action_menu').toggle();
		});
	});

	var VueScrollTo = require('vue-scrollto');
    import moment from 'moment';
	Vue.use(VueScrollTo);
	
	export default {
		props: ['email_request', 'base_url'],
	    components:{ VueScrollTo },
		data() {
			return{
				LoadingContacts: false,
				LoadingMessages: false,

                selected_user: [],
				contacts: [],
				messages: [],
				message: '',
				user_id: '',
				profile_pic: '',

				contactSearch: '',
				emailcheck: '',
				emailcheckError: '',
				checkedEmailResult: [],
				count: 0,
				scrollTopper: 0,
				addedHeight: 0,

				prev_message_ctr: 0,
				next_message_ctr: 0,

				searchNewContact: [],

            	timer: '',
			}
		},
		watch: {
			count: function() {
				this.$nextTick(function() {
					var container = this.$refs.msgContainer;
					container.scrollTop = container.scrollHeight + 120;
				});
			}, 
			scrollTopper: function(){
				this.$nextTick(function() {
					var container = this.$refs.msgContainer;
					container.scrollTop = this.addedHeight;
				});

			}
		},

	    beforeDestroy () {
	    	clearInterval(this.timer)
	    },
		methods: {
	        cancelAutoUpdate () { clearInterval(this.timer) },
            newline: function(){ this.message = this.message; },
            date: function (date) { return moment(date).format('MMMM D, YYYY'); },
            decodeHtml: function (html) {
				var txt = document.createElement("textarea");
				txt.innerHTML = html;
				return txt.value;
			},
            findUserFromContacts: function(email){
			    var toReturn; 
			    var i = 0;
			    var tmp = this.contacts
			    $.each(this.contacts, function(key, val) {
			        if(val.email == email) {
			            toReturn = tmp[i];
			            return false;
			        }
			        i++;
			    }); 
			    return toReturn;
            },

			onScroll ({ target: { scrollTop, clientHeight, scrollHeight }}) {
				if (scrollTop <= 0) {
					this.get_previous_message();
				}
			},

			add_contact: function(){
				this.selected_user = [];
				this.messages = [];
                //this.ready = true;
                var options = {
				    container: '#container',
				    easing: 'ease-in',
				    offset: -60,
				    force: true,
				    cancelable: true,
				    onStart: function(element) {
				      // scrolling started
				    },
				    onDone: function(element) {
				      // scrolling is done
				    },
				    onCancel: function() {
				      // scrolling has been interrupted
				    },
				    x: false,
				    y: true
				}
                VueScrollTo.scrollTo('#msg_card_container');
			},
            get_user: function() {
                axios.get(this.base_url + '/get_user_id')
                .then(data => {
                    this.user_id = data.data.id;
                    this.profile_pic = data.data.profile_pic;
                }).catch(() => {

                });
            },
            get_contacts: function() {
            	this.contacts = [];
            	this.LoadingContacts = true;
            	this.selected_user = [];
            	var tmp = [];
                axios.get(this.base_url + '/contact/'+this.contactSearch)
                .then(({ data }) => {
                    this.contacts = data;
                    tmp = this.findUserFromContacts(this.emailcheck);
                    if(this.contacts.length > 0){
                    	if(this.email_request){
                    			this.selected_user = typeof  tmp !== 'undefined' ? tmp: [];
                    	}else{
                    		this.selected_user = this.contacts[0];
                    	}
                    	this.LoadingMessages = true;
                    	this.get_messages();
                    }
            		this.LoadingContacts = false;
                }).catch(() => {

                });
            },
            get_messages: function() {
                this.messages = [];
                if(Object.keys(this.selected_user).length != 0){
	            	//this.LoadingMessages = true;
	                axios.get(this.base_url + '/get_message/' + this.selected_user.contact_id)
	                .then(({ data }) => {
                		this.LoadingMessages = false;
                		if(data){
		                	this.prev_message_ctr = data[0].count;
		                    this.messages = data.slice().reverse();
		                	this.prev_message_ctr = this.messages.length;
		            		this.count++;
                		}
	                }).catch(() => {

	                });
                }
            },
            get_previous_message: function(){

	            this.LoadingMessages = true;
            	/*alert(this.prev_message_ctr);*/
            	$('#prevMessageContainer').scrollTop = 2;
                axios.get(this.base_url + '/get_prev_message/' + this.selected_user.contact_id+ '/'+ this.prev_message_ctr)
                .then(data => {
                	var SelectedUser = this.selected_user;
                	var user_id = this.user_id;
                	var addedHeight = 0;
                	this.prev_message_ctr += data.data.length;
                	var homeURL = this.base_url;
                	$.each(data.data, function(key, m){
                		var SenderData = (m.sender_id != user_id) ? SelectedUser : m;
		            	var options = {
		            		templateClass: m.sender_id == user_id ? 'justify-content-end' : 'justify-content-start',
		            		imgContainerClass: 'img_cont_msg',
		            		imgClass: 'rounded-circle user_img_msg',
		            		imgPos: SenderData.sender_id != user_id ? 'left' : 'right',
		            		messageContainerClass: m.sender_id == user_id ? 'msg_cotainer_send' : 'msg_cotainer',
		            		dateTimeClass: m.sender_id == user_id ? 'msg_time_send' : 'msg_time',

		            		imgSrc: SenderData.profile_pic ? homeURL+'/img/profiles/' + SenderData.profile_pic : homeURL+'/img/user-circle.png',
		            		message: m.text_message,
		            		dateTimetxt: m.created_at,
		            	};
		            	var template = $("<div class='d-flex mb-4'></div>").addClass(options.templateClass);
		        		var imgContainer = $("<div></div>").addClass(options.imgContainerClass);

		        		var img = $("<img src='/img/user-circle.png'>").attr('src', options.imgSrc).addClass(options.imgClass);
		        		imgContainer.append(img);

		        		var messageContainer = $("<div></div>").addClass(options.messageContainerClass);
		        		var mess = $("<div style='white-space: pre-wrap;'></div>").html(options.message);
		        		messageContainer.append(mess);
		        		var time = $("<span></span>").addClass(options.dateTimeClass).text(moment(options.dateTimetxt).format('hh:mm A | MMMM D, YYYY'));
		        		messageContainer.append(time);

		        		template.append(messageContainer);
		        		if(options.imgPos == 'left') template.prepend(imgContainer);
		        		else template.append(imgContainer);

		        		$('#prevMessageContainer').prepend(template);
						addedHeight += template.height();
                	});
                	this.addedHeight = (addedHeight *1.5);
                	this.scrollTopper++;
                	//alert(addedHeight);
                	this.LoadingMessages = false;
                }).catch(res => {
                });
            },
            get_new_message: function(){
            	if(typeof this.selected_user.contact_id !== 'undefined'){
					axios.get(this.base_url + '/get_new_message/'+this.selected_user.contact_id)
					.then(data => {
	                	var SelectedUser = this.selected_user;
	                	var user_id = this.user_id;
                		var homeURL = this.base_url;
	                	$.each(data.data, function(key, m){
	                		var SenderData = (m.sender_id != user_id) ? SelectedUser : m;
			            	var options = {
			            		templateClass: m.sender_id == user_id ? 'justify-content-end' : 'justify-content-start',
			            		imgContainerClass: 'img_cont_msg',
			            		imgClass: 'rounded-circle user_img_msg',
			            		imgPos: SenderData.sender_id != user_id ? 'left' : 'right',
			            		messageContainerClass: m.sender_id == user_id ? 'msg_cotainer_send' : 'msg_cotainer',
			            		dateTimeClass: m.sender_id == user_id ? 'msg_time_send' : 'msg_time',

			            		imgSrc: SenderData.profile_pic ? homeURL+'/img/profiles/' + SenderData.profile_pic : homeURL+'/img/user-circle.png',
			            		message: m.text_message,
			            		dateTimetxt: m.created_at,
			            	};
			            	var template = $("<div class='d-flex mb-4'></div>").addClass(options.templateClass);
			        		var imgContainer = $("<div></div>").addClass(options.imgContainerClass);

			        		var img = $("<img src='/img/user-circle.png'>").attr('src', options.imgSrc).addClass(options.imgClass);
			        		imgContainer.append(img);

			        		var messageContainer = $("<div></div>").addClass(options.messageContainerClass);
			        		var mess = $("<div style='white-space: pre-wrap;'></div>").text(options.message);
			        		messageContainer.append(mess);
			        		var time = $("<span></span>").addClass(options.dateTimeClass).text(moment(options.dateTimetxt).format('hh:mm A | MMMM D, YYYY'));
			        		messageContainer.append(time);

			        		template.append(messageContainer);
			        		if(options.imgPos == 'left') template.prepend(imgContainer);
			        		else template.append(imgContainer);

			        		$('#prevMessageContainer').append(template);
	                	});
	                	if(data.data.length > 0) this.count++;
					}).catch(() => {

					});
            	}else{
            		
            		this.LoadingMessages = false;
            	}
            },

            messageTemplate: function(option){
            	var homeURL = this.base_url;
            	var options = {
            		templateClass: 'justify-content-end',//'justify-content-end' : 'justify-content-start'
            		imgContainerClass: 'img_cont_msg',
            		imgClass: 'rounded-circle user_img_msg',
            		imgPos: 'left', // left or right
            		messageContainerClass: 'msg_cotainer_send', //'msg_cotainer_send' or 'msg_cotainer'
            		dateTimeClass: 'msg_time_send',//'msg_time_send' or 'msg_time'

            		imgSrc: this.base_url + homeURL+'/img/user-circle.png',
            		message: '',
            		dateTimetxt: '',

            		prevMessageContainer: $('#prevMessageContainer'),

            	};

            	var template = $("<div class='d-flex mb-4'></div>").addClass(options.templateClass);
        		var imgContainer = $("<div></div>").addClass(options.imgContainerClass);
			        		
        		var img = $("<img src='/img/user-circle.png'>").attr('src', options.imgSrc).addClass(options.imgClass);
        		imgContainer.append(img);

        		var messageContainer = $("<div></div>").addClass(options.messageContainerClass);
        		var mess = $("<div style='white-space: pre-wrap;'></div>").text(options.message);
        		messageContainer.append(mess);
        		var time = $("<span></span>").addClass(options.dateTimeClass).text(moment(options.dateTimetxt).format('hh:mm A | MMMM D, YYYY'));
        		messageContainer.append(time);

        		template.append(messageContainer);
        		if(options.imgPos == 'left') template.prepend(imgContainer);
        		else template.append(imgContainer);

        		options.prevMessageContainer.prepend($template);
            },

            send_message: function() {
            	if(this.message != '' ){
            		var userID = this.selected_user.contact_id;
            		var newContact = false;
            		var homeURL = this.base_url;
            		if(typeof this.checkedEmailResult.contact_id !== 'undefined'){
            			userID = this.checkedEmailResult.contact_id;
            			newContact = true;
            		}
	                axios.post(this.base_url + '/send_message/' + this.selected_user.contact_id, { text_message: this.message, })
	                .then(data => {
		            	var options = {
		            		templateClass: 'justify-content-end',
		            		imgContainerClass: 'img_cont_msg',
		            		imgClass: 'rounded-circle user_img_msg',
		            		messageContainerClass: 'msg_cotainer_send',
		            		dateTimeClass: 'msg_time_send',

		            		imgSrc: this.profile_pic ? homeURL+'/img/profiles/' + this.profile_pic : homeURL+'/img/user-circle.png',
		            		message: this.message,
		            		dateTimetxt: data.data.time,
		            	};
	            		if(newContact){
	            			//this.emailcheck = 
	            			//this.get_contacts();
	            			this.contacts.push(this.checkedEmailResult);
	            		}
		            	var template = $("<div class='d-flex mb-4'></div>").addClass(options.templateClass);
		        		var imgContainer = $("<div></div>").addClass(options.imgContainerClass);
			        		
		        		var img = $("<img src='/img/user-circle.png'>").attr('src', options.imgSrc).addClass(options.imgClass);
		        		imgContainer.append(img);

		        		var messageContainer = $("<div></div>").addClass(options.messageContainerClass);
		        		var mess = $("<div style='white-space: pre-wrap;'></div>").text(options.message);
		        		messageContainer.append(mess);
		        		var time = $("<span></span>").addClass(options.dateTimeClass).text(moment(options.dateTimetxt).format('hh:mm A | MMMM D, YYYY'));
		        		messageContainer.append(time);

		        		template.append(messageContainer);
		        		template.append(imgContainer);

		        		$('#prevMessageContainer').append(template);
	                    this.message = '';
	                	this.count++;

	                }).catch(() => {

	                });

            	}
            },
            select_user: function(user) {
                //this.ready = true;
                $("#prevMessageContainer").empty();
                var options = {
				    container: '#container',
				    easing: 'ease-in',
				    offset: -60,
				    force: true,
				    cancelable: true,
				    onStart: function(element) {
				      // scrolling started
				    },
				    onDone: function(element) {
				      // scrolling is done
				    },
				    onCancel: function() {
				      // scrolling has been interrupted
				    },
				    x: false,
				    y: true
				}

                this.selected_user = user;
                this.LoadingMessages = true;

                VueScrollTo.scrollTo('#msg_card_container');
                //this will allow users to unselect current contact and clear message box ui
                //this will also open adding new contact via email
                /*if(this.selected_user.contact_id != user.contact_id){
                	this.selected_user = user;
                }else{
                	this.selected_user = [];
                	this.messages = [];
                }*/

                //this.get_messages();
            },

            validEmail: function (email) {
                var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },
            check_email: function(){
            	this.emailcheckError = '';
            	this.checkedEmailResult = [];

            	if(this.validEmail(this.emailcheck)){
	            	var tmp = this.findUserFromContacts(this.emailcheck);
	            	if(typeof tmp !== 'undefined'){
		            	this.selected_user = tmp;
		            	this.LoadingMessages = true;
		            	this.get_messages();
	            	}else if(this.emailcheck !='' && !this.findUserFromContacts(this.emailcheck)){
		                axios.get(this.base_url + '/check_email/' + this.emailcheck)
		                .then( data  => {
		                	if(data.data.length <= 0) this.emailcheckError = 'User not found!';
		                	else if(typeof data.data.fullname !== 'undefined' && data.data.result != 1){
				                	this.checkedEmailResult = data.data;
				            		this.selected_user = data.data;
		                	}
		            		this.LoadingMessages = false;
		                }).catch(error => {

		                	//this.emailcheckError = error.response.data.email[0];
		                });
		            }
            	}else{
	                axios.get(this.base_url + '/check_email/' + this.emailcheck)
	                .then( data  => {
	                	if(data.data.length <= 0) this.emailcheckError = 'User not found!';
	                	else if(data.data.length > 0){
	                		this.searchNewContact = data.data;
	                	}
	            		this.LoadingMessages = false;
	                }).catch(error => {
	                });
            	}
            },
            chatNewContact: function(data){
            	this.checkedEmailResult = data;
            	this.selected_user = data;
		            	this.get_messages();
            }
		},
		created(){

            this.get_contacts();
            this.get_user();
            if(this.email_request != '')
            {
            	this.emailcheck = this.email_request;
            	this.check_email();
            }
        	this.timer = setInterval(this.get_new_message, 3000)
		}
	}
</script>

<style scoped>
	.add-contact{
		color: #fff;
		font-size: 24px;
		cursor: pointer;
	}
	.add-contact:hover{
		color: #eee;
	}
	.chat{
		margin-top: 2%;
		margin-bottom: none;
	}
	.card{
		min-height: 500px;
		border-radius: 15px !important;
		background-color: rgba(0,0,0,0.4) !important;
	}
	.contacts_body{
		padding:  0.75rem 0 !important;
		overflow-y: auto;
		white-space: nowrap;
	}
	.msg_card_body{
		overflow-y: auto;
	}
	.card-header{
		border-radius: 15px 15px 0 0 !important;
		border-bottom: 0 !important;
	}
	.card-footer{
		border-radius: 0 0 15px 15px !important;
		border-top: 0 !important;
	}
	.container{
		align-content: center;
	}
	.search{
		border-radius: 15px 0 0 15px !important;
		background-color: rgba(0,0,0,0.3) !important;
		border:0 !important;
		color:white !important;
	}
	.search:focus{
		box-shadow:none !important;
		outline:0px !important;
	}
	.type_msg{
		background-color: rgba(0,0,0,0.3) !important;
		border:0 !important;
		color:white !important;
		height: 60px !important;
		overflow-y: auto;
	}
	.type_msg:focus{
		box-shadow:none !important;
		outline:0px !important;
	}
	.attach_btn{
		border-radius: 15px 0 0 15px !important;
		background-color: rgba(0,0,0,0.3) !important;
		border:0 !important;
		color: white !important;
		cursor: pointer;
	}
	.send_btn{
		border-radius: 0 15px 15px 0 !important;
		background-color: rgba(0,0,0,0.3) !important;
		border:0 !important;
		color: white !important;
		cursor: pointer;
	}

	.search_btn:hover{
		color: #C0C0C0 !important;
	}
	.search_btn{
		border-radius: 0 15px 15px 0 !important;
		background-color: rgba(0,0,0,0.3) !important;
		border:0 !important;
		color: white !important;
		cursor: pointer;
	}
	.contacts{
		list-style: none;
		padding: 0;
	}
	.contacts li{
		width: 100% !important;
		padding: 5px 10px;
		/*margin-bottom: 15px !important;*/
		cursor: pointer;
	}
	.contacts li:hover{
		background-color: rgba(0,0,0,0.1);
	}
	.active{
		background-color: rgba(0,0,0,0.3);
	}
	.user_img{
		height: 70px;
		width: 70px;
		border:1.5px solid #f5f6fa;
	}
	.img_cont{
		position: relative;
		height: 70px;
		width: 70px;
	}
	.online_icon{
		position: absolute;
		height: 15px;
		width:15px;
		background-color: #4cd137;
		border-radius: 50%;
		bottom: 0.2em;
		right: 0.4em;
		border:1.5px solid white;
	}
	.offline{
		background-color: #c23616 !important;
	}
	.user_info{
		margin-top: auto;
		margin-bottom: auto;
		margin-left: 15px;
	}
	.user_info span{
		font-size: 20px;
		color: white;
	}
	.user_info p{
		font-size: 10px;
		color: rgba(255,255,255,0.6);
	}
	.video_cam{
		margin-left: 50px;
		margin-top: 5px;
	}
	.video_cam span{
		color: white;
		font-size: 20px;
		cursor: pointer;
		margin-right: 20px;
	}
	.msg_head{
		position: relative;
	}
	#action_menu_btn{
		position: absolute;
		right: 10px;
		top: 10px;
		color: white;
		cursor: pointer;
		font-size: 20px;
	}
	.action_menu{
		z-index: 1;
		position: absolute;
		padding: 15px 0;
		background-color: rgba(0,0,0,0.5);
		color: white;
		border-radius: 15px;
		top: 30px;
		right: 15px;
		display: none;
	}
	.action_menu ul{
		list-style: none;
		padding: 0;
		margin: 0;
	}
	.action_menu ul li{
		width: 100%;
		padding: 10px 15px;
		margin-bottom: 5px;
	}
	.action_menu ul li i{
		padding-right: 10px;
	
	}
	.action_menu ul li:hover{
		cursor: pointer;
		background-color: rgba(0,0,0,0.2);
	}
	@media(max-width: 576px){
		.contacts_card{
			margin-bottom: 15px !important;
		}
	}
</style>
<style>
	.chat-wrapper{
		background: #7F7FD5;
		background: -webkit-linear-gradient(to right, #91EAE4, #86A8E7, #7F7FD5);
		background: linear-gradient(to right, #91EAE4, #86A8E7, #7F7FD5);
	}

	.user_img_msg{
		height: 40px;
		width: 40px;
		border:1.5px solid #f5f6fa;
	}
	.img_cont_msg{
		height: 40px;
		width: 40px;
	}
	.msg_cotainer{
		margin-top: auto;
		margin-bottom: auto;
		margin-left: 10px;
		border-radius: 25px;
		background-color: #82ccdd;
		padding: 10px;
		position: relative;
	}
	.msg_cotainer_send{
		margin-top: auto;
		margin-bottom: auto;
		margin-right: 10px;
		border-radius: 25px;
		background-color: #78e08f;
		padding: 10px;
		position: relative;
	}
	.msg_time{
		position: absolute;
		left: 0;
		bottom: -15px;
		color: rgba(255,255,255,0.5);
		font-size: 10px;
		min-width: 150px;
	}
	.msg_time_send{
		position: absolute;
		right:0;
		bottom: -15px;
		color: rgba(255,255,255,0.5);
		font-size: 10px;
		min-width: 150px;
		text-align: right;
	}
</style>