  <script type="text/javascript">
    $(".cantidad_visitas_hoy").html("<?php echo $totalVisitasHoy?>");    
    $(".cantidad_visitas_ayer").html("<?php echo $totalVisitasAyer?>");    

    $( ".detalle_hoy" ).slideToggle( "slow", function() {  });
    
    $(".cont_visitas_hoy" ).click(function() {
      $( ".detalle_ayer" ).hide( );
      $( ".detalle_hoy" ).slideToggle( "slow", function() {  });
    });

    $( ".cont_visitas_ayer" ).click(function() {
      $( ".detalle_hoy" ).hide( );
      $( ".detalle_ayer" ).slideToggle( "slow", function() { });
    });
  </script>
  
  <article class="post-list-small__entry clearfix info_cumple ">

    <div class="row">
      <div class="col-lg-6">
       <a href="#!"><h4 class="widget-title cont_visitas_hoy" style="font-size: 13px">Hoy <span class="cantidad_visitas_hoy"></span></h4></a>
      </div>

      <div class="col-lg-6">
        <a href="#!"> <h4 class="widget-title cont_visitas_ayer" style="font-size: 13px">Ayer <span class="cantidad_visitas_ayer"></span></h4></a> 
      </div>
    </div>

    <div class="row">
       <div class="post-list-small__body detalle_hoy" style="display: none;"> 
        <h5 class="post-list-extrasmall__entry-title" style="margin-top: 2px!important;">
          <?php  
            foreach($visitas_hoy as $v){
          ?>
            <div class="row">
	            <div class="col-lg-12">
	             	<span class="fecha_cumple" style="float: left;margin-top:5px;margin-left: 10px;"><?php echo $v["cantidad"]." - ".$v["aplicacion"]." (".$v["descripcion"].")"?></span>
	            </div>
            </div>
          <?php
            }
          ?>
      
        </h5>
      </div>  

      <div class="post-list-small__body detalle_ayer" style="display: none;"> 
          <h5 class="post-list-extrasmall__entry-title" style="margin-top: 2px!important;">
            <?php  
              foreach($visitas_ayer as $v2){
            ?>
             <div class="row">
              <div class="col-lg-12">
               <span class="fecha_cumple" style="float: left;margin-top:5px;margin-left: 10px;"><?php echo $v2["cantidad"]." - ".$v2["aplicacion"]." (".$v2["descripcion"].")"?></span>
              </div>
            </div>
            <?php
              }
            ?>
               
          </h5>
        </div> 
      </div>
    </div>

  </article>

