<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Proyeccion;
use App\Models\Archivo;
use App\Models\WebToken;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
class Proyecciones extends ResourceController
{
    function obtenerProyecciones() {
        $Proyeccion=new Proyeccion();
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
		echo json_encode($Proyeccion->getProyecciones($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial,$fechaFinal));
	}
    function obtenerNumeroProyecciones() {
        $Proyeccion=new Proyeccion();
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
		echo json_encode($Proyeccion->getNumeroProyecciones($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial,$fechaFinal));
	}

	function create(){
		$Proyeccion=new Proyeccion();
		$Archivo=new Archivo();
		$WebToken=new WebToken();
		$jwt=$this->request->getPost('jwt');
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idGasolinera = $jwt->idGasolinera;
		if($idGasolinera==0){
            $idGasolinera = $this->request->getPost("idGasolinera");
		}
		$mes = $this->request->getPost("mes");
		$InventarioSuper = $this->request->getPost("InventarioSuper");
		$InventarioPremium = $this->request->getPost("InventarioPremium");
		$InventarioDiesel = $this->request->getPost("InventarioDiesel");
		$ProyeccionVentaSuper = $this->request->getPost("ProyeccionVentaSuper");
		$ProyeccionVentaPremium = $this->request->getPost("ProyeccionVentaPremium");
		$ProyeccionVentaDiesel = $this->request->getPost("ProyeccionVentaDiesel");
		$Contador=$Proyeccion->getProyeccionesContador();
		if($Contador){
			$Contador=$Contador['consecutivo']+1;
		}else{	
			$Contador=1;
		}
		$Inventario=[
				'idGasolinera'=>$idGasolinera,
				'InventarioInicialSuper'=>$InventarioSuper,
				'InventarioInicialPremium'=>$InventarioPremium,
				'InventarioInicialDiesel'=>$InventarioDiesel,
				'MesInventarioInicial'=>$mes
			];
		$Proyeccion->altaInventarioInicial($Inventario);
		$numeroDias=cal_days_in_month(CAL_GREGORIAN, $mes, date('Y'));
		for ($i = 0; $i < $numeroDias; $i ++) {
			$ii=$i+1;
			$Proyecciones=[
				'fechaProyeccion'=> date('Y')."-".$mes."-".$ii,
				'idGasolinera'=>$idGasolinera,
				'consecutivo'=>$Contador,
				'proyeccionInventarioSuper'=>$InventarioSuper,
				'proyeccionVentaSuper'=>$ProyeccionVentaSuper,
				'proyeccionCompraSuper'=>0,
				'proyeccionInventarioPremium'=>$InventarioPremium,
				'proyeccionVentaPremium'=>$ProyeccionVentaPremium,
				'proyeccionCompraPremium'=>0,
				'proyeccionInventarioDiesel'=>$InventarioDiesel,
				'proyeccionVentaDiesel'=>$ProyeccionVentaDiesel,
				'proyeccionCompraDiesel'=>0
			];
			$Proyeccion->insert($Proyecciones);
			$InventarioSuper=$InventarioSuper-$ProyeccionVentaSuper;
			$InventarioPremium=$InventarioPremium-$ProyeccionVentaPremium;
			$InventarioDiesel=$InventarioDiesel-$ProyeccionVentaDiesel;
		}
	
			echo json_encode([
				'message' => "Se han dado de alta las proyecciones"
			]);
	}
	function update($idProyeccion = null){

		$Proyeccion=new Proyeccion();
		$WebToken=new WebToken();
		$jwt=$this->request->getRawInputVar('jwt');
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$fechaProyeccion=$this->request->getRawInputVar('fechaProyeccion');
		$Inventario=$this->request->getRawInputVar('Inventario');
		$Venta=$this->request->getRawInputVar('Venta');
		$Compra=$this->request->getRawInputVar('Compra');
		$idGasolina=$this->request->getRawInputVar('idGasolina');
	if($idGasolina==1){
		$data=[
			'fechaProyeccion'=>$fechaProyeccion,
			'proyeccionInventarioSuper'=>$Inventario,
			'proyeccionVentaSuper'=>$Venta,
			'proyeccionCompraSuper'=>$Compra,
		];
	}else if($idGasolina==2){
		$data=[
			'fechaProyeccion'=>$fechaProyeccion,
			'proyeccionInventarioPremium'=>$Inventario,
			'proyeccionVentaPremium'=>$Venta,
			'proyeccionCompraPremium'=>$Compra,
		];


	}else if($idGasolina==3){
		$data=[
			'fechaProyeccion'=>$fechaProyeccion,
			'proyeccionInventarioDiesel'=>$Inventario,
			'proyeccionVentaDiesel'=>$Venta,
			'proyeccionCompraDiesel'=>$Compra,
		];
	}
		
			$Proyeccion->actualizarProyeccion($idProyeccion,$data);		
		echo json_encode([
			'message' => "La proyeccion ha sido actualizada correctamente"
		]);
	}
	function deleteProyeccion() {
		$Proyeccion=new Proyeccion();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$Consecutivo = $this->request->getPost("consecutivo"); 
		$Proyeccion->deleteProyeccion($Consecutivo);
		echo json_encode([
			'message' => "Se han eliminado las proyecciones"
		]);
	}

	function deleteProyeccionxId() {
		$Proyeccion=new Proyeccion();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idProyeccion = $this->request->getPost("idProyeccion"); 
		$Proyeccion->deleteProyeccion($idProyeccion);
		echo json_encode([
			'message' => "Se han eliminado las proyecciones"
		]);
	}

	function obtenerProyeccionesxConsecutivo() {
        $Proyeccion=new Proyeccion();
		$Consecutivo = $this->request->getPost("consecutivo"); 
		echo json_encode($Proyeccion->selectProyeccionxConsecutivo($Consecutivo));
	}
	function obtenerProyeccionesxidProyeccion() {
        $Proyeccion=new Proyeccion();
		$idProyeccion = $this->request->getPost("idProyeccion"); 
		echo json_encode($Proyeccion->selectProyeccionxidProyeccion($idProyeccion));
	}
	function ModificarInventario(){
		$Proyeccion=new Proyeccion();
		$Archivo=new Archivo();
		$WebToken=new WebToken();
		$jwt=$this->request->getPost('jwt');
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idProyeccion = $this->request->getPost("idProyeccion");
		$inventario  = $this->request->getPost("inventario");
		$TipoGasolina  = $this->request->getPost("TipoGasolina");
		$Contador=$Proyeccion->getProyeccionesContador();
		$proyeccionActual=$Proyeccion->selectProyeccionxidProyeccion($idProyeccion);
		$proyeccionesBase=$Proyeccion->getProyeccionessiguientes($idProyeccion,$proyeccionActual['consecutivo']);
		for ($i = 0; $i < sizeof($proyeccionesBase); $i ++) {
			if($TipoGasolina=="Super"){
				$Proyecciones=[
					'proyeccionInventarioSuper'=>$inventario,
					'proyeccionVentaSuper'=>$proyeccionesBase[$i]['proyeccionVentaSuper'],
					'proyeccionCompraSuper'=>$proyeccionesBase[$i]['proyeccionCompraSuper']
				];
				$Proyeccion->actualizarProyeccion($proyeccionesBase[$i]['idProyeccion'],$Proyecciones);
				$inventario=$inventario-$proyeccionesBase[$i]['proyeccionVentaSuper'];
				$inventario=$inventario+$proyeccionesBase[$i]['proyeccionCompraSuper'];
			
			}
			if($TipoGasolina=="Premium"){
				$Proyecciones=[
					'proyeccionInventarioPremium'=>$inventario,
					'proyeccionVentaPremium'=>$proyeccionesBase[$i]['proyeccionVentaPremium'],
					'proyeccionCompraPremium'=>$proyeccionesBase[$i]['proyeccionCompraPremium']
				];
				$Proyeccion->actualizarProyeccion($proyeccionesBase[$i]['idProyeccion'],$Proyecciones);
				$inventario=$inventario-$proyeccionesBase[$i]['proyeccionVentaPremium'];
				$inventario=$inventario+$proyeccionesBase[$i]['proyeccionCompraPremium'];
			
			}
			if($TipoGasolina=="Diesel"){
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
				'message' => "Se han dado de alta las proyecciones"
			]);
	}
	function ModificarCompra(){
		$Proyeccion=new Proyeccion();
		$Archivo=new Archivo();
		$WebToken=new WebToken();
		$jwt=$this->request->getPost('jwt');
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idProyeccion = $this->request->getPost("idProyeccion");
		$compra  = $this->request->getPost("compra");
		$TipoGasolina  = $this->request->getPost("TipoGasolina");
		$proyeccionActual=$Proyeccion->selectProyeccionxidProyeccion($idProyeccion);
		
		if($TipoGasolina=="Super"){
			$inventario  = $proyeccionActual['proyeccionInventarioSuper'];
			$com=[
				'proyeccionCompraSuper'=>$compra,
			];
			$Proyeccion->actualizarProyeccion($idProyeccion,$com);
			$proyeccionesBase=$Proyeccion->getProyeccionessiguientes($idProyeccion,$proyeccionActual['consecutivo']);
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
		if($TipoGasolina=="Premium"){
			$inventario  = $proyeccionActual['proyeccionInventarioPremium'];
			$com=[
				'proyeccionCompraPremium'=>$compra
			];
			$Proyeccion->actualizarProyeccion($idProyeccion,$com);
			$proyeccionesBase=$Proyeccion->getProyeccionessiguientes($idProyeccion,$proyeccionActual['consecutivo']);
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
		if($TipoGasolina=="Diesel"){
			$inventario  = $proyeccionActual['proyeccionInventarioDiesel'];
			$com=[
				'proyeccionCompraDiesel'=>$compra
			];
			$Proyeccion->actualizarProyeccion($idProyeccion,$com);
			$proyeccionesBase=$Proyeccion->getProyeccionessiguientes($idProyeccion,$proyeccionActual['consecutivo']);
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
			'message' => "Se han dado de alta las proyecciones"
		]);
	}
}?>