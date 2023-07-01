<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function meses($mes){
	switch ($mes) {
		case '1':$mes="Enero";break;
		case '2':$mes="Febrero";break;
		case '3':$mes="Marzo";break;
		case '4':$mes="Abril";break;
		case '5':$mes="Mayo";break;
		case '6':$mes="Junio";break;
		case '7':$mes="Julio";break;
		case '8':$mes="Agosto";break;
		case '9':$mes="Septiembre";break;
		case '10':$mes="Octubre";break;
		case '11':$mes="Noviembre";break;
		case '12':$mes="Diciembre";break;
	}
	return $mes;
}


function obtenerNumeroMes($mes) {
    $meses = array(
        'JUN' => '06',
        'JUL' => '07',
        'AGO' => '08',
        'SEP' => '09',
        'OCT' => '10',
        'NOV' => '11',
        'DIC' => '12',
        'ENE' => '01',
        'FEB' => '02',
        'MAR' => '03',
        'ABR' => '04',
        'MAY' => '05'
    );
    return isset($meses[$mes]) ? $meses[$mes] : '';
}


function mesesCorto($mes){
	switch ($mes) {
		case '1':$mes="Ene";break;
		case '2':$mes="Feb";break;
		case '3':$mes="Mar";break;
		case '4':$mes="Abr";break;
		case '5':$mes="Mayo";break;
		case '6':$mes="Jun";break;
		case '7':$mes="Jul";break;
		case '8':$mes="Ago";break;
		case '9':$mes="Sept";break;
		case '10':$mes="Oct";break;
		case '11':$mes="Nov";break;
		case '12':$mes="Dic";break;
	}
	return $mes;
}


function mesesTextoaNumero($mes){
	switch ($mes) {
		case 'ene':$mes="1";break;
		case 'feb':$mes="2";break;
		case 'mar':$mes="3";break;
		case 'abr':$mes="4";break;
		case 'may':$mes="5";break;
		case 'jun':$mes="6";break;
		case 'jul':$mes="7";break;
		case 'ago':$mes="8";break;
		case 'sep':$mes="9";break;
		case 'oct':$mes="10";break;
		case 'nov':$mes="11";break;
		case 'dic':$mes="12";break;
		default: $mes="0";
	}
	return $mes;
}

function meses_corto($mes){
	switch ($mes) {
		case '1':$mes="Ene";break;
		case '2':$mes="Feb";break;
		case '3':$mes="Mar";break;
		case '4':$mes="Abr";break;
		case '5':$mes="Mayo";break;
		case '6':$mes="Jun";break;
		case '7':$mes="Jul";break;
		case '8':$mes="Ago";break;
		case '9':$mes="Sept";break;
		case '10':$mes="Oct";break;
		case '11':$mes="Nov";break;
		case '12':$mes="Dic";break;
	}
	return $mes;
}


function diaCompleto($dia){
	switch ($dia) {
		case '1':$dia="Lunes";break;
		case '2':$dia="Martes";break;
		case '3':$dia="Miercoles";break;
		case '4':$dia="Jueves";break;
		case '5':$dia="Viernes";break;
		case '6':$dia="Sabado";break;
		case '7':$dia="Domingo";break;

	}
	return $dia;
}

function diaCorto($dia){
	switch ($dia) {
		case '1':$dia="L";break;
		case '2':$dia="M";break;
		case '3':$dia="M";break;
		case '4':$dia="J";break;
		case '5':$dia="V";break;
		case '6':$dia="S";break;
		case '0':$dia="D";break;

	}
	return $dia;
}
											
function diasConFecha($fecha){
	$fecha1=explode('-',$fecha);
	$anio=$fecha1[0];  
	$mes=$fecha1[1];  
	$dia=$fecha1[2];  
	$dia_semana=date('w', strtotime($fecha));
	return diaCorto($dia_semana)."".$dia."-".$mes;
}

function fecha_to_str($fecha){
	$fecha1=explode('-',$fecha);
	$anio=$fecha1[0];  
	$mes=$fecha1[1];  
	$dia=$fecha1[2];  
	$dia_semana=date('w', strtotime($fecha));
	// return $this->dia($dia_semana)."".$this->meses($mes)." ".$dia;
	return diaCorto($dia_semana)."".$dia."-".$mes;

	/*$fecha=explode(' ',$fecha);
	$fecha2=$fecha[0];  
	@$hora=$fecha[1];  
	$fecha3=explode('-', $fecha2);
	$anio=$fecha3[0];$mes=$fecha3[1];$dia=$fecha3[2];
	return $dia." de ".meses($mes)."  ".$anio;*/
}

function date_to_str($fecha){
	$fecha=explode('-',$fecha);
	$anio=$fecha[0];
	$mes=$fecha[1]; 
	$dia=$fecha[2]; 
	return $dia." de ".meses_corto($mes);
}

function date_to_str_full($fecha){
	$fecha=explode('-',$fecha);
	$anio=$fecha[0];
	$mes=$fecha[1]; 
	$dia=$fecha[2]; 
	return $dia." de ".meses($mes);
}

function anio($name=FALSE,$class=FALSE){
	 $date=date("Y");	
	 $año='<select name="'.$name.'" class="'.$class.'">';
		$año.='<option value="">Año</option>';
	 	$año.='<option value='.$date.'>'. $date.'</option>';
		$año.='<option value="2013">2013</option>';
	 $año.='</select>';
	 return $año;
}

function generaMeses($name=FALSE,$class=FALSE){
	 $fecha='<select name="'.$name.'" class="'.$class.'">';	 
	 	$fecha.='<option value="">Mes</option>';
	 for ($i=1; $i <=12 ; $i++) { 
	 	$fecha.='<option value='.$i.'>'.meses($i).'</option>';
	 }
	 $fecha.='</select>';
	 return $fecha;
}

function arrayRangoFechas($first, $last, $step = '+1 day', $output_format = 'd/m/Y' ) {
    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);
    while( $current <= $last ) {
        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }
    return $dates;
}

function dia($dia){
	switch ($dia) {
		case '1':$dia="L";break;
		case '2':$dia="M";break;
		case '3':$dia="M";break;
		case '4':$dia="J";break;
		case '5':$dia="V";break;
		case '6':$dia="S";break;
		case '0':$dia="D";break;

	}
	return $dia;
}

/*function fecha_to_str($fecha){
	$fecha1=explode('-',$fecha);
	$anio=$fecha1[0];  
	$mes=$fecha1[1];  
	$dia=$fecha1[2];  
	$dia_semana=date('w', strtotime($fecha));
	return $this->dia($dia_semana)."".$dia."-".$mes;
}*/


/*function fecha_to_str_noticias($fecha){
	$fecha1=explode('-',$fecha);
	$anio=$fecha1[0];  
	$mes=$fecha1[1];  
	$dia=$fecha1[2];  
	$dia_semana=date('d', strtotime($fecha));
	return $this->diaCompleto($dia_semana)."".$dia."-".$mes;
}*/

function fecha_to_str_noticias($f){
	$fecha = explode(' ',$f);
	$fecha2 = $fecha[0];  
	@$hora=$fecha[1]; 

	$fecha3 = explode('-', $fecha2);

	$anio = $fecha3[0];
	$mes = $fecha3[1];
	$dia = $fecha3[2];

	return ($dia)." de ".meses($mes)."  ".$anio;
	/*return $f;*/
}

function periodoFechas($desde,$hasta){
	$mes1 = substr($desde,5,2);
	$dia1 = substr($desde,8,9);

	$mes2 = substr($hasta,5,2);
	$dia2 = substr($hasta,8,9);

	return $dia1."-".$mes1." ".$dia2."-".$mes2;
}

function mesesPeriodo($periodo){

	if(date("d")>"24"){

		if($periodo == "actual"){
			$mes_actual = date('m', strtotime('+1 month', strtotime(date('Y-m-25'))));

		}elseif($periodo == "anterior"){
			$mes_anterior = date('m', strtotime(date('Y-m-25')));

		}elseif($periodo == "anterior2"){
			$mes_anterior2 = date('m', strtotime('-1 month', strtotime(date('Y-m-25'))));
		}

	}else{
		
		if($periodo == "actual"){
			$mes_actual = date('m', strtotime(date('Y-m-25')));

		}elseif($periodo == "anterior"){
			$mes_anterior= date('m', strtotime('-1 month', strtotime(date('Y-m-25'))));

		}elseif($periodo == "anterior2"){
			$mes_anterior2 = date('m', strtotime('-2 month', strtotime(date('Y-m-25'))));
		}

	}

	if($periodo=="actual"){
		return meses($mes_actual);
	}

	if($periodo=="anterior"){
		return meses(@$mes_anterior);
	}

	if($periodo=="anterior2"){
		return meses(@$mes_anterior2);
	}

}

/*function mesesPeriodo($periodo){

	
	if(date("d")>"24"){

		if($periodo=="actual"){
			$mes_actual = date('m', strtotime(date('Y-m-25')));

		}elseif($periodo=="anterior"){
			$mes_anterior = date('m', strtotime('-1 month', strtotime(date('Y-m-25'))));

		}elseif($periodo=="anterior_2"){
			$mes_anterior2 = date('m', strtotime('-2 month', strtotime(date('Y-m-25'))));
		}

	}else{
		
		if($periodo=="actual"){
			$mes_actual= date('m', strtotime('-1 month', strtotime(date('Y-m-25'))));

		}elseif($periodo=="anterior"){
			$mes_anterior= date('m', strtotime('-2 month', strtotime(date('Y-m-25'))));

		}elseif($periodo=="anterior_2"){
			$mes_anterior2 = date('m', strtotime('-4 month', strtotime(date('Y-m-25'))));

		}

	}


	if($periodo=="actual"){
		return meses($mes_actual);
	}

	if($periodo=="anterior"){
		return meses($mes_anterior);
	}

	if($periodo=="anterior2"){
		return meses($mes_anterior2);
	}

}*/


function getPeriodo($periodo){
	
	if(date("d")>"24"){

		if($periodo=="actual"){
			$mes_actual = date('Y-m-01', strtotime('+1 month', strtotime(date('Y-m-25'))));

		}elseif($periodo=="anterior"){
			$mes_anterior = date('Y-m-01', strtotime(date('Y-m-25')));

		}elseif($periodo=="anterior_2"){
			$mes_anterior2 = date('Y-m-01', strtotime('-1 month', strtotime(date('Y-m-25'))));
		}

	}else{
		
		if($periodo=="actual"){
			$mes_actual = date('Y-m-01', strtotime(date('Y-m-25')));

		}elseif($periodo=="anterior"){
			$mes_anterior= date('Y-m-01', strtotime('-1 month', strtotime(date('Y-m-25'))));

		}elseif($periodo=="anterior_2"){
			$mes_anterior2 = date('Y-m-01', strtotime('-2 month', strtotime(date('Y-m-25'))));

		}

	}


	if($periodo=="actual"){
		return ($mes_actual);
	}

	if($periodo=="anterior"){
		return ($mes_anterior);
	}

	if($periodo=="anterior2"){
		return ($mes_anterior2);
	}

}


/*function getPeriodo($periodo){
	
	if(date("d")>"24"){

		if($periodo=="actual"){
			$mes_actual = date('Y-m-01', strtotime(date('Y-m-25')));

		}elseif($periodo=="anterior"){
			$mes_anterior = date('Y-m-01', strtotime('-1 month', strtotime(date('Y-m-25'))));

		}elseif($periodo=="anterior_2"){
			$mes_anterior2 = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
		}

	}else{
		
		if($periodo=="actual"){
			$mes_actual= date('Y-m-01', strtotime('-1 month', strtotime(date('Y-m-25'))));

		}elseif($periodo=="anterior"){
			$mes_anterior= date('Y-m-01', strtotime('-2 month', strtotime(date('Y-m-25'))));

		}elseif($periodo=="anterior_2"){
			$mes_anterior2 = date('Y-m-01', strtotime('-4 month', strtotime(date('Y-m-25'))));

		}

	}


	if($periodo=="actual"){
		return ($mes_actual);
	}

	if($periodo=="anterior"){
		return ($mes_anterior);
	}

	if($periodo=="anterior2"){
		return ($mes_anterior2);
	}

}*/



function mesesPeriodoCalidad($periodo){
	
	if(date("d")>"24"){
		$mes_actual = date('m', strtotime(date('Y-m-d'). ' -2 months'));
		$mes_anterior = date('m', strtotime(date('Y-m-d'). ' -3 months'));
		$mes_anterior2 = date('m', strtotime(date('Y-m-d'). ' -4 months'));
		$mes_anterior3 = date('m', strtotime(date('Y-m-d'). ' -5 months'));
		$mes_anterior4 = date('m', strtotime(date('Y-m-d'). ' -6 months'));
		$mes_anterior5 = date('m', strtotime(date('Y-m-d'). ' -7 months'));
	}else{
		$mes_actual = date('m', strtotime(date('Y-m-d'). ' -1 months'));
		$mes_anterior = date('m', strtotime(date('Y-m-d'). ' -2 months'));
		$mes_anterior2 = date('m', strtotime(date('Y-m-d'). ' -3 months'));
		$mes_anterior3 = date('m', strtotime(date('Y-m-d'). ' -4 months'));
		$mes_anterior4 = date('m', strtotime(date('Y-m-d'). ' -5 months'));
		$mes_anterior5 = date('m', strtotime(date('Y-m-d'). ' -6 months'));
	}


	if($periodo=="actual"){
		return meses($mes_actual);
	}

	if($periodo=="anterior"){
		return meses($mes_anterior);
	}

	if($periodo=="anterior2"){
		return meses($mes_anterior2);
	}

	if($periodo=="anterior3"){
		return meses($mes_anterior3);
	}
	if($periodo=="anterior4"){
		return meses($mes_anterior4);
	}

	if($periodo=="anterior5"){
		return meses($mes_anterior5);
	}

}



function getFechasPeriodo($periodo){
	if(date("d")>"24"){

		if($periodo=="actual"){
			$desde_calidad = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
			$hasta_calidad = date('Y-m-d', strtotime(date('Y-m-24')));
			$desde_prod = date('Y-m-d', strtotime(date('Y-m-25')));
			$hasta_prod = date('Y-m-d', strtotime('+1 month', strtotime(date('Y-m-24'))));

		}elseif($periodo=="anterior"){
			$desde_calidad = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
			$hasta_calidad = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
			$desde_prod = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
			$hasta_prod= date('Y-m-d', strtotime(date('Y-m-24')));

		}elseif($periodo=="anterior_2"){
			$desde_calidad = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
			$hasta_calidad = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
			$desde_prod = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
			$hasta_prod = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
		}
		elseif($periodo=="anterior_3"){
			$desde_calidad = date('Y-m-d', strtotime('-4 month', strtotime(date('Y-m-25'))));
			$hasta_calidad = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-24'))));
			$desde_prod = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
			$hasta_prod = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
		}
		elseif($periodo=="anterior_4"){
			$desde_calidad = date('Y-m-d', strtotime('-5 month', strtotime(date('Y-m-25'))));
			$hasta_calidad = date('Y-m-d', strtotime('-4 month', strtotime(date('Y-m-24'))));
			$desde_prod = date('Y-m-d', strtotime('-4 month', strtotime(date('Y-m-25'))));
			$hasta_prod = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-24'))));
		}
		elseif($periodo=="anterior_5"){
			$desde_calidad = date('Y-m-d', strtotime('-6 month', strtotime(date('Y-m-25'))));
			$hasta_calidad = date('Y-m-d', strtotime('-5 month', strtotime(date('Y-m-24'))));
			$desde_prod = date('Y-m-d', strtotime('-4 month', strtotime(date('Y-m-25'))));
			$hasta_prod = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-24'))));
		}

	}else{
		
		if($periodo=="actual"){
			$desde_calidad = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
			$hasta_calidad= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));
			$desde_prod= date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-25'))));
			$hasta_prod= date('Y-m-d');

		}elseif($periodo=="anterior"){
			$desde_calidad = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
			$hasta_calidad = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));
			$desde_prod= date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-25'))));
			$hasta_prod = date('Y-m-d', strtotime('-1 month', strtotime(date('Y-m-24'))));

		}elseif($periodo=="anterior_2"){
			$desde_calidad = date('Y-m-d', strtotime('-4 month', strtotime(date('Y-m-25'))));
			$hasta_calidad = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-24'))));
			$desde_prod= date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-25'))));
			$hasta_prod = date('Y-m-d', strtotime('-2 month', strtotime(date('Y-m-24'))));

		}elseif($periodo=="anterior_3"){
			$desde_calidad = date('Y-m-d', strtotime('-5 month', strtotime(date('Y-m-25'))));
			$hasta_calidad = date('Y-m-d', strtotime('-4 month', strtotime(date('Y-m-24'))));
			$desde_prod= date('Y-m-d', strtotime('-4 month', strtotime(date('Y-m-25'))));
			$hasta_prod = date('Y-m-d', strtotime('-3 month', strtotime(date('Y-m-24'))));

	    }elseif($periodo=="anterior_4"){
			$desde_calidad = date('Y-m-d', strtotime('-6 month', strtotime(date('Y-m-25'))));
			$hasta_calidad = date('Y-m-d', strtotime('-5 month', strtotime(date('Y-m-24'))));
			$desde_prod= date('Y-m-d', strtotime('-5 month', strtotime(date('Y-m-25'))));
			$hasta_prod = date('Y-m-d', strtotime('-4 month', strtotime(date('Y-m-24'))));

		}elseif($periodo=="anterior_5"){
			$desde_calidad = date('Y-m-d', strtotime('-7 month', strtotime(date('Y-m-25'))));
			$hasta_calidad = date('Y-m-d', strtotime('-6 month', strtotime(date('Y-m-24'))));
			$desde_prod= date('Y-m-d', strtotime('-6 month', strtotime(date('Y-m-25'))));
			$hasta_prod = date('Y-m-d', strtotime('-5 month', strtotime(date('Y-m-24'))));
		}

	}

	$data = array(
		    "desde_calidad" => $desde_calidad,
			"hasta_calidad" => $hasta_calidad,
			"desde_prod" => $desde_prod,
			"hasta_prod" => $hasta_prod,
	);

	return $data;
}

/*function asd(){
}*/

/* End of file csv_helper.php */
/* Location: ./system/helpers/csv_helper.php */