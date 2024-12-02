<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Archivo;
use App\Models\Sonic;
use App\Models\WebToken;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx; 
class Sonics extends ResourceController
{
	

	function LeerExcel(){
		$Sonic=new Sonic();
		$Archivo=new Archivo();
		$WebToken=new WebToken();
		$jwt=$this->request->getPost('jwt');
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$archivo = $Archivo->subirArchivoExcel("archivo");

		// $Contador=0;
		$Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadSheet = $Reader->load($archivo['directorio'] );//cargo el archivo
		$excelSheet = $spreadSheet->getActiveSheet(); //<- obtienen la hoja activa
		$spreadSheetAry = $excelSheet->toArray();// <-meten todo lo que esta en la hoja activa en un arreglo
		$sheetCount = count($spreadSheetAry);
		for ($i = 1; $i < $sheetCount; $i++) {
			if($spreadSheetAry[$i][1]!="SKU"){
			// $Unit = 0.0;
			// if (isset($spreadSheetAry[$i][1])) {	
			// $Unit = $spreadSheetAry[$i][1];
			// }
			$SKU = 0.0;
			if (isset($spreadSheetAry[$i][1])) {
			$SKU = $spreadSheetAry[$i][1];
			}
			$Description = 0.0;
			if (isset($spreadSheetAry[$i][2])) {
			$Description = $spreadSheetAry[$i][2];
			}
			$Precio = 0.0;
			if (isset($spreadSheetAry[$i][3])) {
			$Precio = $spreadSheetAry[$i][3];
			}
			// $Contract_1Yr= 0.0;
			// if (isset($spreadSheetAry[$i][4])) {
			// $Contract_1Yr = $spreadSheetAry[$i][4];
			// }
			// $Contract_2Yr = 0.0;
			// if (isset($spreadSheetAry[$i][6])) {
			// $Contract_2Yr = $spreadSheetAry[$i][6];
			// }
			// $Contract_3Yr = 0.0;
			// if (isset($spreadSheetAry[$i][7])) {
			// $Contract_3Yr = $spreadSheetAry[$i][7];
			// }
			// $Contract_4Yr = 0.0;
			// if (isset($spreadSheetAry[$i][8])) {
			// $Contract_4Yr = $spreadSheetAry[$i][8];
			// }
			// $Contract_5Yr = 0.0;
			// if (isset($spreadSheetAry[$i][9])) {
			// $Contract_5Yr = $spreadSheetAry[$i][9];
			// }
			// $Comments = 0.0;
			// if (isset($spreadSheetAry[$i][10])) {
			// $Comments = $spreadSheetAry[$i][10];
			// }
			$HardwareorSoftware	 = 0.0;
			if (isset($spreadSheetAry[$i][4])) {
			$HardwareorSoftware	 = $spreadSheetAry[$i][4];
			}
			$idrel = 0.0;
			if (isset($spreadSheetAry[$i][5])) {
			$idrel = $spreadSheetAry[$i][5];
			}
			 $data=[
			// 'Unit'=>$Unit ,
			'SKU'=>$SKU ,
			'Description'=>$Description ,
			'Precio'=>$Precio ,
			// 'Contract_1Yr'=>$Contract_1Yr ,
			// 'Contract_2Yr'=>$Contract_2Yr ,
			// 'Contract_3Yr'=>$Contract_3Yr ,
			// 'Contract_4Yr'=>$Contract_4Yr ,
			// 'Contract_5Yr'=>$Contract_5Yr ,
			// 'Comments'=>$Comments ,
			'HardwareorSoftware'=>$HardwareorSoftware ,
			'idrel'=>$idrel ,
			 ];
			 $existe=$Sonic->selectProductosxsku($SKU);
			 	if(isset($existe)&&$data['SKU']!=0){
					$Sonic->actualizarProductos($existe['idproducto_Sonic'],$data);
			 	}else if($data['SKU']!=0){
						$Sonic->crearProducto($data);
					}
					
				}
			}
			$Archivo->borrarArchivo($archivo['directorio']);
			echo json_encode([
				'message' => "Se han dado de alta los productos"
			]);
	}



    function obtenerProductos() {
        $Sonic=new Sonic();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		$filtro = $this->request->getPost("filtro");
		echo json_encode($Sonic->getProductos($page, $pageSize, $sort, $order,$filtro));
	}
	function obtenerNumeroProductos() {
        $Sonic=new Sonic();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		$filtro = $this->request->getPost("filtro");
		echo json_encode($Sonic->getNumeroProductos($page, $pageSize, $sort, $order,$filtro));
	}

    function CrearProducto() {
        $Sonic=new Sonic();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
        // $Unit = $this->request->getPost("Unit");
        $SKU = $this->request->getPost("SKU");
        $Description = $this->request->getPost("Description");
        $Precio = $this->request->getPost("Precio");
        // $Contract_1Yr = $this->request->getPost("Contract_1Yr");
        // $Contract_2Yr = $this->request->getPost("Contract_2Yr");
        // $Contract_3Yr = $this->request->getPost("Contract_3Yr");
        // $Contract_4Yr = $this->request->getPost("Contract_4Yr");
        // $Contract_5Yr = $this->request->getPost("Contract_5Yr");
        // $Comments = $this->request->getPost("Comments");
        $HardwareorSoftware	 = $this->request->getPost("HardwareorSoftware");
		$idrel = $this->request->getPost('idrel');
		
        $data=[
			// 'Unit'=>$Unit ,
			'SKU'=>$SKU ,
			'Description'=>$Description ,
			'Precio'=>$Precio ,
			// 'Contract_1Yr'=>$Contract_1Yr ,
			// 'Contract_2Yr'=>$Contract_2Yr ,
			// 'Contract_3Yr'=>$Contract_3Yr ,
			// 'Contract_4Yr'=>$Contract_4Yr ,
			// 'Contract_5Yr'=>$Contract_5Yr ,
			// 'Comments'=>$Comments ,
			'HardwareorSoftware'=>$HardwareorSoftware ,
			'idrel'=>$idrel ,
		];
		$Sonic->crearProducto($data);
		echo json_encode([
			'message' => "Se ha dado de alta el producto"
		]);
	}

	function obtenerRelProductosSolucion(){
		$Sonic=new Sonic();
		echo json_encode($Sonic->selectRelProductosSolucion());
	}
    function ActualizarProducto() {
        $Sonic=new Sonic();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idproducto_Sonic = $this->request->getPost("idproducto_Sonic"); 
        // $Unit = $this->request->getPost("Unit");
		$SKU = $this->request->getPost("SKU");
        $Description = $this->request->getPost("Description");
        $Precio = $this->request->getPost("Precio");
        // $Contract_1Yr = $this->request->getPost("Contract_1Yr");
        // $Contract_2Yr = $this->request->getPost("Contract_2Yr");
        // $Contract_3Yr = $this->request->getPost("Contract_3Yr");
        // $Contract_4Yr = $this->request->getPost("Contract_4Yr");
        // $Contract_5Yr = $this->request->getPost("Contract_5Yr");
        // $Comments = $this->request->getPost("Comments");
        $HardwareorSoftware = $this->request->getPost("HardwareorSoftware");
        $idrel = $this->request->getPost("idrel");
        $data=[
			// 'Unit'=>$Unit,
			'SKU'=>$SKU ,
			'Description'=>$Description ,
			'Precio'=>$Precio ,
			// 'Contract_1Yr'=>$Contract_1Yr ,
			// 'Contract_2Yr'=>$Contract_2Yr ,
			// 'Contract_3Yr'=>$Contract_3Yr ,
			// 'Contract_4Yr'=>$Contract_4Yr ,
			// 'Contract_5Yr'=>$Contract_5Yr ,
			// 'Comments'=>$Comments ,
			'HardwareorSoftware'=>$HardwareorSoftware ,
			'idrel'=>$idrel ,
		];
		$Sonic->actualizarProductos($idproducto_Sonic,$data);
		
		echo json_encode([
			'message' => "Se ha actualizado el Producto"
		]);
	}

    function EliminarProducto() {
        $Sonic=new Sonic();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idproducto_Sonic = $this->request->getPost("idproducto_Sonic");
		$Sonic->deleteProductos($idproducto_Sonic);
		echo json_encode([
			'message' => "Se ha eliminado el Producto"
		]);
	}

    function obtenerProductoxId() {
        $Sonic=new Sonic();
		$idproducto_Sonic = $this->request->getPost("idproducto_Sonic"); 
		echo json_encode($Sonic->selectProductosxid($idproducto_Sonic));
	}


}
?>