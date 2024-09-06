<?php
namespace App\Models;
use CodeIgniter\Model;
class Proyeccion extends Model
{
    function getProyecciones($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial, $fechaFinal)
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
		$builder = $db->table('ProdProyecciones');
        $builder->select("ProdProyecciones.consecutivo,ProdProyecciones.idGasolinera,MIN(ProdProyecciones.fechaProyeccion) as FechaMinima,max(ProdProyecciones.fechaProyeccion)as FechaMaxima,CatGasolineras.nombreGasolinera");
		$builder->join("CatGasolineras ", "CatGasolineras.idGasolinera=ProdProyecciones.idGasolinera","LEFT");
        if($idGasolinera!=0){
			$builder->where('ProdProyecciones.idGasolinera', $idGasolinera);
		}
		if(!empty($fechaInicial)){
			$builder->where("ProdProyecciones.fechaProyeccion >= $fechaInicial");
			}
		if(!empty($fechaFinal)){
			$builder->where("ProdProyecciones.fechaProyeccion <= $fechaFinal");
		}
		$builder->groupBy('ProdProyecciones.consecutivo');
        $builder->orderBy('ProdProyecciones.consecutivo', 'desc');
		$offset = $pageSize * $page;
		$builder->limit($pageSize,$offset);
		return $builder->get()->getResultArray();
	}

	function getNumeroProyecciones($page, $pageSize, $sort, $order,$idGasolinera,$fechaInicial, $fechaFinal)
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
		$builder = $db->table('ProdProyecciones');
		$builder->select("ProdProyecciones.consecutivo,ProdProyecciones.idGasolinera,MIN(ProdProyecciones.fechaProyeccion) as FechaMinima,max(ProdProyecciones.fechaProyeccion)as FechaMaxima,CatGasolineras.nombreGasolinera");
		$builder->join("CatGasolineras ", "CatGasolineras.idGasolinera=ProdProyecciones.idGasolinera","LEFT");
        if($idGasolinera!=0){
			$builder->where('ProdProyecciones.idGasolinera', $idGasolinera);
		}
		if(!empty($fechaInicial)){
			$builder->where("ProdProyecciones.fechaProyeccion >= $fechaInicial");
			}
		if(!empty($fechaFinal)){
			$builder->where("ProdProyecciones.fechaProyeccion <= $fechaFinal");
		}
		$builder->groupBy('ProdProyecciones.consecutivo');
		return $builder->get()->getNumRows();
	}

    function insert($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('ProdProyecciones');
		$builder->insert($data);
		return $db->insertID();
	}

    function actualizarProyeccion($idProyeccion= NULL, $data= NULL)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdProyecciones');
		$builder->where("idProyeccion", $idProyeccion);
		$builder->update($data);
	}
    function deleteProyeccion($Consecutivo= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('ProdProyecciones');
		$builder->where("consecutivo", $Consecutivo);
		$builder->delete();
	}
	function deleteProyeccionxid($idProyeccion= NULL,$purge = false)
	{   
        $db      = \Config\Database::connect();
        $builder = $db->table('ProdProyecciones');
		$builder->where("idProyeccion", $idProyeccion);
		$builder->delete();
	}

	function selectProyeccionxidProyeccion($idProyeccion)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdProyecciones');
        $builder->select("ProdProyecciones.*,G.nombreGasolinera as nombreGasolinera");
		$builder->join("CatGasolineras G", "G.idGasolinera=ProdProyecciones.idGasolinera","LEFT");
		$builder->where('ProdProyecciones.idProyeccion', $idProyeccion);
		return $builder->get()->getRowArray();
	}
    function selectProyeccionxConsecutivo($Consecutivo)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdProyecciones');
        $builder->select("ProdProyecciones.*,G.nombreGasolinera as nombreGasolinera");
		$builder->join("CatGasolineras G", "G.idGasolinera=ProdProyecciones.idGasolinera","LEFT");
		$builder->where('ProdProyecciones.consecutivo', $Consecutivo);
		return $builder->get()->getResultArray();
	}

	function getProyeccionesContador()
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdProyecciones');
		$builder->select("ProdProyecciones.*");
		$builder->join("CatGasolineras G", "G.idGasolinera=ProdProyecciones.idGasolinera","LEFT");
		$builder->orderBy('ProdProyecciones.consecutivo', 'desc');
		$builder->limit(1);
		return $builder->get()->getRowArray();
	}

	function altaInventarioInicial($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('ProdInventarioInicial');
		$builder->insert($data);
		return $db->insertID();
	}
	function getProyeccionessiguientes($idProyeccion,$consecutivo){
		$db      = \Config\Database::connect();
		$builder = $db->table('ProdProyecciones');
		$builder->select("ProdProyecciones.*");
		$builder->where('ProdProyecciones.idProyeccion>=', $idProyeccion);
		$builder->where('ProdProyecciones.consecutivo', $consecutivo);
		$builder->orderBy('ProdProyecciones.idProyeccion', 'asc');
		return $builder->get()->getResultArray();
	}
	function selectProyeccionxFecha($fecha)
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('ProdProyecciones');
        $builder->select("ProdProyecciones.*,G.nombreGasolinera as nombreGasolinera");
		$builder->join("CatGasolineras G", "G.idGasolinera=ProdProyecciones.idGasolinera","LEFT");
		$builder->where('ProdProyecciones.fechaProyeccion', $fecha);
		return $builder->get()->getRowArray();
	}
}
?>