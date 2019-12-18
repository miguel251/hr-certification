new Vue({
    el:'#evalua',
    data: {
        id_empleado:  new URLSearchParams(location.search),
        id_periodo:0,
        id_objetivo: 0,
        unidades:[],
        relaciones:[],
        balanceds:[],
        alineaciones:[],
        objetivos:'',
        objetivo:'',
        empleado:'',
        relacion:'',
        referencia:'',
        comentario:'',
        ponderacion: 0,
        fecha_entrega:'',
        valorReferencia: 0,
        descripcion:'',
        balanced:'',
        resultadoEsperado:'',
        resultado:0,
        unidad:'',
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
            });
        },
        getAllData: function (){
            this.clearInput();
            this.getUnidad();
            this.getRelacion();
            this.getBalanced();
            this.getAlineacion();
        },
        getUnidad: function (){
            axios.post('/jmdistributions/Hr/Controlador/UnidadController',{
                data:{
                    function: 'unidad',
                }
            }).then(response =>{
                this.unidades = response.data;

            });
        },
        getRelacion:function (){
            axios.post('/jmdistributions/Hr/Controlador/RelacionController',{
                data:{
                    function: 'relacion',
                }
            }).then(response =>{                            
                this.relaciones = response.data;

            });
        },
        getBalanced: function(){
            axios.post('/jmdistributions/Hr/Controlador/BalancedController',{
                data:{
                    function: 'balanced',
                }
            }).then(response =>{                                           
                this.balanceds = response.data;

            });
        },
        getAlineacion: function(){
            axios.post('/jmdistributions/Hr/Controlador/AlineacionController',{
                data:{
                    function: 'alineacion',
                }
            }).then(response =>{                         
                this.alineaciones = response.data;

            });
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
                this.getAllData();                
                this.descripcion = response.data[0].descripcion;
                this.resultado = response.data[0].resultado_esperado;
                this.unidad = response.data[0].id_unidad;
                this.relacion = response.data[0].id_relacion;
                this.ponderacion = response.data[0].ponderacion;
                this.fecha_entrega = response.data[0].fecha_entrega;
                this.balanced = response.data[0].id_balance;
                this.objetivo = response.data[0].id_alineacion;
                this.id_objetivo = response.data[0].id_objetivo;
                this.referencia = response.data[0].valor_referencia;
                this.comentario = response.data[0].comentario_supervisor;
                
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
        validateUpdate: function(e){
            //Dependera del valor del id de la tabla relacion (mas es mejor-1 o menos es mejor-2)
            if(this.referencia > this.resultado && this.relacion == 1){
                this.alert('El valor de referencia no puede ser mayor al resultado esperado');
            }else if(this.referencia <= this.resultado && this.relacion == 2){
                this.alert('El valor de referencia no puede ser menor o igual a resultado esperado');
            }else{
                this.updateObjective();
            }
            
         e.preventDefault();
        },
        updateObjective: function(){
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id: this.id_objetivo,
                    function: 'actualizar',
                    descripcion: this.descripcion,
                    resultado: this.resultado,
                    unidad: this.unidad,
                    relacion: this.relacion,
                    ponderacion: this.ponderacion,
                    fecha_entrega: this.fecha_entrega,
                    balanced: this.balanced,
                    objetivo: this.objetivo,
                    referencia: this.referencia,
                    comentario: this.comentario,
                }
            }).then((response) =>{
                if(response.data){
                    this.success('El objetivo se actualizo correctamente.');
                    this.getObjective();
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