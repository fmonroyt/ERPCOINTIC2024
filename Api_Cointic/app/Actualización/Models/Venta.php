<?php
namespace App\Models;
use CodeIgniter\Model;
class Venta extends Model
{
    function getVentas($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial, $fechaFinal)
	{
		if ($fechaInicial){
			$fechaInicial = $this->db->escape($fechaInicial);
		}else{
			$fechaInicial = $this->db->escape("1950-01-01");}
		if ($fechaFinal){
			$fechaFinal = $this->db->escape($fechaFinal);}
		else{
			$fechaFinal = $this->db->escape("2050-12-31");}
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdVentas');
        $builder->select("ProdVentas.*,G.nombreGasolinera as nombreGasolinera");
		$builder->join("CatGasolineras G", "G.idGasolinera=ProdVentas.idGasolinera","LEFT");
		if($idGasolinera!=0 && $idGasolinera!=null){
			$builder->where('ProdVentas.idGasolinera', $idGasolinera);
		}
		if(!empty($fechaInicial)){
			$builder->where("ProdVentas.FechaVenta >=$fechaInicial");
			}
		if(!empty($fechaFinal)){
			$builder->where("ProdVentas.FechaVenta <= $fechaFinal");
		}
        $builder->orderBy('ProdVentas.idVenta', 'asc');
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroVentas($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial, $fechaFinal)
	{
		if ($fechaInicial){
			$fechaInicial = $this->db->escape($fechaInicial);
		}else{
			$fechaInicial = $this->db->escape("1950-01-01");}
		if ($fechaFinal){
			$fechaFinal = $this->db->escape($fechaFinal);}
		else{
			$fechaFinal = $this->db->escape("2050-12-31");}
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdVentas');
		$builder->select("*");
		if($idGasolinera!=0  && $idGasolinera!=null){
			$builder->where('ProdVentas.idGasolinera', $idGasolinera);
		}		
		if(!empty($fechaInicial)){
			$builder->where("ProdVentas.FechaVenta >=$fechaInicial");
			}
		if(!empty($fechaFinal)){
			$builder->where("ProdVentas.FechaVenta <= $fechaFinal");
		}
		return $builder->get()->getNumRows();
	}

    function insert($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('ProdVentas');
		$builder->insert($data);
		return $db->insertID();
	}

    function actualizarVenta($idVenta= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdVentas');
		$builder->where("idVenta", $idVenta);
		$builder->update($data);
	}
    function deleteVenta($idVenta= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('ProdVentas');
		$builder->where("idVenta", $idVenta);
		$builder->delete();
	}
    function selectVentaxid($idVenta)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdVentas');
        $builder->select("ProdVentas.*,G.nombreGasolinera as nombreGasolinera");
		$builder->join("CatGasolineras G", "G.idGasolinera=ProdVentas.idGasolinera","LEFT");
		$builder->where('ProdVentas.idVenta', $idVenta);
		return $builder->get()->getRowArray();
	}
}
?>