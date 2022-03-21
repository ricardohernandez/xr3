<style type="text/css">
    .modal_jefe{
      width: 35%!important;
    }

    
  .red{
    background-color: #DC3545;
    color: #fff;
  }
</style>

<script type="text/javascript">
  $(function(){

  /*****DATATABLE*****/   
    var listaJefes = $('#listaJefes').DataTable({
       "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
       "iDisplayLength":-1, 
       "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
       "bPaginate": false,
       "aaSorting" : [[2,"asc"]],
       "scrollY": "60vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>listaJefes",
          "dataSrc": function (json) {
            $(".btn_filtro_jefe").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_jefe").prop("disabled" , false);
            return json;
          },       
          data: function(param){
          }
        },    
       "columns": [
	      {
	       "class":"centered center margen-td","data": function(row,type,val,meta){
	          //btn='<center><a data-toggle="modal" href="#modal_jefe" data-hash_jefe="'+row.hash_jefe+'" data-placement="top" data-toggle="tooltip" title="Modificar" class="fa fa-edit btn_modificar"></a>';
	          //btn='<a href="#" data-placement="top" data-toggle="tooltip" title="Eliminar" class="fa fa-trash borrar_registro" data-hash_jefe="'+row.hash_jefe+'"></a></center>';
	          return "";
	        }
	      },
        { "data": "id_jefe" ,"class":"margen-td centered"},
        { "data": "nombre_jefe" ,"class":"margen-td centered"},
         
        ]
      }); 
  

      $(document).on('keyup paste', '#buscador_jefe', function() {
        listaJefes.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
      }, 500 );

  /*********INGRESO************/

    $(document).off('click', '.btn_nueva_jefe').on('click', '.btn_nueva_jefe',function(event) {
        $('#modal_jefe').modal('toggle'); 
        $(".btn_guardar_jefe").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_guardar_jefe").attr("disabled", false);
        $(".cierra_modal_jefe").attr("disabled", false);
        $('#formJefes')[0].reset();
        $("#hash_jefe").val("");
        $("#formJefes input,#formJefes select,#formJefes button,#formJefes").prop("disabled", false);
    });     

    $(document).off('submit', '#formJefes').on('submit', '#formJefes',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formJefes");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formJefes').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              // $(".btn_guardar_jefe").attr("disabled", true);
              // $(".cierra_modal_jefe").attr("disabled", true);
              // $("#formJefes input,#formJefes select,#formJefes button,#formJefes").prop("disabled", true);
            },
            success: function (data) {
	            if(data.res == "error"){
	                $(".btn_guardar_jefe").attr("disabled", false);
	                $(".cierra_modal_jefe").attr("disabled", false);

	                $.notify(data.msg, {
	                  className:'error',
	                  globalPosition: 'top right',
	                  autoHideDelay:5000,
	                });

	                $("#formJefes input,#formJefes select,#formJefes button,#formJefes").prop("disabled", false);

	            }else if(data.res == "ok"){
	                  $(".btn_guardar_jefe").attr("disabled", false);
	                  $(".cierra_modal_jefe").attr("disabled", false);

	                  $.notify("Datos ingresados correctamente.", {
	                    className:'success',
	                    globalPosition: 'top right',
	                    autoHideDelay:5000,
	                  });
	                
	                  $('#modal_jefe').modal("toggle");
	                  listaJefes.ajax.reload();
	            }

	            $(".btn_guardar_jefe").attr("disabled", false);
	            $(".cierra_modal_jefe").attr("disabled", false);
	            $("#formJefes input,#formJefes select,#formJefes button,#formJefes").prop("disabled", false);
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
	                    $('#modal_jefe').modal("toggle");
	                }    
	                return;
	            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_jefe').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

   $(document).off('click', '.btn_modificar').on('click', '.btn_modificar',function(event) {
      $("#hash_jefe").val("");
      hash_jefe = $(this).attr("data-hash_jefe");
      $("#hash_jefe").val(hash_jefe);
        
      $.ajax({
        url: "getDataJefe"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash_jefe : hash_jefe},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardar_jefe").attr("disabled", true);
          $(".cierra_modal_jefe").attr("disabled", true);
          $("#formJefes input,#formJefes select,#formJefes button,#formJefes").prop("disabled", true);
        },
        success: function (data) {
          $(".btn_guardar_jefe").attr("disabled", false);
          $(".cierra_modal_jefe").attr("disabled", false);
          $("#formJefes input,#formJefes select,#formJefes button,#formJefes").prop("disabled", false);
        
          if(data.res=="ok"){
            for(dato in data.datos){
              $("#jefe  option[value='"+data.datos[dato].jefe+"'").prop("selected", true);
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
                  $('#modal_jefe').modal("toggle");
              }    
              return;
          }

          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
              $('#modal_jefe').modal("toggle");
          }
        },timeout:25000
      }); 
    });

   	$(document).off('click', '.borrar_registro').on('click', '.borrar_registro',function(event) {
        var hash_jefe=$(this).attr("data-hash_jefe");
          if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaJefes'+"?"+$.now(),{"hash_jefe": hash_jefe}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
               listaJefes.ajax.reload();
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

        <div class="col-lg-2">  
	        <div class="form-group">
	           <button type="button" class="btn btn-block btn-sm btn-primary btn_nueva_jefe btn_xr3">
	           <i class="fa fa-plus-circle"></i>  Crear 
	           </button>
	        </div>
		</div>

	    <div class="col-12 col-lg-4">  
	       <div class="form-group">
	        <input type="text" placeholder="Busqueda" id="buscador_jefe" class="buscador_jefe form-control form-control-sm">
	       </div>
	    </div>

        <!-- <div class="col-6 col-lg-1">
	        <div class="form-group">
		         <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_jefe btn_xr3">
		         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
		         </button>
	        </div>
        </div> -->

      
      </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="listaJefes" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <th class="centered" style="width: 50px;"></th>    
            <th class="centered">ID</th> 
            <th class="centered">Jefe</th> 
             </tr>
        </thead>
      </table>
    </div>
  </div>


<!--  FORMULARIO-->
  <div id="modal_jefe" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formJefes",array("id"=>"formJefes","class"=>"formJefes"))?>

    <div class="modal-dialog modal_jefe modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header">
          <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
            <div class="form-row">
              <div class="col-9 col-lg-6">
                  <button type="submit" class="btn-block btn btn-sm btn-success btn_guardar_jefe">
                   <i class="fa fa-save"></i> Guardar
                  </button>
              </div>
              <div class="col-3 col-lg-6">
                <button class="btn-block btn btn-sm btn-danger cierra_modal_jefe" data-dismiss="modal" aria-hidden="true">
                 <i class="fa fa-window-close"></i> Cerrar
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <input type="hidden" name="hash_jefe" id="hash_jefe">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro Jefe </legend>

            <div class="form-row">
              
               <div class="col-lg-12">               
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Jefe </label>
                  <select id="jefe" name="jefe" class="custom-select custom-select-sm">
                  <option value="" selected>Seleccione </option>
                      <?php 
                      foreach($usuarios as $u){
                        ?>
                          <option value="<?php echo $u["id"]; ?>"><?php echo $u["nombre"]; ?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>

            </div>
          </fieldset> 
        </div>

      </div>
    </div>
    <?php echo form_close(); ?>
  </div>


