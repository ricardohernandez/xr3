<script>
  $(function(){
    url="<?php echo base_url() ?>";

     $('a.gallery').featherlightGallery({
      previousIcon: '«',
      nextIcon: '»',
      galleryFadeIn: 300,
      openSpeed: 300
    });

    $('.light').nivoLightbox({
        effect: 'fadeScale',                        // The effect to use when showing the lightbox
        theme: 'default',                           // The lightbox theme to use
        keyboardNav: true,                          // Enable/Disable keyboard navigation (left/right/escape)
        clickOverlayToClose: true,                  // If false clicking the "close" button will be the only way to close the lightbox
        errorMessage: 'Problemas cargando la imagen.' // Error message when content can't be loaded
    });


   });
</script>

<?php  
if(FALSE!=$noticia)
foreach($noticia as $n){
?>

<div class="content-box">           
  <!-- standard post -->
    <article class="entry mb-0">
      
      <div class="single-post__entry-header entry__header">
        
        <h1 class="single-post__entry-title">
          <?php echo $n["titulo"]?>
        </h1>

        <div class="entry__meta-holder">
          <ul class="entry__meta">
            <!-- <li class="entry__meta-author">
              <span>by</span>
              <a href="#">DeoThemes</a>
            </li> -->
            <li class="entry__meta-date">
             <?php echo fecha_to_str_noticias($n["fecha"]); ?>
            </li>

            <!-- <li class="entry__meta-date">
               <a href="#" class="entry__meta-category entry__meta-category--label entry__meta-category--green"><?php echo $n["categoria"] ?></a>
            </li> -->

          </ul>

          <!-- <ul class="entry__meta">
            <li class="entry__meta-views">
              <i class="ui-eye"></i>
              <span>1356</span>
            </li>

            <li class="entry__meta-comments">
              <a href="#">
                <i class="ui-chat-empty"></i>13
              </a>
            </li>

          </ul> -->

        </div>
      </div> <!-- end entry header -->

      <div class="entry__img-holder">

        <?php  
         $archivo=explode('.',$n["imagen"]);
         $extension=$archivo[1];
         //$imagen= base_url()."assets/imagenes/noticias/".$n["imagen"];
         $imagen= "./noticias/imagenes/".$n["imagen"];

         if($extension=="mp4"){
          ?>
            
           <video loop  class="video entry__img" controls>
              <source src="./noticias/videos/<?php echo $n["imagen"]?>" type="video/mp4">
           </video>  

          <?php
         }else{
          ?>

          <div class="row">

          <div class="col-lg-12">
           <div class="entry__img-holder">
             <a data-lightbox-gallery="gallery1" class="light z-depth-3" href="<?php echo $imagen?>">
                <img class="entry__img" data-caption="" src="<?php echo $imagen?>">
             </a>
           </div>
          </div>

          </div>

        
          <?php
         }
       ?>

      </div>

      <div class="entry__article-wrap">

        <div class="entry__article">

          <p><?php echo stripslashes($n["descripcion"]) ?></p>

        </div> 
      </div> 

      <!-- Slider -->

          <div class="row">
              <?php  
              if($galeria!=FALSE)
                foreach($galeria as $g){
                 // $imagen= base_url()."assets/imagenes/noticias/".$g["imagen"];
                  $imagen= "./noticias/imagenes/".$g["imagen"];
                  ?>
                     <div class="col-lg-4">
                       <div class="entry__img-holder">
                         <a data-lightbox-gallery="gallery1" class="light z-depth-3" href="<?php echo $imagen?>">
                            <img class="entry__img" data-caption="" src="<?php echo $imagen?>">
                         </a>
                       </div>
                      </div>
                  <?php
                }
              ?>
          </div> 

      
    </article> 

  </div> <!-- end content box -->

<?php  
}
?>
