<?php

namespace hojaDeVida\crearDocente\funcion;

use hojaDeVida\crearDocente\funcion\redireccionar;

include_once ('redireccionar.php');
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class Registrar {
	
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
	function procesarFormulario() {
		$conexion = "estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/asignacionPuntajes/salariales/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/asignacionPuntajes/salariales/" . $esteBloque ['nombre'];
		
        
        $datosSolicitud = array (
        		'numero_solicitud' => $_REQUEST ['numSolicitud'],
        		'vigencia' => $_REQUEST ['vigencia'],
        		'claseCIIU' => $_REQUEST ['claseCIIU'],
        		'unidad' => $_REQUEST ['unidad'],
        		'cantidad' => $_REQUEST ['cantidad'],
        		'cotizaciones' => $_REQUEST ['cotizaciones']
        );
        
        
        
        
        
        if (isset($_REQUEST['estadoSolicitudRelacionada']) && $_REQUEST['estadoSolicitudRelacionada'] == "CREADO" ) {
        	//Actualizar datos del Objeto a contratar
        	$cadenaSql = $this->miSql->getCadenaSql ( 'actualizar', $datosSolicitud );
        	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
        }else {
        	//Guardar datos del Objeto a contratar
        	$cadenaSql = $this->miSql->getCadenaSql ( 'registrar', $datosSolicitud );
        	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "insertar" );
        }
        

		
		if ($resultado) {
			
			if (isset($_REQUEST['estadoSolicitudRelacionada']) && $_REQUEST['estadoSolicitudRelacionada'] == "CREADO" ) {
				
				$datosSolicitudNecesidad = array (
						'idSolicitud' => $_REQUEST['numSolicitud'],
						'vigencia' => $_REQUEST['vigencia']
				);
				
				$cadena_sql = $this->miSql->getCadenaSql ( "informacionSolicitudAgora", $datosSolicitudNecesidad);
				$resultadoNecesidadRelacionada = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
				
				$datos = array (
						'idObjeto' => $resultadoNecesidadRelacionada[0]['id_objeto'],
						'numero_solicitud' => $_REQUEST ['numSolicitud'],
						'vigencia' => $_REQUEST ['vigencia'],
						'cotizaciones' => $_REQUEST ['cotizaciones'],
						'estadoSolicitud' => $_REQUEST['estadoSolicitudRelacionada']
				);
				
			}else{
				//Conusltar el ultimo ID del objeto
				$cadenaSql = $this->miSql->getCadenaSql ( 'lastIdObjeto' );
				$lastId = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				$datos = array (
						'idObjeto' => $lastId[0][0],
						'numero_solicitud' => $_REQUEST ['numSolicitud'],
						'vigencia' => $_REQUEST ['vigencia'],
						'cotizaciones' => $_REQUEST ['cotizaciones'],
						'estadoSolicitud' => $_REQUEST['estadoSolicitudRelacionada']
				);
			}
                    
			redireccion::redireccionar ( 'inserto',  $datos);
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

$miRegistrador = new Registrar ( $this->lenguaje, $this->sql, $this->funcion );

$resultado = $miRegistrador->procesarFormulario ();

?>
