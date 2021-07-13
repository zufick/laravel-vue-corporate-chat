<template>
    <div class="d-inline-block">
        <v-btn
            class="p-2"
            fab
            dark
            x-small
            elevation="0"
            v-if="visible"
            @click.stop
            @click="openMembers"
        >
            <v-icon dark>
                mdi-account-multiple-plus
            </v-icon>
        </v-btn>

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
                    <v-toolbar-title>Участники канала</v-toolbar-title>
                </v-toolbar>
                <v-list
                    three-line
                    subheader
                >
                    <RoomMembersTable :room="room"></RoomMembersTable>
                </v-list>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
import RoomMembersTable from "./RoomMembersTable";


export default {
    components: {
        RoomMembersTable
    },
    props: ['room', 'visible'],
    data(){
        return {
            dialog: false,
        }
    },
    methods: {
        openMembers() {
            this.dialog = true;
        }
    }
}
</script>

<style scoped>

</style>
