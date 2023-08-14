<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Caomodel extends CI_Model {

	public function __construct(){
		parent::__construct();
		$this->load->helper(array('fechas'));
	}
	
	public function insertarVisita($data){
		if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}
						
	/********TURNOS***********/

		public function getCabecerasTurnos($desde,$hasta){
			$array_fechas = arrayRangoFechas($desde,$hasta,"+1 day", "Y-m-d");
			$cabeceras = array();
			$cabeceras[] = "Trabajador";
			$cabeceras[] = "RUT";
			$cabeceras[] = "Jefe";
			$cabeceras[] = "Proyecto";
			$cabeceras[] = "Nivel Técnico";
			foreach($array_fechas as $fecha){
				$cabeceras[] = diasConFecha($fecha);
			}
			
			return $cabeceras;
		}
			
		/*CONCAT(SUBSTRING_INDEX(u.nombres, ' ', '1'),'  ',
		SUBSTRING_INDEX(SUBSTRING_INDEX(u.apellidos, ' ', '-2'), ' ', '1') , ' ' , 
		LEFT(SUBSTRING_INDEX(u.apellidos,' ', '-1'), 1) , '. ')  as 'trabajador',*/

		public function listaTurnos($desde,$hasta,$jefe,$trabajador,$nivel_tecnico,$tipo){
			$this->db->select("sha1(c.id) as hash,
				c.id as id,
				CONCAT(SUBSTRING(c.rut_tecnico, 1, LENGTH(c.rut_tecnico) - 1), '-', SUBSTRING(c.rut_tecnico, -1))  as rut_tecnico,
				CONCAT(u.nombres,' ',u.apellidos)  'trabajador',
				CONCAT(SUBSTRING_INDEX(u.nombres, ' ', 1),' ',SUBSTRING_INDEX(u.apellidos, ' ', 1))  'trabajador',

				CONCAT(LEFT(u2.nombres, 1),'. ',SUBSTRING_INDEX(u2.apellidos, ' ', 1))  'jefe',
				if(c.fecha!='0000-00-00', DATE_FORMAT(c.fecha,'%d-%m-%Y'),'') as 'fecha',
				upr.proyecto as proyecto,
 				utn.nivel as nivel_tecnico
				");

			$this->db->join('usuarios u', 'u.rut = c.rut_tecnico', 'left');
			$this->db->join('usuarios_jefes uj', 'uj.id = u.id_jefe', 'left');
			$this->db->join('usuarios u2', 'u2.id = uj.id_jefe', 'left');
			$this->db->join('usuarios_tecnicos_niveles utn', 'utn.id = u.id_nivel_tecnico', 'left');
			$this->db->join('usuarios_proyectos upr', 'upr.id = u.id_proyecto', 'left');

			if($desde!="" and $hasta!=""){$this->db->where("c.fecha BETWEEN '".$desde."' AND '".$hasta."'");	}
			if($trabajador!=""){$this->db->where('c.rut_tecnico', $trabajador);}
			if($jefe!=""){	$this->db->where('u.id_jefe', $jefe);}
			if($nivel_tecnico!=""){	$this->db->where('u.id_nivel_tecnico', $nivel_tecnico);}

			$this->db->where('u.estado',"1");
			$this->db->order_by('u.nombres', 'asc');
			$this->db->group_by('u.id');
			
			$res=$this->db->get('cao c');
			$array_fechas = arrayRangoFechas($desde,$hasta,"+1 day", "Y-m-d");
			$array = array();

			foreach($res->result_array() as $key){
				$temp=array();
				$temp["Trabajador"] = $key["trabajador"];
				$temp["RUT"] = $key["rut_tecnico"];
				$temp["Jefe"] = $key["jefe"];
				$temp["Proyecto"] = $key["proyecto"];
				$temp["Nivel Técnico"] = $key["nivel_tecnico"];

				foreach($array_fechas as $fecha){
					$this->db->select('sha1(c.id) as hash_turnos,ct.codigo as codigo,ct.rango_horario');
					$this->db->join('cao_turnos_ausencias ct', 'ct.id = c.id_turno', 'left');
					$this->db->where('fecha="'.$fecha.'" AND `rut_tecnico` = "'.str_replace('-', '', $key["rut_tecnico"]).'"');
					$res2=$this->db->get('cao c');
					
					if($res2->num_rows()>0){
						foreach($res2->result_array() as $dia){
							
							if($tipo==0 and $this->session->userdata('id_perfil')<=3){
								//$html = "<button class='btn btn-xs btn-primary btn_xr3 btn-edit-turnos' data-hash_turnos='".$dia["hash_turnos"]."'>".$dia["codigo"]."</button>";
								$html = "<a class='btn-edit-turnos' title='".$dia["rango_horario"]."' data-hash_turnos='".$dia["hash_turnos"]."'>".$dia["codigo"]."</a>";
							}else{
								$html = $dia["codigo"];
							}
							
							$temp[diasConFecha($fecha)] = $html;
						}
					}else{
						$temp[diasConFecha($fecha)] = "";
					}
				}

				$array[]=$temp;
			}

			return $array;
		}

		public function getDataTurnos($hash_turno){
			$this->db->select("sha1(c.id) as hash_turno,
				c.*");
			$this->db->where('sha1(c.id)', $hash_turno);
			$res=$this->db->get('cao c');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function formTurnos($data){
			if($this->db->insert('cao', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		public function actualizarTurnos($hash,$data){
			$this->db->where('sha1(id)', $hash);
			if($this->db->update('cao', $data)){
				return TRUE;
			}
			return FALSE;
		}
		
		public function listaTurnosTipos(){
			$this->db->order_by('codigo', 'asc');
			$this->db->where('estado', "activo");
			$this->db->where('id<>1');
			$this->db->where('id<>2');
			$res=$this->db->get('cao_turnos_ausencias');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function listaPerfiles(){
			$this->db->order_by('perfil', 'asc');
			$res=$this->db->get('usuarios_perfiles');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function listaCargos(){
			$this->db->order_by('cargo', 'asc');
			$res=$this->db->get('usuarios_cargos');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function listaTiposRed(){
			$this->db->order_by('nivel', 'asc');
			$res=$this->db->get('usuarios_tecnicos_niveles');
			if($res->num_rows()>0){
				return $res->result_array();
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


		public function listaProyectos(){
			$this->db->order_by('proyecto', 'asc');
			$res=$this->db->get('usuarios_proyectos');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function listaJefes(){
			$this->db->select("sha1(uj.id) as hash_jefes,
				uj.id as id_jefe,
				uj.id_jefe as id_usuario_jefe,
				CONCAT(u.nombres,' ',u.apellidos)  'nombre_jefe'
				");

			$this->db->join('usuarios u', 'u.id = uj.id_jefe', 'left');

			if($this->session->userdata('id_perfil')==3){
				if($this->session->userdata('verificacionJefe')=="1"){
					$this->db->where('uj.id', $this->session->userdata('id_jefe'));
				}
			}

			$this->db->order_by('nombre_jefe', 'asc');
			$res=$this->db->get('usuarios_jefes uj');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function listaTrabajadoresTurnos($jefe){
			$this->db->select("concat(substr(replace(rut,'-',''),1,char_length(replace(rut,'-',''))-1),'-',substr(replace(rut,'-',''),char_length(replace(rut,'-','')))) as 'rut_format',
				empresa,id,rut,
			    CONCAT(nombres,'  ',apellidos) as 'nombre_completo',
			    CONCAT(SUBSTRING_INDEX(nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(apellidos, ' ', '-2'), ' ', '1')) as 'nombre_corto'");
			
			if($this->session->userdata('id_perfil')==4){
				$this->db->where('rut', $this->session->userdata('rut'));
			}

			if($jefe!=""){
				$this->db->where('id_jefe', $jefe);
			}
			$this->db->where('estado',"1");

			$this->db->order_by('nombres', 'asc');
			$res=$this->db->get("usuarios");
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["rut"];
					$temp["text"]=$key["rut_format"]."  |  ".$key["nombre_completo"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}


		public function eliminarTurnosPorFecha($rut,$fecha){
			$this->db->where('rut_tecnico', $rut);
			$this->db->where('fecha', $fecha);
			if($this ->db->delete('cao')){
			  	return TRUE;
			}
			return FALSE;
		}

		public function eliminarTurnos($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('cao')){
			  	return TRUE;
			}
			return FALSE;
		}

		public function existeTurno($rut,$fecha){
			$this->db->where('rut_tecnico', $rut);
			$this->db->where('fecha', $fecha);
			$res = $this->db->get('cao');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function existeTurnoMod($hash,$rut,$fecha){
			$this->db->where('rut_tecnico', $rut);
			$this->db->where('fecha', $fecha);
			$this->db->where('sha1(id)<>', $hash);
			$res = $this->db->get('cao');
			/*echo $this->db->last_query();*/
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}


	/**********LICENCIAS************/

		public function getLicenciasList($inactivos){
			$this->db->select('ul.*,
				sha1(ul.id) as hash_id,
				u.id as id_usuario,
				u.rut as rut,
				u.empresa as empresa,
				CONCAT(u.nombres," " ,u.apellidos) as "usuario",
				CONCAT(us.nombres," ",us.apellidos) as "digitador",
				ul.id_digitador as id_digitador,
				if(ul.fecha_registro!="1970-01-01" and ul.fecha_registro!="0000-00-00",DATE_FORMAT(ul.fecha_registro,"%d-%m-%Y"),"") as "fecha_registro",
				if(ul.fecha_inicio!="1970-01-01" and ul.fecha_inicio!="0000-00-00",DATE_FORMAT(ul.fecha_inicio,"%d-%m-%Y"),"") as "fecha_inicio",
				if(ul.fecha_termino!="1970-01-01" and ul.fecha_termino!="0000-00-00",DATE_FORMAT(ul.fecha_termino,"%d-%m-%Y"),"") as "fecha_termino",
				CONCAT(u2.nombres," ",u2.apellidos) as "jefe",
				ult.id as id_tipo,
				ult.tipo as tipo_licencia,
				CASE 
		          WHEN DATEDIFF((date(ul.fecha_termino)), NOW()) > 0 THEN "si"
		          WHEN DATEDIFF((date(ul.fecha_termino)), NOW()) < 0 THEN "no"
		        END AS vigencia

				');
			$this->db->join('usuarios_licencias as ul', 'ul.id_usuario = u.id', 'left');
			$this->db->join('usuarios_licencias_tipos as ult', 'ult.id = ul.id_tipo_licencia', 'left');
			$this->db->join('usuarios as us', 'us.id = ul.id_digitador', 'left');
			$this->db->join('usuarios_jefes uj', 'uj.id = u.id_jefe', 'left');
			$this->db->join('usuarios u2', 'u2.id = uj.id_jefe', 'left');

			$this->db->order_by('u.nombres', 'asc');
			$this->db->where('ul.id_usuario is not null');

			if($inactivos!="on"){
				$this->db->where('u.estado', "1");
			}
			
			$res=$this->db->get('usuarios as u');
			return $res->result_array();
		}

		public function getCorreoJefeUsuario($id_jefe,$empresa){
			$this->db->select('rut_jefatura');
			$this->db->where('id', $id_jefe);
			$res=$this->db->get('mantenedor_nombrejefatura');
			$row=$res->row_array();

			$this->db->select('correo');
			$this->db->where('rut', $row["rut_jefatura"]);
			$this->db->where('empresa', $empresa);
			$res2=$this->db->get('usuarios');
			if($res2->num_rows()>0){
				$row2=$res2->row_array();
				return $row2["correo"];
			}
			return FALSE;
		}


		public function getDataRegistroLicencias($hash){
				$this->db->select('ul.*,
				sha1(ul.id) as hash_id,
				u.id as id_usuario,
				u.rut as rut,
				u.empresa as empresa,
				ul.id_digitador as id_digitador,
				if(ul.fecha_registro!="1970-01-01" and ul.fecha_registro!="0000-00-00",DATE_FORMAT(ul.fecha_registro,"%d-%m-%Y"),"") as "fecha_registro",
				if(ul.fecha_inicio!="1970-01-01" and ul.fecha_inicio!="0000-00-00",DATE_FORMAT(ul.fecha_inicio,"%d-%m-%Y"),"") as "fecha_inicio",
				if(ul.fecha_termino!="1970-01-01" and ul.fecha_termino!="0000-00-00",DATE_FORMAT(ul.fecha_termino,"%d-%m-%Y"),"") as "fecha_termino",
				ult.id as id_tipo,
				ult.tipo as tipo_licencia
				');
			$this->db->join('usuarios_licencias as ul', 'ul.id_usuario = u.id', 'left');
			$this->db->join('usuarios_licencias_tipos as ult', 'ult.id = ul.id_tipo_licencia', 'left');
			$this->db->join('usuarios as us', 'us.id = ul.id_digitador', 'left');
			$this->db->where('sha1(ul.id)', $hash);
			$res=$this->db->get('usuarios as u');
			return $res->result_array();
		}


		public function formActualizarLicencias($id,$data){
			$this->db->where('sha1(id)', $id);
		    if($this->db->update('usuarios_licencias', $data)){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function formIngresoLicencias($data){
			if($this->db->insert('usuarios_licencias', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaLicencias($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('usuarios_licencias')){
			  	return TRUE;
			}
			return FALSE;
		}

		public function listaTiposLicencias(){
			$this->db->order_by('tipo', 'asc');
			$res=$this->db->get('usuarios_licencias_tipos');
			return $res->result_array();
		}

	/**********VACACIONES************/
		
		public function listaUsuariosS2(){
			$this->db->select("concat(substr(replace(rut,'-',''),1,char_length(replace(rut,'-',''))-1),'-',substr(replace(rut,'-',''),char_length(replace(rut,'-','')))) as 'rut_format',
				rut,empresa,id,
				CONCAT(nombres,' ' ,apellidos) as nombre_completo
			");
			$this->db->where('estado',"1");
			$this->db->order_by('nombre_completo', 'asc');
			$res=$this->db->get("usuarios");
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["id"];
					$temp["text"]=$key["rut_format"]."  |  ".$key["nombre_completo"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}
		

		public function getVacacionesList($inactivos){
			$this->db->select('ul.*,
				sha1(ul.id) as hash_id,
				u.id as id_usuario,
				u.rut as rut,
				CONCAT(u.nombres," " ,u.apellidos) as "usuario",
				CONCAT(us.nombres," ",us.apellidos) as "digitador",
				ul.id_digitador as id_digitador,
				if(ul.fecha_registro!="1970-01-01" and ul.fecha_registro!="0000-00-00",DATE_FORMAT(ul.fecha_registro,"%d-%m-%Y"),"") as "fecha_registro",
				if(ul.fecha_inicio!="1970-01-01" and ul.fecha_inicio!="0000-00-00",DATE_FORMAT(ul.fecha_inicio,"%d-%m-%Y"),"") as "fecha_inicio",
				if(ul.fecha_termino!="1970-01-01" and ul.fecha_termino!="0000-00-00",DATE_FORMAT(ul.fecha_termino,"%d-%m-%Y"),"") as "fecha_termino",
				CONCAT(u2.nombres," ",u2.apellidos) as "jefe",
				CASE 
		          WHEN DATEDIFF((date(ul.fecha_termino)), NOW()) > 0 THEN "si"
		          WHEN DATEDIFF((date(ul.fecha_termino)), NOW()) < 0 THEN "no"
		        END AS vigencia
				');
			$this->db->join('usuarios_vacaciones as ul', 'ul.id_usuario = u.id', 'left');
			$this->db->join('usuarios as us', 'us.id = ul.id_digitador', 'left');
			$this->db->join('usuarios_jefes uj', 'uj.id = u.id_jefe', 'left');
			$this->db->join('usuarios u2', 'u2.id = uj.id_jefe', 'left');

			$this->db->order_by('u.nombres', 'asc');
			$this->db->where('ul.id_usuario is not null');

			if($inactivos!="on"){
				$this->db->where('u.estado', "1");
			}
			
			$res=$this->db->get('usuarios as u');
			return $res->result_array();
		}

		
		public function getDataRegistroVacaciones($hash){
				$this->db->select('ul.*,
				sha1(ul.id) as hash_id,
				u.id as id_usuario,
				u.rut as rut,
				ul.id_digitador as id_digitador,
				if(ul.fecha_registro!="1970-01-01" and ul.fecha_registro!="0000-00-00",DATE_FORMAT(ul.fecha_registro,"%d-%m-%Y"),"") as "fecha_registro",
				if(ul.fecha_inicio!="1970-01-01" and ul.fecha_inicio!="0000-00-00",DATE_FORMAT(ul.fecha_inicio,"%d-%m-%Y"),"") as "fecha_inicio",
				if(ul.fecha_termino!="1970-01-01" and ul.fecha_termino!="0000-00-00",DATE_FORMAT(ul.fecha_termino,"%d-%m-%Y"),"") as "fecha_termino",
				');
			$this->db->join('usuarios_vacaciones as ul', 'ul.id_usuario = u.id', 'left');
			$this->db->join('usuarios as us', 'us.id = ul.id_digitador', 'left');
			$this->db->where('sha1(ul.id)', $hash);
			$res=$this->db->get('usuarios as u');
			return $res->result_array();
		}


		public function formActualizarVacaciones($id,$data){
			$this->db->where('sha1(id)', $id);
		    if($this->db->update('usuarios_vacaciones', $data)){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function formIngresoVacaciones($data){
			if($this->db->insert('usuarios_vacaciones', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaVacaciones($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('usuarios_vacaciones')){
			  	return TRUE;
			}
			return FALSE;
		}

		public function getRutPorId($id){
			$this->db->select('rut');
			$this->db->where('id', $id);
			$res=$this->db->get('usuarios');
			if($res->num_rows()>0){
				$row = $res->row_array();
				return $row["rut"];
			}
			return FALSE;
		}

	/**********MANTENEDOR TURNOS************/
		
		public function getMantenedorTurnosList(){
			$this->db->select('sha1(id) as hash_id,t.*');
			$this->db->order_by('codigo', 'asc');
			$res=$this->db->get('cao_turnos_ausencias as t');
			return $res->result_array();
		}

		
		public function getDataMantenedorTurnos($hash){
			$this->db->select('sha1(id) as hash_id,t.*');
			$this->db->order_by('codigo', 'asc');
			$this->db->where('sha1(id)', $hash);
			$res=$this->db->get('cao_turnos_ausencias as t');
			return $res->result_array();
		}


		public function formActualizarMantenedorTurnos($id,$data){
			$this->db->where('sha1(id)', $id);
		    if($this->db->update('cao_turnos_ausencias', $data)){
		    	return TRUE;
		    }
		    return FALSE;
		}

		public function formMantenedorTurnos($data){
			if($this->db->insert('cao_turnos_ausencias', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		} 
		
		public function eliminaMantenedorTurnos($hash){
			$this->db->where('sha1(id)', $hash);
			if($this ->db->delete('cao_turnos_ausencias')){
			  	return TRUE;
			}
			return FALSE;
		}

		public function existeMantenedorTurno($codigo){
			$this->db->where('codigo', $codigo);
			$res = $this->db->get('cao_turnos_ausencias');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

		public function existeMantenedorTurnoMod($hash,$codigo){
			$this->db->where('codigo', $codigo);
			$this->db->where('sha1(id)<>', $hash);
			$res = $this->db->get('cao_turnos_ausencias');
			if($res->num_rows()>0){
				return TRUE;
			}
			return FALSE;
		}

}

