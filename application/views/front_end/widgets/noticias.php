<script>
  $(function(){
    url="<?php echo base_url() ?>";
    
    $('.video').mouseover(function(){
          $(this).get(0).play();
      }).mouseout(function(){
          $(this).get(0).pause();
    });

    $(document).off('click', '.link_noticia').on('click', '.link_noticia', function(event) {
      hash=$(this).data("hash");
      $(".cont_noticias").hide();
      $.get(url+"cargaVistaNoticia", {hash:hash} ,function( data ) {
        $(".cont_noticias").html(data).fadeIn('500');    
      });
    });

   });
</script>
<?php    
if(FALSE!=$noticias)
foreach($noticias as $n){
?>

<div class="col-lg-6 tabs__content-pane tabs__content-pane--active">    
  <article class="entry card">
    <div class="entry__img-holder card__img-holder">
      <a href="#!">
        <!-- <div class="thumb-container thumb-60"> -->
        <div class="thumb-container" style="height: 200px;">
         <?php  
           $archivo=explode('.',$n["imagen"]);
           $extension=$archivo[1];

           if($extension=="mp4"){
            ?>
              
             <video loop  class="video" controls>
                <source src="./noticias/videos/<?php echo $n["imagen"]?>" type="video/mp4">
             </video>  

            <?php
           }else{
            ?>
               <!-- <img data-hash="<?php echo $n["hash"]?>" class="link_noticia" src="../assets/imagenes/noticias/<?php echo $n["imagen"]?>"> -->
               <img data-hash="<?php echo $n["hash"]?>" class="link_noticia" src="./noticias/imagenes/<?php echo $n["imagen"]?>">
            <?php
           }
         ?>

        </div>
      </a>

      <a href="#" class="entry__meta-category entry__meta-category--label entry__meta-category--align-in-corner entry__meta-category--violet"><?php echo $n["categoria"]?></a>
     
    </div>
    <div class="entry__body card__body">
      <div class="entry__header">
        
        <h2 class="entry__title">
          <a href="#" class="link_noticia" data-hash="<?php echo $n["hash"]?>"><?php echo $n["titulo"] ?></a>
        </h2>
       
      </div>
      <div class="entry__excerpt">
          <ul class="entry__meta">
            <li class="entry__meta-date" style="text-transform: none;font-size: 12px">
              <?php echo fecha_to_str_noticias($n["fecha"]); ?> - <?php echo cortarTexto(strip_tags($n["descripcion"]),50) ?></p>
            </li>
        </ul>
      </div>
    </div>
  </article>
</div>
<?php  
}
?>
