<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>No Javascript</title>
</head>
<style type="text/css" media="screen">
body{
	min-height: 600px;
	background-color: #E9EAED;
	color: #141823;
	font-family:arial;
}
.cont{
	width:80%;
	margin: 0 auto;
}
.cont_nojs{
	width: 445px;
	border-radius: 4px;
	margin-left: auto;
	margin-right: auto;
	background-color: #FFF;
	border: 1px solid #CCC;
	margin-top: 20px;
	margin-bottom: 20px;
	padding-top: 10px;
	line-height: 1.34;
}
.titulo{
border-color: #CCC;
padding-bottom: 0.5em;
padding: 6px 0px 16px;
border-bottom: 1px solid #AAA;
margin-left: 20px;
margin-right: 20px;
margin-top: 5px;
}
.titulo h3{
line-height: 20px;
min-height: 20px;
padding-bottom: 2px;
vertical-align: bottom;
color: #1E2D4C;
font-size: 16px;
color: #141823;
margin: 0px;
padding: 0px;
}
.desc{
margin-bottom: 15px;
padding-left: 20px;
padding-right: 20px;
padding-top: 10px;
}
.desc p{
margin-bottom: 15px;
padding-left: 20px;
padding-right: 20px;
padding-top: 10px;
}
</style>
<body>
	
<div class="cont">
	<div class="cont_nojs">
	<div class="titulo">
		<h3>Se requiere Javascript</h3>
	</div>
	<div class="desc">
		<p>Lo sentimos, pero para que la aplicaci&oacute;n funcione correctamente
		 debes activar JavaScript.<br> Si no puedes activarlo, Contactar a <br>ricardo.hernandez@km-t.cl </p>
	<center><a href="<?php echo base_url()?>">Recargar</a></center>
	</div>	
	</div>
</div>

</body>
</html>