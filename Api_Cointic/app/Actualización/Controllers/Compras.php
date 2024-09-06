<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Compra;
use App\Models\Proyeccion;
use App\Models\WebToken;
class Compras extends ResourceController
{
    function obtenerCompras() {
        $Compra=new Compra();
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
		echo json_encode($Compra->getCompras($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial,$fechaFinal));
	}
    function obtenerNumeroCompras() {
        $Compra=new Compra();
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
		echo json_encode($Compra->getNumeroCompras($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial,$fechaFinal));
	}
    function create() {
        $Compra=new Compra();
		$Proyeccion=new Proyeccion();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
		$idGasolinera = $jwt->idGasolinera;
		$FechaCompra = $this->request->getPost("FechaCompra"); 
        $TipoMovimiento = $this->request->getPost("TipoMovimiento");
        $idTipoGasolina = $this->request->getPost("idTipoGasolina");
		if($idGasolinera==0){
        $idGasolinera = $this->request->getPost("idGasolinera");
		}
        $cantidad = $this->request->getPost("cantidad");
        $data=[
			'TipoMovimiento'=>$TipoMovimiento,
            'idTipoGasolina'=>$idTipoGasolina,
            'Fechacompra'=>$FechaCompra,
            'idGasolinera'=>$idGasolinera,
            'cantidad'=>$cantidad
		];
		$Compra->insert($data);

		$ProyeccionOld=$Proyeccion->selectProyeccionxFecha($FechaCompra);
		if($idTipoGasolina==1){
			$com=[
				'proyeccionCompraSuper'=>$cantidad,
			];
			$Proyeccion->actualizarProyeccion($ProyeccionOld['idProyeccion'],$com);
			$proyeccionActual=$Proyeccion->selectProyeccionxidProyeccion($ProyeccionOld['idProyeccion']);
			$proyeccionesBase=$Proyeccion->getProyeccionessiguientes($ProyeccionOld['idProyeccion'],$ProyeccionOld['consecutivo']);
			$inventario  = $ProyeccionOld['proyeccionInventarioSuper'];
			print_r($inventario);
		// 	for ($i = 0; $i < sizeof($proyeccionesBase); $i ++) {
		// 		$Proyecciones=[
		// 			'proyeccionInventarioSuper'=>$inventario,
		// 			'proyeccionVentaSuper'=>$proyeccionesBase[$i]['proyeccionVentaSuper'],
		// 			'proyeccionCompraSuper'=>$proyeccionesBase[$i]['proyeccionCompraSuper']
		// 		];
		// 		$Proyeccion->actualizarProyeccion($proyeccionesBase[$i]['idProyeccion'],$Proyecciones);
		// 		$inventario=$inventario-$proyeccionesBase[$i]['proyeccionVentaSuper'];
		// 		$inventario=$inventario+$proyeccionesBase[$i]['proyeccionCompraSuper'];
		// 	}
		}
		if($idTipoGasolina==2){
			$com=[
				'proyeccionCompraPremium'=>$cantidad
			];
			$Proyeccion->actualizarProyeccion($ProyeccionOld['idProyeccion'],$com);
			$proyeccionActual=$Proyeccion->selectProyeccionxidProyeccion($ProyeccionOld['idProyeccion']);
			$proyeccionesBase=$Proyeccion->getProyeccionessiguientes($ProyeccionOld['idProyeccion'],$ProyeccionOld['consecutivo']);
			$inventario  = $proyeccionActual['proyeccionInventarioPremium'];
			for ($i = 0; $i < sizeof($proyeccionesBase); $i ++) {
				$Proyecciones=[
					'proyeccionInventarioPremium'=>$inventario,
					'proyeccionVentaPremium'=>$proyeccionesBase[$i]['proyeccionVentaPremium'],
					'proyeccionCompraPremium'=>$proyeccionesBase[$i]['proyeccionCompraPremium']
				];
				$Proyeccion->actualizarProyeccion($proyeccionesBase[$i]['idProyeccion'],$Proyecciones);
				$inventario=$inventario-$proyeccionesBase[$i]['proyeccionVentaPremium'];
				$inventario=$inventario+$proyeccionesBase[$i]['proyeccionCompraPremium'];
			}
		}
		if($idTipoGasolina==3){
			$com=[
				'proyeccionCompraDiesel'=>$cantidad
			];
			$Proyeccion->actualizarProyeccion($ProyeccionOld['idProyeccion'],$com);
			
			$proyeccionActual=$Proyeccion->selectProyeccionxidProyeccion($ProyeccionOld['idProyeccion']);
			$proyeccionesBase=$Proyeccion->getProyeccionessiguientes($ProyeccionOld['idProyeccion'],$ProyeccionOld['consecutivo']);
			$inventario  = $proyeccionActual['proyeccionInventarioDiesel'];
			for ($i = 0; $i < sizeof($proyeccionesBase); $i ++) {
				$Proyecciones=[
					'proyeccionInventarioDiesel'=>$inventario,
					'proyeccionVentaDiesel'=>$proyeccionesBase[$i]['proyeccionVentaDiesel'],
					'proyeccionCompraDiesel'=>$proyeccionesBase[$i]['proyeccionCompraDiesel']
				];
				$Proyeccion->actualizarProyeccion($proyeccionesBase[$i]['idProyeccion'],$Proyecciones);
				$inventario=$inventario-$proyeccionesBase[$i]['proyeccionVentaDiesel'];
				$inventario=$inventario+$proyeccionesBase[$i]['proyeccionCompraDiesel']; 
			}
		}
		
		// echo json_encode([
		// 	'message' => "Se ha registrado la compra correctamente"
		// ]);
	}
    function update($idCompra = null) {
        $Compra=new Compra();
		$Proyeccion=new Proyeccion();
		$WebToken=new WebToken();
		$jwt = $this->request->getRawInputVar("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idGasolinera = $jwt->idGasolinera;
		$FechaCompra = $this->request->getRawInputVar("FechaCompra"); 
        $TipoMovimiento = $this->request->getRawInputVar("TipoMovimiento");
        $idTipoGasolina = $this->request->getRawInputVar("idTipoGasolina");
		if($idGasolinera==0){
			$idGasolinera = $this->request->getRawInputVar("idGasolinera");
		}
        $cantidad = $this->request->getRawInputVar("cantidad");
        $data=[
			'TipoMovimiento'=>$TipoMovimiento,
            'idTipoGasolina'=>$idTipoGasolina,
			'Fechacompra'=>$FechaCompra,
            'idGasolinera'=>$idGasolinera,
            'cantidad'=>$cantidad
		];
		$Compra->actualizarCompra($idCompra,$data);
		
		$ProyeccionOld=$Proyeccion->selectProyeccionxFecha($FechaCompra);
		if($idTipoGasolina==1){
			$com=[
				'proyeccionCompraSuper'=>$cantidad,
			];
			$Proyeccion->actualizarProyeccion($ProyeccionOld['idProyeccion'],$com);
			$inventario  = $ProyeccionOld['proyeccionInventarioSuper'];
			$proyeccionActual=$Proyeccion->selectProyeccionxidProyeccion($ProyeccionOld['idProyeccion']);
			$proyeccionesBase=$Proyeccion->getProyeccionessiguientes($ProyeccionOld['idProyeccion'],$ProyeccionOld['consecutivo']);
			for ($i = 0; $i < sizeof($proyeccionesBase); $i ++) {
				$Proyecciones=[
					'proyeccionInventarioSuper'=>$inventario,
					'proyeccionVentaSuper'=>$proyeccionesBase[$i]['proyeccionVentaSuper'],
					'proyeccionCompraSuper'=>$proyeccionesBase[$i]['proyeccionCompraSuper']
				];
				$Proyeccion->actualizarProyeccion($proyeccionesBase[$i]['idProyeccion'],$Proyecciones);
				$inventario=$inventario-$proyeccionesBase[$i]['proyeccionVentaSuper'];
				$inventario=$inventario+$proyeccionesBase[$i]['proyeccionCompraSuper'];
			}
		}
		if($idTipoGasolina==2){
			$com=[
				'proyeccionCompraPremium'=>$cantidad
			];
			$Proyeccion->actualizarProyeccion($ProyeccionOld['idProyeccion'],$com);
			$inventario  = $Proyeccion['proyeccionInventarioPremium'];
			$proyeccionActual=$Proyeccion->selectProyeccionxidProyeccion($ProyeccionOld['idProyeccion']);
			$proyeccionesBase=$Proyeccion->getProyeccionessiguientes($ProyeccionOld['idProyeccion'],$ProyeccionOld['consecutivo']);
			for ($i = 0; $i < sizeof($proyeccionesBase); $i ++) {
				$Proyecciones=[
					'proyeccionInventarioPremium'=>$inventario,
					'proyeccionVentaPremium'=>$proyeccionesBase[$i]['proyeccionVentaPremium'],
					'proyeccionCompraPremium'=>$proyeccionesBase[$i]['proyeccionCompraPremium']
				];
				$Proyeccion->actualizarProyeccion($proyeccionesBase[$i]['idProyeccion'],$Proyecciones);
				$inventario=$inventario-$proyeccionesBase[$i]['proyeccionVentaPremium'];
				$inventario=$inventario+$proyeccionesBase[$i]['proyeccionCompraPremium'];
			}
		}
		if($idTipoGasolina==3){
			$com=[
				'proyeccionCompraDiesel'=>$cantidad
			];
			$Proyeccion->actualizarProyeccion($ProyeccionOld['idProyeccion'],$com);
			$inventario  = $ProyeccionOld['proyeccionInventarioDiesel'];
			$proyeccionActual=$Proyeccion->selectProyeccionxidProyeccion($ProyeccionOld['idProyeccion']);
			$proyeccionesBase=$Proyeccion->getProyeccionessiguientes($ProyeccionOld['idProyeccion'],$ProyeccionOld['consecutivo']);
			for ($i = 0; $i < sizeof($proyeccionesBase); $i ++) {
				$Proyecciones=[
					'proyeccionInventarioDiesel'=>$inventario,
					'proyeccionVentaDiesel'=>$proyeccionesBase[$i]['proyeccionVentaDiesel'],
					'proyeccionCompraDiesel'=>$proyeccionesBase[$i]['proyeccionCompraDiesel']
				];
				$Proyeccion->actualizarProyeccion($proyeccionesBase[$i]['idProyeccion'],$Proyecciones);
				$inventario=$inventario-$proyeccionesBase[$i]['proyeccionVentaDiesel'];
				$inventario=$inventario+$proyeccionesBase[$i]['proyeccionCompraDiesel']; 
			}
		}
		echo json_encode([
			'message' => "Se ha actualizado la compra"
		]);
	}
    function deleteCompra($idCompra = null) {
        $Compra=new Compra();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idCompra = $this->request->getPost("idCompra"); 
		$Compra->deleteCompra($idCompra);
		echo json_encode([
			'message' => "Se ha eliminado la compra"
		]);
	}
    function obtenerCompraxId() {
        $Compra=new Compra();
		$idCompra = $this->request->getPost("idCompra"); 
		echo json_encode($Compra->selectCompraxid($idCompra));
	}
}?>