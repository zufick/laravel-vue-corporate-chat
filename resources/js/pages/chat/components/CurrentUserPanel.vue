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
            <ManageUsersButton v-if="userAdmin"></ManageUsersButton>
            <LogoutButton></LogoutButton>
        </div>
    </v-card>
</template>

<script>
import store from "../../../store";
import LogoutButton from "./LogoutButton";
import ManageUsersButton from "./ManageUsersButton";


export default {
    components: {
        LogoutButton,
        ManageUsersButton,
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
