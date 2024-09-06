<?php
namespace App\Models;
use CodeIgniter\Model;
class Inventario extends Model
{
    function selecttotalCompraSuper($idGasolinera)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('ProdCompras');
		$builder->selectSum('ProdCompras.cantidad');
        $builder->where('ProdCompras.idTipoGasolina',1);
        $builder->where('ProdCompras.idGasolinera',$idGasolinera);
		return $builder->get()->getRowArray();
	}
    function selecttotalCompraPremium($idGasolinera)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('ProdCompras');
		$builder->selectSum('ProdCompras.cantidad');
        $builder->where('ProdCompras.idTipoGasolina',2);
        $builder->where('ProdCompras.idGasolinera',$idGasolinera);
		return $builder->get()->getRowArray();
	}
    function selecttotalCompraDiesel($idGasolinera)
	{
        $db      = \Config\Database::connect();
        $builder = $db->table('ProdCompras');
		$builder->selectSum('ProdCompras.cantidad');
        $builder->where('ProdCompras.idTipoGasolina',3);
        $builder->where('ProdCompras.idGasolinera',$idGasolinera);
		return $builder->get()->getRowArray();
	}

    function selecttotalVentaSuper($idGasolinera)
        {
            $db      = \Config\Database::connect();
            $builder = $db->table('ProdVentas');
            $builder->selectSum('ProdVentas.cantidadSuper');
            $builder->where('ProdVentas.idGasolinera',$idGasolinera);
            return $builder->get()->getRowArray();
        }

    function selecttotalVentaPremium($idGasolinera)
        {
            $db      = \Config\Database::connect();
            $builder = $db->table('ProdVentas');
            $builder->selectSum('ProdVentas.cantidadPremium');
            $builder->where('ProdVentas.idGasolinera',$idGasolinera);
            return $builder->get()->getRowArray();
        }
    function selecttotalVentaDiesel($idGasolinera)
        {
            $db      = \Config\Database::connect();
            $builder = $db->table('ProdVentas');
            $builder->selectSum('ProdVentas.cantidadDiesel');
            $builder->where('ProdVentas.idGasolinera',$idGasolinera);
            return $builder->get()->getRowArray();
        }
    function selecttotalCompraSuperxfecha($idGasolinera,$fecha)
        {
            $db      = \Config\Database::connect();
            $builder = $db->table('ProdCompras');
            $builder->selectSum('ProdCompras.cantidad');
            $builder->where('ProdCompras.idTipoGasolina',1);
            $builder->where('ProdCompras.idGasolinera',$idGasolinera);
            $builder->where("DATE(ProdCompras.Fechacompra) <=", $fecha);
            return $builder->get()->getRowArray();
        }
    function selecttotalCompraPremiumxfecha($idGasolinera,$fecha)
        {
            $db      = \Config\Database::connect();
            $builder = $db->table('ProdCompras');
            $builder->selectSum('ProdCompras.cantidad');
            $builder->where('ProdCompras.idTipoGasolina',2);
            $builder->where('ProdCompras.idGasolinera',$idGasolinera);
            $builder->where("DATE(ProdCompras.Fechacompra) <=", $fecha);
            return $builder->get()->getRowArray();
        }
    function selecttotalCompraDieselxfecha($idGasolinera,$fecha)
        {
            $db      = \Config\Database::connect();
            $builder = $db->table('ProdCompras');
            $builder->selectSum('ProdCompras.cantidad');
            $builder->where('ProdCompras.idTipoGasolina',3);
            $builder->where('ProdCompras.idGasolinera',$idGasolinera);
            $builder->where("DATE(ProdCompras.Fechacompra) <=", $fecha);
            return $builder->get()->getRowArray();
        }
    function selecttotalVentaSuperxfecha($idGasolinera,$fecha)
        {
            $db      = \Config\Database::connect();
            $builder = $db->table('ProdVentas');
            $builder->selectSum('ProdVentas.cantidadSuper');
            $builder->where('ProdVentas.idGasolinera',$idGasolinera);
            $builder->where("DATE(ProdVentas.FechaVenta) <=", $fecha);
            return $builder->get()->getRowArray();
        }
    function selecttotalVentaPremiumxfecha($idGasolinera,$fecha)
        {
            $db      = \Config\Database::connect();
            $builder = $db->table('ProdVentas');
            $builder->selectSum('ProdVentas.cantidadPremium');
            $builder->where('ProdVentas.idGasolinera',$idGasolinera);
            $builder->where("DATE(ProdVentas.FechaVenta) <=",$fecha);
            return $builder->get()->getRowArray();
        }
    function selecttotalVentaDieselxfecha($idGasolinera,$fecha)
        {
            $db      = \Config\Database::connect();
            $builder = $db->table('ProdVentas');
            $builder->selectSum('ProdVentas.cantidadDiesel');
            $builder->where('ProdVentas.idGasolinera',$idGasolinera);
            $builder->where("DATE(ProdVentas.FechaVenta) <=", $fecha);
            return $builder->get()->getRowArray();
        }

        function selectVentaxFecha($fecha,$idGasolinera)
        {
            $db      = \Config\Database::connect();
            $builder = $db->table('ProdVentas');
            $builder->select("ProdVentas.*,G.nombreGasolinera as nombreGasolinera");
            $builder->join("CatGasolineras G", "G.idGasolinera=ProdVentas.idGasolinera","LEFT");
            $builder->where('ProdVentas.idGasolinera',$idGasolinera);
            $builder->where('DATE(ProdVentas.FechaVenta)', $fecha);
            return $builder->get()->getRowArray();
        }
        function selectCompraxFecha($fecha,$idTipoGasolina,$idGasolinera)
        {
            $db      = \Config\Database::connect();
            $builder = $db->table('ProdCompras');
            $builder->select('ProdCompras.*,G.nombreGasolinera as nombreGasolinera,tg.nombreGasolina as nombreGasolina');
            $builder->join("CatTipoGasolina tg", "tg.idTipoGasolina=ProdCompras.idTipoGasolina","LEFT");
            $builder->join("CatGasolineras G", "G.idGasolinera=ProdCompras.idGasolinera","LEFT");
            $builder->where('DATE(ProdCompras.Fechacompra)', $fecha);
            $builder->where('ProdCompras.idTipoGasolina', $idTipoGasolina);
            $builder->where('ProdCompras.idGasolinera', $idGasolinera);
            return $builder->get()->getRowArray();
        }
}
?>