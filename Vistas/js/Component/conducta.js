new Vue({
    el:'#conducta',
    data:{
        id_grupo:'',
        id_conducta:'',
        grupos:'',
        grupo:'',
        conductas:[],
        competencias:[],
        competencia: 0,
        definicionComp: '',
        definicion:'', 
        descripcionConducta: '',
        conducta:0,
        titulo:'',
        definicion:''
    },
    mounted(){
        this.getGrupos();
    },
    methods:{
        getGrupos: function(){//Obtiene todos los grupos del primer filtro 
            axios.post('/jmdistributions/Hr/Controlador/GrupoController',{
                data:{
                    function:'grupos',
                }
            }).then(response =>{
                this.grupos = response.data;
            });
        },
        getCompetencia:function() { //Obtiene las competencias en base al id del grupo seleccionado (segundo filtro depende del primero)
            axios.post('/jmdistributions/Hr/Controlador/CompetenciaController',{
                data:{
                    id: this.grupo, //id del grupo
                    function:'competencias',
                }
            }).then(response =>{
                this.competencia = 0;//cambia a 0 el id cada que se cambia la competencia
                this.definicionComp = null; // reset a definicion de competencia
                this.competencias = response.data;
            });
        },
        getConductas: function(){ //Obtiene todas las conductas con relacion al filtro de competencias 
            axios.post('/jmdistributions/Hr/Controlador/CompetenciaController',{
                data:{
                    id: this.competencia, //id de competencia se envia cada que se hace un cambio en el filtro competencia
                    function:'conductas',
                }
            }).then(response =>{               
                this.conductas = response.data;
                
            });
        },
        addConducta:function() {//Agrega una conducta nueva
            axios.post('/jmdistributions/Hr/Controlador/CompetenciaController',{
                data:{
                    descripcion: this.descripcionConducta,
                    id:this.competencia,
                    function:'guardarConducta',
                }
            }).then(response =>{               
                if(response.data.estado == 1){
                    this.success('La conducta se agrego.');
                    this.getConductas();
                }else{
                    this.error(response.data.mensaje);
                }
            });
        },
        addCompetencia: function() {//Agrega competencia
            axios.post('/jmdistributions/Hr/Controlador/CompetenciaController',{
                data:{
                    id: this.grupo,
                    competencia: this.titulo,
                    definicion: this.definicion,
                    function:'guardar',
                }
            }).then(response =>{                
                if(response.data == 1){
                    this.success('La competencia se agrego.');
                    this.getCompetencia();
                }else{
                    this.error('Error al agregar la competencia.');
                }
            });
        },
        findCompetencia: function() {//Busca una competencia (se utilizan para editar)
            axios.post('/jmdistributions/Hr/Controlador/CompetenciaController',{
                data:{
                    id: this.competencia, //id de competencia
                    function:'buscar',
                }
            }).then(response =>{                
                this.definicionComp = response.data;
                this.definicion = response.data[0].definicion;
                this.getConductas();
            });
        },
        findConducta:function (id) {//Busca una conducta (se utilizan para editar)
            axios.post('/jmdistributions/Hr/Controlador/CompetenciaController',{
                data:{
                    id: id, // id de conducta
                    function:'buscarConducta',
                }
            }).then(response =>{
                this.idConducta = response.data[0].id_conducta;
                this.descripcionConducta = response.data[0].descripcion;
            });
        },
        updateCompetencia:function() {//Actualiza competencia
            axios.post('/jmdistributions/Hr/Controlador/CompetenciaController',{
                data:{
                    id: this.competencia,//id competencia
                    definicion: this.definicion,
                    function:'actualizarCompetencia',
                }
            }).then(response =>{
                if(response.data == 1){
                    this.success('La competencia se actualizo.');
                    this.findCompetencia();
                }else{
                    this.error('Error al actualizar.');
                }               
            });
        },
        updateConducta:function() {//actualiza conducta
            axios.post('/jmdistributions/Hr/Controlador/CompetenciaController',{
                data:{
                    id: this.idConducta,
                    descripcion: this.descripcionConducta,
                    function:'actualizarConducta',
                }
            }).then(response =>{
                if(response.data == 1){
                    this.success('La conducta se actualizo');
                    this.getConductas();
                }else{
                    this.error('Error al actualizar');
                }
            });
        },
        deleteConducta: function(idConducta) {//Elimina conducta
            axios.post('/jmdistributions/Hr/Controlador/CompetenciaController',{
                data:{
                    id: idConducta,
                    function:'eliminarConducta',
                }
            }).then(response =>{               
                if(response.data == 1 ){
                    this.success('La conducta se elimino.');
                    this.getConductas();
                }else{
                    this.error('Error al eliminar.');
                }
            });
        },
        //---------Alert------//
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
                    this.deleteConducta(id);
                }
              })
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
        clearData: function() { //Reset a variables
            this.titulo = null;
            this.definicion = null;
            this.idConducta = null;
            this.descripcionConducta = null;
        }
    }
    
});