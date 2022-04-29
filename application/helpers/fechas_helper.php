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


function dia($dia){
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
	$fecha=explode(' ',$fecha);
	$fecha2=$fecha[0];  
	@$hora=$fecha[1];  
	$fecha3=explode('-', $fecha2);
	$anio=$fecha3[0];$mes=$fecha3[1];$dia=$fecha3[2];
	return $dia." de ".meses($mes)."  ".$anio;
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

/*function asd(){
}*/

/* End of file csv_helper.php */
/* Location: ./system/helpers/csv_helper.php */