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
        >
            <template v-slot:item.approved="{ item }">
                <v-checkbox
                    v-model="item.approved"
                    disabled
                ></v-checkbox>
            </template>
            <template v-slot:item.admin="{ item }">
                <v-checkbox
                    v-model="item.admin"
                    disabled
                ></v-checkbox>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon
                    small
                    class="mr-2"
                    @click="editItem(item)"
                    :disabled="item.id === currentUser.id"
                >
                    mdi-pencil
                </v-icon>
                <v-icon
                    small
                    @click="deleteItem(item)"
                    :disabled="item.id === currentUser.id"
                >
                    mdi-delete
                </v-icon>
            </template>
        </v-data-table>

        <!-- Edit Dialog -->
        <v-dialog
            v-model="dialog"
            max-width="500px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                    <v-container>
                        <v-text-field
                            v-model="editedItem.name"
                            :error-messages="editedItemErrors.name"
                            label="Имя"
                        ></v-text-field>
                        <v-text-field
                            v-model="editedItem.email"
                            :error-messages="editedItemErrors.email"
                            label="E-mail"
                        ></v-text-field>
                        <v-checkbox
                            v-model="editedItem.admin"
                            label="Администратор"
                        ></v-checkbox>
                        <v-checkbox
                            v-model="editedItem.approved"
                            label="Одобренный"
                        ></v-checkbox>
                        <v-text-field
                            v-model="editedItem.password"
                            label="Новый пароль"
                            hint="Заполните данное поле если хотите изменить пароль"
                        ></v-text-field>
                    </v-container>
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="close"
                    >
                        Отмена
                    </v-btn>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="save"
                    >
                        Сохранить
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- End Edit Dialog -->

        <!-- Delete Dialog -->
        <v-dialog v-model="dialogDelete" max-width="500px">
            <v-card>
                <v-card-title class="text-h5">Удаление</v-card-title>
                <v-card-text>Вы уверены, что хотите удалить данного пользователя?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="closeDelete">Отмена</v-btn>
                    <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
                    <v-spacer></v-spacer>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <!-- End Delete Dialog -->

    </v-card>

</template>

<script>
import store from "@/store";

export default {
    data(){
        return {
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
                { text: 'Имя', value: 'name' },
                { text: 'E-mail', value: 'email' },
                { text: 'Дата регистрации', value: 'created_at' },
                { text: 'Администратор', value: 'admin' },
                { text: 'Одобренный', value: 'approved' },
                { text: 'Действия', value: 'actions', sortable: false },
            ],
            editedItem: {},
            editedItemErrors: [],
            defaultItem: {
                name: '',
                email: '',
                admin: 0,
                approved: 0,
            },
            users: []
        }
    },
    computed: {
        formTitle () {
            return this.editedIndex === -1 ? 'Новый пользователь' : 'Редактирование пользователя'
        },
        currentUser(){
            return store.user;
        }
    },
    mounted() {
        this.loadUsers();
    },
    methods: {
        editItem (item) {
            this.editedIndex = this.users.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialog = true
        },
        deleteItem (item) {
            this.editedIndex = this.users.indexOf(item)
            this.editedItem = Object.assign({}, item)
            this.dialogDelete = true
        },
        async deleteItemConfirm () {
            let user = this.users[this.editedIndex];
            try {
                await axios.delete('/api/user/' + user.id);
                this.users.splice(this.editedIndex, 1)
                this.closeDelete()
            }
            catch (e) {
                alert("Произошла ошибка при удалении пользователя.");
            }
        },
        async loadUsers(){
            this.loadingUsers = true;
            try {
                let res = await axios.get('/api/users');
                this.users = res.data.users;
            }catch (e) {
                alert("Произошла ошибка при получении пользователей");
            }
            this.loadingUsers = false;
        },
        close () {
            this.dialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },
        closeDelete () {
            this.dialogDelete = false
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem)
                this.editedIndex = -1
            })
        },
        async save () {
            try {
                await axios.patch("/api/user/" + this.editedItem.id, this.editedItem);
            }catch (e) {
                if(e.response.status === 422){
                    this.editedItemErrors = e.response.data.errors;
                    return;
                }
            }
            if (this.editedIndex > -1) {
                Object.assign(this.users[this.editedIndex], this.editedItem)
            } else {
                this.users.push(this.editedItem)
            }
            this.close()
        },
    }
}
</script>

<style scoped>

</style>
