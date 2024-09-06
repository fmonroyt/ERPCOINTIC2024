<?php
namespace App\Models;
use CodeIgniter\Model;
class Compra extends Model
{
    function getCompras($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial, $fechaFinal)
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
		$builder = $db->table('ProdCompras');
        $builder->select("ProdCompras.*,G.nombreGasolinera as nombreGasolinera,tg.nombreGasolina as nombreGasolina");
        $builder->join("CatTipoGasolina tg", "tg.idTipoGasolina=ProdCompras.idTipoGasolina","LEFT");
		$builder->join("CatGasolineras G", "G.idGasolinera=ProdCompras.idGasolinera","LEFT");
		if($idGasolinera!=0 && $idGasolinera!=null){
			$builder->where('ProdCompras.idGasolinera', $idGasolinera);
		}
		if(!empty($fechaInicial)){
			$builder->where("ProdCompras.Fechacompra >=$fechaInicial");
			}
		if(!empty($fechaFinal)){
			$builder->where("ProdCompras.Fechacompra <= $fechaFinal");
		}
        $builder->orderBy('ProdCompras.Fechacompra', 'asc');
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroCompras($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial, $fechaFinal)
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
		$builder = $db->table('ProdCompras');
		$builder->select("*");
		if($idGasolinera!=0 && $idGasolinera!=null){
		$builder->where('ProdCompras.idGasolinera', $idGasolinera);
		}
		if(!empty($fechaInicial)){
			$builder->where("ProdCompras.Fechacompra >=$fechaInicial");
			}
		if(!empty($fechaFinal)){
			$builder->where("ProdCompras.Fechacompra <= $fechaFinal");
		}
		return $builder->get()->getNumRows();
	}

    function insert($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('ProdCompras');
		$builder->insert($data);
		return $db->insertID();
	}

    function actualizarCompra($idCompra= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdCompras');
		$builder->where("idCompra", $idCompra);
		$builder->update($data);
	}
    function deleteCompra($idCompra= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('ProdCompras');
		$builder->where("idCompra", $idCompra);
		$builder->delete();
	}
    function selectCompraxid($idCompra)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('ProdCompras');
		$builder->select('ProdCompras.*,G.nombreGasolinera as nombreGasolinera,tg.nombreGasolina as nombreGasolina');
        $builder->join("CatTipoGasolina tg", "tg.idTipoGasolina=ProdCompras.idTipoGasolina","LEFT");
		$builder->join("CatGasolineras G", "G.idGasolinera=ProdCompras.idGasolinera","LEFT");
		$builder->where('ProdCompras.idCompra', $idCompra);
		return $builder->get()->getRowArray();
	}
}
?>