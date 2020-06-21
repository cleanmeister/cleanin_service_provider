<template>
  <div>
      <div class="row">
          <div class="col-4">
              <div class="card">
                  <div class="card-header">

                  </div>
                  <div class="card-body">
                    <ul class="list-group list-group-flush" v-show="contacts.length > 0">
                        <li class="list-group-item list-group-item-action" v-for="(contact, index) in contacts" :key="index" @click="select_user(contact)" v-show="contact.id !== user_id">
                            {{ contact.profile.firstname }} {{ contact.profile.lastname }}
                        </li>
                    </ul>
                  </div>
              </div>
          </div>
          <div class="col-8">
              <div class="card">
                  <div class="card-header">
                      <span v-show="selected_user !== ''">
                          {{ selected_user.firstname }}  {{ selected_user.lastname }}
                        </span>
                  </div>
                  <div class="card-body" style="height: 25rem;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" v-for="(m, index) in messages" :key="index" :class="{ 'text-left' : m.sender_id == selected_user.user_id, 'text-right' : m.sender_id == user_id }">
                            {{ m.receiver_id }}
                            {{ m.sender_id }}
                            {{ m.text_message }}
                        </li>
                    </ul>
                    
                  </div>
                  <div class="card-footer">
                      <form @submit.prevent="send_message()">
                        <div class="row">
                            <div class="col-10">
                                <textarea rows="3" v-model="message" class="form-control form-control-sm"></textarea>
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-sm btn-primary">Sent</button>
                            </div>
                        </div>
                    </form>
                  </div>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
export default {
    props: ['base_url'],
    data(){
        return{
            contacts: [],
            selected_user: {
                firstname: '',
                lastname: '',
            },
            messages: [],
            message: '',
            user_id: '',
        }
    },
    methods:{
        get_contacts(){
            axios.get(this.base_url + '/contact').then(({data}) => {
                this.contacts = data;
            }).catch(() => {

            });
        },
        select_user(user){
            this.selected_user = user.profile;
            this.get_messages();
        },
        get_messages(){
            axios.get(this.base_url + '/get_message/'+this.selected_user.user_id).then(({data}) => {
                this.messages = data;
            }).catch(() => {

            });
        },
        send_message(){
            axios.post(this.base_url + '/send_message/'+this.selected_user.user_id,{
                text_message: this.message,
            }).then(() => {
                this.get_messages();
            }).catch(() => {

            });
        },
        get_user(){
            axios.get(this.base_url + '/get_user_id').then(({data}) => {
                this.user_id = data;
            }).catch(() => {

            });
        }
    },
    created(){
        this.get_user();
        this.get_contacts();
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