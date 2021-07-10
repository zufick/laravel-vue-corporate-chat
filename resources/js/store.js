import Vue from 'vue';

/**
 * Хранилище данных приложения
 */
export default Vue.observable({
    user: {},
    userCheckInitialized: false,
    saveToken(token){
        localStorage.setItem('token', token);
    },
    async authorize(){
        let token = localStorage.getItem('token');
        if(token){
            axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;

            try {
                let res = await axios.get('/api/user');
                if (res.status === 200)
                    this.user = res.data;
            }
            catch(e){
                if (e.response.status === 401) {
                    localStorage.removeItem('token');
                }
            }

            this.userCheckInitialized = true;
        }
    },
    async logout(){
        try {
            let res = await axios.post('/api/logout');
            if(res.status === 200){
                this.user = {};
                localStorage.removeItem('token');
                return true;
            }
        }
        catch (e) {

        }
        return false;
    }
});
