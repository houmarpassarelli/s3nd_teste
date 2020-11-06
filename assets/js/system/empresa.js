new Vue({
    el: '#empresa',
    data: {
        employees : null,
        edit_show : [],        
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
        async updateEmployees(e, id){
            
            let data = new FormData(document.getElementById(e.target.id));

            data.append("request","update");

            axios.post(this.api + id, data)
            .then(response => { console.log(response) });
        }
    }
});