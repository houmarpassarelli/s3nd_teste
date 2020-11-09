new Vue({
    el: '#cliente',
    data:{
        userstime: null, 
        api: window.location.origin + '/api/cliente/'
    },
    created(){
        this.getusersTime();
    },
    mounted(){
        this.interval = setInterval(() => this.getusersTime(), 60000);
        loading(false);
    },
    methods:{
        async getusersTime(){
            const response = await axios.get(this.api).then(response => response);
            this.userstime = response.data;
        }
    }
});
