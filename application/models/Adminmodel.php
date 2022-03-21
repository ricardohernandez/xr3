<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class AdminModel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  	if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	/************NOTICIAS****************/

		public function nuevaNoticiaAdmin($data){
		    if($this->db->insert('noticias', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		public function getUltimaNoticia(){
			$this->db->order_by('id', 'desc');
			$this->db->limit(1);
			$res=$this->db->get('noticias');
			return $res->result_array();
		}

		public function getUltimaNoticiaEnvioCorreo(){
			$this->db->select('u.empresa as empresa_usuario,n.*,ne.*');
			$this->db->order_by('n.id', 'desc');
			$this->db->where('ne.enviado', "no");
			$this->db->join('noticias_envios ne', 'ne.id_noticia = n.id', 'left');
			$this->db->join('usuario as u', 'u.correo = ne.correo_usuario', 'left');
			$this->db->limit(30);
			// $this->db->group_by('ne.id_noticia');
			$res=$this->db->get('noticias n');
			return $res->result_array();
		}
		

		public function getCorreosEmpresa($empresa,$p){
			$this->db->select('correo');	
			
			$this->db->where('estado', "Activo");
			$this->db->where('LOCATE("@",correo)>0');
			
			if($empresa!="ambas"){
				$this->db->where('empresa', $empresa);
			}
			
			if($p){
				$this->db->where('empresa', "splice");
				$this->db->where('rut', "169868220");
			}

			
			$res=$this->db->get('usuario');
			return $res->result_array();
		}

		public function getCorreosEmpresaCron($empresa,$p){
			$this->db->select('correo');	

			if($p){

				$this->db->where('empresa', "splice");
				$this->db->where('rut', "169868220");
			}else{

				$this->db->where('estado', "Activo");
				$this->db->where('LOCATE("@",correo)>0');
			
				if($empresa!="ambas"){
					$this->db->where('empresa', $empresa);
				}
			}

			$res=$this->db->get('usuario');
			return $res->result_array();
		}

		public function getCorreosEmpresaDisponibles($empresa,$id_noticia){
			$this->db->select('correo_usuario');	
			$this->db->where('enviado', "no");
			$this->db->where('id_noticia', $id_noticia);
			if($empresa!="ambas"){
				$this->db->where('empresa', $empresa);
			}	
			$this->db->limit(30);
			$res=$this->db->get('noticias_envios');
			$this->db->last_query();
			return $res->result_array();
		}

		public function actualizaNoticiaCorreo($correo,$data){
			$this->db->where('correo_usuario', $correo);
			if($this->db->update('noticias_envios', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function vaciaNoticiaCorreo(){
			$this->db->truncate('noticias_envios');
			return TRUE;
		}

		public function insertaNoticiaCorreo($data){
			if($this->db->insert('noticias_envios', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function actualizaNoticia($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('noticias', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function listaCategorias(){
			$this->db->order_by('categoria', 'asc');
			$res=$this->db->get('noticias_categorias');
			return $res->result_array();
		}

		public function listaNoticiasAdmin(){
			$this->db->select('n.id as id,
				sha1(n.id) as hash_id,
				n.id_categoria as id_categoria,
				n.titulo as titulo,
				nc.categoria as categoria,
				n.fecha as fecha,
				n.descripcion as descripcion,
				n.imagen as imagen,
				n.url as url');

			$this->db->join('noticias_categorias as nc', 'nc.id = n.id_categoria', 'left');
			$this->db->order_by("n.fecha","desc");
			$res=$this->db->get("noticias as n");
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataNoticia($hash){
			$this->db->select('n.id as id,
				sha1(n.id) as hash_id,
				n.id_categoria as id_categoria,
				n.titulo as titulo,
				nc.categoria as categoria,
				n.fecha as fecha,
				n.descripcion as descripcion,
				n.imagen as imagen,
				n.url as url
				');

			$this->db->join('noticias_categorias as nc', 'nc.id = n.id_categoria', 'left');
			$this->db->where('sha1(n.id)', $hash);
			$res=$this->db->get("noticias as n");
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataNoticiaGaleria($hash){
			$this->db->select('
				g.id as id_galeria,
				g.id_noticia as id_noticia,
				g.titulo as titulo_galeria,
				g.imagen as imagen
				');

			$this->db->where('sha1(g.id_noticia)', $hash);
			$res=$this->db->get("noticias_galeria as g");
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getImagen($hash){
			$this->db->select('imagen');
			$this->db->where('sha1(id)', $hash);
			$res=$this->db->get('noticias');
			$row=$res->row_array();
			return $row["imagen"];
		}

		public function getImagenGaleria($id){
			$this->db->select('imagen');
			$this->db->where('id', $id);
			$res=$this->db->get('noticias_galeria');
			$row=$res->row_array();
			return $row["imagen"];
		}

		public function getImagenesGaleria($hash){
			$this->db->where('sha1(id_noticia)', $hash);
			$res=$this->db->get('noticias_galeria');
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function eliminaNoticia($hash){
		  $this->db->where('sha1(id)', $hash);
		  if($this ->db->delete('noticias')){
		  	$this->db->where('sha1(id_noticia)', $hash);
		    $this ->db->delete('noticias_galeria');
		  	return TRUE;
		  }
		  return FALSE;
		}

		public function eliminaEnvioCorreoNoticias($hash){
		  $this->db->where('sha1(id_noticia)', $hash);
		  if($this ->db->delete('noticias_envios')){
		  	return TRUE;
		  }
		  return FALSE;
		}

		public function eliminaImagen($id){
		  $this->db->where('id', $id);
		  if($this ->db->delete('noticias_galeria')){
		  	return TRUE;
		  }
		  return FALSE;
		}
		

		public function agregaImagenesNoticia($data){
			if($this->db->insert('noticias_galeria', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function getIdPorHash($hash){
			$this->db->select('id');
			$this->db->where('sha1(id)', $hash);
			$res=$this->db->get('noticias');
			$row=$res->row_array();
			return $row["id"];
		}

		public function getCantidadImagenes($id){
			$this->db->where('id_noticia', $id);
			$res=$this->db->get('noticias_galeria');
			return $res->num_rows();
		}

	/************INFORMACIONES****************/

		public function nuevaInformacion($data){
	   	    if($this->db->insert('noticias_informaciones', $data)){
			return $this->db->insert_id();
			}
			return FALSE;
		}

		public function actualizaInformacion($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('noticias_informaciones', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function listaInformaciones(){
			$this->db->select('n.id as id,
				sha1(n.id) as hash_id,
				n.titulo as titulo,
				n.fecha as fecha,
				n.hora as hora,
				n.id_digitador as id_digitador');

			$this->db->order_by("n.fecha","desc");
			$this->db->order_by("n.hora","desc");
			$res=$this->db->get("noticias_informaciones as n");
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataInformacion($hash){
			$this->db->select('n.id as id,
				sha1(n.id) as hash_id,
				n.titulo as titulo,
				n.fecha as fecha,
				n.hora as hora,
				n.id_digitador as id_digitador
				');

			$this->db->where('sha1(n.id)', $hash);
			$res=$this->db->get("noticias_informaciones as n");
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function eliminaInformacion($hash){
		  $this->db->where('sha1(id)', $hash);
		  if($this ->db->delete('noticias_informaciones')){
		  	return TRUE;
		  }
		  return FALSE;
		}

		

	/************NACIMIENTOS****************/

		public function nuevoNacimiento($data){
	    	if($this->db->insert('nacimientos', $data)){
				return $this->db->insert_id();
			}
			return FALSE;
		}

		public function actualizaNacimiento($id,$data){
			$this->db->where('sha1(id)', $id);
			if($this->db->update('nacimientos', $data)){
				return TRUE;
			}
			return FALSE;
		}

		public function listaNacimientos(){
			$this->db->select('n.id as id,
				sha1(n.id) as hash_id,
				n.rut_usuario as rut_usuario,
				CONCAT(u.primer_nombre," ",u.apellido_paterno) as "usuario",	
				n.esposa as esposa,
				n.hijo as hijo,
				n.fecha as fecha,
				n.imagen as imagen,
				n.comentarios as comentarios
				');

			$this->db->join('usuario as u', 'u.rut = n.rut_usuario', 'left');
			$res=$this->db->get("nacimientos as n");
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function getDataNacimientos($hash){
			$this->db->select('n.id as id,
				sha1(n.id) as hash_id,
				n.rut_usuario as rut_usuario,
				n.esposa as esposa,
				n.hijo as hijo,
				n.fecha as fecha,
				n.imagen as imagen,
				n.comentarios as comentarios
				');

			$this->db->where('sha1(n.id)', $hash);
			$this->db->join('usuario as u', 'u.rut = n.rut_usuario', 'left');
			$res=$this->db->get("nacimientos as n");
			if($res->num_rows()>0){
				return $res->result_array();
			}
			return FALSE;
		}

		public function listaUsuarios(){
			$this->db->select('u.id as id,
				u.rut as rut_usuario,
				u.primer_nombre as primer_nombre,
				u.apellido_paterno as apellido_paterno,
				u.apellido_materno as apellido_materno,
				u.empresa as empresa');
			$this->db->where('estado', "Activo");
			$this->db->order_by('u.primer_nombre', 'asc');
			$res=$this->db->get("usuario as u");
			if($res->num_rows()>0){
				$array=array();
				foreach($res->result_array() as $key){
					$temp=array();
					$temp["id"]=$key["rut_usuario"];
					$temp["text"]=$key["primer_nombre"]." ".$key["apellido_paterno"]." ".$key["apellido_materno"]." | ".$key["empresa"];
					$array[]=$temp;
				}
				return json_encode($array);
			}
			return FALSE;
		}

		public function getImagenNacimiento($hash){
			$this->db->select('imagen');
			$this->db->where('sha1(id)', $hash);
			$res=$this->db->get('nacimientos');
			$row=$res->row_array();
			return $row["imagen"];
		}

		
		public function eliminaNacimientos($hash){
			  $this->db->where('sha1(id)', $hash);
			  if($this ->db->delete('nacimientos')){
			  	return TRUE;
			  }
			  return FALSE;
		}


	public function listaVisitas(){
		$this->db->select("CONCAT(u.primer_nombre,' ',u.apellido_paterno) as 'usuario',
						a.nombre as aplicacion,
						av.modulo as modulo,
						av.fecha as fecha,
						av.navegador as navegador,
						av.ip as ip
			");
		$this->db->join('aplicaciones as a', 'a.id = av.id_aplicacion', 'left');
		$this->db->join('usuario as u', 'u.id = av.id_usuario', 'left');
		$this->db->order_by('av.id', 'desc');
		$this->db->limit(500);
		$res=$this->db->get('aplicaciones_visitas as av');
		return $res->result_array();
	}



}

/* End of file homeModel.php */
/* Location: ./application/models/front_end/homeModel.php */
