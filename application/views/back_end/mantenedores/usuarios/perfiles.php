<style type="text/css">
   @media (max-width: 768px){
    .modal_perfil{
      width: 95%!important;
    }
  }

  @media (min-width: 768px){
    .modal_perfil{
      width: 35%!important;
    }
  }
</style>

<script type="text/javascript">
  $(function(){

  /*****DATATABLE*****/   
    var listaPerfiles = $('#listaPerfiles').DataTable({
       "aaSorting" : [[1,"asc"]],
       "scrollY": "65vh",
       "responsive" :false,
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>listaPerfiles",
          "dataSrc": function (json) {
            $(".btn_filtro_perfil").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_perfil").prop("disabled" , false);
            return json;
          },       
          data: function(param){
          }
        },    
       "columns": [
	    {
	       "class":"centered center margen-td","data": function(row,type,val,meta){
	          btn='<center><a data-toggle="modal" href="#modal_perfil" data-hash_perfil="'+row.hash_perfil+'" data-placement="top" data-toggle="tooltip" title="Modificar" class="fa fa-edit btn_modificar"></a>';
	          btn+='<a href="#" data-placement="top" data-toggle="tooltip" title="Eliminar" class="fa fa-trash borrar_registro" data-hash_perfil="'+row.hash_perfil+'"></a></center>';
	          return btn;
	        }
	      },
	    { "data": "perfil" ,"class":"margen-td centered"},
         
        ]
      }); 
  

      $(document).on('keyup paste', '#buscador_perfil', function() {
        listaPerfiles.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
      }, 500 );

  /*********INGRESO************/

    $(document).off('click', '.btn_nueva_perfil').on('click', '.btn_nueva_perfil',function(event) {
        $('#modal_perfil').modal('toggle'); 
        $(".btn_guardar_perfil").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_guardar_perfil").attr("disabled", false);
        $(".cierra_modal_perfil").attr("disabled", false);
        $('#formPerfiles')[0].reset();
        $("#hash_perfil").val("");
        $("#formPerfiles input,#formPerfiles select,#formPerfiles button,#formPerfiles").prop("disabled", false);
    });     

    $(document).off('submit', '#formPerfiles').on('submit', '#formPerfiles',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formPerfiles");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formPerfiles').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_perfil").attr("disabled", true);
              $(".cierra_modal_perfil").attr("disabled", true);
              $("#formPerfiles input,#formPerfiles select,#formPerfiles button,#formPerfiles").prop("disabled", true);
            },
            success: function (data) {
	            if(data.res == "error"){
	                $(".btn_guardar_perfil").attr("disabled", false);
	                $(".cierra_modal_perfil").attr("disabled", false);

	                $.notify(data.msg, {
	                  className:'error',
	                  globalPosition: 'top right',
	                  autoHideDelay:5000,
	                });

	                $("#formPerfiles input,#formPerfiles select,#formPerfiles button,#formPerfiles").prop("disabled", false);

	            }else if(data.res == "ok"){
	                  $(".btn_guardar_perfil").attr("disabled", false);
	                  $(".cierra_modal_perfil").attr("disabled", false);

	                  $.notify("Datos ingresados correctamente.", {
	                    className:'success',
	                    globalPosition: 'top right',
	                    autoHideDelay:5000,
	                  });
	                
	                  $('#modal_perfil').modal("toggle");
	                  listaPerfiles.ajax.reload();
	            }

	            $(".btn_guardar_perfil").attr("disabled", false);
	            $(".cierra_modal_perfil").attr("disabled", false);
	            $("#formPerfiles input,#formPerfiles select,#formPerfiles button,#formPerfiles").prop("disabled", false);
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
	                    $('#modal_perfil').modal("toggle");
	                }    
	                return;
	            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_perfil').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

   $(document).off('click', '.btn_modificar').on('click', '.btn_modificar',function(event) {
      $("#hash_perfil").val("");
      hash_perfil = $(this).attr("data-hash_perfil");
      $("#hash_perfil").val(hash_perfil);
        
      $.ajax({
        url: "getDataPerfiles"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash_perfil : hash_perfil},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardar_perfil").attr("disabled", true);
          $(".cierra_modal_perfil").attr("disabled", true);
          $("#formPerfiles input,#formPerfiles select,#formPerfiles button,#formPerfiles").prop("disabled", true);
        },
        success: function (data) {
          $(".btn_guardar_perfil").attr("disabled", false);
          $(".cierra_modal_perfil").attr("disabled", false);
          $("#formPerfiles input,#formPerfiles select,#formPerfiles button,#formPerfiles").prop("disabled", false);
        
          if(data.res=="ok"){
            for(dato in data.datos){
              $("#perfil").val(data.datos[dato].perfil);
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
                  $('#modal_perfil').modal("toggle");
              }    
              return;
          }

          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
              $('#modal_perfil').modal("toggle");
          }
        },timeout:25000
      }); 
    });

   	$(document).off('click', '.borrar_registro').on('click', '.borrar_registro',function(event) {
        var hash_perfil=$(this).attr("data-hash_perfil");
          if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaPerfiles'+"?"+$.now(),{"hash_perfil": hash_perfil}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
               listaPerfiles.ajax.reload();
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
	           <button type="button" class="btn btn-block btn-sm btn-primary btn_nueva_perfil btn_xr3">
	           <i class="fa fa-plus-circle"></i>  Crear 
	           </button>
	        </div>
		</div>

	    <div class="col-6  col-lg-4">  
	       <div class="form-group">
	        <input type="text" placeholder="Busqueda" id="buscador_perfil" class="buscador_perfil form-control form-control-sm">
	       </div>
	    </div>

        <!-- <div class="col-6 col-lg-1">
	        <div class="form-group">
		         <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_perfil btn_xr3">
		         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
		         </button>
	        </div>
        </div> -->

      
      </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="listaPerfiles" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <th class="centered" style="width: 50px;">Acciones</th>    
            <th class="centered">Perfil</th> 
             </tr>
        </thead>
      </table>
    </div>
  </div>


<!--  FORMULARIO-->
  <div id="modal_perfil" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formPerfiles",array("id"=>"formPerfiles","class"=>"formPerfiles"))?>

    <div class="modal-dialog modal_perfil modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-header">
          <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
            <div class="form-row">
              <div class="col-9 col-lg-6">
                  <button type="submit" class="btn-block btn btn-sm btn-primary btn_guardar_perfil">
                   <i class="fa fa-save"></i> Guardar
                  </button>
              </div>
              <div class="col-3 col-lg-6">
                <button class="btn-block btn btn-sm btn-secondary cierra_modal_perfil" data-dismiss="modal" aria-hidden="true">
                 <i class="fa fa-window-close"></i> Cerrar
                </button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <input type="hidden" name="hash_perfil" id="hash_perfil">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro Perfil </legend>

            <div class="form-row">
              
              <div class="col-lg-12">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Perfil </label>
                <input placeholder="Perfil"  type="text" name="perfil"  id="perfil" class="form-control form-control-sm" autocomplete="off" />
                </div>
              </div>

            </div>
          </fieldset> 
        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>


