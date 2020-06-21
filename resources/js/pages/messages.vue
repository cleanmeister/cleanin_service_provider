<template>
    <div class="container mt-2">
        <div class="row">
            <div class="col-12">
            </div>
            <div class="col-12">
                <h3 class=" text-center">Messaging</h3>
                <div class="messaging">
                    <div class="inbox_msg">
                        <div class="inbox_people">
                            <div class="headind_srch" style="min-height: 63px;">
                                <div class="recent_heading">
                                    <h4>Recent</h4>
                                </div>
                                <div class="srch_bar">
                                        <!---->
                                    <div class="stylish-input-group">
                                        <input type="text" class="search-bar" placeholder="Search">
                                        <span class="input-group-addon">
                                            <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                                        </span>
                                    </div>

                                </div>
                            </div>
                            <div class="inbox_chat">
                                <div class="chat_list" style="cursor: pointer;" v-for="(contact,index) in contacts" :key="index" @click="(select_user(contact),view_message(contact.contact_id))" v-show="contact.contact_id !== user_id">
                                    <div class="chat_people">
                                        <div class="chat_img">
                                            <img class="rounded-circle" :src="contact.profile_pic ? '/img/profiles/' +contact.profile_pic : '/img/user-circle.png'" alt="img" width="40px" height="40px">
                                        </div>
                                        <div class="chat_ib">
                                            <h5>{{ contact.fullname }}<span class="chat_date">{{ contact.lastmessage.date }}</span></h5>
                                            <p>{{ contact.lastmessage.message }}</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="mesgs">

                            <div class="msg_history">

                                <div v-for="(m,index) in messages" :key="index" v-bind:class="(m.sender_id == selected_user.contact_id) ? 'incoming_msg' : 'outgoing_msg'">
                                    <div v-if="m.sender_id == selected_user.contact_id" v-bind:class="m.sender_id == selected_user.contact_id ? 'incoming_msg_img' : ''">
                                        <img class="rounded-circle" :src="m.sender_profile_pic ? '/img/profiles/' + m.sender_profile_pic : '/img/user-circle.png'" alt="img" width="40px" height="40px">
                                    </div>
                                    <div v-bind:class="(m.sender_id == selected_user.contact_id) ? 'received_msg' : 'sent_msg'">
                                        <div :class="{ 'received_withd_msg': m.sender_id == selected_user.contact_id }">
                                            <p>{{ m.text_message }}</p>
                                            <span class="time_date">{{ m.created_at | moment('hh:mm A')  }} | {{ date(m.created_at) }}</span>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="type_msg">
                                <div class="input_msg_write">
                                    <form @submit.prevent="send_message()">
                                        <input type="text" v-model="message" class="write_msg" placeholder="Type a message" />
                                        <button class="msg_send_btn" type="submit"><i class="fas fa-paper-plane"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .inbox_people {
        background: #f8f8f8 none repeat scroll 0 0;
        float: left;
        overflow: hidden;
        width: 40%;
        border-right: 1px solid #c4c4c4;
    }

    .inbox_msg {
        border: 1px solid #c4c4c4;
        clear: both;
        overflow: hidden;
    }

    .top_spac {
        margin: 20px 0 0;
    }


    .recent_heading {
        float: left;
        width: 40%;
    }

    .srch_bar {
        display: inline-block;
        text-align: right;
        width: 60%;
        padding:5px;
    }

    .headind_srch {
        padding: 10px 29px 10px 20px;
        overflow: hidden;
        border-bottom: 1px solid #c4c4c4;
    }

    .recent_heading h4 {
        color: #05728f;
        font-size: 21px;
        margin: auto;
    }

    .srch_bar input {
        border: 1px solid #cdcdcd;
        border-width: 0 0 1px 0;
        width: 80%;
        padding: 2px 0 4px 6px;
        background: none;
    }

    .srch_bar .input-group-addon button {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: medium none;
        padding: 0;
        color: #707070;
        font-size: 18px;
    }

    .srch_bar .input-group-addon {
        margin: 0 0 0 -27px;
    }

    .chat_ib h5 {
        font-size: 15px;
        color: #464646;
        margin: 0 0 8px 0;
    }

    .chat_ib h5 span {
        font-size: 13px;
        float: right;
    }

    .chat_ib p {
        font-size: 14px;
        color: #989898;
        margin: auto
    }

    .chat_img {
        float: left;
        width: 11%;
    }

    .chat_ib {
        float: left;
        padding: 0 0 0 15px;
        width: 88%;
    }

    .chat_people {
        overflow: hidden;
        clear: both;
    }

    .chat_list {
        border-bottom: 1px solid #c4c4c4;
        margin: 0;
        padding: 18px 16px 10px;
    }

    .inbox_chat {
        height: 550px;
        overflow-y: scroll;
    }

    .active_chat {
        background: #ebebeb;
    }

    .incoming_msg_img {
        display: inline-block;
        width: 6%;
    }

    .received_msg {
        display: inline-block;
        padding: 0 0 0 10px;
        vertical-align: top;
        width: 92%;
    }

    .received_withd_msg p {
        background: #ebebeb none repeat scroll 0 0;
        border-radius: 3px;
        color: #646464;
        font-size: 14px;
        margin: 0;
        padding: 5px 10px 5px 12px;
        width: 100%;
    }

    .time_date {
        color: #747474;
        display: block;
        font-size: 12px;
        margin: 8px 0 0;
    }

    .received_withd_msg {
        width: 57%;
    }

    .mesgs {
        float: left;
        padding: 30px 15px 0 25px;
        width: 60%;
    }

    .sent_msg p {
        background: #614084 none repeat scroll 0 0;
        border-radius: 3px;
        font-size: 14px;
        margin: 0;
        color: #fff;
        padding: 5px 10px 5px 12px;
        width: 100%;
    }

    .outgoing_msg {
        overflow: hidden;
        margin: 26px 0 26px;
    }

    .sent_msg {
        float: right;
        width: 46%;
    }

    .input_msg_write input {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: medium none;
        color: #4c4c4c;
        font-size: 15px;
        min-height: 48px;
        width: 100%;
    }

    .type_msg {
        border-top: 1px solid #c4c4c4;
        position: relative;
    }

    .msg_send_btn {
        background: #05728f none repeat scroll 0 0;
        border: medium none;
        border-radius: 50%;
        color: #fff;
        cursor: pointer;
        font-size: 17px;
        height: 33px;
        position: absolute;
        right: 0;
        top: 11px;
        width: 33px;
    }

    .messaging {
        padding: 0 0 50px 0;
    }

    .msg_history {
        height: 516px;
        overflow-y: auto;
    }
</style>

<script>
    import moment from 'moment';
    export default {
        props: ['base_url'],
        data() {
            return {
                contacts: [],
                selected_user: {},
                messages: [],
                message: '',
                user_id: '',
                ready: false
            }
        },
        methods: {
            newline: function(){
                this.message = '${this.message}\n';
            },
            get_contacts: function() {
                axios.get(this.base_url + '/contact')
                .then(({ data }) => {
                    this.contacts = data;

                }).catch(() => {

                });
            },
            select_user: function(user) {
                this.ready = true;
                this.selected_user = user;
                this.get_messages();
            },
            get_messages: function() {
                axios.get(this.base_url + '/get_message/' + this.selected_user.contact_id)
                .then(({ data }) => {
                    this.messages = data;
                }).catch(() => {

                });
            },
            send_message: function() {

                axios.post(this.base_url + '/send_message/' + this.selected_user.contact_id, { text_message: this.message, })
                .then(() => {
                    this.message = '';
                    this.get_messages();
                }).catch(() => {

                });
            },
            view_message: function(id){
                axios.put(this.base_url + '/view_message/' + id)
                .then((res) => {

                }).catch(() => {
                
                });
            },
            get_user: function() {
                axios.get(this.base_url + '/get_user_id')
                .then(data => {
                    this.user_id = data;
                }).catch(() => {

                });
            },

            date: function (date) {
                return moment(date).format('MMMM D, YYYY');
            }
        },
        created() {
            this.get_user();
            this.get_contacts();
        },
        mounted() {
            window.Echo.channel("message_created").listen(".message.created", (e) => {
                this.get_messages();
            });
            window.Echo.channel("message_updated").listen(".message.updated", (e) => {
                this.get_messages();
            });
        }
    }
</script>

<style>
    .list-group {
        height: 18rem !important;

        overflow-y: scroll;
        -webkit-overflow-scrolling: touch;

    }

</style>
