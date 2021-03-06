<?php

if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
} else {

    $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

    $rutaBloque = $this->miConfigurador->getVariableConfiguracion("host");
    $rutaBloque.=$this->miConfigurador->getVariableConfiguracion("site") . "/blocks/";
    $rutaBloque.= $esteBloque['grupo'] . "/" . $esteBloque['nombre'];

    $directorio = $this->miConfigurador->getVariableConfiguracion("host");
    $directorio.= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
    $directorio.=$this->miConfigurador->getVariableConfiguracion("enlace");
    $miSesion = Sesion::singleton();

    $nombreFormulario = $esteBloque["nombre"];

    include_once("core/crypto/Encriptador.class.php");
    $cripto = Encriptador::singleton();

    $directorio = $this->miConfigurador->getVariableConfiguracion("rutaUrlBloque") . "/imagen/";

    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion("pagina");


    //*************************************************************************** DBMS *******************************
    //****************************************************************************************************************

    $conexion = 'estructura';
    $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

    $conexion = 'sicapital';
    $siCapitalRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

    $conexion = 'centralUD';
    $centralUDRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

    $conexion = 'argo_contratos';
    $argoRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

    $conexion = 'core_central';
    $coreRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

    $conexion = 'framework';
    $frameworkRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

    //*************************************************************************** DBMS *******************************
    //****************************************************************************************************************


    $tab = 1;
//---------------Inicio Formulario (<form>)--------------------------------
    $atributos["id"] = $nombreFormulario;
    $atributos["tipoFormulario"] = "multipart/form-data";
    $atributos["metodo"] = "POST";
    $atributos["nombreFormulario"] = $nombreFormulario;
    $atributos["tipoEtiqueta"] = 'inicio';
    $verificarFormulario = "1";
    echo $this->miFormulario->formulario($atributos);



    $miPaginaActual = $this->miConfigurador->getVariableConfiguracion('pagina');

    $directorio = $this->miConfigurador->getVariableConfiguracion("host");
    $directorio .= $this->miConfigurador->getVariableConfiguracion("site") . "/index.php?";
    $directorio .= $this->miConfigurador->getVariableConfiguracion("enlace");

    $variable = "pagina=" . $miPaginaActual;
    //$variable .= "&usuario=".$_REQUEST['usuario'];
    $variable = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable, $directorio);

    // ---------------- CONTROL: Cuadro de Texto --------------------------------------------------------    	
    unset($atributos);

    $atributos["id"] = "divNoEncontroEgresado";
    $atributos["estilo"] = "marcoBotones";

    //$atributos["estiloEnLinea"]="display:none"; 
    echo $this->miFormulario->division("inicio", $atributos);


    if ($_REQUEST['mensaje'] == 'actualizoRespuesta') {
    
    
    
    	$tipo = 'success';
    	$mensaje = "Se <b>Actualizo</b> la respuesta a la solicitud <b>".$_REQUEST['numero_solicitud']."</b> con vigencia ".$_REQUEST['vigencia']."<br >";
    	$mensaje .= "La fecha de cierre de esta cotización es el <b>".$_REQUEST['fecha_cierre']."</b> podra recibir notificaciones de respuesta del ordenador hasta la misma, <br>así como notificación luego de esta indicando la decision del Ordenador.<br >
        		<br><br>
        		<b>IMPORTANTE:</b> Le recordamos nuevamente tener presente los descuentos que realiza la Universidad los cuales se calculan antes de IVA, así:<br><b>
        		- Estampilla UD (1%)<br>
				- Adulto Mayor (2%)<br>
				- Pro Cultura (0.5%)</b>
        		";
    	$mensaje .= "<strong><br >";
    	$boton = "continuar";
    
    	$valorCodificado = "pagina=" . $miPaginaActual;
    	$valorCodificado.="&opcion=mostrar";
    	$valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
    	$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    }
    if ($_REQUEST['mensaje'] == 'insertoRespuesta') {
        
        
                        
        $tipo = 'success';
        $mensaje = "Se <b>Registro</b> la respuesta a la solicitud <b>".$_REQUEST['numero_solicitud']."</b> con vigencia ".$_REQUEST['vigencia']."<br >";
        $mensaje .= "La fecha de cierre de esta cotización es el <b>".$_REQUEST['fecha_cierre']."</b> podra recibir notificaciones de respuesta del ordenador hasta la misma, <br>así como notificación luego de esta indicando la decision del Ordenador.<br >
        		<br><br>
        		<b>IMPORTANTE:</b> Le recordamos nuevamente tener presente los descuentos que realiza la Universidad los cuales se calculan antes de IVA, así:<br><b>
        		- Estampilla UD (1%)<br>
				- Adulto Mayor (2%)<br>
				- Pro Cultura (0.5%)</b>
        		";
        $mensaje .= "<strong><br >";
        $boton = "continuar";

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=mostrar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    }
    
    if ($_REQUEST['mensaje'] == 'insertoObs') {
    	
    	$cadenaSql = $this->sql->getCadenaSql('listarObjetosParaMensajeJoin', $_REQUEST['objeto']);
    	$datosSolicitud= $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda", $_REQUEST['objeto'], 'listarObjetosParaMensajeJoin');
    	
    	$timeStamp = date( "m/d/Y h:i A", strtotime($_REQUEST['fecha']));
    	
    	$tipo = 'success';
    	$mensaje = "Se registro la <b>Observación</b> a la solicitud <b>".$datosSolicitud[0]['numero_solicitud']."</b> con vigencia <b>".$datosSolicitud[0]['vigencia']."</b><br >";
    	$mensaje .= "La fecha de registro es (".$timeStamp.").<br><br> Podra ver en el anterior módulo cuando, su observación sea vista por el solicitante.<br >";
    	$mensaje .= "<strong><br >";
    	$boton = "continuar";
    
    	$valorCodificado = "pagina=" . $miPaginaActual;
    	$valorCodificado.="&opcion=mostrar";
    	$valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
    	$valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    }
    
     if ($_REQUEST['mensaje'] == 'NoinsertoRespuesta') {
        $tipo = 'error';
        $mensaje = "No fue posible insertar respuesta. Inténte nuevamente por favor.<br >";
        $mensaje .= "<br><br>Puede comunicarse con el Administrador del Sistema y reportar el caso <br> (" . $_REQUEST['caso'] . ")";
        $mensaje .= "<strong><br >";
        $boton = "continuar";

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=mostrar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    }


    
    
    if ($_REQUEST['mensaje'] == 'confirma') {


        $tipo = 'success';
        $mensaje = "Se registro la PERSONA EXTRANJERA, puede OPRIMIR CONTINUAR y terminar el proceso.<br >";
        $mensaje .= "<strong><br >";
        $boton = "continuar";

        if (isset($_REQUEST['correo'])) {
            $_REQUEST['correo'] = str_replace('\\', "", $_REQUEST['correo']);
        }


        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=mostrar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    } else if ($_REQUEST['mensaje'] == 'mensajeExisteProveedor') {
        $tipo = 'error';
        $mensaje = "Error en el cargue. <br>El número de Documento " . $_REQUEST['nit'] . " ya se encuentra Registrado.";
        $boton = "regresar";

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=mostrar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    } else if ($_REQUEST['mensaje'] == 'mensajeExisteProveedorLegal') {
        $tipo = 'error';
        $mensaje = "Error en el cargue. <br>El número de Documento del Representante Legal " . $_REQUEST['nit'] . " ya se encuentra Registrado.";
        $boton = "regresar";

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=mostrar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    } else if ($_REQUEST['mensaje'] == 'mensajeExisteActividad') {
        $tipo = 'error';
        $mensaje = "Error en el cargue. <br>La Actividad ya se encuentra Registrada.";
        $boton = "regresar";

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=actividad";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        $valorCodificado.="&nit=" . $_REQUEST['nit'];
    } else if ($_REQUEST['mensaje'] == 'error') {
        $tipo = 'error';
        $mensaje = "Error al Cargar los Datos. <br>La Informaciòn no se diligenció Correctamente, Por Favor Vuelva a Intertarlo";
        $boton = "regresar";

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=mostrar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    } else if ($_REQUEST['mensaje'] == 'errorUsuario') {
        $tipo = 'warning';
        $mensaje = "Se Registro Información Personal. <br>Existe un PROBLEMA (No se Registro USUARIO en el Sistema AGORA.), Por Favor Comuniquese con el 
    		Administrador del Sistema para solicitar un Usuario. No debe volver a Registrarse";
        $boton = "regresar";

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=mostrar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    } else if ($_REQUEST['mensaje'] == 'registroActividad') {
        $tipo = 'success';
        $mensaje = "Se registro la Actividad Económica<br >";
        $mensaje .= "<strong>" . $_REQUEST['actividad'] . "</strong><br >";
        $boton = "regresar";

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=actividad";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
        $valorCodificado.="&nit=" . $_REQUEST['nit'];
    } else if ($_REQUEST['mensaje'] == 'actualizo') {
        $tipo = 'success';
        $mensaje = "Se guardaron los datos de la PERSONA EXTRANJERA.<br >";
        $boton = "regresar";

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=modificar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    } else if ($_REQUEST['mensaje'] == 'noActualizo') {
        $tipo = 'error';
        $mensaje = "Error en el cargue. <br>No se Actualizaron los Datos.";
        $boton = "regresar";

        $valorCodificado = "pagina=" . $miPaginaActual;
        $valorCodificado.="&opcion=modificar";
        $valorCodificado.="&bloque=" . $esteBloque["id_bloque"];
        $valorCodificado.="&bloqueGrupo=" . $esteBloque["grupo"];
    }


    /**
     * IMPORTANTE: Este formulario está utilizando jquery.
     * Por tanto en el archivo ready.php se delaran algunas funciones js
     * que lo complementan.
     */
    // Rescatar los datos de este bloque
    $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

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
    $_REQUEST['tiempo'] = time();

    // -------------------------------------------------------------------------------------------------
    // ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
    $esteCampo = $esteBloque ['nombre'];
    $atributos ['id'] = $esteCampo;
    $atributos ['nombre'] = $esteCampo;

    // Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
    $atributos ['tipoFormulario'] = 'multipart/form-data';

    // Si no se coloca, entonces toma el valor predeterminado 'POST'
    $atributos ['metodo'] = 'POST';

    // Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
    $atributos ['action'] = 'index.php';
    $atributos ['titulo'] = $this->lenguaje->getCadena($esteCampo);

    // Si no se coloca, entonces toma el valor predeterminado.
    $atributos ['estilo'] = '';
    $atributos ['marco'] = false;
    $tab = 1;
    // ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------
    // ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
    $atributos['tipoEtiqueta'] = 'inicio';
    echo $this->miFormulario->formulario($atributos);

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
    echo $this->miFormulario->division("inicio", $atributos);

    // -----------------CONTROL: Botón ----------------------------------------------------------------
    $esteCampo = $boton;
    $atributos ["id"] = $esteCampo;
    $atributos ["tabIndex"] = $tab;
    $atributos ["tipo"] = 'boton';
    // submit: no se coloca si se desea un tipo button genérico
    $atributos ['submit'] = true;
    $atributos ["estiloMarco"] = '';
    $atributos ["estiloBoton"] = 'jqueryui';
    // verificar: true para verificar el formulario antes de pasarlo al servidor.
    $atributos ["verificar"] = '';
    $atributos ["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
    $atributos ["valor"] = $this->lenguaje->getCadena($esteCampo);
    $atributos ['nombreFormulario'] = $esteBloque ['nombre'];
    $tab ++;

    // Aplica atributos globales al control
    $atributos = array_merge($atributos, $atributosGlobales);
    echo $this->miFormulario->campoBoton($atributos);
    // -----------------FIN CONTROL: Botón -----------------------------------------------------------
    // ------------------Fin Division para los botones-------------------------
    echo $this->miFormulario->division("fin");

    // ------------------- SECCION: Paso de variables ------------------------------------------------

    /**
     * SARA permite que los nombres de los campos sean dinámicos.
     * Para ello utiliza la hora en que es creado el formulario para
     * codificar el nombre de cada campo. 
     */
    $valorCodificado .= "&campoSeguro=" . $_REQUEST['tiempo'];
    // Paso 2: codificar la cadena resultante
    $valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

    $atributos ["id"] = "formSaraData"; // No cambiar este nombre
    $atributos ["tipo"] = "hidden";
    $atributos ['estilo'] = '';
    $atributos ["obligatorio"] = false;
    $atributos ['marco'] = false;
    $atributos ["etiqueta"] = "";
    $atributos ["valor"] = $valorCodificado;
    echo $this->miFormulario->campoCuadroTexto($atributos);
    unset($atributos);

    // ----------------FIN SECCION: Paso de variables -------------------------------------------------
    // ---------------- FIN SECCION: Controles del Formulario -------------------------------------------
    // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
    // Se debe declarar el mismo atributo de marco con que se inició el formulario.
    $atributos ['marco'] = false;
    $atributos ['tipoEtiqueta'] = 'fin';
    echo $this->miFormulario->formulario($atributos);

    return true;
}
?>