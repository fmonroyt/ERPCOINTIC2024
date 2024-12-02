<?php
namespace App\Controllers;
require_once($_SERVER['DOCUMENT_ROOT']. '/Api_Template/PHPMailer/src/Exception.php');
require_once($_SERVER['DOCUMENT_ROOT']. '/Api_Template/PHPMailer/src/PHPMailer.php');
require_once($_SERVER['DOCUMENT_ROOT']. '/Api_Template/PHPMailer/src/SMTP.php');
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Usuario;
use App\Models\WebToken;
use App\Models\Archivo;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
class Usuarios extends ResourceController
{
	public function getIndex()
    {
        return 'Hello ';
    }
	function Autenticar()
	{
		// $method = $request->getMethod();
		$Usuario=new Usuario();
		$WebToken=new WebToken();
		$usuario = $this->request->getPost("usuario");
		$password = $this->request->getPost("password");
		$tipoUsuario = $this->request->getPost("tipo");
        if($tipoUsuario!=''){
			$autenticado = $Usuario->auth($usuario, $password, $tipoUsuario);
		}
		else{
			$autenticado = $Usuario->auth($usuario, $password, null);
		}
		
		if (!empty($autenticado)) {

			$jwt = $WebToken->generarJWT(array(
				'idUsuario' => $autenticado['idUsuario'],
				'idPerfil' => $autenticado['idPerfil'],
				'status' => $autenticado['Status'],
				
			));
			http_response_code(200);
			echo json_encode(array(
				'message' => "Usuario autenticado exitosamente.",
				'jwt' => $jwt
			));
		} else {
			$data=array('message' => "Credenciales de usuario no válidas");
			// $this->response->setStatusCode(403)->setBody($body);
			$this->response->setStatusCode(403, json_encode(array('message' => "Credenciales de usuario no válidas")));
			return $this->response->setJSON($data);
		}
		// echo json_encode(array('message' => "Credenciales de usuario no válidas"));
	}
	// registra un usuario de tipo cliente.




	function Usuario()
	{
		$Usuario=new Usuario();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("usuario");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;

		if (empty($idUsuario)) {
			http_response_code(500);
			echo json_encode(array(
				'message' => "Tú sesión expiró. Por favor, vuelve a iniciar sesión." . json_encode($_SESSION)
			));
			return;
		} else {
			// if ($this->input->post("idUsuario") && $jwt->idTipoUsuario != 27) {
			// 	echo json_encode($this->Usuario->getUsuario($this->input->post("idUsuario")));
			// } else {
			// 	echo json_encode($this->Usuario->getUsuario($idUsuario));
			// }
			echo json_encode($Usuario->getUsuario($idUsuario));

		}
	}

	function UsuarioAngular()
	{
		$Usuario=new Usuario();
		$WebToken=new WebToken();
		// $this->load->model("WebToken");
		$jwt =  $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		//echo $idUsuario;
		if (empty($idUsuario)) {
			http_response_code(500);
			echo json_encode(array(
				'message' => "Tú sesión expiró. Por favor, vuelve a iniciar sesión." . json_encode($_SESSION)
			));
			return;
		} else {
				echo json_encode($Usuario->getUsuarioAngular($idUsuario));
		}
	}

	function getUsuarioxid()
	{
		$Usuario=new Usuario();
		$idUsuario = $this->request->getPost('idUsuario');

		echo json_encode($Usuario->getUsuario($idUsuario));		
	}
	function crearusuario() {
		$Usuario = new Usuario();
		$WebToken = new WebToken();
		$Archivo = new Archivo();
		
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$nombreUsuario = $this->request->getPost('nombreUsuario');
		$apellidoPaterno = $this->request->getPost('apellidoPaterno');
		$apellidoMaterno = $this->request->getPost('apellidoMaterno');
		$nickName = $this->request->getPost('nickName');
		$pass = $this->request->getPost('password');
		$hashed_password = password_hash($pass, PASSWORD_DEFAULT);
		$correoDestino = $this->request->getPost('correoDestino');
		$idDepartamento = $this->request->getPost('idDepartamento');
		$idPerfil = $this->request->getPost('idPerfil');
		
		// Obtener el archivo
		$archivo = $this->request->getFile('archivo');
	
		// Verificación del archivo, solo si no es null
		if ($archivo !== null && $archivo->isValid()) {
			$archivo = $Archivo->subirArchivoimagenperfil($archivo);  // Pasa el archivo como objeto, no como string
		} else {
			// Si el archivo no se sube correctamente
			$archivo = array(
				'directorio' => null,
				'tipo' => null,
				'extension' => null,
				'nombreOriginal' => null
			);
		}
		$data = [
			'nombreUsuario' => $nombreUsuario,
			'apellidoPaterno' => $apellidoPaterno,
			'apellidoMaterno' => $apellidoMaterno,
			'nickName' => $nickName,
			'passwordUsuario' => $hashed_password,
			'correoDestino' => $correoDestino,
			'idPerfil' => $idPerfil,
			'idDepartamento' => $idDepartamento,
			'Status' => 0,
			'fotoUsuario' => $archivo['directorio'], // Guardar la ruta del archivo
		];
	
		$Usuario->insert($data);
		$data = array('message' => "El usuario ha sido creado correctamente");
		return $this->response->setJSON($data);
	}
	
/*
	function crearusuario(){
		$Usuario=new Usuario();
		$WebToken=new WebToken();
		$Archivo=new Archivo();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$nombreUsuario=$this->request->getPost('nombreUsuario');
		$apellidoPaterno=$this->request->getPost('apellidoPaterno');
		$apellidoMaterno=$this->request->getPost('apellidoMaterno');
		$nickName=$this->request->getPost('nickName');
		$pass=$this->request->getPost('password');
	  	$hashed_password = password_hash($pass, PASSWORD_DEFAULT);
		$correoDestino=$this->request->getPost('correoDestino');
		$idDepartamento=$this->request->getPost('idDepartamento');
		$idPerfil=$this->request->getPost('idPerfil');
		
		if ($_FILES && $_FILES['archivo']) {
			$archivo = $Archivo->subirArchivoimagenperfil("archivo");
		} else {
			$archivo = array(
				'directorio' => null,
				'tipo' => null,
				'extension' => null,
				'nombreOriginal' => null
			);
		}
		$data=[
			'nombreUsuario'=>$nombreUsuario,
			'apellidoPaterno'=>$apellidoPaterno,
			'apellidoMaterno'=>$apellidoMaterno,
			'nickName'=>$nickName,
			'passwordUsuario'=>$hashed_password ,
			'correoDestino'=>$correoDestino,
			'idPerfil'=>$idPerfil,
			'idDepartamento'=>$idDepartamento,
			'Status'=> 0,
			'fotoUsuario'=>$archivo['directorio'],
		];
		$Usuario->insert($data);
		$data=array('message' => "El usuario ha sido creado correctamente");
		$this->response->setStatusCode(200, json_encode(array('message' => "El usuario ha sido creado correctamente")));
		return $this->response->setJSON($data);
	}
	*/
	
	function editarusuario(){
		$Usuario=new Usuario();
		$WebToken=new WebToken();
		$Archivo=new Archivo();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idUsuarioEditar = $this->request->getPost("idUsuarioEditar");
		$nombreUsuario = $this->request->getPost("nombreUsuario");
		$apellidoPaterno = $this->request->getPost("apellidoPaterno");
		$apellidoMaterno = $this->request->getPost("apellidoMaterno");
		$nickName = $this->request->getPost("nickName");
		$pass=$this->request->getPost('password');
		$correoDestino = $this->request->getPost("correoDestino");
		$idDepartamento = $this->request->getPost("idDepartamento");
		$idPerfil = $this->request->getPost("idPerfil");

		//$telefono_cliente = $this->request->getPost("telefono_cliente");
		//$direccion = $this->request->getPost("direccion");
		$status = $this->request->getPost("status");
		if($pass){
			$hashed_password = password_hash($pass, PASSWORD_DEFAULT);
		}
		//$tipoUsuario=$this->request->getPost('tipoUsuario');
			$data=[
			//'idUsuarioEditar'=>$idUsuarioEditar,
			'nombreUsuario'=>$nombreUsuario,
			'apellidoPaterno'=>$apellidoPaterno,
			'apellidoMaterno'=>$apellidoMaterno,
			'nickName'=>$nickName,
			'correoDestino'=>$correoDestino,
			'idDepartamento'=>$idDepartamento,
			'idPerfil'=>$idPerfil,
			//'telefono_cliente'=>$telefono_cliente,
			//'direccion'=>$direccion,
			//'pais_residencia'=>118,
			//'idtipoUsuario'=>$tipoUsuario,
			//'aceptoFlujo'=>0,
			'status'=>$status,
			];
			$Usuario->updateUsuario($idUsuarioEditar,$data);
		if($pass){
			$data=[
			'passwordUsuario'=>$hashed_password,
			];
			$Usuario->updateUsuario($idUsuarioEditar,$data);	

			$titulo="Cambio de accesos de su usuario";
			$mensaje="<p>Buen día ".$nombreUsuario."</p>
			<p>estos son los nuevos accesos para acceder a su perfil, por favor mantengalos bajo resguardo</p>
			<p>Usuario:".$correoDestino."</p>
			<p>Contraseña:".$pass."</p>";
			$this->enviarCorreo($correoDestino,$titulo,$mensaje);
		}
		echo json_encode([
			'message' => "El usuario ha sido actualizado correctamente"
		]);
	}
	

	
	function ActualizarFotoPerfil(){
		$Usuario=new Usuario();
		$WebToken=new WebToken();
		$Archivo=new Archivo();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		  $idUsuarioEditar=$this->request->getPost('idUsuarioEditar');
			$archivo = $Archivo->subirArchivoimagenperfil("archivo");
			$data=[
				'fotoUsuario'=>$archivo['directorio'],
			];
			$Usuario->updateUsuario($id,$data);
			echo json_encode([
				'message' => "Foto actualizada correctamente"
			]);
	}

	protected $allowedFields = [
        'idUsuario', 
        'newstatus', 
        
    ];
	function cambiarStatusUsuarios(){
		$Usuario=new Usuario();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idUsuario=$this->request->getPost('idUsuario');
		$newstatus=$this->request->getPost('newstatus');
			$data=[
			'Status'=>$newstatus,
			];
			$Usuario->delete($idUsuario,$data);	

	$data=array('message' => "El status del usuario ha cambiado exitosamente");
	$this->response->setStatusCode(200, json_encode(array('message' => "El status del usuario ha cambiado exitosamente")));
	return $this->response->setJSON($data);
		// echo json_encode([
		// 	'message' => "El status del usuario ha cambiado exitosamente"
		// ]);
	}


/*
function cambiarStatusUsuarios(){
    try {
        $Usuario = new Usuario();
        $WebToken = new WebToken();

        // Decodificar el JWT
        $jwt = $this->request->getPost("jwt");
        $jwt = $WebToken->decodificarJWT($jwt);
        $idUsuario = $jwt->idUsuario;

        // Obtener datos del request
        $idUsuarioEditar = $this->request->getPost('idUsuario');
        $newstatus = $this->request->getPost('newstatus');

        // Preparar los datos para actualizar
        $data = [
            'Status' => $newstatus,
        ];

        // Actualizar el usuario en la base de datos
        $Usuario->update($idUsuarioEditar, $data);

        // Respuesta de éxito
        $response = ['message' => "El status del usuario ha cambiado exitosamente"];
        return $this->response->setStatusCode(200)->setJSON($response);

    } catch (\Exception $e) {
        // Manejo de errores
        $errorResponse = ['message' => 'Error al cambiar el status del usuario', 'error' => $e->getMessage()];
        return $this->response->setStatusCode(500)->setJSON($errorResponse);
    }
}
*/
	// retorna los usuarios de la base. TODO: Necesita una medida de seguridad adicional
	function ObtenerUsuarios()
{
		$Usuario=new Usuario();
		$WebToken=new WebToken();
		$jwt = $WebToken->decodificarJWT($this->request->getPost("jwt"));
		$idUsuario = $jwt->idUsuario;
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Usuario->getUsuarios($page, $pageSize));
	}
	function ObtenerUsuariosNumeros()
	{
		$Usuario=new Usuario();
		$WebToken=new WebToken();
		$jwt = $WebToken->decodificarJWT($this->request->getPost("jwt"));
		$idUsuario = $jwt->idUsuario;
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Usuario->getUsuariosNumero($page, $pageSize));
	}



	function getPerfiles()
	{
		$Usuario=new Usuario();
		echo json_encode($Usuario->getPerfiles());
	}
	function getDepartamentos()
	{
		$Usuario=new Usuario();
		echo json_encode($Usuario->getDepartamentos());
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
}
