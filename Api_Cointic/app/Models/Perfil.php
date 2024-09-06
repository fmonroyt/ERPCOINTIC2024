<?php
namespace App\Models;
use CodeIgniter\Model;
class Perfil extends Model
{
    function getPerfiles($page, $pageSize, $sort, $order)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatPerfiles');
		$builder->select("*");
		$builder->where("CatPerfiles.idPerfil !=", 1);
		$builder->orderBy('nombrePerfil', $order);
		$offset = $pageSize * $page;
		$builder->limit($pageSize, $offset);
		return $builder->get()->getResultArray();
	}
	function getDepartamentos($page, $pageSize, $sort, $order)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatDepartamentos');
        $builder->select("CatDepartamentos.*");
		$builder->orderBy('CatDepartamentos.idDepartamento', $order);
		$builder->orderBy('nombreDepartamento', $order);
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getDepartamentos();
	}

	function getNumeroPerfiles($page, $pageSize, $sort, $order)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatPerfiles');
		$builder->select("*");
		$builder->where("CatPerfiles.idPerfil !=", 1);
		return $builder->get()->getNumRows();
	}
    function insert($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatPerfiles');
		$builder->insert($data);
		return $db->insertID();
	}

    function actualizarPerfil($idPerfil= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatPerfiles');
		$builder->where("idPerfil", $idPerfil);
		$builder->update($data);
	}

    
	function deleterPerfil($idPerfil= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('CatPerfiles');
		$builder->where("idPerfil", $idPerfil);
		$builder->delete();
	}
    function selectPerfilxid($idPerfil)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatPerfiles');
		$builder->select('CatPerfiles.*');
		$builder->where('idPerfil', $idPerfil);
		return $builder->get()->getRowArray();
	}

    function selectModulos()
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatModulo');
		$builder->select('CatModulo.*');
		return $builder->get()->getResultArray();
	}
}
?>