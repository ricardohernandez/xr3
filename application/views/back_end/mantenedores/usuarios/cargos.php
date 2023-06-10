<style type="text/css">

  @media (max-width: 768px){
    .modal_cargo{
      width: 95%!important;
    }
  }

  @media (min-width: 768px){
    .modal_cargo{
      width: 35%!important;
    }
  }

</style>

<script type="text/javascript">
  $(function(){

  /*****DATATABLE*****/   
    var listaCargos = $('#listaCargos').DataTable({
       "aaSorting" : [[1,"asc"]],
       "scrollY": "65vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "responsive" :false,
       "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>listaCargos",
          "dataSrc": function (json) {
            $(".btn_filtro_cargos").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_cargos").prop("disabled" , false);
            return json;
          },       
          data: function(param){
          }
        },    
       "columns": [
          {
           "class":"centered center margen-td","data": function(row,type,val,meta){
              btn='<center><a data-toggle="modal" href="#modal_cargo" data-hash_cargo="'+row.hash_cargo+'" data-placement="top" data-toggle="tooltip" title="Modificar" class="fa fa-edit btn_modificar"></a>';
              btn+='<a href="#" data-placement="top" data-toggle="tooltip" title="Eliminar" class="fa fa-trash borrar_registro" data-hash_cargo="'+row.hash_cargo+'"></a></center>';
              return btn;
            }
          },
          { "data": "cargo" ,"class":"margen-td centered"},
         
        ]
      }); 
  

      $(document).on('keyup paste', '#buscador_cargo', function() {
        listaCargos.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
      }, 500 );

  /*********INGRESO************/

    $(document).off('click', '.btn_nuevo_cargo').on('click', '.btn_nuevo_cargo',function(event) {
        $('#modal_cargo').modal('toggle'); 
        $(".btn_guardar_cargo").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_guardar_cargo").attr("disabled", false);
        $(".cierra_modal_cargo").attr("disabled", false);
        $('#formCargos')[0].reset();
        $("#hash_cargo").val("");
        $("#formCargos input,#formCargos select,#formCargos button,#formCargos").prop("disabled", false);
    });     

    $(document).off('submit', '#formCargos').on('submit', '#formCargos',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formCargos");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formCargos').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_cargo").attr("disabled", true);
              $(".cierra_modal_cargo").attr("disabled", true);
              $("#formCargos input,#formCargos select,#formCargos button,#formCargos").prop("disabled", true);
            },
            success: function (data) {
	            if(data.res == "error"){
	                $(".btn_guardar_cargo").attr("disabled", false);
	                $(".cierra_modal_cargo").attr("disabled", false);

	                $.notify(data.msg, {
	                  className:'error',
	                  globalPosition: 'top right',
	                  autoHideDelay:5000,
	                });

	                $("#formCargos input,#formCargos select,#formCargos button,#formCargos").prop("disabled", false);

	            }else if(data.res == "ok"){
	                  $(".btn_guardar_cargo").attr("disabled", false);
	                  $(".cierra_modal_cargo").attr("disabled", false);

	                  $.notify("Datos ingresados correctamente.", {
	                    className:'success',
	                    globalPosition: 'top right',
	                    autoHideDelay:5000,
	                  });
	                
	                  $('#modal_cargo').modal("toggle");
	                  listaCargos.ajax.reload();
	            }

	            $(".btn_guardar_cargo").attr("disabled", false);
	            $(".cierra_modal_cargo").attr("disabled", false);
	            $("#formCargos input,#formCargos select,#formCargos button,#formCargos").prop("disabled", false);
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
	                    $('#modal_cargo').modal("toggle");
	                }    
	                return;
	            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_cargo').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

  /**********EDIT CARGO******************/

   $(document).off('click', '.btn_modificar').on('click', '.btn_modificar',function(event) {
      $("#hash_cargo").val("");
      hash_cargo = $(this).attr("data-hash_cargo");
      $("#hash_cargo").val(hash_cargo);
        
      $.ajax({
        url: "getDataCargos"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash_cargo : hash_cargo},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardar_cargo").attr("disabled", true);
          $(".cierra_modal_cargo").attr("disabled", true);
          $("#formCargos input,#formCargos select,#formCargos button,#formCargos").prop("disabled", true);
        },
        success: function (data) {
          $(".btn_guardar_cargo").attr("disabled", false);
          $(".cierra_modal_cargo").attr("disabled", false);
          $("#formCargos input,#formCargos select,#formCargos button,#formCargos").prop("disabled", false);
        
          if(data.res=="ok"){
            for(dato in data.datos){
              $("#cargo").val(data.datos[dato].cargo);
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
                  $('#modal_cargo').modal("toggle");
              }    
              return;
          }

          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
              $('#modal_cargo').modal("toggle");
          }
        },timeout:25000
      }); 
    });

   	$(document).off('click', '.borrar_registro').on('click', '.borrar_registro',function(event) {
        var hash_cargo=$(this).attr("data-hash_cargo");
          if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaCargo'+"?"+$.now(),{"hash_cargo": hash_cargo}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
               listaCargos.ajax.reload();
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

        <div class="col-6  col-lg-1">  
	        <div class="form-group">
	           <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_cargo btn_xr3">
	           <i class="fa fa-plus-circle"></i>  Crear 
	           </button>
	        </div>
		</div>

	    <div class="col-6  col-lg-4">  
	       <div class="form-group">
	        <input type="text" placeholder="Busqueda" id="buscador_cargo" class="buscador_cargo form-control form-control-sm">
	       </div>
	    </div>

        <!-- <div class="col-6 col-lg-1">
	        <div class="form-group">
		         <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_cargos btn_xr3">
		         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
		         </button>
	        </div>
        </div> -->

      
      </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="listaCargos" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <th class="centered" style="width: 50px;">Acciones</th>    
            <th class="centered">Cargo</th> 
             </tr>
        </thead>
      </table>
    </div>
  </div>


<!--  FORMULARIO-->
  <div id="modal_cargo" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formCargos",array("id"=>"formCargos","class"=>"formCargos"))?>

    <div class="modal-dialog modal_cargo modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header">
          <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
            <div class="form-row">
              <div class="col-9 col-lg-6">
                  <button type="submit" class="btn-block btn btn-sm btn-primary btn_guardar_cargo">
                   <i class="fa fa-save"></i> Guardar
                  </button>
              </div>
              <div class="col-3 col-lg-6">
                <button class="btn-block btn btn-sm btn-secondary cierra_modal_cargo" data-dismiss="modal" aria-hidden="true">
                 <i class="fa fa-window-close"></i> Cerrar
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <input type="hidden" name="hash_cargo" id="hash_cargo">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro Cargos </legend>

            <div class="form-row">
              
              <div class="col-lg-12">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Cargo </label>
                <input placeholder="Cargos"  type="text" name="cargo"  id="cargo" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

            </div>
          </fieldset> 
        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>



 

