<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Usuario;
use App\Models\WebToken;
use App\Models\Archivo;
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
				'idGasolinera' => $autenticado['idGasolinera']
			));
			http_response_code(200);
			echo json_encode(array(
				'message' => "Usuario autenticado exitosamente.",
				'jwt' => $jwt
			));
		} else {
			http_response_code(403);
			echo json_encode(array(
				 'message' => "Credenciales de usuario no válidas"
				// 'message' => $autenticado
			));
		}
		// echo json_encode(array(
		// 			 'message' => "Credenciales de usuario no válidas"
			
		// 		));
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

	function Usuarioxid()
	{
		$Usuario=new Usuario();
		$idUsuario = $this->request->getPost('idUsuario');

		echo json_encode($Usuario->getUsuario($idUsuario));
			
	}

	function create(){
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
		$idGasolinera=$this->request->getPost('idGasolinera');
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
			'correoDestino'=>$correoDestino ,
			'idGasolinera'=>$idGasolinera,
			'idPerfil'=>$idPerfil,
			'Status'=> 0,
			'fotoUsuario'=>$archivo['directorio'],
		];
		$Usuario->insert($data);
		
		echo json_encode([
			'message' => "El usuario ha sido creado correctamente"
		]);
	}
	
	function update($id = null){

		$Usuario=new Usuario();
		$WebToken=new WebToken();
		$Archivo=new Archivo();
		$jwt=$this->request->getRawInputVar('jwt');
		// $jwt = $formdata['jwt'];

		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		// $idUsuarioEditar=$this->request->getPost('idUsuarioEditar');
		$nombreUsuario=$this->request->getRawInputVar('nombreUsuario');
		$apellidoPaterno=$this->request->getRawInputVar('apellidoPaterno');
		$apellidoMaterno=$this->request->getRawInputVar('apellidoMaterno');
		$nickName=$this->request->getRawInputVar('nickName');
		$pass=$this->request->getRawInputVar('password');
		if($pass){
			$hashed_password = password_hash($pass, PASSWORD_DEFAULT);
		}
		$correoDestino=$this->request->getRawInputVar('correoDestino');
		$idGasolinera=$this->request->getRawInputVar('idGasolinera');
		$idPerfil=$this->request->getRawInputVar('idPerfil');
		
	
			$data=[
				'nombreUsuario'=>$nombreUsuario,
				'apellidoPaterno'=>$apellidoPaterno,
				'apellidoMaterno'=>$apellidoMaterno,
				'nickName'=>$nickName,
				'correoDestino'=>$correoDestino ,
				'idGasolinera'=>$idGasolinera,
				'idPerfil'=>$idPerfil,
				'Status'=> 0
			];
			$Usuario->updateUsuario($id,$data);

		
		if($pass){
			$data=[
			'passwordUsuario'=>$hashed_password,
			];
			$Usuario->updateUsuario($id,$data);	
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

	function CambiarStatusUsuarios(){
		$Usuario=new Usuario();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idUsuarioEditar=$this->request->getPost('idUsuario');
		$newstatus=$this->request->getPost('newstatus');
			$data=[
			'Status'=>$newstatus,
			];
			$Usuario->update($idUsuarioEditar,$data);	
		echo json_encode([
			'message' => "El status del usuario ha cambiado exitosamente"
		]);
	}

	// retorna los usuarios de la base. TODO: Necesita una medida de seguridad adicional
	function ObtenerUsuarios()
	{
		$Usuario=new Usuario();
		$WebToken=new WebToken();
		$jwt = $WebToken->decodificarJWT($this->request->getPost("jwt"));
		$idUsuario = $jwt->idUsuario;
		$idTipoUsuario=$this->request->getPost("idTipoUsuario");
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Usuario->getUsuarios($idTipoUsuario,$page, $pageSize, $sort, $order));
	}
	function ObtenerUsuariosNumeros()
	{
		$Usuario=new Usuario();
		$WebToken=new WebToken();
		$jwt = $WebToken->decodificarJWT($this->request->getPost("jwt"));
		$idUsuario = $jwt->idUsuario;
		$idTipoUsuario=$this->request->getPost("idTipoUsuario");
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Usuario->getUsuariosNumero($idTipoUsuario,$page, $pageSize, $sort, $order));
	}

	function CatGasolineras()
	{
		$Usuario=new Usuario();
		echo json_encode($Usuario->getCatGasolineras());
	}

	function Perfiles()
	{
		$Usuario=new Usuario();
		echo json_encode($Usuario->getPerfiles());
	}
}
