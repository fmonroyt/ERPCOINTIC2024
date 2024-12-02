<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Solucion;
use App\Models\WebToken;
class Soluciones extends ResourceController
{
    function obtenerSoluciones() {
        $Solucion=new Solucion();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Solucion->getSoluciones($page, $pageSize, $sort, $order));
	}
	function obtenerNumeroSoluciones() {
        $Solucion=new Solucion();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Solucion->getNumeroSoluciones($page, $pageSize, $sort, $order));
	}

    function CrearSolucion() {
        $Solucion=new Solucion();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
        $nombre = $this->request->getPost("nombre");
        $data=[
			'nombre'=>$nombre ,
		];
		$Solucion->insert($data);
		echo json_encode([
			'message' => "Se ha dado de alta la Solucion"
		]);
	}
    function ActualizarSolucion() {
        $Solucion=new Solucion();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idsoluciones = $this->request->getPost("idsoluciones"); 
        $nombre = $this->request->getPost("nombre");
        $data=[
			'nombre'=>$nombre,
		];
		$Solucion->actualizarSoluciones($idsoluciones,$data);
		
		echo json_encode([
			'message' => "Se ha actualizado la solucion"
		]);
	}

    function EliminarSolucion() {
        $Solucion=new Solucion();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idsoluciones = $this->request->getPost('idsoluciones');
		$Solucion->deleteSoluciones($idsoluciones);
		echo json_encode([
			'message$idsoluciones = $this->request->getPost("idsoluciones"); ' => "Se ha eliminado la Solucion"
		]);
	}

    function obtenerSolucionxId() {
        $Solucion=new Solucion();
		$idsoluciones = $this->request->getPost("idsoluciones"); 
		echo json_encode($Solucion->selectSolucionesxid($idsoluciones));
	}

	function ObtenerMarcas() {
        $Solucion=new Solucion();
		echo json_encode($Solucion->selectMarcas());
	}
	function ObtenerMarcasAsignadas() {
        $Solucion=new Solucion();
		$idsoluciones = $this->request->getPost("idsoluciones"); 
		echo json_encode($Solucion->selectMarcasxIdSolucion($idsoluciones));
	}
	function AsignarMarcas() {
        $Solucion=new Solucion();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idsoluciones = $this->request->getPost("idsoluciones"); 
		$Marcas = explode("," , $this->request->getPost("Marcas"));
		$MarcasAsignadas=$Solucion->selectMarcasIdxIdSolucion($idsoluciones);
		$Solucion->desactivarMarcas($idsoluciones);
		$marcasAs=[];
		foreach($MarcasAsignadas as $marcaas) {
			array_push($marcasAs,$marcaas['idmarca']);
        } 
		foreach($Marcas as $marca) {
			if(in_array($marca,$marcasAs)){

				$Solucion->activarMarcas($idsoluciones,$marca);
			}else{
				$data=["idsoluciones"=>$idsoluciones,
				"idMarca"=>$marca,
				"status"=>0];
				$Solucion->crearRelMarcas($data);
			}
        } 
		
		echo json_encode([
			'message' => "Se han asignado las marcas correctamente"
		]);


	}
}
?>