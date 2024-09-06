<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Perfil;
use App\Models\WebToken;
class Perfiles extends ResourceController
{
	function obtenerPerfiles() {
        $Perfil=new Perfil();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Perfil->getPerfiles($page, $pageSize, $sort, $order));
	}
	function obtenerNumeroPerfiles() {
        $Perfil=new Perfil();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Perfil->getNumeroPerfiles($page, $pageSize, $sort, $order));
	}

    function CrearPerfil() {
        $Perfil=new Perfil();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
		$nombrePerfil = $this->request->getPost("nombrePerfil");
        $data=[
			'nombrePerfil'=>$nombrePerfil,
		];
		$Perfil->insert($data);
		echo json_encode([
			'message' => "Se ha dado de alta el perfil"
		]);
	}

    function ActualizarPerfil() {
        $Perfil=new Perfil();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idPerfil = $this->request->getPost("idPerfil");
		$nombrePerfil = $this->request->getPost("nombrePerfil");
		$data=[
			'nombrePerfil' => $nombrePerfil
		];
		$Perfil->ActualizarPerfil($idPerfil,$data);
		
		echo json_encode([
			'message' => "Se ha actualizado el perfil"
		]);
	}
    function EliminarPerfil() {
        $Perfil=new Perfil();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idPerfil = $this->request->getPost("idPerfil"); 
		$Perfil->deleterPerfil($idPerfil);
		echo json_encode([
			'message' => "Se ha eliminado el perfil"
		]);
	}
    function obtenerPerfilxId() {
        $Perfil=new Perfil();
		$idPerfil = $this->request->getPost("idPerfil"); 
		echo json_encode($Perfil->selectPerfilxid($idPerfil));
	}

    function obtenerModulos() {
        $Perfil=new Perfil();
		echo json_encode($Perfil->selectModulos());
	}
}
?>