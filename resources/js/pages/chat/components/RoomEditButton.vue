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
            @click="dialog = true"
        >
            <v-icon dark>
                mdi-pencil
            </v-icon>
        </v-btn>

        <v-dialog
            v-model="dialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5">Редактирование канала</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-text-field
                            v-model="name"
                            label="Название канала"
                            required
                        ></v-text-field>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="dialog = false"
                    >
                        Отмена
                    </v-btn>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="editRoom"
                        :loading="loading"
                        :disabled="!name"
                    >
                        Сохранить
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
            name: this.room.name,
            dialog: false,
            loading: false,
        }
    },
    methods: {
        async editRoom(){
            this.loading = true;
            await axios.patch("/api/rooms/" + this.room.id, {name: this.name});
            this.loading = false;
            this.dialog = false;
        }
    }
}
</script>

<style scoped>

</style>
