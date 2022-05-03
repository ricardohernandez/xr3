<script type="text/javascript">
	$(function(){
		$(".btn_modal_pass").click(function(event) {
		    $("#modal_pass").modal('toggle'); 
		    $(".cont_mensajes").html();
		    $("#c_actual").val(""); 
		    $("#nueva_c").val(""); 
		    $("#confirma_c").val(""); 
		    $("#btn_cambia_pass").attr("disabled", false);
		    $("#btn_cerrar_mod_pass").attr("disabled", false);
		});

		$(document).off('submit', '#formCambiarPass').on('submit', '#formCambiarPass',function(event) {
	      var url="<?php echo base_url()?>";
	      var formElement = document.querySelector("#formCambiarPass");
	      var formData = new FormData(formElement);

	      $.ajax({
	        url: $('.formCambiarPass').attr('action')+"?"+$.now(),  
	        type: 'POST',
	        data: formData,
	        cache: false,
	        processData: false,
	        dataType: "json",
	        contentType : false,
	        beforeSend:function(){
	         $("#btn_cambia_pass").html('<i class="fas fa-cog fa-spin icono_btn"></i> Cargando...').prop("disabled",true);
	         $("#btn_cerrar_mod_pass").attr("disabled", true);
	        },
	        success: function (data) {
	          if(data.res == "error"){
	             $("#btn_cambia_pass").html('<i class="fas fa-save icono_btn"></i> Actualizar').prop("disabled",false);
	             $("#btn_cerrar_mod_pass").attr("disabled", false);
	             $(".cont_mensajes").html('<div class="alert alert-danger alert-dismissible fade show">'+
	              ''+data.msg+''+
	              '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

	          }else if(data.res == "ok"){
	             $("#btn_cambia_pass").html('<i class="fas fa-save icono_btn"></i> Actualizar');
	             $(".cont_mensajes").html('<div class="alert alert-success alert-dismissible fade show">'+
	              ''+data.msg+''+
	              '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

	             setTimeout(function(){ 
	              $('#formCambiarPass')[0].reset();
	              $('#modal_pass').modal('toggle'); 
	              $("#btn_cambia_pass").attr("disabled", false);
	              $("#btn_cerrar_mod_pass").attr("disabled", false);
	             } ,2000);  
	          }
	        }
	       });
	       return false;
	    });

	 	$("#sp").change(function(event) {
		     p = $(this).val();
		     $.post('verComo'+"?"+$.now(), {"p":p} ,function(data) {
		        if(data.res=="success"){
		          location.reload();
		        }
		     },"json");
	    });
	})
</script>
<style type="text/css">
	@media (min-width: 1024px) {
    .modal_pass{
	    width:35%!important;
	  }
	}

	@media (max-width: 1024px) {
    .modal_pass{
      width:95%!important;
    }
	}

	@media (max-width: 768px){
    .logo_pto {
    	 margin-top: 5px;
       width: 60px;
       margin-right:4px;
       margin-left:6px;
    }
    .logo_empresa{
    	 width: 60px;
    }
  }

  @media (min-width: 768px){
    .logo_pto {
       width: 60px;
       margin-right:20px;
       margin-top: -2px;
    }
    .logo_empresa{
    	width: 70px;
    	margin-top: -10px!important;
    }
  }
</style>

<!-- SIDENAV -->    
	<header class="sidenav" id="sidenav">
	  <!-- CLOSE -->
	    <div class="sidenav__close">
	      <button class="sidenav__close-button" id="sidenav__close-button" aria-label="close sidenav">
	        <i class="ui-close sidenav__close-icon"></i>
	      </button>
	    </div>
	  <!-- SIDENAV -->
	    <nav class="sidenav__menu-container">
	      <ul class="sidenav__menu" role="menubar">
	        <li>
	          <a href="#" class="sidenav__menu-url">Aplicaciones</a>
	            <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
	            <ul class="sidenav__menu-dropdown">
                <!-- <li class="">
                <a class="sidenav__menu-url" href="#!">SUBMENU</a>
                <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
                <ul class="sidenav__menu-dropdown">
                   <li><a class="sidenav__menu-url" href="">APP</a></li>
                </ul>
                </li> -->
	                <?php  
		          			if($this->session->userdata('id_perfil')<=3){
		         	    ?>
          		 	<li><a class="sidenav__menu-url" href="<?php echo base_url() ?>cao"> CAO - Control de asistencia operacional</a></li>
	          		 	<?php
					          }
					        ?>
                <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>checklist_ots"> CLH - Checklist herramientas</a></li>
                <!-- <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>checklist_hfc"> Checklist HFC</a></li> -->
                <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>calidad"> RCO - reporte calidad operaciones</a></li>
                <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>productividad"> RPO - Reporte productividad operaciones</a></li>
	            </ul>
	        </li>

	        <?php  
	          if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
	            ?>
              <li>
                <a href="#" class="sidenav__menu-url">Editores</a>
                <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
                <ul class="sidenav__menu-dropdown">
                    <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>admin_xr3#noticias"> Noticias</a></li>
                    <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>admin_xr3#informaciones"> Informaciones</a></li>
                </ul>
              </li>
	            <?php
	          }
	        ?>


	        <?php  
	          if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
	            ?>
	             <li>
	                <a href="#" class="sidenav__menu-url">Mantenedores</a>
	                <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
	                <ul class="sidenav__menu-dropdown">
	                    <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>mantenedor_usuarios"> Mantenedor usuarios</a></li>
	                </ul>
	              </li>
	            <?php
	          }
	        ?>

	        <li>
	          <a href="#" class="sidenav__menu-url"><?php echo $this->session->userdata("nombre_completo")?></a>
	            <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>
	            <ul class="sidenav__menu-dropdown">
	              <li><a class="sidenav__menu-url btn_modal_pass" href="#!">Cambiar Contrase&ntilde;a</a></li>
	              <li><a class="sidenav__menu-url" href="<?php echo base_url()?>unlogin">Cerrar Sesi&oacute;n</a></li>
	            </ul>
	        </li>
	      </ul> 
	    </nav>
	  <!-- SIDENAV -->
  </header> 

<!-- MAIN -->  
	<main class="main oh" id="main">
  <header class="nav">
    <div class="nav__holder nav--sticky">
      <div class="container-fluid relative">
        <div class="flex-parent">
          <!-- BOTON MENU  -->
            <button class="nav-icon-toggle" id="nav-icon-toggle" aria-label="Open side menu">
              <span class="nav-icon-toggle__box">
                <span class="nav-icon-toggle__inner"></span>
              </span>
            </button> 
          <!-- LOGO -->
            <a href="<?php echo base_url() ?>" class="logo">
            	<!-- <img class="logo_pto" src="<?php echo base_url();?>assets3/imagenes/pto.jpg" alt="logo"> -->
              <img class="logo_empresa" src="<?php echo base_url();?>assets3/imagenes/logo.png" alt="logo">
            </a>
          <!-- MENU IZQUIERDA -->
            <nav class="flex-child nav__wrap d-none d-lg-block">              
              <ul class="nav__menu">
                <li class="nav__dropdown ">
                <a href="#">Aplicaciones</a>
                  <ul class="nav__dropdown-menu"> 
                  	<?php  
		          				if($this->session->userdata('id_perfil')<=3){
			         	    ?>
	          		    <li><a  class="menu_list" href="<?php echo base_url() ?>cao"> CAO - Control de asistencia operacional</a></li>
	          		    <?php
						          }
						        ?>
                    <li><a  class="menu_list" href="<?php echo base_url() ?>checklist_ots"> CLH - Checklist herramientas</a></li>
                  	<li><a  class="menu_list" href="<?php echo base_url() ?>calidad"> RCO - Reporte calidad operaciones</a></li>
                  	<li><a  class="menu_list" href="<?php echo base_url() ?>productividad"> RPO - Reporte productividad operaciones</a></li>
                    <!-- <li class="nav__dropdown">
                      <a class="menu_list" href="#!">sub</a>
                      <ul class="nav__dropdown-menu">
                        <li><a  class="menu_list" href="">app</a></li>
                      </ul>
                    </li> -->
                  </ul>
                </li>

              	<?php  
			         	 if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
			          ?>
                  <li class="nav__dropdown ">
                  <a href="#">Editores</a>
                      <ul class="nav__dropdown-menu"> 
                      	<li><a  class="menu_list" href="<?php echo base_url() ?>admin_xr3#noticias"> Noticias</a></li>
                          <li><a  class="menu_list" href="<?php echo base_url() ?>admin_xr3#informaciones"> Informaciones</a></li>
                      </ul>
                  </li>
		            <?php
				          }
				        ?>

				        <?php  
				          if($this->session->userdata('id_perfil')==1 || $this->session->userdata('id_perfil')==2){
				            ?>
		                        <li class="nav__dropdown ">
		                        <a href="#">Mantenedores</a>
		                            <ul class="nav__dropdown-menu"> 
						            	<li><a  class="menu_list" href="<?php echo base_url() ?>mantenedor_usuarios"> Mantenedor usuarios</a></li>
		                            </ul>
		                        </li>
				            <?php
				          }
				        ?>

	            </ul> 
        	  </nav> 
          <!-- MENU DERECHA -->
            <div class="nav__right">
                <div class="nav__right-item nav__search">
	             	
	             	<ul class="nav__menu menu_derecho">

	             			<?php  
	                 	 	if($this->session->userdata('id_perfil')<=2){
	                  ?>

	                  <li class="">
	                   <a href="<?php echo base_url() ?>ticket"> 
	                       Ticket desarrollos
	                    </a>
	                  </li>

	                  <?php
                  		}
                  	?>

	                  <?php  
	                 	 	if($this->session->userdata('rut')=="169868220" || $this->session->userdata('rut')=="119752949"){
	                  ?>

	                  <li class="" style="margin-left: 40px;margin-right: 10px">
	                      Ver como
	                  </li>

	                  <li class="">
											<select id="sp"  style="margin-bottom: 0px!important;" class="custom-select custom-select-sm">
										    <?php  
										    foreach($perfiles as $p){
										    if($this->session->userdata('id_perfil')==$p["id"]){
										      ?>
										        <option  selected value="<?php echo $p["id"] ?>"><?php echo $p["perfil"] ?> </option>
										      <?php
										    }else{
										      ?>
										        <option  value="<?php echo $p["id"] ?>"><?php echo $p["perfil"] ?> </option>
										      <?php
										    }
										    ?>
										    <?php
										    }
										    ?>
											</select>
	                 	</li>

	                 	<?php
                  		}
                  	?>

	                </ul>

	                <ul class="nav__menu menu_derecho">
		                <li class="nav__dropdown">
		                  <a href="#"> 
		                  	<?php  
		                  		if($this->session->userdata('foto')!=""){
		                  			?>
		                  				 <img style="width: 39px!important; height: 39px; margin: -8px 5px 0px 5px;border-radius: 50%;" class="" src="<?php echo base_url() ?>fotos_usuarios/<?php echo $this->session->userdata('foto')?>" alt="Foto">
		                  			<?php
		                  		}
		                  	?>
		                  <?php echo $this->session->userdata("nombre_completo")?>
		                  </a>
		                  <ul class="nav__dropdown-menu ">
		                     <li class="nav__dropdown">
		                        <a class="menu_list btn_modal_pass" href="#!" id="">Cambiar Contrase&ntilde;a</a>
		                        <a class="menu_list" href="<?php echo base_url()?>unlogin">Cerrar Sesi&oacute;n</a>
		                    </li>          
		                  </ul>
		                </li>
	                </ul>
                </div>   
            </div> 
        </div>
      </div>
    </div>
  </header> 

<!-- MODAL CONTRASEÑA-->
  <div class="modal fade" id="modal_pass" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="" aria-hidden="true">
    <div class="modal-dialog modal_pass" role="document">
      <div class="modal-content">
      
        <div class="modal-header">
          <h5 class="modal-title">Cambiar contrase&ntilde;a</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <?php echo form_open_multipart('cambiarPass', array('id'=>'formCambiarPass','class'=>'formCambiarPass')); ?>

          <div class="modal-body">
     
            <div class="row">
              <div class="col">
                <input type="hidden" value="" name="hash" id="hash">
                  
                  <div class="cont_mensajes"></div>

                  <div class="form-row">
                      
                      <div class="form-group col-md-12">
                          <label for="">Contrase&ntilde;a actual</label>
                          <input  id="c_actual" name="c_actual" size="20" maxlength="20" type="text" class="form-control form-control-sm" placeholder=""> 
                      </div>

                      <div class="form-group col-md-12">
                          <label for="">Nueva contrase&ntilde;a </label>
                          <input  id="nueva_c" name="nueva_c" size="20" maxlength="20" type="text" class="form-control form-control-sm" placeholder=""> 
                      </div>

                      <div class="form-group col-md-12">
                          <label for="">Confirme Contrase&ntilde;a</label>
                          <input  id="confirma_c" name="confirma_c" size="20" maxlength="20" type="text" class="form-control form-control-sm" placeholder=""> 
                      </div>
                  </div>
              </div>
          </div>

          <div class="modal-footer">
              <button type="submit" class="btn btn-primary" id="btn_cambia_pass"><i class="fa fa-save icono_btn"></i> Actualizar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal" id="btn_cerrar_mod_pass"><i class="fa fa-window-close icono_btn"></i> Cerrar</button>
          </div>

        <?php echo form_close();?>  

      </div>
    </div>
  </div>

</div>

