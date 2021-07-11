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

        <v-navigation-drawer
            app
            clipped
            right
        >
            <v-list>
                <v-list-item
                    v-for="(user,id) in users"
                    :key="id"
                    link
                >
                    <v-list-item-content>

                        <v-list-item-title>
                            <v-badge
                                bordered
                                bottom
                                color="green accent-4"
                                dot
                                offset-x="10"
                                offset-y="10"
                                class="mt-0 mr-1"
                            >
                                <v-avatar
                                    color="primary"
                                    size="38"
                                >
                                    <span class="white--text text-h5">{{ userInitials(user.name) }}</span>
                                </v-avatar>
                            </v-badge>
                            {{ user.name }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

            </v-list>
        </v-navigation-drawer>
    </div>
</template>

<script>
import store from "@/store";

export default {

    data() {
        return {
            roomId: 1,
            messages: [],
            users: [],
            textMessage: '',
        }
    },
    mounted() {
        this.loadPreviousMessages();
        window.Echo.join(`chat.${this.roomId}`)
        .here((users) => {
            this.users = users;
        })
        .joining((user) => {
            this.users.push(user);
        })
        .leaving((user) => {
            this.users = this.users.filter(u => u.id !== user.id);
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
        async loadPreviousMessages(){
            let res = await axios.get('/api/messages/' + this.roomId);
            this.messages = res.data.messages;
        },
        userInitials(name) {
            return name[0];
        },
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
