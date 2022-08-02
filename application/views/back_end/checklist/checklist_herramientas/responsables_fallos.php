<style type="text/css">
    .red{
	    background-color: #DC3545;
	    color: #fff;
    }
    .modal_responsable_fallos{
         width: 35%!important;
    }
</style>

<script type="text/javascript">
    $(function(){

    const perfil="<?php echo $this->session->userdata('perfil'); ?>";
    const base = "<?php echo base_url() ?>";

    $(document).off('keydown', '.numbersOnly').on('keydown', '.numbersOnly',function(e) {
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
          (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
          (e.keyCode >= 35 && e.keyCode <= 40)) {
               return;
      }
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
          e.preventDefault();
      }
    });

    /*****DATATABLE*****/   
  
    var tb_responsables_fallos = $('#tb_responsables_fallos').DataTable({
       "iDisplayLength":100, 
       "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
       "bPaginate": true,
       "aaSorting" : [6 , "desc"],
       "scrollY": "60vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" :true,
       columnDefs: [
          { orderable: false, targets: 0 }
       ],
       "ajax": {
          "url":"<?php echo base_url();?>listaResponsablesFallosHerramientas",
          "dataSrc": function (json) {
            $(".btn_filtro_responsable_fallos").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_responsable_fallos").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            param.proyecto = $("#proyecto_fallos_f").val();
          }
        },    
       "columns": [
          {
           "class":"centered center margen-td","data": function(row,type,val,meta){
              btn='<center><a data-toggle="modal" href="#modal_responsable_fallos" data-hash_responsable_fallos="'+row.hash_responsable_fallos+'" data-placement="top" data-toggle="tooltip" title="Modificar" class="fa fa-edit btn_modificar_responsable_fallos"></a>';
              // btn+='<a href="#" data-placement="top" data-toggle="tooltip" title="Eliminar" class="fa fa-trash borrar_responsable_fallos" data-hash_responsable_fallos="'+row.hash_responsable_fallos+'"></a></center>';
              return btn;
            }
          },
          { "data": "tipo" ,"class":"margen-td centered"},
          { "data": "descripcion" ,"class":"margen-td centered"},
          { "data": "responsable" ,"class":"margen-td centered"},
          { "data": "plazo" ,"class":"margen-td centered"},
          { "data": "proyecto" ,"class":"margen-td centered"},
          { "data": "ultima_actualizacion" ,"class":"margen-td centered"},
        ]
      }); 

      $(document).on('keyup paste', '#buscador_herr', function() {
        tb_responsables_fallos.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

	  setTimeout( function () {
	  $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
  	  }, 500 );

      $(document).off('click', '.btn_filtro_responsable_fallos').on('click', '.btn_filtro_responsable_fallos',function(event) {
         event.preventDefault();
         $(this).prop("disabled" , true);
         $(".btn_filtro_responsable_fallos").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
         tb_responsables_fallos.ajax.reload();
	  });


  /*********INGRESO************/

    $(document).off('click', '.btn_nuevo_responsable_fallos').on('click', '.btn_nuevo_responsable_fallos',function(event) {
        $('#modal_responsable_fallos').modal('toggle'); 
        $(".btn_guardar_responsable_fallos").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_guardar_responsable_fallos").attr("disabled", false);
        $(".cierra_modal_responsable_fallos").attr("disabled", false);
        $('#formResponsablesFallos')[0].reset();
        $("#hash_responsable_fallos").val("");
        $("#formResponsablesFallos input,#formResponsablesFallos select,#formResponsablesFallos button,#formResponsablesFallos").prop("disabled", false);
    });     


    $(document).off('submit', '#formResponsablesFallos').on('submit', '#formResponsablesFallos',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formResponsablesFallos");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formResponsablesFallos').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_responsable_fallos").attr("disabled", true);
              $(".cierra_modal_responsable_fallos").attr("disabled", true);
              $("#formResponsablesFallos input,#formResponsablesFallos select,#formResponsablesFallos button,#formResponsablesFallos").prop("disabled", true);
            },
            success: function (data) {
             if(data.res == "error"){

                $(".btn_guardar_responsable_fallos").attr("disabled", false);
                $(".cierra_modal_responsable_fallos").attr("disabled", false);

                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $("#formResponsablesFallos input,#formResponsablesFallos select,#formResponsablesFallos button,#formResponsablesFallos").prop("disabled", false);

              }else if(data.res == "ok"){
                  $(".btn_guardar_responsable_fallos").attr("disabled", false);
                  $(".cierra_modal_responsable_fallos").attr("disabled", false);

                  $.notify("Datos ingresados correctamente.", {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                
                  $('#modal_responsable_fallos').modal("toggle");
                  tb_responsables_fallos.ajax.reload();
            }

            $(".btn_guardar_responsable_fallos").attr("disabled", false);
            $(".cierra_modal_responsable_fallos").attr("disabled", false);
            $("#formResponsablesFallos input,#formResponsablesFallos select,#formResponsablesFallos button,#formResponsablesFallos").prop("disabled", false);
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
                    $('#modal_responsable_fallos').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_responsable_fallos').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

   $(document).off('click', '.btn_modificar_responsable_fallos').on('click', '.btn_modificar_responsable_fallos',function(event) {
      $("#hash_responsable_fallos").val("");
      hash_responsable_fallos = $(this).attr("data-hash_responsable_fallos");
      $("#hash_responsable_fallos").val(hash_responsable_fallos);
      $('#formResponsablesFallos')[0].reset();

      $.ajax({
        url: "getDataResponsableFallosHerramientas"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash_responsable_fallos : hash_responsable_fallos},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardar_responsable_fallos").attr("disabled", true);
          $(".cierra_modal_responsable_fallos").attr("disabled", true);
          $("#formResponsablesFallos input,#formResponsablesFallos select,#formResponsablesFallos button,#formResponsablesFallos").prop("disabled", true);
        },

        success: function (data) {
          $(".btn_guardar_responsable_fallos").attr("disabled", false);
          $(".cierra_modal_responsable_fallos").attr("disabled", false);
          $("#formResponsablesFallos input,#formResponsablesFallos select,#formResponsablesFallos button,#formResponsablesFallos").prop("disabled", false);
        
          if(data.res=="ok"){
            for(dato in data.datos){
              $("#item_fallos  option[value='"+data.datos[dato].id_item+"'").prop("selected", true);
              $("#proyecto_fallos  option[value='"+data.datos[dato].id_proyecto+"'").prop("selected", true);
              $("#responsable_fallos  option[value='"+data.datos[dato].id_responsable+"'").prop("selected", true);
              $("#plazo_fallos").val(data.datos[dato].plazo);
            } 
          }

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
                  $('#modal_responsable_fallos').modal("toggle");
              }    
              return;
          }

          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
              $('#modal_responsable_fallos').modal("toggle");
          }
        },timeout:25000
      }); 
    });


    $(document).off('click', '.borrar_responsable_fallos').on('click', '.borrar_responsable_fallos',function(event) {
        var hash_responsable_fallos=$(this).attr("data-hash_responsable_fallos");
          if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaResponsableFallosHerramientas'+"?"+$.now(),{"hash_responsable_fallos": hash_responsable_fallos}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
               tb_responsables_fallos.ajax.reload();
              }else{
                $.notify(data.msg, {
                  className:'danger',
                  globalPosition: 'top right'
                });
              }
            },"json");
        }
    });


    $(document).off('change', '#proyecto_fallos_f').on('change', '#proyecto_fallos_f', function(event) {
      tb_responsables_fallos.ajax.reload()
    }); 

  })
</script>

<!-- FILTROS -->
  
    <div class="form-row">

        <!-- <div class="col-6 col-lg-2">  
	        <div class="form-group">
	           <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_responsable_fallos btn_xr3">
	           <i class="fa fa-plus-circle"></i>  Crear
	           </button>
	        </div>
        </div> -->

        <!--   <div class="col-lg-2">               
		    <div class="form-group">
		     <div class="input-group mb-3">
		        <select id="tipo_checklist" name="tipo_checklist" class="custom-select custom-select-sm">
		        <option value="" selected>Checklist | Todos</option>
		        <option value="1" selected>Herramientas</option>
		        <option value="2" selected>HFC</option>
		        <option value="3" selected>FTTH</option>
		        </select>
		      </div>
		    </div>
	    </div> -->


        <div class="col-lg-2">               
		    <div class="form-group">
		     <div class="input-group mb-3">
		        <select id="proyecto_fallos_f" name="proyecto_fallos_f" class="custom-select custom-select-sm">
		        <option value="" selected>Proyecto</option>
		            <?php 
		            foreach($proyectos as $p){
		              ?>
		                <option value="<?php echo $p["id"]; ?>"><?php echo $p["proyecto"]; ?></option>
		              <?php
		            }
		          ?>
		        </select>
		      </div>
		    </div>
	    </div>

	    <div class="col-12 col-lg-4">  
	       <div class="form-group">
	        <input type="text" placeholder="Busqueda" id="buscador_herr" class="buscador_herr form-control form-control-sm">
	       </div>
	    </div>

	   <!--  <div class="col-6 col-lg-1">
	        <div class="form-group">
	         <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_responsable_fallos btn_xr3">
	         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
	         </button>
	       </div>
	    </div> -->

      <!-- <div class="col-6 col-lg-1">  
        <div class="form-group">
         <button type="button"  class="btn-block btn btn-sm btn-primary btn_excel btn_xr3">
         <i class="fa fa-save"></i> Exportar Excel
         </button>
        </div>
      </div> -->
      
      </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="tb_responsables_fallos" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <th class="centered" style="width: 50px;">Acciones</th>   
            <th class="centered">Tipo</th>   
            <th class="centered">Descripción</th>   
            <th class="centered">Responsable</th> 
            <th class="centered">Plazo</th>   
            <th class="centered">Proyecto</th>   
            <th class="centered">&Uacute;ltima actualización</th>   
          </tr>
        </thead>
      </table>
    </div>
  </div>


<!--  FORMULARIO-->
  <div id="modal_responsable_fallos" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formResponsablesFallosHerramientas",array("id"=>"formResponsablesFallos","class"=>"formResponsablesFallos"))?>

    <div class="modal-dialog modal_responsable_fallos modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header">
          <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
	          <div class="form-row">
	            <div class="col-9 col-lg-6">
	                <button type="submit" class="btn-block btn btn-sm btn-success btn_guardar_responsable_fallos">
	                 <i class="fa fa-save"></i> Guardar
	                </button>
	            </div>
	            <div class="col-3 col-lg-6">
	              <button class="btn-block btn btn-sm btn-danger cierra_modal_responsable_fallos" data-dismiss="modal" aria-hidden="true">
	             <!--   <i class="fa fa-window-close"></i>  -->Cerrar
	              </button>
	            </div>
	          </div>
	        </div>
        </div>

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <input type="hidden" name="hash_responsable_fallos" id="hash_responsable_fallos">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro responsable </legend>

            <div class="form-row">
              
                <div class="col-lg-12">               
	  			    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Item descripción</label>
	  			    <div class="form-group">
	  			     <div class="input-group mb-3">
	  			        <select id="item_fallos" name="item_fallos" class="custom-select custom-select-sm">
	  			        <option value="" selected>Seleccione...</option>
	  			            <?php 
	  			            foreach($listaItemsHerramientas as $l){
	  			              ?>
	  			                <option value="<?php echo $l["id"]; ?>"><?php echo $l["descripcion"]; ?></option>
	  			              <?php
	  			            }
	  			          ?>
	  			        </select>
	  			      </div>
	  			    </div>
  			    </div>

                <div class="col-lg-12">               
	  			    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Proyecto</label>
	  			    <div class="form-group">
	  			     <div class="input-group mb-3">
	  			        <select id="proyecto_fallos" name="proyecto_fallos" class="custom-select custom-select-sm">
	  			        <option value="" selected>Seleccione...</option>
	  			            <?php 
	  			            foreach($proyectos as $p){
	  			              ?>
	  			                <option value="<?php echo $p["id"]; ?>"><?php echo $p["proyecto"]; ?></option>
	  			              <?php
	  			            }
	  			          ?>
	  			        </select>
	  			      </div>
	  			    </div>
  			    </div>

  			    <div class="col-lg-12">               
	  			    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Responsable</label>
	  			    <div class="form-group">
	  			     <div class="input-group mb-3">
	  			        <select id="responsable_fallos" name="responsable_fallos" class="custom-select custom-select-sm">
	  			        <option value="" selected>Seleccione...</option>
	  			            <?php 
	  			            foreach($responsables as $r){
	  			              ?>
	  			                <option value="<?php echo $r["id"]; ?>"><?php echo $r["responsable"]; ?></option>
	  			              <?php
	  			            }
	  			          ?>
	  			        </select>
	  			      </div>
	  			    </div>
  			    </div>

                <div class="col-lg-12">  
	                <div class="form-group">
	                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Plazo </label>
	                <input placeholder="Plazo"  type="text" size="2" maxlength="2" name="plazo_fallos"  id="plazo_fallos" class="numbersOnly form-control form-control-sm" autocomplete="off" />
	                </div>
                </div>

            </div>
          </fieldset> 
         
        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>



 

