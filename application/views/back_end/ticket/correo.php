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
<div style="background-color:#fff;width:90%;border:1px solid #ccc;padding:10px 20px;margin:0 auto;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
  <br>
     <table align="center" border="1" cellpadding="0" cellspacing="0" width="100%" style="border-color:#ccc;border-collapse: collapse;font-size:12px!important;padding:20px;">
        <tbody>

            <tr>
	            <td width="100%" colspan="2" style="text-align:center;background-color:#32477C;color: #fff; padding:1px;margin:1px;font-size:15px;font-weight:bold;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
	               <span><?php echo $titulo; ?></span>
	            </td>
            </tr>

            <tr>
	            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
	            Descripci&oacute;n
	            </td>
	            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
	            <?php echo $dato["descripcion"]; ?>
	            </td>
            </tr>

            <tr>
	            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
	               Tipo
	            </td>
	            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
	            <?php echo $dato["tipo"];?>
	            </td>
            </tr>

            <tr>
              <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Fecha ingreso
              </td>
              <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              <?php echo $dato["fecha_ingreso"];?>
              </td>
            </tr>

            <tr>
              <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Usuario
              </td>
              <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              <?php echo $dato["digitador"];?>
              </td>
            </tr>

            <tr>
              <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Fecha respuesta  
              </td>
              <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              <?php echo $dato["fecha_respuesta"];?>
              </td>
            </tr>

        </tbody>
     </table>

     <br>
     <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
      <p style="text-align:center;">
      <a style="color: #32477C;text-decoration: underline;font-size:18px;padding:2px;margin:1px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;" href="https://xr3t.cl/ticket">Ir a aplicación </a>
      </p>
     </table>

</div>
</body>
<?php
}
?>
</html>
