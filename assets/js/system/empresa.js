new Vue({
    el: '#empresa',
    data: {
        employees : null,
        expedient : null,
        edit_show : [],
        weekdays : ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'],     
        api : window.location.origin + '/api/empresa/'
    },
    mounted(){
        this.getEmployees();
    },
    methods:{
        async delEmployees(id){

            loading();

            const response = await axios.delete(this.api + id).then(response => response);

            if(response.status == 204){
                await this.getEmployees();
                this.collapse('all', 'hide');
                loading(false);
            }
        },
        async getEmployees(){

            loading(true);

            const response = await axios.get(this.api).then(response => response);
            await this.getOfficeExpedient();
            
            if(response.data.length > 0){

                this.employees = response.data;
                
                let length = 0;
            
                while(length < this.employees.length){
                    this.edit_show.push(false);    
                    length++;
                }

                loading(false);
            }
        },
        async createEmployee(e){

            loading(true, true);
            
            let data = new FormData(document.getElementById(e.target.id));

            data.append("request", "insert");

            let config = {
                    headers: {
                        'Content-Type': 'application/json;charset=UTF-8'
                    }
                };

            const response = await axios.post(this.api, data, config).then(response => response);

            if(response.status == 204){
                await this.getEmployees();
                $('#addmodal').modal('hide');
                this.resetForm();
                this.collapse('all', 'hide');
                loading(false);
            }
        },
        async updateEmployees(e, id, index){

            loading(true);

            let data = new FormData(document.getElementById(e.target.id));

            data.append("request","update");

            let config = {
                    headers: {
                        'Content-Type': 'application/json;charset=UTF-8'
                    }
                };

            const response = await axios.post(this.api + id, data, config).then(response => response);
            
            if(response.status == 204){
                await this.getEmployees();
                this.$set(this.edit_show, index, !this.edit_show[index]);
                loading(false);
            }
        },
        async getOfficeExpedient(){

            const response = await axios.get(this.api + "?acao=setofficeexpedient").then(response => response);
            
            if(response.data.length > 0){
                this.expedient = response.data;
            }
        },
        collapse(id, effect){
            if(id == 'all'){
                
                let length = 0;

                while(length < this.employees.length){
                    $('#colaborador' + length).collapse(effect);    
                    length++;
                }
            }else{
                $('#colaborador' + id).collapse(effect);
            }
        },
        resetForm(){

            let form = document.getElementById('form_cadastro');

            form.reset();
        }
    }
});