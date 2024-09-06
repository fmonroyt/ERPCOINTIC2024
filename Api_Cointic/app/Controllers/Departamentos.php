<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Departamento;
use App\Models\WebToken;
class Departamentos extends ResourceController
{
    function obtenerDepartamentos() {
        $Departamento=new Departamento();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Departamento->getDepartamentos($page, $pageSize, $sort, $order));
	}
	function obtenerNumeroDepartamentos() {
        $Departamento=new Departamento();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Departamento->getNumeroDepartamentos($page, $pageSize, $sort, $order));
	}

    function CrearDepartamento() {
        $Departamento=new Departamento();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
        $nombreDepartamento = $this->request->getPost("nombreDepartamento");
        $data=[
			'nombreDepartamento'=>$nombreDepartamento ,
		];
		$Departamento->insert($data);
		echo json_encode([
			'message' => "Se ha dado de alta el departamento"
		]);
	}
    function ActualizarDepartamento() {
        $Departamento=new Departamento();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idDepartamento = $this->request->getPost("idDepartamento"); 
        $nombreDepartamento = $this->request->getPost("nombreDepartamento");
        $data=[
			'nombreDepartamento'=>$nombreDepartamento,
		];
		$Departamento->actualizarDepartamentos($idDepartamento,$data);
		
		echo json_encode([
			'message' => "Se ha actualizado el departamento"
		]);
	}

    function EliminarDepartamento() {
        $Departamento=new Departamento();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idDepartamento = $this->request->getPost("idDepartamento"); 
		$Departamento->deleteDepartamentos($idDepartamento);
		echo json_encode([
			'message' => "Se ha eliminado el departamento"
		]);
	}

    function obtenerDepartamentoxId() {
        $Departamento=new Departamento();
		$idDepartamento = $this->request->getPost("idDepartamento"); 
		echo json_encode($Departamento->selectDepartamentosxid($idDepartamento));
	}
}
?>