
 <?php   
   foreach($ingresos as $u){
 ?>
 
  <article class="post-list-small__entry clearfix info_ingreso" data-hash="<?php echo $u["hash"] ?>">
    <div class="post-list-small__img-holder">
      <?php
        if($u["foto"]!=""){
      ?>
        <div class="thumb-container thumb-100">
          <a href="#!">
            <img data-src="<?php echo base_url() ?>fotos_usuarios/<?php echo $u["foto"]?>" src="<?php echo base_url() ?>fotos_usuarios/<?php echo $u["foto"]?>" alt=""  class="img_cumple lazyload">
          </a>
        </div>
      <?php
      ?> 

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
        <a href="#!"><?php echo $u["nombre_corto"]?> - <?php echo ($u["plaza"])?></a>
        <span class="fecha_cumple"><?php  echo date_to_str_full($u["fecha"])?></span>
      </h5>
    </div>                  
  </article>

<?php
  }
?>
</li>
</ul>           

