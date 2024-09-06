<?php
namespace App\Controllers;
require_once($_SERVER['DOCUMENT_ROOT']. '/ApiGlobalOro/PHPMailer/src/Exception.php');
require_once($_SERVER['DOCUMENT_ROOT']. '/ApiGlobalOro/PHPMailer/src/PHPMailer.php');
require_once($_SERVER['DOCUMENT_ROOT']. '/ApiGlobalOro/PHPMailer/src/SMTP.php');

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Cliente;
use App\Models\Venta;
use App\Models\WebToken;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


class Clientes extends ResourceController
{
    function obtenerClientes() {
        $Cliente=new Cliente();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		// $sort = $this->request->getPost("sort");
		// $order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Cliente->getClientes($page, $pageSize));
	}
    function obtenerNumeroClientes() {
        $Cliente=new Cliente();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		// $sort = $this->request->getPost("sort");
		// $order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Cliente->getNumeroClientes($page, $pageSize));
	}
    function create() {
        $Cliente=new Cliente();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
		$FechaCompra = $this->request->getPost("FechaCompra"); 
        $TipoMovimiento = $this->request->getPost("TipoMovimiento");
        $idTipoGasolina = $this->request->getPost("idTipoGasolina");
        $cantidad = $this->request->getPost("cantidad");
        $data=[
			'TipoMovimiento'=>$TipoMovimiento,
            'idTipoGasolina'=>$idTipoGasolina,
            'Fechacompra'=>$FechaCompra,
            'idGasolinera'=>$idGasolinera,
            'cantidad'=>$cantidad
		];
		$Cliente->insert($data);
		echo json_encode([
			'message' => "Se ha registrado la compra correctamente"
		]);
	}
    function cambiarEstatus() {
        $Cliente=new Cliente();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$estatus = $this->request->getPost("estatus");
		$idCliente = $this->request->getPost("idCliente");  
        $data=[
			'status'=>$estatus,
		];
		$Cliente->actualizarClientes($idCliente,$data);
		$data=array('message' => "El status del usuario ha cambiado exitosamente");
		$this->response->setStatusCode(200, json_encode(array('message' => "El status del usuario ha cambiado exitosamente")));
		return $this->response->setJSON($data);
	}

	function update($idCliente = null) {
        $Cliente=new Cliente();

		$WebToken=new WebToken();
		$jwt = $this->request->getRawInputVar("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$FechaCompra = $this->request->getRawInputVar("FechaCompra"); 
        $TipoMovimiento = $this->request->getRawInputVar("TipoMovimiento");
        $idTipoGasolina = $this->request->getRawInputVar("idTipoGasolina");
        $cantidad = $this->request->getRawInputVar("cantidad");
        $data=[
			'TipoMovimiento'=>$TipoMovimiento,
            'idTipoGasolina'=>$idTipoGasolina,
			'Fechacompra'=>$FechaCompra,
            'idGasolinera'=>$idGasolinera,
            'cantidad'=>$cantidad
		];
		$Cliente->actualizarClientes($idCliente,$data);
		echo json_encode([
			'message' => "Se ha actualizado el cliente"
		]);
	}

	function actualizarCliente() {
        $Cliente=new Cliente();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idCliente = $this->request->getPost("idCliente");
		$nombre_cliente = $this->request->getPost("nombre_cliente");
		$telefono_cliente = $this->request->getPost("telefono_cliente");
		$correo = $this->request->getPost("correo");
		$direccion = $this->request->getPost("direccion");
        $data=[
			'nombre_cliente'=>$nombre_cliente,
            'telefono_cliente'=>$telefono_cliente,
			'correo'=>$correo,
            'direccion'=>$direccion
		];
		$Cliente->actualizarClientes($idCliente,$data);
		$data=array('message' => "Se ha actualizado el cliente");
		$this->response->setStatusCode(200, json_encode(array('message' => "Se ha actualizado el cliente")));
		return $this->response->setJSON($data);
		// echo json_encode([
		// 	'message' => "Se ha actualizado el cliente"
		// ]);
	}
	function aceptarFlujo() {
        $Cliente=new Cliente();
		$Venta=new Venta();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idCliente = $this->request->getPost("idCliente");
        $data=[
			'aceptoFlujo'=>1
		];
		$Cliente->actualizarClientes($idCliente,$data);
		$Transaccion = $Venta->selectultimatransaccionxidCliente($idCliente);
		$idTransaccion=$Transaccion['idTransaccion'];
		// $transt=$Venta->selecttransaccion($idTransaccion);
		$datosCliente=$Cliente->selectClientesxid($idCliente);
		$nombre_cliente=$datosCliente['nombre_cliente'];
		$tituload="El cliente acepto el flujo";
		$mensajead="<p>Buen día </p>
		<p>La compra del cliente ".$nombre_cliente." , con fecha del ".$Transaccion['fecha']." acaba de ser procesada y el cliente ha aceptado el flujo, favor de verificar el estatus de su validación en la plataforma de Legalario y poner su oro bajo resguardo.</p>";
		$this->enviarCorreoAdmn($tituload,$mensajead);
		$data=array('message' => "Se ha aceptado el proceso de validación, por favor revise su correo para continuar con él");
		$this->response->setStatusCode(200, json_encode(array('message' => "Se ha aceptado el proceso de validación, por favor revise su correo para continuar con él")));
		return $this->response->setJSON($data);
		// echo json_encode([
		// 	'message' => "Se ha actualizado el cliente"
		// ]);
	}

    function deleteCompra($idCliente = null) {
        $Cliente=new Cliente();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idCliente = $this->request->getPost("idCliente"); 
		$Cliente->deleteClientes($idCompra);
		echo json_encode([
			'message' => "Se ha eliminado el cliente"
		]);
	}

    function obtenerpaises() {
        $Cliente=new Cliente();
		echo json_encode($Cliente->selectPais());
	}

	function crearcliente(){
		$Cliente=new Cliente();
		$WebToken=new WebToken();
		// $Archivo=new Archivo();
		// $jwt = $this->request->getPost("jwt");
		// $jwt = $WebToken->decodificarJWT($jwt);
		// $idUsuario = $jwt->idUsuario;
		$nombre_cliente = $this->request->getPost("nombre_cliente");
		$telefono_cliente = $this->request->getPost("telefono_cliente");
		$correo = $this->request->getPost("correo");
		$direccion = $this->request->getPost("direccion");
		$pass=$this->request->getPost('password');
	  	$hashed_password = password_hash($pass, PASSWORD_DEFAULT);
		$pais_residencia=$this->request->getPost('pais_residencia');
		$verificarexistencia=$Cliente->verificarexistenciaClientes($correo);
		if(!$verificarexistencia){
			$data=[
				'nombre_cliente'=>$nombre_cliente,
				'telefono_cliente'=>$telefono_cliente,
				'correo'=>$correo,
				'direccion'=>$direccion,
				'passwordUsuario'=>$hashed_password,
				'pais_residencia'=>$pais_residencia,
				'idtipoUsuario'=>2,
				'aceptoFlujo'=>0,
				'status'=>4,
			];
			$Cliente->insert($data);
			$titulo="Creacion de cuenta";
			$mensaje="<p>Buen día ".$nombre_cliente."</p>
			<p>Su cuenta se ha creado de manera exitosa, estos son los accesos para acceder a su cuenta, por favor mantengalos bajo resguardo</p>
			<p>Usuario:".$correo."</p>
			<p>Contraseña:".$pass."</p>";
			$this->enviarCorreo($correo,$titulo,$mensaje);
			$data=array('message' => "Se ha creado su usuario");
			$this->response->setStatusCode(200, json_encode(array('message' => "El usuario ha sido creado correctamente")));
			return $this->response->setJSON($data);
		}
		else{
			$data=array('message' => "Ya existe un usuario con este correo");
			$this->response->setStatusCode(500, json_encode(array('message' => "Ya existe un usuario con este correo")));
			return $this->response->setJSON($data);
		}
		
	
		// echo json_encode([
		// 	'message' => "El usuario ha sido creado correctamente"
		// ]);
	}

	function obtenerClientexId(){
		$WebToken=new WebToken();
		// $Archivo=new Archivo();
		$Cliente=new Cliente();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idCliente = $this->request->getPost("idCliente"); 
		echo json_encode($Cliente->selectClientesxid($idCliente));
	}

	function enviarCorreo($correo,$titulo,$mensaje)
	{
				$sender=mb_convert_encoding('Notificacion GlobalShield', "UTF-8");
				$mail = new PHPMailer(true);
				try {
				// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
				$mail->isSMTP();                                            //Send using SMTP
				$mail->Host       = 'globalshield.com.mx';                     //Set the SMTP server to send through
				$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
				$mail->Username   = 'notificacion@globalshield.com.mx';                     //SMTP username
				$mail->Password   = 'Q8op#f92';                               //SMTP password
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
				$mail->Port       = 587;    
				$mail->SMTPSecure = "tls";
				$mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true,
					),
				);
				$mail->setFrom('notificacion@globalshield.com.mx', $sender);
				$mail->addAddress($correo); 
				$mail->isHTML(true);
				$mail->Subject = mb_convert_encoding($titulo, "UTF-8");
				$mail->Body    = '
					  <!doctype html>
						  <html lang="es">
						  <head>
							  <meta charset="utf-8">
							  <title>Notificacion de venta de oro</title>
							  <meta name="description" content="GlobalShield">
							  <style>
								  .centrado {
									  text-align: center;
								  }
								  .imagenPrincipal {
									  max-width: 20%;
									  min-width: 20%;
								  }
								  .divBoton {
									  background-color:#01224e;
									  width: 50%;
									  color: #FFFFFF;
									  margin-left: 25%;
									  padding-top: 30px;
									  padding-bottom: 30px;
									  border: #021b3b 1px solid;
									  border-radius: 20px;
									  font-weight: bold;
								  }
								  .bordeado {
									  border: 1px solid black;
									  border-radius: 20px;
									  padding: 10px;
								  }
							  </style>
						  </head>
						  <body>
						  <img src="https://www.globalshield.com.mx/inversion-en-oro/assets/images/logoglobalcorreo.jpg">
							  <div class="bordeado">
								  <div class="centrado">
									  <h1 class="centrado">'.mb_convert_encoding($titulo, "UTF-8").'</h1>
								  </div>
								  <div class="centrado">
									  
								  </div>
								  <div class="centrado">
									  '.mb_convert_encoding($mensaje, "UTF-8").'
								  </div>
								  <div class="centrado" align="center">
					  
								  </div>
							  </div>
						  </body>
					  </html>';
					  $mail->send();
						//   echo 'Message has been sent';
					  } catch (Exception $e) {
						//   echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
					  }
	}
	function enviarCorreoAdmn($titulo,$mensaje)
	{

				$Venta=new Venta();
				$sender=mb_convert_encoding('Notificacion GlobalShield', "UTF-8");
				$mail = new PHPMailer(true);
				try {
				// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
				$mail->isSMTP();                                            //Send using SMTP
				$mail->Host       = 'globalshield.com.mx';                     //Set the SMTP server to send through
				$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
				$mail->Username   = 'notificacion@globalshield.com.mx';                     //SMTP username
				$mail->Password   = 'Q8op#f92';                               //SMTP password
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
				$mail->Port       = 587;    
				$mail->SMTPSecure = "tls";
				$mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true,
					),
				);
				$mail->setFrom('notificacion@globalshield.com.mx', $sender);
			 
				$admincorreos=$Venta->obtenerUsuariosAdmin();
				$numerocorreo=0;
				foreach($admincorreos as $prod){
					if($numerocorreo==1){
						$mail->addAddress($prod['correo']);
					}else if($numerocorreo>1){
						$mail->AddCC($prod['correo']);
					}
					$numerocorreo=$numerocorreo+1;
				}
				$mail->isHTML(true);
				$mail->Subject = mb_convert_encoding($titulo, "UTF-8");
				$mail->Body    = '
					  <!doctype html>
						  <html lang="es">
						  <head>
							  <meta charset="utf-8">
							  <title>Notificacion venta de oro</title>
							  <meta name="description" content="GlobalShield">
							  <style>
								  .centrado {
									  text-align: center;
								  }
								.table.center {
  								  margin-left:auto; 
  								  margin-right:auto;
  								}
								  .imagenPrincipal {
									  max-width: 20%;
									  min-width: 20%;
								  }
								  .divBoton {
									  background-color:#01224e;
									  width: 50%;
									  color: #FFFFFF;
									  margin-left: 25%;
									  padding-top: 30px;
									  padding-bottom: 30px;
									  border: #021b3b 1px solid;
									  border-radius: 20px;
									  font-weight: bold;
								  }
								  .bordeado {
									  border: 1px solid black;
									  border-radius: 20px;
									  padding: 10px;
								  }
							  </style>
						  </head>
						  <body>
						  <img src="https://www.globalshield.com.mx/inversion-en-oro/assets/images/logoglobalcorreo.jpg">
							  <div class="bordeado">
								  <div class="centrado">
									  <h1 class="centrado">'.mb_convert_encoding($titulo, "UTF-8").'</h1>
								  </div>
								  <div class="centrado">
									  
								  </div>
								  <div class="centrado" align="center">
									  '.mb_convert_encoding($mensaje, "UTF-8").'
								  </div>
								  <div class="centrado" align="center">
					  
								  </div>
							  </div>
						  </body>
					  </html>';
					  $mail->send();
						//   echo 'Message has been sent';
					  } catch (Exception $e) {
						//   echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
					  }
	}
}?>