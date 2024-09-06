<?php
namespace App\Controllers;
require_once($_SERVER['DOCUMENT_ROOT']. '/ApiGlobalOro/PHPMailer/src/Exception.php');
require_once($_SERVER['DOCUMENT_ROOT']. '/ApiGlobalOro/PHPMailer/src/PHPMailer.php');
require_once($_SERVER['DOCUMENT_ROOT']. '/ApiGlobalOro/PHPMailer/src/SMTP.php');
// require 'https://www.globalshield.com.mx/ApiGlobalOro/PHPMailer/src/Exception.php';
// require 'https://www.globalshield.com.mx/ApiGlobalOro/PHPMailer/src/PHPMailer.php';
// require 'https://www.globalshield.com.mx/ApiGlobalOro/PHPMailer/src/SMTP.php';
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Archivo;
use App\Models\WebToken;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use MercadoPago;

class Ventas extends ResourceController
{
    function obtenerVentas() {
        $Venta=new Venta();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idCliente = $this->request->getPost("idCliente");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Venta->getVentas($page, $pageSize, $idCliente));
	}
    function obtenerNumeroVentas() {
        $Venta=new Venta();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idCliente = $this->request->getPost("idCliente");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Venta->getNumeroVentas($page, $pageSize, $idCliente));
	}
	function obtenerproductos() {
        $Venta=new Venta();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idTransaccion = $this->request->getPost("idTransaccion");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Venta->getProductos($page, $pageSize, $idTransaccion));
	}
	function obtenerNumeroProductos() {
        $Venta=new Venta();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idTransaccion = $this->request->getPost("idTransaccion");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		echo json_encode($Venta->getNumeroProductos($page, $pageSize, $idTransaccion));
	}

	function SumaTotaProducto(){
		$Venta=new Venta();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idTransaccion = $this->request->getPost("idTransaccion");
		echo json_encode($Venta->getSumaProductos($idTransaccion));
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
	function obtenerlinkpago() {
		MercadoPago\SDK::setAccessToken("APP_USR-968923362167994-032112-2230b36cb9bd36944525d68d8edb89c7-1738428552");
		$preference=new MercadoPago\Preference();
		$Venta=new Venta();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
		$existeCliente=$Venta->selecttransaccionxidCliente($idUsuario);
		if(sizeof($existeCliente)==0){
			$preference->back_urls=array("success"=>'http://localhost:4200/pages/Avisolegalario/'.$idUsuario,"failure"=>'http://localhost:4200/shop/checkout',"pending"=>'http://localhost:4200/shop/checkout');

			// $preference->back_urls=array("success"=>'https://www.globalshield.com.mx/pages/Avisolegalario/'.$idUsuario,"failure"=>'https://www.globalshield.com.mx/shop/checkout',"pending"=>'https://www.globalshield.com.mx/shop/checkout');
		}else if(sizeof($existeCliente)>=1){
			$preference->back_urls=array("success"=>'http://localhost:4200/pages/CompraOro',"failure"=>'http://localhost:4200/shop/checkout',"pending"=>'http://localhost:4200/shop/checkout');

			// $preference->back_urls=array("success"=>'https://www.globalshield.com.mx/pages/CompraOro',"failure"=>'https://www.globalshield.com.mx/shop/checkout',"pending"=>'https://www.globalshield.com.mx/shop/checkout');
		}
		// $preference->back_urls=array("success"=>base_url('http://localhost:4200/home/CompraOro'),"failure"=>base_url('http://localhost:4200/shop/checkout'),"pending"=>base_url('http://localhost:4200/shop/checkout'));
		
		$productos=json_decode($this->request->getPost('productos'),true);
		$products=[];
		foreach($productos as $prod){
			$item= new MercadoPago\Item();
			$item->id=$prod['id'];
			$item->title=$prod['title'];
			$item->description=$prod['description'];
			$item->quantity=$prod['quantity'];
			$item->unit_price=number_format($prod['pricemxn'], 2, '.', '');
			$item->currency_id="MXN";
			array_push($products,$item); 

		}
		$preference->items=$products;
		$preference->notification_url='https://f8c2-201-141-17-1.ngrok-free.app/ApiGlobalOro/Ventas/crearventa';

			// $preference->notification_url='https://www.globalshield.com.mx/ApiGlobalOro/Ventas/crearventa';
		$preference->save();
		$data=[
			"id"=>$preference->id,
			"rutapago"=>$preference->init_point,
			"sandbox"=>$preference->sandbox_init_point,
			"backrul"=>$preference->back_urls
		];
		$fecha=date('Y-m-d');
		$transaccion=[
            'idCliente'=>$idUsuario,
			'idPreferencia'=>$preference->id,
			'fecha'=>$fecha
		]; 
		$idTransaccion=$Venta->crearTempTransacciones($transaccion);
		$Venta->crearTempCompra($idTransaccion,$productos);

		echo json_encode([$data]);
	}

	function crearventa() {
		MercadoPago\SDK::setAccessToken("APP_USR-968923362167994-032112-2230b36cb9bd36944525d68d8edb89c7-1738428552");
		$Venta=new Venta();
		$Cliente=new Cliente();
		$fecha=date('Y-m-d');
		$fechaañonext=date('Y-m-d', strtotime('+1 year'));
		if(isset($_GET["topic"])){
			$topic=$_GET["topic"];
		}
		if(isset($_GET["type"])){
			$topic=$_GET["type"];
		}
		if(isset($_GET['data_id'])){
			$id=$_GET['data_id'];
		}
		if(isset($_GET["id"])){
			$id=$_GET["id"];
		}
		$merchant_order = null;
		switch($topic) {
			case "payment":
				$payment = MercadoPago\Payment::find_by_id($id);
				// Get the payment and the corresponding merchant_order reported by the IPN.
				$merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);
				break;
			case "merchant_order":
				$merchant_order = MercadoPago\MerchantOrder::find_by_id($id);
				break;
		}

		$preferenceid=$merchant_order->preference_id;
		$payments=$merchant_order->payments;
		//print_r($payments);
		$statusPago="sinpagar";
		foreach ($payments as $pay){
		if($pay->status=='approved'){
		// print_r($preferenceid);
		$transt=$Venta->selecttemporaltransaccion($preferenceid);
		$comprat=$Venta->selectCompraTemporal($preferenceid);
		$existe=$Venta->selecttransaccionxPreferencia($preferenceid);
		$existeCliente=$Venta->selecttransaccionxidCliente($transt['idCliente']);
		$datosCliente=$Cliente->selectClientesxid($transt['idCliente']);
		$nombre_cliente=$datosCliente['nombre_cliente'];
		$correo=$datosCliente['correo'];
		$titulo="Detalles de su compra";
		$mensaje="<p>Buen día ".$nombre_cliente."</p>
		<p>Estos son los detalles de su compra</p>
		<p>Usuario:".$correo."</p>";

		if(sizeof($existeCliente)==0){
			$data=[
				'status'=>1,
				'aceptoFlujo'=>0
				];
			$Cliente->actualizarClientes($transt['idCliente'],$data);	
		}
		if(!isset($existe)){
			$transaccion=[
        	    'idCliente'=>$transt['idCliente'],
				'idPreferencia'=>$transt['idPreferencia'],
				'estatusCompra'=>1,
				'fecha'=>$fecha,
			]; 
			$mensaje.="<div align=center>
						<table  style='margin-left:auto;margin-right:auto;'>
						 	<thead>
								<th>Cliente</th>
								<th>Fecha de la compra</th>
						 	</thead>
							<tbody>
								<tr>
									<td>".$nombre_cliente."</td>
									<td>".$fecha."</td>
								</tr>
							</tbody>
						</table>
						</div>
						<div align=center>
						<table style='margin-left:auto;margin-right:auto;'>
							<thead>
								<th>Producto</th>
								<th>Cantidad</th>
								<th>Precio unitario</th>
								<th>Total</th>
						 	</thead>
							<tbody>";
								
								
							
			$idTransaccion=$Venta->insertTransaccion($transaccion);
			$total=0;
			foreach($comprat as $prod){
				$compra=[
					'idTransaccion'=>$idTransaccion,
					'idProducto'=>$prod['idProducto'],
					'cantidad'=>$prod['cantidad'],
					'preciounitario'=>number_format($prod['preciounitario'], 2, '.', ''),
					'preciounitarioUSD'=>number_format($prod['preciounitarioUSD'], 2, '.', '')
				];
				$datosProducto=$Venta->selectProductosxid($prod['idProducto']);
				$subtotal=$prod['cantidad']*$prod['preciounitario'];
				$mensaje.="<tr><td>".$datosProducto['idProducto']."</td>
								<td>".$prod['cantidad']."</td>
								<td>".number_format($prod['preciounitario'], 2, '.', '')."</td>
								<td>".number_format($subtotal, 2, '.', '')."</td>	
							</tr>";
							
							$total=$total+$subtotal;							
				$idCompra=$Venta->crearCompra($compra);
			}
			$mensaje.="</tbody>
						</table>
						</div>
						<br>
						<div align=center>
						<table style='margin-left:auto;margin-right:auto;'>
						<tbody>
						 <tr><td>TOTAL:</td><td>".number_format($total, 2, '.', '')."</td></tr>
						</tbody>
						</ table>
						</div>";
			$rentaCaja=[
				'fechaInicio'=>$fecha,
				'fechaFinal'=>$fechaañonext,
				'idTransaccion'=>$idTransaccion,
				'idCliente'=>$transt['idCliente']
			];
			$idRenta=$Venta->crearRentaCompra($rentaCaja);
			$this->enviarCorreo($correo,$titulo,$mensaje);
		}
		$data=array('message' => "Todo ok");
		$this->response->setStatusCode(200, json_encode(array('message' => "Todo ok")));
		return $this->response->setJSON($data);
			}else{
				$data=array('message' => "Pago no procesado");
				$this->response->setStatusCode(200, json_encode(array('message' => "Pago no procesado")));
				return $this->response->setJSON($data);
			}
		}
	}
	function pagoexitoso() {

		$Venta=new Venta();
		$fecha=date('Y-m-d');
		$fechaañonext=date('Y-m-d', strtotime('+1 year'));
		$transaccion=[
            'idCliente'=>$idComprador,
			'fecha'=>$fecha,
		]; 
		$idTransaccion=$Venta->insertTransaccion($transaccion);
		foreach($productos as $prod){
			$compra=[
				'idTransaccion'=>$idTransaccion,
				'idProducto'=>$prod['id'],
				'cantidad'=>$prod['quantity'],
				'preciounitario'=>number_format($prod['pricemxn'], 2, '.', '')
			];
			$idCompra=$Venta->crearCompra($compra);
		}
		$rentaCaja=[
			'fechaInicio'=>$fecha,
			'fechaFinal'=>$fechaañonext,
			'idCliente'=>$idComprador
		];
		$idRenta=$Venta->crearRentaCompra($rentaCaja);
		redirect('http://localhost:4200/home/CompraOro', 'refresh');
	}
	function pagofallido() {
		$data=array('message' => "Pago ha fallado");
		$this->response->setStatusCode(409, json_encode(array('message' => "Pago ha fallado")));
		return $this->response->setJSON($data);
		// echo json_encode([
		// 	'message' => "Pago ha fallado"
		// ]);
	}
	function pagopendiente() {
		$data=array('message' => "El pago se encuentra pendiente");
		$this->response->setStatusCode(409, json_encode(array('message' => "El pago se encuentra pendiente")));
		return $this->response->setJSON($data);
		// echo json_encode([
		// 	'message' => "El pago se encuentra pendiente"
		// ]);
	}
    function obtenerVentaxId() {
        $Venta=new Venta();
		$idVenta = $this->request->getPost("idVenta"); 
		echo json_encode($Venta->selectVentaxid($idVenta));
	}
	function crearventaTransferencia() {
		$Archivo=new Archivo();
		$WebToken=new WebToken();
		$Venta=new Venta();
		$Cliente=new Cliente();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idPreferencia = $this->request->getPost("idPreferencia");
		
		if ($_FILES && $_FILES['archivo']) {
			$archivo = $Archivo->subirArchivo("archivo");
		} else {
			$archivo = array(
				'directorio' => null,
				'tipo' => null,
				'extension' => null,
				'nombreOriginal' => null
			);
		}
		
		$transt=$Venta->selecttemporaltransaccion($idPreferencia);
		$existeCliente=$Venta->selecttransaccionxidCliente($transt['idCliente']);
		$datosCliente=$Cliente->selectClientesxid($transt['idCliente']);
		$nombre_cliente=$datosCliente['nombre_cliente'];
		$correo=$datosCliente['correo'];
		$titulo="Detalles de su compra";
		$mensaje="<p>Buen día ".$nombre_cliente."</p>
		<p>Estos son los detalles de su compra</p>
		<p>Usuario:".$correo."</p>";
		$fecha=date('Y-m-d');
		if(sizeof($existeCliente)==0){
			$data=[
				'status'=>1,
				'aceptoFlujo'=>0
				];
				$Cliente->actualizarClientes($transt['idCliente'],$data);	
			}
			$mensaje.="<div align=center>
							<table style='margin-left:auto;margin-right:auto;>
							 	<thead>
									<th>Cliente</th>
									<th>Fecha de la compra</th>
							 	</thead>
								<tbody>
									<tr>
										<td>".$nombre_cliente."</td>
										<td>".$fecha."</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div align=center>
						<table style='margin-left:auto;margin-right:auto;>
							<thead>
								<th>Producto</th>
								<th>Cantidad</th>
								<th>Precio unitario</th>
								<th>Total</th>
						 	</thead>
							<tbody>";

			

		$comprat=$Venta->selectCompraTemporal($idPreferencia);
	
		$fechaañonext=date('Y-m-d', strtotime('+1 year'));
		$transaccion=[
			'idCliente'=>$transt['idCliente'],
			'idPreferencia'=>$transt['idPreferencia'],
			'archivoTransferencia'=>$archivo['directorio'],
			'estatusCompra'=>1,
			'fecha'=>$fecha,
		]; 
		$idTransaccion=$Venta->insertTransaccion($transaccion);
		$total=0;
		foreach($comprat as $prod){
			$compra=[
				'idTransaccion'=>$idTransaccion,
				'idProducto'=>$prod['idProducto'],
				'cantidad'=>$prod['cantidad'],
				'preciounitario'=>number_format($prod['preciounitario'], 2, '.', ''),
				'preciounitarioUSD'=>number_format($prod['preciounitarioUSD'], 2, '.', '')
			];
			$idCompra=$Venta->crearCompra($compra);
			$datosProducto=$Venta->selectProductosxid($prod['idProducto']);
			$subtotal=$prod['cantidad']*$prod['preciounitario'];
			$mensaje.="<tr><td>".$datosProducto['idProducto']."</td>
							<td>".$prod['cantidad']."</td>
							<td>".number_format($prod['preciounitario'], 2, '.', '')."</td>
							<td>".number_format($subtotal, 2, '.', '')."</td>	
						</tr>";
						
						$total=$total+$subtotal;
		}
		$mensaje.="</tbody>
		</table>
		</div>
		<br>
		<div align=center>
		<table style='margin-left:auto;margin-right:auto;'>
		<tbody>
		 <tr><td>TOTAL:</td><td>".number_format($total, 2, '.', '')."</td></tr>
		</tbody>
		</table>
		</div>";

		$rentaCaja=[
			'fechaInicio'=>$fecha,
			'fechaFinal'=>$fechaañonext,
			'idTransaccion'=>$idTransaccion,
			'idCliente'=>$transt['idCliente']
		];
		$idRenta=$Venta->crearRentaCompra($rentaCaja);
		$this->enviarCorreo($correo,$titulo,$mensaje);

		$data=array('message' => "Venta procesada de manera correcta");
		$this->response->setStatusCode(200, json_encode(array('message' => "Venta procesada de manera correcta")));
		return $this->response->setJSON($data);
	
	}

	function obtenerProductosCompradosxCliente() {
        $Venta=new Venta();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idCliente = $this->request->getPost("idCliente");
		echo json_encode($Venta->selectProductosCompradosxCliente($idCliente));
	}

	
	// function obtenerProductosCompradosxCliente() {
    //     $Venta=new Venta();
	// 	$WebToken=new WebToken();
	// 	$jwt = $this->request->getPost("jwt");
	// 	$jwt = $WebToken->decodificarJWT($jwt);
	// 	$idCliente = $this->request->getPost("idCliente");
	// 	echo json_encode($Venta->selectProductosCompradosxCliente($idCliente));
	// }
	function cambiarEstatusCompra() {
        $Venta=new Venta();
		$Cliente=new Cliente();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idTransaccion = $this->request->getPost("idTransaccion");
		$estatus = $this->request->getPost("estatus");
		$data=[
			'estatusCompra'=>$estatus,
		];
		$transt=$Venta->selecttransaccion($idTransaccion);
		$fecha=$transt['fecha'];
		$datosCliente=$Cliente->selectClientesxid($transt['idCliente']);
		$comprat=$Venta->selectCompratransaccion($transt['idTransaccion']);
		$nombre_cliente=$datosCliente['nombre_cliente'];
		$correo=$datosCliente['correo'];
		$titulo="Actualizacion del estatus de su compra";
		$mensaje="<p>Buen día ".$nombre_cliente."</p>";
		if($estatus==1){
			$mensaje.="<p>Su compra se encuentra bajo resguardo por Globalshield</p>";
		}else if($estatus==2){
			$mensaje.="<p>Su compra se encuentra iniciando el proceso de reembolso por Globalshield</p>";
		}else if($estatus==3){
			$mensaje.="<p>Su compra ha sido reembolsada por Globalshield</p>";
		}else if($estatus==4){
			$mensaje.="<p>Su compra se encuentra en proceso de envío</p>";
		}else if($estatus==5){
			$mensaje.="<p>Su compra ha sido enviada</p>";
		}else if($estatus==6){
			$mensaje.="<p>Su compra ha sido recibida</p>";
		}else if($estatus==7){
			$mensaje.="<p>Su compra ha sido cancelada</p>";
		}
		$mensaje.="<p>Estos son los detalles de su compra</p>
		<p>Usuario:".$correo."</p>
		<div align=center>
							<table style='margin-left:auto;margin-right:auto;>
							 	<thead>
									<th>Cliente</th>
									<th>Fecha de la compra</th>
							 	</thead>
								<tbody>
									<tr>
										<td>".$nombre_cliente."</td>
										<td>".$fecha."</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div align=center>
						<table style='margin-left:auto;margin-right:auto;>
							<thead>
								<th>Producto</th>
								<th>Cantidad</th>
								<th>Precio unitario</th>
								<th>Total</th>
						 	</thead>
							<tbody>";

							$total=0;
							foreach($comprat as $prod){
								$datosProducto=$Venta->selectProductosxid($prod['idProducto']);
								$subtotal=$prod['cantidad']*$prod['preciounitario'];
								$mensaje.="<tr><td>".$datosProducto['idProducto']."</td>
												<td>".$prod['cantidad']."</td>
												<td>".number_format($prod['preciounitario'], 2, '.', '')."</td>
												<td>".number_format($subtotal, 2, '.', '')."</td>	
											</tr>";
											$total=$total+$subtotal;							
							}

							$mensaje.="</tbody>
							</table>
							</div>
							<br>
							<div align=center>
							<table style='margin-left:auto;margin-right:auto;'>
							<tbody>
							 <tr><td>TOTAL:</td><td>".number_format($total, 2, '.', '')."</td></tr>
							</tbody>
							</ table>
							</div>";
							$this->enviarCorreo($correo,$titulo,$mensaje);
							if($estatus==2){
								$tituload="Reembolso de compra";
								$mensajead="<p>Buen día </p>
								<p>La compra del cliente ".$nombre_cliente." , con fecha del ".$transt['fecha']." acaba de marcarse para iniciar el proceso de devolución, favor de iniciar el proceso con Mercado pago o con la institución bancaria correspondiente para el reembolso</p>";
								$this->enviarCorreoAdmn($tituload,$mensajead);
							}else if($estatus==3){
								$mensajead="<p>Buen día </p>
								<p>La compra del cliente ".$nombre_cliente." , con fecha del ".$transt['fecha']." acaba de ser reembolsado</p>";
								$this->enviarCorreoAdmn($tituload,$mensajead);
							}else if($estatus==7){
								$tituload="Compra cancelada";
								$mensajead="<p>Buen día </p>
								<p>La compra del cliente ".$nombre_cliente." , con fecha del ".$transt['fecha']." acaba de ser cancelada, reembolsado, favor de iniciar el proceso con Mercado pago o con la institución bancaria correspondiente para el reembolso</p>";
								$this->enviarCorreoAdmn($tituload,$mensajead);
							}
		echo json_encode($Venta->actualizarCompra($idTransaccion,$data));
	}

	function negarFlujo() {
        $Venta=new Venta();
		$Cliente=new Cliente();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$Transaccion = $Venta->selectultimatransaccionxidCliente($idUsuario);
		$idTransaccion=$Transaccion['idTransaccion'];
		// $estatus = $this->request->getPost("estatus");
		$data=[
			'estatusCompra'=>2,
		];
		$data2=[
			'aceptoFlujo'=>2
		];
		$transt=$Venta->selecttransaccion($idTransaccion);
		$fecha=$transt['fecha'];
		$datosCliente=$Cliente->selectClientesxid($transt['idCliente']);
		$comprat=$Venta->selectCompratransaccion($transt['idTransaccion']);
		$nombre_cliente=$datosCliente['nombre_cliente'];
		$correo=$datosCliente['correo'];
		$titulo="Actualizacion del estatus de su compra";
		$mensaje="<p>Buen día ".$nombre_cliente."</p>
		<p>Su compra se encuentra iniciando el proceso de reembolso por Globalshield</p>
		<p>Estos son los detalles de su compra</p>
		<p>Usuario:".$correo."</p>
		<div align=center>
			<table style='margin-left:auto;margin-right:auto;>
			 	<thead>
					<th>Cliente</th>
					<th>Fecha de la compra</th>
			 	</thead>
				<tbody>
					<tr>
						<td>".$nombre_cliente."</td>
						<td>".$fecha."</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div align=center>
			<table style='margin-left:auto;margin-right:auto;>
				<thead>
					<th>Producto</th>
					<th>Cantidad</th>
					<th>Precio unitario</th>
					<th>Total</th>
				</thead>
				<tbody>";
		$total=0;
		foreach($comprat as $prod){
			$datosProducto=$Venta->selectProductosxid($prod['idProducto']);
			$subtotal=$prod['cantidad']*$prod['preciounitario'];
			$mensaje.="<tr><td>".$datosProducto['idProducto']."</td>
							<td>".$prod['cantidad']."</td>
							<td>".number_format($prod['preciounitario'], 2, '.', '')."</td>
							<td>".number_format($subtotal, 2, '.', '')."</td>	
						</tr>";
						$total=$total+$subtotal;							
		}
		$mensaje.="</tbody>
			</table>
		</div>
		<br>
		<div align=center>
			<table style='margin-left:auto;margin-right:auto;'>
				<tbody>
					<tr><td>TOTAL:</td><td>".number_format($total, 2, '.', '')."</td></tr>
				</tbody>
			</table>
		</div>";
		$this->enviarCorreo($correo,$titulo,$mensaje);
		$tituload="Reembolso de compra";
		$mensajead="<p>Buen día </p>
		<p>La compra del cliente ".$nombre_cliente." , con fecha del ".$transt['fecha']." acaba de marcarse para iniciar el proceso de devolución, favor de iniciar el proceso con Mercado pago o con la institución bancaria correspondiente para el reembolso</p>";
		$this->enviarCorreoAdmn($tituload,$mensajead);
		$Cliente->actualizarClientes($idUsuario,$data2);
		echo json_encode($Venta->actualizarCompra($idTransaccion,$data));
	}

	function tieneComprasAnteriores(){
		$Venta=new Venta();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario=$this->request->getPost("idUsuario");
		$existe=$Venta->selecttransaccionxidCliente($idUsuario);
		
		if(sizeof($existe)==0){
			$data=[
				'notieneregistros'=>true,
			];
			echo json_encode($data);
		}else{
			$data=[
				'notieneregistros'=>false,
			];
			echo json_encode($data);
		}
	}

	function obtenerlinkpagoAcunacion() {
		MercadoPago\SDK::setAccessToken("APP_USR-968923362167994-032112-2230b36cb9bd36944525d68d8edb89c7-1738428552");
		$preference=new MercadoPago\Preference();
		$Venta=new Venta();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
		$existeCliente=$Venta->selecttransaccionxidCliente($idUsuario);
		$preference->back_urls=array("success"=>'http://localhost:4200/pages/compras/'.$idUsuario,"failure"=>'http://localhost:4200/shop/checkout',"pending"=>'http://localhost:4200/shop/checkout');	
		$productos=json_decode($this->request->getPost('productos'),true);
		$products=[];
		foreach($productos as $prod){
			$item= new MercadoPago\Item();
			$item->id=$prod['id'];
			$item->title=$prod['title'];
			$item->description=$prod['description'];
			$item->quantity=$prod['quantity'];
			$item->unit_price=number_format($prod['pricemxn'], 2, '.', '');
			$item->currency_id="MXN";
			array_push($products,$item); 

		}
		$preference->items=$products;
		$preference->notification_url='https://f8c2-201-141-17-1.ngrok-free.app/ApiGlobalOro/Ventas/crearAcunacion';

			// $preference->notification_url='https://www.globalshield.com.mx/ApiGlobalOro/Ventas/crearventa';
		$preference->save();
		$data=[
			"id"=>$preference->id,
			"rutapago"=>$preference->init_point,
			"sandbox"=>$preference->sandbox_init_point,
			"backrul"=>$preference->back_urls
		];
		$fecha=date('Y-m-d');
		$transaccion=[
            'idCliente'=>$idUsuario,
			'idPreferencia'=>$preference->id,
			'fecha'=>$fecha
		]; 
		$idTransaccion=$Venta->crearTempAcunacion($transaccion);
		$Venta->crearTempCompra($idTransaccion,$productos);

		echo json_encode([$data]);
	}

	

	function crearAcunacion() {
		MercadoPago\SDK::setAccessToken("APP_USR-968923362167994-032112-2230b36cb9bd36944525d68d8edb89c7-1738428552");
		$Venta=new Venta();
		$Cliente=new Cliente();
		$fecha=date('Y-m-d');
		$fechaañonext=date('Y-m-d', strtotime('+1 year'));
		if(isset($_GET["topic"])){
			$topic=$_GET["topic"];
		}
		if(isset($_GET["type"])){
			$topic=$_GET["type"];
		}
		if(isset($_GET['data_id'])){
			$id=$_GET['data_id'];
		}
		if(isset($_GET["id"])){
			$id=$_GET["id"];
		}
		$merchant_order = null;
		switch($topic) {
			case "payment":
				$payment = MercadoPago\Payment::find_by_id($id);
				// Get the payment and the corresponding merchant_order reported by the IPN.
				$merchant_order = MercadoPago\MerchantOrder::find_by_id($payment->order->id);
				break;
			case "merchant_order":
				$merchant_order = MercadoPago\MerchantOrder::find_by_id($id);
				break;
		}

		$preferenceid=$merchant_order->preference_id;
		$payments=$merchant_order->payments;
		//print_r($payments);
		$statusPago="sinpagar";
		foreach ($payments as $pay){
		if($pay->status=='approved'){
		// print_r($preferenceid);
		$transt=$Venta->selecttemporaltransaccion($preferenceid);
		$comprat=$Venta->selectProdacunacionTemporal($preferenceid);
		$existe=$Venta->selecttransaccionxPreferencia($preferenceid);
		$existeCliente=$Venta->selecttransaccionxidCliente($transt['idCliente']);
		$datosCliente=$Cliente->selectClientesxid($transt['idCliente']);
		$nombre_cliente=$datosCliente['nombre_cliente'];
		$correo=$datosCliente['correo'];
		$titulo="Detalles de su compra";
		$mensaje="<p>Buen día ".$nombre_cliente."</p>
		<p>Estos son los detalles de su compra</p>
		<p>Usuario:".$correo."</p>";

		if(sizeof($existeCliente)==0){
			$data=[
				'status'=>1,
				'aceptoFlujo'=>0
				];
			$Cliente->actualizarClientes($transt['idCliente'],$data);	
		}
		if(!isset($existe)){
			$transaccion=[
        	    'idCliente'=>$transt['idCliente'],
				'idPreferencia'=>$transt['idPreferencia'],
				'estatusCompra'=>1,
				'fecha'=>$fecha,
			]; 
			$mensaje.="<div align=center>
						<table  style='margin-left:auto;margin-right:auto;'>
						 	<thead>
								<th>Cliente</th>
								<th>Fecha de la compra</th>
						 	</thead>
							<tbody>
								<tr>
									<td>".$nombre_cliente."</td>
									<td>".$fecha."</td>
								</tr>
							</tbody>
						</table>
						</div>
						<div align=center>
						<table style='margin-left:auto;margin-right:auto;'>
							<thead>
								<th>Producto</th>
								<th>Cantidad</th>
								<th>Precio unitario</th>
								<th>Total</th>
						 	</thead>
							<tbody>";				
			$idTransaccion=$Venta->insertTransaccion($transaccion);
			$total=0;
			foreach($comprat as $prod){
				$compra=[
					'idTransaccion'=>$idTransaccion,
					'idProducto'=>$prod['idProducto'],
					'cantidad'=>$prod['cantidad'],
					'preciounitario'=>number_format($prod['preciounitario'], 2, '.', ''),
					'preciounitarioUSD'=>number_format($prod['preciounitarioUSD'], 2, '.', '')
				];
				$datosProducto=$Venta->selectProductosxid($prod['idProducto']);
				$subtotal=$prod['cantidad']*$prod['preciounitario'];
				$mensaje.="<tr><td>".$datosProducto['idProducto']."</td>
								<td>".$prod['cantidad']."</td>
								<td>".number_format($prod['preciounitario'], 2, '.', '')."</td>
								<td>".number_format($subtotal, 2, '.', '')."</td>	
							</tr>";
							
							$total=$total+$subtotal;							
				$idCompra=$Venta->crearCompra($compra);
			}
			$mensaje.="</tbody>
						</table>
						</div>
						<br>
						<div align=center>
						<table style='margin-left:auto;margin-right:auto;'>
						<tbody>
						 <tr><td>TOTAL:</td><td>".number_format($total, 2, '.', '')."</td></tr>
						</tbody>
						</ table>
						<p>Por favor revise su correo para las actualizaciones con respecto a su envio.</p>
						</div>";
			$rentaCaja=[
				'fechaInicio'=>$fecha,
				'fechaFinal'=>$fechaañonext,
				'idTransaccion'=>$idTransaccion,
				'idCliente'=>$transt['idCliente']
			];
			$idRenta=$Venta->crearRentaCompra($rentaCaja);
			$this->enviarCorreo($correo,$titulo,$mensaje);
		}
		$data=array('message' => "Todo ok");
		$this->response->setStatusCode(200, json_encode(array('message' => "Todo ok")));
		return $this->response->setJSON($data);
			}else{
				$data=array('message' => "Pago no procesado");
				$this->response->setStatusCode(200, json_encode(array('message' => "Pago no procesado")));
				return $this->response->setJSON($data);
			}
		}
	}

	function ActualizarAcunacion() {
        $Venta=new Venta();
		$Cliente=new Cliente();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idTransaccion = $this->request->getPost("idTransaccion");
		$estatus = $this->request->getPost("estatus");
		$fechaCreacion = $this->request->getPost("fechaCreacion");
		$fechaEnvio = $this->request->getPost("fechaEnvio");
		$empresaEnvio = $this->request->getPost("empresaEnvio");
		$guiaEnvio = $this->request->getPost("guiaEnvio");
		$data=[
			'estatusCompra'=>$estatus,
			'fechaCreacion'=>$fechaCreacion,
			'fechaEnvio'=>$fechaEnvio,
			'empresaEnvio'=>$empresaEnvio,
			'guiaEnvio'=>$guiaEnvio,

		];
		$transt=$Venta->selecttransaccion($idTransaccion);
		$fecha=$transt['fecha'];
		$datosCliente=$Cliente->selectClientesxid($transt['idCliente']);
		$comprat=$Venta->getProductoAcunacion($transt['idTransaccion']);
		$envio=$Venta->selectEnviotransaccion($transt['idTransaccion']);
		$mensajeEmpresa='';
		if(!isset($empresaEnvio)){
			$dataEnvio=[
				'fechaCreacion'=>$fechaCreacion,
				'fechaEnvio'=>$fechaEnvio,
				'empresaEnvio'=>$empresaEnvio,
				'guiaEnvio'=>$guiaEnvio,
			];
			if(!isset($envio)){
				
			}
			$EmperesaEnvioDatos=$Venta->selectEmpresaEnvioxidEmpresa($empresaEnvio);
			if(!isset($guiaEnvio)){
				$mensajeEmpresa="<p>Su compra se encuentra en proceso de envío con ".$EmperesaEnvioDatos['nombreEmpresa']."</p>";
				$mensajeEmpresa.="<p>Puede rastrear su paquete con la siguiente guia ".$guiaEnvio."</p>";
			}else{
				$mensajeEmpresa="<p>Su compra sera enviada con la empresa ".$EmperesaEnvioDatos['nombreEmpresa'].". por favor espere para recibir el numero de guia por estos medios para poder rastrear su pedido</p>";
			}	
		}
		
		$nombre_cliente=$datosCliente['nombre_cliente'];
		$correo=$datosCliente['correo'];
		$titulo="Actualizacion del estatus de su compra";
		$mensaje="<p>Buen día ".$nombre_cliente."</p>";
	 if($estatus==4){
			$mensaje.="<p>Su compra se encuentra en proceso de envío</p>";
			$mensaje.=$mensajeEmpresa;
		}else if($estatus==5){
			$mensaje.="<p>Su compra ha sido enviada</p>";
			$mensaje.=$mensajeEmpresa;
		}else if($estatus==6){
			$mensaje.="<p>Su compra ha sido Entregada</p>";
		}
		$mensaje.="<p>Estos son los detalles de su compra</p>
		<p>Usuario:".$correo."</p>
		<div align=center>
							<table style='margin-left:auto;margin-right:auto;>
							 	<thead>
									<th>Cliente</th>
									<th>Fecha de la compra</th>
							 	</thead>
								<tbody>
									<tr>
										<td>".$nombre_cliente."</td>
										<td>".$fecha."</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div align=center>
						<table style='margin-left:auto;margin-right:auto;>
							<thead>
								<th>Producto</th>
								<th>Cantidad</th>
								<th>Precio unitario</th>
								<th>Total</th>
						 	</thead>
							<tbody>";

							$total=0;
							foreach($comprat as $prod){
								$datosProducto=$Venta->selectProductosxid($prod['idProducto']);
								$subtotal=$prod['cantidad']*$prod['preciounitario'];
								$mensaje.="<tr><td>".$datosProducto['idProducto']."</td>
												<td>".$prod['cantidad']."</td>
												<td>".number_format($prod['preciounitario'], 2, '.', '')."</td>
												<td>".number_format($subtotal, 2, '.', '')."</td>	
											</tr>";
											$total=$total+$subtotal;							
							}

							$mensaje.="</tbody>
							</table>
							</div>
							<br>
							<div align=center>
							<table style='margin-left:auto;margin-right:auto;'>
							<tbody>
							 <tr><td>TOTAL:</td><td>".number_format($total, 2, '.', '')."</td></tr>
							</tbody>
							</ table>
							</div>";
							$this->enviarCorreo($correo,$titulo,$mensaje);
							if($estatus==4){
								$tituload="Reembolso de compra";
								$mensajead="<p>Buen día </p>
								<p>La compra del cliente ".$nombre_cliente." , con fecha del ".$transt['fecha']." acaba de marcarse para iniciar el proceso de envio con la empresa </p>";
								$mensajead.=$mensajeEmpresa;
								$this->enviarCorreoAdmn($tituload,$mensajead);
							}else if($estatus==5){
								$mensajead="<p>Buen día </p>
								<p>La compra del cliente ".$nombre_cliente." , con fecha del ".$transt['fecha']." acaba de ser reembolsado</p>";
								$mensajead.=$mensajeEmpresa;
								$this->enviarCorreoAdmn($tituload,$mensajead);
							}else if($estatus==6){
								$tituload="Compra cancelada";
								$mensajead="<p>Buen día </p>
								<p>La compra del cliente ".$nombre_cliente." , con fecha del ".$transt['fecha']." acaba de ser cancelada, reembolsado, favor de iniciar el proceso con Mercado pago o con la institución bancaria correspondiente para el reembolso</p>";
								$this->enviarCorreoAdmn($tituload,$mensajead);
							}
		echo json_encode($Venta->actualizarCompra($idTransaccion,$data));
	}

	function enviarCorreo($correo,$titulo,$mensaje)
	{
		$Venta=new Venta();

				$sender=mb_convert_encoding('Notificacion GlobalShield', "UTF-8");
				$mail = new PHPMailer(true);
				try {
				// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
				$mail->isSMTP();                                            //Send using SMTP
				$mail->Host       = 'globalshield.com.mx';                     //Set the SMTP server to send through
				$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
				$mail->Username   = 'notificacion@globalshield.com.mx';                     //SMTP username
				$mail->Password   = 'Q8op#f92';                               //SMTP password
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
				$mail->Port       = 587;    
				$mail->SMTPSecure = "tls";
				$mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true,
					),
				);
				$mail->setFrom('notificacion@globalshield.com.mx', $sender);
				$mail->addAddress($correo); 
				$mail->isHTML(true);
				$mail->Subject = mb_convert_encoding($titulo, "UTF-8");
				$mail->Body    = '
					  <!doctype html>
						  <html lang="es">
						  <head>
							  <meta charset="utf-8">
							  <title>Notificacion venta de oro</title>
							  <meta name="description" content="GlobalShield">
							  <style>
								  .centrado {
									  text-align: center;
								  }
								.table.center {
  								  margin-left:auto; 
  								  margin-right:auto;
  								}
								  .imagenPrincipal {
									  max-width: 20%;
									  min-width: 20%;
								  }
								  .divBoton {
									  background-color:#01224e;
									  width: 50%;
									  color: #FFFFFF;
									  margin-left: 25%;
									  padding-top: 30px;
									  padding-bottom: 30px;
									  border: #021b3b 1px solid;
									  border-radius: 20px;
									  font-weight: bold;
								  }
								  .bordeado {
									  border: 1px solid black;
									  border-radius: 20px;
									  padding: 10px;
								  }
							  </style>
						  </head>
						  <body>
						  <img src="https://www.globalshield.com.mx/inversion-en-oro/assets/images/logoglobalcorreo.jpg">
							  <div class="bordeado">
								  <div class="centrado">
									  <h1 class="centrado">'.mb_convert_encoding($titulo, "UTF-8").'</h1>
								  </div>
								  <div class="centrado">
									  
								  </div>
								  <div class="centrado" align="center">
									  '.mb_convert_encoding($mensaje, "UTF-8").'
								  </div>
								  <div class="centrado" align="center">
					  
								  </div>
							  </div>
						  </body>
					  </html>';
					  $mail->send();
						//   echo 'Message has been sent';
					  } catch (Exception $e) {
						//   echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
					  }
	}

	function enviarCorreoAdmn($titulo,$mensaje)
	{	

				$Venta=new Venta();
				$sender=mb_convert_encoding('Notificacion GlobalShield', "UTF-8");
				$mail = new PHPMailer(true);
				try {
				// $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
				$mail->isSMTP();                                            //Send using SMTP
				$mail->Host       = 'globalshield.com.mx';                     //Set the SMTP server to send through
				$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
				$mail->Username   = 'notificacion@globalshield.com.mx';                     //SMTP username
				$mail->Password   = 'Q8op#f92';                               //SMTP password
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
				$mail->Port       = 587;    
				$mail->SMTPSecure = "tls";
				$mail->SMTPOptions = array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true,
					),
				);
				$mail->setFrom('notificacion@globalshield.com.mx', $sender);
			 
				$admincorreos=$Venta->obtenerUsuariosAdmin();
				$numerocorreo=0;
				foreach($admincorreos as $prod){
					$numerocorreo=$numerocorreo+1;
					if($numerocorreo==1){
						$mail->addAddress($prod['correo']);
					}else if($numerocorreo>1){
						$mail->AddCC($prod['correo']);
					}
				}
				$mail->isHTML(true);
				$mail->Subject = mb_convert_encoding($titulo, "UTF-8");
				$mail->Body    = '
					  <!doctype html>
						  <html lang="es">
						  <head>
							  <meta charset="utf-8">
							  <title>Notificacion venta de oro</title>
							  <meta name="description" content="GlobalShield">
							  <style>
								  .centrado {
									  text-align: center;
								  }
								.table.center {
  								  margin-left:auto; 
  								  margin-right:auto;
  								}
								  .imagenPrincipal {
									  max-width: 20%;
									  min-width: 20%;
								  }
								  .divBoton {
									  background-color:#01224e;
									  width: 50%;
									  color: #FFFFFF;
									  margin-left: 25%;
									  padding-top: 30px;
									  padding-bottom: 30px;
									  border: #021b3b 1px solid;
									  border-radius: 20px;
									  font-weight: bold;
								  }
								  .bordeado {
									  border: 1px solid black;
									  border-radius: 20px;
									  padding: 10px;
								  }
							  </style>
						  </head>
						  <body>
						  <img src="https://www.globalshield.com.mx/inversion-en-oro/assets/images/logoglobalcorreo.jpg">
							  <div class="bordeado">
								  <div class="centrado">
									  <h1 class="centrado">'.mb_convert_encoding($titulo, "UTF-8").'</h1>
								  </div>
								  <div class="centrado">
									  
								  </div>
								  <div class="centrado" align="center">
									  '.mb_convert_encoding($mensaje, "UTF-8").'
								  </div>
								  <div class="centrado" align="center">
					  
								  </div>
							  </div>
						  </body>
					  </html>';
					  $mail->send();
						//   echo 'Message has been sent';
					  } catch (Exception $e) {
						//   echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
					  }
	}
}?>