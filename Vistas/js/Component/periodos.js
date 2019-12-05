new Vue({
    el:'#periodos',
    data:{
        objetivos:[],
        periodos:[],
        conductas:[],
        areas:[],
        showPeriodo: false,
        empleados:[],
        periodo:'',
        comentarioGeneralS:'',
        comentarioGeneralE:'',
        comentarioEmpleado:'',
        comentarioSupervisor:'',
        promedioConducta:0,
        promedio:0,
        evaluacion:0,
        id_puesto:0,
        id_area:0,
        id_empleado:0,
        id_periodo:0,
        id_objetivo:0,
    },
    mounted(){
        this.getDatosEmpleados();
        this.getArea(); 
    },
    methods:{
        getDatosEmpleados:function() {
            axios.post('/jmdistributions/Hr/Controlador/EmpleadoController',{
                data:{
                    function: 'empleadosPeriodo',
                }
            }).then(response =>{    
                this.empleados = response.data;                 
            });
        },
        getArea:function() {
            axios.post('/jmdistributions/Hr/Controlador/EmpleadoController',{
                data:{
                    function: 'areasPeriodo',
                }
            }).then(response =>{                                
                this.areas = response.data;   
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
                    id_periodo: this.periodo,
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
        findArea: function(id_empleado) {
            axios.post('/jmdistributions/Hr/Controlador/EmpleadoController',{
                data:{
                    id_empleado: id_empleado,
                    function: 'buscarAreaEmpleado',
                }
            }).then(response =>{                    
                this.id_area = response.data[0].id_area;
                this.areas = response.data;
                this.periodos = [];
                this.periodo = 0;
                this.objetivos = [];
                this.conductas = [];
                this.showPeriodo = true;
                let empleado = [];
                empleado = this.empleados.find(empleado => empleado.id_empleado === id_empleado);                
                this.id_puesto = empleado.id_puesto;
                this.getPeriodo();
            });
        },
        findEmpleados:function(id_area) {
            if(id_area === 'todos'){
                this.id_periodo = 0;
                this.id_empleado = 0;
                this.showPeriodo = false;
                this.getDatosEmpleados();
                return;
            }
            axios.post('/jmdistributions/Hr/Controlador/EmpleadoController',{
                data:{
                    id_area: id_area,
                    function: 'buscarEmpleadoArea',
                }
            }).then(response =>{    
                this.empleados = response.data;
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