<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Archivo;
use App\Models\Watchguard;
use App\Models\WebToken;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx; 
class Watchguards extends ResourceController
{
	

	function LeerExcel(){
		$Watchguard=new Watchguard();
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
			$Category = 0.0;
			if (isset($spreadSheetAry[$i][4])) {
			$Category= $spreadSheetAry[$i][4];
			}
			$idrel = 0.0;
			if (isset($spreadSheetAry[$i][5])) {
			$idrel = $spreadSheetAry[$i][5];
			}
			 $data=[
			
			'SKU'=>$SKU ,
			'Description'=>$Description,
			'Precio'=>$Precio,
			'Category'=>$Category,
			'idrel'=>$idrel,
			 ];
			 $existe=$Watchguard->selectProductosxsku($SKU);
			 	if(isset($existe)&&$data['SKU']!=0){
					$Watchguard->actualizarProductos($existe['idproduct_Watchguard'],$data);
			 	}else if($data['SKU']!=0){
						$Watchguard->crearProducto($data);
					}
					
				}
			}
			$Archivo->borrarArchivo($archivo['directorio']);
			echo json_encode([
				'message' => "Se han dado de alta los productos"
			]);
	}



    function obtenerProductos() {
        $Watchguard=new Watchguard();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		$filtro = $this->request->getPost("filtro");
		echo json_encode($Watchguard->getProductos($page, $pageSize, $sort, $order,$filtro));
	}
	function obtenerNumeroProductos() {
        $Watchguard=new Watchguard();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		$filtro = $this->request->getPost("filtro");
		echo json_encode($Watchguard->getNumeroProductos($page, $pageSize, $sort, $order,$filtro));
	}

    function CrearProducto() {
        $Watchguard=new Watchguard();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
        $SKU = $this->request->getPost("SKU");
        $Description = $this->request->getPost("Description");
        $Precio = $this->request->getPost("Precio");
        $Category	 = $this->request->getPost("Category");
		$idrel = $this->request->getPost('idrel');
		
        $data=[
			'SKU'=>$SKU ,
			'Description'=>$Description ,
			'Precio'=>$Precio,
			'Category'=>$Category ,
			'idrel'=>$idrel ,
		];
		$Watchguard->crearProducto($data);
		echo json_encode([
			'message' => "Se ha dado de alta el producto"
		]);
	}

	function obtenerRelProductosSolucion(){
		$Watchguard=new Watchguard();
		echo json_encode($Watchguard->selectRelProductosSolucion());
	}
    function ActualizarProducto() {
        $Watchguard=new Watchguard();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idproduct_Watchguard = $this->request->getPost("idproduct_Watchguard"); 
		$SKU = $this->request->getPost("SKU");
        $Description = $this->request->getPost("Description");
        $Precio = $this->request->getPost("Precio");
        $Category = $this->request->getPost("Category");
        $idrel = $this->request->getPost("idrel");
        $data=[
			'SKU'=>$SKU ,
			'Description'=>$Description ,
			'Precio'=>$Precio ,
			'Category'=>$Category ,
			'idrel'=>$idrel ,
		];
		$Watchguard->actualizarProductos($idproduct_Watchguard,$data);
		
		echo json_encode([
			'message' => "Se ha actualizado el Producto"
		]);
	}

    function EliminarProducto() {
        $Watchguard=new Watchguard();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idproduct_Watchguard = $this->request->getPost("idproduct_Watchguard");
		$Watchguard->deleteProductos($idproduct_Watchguard);
		echo json_encode([
			'message' => "Se ha eliminado el Producto"
		]);
	}

    function obtenerProductoxId() {
        $Watchguard=new Watchguard();
		$idproduct_Watchguard = $this->request->getPost("idproduct_Watchguard"); 
		echo json_encode($Watchguard->selectProductosxid($idproduct_Watchguard));
	}


}
?>