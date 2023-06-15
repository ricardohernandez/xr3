<style type="text/css">
  .texto_modal_datos{
    display: inline-block;
    width: 120px;
    font-weight: bold;
  }
  .img_torta{
    margin-top:-10px;
    width: 20px;
    float: right;
  }

  .item_activo_cumple{
   /*  background: #F48634; */
   /*  border-radius: 15px; */
    color: #fff!important;
  }

  .enlace_activo_cumple{
    display:block;
    color:#F38733!important;
    font-size: 14px;
    font-weight:bold;
  }

  .enlace_activo_cumple:hover{
    /* color:#fff!important; */
  }

  .txt_activo_cumple{
    display: inline;
    margin: -18px 20px;
    font-weight:bold;
    float: right;
    font-size: 15px;
    color: #F38733;
  }

</style>
<script type="text/javascript">
  $(function(){

    base="<?php echo base_url() ?>";
    $(document).off('click', '.info_cumple').on('click', '.info_cumple', function(event) {

      hash=$(this).data("hash");
       $.ajax({
        url: base+"infoUsuario",  
        type: 'POST',
        data: {"hash":hash},
        cache: false,
        success: function (data) {
          var json = JSON.parse(data);
          if(json.res == "ok"){      
            for(datos in json.usuario) {
              imagen=json.usuario[datos].imagen;
              ruta="<?php echo base_url() ?>fotos_usuarios/"+imagen;

              if(imagen!=""){
                $(".modal_datos_img").attr("src",ruta);
              }else{
                $(".modal_datos_img").hide();
              }

              fecha=json.usuario[datos].fecha_nacimiento;
              $(".modal_datos_nombre").html(json.usuario[datos].nombre_corto);
              $(".text_cumple").html("<span class='texto_modal_datos'>Cumpleaños  </span>"+fecha);
              $(".text_area").html("<span class='texto_modal_datos'>Zona  </span>"+json.usuario[datos].area);
              $(".text_cargo").html("<span class='texto_modal_datos'>Cargo  </span>"+json.usuario[datos].cargo);
              $(".text_proyecto").html("<span class='texto_modal_datos'>Proyecto  </span>"+json.usuario[datos].proyecto);
              $(".text_jefatura").html("<span class='texto_modal_datos'>Jefatura  </span>"+json.usuario[datos].jefe);
            }

            setTimeout(function(){$("#modal_datos").modal('toggle');  }, 300);

          }
        }
      });
    });
    
  })
</script>
	<?php  
	  foreach($cumpleanios as $c){
      $dia_actual=date("m-d");

      if($c["dia_actual"] == $dia_actual){
        ?>
        <article class="post-list-small__entry clearfix info_cumple item_activo_cumple" data-hash="<?php echo $c["hash"] ?>">
        <?php
      }else{
        ?>
        <article class="post-list-small__entry clearfix info_cumple" data-hash="<?php echo $c["hash"] ?>">
        <?php
      }
		?>

	    <div class="post-list-small__img-holder">
        <?php
        	if($c["foto"]!=""){
        ?>
	        <div class="thumb-container thumb-100">
	          <a href="#!">
	            <img data-src="<?php echo base_url() ?>fotos_usuarios/<?php echo $c["foto"]?>" src="<?php echo base_url() ?>fotos_usuarios/<?php echo $c["foto"]?>" alt=""  class="img_cumple lazyload">
	          </a>
	        </div>
        <?php
          }else{
            ?>
            <div class="thumb-container thumb-100">
              <a href="#!">
              <i class="fas fa-user-circle img_cumple nofoto lazyload"></i>
              </a>
            </div>
          <?php
          }
        ?>
	    </div>

	    <div class="post-list-small__body"> 
        <h5 class="post-list-extrasmall__entry-title">
        <?php 
	          $dia_actual=date("m-d");
	          if($c["dia_actual"] == $dia_actual){
	            ?>
	              <a href="#!" class="enlace_activo_cumple"><?php echo $c["nombre_corto"]?> - <?php echo mb_strimwidth($c["proyecto"], 0, 30, '...')." - ".$c["plaza"]; ?></a>
	              <span class="txt_activo_cumple"> Hoy</span><!-- <img data-src="http://intranet.km-t.cl/assets/imagenes/torta.png" src="http://intranet.km-t.cl/assets/imagenes/torta.png" alt=""  class="img_torta lazyload"> -->
	            <?php
	          }else{
	            ?>
	              <a href="#!"><?php echo $c["nombre_corto"]?> - <?php echo mb_strimwidth($c["proyecto"], 0, 30, '...')." - ".$c["plaza"]; ?></a>
	              <span class="fecha_cumple"><?php echo date_to_str_full($c["fecha_nacimiento"])?></span>
	            <?php
	          }
	        ?>
        </h5>
	    </div>   

	  </article>

	  <?php
   }
?>

<!-- MODAL INFO-->
  <div class="modal fade" id="modal_datos" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title ">Cumpleaños de <span class="modal_datos_nombre"></span></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">   
          <div class="row">
            
            <div class="col-lg-5 col-sm-12">
                  <img alt="" class="img-thumbnail modal_datos_img">
            </div>

            <div class="col-lg-7 col-sm-12">
                 <ul class="list-group list-group-flush">
                  <li class="list-group-item text_cumple"></li>
                  <li class="list-group-item text_area"></li>
                  <li class="list-group-item text_cargo"></li>
                  <li class="list-group-item text_proyecto"></li>
                  <li class="list-group-item text_jefatura"></li>
                </ul>
            </div>

          </div>         
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_informacion"><i class="fas fa-window-close"></i> Cerrar</button>
        </div>

      </div>
    </div>
  </div>