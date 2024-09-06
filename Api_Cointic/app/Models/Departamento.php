<?php
namespace App\Models;
use CodeIgniter\Model;
class Departamento extends Model
{
    function getDepartamentos($page, $pageSize, $sort, $order)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatDepartamentos');
        $builder->select("CatDepartamentos.*");
		$builder->orderBy('CatDepartamentos.idDepartamento', $order);
		$builder->orderBy('nombreDepartamento', $order);
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroDepartamentos($page, $pageSize, $sort, $order)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatDepartamentos');
		$builder->select("*");
		return $builder->get()->getNumRows();
	}
    function insert($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatDepartamentos');
		$builder->insert($data);
		return $db->insertID();
	}

    function actualizarDepartamentos($idDepartamento= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatDepartamentos');
		$builder->where("idDepartamento", $idDepartamento);
		$builder->update($data);
	}

    
	function deleteDepartamentos($idDepartamento= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('CatDepartamentos');
		$builder->where("idDepartamento", $idDepartamento);
		$builder->delete();
	}
    function selectDepartamentosxid($idDepartamento)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatDepartamentos');
		$builder->select('CatDepartamentos.*');
		$builder->where('idDepartamento', $idDepartamento);
		return $builder->get()->getRowArray();
	}
}
?>