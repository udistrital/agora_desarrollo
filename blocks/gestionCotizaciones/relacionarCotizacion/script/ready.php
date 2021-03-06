
window.onload = detectarCarga;
function detectarCarga(){

	$('#marcoDatosLoad').fadeOut(1000, function (){
		$('#AgrupacionDisponibilidadNec').fadeIn(500);
	});
	
	$('#valorAvance').fadeOut(300);
	$('#valoresForma').fadeOut(300);
	
	$('#valorAvanceReplica').fadeOut(300);
	
	tinyMCE.get('<?php echo $this->campoSeguro('criterioSeleccion')?>').theme.resizeTo("100%", 150);
	tinyMCE.get('<?php echo $this->campoSeguro('plazoEjecucion')?>').theme.resizeTo("100%", 50);
	
	tinyMCE.get('<?php echo $this->campoSeguro('observaciones')?>').theme.resizeTo("100%", 60);
	
}

	$(':checkbox[readonly=readonly]').click(function(){
	
		return false;        

	}); 

$('#<?php echo $this->campoSeguro('fechaCierre')?>').datepicker({
		<?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
                dateFormat: 'dd/mm/yy',
		minDate: 0,
        yearRange: '0:+50',
		changeYear: true,
		changeMonth: true,
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		    dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		    dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		    
			
	   });
	   
$('#<?php echo $this->campoSeguro('fechaApertura')?>').datepicker({
		<?php /*?>timeFormat: 'HH:mm:ss',<?php */?>
                dateFormat: 'dd/mm/yy',
		minDate: 0,
        yearRange: '0:+0',
		changeYear: true,
		changeMonth: true,
		monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
		    'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
		    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
		    dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
		    dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
		    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa'],
		    
			
	   });
	   
	   
$( function() {
		var dateFormat = "dd/mm/yy",
			from = $( '#<?php echo $this->campoSeguro('fechaApertura')?>' )
				.datepicker({
				defaultDate: "+1w",
			        yearRange: '0:+0',
					changeYear: true,
					changeMonth: true,
					monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
				    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
				    dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
				    dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
				    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa']
				})
				.on( "change", function() {
					to.datepicker( "option", "minDate", getDate( this ) );
				}),
			to = $( '#<?php echo $this->campoSeguro('fechaCierre')?>' ).datepicker({
				defaultDate: "+1w",
			        yearRange: '0:+50',
					changeYear: true,
					changeMonth: true,
					monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
				    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
				    dayNames: ['Domingo','Lunes','Martes','Miercoles','Jueves','Viernes','Sabado'],
				    dayNamesShort: ['Dom','Lun','Mar','Mie','Jue','Vie','Sab'],
				    dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sa']
			})
			.on( "change", function() {
				from.datepicker( "option", "maxDate", getDate( this ) );
			});

		function getDate( element ) {
			var date;
			try {
				date = $.datepicker.parseDate( dateFormat, element.value );
			} catch( error ) {
				date = null;
			}

			return date;
		}
	} );	   	   

$( '#<?php echo $this->campoSeguro('fechaApertura')?>' ).datepicker()
    .on("input change", function (e) {
    
	    var start = $( '#<?php echo $this->campoSeguro('fechaApertura')?>' ).datepicker('getDate');
	    var end   = $( '#<?php echo $this->campoSeguro('fechaCierre')?>' ).datepicker('getDate');
	    if(!start || !end){
	        return;
	    }
	    var days = 0;
	    if (start && end) {
	        days = Math.floor((end.getTime() - start.getTime()) / 86400000); // ms per day
	    }
	    
	    if(days <= 3){
	    
	    	swal({
                            title: 'Ocurrio un problema...',
                            type: 'error',
                            html:
                                    'La Fecha de Cierre debe ser mayor a (3) días en relación a la fecha de Apertura.',
                            confirmButtonText:
                                    'Ok'

            })
            
            $( '#<?php echo $this->campoSeguro('fechaCierre')?>' ).val("");
                                 
	    	
	    }
	    
	});


$( '#<?php echo $this->campoSeguro('fechaCierre')?>' ).datepicker()
    .on("input change", function (e) {
    
	    var start = $( '#<?php echo $this->campoSeguro('fechaApertura')?>' ).datepicker('getDate');
	    var end   = $( '#<?php echo $this->campoSeguro('fechaCierre')?>' ).datepicker('getDate');
	    if(!start || !end){
	        return;
	    }
	    var days = 0;
	    if (start && end) {
	        days = Math.floor((end.getTime() - start.getTime()) / 86400000); // ms per day
	    }
	    
	    if(days <= 3){
	    
	    	swal({
                            title: 'Ocurrio un problema...',
                            type: 'error',
                            html:
                                    'La Fecha de Cierre debe ser mayor a (3) días en relación a la fecha de Apertura.',
                            confirmButtonText:
                                    'Ok'

            })
            
            $( '#<?php echo $this->campoSeguro('fechaCierre')?>' ).val("");
                                 
	    	
	    }
	    
	});


$("#relacionarCotizacionRegistrar").validationEngine({
	validateNonVisibleFields: true,
	promptPosition : "inline", 
	scroll: true,
	autoHidePrompt: true,
	autoHideDelay: 9000,
    updatePromptsPosition:false
});

$("#gestionObjetoConsultarRel").validationEngine({
	promptPosition : "bottomRight:-150", 
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
});

$("#gestionObjetoConsultarCot").validationEngine({
	promptPosition : "bottomRight:-150", 
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
});

$( ".widget input[type=submit], .widget a, .widget button" ).button();
        
/*
 * Función que organiza los tabs en la interfaz gráfica
 */
$(function() {
	$("#tabs").tabs();
}); 

/*
 * Asociar el widget de validación al formulario
 */

/*
 * Se define el ancho de los campos de listas desplegables
 */


// Asociar el widget de validación al formulario

/////////Se define el ancho de los campos de listas desplegables///////
$('#<?php echo $this->campoSeguro('divisionCIIU')?>').width(750);
$('#<?php echo $this->campoSeguro('grupoCIIU')?>').width(750);
$('#<?php echo $this->campoSeguro('claseCIIU')?>').width(750);
$('#<?php echo $this->campoSeguro('unidad')?>').width(250);

$('#<?php echo $this->campoSeguro('objetoArea')?>').width(750);
$('#<?php echo $this->campoSeguro('objetoNBC')?>').width(750);

$('#<?php echo $this->campoSeguro('unidadEjecutoraCheck')?>').width(250);
$('#<?php echo $this->campoSeguro('unidadEjecutoraCheckRelacionada')?>').width(250);
$('#<?php echo $this->campoSeguro('unidadEjecutoraCheckCotizacion')?>').width(250);

$('#<?php echo $this->campoSeguro('vigenciaNecesidad')?>').width(250);
$('#<?php echo $this->campoSeguro('vigenciaNecesidadRelacionada')?>').width(250);
$('#<?php echo $this->campoSeguro('vigenciaNecesidadCotizacion')?>').width(250);

$('#<?php echo $this->campoSeguro('tipoNecesidad')?>').width(250);
$('#<?php echo $this->campoSeguro('unidadEjecutora')?>').width(250);
$('#<?php echo $this->campoSeguro('dependencia')?>').width(750);
$('#<?php echo $this->campoSeguro('ordenador')?>').width(750);
$('#<?php echo $this->campoSeguro('solicitante')?>').width(750);
$('#<?php echo $this->campoSeguro('medioPago')?>').width(250);
$('#<?php echo $this->campoSeguro('tipoFormaPago')?>').width(350);
$('#<?php echo $this->campoSeguro('formaSeleccion')?>').width(350);
$('#<?php echo $this->campoSeguro('tipoContrato')?>').width(450);
$('#<?php echo $this->campoSeguro('replicarParcial')?>').width(150);


//////////////////**********Se definen los campos que requieren campos de select2**********////////////////
$('#<?php echo $this->campoSeguro('divisionCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('grupoCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('claseCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('unidad')?>').select2();

$('#<?php echo $this->campoSeguro('objetoArea')?>').select2();
$('#<?php echo $this->campoSeguro('objetoNBC')?>').select2();

$('#<?php echo $this->campoSeguro('unidadEjecutoraCheck')?>').select2();
$('#<?php echo $this->campoSeguro('unidadEjecutoraCheckRelacionada')?>').select2();
$('#<?php echo $this->campoSeguro('unidadEjecutoraCheckCotizacion')?>').select2();

$('#<?php echo $this->campoSeguro('vigenciaNecesidad')?>').select2();
$('#<?php echo $this->campoSeguro('vigenciaNecesidadRelacionada')?>').select2();
$('#<?php echo $this->campoSeguro('vigenciaNecesidadCotizacion')?>').select2();

$('#<?php echo $this->campoSeguro('tipoNecesidad')?>').select2();
$('#<?php echo $this->campoSeguro('unidadEjecutora')?>').select2();
$('#<?php echo $this->campoSeguro('dependencia')?>').select2();
$('#<?php echo $this->campoSeguro('ordenador')?>').select2();
$('#<?php echo $this->campoSeguro('solicitante')?>').select2();
$('#<?php echo $this->campoSeguro('medioPago')?>').select2();
$('#<?php echo $this->campoSeguro('tipoFormaPago')?>').select2();
$('#<?php echo $this->campoSeguro('formaSeleccion')?>').select2();
$('#<?php echo $this->campoSeguro('tipoContrato')?>').select2();
$('#<?php echo $this->campoSeguro('replicarParcial')?>').select2();


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$('#tablaObjetos').dataTable({
        
    "language": {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
	"sZeroRecords":    "No se encontraron resultados",
        "sSearch":         "Buscar:",
        "sLoadingRecords": "Cargando...",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
	"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
	"oPaginate": {
		"sFirst":    "Primero",
		"sLast":     "Ãšltimo",
		"sNext":     "Siguiente",
		"sPrevious": "Anterior"
		}
    }
});


$('#tablaPersonas').dataTable({
        
    "language": {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
	"sZeroRecords":    "No se encontraron resultados",
        "sSearch":         "Buscar:",
        "sLoadingRecords": "Cargando...",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
	"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
	"oPaginate": {
		"sFirst":    "Primero",
		"sLast":     "Ãšltimo",
		"sNext":     "Siguiente",
		"sPrevious": "Anterior"
		}
    }
});



$('#tablaObjetos').dataTable({
        
    "language": {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
	"sZeroRecords":    "No se encontraron resultados",
        "sSearch":         "Buscar:",
        "sLoadingRecords": "Cargando...",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
	"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
	"oPaginate": {
		"sFirst":    "Primero",
		"sLast":     "Ãšltimo",
		"sNext":     "Siguiente",
		"sPrevious": "Anterior"
		}
    }
});

$('#tablaObjetosSinCotizacion').dataTable({
        
    "language": {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
	"sZeroRecords":    "No se encontraron resultados",
        "sSearch":         "Buscar:",
        "sLoadingRecords": "Cargando...",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
	"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
	"oPaginate": {
		"sFirst":    "Primero",
		"sLast":     "Ãšltimo",
		"sNext":     "Siguiente",
		"sPrevious": "Anterior"
		}
    }
});


$('#tablaObjetosEnCotizacion').dataTable({
    "aaSorting": [[ 0, "desc" ]] , 
    "language": {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
	"sZeroRecords":    "No se encontraron resultados",
        "sSearch":         "Buscar:",
        "sLoadingRecords": "Cargando...",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
	"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
	"oPaginate": {
		"sFirst":    "Primero",
		"sLast":     "Ãšltimo",
		"sNext":     "Siguiente",
		"sPrevious": "Anterior"
		}
    }
});


$(function(){
	// Clona la fila oculta que tiene los campos base, y la agrega al final de la tabla
	$("#agregar").on('click', function(){
		$("#tabla tbody tr:eq(0)").clone().removeClass('fila-base').appendTo("#tabla tbody");
	});
 
	// Evento que selecciona la fila y la elimina 
	$(document).on("click",".eliminar",function(){
		var parent = $(this).parents().get(0);
		$(parent).remove();
	});
        
        
        $('#<?php echo $this->campoSeguro('botonRegistrar')?>').click(function() {
                alert('aaaaaaaaaaaaaaaa');
        });
});

$('#<?php echo $this->campoSeguro('unidadEjecutoraCheck') ?>').width(300);
$("#<?php echo $this->campoSeguro('unidadEjecutoraCheck') ?>").select2();
$('#<?php echo $this->campoSeguro('vigencia_solicitud_consulta') ?>').width(200);
$("#<?php echo $this->campoSeguro('vigencia_solicitud_consulta') ?>").select2();
$('#<?php echo $this->campoSeguro('numero_disponibilidad') ?>').width(200);
$("#<?php echo $this->campoSeguro('numero_disponibilidad') ?>").select2();
$('#<?php echo $this->campoSeguro('dependenciaDestino')?>').width(750);
$('#<?php echo $this->campoSeguro('dependenciaDestino')?>').select2();
$("#<?php echo $this->campoSeguro('tipo_registro') ?>").width(220);
$("#<?php echo $this->campoSeguro('tipo_registro')?>").select2();
 $('#<?php echo $this->campoSeguro('tipoItem')?>').width(240);
 $("#<?php echo $this->campoSeguro('tipoItem')?>").select2();
  $('#<?php echo $this->campoSeguro('unidadItem')?>').width(240);
 $("#<?php echo $this->campoSeguro('unidadItem')?>").select2();


 
 $( "#<?php echo $this->campoSeguro('tipo_registro')?>" ).change(function() {
        
            switch($("#<?php echo $this->campoSeguro('tipo_registro')?>").val())
            {
                           
                case '1':
                    
                   
                    $("#<?php echo $this->campoSeguro('cargar_elemento')?>").css('display','block');
                    $("#<?php echo $this->campoSeguro('cargue_elementos')?>").css('display','none');

                   

                break;
                
                
                       case '2':
                    
                    $("#<?php echo $this->campoSeguro('cargar_elemento')?>").css('display','none');
                    $("#<?php echo $this->campoSeguro('cargue_elementos')?>").css('display','block');
       
                break;
                

                default:
                
                    $("#<?php echo $this->campoSeguro('cargar_elemento')?>").css('display','block');
                    $("#<?php echo $this->campoSeguro('cargue_elementos')?>").css('display','none');
                   
                   break;
                
                
             }
          }); 