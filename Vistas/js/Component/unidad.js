new Vue({
    el:'#unidad',
    data:{
        unidades:[],
        unidad:'',
        id_unidad:0,
    },
    mounted(){
        this.getUnidades();
    },
    methods:{
        getUnidades: function(){            
            axios.post('/jmdistributions/Hr/Controlador/UnidadController',{
                data:{
                    function:'unidad',
                }
            }).then(response =>{                      
                this.unidades = response.data;
            });
        },
        addUnidad:function(event){
            event.preventDefault();
            axios.post('/jmdistributions/Hr/Controlador/UnidadController',{
                data:{
                    unidad: this.unidad,
                    function:'addUnidad',
                }
            }).then(response =>{  
                console.log(response.data);
                                    
                if(response.data == 1){
                    this.success('Se agrego la unidad.');
                    this.getUnidades();
                }else if(response.data == -1){
                    this.alert('La unidad ya existe');
                }else{
                    this.error('Error al guardar');
                }
                
            });
        },
        deleteUnidad:function(id_unidad) {
        },
        updateUnidad:function(event){
            event.preventDefault();
            axios.post('/jmdistributions/Hr/Controlador/UnidadController',{
                data:{
                    id_unidad: this.id_unidad,
                    unidad: this.unidad,
                    function:'updateUnidad',
                }
            }).then(response =>{  
                if(response.data == 1)
                {
                    this.success('La unidad se actualizo.');
                    this.getUnidades();
                }else{
                    this.error('Error al actulizar.');
                }
            });
        },
        findUnidad:function(id_unidad) {
            axios.post('/jmdistributions/Hr/Controlador/UnidadController',{
                data:{
                    id:id_unidad,
                    function:'buscar',
                }
            }).then(response =>{                                      
                this.unidad = response.data[0].unidad;
                this.id_unidad = response.data[0].id_unidad;                
            });
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
        },
        clearData:function () {
            this.unidad = '';
            this.id_unidad = 0;
        }
    }
    
});