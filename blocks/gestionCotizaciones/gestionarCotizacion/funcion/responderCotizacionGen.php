<?php

namespace hojaDeVida\crearDocente\funcion;

use hojaDeVida\crearDocente\funcion\redireccionar;

include_once ('redireccionar.php');
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class SolicitudCotizacion {
	
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miFuncion;
	var $miSql;
	var $conexion;
	
	
	function __construct($lenguaje, $sql, $funcion) {
		
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miSql = $sql;
		$this->miFuncion = $funcion;
		
	}
	function cambiafecha_format($fecha) {
		ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
		$fechana = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
		return $fechana;
	}
	
	function campoSeguroCodificar($cadena, $tiempoRequest){
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		/*++++++++++++++++++++++++++++++++++++++++++++ OBTENER CAMPO POST (Codificar) +++++++++++++++++++++++++++++++++++++++++++*/
	
		$tiempo = (int) substr($tiempoRequest, 0, -2);
		$tiempo = $tiempo * pow(10, 2);
	
		$campoSeguro = $this->miConfigurador->fabricaConexiones->crypto->codificar($cadena.$tiempo);
	
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		return $campoSeguro;
	}
	
	function campoSeguroDecodificar($campoSeguroRequest, $tiempoRequest){
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		/*++++++++++++++++++++++++++++++++++++++++++++ OBTENER CAMPO POST (Decodificar) +++++++++++++++++++++++++++++++++++++++++*/
	
		$tiempo = (int) substr($tiempoRequest, 0, -2);
		$tiempo = $tiempo * pow(10, 2);
	
		$campoSeguro = $this->miConfigurador->fabricaConexiones->crypto->decodificar($campoSeguroRequest);
	
		$campo = str_replace($tiempo, "", $campoSeguro);
	
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		/*+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
		return $campo;
	}
	function procesarFormulario() {
		
		$conexion = "estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/asignacionPuntajes/salariales/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/asignacionPuntajes/salariales/" . $esteBloque ['nombre'];
		
		
		$respuesta = $_POST[$this->campoSeguroCodificar('respuesta', $_REQUEST['tiempo'])];
		
		
		$_REQUEST['decision']='EN ESTUDIO';

		
		//Buscar usuario para enviar correo
		$cadenaSql = $this->miSql->getCadenaSql ( 'buscarProveedoresInfoCotizacion', $_REQUEST ['idObjeto'] );
		$resultadoProveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		
		foreach ($resultadoProveedor as $dato):
			
				$datosSolicitud = array (
						'objeto' => $_REQUEST ['idObjeto'],
						'proveedor' => $dato['id_proveedor']
				);
					
				$cadenaSql = $this->miSql->getCadenaSql ( 'consultarSolicitudxProveedor', $datosSolicitud );
				$resultadoSolicitudxPersona = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				
				$datosSolicitudRes = array (
						'objeto' => $_REQUEST ['idObjeto'],
						'proveedor' => $dato['id_proveedor'],
						'solicitud' => $resultadoSolicitudxPersona[0]['id_solicitud']
				);
					
				$cadenaSql = $this->miSql->getCadenaSql ( 'consultarSolicitudxProveedorRes', $datosSolicitudRes );
				$resultadoSolicitudxPersonaRes = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				
				if(!$resultadoSolicitudxPersonaRes){
					
					$datosRes = array (
							'id_objeto' => $_REQUEST ['idObjeto'],
							'id_proveedor' => $dato['id_proveedor'],
							'id_respuesta_directa' => null,
							'id_solicitud' => $resultadoSolicitudxPersona[0]['id_solicitud'],
							'respuesta' => $respuesta,
							'decision' => $_REQUEST['decision'],
							'usuario' => $_REQUEST ['usuario']
					);
					
					// Inserto las solicitudes de cotizacion para cada proveedor
					$cadenaSql = $this->miSql->getCadenaSql ( 'ingresarRespuestaCotizacion', $datosRes );
					$resultadoRegRes = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
					
				}
				
		
		endforeach;
		
		
		$datos = array (
				'id_objeto' => $_REQUEST ['idObjeto'],
				'id_respuesta_directa' => null,
				'respuesta' => $respuesta,
				'decision' => $_REQUEST['decision'],
				'usuario' => $_REQUEST ['usuario']
		);
		
		// actualizo estado del objeto a contratar a 2(cotizacion)
		// actualizo fecha de solicitud
		// Actualizar estado del OBJETO CONTRATO A ASIGNADA
		
		if($_REQUEST['decision'] == "APROBADO - SELECCIONADO"){
			
			$numberSolicitud = "SC-" . sprintf("%05d", $_REQUEST['idObjeto']);
			
			$parametros = array (
					'idObjeto' => $_REQUEST ['idObjeto'],
					'numero_solicitud' => $numberSolicitud,
					'estado' => 'ASIGNADO', // solicitud de cotizacion
					'fecha' => date ( "Y-m-d" ),
					'usuario' => $_REQUEST ['usuario']
			);
			// Actualizo estado del objeto a contratar
			
			$cadenaSql = $this->miSql->getCadenaSql ( 'actualizarObjeto', $parametros );
			//$resultadoAct = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
			$resultadoAct = true;
			
		}else{
			$resultadoAct = true;
		}
		

		

		
		
		if ($resultadoAct) {
			redireccion::redireccionar ( 'respondioCotizacionGen', $datos );
			exit ();
		} else {
			redireccion::redireccionar ( 'noInserto' );
			exit ();
		}
	}
	function resetForm() {
		foreach ( $_REQUEST as $clave => $valor ) {
			
			if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
				unset ( $_REQUEST [$clave] );
			}
		}
	}
}

$miRegistrador = new SolicitudCotizacion ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>
