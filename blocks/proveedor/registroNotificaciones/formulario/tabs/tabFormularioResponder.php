<?php

namespace proveedor\registroProveedor\formulario;

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

           



            // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
            $esteCampo = "marcoContratos";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
            unset($atributos);


            unset($resultado);

            //****************************************************************************************
            //****************************************************************************************

            $cadenaSql = $this->miSql->getCadenaSql('consultar_proveedor', $_REQUEST ["usuario"]);
            $resultadoDoc = $frameworkRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

            $numeroDocumento = $resultadoDoc[0]['identificacion'];

            $cadenaSql = $this->miSql->getCadenaSql('consultar_DatosProveedor', $numeroDocumento);
            $resultadoDats = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

            $idProveedor = $resultadoDats[0]['id_proveedor'];
            $tipoPersona = $resultadoDats[0]['tipopersona'];
            $nombrePersona = $resultadoDats[0]['nom_proveedor'];
            $correo = $resultadoDats[0]['correo'];
            $direccion = $resultadoDats[0]['direccion'];
            
            

            $esteCampo = "marcoInfoCont";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos); 
            {


                //INICIO INFORMACION
                echo "<span class='textoElegante textoGrande textoAzul'>Nombre de la Persona: </span>";
                echo "<span class='textoElegante textoGrande textoGris'>" . $nombrePersona . "</span></br>";
                echo "<span class='textoElegante textoGrande textoAzul'>Documento : </span>";
                echo "<span class='textoElegante textoGrande textoGris'>" . $numeroDocumento . "</span></br>";
                echo "<span class='textoElegante textoGrande textoAzul'>Tipo Persona : </span>";
                echo "<span class='textoElegante textoGrande textoGris'>" . $tipoPersona . "</span></br>";
                echo "<span class='textoElegante textoGrande textoAzul'>Dirección : </span>";
                echo "<span class='textoElegante textoGrande textoGris'>" . $direccion . "</span></br>";
                echo "<span class='textoElegante textoGrande textoAzul'>Correo : </span>";
                echo "<span class='textoElegante textoGrande textoGris'>" . $correo . "</span></br>";
                //FIN INFORMACION
            }
            echo $this->miFormulario->marcoAgrupacion('fin', $atributos);

            if (isset($_REQUEST['tipoCotizacion']) && $_REQUEST['tipoCotizacion'] == 'BIEN') {
                $campo1 = "entregables";
                $campo2 = "plazoEntrega";
            } else {
                if (isset($_REQUEST['tipoCotizacion']) && $_REQUEST['tipoCotizacion'] == 'SERVICIO') {
                    $campo1 = "desServicio";
                    $campo2 = "detalleEjecucion";
                } else {
                    $campo1 = "entregablesdesServicio";
                    $campo2 = "entregaEjecucion";
                }
            }


            $esteCampo = "marcoContratosTablaRes";
            $atributos ['id'] = $esteCampo;
            $atributos ["estilo"] = "jqueryui";
            $atributos ['tipoEtiqueta'] = 'inicio';
            $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
            echo $this->miFormulario->marcoAgrupacion('inicio', $atributos); 
            {


                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = $campo1;
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
                $atributos ['etiqueta'] = $this->lenguaje->getCadena($campo1);
                $atributos ['validar'] = 'required,minSize[4]';
                $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                $atributos ['deshabilitado'] = false;
                $atributos ['tamanno'] = 20;
                $atributos ['maximoTamanno'] = '';
                $atributos ['anchoEtiqueta'] = 220;
                $atributos ['textoEnriquecido'] = true; //Este atributo se coloca una sola vez en todo el formulario (ERROR paso de datos)

                $atributos ['valor'] = '';

                $tab ++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoTextArea($atributos);
                unset($atributos);


                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = $campo2;
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
                $atributos ['etiqueta'] = $this->lenguaje->getCadena($campo2);
                $atributos ['validar'] = 'required,minSize[4]';
                $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                $atributos ['deshabilitado'] = false;
                $atributos ['tamanno'] = 20;
                $atributos ['maximoTamanno'] = '';
                $atributos ['anchoEtiqueta'] = 220;



                $atributos ['valor'] = '';

                $tab ++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoTextArea($atributos);
                unset($atributos);


                
                                $esteCampo = 'formaPagoCot';
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
				$atributos ['validar'] = 'required,minSize[30]';
				$atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
				$atributos ['deshabilitado'] = false;
				$atributos ['tamanno'] = 20;
				$atributos ['maximoTamanno'] = '';
				$atributos ['anchoEtiqueta'] = 220;

				
				$atributos ['valor'] = '';
				
				$tab ++;
				
				// Aplica atributos globales al control
				$atributos = array_merge ( $atributos, $atributosGlobales );
				echo $this->miFormulario->campoTextArea ( $atributos );
				unset ( $atributos );



               
                // ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------
                $esteCampo = 'descuentos';
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
                $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                $atributos ['validar'] = 'required,minSize[4]';
                $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                $atributos ['deshabilitado'] = false;
                $atributos ['tamanno'] = 20;
                $atributos ['maximoTamanno'] = '';
                $atributos ['anchoEtiqueta'] = 220;


                $atributos ['valor'] = '';

                $tab ++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoTextArea($atributos);
                unset($atributos);

                // ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                $esteCampo = 'precioCot';
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
                $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                $atributos ['validar'] = 'required, minSize[1], maxSize[40],custom[onlyNumberSp]';

                $atributos ['valor'] = '';

                $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                $atributos ['deshabilitado'] = false;
                $atributos ['tamanno'] = 15;
                $atributos ['maximoTamanno'] = '30';
                $atributos ['anchoEtiqueta'] = 400;
                $tab ++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);
                // ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                // ----------------INICIO CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------
                $esteCampo = 'fechaVencimientoCot';
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
                $atributos ['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                $atributos ['validar'] = 'required,custom[date]';

                $atributos ['valor'] = date('d/m/Y');

                $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo . 'Titulo');
                $atributos ['deshabilitado'] = false;
                $atributos ['tamanno'] = 15;
                $atributos ['maximoTamanno'] = '30';
                $atributos ['anchoEtiqueta'] = 400;
                $tab ++;

                // Aplica atributos globales al control
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);
                // ----------------FIN CONTROL: Campo de Texto FECHA SOLICITUD--------------------------------------------------------


                $esteCampo = "marcoAnexo";
                $atributos ['id'] = $esteCampo;
                $atributos ["estilo"] = "jqueryui";
                $atributos ['tipoEtiqueta'] = 'inicio';
                $atributos ["leyenda"] = $this->lenguaje->getCadena($esteCampo);
                echo $this->miFormulario->marcoAgrupacion('inicio', $atributos);
                // ----------------INICIO CONTROL: DOCUMENTO--------------------------------------------------------
                $esteCampo = "cotizacionSoporte";
                $atributos ["id"] = $esteCampo; // No cambiar este nombre
                $atributos ["nombre"] = $esteCampo;
                $atributos ["tipo"] = "file";
                // $atributos ["obligatorio"] = true;
                $atributos ["etiquetaObligatorio"] = true;
                $atributos ["tabIndex"] = $tab ++;
                $atributos ["columnas"] = 1;
                $atributos ["estilo"] = "textoIzquierda";
                $atributos ["anchoEtiqueta"] = 400;
                $atributos ["tamanno"] = 500000;
                $atributos ["validar"] = "required";

                $atributos ["etiqueta"] = $this->lenguaje->getCadena($esteCampo);
                // $atributos ['titulo'] = $this->lenguaje->getCadena ( $esteCampo . 'Titulo' );
                // $atributos ["valor"] = $valorCodificado;
                $atributos = array_merge($atributos, $atributosGlobales);
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);
                // ----------------FIN CONTROL: DOCUMENTO--------------------------------------------------------

                echo $this->miFormulario->marcoAgrupacion('fin');




            $tipo = 'warning';
            $mensaje = "<b>IMPORTANTE</b><br>
							<br>
							Solo se permite responder la cotización <b>(1) una</b> sola vez, por favor verfique la información relacionada
							y guarde posteriormente, recuerde que no podra hacer cambios de la información diligenciada.
							";
            // ---------------- SECCION: Controles del Formulario -----------------------------------------------
            $esteCampo = 'mensaje';
            $atributos["id"] = $esteCampo; //Cambiar este nombre y el estilo si no se desea mostrar los mensajes animados
            $atributos["etiqueta"] = "";
            $atributos["estilo"] = "centrar";
            $atributos["tipo"] = $tipo;
            $atributos["mensaje"] = $mensaje;
            echo $this->miFormulario->cuadroMensaje($atributos);
            unset($atributos);



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
		
        }
       echo $this->miFormulario->marcoAgrupacion('fin', $atributos);

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
                $valorCodificado .= "&solicitud=".$_REQUEST['idSolicitud'];
                $valorCodificado .= "&tipoCotizacion=".$_REQUEST['tipoCotizacion'];
                $valorCodificado .= "&id_proveedor=".$idProveedor;
                 $valorCodificado .= "&numero_solicitud=" . $_REQUEST['numero_solicitud'];
                  $valorCodificado .= "&vigencia=" . $_REQUEST['vigencia'];
                   $valorCodificado .= "&titulo_cotizacion=" . $_REQUEST['titulo_cotizacion'];
                    $valorCodificado .=  "&fecha_cierre=" . $_REQUEST['fecha_cierre'];
                
                
		
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
        