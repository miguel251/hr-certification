new Vue({
    el:'#misperiodos',
    data:{
        objetivos:[],
        periodos:[],
        conductas:[],
        periodo:'',
        comentarioGeneralS:'',
        comentarioGeneralE:'',
        comentarioEmpleado:'',
        comentarioSupervisor:'',
        promedioConducta:0,
        promedio:0,
        evaluacion:0,
        id_puesto:0,
        id_empleado:0,
        id_periodo:0,
        id_objetivo:0,
    },
    mounted(){
        this.getDatosEmpleado();
    },
    methods:{
        getDatosEmpleado:function() {
            axios.post('/jmdistributions/Hr/Controlador/EmpleadoController',{
                data:{
                    id: 0,
                    function: 'buscar',
                }
            }).then(response =>{ 
                this.id_empleado = response.data[0].id_empleado;
                this.id_puesto = response.data[0].id_puesto;
                this.getPeriodo();                         
            });
        },
        getObjetivos: function(periodo){            
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id_empleado: this.id_empleado,
                    id_periodo: periodo,
                    function: 'buscaObjetivos',
                }
            }).then(response =>{               
                this.objetivos = response.data;
                this.getPromedio(this.objetivos);
            });
        },
        getPeriodo:function() {
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    idEmpleado: this.id_empleado,
                    function: 'periodoEmpleado',
                }
            }).then(response =>{                
                this.periodos = response.data;
            });
        },
        getConductas:function(id_periodo) {
            axios.post('/jmdistributions/Hr/Controlador/PuestoController',{
                data:{
                    id_empleado:this.id_empleado,
                    id_periodo: id_periodo,
                    id_puesto:this.id_puesto,
                    function:'conductasEvaluar',
                }
            }).then(response =>{                                                             
                this.conductas = response.data;
                this.evaluarConducta();
            });
        },
        getPromedio:function(objetivos){
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    objetivos: objetivos,
                    function: 'promedio',
                }
            }).then(response =>{            
                this.promedio = response.data;
                this.getpromedioConducta(this.promedio);
            });
        },
        getpromedioConducta: function(promedio){
            axios.post('/jmdistributions/Hr/Controlador/PesoController',{
                data:{
                    function: 'peso',
                }
            }).then(response =>{
                let evalConducta = 0;
                evalConducta = parseFloat(this.promedioConducta  ? this.promedioConducta : 0);
                evalConducta = (evalConducta/100) * response.data[0].peso_competencia;
                this.pesoObjetivo = response.data[0].peso_objetivo;
                this.evaluacion =  ((promedio/100) * this.pesoObjetivo) + evalConducta;
                
            });
        },
        findEvaluation: function() {
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    id_empleado:this.id_empleado,
                    id_periodo: this.periodo,
                    function:'getcomentarios',
                }
            }).then(response =>{
                if (typeof response.data[0] != 'undefined') {
                    let comentarioSupervisor = response.data[0].comentario_supervisor;
                    let comentarioEmpleado = response.data[0].comentario_empleado;
                    this.comentarioGeneralS = comentarioSupervisor != null ? comentarioSupervisor : '';
                    this.comentarioGeneralE = comentarioEmpleado != null ? comentarioEmpleado : '';
                }
                this.getObjetivos(this.periodo);
                this.getConductas(this.periodo);
            });  
        },
        findComentarios:function(id) {
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id: id, // id de objetivo
                    function:'buscarComentario',
                }
            }).then(response =>{                                
                let comentearioSupervisor = response.data[0].comentario_supervisor;
                let comentarioEmpleado = response.data[0].comentario_empleado;

                this.comentarioEmpleado = comentarioEmpleado != null ? comentarioEmpleado : ' ';
                this.comentarioSupervisor = comentearioSupervisor != null ? comentearioSupervisor : ' ';
            });
        },
        evaluarConducta:function () {
            axios.post('/jmdistributions/Hr/Controlador/PuestoController',{
                data:{
                    conductas: this.conductas,
                    function:'evaluarConductas',
                }
            }).then(response =>{
                this.promedioConducta = response.data.promedio;
                this.getpromedioConducta(this.promedio);
            });
        },
        formatNumber: function(number){            
            number = parseFloat(number);
            return number.toFixed(2);
        },
    }
    
});