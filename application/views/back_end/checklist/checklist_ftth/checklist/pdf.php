<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Checklist herramientas</title>
<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous"> -->
<style type="text/css">
   
  body{
 	 	font-family:"Helvetica Neue", Helvetica, Arial, sans-serif!important;
  }
  table{
  	 font-family:"Helvetica Neue", Helvetica, Arial, sans-serif!important;
  	 font-size: 11px!important;
  }
  table tr td{
  	padding :1px 3px!important;
  }
  .color_xr3{
    /*background-color: #172969;*/
    background-color: #E9ECEF;
  	color:#000;
    font-size: 11px!important;
  }
  .rojo{
    background-color: #DC3545;
    color: #fff;
    font-size: 11px!important;
  }
  .verde{
    font-size: 11px!important;
  }
  .gris{
    /*background-color: grey;
    color: #fff;
    font-size: 11px!important;*/
  }

	.table {
	  width: 100%;
	  max-width: 100%;
	  margin-bottom: 1rem;
	}

	.table th,
	.table td {
	  padding: 0.25rem;
	  vertical-align: top;
	  border-top: 1px solid #eceeef;
	}

	.table thead th {
	  vertical-align: bottom;
	  border-bottom: 2px solid #eceeef;
    font-size: 11px!important;
	}

	.table tbody + tbody {
	  border-top: 2px solid #eceeef;
	}

	.table .table {
	  background-color: #fff;
	}

	.table-sm th,
	.table-sm td {
	  padding: 0.1rem;
	}

	.table-bordered {
	  border: 1px solid #eceeef;
	}

	.table-bordered th,
	.table-bordered td {
	  border: 1px solid #eceeef;
	}

	.table-bordered thead th,
	.table-bordered thead td {
	  border-bottom-width: 2px;
    font-size: 11px!important;
	}

	.table-striped tbody tr:nth-of-type(odd) {
	  background-color: rgba(0, 0, 0, 0.05);
	}


	.table-active,
	.table-active > th,
	.table-active > td {
	  background-color: rgba(0, 0, 0, 0.075);
	}

	.table-hover .table-active:hover {
	  background-color: rgba(0, 0, 0, 0.075);
	}

	
	.text-center{
		text-align: center!important;
	}
</style>
</head>
<body>

</head>
<body>
    
<?php 
  if($data!=FALSE){
    foreach($data as $d){
   ?>
    <div id="header" class="header">
     <div class="header-logo">
       <img src="https://xr3t.cl/assets3/imagenes/logo2.png" style="width:110px;height: 46px!important">
        <span style="position:absolute;font-size: 12px;margin-left: 30px;margin-top:0px!important;font-weight: bold;text-decoration: underline;"><?php  echo $titulo  ?></span>
      </div>    
    </div>
  
    <div id="content">

      <table  class="table table-sm" border="0" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
              <td width="33%" class="text-center color_xr3" style="padding: 4px!important;font-size: 10px!important;"><b>Auditor : <?php echo $d["auditor"]; ?></b></td>
              <td width="33%" class="text-center color_xr3" style="padding: 4px!important;font-size: 10px!important;"><b>Fecha : <?php echo $d["fecha"]; ?></b></td>
              <td width="33%" class="text-center color_xr3" style="padding: 4px!important;font-size: 10px!important;"><b>Zona : <?php echo $d["area"]; ?></b></td>
            </tr>

            <tr>
              <td width="50%" class="text-center color_xr3" style="padding: 4px!important;font-size: 10px!important;"><b>Direcci&oacute;n : <?php echo $d["direccion"] ?></b></td>
              <td width="25%" class="text-center color_xr3" style="padding: 4px!important;font-size: 10px!important;"><b>Proyecto : <?php echo $d["proyecto"] ?></b></td>
              <td width="25%" class="text-center color_xr3" style="padding: 4px!important;font-size: 10px!important;"><b>OT : <?php echo $d["n_ot"] ?></b></td>
            </tr>
            
        </thead>
      </table>

      <table  class="table table-sm" border="0" cellpadding="0" cellspacing="0" style="margin-top:-15px!important;">
       <thead>
         <tr class="">     
            <!-- <td width="20%" class="text-center color_xr3" style="padding: 4px!important;font-size: 10px!important;"><b>Tipo</b></td> -->
            <td width="70%" class="text-center color_xr3" style="padding: 4px!important;font-size: 10px!important;"><b>Descripci&oacute;n</b></td>  
            <td width="5%" class="text-center color_xr3" style="padding: 4px!important;font-size: 10px!important;"><b>Res.</b></td>
            <td width="25%" class="text-center color_xr3" style="padding: 4px!important;font-size: 10px!important;"><b>Observaci&oacute;n</b></td>
          </tr>
        </thead>
        <tbody>
         <!--  echo "<td><span style='padding:5px;' class='".$clase."'>".$det["estado_str"]."</span></td>"; -->
          <?php
            if($detalle!=FALSE){
             foreach ($detalle as $det) {  

                if($det["estado_str"] == "nook"){
                  $clase = "rojo";

                }elseif($det["estado_str"] == "noap"){
                  $clase = "gris";

                }else{
                  $clase = "verde";
                }

              ?>        
                <tr class="<?php echo $clase?>">     
                  <!--  <td> <?php echo $det["tipo"];?></td> -->
                   <td> <?php echo $det["descripcion"];?></td>
                   <td> <?php echo $det["estado_str"];?></td>
                   <td> <?php echo $det["observacion"];?></td>
                </tr>
                <?php 
                } 
              }
          ?>
        </tbody>
      </table>
        
    <?php
    	}
    	}
    ?>
      
  </div>
</body>
</html>