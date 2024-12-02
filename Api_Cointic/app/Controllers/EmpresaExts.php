<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Archivo;
use App\Models\EmpresaExt;
use App\Models\WebToken;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx; 
class EmpresaExts extends ResourceController
{
	

	function LeerExcel(){
		$EmpresaExt=new EmpresaExt();
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
			if($spreadSheetAry[$i][1]!="nombre"){
			$nombre = 0.0;
			if (isset($spreadSheetAry[$i][1])) {	
			$nombre = $spreadSheetAry[$i][1];
			}
			$telefono = 0.0;
			if (isset($spreadSheetAry[$i][2])) {
			$telefono = $spreadSheetAry[$i][2];
			}
			$ciudad = 0.0;
			if (isset($spreadSheetAry[$i][3])) {
			$ciudad = $spreadSheetAry[$i][3];
			}
			$pais= 0.0;
			if (isset($spreadSheetAry[$i][4])) {
			$pais = $spreadSheetAry[$i][4];
			}
			$idusuario = 0.0;
			if (isset($spreadSheetAry[$i][5])) {
			$idusuario = $spreadSheetAry[$i][5];
			}
			 $data=[
			
			'nombre'=>$nombre ,
			'telefono'=>$telefono ,
			'ciudad'=>$ciudad ,
			'pais'=>$pais ,
			'idusuario'=>$idusuario ,
			 ];
			 $existe=$EmpresaExt->selectEmpresaxnombre($nombre);
			 	if(isset($existe)&&$data['nombre']!=0){
					$EmpresaExt->actualizarEmpresa($existe['idEmpresaExt'],$data);
			 	}else if($data['nombre']!=0){
						$EmpresaExt->crearEmpresa($data);
					}
					
				}
			}
			$Archivo->borrarArchivo($archivo['directorio']);
			echo json_encode([
				'message' => "Se han dado de alta la empresa"
			]);
	}



    function obtenerEmpresaExt() {
        $EmpresaExt=new EmpresaExt();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		$filtro = $this->request->getPost("filtro");
		echo json_encode($EmpresaExt->getEmpresas($page, $pageSize, $sort, $order,$filtro));
	}

	function obtenerNumeroEmpresaExt() {
        $EmpresaExt=new EmpresaExt();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		$filtro = $this->request->getPost("filtro");
		echo json_encode($EmpresaExt->getNumeroEmpresas($page, $pageSize, $sort, $order,$filtro));
	}

    function CrearEmpresaExt() {
        $EmpresaExt=new EmpresaExt();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
        $nombre = $this->request->getPost("nombre");
        $telefono = $this->request->getPost("telefono");
        $ciudad = $this->request->getPost("ciudad");
        $pais = $this->request->getPost("pais");
        $idusuario = $this->request->getPost("idusuario");
        
        $data=[
			'nombre'=>$nombre,
			'telefono'=>$telefono,
			'ciudad'=>$ciudad,
			'pais'=>$pais ,
			'idusuario'=>$idusuario ,
		];
		$EmpresaExt->crearEmpresa($data);
		echo json_encode([
			'message' => "Se ha dado de alta el producto"
		]);
	}
	
	function obtenerIdUsuario(){
		$EmpresaExt=new EmpresaExt();
		echo json_encode($EmpresaExt->selectIdUsuario());
	}
    function ActualizarEmpresaExt() {
        $EmpresaExt=new EmpresaExt();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idEmpresaExt = $this->request->getPost("idEmpresaExt"); 
    $nombre = $this->request->getPost("nombre");
		$telefono = $this->request->getPost("telefono");
    $ciudad = $this->request->getPost("ciudad");
    $pais = $this->request->getPost("pais");
    $idusuario = $this->request->getPost("idusuario");
    $data=[
			'nombre'=>$nombre,
			'telefono'=>$telefono ,
			'ciudad'=>$ciudad ,
			'pais'=>$pais ,
			'idusuario'=>$idusuario ,
		];
		$EmpresaExt->actualizarEmpresa($idEmpresaExt,$data);
		
		echo json_encode([
			'message' => "Se ha actualizado la empresa"
		]);
	}

    function EliminarEmpresaExt() {
        $EmpresaExt=new EmpresaExt();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario;
		$idEmpresaExt = $this->request->getPost("idEmpresaExt");
		$EmpresaExt->deleteEmpresa($idEmpresaExt);
		echo json_encode([
			'message' => "Se ha eliminado la empresa"
		]);
	}

    function obtenerEmpresasExt() {
        $EmpresaExt=new EmpresaExt();
		$idEmpresaExt = $this->request->getPost("idEmpresaExt"); 
		echo json_encode($EmpresaExt->selectEmpresaxid($idEmpresaExt));
	}

}
?>