new Vue({
    el:'#evalua',
    data: {
        id_empleado:  new URLSearchParams(location.search),
        id_periodo:0,
        idConducta:0,
        bandera:false,
        banderaConducta:3,
        objetivos:'',
        conductas:[],
        empleado:'',
        evaluacion:0,
        relacion:'',
        promedio: 0,
        promedioConducta:0,
        pesoObjetivo: 0,
        pesoCompetencia:0,
        id_objetivo: 0,
        ponderacion: 0,
        valorReferencia: 0,
        relacion:'',
        competencias:[],
        userGuest:'http://localhost/jmdistributions/Hr/Vistas/invitado/?user=',
        userPass:'',
        //variables de formulario
        descripcion:'',
        resultadoEsperado:'',
        unidad:'',
        resultadoSugerido:0,
        resultadoObtenido:'',
        comentarioGeneralS:'',
        comentarioGeneralE:'',
        comentarioEmpleado:'',
        comentarioSupervisor:'',
        sugerenciaCalidad:'',
        sugerenciaFrecuencia:'',
        //Criterios
        calidad:'',
        calidades:'',
        frecuencia:'',
        frecuencias:'',
        criterioCalidad:'',
        criteriosP:[],
    },
    mounted(){
        this.getDatosEmpleado();
    },
    methods:{
        getDatosEmpleado:function() {
            axios.post('/jmdistributions/Hr/Controlador/EmpleadoController',{
                data:{
                    id: this.id_empleado.get('id'),
                    function: 'buscar',
                }
            }).then(response =>{
                this.empleado = response.data;
                this.getPeriodos(response.data[0].id_puesto);
                this.getObjective();
                this.getCriterioPesos();   
                          
            });
        },
        getAllData: function (){
            this.clearInput();
            this.getUnidad();
            this.getRelacion();
        },
        formatNumber: function(number){            
            number = parseFloat(number);
            return number.toFixed(2);
        },
        getPeriodos: function(id_puesto){            
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    function: 'periodo',
                }
            }).then(response =>{                      
                this.id_periodo = response.data[0].id_periodo;
                this.getConductas(id_puesto, this.id_periodo);
                this.getComentarios(this.id_empleado.get('id'));
            });
        },
        getObjective: function(){
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id: this.id_empleado.get('id'),
                    function: 'objetivoPeriodo',
                }
            }).then(response =>{  
                this.objetivos = response.data;
                this.getPromedio(this.objetivos);
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
                this.getPesos(this.promedio);
            });
        },
        getPesos: function(promedio){
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
                this.banderaConducta +=1;
                if(this.banderaConducta == 2){
                    this.addCalificacionPeriodo(this.evaluacion);
                    this.bandera = 0;
                }else if(this.bandera){
                    this.addCalificacionPeriodo(this.evaluacion);
                    this.bandera = false;
                }
            });
        },
        addCalificacionPeriodo:function (calificacion) {
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    id_empleado: this.id_empleado.get('id'),
                    id_periodo: this.id_periodo,
                    calificacion: calificacion,
                    function: 'agregarCalificacionPeriodo',
                }
            }).then(response =>{ 
            });
        },
        getUnidad: function(){
            axios.post('/jmdistributions/Hr/Controlador/UnidadController',{
                data:{
                    function: 'unidad',
                }
            }).then(response =>{
                this.unidades = response.data;

            });
        },
        getComentarios: function(id_empleado) {
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    id_empleado:id_empleado,
                    id_periodo: this.id_periodo,
                    function:'getcomentarios',
                }
            }).then(response =>{
                if (typeof response.data[0] != 'undefined') {
                    let comentarioSupervisor = response.data[0].comentario_supervisor;
                    let comentarioEmpleado = response.data[0].comentario_empleado;
                    this.comentarioGeneralS = comentarioSupervisor != null ? comentarioSupervisor : '';
                    this.comentarioGeneralE = comentarioEmpleado != null ? comentarioEmpleado : '';
                }
            });  
        },
        //Criterio eval
        getCriterioCalidad:function() {
            axios.post('/jmdistributions/Hr/Controlador/CriterioController',{
                data:{
                    function:'getCalidad',
                }
            }).then(response =>{
                this.calidades = response.data;
            });  
        },
        getCriterioFrecuencia:function() {
            axios.post('/jmdistributions/Hr/Controlador/CriterioController',{
                data:{
                    function:'getFrecuencia',
                }
            }).then(response =>{
                this.frecuencias = response.data;
            });  
        },
        //fin criterio
        evalConducta:function() {
            axios.post('/jmdistributions/Hr/Controlador/CompetenciaController',{
                data:{
                    id_periodo: this.id_periodo,
                    id_empleado: this.id_empleado.get('id'),
                    id_conducta: this.idConducta,
                    id_calidad: this.calidad,
                    id_frecuencia: this.frecuencia,
                    tipo:'supervisor',
                    function: 'eval',
                }
            }).then(response =>{
                if(response.data == 1){
                    this.getDatosEmpleado();
                    this.success('Se guardo la evaluacion.');
                    this.banderaConducta = 0;
                }else{
                    this.error('Error al guardar.')
                }                       
            });
        },
        findUnidad(id){
            axios.post('/jmdistributions/Hr/Controlador/UnidadController',{
                data:{
                    id: id,
                    function: 'buscar',
                }
            }).then((response) =>{
                this.unidad = response.data[0].unidad;
            });
        },
        findRelacion(id){
            axios.post('/jmdistributions/Hr/Controlador/RelacionController',{
                data:{
                    id: id,
                    function: 'buscar',
                }
            }).then((response) =>{
                this.relacion = response.data[0].relacion;
            });
        },
        findObjective(id){
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id: id,
                    function: 'buscar',
                }
            }).then((response) =>{
                this.id_objetivo = id;            
                this.descripcion = response.data[0].descripcion;
                this.resultadoEsperado = response.data[0].resultado_esperado;
                this.resultadoObtenido = response.data[0].valor_obtenido ? response.data[0].valor_obtenido : '' ;
                this.ponderacion = response.data[0].ponderacion;
                this.valorReferencia = response.data[0].valor_referencia;
                this.resultadoSugerido = response.data[0].valor_sugerencia != null ? response.data[0].valor_sugerencia : 0;
                this.findUnidad(response.data[0].id_unidad);
                this.findRelacion(response.data[0].id_relacion);
            });
        },
        findConducta:function (id) {
            axios.post('/jmdistributions/Hr/Controlador/CompetenciaController',{
                data:{
                    id: id, // id de conducta
                    function:'buscarConducta',
                }
            }).then(response =>{
                this.competencias = response.data;
                this.getCriterioCalidad();
                this.getCriterioFrecuencia();
                this.findCriterio(id);
                this.idConducta = response.data[0].id_conducta;
            });
        },
        findCriterio:function(id_conducta) {
            axios.post('/jmdistributions/Hr/Controlador/CompetenciaController',{
                data:{
                    id_conducta: id_conducta, // id de conducta
                    id_empleado: this.id_empleado.get('id'),
                    id_periodo: this.id_periodo,
                    function:'buscarCriterio',
                }
            }).then(response =>{
                if(response.data == 0){
                    return;
                }else{                    
                    this.calidad = response.data[0].id_calidad;
                    this.frecuencia = response.data[0].id_frecuencia;
                    this.sugerenciaCalidad = response.data[0].id_calidad_sugerencia;
                    this.sugerenciaFrecuencia = response.data[0].id_frecuencia_sugerencia;
                }
            });
        },
        findComentarios:function(id) {
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id: id, // id de objetivo
                    function:'buscarComentario',
                }
            }).then(response =>{
                
                if(typeof response.data[0] != 'undefined'){
                    this.id_objetivo = id;
                
                    let comentearioSupervisor = response.data[0].comentario_supervisor;
                    let comentarioEmpleado = response.data[0].comentario_empleado;
    
                    this.comentarioEmpleado = comentarioEmpleado != null ? comentarioEmpleado : ' ';
                    this.comentarioSupervisor = comentearioSupervisor != null ? comentearioSupervisor : ' ';
                }
            });
        },
        addComentarioGeneral:function(event){
            event.preventDefault();
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    id_empleado: this.id_empleado.get('id'),
                    id_periodo: this.id_periodo,
                    comentario: this.comentarioGeneralS,
                    function:'comentarioSupervisor',
                }
            }).then(response =>{
                if(response.data === 1 ){
                    this.success('Se agrego el comentario.');
                }else{
                    this.error('Error al añadir el comentario.');
                }
            });
        },
        addComentario:function(event) {
            event.preventDefault();
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id: this.id_objetivo, // id de objetivo
                    comentario: this.comentarioSupervisor,
                    function:'comentarioSupervisor',
                }
            }).then(response =>{
                if(response.data === 1 ){
                    this.success('Se agrego el comentario.');
                }else{
                    this.error('Error al añadir el comentario.');
                }
                
            });
        },
        getCriterioPesos:function(){
            axios.post('/jmdistributions/Hr/Controlador/CriterioController',{
                data:{
                    function: 'criterioP',
                }
            }).then(response =>{   
                this.criteriosP = response.data;
            });
        },
        getConductas:function(id_puesto, id_periodo) {            
            axios.post('/jmdistributions/Hr/Controlador/PuestoController',{
                data:{
                    id_empleado:this.id_empleado.get('id'),
                    id_periodo: id_periodo,
                    id_puesto:id_puesto, //id de puesto
                    function:'conductasEvaluar',
                }
            }).then(response =>{
                this.conductas = response.data;
                this.evaluarConducta();
            });
        },
        getGuestUSer:function (id_supervisor) {
            axios.post('/jmdistributions/Hr/Controlador/UserGuestController',{
                data:{
                    id_empleado:this.id_empleado.get('id'),
                    id_supervisor: id_supervisor,
                    function:'userguest',
                }
            }).then(response =>{
                let regex = new RegExp('.*=[0-9]+$');                
                if(!regex.test(this.userGuest)){
                    this.userGuest += response.data[0].usuario;
                }
                this.userPass = response.data[0].contrasena;
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
                this.getPesos(this.promedio);
            });
        },
        calculate: function(){
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id: this.id_objetivo,
                    function: 'calcular',
                    resultadoObtenido: this.resultadoObtenido,
                    resultadoEsperado: this.resultadoEsperado,
                    ponderacion: this.ponderacion,
                    valorReferencia: this.valorReferencia,
                    relacion: this.relacion
                }
            }).then((response) =>{                
                if(response.data.estado == 1)
                {
                    this.success(response.data.mensaje);
                    this.getObjective();
                    this.bandera = true;                    
                }else{
                    this.error(response.data.mensaje);
                }
                
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
                type: 'error',
                title: 'Oops...',
                text: message,
              })
        },
        clearInput: function(){
            this.unidad= '';           
            this.descripcion= '';
            this.resultado= '';
            this.medicion= '';
            this.relacion= '';
            this.ponderacion= '';
            this.fecha_entrega= '';
            this.balanced= '';
            this.objetivo= '';
            this.referencia= 0;
        }
    }
});