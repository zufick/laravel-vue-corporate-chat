<template>
    <div class="d-inline-block">
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    @click="dialog = true"
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

        <v-row justify="center">
            <v-dialog
                v-model="dialog"
                fullscreen
                hide-overlay
                transition="dialog-bottom-transition"
            >
                <v-card>
                    <v-toolbar
                        dark
                        color="primary"
                    >
                        <v-btn
                            icon
                            dark
                            @click="dialog = false"
                        >
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                        <v-toolbar-title>Управление пользователями</v-toolbar-title>
                        <v-spacer></v-spacer>
                        <v-toolbar-items>
                            <v-btn
                                dark
                                text
                                @click="dialog = false"
                            >
                                Save
                            </v-btn>
                        </v-toolbar-items>
                    </v-toolbar>
                    <v-list
                        three-line
                        subheader
                    >
                        <ManageUsersTable></ManageUsersTable>
                    </v-list>
                    <v-divider></v-divider>
                    <v-list
                        three-line
                        subheader
                    >
                        <v-subheader>General</v-subheader>
                    </v-list>
                </v-card>
            </v-dialog>
        </v-row>
    </div>
</template>

<script>
import ManageUsersTable from "./ManageUsersTable";

export default {
    components: {ManageUsersTable},
    data() {
        return {
            dialog: false,
        }
    }
}
</script>

<style scoped>

</style>
