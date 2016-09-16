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
	
	function formulario() {
		
		/**
		 * IMPORTANTE: Este formulario está utilizando jquery.
		 * Por tanto en el archivo ready.php se delaran algunas funciones js
		 * que lo complementan.
		 */
		
		$conexion = "estructura";
		$esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$conexion = "sicapital";
		$siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		$conexion = "argo_contratos";
		$argoRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB ( $conexion );
		
		
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
		$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo );
		
		// Si no se coloca, entonces toma el valor predeterminado.
		$atributos ['estilo'] = '';
		$atributos ['marco'] = true;
		$tab = 1;
			
			// ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
			
		// ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
		$atributos ['tipoEtiqueta'] = 'inicio';
		// Aplica atributos globales al control
		echo $this->miFormulario->formulario ( $atributos );
		
		$esteCampo = "marcoDatos";
		$atributos ['id'] = $esteCampo;
		$atributos ["estilo"] = "jqueryui";
		$atributos ['tipoEtiqueta'] = 'inicio';
		$atributos ["leyenda"] = $this->lenguaje->getCadena ( $esteCampo );
		echo $this->miFormulario->marcoAgrupacion ( 'inicio', $atributos );
		
		
		
		if(isset($_REQUEST['vigenciaNecesidad'])){
		
			$valorVigencia = $_REQUEST['vigenciaNecesidad'];
			
			$this->cadena_sql = $this->miSql->getCadenaSql ( "listarContratosRelacionadosXVigencia", $valorVigencia );
			$resultado = $esteRecursoDB->ejecutarAcceso ( $this->cadena_sql, "busqueda" );
			
			if(isset($resultado[0][0])){
				$datos = array (//Datos Relacionados ya en el sistema AGORA
						'contratos' => $resultado[0][0],
						'vigencia' => $valorVigencia
				);
			}else{
				$datos = array (//No existen Datos Relacionados ya en el sistema AGORA
						'contratos' => "-1",
						'vigencia' => $valorVigencia
				);
			}
		
			$cadena_sql = $this->miSql->getCadenaSql ( "listaContratoXVigencia", $datos);
			$resultado = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
			
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
			
			$onlyCheck = false;
		}else{
			
			
			// ---------------- CONTROL: Lista Vigencia--------------------------------------------------------
			$esteCampo = "vigenciaNecesidad";
			$atributos ['nombre'] = $esteCampo;
			$atributos ['id'] = $esteCampo;
			$atributos ['etiqueta'] = $this->lenguaje->getCadena ( $esteCampo );
			$atributos ["etiquetaObligatorio"] = true;
			$atributos ['tab'] = $tab ++;
			$atributos ['anchoEtiqueta'] = 200;
			$atributos ['evento'] = '';
			if (isset ( $estadoSolicitud )) {
				$atributos ['seleccion'] = $resultadoNecesidadRelacionadaCIIU[0]['num_division'];
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
			$atributos ['cadena_sql'] = $this->miSql->getCadenaSql ( 'filtroVigencia' );
			$matrizItems = $siCapitalRecursoDB->ejecutarAcceso ( $atributos ['cadena_sql'], "busqueda" );
			$atributos ['matrizItems'] = $matrizItems;
			$atributos = array_merge ( $atributos, $atributosGlobales );
			echo $this->miFormulario->campoCuadroLista ( $atributos );
			unset ( $atributos );
			// ----------------FIN CONTROL: Lista Vigencia--------------------------------------------------------
			
			
			$resultado = false;
			$onlyCheck = true;
		}
		
		
		
		
		//echo $cadena_sql;// SI CAPITAL <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
		//var_dump($resultado);
		
		
		
		if ($resultado) {
			
			
			echo '<table id="tablaObjetos" class="display" cellspacing="0" width="100%"> ';
			
			echo "<thead>
				<tr>
					<th><center>Número Contrato</center></th>
					<th><center>Vigencia</center></th>
					<th><center>Solicitud de Necesidad</center></th>
					<th><center>Número CDP</center></th>
					<th><center>Plazo Ejecución</center></th>
					<th><center>Contratista</center></th>
					<th><center>Tipo Contratista</center></th>
                    <th><center>Ordenador del Gasto</center></th>
					<th><center>Supervisor</center></th>
					<th><center>Fecha Registro</center></th>
					<th><center>Estado</center></th>
					<th><center>Necesidad</center></th>
					<th><center>Contrato</center></th>
					<th><center>Relacionar</center></th>
				</tr>
				</thead>
				<tbody>";
			
			foreach ($resultado as $dato):
			
			$datosCon = array (//Datos
					'num_contrato' => $dato['numero_contrato'],
					'vigencia' => $dato['vigencia']
			);
			$cadena_sql = $this->miSql->getCadenaSql ( "consultarEstadoContrato", $datosCon);
			$estadoCont = $argoRecursoDB->ejecutarAcceso ( $cadena_sql, "busqueda" );
			
			
			$variableView = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variableView .= "&opcion=verSolicitud";
			$variableView .= "&idSolicitud=" . $dato['numero_solicitud_necesidad'];
			$variableView .= "&vigencia=" . $dato['vigencia'];
			$variableView = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableView, $directorio );
			$imagenView = 'verPro.png';
			
			
			$variableViewCon = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variableViewCon .= "&opcion=verSolicitud";
			$variableViewCon .= "&idSolicitud=" . $dato['numero_contrato'];
			$variableViewCon .= "&vigencia=" . $dato['vigencia'];
			$variableViewCon = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableViewCon, $directorio );
			$imagenViewCon = 'cotPro.png';
				
				
			$variableEdit = "pagina=" . $miPaginaActual; // pendiente la pagina para modificar parametro
			$variableEdit .= "&opcion=modificarSolicitud";
			$variableEdit .= "&idSolicitud=" . $dato['numero_contrato'];
			$variableEdit .= "&vigencia=" . $dato['vigencia'];
			$variableEdit = $this->miConfigurador->fabricaConexiones->crypto->codificar_url ( $variableEdit, $directorio );
			if(strtoupper ( $estadoCont[0]['estado'] ) == "FINALIZADO"){
				$imagenEdit = 'addPro.png';
			}else{
				$variableEdit = "#";
				$imagenEdit = 'cancel.png';
			}
			
				
			
			
			if($dato['identificacion_sociedad_temporal'] != null){
				$dato['identificacion_contratista'] = $dato['identificacion_sociedad_temporal'];
				$tipoSoc = "CONSORCIO o UNION TEMPORAL";
			}else{
				$tipoSoc = "INDIVIDUAL";
			}
				
			$mostrarHtml = "<tr>
						<td><center>" . $dato['numero_contrato'] . "</center></td>
						<td><center>" . $dato['vigencia'] . "</center></td>
						<td><center>" . $dato['numero_solicitud_necesidad'] . "</center></td>
						<td><center>" . $dato['numero_cdp'] . "</center></td>
						<td><center>" . $dato['plazo_ejecucion'] . "</center></td>
					    <td><center>" . $dato['identificacion_contratista'] . "</center></td>
						<td><center>" . $tipoSoc . "</center></td>
						<td><center>" . $dato['nombre_ordenador_gasto'] . "</center></td>
						<td><center>" . $dato['identificacion_supervisor'] . "</center></td>
						<td><center>" . $dato['fecha_registro'] . "</center></td>
						<td><center>" . strtoupper ( $estadoCont[0]['estado'] ) . "</center></td>
						<td><center>
							<a href='" . $variableView . "'>
								<img src='" . $rutaBloque . "/images/" . $imagenView . "' width='15px'>
							</a>
						</center></td>
					    <td><center>
							<a href='" . $variableViewCon . "'>
								<img src='" . $rutaBloque . "/images/" . $imagenViewCon . "' width='15px'>
							</a>
						</center></td>
						<td><center>
							<a href='" . $variableEdit . "'>
								<img src='" . $rutaBloque . "/images/" . $imagenEdit . "' width='15px'>
							</a>
						</center></td>
					</tr>";
			echo $mostrarHtml;
			unset ( $mostrarHtml );
			unset ( $variableView );
			unset ( $variableEdit );
			endforeach;
			
			echo "</tbody>";
			echo "</table>";
			
		
		} else if(isset($_REQUEST['vigenciaNecesidad'])){
			// ------------------INICIO Division para los botones-------------------------
			$atributos ["id"] = "divNoEncontroEgresado";
			$atributos ["estilo"] = "marcoBotones";
			echo $this->miFormulario->division ( "inicio", $atributos );
			// -------------SECCION: Controles del Formulario-----------------------
			$esteCampo = "mensajeObjeto";
			$atributos ["id"] = $esteCampo; // Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
			$atributos ["etiqueta"] = "";
			$atributos ["estilo"] = "centrar";
			$atributos ["tipo"] = 'error';
			$atributos ["mensaje"] = "Actualmente no hay Contratos Disponibles con Vigencia <b>".$valorVigencia."</b> para Relacionar en AGORA </br> Los Contratos son Gestionados mediante el Sistema <b>ARGO</b>. <br>";
				
			echo $this->miFormulario->cuadroMensaje ( $atributos );
			unset ( $atributos );
			// -------------FIN Control Formulario----------------------
			// ------------------FIN Division para los botones-------------------------
			echo $this->miFormulario->division ( "fin" );
			unset ( $atributos );
		}
		
		
		
		
		echo $this->miFormulario->marcoAgrupacion ( 'fin' );
		
		
		// ------------------Division para los botones-------------------------
		$atributos ["id"] = "botones";
		$atributos ["estilo"] = "marcoBotones";
		echo $this->miFormulario->division ( "inicio", $atributos );
		{
			// -----------------CONTROL: Botón ----------------------------------------------------------------
			$esteCampo = 'botonContinuar';
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
			if($onlyCheck){
				echo $this->miFormulario->campoBoton ( $atributos );
			}
			
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
				
				//$valorCodificado  = "action=" . $esteBloque ["nombre"];
				$valorCodificado = "&pagina=" . $this->miConfigurador->getVariableConfiguracion ( 'pagina' );
				$valorCodificado .= "&bloque=" . $esteBloque ['nombre'];
				$valorCodificado .= "&bloqueGrupo=" . $esteBloque ["grupo"];
				$valorCodificado .= "&opcion=nuevoRelacionar";
				
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
				
				$atributos ['marco'] = true;
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
