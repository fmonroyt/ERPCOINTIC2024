<?php
namespace App\Models;
use CodeIgniter\Model;
class Marca extends Model
{
    function getMarcas($page, $pageSize, $sort, $order)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatMarcas');
        $builder->select("CatMarcas.*");
		$builder->orderBy('CatMarcas.idMarcas', $order);
		$builder->orderBy('Nombre', $order);
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroMarcas($page, $pageSize, $sort, $order)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatMarcas');
		$builder->select("*");
		return $builder->get()->getNumRows();
	}
    function insert($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatMarcas');
		$builder->insert($data);
		return $db->insertID();
	}
	

    function actualizarMarcas($idMarcas= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatMarcas');
		$builder->where("idMarcas", $idMarcas);
		$builder->update($data);
	}

    
	function deleteMarcas($idMarcas= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('CatMarcas');
		$builder->where("idMarcas", $idMarcas);
		$builder->delete();
	}
    function selectMarcasxid($idMarcas)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatMarcas');
		$builder->select('CatMarcas.*');
		$builder->where('idMarcas', $idMarcas);
		return $builder->get()->getRowArray();
	}
}
?>