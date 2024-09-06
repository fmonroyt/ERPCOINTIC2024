<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Gasolinera;
use App\Models\WebToken;
class Gasolineras extends ResourceController
{
    function obtenerGasolineras() {
        $Gasolinera=new Gasolinera();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Gasolinera->getGasolineras($page, $pageSize, $sort, $order));
	}
	function obtenerNumeroGasolineras() {
        $Gasolinera=new Gasolinera();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Gasolinera->getNumeroGasolineras($page, $pageSize, $sort, $order));
	}

    function create() {
        $Gasolinera=new Gasolinera();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
        $nombreGasolinera = $this->request->getPost("nombreGasolinera");
        $pertenencia = $this->request->getPost("pertenencia");
        $idGasolineraCompite = $this->request->getPost("idGasolineraCompite");
        $data=[
			'nombreGasolinera'=>$nombreGasolinera,
            'pertenencia'=>$pertenencia,
            'idGasolineraCompite'=>$idGasolineraCompite
		];
		$Gasolinera->insert($data);
		echo json_encode([
			'message' => "Se ha dado de alta la gasolinera"
		]);
	}
    function update($idGasolinera = null) {
        $Gasolinera=new Gasolinera();
		$WebToken=new WebToken();
		$jwt = $this->request->getRawInputVar("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
        $nombreGasolinera = $this->request->getRawInputVar("nombreGasolinera");
        $pertenencia = $this->request->getRawInputVar("pertenencia");
        $idGasolineraCompite = $this->request->getRawInputVar("idGasolineraCompite");
		// $idPerfil = $this->input->post("idPerfil"); 
        $data=[
			'nombreGasolinera'=>$nombreGasolinera,
            'pertenencia'=>$pertenencia,
            'idGasolineraCompite'=>$idGasolineraCompite
		];
		$Gasolinera->actualizarGasolinera($idGasolinera,$data);
		
		echo json_encode([
			'message' => "Se ha actualizado la gasolinera"
		]);
	}

    function deleteGasolinera() {
        $Gasolinera=new Gasolinera();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idGasolinera = $this->request->getPost("idGasolinera"); 
		$Gasolinera->deleteGasolinera($idGasolinera);
		echo json_encode([
			'message' => "Se ha eliminado la gasolinera"
		]);
	}

    function obtenerGasolineraxId() {
        $Gasolinera=new Gasolinera();
		$idGasolinera = $this->request->getPost("idGasolinera"); 
		echo json_encode($Gasolinera->selectGasolineraxid($idGasolinera));
	}
}
?>