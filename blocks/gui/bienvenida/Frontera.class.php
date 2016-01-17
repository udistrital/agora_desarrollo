<?php
include_once("core/manager/Configurador.class.php");

class Fronterabienvenida{

	var $ruta;
	var $sql;
	var $funcion;
	var $lenguaje;
	var $formulario;

	var $miConfigurador;

	function __construct()
	{

		$this->miConfigurador=Configurador::singleton();
	}

	public function setRuta($unaRuta){
		$this->ruta=$unaRuta;
	}

	public function setLenguaje($lenguaje){
		$this->lenguaje=$lenguaje;
	}

	public function setFormulario($formulario){
		$this->formulario=$formulario;
	}

	function frontera()
	{
		$this->html();
	}

	function setSql($a)
	{
		$this->sql=$a;

	}

	function setFuncion($funcion)
	{
		$this->funcion=$funcion;

	}

	function html()
	{

		include_once("core/builder/FormularioHtml.class.php");

		$this->ruta=$this->miConfigurador->getVariableConfiguracion("rutaBloque");


		$this->miFormulario=new formularioHtml();

		$_REQUEST['opcion']="mostrar";
		include_once($this->ruta."/formulario/panelCentral.php");

	}





}
?>
