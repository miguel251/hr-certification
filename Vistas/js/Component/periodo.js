new Vue({
    el:'#periodo',
    data:{
        id_periodo:'',
        periodos:'',
        periodo:'',
        //variables de formulario
        fechaInicial:'',
        fechaFinal:'',
        titulo:'',
        activo: 0
    },
    mounted(){
        this.getPeriodos();
    },
    methods:{
        getPeriodos: function(){            
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    function: 'periodos',
                }
            }).then(response =>{               
                this.periodos = response.data;
            });
        },
        findPeriodo: function(id){
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    id:id,
                    function: 'buscar',
                }
            }).then(response =>{           
                this.id_periodo = response.data[0].id_periodo;           
                this.titulo = response.data[0].titulo;
                this.fechaInicial = response.data[0].fecha_inicio;
                this.fechaFinal = response.data[0].fecha_final;
                this.activo = response.data[0].activo;
            });
        },
        addPeriodo: function(e){
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    id_periodo: this.id_periodo,           
                    titulo: this.titulo,
                    fechaInicial: this.fechaInicial,
                    fechaFinal: this.fechaFinal,
                    activo: 0,
                    function: 'agregar',
                }
            }).then(response =>{
                if(response.data.estado == 1){
                    this.success(response.data.mensaje);
                    this.getPeriodos();
                }
                
            });
            e.preventDefault();
        },
        updatePeriodo:function(){
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    activo: this.activo,
                    id:this.id_periodo,
                    titulo: this.titulo,
                    fechaInicial: this.fechaInicial,
                    fechaFinal: this.fechaFinal,
                    function: 'actualizar',
                }
            }).then(response =>{
                
                if(response.data.estado == 0){
                    this.alert(response.data.mensaje);
                    this.findPeriodo(this.id_periodo);
                }else if(response.data.estado == 1){
                    this.getPeriodos();
                    this.success(response.data.mensaje);
                }else if(response.data.estado == -1){
                    this.findPeriodo(this.id_periodo);
                    this.error(response.data.mensaje);
                }else if(response.data.estado == -2){
                    this.findPeriodo(this.id_periodo);
                    this.alert(response.data.mensaje);
                }
                
            });
        },
        alertDelete: function(id, estado){
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Si, eliminar!',
                cancelButtonText: 'Cancelar'
              }).then((result) => {
                if (result.value) {
                    if(estado == 1){
                        Swal.fire(
                            'Oops...',
                            'No se puede eliminar un periodo activo.',
                            'warning'
                          )
                          return;
                    }                   
                    this.deletePeriodo(id);
                }
              })
        },
        deletePeriodo: function(id){
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    id_periodo: id,
                    function: 'eliminar',
                }
            }).then(response =>{
                if(response.data == 1){
                    this.success('El periodo se elimino');
                    this.getPeriodos();
                }else{
                    this.error('Error al eliminar el periodo')
                }
            });
        },
        formatDate: function(date){

            let formatdate = new Date(date);
            let options = {  year: 'numeric', month: 'short', day: 'numeric' };
            return formatdate.toLocaleDateString("es-MX", options);
        },
        clearData: function(){
            this.id_periodo=null;
            this.fechaInicial=null;
            this.fechaFinal=null;
            this.titulo=null;
            this.activo= 0;
        },
        error:function(){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
              })    
        },
        alert:function(message){
            Swal.fire({
                icon: 'warning',
                title: 'Error',
                text: message,
              })
        },
        success:function(message){
            Swal.fire({
                icon: 'success',
                title: 'Completado',
                text: message,
              })
        }
    }
    
});