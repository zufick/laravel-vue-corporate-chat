<template>
    <div class="d-inline-block">
        <v-tooltip bottom>
            <template v-slot:activator="{ on, attrs }">
                <v-btn
                    icon
                    elevation="2"
                    v-bind="attrs"
                    v-on="on"
                    @click.stop="dialog = true"
                >
                    <v-icon small>logout</v-icon>
                </v-btn>
            </template>
            <span>Выйти</span>
        </v-tooltip>
        <div>
            <v-row justify="center">

                <v-dialog
                    v-model="dialog"
                    max-width="290"
                >
                    <v-card>
                        <v-card-title class="text-h5">
                            Выйти из аккаунта
                        </v-card-title>

                        <v-card-text>
                            Вы уверены, что хотите выйти из аккаунта?
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <v-btn
                                color="darken-1"
                                text
                                @click="dialog = false"
                            >
                                Отмена
                            </v-btn>

                            <v-btn
                                color="red darken-1"
                                text
                                :disabled="loading"
                                :loading="loading"
                                @click="logout"
                            >
                                Подтвердить
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-row>
        </div>
    </div>
</template>

<script>
import store from '@/store.js';

export default {
    data () {
        return {
            loading: false,
            dialog: false,
        }
    },
    methods: {
        async logout(){
            this.loading = true;
            if(await store.logout()){
                this.$router.push('/login');
            }
            this.loading = false;
        }
    }
}

</script>

<style scoped>

</style>
