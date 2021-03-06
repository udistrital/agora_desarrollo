<?php

namespace proveedor\registroNotificaciones\funcion;

use proveedor\registroNotificaciones\funcion\redireccionar;

include_once ('redireccionar.php');

if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
}

class Registrar {

    var $miConfigurador;
    var $lenguaje;
    var $miFormulario;
    var $miFuncion;
    var $miSql;
    var $conexion;

    function __construct($lenguaje, $sql, $funcion) {

        $this->miConfigurador = \Configurador::singleton();
        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');
        $this->lenguaje = $lenguaje;
        $this->miSql = $sql;
        $this->miFuncion = $funcion;
    }

    function cambiafecha_format($fecha) {
        ereg("([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
        $fechana = $mifecha[3] . "-" . $mifecha[2] . "-" . $mifecha[1];
        return $fechana;
    }

    function campoSeguroCodificar($cadena, $tiempoRequest) {
        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        /* ++++++++++++++++++++++++++++++++++++++++++++ OBTENER CAMPO POST (Codificar) +++++++++++++++++++++++++++++++++++++++++++ */

        $tiempo = (int) substr($tiempoRequest, 0, -2);
        $tiempo = $tiempo * pow(10, 2);

        $campoSeguro = $this->miConfigurador->fabricaConexiones->crypto->codificar($cadena . $tiempo);




        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        return $campoSeguro;
    }

    function campoSeguroDecodificar($campoSeguroRequest, $tiempoRequest) {
        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        /* ++++++++++++++++++++++++++++++++++++++++++++ OBTENER CAMPO POST (Decodificar) +++++++++++++++++++++++++++++++++++++++++ */

        $tiempo = (int) substr($tiempoRequest, 0, -2);
        $tiempo = $tiempo * pow(10, 2);

        $campoSeguro = $this->miConfigurador->fabricaConexiones->crypto->decodificar($campoSeguroRequest);

        $campo = str_replace($tiempo, "", $campoSeguro);

        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        /* +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */
        return $campo;
    }

    function procesarFormulario() {

        $conexion = "estructura";
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        $conexion = 'framework';
        $frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

        $rutaBloque = $this->miConfigurador->getVariableConfiguracion("raizDocumento") . "/blocks/proveedor/";
        $rutaBloque .= $esteBloque ['nombre'];
        
        $rutaBloqueArchivo = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/proveedor/registroNotificaciones";
	
        $host = $this->miConfigurador->getVariableConfiguracion("host") . $this->miConfigurador->getVariableConfiguracion("site") . "/blocks/proveedor/" . $esteBloque ['nombre'];


        $hostArchivo = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/proveedor/registroProveedor";


        foreach ($_FILES as $key => $values) {

            $archivo = $_FILES [$key];
        }
		
        $SQLs = [];


        $cotizacion_soporte = $this->campoSeguroCodificar('cotizacionSoporte', $_REQUEST['tiempo']);

        if ($_FILES [$cotizacion_soporte] ['name'] != '') {

            // obtenemos los datos del archivo
            $tamano = $archivo ['size'];
            $tipo = $archivo ['type'];
            $archivo1 = $archivo ['name'];
            $prefijo = substr(md5(uniqid(rand())), 0, 6);

            if ($archivo1 != "") {
                // guardamos el archivo a la carpeta files
                $destino1 = $rutaBloqueArchivo . "/soportes/" . $prefijo . "_" . $archivo1;

                if (copy($archivo ['tmp_name'], $destino1)) {
                    $status = "Archivo subido: <b>" . $archivo1 . "</b>";
                    $destino1 = $prefijo . "_" . $archivo1;
                } else {
                    $status = "Error al subir el archivo";
                }
            } else {
                $status = "Error al subir archivo";
            }
        } else {

            $destino1 = NULL;
            $archivo1 = NULL;
        }
		


        $entregaServicio = '';
        $plazoEjecucion = '';

        /* -------------------------------------------------------------------------------------- */
        /* -------------------------------------------------------------------------------------- */
//
        if (isset($_REQUEST['tipoCotizacion'])) {//CAST tipo de NECESIDAD
            switch ($_REQUEST['tipoCotizacion']) {
                case 'BIEN' :
                    $entregaServicio = 'entregables';
                    $plazoEjecucion = 'plazoEntrega';
                    break;
                case 'SERVICIO' :
                    $entregaServicio = 'desServicio';
                    $plazoEjecucion = 'detalleEjecucion';
                    break;
                default:
                    $entregaServicio = 'entregablesdesServicio';
                    $plazoEjecucion = 'entregaEjecucion';
                    break;
            }
        }


        $entregables = "";
        $plazoEntrega = "";
        //$entregables = $_POST[$this->campoSeguroCodificar($entregaServicio, $_REQUEST['tiempo'])];
        //$plazoEntrega = $_POST[$this->campoSeguroCodificar($plazoEjecucion, $_REQUEST['tiempo'])];
        $descuentos = $_POST[$this->campoSeguroCodificar('descuentos', $_REQUEST['tiempo'])];
        $observaciones = $_POST[$this->campoSeguroCodificar('observaciones', $_REQUEST['tiempo'])];


        $datosTextoEnriquecido = array(
            'entregables' => $entregables,
            'plazoEntrega' => $plazoEntrega,
            'descuentos' => $descuentos,
        	'observaciones' => $observaciones
        );
        
        
        $datosConsultaSol = array(
            'proveedor' =>$_REQUEST['id_proveedor'],
            'solicitud' => $_REQUEST['solicitud']
        );
        
        $cadenaSql = $this->miSql->getCadenaSql('consultarIdsolicitud', $datosConsultaSol);
        $id_solicitud= $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $datosConsultaSol, 'consultarIdsolicitud');


        $fechaVencimiento = $this->cambiafecha_format($_REQUEST ['fechaVencimientoCot']);

        $datosSolicitud = array(
            'solicitud' =>$id_solicitud[0][0],
            'entregables' => $datosTextoEnriquecido['entregables'],
            'plazoEntrega' => $datosTextoEnriquecido['plazoEntrega'],
            'precio' => $_REQUEST['precioCot'],
            'descuentos' => $datosTextoEnriquecido['descuentos'],
            'observaciones' => $datosTextoEnriquecido['observaciones'],
            'fechaVencimiento' => $fechaVencimiento,
            'soporte' => $destino1,
            'fechaRegistro' => date('Y-m-d'),
            'usuario' => $_REQUEST ['usuario']
        );

        
        $datosRespuestaSolicitudCotizacionOld = $this->miSql->getCadenaSql ( 'buscarRespuesta', $id_solicitud[0][0] );
        $resultadoRespuesta = $esteRecursoDB->ejecutarAcceso($datosRespuestaSolicitudCotizacionOld, "busqueda");
        
        $datosRespuesta = array(
        		'id' => $resultadoRespuesta[0]['id'],
        		'solicitud_cotizacion' => $id_solicitud[0][0],
        		'informacion_entrega' => $datosTextoEnriquecido['entregables'],
        		'observaciones' => $datosTextoEnriquecido['observaciones'],
        		'fecha_vencimiento' => $fechaVencimiento,
        		'soporte_cotizacion' => $destino1,
        		'fecha_registro' => $resultadoRespuesta[0]['fecha_registro'],
        		'descripcion' => $datosTextoEnriquecido['plazoEntrega'],
        		'descuentos' => $datosTextoEnriquecido['descuentos'],
        		'estado' => 't'
        );
        
        
        
        
        //*****************************************************************************************
        
        
        $cadena_sql = $this->miSql->getCadenaSql("buscarDetalleItemsCastRes", $resultadoRespuesta[0]['id']);
        $resultadoItemsCast = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        $cadena_sql = $cadenaSql = $this->miSql->getCadenaSql('adendasModificacionRes', $resultadoItemsCast[0][0]);
        $resultadoAdendas = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        
        $cadena_sql = $cadenaSql = $this->miSql->getCadenaSql('adendasModificacionSolCastRes', $resultadoItemsCast[0][0]);
        $resultadoAdendasSolCast = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
        
        if($resultadoAdendas && $resultadoAdendasSolCast){
        	$existeRegistroBase = true;
        }else{
        	$existeRegistroBase = false;
        }
        
        
        
        if(!$existeRegistroBase){//Hacer REGISTRO BASE Información Inicial
        	
        	$json_anterior = json_encode($resultadoRespuesta);    	
        	$json_nuevo = "[{}]";
        	
        	$datosSolicitudLog = array(
        			'registro_anterior' => $json_anterior,
        			'id_respuesta' => $resultadoRespuesta[0]['id'],
        			'fecha' => date("Y-m-d H:i:s"),
        			'registro_nuevo' => $json_nuevo
        	);
        	
        	$cadenaSqlLogRes = $this->miSql->getCadenaSql('insertarLogRespuestaBaseOne', $datosSolicitudLog);
        	array_push($SQLs, $cadenaSqlLogRes);
        	
        	
        	//******************************** ITEMS ****
        	
        	$countFPParam = $_REQUEST ['countItems'];
        	
        	 
        	$subFP = explode("@$&$@", $_REQUEST ['idsItems']);
        	
        	$subFPValores = explode("@$&$@", $_REQUEST ['idsItemsProv']);
        	 
        	$cantidadParametrosValores = ($countFPParam-1) * 3;
        	$cantidadParametros = ($countFPParam-1);
        	
        	$limitP = 0;
        	while($limitP < $cantidadParametrosValores){
        	
        		$subCountValores[$limitP] = explode(" ", $subFPValores[$limitP]);
        	
        		$limitP++;
        	
        	}
        	
        	$limitP = 0;
        	while($limitP < $cantidadParametros){
        	
        		$limitP++;
        		$subCount[$limitP] = explode(" ", $subFP[$limitP]);
        	
        	}
        	$limit = 0;
        	$limitIds = 0;
        	
        	while ( $limit < $cantidadParametrosValores ) {
        			
        		$valorMod = str_replace ( ".", "", $subFPValores [$limit] );
        		$valorMod = str_replace ( ",", ".", $valorMod );
        			
        		$valorIva = $subFPValores [$limit + 1];
        		$valorIva = str_replace ( "\\", "", $valorIva );
        		$valorFicha = $subFPValores [$limit + 2];
        			
        		$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'consultar_id_iva_Item', $valorIva );
        		$matrizItemsIva = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
        			
        		$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'respuestaItemActual', $subFP [$limitIds + 1] );
        		$datoFPActual = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
        			
        			
        		$json_anterior = json_encode($datoFPActual);
        		$json_nuevo = "[{}]";
        			
        		$datosFPLog = array(
        				'registro_anterior' => $json_anterior,
        				'item_cotizacion' => $subFP [$limitIds + 1],
        				'fecha' => date("Y-m-d H:i:s"),
        				'registro_nuevo' => $json_nuevo,
        				'modificacion_respuesta_cotizacion_proveedor' => "currval('agora.modificacion_respuesta_cotizacion_proveedor_id_seq')"
        		);
        			
        		$cadenaSqlLogFP = $this->miSql->getCadenaSql('insertarLogRespuestaBaseItemOne', $datosFPLog);
        		array_push($SQLs, $cadenaSqlLogFP);
        			
        		$limit = $limit + 3;
        		$limitIds = $limitIds + 1;
        	}
        	 
        	//****************************************
        	     
        	
        	
        }
        
        
        //*****************************************************************************************
        

        $json_anterior = json_encode($resultadoRespuesta);
        
        $json_nuevo = json_encode($datosRespuesta);
        
        																	//$json_decode = json_decode($json_item, true);
        
        $datosSolicitudLog = array(
        		'registro_anterior' => $json_anterior,
        		'id_respuesta' => $resultadoRespuesta[0]['id'],
        		'fecha' => date("Y-m-d H:i:s"),
        		'registro_nuevo' => $json_nuevo
        );
        
        $cadenaSqlLogRes = $this->miSql->getCadenaSql('insertarLogRespuestaBase', $datosSolicitudLog);
        array_push($SQLs, $cadenaSqlLogRes);
        
        
        if($destino1 != null){ //Se actualizó el archivo adjunto
        	//UPDATE File
        	
        	$datosRespuestaSolicitudCotizacionUpd = $this->miSql->getCadenaSql ( 'actualizarRespuestaWithFile', $datosRespuesta );
        	array_push($SQLs, $datosRespuestaSolicitudCotizacionUpd);
        	
        }else{
        	
        	$datosRespuestaSolicitudCotizacionUpd = $this->miSql->getCadenaSql ( 'actualizarRespuestaNotFile', $datosRespuesta );
        	array_push($SQLs, $datosRespuestaSolicitudCotizacionUpd);
        	
        }
        
        
        //******************************** ITEMS ****************************************************************
        
        $countFPParam = $_REQUEST ['countItems'];
        
     
        $subFP = explode("@$&$@", $_REQUEST ['idsItems']);
        
        $subFPValores = explode("@$&$@", $_REQUEST ['idsItemsProv']);
       
        $cantidadParametrosValores = ($countFPParam-1) * 3;
        $cantidadParametros = ($countFPParam-1);
        
        $limitP = 0;
        while($limitP < $cantidadParametrosValores){
        	 
        	 $subCountValores[$limitP] = explode(" ", $subFPValores[$limitP]);
        	 
        	$limitP++;
        	 
        }
        
        $limitP = 0;
        while($limitP < $cantidadParametros){
        	 
        	$limitP++;
        	$subCount[$limitP] = explode(" ", $subFP[$limitP]);
        	 
        }
		$limit = 0;
		$limitIds = 0;
		
		while ( $limit < $cantidadParametrosValores ) {
			
			$valorMod = str_replace ( ".", "", $subFPValores [$limit] );
			$valorMod = str_replace ( ",", ".", $valorMod );
			
			$valorIva = $subFPValores [$limit + 1];
			$valorIva = str_replace ( "\\", "", $valorIva );
			$valorFicha = $subFPValores [$limit + 2];
			
			$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'consultar_id_iva_Item', $valorIva );
			$matrizItemsIva = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
			
			$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'respuestaItemActual', $subFP [$limitIds + 1] );
			$datoFPActual = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
			
			$datoFP = array (
					'id' => $subFP [$limitIds + 1],
					'respuesta_cotizacion_proveedor' => $datoFPActual[0]['respuesta_cotizacion_proveedor'],
					'item_cotizacion_padre_id' => $datoFPActual[0]['item_cotizacion_padre_id'],
					'valor_unitario' => $valorMod,
					'iva' => $matrizItemsIva [0] [0],
					'ficha_tecnica' => $valorFicha 
			);
			
			
			$json_anterior = json_encode($datoFPActual);
			$json_nuevo = json_encode($datoFP);
			
			$datosFPLog = array(
					'registro_anterior' => $json_anterior,
					'item_cotizacion' => $subFP [$limitIds + 1],
					'fecha' => date("Y-m-d H:i:s"),
					'registro_nuevo' => $json_nuevo,
					'modificacion_respuesta_cotizacion_proveedor' => "currval('agora.modificacion_respuesta_cotizacion_proveedor_id_seq')"
			);
			
			$cadenaSqlLogFP = $this->miSql->getCadenaSql('insertarLogRespuestaBaseItem', $datosFPLog);
			array_push($SQLs, $cadenaSqlLogFP);
			
			
			$datoRegFP = $this->miSql->getCadenaSql ( 'actualizarRespuestaItemsProveedor', $datoFP );
			array_push ( $SQLs, $datoRegFP );
			
			$limit = $limit + 3;
			$limitIds = $limitIds + 1;
		}
       
        //*************************************************************************************************************

        $actualizoRespuestaProveedorCotizacion = $esteRecursoDB->transaccion($SQLs);
        
        
        if ($actualizoRespuestaProveedorCotizacion) {
        	
        	
			$datos = array (
					'solicitud' => $id_solicitud [0] [0],
					'entregables' => $datosTextoEnriquecido ['entregables'],
					'plazoEntrega' => $datosTextoEnriquecido ['plazoEntrega'],
					'precio' => $_REQUEST ['precioCot'],
					'observaciones' => $datosTextoEnriquecido ['observaciones'],
					'descuentos' => $datosTextoEnriquecido ['descuentos'],
					'fechaVencimiento' => $fechaVencimiento,
					'soporte' => $destino1,
					'fechaRegistro' => date ( 'Y-m-d' ),
					'usuario' => $_REQUEST ['usuario'],
					'proveedor' => $_REQUEST ['id_proveedor'],
					'objeto' => $_REQUEST ['solicitud'],
					'numero_solicitud' => $_REQUEST ['numero_solicitud'],
					'vigencia' => $_REQUEST ['vigencia'],
					'titulo_cotizacion' => $_REQUEST ['titulo_cotizacion'],
					'fecha_cierre' => $_REQUEST ['fecha_cierre'] 
			);


                if (!empty($_SERVER['HTTP_CLIENT_IP'])){
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }else{
                    $ip = $_SERVER['REMOTE_ADDR'];
                }
                $c = 0;
                while ($c < count($SQLs)){
                    $SQLsDec[$c] = $this->miConfigurador->fabricaConexiones->crypto->codificar($SQLs[$c]);
                    $c++;
                }
                $query = json_encode($SQLsDec);
                $numberSolicitud = "SC-" . sprintf("%05d", $_REQUEST ['solicitud']);

                $c = 0;
                while ($c < count($resultadoRespuesta[0])/2){
                    $DecOld[$c] = $this->miConfigurador->fabricaConexiones->crypto->codificar($resultadoRespuesta[0][$c]);
                    $c++;
                }
                $data = json_encode($DecOld);
                
                    
                $datosLog = array (
                        'tipo_log' => 'MODIFICACION',
                        'modulo' => 'MOCOTP',
                        'numero_cotizacion' => $numberSolicitud,
                        'vigencia' => $_REQUEST ['vigencia'],
                        'query' => $query,
                        'data' => $data,
                        'host' => $ip,
                        'fecha_log' => date("Y-m-d H:i:s"),
                        'usuario' => $_REQUEST ['usuario']
                );
                $cadenaSQL = $this->miSql->getCadenaSql("insertarLogCotizacionUp", $datosLog);
                $resultadoLog = $frameworkRecursoDB->ejecutarAcceso($cadenaSQL, 'busqueda');

            
            redireccion::redireccionar('actualizo', $datos);
            exit();
        } else {

                if (!empty($_SERVER['HTTP_CLIENT_IP'])){
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }else{
                    $ip = $_SERVER['REMOTE_ADDR'];
                }
                $c = 0;
                while ($c < count($SQLs)){
                    $SQLsDec[$c] = $this->miConfigurador->fabricaConexiones->crypto->codificar($SQLs[$c]);
                    $c++;
                }
                $query = json_encode($SQLsDec);
                $error = json_encode(error_get_last());
                $numberSolicitud = "SC-" . sprintf("%05d", $_REQUEST ['solicitud']);
                
                $datosLog = array (
                        'tipo_log' => 'MODIFICACION',
                        'modulo' => 'MOCOTP',
                        'numero_cotizacion' => $numberSolicitud,
                        'vigencia' => $_REQUEST ['vigencia'],
                        'query' => $query,
                        'error' => $error,
                        'host' => $ip,
                        'fecha_log' => date("Y-m-d H:i:s"),
                        'usuario' => $_REQUEST ['usuario']
                );
                $cadenaSQL = $this->miSql->getCadenaSql("insertarLogCotizacionError", $datosLog);
                $resultadoLog = $frameworkRecursoDB->ejecutarAcceso($cadenaSQL, 'busqueda');
                    
                $caso = "RCLP-" . date("Y") . "-" . $resultadoLog[0][0];
           
            redireccion::redireccionar('noInserto', $caso);
            exit();
        }
    }

    function resetForm() {
        foreach ($_REQUEST as $clave => $valor) {

            if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
                unset($_REQUEST [$clave]);
            }
        }
    }

}

$miRegistrador = new Registrar($this->lenguaje, $this->sql, $this->funcion);

$resultado = $miRegistrador->procesarFormulario();
?>