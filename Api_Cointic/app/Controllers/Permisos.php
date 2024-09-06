<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Permiso;
use App\Models\WebToken;
class Permisos extends ResourceController
{

	function getPermisosUsuario() {
        $Permiso=new Permiso();
		$WebToken=new WebToken();
		$jwt = $WebToken->decodificarJWT($this->request->getPost("jwt"));
		$idPerfil = $this->request->getPost("idPerfil"); // este es el id del que se quieren obtener los permisos
		if(empty($idPerfil)) {
			$idPerfil = $jwt->idPerfil;
		}
		echo json_encode($Permiso->getPermisosUsuario($idPerfil));
	}

    
	function asignarPermiso()
	{
        $Permiso=new Permiso();
		$WebToken=new WebToken();
		$jwt = $WebToken->decodificarJWT($this->request->getPost("jwt"));
		$idPerfil = $this->request->getPost("idPerfil");
		$permiso = $this->request->getPost("valor");
		$campo = $this->request->getPost("columna");
		$idModulo = $this->request->getPost("idModulo");
		if($idPerfil==1){
			return;
		}
		$this->validarExistencia($idPerfil, $idModulo);
		$Permiso->actualizarPermiso($idPerfil, $idModulo, array($campo => $permiso));
		echo json_encode([
			'message' => "Se asignaron permisos"
		]);
	}
    function validarExistencia($idPerfil, $idModulo)
	{
        $Permiso=new Permiso();
		if($idPerfil==1)
			return;
		$Permiso->validacionExistencia($idPerfil, $idModulo);
	}
}
?>