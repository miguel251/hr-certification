new Vue({
    el:'#avance',
    data:{
        periodos:0,
        supervisores:0,
        id_supervisor:0,
        id_periodo:0,
        avances:[],
    },
    mounted(){ 
        this.getPeriodos();
    },
    methods:{
        getPeriodos:function () {
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController.php',{
                data:{
                    function:'PeriodoAsignado',
                }
            }).then(response =>{                          
                this.periodos = response.data;
            });  
        },
        getSupervisores:function(id_periodo) {
            axios.post('/jmdistributions/Hr/Controlador/EmpleadoController.php',{
                data:{
                    id_periodo: id_periodo,
                    function:'supervisor',
                }
            }).then(response =>{
                this.id_supervisor = 0;              
                this.supervisores = response.data;
            });    
        },
        findAvance:function (id_supervisor) {
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController.php',{
                data:{
                    id_periodo: this.id_periodo,
                    id_empleado: id_supervisor,
                    function:'avance',
                }
            }).then(response =>{                
                this.avances = response.data; 
            });  
        },
        success: function(message){
            Swal.fire(
                'Completado',
                 message,
                'success'
              )
        },
        error: function(message){
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
              })
        },
        clearData:function() {
        }
    }
    
});
//3N1CK3CD2HL239378