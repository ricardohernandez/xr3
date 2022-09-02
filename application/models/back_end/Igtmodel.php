<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Igtmodel extends CI_Model {

		public function __construct(){
		parent::__construct();
	}

	public function insertarVisita($data){
	  if($this->db->insert('aplicaciones_visitas', $data)){
			return TRUE;
		}
		return FALSE;
	}

	public function getPerfilTecnico($rut){
		$this->db->select('id_nivel_tecnico');
		$this->db->where('rut', $rut);
		$res = $this->db->get('usuarios');
		if($res->num_rows()>0){
			$row = $res->row_array();
			return $row["id_nivel_tecnico"];
		}
		return FALSE;
	}

	public function getFotoTecnico($rut){
		$this->db->select('foto');
		$this->db->where('rut', $rut);
		$res = $this->db->get('usuarios');
		if($res->num_rows()>0){
			$row = $res->row_array();
			return $row["foto"];
		}
		return FALSE;
	}

	
	public function getDataPorPerfilTecnico($nivel,$indicador){
		$this->db->where('id_nivel', $nivel);
		$this->db->where('id_indicador', $indicador);
		$res = $this->db->get('usuarios_tecnicos_niveles_metas');
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}


	public function getMetaIndicador($perfil_tecnico,$indicador,$periodo){
		if($periodo=="actual"){
			$this->db->select('utn.meta_actual as meta');
		}elseif($periodo=="anterior"){
			$this->db->select('utn.meta_anterior as meta');
		}

		$this->db->where('id_nivel', $perfil_tecnico);
		$this->db->where('id_indicador', $indicador);
		$res = $this->db->get('usuarios_tecnicos_niveles_metas utn');
		if($res->num_rows()>0){
			$row = $res->row_array();
			return $row["meta"];
		}
		return FALSE;
	}

	/**************PRODUCTIVIDAD PROM FTTH *********************/

		public function dataPromFTTH($desde,$hasta,$trabajador){
			$array_fechas = $this->date_range($desde,$hasta,"+1 day", "Y-m-d");
			$array = array();
			$temp=array();
			$cantidad = array();

			foreach($array_fechas as $fecha){
				$this->db->select("count(id)  as cantidad");
				$this->db->where('fecha="'.$fecha.'" AND `rut_tecnico` = "'.$trabajador.'" AND (tecnologia="FTTH" or tecnologia="dual")');

				$res2=$this->db->get('productividad');
				
				if($res2->num_rows()>0){
					$suma_cantidad=0;
					foreach($res2->result_array() as $key2){
						$suma_cantidad = $suma_cantidad+$key2["cantidad"];
					}
					
					if($suma_cantidad!=0){
						$cantidad[] = $suma_cantidad;
					}
				}


				$a = array_filter($cantidad);
				if(count($a)) {
				    $temp["Promedio"] = round(array_sum($a)/count($a),2);
				}else{
					$temp["Promedio"] = 0;
				}
			}

			if($temp["Promedio"]==0){
				return FALSE;
			}

			$temp2=array();
			$temp2[] = array("Label","Value"); 
			$temp2[] = array("",(float)$array[0]["Promedio"]); 
		    $filas = $temp2;
		    return $filas;

		}


	/**************PRODUCTIVIDAD FTTH+HFC *********************/

		public function dataProdHFCFTTH($desde,$hasta,$trabajador){
			$this->db->select('if(sum(puntaje)!=0, sum(puntaje) ,"") as total');
			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			$this->db->group_by('u.id');

			if($desde!="" and $hasta!=""){
				$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");
			}

			$this->db->where('p.rut_tecnico', $trabajador);		
			$res=$this->db->get('productividad p');
			$temp=array();
			$temp[] = array("Label","Value"); 
			
			foreach($res->result_array() as $key){
				$temp[] = array("",(int)$key["total"]); 
			}

		    $filas = $temp;
		    return $filas;
		}
		
		

	/**************PRODUCTIVIDAD PROMEDIO HFC *********************/

		public function dataPromHFC($desde,$hasta,$trabajador,$tipo){
			$this->db->select("sha1(p.id) as hash,
				p.id as id,
				u.foto as foto,
				CONCAT(u.nombres,' ',u.apellidos) as 'trabajador',
				a.area as area,
				if(sum(puntaje)!=0, sum(puntaje) ,'') as total,
				p.rut_tecnico as rut_tecnico,
				if(p.fecha!='0000-00-00', DATE_FORMAT(p.fecha,'%d-%m-%Y'),'') as 'fecha'");

			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			$this->db->join('usuarios_areas a', 'u.id_area = a.id', 'left');

			if($desde!="" and $hasta!=""){$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	}
			$this->db->where('p.rut_tecnico', $trabajador);
		
			if($tipo=="prom"){
				$this->db->where('p.tecnologia', "HFC");
			}

			$this->db->order_by('u.nombres', 'asc');
			$this->db->group_by('u.id');
			$res=$this->db->get('productividad p');

			$array_fechas = $this->date_range($desde,$hasta,"+1 day", "Y-m-d");
			$array = array();
			if($res->num_rows()>0){
				foreach($res->result_array() as $key){
					$temp=array();

					$dias = 0;
					$puntajes = array();

					foreach($array_fechas as $fecha){

						$this->db->select("if(sum(puntaje)!=0,sum(puntaje),0)  as puntaje");
					
						if($tipo=="prom"){
							$this->db->where('fecha="'.$fecha.'" AND `rut_tecnico` = "'.$key["rut_tecnico"].'" AND tecnologia="HFC"');
						}else{
							$this->db->where('fecha="'.$fecha.'" AND `rut_tecnico` = "'.$key["rut_tecnico"].'"');
						}

						$res2=$this->db->get('productividad');
						
						if($res2->num_rows()>0){
							$puntaje=0;
							foreach($res2->result_array() as $key2){
								$puntaje = $puntaje+$key2["puntaje"];
							}
							
							if($puntaje!=0){
								$dias++;
								$puntajes[] = $puntaje;
							}
						}

						$temp["dias"] = $dias;

						$a = array_filter($puntajes);
						if(count($a)) {
						    $temp["Promedio"] = round(array_sum($a)/count($a),2);
						}else{
							$temp["Promedio"] = 0;
						}
					}
					$array[]=$temp;
				}

				$temp2=array();
				$temp2[] = array("Label","Value"); 
			
				if($tipo=="prom"){
					$temp2[] = array("",(float)$array[0]["Promedio"]); 
				}else{
					$temp2[] = array("",(float)$array[0]["dias"]); 
				}
				
			    $filas = $temp2;
			    return $filas;

			}else{
				return FALSE;
			}

		}
		

	/******************CALIDAD HFC*********************/

		public function dataCalidadHFC($desde,$hasta,$trabajador){
			$this->db->select("
				CONCAT('".$desde."',' ','".$hasta."') as 'periodo',

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
			}

			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			$res=$this->db->get("productividad_calidad p");


			if($res->num_rows()>0){
				$row = $res->row_array();
				$temp=array();
				$temp[] = array("Label","Value"); 
				$temp[] = array("",(float)$row["calidad"]); 
			    $filas = $temp;
			    return $filas;
			}
			return FALSE;

		}

	/******************CALIDAD FTTH*********************/

		public function dataCalidadFTTH($desde,$hasta,$trabajador){
			$this->db->select("
				CONCAT('".$desde."',' ','".$hasta."') as 'periodo',

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

		        * 100 ),2),'%') AS 'calidad'

		    ",FALSE);

			$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	

			if($trabajador!=""){
				$this->db->where('rut_tecnico', $trabajador);
			}

			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			$res=$this->db->get("productividad_calidad p");


			if($res->num_rows()>0){
				$row = $res->row_array();
				$temp=array();
				$temp[] = array("Label","Value"); 
				$temp[] = array("",(float)$row["calidad"]); 
			    $filas = $temp;
			    return $filas;
			}
			return FALSE;

		}

	/******************DECLARACION OT *********************/

		public function dataDeclaracionOT($desde,$hasta,$trabajador){
			$this->db->select("
			    SUM(if(p.estado_ot = 'DRIVE NO DETECTA REGISTRO', 1, 0)) AS sin_registro,
				count(p.id) as  total,

           		count(p.id)-
	            SUM(CASE 
	        		WHEN p.estado_ot ='DRIVE NO DETECTA REGISTRO' 
	        		THEN 1
	            ELSE 0
	            END) AS 'con_registro',

	            ROUND((count(p.id)-
	            SUM(CASE 
	        		WHEN p.estado_ot ='DRIVE NO DETECTA REGISTRO' 
	        		THEN 1
	            ELSE 0
	            END))*100/count(p.id),2) as porcentaje

	        ");

			if($desde!="" and $hasta!=""){
				$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
			}

			if($trabajador!=""){
				$this->db->where('p.rut_tecnico', $trabajador);
			}

			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			$this->db->order_by('p.fecha', 'desc');
			$res=$this->db->get('productividad p');
			if($res->num_rows()>0){
				$row = $res->row_array();
				$temp=array();
				$temp[] = array("Label","Value"); 
				$temp[] = array("",(float)$row["porcentaje"]); 
			    $filas = $temp;
			    return $filas;
			}
			return FALSE;
		
		}

		public function graficoHFC($desde,$hasta,$trabajador){
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

		        * 100 ),2),'%') AS 'calidad'

		    ",FALSE);

			$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	

			if($trabajador!=""){
				$this->db->where('rut_tecnico', $trabajador);
			}

			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			$this->db->join('usuarios_areas a', 'u.id_area = a.id', 'left');	


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
				 	    $temp[] = strtotime($desde);
					    $array[] = $temp;

					}else{
						return false;
					}
				}
				return $array;
			}
		}

		public function graficoFTTH($desde,$hasta,$trabajador){
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
			}

			$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
			$this->db->join('usuarios_areas a', 'u.id_area = a.id', 'left');	
		
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
				 	    $temp[] = strtotime($desde);
					    $array[] = $temp;

					}else{
						return false;
					}
				}
				return $array;
			}
		}


	public function listaTrabajadoresIGT($jefe){
		$this->db->select("concat(substr(replace(rut,'-',''),1,char_length(replace(rut,'-',''))-1),'-',substr(replace(rut,'-',''),char_length(replace(rut,'-','')))) as 'rut_format',
			empresa,id,rut,
		    CONCAT(nombres,'  ',apellidos) as 'nombre_completo',
		    CONCAT(SUBSTRING_INDEX(nombres, ' ', '1'),'  ',SUBSTRING_INDEX(SUBSTRING_INDEX(apellidos, ' ', '-2'), ' ', '1')) as 'nombre_corto',
		");
		
		if($this->session->userdata('id_perfil')==4){
			$this->db->where('rut', $this->session->userdata('rut'));
		}else{
			 $this->db->where('(id_perfil=4 or id_perfil=3)');
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

	

	public function listaDetalleOtsDrive($desde,$hasta,$trabajador,$jefe){
		$this->db->select("sha1(p.id) as hash,
			p.*,
			TRIM(puntaje)+0 as puntaje,
			CONCAT(u.nombres,' ',u.apellidos) as 'tecnico',
	        if(p.fecha!='0000-00-00', DATE_FORMAT(p.fecha,'%Y-%m-%d'),'') as 'fecha'
		");


		if($desde!="" and $hasta!=""){
			$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	
		}

		if($trabajador!=""){
			$this->db->where('p.rut_tecnico', $trabajador);
		}


		if($jefe!=""){
			$this->db->where('u.id_jefe', $jefe);
		}

		$this->db->where('estado_ot', "DRIVE NO DETECTA REGISTRO");
		$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
		$this->db->order_by('p.fecha', 'desc');
		$res=$this->db->get('productividad p');
		// echo $this->db->last_query();
		if($res->num_rows()>0){
			return $res->result_array();
		}
		return FALSE;
	}

	
	public function date_range($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {
	    $dates = array();
	    $current = strtotime($first);
	    $last = strtotime($last);
	    while( $current <= $last ) {
	        $dates[] = date($output_format, $current);
	        $current = strtotime($step, $current);
	    }

	    return $dates;
	}

	

	public function listaResumen($desde,$hasta,$jefe,$trabajador){
		$this->db->select("sha1(p.id) as hash,
			p.id as id,
			u.foto as foto,
			CONCAT(u.nombres,' ',u.apellidos) as 'trabajador',
			a.area as area,
			if(sum(puntaje)!=0, sum(puntaje) ,'') as total,
			p.rut_tecnico as rut_tecnico,
			if(p.fecha!='0000-00-00', DATE_FORMAT(p.fecha,'%d-%m-%Y'),'') as 'fecha'");

		$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');
		$this->db->join('usuarios_areas a', 'u.id_area = a.id', 'left');

		if($desde!="" and $hasta!=""){$this->db->where("p.fecha BETWEEN '".$desde."' AND '".$hasta."'");	}
		$this->db->where('p.rut_tecnico', $trabajador);
     	/*	if($jefe!=""){	$this->db->where('u.id_jefe', $jefe);}*/

		$this->db->order_by('u.nombres', 'asc');
		$this->db->group_by('u.id');
		$res=$this->db->get('productividad p');

		$array_fechas = $this->date_range($desde,$hasta,"+1 day", "Y-m-d");
		$array = array();
		if($res->num_rows()>0){
			foreach($res->result_array() as $key){
				$temp=array();

				$temp["Zona"] = $key["area"];
				$temp["foto"] = $key["foto"];
				$temp["Trabajador"] = $key["trabajador"];
				$dias = 0;
				$puntajes = array();

				foreach($array_fechas as $fecha){

					$this->db->select("if(sum(puntaje)!=0,sum(puntaje),0)  as puntaje");
					$this->db->where('fecha="'.$fecha.'" AND `rut_tecnico` = "'.$key["rut_tecnico"].'"');
					$res2=$this->db->get('productividad');
					
					if($res2->num_rows()>0){
						$puntaje=0;
						foreach($res2->result_array() as $key2){
							$puntaje = $puntaje+$key2["puntaje"];
						}
						
						if($puntaje!=0){
							$dias++;
							$puntajes[] = $puntaje;
						}

						$temp[$this->fecha_to_str($fecha)] = round($puntaje,2);
					}else{
						$temp[$this->fecha_to_str($fecha)] = "";
					}

					$temp["Días"] = $dias;

					$a = array_filter($puntajes);
					if(count($a)) {
					    $temp["Promedio"] = round(array_sum($a)/count($a),2);
					}else{
						$temp["Promedio"] = 0;
					}
						
				}

				$temp["Total"] = round($key["total"],2);
				$array[]=$temp;
			}



			return $array;
		}else{
			return FALSE;
		}

		
	}


	public function puntosPorFechas($desde,$hasta,$trabajador,$jefe){
		$this->db->select("if(fecha!='0000-00-00', DATE_FORMAT(fecha,'%d-%m-%Y'),'') as 'fecha',
			if(fecha!='0000-00-00', DATE_FORMAT(fecha,'%d-%m'),'') as 'fecha_str',

		 SUM(puntaje) as puntos", FALSE);

		$this->db->join('usuarios u', 'u.rut = p.rut_tecnico', 'left');

		if($desde!="" and $hasta!=""){
			$this->db->where("fecha BETWEEN '".$desde."' AND '".$hasta."'");	
		}

		if($trabajador!=""){
			$this->db->where('rut_tecnico', $trabajador);
		}

		if($jefe!=""){
			$this->db->where('u.id_jefe', $jefe);
		}

		$this->db->group_by('(fecha)');
		$this->db->order_by('fecha', 'asc');
		$res=$this->db->get("productividad p");
		$array = array();
		
		$array[]= array(
			"Fecha",
			"Puntos",
			array('role'=> 'annotation'),
			array('role'=> 'annotationText'),
		);

		foreach($res->result_array() as $key){
			$temp = array();
		    $temp[] = (string)$key["fecha_str"]; 
		    $temp[] = (float) $key['puntos'];
	 	    $temp[] = (string) $v = ($key['puntos']==0) ? null: round($key['puntos'],2);
	 	    $temp[] = strtotime($key["fecha"]);

		    $array[] = $temp;
		}
		return $array;
	}


}