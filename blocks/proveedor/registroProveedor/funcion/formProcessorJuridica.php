<?php

namespace proveedor\registroProveedor\funcion;

use proveedor\registroProveedor\funcion\redireccionar;

include_once ('redireccionar.php');
if (! isset ( $GLOBALS ["autorizado"] )) {
	include ("../index.php");
	exit ();
}
class Formulario {
	
	var $miConfigurador;
	var $lenguaje;
	var $miFormulario;
	var $miFuncion;
	var $miSql;
	var $conexion;
	var $miLogger;
	
	function __construct($lenguaje, $sql, $funcion, $miLogger) {
		
		$this->miConfigurador = \Configurador::singleton ();
		$this->miConfigurador->fabricaConexiones->setRecursoDB ( 'principal' );
		$this->lenguaje = $lenguaje;
		$this->miSql = $sql;
		$this->miFuncion = $funcion;
		$this->miLogger= $miLogger;

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
		
		
		
		$esteBloque = $this->miConfigurador->getVariableConfiguracion ( "esteBloque" );
		
		$rutaBloque = $this->miConfigurador->getVariableConfiguracion ( "raizDocumento" ) . "/blocks/proveedor/";
		$rutaBloque .= $esteBloque ['nombre'];
		$host = $this->miConfigurador->getVariableConfiguracion ( "host" ) . $this->miConfigurador->getVariableConfiguracion ( "site" ) . "/blocks/proveedor/" . $esteBloque ['nombre'];
		
		
		$SQLs = [];
		$representanteExiste = false;
		
		/*Variables Texto Enriquecido ----------------------------------------------------------*/
		/*--------------------------------------------------------------------------------------*/
		$descripcion = $_POST[$this->campoSeguroCodificar('descripcion', $_REQUEST['tiempo'])];
		$_REQUEST['descripcion'] = str_replace("'", "\"", $descripcion);
		
		
		if(isset($_REQUEST['correo'])){$_REQUEST['correo'] = str_replace('\\', "", $_REQUEST['correo']);}
		if(isset($_REQUEST['correoPer'])){$_REQUEST['correoPer'] = str_replace('\\', "", $_REQUEST['correoPer']);}
		
		
		if(isset($_REQUEST['nombreEmpresa'])){$_REQUEST['nombreEmpresa']=mb_strtoupper($_REQUEST['nombreEmpresa'],'utf-8');}
		if(isset($_REQUEST['asesorComercial'])){$_REQUEST['asesorComercial']=mb_strtoupper($_REQUEST['asesorComercial'],'utf-8');}
		if(isset($_REQUEST['primerApellido'])){$_REQUEST['primerApellido']=mb_strtoupper($_REQUEST['primerApellido'],'utf-8');}
		if(isset($_REQUEST['segundoApellido'])){$_REQUEST['segundoApellido']=mb_strtoupper($_REQUEST['segundoApellido'],'utf-8');}
		if(isset($_REQUEST['primerNombre'])){$_REQUEST['primerNombre']=mb_strtoupper($_REQUEST['primerNombre'],'utf-8');}
		if(isset($_REQUEST['segundoNombre'])){$_REQUEST['segundoNombre']=mb_strtoupper($_REQUEST['segundoNombre'],'utf-8');}
		if(isset($_REQUEST['cargo'])){$_REQUEST['cargo']=mb_strtoupper($_REQUEST['cargo'],'utf-8');}
		if(isset($_REQUEST['profesion'])){$_REQUEST['profesion']=mb_strtoupper($_REQUEST['profesion'],'utf-8');}
		if(isset($_REQUEST['especialidad'])){$_REQUEST['especialidad']=mb_strtoupper($_REQUEST['especialidad'],'utf-8');}
		if(isset($_REQUEST['descripcion'])){$_REQUEST['descripcion']=mb_strtoupper($_REQUEST['descripcion'],'utf-8');}
		

		$_REQUEST['correoPer'] = "pruebas@udistrital.edu.co";
		$_REQUEST['numeroContacto'] = 3239300;
		
		//Guardar RUT adjuntado Persona Juridica*********************************************************************
		$_REQUEST ['destino'] = '';
		// Guardar el archivo
		if ($_FILES) {			
			$i = 0;
			foreach ( $_FILES as $key => $values ) {
				$archivoCarga[$i] = $_FILES [$key];
				$i++;
			}	
			$archivo = $archivoCarga[0];
			// obtenemos los datos del archivo
			$tamano = $archivo ['size'];
			$tipo = $archivo ['type'];
			$archivo1 = $archivo ['name'];
			$prefijo = substr ( md5 ( uniqid ( rand () ) ), 0, 6 );
			$nombreDoc = $prefijo . "-" . $archivo1;
			
			if ($archivo1 != "") {
				// guardamos el archivo a la carpeta files
				$destino = $rutaBloque . "/files/" . $nombreDoc;
				
				if (copy ( $archivo ['tmp_name'], $destino )) {
					$status = "Archivo subido: <b>" . $archivo1 . "</b>";
					$_REQUEST ['destino'] = $prefijo . "-" . $archivo1;
				} else {
					$status = "<br>Error al subir el archivo1";
				}
			} else {
				$status = "<br>Error al subir archivo2";
			}
		} else {
			echo "<br>NO existe el archivo D:!!!";
		}
		//************************************************************************************************************
		
		
		//Guardar RUP adjuntado Persona Juridica*********************************************************************
		$_REQUEST ['destino2'] = '';
		// Guardar el archivo
		if ($_FILES) {
			$i = 0;
			foreach ( $_FILES as $key => $values ) {
				$archivoCarga[$i] = $_FILES [$key];
				$i++;
			}
			$archivo = $archivoCarga[1];
			// obtenemos los datos del archivo
			$tamano = $archivo ['size'];
			$tipo = $archivo ['type'];
			$archivo1 = $archivo ['name'];
			$prefijo = substr ( md5 ( uniqid ( rand () ) ), 0, 6 );
			$nombreDoc = $prefijo . "-" . $archivo1;
				
			if ($archivo1 != "") {
				// guardamos el archivo a la carpeta files
				$destino = $rutaBloque . "/files/" . $nombreDoc;
		
				if (copy ( $archivo ['tmp_name'], $destino )) {
					$status = "Archivo subido: <b>" . $archivo1 . "</b>";
					$_REQUEST ['destino2'] = $prefijo . "-" . $archivo1;
				} else {
					$status = "<br>Error al subir el archivo1";
				}
			} else {
				$status = "<br>Error al subir archivo2";
			}
		} else {
			echo "<br>NO existe el archivo D:!!!";
		}
		//************************************************************************************************************
		

		if(isset($_REQUEST['tipoPersona'])){//CAST genero tipoCuenta
			switch($_REQUEST['tipoPersona']){
				case 1 :
					$_REQUEST['tipoPersona']='NATURAL';
					break;
				case 2 :
					$_REQUEST['tipoPersona']='JURIDICA';
					break;
			}
		}
		
		$arregloUnique = array (
				'num_documento' => $_REQUEST['nit'],
				'tipo_persona' => $_REQUEST ['tipoPersona']
		);
		
		unset($resultado);
		//VERIFICAR SI EL PROVEEDOR YA SE ENCUENTRA REGISTRADO
		$cadenaSql = $this->miSql->getCadenaSql ( "verificarProveedor", $arregloUnique);
		$resultadoVerificar = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );

		if ($resultadoVerificar) {
			//El proveedor ya existe
			redireccion::redireccionar ( 'existeProveedor',  $_REQUEST ['nit']);
			exit();    
		}else{
			
			$arregloUnique = array (
					'num_documento' => $_REQUEST['numeroDocumento'],
					'tipo_persona' => 'NATURAL'
			);
			
			//VERIFICAR SI LA CEDULA YA SE ENCUENTRA REGISTRADA
			$cadenaSql = $this->miSql->getCadenaSql ( "verificarProveedor", $arregloUnique);
			$resultadoVerificar = $esteRecursoDB->ejecutarAcceso ( $cadenaSql, 'busqueda' );
			if ($resultadoVerificar) {
				
				/*
				 * 
				 * Caso especial donde el representante legal ya esta registrado en el Sistema
				 * se crea llave de relación entre la Persona Juridica y la Persona Natural
				 * 
				 * */
				
				$representanteExiste = true;
			}
				
				
				if(isset($_REQUEST['tipoCuenta'])){//CAST
					switch($_REQUEST['tipoCuenta']){
						case 1 :
							$_REQUEST['tipoCuenta']='AHORROS';
							break;
						case 2 :
							$_REQUEST['tipoCuenta']='CORRIENTE';
							break;
					}
				}
				
				if(isset($_REQUEST['genero'])){//CAST
					switch($_REQUEST['genero']){
						case 1 :
							$_REQUEST['genero']='MASCULINO';
							break;
						case 2 :
							$_REQUEST['genero']='FEMENINO';
							break;
					}
				}
				
				
				if(isset($_REQUEST['perfil'])){//CAST
					switch($_REQUEST['perfil']){
						case 1 :
							$_REQUEST ['perfil'] = 18;
							$_REQUEST ['personaNBC'] = 49207;
							break;
						case 2 :
							$_REQUEST ['perfil'] = 19;
							break;
						case 3 :
							$_REQUEST ['perfil'] = 20;
							break;
						case 4 :
							$_REQUEST ['perfil'] = 21;
							break;
						case 5 :
							$_REQUEST ['perfil'] = 22;
							$_REQUEST ['personaNBC'] = 0;
							break;
						case 6 :
							$_REQUEST ['perfil'] = 38;
							break;
						case 7 :
							$_REQUEST ['perfil'] = 39;
							break;
					}
				}
				
				$fechaActual = date ( 'Y-m-d' . ' - ' .'h:i:s A');
				
				$datosInformacionProveedorPersonaJuridica = array (
						'tipoPersona' => $_REQUEST['tipoPersona'],
						'numero_documento' => $_REQUEST['nit'],
						'nombre_proveedor' => $_REQUEST['nombreEmpresa'],
						'id_ciudad_contacto' =>	$_REQUEST['ciudad'],
						'direccion_contacto' => $_REQUEST['direccion'],
						'correo_contacto' => $_REQUEST['correo'],
						'web_contacto' => $_REQUEST['sitioWeb'],
						'nom_asesor_comercial_contacto' => $_REQUEST['asesorComercial'],
						'tel_asesor_comercial_contacto' => $_REQUEST['telAsesor'],
						'tipo_cuenta_bancaria' => $_REQUEST['tipoCuenta'],
						'num_cuenta_bancaria' => $_REQUEST['numeroCuenta'],
						'id_entidad_bancaria' => $_REQUEST['entidadBancaria'],
						'anexo_rut' => $_REQUEST ['destino'],
						'anexo_rup' => $_REQUEST ['destino2'],
						'descripcion_proveedor' => $_REQUEST['descripcion'],
						'fecha_registro' => $fechaActual,
						'fecha_modificación' => $fechaActual,
						'id_estado' => '1' //Estado Inactivo
				);
					
				//Guardar datos PROVEEDOR
				$cadenaSqlInfoProveedor = $this->miSql->getCadenaSql("insertarInformacionProveedor",$datosInformacionProveedorPersonaJuridica);
				array_push($SQLs, $cadenaSqlInfoProveedor);
				
				
				$datosTelefonoFijoPersonaProveedor = array (
						'num_telefono' => $_REQUEST['telefono'],
						'extension_telefono' => $_REQUEST['extension'],
						'tipo' => '1'
				);
				
				
				$cadenaSqlTelFijo = $this->miSql->getCadenaSql("insertarInformacionProveedorTelefono",$datosTelefonoFijoPersonaProveedor);
				array_push($SQLs, $cadenaSqlTelFijo);
				
				
				
				$datosTelefonoProveedorTipoA = array (
						'fki_id_tel' => "currval('agora.prov_proveedor_telefono')",
						'fki_id_Proveedor' => "currval('agora.prov_proveedor_info_id_proveedor_seq')"
				);
				
				$cadenaSqlResTelFijo = $this->miSql->getCadenaSql("insertarInformacionProveedorXTelefono",$datosTelefonoProveedorTipoA);
				array_push($SQLs, $cadenaSqlResTelFijo);
				
				
				
				// REGISTRO LA ACTIVIDAD
				$arregloCIIU = array (
						'fk_id_proveedor' => "currval('agora.prov_proveedor_info_id_proveedor_seq')",
						'num_documento' => $_REQUEST['nit'],
						'actividad' => $_REQUEST ['claseCIIUJur']
				);
				// Guardar ACTIVIDAD
				$cadenaSqlActividad = $this->miSql->getCadenaSql ( "registrarActividad", $arregloCIIU );
				array_push($SQLs, $cadenaSqlActividad);
				
				
				
				
				//Caso representante legal registrado de manera inicial
				if($representanteExiste){
					
					//RELACIONAR REPRESENTANTE LEGAL (Registrado anteriormente como Persona Natural)
					//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
					//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
					
					$datosProveedorXRepre = array (
							'fki_id_Proveedor' => "currval('agora.prov_proveedor_info_id_proveedor_seq')",
							'fki_id_Representante' => $_REQUEST['numeroDocumento'],
							'correo_Repre' => $_REQUEST['correoPer'],
							'tel_Repre' => $_REQUEST['numeroContacto'],
					);
						
					$cadenaSqlProveedorXRepresentante = $this->miSql->getCadenaSql("insertarInformacionProveedorXRepresentante",$datosProveedorXRepre);
					array_push($SQLs, $cadenaSqlProveedorXRepresentante);
						
					
					$datosProCar = array (
							'representante' => $_REQUEST['numeroDocumento'],
							'cargo' => $_REQUEST['cargo']
					);
						
					$cadenaSqlProCar = $this->miSql->getCadenaSql("updateCargoPJ",$datosProCar);
					array_push($SQLs, $cadenaSqlProCar);
					

					if(isset($_REQUEST['paisEmpresa'])){//CAST
						switch($_REQUEST['paisEmpresa']){
							case 1 :
								$_REQUEST['paisEmpresa']='NACIONAL';
								$_REQUEST['personaJuridicaCiudad'] = null;
								break;
							case 2 :
								$_REQUEST['paisEmpresa']='EXTRANJERO';
								break;
						}
					}
						
					if(isset($_REQUEST['tipoIdentifiExtranjera'])){//CAST
						switch($_REQUEST['tipoIdentifiExtranjera']){
							case 1 :
								$_REQUEST['tipoIdentifiExtranjera']='CEDULA DE EXTRANJERIA';
								break;
							case 2 :
								$_REQUEST['tipoIdentifiExtranjera']='PASAPORTE';
								break;
						}
					}
						
					if(isset($_REQUEST['regimenContributivo'])){//CAST
						switch($_REQUEST['regimenContributivo']){
							case 1 :
								$_REQUEST['regimenContributivo']='COMUN';
								break;
							case 2 :
								$_REQUEST['regimenContributivo']='SIMPLIFICADO';
								break;
						}
					}
						
					if(isset($_REQUEST['productoImportacion'])){
						switch($_REQUEST ['productoImportacion']){
							case 1 :
								$_REQUEST ['productoImportacion']='TRUE';
								break;
							case 2 :
								$_REQUEST ['productoImportacion']='FALSE';
								break;
							default:
								$_REQUEST ['productoImportacion']='NULL';
								break;
						}
					}
						
					if(isset($_REQUEST['pyme'])){
						switch($_REQUEST ['pyme']){
							case 1 :
								$_REQUEST ['pyme']='TRUE';
								break;
							case 2 :
								$_REQUEST ['pyme']='FALSE';
								break;
							default:
								$_REQUEST ['pyme']='NULL';
								break;
						}
					}
						
					if(isset($_REQUEST['registroMercantil'])){
						switch($_REQUEST ['registroMercantil']){
							case 1 :
								$_REQUEST ['registroMercantil']='TRUE';
								break;
							case 2 :
								$_REQUEST ['registroMercantil']='FALSE';
								break;
							default:
								$_REQUEST ['registroMercantil']='NULL';
								break;
						}
					}
						
					if(isset($_REQUEST['sujetoDeRetencion'])){
						switch($_REQUEST ['sujetoDeRetencion']){
							case 1 :
								$_REQUEST ['sujetoDeRetencion']='TRUE';
								break;
							case 2 :
								$_REQUEST ['sujetoDeRetencion']='FALSE';
								break;
							default:
								$_REQUEST ['sujetoDeRetencion']='NULL';
								break;
						}
					}
						
						
					if(isset($_REQUEST['agenteRetenedor'])){
						switch($_REQUEST ['agenteRetenedor']){
							case 1 :
								$_REQUEST ['agenteRetenedor']='TRUE';
								break;
							case 2 :
								$_REQUEST ['agenteRetenedor']='FALSE';
								break;
							default:
								$_REQUEST ['agenteRetenedor']='NULL';
								break;
						}
					}
						
					if(isset($_REQUEST['responsableICA'])){
						switch($_REQUEST ['responsableICA']){
							case 1 :
								$_REQUEST ['responsableICA']='TRUE';
								break;
							case 2 :
								$_REQUEST ['responsableICA']='FALSE';
								break;
							default:
								$_REQUEST ['responsableICA']='NULL';
								break;
						}
					}
						
						
					if(isset($_REQUEST['responsableIVA'])){
						switch($_REQUEST ['responsableIVA']){
							case 1 :
								$_REQUEST ['responsableIVA']='TRUE';
								break;
							case 2 :
								$_REQUEST ['responsableIVA']='FALSE';
								break;
							default:
								$_REQUEST ['responsableIVA']='NULL';
								break;
						}
					}
						
					
					$datosInformacionPersonaJuridica = array (
							'fki_numero_documento' => $_REQUEST['nit'],
							'digito_verificacion' => $_REQUEST['digito'],
							'procedencia_empresa' => $_REQUEST['paisEmpresa'],
							'id_ciudad_origen' => $_REQUEST['personaJuridicaCiudad'],
							'codigo_pais_dian' => $_REQUEST['codigoPais'],
							'codigo_postal' => $_REQUEST['codigoPostal'],
							'tipo_identificacion_extranjera' => $_REQUEST['tipoIdentifiExtranjera'],
							'num_cedula_extranjeria' => $_REQUEST['cedulaExtranjeria'],
							'num_pasaporte' => $_REQUEST['pasaporte'],
							'id_tipo_conformacion' => $_REQUEST['tipoConformacion'],
							'monto_capital_autorizado' => $_REQUEST['monto'],
							'genero' => 'NO APLICA',
							'nom_proveedor' => $_REQUEST['nombreEmpresa'],
							'regimen_contributivo' => $_REQUEST['regimenContributivo'],
							'exclusividad_producto' => $_REQUEST ['productoImportacion'],
							'pyme' => $_REQUEST ['pyme'],
							'registro_mercantil' => $_REQUEST ['registroMercantil'],
							'sujeto_retencion' => $_REQUEST ['sujetoDeRetencion'],
							'agente_retenedor' => $_REQUEST ['agenteRetenedor'],
							'responsable_ICA' => $_REQUEST ['responsableICA'],
							'responsable_IVA' => $_REQUEST ['responsableIVA']
					);
						
					//Guardar datos PROVEEDOR JURIDICA
					$cadenaSqlPersonaJuridica = $this->miSql->getCadenaSql ( "registrarProveedorJuridica", $datosInformacionPersonaJuridica );
					array_push($SQLs, $cadenaSqlPersonaJuridica);
					
					
					//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
					//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
					
					
					
				}else{
					
					
					//REGISTRO REPRESENTANTE LEGAL (No Registrado anteriormente como Persona Natural)
					//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
					//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
					
					
					//CAST****************************************************************
					$dateExp = explode("/", $_REQUEST ['fechaExpeRep']);
					$cadena_fecha = $dateExp[2]."-".$dateExp[1]."-".$dateExp[0];
					$_REQUEST ['fechaExpeRep'] = $cadena_fecha;
					//********************************************************************
					
					
					$datosInformacionPersonaNaturalRepresentante = array (
							'id_tipo_documento' =>	$_REQUEST['tipoDocumento'],
							'fki_numero_documento' => $_REQUEST['numeroDocumento'],
							'digito_verificacion' => $_REQUEST['digitoRepre'],
							'primer_apellido' => $_REQUEST['primerApellido'],
							'segundo_apellido' => $_REQUEST['segundoApellido'],
							'primer_nombre' => $_REQUEST['primerNombre'],
							'segundo_nombre' => $_REQUEST['segundoNombre'],
							'genero' => $_REQUEST['genero'],
							'cargo' => $_REQUEST['cargo'],
							'id_pais_nacimiento' => $_REQUEST['paisNacimiento'],
							'id_perfil' => $_REQUEST['perfil'],
							'id_nucleo_basico' => $_REQUEST['personaNBC'],
							'profesion' => $_REQUEST['profesion'],
							'especialidad' => $_REQUEST['especialidad'],
							'monto_capital_autorizado' => null,
							'grupoEtnico' => null,
							'comunidadLGBT' => 'FALSE',
							'cabezaFamilia' => 'FALSE',
							'personasCargo' => 'FALSE',
							'numeroPersonasCargo' => null,
							'estadoCivil' => 'SOLTERO',
							'discapacidad' => 'FALSE',
							'tipoDiscapacidad' => null,
							'declarante_renta' => 'FALSE',
							'medicina_prepagada' => 'FALSE',
							'valor_uvt_prepagada' => null,
							'cuenta_ahorro_afc' => 'FALSE',
							'num_cuenta_bancaria_afc' => null,
							'id_entidad_bancaria_afc' => null,
							'interes_vivienda_afc' => null,
							'dependiente_hijo_menor_edad' => 'FALSE',
							'dependiente_hijo_menos23_estudiando' => 'FALSE',
							'dependiente_hijo_mas23_discapacitado' => 'FALSE',
							'dependiente_conyuge' => 'FALSE',
							'dependiente_padre_o_hermano' => 'FALSE',
							'id_eps' => null,
							'id_fondo_pension' => null,
							'id_caja_compensacion' => null,
							'fecha_expedicion_doc' => $_REQUEST ['fechaExpeRep'],
							'id_lugar_expedicion_doc' => $_REQUEST ['ciudadExpeRep']
					);
					
					
					//Guardar datos PROVEEDOR NATURAL REPRESENTANTE
					$cadenaSqlPersonaNatural = $this->miSql->getCadenaSql ( "registrarProveedorNatural", $datosInformacionPersonaNaturalRepresentante );
					array_push($SQLs, $cadenaSqlPersonaNatural);
					
					
					
					$datosProveedorXRepre = array (
							'fki_id_Proveedor' => "currval('agora.prov_proveedor_info_id_proveedor_seq')",
							'fki_id_Representante' => $_REQUEST['numeroDocumento'],
							'correo_Repre' => $_REQUEST['correoPer'],
							'tel_Repre' => $_REQUEST['numeroContacto'],
					);
					
					$cadenaSqlProveedorXRepresentante = $this->miSql->getCadenaSql("insertarInformacionProveedorXRepresentante",$datosProveedorXRepre);
					array_push($SQLs, $cadenaSqlProveedorXRepresentante);
					
					
					
					

					if(isset($_REQUEST['paisEmpresa'])){//CAST
						switch($_REQUEST['paisEmpresa']){
							case 1 :
								$_REQUEST['paisEmpresa']='NACIONAL';
								$_REQUEST['personaJuridicaCiudad'] = null;
								break;
							case 2 :
								$_REQUEST['paisEmpresa']='EXTRANJERO';
								break;
						}
					}
						
					if(isset($_REQUEST['tipoIdentifiExtranjera'])){//CAST
						switch($_REQUEST['tipoIdentifiExtranjera']){
							case 1 :
								$_REQUEST['tipoIdentifiExtranjera']='CEDULA DE EXTRANJERIA';
								break;
							case 2 :
								$_REQUEST['tipoIdentifiExtranjera']='PASAPORTE';
								break;
						}
					}
						
					if(isset($_REQUEST['regimenContributivo'])){//CAST
						switch($_REQUEST['regimenContributivo']){
							case 1 :
								$_REQUEST['regimenContributivo']='COMUN';
								break;
							case 2 :
								$_REQUEST['regimenContributivo']='SIMPLIFICADO';
								break;
						}
					}
						
					if(isset($_REQUEST['productoImportacion'])){
						switch($_REQUEST ['productoImportacion']){
							case 1 :
								$_REQUEST ['productoImportacion']='TRUE';
								break;
							case 2 :
								$_REQUEST ['productoImportacion']='FALSE';
								break;
							default:
								$_REQUEST ['productoImportacion']='NULL';
								break;
						}
					}
						
					if(isset($_REQUEST['pyme'])){
						switch($_REQUEST ['pyme']){
							case 1 :
								$_REQUEST ['pyme']='TRUE';
								break;
							case 2 :
								$_REQUEST ['pyme']='FALSE';
								break;
							default:
								$_REQUEST ['pyme']='NULL';
								break;
						}
					}
						
					if(isset($_REQUEST['registroMercantil'])){
						switch($_REQUEST ['registroMercantil']){
							case 1 :
								$_REQUEST ['registroMercantil']='TRUE';
								break;
							case 2 :
								$_REQUEST ['registroMercantil']='FALSE';
								break;
							default:
								$_REQUEST ['registroMercantil']='NULL';
								break;
						}
					}
						
					if(isset($_REQUEST['sujetoDeRetencion'])){
						switch($_REQUEST ['sujetoDeRetencion']){
							case 1 :
								$_REQUEST ['sujetoDeRetencion']='TRUE';
								break;
							case 2 :
								$_REQUEST ['sujetoDeRetencion']='FALSE';
								break;
							default:
								$_REQUEST ['sujetoDeRetencion']='NULL';
								break;
						}
					}
						
						
					if(isset($_REQUEST['agenteRetenedor'])){
						switch($_REQUEST ['agenteRetenedor']){
							case 1 :
								$_REQUEST ['agenteRetenedor']='TRUE';
								break;
							case 2 :
								$_REQUEST ['agenteRetenedor']='FALSE';
								break;
							default:
								$_REQUEST ['agenteRetenedor']='NULL';
								break;
						}
					}
						
					if(isset($_REQUEST['responsableICA'])){
						switch($_REQUEST ['responsableICA']){
							case 1 :
								$_REQUEST ['responsableICA']='TRUE';
								break;
							case 2 :
								$_REQUEST ['responsableICA']='FALSE';
								break;
							default:
								$_REQUEST ['responsableICA']='NULL';
								break;
						}
					}
						
						
					if(isset($_REQUEST['responsableIVA'])){
						switch($_REQUEST ['responsableIVA']){
							case 1 :
								$_REQUEST ['responsableIVA']='TRUE';
								break;
							case 2 :
								$_REQUEST ['responsableIVA']='FALSE';
								break;
							default:
								$_REQUEST ['responsableIVA']='NULL';
								break;
						}
					}
						
					
					$datosInformacionPersonaJuridica = array (
							'fki_numero_documento' => $_REQUEST['nit'],
							'digito_verificacion' => $_REQUEST['digito'],
							'procedencia_empresa' => $_REQUEST['paisEmpresa'],
							'id_ciudad_origen' => $_REQUEST['personaJuridicaCiudad'],
							'codigo_pais_dian' => $_REQUEST['codigoPais'],
							'codigo_postal' => $_REQUEST['codigoPostal'],
							'tipo_identificacion_extranjera' => $_REQUEST['tipoIdentifiExtranjera'],
							'num_cedula_extranjeria' => $_REQUEST['cedulaExtranjeria'],
							'num_pasaporte' => $_REQUEST['pasaporte'],
							'id_tipo_conformacion' => $_REQUEST['tipoConformacion'],
							'monto_capital_autorizado' => $_REQUEST['monto'],
							'genero' => 'NO APLICA',
							'nom_proveedor' => $_REQUEST['nombreEmpresa'],
							'regimen_contributivo' => $_REQUEST['regimenContributivo'],
							'exclusividad_producto' => $_REQUEST ['productoImportacion'],
							'pyme' => $_REQUEST ['pyme'],
							'registro_mercantil' => $_REQUEST ['registroMercantil'],
							'sujeto_retencion' => $_REQUEST ['sujetoDeRetencion'],
							'agente_retenedor' => $_REQUEST ['agenteRetenedor'],
							'responsable_ICA' => $_REQUEST ['responsableICA'],
							'responsable_IVA' => $_REQUEST ['responsableIVA']
					);
						
					//Guardar datos PROVEEDOR JURIDICA
					$cadenaSqlPersonaJuridica = $this->miSql->getCadenaSql ( "registrarProveedorJuridica", $datosInformacionPersonaJuridica );
					array_push($SQLs, $cadenaSqlPersonaJuridica);
						
						
					//****************************************************************************************************************************
					$nombrePersonaRepre = $_REQUEST['primerNombre'] . ' ' . $_REQUEST['segundoNombre'] . ' ' . $_REQUEST['primerApellido'] . ' ' . $_REQUEST['segundoApellido'];
						
					$datosInformacionProveedorPersonaNaturalRepresentante = array (
							'tipoPersona' => 'NATURAL',
							'numero_documento' => $_REQUEST['numeroDocumento'],
							'nombre_proveedor' => $nombrePersonaRepre,
							'id_ciudad_contacto' =>	$_REQUEST['ciudad'],
							'direccion_contacto' => $_REQUEST['direccion'],
							'correo_contacto' => $_REQUEST['correoPer'],
							'web_contacto' => '',
							'nom_asesor_comercial_contacto' => '',
							'tel_asesor_comercial_contacto' => '',
							'tipo_cuenta_bancaria' => $_REQUEST['tipoCuenta'],
							'num_cuenta_bancaria' => $_REQUEST['numeroCuenta'],
							'id_entidad_bancaria' => $_REQUEST['entidadBancaria'],
							'anexo_rut' => null,
							'anexo_rup' => null,
							'descripcion_proveedor' => '',
							'fecha_registro' => $fechaActual,
							'fecha_modificación' => $fechaActual,
							'id_estado' => '2' //Estado Inactivo
					);
					//Guardar datos PROVEEDOR Representante
					$cadenaSqlProveedorNatural = $this->miSql->getCadenaSql("insertarInformacionProveedor",$datosInformacionProveedorPersonaNaturalRepresentante);
					array_push($SQLs, $cadenaSqlProveedorNatural);	
					//****************************************************************************************************************************
					
					//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
					//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
					
				}

				$registroPersona = $esteRecursoDB->transaccion($SQLs);

				if ($registroPersona != false) {
				
				
				
					//****************************************************************
					$_REQUEST['identificacion'] = $_REQUEST['nit'];
					$_REQUEST['tipo_identificacion'] = "NIT";
					$_REQUEST['nombres'] = "EMPRESA";
					$_REQUEST['apellidos'] = $_REQUEST['nombreEmpresa'];
					$_REQUEST['subsistema'] = 2;
					$_REQUEST['perfil'] = 7;
					
					$fechaEnd = date('Y-m-j');
					$nuevafechaEnd = strtotime ( '+2 year' , strtotime ( $fechaEnd ) ) ;
					$nuevafechaEnd = date ( 'Y-m-j' , $nuevafechaEnd );
						
					$_REQUEST['fechaFin'] = $nuevafechaEnd;
					//****************************************************************
				
				
				
					$caracteres = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
					$num = '1234567890';
					$caracter = '=$';
					$numerodeletras=5;
					$pass = "";
					$keycar=$keyNum= "";
					for($i=0;$i<$numerodeletras;$i++)
					{$pass .= substr($caracteres,rand(0,strlen($caracteres)),1); }
				
					$maxCar = strlen($caracter)-1;
					$maxNum = strlen($num)-1;
				
					for($j=0;$j < 1;$j++)
					{ $keycar .= $caracter{mt_rand(0,$maxCar)};}
					for($k=0;$k < 2;$k++)
					{ $keyNum .= $num{mt_rand(0,$maxNum)};}
					$pass=$pass.$keycar.$keyNum;
					$password = $this->miConfigurador->fabricaConexiones->crypto->codificarClave ( $pass );
					$hoy = date("Y-m-d");
				
					$arregloDatos = array(
							'id_usuario'=>$_REQUEST['tipo_identificacion'].$_REQUEST['identificacion'],
							'nombres'=>$_REQUEST['nombres'],
							'apellidos'=>$_REQUEST['apellidos'],
							'correo'=>$_REQUEST['correo'],
							'telefono'=>$_REQUEST['telefono'],
							'subsistema'=>$_REQUEST['subsistema'],
							'perfil'=>$_REQUEST['perfil'],
							'password'=>$password,
							'pass'=>$pass,
							'fechaIni'  =>$hoy,
							'fechaFin'  =>$_REQUEST['fechaFin'],
							'identificacion'=>$_REQUEST['identificacion'],
							'tipo_identificacion'=>$_REQUEST['tipo_identificacion'],  );
				
				
						
				
				
					$this->cadena_sql = $this->miSql->getCadenaSql("insertarUsuario", $arregloDatos);
					$resultadoEstado = $frameworkRecursoDB->ejecutarAcceso($this->cadena_sql, "acceso");
				
				
					$this->cadena_sql = $this->miSql->getCadenaSql("insertarPerfilUsuario", $arregloDatos);
					$resultadoPerfil = $frameworkRecursoDB->ejecutarAcceso($this->cadena_sql, "acceso");
				
				
					$parametro['id_usuario'] = $arregloDatos['id_usuario'];
					$cadena_sql = $this->miSql->getCadenaSql("consultarPerfilUsuario", $parametro);
					$resultadoPerfil = $frameworkRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
				
					$c = 0;
					while ($c < count($SQLs)){
						$SQLsDec[$c] = $this->miConfigurador->fabricaConexiones->crypto->codificar($SQLs[$c]);
						$c++;
					}
					$query = json_encode($SQLsDec);
					
					$log = array('accion'=>"REGISTRO PERSONA JURIDICA",
							'id_registro'=>$_REQUEST['tipo_identificacion'].$_REQUEST['identificacion'],
							'tipo_registro'=>"GESTION USUARIO JURIDICA",
							'nombre_registro'=>"id_usuario=>".$_REQUEST['tipo_identificacion'].$_REQUEST['identificacion'].
												"|identificacion=>".$_REQUEST['identificacion'].
												"|tipo_identificacion=>".$_REQUEST['tipo_identificacion'].
												"|nombres=>".$_REQUEST['nombres'].
												"|apellidos=>".$_REQUEST['apellidos'].
												"|correo=>".$_REQUEST['correo'].
												"|telefono=>".$_REQUEST['telefono'].
												"|subsistema=>".$_REQUEST['subsistema'].
												"|perfil=>".$_REQUEST['perfil'].
												"|fechaIni=>".$hoy.
												"|fechaFin=>".$_REQUEST['fechaFin'],
							'descripcion'=>"Registro de nuevo Usuario ".$_REQUEST['tipo_identificacion'].$_REQUEST['identificacion']." con perfil ".$resultadoPerfil[0]['rol_alias'],
							'query'=> $query,
					);
				
				
				
					$this->miLogger->log_usuario($log);
				
					$arregloDatos['perfilUs'] = $resultadoPerfil[0]['rol_alias'];
				
					$datosRegistroUsuario = array (
							'tipo_identificacion'=>$_REQUEST['tipo_identificacion'],
							'num_documento' => $_REQUEST ['nit'],
							'tipo_persona' => $_REQUEST ['tipoPersona'],
							'contrasena' => $password,
							'generadaPass' => $pass,
							'tipo' => "Tercero",
							'rolMenu' => "Proveedor",
							'estado' => "Inactivo",
							'nombre' => $_REQUEST ["nombres"],
							'apellido' => $_REQUEST ["apellidos"],
							'correo' => $_REQUEST['correo'],
							'telefono' => $_REQUEST['telefono'],
							'id_usuario' => $arregloDatos['id_usuario']
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
					$error = json_encode(error_get_last());
						
					$datosLog = array (
							'tipo_log' => 'REGISTRO',
							'tipo_persona' => 'JURIDICA',
							'documento' => $_REQUEST ['nit'],
							'query' => $query,
							'data' => null,
							'host' => $ip,
							'fecha_log' => date("Y-m-d H:i:s"),
							'usuario' => 'REG777'
					);
					$cadenaSQL = $this->miSql->getCadenaSql("insertarLogProveedorBnRg", $datosLog);
					$resultadoLog = $frameworkRecursoDB->ejecutarAcceso($cadenaSQL, 'busqueda');
		
					
				
				
					if($resultadoEstado && $resultadoPerfil){
				
						redireccion::redireccionar ( 'registroProveedor',  $datosRegistroUsuario);
						exit();
				
					}else{
				
						redireccion::redireccionar ( 'noregistroUsuario',  $_REQUEST['usuario']);
						exit();
				
					}
				
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
					
					$datosLog = array (
							'tipo_log' => 'REGISTRO',
							'tipo_persona' => 'JURIDICA',
							'documento' => $_REQUEST ['nit'],
							'query' => $query,
							'error' => $error,
							'host' => $ip,
							'fecha_log' => date("Y-m-d H:i:s"),
							'usuario' => 'REG777'
					);
					$cadenaSQL = $this->miSql->getCadenaSql("insertarLogProveedor", $datosLog);
					$resultadoLog = $frameworkRecursoDB->ejecutarAcceso($cadenaSQL, 'busqueda');
						
					$caso = "RC-" . date("Y") . "-" . $resultadoLog[0][0];
					
					redireccion::redireccionar ( 'noregistro',  $caso);
					exit();
				}
		
		}		
		
	


	}
	
	function resetForm() {
		foreach ( $_REQUEST as $clave => $valor ) {
			
			if ($clave != 'pagina' && $clave != 'development' && $clave != 'jquery' && $clave != 'tiempo') {
				unset ( $_REQUEST [$clave] );
			}
		}
	}
}

$miRegistrador = new Formulario ( $this->lenguaje, $this->sql, $this->funcion,$this->miLogger );
$resultado = $miRegistrador->procesarFormulario ();

?>
