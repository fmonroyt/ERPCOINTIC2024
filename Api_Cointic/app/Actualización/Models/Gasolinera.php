<?php
namespace App\Models;
use CodeIgniter\Model;
class Gasolinera extends Model
{
    function getGasolineras($page, $pageSize, $sort, $order)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatGasolineras');
        $builder->select("CatGasolineras.*,compite.nombreGasolinera as Compite");
        $builder->join("CatGasolineras compite", "compite.idGasolinera=CatGasolineras.idGasolineraCompite","LEFT");
		$builder->orderBy('CatGasolineras.idGasolinera', $order);
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroGasolineras($page, $pageSize, $sort, $order)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatGasolineras');
		$builder->select("*");
		return $builder->get()->getNumRows();
	}
    function insert($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatGasolineras');
		$builder->insert($data);
		return $db->insertID();
	}

    function actualizarGasolinera($idGasolinera= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatGasolineras');
		$builder->where("idGasolinera", $idGasolinera);
		$builder->update($data);
	}

    
	function deleteGasolinera($idGasolinera= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('CatGasolineras');
		$builder->where("idGasolinera", $idGasolinera);
		$builder->delete();
	}
    function selectGasolineraxid($idGasolinera)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatGasolineras');
		$builder->select('CatGasolineras.*');
		$builder->where('idGasolinera', $idGasolinera);
		return $builder->get()->getRowArray();
	}
}
?>