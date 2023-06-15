<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function corta_str($str,$largo){
	$texto=$str;
	$texto_size=$largo;
	$texto=substr($texto, 0,$texto_size);
	$index=strrpos($texto, " ");
	$texto=substr($texto, 0,$index); $texto.="<br>";
	return $texto;
}

function cortarTexto($texto, $numMaxCaract){
	if (strlen($texto) <  $numMaxCaract){
		$textoCortado = $texto;
	}else{
		$textoCortado = substr($texto, 0, $numMaxCaract);
		$ultimoEspacio = strripos($textoCortado, " ");
 
		if ($ultimoEspacio !== false){
			$textoCortadoTmp = substr($textoCortado, 0, $ultimoEspacio);
			if (substr($textoCortado, $ultimoEspacio)){
				$textoCortadoTmp .= '...';
			}
			$textoCortado = $textoCortadoTmp;
		}elseif (substr($texto, $numMaxCaract)){
			$textoCortado .= '...';
		}
	}
 
	return $textoCortado;
}