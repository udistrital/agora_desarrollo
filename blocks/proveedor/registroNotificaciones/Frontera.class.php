<?php

namespace proveedor\registroProveedor;

if (!isset($GLOBALS ["autorizado"])) {
    include ("../index.php");
    exit();
}

include_once ("core/manager/Configurador.class.php");

class Frontera {

    var $ruta;
    var $sql;
    var $funcion;
    var $lenguaje;
    var $formulario;
    var $miConfigurador;

    function __construct() {

        $this->miConfigurador = \Configurador::singleton();
    }

    public function setRuta($unaRuta) {
        $this->ruta = $unaRuta;
    }

    public function setLenguaje($lenguaje) {
        $this->lenguaje = $lenguaje;
    }

    public function setFormulario($formulario) {
        $this->formulario = $formulario;
    }

    function frontera() {
        $this->html();
    }

    function setSql($a) {
        $this->sql = $a;
    }

    function setFuncion($funcion) {
        $this->funcion = $funcion;
    }

    function html() {

        include_once("core/builder/FormularioHtml.class.php");

        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");
        $this->miFormulario = new \FormularioHtml();


        if (isset($_REQUEST['opcion'])) {

            switch ($_REQUEST['opcion']) {

                case "mensaje":
                    include_once($this->ruta . "/formulario/mensaje.php");
                    break;

                case "mostrar":
                    include_once($this->ruta . "/formulario/formulario.php");
                    break;

                case "observaciones" :
                    include_once ($this->ruta . "/formulario/observaciones.php");
                    break;

                case "cotizacion":
                    include_once($this->ruta . "/formulario/formulario.php");
                    break;

                case "modificarSolicitudRelacionada":
                    
                    include_once($this->ruta . "/formulario/responderCotizacion.php");
                    break;

                case "modificarItemsSolMod":
          
                    include_once($this->ruta . "/formulario/responderCotizacionModificada.php");
                    break;

                case "verDetalleRespuesta":
                    include_once($this->ruta . "/formulario/detalleRespuesta.php");
                    break;

                case "verRespuestaOrdenador":
                    include_once($this->ruta . "/formulario/detalleRespuestaOrdenador.php");
                    break;
            }
        } else {


            if ($_REQUEST['pagina'] == 'registroProveedor') {
                $_REQUEST['opcion'] = "mostrar";
                include_once($this->ruta . "/formulario/formulario.php");
            } else {
                $_REQUEST['opcion'] = "mostrar";
                include_once($this->ruta . "/formulario/formulario.php");
                //$_REQUEST['opcion'] = "inicio";
                //include_once($this->ruta . "/formulario/inicioProveedor.php");
            }
        }
    }

}

?>
