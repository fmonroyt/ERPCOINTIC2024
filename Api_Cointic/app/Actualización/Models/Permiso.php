<?php
namespace App\Models;
use CodeIgniter\Model;
class Permiso extends Model
{
	function getPermisosUsuario($idPerfil)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('RelPermisos');
		$builder->select("*");
		$builder->where("idPerfil", $idPerfil);
		return $builder->get()->getResultArray();
	}
	function actualizarPermiso($idPerfil, $idModulo, $data)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('RelPermisos');
		$builder->where("idPerfil", $idPerfil);
		$builder->where("idModulo", $idModulo);
		$builder->update($data);
	}
    function validacionExistencia($idPerfil, $idModulo)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('RelPermisos');
		$builder->select("*");
		$builder->where("idPerfil", $idPerfil);
		$builder->where("idModulo", $idModulo);
		$existencia=$builder->get()->getRowArray();
		if(empty($existencia))
		{
			$builder->insert(array('idPerfil' => $idPerfil, 'idModulo' => $idModulo));
		}
	}
    
    function getPermisosUsuarioModulo($idPerfil, $idModulo){
        $db      = \Config\Database::connect();
        $builder = $db->table('RelPermisos');
        $builder->select("*");
        $builder->where("idPerfil", $idPerfil);
        $builder->where("idModulo", $idModulo);
        return $builder->get()->getRowArray();
    }
    function tienePermisosUsuarioModulo($idUsuario, $idModulo)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('RelPermisos');
		$builder->select("*");
		$builder->join("CatPerfiles", "RelPermisos.idPerfil=CatPerfiles.idPerfil");
		$builder->join("CatUsuarios", "CatUsuarios.idPerfil=CatPerfiles.idPerfil");
		$builder->where("(RelPermisos.mostrar = 1 OR RelPermisos.alta = 1 OR RelPermisos.eliminar = 1 OR RelPermisos.detalle = 1 OR RelPermisos.editar = 1)");
		$builder->where("RelPermisos.idModulo", $idModulo);
		$builder->where("CatUsuarios.idUsuario", $idUsuario);
		return $builder->get()->getNumRows();
	}
}
?>