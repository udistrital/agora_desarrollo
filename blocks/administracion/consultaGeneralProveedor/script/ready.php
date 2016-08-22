$("#consultaGeneralProveedor").validationEngine({
    validateNonVisibleFields: false,
	promptPosition : "bottomRight:-150", 
	scroll: false,
	autoHidePrompt: true,
	autoHideDelay: 2000
});

$("button").button().click(function (event) { 
    event.preventDefault();
});
        
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


$('#<?php echo $this->campoSeguro('tipoPersona')?>').width(150);
$('#<?php echo $this->campoSeguro('paisEmpresa')?>').width(150);
$('#<?php echo $this->campoSeguro('tipoIdentifiExtranjera')?>').width(250);
$('#<?php echo $this->campoSeguro('tipoDocumento')?>').width(250);
$('#<?php echo $this->campoSeguro('tipoDocumentoNat')?>').width(250);

$('#<?php echo $this->campoSeguro('productoImportacion')?>').width(150);
$('#<?php echo $this->campoSeguro('regimenContributivo')?>').width(150);
$('#<?php echo $this->campoSeguro('pyme')?>').width(150);
$('#<?php echo $this->campoSeguro('registroMercantil')?>').width(150);

$('#<?php echo $this->campoSeguro('personaJuridicaPais')?>').width(250);
$("#<?php echo $this->campoSeguro('personaJuridicaPais')?>").select2();
$('#<?php echo $this->campoSeguro('personaJuridicaDepartamento')?>').width(250);
$("#<?php echo $this->campoSeguro('personaJuridicaDepartamento')?>").select2();
$('#<?php echo $this->campoSeguro('personaJuridicaCiudad')?>').width(250);
$("#<?php echo $this->campoSeguro('personaJuridicaCiudad')?>").select2();

$('#<?php echo $this->campoSeguro('personaNaturalContaDepartamento')?>').width(250);
$("#<?php echo $this->campoSeguro('personaNaturalContaDepartamento')?>").select2();
$('#<?php echo $this->campoSeguro('personaNaturalContaCiudad')?>').width(250);
$("#<?php echo $this->campoSeguro('personaNaturalContaCiudad')?>").select2();

$('#<?php echo $this->campoSeguro('departamento')?>').width(250);
$("#<?php echo $this->campoSeguro('departamento')?>").select2();
$('#<?php echo $this->campoSeguro('ciudad')?>').width(250);
$("#<?php echo $this->campoSeguro('ciudad')?>").select2();

$('#<?php echo $this->campoSeguro('genero')?>').width(250);
$("#<?php echo $this->campoSeguro('genero')?>").select2();
$('#<?php echo $this->campoSeguro('generoNat')?>').width(250);
$("#<?php echo $this->campoSeguro('generoNat')?>").select2();

$('#<?php echo $this->campoSeguro('perfil')?>').width(250);
$("#<?php echo $this->campoSeguro('perfil')?>").select2();

$('#<?php echo $this->campoSeguro('tipoCuenta')?>').width(250);
$("#<?php echo $this->campoSeguro('tipoCuenta')?>").select2();
$('#<?php echo $this->campoSeguro('entidadBancaria')?>').width(300);
$("#<?php echo $this->campoSeguro('entidadBancaria')?>").select2();
$('#<?php echo $this->campoSeguro('tipoConformacion')?>').width(300);
$("#<?php echo $this->campoSeguro('tipoConformacion')?>").select2();
$('#<?php echo $this->campoSeguro('paisNacimiento')?>').width(250);
$("#<?php echo $this->campoSeguro('paisNacimiento')?>").select2();


$('#<?php echo $this->campoSeguro('perfilNat')?>').width(250);
$("#<?php echo $this->campoSeguro('perfilNat')?>").select2();

$('#<?php echo $this->campoSeguro('tipoCuentaNat')?>').width(250);
$("#<?php echo $this->campoSeguro('tipoCuentaNat')?>").select2();
$('#<?php echo $this->campoSeguro('entidadBancariaNat')?>').width(300);
$("#<?php echo $this->campoSeguro('entidadBancariaNat')?>").select2();
$('#<?php echo $this->campoSeguro('paisNacimientoNat')?>').width(250);
$("#<?php echo $this->campoSeguro('paisNacimientoNat')?>").select2();

$('#<?php echo $this->campoSeguro('sujetoDeRetencion')?>').width(150);
$("#<?php echo $this->campoSeguro('sujetoDeRetencion')?>").select2();
$('#<?php echo $this->campoSeguro('agenteRetenedor')?>').width(150);
$("#<?php echo $this->campoSeguro('agenteRetenedor')?>").select2();
$('#<?php echo $this->campoSeguro('responsableICA')?>').width(150);
$("#<?php echo $this->campoSeguro('responsableICA')?>").select2();
$('#<?php echo $this->campoSeguro('responsableIVA')?>').width(150);
$("#<?php echo $this->campoSeguro('responsableIVA')?>").select2();

 $('#<?php echo $this->campoSeguro('seccionParametrosNat')?>').width(150);
 $("#<?php echo $this->campoSeguro('seccionParametrosNat')?>").select2(); 
 $('#<?php echo $this->campoSeguro('listaNomenclaturasNat')?>').width(240);
 $("#<?php echo $this->campoSeguro('listaNomenclaturasNat')?>").select2();
 
 $('#<?php echo $this->campoSeguro('seccionParametros')?>').width(150);
 $("#<?php echo $this->campoSeguro('seccionParametros')?>").select2(); 
 $('#<?php echo $this->campoSeguro('listaNomenclaturas')?>').width(240);
 $("#<?php echo $this->campoSeguro('listaNomenclaturas')?>").select2();

//////////////////**********Se definen los campos que requieren campos de select2**********////////////////
$('#<?php echo $this->campoSeguro('divisionCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('grupoCIIU')?>').select2();
$('#<?php echo $this->campoSeguro('claseCIIU')?>').select2();

$('#<?php echo $this->campoSeguro('tipoPersona')?>').select2();
$('#<?php echo $this->campoSeguro('paisEmpresa')?>').select2();
$('#<?php echo $this->campoSeguro('tipoIdentifiExtranjera')?>').select2();
$('#<?php echo $this->campoSeguro('tipoDocumento')?>').select2();
$('#<?php echo $this->campoSeguro('tipoDocumentoNat')?>').select2();

$('#<?php echo $this->campoSeguro('productoImportacion')?>').select2();
$('#<?php echo $this->campoSeguro('regimenContributivo')?>').select2();
$('#<?php echo $this->campoSeguro('pyme')?>').select2();
$('#<?php echo $this->campoSeguro('registroMercantil')?>').select2();



//////////////////Efectos Campos de Selección y Campos Dependientes///////////////////////////////////////


if($('#<?php echo $this->campoSeguro('tipoPersona_Update') ?>').val() == 1){
	$("#marcoDatosJuridicaUP").hide("fast");
}else if($('#<?php echo $this->campoSeguro('tipoPersona_Update') ?>').val() == 2){
	$("#marcoDatosNaturalUP").hide("fast");
}else{
	$("#marcoDatosNatural").hide("fast");
	$("#marcoDatosJuridica").hide("fast");
}


if($('#<?php echo $this->campoSeguro('tipoPersona_Update') ?>').val() == 1){
	$("#marcoDatosJuridicaUPV").hide("fast");
}else if($('#<?php echo $this->campoSeguro('tipoPersona_Update') ?>').val() == 2){
	$("#marcoDatosNaturalUPV").hide("fast");
}else{
	$("#marcoDatosNatural").hide("fast");
	$("#marcoDatosJuridica").hide("fast");
}



//$("#editarBotonesConcepto").show("slow");
$("#marcoProcedencia").hide("fast");
$("#obligatorioCedula").hide("fast");
$("#obligatorioPasaporte").hide("fast");


if($('#<?php echo $this->campoSeguro('perfil') ?>').val() == 4){
	$("#obligatorioProfesion").show("fast");
	$("#obligatorioEspecialidad").show("fast");
}else if ($('#<?php echo $this->campoSeguro('perfil') ?>').val() == 3){
	$("#obligatorioProfesion").show("fast");
	$("#obligatorioEspecialidad").hide("fast");
}else{
	$("#obligatorioProfesion").hide("fast");
	$("#obligatorioEspecialidad").hide("fast");
}


if($('#<?php echo $this->campoSeguro('perfilNat') ?>').val() == 4){
	$("#obligatorioProfesionNat").show("fast");
	$("#obligatorioEspecialidadNat").show("fast");
}else if ($('#<?php echo $this->campoSeguro('perfilNat') ?>').val() == 3){
	$("#obligatorioProfesionNat").show("fast");
	$("#obligatorioEspecialidadNat").hide("fast");
}else{
	$("#obligatorioProfesionNat").hide("fast");
	$("#obligatorioEspecialidadNat").hide("fast");
}


if($('#<?php echo $this->campoSeguro('paisEmpresa') ?>').val() == 2){
		$("#marcoProcedencia").show("slow");
}else {
		$("#marcoProcedencia").hide("slow");
}


if($('#<?php echo $this->campoSeguro('tipoIdentifiExtranjera') ?>').val() == 1){
		$("#obligatorioCedula").show("fast");
		$("#obligatorioPasaporte").hide("fast");
}else if ($('#<?php echo $this->campoSeguro('tipoIdentifiExtranjera') ?>').val() == 2){
		$("#obligatorioCedula").hide("fast");
		$("#obligatorioPasaporte").show("fast");
}else{
		$("#obligatorioCedula").hide("fast");
		$("#obligatorioPasaporte").hide("fast");
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$('#tablaContratos').DataTable({
        
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