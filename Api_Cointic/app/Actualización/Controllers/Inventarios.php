<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Inventario;
use App\Models\WebToken;
class Inventarios extends ResourceController
{
    function obtenerTotales() {
        $Inventario=new Inventario();
		$idGasolinera = $this->request->getPost("idGasolinera"); 
		$data=[
			"totalCompraSuper"=>$Inventario->selecttotalCompraSuper($idGasolinera)['cantidad'],
			"totalCompraPremium"=>$Inventario->selecttotalCompraPremium($idGasolinera)['cantidad'],
			"totalCompraDiesel"=>$Inventario->selecttotalCompraDiesel($idGasolinera)['cantidad'],
			"totalVentaSuper"=>$Inventario->selecttotalVentaSuper($idGasolinera)['cantidadSuper'],
			"totalVentaPremium"=>$Inventario->selecttotalVentaPremium($idGasolinera)['cantidadPremium'],
			"totalVentaDiesel"=>$Inventario->selecttotalVentaDiesel($idGasolinera)['cantidadDiesel']
		];
		echo json_encode($data);
	}

	function obtenerTotalesxfecha() {
        $Inventario=new Inventario();
		$idGasolinera = $this->request->getPost("idGasolinera");
		$fecha = $this->request->getPost("fecha");
		$fecha = date('Y-m-d', strtotime($fecha));
		$data=[
			"totalCompraSuper"=>$Inventario->selecttotalCompraSuperxfecha($idGasolinera,$fecha)['cantidad'],
			"totalCompraPremium"=>$Inventario->selecttotalCompraPremiumxfecha($idGasolinera,$fecha)['cantidad'],
			"totalCompraDiesel"=>$Inventario->selecttotalCompraDieselxfecha($idGasolinera,$fecha)['cantidad'],
			"totalVentaSuper"=>$Inventario->selecttotalVentaSuperxfecha($idGasolinera,$fecha)['cantidadSuper'],
			"totalVentaPremium"=>$Inventario->selecttotalVentaPremiumxfecha($idGasolinera,$fecha)['cantidadPremium'],
			"totalVentaDiesel"=>$Inventario->selecttotalVentaDieselxfecha($idGasolinera,$fecha)['cantidadDiesel']
		];
		echo json_encode($data);
	}
	function obtenerVentaxfecha() {
        $Inventario=new Inventario();
		$idGasolinera = $this->request->getPost("idGasolinera");
		$fecha = $this->request->getPost("fecha");
		$fecha = date('Y-m-d', strtotime($fecha)); 
		echo json_encode($Inventario->selectVentaxFecha($fecha,$idGasolinera));
	}
	function ObtenerCompraxFecha() {
        $Inventario=new Inventario();
		$idGasolinera = $this->request->getPost("idGasolinera");
		$fecha = $this->request->getPost("fecha");
		$fecha = date('Y-m-d', strtotime($fecha));
		$idTipoGasolina = $this->request->getPost("idGasolina");
		echo json_encode($Inventario->selectCompraxFecha($fecha,$idTipoGasolina,$idGasolinera));
	}
}
?>