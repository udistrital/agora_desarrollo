<?php

/**
 * Mensaje.class.php
 *
 * Encargado de mostrar mensajes de texto cuando ocurrer errores fatales en la aplicacion
 *
 * @author  Paulo Cesar Coronado
 * @version     1.0.0.0
 * @package     config/builder
 * @copyright   Universidad Distrital Francisco Jose de Caldas - Grupo de Trabajo Academico GNU/Linux GLUD
 * @license     GPL Version 3 o posterior
 *
 */

/**
 *
 * @todo Implementar el plugin php-gettext, de tal manera que el archivo de localización esté en formato .mo
 *       Los archivos correspondientes se encuuentran en la carpeta plugin/php-gettext
 */
class Mensaje {
    
    private static $instance;
    
    /**
     *
     * @deprecated Arreglo que contiene las variables de configuración
     * @var String
     */
    private $miConfigurador;
    
    function __construct() {
        
        $this->miConfigurador = Configurador::singleton ();
    
    }
    
    public static function singleton() {
        
        if (! isset ( self::$instance )) {
            $className = __CLASS__;
            self::$instance = new $className ();
        }
        return self::$instance;
    
    }
    
    function mostrarMensaje($mensaje, $tipoMensaje = "warning") {
        
        if ($mensaje != '' && $tipoMensaje != '') {
            include_once ('Mensaje.page.php');
        }
    
    }
    
    function mostrarMensajeRedireccion($mensaje, $tipoMensaje = "warning", $url) {
        
        if ($mensaje != '' && $tipoMensaje != '' && $url != '') {
            include_once ('Mensaje.page.php');
        }
    
    }

}
?>