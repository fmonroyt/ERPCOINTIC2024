<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Archivo;
use App\Models\Contacto;
use App\Models\WebToken;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx; 
class Contactos extends ResourceController
{
	

	function LeerExcel(){
		$Contacto=new Contacto();
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
			$apellido = 0.0;
			if (isset($spreadSheetAry[$i][2])) {
			$apellido = $spreadSheetAry[$i][2];
			}
			$correo = 0.0;
			if (isset($spreadSheetAry[$i][3])) {
			$correo = $spreadSheetAry[$i][3];
			}
			$telefono= 0.0;
			if (isset($spreadSheetAry[$i][4])) {
			$telefono = $spreadSheetAry[$i][4];
			}
			$idempresaext = 0.0;
			if (isset($spreadSheetAry[$i][5])) {
			$idempresaext = $spreadSheetAry[$i][5];
			}
			 $data=[
			
			'nombre'=>$nombre ,
			'apellido'=>$apellido ,
			'correo'=>$correo ,
			'telefono'=>$telefono ,
			'idempresaext'=>$idempresaext ,
			 ];
			 $existe=$Contacto->selectContactoxnombre($nombre);
			 	if(isset($existe)&&$data['nombre']!=0){
					$Contacto->actualizarContacto($existe['idcontacto'],$data);
			 	}else if($data['nombre']!=0){
						$Contacto->crearContacto($data);
					}
					
				}
			}
			$Archivo->borrarArchivo($archivo['directorio']);
			echo json_encode([
				'message' => "Se han dado de alta el contacto"
			]);
	}



    function obtenerContacto() {
        $Contacto=new Contacto();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		$filtro = $this->request->getPost("filtro");
		echo json_encode($Contacto->getContactos($page, $pageSize, $sort, $order,$filtro));
	}

	function obtenerNumeroContacto() {
        $Contacto=new Contacto();
		$WebToken=new WebToken();
		$sort = $this->request->getPost("sort");
		$order = $this->request->getPost("order");
		$page = $this->request->getPost("page");
		$pageSize = $this->request->getPost("pageSize");
		$filtro = $this->request->getPost("filtro");
		echo json_encode($Contacto->getNumeroContactos($page, $pageSize, $sort, $order,$filtro));
	}

    function CrearContacto() {
        $Contacto=new Contacto();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		$idUsuario = $jwt->idUsuario; 
        $nombre = $this->request->getPost("nombre");
        $apellido = $this->request->getPost("apellido");
        $correo = $this->request->getPost("correo");
        $telefono = $this->request->getPost("telefono");
        $idempresaext = $this->request->getPost("idempresaext");
        
        $data=[
			'nombre'=>$nombre,
			'apellido'=>$apellido,
			'correo'=>$correo,
			'telefono'=>$telefono ,
			'idempresaext'=>$idempresaext ,
		];
		$Contacto->CrearContacto($data);
		echo json_encode([
			'message' => "Se ha dado de alta el producto"
		]);
	}
	
	function obtenerIdempresaext(){
		$Contacto=new Contacto();
		echo json_encode($Contacto->selectIdempresaext());
	}
    function ActualizarContactos() {
        $Contacto=new Contacto();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		// $idEmpresaExt = $jwt->idEmpresaExt;
		$idcontacto = $this->request->getPost("idcontacto"); 
    	$nombre = $this->request->getPost("nombre");
		$apellido = $this->request->getPost("apellido");
		$correo = $this->request->getPost("correo");
		$telefono = $this->request->getPost("telefono");
		$idempresaext = $this->request->getPost("idempresaext");
		$data=[
			'nombre'=>$nombre,
			'apellido'=>$apellido ,
			'correo'=>$correo ,
			'telefono'=>$telefono ,
			'idempresaext'=>$idempresaext ,
		];
		$Contacto->actualizarContacto($idcontacto,$data);
		
		echo json_encode([
			'message' => "Se ha actualizado El contacto"
		]);
	}

    function EliminarContacto() {
        $Contacto=new Contacto();
		$WebToken=new WebToken();
		$jwt = $this->request->getPost("jwt");
		$jwt = $WebToken->decodificarJWT($jwt);
		// $idempresaext = $jwt->idempresaext;
		// $idEmpresaExt = $jwt->idEmpresaExt;
		$idUsuario = $jwt->idUsuario;
		$idcontacto = $this->request->getPost("idcontacto");
		$Contacto->deleteContacto($idcontacto);
		echo json_encode([
			'message' => "Se ha eliminado el contacto"
		]);
	}

    function obtenerContactos() {
        $Contacto=new Contacto();
		$idcontacto = $this->request->getPost("idcontacto"); 
		echo json_encode($Contacto->selectContactoxid($idcontacto));
	}

}
?>