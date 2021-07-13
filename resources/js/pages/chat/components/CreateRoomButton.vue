<template>
    <div>
        <v-btn
            class="mx-2"
            fab
            dark
            x-small
            @click="dialog = true"
        >
            <v-icon dark>
                mdi-plus
            </v-icon>
        </v-btn>

        <v-dialog
            v-model="dialog"
            persistent
            max-width="600px"
        >
            <v-card>
                <v-card-title>
                    <span class="text-h5">Создание канала</span>
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
                        @click="createRoom"
                        :loading="loading"

                    >
                        Создать
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script>
export default {
    data(){
        return {
            name: "",
            dialog: false,
            loading: false,
        }
    },
    methods: {
        async createRoom(){
            this.loading = true;
            await axios.post("/api/rooms", {name: this.name});
            this.loading = false;
            this.dialog = false;
        }
    }
}
</script>

<style scoped>

</style>
