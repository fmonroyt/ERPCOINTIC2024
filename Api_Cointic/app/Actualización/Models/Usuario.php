<?php
namespace App\Models;
use CodeIgniter\Model;

class Usuario extends Model
{
	//     protected function initialize()
    // {
	// 	$this->$table = 'CatUsuarios';
	// 
    // }

	// retorna el row de usuario si el usuario y password son válidos, falso de lo contrario
	function auth($usuario, $password)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatUsuarios');
		$builder->select('*');
		// $builder->from('CatUsuarios');
		$builder->where('nickName', $usuario);
		$builder->where('Status', 0); // solo usuarios activos
				$query = $builder->get();
		if ($query->getNumRows()) // si el usuario existe en la base de datos...
		{
			
			$datos = $query->getRowArray();
			 $hashed_password = $datos['passwordUsuario'];	
				if(password_verify($password, $hashed_password)) {
				return $this->getUsuario($query->getRowArray()['idUsuario']);
			} else {
				return false;
			}
		} else {
			return false;
		}
	}


	// inserta un usuario en la base de datos
	function insert($data = NULL,$returnID = true)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatUsuarios');
		$builder->insert($data);
		return $db->insertID();
	}
	// actualiza un usuario en la base de datos
	function updateUsuario($idUsuario = NULL, $data= NULL)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatUsuarios');
		$builder->where("idUsuario", $idUsuario);
		$builder->update($data);
	}
	// elimina un usuario de la base de datos
	function delete($idUsuario = NULL,$purge = false)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatUsuarios');
		$builder->where("idUsuario", $idUsuario);
		$builder->delete();
	}
	function getUsuarioByEmail($email)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatUsuarios');
		return $builder->select("CatUsuarios.idUsuario, CatUsuarios.nombreUsuario, CatUsuarios.apellidoPaterno, CatUsuarios.apellidoMaterno,CatUsuarios.nickName, CatUsuarios.correoDestino, CatUsuarios.idGasolinera, CatUsuarios.idPerfil, CatUsuarios.Status, CatUsuarios.fotoUsuario")

		->where("correoDestino", $email)
		->get()->getRowArray();
	}
	function getUsuario($idUsuario)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatUsuarios');
		return $builder->select("CatUsuarios.idUsuario, CatUsuarios.nombreUsuario, CatUsuarios.apellidoPaterno, CatUsuarios.apellidoMaterno, CatUsuarios.nickName, CatUsuarios.correoDestino, CatUsuarios.idGasolinera, CatUsuarios.idPerfil, CatUsuarios.Status, CatUsuarios.fotoUsuario, CatPerfiles.nombrePerfil")
		
			->join("CatPerfiles", "CatUsuarios.idPerfil = CatPerfiles.idPerfil")
			->where("CatUsuarios.idUsuario", $idUsuario)->get()->getRowArray();
	}

	function getUsuarios($idTipoUsuario = null,$page, $pageSize, $sort, $order)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatUsuarios');
		$builder->select("CatUsuarios.idUsuario, 
		CatUsuarios.nombreUsuario,
		CatUsuarios.apellidoPaterno,
		CatUsuarios.apellidoMaterno,
		CatUsuarios.nickName,
		CatUsuarios.correoDestino,
	    CatUsuarios.idGasolinera,
		CatUsuarios.idPerfil,
		CatUsuarios.Status, 
		CatUsuarios.fotoUsuario,
		CatPerfiles.nombrePerfil,
		CatGasolineras.nombreGasolinera")
			->join("CatPerfiles", "CatUsuarios.idPerfil = CatPerfiles.idPerfil")
			->join("CatGasolineras", "CatUsuarios.idGasolinera = CatGasolineras.idGasolinera")
			
			// ->group_by("CatUsuarios.idUsuario")
			->orderBy("CatPerfiles.nombrePerfil, CatUsuarios.nombreUsuario, CatUsuarios.apellidoPaterno, CatUsuarios.apellidoMaterno","ASC");
			$builder->orderBy('nombreGasolinera', $order);
		$offset = $pageSize * $page;
		$builder->limit($pageSize, $offset);
		return $builder->get()->getResultArray();
	}

	function getUsuariosNumero($idTipoUsuario = null,$page, $pageSize, $sort, $order)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatUsuarios');
		$builder->select("CatUsuarios.idUsuario, 
		CatUsuarios.nombreUsuario,
		CatUsuarios.apellidoPaterno,
		CatUsuarios.apellidoMaterno,
		CatUsuarios.nickName,
		CatUsuarios.correoDestino,
	    CatUsuarios.idGasolinera,
		CatUsuarios.idPerfil,
		CatUsuarios.Status, 
		CatUsuarios.fotoUsuario,
		CatPerfiles.nombrePerfil,
		CatGasolineras.nombreGasolinera")
			->join("CatPerfiles", "CatUsuarios.idPerfil = CatPerfiles.idPerfil")
			->join("CatGasolineras", "CatUsuarios.idGasolinera = CatGasolineras.idGasolinera")
			// ->group_by("CatUsuarios.idUsuario")
			->orderBy("CatPerfiles.nombrePerfil, CatUsuarios.nombreUsuario, CatUsuarios.apellidoPaterno, CatUsuarios.apellidoMaterno","ASC");

		return $builder->get()->getNumRows();
	}


	// debe recibir el password a verificar y el nivel (IDs de tipos de usuarios) de usuario. Retorna el usuario de ese password ó false.
	function verificarPassword($password, $tiposDeUsuarios)
 	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatUsuarios');
		$builder->select('*');
		$builder->where('Status', 0); // solo usuarios activos
		$builder->whereIn('idPerfil', $tiposDeUsuarios);
		$query = $builder->get()->result_array();
		if (!empty($query)) // si hay usuarios activos del tipo que solicitaron existe en la base de datos...
		{
			foreach ($query as $usuario) {
				$hashed_password=$usuario['passwordUsuario'];
				if(password_verify($password, $hashed_password)) {
					return $this->getUsuario($usuario['idUsuario']); // esta linea solo sirve para quitar campos extras...
				} 
			}
			return false;
		} else {
			return false;
		}
	}

	function getCatGasolineras()
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatGasolineras');
		$builder->select("*");
		$builder->where('CatGasolineras.idGasolineraCompite', 0);
		$builder->orderBy('nombreGasolinera', 'asc');
		return $builder->get()->getResultArray();
	}

	function getPerfiles()
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatPerfiles');
		$builder->select("*");
		$builder->orderBy('nombrePerfil','asc');
		return $builder->get()->getResultArray();
	}
}
