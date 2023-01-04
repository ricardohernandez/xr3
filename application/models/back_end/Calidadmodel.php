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

		/*if($jefe!=""){
			$rut_jefe = $this->getRutTecnicoJefe($jefe);
			$cargo_jefe = $this->getCargoJefe($jefe);
		}*/

		$this->db->select("sha1(p.id) as hash,
			concat(substr(replace(rut,'-',''),1,char_length(replace(rut,'-',''))-1),'-',substr(replace(rut,'-',''),char_length(replace(rut,'-','')))) as 'rut_tecnico',
			p.*,
			CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',
	        if(p.diferencia_dias!='0', p.diferencia_dias,'') as 'diferencia_dias',
	        if(p.fecha!='0000-00-00' and p.fecha!='1970-01-01', DATE_FORMAT(p.fecha,'%Y-%m-%d'),'') as 'fecha',
	        if(p.fecha_2davisita!='0000-00-00'  and p.fecha_2davisita!='1970-01-01' , DATE_FORMAT(p.fecha_2davisita,'%Y-%m-%d'),'') as 'fecha_2davisita'
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

		
		$this->db->where('diferencia_dias<=', 30);
		/*if($jefe!=""){

			if($cargo_jefe=="18"){//TECNICO LIDER MUESTRA SU GENTE MAS EL MISMO
				$this->db->where('(u.id_jefe="'.$jefe.'" or p.rut_tecnico="'.$rut_jefe.'")');
				$this->db->group_by('p.rut_tecnico');
			}
			
			
			$this->db->where('u.id_jefe', $jefe);
		}else{
			//$this->db->group_by('p.rut_tecnico');
		}*/


		/*$this->db->where('falla', "si");*/
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

	public function dataPorOt($ot){
		$this->db->where('ot', $ot);
		$res = $this->db->get('productividad_calidad');
		if($res->num_rows()>0){
			return $res->result_array();
		}else{
			return FALSE;
		}
	}

	public function eliminarPeriodoActual($desde,$hasta){
		$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");
	    if($this ->db->delete('productividad_calidad')){
	    	return TRUE;
	    }
	    return FALSE;
	}

	public function formActualizaCalidad($id,$data){
		$this->db->where('id', $id);
		if($this->db->update('productividad_calidad', $data)){
			return TRUE;
		}
		return FALSE;
	}

	public function getRutTecnicoJefe($id_jefe){
		$this->db->select('us.rut rut');
		$this->db->join('usuarios us', 'us.id = uj.id_jefe', 'left');
		$this->db->where('uj.id', $id_jefe);
		$res=$this->db->get('usuarios_jefes uj');
		$row = $res->row_array();
		return $row["rut"];
	}

	public function getCargoJefe($id_jefe){
		$this->db->select('us2.id_cargo id_cargo');
		$this->db->join('usuarios us2', 'us2.id = uj.id_jefe', 'left');
		$this->db->where('uj.id', $id_jefe);
		$res=$this->db->get('usuarios_jefes uj');
		$row = $res->row_array();
		return $row["id_cargo"];
	}
	
	public function listaResumenCalidad($desde,$hasta,$trabajador,$jefe,$tipo_red,$desde_prod,$hasta_prod,$proyecto){
		$desde_str= date('d-m', strtotime($desde));
		$hasta_str= date('d-m', strtotime($hasta));

		if($jefe!=""){
			$rut_jefe = $this->getRutTecnicoJefe($jefe);
			$cargo_jefe = $this->getCargoJefe($jefe);
		}

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
            * 100 ),2),'%') AS 'calidad_FTTH'
		");

		/*(SELECT 
        	(SUM(puntaje)) as puntaje
	        FROM productividad pr
	        WHERE pr.rut_tecnico=p.rut_tecnico and
	        pr.fecha BETWEEN '".$desde_prod."' AND '".$hasta_prod."'
        ) as productividad*/
		 
		/*format(SUM(puntaje),0,'de_DE') as puntaje*/
		$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
		$this->db->join('usuarios_areas a', 'u.id_area = a.id', 'left');	
		$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
	

		if($trabajador!=""){
			$this->db->where('p.rut_tecnico', $trabajador);
		}

		if($proyecto!=""){
			$this->db->where('u.id_proyecto', $proyecto);
		}

		if($tipo_red!=""){
			$this->db->where('p.tipo_red', $tipo_red);
		}


		if($jefe!=""){

			if($cargo_jefe=="18"){//TECNICO LIDER MUESTRA SU GENTE MAS EL MISMO
				$this->db->where('(u.id_jefe="'.$jefe.'" or p.rut_tecnico="'.$rut_jefe.'")');
				$this->db->group_by('p.rut_tecnico');
			}

			//$this->db->where('u.id_jefe', $jefe);
		}else{
			$this->db->group_by('p.rut_tecnico');
		}

		
		$res=$this->db->get('productividad_calidad p');

		if($res->num_rows()>0){
			foreach($res->result_array() as $key){
				if($key["trabajador"]!=""){
					return $res->result_array();
				}
			}
		}
	}

	public function listaTecnicosLideres($jefe){
		/*$this->db->select("id,rut,CONCAT(u.nombres,' ',u.apellidos)  'nombre_jefe'");
		$this->db->where('id_jefe', $jefe);
		$res=$this->db->get('usuarios u');*/

		$this->db->select("uj.id id,CONCAT(u.nombres,' ',u.apellidos)  'nombre_jefe'");
		$this->db->join('usuarios u', 'u.id = uj.id_jefe', 'left');
		$this->db->where('u.id_jefe', $jefe);
		$res=$this->db->get('usuarios_jefes uj');
		return $res->result_array();
	}

	public function listaResumenCalidadLideres($desde,$hasta,$trabajador,$jefe,$tipo_red,$desde_prod,$hasta_prod,$proyecto){
		$desde_str= date('d-m', strtotime($desde));
		$hasta_str= date('d-m', strtotime($hasta));
		//LIDER DE OPERACIONES TECNICAS mostrar los SUBTOTAL DE GRUPO de cada uno de sus TECNICOS LIDER A CARGO 
		
		$tecnicos_lideres = $this->listaTecnicosLideres($jefe);
		/*echo "<pre>";
		print_r($tecnicos_lideres);*/
		$array = array();
		foreach($tecnicos_lideres as $lider){

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
	            * 100 ),2),'%') AS 'calidad_FTTH'
		    ");
			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			$this->db->join('usuarios_areas a', 'u.id_area = a.id', 'left');	
			$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			$this->db->where('u.id_jefe', $lider["id"]);
			$this->db->group_by('u.id_jefe');
			$res_tecnicos=$this->db->get('productividad_calidad p');
			/*echo $this->db->last_query();
			echo "<br>";*/
			foreach($res_tecnicos->result_array() as $tecnicos){
				$temp = array();
				$temp["trabajador"] = $lider["nombre_jefe"];
				$temp["rut"] = $tecnicos["rut"];
				$temp["periodo"] = $tecnicos["periodo"];
				$temp["q_HFC"] = $tecnicos["q_HFC"];
				$temp["fallos_HFC"] = $tecnicos["fallos_HFC"];
				$temp["q_FTTH"] = $tecnicos["q_FTTH"];
				$temp["fallos_FTTH"] = $tecnicos["fallos_FTTH"];
				$temp["calidad_HFC"] = $tecnicos["calidad_HFC"];
				$temp["calidad_FTTH"] = $tecnicos["calidad_FTTH"];
				$array[] = $temp;
			}

		}
			
		/*echo "<pre>";
		print_r($array);exit;*/

		return $array;
	}

	public function graficoHFC($desde,$hasta,$trabajador,$jefe,$proyecto){
		$desde_str= date('d-m', strtotime($desde));
		$hasta_str= date('d-m', strtotime($hasta));

		if($jefe!=""){
			$rut_jefe = $this->getRutTecnicoJefe($jefe);
			$cargo_jefe = $this->getCargoJefe($jefe);
		}
	
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

	        * 100 ),2),'%') AS 'calidad'

	    ",FALSE);

		$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	

		if($trabajador!=""){
			$this->db->where('rut_tecnico', $trabajador);
		}else{
			$this->db->group_by('periodo');
		}

		if($jefe!=""){

			if($cargo_jefe=="18"){//TECNICO LIDER MUESTRA SU GENTE MAS EL MISMO
				$this->db->where('(u.id_jefe="'.$jefe.'" or p.rut_tecnico="'.$rut_jefe.'")');
				$this->db->group_by('periodo');
			}

		}else{
			/*$this->db->group_by('p.rut_tecnico');*/
		}

		/*if($jefe!=""){
			$this->db->where('u.id_jefe', $jefe);
		}*/

		if($proyecto!=""){
			$this->db->where('u.id_proyecto', $proyecto);
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

	public function graficoHFCCalidadLideres($desde,$hasta,$trabajador,$jefe,$proyecto){
		$desde_str= date('d-m', strtotime($desde));
		$hasta_str= date('d-m', strtotime($hasta));

		if($jefe!=""){
			$rut_jefe = $this->getRutTecnicoJefe($jefe);
			$cargo_jefe = $this->getCargoJefe($jefe);
		}
		
		$tecnicos_lideres = $this->listaTecnicosLideres($jefe);
		$array = array();
		$suma_ordenes=0;$promedio_calidad=array();$suma_fallos=0;$periodo="";

		foreach($tecnicos_lideres as $lider){

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

		        ROUND((
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
		        * 100 ),2) AS 'calidad'",FALSE);

			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			$this->db->join('usuarios_areas a', 'u.id_area = a.id', 'left');	
			$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			$this->db->where('u.id_jefe', $lider["id"]);
			$this->db->group_by('u.id_jefe');
			$this->db->group_by('periodo');

			$res_tecnicos=$this->db->get('productividad_calidad p');

			/*if($trabajador!=""){
				$this->db->where('rut_tecnico', $trabajador);
			}else{
				$this->db->group_by('periodo');
			}*/
			
			foreach($res_tecnicos->result_array() as $tecnicos){
				if($tecnicos["trabajador"]!=""){
					$periodo = $tecnicos["periodo"];
					/*if($tecnicos["calidad"]!=""){
						$promedio_calidad[]= $tecnicos["calidad"];
					}*/

					$suma_ordenes += $tecnicos["ordenes"];
					$suma_fallos += $tecnicos["fallos"];
				}else{
					return false;
				}
			}
		}

		$temp = array();
	    $temp[] = (string)$periodo; 
	   
	    /*$a = array_filter($promedio_calidad);
		if(count($a)) {
		    $average = array_sum($a)/count($a);
		}else{
			$average=0;
		}*/

	    /*$temp[] = (float)round($average,2); */

	    $calidad =  ($suma_fallos/$suma_ordenes)*100;
	    $temp[] = (float) round($calidad,2);
	    $temp[] = (int) $suma_ordenes;
	    $temp[] = (int) $suma_fallos;
 	    //$temp[] = (string) $v = ($key['calidad']==0) ? null: $key['calidad'];
 	    //$temp[] = (string) $v = ($key['ordenes']==0) ? null: $key['ordenes'];
 	    //$temp[] = (string) $v = ($key['fallos']==0) ? null: $key['fallos'];
 	    $temp[] = strtotime($desde);
		$array[] = $temp;

		return $array;
	}	
	
	public function graficoFTTH($desde,$hasta,$trabajador,$jefe,$proyecto){
		$desde_str= date('d-m', strtotime($desde));
		$hasta_str= date('d-m', strtotime($hasta));

		if($jefe!=""){
			$rut_jefe = $this->getRutTecnicoJefe($jefe);
			$cargo_jefe = $this->getCargoJefe($jefe);
		}

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

			if($cargo_jefe=="18"){//TECNICO LIDER MUESTRA SU GENTE MAS EL MISMO
				$this->db->where('(u.id_jefe="'.$jefe.'" or p.rut_tecnico="'.$rut_jefe.'")');
				/*$this->db->group_by('p.rut_tecnico');*/
			}

		}else{
			/*$this->db->group_by('p.rut_tecnico');*/
		}

		/*if($jefe!=""){
			$this->db->where('u.id_jefe', $jefe);
		}*/

		if($proyecto!=""){
			$this->db->where('u.id_proyecto', $proyecto);
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

	public function graficoFTTHCalidadLideres($desde,$hasta,$trabajador,$jefe,$proyecto){
		$desde_str= date('d-m', strtotime($desde));
		$hasta_str= date('d-m', strtotime($hasta));

		if($jefe!=""){
			$rut_jefe = $this->getRutTecnicoJefe($jefe);
			$cargo_jefe = $this->getCargoJefe($jefe);
		}
		
		$tecnicos_lideres = $this->listaTecnicosLideres($jefe);
		$array = array();
		$suma_ordenes=0;$promedio_calidad=array();$suma_fallos=0;$periodo="";

		foreach($tecnicos_lideres as $lider){

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

		        ROUND((
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
		        * 100 ),2) AS 'calidad'",FALSE);

			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			$this->db->join('usuarios_areas a', 'u.id_area = a.id', 'left');	
			$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			$this->db->where('u.id_jefe', $lider["id"]);
			$this->db->group_by('u.id_jefe');
			$this->db->group_by('periodo');

			$res_tecnicos=$this->db->get('productividad_calidad p');

			/*if($trabajador!=""){
				$this->db->where('rut_tecnico', $trabajador);
			}else{
				$this->db->group_by('periodo');
			}*/
			
			foreach($res_tecnicos->result_array() as $tecnicos){
				if($tecnicos["trabajador"]!=""){
					$periodo = $tecnicos["periodo"];
					if($tecnicos["calidad"]!=""){
						$promedio_calidad[]= $tecnicos["calidad"];
					}
					$suma_ordenes += $tecnicos["ordenes"];
					$suma_fallos += $tecnicos["fallos"];
				}else{
					return false;
				}
			}
		}

		$temp = array();
	    $temp[] = (string)$periodo; 
	   
	    /*$a = array_filter($promedio_calidad);
		if(count($a)) {
		    $average = array_sum($a)/count($a);
		}else{
			$average="";
		}

	    $temp[] = (float)round($average,2); */
	    $calidad =  ($suma_fallos/$suma_ordenes)*100;
	    $temp[] = (float) round($calidad,2);
	    $temp[] = (int) $suma_ordenes;
	    $temp[] = (int) $suma_fallos;
 	    //$temp[] = (string) $v = ($key['calidad']==0) ? null: $key['calidad'];
 	    //$temp[] = (string) $v = ($key['ordenes']==0) ? null: $key['ordenes'];
 	    //$temp[] = (string) $v = ($key['fallos']==0) ? null: $key['fallos'];
 	    $temp[] = strtotime($desde);
		$array[] = $temp;

		return $array;
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
		
		$this->db->where('(u.id_cargo=32 or u.id_cargo=18)'); //u.id_cargo=10
		$this->db->order_by('nombre_jefe', 'asc');
		$res=$this->db->get('usuarios_jefes uj');
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
