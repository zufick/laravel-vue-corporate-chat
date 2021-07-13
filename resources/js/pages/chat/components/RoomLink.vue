<template>
    <v-list-item
        link
        @click="$emit('click')"
    >
        <v-hover v-model="hover">
            <v-list-item-content class="d-flex align-center justify-space-between">
                <v-list-item-title class="d-flex justify-space-between align-center py-1" >
                    <p class="mb-0 linkItemsContainer"><b>#</b> {{ room.name }}</p>
                    <div>
                        <RoomMembersButton :visible="hover && room.canModerateRoom" :room="room"></RoomMembersButton>

                        <v-btn
                            class="p-2"
                            fab
                            dark
                            x-small
                            elevation="0"
                            v-if="hover && room.canModerateRoom"
                            @click.stop
                        >
                            <v-icon dark>
                                mdi-pencil
                            </v-icon>
                        </v-btn>
                        <RoomDeleteButton :visible="hover && room.canModerateRoom" :room="room"></RoomDeleteButton>
                    </div>
                </v-list-item-title>
            </v-list-item-content>
        </v-hover>
    </v-list-item>
</template>

<script>
import RoomDeleteButton from "./RoomDeleteButton";
import RoomMembersButton from "./RoomMembersButton";

export default {
    components: {
        RoomDeleteButton,
        RoomMembersButton
    },
    props: ['room'],
    data(){
        return {
            hover: false,
        }
    },
    methods: {
        openMembers(){
            alert("aaa");
        }
    }
}
</script>

<style scoped>
    .linkItemsContainer{
        max-width: 80%;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
