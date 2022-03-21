<?php  
if(FALSE!=$informaciones){
	foreach($informaciones as $inf){
		?>
			<li class="newsticker__item"><a href="#" class="newsticker__item-url"><?php echo $inf["titulo"] ?></a></li>
		<?php
	}
}
?>
