<template>
    <v-card >
        <v-card-title>
            Все пользователи
            <v-btn :loading="loadingUsers" color="primary" icon class="ml-4" @click="loadUsers">
                <v-icon
                >
                    mdi-refresh
                </v-icon>
            </v-btn>
            <v-spacer></v-spacer>
            <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
        </v-card-title>
        <v-data-table
            :headers="headers"
            :items="users"
            :search="search"
            :sort-by.sync="sortBy"
            :sort-desc.sync="sortDesc"
        >
            <template v-slot:item.canModerateRoom="{ item }">
                <v-checkbox
                    v-model="item.canModerateRoom"
                    disabled
                ></v-checkbox>
            </template>
            <template v-slot:item.canJoinRoom="{ item }">
                <v-checkbox
                    v-model="item.canJoinRoom"
                    disabled
                ></v-checkbox>
            </template>
            <template v-slot:item.actions="{ item }">
                <div v-if="!(item.id === currentUser.id || item.admin)">
                    <v-btn @click="giveModerator(item.id)" v-if="item.canJoinRoom && !item.canModerateRoom">Назначить модератором</v-btn>
                    <v-btn @click="removeModerator(item.id)" color="error" v-if="item.canModerateRoom">Забрать модераторство</v-btn>
                    <v-btn @click="invite(item.id)" color="primary" v-if="!item.canJoinRoom">Пригласить</v-btn>
                    <v-btn @click="kick(item.id)" color="error" v-if="item.canJoinRoom">Выгнать</v-btn>
                </div>
            </template>
        </v-data-table>

    </v-card>
</template>

<script>
import store from "@/store";

export default {
    props: ["room"],
    data() {
        return {
            sortBy: 'canJoinRoom',
            sortDesc: true,
            search: '',
            dialog: false,
            dialogDelete: false,
            loadingUsers: false,
            headers: [
                {
                    text: 'id',
                    align: 'start',
                    value: 'id',
                },
                {text: 'Имя', value: 'name'},
                {text: 'Модератор канала', value: 'canModerateRoom'},
                {text: 'Состоит в канале', value: 'canJoinRoom'},
                {text: 'Действия', value: 'actions', sortable: false},
            ],
            users: []
        }
    },
    mounted() {
        this.loadUsers();
    },
    methods: {
        async loadUsers(){
            let res = await axios.get("/api/rooms/" + this.room.id);
            this.users = res.data;
        },
        async invite(userId){
            let res = await axios.post(`/api/rooms/${this.room.id}/${userId}/invite`);
            this.loadUsers();
        },
        async kick(userId){
            let res = await axios.post(`/api/rooms/${this.room.id}/${userId}/kick`);
            this.loadUsers();

        },
        async giveModerator(userId){
            let res = await axios.post(`/api/rooms/${this.room.id}/${userId}/moder`);
            this.loadUsers();

        },
        async removeModerator(userId){
            let res = await axios.post(`/api/rooms/${this.room.id}/${userId}/demoder`);
            this.loadUsers();

        }
    },
    computed: {
        currentUser(){
            return store.user;
        }
    },
}
</script>

<style scoped>

</style>
