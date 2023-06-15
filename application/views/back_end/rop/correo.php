<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>

<?php

  if($dato["estado"]=="1"){
    $color = "#107C41";
  }else{
    $color = "#32477C";
  }
?>

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
            Requerimiento 
            </td>
            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
            <?php echo $dato["requerimiento"]; ?>
            </td>
          </tr>

          <tr>
            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Fecha ingreso
            </td>
            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
            <?php echo $dato["fecha_ingreso"]." ".$dato["hora_ingreso"];?>
            </td>
          </tr>

          <tr>
            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Solicitante 
            </td>
            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
            <?php echo $dato["solicitante"];?>
            </td>
          </tr>

          <tr>
            <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              Comuna
            </td>
            <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
            <?php echo $dato["comuna"];?>
            </td>
          </tr>

          <?php 
          if($dato["id_estado"]==1){
            ?>
            
            <tr>
              <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
                  Persona asignada
              </td>
              <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              <?php echo $dato["usuario_asignado"];?>
              </td>
            </tr>
 
            <?php
          }
          ?>

          <?php 
          if($dato["id_estado"]==4){
            ?>
            
            <tr>
              <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
                  Fecha y hora de validación
              </td>
              <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              <?php echo $dato["fecha_validacion"]." ".$dato["hora_validacion"];?>
              </td>
            </tr>

            
            <tr>
              <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
                  Validador sistemico 
              </td>
              <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              <?php echo $dato["validador_sistema"];?>
              </td>
            </tr>

            <?php
          }
          ?>

        <?php 
          if($dato["id_estado"]==2 or $dato["id_estado"]==3){
            ?>
            
            <tr>
              <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
                  Fecha y hora de finalizado
              </td>
              <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              <?php echo $dato["fecha_fin"]." ".$dato["hora_fin"];?>
              </td>
            </tr>

            <tr>
              <td width="20%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
                Observación de finalizado
              </td>
              <td width="80%" style="padding-left:20px;border-color:#ccc;border-collapse: collapse;padding:2px;margin:1px;font-size:13px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
              <?php echo $dato["observacion_fin"];?>
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
      <a style="color: #32477C;text-decoration: underline;font-size:18px;padding:2px;margin:1px;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;" href="https://xr3t.cl/syr">Ir a aplicación </a>
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
