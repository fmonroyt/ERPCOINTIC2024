<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Marca;
use App\Models\WebToken;
class Marcas extends ResourceController
{
    function obtenerMarcas() {
        $Marca=new Marca();
		// $WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Marca->getMarcas($page, $pageSize, $sort, $order));
	}
	function obtenerNumeroMarcas() {
        $Marca=new Marca();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Marca->getNumeroMarcas($page, $pageSize, $sort, $order));
	}

    function CrearMarca() {
        $Marca=new Marca();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
        $Nombre = $this->request->getPost("Nombre");
        $data=[
			'Nombre'=>$Nombre ,
		];
		$Marca->insert($data);
		echo json_encode([
			'message' => "Se ha dado de alta la Marca"
		]);
	}
    function ActualizarMarca() {
        $Marca=new Marca();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idMarcas = $this->request->getPost("idMarcas"); 
        $Nombre = $this->request->getPost("Nombre");
        $data=[
			'Nombre'=>$Nombre,
		];
		$Marca->actualizarMarcas($idMarcas,$data);
		
		echo json_encode([
			'message' => "Se ha actualizado la marca"
		]);
	}

    function EliminarMarca() {
        $Marca=new Marca();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idMarcas = $this->request->getPost("idMarcas"); 
		$Marca->deleteMarcas($idMarcas);
		echo json_encode([
			'message' => "Se ha eliminado la marca"
		]);
	}

    function obtenerMarcaxId() {
        $Marca=new Marca();
		$idMarcas = $this->request->getPost("idMarcas"); 
		echo json_encode($Marca->selectMarcasxid($idMarcas));
	}
}
?>