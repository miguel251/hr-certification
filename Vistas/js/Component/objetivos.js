Vue.component('modal', {
    template: '#modal-template'
  })

    new Vue({
    el:'#app',
    data: {
        showModal: false,
        message: new URLSearchParams(location.search),
        asignar_periodo: true,
        empleado: '',
        comentario:'',
        referencia: 0,
        periodos: '',
        total: 0,
        elimanado: 0,
        id_objetivo: 0,
        objetivos: '',
        unidades: '',
        relaciones: '',
        balanceds: '',
        alineaciones: '',
        unidad:'',
        descripcion: '',
        resultado: '',
        medicion: '',
        relacion: '',
        ponderacion: '',
        fecha_entrega: '',
        balanced: '',
        objetivo: '',
        periodo:''
    },
    mounted(){
        axios.post('/jmdistributions/Hr/Controlador/EmpleadoController',{
            data:{
                id: this.message.get('id'),
                function: 'buscar',
            }
        }).then(response =>{
            this.empleado = response.data;
            this.getObjective();
        });
    },
    methods:{
        warning: function(id)
        {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonText: 'Eliminar'
            }).then((result) => {
                if (result.value) {
                    this.deleteObjective(id);
                Swal.fire(
                    '¡Eliminado!',
                    'El objetivo se elimino.',
                    'success'
                )
                }
            });
        },
        getObjective: function(){
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id: this.message.get('id'),
                    function: 'objetivo',
                }
            }).then(response =>{
                this.total = response.data.total;
                delete response.data.total;                
                this.objetivos = response.data;

            });
        },
        getPeriodActive(){
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    function: 'periodo',
                }
            }).then(response =>{
                this.periodos = response.data;
            });
        },
        assignPeriod:function(e){
            axios.post('/jmdistributions/Hr/Controlador/PeriodoController',{
                data:{
                    objetivos: this.objetivos,
                    id: this.periodo,
                    id_empleado: this.message.get('id'),
                    function: 'asginar',
                }
            }).then(response =>{
                if(response.data.estado)
                {
                    this.success(response.data.mensaje);
                    this.asignar_periodo = false;
                }else{
                    this.alert(response.data.mensaje)
                }
                
            });
            e.preventDefault();
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
        validateForm: function(e){
            e.preventDefault();
            //Dependera del valor del id de la tabla relacion (mas es mejor-1 o menos es mejor-2)
            if(this.referencia > this.resultado && this.relacion == 1){
                this.alert('El valor de referencia no puede ser mayor al resultado esperado');
            }else if(this.referencia <= this.resultado && this.relacion == 2){
                this.alert('El valor de referencia no puede ser menor o igual a resultado esperado');
            }else{
                this.storeObjective();
            }
        },
        storeObjective: function(){
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id_empleado: this.message.get('id'),
                    function: 'agregar',
                    descripcion: this.descripcion,
                    resultado: this.resultado,
                    unidad: this.unidad,
                    relacion: this.relacion,
                    ponderacion: this.ponderacion,
                    fecha_entrega: this.fecha_entrega,
                    balanced: this.balanced,
                    objetivo:this.objetivo,
                    referencia: this.referencia,
                    comentario: this.comentario,

                }
            }).then(response =>{                             
                if(response.data === 1){
                    this.success('El objetivo se agrego con éxito');
                    this.clearInput();
                    this.getObjective();
                }else{
                    this.error();
                }
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
                this.referencia = Number(response.data[0].valor_referencia);
                this.comentario = response.data[0].comentario_supervisor;
                
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
        deleteObjective: function(id){
            axios.post('/jmdistributions/Hr/Controlador/ObjetivoController',{
                data:{
                    id: id,
                    function: 'eliminar',
                }
            }).then((response) =>{
                this.getObjective();
                
            });
        },
        success: function(message){
            Swal.fire(
                'Completado',
                 message,
                'success'
              )
        },
        alert: function(message){
            Swal.fire({
                type: 'warning',
                text: message,
            })
        },
        error: function(){
            Swal.fire({
                type: 'error',
                title: 'Oops...',
                text: 'Error al guardar',
              })
        },
        clearInput: function(){
            this.asignar_periodo = true;
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
            this.comentario= '';
        }
    }
});