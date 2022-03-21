<script>
	$(function(){
      $(document).off('click', '.tabs__item').on('click', '.tabs__item',function(event) {
      	elem=$(this);
      	$(".tabs__item").removeClass("tabs__item--active");
      	elem.addClass("tabs__item--active");
      });
	})

</script>
<li class="tabs__item tabs__item--active">
  <a href="#tab-" class="tabs__trigger">Todo</a>
</li>

<?php  
if(FALSE!=$categorias){
 foreach($categorias as $cat){
    ?>
    <li class="tabs__item">
      <a href="#tab-<?php echo $cat["id"]?>" class="tabs__trigger"><?php echo $cat["categoria"]?></a>
    </li>

    <?php
  }
}
?>
