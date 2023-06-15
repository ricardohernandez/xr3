<style type="text/css">

  @media (max-width: 768px){
    .modal_plaza{
      width: 95%!important;
    }
  }

  @media (min-width: 768px){
    .modal_plaza{
      width: 35%!important;
    }
  }

</style>

<script type="text/javascript">
  $(function(){

  /*****DATATABLE*****/   
    var listaPlazas = $('#listaPlazas').DataTable({
       "aaSorting" : [[1,"asc"]],
       "scrollY": "65vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "responsive" :false,
       "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>listaPlazas",
          "dataSrc": function (json) {
            $(".btn_filtro_plaza").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_plaza").prop("disabled" , false);
            return json;
          },       
          data: function(param){
          }
        },    
       "columns": [
          {
            "class":"centered center margen-td","data": function(row,type,val,meta){
                btn='<center><a data-toggle="modal" href="#modal_plaza" data-hash_plaza="'+row.hash_plaza+'" data-placement="top" data-toggle="tooltip" title="Modificar" class="fa fa-edit btn_modificar"></a>';
                btn+='<a href="#" data-placement="top" data-toggle="tooltip" title="Eliminar" class="fa fa-trash borrar_registro" data-hash_plaza="'+row.hash_plaza+'"></a></center>';
                return btn;
              }
            },
          { "data": "plaza" ,"class":"margen-td centered"},
        ]
      }); 
  

      $(document).on('keyup paste', '#buscador_plaza', function() {
        listaPlazas.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
      }, 500 );

  /*********INGRESO************/

    $(document).off('click', '.btn_plaza').on('click', '.btn_plaza',function(event) {
        $('#modal_plaza').modal('toggle'); 
        $(".btn_nueva_plaza").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_nueva_plaza").attr("disabled", false);
        $(".cierra_modal_plaza").attr("disabled", false);
        $('#formPlazas')[0].reset();
        $("#hash_plaza").val("");
        $("#formPlazas input,#formPlazas select,#formPlazas button,#formPlazas").prop("disabled", false);
    });     

    $(document).off('submit', '#formPlazas').on('submit', '#formPlazas',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formPlazas");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formPlazas').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_nueva_plaza").attr("disabled", true);
              $(".cierra_modal_plaza").attr("disabled", true);
              $("#formPlazas input,#formPlazas select,#formPlazas button,#formPlazas").prop("disabled", true);
            },
            success: function (data) {
	            if(data.res == "error"){
	                $(".btn_nueva_plaza").attr("disabled", false);
	                $(".cierra_modal_plaza").attr("disabled", false);

	                $.notify(data.msg, {
	                  className:'error',
	                  globalPosition: 'top right',
	                  autoHideDelay:5000,
	                });

	                $("#formPlazas input,#formPlazas select,#formPlazas button,#formPlazas").prop("disabled", false);

	            }else if(data.res == "ok"){
	                  $(".btn_nueva_plaza").attr("disabled", false);
	                  $(".cierra_modal_plaza").attr("disabled", false);

	                  $.notify("Datos ingresados correctamente.", {
	                    className:'success',
	                    globalPosition: 'top right',
	                    autoHideDelay:5000,
	                  });
	                
	                  $('#modal_plaza').modal("toggle");
	                  listaPlazas.ajax.reload();
	            }

	            $(".btn_nueva_plaza").attr("disabled", false);
	            $(".cierra_modal_plaza").attr("disabled", false);
	            $("#formPlazas input,#formPlazas select,#formPlazas button,#formPlazas").prop("disabled", false);
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
	                    $('#modal_plaza').modal("toggle");
	                }    
	                return;
	            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_plaza').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

   $(document).off('click', '.btn_modificar').on('click', '.btn_modificar',function(event) {
      $("#hash_plaza").val("");
      hash_plaza = $(this).attr("data-hash_plaza");
      $("#hash_plaza").val(hash_plaza);
        
      $.ajax({
        url: "getDataPlazas"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash_plaza : hash_plaza},
        dataType:"json",
        beforeSend:function(){
          $(".btn_nueva_plaza").attr("disabled", true);
          $(".cierra_modal_plaza").attr("disabled", true);
          $("#formPlazas input,#formPlazas select,#formPlazas button,#formPlazas").prop("disabled", true);
        },
        success: function (data) {
          $(".btn_nueva_plaza").attr("disabled", false);
          $(".cierra_modal_plaza").attr("disabled", false);
          $("#formPlazas input,#formPlazas select,#formPlazas button,#formPlazas").prop("disabled", false);
        
          if(data.res=="ok"){
            for(dato in data.datos){
              $("#plaza").val(data.datos[dato].plaza);
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
                  $('#modal_plaza').modal("toggle");
              }    
              return;
          }

          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
              $('#modal_plaza').modal("toggle");
          }
        },timeout:25000
      }); 
    });

   	$(document).off('click', '.borrar_registro').on('click', '.borrar_registro',function(event) {
        var hash_plaza=$(this).attr("data-hash_plaza");
          if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaPlazas'+"?"+$.now(),{"hash_plaza": hash_plaza}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
               listaPlazas.ajax.reload();
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
	           <button type="button" class="btn btn-block btn-sm btn-primary btn_plaza btn_xr3">
	           <i class="fa fa-plus-circle"></i>  Crear 
	           </button>
	        </div>
		</div>

	    <div class="col-6  col-lg-4">  
	       <div class="form-group">
	        <input type="text" placeholder="Busqueda" id="buscador_plaza" class="buscador_plaza form-control form-control-sm">
	       </div>
	    </div>

        <!-- <div class="col-6 col-lg-1">
	        <div class="form-group">
		         <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_plaza btn_xr3">
		         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
		         </button>
	        </div>
        </div> -->

      
      </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="listaPlazas" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <th class="centered" style="width: 50px;">Acciones</th>    
            <th class="centered">Plaza</th> 
             </tr>
        </thead>
      </table>
    </div>
  </div>


<!--  FORMULARIO-->
  <div id="modal_plaza" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formPlazas",array("id"=>"formPlazas","class"=>"formPlazas"))?>

    <div class="modal-dialog modal_plaza modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header">
          <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
            <div class="form-row">
              <div class="col-9 col-lg-6">
                  <button type="submit" class="btn-block btn btn-sm btn-primary btn_nueva_plaza">
                   <i class="fa fa-save"></i> Guardar
                  </button>
              </div>
              <div class="col-3 col-lg-6">
                <button class="btn-block btn btn-sm btn-secondary cierra_modal_plaza" data-dismiss="modal" aria-hidden="true">
                 <i class="fa fa-window-close"></i> Cerrar
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <input type="hidden" name="hash_plaza" id="hash_plaza">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro Plaza </legend>

            <div class="form-row">
              
              <div class="col-lg-12">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Plaza </label>
                <input placeholder="Plaza"  type="text" name="plaza"  id="plaza" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

            </div>
          </fieldset> 
        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>



 

