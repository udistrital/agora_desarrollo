<?php

namespace hojaDeVida\crearDocente;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}

include_once ("core/manager/Configurador.class.php");
include_once ("core/connection/Sql.class.php");

// Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
// en camel case precedida por la palabra sql
class Sql extends \Sql {
	var $miConfigurador;
	function __construct() {
		$this->miConfigurador = \Configurador::singleton ();
	}
	function getCadenaSql($tipo, $variable = "") {
		
		/**
		 * 1.
		 * Revisar las variables para evitar SQL Injection
		 */
		$prefijo = $this->miConfigurador->getVariableConfiguracion ( "prefijo" );
		$idSesion = $this->miConfigurador->getVariableConfiguracion ( "id_sesion" );
		
		switch ($tipo) {
			
			/* REGISTRAR COTIZACION */
			case "actualizarRespuestaCotizacionDec" :
				$cadenaSql = " UPDATE agora.respuesta_cotizacion_ordenador SET ";
				$cadenaSql .= " decision = '" . $variable ['decision'] ."' ";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " id_objeto = '" . $variable ['id_objeto'] . "' AND id_solicitud = ".$variable ['id_solicitud'];
				break;
			
			/* REGISTRAR COTIZACION */
			case "actualizarRespuestaCotizacion" :
				$cadenaSql = " UPDATE agora.respuesta_cotizacion_ordenador SET ";
				$cadenaSql .= " decision = '" . $variable ['decision'] ."', ";
				$cadenaSql .= " respuesta = '" . $variable ['respuesta'] ."' ";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " id_objeto = '" . $variable ['id_objeto'] . "' AND id_solicitud = ".$variable ['id_solicitud'];
				break;
                            
                        case "buscarActividades" :
				$cadenaSql = " SELECT id_subclase ";
				$cadenaSql .= " FROM  agora.objeto_cotizacion_actividad_ciiu ";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " id_objeto = '" . $variable . "'";
				break;    
			
			
			/* ACTUALIZAR - OBJETO CONTRATO - ESTADO */
			case 'actualizarObjetoEst' :
				$cadenaSql = "UPDATE agora.objeto_cotizacion SET ";
				$cadenaSql .= "estado = '" . $variable ['estado'] . "' ";
				$cadenaSql .= " WHERE id_objeto=";
				$cadenaSql .= "'" . $variable ['id_objeto'] . "' ";
				break;
			
			/* ACTUALIZAR - OBJETO CONTRATO - ESTADO */
			case 'actualizarObjetoAsig' :
				$cadenaSql = "UPDATE agora.objeto_cotizacion SET ";
				$cadenaSql .= "justificacion_seleccion = '" . $variable ['justificacion'] . "' ";
				$cadenaSql .= " WHERE id_objeto=";
				$cadenaSql .= "'" . $variable ['id_objeto'] . "' ";
				break;
			
			 case "ciiuSubClaseByNumPush" :
						$cadenaSql = "SELECT";
						$cadenaSql .= " clase AS clase,";
						$cadenaSql .= " nombre AS descripcion,";
						$cadenaSql .= " id_subclase AS id_subclase,";
						$cadenaSql .= "	id_subclase||' - ('||nombre||')' AS  nombre";
						$cadenaSql .= " FROM ";
						$cadenaSql .= " core.ciiu_subclase";
						$cadenaSql .= " WHERE id_subclase = '".$variable."'";
						break;
                            
			case "buscarProveedoresInfoCotizacionCal" :
				$cadenaSql=" SELECT";
				$cadenaSql.=" S.id_solicitud";
				$cadenaSql.=" FROM";
				$cadenaSql.=" agora.solicitud_cotizacion S";
				$cadenaSql.=" JOIN agora.informacion_proveedor P ON P.id_proveedor = S.id_proveedor";
				$cadenaSql.=" JOIN core.ciudad U ON P.id_ciudad_contacto = U.id_ciudad";
				$cadenaSql.=" JOIN core.departamento D ON U.id_departamento = D.id_departamento";
				$cadenaSql.=" WHERE  S.id_objeto = " . $variable ['id_objeto'] ." AND S.id_proveedor =" . $variable['id_proveedor'];
				break;
			
			/* REGISTRAR COTIZACION */
			case "ingresarRespuestaCotizacionAsig" :
				$cadenaSql = " UPDATE agora.respuesta_cotizacion_ordenador SET ";
				$cadenaSql .= " decision = '" . $variable ['decision'] ."' ";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " id_objeto = '" . $variable ['id_objeto'] . "' AND id_solicitud = ".$variable ['id_solicitud'];
				break;
				
			
			case "buscarProveedoresInfoCotizacionMat" :
				$cadenaSql=" SELECT";
				$cadenaSql.=" P.id_proveedor,";
				$cadenaSql.=" ('(' ||P.num_documento || ', ' || P.tipopersona || ') - ' || P.nom_proveedor) as name ";
				$cadenaSql.=" FROM";
				$cadenaSql.=" agora.solicitud_cotizacion S";
				$cadenaSql.=" JOIN agora.informacion_proveedor P ON P.id_proveedor = S.id_proveedor";
				$cadenaSql.=" JOIN core.ciudad U ON P.id_ciudad_contacto = U.id_ciudad";
				$cadenaSql.=" JOIN core.departamento D ON U.id_departamento = D.id_departamento";
				$cadenaSql.=" WHERE  id_objeto = " . $variable;
				break;
			
			case "consultarSolicitudxProveedorRes" :
				$cadenaSql = "SELECT";
				$cadenaSql .= "	*";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.respuesta_cotizacion_ordenador";
				$cadenaSql .= " WHERE id_objeto = ".$variable['objeto'] . " AND id_solicitud = ".$variable['solicitud'];
				break;
			
			/* REGISTRAR COTIZACION */
			case "ingresarRespuestaCotizacion" :
				$hoy = date ( "Y-m-d" );
			
				$cadenaSql = " INSERT INTO agora.respuesta_cotizacion_ordenador";
				$cadenaSql .= " (";
				$cadenaSql .= " id_solicitud,";
				$cadenaSql .= " id_objeto,";
				$cadenaSql .= " respuesta,";
				$cadenaSql .= " decision";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES";
				$cadenaSql .= " (";
				$cadenaSql .= " " . $variable ['id_solicitud'] . ",";
				$cadenaSql .= " " . $variable ['id_objeto'] . ",";
				$cadenaSql .= " '" . $variable ['respuesta'] . "',";
				$cadenaSql .= " '" . $variable ['decision'] . "'";
				$cadenaSql .= " );";
				break;
			
			case "consultarSolicitudxProveedor" :
				$cadenaSql = "SELECT";
				$cadenaSql .= "	*";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.solicitud_cotizacion";
				$cadenaSql .= " WHERE id_objeto = ".$variable['objeto'] . " AND id_proveedor = ".$variable['proveedor'];
				break;
			
			case "buscarSolicitante" :
				$cadenaSql = "SELECT";
				$cadenaSql .= "	cargo";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " argo.cargo_supervisor_temporal";
				$cadenaSql .= " WHERE id = ".$variable;
				break;
			
			case "solicitante" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id,";
				$cadenaSql .= "	cargo";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " argo.cargo_supervisor_temporal";
				$cadenaSql .= " ORDER BY cargo";
				break;
			
			case "consultarRespuestaProveedor" :
				$cadenaSql = " SELECT * FROM agora.respuesta_cotizacion_proveedor ";
				$cadenaSql .= " WHERE id_solicitud = ".$variable ;//. " AND id_proveedor = " . $variable['idProveedor'];
				break;
			
			case "consultarIdRespuestaProveedor" :
				$cadenaSql = " SELECT * FROM agora.solicitud_cotizacion ";
				$cadenaSql .= " WHERE id_objeto = ".$variable['idObjeto'] . " AND id_proveedor = " . $variable['idProveedor'];
				break;
			
			case "consultar_proveedor" :
				$cadenaSql = " SELECT U.identificacion, U.tipo_identificacion FROM prov_usuario U";
				$cadenaSql .= " WHERE U.id_usuario = '" . $variable . "'";
				break;
					
			case "consultar_DatosProveedor" :
				$cadenaSql = " SELECT * FROM agora.informacion_proveedor ";
				$cadenaSql .= " WHERE id_proveedor = " . $variable . ";";
				break;
			
			
			case "listarObjetosParaCotizacion" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" * ";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" agora.objeto_cotizacion";
				$cadenaSql.=" WHERE responsable = '" . $variable . "';";
				break;
			
			
			case "buscarAreaConocimiento" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= " id_area,";
				$cadenaSql .= " nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.snies_area ";
				$cadenaSql .= " WHERE estado != 'INACTIVO';";
				break;
			
			case "buscarAreaConocimientoXNBC" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= " id_area,";
				$cadenaSql .= " nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.snies_nucleo_basico ";
				$cadenaSql .= " WHERE id_nucleo = " . $variable . ";";
				break;
			
			case "buscarNBCAjax" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= " id_nucleo,";
				$cadenaSql .= " nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.snies_nucleo_basico ";
				$cadenaSql .= " WHERE estado != 'INACTIVO'";
				$cadenaSql .= " AND id_area = " . $variable . ";";
				break;
				
			case "buscarNBC" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= " id_nucleo,";
				$cadenaSql .= " nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.snies_nucleo_basico ";
				$cadenaSql .= " WHERE estado != 'INACTIVO'";
				$cadenaSql .= " AND id_nucleo = " . $variable . ";";
				break;
			
			case "buscarUsuario" ://****************************************************************************
				$cadenaSql=" SELECT";
				$cadenaSql.=" *";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" prov_usuario";
				$cadenaSql.=" WHERE id_usuario = '" . $variable . "'";
				break;
			
			/* CONSULTAR siCapital */
			case "listaSolicitudNecesidad" :
				$cadenaSql = "SELECT *";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " CO_SOLICITUD_ADQ";
				$cadenaSql .= " WHERE VIGENCIA = " . $variable . "";
				break;
				
			case "listaSolicitudNecesidadXVigencia" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" P.NUM_SOL_ADQ as NUM_SOL_ADQ,";
				$cadenaSql.=" P.VIGENCIA as VIGENCIA,";
				$cadenaSql.=" U.NOMBRE_DEPENDENCIA as DEPENDENCIA,";
				$cadenaSql.=" F.FUN_NOMBRE as FUNCIONARIO,";
				$cadenaSql.=" F.FUN_CARGO as FUNCIONARIO_CARGO,";
				$cadenaSql.=" F.FUN_TIPO as FUNCIONARIO_TIPO,";
				$cadenaSql.=" P.FECHA_SOLICITUD as FECHA_SOLICITUD,";
				$cadenaSql.=" P.ORIGEN_SOLICITUD as ORIGEN_SOLICITUD,";
				$cadenaSql.=" V.NOMBRE_DEPENDENCIA as DEPENDENCIA_DESTINO,";
				$cadenaSql.=" P.JUSTIFICACION as JUSTIFICACION,";
				$cadenaSql.=" P.CONDICIONES_CONTRATACION as CONDICIONES,";
				$cadenaSql.=" P.VALOR_CONTRATACION as VALOR_CONTRATACION,";
				$cadenaSql.=" P.OBJETO as OBJETO,";
				$cadenaSql.=" P.TIPO_CONTRATACION as TIPO_CONTRATACION,";
				$cadenaSql.=" P.PLAZO_EJECUCION as PLAZO_EJECUCION,";
				$cadenaSql.=" P.ELABORADO_POR as ELABORADO_POR,";
				$cadenaSql.=" O.ORG_NOMBRE as ORDENADOR_GASTO,";
				$cadenaSql.=" O.ORG_ORDENADOR_GASTO as CARGO_ORDENADOR_GASTO,";
				$cadenaSql.=" P.CODIGO_UNIDAD_EJECUTORA as CODIGO_UNIDAD_EJECUTORA,";
				$cadenaSql.=" P.ESTADO as ESTADO";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" CO_SOLICITUD_ADQ P";
				$cadenaSql.=" JOIN CO_DEPENDENCIAS U ON U.COD_DEPENDENCIA = P.DEPENDENCIA";
				$cadenaSql.=" JOIN CO_DEPENDENCIAS V ON V.COD_DEPENDENCIA = P.DEPENDENCIA_DESTINO";
				$cadenaSql.=" JOIN SICAARKA.FUNCIONARIOS F ON F.FUN_IDENTIFICADOR = P.FUNCIONARIO";
				$cadenaSql.=" JOIN SICAARKA.ORDENADORES_GASTO O ON O.ORG_TIPO_ORDENADOR = P.CODIGO_ORDENADOR AND O.ORG_ESTADO = 'A'";
				$cadenaSql.=" WHERE P.VIGENCIA = " . $variable ['vigencia'];
				$cadenaSql.=" AND P.NUM_SOL_ADQ NOT IN (" . $variable ['solicitudes'] . ")";
				$cadenaSql.=" AND P.CODIGO_UNIDAD_EJECUTORA = " . $variable ['unidadEjecutora'];
				$cadenaSql.=" ORDER BY P.NUM_SOL_ADQ DESC";
				break;
				
			case "listaSolicitudNecesidadXNumSolicitud" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " P.NUM_SOL_ADQ as NUM_SOL_ADQ,";
				$cadenaSql .= " P.VIGENCIA as VIGENCIA,";
				$cadenaSql .= " U.NOMBRE_DEPENDENCIA as DEPENDENCIA,";
				$cadenaSql .= " F.FUN_NOMBRE as FUNCIONARIO,";
				$cadenaSql .= " F.FUN_CARGO as FUNCIONARIO_CARGO,";
				$cadenaSql .= " F.FUN_TIPO as FUNCIONARIO_TIPO,";
				$cadenaSql .= " P.FECHA_SOLICITUD as FECHA_SOLICITUD,";
				$cadenaSql .= " P.ORIGEN_SOLICITUD as ORIGEN_SOLICITUD,";
				$cadenaSql .= " V.NOMBRE_DEPENDENCIA as DEPENDENCIA_DESTINO,";
				$cadenaSql .= " P.JUSTIFICACION as JUSTIFICACION,";
				$cadenaSql .= " P.CONDICIONES_CONTRATACION as CONDICIONES,";
				$cadenaSql .= " P.VALOR_CONTRATACION as VALOR_CONTRATACION,";
				$cadenaSql .= " P.OBJETO as OBJETO,";
				$cadenaSql .= " P.TIPO_CONTRATACION as TIPO_CONTRATACION,";
				$cadenaSql .= " P.PLAZO_EJECUCION as PLAZO_EJECUCION,";
				$cadenaSql .= " P.ELABORADO_POR as ELABORADO_POR,";
				$cadenaSql .= " O.ORG_NOMBRE as ORDENADOR_GASTO,";
				$cadenaSql .= " O.ORG_ORDENADOR_GASTO as CARGO_ORDENADOR_GASTO,";
				$cadenaSql .= " P.CODIGO_UNIDAD_EJECUTORA as CODIGO_UNIDAD_EJECUTORA,";
				$cadenaSql .= " P.ESTADO as ESTADO";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " CO_SOLICITUD_ADQ P";
				$cadenaSql .= " JOIN CO_DEPENDENCIAS U ON U.COD_DEPENDENCIA = P.DEPENDENCIA";
				$cadenaSql .= " JOIN CO_DEPENDENCIAS V ON V.COD_DEPENDENCIA = P.DEPENDENCIA_DESTINO";
				$cadenaSql .= " JOIN SICAARKA.FUNCIONARIOS F ON F.FUN_IDENTIFICADOR = P.FUNCIONARIO";
				$cadenaSql .= " JOIN SICAARKA.ORDENADORES_GASTO O ON O.ORG_TIPO_ORDENADOR = P.CODIGO_ORDENADOR AND O.ORG_ESTADO = 'A'";
				$cadenaSql .= " WHERE P.NUM_SOL_ADQ = " . $variable['idSolicitud'];
				$cadenaSql .= " AND P.VIGENCIA = " . $variable['vigencia'];
				$cadenaSql .= " AND P.CODIGO_UNIDAD_EJECUTORA = " . $variable ['unidadEjecutora'];
				break;
			

				
			case "listarObjetosSinCotizacionXVigencia" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" string_agg(cast(numero_solicitud as text),',' ";
				$cadenaSql.=" ORDER BY ";
				$cadenaSql.=" numero_solicitud) ";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" agora.objeto_cotizacion ";
				$cadenaSql.=" WHERE ";
				$cadenaSql.=" vigencia = " . $variable['vigencia'];
				$cadenaSql.=" AND unidad_ejecutora = " . $variable ['unidadEjecutora'];
				$cadenaSql.=" AND ";
				$cadenaSql.=" estado = 'RELACIONADO';";
				break;
				
			case "listarObjetosRelacionadosXVigencia" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " string_agg(cast(numero_solicitud as text),',' ";
				$cadenaSql .= " ORDER BY ";
				$cadenaSql .= " numero_solicitud) ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_cotizacion ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " vigencia = " . $variable['vigencia'];
				$cadenaSql .= " AND unidad_ejecutora = " . $variable ['unidadEjecutora'].";";
				break;
				
				
			case "listarObjetosConCotizacionXVigencia" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " string_agg(cast(numero_solicitud as text),',' ";
				$cadenaSql .= " ORDER BY ";
				$cadenaSql .= " numero_solicitud) ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_cotizacion ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " vigencia = " . $variable['vigencia'];
				$cadenaSql .= " AND unidad_ejecutora = " . $variable ['unidadEjecutora'];
				$cadenaSql .= " AND ";
				$cadenaSql .= " estado = 'COTIZACION';";
				break;
				
			case "listaSolicitudNecesidadXNumSolicitudSinCotizar" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " P.NUM_SOL_ADQ as NUM_SOL_ADQ,";
				$cadenaSql .= " P.VIGENCIA as VIGENCIA,";
				$cadenaSql .= " U.NOMBRE_DEPENDENCIA as DEPENDENCIA,";
				$cadenaSql .= " F.FUN_NOMBRE as FUNCIONARIO,";
				$cadenaSql .= " F.FUN_CARGO as FUNCIONARIO_CARGO,";
				$cadenaSql .= " F.FUN_TIPO as FUNCIONARIO_TIPO,";
				$cadenaSql .= " P.FECHA_SOLICITUD as FECHA_SOLICITUD,";
				$cadenaSql .= " P.ORIGEN_SOLICITUD as ORIGEN_SOLICITUD,";
				$cadenaSql .= " V.NOMBRE_DEPENDENCIA as DEPENDENCIA_DESTINO,";
				$cadenaSql .= " P.JUSTIFICACION as JUSTIFICACION,";
				$cadenaSql .= " P.CONDICIONES_CONTRATACION as CONDICIONES,";
				$cadenaSql .= " P.VALOR_CONTRATACION as VALOR_CONTRATACION,";
				$cadenaSql .= " P.OBJETO as OBJETO,";
				$cadenaSql .= " P.TIPO_CONTRATACION as TIPO_CONTRATACION,";
				$cadenaSql .= " P.PLAZO_EJECUCION as PLAZO_EJECUCION,";
				$cadenaSql .= " P.ELABORADO_POR as ELABORADO_POR,";
				$cadenaSql .= " O.ORG_NOMBRE as ORDENADOR_GASTO,";
				$cadenaSql .= " O.ORG_ORDENADOR_GASTO as CARGO_ORDENADOR_GASTO,";
				$cadenaSql .= " P.CODIGO_UNIDAD_EJECUTORA as CODIGO_UNIDAD_EJECUTORA,";
				$cadenaSql .= " P.ESTADO as ESTADO";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " CO_SOLICITUD_ADQ P";
				$cadenaSql .= " JOIN CO_DEPENDENCIAS U ON U.COD_DEPENDENCIA = P.DEPENDENCIA";
				$cadenaSql .= " JOIN CO_DEPENDENCIAS V ON V.COD_DEPENDENCIA = P.DEPENDENCIA_DESTINO";
				$cadenaSql .= " JOIN SICAARKA.FUNCIONARIOS F ON F.FUN_IDENTIFICADOR = P.FUNCIONARIO";
				$cadenaSql .= " JOIN SICAARKA.ORDENADORES_GASTO O ON O.ORG_TIPO_ORDENADOR = P.CODIGO_ORDENADOR AND O.ORG_ESTADO = 'A'";
				$cadenaSql .= " WHERE P.NUM_SOL_ADQ IN (" . $variable ['solicitudes'] . ")";
				$cadenaSql .= " AND P.VIGENCIA = " . $variable ['vigencia'];
				$cadenaSql .= " AND P.CODIGO_UNIDAD_EJECUTORA = " . $variable ['unidadEjecutora'];
				break;
				
			case "listaSolicitudNecesidadXNumSolicitudEnCotizar" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " P.NUM_SOL_ADQ as NUM_SOL_ADQ,";
				$cadenaSql .= " P.VIGENCIA as VIGENCIA,";
				$cadenaSql .= " U.NOMBRE_DEPENDENCIA as DEPENDENCIA,";
				$cadenaSql .= " F.FUN_NOMBRE as FUNCIONARIO,";
				$cadenaSql .= " F.FUN_CARGO as FUNCIONARIO_CARGO,";
				$cadenaSql .= " F.FUN_TIPO as FUNCIONARIO_TIPO,";
				$cadenaSql .= " P.FECHA_SOLICITUD as FECHA_SOLICITUD,";
				$cadenaSql .= " P.ORIGEN_SOLICITUD as ORIGEN_SOLICITUD,";
				$cadenaSql .= " V.NOMBRE_DEPENDENCIA as DEPENDENCIA_DESTINO,";
				$cadenaSql .= " P.JUSTIFICACION as JUSTIFICACION,";
				$cadenaSql .= " P.CONDICIONES_CONTRATACION as CONDICIONES,";
				$cadenaSql .= " P.VALOR_CONTRATACION as VALOR_CONTRATACION,";
				$cadenaSql .= " P.OBJETO as OBJETO,";
				$cadenaSql .= " P.TIPO_CONTRATACION as TIPO_CONTRATACION,";
				$cadenaSql .= " P.PLAZO_EJECUCION as PLAZO_EJECUCION,";
				$cadenaSql .= " P.ELABORADO_POR as ELABORADO_POR,";
				$cadenaSql .= " O.ORG_NOMBRE as ORDENADOR_GASTO,";
				$cadenaSql .= " O.ORG_ORDENADOR_GASTO as CARGO_ORDENADOR_GASTO,";
				$cadenaSql .= " P.CODIGO_UNIDAD_EJECUTORA as CODIGO_UNIDAD_EJECUTORA,";
				$cadenaSql .= " P.ESTADO as ESTADO";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " CO_SOLICITUD_ADQ P";
				$cadenaSql .= " JOIN CO_DEPENDENCIAS U ON U.COD_DEPENDENCIA = P.DEPENDENCIA";
				$cadenaSql .= " JOIN CO_DEPENDENCIAS V ON V.COD_DEPENDENCIA = P.DEPENDENCIA_DESTINO";
				$cadenaSql .= " JOIN SICAARKA.FUNCIONARIOS F ON F.FUN_IDENTIFICADOR = P.FUNCIONARIO";
				$cadenaSql .= " JOIN SICAARKA.ORDENADORES_GASTO O ON O.ORG_TIPO_ORDENADOR = P.CODIGO_ORDENADOR AND O.ORG_ESTADO = 'A'";
				$cadenaSql .= " WHERE P.NUM_SOL_ADQ IN (" . $variable ['solicitudes'] . ")";
				$cadenaSql .= " AND P.VIGENCIA = " . $variable ['vigencia'];
				$cadenaSql .= " AND P.CODIGO_UNIDAD_EJECUTORA = " . $variable ['unidadEjecutora'];
				break;
				
			case "estadoSolicitudAgora" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " estado ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_cotizacion ";
				$cadenaSql .= " WHERE id_objeto = " . $variable['idObjeto'];
				break;
				
			case "informacionSolicitudAgoraNoCast" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " * ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_cotizacion";
				$cadenaSql .= " WHERE  id_objeto=" . $variable['idObjeto']; // Activo
				break;
				
			case "informacionSolicitudAgora" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_objeto,";
				$cadenaSql .= " titulo_cotizacion,";
				$cadenaSql .= " numero_solicitud,";
				$cadenaSql .= " id_solicitante,";
				$cadenaSql .= " vigencia,";
				$cadenaSql .= " unidad_ejecutora,";
				$cadenaSql .= "	fecha_registro,";
				$cadenaSql .= "	fecha_solicitud_cotizacion,";
				$cadenaSql .= "	tipo_necesidad,";
				$cadenaSql .= "	esp.dependencia,";
				$cadenaSql .= " objetivo,";
				$cadenaSql .= " requisitos,";
				$cadenaSql .= " observaciones,";
				$cadenaSql .= " responsable,";
				$cadenaSql .= " fecha_apertura,";
				$cadenaSql .= " fecha_cierre,";
				$cadenaSql .= "	estado";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_cotizacion O";
				$cadenaSql .= " JOIN agora.parametro_dependencia esp ON esp.id_dependencia = O.dependencia";
				$cadenaSql .= " WHERE  id_objeto=" . $variable['idObjeto']; // Activo
				break;
				
				
			case "informacionCIIURelacionada" :
				$cadenaSql=" SELECT D.id_division as num_division, P.id_clase as num_clase, C.id_subclase as num_subclase";
				$cadenaSql.=" FROM agora.objeto_cotizacion T";
				$cadenaSql.=" JOIN core.ciiu_subclase C ON C.id_subclase = T.codigociiu";
				$cadenaSql.=" JOIN core.ciiu_clase P ON P.id_clase = C.clase";
				$cadenaSql.=" JOIN core.ciiu_division D ON D.id_division = P.division";
				$cadenaSql .= " WHERE T.id_objeto = " . $variable ['idObjeto'];
				break;
				
				
			case "filtroVigencia" :
				$cadenaSql=" SELECT ";
				$cadenaSql.=" VIGENCIA AS ID, ";
				$cadenaSql.=" VIGENCIA AS VIGENCIA ";
				$cadenaSql.=" FROM ";
				$cadenaSql.=" CO_SOLICITUD_ADQ ";
				$cadenaSql.=" GROUP BY VIGENCIA ";
				$cadenaSql.=" ORDER BY VIGENCIA";
				break;
				
				/* CONSULTAR ACTIVIDADES DE LA NECESIDAD */
                            case "eliminarActividadActual" : // ********************************************************************
				$cadenaSql = " DELETE FROM";
				$cadenaSql .= " agora.objeto_cotizacion_actividad_ciiu ";
				$cadenaSql .= " WHERE id_objeto = " . $variable;
				break;
			case "consultarActividades" : // ********************************************************************
				$cadenaSql = " SELECT";
				$cadenaSql .= " A.id_subclase,";
				$cadenaSql .= " nombre,";
                                $cadenaSql .= " clase";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_cotizacion_actividad_ciiu A";
				$cadenaSql .= " JOIN core.ciiu_subclase S ON S.id_subclase = A.id_subclase ";
				$cadenaSql .= " WHERE id_objeto = " . $variable;
				break;
				
			case "consultarActividadesImp" : // ********************************************************************
				$cadenaSql = " SELECT";
				$cadenaSql .= " A.id_subclase,";
				$cadenaSql .= " UPPER(S.nombre) as nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_cotizacion_actividad_ciiu A";
				$cadenaSql .= " JOIN core.ciiu_subclase S ON S.id_subclase = A.id_subclase ";
				$cadenaSql .= " WHERE id_objeto = " . $variable;
				break;
				
			case "consultarNBCImp" : // ********************************************************************
				$cadenaSql = " SELECT";
				$cadenaSql .= " A.id_nucleo,";
				$cadenaSql .= " UPPER(S.nombre) as nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_cotizacion_nucleo_basico A";
				$cadenaSql .= " JOIN core.snies_nucleo_basico S ON S.id_nucleo = A.id_nucleo ";
				$cadenaSql .= " WHERE A.id_objeto = " . $variable;
				break;
				
				
				/* REGISTRAR DATOS - USUARIO */
			case "registrarActividad" : // **********************************************************
				$cadenaSql = " INSERT INTO ";
				$cadenaSql .= " agora.objeto_cotizacion_actividad_ciiu ";
				$cadenaSql .= " (";
				$cadenaSql .= " id_objeto,";
				$cadenaSql .= " id_subclase";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES";
				$cadenaSql .= " (";
				$cadenaSql .= " '" . $variable ['idObjeto'] . "',";
				$cadenaSql .= " '" . $variable ['actividad'] . "'";
				$cadenaSql .= " );";
				break;
				
			case "registrarNucleoBasico" : // **********************************************************
				$cadenaSql = " INSERT INTO ";
				$cadenaSql .= " agora.objeto_cotizacion_nucleo_basico ";
				$cadenaSql .= " (";
				$cadenaSql .= " id_objeto,";
				$cadenaSql .= " id_nucleo";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES";
				$cadenaSql .= " (";
				$cadenaSql .= " '" . $variable ['idObjeto'] . "',";
				$cadenaSql .= " '" . $variable ['objetoNBC'] . "'";
				$cadenaSql .= " );";
				break;
				
			case "actualizarNucleoBasico" : // **********************************************************
				$cadenaSql = " UPDATE agora.objeto_cotizacion_nucleo_basico SET ";
				$cadenaSql .= " id_nucleo = " . $variable ['objetoNBC'];
				$cadenaSql .= " WHERE";
				$cadenaSql .= " id_objeto = '" . $variable ['idObjeto'] . "';";
				break;
				
			case "consultarNucleoBasico" : // **********************************************************
				$cadenaSql = " SELECT ";
				$cadenaSql .= " id_nucleo ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_cotizacion_nucleo_basico ";
				$cadenaSql .= " WHERE";
				$cadenaSql .= " id_objeto = '" . $variable . "';";
				break;
				
				
				
				
				
			/* VERIFICAR NUMERO DE NIT */
			case "verificarActividad" : // ******************************************************************
				$cadenaSql = " SELECT";
				$cadenaSql .= " id_objeto";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_cotizacion_actividad_ciiu ";
				$cadenaSql .= " WHERE id_objeto= '" . $variable ['idObjeto'] . "'";
				$cadenaSql .= " AND id_subclase = '" . $variable ['actividad'] . "'";
				break;
				
			case "actividadesXNecesidad" :
				$cadenaSql = " SELECT ";
				$cadenaSql .= " string_agg('''' || cast(id_subclase as text) || '''',',' ";
				$cadenaSql .= " ORDER BY ";
				$cadenaSql .= " id_subclase) ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_cotizacion_actividad_ciiu ";
				$cadenaSql .= " WHERE ";
				$cadenaSql .= " id_objeto = " . $variable . ";";
				break;
				
				
				
				
				
				
			
			
			
				
			/* REGISTRAR codigo validacion */
			case "ingresarCodigo" :
				$cadenaSql = " INSERT INTO agora.codigo_validacion";
				$cadenaSql .= " (";
				$cadenaSql .= " id_tabla,";
				$cadenaSql .= " tipo_certificacion,";
				$cadenaSql .= " fecha";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES";
				$cadenaSql .= " (";
				$cadenaSql .= " '" . $variable ['idObjeto'] . "',";
				$cadenaSql .= " '" . $variable ['tipo'] . "',";
				$cadenaSql .= " '" . $variable ['fecha'] . "'";
				$cadenaSql .= " ) RETURNING id_codigo_validacion;";
				break;
			
			/* BUSCAR PROVEEDORES SOLICITUD DE COTIZACION */
			case "buscarProveedores" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " P.id_proveedor,";
				$cadenaSql .= " P.tipopersona,";
				$cadenaSql .= "	P.correo,";
				$cadenaSql .= " P.nom_proveedor,";
				$cadenaSql .= "	P.num_documento,";
				$cadenaSql .= " P.puntaje_evaluacion,";
				$cadenaSql .= "	P.clasificacion_evaluacion";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.solicitud_cotizacion S";
				$cadenaSql .= " JOIN agora.informacion_proveedor P ON P.id_proveedor = S.id_proveedor";
				$cadenaSql .= " WHERE  id_objeto=" . $variable;
				$cadenaSql .= " ORDER BY puntaje_evaluacion DESC";
				break;
				
			case "buscarProveedoresInfoCotizacion" :
				$cadenaSql=" SELECT";
				$cadenaSql.=" P.id_proveedor,";
				$cadenaSql.=" P.tipopersona,";
				$cadenaSql.=" P.correo,";
				$cadenaSql.=" P.direccion,";
				$cadenaSql.=" P.web,";
				$cadenaSql.=" (U.nombre || ', ' || D.nombre || ', ' || 'Colombia') as ubicacion, ";
				$cadenaSql.=" P.nom_proveedor,";
				$cadenaSql.=" P.num_documento,";
				$cadenaSql.=" P.puntaje_evaluacion,";
				$cadenaSql.=" P.clasificacion_evaluacion";
				$cadenaSql.=" FROM";
				$cadenaSql.=" agora.solicitud_cotizacion S";
				$cadenaSql.=" JOIN agora.informacion_proveedor P ON P.id_proveedor = S.id_proveedor";
				$cadenaSql.=" JOIN core.ciudad U ON P.id_ciudad_contacto = U.id_ciudad";
				$cadenaSql.=" JOIN core.departamento D ON U.id_departamento = D.id_departamento";
				$cadenaSql.=" WHERE  id_objeto = " . $variable;
				break;
			
			/* REGISTRAR COTIZACION */
			case "ingresarCotizacion" :
				$hoy = date ( "Y-m-d" );
				
				$cadenaSql = " INSERT INTO agora.solicitud_cotizacion";
				$cadenaSql .= " (";
				$cadenaSql .= " id_objeto,";
				$cadenaSql .= " id_proveedor,";
				$cadenaSql .= " estado";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES";
				$cadenaSql .= " (";
				$cadenaSql .= " '" . $variable [0] . "',";
				$cadenaSql .= " '" . $variable [1] . "',";
				$cadenaSql .= " 'ABIERTO'";
				$cadenaSql .= " );";
				break;
			
			/* ACTUALIZAR - OBJETO CONTRATO - ESTADO */
			case 'actualizarObjeto' :
				$cadenaSql = "UPDATE agora.objeto_cotizacion SET ";
				$cadenaSql .= "numero_solicitud = '" . $variable ['numero_solicitud'] . "',";
				$cadenaSql .= "estado = '" . $variable ['estado'] . "',";
				$cadenaSql .= "fecha_solicitud_cotizacion = '" . $variable ['fecha'] . "'";
				$cadenaSql .= " WHERE id_objeto=";
				$cadenaSql .= "'" . $variable ['idObjeto'] . "' ";
				break;
			
			/* verificar si existe proveedores con la actividad economica */
			case "verificarActividadProveedor" :
				$cadenaSql = "SELECT *";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.proveedor_actividad_ciiu";
				$cadenaSql .= " WHERE  id_subclase IN (" . $variable . ");";
				break;
			
			/**
			 * Lista proveedores
			 * Que cumlen con la actividad economica
			 * Que Tienen puntaje mayor a 45 (Se elimina la restriccion por peticion de Usuario)
			 * El limite de registros lo establece el objeto a contratar
			 */
			case "proveedoresByClasificacion" :
				$cadenaSql = "SELECT DISTINCT";
				$cadenaSql .= " id_proveedor,";
				$cadenaSql .= " P.num_documento,";
				$cadenaSql .= "	nom_proveedor,";
				$cadenaSql .= "	puntaje_evaluacion,";
				$cadenaSql .= "	tipopersona,";
				$cadenaSql .= "	correo,";
				$cadenaSql .= "	clasificacion_evaluacion";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor P";
				$cadenaSql .= " JOIN agora.proveedor_actividad_ciiu A ON A.num_documento = P.num_documento";
				$cadenaSql .= " WHERE  A.id_subclase IN (" . $variable ['actividadEconomica'] . ")";
				//$cadenaSql .= " AND P.puntaje_evaluacion > 45";
				$cadenaSql .= " AND P.estado = '1'";
				//$cadenaSql .= " ORDER BY puntaje_evaluacion DESC";
				//$cadenaSql .= " LIMIT " . $variable ['numCotizaciones'];
				break;
				
			case "proveedoresByClasificacionConv" :
				$cadenaSql = "SELECT DISTINCT";
				$cadenaSql .= " id_proveedor,";
				$cadenaSql .= " P.num_documento,";
				$cadenaSql .= "	nom_proveedor,";
				$cadenaSql .= "	puntaje_evaluacion,";
				$cadenaSql .= "	tipopersona,";
				$cadenaSql .= "	correo,";
				$cadenaSql .= "	clasificacion_evaluacion";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.informacion_proveedor P";
				$cadenaSql .= " JOIN agora.proveedor_actividad_ciiu A ON A.num_documento = P.num_documento";
				$cadenaSql .= " JOIN agora.informacion_persona_natural PN ON PN.num_documento_persona = P.num_documento";
				$cadenaSql .= " WHERE  A.id_subclase IN (" . $variable ['actividadEconomica'] . ")";
				$cadenaSql .= " AND PN.id_nucleo_basico = " . $variable ['objetoNBC'];
				$cadenaSql .= " AND P.estado = '1'";
				//$cadenaSql .= " AND P.tipopersona = 'NATURAL'";
				//$cadenaSql .= " ORDER BY puntaje_evaluacion DESC";
				//$cadenaSql .= " LIMIT " . $variable ['numCotizaciones'];
				break;
				
				
			/* ULTIMO NUMERO DE SECUENCIA */
			case "lastIdObjeto" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " last_value";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.prov_objeto_contratar_id_objeto_seq";
				break;
			
				/* CONSULTAR - OBJETO A CONTRATAR - ESPECIFICO */
			case "objetoContratar" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " titulo_cotizacion,";
				$cadenaSql .= " numero_solicitud,";
				$cadenaSql .= " vigencia,";
				$cadenaSql .= " unidad_ejecutora,";
				$cadenaSql .= " id_solicitante,";
				$cadenaSql .= "	fecha_registro,";
				$cadenaSql .= "	fecha_solicitud_cotizacion,";
				$cadenaSql .= "	tipo_necesidad,";
				$cadenaSql .= "	esp.dependencia,";
				$cadenaSql .= " objetivo,";
				$cadenaSql .= " requisitos,";
				$cadenaSql .= " observaciones,";
				$cadenaSql .= " responsable,";
				$cadenaSql .= " fecha_apertura,";
				$cadenaSql .= " fecha_cierre,";
				$cadenaSql .= "	estado";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_cotizacion O";
				$cadenaSql .= " JOIN agora.parametro_dependencia esp ON esp.id_dependencia = O.dependencia";
				$cadenaSql .= " WHERE  id_objeto=" . $variable; // Activo
				break;
			
			/* LISTA - OBJETO A CONTRATAR */
			case "listaObjetoContratar" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_objeto,";
				$cadenaSql .= " numero_solicitud,";
				$cadenaSql .= " vigencia,";
				$cadenaSql .= " unidad_ejecutora,";
				$cadenaSql .= "	cantidad,";
				$cadenaSql .= "	fechasolicitudcotizacion,";
				$cadenaSql .= "	fecharegistro,";
				$cadenaSql .= "	numero_cotizaciones,";
				$cadenaSql .= "	estado";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_cotizacion O";
				$cadenaSql .= " WHERE  estado= '" . $variable ."'"; // Activo
				$cadenaSql .= " ORDER BY fechaRegistro";
				break;
			
			/* CIIU */
			case "ciiuDivision" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_division,";
				$cadenaSql .= "	nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.ciiu_division";
				$cadenaSql .= " ORDER BY nombre";
				break;
			
			case "ciiuGrupo" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_clase,";
				$cadenaSql .= "	nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.ciiu_clase";
				$cadenaSql .= " WHERE division ='" . $variable . "'";
				$cadenaSql .= " ORDER BY nombre";
				break;
			
			case "ciiuClase" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_subclase,";
				$cadenaSql .= "	nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.ciiu_subclase";
				$cadenaSql .= " WHERE clase ='" . $variable . "'";
				$cadenaSql .= " ORDER BY nombre";
				break;
			/* LISTA - ORDENAR DEL GASTO */
			case "ordenador" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_ordenador,";
				$cadenaSql .= "	nombre_ordenador";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.ordenador_gasto";
				$cadenaSql .= " order by nombre_ordenador";
				break;
			/* LISTA - DEPENDENCIA */
			case "dependencia" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_dependencia,";
				$cadenaSql .= "	dependencia";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.parametro_dependencia";
				$cadenaSql .= " ORDER BY dependencia";
				break;
			
				/* LISTA - DEPENDENCIA */
			case "buscarDependencia" :
				$cadenaSql = "SELECT";
				$cadenaSql .= "	dependencia";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.parametro_dependencia";
				$cadenaSql .= " WHERE id_dependencia = ".$variable;
				break;
				
			/* LISTA - UNIDAD */
			case "unidad" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_unidad,";
				$cadenaSql .= "	(tipo || '-' || unidad) AS unidad";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.unidad";
				$cadenaSql .= " ORDER BY tipo";
				break;
				
				
				
			case "ciiuSubClase" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_subclase AS id_subclase,";
				$cadenaSql .= "	id_subclase||' - ('||nombre||')' AS  nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.ciiu_subclase";
				$cadenaSql .= " ORDER BY id_subclase";
				break;
				
			case "ciiuSubClaseByNum" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " id_subclase AS id_subclase,";
				$cadenaSql .= "	id_subclase||' - ('||nombre||')' AS  nombre";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " core.ciiu_subclase";
				$cadenaSql .= " WHERE id_subclase = '".$variable."'";
				break;
				
				
			case "infoCotizacion" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " * ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_cotizacion";
				$cadenaSql .= " WHERE id_objeto = " . $variable;
				break;
				
			case "infoCotizacionCast" :
				$cadenaSql = "SELECT";
				$cadenaSql .= " oc.titulo_cotizacion, ";
				$cadenaSql .= " oc.id_solicitante, ";
				$cadenaSql .= " oc.fecha_apertura, ";
				$cadenaSql .= " oc.fecha_cierre, ";
				$cadenaSql .= " esp.dependencia ";
				$cadenaSql .= " FROM ";
				$cadenaSql .= " agora.objeto_cotizacion oc";
				$cadenaSql .= " JOIN agora.parametro_dependencia esp ON esp.id_dependencia = oc.dependencia";
				$cadenaSql .= " WHERE id_objeto = " . $variable;
				break;
				
				/* REGISTRAR DATOS DEL OBJETO A CONTRATAR */
			case "registrar" :
				$hoy = date ( "Y-m-d" );
				$cadenaSql = " INSERT INTO agora.objeto_cotizacion";
				$cadenaSql .= " (";
				$cadenaSql .= " titulo_cotizacion,";
				$cadenaSql .= " dependencia,";
				$cadenaSql .= " fecha_apertura,";
				$cadenaSql .= " fecha_cierre,";
				$cadenaSql .= " objetivo,";
				$cadenaSql .= " requisitos,";
				$cadenaSql .= " observaciones,";
				$cadenaSql .= " vigencia,";
				$cadenaSql .= " unidad_ejecutora,";
				$cadenaSql .= " estado,";
				$cadenaSql .= " tipo_necesidad,";
				$cadenaSql .= " fecha_registro,";
				$cadenaSql .= " responsable";
				$cadenaSql .= " )";
				$cadenaSql .= " VALUES";
				$cadenaSql .= " (";
				$cadenaSql .= " '" . $variable ['titulo_cotizacion'] . "',";
				$cadenaSql .= " " . $variable ['dependencia'] . ",";
				$cadenaSql .= " '" . $variable ['fecha_apertura'] . "',";
				$cadenaSql .= " '" . $variable ['fecha_cierre'] . "',";
				$cadenaSql .= " '" . $variable ['objetivo'] . "',";
				$cadenaSql .= " '" . $variable ['requisitos'] . "',";
				$cadenaSql .= " '" . $variable ['observaciones'] . "',";
				$cadenaSql .= " " . $variable ['vigencia'] . ",";
				$cadenaSql .= " " . $variable ['unidad_ejecutora'] . ",";
				$cadenaSql .= " 'RELACIONADO',";
				$cadenaSql .= " '" . $variable ['tipo_necesidad'] . "',";
				$cadenaSql .= " '" . $hoy . "',";
				$cadenaSql .= " '" . $variable ['usuario'] . "'";
				$cadenaSql .= " )";
				$cadenaSql .= " RETURNING  id_objeto; ";
				break;
				
				
				/* ACTUALIZAR DATOS DEL OBJETO A CONTRATAR */
			case "actualizar" :
				$cadenaSql = " UPDATE agora.objeto_cotizacion SET";
				$cadenaSql .= " titulo_cotizacion = "." '" . $variable ['titulo_cotizacion'] . "',";
				$cadenaSql .= " dependencia = "." " . $variable ['dependencia'] . ",";;
				$cadenaSql .= " fecha_apertura = "." '" . $variable ['fecha_apertura'] . "',";
				$cadenaSql .= " fecha_cierre = "." '" . $variable ['fecha_cierre'] . "',";
				$cadenaSql .= " objetivo = "." '" . $variable ['objetivo'] . "',";
				$cadenaSql .= " requisitos = "." '" . $variable ['requisitos'] . "',";
				$cadenaSql .= " observaciones = "." '" . $variable ['observaciones'] . "',";
				$cadenaSql .= " vigencia = "." " . $variable ['vigencia'] . ",";
				$cadenaSql .= " unidad_ejecutora = "." " . $variable ['unidad_ejecutora'] . ",";
				$cadenaSql .= " id_solicitante = "." " . $variable ['solicitante'] . ",";
				$cadenaSql .= " estado = 'RELACIONADO',";
				$cadenaSql .= " tipo_necesidad = '" . $variable ['tipo_necesidad'] . "',";
				$cadenaSql .= " responsable = "." '" . $variable ['usuario'] . "'";
				$cadenaSql .= " WHERE id_objeto = " . $variable ['idObjeto'];
				break;
				
				
			case 'actualizarAnexo' :
				$cadenaSql = "UPDATE agora.objeto_cotizacion SET ";
				$cadenaSql .= "anexo_cotizacion ='" . $variable ['destino'] . "'";
				$cadenaSql .= " WHERE id_objeto = " . $variable ['idObjeto'];
				break;

		
			/**
			 * Clausulas genéricas.
			 * se espera que estén en todos los formularios
			 * que utilicen esta plantilla
			 */
			case "iniciarTransaccion" :
				$cadenaSql = "START TRANSACTION";
				break;
			
			case "finalizarTransaccion" :
				$cadenaSql = "COMMIT";
				break;
			
			case "cancelarTransaccion" :
				$cadenaSql = "ROLLBACK";
				break;
			
			case "eliminarTemp" :
				
				$cadenaSql = "DELETE ";
				$cadenaSql .= "FROM ";
				$cadenaSql .= $prefijo . "tempFormulario ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "id_sesion = '" . $variable . "' ";
				break;
			
			case "insertarTemp" :
				$cadenaSql = "INSERT INTO ";
				$cadenaSql .= $prefijo . "tempFormulario ";
				$cadenaSql .= "( ";
				$cadenaSql .= "id_sesion, ";
				$cadenaSql .= "formulario, ";
				$cadenaSql .= "campo, ";
				$cadenaSql .= "valor, ";
				$cadenaSql .= "fecha ";
				$cadenaSql .= ") ";
				$cadenaSql .= "VALUES ";
				
				foreach ( $_REQUEST as $clave => $valor ) {
					$cadenaSql .= "( ";
					$cadenaSql .= "'" . $idSesion . "', ";
					$cadenaSql .= "'" . $variable ['formulario'] . "', ";
					$cadenaSql .= "'" . $clave . "', ";
					$cadenaSql .= "'" . $valor . "', ";
					$cadenaSql .= "'" . $variable ['fecha'] . "' ";
					$cadenaSql .= "),";
				}
				
				$cadenaSql = substr ( $cadenaSql, 0, (strlen ( $cadenaSql ) - 1) );
				break;
			
			case "rescatarTemp" :
				$cadenaSql = "SELECT ";
				$cadenaSql .= "id_sesion, ";
				$cadenaSql .= "formulario, ";
				$cadenaSql .= "campo, ";
				$cadenaSql .= "valor, ";
				$cadenaSql .= "fecha ";
				$cadenaSql .= "FROM ";
				$cadenaSql .= $prefijo . "tempFormulario ";
				$cadenaSql .= "WHERE ";
				$cadenaSql .= "id_sesion='" . $idSesion . "'";
				break;
			

								

		}
		
		return $cadenaSql;
	}
}

?>
