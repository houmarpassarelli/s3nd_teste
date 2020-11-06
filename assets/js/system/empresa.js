new Vue({
    el: '#empresa',
    data: {
        employees : null,
        api : window.location.origin + '/empresa/'
    },
    beforeCreate(){
        
    },
    beforeUpdate(){
        
    },
    beforeMount(){
        
    },
    mounted(){
        // this.getEmployees();
        this.putEmployees();
    },
    methods:{
        async getEmployees(){

            const response = await fetch('https://api.coindesk.com/v1/bpi/currentprice.json');
            this.employees = await response.json();

            await loading(false);
        },
        async putEmployees(){

            loading();

            const response = await fetch(`${this.api}?acao=put`);
            // this.employees = await response;
            console.log(response);
            await loading(false);
        }
    }
});