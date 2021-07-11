<template>
    <v-switch
        v-model="dark"
        inset
        label="Тёмная тема"
    ></v-switch>
</template>

<script>
export default {
    data(){
        return {
            dark: localStorage.getItem('dark-theme') === 'true',
        }
    },
    mounted() {

    },
    watch: {
        dark: function(val){
            this.$vuetify.theme.dark = val;
            localStorage.setItem('dark-theme', val);
        }
    }
}
</script>

<style scoped>

</style>
