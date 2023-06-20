<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuariosmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function insertarVisita($data){
		if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	//USUARIOS
	
		public function listaUsuarios($estado){
			$this->db->select("sha1(u.id) as hash_usuario,
				u.*,
				concat(substr(replace(u.rut,'-',''),1,char_length(replace(u.rut,'-',''))-1),'-',substr(replace(u.rut,'-',''),char_length(replace(u.rut,'-','')))) as 'rut',
				up.perfil as perfil,
				upr.proyecto as proyecto,
				CONCAT(SUBSTRING_INDEX(u.nombres, ' ', 1), ' ', SUBSTRING_INDEX(u.apellidos, ' ', 1)) AS nombre, 
				CONCAT(SUBSTRING_INDEX(u2.nombres, ' ', 1), ' ', SUBSTRING_INDEX(u2.apellidos, ' ', 1)) AS jefe ,
				uc.cargo as cargo,
				ua.area as area,
				upl.plaza as plaza,
			    if(u.fecha_nacimiento!='1970-01-01' and u.fecha_nacimiento!='0000-00-00',DATE_FORMAT(u.fecha_nacimiento,'%Y-%m-%d'),'') as 'fecha_nacimiento',
				if(u.fecha_ingreso!='1970-01-01' and u.fecha_ingreso!='0000-00-00',DATE_FORMAT(u.fecha_ingreso,'%Y-%m-%d'),'') as 'fecha_ingreso',
				if(u.fecha_salida!='1970-01-01' and u.fecha_salida!='0000-00-00',DATE_FORMAT(u.fecha_salida,'%Y-%m-%d'),'') as 'fecha_salida',
				CASE 
		          WHEN u.estado=1 THEN 'Activo'
		          WHEN u.estado=0 THEN 'Baja'
		          ELSE ''
		        END AS estado_str,			
		        utn.nivel as nivel_tecnico,
				utc.tipo as tipo_contrato
			");

			$this->db->join('usuarios_perfiles as up', 'up.id = u.id_perfil', 'left');
			$this->db->join('usuarios_proyectos upr', 'upr.id = u.id_proyecto', 'left');
			
			$this->db->join('usuarios_jefes uj', 'uj.id = u.id_jefe', 'left');
			$this->db->join('usuarios u2', 'u2.id = uj.id_jefe', 'left');

			$this->db->join('usuarios_cargos uc', 'uc.id = u.id_cargo', 'left');
			$this->db->join('usuarios_areas ua', 'ua.id = u.id_area', 'left');
			$this->db->join('usuarios_plazas upl', 'upl.id = u.id_plaza', 'left');
			$this->db->join('usuarios_tecnicos_niveles utn', 'utn.id = u.id_nivel_tecnico', 'left');
			$this->db->join('usuarios_tipos_contrato utc', 'utc.id = u.id_tipo_contrato', 'left');

			if($this->session->userdata('id_perfil')<>1){
				$this->db->where('u.id_perfil<>', 1);
			}

			if($estado==1){
				$this->db->where('u.estado', "1");
			}

			$res=$this->db->get('usuarios u');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataUsuarios($hash){

			$this->db->select("sha1(u.id) as hash_usuario,
				u.*,
				up.perfil as perfil,
				upr.proyecto as proyecto,
				uj.id_jefe as jefe,
				uc.cargo as cargo,
				ua.area as area,
				if(u.fecha_nacimiento!='1970-01-01' and u.fecha_nacimiento!='0000-00-00',u.fecha_nacimiento,'') as 'fecha_nacimiento',
				if(u.fecha_ingreso!='1970-01-01' and u.fecha_ingreso!='0000-00-00',u.fecha_ingreso,'') as 'fecha_ingreso',
				if(u.fecha_salida!='1970-01-01' and u.fecha_salida!='0000-00-00',u.fecha_salida,'') as 'fecha_salida',
				CASE 
		          WHEN u.estado=1 THEN 'Activo'
		          WHEN u.estado=0 THEN 'Baja'
		          ELSE ''
		        END AS estado_str,			
				");
			
			$this->db->join('usuarios_perfiles as up', 'up.id = u.id_perfil', 'left');
			$this->db->join('usuarios_proyectos upr', 'upr.id = u.id_proyecto', 'left');
			$this->db->join('usuarios_jefes uj', 'uj.id = u.id_jefe', 'left');
			$this->db->join('usuarios_cargos uc', 'uc.id = u.id_cargo', 'left');
			$this->db->join('usuarios_areas ua', 'ua.id = u.id_area', 'left');
			$this->db->where('sha1(u.id)', $hash);
			$res=$this->db->get('usuarios u');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function formUsuario($data){
			if($this->db->insert('usuarios', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		
		public function listaNivelesTecnicos(){
			$this->db->order_by('nivel', 'asc');
			$res=$this->db->get('usuarios_tecnicos_niveles');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getPerfiles(){
			$this->db->order_by('perfil', 'asc');
			$res=$this->db->get('usuarios_perfiles');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getCargos(){
			$this->db->order_by('cargo', 'asc');
			$res=$this->db->get('usuarios_cargos');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getAreas(){
			$this->db->order_by('area', 'asc');
			$res=$this->db->get('usuarios_areas');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getPlazas(){
			$this->db->order_by('plaza', 'asc');
			$res=$this->db->get('usuarios_plazas');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getProyectos(){
			$this->db->order_by('proyecto', 'asc');
			$res=$this->db->get('usuarios_proyectos');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}


		public function getJefes(){
			$this->db->select("sha1(uj.id) as hash_jefes,
				uj.id as id_jefe,
				uj.id_jefe as id_usuario_jefe,
				CONCAT(u.nombres,' ',u.apellidos)  'nombre_jefe'
				");
			$this->db->join('usuarios u', 'u.id = uj.id_jefe', 'left');
			$this->db->order_by('nombre_jefe', 'asc');
			$res=$this->db->get('usuarios_jefes uj');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}



		public function getIdJefe($rut){
			$this->db->select('id');
			$this->db->where('rut', $rut);
			$res=$this->db->get('usuarios');
			
			if($res->num_rows()==0){
				return FALSE;
			}else{

				$row = $res->row_array();

				$this->db->select('id');
				$this->db->where('id_jefe', $row["id"]);
				$res2=$this->db->get('usuarios_jefes');

				if($res2->num_rows()==0){
					return FALSE;
				}else{
					$row2 = $res2->row_array();
					return $row2["id"];
				}
			}
		}

		public function actualizarUsuario($hash,$data){
			$this->db->where('sha1(id)', $hash);
			if($this->db->update('usuarios', $data)){
				/*echo $this->db->last_query();
				echo "<br>";*/

				return TRUE;
			}
			return FALSE;
		}

		public function getIdUsuarioPorRut($rut){
			$this->db->select('id');
			$this->db->where('rut', $rut);
			$res=$this->db->get('usuarios');

			if($res->num_rows()==0){
				return FALSE;
			}else{
				$row = $res->row_array();
				return $row["id"];
			}

			
		}

		public function getIdCargoPorNombre($cargo){
			$this->db->where('cargo', $cargo);
			$res=$this->db->get('usuarios_cargos');
			$row=$res->row_array();
			return $row["id"];
		}

		public function getIdAreaPorNombre($area){
			$this->db->where('area', $area);
			$res=$this->db->get('usuarios_areas');
			$row=$res->row_array();
			return $row["id"];
		}

		public function getIdProyectoPorNombre($proyecto){
			$this->db->where('proyecto', $proyecto);
			$res=$this->db->get('usuarios_proyectos');
			$row=$res->row_array();
			return $row["id"];
		}

		public function getIdJefePorNombre($nombre_jefe){
			$nombre = explode(" ", trim($nombre_jefe));
			$this->db->select('uj.id_jefe as id_jefe,rut,nombres,apellidos');
			/*print_r($nombre);*/

			$this->db->like('u.nombres', trim($nombre[0]) , 'both');
			$this->db->like('u.apellidos', trim($nombre[1]) , 'both');
			$this->db->join('usuarios_jefes uj', 'uj.id_jefe = u.id', 'left');
			$res=$this->db->get('usuarios u');
			/*echo $this->db->last_query();
			echo "<br>";*/
			$row=$res->row_array();
			return $row["id_jefe"];
		}


		public function truncateUsuarios(){
			$this->db->truncate('usuarios');
		}

		public function existeUsuario($rut){
			$this->db->where('u.rut', $rut);
			$res=$this->db->get('usuarios u');
		    if($res->num_rows()==0){
		    	return FALSE;
		    }
		    return TRUE;
		}

		public function eliminaUsuario($hash){
			$this->db->where('sha1(id)', $hash);
		    if($this ->db->delete('usuarios')){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function listaTiposContratos(){
			$this->db->order_by('tipo', 'asc');
			$res=$this->db->get('usuarios_tipos_contrato');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function correoDatosFaltantes() {
				$campos_vacios = array(
					'nombres', 'apellidos', 'rut', 'correo_empresa',
					'id_jefe', 'id_plaza', 'zona', 'subzona',
					'celular_empresa', 'nacionalidad',
					'subzona', 'fecha_ingreso', 'fecha_nacimiento',
					'domicilio'
				);
			
				$this->db->where('estado', 1);
				$usuarios = $this->db->get('usuarios')->result();
			
				$usuarios_campos_vacios = array();
				$campos_vacios_totales = array();
				$total_vacios = 0;
			
				foreach ($campos_vacios as $campo) {
					$campos_vacios_totales[$campo] = 0;
				}
			
				foreach ($usuarios as $usuario) {
					$campos_vacios_usuario = array();
			
					foreach ($campos_vacios as $campo) {
						if (empty($usuario->$campo)) {
							$campos_vacios_usuario[] = $campo;
							$campos_vacios_totales[$campo]++;
							$total_vacios++;
						}
					}
			
					if (!empty($campos_vacios_usuario)) {
						$usuario->campos_vacios = $campos_vacios_usuario;
						$usuarios_campos_vacios[] = $usuario;
					}
				}
			
				return array(
					'usuarios' => $usuarios_campos_vacios,
					'campos_vacios' => $campos_vacios_totales,
					'total_vacios' => $total_vacios
				);
		}

		/* public function listaDatosFaltantes($dato,$empresa){
			$this->db->select("
				CONCAT(primer_nombre, ' ',segundo_nombre, ' ',apellido_paterno, ' ',apellido_materno) as 'nombre',
				empresa,
				adjuntar_foto,
				nacionalidad,
				Direccion_domicilio,
				estado_civil,
				correo_personal,
				fono_celular,
				id_cargo,
				banco_num_cuenta,
				datos_emergencia,
				fecha_vac_covid,
				fecha_vac_covid2");

			if($dato=="foto"){$this->db->where('adjuntar_foto = ""');}
			if($dato=="nacionalidad"){$this->db->where('nacionalidad = ""');}
			if($dato=="Direccion_domicilio"){$this->db->where('Direccion_domicilio = ""');}
			if($dato=="estado_civil"){$this->db->where('estado_civil = ""');}
			if($dato=="correo_personal"){$this->db->where('correo_personal = ""');}
			if($dato=="fono_celular"){$this->db->where('fono_celular = ""');}
			if($dato=="id_cargo"){$this->db->where('id_cargo = "0"');}
			if($dato=="banco_num_cuenta"){$this->db->where('banco_num_cuenta = ""');}
			if($dato=="datos_emergencia"){$this->db->where('datos_emergencia = ""');}
			if($dato=="fecha_vac_covid"){$this->db->where('fecha_vac_covid = "0000-00-00"');}
			if($dato=="fecha_vac_covid2"){$this->db->where('fecha_vac_covid2 = "0000-00-00"');}

			$this->db->order_by('primer_nombre', 'asc');
			$this->db->where('estado', "Activo");
			$this->db->where('rut<>', "769142606");
			$this->db->where('id_tipo_contrato<>', "7");
			$this->db->where('no_vacuna<>', "1");
			$this->db->where('empresa', $empresa);
			$res=$this->db->get('usuario');
			return $res->result_array();
		} */


	//CARGOS

		public function listaCargos(){
			$this->db->select('sha1(id) as hash_cargo,cargo');
			$this->db->order_by('cargo', 'asc');
			$res=$this->db->get('usuarios_cargos');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataCargos($hash){
			$this->db->where('sha1(id)', $hash);
			$res=$this->db->get('usuarios_cargos');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function formCargos($data){
			if($this->db->insert('usuarios_cargos', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		public function actualizarCargos($hash,$data){
			$this->db->where('sha1(id)', $hash);
			if($this->db->update('usuarios_cargos', $data)){
				return TRUE;
			}
			return FALSE;
		}
		
		public function eliminaCargo($hash){
			$this->db->where('sha1(id)', $hash);
		    if($this ->db->delete('usuarios_cargos')){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function existeCargo($cargo){
			$this->db->where('cargo', $cargo);
			$res=$this->db->get('usuarios_cargos');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function existeCargoMod($cargo,$id){
			$this->db->where('cargo', $cargo);
			$this->db->where('sha1(id)<>', $id);
			$res=$this->db->get('usuarios_cargos');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

	//PROYECTOS

		public function listaProyectos(){
			$this->db->select('sha1(id) as hash_proyecto,proyecto');
			$this->db->order_by('proyecto', 'asc');
			$res=$this->db->get('usuarios_proyectos');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataProyectos($hash){
			$this->db->where('sha1(id)', $hash);
			$res=$this->db->get('usuarios_proyectos');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function formProyectos($data){
			if($this->db->insert('usuarios_proyectos', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		public function actualizarProyectos($hash,$data){
			$this->db->where('sha1(id)', $hash);
			if($this->db->update('usuarios_proyectos', $data)){
				return TRUE;
			}
			return FALSE;
		}
		
		public function eliminaProyectos($hash){
			$this->db->where('sha1(id)', $hash);
		    if($this ->db->delete('usuarios_proyectos')){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function existeProyecto($proyecto){
			$this->db->where('proyecto', $proyecto);
			$res=$this->db->get('usuarios_proyectos');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function existeProyectoMod($proyecto,$id){
			$this->db->where('proyecto', $proyecto);
			$this->db->where('sha1(id)<>', $id);
			$res=$this->db->get('usuarios_proyectos');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

	//AREAS

		public function listaAreas(){
			$this->db->select('sha1(id) as hash_area,area');
			$this->db->order_by('area', 'asc');
			$res=$this->db->get('usuarios_areas');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataAreas($hash){
			$this->db->where('sha1(id)', $hash);
			$res=$this->db->get('usuarios_areas');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function formAreas($data){
			if($this->db->insert('usuarios_areas', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		public function actualizarAreas($hash,$data){
			$this->db->where('sha1(id)', $hash);
			if($this->db->update('usuarios_areas', $data)){
				return TRUE;
			}
			return FALSE;
		}
		
		public function eliminaAreas($hash){
			$this->db->where('sha1(id)', $hash);
		    if($this ->db->delete('usuarios_areas')){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function existeArea($area){
			$this->db->where('area', $area);
			$res=$this->db->get('usuarios_areas');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function existeAreaMod($area,$id){
			$this->db->where('area', $area);
			$this->db->where('sha1(id)<>', $id);
			$res=$this->db->get('usuarios_areas');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}
	
	//PLAZA

		public function listaPlazas(){
			$this->db->select('sha1(id) as hash_plaza,plaza');
			$this->db->order_by('plaza', 'asc');
			$res=$this->db->get('usuarios_plazas');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataPlazas($hash){
			$this->db->where('sha1(id)', $hash);
			$res=$this->db->get('usuarios_plazas');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function formPlazas($data){
			if($this->db->insert('usuarios_plazas', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		public function actualizarPlazas($hash,$data){
			$this->db->where('sha1(id)', $hash);
			if($this->db->update('usuarios_plazas', $data)){
				return TRUE;
			}
			return FALSE;
		}
		
		public function eliminaPlazas($hash){
			$this->db->where('sha1(id)', $hash);
		    if($this ->db->delete('usuarios_plazas')){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function existePlaza($plaza){
			$this->db->where('plaza', $plaza);
			$res=$this->db->get('usuarios_plazas');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function existePlazaMod($plaza,$id){
			$this->db->where('plaza', $plaza);
			$this->db->where('sha1(id)<>', $id);
			$res=$this->db->get('usuarios_plazas');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

	//JEFES

		public function listaJefes(){
			$this->db->select("sha1(uj.id) as hash_jefe,
				uj.id_jefe as id_jefe,
				CONCAT(u.nombres,' ',u.apellidos)  'nombre_jefe'
				");
			$this->db->join('usuarios u', 'u.id = uj.id_jefe', 'left');
			$this->db->order_by('nombre_jefe', 'asc');
			$res=$this->db->get('usuarios_jefes uj');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataJefes($hash){
			$this->db->where('sha1(id)', $hash);
			$res=$this->db->get('usuarios_jefes');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function formJefes($data){
			if($this->db->insert('usuarios_jefes', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		public function actualizarJefes($hash,$data){
			$this->db->where('sha1(id)', $hash);
			if($this->db->update('usuarios_jefes', $data)){
				return TRUE;
			}
			return FALSE;
		}
		
		public function eliminaJefes($hash){
			$this->db->where('sha1(id)', $hash);
		    if($this ->db->delete('usuarios_jefes')){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function existeJefes($jefes){
			$this->db->where('id_jefe', $jefes);
			$res=$this->db->get('usuarios_jefes');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function existeJefesMod($jefes,$id){
			$this->db->where('id_jefe', $jefes);
			$this->db->where('sha1(id)<>', $id);
			$res=$this->db->get('usuarios_jefes');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function listaUsuariosJefes(){
			$this->db->select("
				u.id id,
				CONCAT(u.nombres,' ',u.apellidos)  'nombre'");
			
			if($this->session->userdata('id_perfil')<>1){
				$this->db->where('id_perfil<>', 1);
			}
			$this->db->order_by('nombre', 'asc');
			$res=$this->db->get('usuarios u');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

	//PERFILES

		public function listaPerfiles(){
			$this->db->select('sha1(id) as hash_perfil,perfil');
			
			if($this->session->userdata('id_perfil')<>1){
				$this->db->where('id<>', 1);
			}

			$this->db->order_by('perfil', 'asc');
			$res=$this->db->get('usuarios_perfiles');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataPerfiles($hash){
			$this->db->where('sha1(id)', $hash);
			$res=$this->db->get('usuarios_perfiles');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function formPerfiles($data){
			if($this->db->insert('usuarios_perfiles', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		public function actualizarPerfiles($hash,$data){
			$this->db->where('sha1(id)', $hash);
			if($this->db->update('usuarios_perfiles', $data)){
				return TRUE;
			}
			return FALSE;
		}
		
		public function eliminaPerfiles($hash){
			$this->db->where('sha1(id)', $hash);
		    if($this ->db->delete('usuarios_perfiles')){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function existePerfil($perfil){
			$this->db->where('perfil', $perfil);
			$res=$this->db->get('usuarios_perfiles');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function existePerfilMod($perfil,$id){
			$this->db->where('perfil', $perfil);
			$this->db->where('sha1(id)<>', $id);
			$res=$this->db->get('usuarios_perfiles');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}


	

}

