<template>
  <div class="container mt-2">
      <div class="row">
        <div class="col-2"></div>
        <div class="col-8">
          <div class="card">
            <div class="card-header">
              Notifications
            </div>
            <div class="card-body">
                <div v-if="notifications.length <= 0" class="text-center">
                  <span><i>Notifications Empty!</i></span>
                </div>
                <div v-if="notifications.length > 0" v-for="(n,index) in notifications" :key="index">
                  <h4 style="margin: 0;"><strong>{{ n.sender_fullname }}</strong></h4>
                  <p style="margin: 0; font-size: 1rem;" class="card-text" v-html="n.notification"></p>
                  <h6><p style="margin: 0;" class="blockquote-footer" v-html="date(n.date)"></p></h6>
                  <hr>
                </div>
            </div>
          </div>
        </div>
        <div class="col-2"></div>
      </div>
  </div>
</template>

<script>
import moment from 'moment';
export default {
    props: ['base_url'],
    data(){
        return{
            notifications: [],
        }
    },
    methods:{
        date: function (date) { return moment(date).format('MMMM D, YYYY'); },
        get_notifications(){
            axios.get(this.base_url + '/get_user_notifs').then(({data}) => {
                this.notifications = data;
            })
        }
    },
    created(){
      this.get_notifications();

    },
    mounted(){

    }
}
</script>

<style>
.list-group{
    height: 23rem;

    overflow-y:scroll;
    -webkit-overflow-scrolling: touch;
}
</style>