new Vue({
    el:'#misobjetivos',
    data:{
        objetivos:[],
        empleado:[],
        conductas:[],
        comentario:'',
        descripcion:'',
        competencias: '',
        calidad:'',
        frecuencia:'',
        calidades:'',
        sugerenciaFrecuencia:'',
        sugerenciaCalidad:'',
        frecuencias:'',
        comentarioEmpleado:'',
        comentarioSupervisor:'',
        comentarioGeneralS:'',
        resultadoEsperado:0,
        unidad:'',
        valorObtenido:0,
        valorSugerencia:0,
        promedioConducta:0,
        promedio:0,
        pesoObjetivo:0,
        evaluacion:0,
        idConducta: 0,
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
                console.log(response.data);
                               
                this.empleado = response.data[0];
                this.id_puesto = this.empleado.id_puesto;
                this.id_empleado = this.empleado.id_empleado;
                this.getPeriodo();
                this.getObjetivos();                              
            });
        },
        getObjetivos: function(){            
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id: this.id_empleado,
                    function: 'objetivoPeriodo',
                }
            }).then(response =>{                
                this.objetivos = response.data;
                this.getPromedio(this.objetivos);
            });
        },
        getPeriodo:function() {
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    function: 'periodo',
                }
            }).then(response =>{                            
                this.id_periodo = response.data[0].id_periodo;
                this.getConductas(this.id_periodo);
                this.getComentario();
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
        getComentario: function() {
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    id_empleado:this.id_empleado,
                    id_periodo: this.id_periodo,
                    function:'getcomentarios',
                }
            }).then(response =>{                
                if (typeof response.data[0] != 'undefined') {
                    this.comentario = response.data[0].comentario_empleado;
                    this.comentarioGeneralS = response.data[0].comentario_supervisor;
                }                                               
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
        addComentarioEmpleado: function(event){ //Comentarios generales del periodo
            event.preventDefault()
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    id_empleado:this.id_empleado,
                    id_periodo: this.id_periodo,
                    comentario: this.comentario,
                    function:'comentarioEmpleado',
                }
            }).then(response =>{
                if(response.data == 1){
                    this.success('El comentario se guardo.');
                }else{
                    this.error('Error al guardar el comentario.');
                }
            });
        },
        addValorSugerido: function(event) {
            event.preventDefault()
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id_objetivo: this.id_objetivo,
                    valor: this.valorSugerencia,
                    function:'addSugerencia',
                }
            }).then(response =>{                              
                if(response.data == 1){
                    this.success('El valor de sugerencia se guardo.');
                    this.getDatosEmpleado();
                }else{
                    this.error('Error al guardar la sugerencia.');
                }
            });
        },
        addComentario:function(event) { //Comentarios por objetivos
            event.preventDefault();
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id: this.id_objetivo, // id de objetivo
                    comentario: this.comentarioEmpleado,
                    function:'comentarioEmpleado',
                }
            }).then(response =>{
                if(response.data === 1 ){
                    this.success('El comentario se guardo.')
                }else{
                    this.error('Error al guardar el comentario.');
                }
            });
        },
        addSugerenciaCriterio:function() {
            axios.post('/jmdistributions/Hr/Controlador/CompetenciaController',{
                data:{
                    id_periodo: this.id_periodo,
                    id_empleado: this.id_empleado,
                    id_conducta: this.idConducta,
                    id_calidad: this.sugerenciaCalidad,
                    id_frecuencia: this.sugerenciaFrecuencia,
                    tipo:'sugerencia',
                    function: 'eval',
                }
            }).then(response =>{
                console.log(response.data);
                if(response.data == 1){
                    this.getDatosEmpleado();
                    this.success('Se guardo la evaluacion.');
                }else{
                    this.error('Error al guardar.')
                }                       
            });
        },
        findObjective: function (id) {
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id: id,
                    function: 'buscar',
                }
            }).then((response) =>{
                this.id_objetivo = id;            
                this.descripcion = response.data[0].descripcion;
                this.resultadoEsperado = response.data[0].resultado_esperado;
                this.valorObtenido = response.data[0].valor_obtenido ? response.data[0].valor_obtenido : '' ;
                this.valorSugerencia = response.data[0].valor_sugerencia != null ? response.data[0].valor_sugerencia : 0;
                this.comentario = response.data[0].comentario ? response.data[0].comentario : '';
                this.findUnidad(response.data[0].id_unidad);             
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
                    id_empleado: this.id_empleado,
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
        formatName: function(nombre) {
            nombre = new String(nombre);
            let reg = /^(\w+)\s(\w+)$/;
            let format ='';
            if(reg.test(nombre)){
                let temp = nombre.split(' ');
                temp.forEach(element => {
                    format += element.charAt(0) +  element.slice(1).toLowerCase() + ' ';
                });
                return format;
            }
            return nombre.charAt(0) +  nombre.slice(1).toLowerCase();
        },
        findComentarios:function(id) {
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id: id, // id de objetivo
                    function:'buscarComentario',
                }
            }).then(response =>{
                this.id_objetivo = id;
                
                let comentearioSupervisor = response.data[0].comentario_supervisor;
                let comentarioEmpleado = response.data[0].comentario_empleado;

                this.comentarioEmpleado = comentarioEmpleado != null ? comentarioEmpleado : ' ';
                this.comentarioSupervisor = comentearioSupervisor != null ? comentearioSupervisor : ' ';
            });
        },
        formatNumber: function(number){            
            number = parseFloat(number);
            return number.toFixed(2);
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