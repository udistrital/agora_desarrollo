<?php
namespace asignacionPuntajes\salariales\experienciaDireccionAcademica\formulario;

if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class FormularioRegistro {
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miSql;
	
	function __construct($lenguaje, $formulario, $sql) {
		
		$this->miConfigurador = \Configurador::singleton ();
		
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		
		$this->miFormulario = $formulario;
		
		$this->miSql = $sql;		
	}
	
	function cambiafecha_format($fecha) {
		ereg("([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
		$fechana = $mifecha[3] . "/" . $mifecha[2] . "/" . $mifecha[1];
		return $fechana;
	}
	
	function formulario() {
		
		/**
		 * IMPORTANTE: Este formulario está utilizando jquery.
		 * Por tanto en el archivo ready.php se delaran algunas funciones js
		 * que lo complementan.
		 */
		
		//*************************************************************************** DBMS *******************************
		//****************************************************************************************************************
		
		$conexion = 'estructura';
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		//$conexion = 'sicapital';
		//$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		//$conexion = 'centralUD';
		//$centralUDRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'argo_contratos';
		$argoRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'core_central';
		$coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		$conexion = 'framework';
		$frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		
		//*************************************************************************** DBMS *******************************
		//****************************************************************************************************************
		
		// Rescatar los datos de este bloque
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		$miPaginaActual = $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
			
		$directorio = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/index.php?";
		$directorio .= $this->miConfigurador->getVariableConfiguracion ( "enlace" );
			
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "host" );
		$rutaBloque .= $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/";
		$rutaBloque .= $esteBloque ['grupo'] . '/' . $esteBloque ['nombre'];
		
		// ---------------- SECCION: Parámetros Globales del Formulario ----------------------------------
		/**
		 * Atributos que deben ser aplicados a todos los controles de este formulario.
		 * Se utiliza un arreglo
		 * independiente debido a que los atributos individuales se reinician cada vez que se declara un campo.
		 *
		 * Si se utiliza esta técnica es necesario realizar un mezcla entre este arreglo y el específico en cada control:
		 * $atributos= array_merge($atributos,$atributosGlobales);
		 */
		$atributosGlobales ['campoSeguro'] = 'true';
		$_REQUEST ['tiempo'] = time ();
		
		// -------------------------------------------------------------------------------------------------
		// ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
		$esteCampo = $esteBloque ['nombre']."Registrar";
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		
		// Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
		$atributos ['tipoFormulario'] = 'multipart/form-data';
		
		// Si no se coloca, entonces toma el valor predeterminado 'POST'
		$atributos ['metodo'] = 'POST';
		
		// Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
		$atributos ['action'] = 'index.php';
		$atributos ['titulo'] = '';
		
		// Si no se coloca, entonces toma el valor predeterminado.
		$atributos ['estilo'] = '';
		$atributos ['marco'] = false;
		$tab = 1;
			
			// ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
			
		// ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
		$atributos ['tipoEtiqueta'] = 'inicio';
		// Aplica atributos globales al control
		echo $this->miFormulario->formulario ( $atributos );
		
		
		
		
		
		$datosSolicitudNecesidad = array (
				'idObjeto' => $_REQUEST['idSolicitud']
		);
		
		//*********************************************************************************************************************************
		$cadena_sql = $this->miSql->getCadenaSql ( "estadoSolicitudAgora", $datosSolicitudNecesidad);
		$resultado = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
		
		if(isset($resultado)) {
				
			if(isset($resultado[0]['estado_cotizacion'])){//CAST
				switch($resultado[0]['estado_cotizacion']){
					case 1 :
						$estadoSolicitud = 'RELACIONADO';
						break;
					case 2 :
						$estadoSolicitud = 'COTIZACION';
						break;
					case 3 :
						$estadoSolicitud = 'ASIGNADO';
						break;
					case 4 :
						$estadoSolicitud = 'CANCELADO';
						break;
					case 5 :
						$estadoSolicitud = 'PROCESADO';
						break;
					case 6 :
						$estadoSolicitud = 'RECOTIZACION';
						break;
				}
			}
				
				
			$cadena_sql = $this->miSql->getCadenaSql ( "informacionSolicitudAgoraNoCast", $datosSolicitudNecesidad);
			$resultadoNecesidadRelacionada = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
				
		
			$cadena_sql = $this->miSql->getCadenaSql ( "informacionCIIURelacionada", $datosSolicitudNecesidad);
			$resultadoNecesidadRelacionadaCIIU = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
		}
		//***************SOLICITUDES RELACIONADAS******************************************************************************************
		
		
		
		if(isset($resultadoNecesidadRelacionada[0]['titulo_cotizacion'])) $_REQUEST['tituloCotizacion'] = $resultadoNecesidadRelacionada[0]['titulo_cotizacion'];
		if(isset($resultadoNecesidadRelacionada[0]['vigencia'])) $_REQUEST['vigencia'] = $resultadoNecesidadRelacionada[0]['vigencia'];
		
		if(isset($resultadoNecesidadRelacionada[0]['responsable'])) $_REQUEST['responsable'] = $resultadoNecesidadRelacionada[0]['responsable'];
		
		if(isset($resultadoNecesidadRelacionada[0]['unidad_ejecutora'])) $_REQUEST['unidadEjecutora'] = $resultadoNecesidadRelacionada[0]['unidad_ejecutora'];
		if(isset($resultadoNecesidadRelacionada[0]['dependencia'])) $_REQUEST['dependencia'] = $resultadoNecesidadRelacionada[0]['dependencia'];
		if(isset($resultadoNecesidadRelacionada[0]['id_solicitante'])) $_REQUEST['solicitante'] = $resultadoNecesidadRelacionada[0]['id_solicitante'];
		
		if(isset($resultadoNecesidadRelacionada[0]['fecha_apertura'])) $_REQUEST['fechaApertura'] = $this->cambiafecha_format($resultadoNecesidadRelacionada[0]['fecha_apertura']);
		if(isset($resultadoNecesidadRelacionada[0]['fecha_cierre'])) $_REQUEST['fechaCierre'] = $this->cambiafecha_format($resultadoNecesidadRelacionada[0]['fecha_cierre']);
		
		if(isset($resultadoNecesidadRelacionada[0]['objetivo'])) $_REQUEST['objetivo'] = $resultadoNecesidadRelacionada[0]['objetivo'];
		if(isset($resultadoNecesidadRelacionada[0]['requisitos'])) $_REQUEST['requisitos'] = $resultadoNecesidadRelacionada[0]['requisitos'];
		if(isset($resultadoNecesidadRelacionada[0]['observaciones'])) $_REQUEST['observaciones'] = $resultadoNecesidadRelacionada[0]['observaciones'];
		if(isset($resultadoNecesidadRelacionada[0]['estado_cotizacion'])) $_REQUEST['estado'] = $resultadoNecesidadRelacionada[0]['estado_cotizacion'];
		
		if(isset($resultadoNecesidadRelacionada[0]['anexo_cotizacion'])) $_REQUEST['cotizacionSoporte'] = $resultadoNecesidadRelacionada[0]['anexo_cotizacion'];
		
		if(isset($resultadoNecesidadRelacionada[0]['plan_accion'])){
			$cadena_sql = $this->miSql->getCadenaSql ( "buscarPlanAccionId", $resultadoNecesidadRelacionada[0]['plan_accion']);
			$resultadoPlan = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
			$_REQUEST['planAccion'] = $resultadoPlan [0]['descripcion'];
		}
		
		
		$cadena_sql = $this->miSql->getCadenaSql ( "buscarDetalleFormaPago", $datosSolicitudNecesidad);
		$resultadoFP = $esteRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
		
		// ----------------INICIO CONTROL: Campo ESTADO RELACION-------------------------------------------------------
		$esteCampo = 'estadoSolicitudRelacionada';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'hidden';
		
		if (isset ( $estadoSolicitud )) {
			$atributos ['valor'] = $estadoSolicitud;
		} else {
			$atributos ['valor'] = '';
		}
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto --------------------------------------------------------
		
		
		
		
		echo "<div id='marcoDatosLoad' style='width: 100%;height: 900px'>
			<div style='width: 100%;height: 100px'>
			</div>
			<center><img src='" . $rutaBloque . "/images/loading.gif'".' width=20% height=20% vspace=15 hspace=3 >
			</center>
		  </div>';
		
		
		
		$esteCampo = "marcoDatosSolicitudCot";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo )  . " - Solicitud Número # (".$resultadoNecesidadRelacionada[0]['numero_solicitud'].")";
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		
		//------------------Division para los botones-------------------------
		$atributos["id"]="botones";
		$atributos["estilo"]="marcoBotones widget";
		echo $this->miFormulario->division("inicio",$atributos);
		
		//******************************************************************************************************************************
		$variable = "pagina=" . $miPaginaActual;
		$variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variable, $directorio );
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = 'botonRegresar';
		$atributos ['id'] = $esteCampo;
		$atributos ['enlace'] = $variable;
		$atributos ['tabIndex'] = 1;
		$atributos ['estilo'] = 'textoSubtitulo';
		$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['ancho'] = '10%';
		$atributos ['alto'] = '10%';
		$atributos ['redirLugar'] = true;
		echo $this->miFormulario->enlace ( $atributos );
		
		unset ( $atributos );
		//********************************************************************************************************************************
		
		//------------------Fin Division para los botones-------------------------
		echo $this->miFormulario->division("fin");
		
		
		
		// ----------------INICIO CONTROL: Campo de Texto NUM SOLICITUD--------------------------------------------------------
		$esteCampo = 'tituloCotizacion';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui mayuscula';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 1;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'required';
		
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['valor'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['valor'] = '';
		}
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = false;
		$atributos ['tamanno'] = 80;
		$atributos ['maximoTamanno'] = '80';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto NUM SOLICITUD--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
		$esteCampo = 'vigencia';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 1;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = '';
		
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['valor'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['valor'] = '';
		}
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 10;
		$atributos ['maximoTamanno'] = '10';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
		
		
		
		
		
		$esteCampo = "marcoDatosSolicitudCotRes";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		$cadenaSql = $this->miSql->getCadenaSql ( 'buscarUsuario', $_REQUEST['usuario'] );
		$resultadoUsuario = $frameworkRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		
		
		// ----------------INICIO CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
		$esteCampo = 'nombresResponsable';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 2;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'required';
		
		$atributos ['valor'] = $resultadoUsuario[0]['nombre'];
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 50;
		$atributos ['maximoTamanno'] = '10';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
		$esteCampo = 'apellidosResponsable';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 2;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'required';
		
		$atributos ['valor'] = $resultadoUsuario[0]['apellido'];
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 50;
		$atributos ['maximoTamanno'] = '10';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
		$esteCampo = 'identificacionResponsable';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 2;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'required';
		
		$atributos ['valor'] = $resultadoUsuario[0]['identificacion'];
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 50;
		$atributos ['maximoTamanno'] = '10';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto FUNCIONARIO--------------------------------------------------------
		
		
		// ---------------- CONTROL: Lista Vigencia--------------------------------------------------------
		$esteCampo = "unidadEjecutora";
		$atributos ['nombre'] = $esteCampo;
		$atributos ['id'] = $esteCampo;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['tab'] = $tab ++;
		$atributos ['anchoEtiqueta'] = 200;
		$atributos ['evento'] = '';
		if (isset ( $resultadoNecesidadRelacionada[0]['unidad_ejecutora'] )) {
			$atributos ['seleccion'] = $resultadoNecesidadRelacionada[0]['unidad_ejecutora'];
		} else {
			$atributos ['seleccion'] = - 1;
		}
		$atributos ['deshabilitado'] = false;
		$atributos ['columnas'] = 2;
		$atributos ['tamanno'] = 1;
		$atributos ['ajax_function'] = "";
		$atributos ['ajax_control'] = $esteCampo;
		$atributos ['estilo'] = "jqueryui";
		$atributos ['validar'] = "required";
		$atributos ['limitar'] = false;
		$atributos ['anchoCaja'] = 60;
		$atributos ['miEvento'] = '';
		
		$matrizItems = array (
				array ( 1, '1 - RECTORÍA' ),
				array ( 2, '2 - IDEXUD' )
		);
		
		$atributos ['matrizItems'] = $matrizItems;
		
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroLista ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Lista Vigencia--------------------------------------------------------
		
		
		// ---------------- CONTROL: Lista clase CIIU--------------------------------------------------------
		$esteCampo = "ordenador";
		$atributos ['nombre'] = $esteCampo;
		$atributos ['id'] = $esteCampo;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['tab'] = $tab ++;
		$atributos ['anchoEtiqueta'] = 200;
		$atributos ['evento'] = '';
		if (isset ( $resultadoNecesidadRelacionada[0]['ordenador_gasto'] )) {
			$atributos ['seleccion'] = $resultadoNecesidadRelacionada[0]['ordenador_gasto'];
		} else {
			$atributos ['seleccion'] = - 1;
		}
		$atributos ['deshabilitado'] = false;
		$atributos ['columnas'] = 1;
		$atributos ['tamanno'] = 1;
		$atributos ['ajax_function'] = "";
		$atributos ['ajax_control'] = $esteCampo;
		$atributos ['estilo'] = "jqueryui";
		$atributos ['validar'] = "required";
		$atributos ['limitar'] = false;
		$atributos ['anchoCaja'] = 60;
		$atributos ['miEvento'] = '';
		$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'ordenadorUdistrital' );
		$matrizItems = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$atributos ['matrizItems'] = $matrizItems;
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroLista ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Lista clase CIIU--------------------------------------------------------
		
		// ---------------- CONTROL: Lista clase CIIU--------------------------------------------------------
		$esteCampo = "dependencia";
		$atributos ['nombre'] = $esteCampo;
		$atributos ['id'] = $esteCampo;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['tab'] = $tab ++;
		$atributos ['anchoEtiqueta'] = 200;
		$atributos ['evento'] = '';
		if (isset ( $resultadoNecesidadRelacionada[0]['jefe_dependencia'] )) {
			$atributos ['seleccion'] = $resultadoNecesidadRelacionada[0]['jefe_dependencia'];
		} else {
			$atributos ['seleccion'] = - 1;
		}
		$atributos ['deshabilitado'] = false;
		$atributos ['columnas'] = 1;
		$atributos ['tamanno'] = 1;
		$atributos ['ajax_function'] = "";
		$atributos ['ajax_control'] = $esteCampo;
		$atributos ['estilo'] = "jqueryui";
		$atributos ['validar'] = "required";
		$atributos ['limitar'] = false;
		$atributos ['anchoCaja'] = 60;
		$atributos ['miEvento'] = '';
		$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'dependenciaUdistrital' );
		$matrizItems = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$atributos ['matrizItems'] = $matrizItems;
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroLista ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Lista clase CIIU--------------------------------------------------------
		
		
		
		
		
		
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
		
		
		
		
		$esteCampo = "marcoDatosSolicitudCotCar";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		
		// ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
		$esteCampo = 'fechaApertura';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 2;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'required,custom[date]';
		
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['valor'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['valor'] = '';
		}
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 15;
		$atributos ['maximoTamanno'] = '30';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
		
		// ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
		$esteCampo = 'fechaCierre';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 2;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'required,custom[date]';
		
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['valor'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['valor'] = '';
		}
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = 15;
		$atributos ['maximoTamanno'] = '30';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
		
		
		
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = 'objetivo';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 120;
		$atributos ['filas'] = 8;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'required,minSize[20]';
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = false;
		$atributos ['tamanno'] = 20;
		$atributos ['maximoTamanno'] = '';
		$atributos ['anchoEtiqueta'] = 220;
		$atributos ['textoEnriquecido'] = true;
		
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['valor'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['valor'] = '';
		}
		
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoTextArea ( $atributos );
		unset ( $atributos );
		
		
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = 'requisitos';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 120;
		$atributos ['filas'] = 8;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'required,minSize[20]';
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = false;
		$atributos ['tamanno'] = 20;
		$atributos ['maximoTamanno'] = '';
		$atributos ['anchoEtiqueta'] = 220;
		
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['valor'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['valor'] = '';
		}
		
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoTextArea ( $atributos );
		unset ( $atributos );
		
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = 'observaciones';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 120;
		$atributos ['filas'] = 8;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = '';
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = false;
		$atributos ['tamanno'] = 20;
		$atributos ['maximoTamanno'] = '';
		$atributos ['anchoEtiqueta'] = 220;
		
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['valor'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['valor'] = '';
		}
		
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoTextArea ( $atributos );
		unset ( $atributos );
		
		
		
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
		
		$esteCampo = "marcoPlan";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
		echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
		// ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = 'planAccion';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 120;
		$atributos ['filas'] = 8;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'required,minSize[20]';
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = false;
		$atributos ['tamanno'] = 20;
		$atributos ['maximoTamanno'] = '';
		$atributos ['anchoEtiqueta'] = 220;
		
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['valor'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['valor'] = '';
		}
		
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoTextArea ( $atributos );
		unset ( $atributos );
		
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
		
		
		
		$esteCampo = "marcoPago";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
		echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
		// ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
		
		
		
		// ---------------- CONTROL: Lista clase CIIU--------------------------------------------------------
		$esteCampo = "medioPago";
		$atributos ['nombre'] = $esteCampo;
		$atributos ['id'] = $esteCampo;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['tab'] = $tab ++;
		$atributos ['anchoEtiqueta'] = 200;
		$atributos ['evento'] = '';
		if (isset ( $resultadoNecesidadRelacionada[0]['tipo_necesidad'] )) {
			$atributos ['seleccion'] = $resultadoNecesidadRelacionada[0]['medio_pago'];
		} else {
			$atributos ['seleccion'] = - 1;
		}
		$atributos ['deshabilitado'] = false;
		$atributos ['columnas'] = 1;
		$atributos ['tamanno'] = 1;
		$atributos ['ajax_function'] = "";
		$atributos ['ajax_control'] = $esteCampo;
		$atributos ['estilo'] = "jqueryui";
		$atributos ['validar'] = "required";
		$atributos ['limitar'] = false;
		$atributos ['anchoCaja'] = 60;
		$atributos ['miEvento'] = '';
		$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'medioPagoUdistrital' );
		$matrizItems = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$atributos ['matrizItems'] = $matrizItems;
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroLista ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Lista clase CIIU--------------------------------------------------------
		
		$esteCampo = "marcoParametrosFormaPago";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		// ---------------- CONTROL: Tipo Forma Pago--------------------------------------------------------
		$esteCampo = "tipoFormaPago";
		$atributos ['nombre'] = $esteCampo;
		$atributos ['id'] = $esteCampo;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ["etiquetaObligatorio"] = false;
		$atributos ['tab'] = $tab ++;
		$atributos ['anchoEtiqueta'] = 200;
		$atributos ['evento'] = '';
		if (isset ( $_REQUEST [$esteCampo] )) {
			$atributos ['seleccion'] = $_REQUEST [$esteCampo];
		} else {
			$atributos ['seleccion'] = - 1;
		}
		$atributos ['deshabilitado'] = false;
		$atributos ['columnas'] = 1;
		$atributos ['tamanno'] = 1;
		$atributos ['ajax_function'] = "";
		$atributos ['ajax_control'] = $esteCampo;
		$atributos ['estilo'] = "jqueryui";
		$atributos ['validar'] = "";
		$atributos ['limitar'] = false;
		$atributos ['anchoCaja'] = 60;
		$atributos ['miEvento'] = '';
		$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'tipoFormaPagoUdistrital' );
		$matrizItems = $coreRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
		$atributos ['matrizItems'] = $matrizItems;
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroLista ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Tipo Forma Pago--------------------------------------------------------
		
		
		// ----------------INICIO CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
		$esteCampo = 'valorFormaPago';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 2;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'custom[number]';
		
		$atributos ['valor'] = '';
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = false;
		$atributos ['tamanno'] = 20;
		$atributos ['maximoTamanno'] = '20';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
		
		
		// ----------------INICIO CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
		$esteCampo = 'porcentajePagoForma';
		$atributos ['id'] = $esteCampo;
		$atributos ['nombre'] = $esteCampo;
		$atributos ['tipo'] = 'text';
		$atributos ['estilo'] = 'jqueryui';
		$atributos ['marco'] = true;
		$atributos ['estiloMarco'] = '';
		$atributos ["etiquetaObligatorio"] = true;
		$atributos ['columnas'] = 2;
		$atributos ['dobleLinea'] = 0;
		$atributos ['tabIndex'] = $tab;
		$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['validar'] = 'custom[number]';
		
		$atributos ['valor'] = '';
		
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
		$atributos ['deshabilitado'] = false;
		$atributos ['tamanno'] = 20;
		$atributos ['maximoTamanno'] = '20';
		$atributos ['anchoEtiqueta'] = 200;
		$tab ++;
		
		// Aplica atributos globales al control
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		// ----------------FIN CONTROL: Campo de Texto VIGENCIA--------------------------------------------------------
		
		
		
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
			
		
		//------------------Division para los botones-------------------------
		$atributos["id"]="botones";
		$atributos["estilo"]="marcoBotones widget";
		echo $this->miFormulario->division("inicio",$atributos);
		
		//******************************************************************************************************************************
		
		// ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
		$esteCampo = 'botonAgregar';
		$atributos ['id'] = $esteCampo;
		$atributos ['enlace'] = '';
		$atributos ['tabIndex'] = 1;
		$atributos ['estilo'] = 'textoSubtitulo';
		$atributos ['enlaceTexto'] = $this->lenguaje->getCadena ( $esteCampo );
		$atributos ['ancho'] = '10%';
		$atributos ['alto'] = '10%';
		$atributos ['redirLugar'] = false;
		echo $this->miFormulario->enlace ( $atributos );
		
		unset ( $atributos );
		//********************************************************************************************************************************
		
		//------------------Fin Division para los botones-------------------------
		echo $this->miFormulario->division("fin");
		
		
		$esteCampo = "marcoFormaPago";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
			
		
		unset ( $atributos );
		$esteCampo = 'countParam';
		$atributos ["id"] = $esteCampo; // No cambiar este nombre
		$atributos ["tipo"] = "text";
		$atributos ['estilo'] = 'jquery';
		$atributos ['columnas'] = 2;
		$atributos ["obligatorio"] = false;
		$atributos ['marco'] = false;
		$atributos ["etiqueta"] = "";
		
		$countLimit = count($resultadoFP);
		
		$textLimit = '( ' . $countLimit . ' ) Parámetro(s) Agregado(s)' . ' - ( Configurado el 100% )';
		
		$atributos ['valor'] = $textLimit;
		
		$atributos ['validar'] = 'required';
		$atributos ['deshabilitado'] = true;
		$atributos ['tamanno'] = '70%';
		$atributos = array_merge ( $atributos, $atributosGlobales );
		echo $this->miFormulario->campoCuadroTexto ( $atributos );
		unset ( $atributos );
		
		?>
				<p>Configurado el 100% de la Forma Pago</p>
					<!-- Slide THREE -->
					  <div class="slideThree">  
					      <input type="checkbox" value="None" id="slideThree" name="check" class="validate[required]" readonly="readonly" checked="true" />
					      <label for="slideThree"></label>
					  </div>
				
				<?php
				
				
				?>
						
									
									<table id="tablaFP" class="table1" width="100%" >
										<!-- Cabecera de la tabla -->
										<thead>
											<tr>
												<th width="20%" >Tipo</th>
												<th width="30%" >Condición de Avance</th>
												<th width="35%" >Porcentaje (%) de Pago</th>
												<th width="15%" >&nbsp;</th>
											</tr>
										</thead>
									 
										<!-- Cuerpo de la tabla con los campos -->
										<tbody>
									 
											<!-- fila base para clonar y agregar al final -->
											<!-- fin de código: fila base -->
									 		
		
									 		
									 		<?php 
									 		
									 		if (isset ( $resultadoFP ) && $resultadoFP) {
									 		$count = count($resultadoFP);
									 		$i = 0;
									 		
										 		while ($i < $count){
										 			
										 			if($resultadoFP[$i][0] == 1){
										 				$tipoValor = " días Transcurridos del Inicio";
										 			}else{
										 				$tipoValor = " % Completado del Total";
										 			}
										 			
										 		?>
												<tr id="nFilas" >
													<td><?php echo $resultadoFP[$i][0] . " - " . $resultadoFP[$i][1] ?></td>
									 				<td><?php echo round($resultadoFP[$i][2],2) . $tipoValor ?></td>
									 				<td><?php echo round($resultadoFP[$i][3],2) . " %" ?></td>
									 				<th class="eliminarFP" scope="row"><div class = "widget">Eliminar</div></th>
									 			</tr>
												<?php
												$i++;
										 		}
									 		}
											
											?>
									 
										</tbody>
									</table>
									<!-- Botón para agregar filas -->
									<!-- 
									<input type="button" id="agregar" value="Agregar fila" /> -->
									
						
						
									
									
									<?php
									
									
									
				
									
									echo $this->miFormulario->marcoAgrupacion ( 'fin' );
						
									
									unset ( $atributos );
									$esteCampo = 'idsFormaPago';
									$atributos ["id"] = $esteCampo; // No cambiar este nombre
									$atributos ["tipo"] = "hidden";
									$atributos ['estilo'] = '';
									$atributos ["obligatorio"] = false;
									$atributos ['marco'] = false;
									$atributos ["etiqueta"] = "";
									if (isset ( $_REQUEST [$esteCampo] )) {
										$atributos ['valor'] = $_REQUEST [$esteCampo];
									} else {
										$atributos ['valor'] = '';
									}
									$atributos ['validar'] = '';
									$atributos = array_merge ( $atributos, $atributosGlobales );
									echo $this->miFormulario->campoCuadroTexto ( $atributos );
									unset ( $atributos );
									
									unset ( $atributos );
									$esteCampo = 'changeFormaPago';
									$atributos ["id"] = $esteCampo; // No cambiar este nombre
									$atributos ["tipo"] = "hidden";
									$atributos ['estilo'] = '';
									$atributos ["obligatorio"] = false;
									$atributos ['marco'] = false;
									$atributos ["etiqueta"] = "";
									$atributos ['valor'] = 'false';
									$atributos ['validar'] = '';
									$atributos = array_merge ( $atributos, $atributosGlobales );
									echo $this->miFormulario->campoCuadroTexto ( $atributos );
									unset ( $atributos );
									
						echo $this->miFormulario->marcoAgrupacion ( 'fin' );
						
				
				
				
				
				
				
				
				
				
				
				
				
				//**************************************** RELACION DATOS AGORA **********************************************************************************************
				//************************************************************************************************************************************************************
				
				
				
				
				
				if(isset($resultadoNecesidadRelacionada[0]['tipo_necesidad'])){//CAST tipo de NECESIDAD
					switch($resultadoNecesidadRelacionada[0]['tipo_necesidad']){
						case 1 :
							$resultadoNecesidadRelacionada[0]['tipo_necesidad'] = 1;
							$service = false;
							break;
						case 2 :
							$resultadoNecesidadRelacionada[0]['tipo_necesidad'] = 2;
							$service = true;
							break;
						case 3 :
							$resultadoNecesidadRelacionada [0] ['tipo_necesidad'] = 3;
							$service = true;
							break;
					}
				}
				
				
				
				$cadenaSql = $this->miSql->getCadenaSql ( 'consultarActividadesImp', $_REQUEST['idSolicitud']  );
				$resultadoActividades = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				$resultadoActividades = false;
				$service = false;
				if( $resultadoActividades ){
				
					$esteCampo = "marcoActividadesRel";
					$atributos ['id'] = $esteCampo;
					$atributos ["estilo"] = "jqueryui";
					$atributos ['tipoEtiqueta'] = 'inicio';
					$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
					echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				
					foreach ($resultadoActividades as $dato):
					echo "<span class='textoElegante textoEnorme textoAzul'>+ </span><b>";
					echo $dato['subclase'] . ' - ' . $dato['nombre'] . "</b><br>";
					endforeach;
				
					echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				}
		
				if($service){
					 
					$cadenaSql = $this->miSql->getCadenaSql ( 'consultarNBCImp', $_REQUEST['idSolicitud']  );
					$resultadoNBC = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
					 
					$esteCampo = "marcoNBCRel";
					$atributos ['id'] = $esteCampo;
					$atributos ["estilo"] = "jqueryui";
					$atributos ['tipoEtiqueta'] = 'inicio';
					$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
					echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
					 
					 
					echo "<span class='textoElegante textoEnorme textoAzul'>+ </span><b>";
					echo $resultadoNBC[0]['nucleo'] . ' - ' . $resultadoNBC[0]['nombre'] . "</b><br>";
					 
					 
					echo $this->miFormulario->marcoAgrupacion ( 'fin' );
					 
				}
				
				
				
				
				
				
				
				
				$esteCampo = "marcoAnexo";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
				echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
				
				
				//INICIO enlace boton descargar Soporte
				//------------------Division para los botones-------------------------
				$atributos["id"]="botones";
				$atributos["estilo"]="marcoBotones widget";
				echo $this->miFormulario->division("inicio",$atributos);
					
				if($_REQUEST['cotizacionSoporte'] != null){
					$enlace = "<br><a href='".$_REQUEST['cotizacionSoporte']."' target='_blank'>";
					$enlace.="<img src='".$rutaBloque."/images/pdf.png' width='35px'><br>Archivo Detalle Cotización";
					$enlace.="</a>";
					echo $enlace;
				}else{
					$enlace = "<br><a href='#' onClick=\"
				swal({
					  title: 'Atención',
					  type: 'info',
					  html:
					    'No se adjunto ningun documento de Soporte para Detalle de Cotización.',
					  confirmButtonText:
					    'Ok'
					})
				\">";
					$enlace.="<img src='".$rutaBloque."/images/pdf.png' width='35px'><br>Archivo Detalle Cotización";
					$enlace.="</a>";
					echo $enlace;
				}
				//------------------Fin Division para los botones-------------------------
				echo $this->miFormulario->division("fin");
				//FIN enlace boton descargar Soporte
				
				
				// ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
				$esteCampo = "cotizacionSoporte";
				$atributos ["id"] = $esteCampo; // No cambiar este nombre
				$atributos ["nombre"] = $esteCampo;
				$atributos ["tipo"] = "file";
				// $atributos ["obligatorio"] = true;
				$atributos ["etiquetaObligatorio"] = false;
				$atributos ["tabIndex"] = $tab ++;
				$atributos ["columnas"] = 1;
				$atributos ["estilo"] = "textoIzquierda";
				$atributos ["anchoEtiqueta"] = 400;
				$atributos ["tamanno"] = 500000;
				$atributos ["validar"] = "";
				
				$atributos ["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
				// $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				// $atributos ["valor"] = $valorCodificado;
				$atributos = array_merge($atributos, $atributosGlobales);
				echo $this->miFormulario->campoCuadroTexto($atributos);
				unset($atributos);
				// ----------------FIN CONTROL: DOCUMENTO--------------------------------------------------------
				
				echo $this->miFormulario->marcoAgrupacion('fin');
				
				
				
				
				
				$esteCampo = "marcoTipoNecesidad";
				$atributos ['id'] = $esteCampo;
				$atributos ["estilo"] = "jqueryui";
				$atributos ['tipoEtiqueta'] = 'inicio';
				$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
				echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
				
				
				
				// ---------------- CONTROL: Lista UNIDAD --------------------------------------------------------
				$esteCampo = "tipoNecesidad";
				$atributos ['nombre'] = $esteCampo;
				$atributos ['id'] = $esteCampo;
				$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
				$atributos ["etiquetaObligatorio"] = true;
				$atributos ['tab'] = $tab ++;
				$atributos ['anchoEtiqueta'] = 200;
				$atributos ['evento'] = '';
				
				
				
				if (isset ( $estadoSolicitud )) {
					$atributos ['seleccion'] = $resultadoNecesidadRelacionada[0]['tipo_necesidad'];
				} else {
					$atributos ['seleccion'] = - 1;
				}
				$atributos ['deshabilitado'] = true;
				$atributos ['columnas'] = 1;
				$atributos ['tamanno'] = 1;
				$atributos ['estilo'] = "jqueryui";
				$atributos ['validar'] = "required";
				$atributos ['limitar'] = false;
				$atributos ['anchoCaja'] = 60;
				$atributos ['miEvento'] = '';
				
				$atributos ['cadena_sql'] = $cadenaSql = $this->miSql->getCadenaSql ( 'tipoNecesidadAdministrativa' );
				$matrizItems = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, "busqueda" );
				
				$atributos ['matrizItems'] = $matrizItems;
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoCuadroLista ( $atributos );
				unset ( $atributos );
				// ----------------FIN CONTROL: Lista UNIDAD--------------------------------------------------------
				
				
				echo $this->miFormulario->marcoAgrupacion ( 'fin' );
				
				
			
		
		
		// ------------------Division para los botones-------------------------
		$atributos ["id"] = "botones";
		$atributos ["estilo"] = "marcoBotones";
		echo $this->miFormulario->division ( "inicio", $atributos );
		{
			// -----------------CONTROL: Botón ----------------------------------------------------------------
			
			
			if (isset ( $estadoSolicitud )) {
				$esteCampo = 'botonModificar';
			} else {
				$esteCampo = 'botonRegistrar';
			}
			
			
			$atributos ["id"] = $esteCampo;
			$atributos ["tabIndex"] = $tab;
			$atributos ["tipo"] = 'boton';
			// submit: no se coloca si se desea un tipo button genérico
			$atributos ['submit'] = 'true';
			$atributos ["estiloMarco"] = '';
			$atributos ["estiloBoton"] = 'jqueryui';
			// verificar: true para verificar el formulario antes de pasarlo al servidor.
			$atributos ["verificar"] = '';
			$atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
			$atributos ["valor"] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ['nombreFormulario'] = $esteBloque ['nombre'] . "Registrar";
			$tab ++;
			
			// Aplica atributos globales al control
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoBoton ( $atributos );
			
			// -----------------FIN CONTROL: Botón -----------------------------------------------------------
		}
		// ------------------Fin Division para los botones-------------------------
		echo $this->miFormulario->division ( "fin" );
				
				
				// ------------------- SECCION: Paso de variables ------------------------------------------------
				
				/**
				 * En algunas ocasiones es útil pasar variables entre las diferentes páginas.
				 * SARA permite realizar esto a través de tres
				 * mecanismos:
				 * (a). Registrando las variables como variables de sesión. Estarán disponibles durante toda la sesión de usuario. Requiere acceso a
				 * la base de datos.
				 * (b). Incluirlas de manera codificada como campos de los formularios. Para ello se utiliza un campo especial denominado
				 * formsara, cuyo valor será una cadena codificada que contiene las variables.
				 * (c) a través de campos ocultos en los formularios. (deprecated)
				 */
				// En este formulario se utiliza el mecanismo (b) para pasar las siguientes variables:
				// Paso 1: crear el listado de variables
				
				$valorCodificado  = "action=" . $esteBloque ["nombre"];
				$valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
				$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
				$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
				$valorCodificado .= "&opcion=registrar";
				$valorCodificado .= "&usuario=" . $_REQUEST['usuario'];
				$valorCodificado .= "&idObjeto=" . $_REQUEST['idSolicitud'];
				$valorCodificado .= "&tipoNecesidad=" . $resultadoNecesidadRelacionada[0]['tipo_necesidad'];
				
				/**
				 * SARA permite que los nombres de los campos sean dinámicos.
				 * Para ello utiliza la hora en que es creado el formulario para
				 * codificar el nombre de cada campo.
				 */
				$valorCodificado .= "&campoSeguro=" . $_REQUEST ['tiempo'];
				$valorCodificado .= "&tiempo=" . time();
				/*
				 * Sara permite validar los campos en el formulario o funcion destino.
				 * Para ello se envía los datos atributos["validadar"] de los componentes del formulario
				 * Estos se pueden obtener en el atributo $this->miFormulario->validadorCampos del formulario
				 * La función $this->miFormulario->codificarCampos() codifica automáticamente el atributo validadorCampos
				 */
				$valorCodificado .= "&validadorCampos=" . $this->miFormulario->codificarCampos();
				
				// Paso 2: codificar la cadena resultante
				$valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar ( $valorCodificado );
				
				$atributos ["id"] = "formSaraData"; // No cambiar este nombre
				$atributos ["tipo"] = "hidden";
				$atributos ['estilo'] = '';
				$atributos ["obligatorio"] = false;
				$atributos ['marco'] = true;
				$atributos ["etiqueta"] = "";
				$atributos ["valor"] = $valorCodificado;
				echo $this->miFormulario->campoCuadroTexto ( $atributos );
				unset ( $atributos );
				
				$atributos ['marco'] = false;
				$atributos ['tipoEtiqueta'] = 'fin';
				echo $this->miFormulario->formulario ( $atributos );
								
				// ----------------FIN SECCION: Paso de variables -------------------------------------------------
				// ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
			// ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
			// Se debe declarar el mismo atributo de marco con que se inició el formulario.
		
	}
	function mensaje() {
		
		// Si existe algun tipo de error en el login aparece el siguiente mensaje
		$mensaje = $this->miConfigurador->getVariableConfiguracion ( 'mostrarMensaje' );
		$this->miConfigurador->setVariableConfiguracion ( 'mostrarMensaje', null );
		
		if ($mensaje) {
			
			$tipoMensaje = $this->miConfigurador->getVariableConfiguracion ( 'tipoMensaje' );
			
			if ($tipoMensaje == 'json') {
				
				$atributos ['mensaje'] = $mensaje;
				$atributos ['json'] = true;
			} else {
				$atributos ['mensaje'] = $this->lenguaje->getCadena ( $mensaje );
			}
			// -------------Control texto-----------------------
			$esteCampo = 'divMensaje';
			$atributos ['id'] = $esteCampo;
			$atributos ["tamanno"] = '';
			$atributos ["estilo"] = 'information';
			$atributos ["etiqueta"] = '';
			$atributos ["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
			echo $this->miFormulario->campoMensaje ( $atributos );
			unset ( $atributos );
		}
		
		return true;
	}
}


$miFormulario = new FormularioRegistro ( $this->lenguaje, $this->miFormulario, $this->sql  );

$miFormulario->formulario ();
$miFormulario->mensaje ();
?>
