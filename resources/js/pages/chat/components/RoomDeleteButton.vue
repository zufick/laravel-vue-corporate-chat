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
            @click="delDialog = true"
        >
            <v-icon dark>
                mdi-delete
            </v-icon>
        </v-btn>

        <v-dialog
            v-model="delDialog"
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Удалить канал
                </v-card-title>

                <v-card-text>
                    Вы уверены, что хотите удалить этот канал?
                    <v-card outlined class="pa-2 mt-2"># {{room.name}}</v-card>
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
                        @click="deleteRoom"
                        :loading="loading"

                    >
                        Удалить
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    props: ['room', 'visible'],
    data(){
        return {
            delDialog: false,
            loading: false,
        }
    },
    methods: {
        async deleteRoom(){
            this.loading = true;
            let res = await axios.delete(`/api/rooms/${this.room.id}`);
        }
    }
}
</script>

<style scoped>

</style>
