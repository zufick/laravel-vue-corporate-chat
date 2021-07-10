<template>
    <v-container class="fill-height">
        <v-row align="center" justify="center" dense>
            <v-col
                cols="12"
                sm="9"
                md="7"
                lg="4"
            >
                <v-card class="pa-8 elevation-3">
                    <form v-on:submit.prevent="formSubmit">
                        <h2 align="center">Регистрация</h2>
                        <v-text-field
                            v-model="regData.name"
                            :error-messages="nameErrors"
                            type="text"
                            name="name"
                            label="Имя"
                            @input="$v.regData.name.$touch()"
                            @blur="$v.regData.name.$touch()"
                        ></v-text-field>

                        <v-text-field
                            v-model="regData.email"
                            :error-messages="emailErrors"
                            type="text"
                            name="email"
                            label="E-mail"
                            @input="$v.regData.email.$touch()"
                            @blur="$v.regData.email.$touch()"
                        ></v-text-field>
                        <v-text-field
                            v-model="regData.password"
                            :error-messages="passwordErrors"
                            type="password"
                            name="password"
                            label="Пароль"
                            hint="Минимальная длина 6 символов"
                            @input="$v.regData.password.$touch()"
                            @blur="$v.regData.password.$touch()"
                        ></v-text-field>
                        <v-text-field
                            v-model="regData.password2"
                            type="password"
                            name="password2"
                            :error-messages="password2Errors"
                            label="Подтверждение пароля"
                            @input="$v.regData.password2.$touch()"
                            @blur="$v.regData.password2.$touch()"
                        ></v-text-field>
                        <v-btn
                            :disabled="forbiddenFormSubmit"
                            :loading="loading"
                            block
                            color="primary"
                            type="submit"
                        >
                            Зарегистрироваться
                        </v-btn>
                        <router-link :disabled="loading" :event="!loading ? 'click' : ''" custom to="/login">
                            <v-btn
                            class="mt-2"
                            block
                            text
                            color="primary"
                            >
                            Войти в аккаунт
                            </v-btn>
                        </router-link>
                    </form>
                </v-card>
            </v-col>
        </v-row>

        <v-dialog
            v-model="dialog"
            max-width="290"
        >
            <v-card>
                <v-card-title class="text-h5">
                    Регистрация
                </v-card-title>

                <v-card-text>
                    {{message}}
                </v-card-text>

                <v-card-actions>
                    <v-spacer></v-spacer>

                    <v-btn
                        color="green darken-1"
                        text
                        @click="dialogConfirm"
                    >
                        Подтвердить
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

    </v-container>
</template>

<script>
import { validationMixin } from 'vuelidate'
import { required, sameAs, minLength, email } from 'vuelidate/lib/validators';

export default {
    mixins: [validationMixin],

    data: () => ({
        dialog: false,
        loading: false,
        message: '',
        regData: {
            name: '',
            email: '',
            password: '',
            password2: '',
        },
        password2: '22',
        errors: [],
    }),
    validations: {
        regData: {
            name: { required },
            email: { required, email },
            password: { required, minLength: minLength(6) },
            password2: {
                required,
                sameAs: sameAs(function () {
                    return this.regData.password
                })
            }
        }
    },
    computed: {
        nameErrors(){
            const errors = this.errors.name ?? [];
            if (!this.$v.regData.name.$dirty) return errors;
            !this.$v.regData.name.required && errors.push('Необходимо ввести имя.')
            return errors

        },
        emailErrors(){
            const errors = this.errors.email ?? [];
            if (!this.$v.regData.email.$dirty) return errors;
            !this.$v.regData.email.required && errors.push('Необходимо ввести E-mail.')
            !this.$v.regData.email.email && errors.push('Введён некорректный E-mail.')
            return errors

        },
        passwordErrors(){
            const errors = this.errors.password ?? [];
            if (!this.$v.regData.password.$dirty) return errors;
            !this.$v.regData.password.minLength && errors.push('Минимальная длина пароля: 6 символов.')
            !this.$v.regData.password.required && errors.push('Необходимо ввести пароль.')
            return errors

        },
        password2Errors(){
            const errors = [];
            if (!this.$v.regData.password2.$dirty) return errors
            !this.$v.regData.password2.sameAs && errors.push('Пароли не совпадают.')
            !this.$v.regData.password2.required && errors.push('Необходимо ввести подтверждение пароля.')
            return errors
        },
        forbiddenFormSubmit(){
            return this.$v.$invalid || this.loading;
        }
    },
    methods: {
        formSubmit(){
            if (!this.$v.$invalid) {
                this.login();
            }else{
                alert("cannot submit");
            }
        },
        async login(){
            this.loading = true;
            try {
                let res = await axios.post('/api/register', this.regData);
                if(res.status === 201){
                    this.message = res.data.message;
                    this.dialog = true;
                }
            }
            catch (e) {
                if(e.response.status === 422){
                    this.errors = e.response.data.errors;
                }
            }
            this.loading = false;
        },
        dialogConfirm(){
            this.dialog = false;
            this.$router.push('/login');
        }
    }
}

</script>

<style scoped>

</style>
