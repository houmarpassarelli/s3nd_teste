new Vue({
    el: '#empresa',
    data: {
        employees : null,
        api : window.location.origin + '/api/empresa/'
    },
    beforeCreate(){
        
    },
    beforeUpdate(){
        
    },
    beforeMount(){
        
    },
    mounted(){
        this.getEmployees();
        // this.putEmployees();
    },
    methods:{
        async getEmployees(){

            await fetch(this.api)
                    .then(response => response.json())
                    .then(data => {this.employees = data});
                    
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