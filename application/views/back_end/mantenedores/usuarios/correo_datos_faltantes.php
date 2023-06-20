<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin:0;padding:0;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
<div style="background-color:#f5f5f5;width:90%;border:1px solid #e0e0e0;padding:10px 20px;margin:0 auto;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">

  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;">
    <tbody>
      <tr>
        <td width="100%" colspan="1" style="text-align:center;background-color:#eeeeee;padding:10px;font-size:20px;font-weight:bold;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
        <span style="color:#333333;"><?php echo $titulo?></span>
        </td>
      </tr>
    </tbody>
  </table>

  <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;">
      <tbody>
          <?php
          foreach ($campos_vacios as $campo => $cantidad) {
              // Modificar el nombre del campo
              $nombre_campo = str_replace('_', ' ', $campo); // Reemplazar guiones bajos por espacios

              // Eliminar la palabra "id" al comienzo del nombre del campo
              if (stripos($nombre_campo, 'id') === 0) {
                  $nombre_campo = substr($nombre_campo, 2); // Eliminar los primeros dos caracteres ("id")
              }
              
              ?>
              <tr>
                  <td style="padding:10px;border-bottom:1px solid #e0e0e0;">
                      <span style="font-size:16px;font-weight:bold;color:#333333;"><?php echo ucfirst(trim($nombre_campo)); ?></span>
                  </td>
                  <td style="padding:10px;border-bottom:1px solid #e0e0e0;">
                      <span style="font-size:16px;color:#333333;"><?php echo $cantidad; ?></span>
                  </td>
              </tr>
              <?php
          }
          ?>
      </tbody>
  </table>

</div>
</body>
</html>
