<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class InicioModel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	/*********LOGIN************/

		public function checkLogin($user,$pass){
			$this->db->where('estado','1');
			$this->db->where("rut" , $user);
			$res=$this->db->get('usuarios');
			if($res->num_rows()>0){
				return $res->num_rows();
			}
			return FALSE;
		}

		public function existeCuenta($user,$pass){
			$this->db->where("estado","1");
			$this->db->where("rut",$user);
			$res=$this->db->get("usuarios");
			if($res->num_rows()>0){
				
				$this->db->where("estado","1");
				$this->db->where("rut",$user);
				$this->db->where("(contrasena)",$pass);
				$res2=$this->db->get("usuarios");
				if($res2->num_rows()>0){
					return TRUE;
				}
				return FALSE;
			}

			return FALSE;
		}


		public function logear($user,$pass,$prueba){
			$this->db->where("estado","1");
			$this->db->where("rut",$user);
			if(!$prueba){
				$this->db->where("(contrasena)",$pass);
			}
			$res=$this->db->get("usuarios");
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function verificacionJefe($id){
			$this->db->where('id_jefe', $id);
			$res=$this->db->get('usuarios_jefes');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function idJefe($idJefe){
			$this->db->where('id_jefe', $idJefe);
			$res=$this->db->get('usuarios_jefes');
			if($res->num_rows()>0){
				$row=$res->row_array();
				return $row["id"];
			}
			return FALSE;
		}

		public function getUserPass($id){
			$this->db->select("contrasena");
			$this->db->where("id",$id);
			$this->db->where("estado","1");
			$res=$this->db->get("usuarios");
			return $res->row_array();
		}

		public function updatePass($id,$data){
			$this->db->where('id', $id);
		    if($this->db->update('usuarios', $data)){
		    	return TRUE;
		    }else{
		    	return FALSE;
		    }
		}

		public function getCorreoUsuario($rut){
			$this->db->select("correo_personal,correo_empresa");
			$this->db->where("rut",$rut);
			$this->db->where("estado","1");
			$res=$this->db->get("usuarios");
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getNombreUsuario($rut){
			$this->db->select("CONCAT(SUBSTRING_INDEX(nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(apellidos, ' ', '-2'), ' ', '1')) as 'nombre'");
			$this->db->where("rut",$rut);
			$this->db->where("estado","1");
			$res=$this->db->get("usuarios");
			if($res->num_rows()>0){
				$row = $res->row_array();
				return $row["nombre"];
			}
			return FALSE;
		}

		public function recuperarpass($rut,$data){
			$this->db->where('rut', $rut);
		    if($this->db->update('usuarios', $data)){
		    	return TRUE;
		    }else{
		    	return FALSE;
		    }
		}
	
	/*********INICIO************/

		public function getNoticias($cat){
			$this->db->select('n.id as id,
				sha1(n.id) as hash,
				n.id_categoria as id_categoria,
				n.titulo as titulo,
				nc.categoria as categoria,
				n.fecha as fecha,
				n.descripcion as descripcion,
				n.imagen as imagen,
				n.url as url');

			if($cat!=""){
				$this->db->where('id_categoria', $cat);
			}
			$this->db->join('noticias_categorias as nc', 'nc.id = n.id_categoria', 'left');
			$this->db->order_by("n.fecha","desc");
			$res=$this->db->get("noticias as n",15);
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}


		public function datosNoticia($hash){
			$this->db->select('n.id as id,
				sha1(n.id) as hash,
				n.id_categoria as id_categoria,
				n.titulo as titulo,
				nc.categoria as categoria,
				n.fecha as fecha,
				n.descripcion as descripcion,
				n.imagen as imagen,
				n.url as url');

			$this->db->from("noticias as n");
			$this->db->join('noticias_categorias as nc', 'nc.id = n.id_categoria', 'left');
			$this->db->where("sha1(n.id)",$hash);
			$res=$this->db->get();
			return $res->result_array();
		}


		public function getGaleria($hash){
			$this->db->where("sha1(id_noticia)",$hash);
			$res=$this->db->get("noticias_galeria");
			return $res->result_array();
		}


		public function cargaCategorias(){
			$this->db->order_by("categoria","asc");
			$res=$this->db->get("noticias_categorias");
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function cargaCumpleanios(){
			$fecha_actual=date("Y-m-d");
			$start=date('m-d', strtotime($fecha_actual. ' - 2 days'));
			$end=date('m-d', strtotime($fecha_actual. ' + 15 days'));

			$sql="SELECT distinct 
				u.rut as rut,
				sha1(u.id) as hash,
			    CONCAT(SUBSTRING_INDEX(nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(apellidos, ' ', '-2'), ' ', '1')) as 'nombre_corto',
				SUBSTRING(u.fecha_nacimiento,6,10) as dia_actual,
		   	    u.fecha_ingreso as fecha,
				u.foto as foto,
				u.subzona as subzona,
				u.comuna as comuna,
				u.fecha_nacimiento as fecha_nacimiento,
				pr.proyecto as proyecto,
				up.plaza as plaza
				FROM usuarios as u
				left join usuarios_cargos as mc ON u.id_cargo = mc.id	
				left join usuarios_plazas as up ON u.id_plaza = up.id	
				left join usuarios_proyectos as pr ON u.id_proyecto = pr.id	
				where estado='1'
				and SUBSTRING(u.fecha_nacimiento,6,10) between '".$start."' and '".$end."'
			    order by SUBSTRING(u.fecha_nacimiento,6,10) asc";
			$res=$this->db->query($sql);
			return $res->result_array();
		}	

		public function cargaIngresos(){
			$this->db->select("sha1(u.id) as hash,
				u.rut as rut,
				u.comuna as comuna,
			    CONCAT(SUBSTRING_INDEX(nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(apellidos, ' ', '-2'), ' ', '1')) as 'nombre_corto',
				u.fecha_ingreso as fecha,
				u.foto as foto,
				u.subzona as subzona,
				up.plaza as plaza,
				pr.proyecto as proyecto");		
			$this->db->from('usuarios as u');
			$this->db->join('usuarios_proyectos as pr', 'u.id_proyecto = pr.id', 'left');
			$this->db->join('usuarios_cargos as c', 'c.id = u.id_cargo', 'left');
			$this->db->join('usuarios_plazas as up', 'up.id = u.id_plaza', 'left');
			$this->db->where("u.estado","1");
			$this->db->where("u.fecha_ingreso !=","NULL");
			$this->db->where("u.fecha_ingreso !=","0000-00-00");
			$this->db->where("date_format(u.fecha_ingreso,'%Y-%m') >=  date_format(now() - interval 2 month,'%Y-%m')");
			$this->db->order_by('u.fecha_ingreso','desc');
			$this->db->limit(20);
			$res =$this->db->get();
			if($res->num_rows()>0){
				return $res->result_array();
			}	
			return false;
		}


		public function cargaInformaciones(){
			$this->db->order_by("fecha","desc");
			$res=$this->db->get("noticias_informaciones");
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function infoUsuario($hash){
			$this->db->query("SET lc_time_names = 'es_CL'");
			$this->db->select("
			    CONCAT(SUBSTRING_INDEX(u.nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(u.apellidos, ' ', '-2'), ' ', '1')) as 'nombre_corto',
				CONCAT(u.nombres,'  ',u.apellidos) as 'nombre_completo',
				u.foto as imagen,
				DATE_FORMAT(u.fecha_nacimiento, '%e %M')  as fecha_nacimiento,
				u.fecha_ingreso as fecha_ingreso,
				a.area as area,	
				c.cargo as cargo,
				p.proyecto as proyecto,
				CONCAT(u2.nombres,' ',u2.apellidos)  'jefe',
				u.rut,
				u.correo_empresa as 'correo',
				u.celular_empresa as 'telefono',
				pl.plaza as 'plaza',


			");
			$this->db->from('usuarios as u');
			$this->db->join('usuarios_areas as a', 'a.id = u.id_area', 'left');
			$this->db->join('usuarios_cargos as c', 'c.id = u.id_cargo', 'left');
			$this->db->join('usuarios_proyectos as p', 'p.id = u.id_proyecto', 'left');
			$this->db->join('usuarios_jefes uj', 'uj.id = u.id_jefe', 'left');
			$this->db->join('usuarios u2', 'u2.id = uj.id_jefe', 'left');
			$this->db->join('usuarios_plazas pl', 'pl.id = u.id_plaza', 'left');

			$this->db->where('sha1(u.id)', $hash);
			$res=$this->db->get();
			if ($res->num_rows()>0) {
				return $res->result_array();
			}
			return FALSE;
		}

		public function listaUsuarios(){
			$this->db->select("concat(substr(replace(rut,'-',''),1,char_length(replace(rut,'-',''))-1),'-',substr(replace(rut,'-',''),char_length(replace(rut,'-','')))) as 'rut_format',
				empresa,sha1(id) as 'id',rut,
			    CONCAT(nombres,'  ',apellidos) as 'nombre_completo',
			    CONCAT(SUBSTRING_INDEX(nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(apellidos, ' ', '-2'), ' ', '1')) as 'nombre_corto'");

			$this->db->where('estado',"1");

			$this->db->order_by('nombres', 'asc');
			$res=$this->db->get("usuarios");
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["id"];
					$temp["text"]=$key["nombre_completo"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}

		public function listaPerfiles(){
	      $this->db->order_by('perfil', 'asc');
	      $res=$this->db->get('usuarios_perfiles');
	      return $res->result_array();
	    }
	   

	    public function dataVisitasHoy(){
			$fecha_actual= date("Y-m-d");
			$this->db->select('count(DISTINCT av.id_usuario) as usuarios,
								count(av.id) as cantidad,
							   a.nombre as aplicacion,
							   a.descripcion as descripcion
							   ');
			$this->db->join('aplicaciones as a', 'a.id = av.id_aplicacion', 'left');
			$this->db->order_by('cantidad', 'desc');
			$this->db->group_by('av.id_aplicacion');
			$this->db->where('av.fecha', date('Y-m-d', strtotime($fecha_actual. ' - 0 day')));
			$res=$this->db->get('aplicaciones_visitas as av');
			return $res->result_array();
		}

		public function dataVisitasAyer(){
			$fecha_actual= date("Y-m-d");
			$this->db->select('count(DISTINCT av.id_usuario) as usuarios,
								count(av.id) as cantidad,
							   a.nombre as aplicacion,
							   a.descripcion as descripcion
							   ');
			$this->db->join('aplicaciones as a', 'a.id = av.id_aplicacion', 'left');
			$this->db->order_by('cantidad', 'desc');
			$this->db->group_by('av.id_aplicacion');
			$this->db->where('av.fecha', date('Y-m-d', strtotime($fecha_actual. ' - 1 day')));
			$res=$this->db->get('aplicaciones_visitas as av');
			return $res->result_array();
		}

		public function totalVisitasHoy(){
			$fecha_actual= date("Y-m-d");
			$this->db->select('count(*) as visitas');
			$this->db->where('fecha', date('Y-m-d', strtotime($fecha_actual. ' - 0 day')));
			$res=$this->db->get('aplicaciones_visitas');
			$row=$res->row_array();
			return $row["visitas"];
		}

		public function totalVisitasAyer(){
			$fecha_actual= date("Y-m-d");
			$this->db->select('count(*) as visitas');
			$this->db->where('fecha', date('Y-m-d', strtotime($fecha_actual. ' - 1 day')));
			$res=$this->db->get('aplicaciones_visitas');
			$row=$res->row_array();
			return $row["visitas"];

		}


	/***************CRON***************************/

		public function correoCumpleanios($fecha){
			$this->db->select("
				u.nombres as nombres,
				u.apellidos as apellidos,
				u.correo_empresa as correo_empresa,
				u.correo_personal as correo_personal,
			    CONCAT(SUBSTRING_INDEX(u.nombres, ' ', '1'),' ',SUBSTRING_INDEX(SUBSTRING_INDEX(u.apellidos,' ','-2'), ' ', '1')) as 'nombre_corto',
				u.fecha_nacimiento as fecha_nacimiento,
				CONCAT(u2.nombres,' ',u2.apellidos)  'jefe',
				u2.correo_empresa as correo_jefe_empresa,
				u2.correo_personal as correo_jefe_personal");

			$this->db->from('usuarios as u');
			$this->db->join('usuarios_jefes uj', 'uj.id = u.id_jefe', 'left');
			$this->db->join('usuarios u2', 'u2.id = uj.id_jefe', 'left');
			$this->db->where('SUBSTRING(u.fecha_nacimiento,6,10)', $fecha);
			$this->db->where('u.estado', '1');
			/*$this->db->where("LOCATE('@',u.correo_empresa)>0");*/
			$res=$this->db->get();
			/*echo $this->db->last_query();*/

			if ($res->num_rows()>0) {
				return $res->result_array();
			}
			return FALSE;
			
		}

}

/* End of file homeModel.php */
/* Location: ./application/models/front_end/homeModel.php */
