<?php

namespace administracion\evaluacionProveedor\funcion;

if (!isset($GLOBALS["autorizado"])) {
	include("index.php");
	exit;
}

/*
 * To change this license header, choose License Headers in Project Properties. To change this template file, choose Tools | Templates and open the template in the editor.
 */
$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );

$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/inventarios/";
$rutaBloque .= $esteBloque ['nombre'];
$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/inventarios/" . $esteBloque ['nombre'];

$conexion = "estructura";
$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );

$resultado = '';

$fechaActual = date ( 'Y-m-d' );

// INICIO Calculo puntaje total

$puntajeTotal = 0;
$puntajeTotal = $_REQUEST ['tiempoEntrega'] + $_REQUEST ['cantidades'] + $_REQUEST ['conformidad'] + $_REQUEST ['funcionalidadAdicional'];
$_REQUEST ['reclamaciones'] = $_REQUEST ['reclamacionSolucion'] == 12 ? 0 : $_REQUEST ['reclamaciones'];
$puntajeTotal = $puntajeTotal + $_REQUEST ['reclamaciones'] + $_REQUEST ['reclamacionSolucion'] + $_REQUEST ['servicioVenta'] + $_REQUEST ['procedimientos'];
$_REQUEST ['garantia'] = $_REQUEST ['garantiaSatisfaccion'] == 15 ? 0 : $_REQUEST ['garantia'];
$puntajeTotal = $puntajeTotal + $_REQUEST ['garantia'] + $_REQUEST ['garantiaSatisfaccion'];

// FIN Calculo puntaje total


// INICIO CALCULO CLASIFICACION
function clasificacion($puntajeTotal = '') {
	if ($puntajeTotal > 79)
		$valor = "A";
	elseif ($puntajeTotal > 45)
		$valor = "B";
	else
		$valor = "C";
	return $valor;
}
$clasificacion = clasificacion ( $puntajeTotal );

// FIN CALCULO CLASIFICACION

// Cargo array con los datos para insertar en la table evaluacionProveedor

$_REQUEST ['idContrato'] = 29;



$arreglo = array (
		$_REQUEST ['idContrato'],
		$fechaActual,
		$_REQUEST ['tiempoEntrega'],
		$_REQUEST ['cantidades'],
		$_REQUEST ['conformidad'],
		$_REQUEST ['funcionalidadAdicional'],
		$_REQUEST ['reclamaciones'],
		$_REQUEST ['reclamacionSolucion'],
		$_REQUEST ['servicioVenta'],
		$_REQUEST ['procedimientos'],
		$_REQUEST ['garantia'],
		$_REQUEST ['garantiaSatisfaccion'],
		$puntajeTotal,
		$clasificacion 
);





// Guardar datos de la evaluacion
$cadenaSql = $this->sql->getCadenaSql ( "registroEvaluacion", $arreglo );
$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );


if ($resultado) {
	// Actualizar estado del CONTRATO A EVALUADO
	$parametros = array (
			'idContrato' => $_REQUEST ['idContrato'],
			'estado' => 'EVALUADO' //Evaluado
	);
	
	$cadenaSql = $this->sql->getCadenaSql ( "actualizarContrato", $parametros );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
	
	// Actualizar PUNTAJE TOTAL DEL PROVEEDOR Y SU CLASIFICACION
	
	
	// Se hace una consulta del Proveedor Evaluado
	if($_REQUEST ["idProveedor"] == 0){
			
		$cadenaSql = $this->sql->getCadenaSql ( 'listarProveedoresXContrato', $_REQUEST["idContrato"] );
		$consulta3_1 = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
		$cadenaSql = $this->sql->getCadenaSql ( 'consultarProveedoresByID', $consulta3_1[0][0] );
		$proveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
		$cantidad = "multiple";
		$numeroPro = count($proveedor);
			
	}else{
		$cadenaSql = $this->sql->getCadenaSql ( 'consultarProveedorByID', $_REQUEST ["idProveedor"] );
		$proveedor = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
		$cantidad = "individual";
		$numeroPro = count($proveedor);
	}
	
	
	
	if ($cantidad == "individual") {
		$_REQUEST["idProveedor"] = $proveedor[0]["id_proveedor"];
	} else {
		$i = 0;
		while ( $i < $numeroPro ) {
			$_REQUEST["idProveedor".$i] = $proveedor[$i]["id_proveedor"];
			$i ++;
		}
	}
	
	if ($cantidad == "individual") {
		
		$puntajeActual = $proveedor [0] ["puntaje_evaluacion"];
		$claseficacionActual = $proveedor [0] ["clasificacion_evaluacion"];
		
		if ($claseficacionActual == 'A' || $claseficacionActual == 'B' || $claseficacionActual == 'C') {
			$puntajeNuevo = ($puntajeActual + $puntajeTotal) / 2;
			$clasficacionNueva = clasificacion ( $puntajeNuevo );
		} else {
			$puntajeNuevo = $puntajeTotal;
			$clasficacionNueva = $clasificacion;
		}
		
		$valores = array (
				'idProveedor' => $_REQUEST ['idProveedor'],
				'puntajeNuevo' => $puntajeNuevo,
				'clasificacion' => $clasficacionNueva
		);
		
		$cadenaSql = $this->sql->getCadenaSql ( "actualizarProveedor", $valores );
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
		
	} else {
		
		$i = 0;
		while ( $i < $numeroPro ) {
			
			$puntajeActual = $proveedor [$i] ["puntaje_evaluacion"];
			$claseficacionActual = $proveedor [$i] ["clasificacion_evaluacion"];
			
			if ($claseficacionActual == 'A' || $claseficacionActual == 'B' || $claseficacionActual == 'C') {
				$puntajeNuevo = ($puntajeActual + $puntajeTotal) / 2;
				$clasficacionNueva = clasificacion ( $puntajeNuevo );
			} else {
				$puntajeNuevo = $puntajeTotal;
				$clasficacionNueva = $clasificacion;
			}
			
			$valores = array (
					'idProveedor' => $_REQUEST ['idProveedor'.$i],
					'puntajeNuevo' => $puntajeNuevo,
					'clasificacion' => $clasficacionNueva
			);
			
			$cadenaSql = $this->sql->getCadenaSql ( "actualizarProveedor", $valores );
			$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'acceso' );
			
			
			$i ++;
		}
	}
	
	
	$parametros2 = array (
			'idTabla' => $_REQUEST ['idContrato'],
			'tipo' => 1, // evaluacion
			'fecha' => date ( "Y-m-d H:i:s" ) 
	);
	// Inserto codigo de validacion
	$cadenaSql = $this->sql->getCadenaSql ( 'ingresarCodigo', $parametros2 );
	$resultado = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
	
	

	$datos = array (
			'idContrato' => $_REQUEST ['idContrato'],
			'idCodigo' => $resultado [0] ['id_codigo_validacion'] 
	);
	
	$this->funcion->Redireccionador ( 'registroExitoso', $datos );
	exit ();
} else {
	$this->funcion->Redireccionador ( 'noregistro', $_REQUEST ['usuario'] );
	exit ();
}
