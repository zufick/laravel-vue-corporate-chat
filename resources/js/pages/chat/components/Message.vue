<template>
    <div>
        <v-hover v-model="hover">
            <v-card class="d-flex justify-between align-center" outlined elevation="0" >
                <v-card-text>
                    <div class="d-flex justify-space-between">
                        <b>{{message.user.name}}:</b>
                        <span v-if="message.created_at" class="text--disabled ml-2">{{date}}</span>
                    </div>
                    <p class="mb-0 mt-2">{{ message.text }}</p>
                </v-card-text>
                <div v-if="hover">
                    <v-btn
                        v-if="message.id && canModerateMessage"
                        fab
                        dark
                        x-small
                        color="red"
                        @click="delDialog = true"
                        absolute
                        style="right: -2px; bottom: -2px;"
                    >
                        <v-icon dark>
                            mdi-delete
                        </v-icon>
                    </v-btn>
                </div>
            </v-card>
        </v-hover>

        <v-dialog
            v-model="delDialog"
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Удалить сообщение
                </v-card-title>

                <v-card-text>
                    Вы уверены, что хотите удалить это сообщение?
                    <v-card outlined class="pa-2 mt-2">{{message.text}}</v-card>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn
                        color="darken-1"
                        text
                        @click="delDialog = false"
                    >
                        Отмена
                    </v-btn>

                    <v-btn
                        color="red darken-1"
                        text
                        @click="deleteMessage"
                    >
                        Удалить
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import store from "@/store";

export default {
    props: ['message', 'canModerateRoom', 'remove'],
    data(){
        return {
            hover: false,
            delDialog: false,
        }
    },
    computed: {
        canModerateMessage(){
            return store.user.id === this.message.user.id || this.canModerateRoom;
        },
        date(){
            return new Date(this.message.created_at).toLocaleString();
        }
    },
    methods: {
        async deleteMessage(){
            this.delDialog = false;
            this.remove(this.message);
            await axios.delete('/api/message/' + this.message.id);
        }
    },
}
</script>

<style scoped>

</style>
