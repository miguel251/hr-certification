new Vue({
    el:'#peso',
    data:{
        pesoObjetivo:'',
        pesoCompetencia:''
    },
    mounted(){
        this.getPesos();
    },
    methods:{
        getPesos: function(){            
            axios.post('/jmdistributions/Hr/Controlador/PesoController',{
                data:{
                    function:'peso',
                }
            }).then(response =>{                      
                this.pesoObjetivo = response.data[0].peso_objetivo;
                this.pesoCompetencia = response.data[0].peso_competencia;
            });
        },
        validatePeso: function(e){
            
            e.preventDefault();

            this.pesoObjetivo = Math.trunc(this.pesoObjetivo);
            this.pesoCompetencia = Math.trunc(this.pesoCompetencia);

            if(this.pesoObjetivo < 0 || this.pesoObjetivo > 100){
                this.error('El peso del objetiivo tiene que estar entre 0 - 100.');
                return;
            }
            if(this.pesoCompetencia < 0 || this.pesoCompetencia > 100){
                this.error('El peso del competencia tiene que estar entre 0 - 100.');
                return;
            }
            if(this.pesoCompetencia + this.pesoObjetivo > 100 || this.pesoCompetencia + this.pesoObjetivo < 100){
                this.error('La suma de los pesos no puede ser diferente de 100');
                return;
            }

            this.updatePeso();
        },
        updatePeso:function(){
            axios.post('/jmdistributions/Hr/Controlador/PesoController',{
                data:{
                    pesoCompetencia: this.pesoCompetencia,
                    pesoObjetivo: this.pesoObjetivo,
                    function: 'actualizar',
                }
            }).then(response =>{
                if (response.data == 1) {
                    this.getPesos();
                    this.success('Los pesos se actualizaron.');
                }else{
                    this.error('Error al actualizar');
                }
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
        }
    }
    
});