<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Archivo;
use App\Models\Sopho;
use App\Models\WebToken;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx; 
class Sophos extends ResourceController
{
	

	function LeerExcel(){
		$Sopho=new Sopho();
		$Archivo=new Archivo();
		$WebToken=new WebToken();
		$jwt=$this->request->getPost('jwt');
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$archivo = $Archivo->subirArchivoExcel("archivo");
		$Reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$spreadSheet = $Reader->load($archivo['directorio'] );//cargo el archivo
		$excelSheet = $spreadSheet->getActiveSheet(); //<- obtienen la hoja activa
		$spreadSheetAry = $excelSheet->toArray();// <-meten todo lo que esta en la hoja activa en un arreglo
		$sheetCount = count($spreadSheetAry);
		for ($i = 1; $i < $sheetCount; $i++) {
			if($spreadSheetAry[$i][1]!="SKU"){
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
		
			$idrel = 0.0;
			if (isset($spreadSheetAry[$i][4])) {
			$idrel = $spreadSheetAry[$i][4];
			}
			 $data=[
			
			'SKU'=>$SKU ,
			'Description'=>$Description,
			'Precio'=>$Precio,
			'idrel'=>$idrel,
			 ];
			 $existe=$Sopho->selectProductosxsku($SKU);
			 	if(isset($existe)&&$data['SKU']!=0){
					$Sopho->actualizarProductos($existe['idproduct_Sophos'],$data);
			 	}else if($data['SKU']!=0){
						$Sopho->crearProducto($data);
					}
					
				}
			}
			$Archivo->borrarArchivo($archivo['directorio']);
			echo json_encode([
				'message' => "Se han dado de alta los productos"
			]);
	}



    function obtenerProductos() {
        $Sopho=new Sopho();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		$filtro = $this->request->getPost("filtro");
		echo json_encode($Sopho->getProductos($page, $pageSize, $sort, $order,$filtro));
	}
	function obtenerNumeroProductos() {
        $Sopho=new Sopho();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		$filtro = $this->request->getPost("filtro");
		echo json_encode($Sopho->getNumeroProductos($page, $pageSize, $sort, $order,$filtro));
	}

    function CrearProducto() {
        $Sopho=new Sopho();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
        $SKU = $this->request->getPost("SKU");
        $Description = $this->request->getPost("Description");
        $Precio = $this->request->getPost("Precio");
		$idrel = $this->request->getPost('idrel');
		
        $data=[
			'SKU'=>$SKU ,
			'Description'=>$Description ,
			'Precio'=>$Precio,
			'idrel'=>$idrel ,
		];
		$Sopho->crearProducto($data);
		echo json_encode([
			'message' => "Se ha dado de alta el producto"
		]);
	}

	function obtenerRelProductosSolucion(){
		$Sopho=new Sopho();
		echo json_encode($Sopho->selectRelProductosSolucion());
	}
    function ActualizarProducto() {
        $Sopho=new Sopho();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idproduct_Sophos = $this->request->getPost("idproduct_Sophos"); 
		$SKU = $this->request->getPost("SKU");
        $Description = $this->request->getPost("Description");
        $Precio = $this->request->getPost("Precio");
        $idrel = $this->request->getPost("idrel");
        $data=[
			'SKU'=>$SKU ,
			'Description'=>$Description ,
			'Precio'=>$Precio ,
			'idrel'=>$idrel ,
		];
		$Sopho->actualizarProductos($idproduct_Sophos,$data);
		
		echo json_encode([
			'message' => "Se ha actualizado el Producto"
		]);
	}

    function EliminarProducto() {
        $Sopho=new Sopho();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idproduct_Sophos = $this->request->getPost("idproduct_Sophos");
		$Sopho->deleteProductos($idproduct_Sophos);
		echo json_encode([
			'message' => "Se ha eliminado el Producto"
		]);
	}

    function obtenerProductoxId() {
        $Sopho=new Sopho();
		$idproduct_Sophos = $this->request->getPost("idproduct_Sophos"); 
		echo json_encode($Sopho->selectProductosxid($idproduct_Sophos));
	}


}
?>