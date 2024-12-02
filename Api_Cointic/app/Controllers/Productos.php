<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Archivo;
use App\Models\Producto;
use App\Models\WebToken;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx; 
class Productos extends ResourceController
{
	

	function LeerExcel(){
		$Producto=new Producto();
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
			if($spreadSheetAry[$i][1]!="UNIT"){
			$Unit = 0.0;
			if (isset($spreadSheetAry[$i][1])) {	
			$Unit = $spreadSheetAry[$i][1];
			}
			$SKU = 0.0;
			if (isset($spreadSheetAry[$i][2])) {
			$SKU = $spreadSheetAry[$i][2];
			}
			$Description = 0.0;
			if (isset($spreadSheetAry[$i][3])) {
			$Description = $spreadSheetAry[$i][3];
			}
			$Price = 0.0;
			if (isset($spreadSheetAry[$i][4])) {
			$Price = $spreadSheetAry[$i][4];
			}
			$Contract_1Yr= 0.0;
			if (isset($spreadSheetAry[$i][5])) {
			$Contract_1Yr = $spreadSheetAry[$i][5];
			}
			$Contract_2Yr = 0.0;
			if (isset($spreadSheetAry[$i][6])) {
			$Contract_2Yr = $spreadSheetAry[$i][6];
			}
			$Contract_3Yr = 0.0;
			if (isset($spreadSheetAry[$i][7])) {
			$Contract_3Yr = $spreadSheetAry[$i][7];
			}
			$Contract_4Yr = 0.0;
			if (isset($spreadSheetAry[$i][8])) {
			$Contract_4Yr = $spreadSheetAry[$i][8];
			}
			$Contract_5Yr = 0.0;
			if (isset($spreadSheetAry[$i][9])) {
			$Contract_5Yr = $spreadSheetAry[$i][9];
			}
			$Comments = 0.0;
			if (isset($spreadSheetAry[$i][10])) {
			$Comments = $spreadSheetAry[$i][10];
			}
			$Category = 0.0;
			if (isset($spreadSheetAry[$i][11])) {
			$Category = $spreadSheetAry[$i][11];
			}
			$idrel = 0.0;
			if (isset($spreadSheetAry[$i][12])) {
			$idrel = $spreadSheetAry[$i][12];
			}
			 $data=[
			'Unit'=>$Unit ,
			'SKU'=>$SKU ,
			'Description'=>$Description ,
			'Price'=>$Price ,
			'Contract_1Yr'=>$Contract_1Yr ,
			'Contract_2Yr'=>$Contract_2Yr ,
			'Contract_3Yr'=>$Contract_3Yr ,
			'Contract_4Yr'=>$Contract_4Yr ,
			'Contract_5Yr'=>$Contract_5Yr ,
			'Comments'=>$Comments ,
			'Category'=>$Category ,
			'idrel'=>$idrel ,
			 ];
			 $existe=$Producto->selectProductosxsku($SKU);
			 	if(isset($existe)&&$data['Unit']!=0){
					$Producto->actualizarProductos($existe['idProducto_Fort'],$data);
			 	}else if($data['Unit']!=0){
						$Producto->crearProducto($data);
					}
					
				}
			}
			$Archivo->borrarArchivo($archivo['directorio']);
			echo json_encode([
				'message' => "Se han dado de alta los productos"
			]);
	}



    function obtenerProductos() {
        $Producto=new Producto();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		$filtro = $this->request->getPost("filtro");
		echo json_encode($Producto->getProductos($page, $pageSize, $sort, $order,$filtro));
	}
	function obtenerNumeroProductos() {
        $Producto=new Producto();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		$filtro = $this->request->getPost("filtro");
		echo json_encode($Producto->getNumeroProductos($page, $pageSize, $sort, $order,$filtro));
	}

    function CrearProducto() {
        $Producto=new Producto();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
        $Unit = $this->request->getPost("Unit");
        $SKU = $this->request->getPost("SKU");
        $Description = $this->request->getPost("Description");
        $Price = $this->request->getPost("Price");
        $Contract_1Yr = $this->request->getPost("Contract_1Yr");
        $Contract_2Yr = $this->request->getPost("Contract_2Yr");
        $Contract_3Yr = $this->request->getPost("Contract_3Yr");
        $Contract_4Yr = $this->request->getPost("Contract_4Yr");
        $Contract_5Yr = $this->request->getPost("Contract_5Yr");
        $Comments = $this->request->getPost("Comments");
        $Category = $this->request->getPost("Category");
		$idrel = $this->request->getPost('idrel');
		
        $data=[
			'Unit'=>$Unit ,
			'SKU'=>$SKU ,
			'Description'=>$Description ,
			'Price'=>$Price ,
			'Contract_1Yr'=>$Contract_1Yr ,
			'Contract_2Yr'=>$Contract_2Yr ,
			'Contract_3Yr'=>$Contract_3Yr ,
			'Contract_4Yr'=>$Contract_4Yr ,
			'Contract_5Yr'=>$Contract_5Yr ,
			'Comments'=>$Comments ,
			'Category'=>$Category ,
			'idrel'=>$idrel ,
		];
		$Producto->crearProducto($data);
		echo json_encode([
			'message' => "Se ha dado de alta el producto"
		]);
	}

	function obtenerRelProductosSolucion(){
		$Producto=new Producto();
		echo json_encode($Producto->selectRelProductosSolucion());
	}
    function ActualizarProducto() {
        $Producto=new Producto();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idProducto_Fort = $this->request->getPost("idProducto_Fort"); 
        $Unit = $this->request->getPost("Unit");
		$SKU = $this->request->getPost("SKU");
        $Description = $this->request->getPost("Description");
        $Price = $this->request->getPost("Price");
        $Contract_1Yr = $this->request->getPost("Contract_1Yr");
        $Contract_2Yr = $this->request->getPost("Contract_2Yr");
        $Contract_3Yr = $this->request->getPost("Contract_3Yr");
        $Contract_4Yr = $this->request->getPost("Contract_4Yr");
        $Contract_5Yr = $this->request->getPost("Contract_5Yr");
        $Comments = $this->request->getPost("Comments");
        $Category = $this->request->getPost("Category");
        $idrel = $this->request->getPost("idrel");
        $data=[
			'Unit'=>$Unit,
			'SKU'=>$SKU ,
			'Description'=>$Description ,
			'Price'=>$Price ,
			'Contract_1Yr'=>$Contract_1Yr ,
			'Contract_2Yr'=>$Contract_2Yr ,
			'Contract_3Yr'=>$Contract_3Yr ,
			'Contract_4Yr'=>$Contract_4Yr ,
			'Contract_5Yr'=>$Contract_5Yr ,
			'Comments'=>$Comments ,
			'Category'=>$Category ,
			'idrel'=>$idrel ,
		];
		$Producto->actualizarProductos($idProducto_Fort,$data);
		
		echo json_encode([
			'message' => "Se ha actualizado el Producto"
		]);
	}

    function EliminarProducto() {
        $Producto=new Producto();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idProducto_Fort = $this->request->getPost("idProducto_Fort");
		$Producto->deleteProductos($idProducto_Fort);
		echo json_encode([
			'message' => "Se ha eliminado el Producto"
		]);
	}

    function obtenerProductoxId() {
        $Producto=new Producto();
		$idProducto_Fort = $this->request->getPost("idProducto_Fort"); 
		echo json_encode($Producto->selectProductosxid($idProducto_Fort));
	}


}
?>