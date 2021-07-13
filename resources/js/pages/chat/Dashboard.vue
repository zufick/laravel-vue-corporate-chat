<template>
    <v-app id="inspire">
        <v-navigation-drawer
            v-model="drawer"
            app
            width="300"
        >
            <v-sheet
                height="128"
                width="100%"
                class="pr-2"
            >
                <CurrentUserPanel></CurrentUserPanel>
            </v-sheet>
            <v-sheet class="d-flex align-center pl-8">
                <ThemePanel></ThemePanel>
            </v-sheet>
            <v-list
                class="pl-4 mt-4 d-flex flex-column justify-space-between"
                shaped
            >
                <div>
                    <v-progress-circular
                        v-if="loadingRooms"
                        indeterminate
                        color="primary"
                    ></v-progress-circular>
                    <v-list-item
                        v-for="room in rooms"
                        :key="room.id"
                        link
                        @click="switchRoom(room)"
                    >
                        <v-list-item-content>
                            <v-list-item-title><b>#</b> {{ room.name }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </div>

            </v-list>
        </v-navigation-drawer>
        <div v-if="!currentRoom.id" class="fill-height">
            <v-app-bar
                app
                clipped-right
                flat
                height="72"
            >
                <h2>Выберите канал для общения</h2>
            </v-app-bar>
            <v-main></v-main>
        </div>
        <Chat v-for="room in rooms" :key="room.id" :class="{ 'd-none': !currentRoom || (currentRoom && currentRoom.id !== room.id) }" :room="room"></Chat>
    </v-app>

</template>

<script>
import CurrentUserPanel from "./components/CurrentUserPanel";
import ThemePanel from "./components/ThemePanel";
import Chat from "./components/Chat";
import store from "@/store";

export default {
    data: () => (
        {
            drawer: null,
            rooms: [],
            loadingRooms: false,
            currentRoom: {},
        }
    ),
    components: {
        CurrentUserPanel,
        ThemePanel,
        Chat
    },
    mounted() {
        this.initRooms();
    },
    methods: {
        async initRooms(){
            this.loadingRooms = true;
            window.Echo.private(`user.rooms.${store.user.id}`)
            .listen('UserRoomsUpdated', ({rooms}) => {
                this.rooms = rooms;
                this.loadingRooms = false;
            })
            await axios.post("/api/rooms");
        },
        switchRoom(room){
            this.currentRoom = room;
            setTimeout(() => {
                this.scrollToBottom();
            }, 50);
        },
        scrollToBottom(){
            this.$vuetify.goTo(document.body.scrollHeight, {
                duration: 10
            });
        }
    }
}
</script>

<style scoped>

</style>
