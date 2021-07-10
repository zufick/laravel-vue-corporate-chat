<template>
    <v-container class="fill-height">
        <v-row align="center" justify="center" dense>
            <v-col
                cols="12"
                sm="8"
                md="5"
                lg="4"
            >
                <v-card class="pa-8 elevation-3">
                    <form v-on:submit.prevent="formSubmit">
                        <h2 align="center">Авторизация</h2>
                        <v-text-field
                            v-model="loginData.email"
                            :error-messages="errors.email"
                            type="text"
                            name="email"
                            label="E-mail"
                        ></v-text-field>
                        <v-text-field
                            v-model="loginData.password"
                            :error-messages="errors.password"

                            type="password"
                            name="password"
                            label="Пароль"
                        ></v-text-field>
                            <v-btn
                                block
                                color="primary"
                                type="submit"
                                :disabled="loading || (!loginData.password || !loginData.email)"
                                :loading="loading"
                            >
                                Войти
                            </v-btn>
                        <router-link custom to="/register" :disabled="loading" :event="!loading ? 'click' : ''">
                            <v-btn
                                class="mt-2"
                                block
                                text
                                color="primary"
                                :disabled="loading"
                            >
                                Создать аккаунт
                            </v-btn>
                        </router-link>

                    </form>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import store from '../store'

export default {
    data: () => ({
        loading: false,
        loginData: {
            email: '',
            password: '',
        },
        errors: [],
    }),
    methods: {
        formSubmit(){
            this.login();
        },
        async login(){
            this.loading = true;
            try {
                let res = await axios.post('/api/login', this.loginData);
                if(res.status === 200){
                    store.saveToken(res.data.token);
                    await store.authorize();
                    this.$router.push('/');
                }
            }
            catch (e) {
                if(e.response.status === 422){
                    this.errors = e.response.data.errors;
                }
            }
            this.loading = false;
        }
    }
}

</script>

<style scoped>

</style>
