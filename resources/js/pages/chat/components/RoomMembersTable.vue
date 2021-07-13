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
                <v-icon
                    small
                    class="mr-2"
                    @click="editItem(item)"
                    :disabled="item.id === currentUser.id || item.admin"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    small
                    @click="deleteItem(item)"
                    :disabled="item.id === currentUser.id || item.admin"
                >
                    mdi-delete
                </v-icon>
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
        invite(){

        },
        kick(){

        },
        giveModerator(){

        },
        removeModerator(){

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
