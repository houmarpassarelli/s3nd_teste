new Vue({
    el : '#loja',
    data : {
        api : window.location.origin + '/api/loja/',
        userip : null,
        usercode : null,
        services : null
    },
    created(){
        this.getUserIp();
    },
    mounted(){
        this.interval = setInterval(() => this.updateUserStatus(), 60000);
        this.getServices();
        loading(false);
    },
    methods:{
        async getServices(){

            const response = await axios.get('./arquivos/services.json').then(response => response);
            this.services = response.data;
        },
        setUserCode(){
            axios.get(this.api + '?acao=code&ip=' + this.userip)
                .then(response => {
                    this.usercode = response.data
                    this.updateUserStatus();
                });
        },
        async updateUserStatus(){
            if(this.userip && this.userip != undefined){
                if(this.usercode && this.usercode != null && this.usercode != undefined){
                    await axios.get(this.api + '?acao=ping&code=' + this.usercode);
                }                
            }else{
                this.getUserIp();
                this.updateUserStatus();
            }
        },
        getUserIp(){
            axios.get('https://api.ipify.org?format=json')
                .then((response) => {
                    this.userip = response.data.ip;
                    this.setUserCode();
                });
        }
    }
});