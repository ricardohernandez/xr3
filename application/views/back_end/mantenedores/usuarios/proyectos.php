<style type="text/css">
  @media (max-width: 768px){
    .modal_proyecto{
      width: 95%!important;
    }
  }

  @media (min-width: 768px){
    .modal_proyecto{
      width: 35%!important;
    }
  }

</style>

<script type="text/javascript">
  $(function(){

  /*****DATATABLE*****/   
    var listaProyectos = $('#listaProyectos').DataTable({
       "aaSorting" : [[1,"asc"]],
       "responsive" :false,
       "scrollY": "65vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>listaProyectos",
          "dataSrc": function (json) {
            $(".btn_filtro_proyecto").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_proyecto").prop("disabled" , false);
            return json;
          },       
          data: function(param){
          }
        },    
       "columns": [
	    {
	       "class":"centered center margen-td","data": function(row,type,val,meta){
	          btn='<center><a data-toggle="modal" href="#modal_proyecto" data-hash_proyecto="'+row.hash_proyecto+'" data-placement="top" data-toggle="tooltip" title="Modificar" class="fa fa-edit btn_modificar"></a>';
	          btn+='<a href="#" data-placement="top" data-toggle="tooltip" title="Eliminar" class="fa fa-trash borrar_registro" data-hash_proyecto="'+row.hash_proyecto+'"></a></center>';
	          return btn;
	        }
	      },
	    { "data": "proyecto" ,"class":"margen-td centered"},
         
        ]
      }); 
  

      $(document).on('keyup paste', '#buscador_proyecto', function() {
        listaProyectos.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
      }, 500 );

  /*********INGRESO************/

    $(document).off('click', '.btn_nuevo_proyecto').on('click', '.btn_nuevo_proyecto',function(event) {
        $('#modal_proyecto').modal('toggle'); 
        $(".btn_guardar_proyecto").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_guardar_proyecto").attr("disabled", false);
        $(".cierra_modal_proyecto").attr("disabled", false);
        $('#formProyectos')[0].reset();
        $("#hash_proyecto").val("");
        $("#formProyectos input,#formProyectos select,#formProyectos button,#formProyectos").prop("disabled", false);
    });     

    $(document).off('submit', '#formProyectos').on('submit', '#formProyectos',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formProyectos");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formProyectos').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_proyecto").attr("disabled", true);
              $(".cierra_modal_proyecto").attr("disabled", true);
              $("#formProyectos input,#formProyectos select,#formProyectos button,#formProyectos").prop("disabled", true);
            },
            success: function (data) {
	            if(data.res == "error"){
	                $(".btn_guardar_proyecto").attr("disabled", false);
	                $(".cierra_modal_proyecto").attr("disabled", false);

	                $.notify(data.msg, {
	                  className:'error',
	                  globalPosition: 'top right',
	                  autoHideDelay:5000,
	                });

	                $("#formProyectos input,#formProyectos select,#formProyectos button,#formProyectos").prop("disabled", false);

	            }else if(data.res == "ok"){
	                  $(".btn_guardar_proyecto").attr("disabled", false);
	                  $(".cierra_modal_proyecto").attr("disabled", false);

	                  $.notify("Datos ingresados correctamente.", {
	                    className:'success',
	                    globalPosition: 'top right',
	                    autoHideDelay:5000,
	                  });
	                
	                  $('#modal_proyecto').modal("toggle");
	                  listaProyectos.ajax.reload();
	            }

	            $(".btn_guardar_proyecto").attr("disabled", false);
	            $(".cierra_modal_proyecto").attr("disabled", false);
	            $("#formProyectos input,#formProyectos select,#formProyectos button,#formProyectos").prop("disabled", false);
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
	                    $('#modal_proyecto').modal("toggle");
	                }    
	                return;
	            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_proyecto').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

   $(document).off('click', '.btn_modificar').on('click', '.btn_modificar',function(event) {
      $("#hash_proyecto").val("");
      hash_proyecto = $(this).attr("data-hash_proyecto");
      $("#hash_proyecto").val(hash_proyecto);
        
      $.ajax({
        url: "getDataProyectos"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash_proyecto : hash_proyecto},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardar_proyecto").attr("disabled", true);
          $(".cierra_modal_proyecto").attr("disabled", true);
          $("#formProyectos input,#formProyectos select,#formProyectos button,#formProyectos").prop("disabled", true);
        },
        success: function (data) {
          $(".btn_guardar_proyecto").attr("disabled", false);
          $(".cierra_modal_proyecto").attr("disabled", false);
          $("#formProyectos input,#formProyectos select,#formProyectos button,#formProyectos").prop("disabled", false);
        
          if(data.res=="ok"){
            for(dato in data.datos){
              $("#proyecto").val(data.datos[dato].proyecto);
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
                  $('#modal_proyecto').modal("toggle");
              }    
              return;
          }

          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
              $('#modal_proyecto').modal("toggle");
          }
        },timeout:25000
      }); 
    });

   	$(document).off('click', '.borrar_registro').on('click', '.borrar_registro',function(event) {
        var hash_proyecto=$(this).attr("data-hash_proyecto");
          if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaProyectos'+"?"+$.now(),{"hash_proyecto": hash_proyecto}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
               listaProyectos.ajax.reload();
              }else{
                $.notify(data.msg, {
                  className:'danger',
                  globalPosition: 'top right'
                });
              }
            },"json");
        }
    });



  })
</script>

<!-- FILTROS -->
  
    <div class="form-row">

        <div class="col-6 col-lg-1">  
	        <div class="form-group">
	           <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_proyecto btn_xr3">
	           <i class="fa fa-plus-circle"></i>  Crear 
	           </button>
	        </div>
		</div>

	    <div class="col-6  col-lg-4">  
	       <div class="form-group">
	        <input type="text" placeholder="Busqueda" id="buscador_proyecto" class="buscador_proyecto form-control form-control-sm">
	       </div>
	    </div>

        <!-- <div class="col-6 col-lg-1">
	        <div class="form-group">
		         <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_proyecto btn_xr3">
		         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
		         </button>
	        </div>
        </div> -->

      
      </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="listaProyectos" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <th class="centered" style="width: 50px;">Acciones</th>    
            <th class="centered">Proyecto</th> 
             </tr>
        </thead>
      </table>
    </div>
  </div>


<!--  FORMULARIO-->
  <div id="modal_proyecto" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formProyectos",array("id"=>"formProyectos","class"=>"formProyectos"))?>

    <div class="modal-dialog modal_proyecto modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header">
          <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
            <div class="form-row">
              <div class="col-9 col-lg-6">
                  <button type="submit" class="btn-block btn btn-sm btn-primary btn_guardar_proyecto">
                   <i class="fa fa-save"></i> Guardar
                  </button>
              </div>
              <div class="col-3 col-lg-6">
                <button class="btn-block btn btn-sm btn-secondary cierra_modal_proyecto" data-dismiss="modal" aria-hidden="true">
                 <i class="fa fa-window-close"></i> Cerrar
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <input type="hidden" name="hash_proyecto" id="hash_proyecto">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro Proyecto </legend>

            <div class="form-row">
              
              <div class="col-lg-12">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Proyecto </label>
                <input placeholder="Proyecto"  type="text" name="proyecto"  id="proyecto" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

            </div>
          </fieldset> 
        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>



 

