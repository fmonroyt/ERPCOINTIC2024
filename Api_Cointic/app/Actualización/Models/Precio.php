<?php
namespace App\Models;
use CodeIgniter\Model;
class Precio extends Model
{
	function getPrecios($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial, $fechaFinal)
	{
		if ($fechaInicial){
			$fechaInicial = $this->db->escape($fechaInicial);
		}else{
			$fechaInicial = $this->db->escape("1950-01-01");}
		if ($fechaFinal){
			$fechaFinal = $this->db->escape($fechaFinal);}
		else{
			$fechaFinal = $this->db->escape("2032-12-31");}
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdHistoricoPrecios');
		$builder->select("ProdHistoricoPrecios.*,G.nombreGasolinera as nombreGasolinera");
		$builder->join("CatGasolineras G", "G.idGasolinera=ProdHistoricoPrecios.idGasolinera","LEFT");
		$builder->where('G.idGasolineraCompite', 0);
		if($idGasolinera!=0 && $idGasolinera!=null){
			$builder->where('ProdHistoricoPrecios.idGasolinera', $idGasolinera);
		}
		if(!empty($fechaInicial)){
			$builder->where("ProdHistoricoPrecios.Fecha >=$fechaInicial");
			}
		if(!empty($fechaFinal)){
			$builder->where("ProdHistoricoPrecios.Fecha <= $fechaFinal");
		}
		$builder->orderBy('ProdHistoricoPrecios.Consecutivo', 'asc');
		$builder->orderBy('ProdHistoricoPrecios.idHistoricoPrecios', 'asc');
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroPrecios($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial, $fechaFinal)
	{
		if ($fechaInicial){
			$fechaInicial = $this->db->escape($fechaInicial);
		}else{
			$fechaInicial = $this->db->escape("2050-01-01");}
		if ($fechaFinal){
			$fechaFinal = $this->db->escape($fechaFinal);}
		else{
			$fechaFinal = $this->db->escape("2050-12-31");}
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdHistoricoPrecios');
		$builder->select("ProdHistoricoPrecios.*");
		$builder->join("CatGasolineras G", "G.idGasolinera=ProdHistoricoPrecios.idGasolinera","LEFT");
		$builder->where('G.idGasolineraCompite', 0);
		if($idGasolinera!=0 && $idGasolinera!=null){
			$builder->where('ProdHistoricoPrecios.idGasolinera', $idGasolinera);
		}		
		if(!empty($fechaInicial)){
			$builder->where("ProdHistoricoPrecios.Fecha >=$fechaInicial");
			}
		if(!empty($fechaFinal)){
			$builder->where("ProdHistoricoPrecios.Fecha <= $fechaFinal");
		}
		return $builder->get()->getNumRows();
	}
	function selectPreciosxConsecutivo($Consecutivo)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdHistoricoPrecios');
        $builder->select("ProdHistoricoPrecios.*,G.nombreGasolinera as nombreGasolinera");
		$builder->join("CatGasolineras G", "G.idGasolinera=ProdHistoricoPrecios.idGasolinera","LEFT");
		$builder->where('ProdHistoricoPrecios.Consecutivo', $Consecutivo);
		$builder->orderBy('ProdHistoricoPrecios.idHistoricoPrecios', 'asc');
		return $builder->get()->getResultArray();
	}
	
	function getPreciosContador()
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdHistoricoPrecios');
		$builder->select("ProdHistoricoPrecios.*");
		$builder->join("CatGasolineras G", "G.idGasolinera=ProdHistoricoPrecios.idGasolinera","LEFT");
		$builder->where('G.idGasolineraCompite', 0);
		$builder->orderBy('ProdHistoricoPrecios.Consecutivo', 'desc');
		$builder->limit(1);
		return $builder->get()->getRowArray();
	}
	function insert($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('ProdHistoricoPrecios');
		$builder->insert($data);
		return $db->insertID();
	}

	function actualizarPrecio($idHistoricoPrecios= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdHistoricoPrecios');
		$builder->where("idHistoricoPrecios", $idHistoricoPrecios);
		$builder->update($data);
	}

	
	function deletePrecio($Consecutivo= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('ProdHistoricoPrecios');
		$builder->where("Consecutivo", $Consecutivo);
		$builder->delete();
	}

	function selectUltimosPreciosxGasolinera($idGasolinera)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdHistoricoPrecios');
        $builder->select("ProdHistoricoPrecios.*,G.nombreGasolinera as nombreGasolinera");
		$builder->join("CatGasolineras G", "G.idGasolinera=ProdHistoricoPrecios.idGasolinera","LEFT");
		$builder->where('ProdHistoricoPrecios.idGasolinera', $idGasolinera);
		$builder->orderBy('ProdHistoricoPrecios.Fecha', 'desc');
		$builder->orderBy('ProdHistoricoPrecios.Consecutivo', 'desc');
		$builder->limit(1);
		return $builder->get()->getRowArray();
	}
	function selectGasolineraCompetenciasxid($idGasolinera)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('CatGasolineras');
		$builder->select('CatGasolineras.*');
		// $builder->where('idGasolinera', $idGasolinera);
		$builder->where('idGasolineraCompite', $idGasolinera);
		return $builder->get()->getResultArray();
	}
}
?>