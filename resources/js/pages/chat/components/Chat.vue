<template>
    <div class="fill-height">
        <v-main class="fill-height">
            <div class="d-flex fill-height flex-column justify-end">
                <v-card class="m-4 align-end" v-for="(message, id) in messages" :key="id">
                    <v-card-text><b>{{message.user.name}}:</b> {{ message.text }}</v-card-text>
                </v-card>
            </div>
            <!--            <router-view></router-view>-->
        </v-main>
        <v-footer
            app
            color="transparent"
            height="72"
            inset
        >
            <v-text-field
                v-model="textMessage"
                background-color="grey lighten-1"
                dense
                flat
                hide-details
                rounded
                solo
                @keyup.enter="sendMessage"
            ></v-text-field>
        </v-footer>
    </div>
</template>

<script>
import store from "@/store";

export default {

    data() {
        return {
            roomId: 1,
            messages: [],
            textMessage: '',
        }
    },
    mounted() {
        window.Echo.join(`chat.${this.roomId}`)
        .here((users) => {
            console.log("here", users);
        })
        .joining((user) => {
            console.log("joining", user.name);
        })
        .leaving((user) => {
            console.log("leaving", user.name);
        })
        .error((error) => {
            console.error(error);
        })
        .listen('MessageEvent', ({message}) => {
           this.messages.push(message);
        });
    },
    computed: {
        pageHeight () {
            return document.body.scrollHeight
        }
    },
    methods: {
        sendMessage(){
            if (/^ *$/.test(this.textMessage))
                return;
            axios.post('/api/messages/' + this.roomId, { text: this.textMessage });
            this.messages.push({
                user: {
                    id: store.user.id,
                    name: store.user.name,
                },
                text: this.textMessage,
            });
            this.textMessage = '';
        }
    },
    watch: {
        messages: function (val) {
            this.$vuetify.goTo(this.pageHeight, {
                duration: 10
            });
        }
    }
}
</script>

<style scoped>

</style>
