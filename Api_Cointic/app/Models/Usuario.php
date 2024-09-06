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
	protected $allowedFields = [
        'idUsuario', 
        'newstatus', 
        
    ];
	
	function auth($usuario, $password)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatUsuarios');
		$builder->select('CatUsuarios.*');
		// $builder->from('CatUsuarios');
		$builder->where('nickName', $usuario);
		$builder->where('Status!=', 1); // solo usuarios activos
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
	//($idUsuario = NULL, $data= NULL)
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
		return $builder->select("CatUsuarios.*")

		->where("correoDestino", $email)
		->get()->getRowArray();
	}
	function getUsuario($idUsuario)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatUsuarios');
		return $builder->select("CatUsuarios.*")
		
			// ->join("CatPerfiles", "CatUsuarios.idPerfil = CatPerfiles.idPerfil")
			->where("CatUsuarios.idUsuario", $idUsuario)->get()->getRowArray();
	}

	/*function getUsuarios($page, $pageSize)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatUsuarios');
		$builder->select("CatUsuarios.*");
		//$builder->join("CatPerfiles", "CatUsuarios.idPerfil = CatPerfiles.idPerfil");
		//$builder->join("CatGasolineras", "CatUsuarios.idGasolinera = CatGasolineras.idGasolinera");
		//$builder->group_by("CatUsuarios.idUsuario");
		//$builder->orderBy('nombreDepartamento', $order);
		$offset = $pageSize * $page;
		$builder->limit($pageSize, $offset);
		return $builder->get()->getResultArray();
	}*/
	function getUsuarios($page, $pageSize){
    $db      = \Config\Database::connect();
    $builder = $db->table('CatUsuarios');
	/*Esto selecciona todos los campos de CatUsuarios, además de nombreDepartamento de CatDepartamentos y nombrePerfil de CatPerfiles. */
    $builder->select('CatUsuarios.*, CatDepartamentos.nombreDepartamento, CatPerfiles.nombrePerfil');
	/*Esto une la tabla CatUsuarios con CatDepartamentos usando idDepartamento.
	El tipo de unión es left, lo que significa que se mostrarán todos los usuarios incluso si no tienen un departamento asociado.*/ 
    $builder->join('CatDepartamentos', 'CatUsuarios.idDepartamento = CatDepartamentos.idDepartamento', 'left');
	/*Esto une la tabla CatUsuarios con CatPerfiles usando idPerfil.
	Similar al anterior, el tipo de unión es left. */
    $builder->join('CatPerfiles', 'CatUsuarios.idPerfil = CatPerfiles.idPerfil', 'left');
    $offset = $pageSize * $page;
    $builder->limit($pageSize, $offset);
    return $builder->get()->getResultArray();
}


	function getUsuariosNumero($page, $pageSize)
	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatUsuarios');
		$builder->select("CatUsuarios.*");
			// ->join("CatPerfiles", "CatUsuarios.idPerfil = CatPerfiles.idPerfil")
			// ->group_by("CatUsuarios.idUsuario")
			

		return $builder->get()->getNumRows();
	}


	// debe recibir el password a verificar y el nivel (IDs de tipos de usuarios) de usuario. Retorna el usuario de ese password ó false.
	function verificarPassword($password, $tiposDeUsuarios)
 	{
		$db      = \Config\Database::connect();
		$builder = $db->table('CatUsuarios');
		$builder->select('*');
		//$builder->where('Status', 4);  solo usuarios activos
		// $builder->whereIn('idPerfil', $tiposDeUsuarios);
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
	function getDepartamentos()
	{
        $db      = \Config\Database::connect();
		$builder = $db->table('CatDepartamentos');
        $builder->select("CatDepartamentos.*");
		$builder->orderBy('nombreDepartamento', 'asc');
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

	 // Función para obtener el nombre del departamento de un usuario dado su ID
	 function getNombreDepartamento($idUsuario)
	 {
		 $db      = \Config\Database::connect();
		 $builder = $db->table('CatUsuarios');
		 
		 // Selecciona el nombre del departamento uniéndolo con la tabla de departamentos
		 $builder->select('Departamentos.nombreDepartamento');
		 $builder->join('Departamentos', 'CatUsuarios.idDepartamento = Departamentos.idDepartamento');
		 $builder->where('CatUsuarios.idUsuario', $idUsuario);
		 
		 $query = $builder->get();
		 
		 // Verifica si se encontró un resultado
		 if ($query->getNumRows() > 0) {
			 return $query->getRowArray()['nombreDepartamento'];
		 } else {
			 return false; // Retorna false si no se encontró el departamento
		 }
	 }
}
