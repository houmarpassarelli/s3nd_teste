new Vue({
    el: '#empresa',
    data: {
        employees : null,
        edit_show : [],
        weekdays : ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'],     
        api : window.location.origin + '/api/empresa/'
    },
    beforeCreate(){
        
    },
    beforeUpdate(){
        
    },    
    beforeMount(){
        
    },
    updated(){
        
    },
    mounted(){
        this.getEmployees();
    },
    methods:{
        async delEmployees(id){

            loading();

            const request = new Request(this.api + id, {
                method: "DELETE"
            });

            await fetch(request).then((response) => {
                if(response.status == 204){
                    this.getEmployees();
                }
            });
        },
        async getEmployees(){

            loading();

            await fetch(this.api)
                    .then(response => response.json())
                    .then(data => {this.employees = data});

            let length = 0;
            
            while(length < this.employees.length){
                this.edit_show.push(false);    
                length++;
            }

            await loading(false);
        },
        async updateEmployees(e, id, index){

            let data = new FormData(document.getElementById(e.target.id));

            data.append("request","update");

            axios.post(this.api + id, data, {
                headers: {
                    'Content-Type': 'application/json;charset=UTF-8'
                }
            })
            .then((response) => { 
                if(response.status == 204){
                    this.getEmployees();
                }
            });

            this.$set(this.edit_show, index, !this.edit_show[index]);
        },
        collapse(id, effect){
            $('#colaborador' + id).collapse(effect);
        }
    }
});