<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<?php
foreach($data as $dato){
?>
<body style="margin:0;padding:0;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;" >
<div style="background-color:#fff;width:90%;padding:10px 20px;margin:0 auto;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
    
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
    <p>
    Se informa que se ha ingresado un fallo en AST - an&aacute;lisis seguro de trabajo 
    </p>
    <p>
      Descripci&oacute;n : <?php echo $dato["descripcion"] ?>
    </p>
  </table>
    
  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
    <p style="text-align:center;">
    <a style="color: #32477C;text-decoration: underline;font-size:18px;padding:2px;margin:1px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;" href="https://xr3t.cl/checklist_herramientas">Ir a aplicación </a>
    </p>
  </table>

</div>
</body>
<?php
}
?>
</html>
