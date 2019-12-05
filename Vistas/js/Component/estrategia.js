new Vue({
    el:'#estrategia',
    data:{
        id_alineacion:'',
        estrategias:'',
        estrategia:'',
        contador:0,
        //variables de formulario
        concepto:'',
        descripcion:'',
    },
    mounted(){
        this.getEstrategias();
    },
    methods:{
        getEstrategias: function(){            
            axios.post('/jmdistributions/Hr/Controlador/AlineacionController',{
                data:{
                    function: 'alineacion',
                }
            }).then(response =>{                              
                this.estrategias = response.data;
            });
        },
        findAlineacion: function(id){
            axios.post('/jmdistributions/Hr/Controlador/AlineacionController',{
                data:{
                    id:id,
                    function: 'buscar',
                }
            }).then(response =>{
                this.id_alineacion = response.data[0].id_alineacion;           
                this.concepto = response.data[0].concepto;
                this.descripcion = response.data[0].descripcion;
            });
        },
        addAlineacion: function(e){
            axios.post('/jmdistributions/Hr/Controlador/AlineacionController',{
                data:{
                    concepto: this.concepto,           
                    descripcion: this.descripcion,
                    function: 'agregar',
                }
            }).then(response =>{
                if(response.data.estado == 1){
                    this.success(response.data.mensaje);
                    this.getEstrategias();
                }
                
            });
            e.preventDefault();
        },
        updateAlineacion:function(){
            axios.post('/jmdistributions/Hr/Controlador/AlineacionController',{
                data:{
                    id:this.id_alineacion,
                    concepto: this.concepto,
                    descripcion: this.descripcion,
                    function: 'actualizar',
                }
            }).then(response =>{
                if (response.data == 1) {
                    this.getEstrategias();
                    this.success('La alineación estratégica se actulizo.');
                }else{
                    this.error('Error al actualizar');
                }
            });
        },
        alertDelete: function(id){
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
                    this.deleteAlineacion(id);
                }
              })
        },
        deleteAlineacion: function(id){
            axios.post('/jmdistributions/Hr/Controlador/AlineacionController',{
                data:{
                    id_periodo: id,
                    function: 'eliminar',
                }
            }).then(response =>{
                console.log(response.data);
                
                if(response.data == 1){
                    this.success('La alineación estratégica se elimino.');
                    this.getEstrategias();
                }else{
                    this.error('Error al eliminar.');
                }
            });
        },
        clearData: function(){
            this.id_alineacion=null;
            this.concepto=null;
            this.descripcion=null;
        },
        error:function(message){
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