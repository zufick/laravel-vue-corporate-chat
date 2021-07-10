<template>
    <v-card class="ml-3 pa-4 mt-4 elevation-1">
        <div class="d-flex align-center">
            <v-badge
                bordered
                bottom
                color="green accent-4"
                dot
                offset-x="10"
                offset-y="10"
            >
                <v-avatar
                    color="primary"
                    size="38"
                >
                    <span class="white--text text-h5">{{ userInitials }}</span>
                </v-avatar>
            </v-badge>
            <div class="d-flex ml-2 flex-column align-content-end justify-content-end">
                <h4>{{ userName }}</h4>
                <p class="mb-0">{{ userEmail }}</p>
            </div>
        </div>
        <div class="mt-4">
            <v-tooltip bottom v-if="userAdmin">
                <template v-slot:activator="{ on, attrs }">
                    <v-btn
                    icon
                    elevation="2"
                    v-bind="attrs"
                    v-on="on"
                    >
                        <v-icon small>mdi-account-multiple</v-icon>
                    </v-btn>
                </template>
                <span>Управление пользователями</span>
            </v-tooltip>
            <LogoutButton></LogoutButton>
        </div>
    </v-card>
</template>

<script>
import store from "../../../store";
import LogoutButton from "./LogoutButton";

export default {
    components: {
        LogoutButton
    },
    computed: {
        userName(){
            return store.user.name;
        },
        userEmail(){
            return store.user.email;
        },
        userAdmin(){
            return store.user.admin;
        },
        userInitials(){
            return this.userName[0];
        }
    }
}
</script>

<style scoped>

</style>
