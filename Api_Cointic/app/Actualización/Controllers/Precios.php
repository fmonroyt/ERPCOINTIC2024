<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Precio;
use App\Models\WebToken;
class Precios extends ResourceController
{
    function obtenerPrecios() {
        $Precio=new Precio();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idGasolinera = $jwt->idGasolinera;
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		if($idGasolinera==0 || $idGasolinera!=null){
            $idGasolinera = $this->request->getPost("idGasolinera");
		}
		$fechaInicial = $this->request->getPost("fechaInicial");
        $fechaFinal = $this->request->getPost("fechaFinal");
		echo json_encode($Precio->getPrecios($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial,$fechaFinal));
	}
    function obtenerNumeroPrecios() {
        $Precio=new Precio();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idGasolinera = $jwt->idGasolinera;
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		if($idGasolinera==0 || $idGasolinera!=null){
            $idGasolinera = $this->request->getPost("idGasolinera");
		}
		$fechaInicial = $this->request->getPost("fechaInicial");
        $fechaFinal = $this->request->getPost("fechaFinal");
		echo json_encode($Precio->getNumeroPrecios($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial,$fechaFinal));
	}
    function create() {
        $Precio=new Precio();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idGasolinera = $jwt->idGasolinera;
		$Precios=json_decode($this->request->getPost('Precios'),true);
		$Contador=$Precio->getPreciosContador();
		$fecha=date('Y-m-d');
		if($idGasolinera==0 ||$idGasolinera!=null){
			$idGasolinera = $this->request->getPost("idGasolinera");
			}
		$contadorsiguiente=$Contador['Consecutivo']+1;
		foreach($Precios as $Precioi){
			$idGasolinera=$Precioi['_idGasolinera'];
			$PrecioMagna=$Precioi['_PrecioMagna'];
			$PrecioPremium=$Precioi['_PrecioPremium'];
			$PrecioDiesel=$Precioi['_PrecioDiesel'];
			$data = [
				"Consecutivo " => $contadorsiguiente,
				"Fecha"=>$fecha,
				"idGasolinera"=>$idGasolinera,
				"PrecioMagna"=>$PrecioMagna,
				"PrecioPremium"=>$PrecioPremium,
				"PrecioDiesel"=>$PrecioDiesel
			];
			$Precio->insert($data);
		}
		echo json_encode([
			'message' => "Se han registrado los precios correctamente"
		]);
	}
    function update($Consecutivo = null) {
        $Precio=new Precio();
		$WebToken=new WebToken();
		$jwt = $this->request->getRawInputVar("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
		$Precios=json_decode($this->request->getRawInputVar('Precios'),true);
		foreach($Precios as $Precioi){
			$idHistoricoPrecios=$Precioi['_idHistoricoPrecios'];
			$idGasolinera=$Precioi['_idGasolinera'];
			$PrecioMagna=$Precioi['_PrecioMagna'];
			$PrecioPremium=$Precioi['_PrecioPremium'];
			$PrecioDiesel=$Precioi['_PrecioDiesel'];
			$data = [
				"idGasolinera"=>$idGasolinera,
				"PrecioMagna"=>$PrecioMagna,
				"PrecioPremium"=>$PrecioPremium,
				"PrecioDiesel"=>$PrecioDiesel
			];
			$Precio->actualizarPrecio($idHistoricoPrecios,$data);
		}
		echo json_encode([
			'message' => "Se han actualizado los precios correctamente"
		]);
	}
	function AltaPreciosPropiosM() {
        $Precio=new Precio();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idGasolinera = $jwt->idGasolinera;
		$fecha=date('Y-m-d');
		// $Precios=json_decode($this->request->post('Precios'),true);
		$PrecioSuper=$this->request->getPost('PrecioSuper');
		$PrecioPremium=$this->request->getPost('PrecioPremium');
		$PrecioDiesel=$this->request->getPost('PrecioDiesel');
		$Contador=$Precio->getPreciosContador();
		$contadorsiguiente=$Contador['Consecutivo']+1;
		if($idGasolinera==0){
            $idGasolinera = $this->request->getPost("idGasolinera");
		}
			$data = [
				"Consecutivo " => $contadorsiguiente,
				"Fecha"=>$fecha,
				"idGasolinera"=>$idGasolinera,
				"PrecioMagna"=>$PrecioSuper,
				"PrecioPremium"=>$PrecioPremium,
				"PrecioDiesel"=>$PrecioDiesel
			];
			$Precio->insert($data);
		
		echo json_encode([
			'message' => "Se han registrado los precios correctamente"
		]);
	}
	function AltaPreciosCompetenciaM() {
        $Precio=new Precio();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idGasolinera = $jwt->idGasolinera;
		if($idGasolinera==0){
            $idGasolinera = $this->request->getPost("idGasolinera");
		}
		$Precios=json_decode($this->request->getPost('Precios'),true);
		$GasolineraPropia=$Precio->selectUltimosPreciosxGasolinera($idGasolinera);
		$Contador=$Precio->getPreciosContador();
		$contadorsiguiente=$Contador['Consecutivo'];
		foreach($Precios as $Precioi){
			$idGasolinera=$Precioi['_idGasolinera'];
			$PrecioMagna=$Precioi['_PrecioMagna'];
			$PrecioPremium=$Precioi['_PrecioPremium'];
			$PrecioDiesel=$Precioi['_PrecioDiesel'];
			$data = [
				"Consecutivo" => $GasolineraPropia['Consecutivo'],
				"Fecha"=>date('d-m-Y'),
				"idGasolinera"=>$idGasolinera,
				"PrecioMagna"=>$PrecioMagna,
				"PrecioPremium"=>$PrecioPremium,
				"PrecioDiesel"=>$PrecioDiesel
			];
			$Precio->insert($data);
		}
		echo json_encode([
			'message' => "Se han registrado los precios correctamente"
		]);
	}
	function ActualizarPreciosPropiosM() {
		$Precio=new Precio();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idGasolinera = $jwt->idGasolinera;
		$idHistoricoPrecios=$this->request->getPost('idHistoricoPrecios');
		$PrecioSuper=$this->request->getPost('PrecioSuper');
		$PrecioPremium=$this->request->getPost('PrecioPremium');
		$PrecioDiesel=$this->request->getPost('PrecioDiesel');

			$data = [
				"PrecioMagna"=>$PrecioSuper,
				"PrecioPremium"=>$PrecioPremium,
				"PrecioDiesel"=>$PrecioDiesel
			];
		$Precio->actualizarPrecio($idHistoricoPrecios,$data);
		
		echo json_encode([
			'message' => "Se han actualizado los precios correctamente"
		]);
	}
	function ActualizarPreciosCompetenciaM() {
		$Precio=new Precio();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idGasolinera = $jwt->idGasolinera;
		$Precios=json_decode($this->request->getPost('Precios'),true);
		foreach($Precios as $Precioi){
			$idHistoricoPrecios=$Precioi['_idHistoricoPrecios'];
			$PrecioMagna=$Precioi['_PrecioMagna'];
			$PrecioPremium=$Precioi['_PrecioPremium'];
			$PrecioDiesel=$Precioi['_PrecioDiesel'];
			$data = [
				"PrecioMagna"=>$PrecioMagna,
				"PrecioPremium"=>$PrecioPremium,
				"PrecioDiesel"=>$PrecioDiesel
			];
			$Precio->actualizarPrecio($idHistoricoPrecios,$data);
		}
		echo json_encode([
			'message' => "Se han actualizado los precios correctamente"
		]);
	}

	function deletePrecio() {
        $Precio=new Precio();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$Consecutivo = $this->request->getPost("Consecutivo"); 
		$Precio->deletePrecio($Consecutivo);
		echo json_encode([
			'message' => "Se han eliminado los precios"
		]);
	}
    function obtenerPrecioxConsecutivo() {
        $Precio=new Precio();
		$Consecutivo = $this->request->getPost("Consecutivo"); 
		echo json_encode($Precio->selectPreciosxConsecutivo($Consecutivo));
	}

	function obtenerGasolinerasCompetenciaxId() {
        $Precio=new Precio();
		$idGasolinera = $this->request->getPost("idGasolinera"); 
		echo json_encode($Precio->selectGasolineraCompetenciasxid($idGasolinera));
	}
}?>