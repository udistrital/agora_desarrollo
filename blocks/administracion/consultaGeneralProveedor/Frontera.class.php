<?php

namespace inventarios\gestionContrato;

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
					
                case "consultarProveedor":
                    include_once($this->ruta . "/formulario/consultarProveedor.php");
                    break;
					
                 case "inhabilidad":
                     include_once($this->ruta . "/formulario/inhabilidad.php");
                    break;
                    
				case "verPro" :
					include_once ($this->ruta . "/formulario/ver.php");
					break;
				
				case "modificarPro" :
					include_once ($this->ruta . "/formulario/modificar.php");
					break;
                
            }
        } else {
            $_REQUEST['opcion'] = "mostrar";
            include_once($this->ruta . "/formulario/seleccionar.php");
        }
    }

}

?>