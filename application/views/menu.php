<script type="text/javascript">



   $(function(){



      const base = "<?php echo base_url() ?>";



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

        $.post("<?php echo base_url()?>"+'verComo'+"?"+$.now(), {"p":p} ,function(data) {

          if(data.res=="success"){

            location.reload();

          }

        },"json");

     });



     $.getJSON(base + "listaUsuariosInicio",{}, function(data) {

      response = data;

      console.log(response);

      }).done(function() {

         $("#usuario").select2({

            placeholder: 'Buscador de usuario',

            data: response,

            width: 'resolve',

            allowClear:true,

            templateResult: formatUsuarioOption,

         });

      });



      function formatUsuarioOption(data) {

         if (!data.id) {

            return data.text;

         }

         // Crea un elemento `<span>` que incluye la imagen y el texto del usuario

         if(data.image !=""){

            var $usuarioOption = $(

            '<span><img src="' +base+'fotos_usuarios/'+ data.image + '" class="user-image" /> ' + data.text + '</span>'

            );

         }else{

            var $usuarioOption = $(

            '<span><i class="fas fa-user-circle user-image nofoto lazyload"></i> ' + data.text + '</span>'

            );

         }

         



         return $usuarioOption;

      }



      $(document).off('change', '#usuario').on('change', '#usuario', function(event) {

         event.preventDefault();

         hash=$("#usuario").val(); 

         $("#modal_user").modal('toggle'); 

   	   $("#cierra_modal").attr("disabled", false);

         $("#usuario").val(null);

         $('#formusuario')[0].reset()

         $("#formusuario input,#formusuario select,#formusuario button,#formusuario").prop("disabled", true)

         $.ajax({

            url: base+"infoUsuario"+"?"+$.now(),  

            type: 'POST',

            cache: false,

            tryCount : 0,

            retryLimit : 3,

            data:{hash:hash},

            dataType:"json",

            beforeSend:function(){

               $(".btn_guardar_detalle").prop("disabled",true); 

               $("#cierra_modal").prop("disabled",true); 

            },

            success: function (data) {

               if(data.res=="ok"){

                  for(dato in data.usuario){

                  $("#usuario_nombre").val(data.usuario[dato].nombre_completo);

                  $("#usuario_fono_contacto").val(data.usuario[dato].telefono);

                  $("#usuario_correo").val(data.usuario[dato].correo);

                  $("#usuario_cargo").val(data.usuario[dato].cargo);

                  $("#usuario_proyecto").val(data.usuario[dato].proyecto);

                  $("#usuario_plaza").val(data.usuario[dato].plaza);

                  }

                  $("#cierra_modal").prop("disabled", false);

                  $(".btn_guardar_detalle").prop("disabled", false);



               }else if(data.res == "sess"){

                  window.location="../";

               }

               $(".btn_guardar_detalle").prop("disabled",false); 

               $("#cierra_modal").prop("disabled",false); 

            },

            error : function(xhr, textStatus, errorThrown ) {

               if (textStatus == 'timeout') {

                  this.tryCount++;

                  if (this.tryCount <= this.retryLimit) {

                        $.notify("Reintentando...", {

                        className:'info',

                        globalPosition: 'top right'

                        });

                        $.ajax(this);

                        return;

                  } else{

                        $.notify("Problemas en el servidor, intente nuevamente.", {

                        className:'warn',

                        globalPosition: 'top right'

                        });     

                        $('#modal_nuevo_usuario').modal("toggle");

                  }    

                  return;

               }

               if (xhr.status == 500) {

                  $.notify("Problemas en el servidor, intente más tarde.", {

                     className:'warn',

                     globalPosition: 'top right'

                  });

                  $('#modal_rcdc').modal("toggle");

               }

            } , timeout:35000

         })   

      }); 

   })

</script>

<style type="text/css">

   .modo_noche, .modo_dia{

      cursor:pointer;

   }

   .user-image{

      height: 32px!important;

      text-align: center!important;

      margin: 0 auto!important;

      width: 32px!important;

      border-radius: 50%!important;

   }

   @media (min-width: 1024px) {

      .modal_pass{

         width:45%!important;

      }

      .modal_user{

         width:45%!important;

      }

   }



   @media (max-width: 1024px) {

      .modal_pass{

         width:95%!important;

      }

      .modal_user{

         width:95%!important;

      }

   }

   /* 

   @media (max-width: 768px){

      .logo_pto {

         margin-top: 5px;

         width: 60px;

         margin-right:4px;

         margin-left:6px;

      }

      .logo_empresa{

         width: 70px;

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

      }

   } */



</style>

<!-- SIDENAV -->    



<?php 

   $perfil = $this->session->userdata('id_perfil');

?>



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

            <a href="#" class="sidenav__menu-url">Documentación</a>

            <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>

            <ul class="sidenav__menu-dropdown">

               <li><a class="sidenav__menu-url"  href="<?php echo base_url() ?>documentacion/capacitacion"> Capacitación </a></li>

               <?php  

                  if($perfil<=3){

               ?>

               <li><a class="sidenav__menu-url"  href="<?php echo base_url() ?>documentacion/datas_mandante"> Datas mandante </a></li>

               <?php

                  }

               ?>



               <?php  

                  if($perfil<=3){

               ?>



               <li class="">                   

                  <a class="sidenav__menu-url" href="<?php echo base_url() ?>documentacion/prevencion_riesgos">Prevenci&oacute;n riesgos</a>

                  <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>

                  <ul class="sidenav__menu-dropdown">

                     <li><a  class="menu_list" href="<?php echo base_url() ?>prevencion_riesgos">Documentos</a>

                     <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>prevencion_riesgos/checklist_prevencion">CKL2 - Checklist EPP y Riesgos del entorno </a>

                  </ul>

               </li>



               <?php

                  }

               ?>



               <?php  

                  //if($perfil<=2){

               ?>

                  <li><a class="sidenav__menu-url"  href="<?php echo base_url() ?>liquidaciones"> RLV - Registro de Liquidación variable </a></li>

               <?php

                  //}

               ?>

             

               <?php  

                  if($perfil<=3){

               ?>

                  <li><a class="sidenav__menu-url"  href="<?php echo base_url() ?>documentacion/reportes"> Reportes Operaciones</a>

                     <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>

                     <ul class="sidenav__menu-dropdown">

                        <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>dashboard/dashboard_operaciones">Dashboard operaciones</a>

                     </ul>

                  </li>

               <?php

                  }

               ?>

            </ul>

         </li>

         <li>

            <a href="#" class="sidenav__menu-url">Aplicaciones</a>

            <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>

            <ul class="sidenav__menu-dropdown">

               <li class="">

                  <a class="sidenav__menu-url" href="#!">CKL - Checklist operativos</a>

                  <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>

                  <ul class="sidenav__menu-dropdown">

                     <?php  

                        if($perfil<=3){

                     ?>



                     <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>checklist_herramientas"> CLH - Checklist herramientas</a></li>

                     <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>checklistHFC"> CLC - Checklist coaxial HFC</a></li>

                     <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>checklistFTTH"> CLF - Checklist fibra FTTH</a></li>



                     <?php

                        }

                        ?>

                     <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>ast">CLA - Checklist AST Análisis seguro de trabajo</a></li>



                     <?php  

                        if($perfil<=3){

                     ?>



                     <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>checklist_reportes">RCH - Reporte Checklist</a></li>



                     <?php

                        }

                     ?>



                  </ul>

               </li>

               <?php  

                  if($perfil<=3){

                   ?>

               <li><a class="sidenav__menu-url" href="<?php echo base_url() ?>cao"> CAO - Control de asistencia operacional</a></li>

               <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>rcdc"> RCDC - Registro centro de comando</a></li>

               <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>flota"> RF - Registro de Flota</a></li>

               <?php

                  }

               ?>



               <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>igt"> IGT - Indicadores de gestión del técnico</a></li>

               <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>materiales"> MAT - Materiales seriados</a></li>

               <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>calidad"> RCO - reporte calidad operaciones</a></li>

               <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>productividad"> RPO - Reporte productividad operaciones</a></li>

               <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>syr"> SYR - Solicitudes y requerimientos</a></li>

               <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>es"> MES - Modulo Escalamiento soporte</a></li>

               <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>rre"> RRE - Registro Retiro De Equipos</a></li>

            </ul>

         </li>

         <?php  

            if($perfil==1 || $perfil==2){

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

            if($perfil<=2){

              ?>

         <li>

            <a href="#" class="sidenav__menu-url">Mantenedores</a>

            <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>

            <ul class="sidenav__menu-dropdown">

               <?php  

               if($perfil==1 || $perfil==2){

               ?>

                  <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>mantenedor_usuarios"> Usuarios</a></li>

               <?php

                  }

               ?>

               <li class="">

                  <a class="sidenav__menu-url" href="#!">Mantenedores Checklist</a>

                  <button class="sidenav__menu-toggle" aria-haspopup="true" aria-label="Open dropdown"><i class="ui-arrow-down"></i></button>

                  <ul class="sidenav__menu-dropdown">

                     <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>mantenedor_herramientas"> Checklist Herramientas</a></li>

                     <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>mantenedor_checklist_hfc"> Checklist HFC</a></li>

                     <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>mantenedor_checklist_ftth"> Checklist HFC</a></li>

                     <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>mantenedor_responsables_fallos"> Responsables fallos herramientas</a></li>

                  </ul>

               </li>

               <li><a  class="sidenav__menu-url" href="<?php echo base_url() ?>mantenedor_metas_igt"> Metas IGT</a></li>

            </ul>

         </li>

         <?php

            }

         ?>



         <li class="">

            <a href="#" class="sidenav__menu-url modo_noche"><i class="fas fa-moon p-3" title="Modo oscuro" style="font-size:1rem;"></i></a>

            <a href="#" class="sidenav__menu-url modo_dia"><i class="fas fa-sun p-3" title="Modo claro"  style="font-size:1rem;"></i></a>

         </li>





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

   <div class="nav__holder" style="width:100%;">

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

               <img class="logo_empresa logo_claro" src="<?php echo base_url();?>assets3/imagenes/logo_claro.png" alt="logo">

               <img class="logo_empresa logo_oscuro " src="<?php echo base_url();?>assets3/imagenes/logo_blanco.png" alt="logo">

            </a>

            <!-- MENU IZQUIERDA -->



            <nav class="flex-child nav__wrap d-none d-lg-block">

               <ul class="nav__menu">

                  <li class="nav__dropdown ">

                     <a href="#">Documentación</a>

                     <ul class="nav__dropdown-menu">

                        <li><a  class="menu_list" href="<?php echo base_url() ?>documentacion/capacitacion"> Capacitación </a></li>

                       

                        <?php  

                           if($perfil<=3){

                        ?>



                           <li><a class="menu_list"  href="<?php echo base_url() ?>documentacion/datas_mandante"> Datas mandante</a></li> 



                        <?php

                           }

                        ?>



                        <?php  

                            if($perfil<=3){

                        ?>

                        

                           <li class="nav__dropdown">

                              <a class="menu_list" href="<?php echo base_url() ?>documentacion/prevencion_riesgos"> Prevenci&oacute;n riesgos</a>

                              <ul class="nav__dropdown-menu">

                                 <li><a  class="menu_list" href="<?php echo base_url() ?>prevencion_riesgos">Documentos</a>

                                 <li><a  class="menu_list" href="<?php echo base_url() ?>prevencion_riesgos/checklist_prevencion">CKLP - Checklist EPP y Riesgos del entorno </a>

                                 </li>

                              </ul>

                           </li>



                        <?php

                           }

                        ?>



                        <?php  

                           //if($perfil<=2){

                        ?>

                           <li><a class="menu_list"  href="<?php echo base_url() ?>liquidaciones"> RLV - Registro de Liquidación variable </a></li>

                        <?php

                           //}

                        ?>



                        <?php  

                           if($perfil<=3){

                        ?>



                        <li class="nav__dropdown">

                           <a class="menu_list" href="<?php echo base_url() ?>documentacion/reportes"> Reportes Operaciones</a>

                           <ul class="nav__dropdown-menu">

                              <li><a  class="menu_list" href="<?php echo base_url() ?>dashboard/dashboard_operaciones">Dashboard operaciones</a>

                              </li>

                           </ul>

                        </li>



                        <?php

                           }

                        ?>

                     </ul>

                  </li>

                  <li class="nav__dropdown ">

                     <a href="#">Aplicaciones</a>

                     <ul class="nav__dropdown-menu">

                        <li class="nav__dropdown">

                           <a class="menu_list" href="#!">CKL - Checklist operativos</a>

                           <ul class="nav__dropdown-menu">

                              <?php  

                                 if($perfil<=3){

                                  ?>

                              <li><a  class="menu_list" href="<?php echo base_url() ?>checklist_herramientas"> CLH - Checklist herramientas</a></li>

                              <li><a  class="menu_list" href="<?php echo base_url() ?>checklistHFC">CLC - Checklist coaxial HFC</a></li>

                              <li><a  class="menu_list" href="<?php echo base_url() ?>checklistFTTH">CLF - Checklist fibra FTTH</a></li>

                              <?php

                                 }

                                 ?>

                              <li><a  class="menu_list" href="<?php echo base_url() ?>ast">CLA - Checklist AST Análisis seguro de trabajo</a></li>



                              <?php  

                                 if($perfil<=3){

                              ?>

                              <li><a  class="menu_list" href="<?php echo base_url() ?>checklist_reportes">RCH - Reporte Checklist</a></li>

                              <?php

                                 }

                              ?>

                           </ul>

                        </li>

                        <?php  

                           if($perfil<=3){

                            ?>

                        <li><a  class="menu_list" href="<?php echo base_url() ?>cao"> CAO - Control de asistencia operacional</a></li>

                        <li><a  class="menu_list" href="<?php echo base_url() ?>rcdc"> RCDC - Registro centro de comando</a></li>

                        <li><a  class="menu_list" href="<?php echo base_url() ?>flota"> RF - Registro de Flota</a></li>

                        <?php

                           }

                           ?>

                        <li><a  class="menu_list" href="<?php echo base_url() ?>igt"> IGT - Indicadores de gestión del técnico</a></li>

                        <li><a  class="menu_list" href="<?php echo base_url() ?>materiales"> MAT - Materiales seriados</a></li>

                        <li><a  class="menu_list" href="<?php echo base_url() ?>calidad"> RCO - Reporte calidad operaciones</a></li>

                        <li><a  class="menu_list" href="<?php echo base_url() ?>productividad"> RPO - Reporte productividad operaciones</a></li>

                        <li><a  class="menu_list" href="<?php echo base_url() ?>syr"> SYR - Solicitudes y requerimientos</a></li>

                        <li><a  class="menu_list" href="<?php echo base_url() ?>es"> MES - Modulo Escalamiento soporte</a></li>

                        <li><a  class="menu_list" href="<?php echo base_url() ?>rre"> RRE - Registro Retiro De Equipos</a></li>





                      

                        <!-- <li class="nav__dropdown">

                           <a class="menu_list" href="#!">sub</a>

                           <ul class="nav__dropdown-menu">

                             <li><a  class="menu_list" href="">app</a></li>

                           </ul>

                           </li> -->

                     </ul>

                  </li>

                  <?php  

                     if($perfil==1 || $perfil==2){

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

                     if($perfil<=2){

                  ?>



                  <li class="nav__dropdown ">

                     <a href="#">Mantenedores</a>

                     <ul class="nav__dropdown-menu">

                        <?php  

                        if($perfil==1 || $perfil==2){

                        ?>

                           <li><a  class="menu_list" href="<?php echo base_url() ?>mantenedor_usuarios"> Usuarios</a></li>

                        <?php

                           }

                        ?>

                        <li class="nav__dropdown">

                           <a class="menu_list" href="#!">Mantenedores Checklist</a>

                           <ul class="nav__dropdown-menu">

                              <li><a  class="menu_list" href="<?php echo base_url() ?>mantenedor_herramientas">Checklist Herramientas</a></li>

                              <li><a  class="menu_list" href="<?php echo base_url() ?>mantenedor_checklist_hfc">Checklist HFC</a></li>

                              <li><a  class="menu_list" href="<?php echo base_url() ?>mantenedor_checklist_ftth">Checklist FTTH</a></li>

                              <li><a  class="menu_list" href="<?php echo base_url() ?>mantenedor_responsables_fallos"> Responsables fallos herramientas</a></li>

                           </ul>

                        </li>

                        <li><a  class="menu_list" href="<?php echo base_url() ?>mantenedor_metas_igt">Metas IGT</a></li>

                     </ul>

                  </li>



                  <?php

                     }

                     ?>



                   <li class="nav__dropdown ">

                     <a href="#">Buscador de usuarios</a>

                     <ul class="nav__dropdown-menu">

                     <?php  

                     if($perfil==1 || $perfil==2){

                     ?>

                     <li>                  

                        <select id="usuario" name="usuario" >

                           <option value="">Buscador de usuario</option>

                        </select> 

                     </li>

                     <?php

                        }

                     ?>

                     </ul>

                  </li>





               </ul>

            </nav>

            <!-- MENU DERECHA -->

            <div class="nav__right">

               <div class="nav__right-item nav__search">

				<ul class="nav__menu menu_derecho">

					<?php  

					if($perfil<=2){

					?>

					<li class="">

					<a href="<?php echo base_url() ?>ticket"> 

					Ticket 

					</a>

					</li>

					<?php

					}

					?>

					<?php  

					if($this->session->userdata('rut')=="169868220" || $this->session->userdata('rut')=="119752949"){

					?>

					<li class="ver_como" style="margin-left: 40px;margin-right: 10px">

					Ver como

					</li>

					<li class="">

					<select id="sp"  style="margin-bottom: 0px!important;" class="custom-select custom-select-sm">

						<?php  

							foreach($perfiles as $p){

							if($perfil==$p["id"]){

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



               <li class="mt-3">



                 <i class="fas fa-moon mx-2 mt-3 p-3 modo_noche" title="Modo oscuro"></i>

                 <i class="fas fa-sun mx-2 mt-3 p-3 modo_dia" title="Modo claro"  ></i>

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



					<ul class="nav__dropdown-menu">

						<li class="nav__dropdown">

							<a class="menu_list btn_modal_pass" href="#!">Cambiar Contrase&ntilde;a</a>

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

            <p class="title_section">Cambiar contrase&ntilde;a</p>

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



<!-- MODAL USUARIO-->

<div class="modal fade" id="modal_user" tabindex="-1"  data-backdrop="static" data-keyboard="false" role="dialog" aria-labelledby="" aria-hidden="true">

   <div class="modal-dialog modal_user" role="document">

      <div class="modal-content">

         <div class="modal-header">

            <p class="title_section" style="margin: auto;">Información de usuario</p>

         </div>

         <?php echo form_open_multipart("formusuario",array("id"=>"formusuario","class"=>"formusuario"))?>

         <div class="modal-body">

            <div class="row">

               <div class="col">

                  <input type="hidden" value="" name="hash" id="hash">

                  <div class="form-row">

                     <div class="form-group col-md-12">

                        <label for="">Nombre de usuario</label>

                        <input  id="usuario_nombre" name="usuario_nombre"  type="text" class="form-control form-control-sm" placeholder=""> 

                     </div>

                     <div class="form-group col-md-12">

                        <label for="">Número de contacto</label>

                        <input  id="usuario_fono_contacto" name="usuario_fono_contacto"  type="text" class="form-control form-control-sm" placeholder=""> 

                     </div>

                     <div class="form-group col-md-12">

                        <label for="">Correo electrónico</label>

                        <input  id="usuario_correo" name="usuario_correo"  type="text" class="form-control form-control-sm" placeholder=""> 

                     </div>

                     <div class="form-group col-md-12">

                        <label for="">Cargo</label>

                        <input  id="usuario_cargo" name="usuario_cargo"  type="text" class="form-control form-control-sm" placeholder=""> 

                     </div>

                     <div class="form-group col-md-12">

                        <label for="">Proyecto</label>

                        <input  id="usuario_proyecto" name="usuario_proyecto"  type="text" class="form-control form-control-sm" placeholder=""> 

                     </div>

                     <div class="form-group col-md-12">

                        <label for="">Plaza</label>

                        <input  id="usuario_plaza" name="usuario_plaza"  type="text" class="form-control form-control-sm" placeholder=""> 

                     </div>

                  </div>

               </div>

            </div>

            <div class="modal-footer">

               <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cierra_modal"><i class="fa fa-window-close icono_btn"></i> Cerrar</button>

            </div>

            <?php echo form_close(); ?>

         </div>

      </div>

   </div>

   

</div>