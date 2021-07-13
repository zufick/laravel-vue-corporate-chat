<template>
    <div class="fill-height">
        <v-app-bar
            app
            clipped-right
            flat
            height="72"
        >
            <h2># {{room.name}}</h2>
            <v-spacer></v-spacer>
            <v-responsive max-width="156">
                <v-text-field
                    dense
                    flat
                    hide-details
                    rounded
                    solo-inverted
                ></v-text-field>
            </v-responsive>
        </v-app-bar>
        <v-main class="fill-height">
            <div class="d-flex fill-height flex-column justify-end">
                <v-btn class="mt-2 mr-2 ml-2" :loading="paginationLoading" v-if="pagination.current_page < pagination.last_page" @click="loadPreviousMessages">Загрузить ещё</v-btn>
                <Message class="m-4 mt-2 align-end ml-2 mr-2" :message="message" :remove="removeDeletedMessage" :canModerateRoom="canModerateRoom" v-for="(message, id) in messages" :key="id"></Message>
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
                    v-for="(user,id) in usersSorted"
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
                            <v-icon v-if="user.admin" color="amber darken-1">mdi-shield-star</v-icon>
                            <v-icon v-if="user.canModerateRoom" color="amber darken-1">mdi-hammer</v-icon>
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
import Message from "./Message";

export default {
    props: ['room'],
    components: {Message},
    data() {
        return {
            messages: [],
            pagination: {},
            paginationIndex: 1,
            paginationLoading: false,
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
        })
        .listen('MessageDeleteEvent', ({messageId}) => {
            this.messages = this.messages.filter(m => m.id !== messageId);
        });
    },
    computed: {
        roomId(){
            return this.room.id;
        },
        /**
         * Сортировка списка пользователей (Сначала идут модераторы)
         * @returns {[]}
         */
        usersSorted(){
            return this.users.sort((a,b) => {
                return (a.canModerateRoom === b.canModerateRoom) ? 0 : a.canModerateRoom ? -1 : 1;
            })
        },
        canModerateRoom(){
            if(!this.users.length)
                return false;

            return this.users.find(u => u.id === store.user.id).canModerateRoom;
        }
    },
    methods: {
        async loadPreviousMessages(){
            this.paginationLoading = true;
            let res = await axios.get(`/api/messages/${this.roomId}?page=${this.paginationIndex}`);
            this.pagination = res.data;
            res.data.data.reverse();
            this.messages = res.data.data.concat(this.messages);
            if(this.paginationIndex === 1) {
                setTimeout(() => {
                    this.scrollToBottom();
                }, 50);
            }
            this.paginationIndex++;
            this.paginationLoading = false;
        },
        userInitials(name) {
            return name[0];
        },
        sendMessage(){
            if (/^ *$/.test(this.textMessage))
                return;

            let message = {
                user: {
                    id: store.user.id,
                    name: store.user.name,
                },
                text: this.textMessage,
            };
            axios.post('/api/messages/' + this.roomId, { text: this.textMessage })
            .then(res => {
                if(res.status === 201) {
                    Object.assign(message, res.data.message);
                }
            });
            this.messages.push(message);
            this.textMessage = '';
        },
        removeDeletedMessage(message){
            this.messages = this.messages.filter(m => m.id !== message.id);
        },
        scrollToBottom(){
            this.$vuetify.goTo(document.body.scrollHeight, {
                duration: 10
            });
        }
    },
    watch: {
        messages: function (val) {
            if(document.body.offsetHeight - (window.innerHeight + window.scrollY) <= 50 )
                this.scrollToBottom();
        }
    }
}
</script>

<style scoped>

</style>
