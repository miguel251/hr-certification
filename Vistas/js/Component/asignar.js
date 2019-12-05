new Vue({
    el:'#asignar',
    data:{
        puestos:[],
        grupos:[],
        conductaAsignado:[],
        conductaAsignar:[],
        conductasPuesto:[],
        competencias:[],
        conductas:[],
        competencia: 0,
        puesto:'',
        grupo:'',
        definicion:''
    },
    mounted(){
        this.getPuestos();
    },
    methods:{
        getPuestos: function(){            
            axios.post('/jmdistributions/Hr/Controlador/PuestoController',{
                data:{
                    function:'puestos',
                }
            }).then(response =>{           
                this.puestos = response.data;
            });
        },
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
                this.competencias = response.data;
            });
        },
        getConductas: function(){ //Obtiene todas las conductas con relacion al filtro de competencias 
            axios.post('/jmdistributions/Hr/Controlador/PuestoController',{
                data:{
                    id_puesto: this.puesto, //id de competencia se envia cada que se hace un cambio en el filtro competencia
                    id_competenca: this.competencia,
                    function:'conductaSinAsignar',
                }
            }).then(response =>{
                this.conductas = response.data;
            });
        },
        findConductasPuesto:function() {
            axios.post('/jmdistributions/Hr/Controlador/PuestoController',{
                data:{
                    id:this.puesto, //id de puesto
                    function:'conductasAsignadas',
                }
            }).then(response =>{
                this.getGrupos();
                this.getConductas();
                this.clearArray();
                this.conductasPuesto = response.data;
                for(let i in this.conductasPuesto){
                    this.conductaAsignado.push(this.conductasPuesto[i].id_conducta);
                }
            });
        },
        unassignCompetencia:function (id_conducta) {
            axios.post('/jmdistributions/Hr/Controlador/PuestoController',{
                data:{
                    id_conducta:id_conducta,
                    function:'quitarConducta',
                }
            }).then(response =>{
                this.findConductasPuesto();
                this.getConductas();
            });                    
        },
        assignCompetencia:function(id_conducta) {          
            axios.post('/jmdistributions/Hr/Controlador/PuestoController',{
                data:{
                    id_puesto:this.puesto,
                    id_conducta:id_conducta,
                    function:'asignarConducta',
                }
            }).then(response =>{                
                this.findConductasPuesto();
                this.getConductas();
                this.conductaAsignar.length = 0;
            }); 
        },
        findCompetencia: function() {//Busca una competencia
            axios.post('/jmdistributions/Hr/Controlador/CompetenciaController',{
                data:{
                    id: this.competencia, //id de competencia
                    function:'buscar',
                }
            }).then(response =>{                
                this.definicion = response.data[0].definicion;
                this.getConductas();
            });
        },
        formatPuesto:function(puesto) {
            return puesto.charAt(0) + puesto.slice(1).toLowerCase(); 
        },
        clearArray:function() {
          this.conductaAsignado.length = 0;
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