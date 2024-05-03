<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<body style="margin:0;padding:0;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;" >
<div style="background-color:#fff;width:90%;padding:10px 20px;margin:0 auto;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
    
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
      <p style="text-align:center;">
      <b style="color: #32477C;text-decoration: none;font-size:16px;padding:2px;margin:1px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;" >
      <?php echo $asunto?>
      </b>
      </p>
    </table>

    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
      <p>
      <span style="text-decoration: none;font-size:14px;padding:2px;margin:1px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
      <?php echo $cuerpo?>
      </span>
      </p>
    </table>

    <table align="center" border="1" cellpadding="0" cellspacing="0" width="100%" style="border-color:#ccc;border-collapse: collapse;font-size:12px!important;padding:20px;">
      <tbody>

          <tr>
            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Solicitante 
            </td>
            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
            <?php echo $dato["nombre_solicitante"];?>
            </td>
          </tr>

          <tr>
            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Fecha de ingreso
            </td>
            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
            <?php echo $dato["fecha_ingreso"] ?? '' ?>
            </td>
          </tr>

          <tr>
            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Nombre de titular
            </td>
            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
            <?php echo $dato["titular_nombre"] ?? '' ?>
            </td>
          </tr>
          
          <tr>
            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Rut de titular 
            </td>
            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
            <?php echo $dato["titular_rut"] ?? '' ?>
            </td>
          </tr>
          <tr>
            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Dirección de titular 
            </td>
            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
            <?php echo $dato["titular_direccion"] ?? '' ?>
            </td>
          </tr>
          <tr>
            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Comuna de titular 
            </td>
            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
            <?php echo $dato["titular_comuna"] ?? '' ?>
            </td>
          </tr>
          <tr>
            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Teléfono de titular 
            </td>
            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
            <?php echo $dato["titular_telefono1"] ?? '' ?>
            </td>
          </tr>
          <tr>
            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Teléfono secundario de titular 
            </td>
            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
            <?php echo $dato["titular_telefono2"] ?? '' ?>
            </td>
          </tr>
          <tr>
            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Compañía
            </td>
            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
            <?php echo $dato["marca"] ?? '' ?>
            </td>
          </tr>
          <tr>
            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Pack
            </td>
            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
            <?php echo $dato["pack"] ?? '' ?>
            </td>
          </tr>
          <tr>
            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Observación de solicitante
            </td>
            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
            <?php echo $dato["observacion_solicitante"] ?? '' ?>
            </td>
          </tr>

          <?php 
          if($dato["estado"]!="Venta ingresada"){
            ?>

            <tr>
              <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
                  Estado
              </td>
              <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              <?php echo $dato["estado"];?>
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

            <tr>
              <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
                Responsable asignado 1
              </td>
              <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              <?php echo $dato["nombre_responsable1"];?>
              </td>
            </tr>

            <tr>
              <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
                Responsable asignado 2
              </td>
              <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              <?php echo $dato["nombre_responsable2"];?>
              </td>
            </tr>
            
            <tr>
              <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
                  Observación Responsables
              </td>
              <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              <?php echo $dato["observacion_responsable"];?>
              </td>
            </tr>
            <tr>
              <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
                  Número de Servicio
              </td>
              <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              <?php echo $dato["numero_ot"];?>
              </td>
            </tr>
 
            <?php
          }
          ?>

      </tbody>
     </table>

    <br>

    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
      <p>
        <span style="text-decoration: none;font-size:14px;padding:2px;margin:1px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
        <?php echo $cuerpo2?>
      </span>
      </p>
    </table>

    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
      <p>
      <a style="color: #32477C;text-decoration: underline;font-size:18px;padding:2px;margin:1px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;" href="https://xr3t.cl/rvt">Ir a aplicación </a>
      </p>
    </table>

    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
      <p>
      <span style=";font-size:13px;padding:2px;margin:1px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;"> 
       Atte.<br> Sistema PTO-XR3 
      </span>
      </p>
    </table>


</div>
</body>
 
</html>
