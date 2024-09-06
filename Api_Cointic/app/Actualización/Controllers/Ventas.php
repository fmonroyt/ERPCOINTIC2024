<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Venta;
use App\Models\Proyeccion;
use App\Models\WebToken;

class Ventas extends ResourceController
{
    function obtenerVentas() {
        $Venta=new Venta();
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
		echo json_encode($Venta->getVentas($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial,$fechaFinal));
	}
    function obtenerNumeroVentas() {
        $Venta=new Venta();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idGasolinera = $jwt->idGasolinera;
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		if($idGasolinera==0){
            $idGasolinera = $this->request->getPost("idGasolinera");
		}
		$fechaInicial = $this->request->getPost("fechaInicial");
        $fechaFinal = $this->request->getPost("fechaFinal");
		echo json_encode($Venta->getNumeroVentas($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial,$fechaFinal));
	}
    function create() {
        $Venta=new Venta();
		$Proyeccion=new Proyeccion();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idGasolinera = $jwt->idGasolinera;
		if($idGasolinera==0||$idGasolinera!=null){
			$idGasolinera = $this->request->getPost("idGasolinera");
		}
		$FechaVenta = $this->request->getPost("FechaVenta"); 
        $cantidadSuper = $this->request->getPost("cantidadSuper");
        $cantidadPremium = $this->request->getPost("cantidadPremium");
        $cantidadDiesel = $this->request->getPost("cantidadDiesel");
        $data=[
            'FechaVenta'=>$FechaVenta,
			'idGasolinera'=>$idGasolinera,
            'cantidadSuper'=>$cantidadSuper,
            'cantidadPremium'=>$cantidadPremium,
            'cantidadDiesel'=>$cantidadDiesel
		];
		$Venta->insert($data);
		$ProyeccionOld=$Proyeccion->selectProyeccionxFecha($FechaVenta);
		$com=[
			'proyeccionVentaSuper'=>$cantidadSuper,
			'proyeccionVentaPremium'=>$cantidadPremium,
			'proyeccionVentaDiesel'=>$cantidadDiesel
		];
		$Proyeccion->actualizarProyeccion($ProyeccionOld['idProyeccion'],$com);
		$inventarioSuper  = $ProyeccionOld['proyeccionInventarioSuper'];
		$inventarioPremium  = $ProyeccionOld['proyeccionInventarioPremium'];
		$inventarioDiesel  = $ProyeccionOld['proyeccionInventarioDiesel'];
		$proyeccionActual=$Proyeccion->selectProyeccionxidProyeccion($ProyeccionOld['idProyeccion']);
		$proyeccionesBase=$Proyeccion->getProyeccionessiguientes($ProyeccionOld['idProyeccion'],$ProyeccionOld['consecutivo']);
		for ($i = 0; $i < sizeof($proyeccionesBase); $i ++) {
			$Proyecciones=[
				'proyeccionInventarioSuper'=>$inventarioSuper,
				'proyeccionVentaSuper'=>$proyeccionesBase[$i]['proyeccionVentaSuper'],
				'proyeccionCompraSuper'=>$proyeccionesBase[$i]['proyeccionCompraSuper'],
				'proyeccionInventarioPremium'=>$inventarioPremium,
				'proyeccionVentaPremium'=>$proyeccionesBase[$i]['proyeccionVentaPremium'],
				'proyeccionCompraPremium'=>$proyeccionesBase[$i]['proyeccionCompraPremium'],
				'proyeccionInventarioDiesel'=>$inventarioDiesel,
				'proyeccionVentaDiesel'=>$proyeccionesBase[$i]['proyeccionVentaDiesel'],
				'proyeccionCompraDiesel'=>$proyeccionesBase[$i]['proyeccionCompraDiesel']
			];
			$Proyeccion->actualizarProyeccion($proyeccionesBase[$i]['idProyeccion'],$Proyecciones);
			$inventarioSuper=$inventarioSuper-$proyeccionesBase[$i]['proyeccionVentaSuper'];
			$inventarioSuper=$inventarioSuper+$proyeccionesBase[$i]['proyeccionCompraSuper'];
			$inventarioPremium=$inventarioPremium-$proyeccionesBase[$i]['proyeccionVentaPremium'];
			$inventarioPremium=$inventarioPremium+$proyeccionesBase[$i]['proyeccionCompraPremium'];
			$inventarioDiesel=$inventarioDiesel-$proyeccionesBase[$i]['proyeccionVentaDiesel'];
			$inventarioDiesel=$inventarioDiesel+$proyeccionesBase[$i]['proyeccionCompraDiesel']; 
		}
		echo json_encode([
			'message' => "Se ha registrado la venta correctamente"
		]);
	}
    function update($idVenta = null) {
        $Venta=new Venta();
		$Proyeccion=new Proyeccion();
		$WebToken=new WebToken();
		$jwt = $this->request->getRawInputVar("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idGasolinera = $jwt->idGasolinera;
		if($idGasolinera==0){
        $idGasolinera = $this->request->getRawInputVar("idGasolinera");
		}
		$FechaVenta = $this->request->getRawInputVar("FechaVenta");
        $cantidadSuper = $this->request->getRawInputVar("cantidadSuper");
        $cantidadPremium = $this->request->getRawInputVar("cantidadPremium");
        $cantidadDiesel = $this->request->getRawInputVar("cantidadDiesel");
        $data=[
			'FechaVenta'=>$FechaVenta,
			'idGasolinera'=>$idGasolinera,
            'cantidadSuper'=>$cantidadSuper,
            'cantidadPremium'=>$cantidadPremium,
            'cantidadDiesel'=>$cantidadDiesel
		];
		$Venta->actualizarVenta($idVenta,$data);
		$ProyeccionOld=$Proyeccion->selectProyeccionxFecha($FechaVenta);
			$com=[
				'proyeccionVentaSuper'=>$cantidadSuper,
				'proyeccionVentaPremium'=>$cantidadPremium,
				'proyeccionVentaDiesel'=>$cantidadDiesel
			];
		$Proyeccion->actualizarProyeccion($ProyeccionOld['idProyeccion'],$com);
		$inventarioSuper  = $ProyeccionOld['proyeccionInventarioSuper'];
		$inventarioPremium  = $ProyeccionOld['proyeccionInventarioPremium'];
		$inventarioDiesel  = $ProyeccionOld['proyeccionInventarioDiesel'];
		$proyeccionActual=$Proyeccion->selectProyeccionxidProyeccion($ProyeccionOld['idProyeccion']);
		$proyeccionesBase=$Proyeccion->getProyeccionessiguientes($ProyeccionOld['idProyeccion'],$ProyeccionOld['consecutivo']);
		for ($i = 0; $i < sizeof($proyeccionesBase); $i ++) {
			$Proyecciones=[
				'proyeccionInventarioSuper'=>$inventarioSuper,
				'proyeccionVentaSuper'=>$proyeccionesBase[$i]['proyeccionVentaSuper'],
				'proyeccionCompraSuper'=>$proyeccionesBase[$i]['proyeccionCompraSuper'],
				'proyeccionInventarioPremium'=>$inventarioPremium,
				'proyeccionVentaPremium'=>$proyeccionesBase[$i]['proyeccionVentaPremium'],
				'proyeccionCompraPremium'=>$proyeccionesBase[$i]['proyeccionCompraPremium'],
				'proyeccionInventarioDiesel'=>$inventarioDiesel,
				'proyeccionVentaDiesel'=>$proyeccionesBase[$i]['proyeccionVentaDiesel'],
				'proyeccionCompraDiesel'=>$proyeccionesBase[$i]['proyeccionCompraDiesel']
			];
			$Proyeccion->actualizarProyeccion($proyeccionesBase[$i]['idProyeccion'],$Proyecciones);
			$inventarioSuper=$inventarioSuper-$proyeccionesBase[$i]['proyeccionVentaSuper'];
			$inventarioSuper=$inventarioSuper+$proyeccionesBase[$i]['proyeccionCompraSuper'];
			$inventarioPremium=$inventarioPremium-$proyeccionesBase[$i]['proyeccionVentaPremium'];
			$inventarioPremium=$inventarioPremium+$proyeccionesBase[$i]['proyeccionCompraPremium'];
			$inventarioDiesel=$inventarioDiesel-$proyeccionesBase[$i]['proyeccionVentaDiesel'];
			$inventarioDiesel=$inventarioDiesel+$proyeccionesBase[$i]['proyeccionCompraDiesel']; 
		}
		echo json_encode([
			'message' => "Se ha actualizado la venta"
		]);
	}
    function deleteVenta() {
        $Venta=new Venta();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idVenta = $this->request->getPost("idVenta"); 
		$Venta->deleteVenta($idVenta);
		echo json_encode([
			'message' => "Se ha eliminado la venta"
		]);
	}
    function obtenerVentaxId() {
        $Venta=new Venta();
		$idVenta = $this->request->getPost("idVenta"); 
		echo json_encode($Venta->selectVentaxid($idVenta));
	}

}?>