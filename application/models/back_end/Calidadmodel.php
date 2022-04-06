<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Calidadmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}
	
	public function insertarVisita($data){
		if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	

	public function listaCalidad($desde,$hasta,$trabajador,$jefe){
		$this->db->select("sha1(p.id) as hash,
			p.*,
			CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',
	        if(p.fecha!='0000-00-00', DATE_FORMAT(p.fecha,'%Y-%m-%d'),'') as 'fecha',
	        if(p.fecha_2davisita!='0000-00-00', DATE_FORMAT(p.fecha_2davisita,'%Y-%m-%d'),'') as 'fecha_2davisita'
		");

		$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');

		if($desde!="" and $hasta!=""){
			$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
		}

		if($trabajador!=""){
			$this->db->where('p.rut_tecnico', $trabajador);
		}

		if($jefe!=""){
			$this->db->where('u.id_jefe', $jefe);
		}

		$this->db->order_by('p.fecha', 'desc');
		$res=$this->db->get('productividad_calidad p');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	public function formCalidad($data){
		if($this->db->insert('productividad_calidad', $data)){
			return TRUE;
		}
		return FALSE;
	}

	public function existeOrdenCalidad($orden){
		$this->db->where('ot', $orden);
		$res=$this->db->get('productividad_calidad');
		if($res->num_rows()>0){
			return TRUE;
		}
		return FALSE;
	}

	public function actualizacionCalidad(){
	    $this->db->select('ultima_actualizacion');
	    $this->db->order_by('id', 'desc');
	    $res=$this->db->get('productividad_calidad',1);
	    $row = $res->row_array();
	    return $row["ultima_actualizacion"];
	}

	public function fecha_to_str($fecha){
		$fecha1=explode('-',$fecha);
		$anio=$fecha1[0];  
		$mes=$fecha1[1];  
		$dia=$fecha1[2];  
		$dia_semana=date('w', strtotime($fecha));
		// return $this->dia($dia_semana)."".$this->meses($mes)." ".$dia;
		return $this->dia($dia_semana)."".$dia."-".$mes;
	}

	public function graficoHFC($desde,$hasta,$trabajador,$jefe){
		$desde_str= date('d-m', strtotime($desde));
		$hasta_str= date('d-m', strtotime($hasta));

		$this->db->select("
			CONCAT(u.nombres,' ',u.apellidos) as 'trabajador',
			CONCAT('".$desde_str."',' ','".$hasta_str."') as 'periodo',

			SUM(CASE 
        		WHEN p.falla ='si' 
        		and p.tipo_red='HFC' 
        		THEN 1
            ELSE 0
            END) as fallos,

            SUM(CASE 
                WHEN p.tipo_red ='HFC'
                THEN 1
                ELSE 0
            END) as ordenes,

	        CONCAT(ROUND((
	            SUM(CASE 
	        		WHEN p.falla ='si' 
	        		and p.tipo_red='HFC' 
	        		THEN 1
	            ELSE 0
	            END)
	            /
	            SUM(CASE 
	                WHEN p.tipo_red ='HFC'
	                THEN 1
	                ELSE 0
	            END)
	        * 100 ),2),'%') AS 'calidad'",FALSE);

		$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	

		if($trabajador!=""){
			$this->db->where('rut_tecnico', $trabajador);
		}else{
			$this->db->group_by('periodo');
		}

		if($jefe!=""){
			$this->db->where('u.id_jefe', $jefe);
		}

		$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
		$this->db->join('usuarios_areas a', 'u.id_area = a.id', 'left');	
		/*$this->db->where('p.rut_tecnico', '173397666');*/
		
		/*$this->db->order_by('fecha', 'asc');*/
		/*$this->db->group_by('(fecha)');
		$this->db->order_by('fecha', 'asc');*/
		$res=$this->db->get("productividad_calidad p");
		$array = array();
		
		if($res->num_rows()>0){
			foreach($res->result_array() as $key){
				if($key["trabajador"]!=""){
					$temp = array();
				    $temp[] = (string)$key["periodo"]; 
				    $temp[] = (float)$key["calidad"]; 
				    $temp[] = (int) $key['ordenes'];
				    $temp[] = (int) $key['fallos'];
			 	   /* $temp[] = (string) $v = ($key['calidad']==0) ? null: $key['calidad'];
			 	    $temp[] = (string) $v = ($key['ordenes']==0) ? null: $key['ordenes'];
			 	    $temp[] = (string) $v = ($key['fallos']==0) ? null: $key['fallos'];*/
			 	    $temp[] = strtotime($desde);
				    $array[] = $temp;

				}else{
					return false;
				}
			}
			return $array;
		}
	}
	
	public function graficoFTTH($desde,$hasta,$trabajador,$jefe){
		$desde_str= date('d-m', strtotime($desde));
		$hasta_str= date('d-m', strtotime($hasta));

		$this->db->select("
				CONCAT(u.nombres,' ',u.apellidos) as 'trabajador',
				CONCAT('".$desde_str."',' ','".$hasta_str."') as 'periodo',

				SUM(CASE 
	        		WHEN p.falla ='si' 
	        		and p.tipo_red='FTTH' 
	        		THEN 1
	            ELSE 0
	            END) as fallos,

	            SUM(CASE 
	                WHEN p.tipo_red ='FTTH'
	                THEN 1
	                ELSE 0
	            END) as ordenes,

            
		        CONCAT(ROUND((
		            SUM(CASE 
		        		WHEN p.falla ='si' 
		        		and p.tipo_red='FTTH' 
		        		THEN 1
		            ELSE 0
		            END)
		            /
		            SUM(CASE 
		                WHEN p.tipo_red ='FTTH'
		                THEN 1
		                ELSE 0
		            END)

	            * 100 ),2),'%') AS 'calidad'",FALSE);

		$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	

		if($trabajador!=""){
			$this->db->where('rut_tecnico', $trabajador);
		}else{
			$this->db->group_by('periodo');
		}

		if($jefe!=""){
			$this->db->where('u.id_jefe', $jefe);
		}

		$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
		$this->db->join('usuarios_areas a', 'u.id_area = a.id', 'left');	
		/*$this->db->where('p.rut_tecnico', '173397666');*/
		
		/*$this->db->order_by('fecha', 'asc');*/
		/*$this->db->group_by('(fecha)');
		$this->db->order_by('fecha', 'asc');*/
		$res=$this->db->get("productividad_calidad p");
		$array = array();
		
		if($res->num_rows()>0){
			foreach($res->result_array() as $key){
				if($key["trabajador"]!=""){
					$temp = array();
				    $temp[] = (string)$key["periodo"]; 
				    $temp[] = (float)$key["calidad"]; 
				    $temp[] = (int) $key['ordenes'];
				    $temp[] = (int) $key['fallos'];
			 	   /* $temp[] = (string) $v = ($key['calidad']==0) ? null: $key['calidad'];
			 	    $temp[] = (string) $v = ($key['ordenes']==0) ? null: $key['ordenes'];
			 	    $temp[] = (string) $v = ($key['fallos']==0) ? null: $key['fallos'];*/
			 	    $temp[] = strtotime($desde);
				    $array[] = $temp;

				}else{
					return false;
				}
			}
			return $array;
		}
	}

	public function listaResumenCalidad($desde,$hasta,$trabajador,$jefe,$tipo_red,$desde_prod,$hasta_prod){
		$desde_str= date('d-m', strtotime($desde));
		$hasta_str= date('d-m', strtotime($hasta));

		$this->db->select("
			CONCAT(u.nombres,' ',u.apellidos) as 'trabajador',
			p.rut_tecnico as rut,
			CONCAT('".$desde_str."','  ','".$hasta_str."') as 'periodo',
			
			SUM(CASE 
             WHEN p.tipo_red='HFC' THEN 1
             ELSE 0
            END) as q_HFC,

			SUM(CASE 
             WHEN p.falla ='si' and p.tipo_red='HFC' THEN 1
             ELSE 0
            END) as fallos_HFC,

            SUM(CASE 
             WHEN p.tipo_red='FTTH' THEN 1
             ELSE 0
            END) as q_FTTH,

            SUM(CASE 
             WHEN p.falla ='si' and p.tipo_red='FTTH' THEN 1
             ELSE 0
            END) as fallos_FTTH,

            CONCAT(ROUND((
	            SUM(CASE 
	        		WHEN p.falla ='si' 
	        		and p.tipo_red='HFC' 
	        		THEN 1
	            ELSE 0
	            END)
	            /
	            SUM(CASE 
	                WHEN p.tipo_red ='HFC'
	                THEN 1
	                ELSE 0
	            END)
            * 100 ),2),'%') AS 'calidad_HFC',

	        CONCAT(ROUND((
	            SUM(CASE 
	        		WHEN p.falla ='si' 
	        		and p.tipo_red='FTTH' 
	        		THEN 1
	            ELSE 0
	            END)
	            /
	            SUM(CASE 
	                WHEN p.tipo_red ='FTTH'
	                THEN 1
	                ELSE 0
	            END)
            * 100 ),2),'%') AS 'calidad_FTTH',

	        (SELECT 
	        	format(SUM(puntaje),0,'de_DE') as puntaje
		        FROM productividad pr
		        WHERE pr.rut_tecnico=p.rut_tecnico and
		        pr.fecha BETWEEN '".$desde_prod."' AND '".$hasta_prod."'
	        ) as productividad
		");
											
		$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
		$this->db->join('usuarios_areas a', 'u.id_area = a.id', 'left');	
		$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
		/*$this->db->where('p.rut_tecnico', '173397666');*/
		/*$this->db->group_by('YEAR(fecha), MONTH(fecha)');*/

		if($trabajador!=""){
			$this->db->where('p.rut_tecnico', $trabajador);
		}

		if($jefe!=""){
			$this->db->where('u.id_jefe', $jefe);
		}

		if($tipo_red!=""){
			$this->db->where('p.tipo_red', $tipo_red);
		}

		$this->db->group_by('p.rut_tecnico');

		$res=$this->db->get('productividad_calidad p');

		if($res->num_rows()>0){
			foreach($res->result_array() as $key){
				if($key["trabajador"]!=""){
					return $res->result_array();
				}else{
					return false;
				}
			}
		}

	}

	public function listaJefes(){
		$this->db->select("sha1(uj.id) as hash_jefes,
			uj.id as id_jefe,
			uj.id_jefe as id_usuario_jefe,
			CONCAT(u.nombres,' ',u.apellidos)  'nombre_jefe'
			");

		$this->db->join('usuarios u', 'u.id = uj.id_jefe', 'left');

		if($this->session->userdata('verificacionJefe')=="1"){
			$this->db->where('uj.id', $this->session->userdata('id_jefe'));
		}

		$this->db->order_by('nombre_jefe', 'asc');
		$res=$this->db->get('usuarios_jefes uj');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	public function listaTrabajadoresCalidad($jefe){
		$this->db->select("concat(substr(replace(rut,'-',''),1,char_length(replace(rut,'-',''))-1),'-',substr(replace(rut,'-',''),char_length(replace(rut,'-','')))) as 'rut_format',
			empresa,id,rut,
		    CONCAT(nombres,'  ',apellidos) as 'nombre_completo',
		    CONCAT(SUBSTRING_INDEX(nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(apellidos, ' ', '-2'), ' ', '1')) as 'nombre_corto',
		");
		
		if($this->session->userdata('id_perfil')==4){
			$this->db->where('rut', $this->session->userdata('rut'));
		}

		if($jefe!=""){
			$this->db->where('id_jefe', $jefe);
		}

		$this->db->order_by('nombres', 'asc');
		$res=$this->db->get("usuarios");
		if($res->num_rows()>0){
			$array=array();
			foreach($res->result_array() as $key){
				$temp=array();
				$temp["id"]=$key["rut"];
				$temp["text"]=$key["rut_format"]."  |  ".$key["nombre_corto"];
				$array[]=$temp;
			}
			return json_encode($array);
		}
		return FALSE;
	}


	/*public function getCabecerasCalidad(){
		$cabeceras = array();
		$cabeceras[] = "Zona";
		$cabeceras[] = "Trabajador";
		$periodo_actual = date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-25')))) . " - ".date('d-m-Y', strtotime('-1 month', strtotime(date('Y-m-24'))));
		$periodo_anterior1 = date('d-m-Y', strtotime('-3 month', strtotime(date('Y-m-25')))) . " - ".date('d-m-Y', strtotime('-2 month', strtotime(date('Y-m-24'))));
		$periodo_anterior2 = date('d-m-Y', strtotime('-4 month', strtotime(date('Y-m-25')))) . " - ".date('d-m-Y', strtotime('-3 month', strtotime(date('Y-m-24'))));
		$periodo_anterior3 = date('d-m-Y', strtotime('-5 month', strtotime(date('Y-m-25')))) . " - ".date('d-m-Y', strtotime('-4 month', strtotime(date('Y-m-24'))));
		$cabeceras[] = $periodo_anterior3;
		$cabeceras[] = $periodo_anterior2;
		$cabeceras[] = $periodo_anterior1;
		$cabeceras[] = $periodo_actual;
		return $cabeceras;
		
	}*/
	
	/*public function listaResumenCalidad($desde,$hasta,$trabajador){
		$this->db->select("
			CONCAT(u.nombres,' ',u.apellidos) as 'trabajador',
			a.area as zona,

			SUM(CASE 
             WHEN p.tipo_red ='HFC' THEN 1
             ELSE 0
            END) AS HFC,

	        SUM(CASE 
             WHEN p.falla ='si' and p.tipo_red='HFC' THEN 1
             ELSE 0
            END) as falla_HFC,

            CONCAT(ROUND((
	            SUM(CASE 
	        		WHEN p.falla ='si' 
	        		and p.tipo_red='HFC' 
	        		and p.fecha BETWEEN '".$desde."' AND '".$hasta."'
	        		THEN 1
	            ELSE 0
	            END)
	            /
                SUM(CASE 
	                WHEN p.tipo_red ='HFC'
	                and p.fecha BETWEEN '".$desde."' AND '".$hasta."'
	                THEN 1
	                ELSE 0
                END)
             * 100 ),2),'%') AS 'actual'
			
		  ");

		$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
		//$this->db->where('p.rut_tecnico', '173397666');
		$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
		$this->db->join('usuarios_areas a', 'u.id_area = a.id', 'left');
		$this->db->group_by('p.rut_tecnico');
		$res=$this->db->get('productividad_calidad p');
		$array = array();

		foreach($res->result_array() as $key){
			$temp = array();
			$temp["Zona"] = $key["zona"];
			$temp["Trabajador"] = $key["trabajador"];
			$temp["Actual"] = $key["actual"];
			$array[] = $temp;
		}
		return $array;
	}*/


	
}
