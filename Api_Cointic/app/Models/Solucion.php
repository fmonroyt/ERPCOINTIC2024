<?php
namespace App\Models;
use CodeIgniter\Model;
class Solucion extends Model
{
    function getSoluciones($page, $pageSize, $sort, $order)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatSoluciones');
        $builder->select("CatSoluciones.*");
		$builder->orderBy('CatSoluciones.idsoluciones', $order);
		$builder->orderBy('nombre', $order);
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroSoluciones($page, $pageSize, $sort, $order)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatSoluciones');
		$builder->select("*");
		return $builder->get()->getNumRows();
	}
    function insert($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatSoluciones');
		$builder->insert($data);
		return $db->insertID();
	}

    function actualizarSoluciones($idsoluciones= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatSoluciones');
		$builder->where("idsoluciones", $idsoluciones);
		$builder->update($data);
	}

    
	function deleteSoluciones($idsoluciones= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('CatSoluciones');
		$builder->where("idsoluciones", $idsoluciones);
		$builder->delete();
	}
    function selectSolucionesxid($idsoluciones)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatSoluciones');
		$builder->select('CatSoluciones.*');
		$builder->where('idsoluciones', $idsoluciones);
		return $builder->get()->getRowArray();
	}

	function selectMarcas()
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatMarcas');
		$builder->select('CatMarcas.*');
		return $builder->get()->getResultArray();
	}

	function selectMarcasxIdSolucion($idsoluciones)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('relmarcasolucion');
		$builder->select('relmarcasolucion.idmarca as idMarcas,CatMarcas.Nombre');
		$builder->join('CatMarcas','CatMarcas.idMarcas=relmarcasolucion.idmarca');
		$builder->where("relmarcasolucion.idsoluciones", $idsoluciones);
		$builder->where("relmarcasolucion.status", 0);
		return $builder->get()->getResultArray();
	}
	function selectMarcasIdxIdSolucion($idsoluciones)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('relmarcasolucion');
		$builder->select('relmarcasolucion.idmarca');
		$builder->where("relmarcasolucion.idsoluciones", $idsoluciones);
		return $builder->get()->getResultArray();
	}

	function crearRelMarcas($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('relmarcasolucion');
		$builder->insert($data);
		return $db->insertID();
	}

	function desactivarMarcas($idsoluciones)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('relmarcasolucion');
		$builder->where("relmarcasolucion.idsoluciones", $idsoluciones);
		$builder->update(array("status"=>1));
	}

	function activarMarcas($idsoluciones,$idMarcas)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('relmarcasolucion');
		$builder->where("relmarcasolucion.idsoluciones", $idsoluciones);
		$builder->where("relmarcasolucion.idmarca", $idMarcas);
		$builder->update(array("status"=>0));
	}
}
?>