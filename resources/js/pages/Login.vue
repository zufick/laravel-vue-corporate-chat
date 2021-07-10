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
                            required
                            v-model="loginData.email"
                            :error-messages="errors.email"
                            type="email"
                            name="email"
                            label="E-mail"
                        ></v-text-field>
                        <v-text-field
                            required
                            v-model="loginData.password"
                            type="password"
                            name="password"
                            label="Пароль"
                            hint="Минимальная длина 6 символов"
                        ></v-text-field>
                        <div class="d-flex justify-space-between">
                            <router-link custom to="/register">
                                <v-btn
                                    text
                                    color="primary"
                                >
                                    Создать аккаунт
                                </v-btn>
                            </router-link>
                            <v-btn
                                color="primary"
                                type="submit"
                            >
                                Войти
                            </v-btn>
                        </div>
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
        }
    }
}

</script>

<style scoped>

</style>
