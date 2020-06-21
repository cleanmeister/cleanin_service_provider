<template>
    <div>
		
        <li class="nav-item">
            <a class="nav-link" v-bind:href="base_url + '/notifications'">
                Notifications <i class="far fa-bell"></i>
                <span v-if="notifications > 0" class="badge badge-warning navbar-badge">{{ notifications }}</span>
            </a>
        </li>
    </div>
</template>
<script>
    export default {
        props: ['base_url'],
        data() {
            return {
                notifications: '',
                timer: ''
            }
        },
        methods: {
            get_new_notifications() {
                axios.get(this.base_url+'/new_user_notifs')
                .then(({ data }) => {
                    this.notifications = data;
                }).catch(() => {

                });
            },
        },
        created() {
            this.get_new_notifications();
            this.timer = setInterval(this.get_new_notifications, 3000);
        },
        beforeDestroy () {
            clearInterval(this.timer)
        },
        mounted() {

        }
    }

</script>
