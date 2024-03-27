<style type="text/css">

  .file_cs{
    display: none;
  }
  .centered {
    text-align: left!important ; /* Cambia 'justify' por 'left' o 'right' según tu preferencia */
  }
  .margen-td {
  text-align: left!important ; /* Cambia 'justify' por 'left' o 'right' según lo que necesites */
  }
  .select2-selection__clear {
    color: grey!important;
  }

</style>

<script type="text/javascript">
  $(function(){

    var desde="<?php echo $desde; ?>";
    var hasta="<?php echo $hasta; ?>";
    $("#desde_t").val(desde);
    $("#hasta_t").val(hasta);

      $(document).off('click', '.btn_nueva_liquidacion').on('click', '.btn_nueva_liquidacion', function(event) {
        $('#formMantenedorFlota')[0].reset();
        $(".form_detalle_asistentes").html("");
        $("#hash_liqui").val("");
        $('#modal_flota').modal('toggle'); 
        $("#formMantenedorFlota input,#formMantenedorFlota select,#formMantenedorFlota button,#formMantenedorFlota").prop("disabled", false);
        $(".btn_ingreso").attr("disabled", false);
        $(".cierra_modal").attr("disabled", false);
       
    });

    $(document).off('submit', '#formMantenedorFlota').on('submit', '#formMantenedorFlota',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formMantenedorFlota");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formMantenedorFlota').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_ingreso").attr("disabled", true);
              $(".cierra_modal").attr("disabled", true);
              $("#formMantenedorFlota input,#formMantenedorFlota select,#formMantenedorFlota button,#formMantenedorFlota").prop("disabled", true);
            },
            success: function (data) {

              if(data.res == "sess"){
                window.location="unlogin";

              }else if(data.res=="ok"){

                $('#modal_flota').modal('toggle'); 
                $("#formMantenedorFlota input,#formMantenedorFlota select,#formMantenedorFlota button,#formMantenedorFlota").prop("disabled", false);
                $(".btn_ingreso").attr("disabled", false);
                $(".cierra_modal").attr("disabled", false);

                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $('#formMantenedorFlota')[0].reset();
                lista_flota.ajax.reload();

              }else if(data.res=="error"){
                    
                $(".btn_ingreso").attr("disabled", false);
                $(".cierra_modal").attr("disabled", false);
                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });
                $("#formMantenedorFlota input,#formMantenedorFlota select,#formMantenedorFlota button,#formMantenedorFlota").prop("disabled", false);

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
                    /*  $('#modal_flota').modal("toggle"); */
                }    
                return;
              }
              if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                /* $('#modal_flota').modal("toggle"); */
              }
          },timeout:35000
        }); 
      return false; 
    })

      $(document).off('change', '.file_cs').on('change', '.file_cs',function(event) {
        var myFormData = new FormData();
        myFormData.append('userfile', $('#userfile').prop('files')[0]);
        $.ajax({
            url: "formCargaMasivaFlota"+"?"+$.now(),  
            type: 'POST',
            data: myFormData,
            cache: false,
            tryCount : 0,
            retryLimit : 3,
            processData: false,
            contentType : false,
            dataType:"json",
            beforeSend:function(){
              $(".btn_file_cs").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...').prop("disabled",true);
            },  
            success: function (data) {
               $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base ').prop("disabled",false);
                if(data.res=="ok"){
                  $.notify(data.msg, {
                      className:data.tipo,
                      globalPosition: 'top center',
                      autoHideDelay: 20000,
                  });
                  
                }else{
                  $.notify(data.msg, {
                      className:data.tipo,
                      globalPosition: 'top center',
                      autoHideDelay: 10000,
                  });
                }

                $("#userfile").val(null);

            },
            error : function(xhr, textStatus, errorThrown ) {
              $("#userfile").val(null);
              if (textStatus == 'timeout') {
                  this.tryCount++;
                  if (this.tryCount <= this.retryLimit) {
                      $.notify("Reintentando...", {
                        className:'info',
                        globalPosition: 'top center'
                      });
                      $.ajax(this);
                      $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base productividad').prop("disabled",false);
                      return;
                  } else{
                     $.notify("Problemas cargando el archivo, intente nuevamente.", {
                        className:'warn',
                        globalPosition: 'top center',
                        autoHideDelay: 10000,
                      });
                  }    
                  return;
              }

              if (xhr.status == 500) {
                 $.notify("Problemas cargando el archivo, intente nuevamente.", {
                    className:'warn',
                    globalPosition: 'top center',
                    autoHideDelay: 10000,
                 });
              $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base').prop("disabled",false);
              }
          },timeout:120000
        });
      })
      
      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      /******* LISTA FLOTA ********/

        var lista_flota = $('#lista_flota').DataTable({
        "responsive":false,
        "aaSorting" : [[1,"desc"]],
        "scrollY": "300px",
        "scrollX": true,
        "sAjaxDataProp": "result",        
        "bDeferRender": true,
        "bInfo" : false,
        "select" : true,
        "autoWidth": true,
        // "columnDefs": [{ orderable: false, targets: 0 }  ],
        "ajax": {
            "url":"<?php echo base_url();?>listaMantenedorFlota",    
            "dataSrc": function (json) {
              return json;
            },     
            data: function(param){
            },
          },    
        "columns": [
            {
              "class":"centered margen-td","width" : "50px","data": function(row,type,val,meta){
                btn = "<center>";
                  btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar rojo"><i class="fa fa-trash"></i></a></center>';
                return btn;

              }
            },
            { "data": "id" ,"class":"margen-td centered","width" : "100px"},
            { "data": "patente" ,"class":"margen-td centered","width" : "100px"},
          ],
        });
    
        setTimeout( function () {
          var lista_flota = $.fn.dataTable.fnTables(true);
          if (lista_flota.length > 0 ) {
              $(lista_flota).dataTable().fnAdjustColumnSizing();
        }}, 200 ); 

        setTimeout( function () {
          var lista_flota = $.fn.dataTable.fnTables(true);
          if (lista_flota.length > 0 ) {
              $(lista_flota).dataTable().fnAdjustColumnSizing();
        }}, 2000 ); 

        setTimeout( function () {
          var lista_flota = $.fn.dataTable.fnTables(true);
          if (lista_flota.length > 0 ) {
              $( lista_flota).dataTable().fnAdjustColumnSizing();
          }
        }, 4000 ); 

      $(document).on('keyup paste', '#buscador', function() {
        lista_flota.search($(this).val().trim()).draw();
      });

          
    $(document).off('click', '.btn_eliminar').on('click', '.btn_eliminar',function(event) {
      hash=$(this).data("hash");
      if(confirm("¿Esta seguro que desea eliminar este registro?")){
        $.post('<?php echo base_url();?>eliminaMantenedorFlota'+"?"+$.now(),{"hash": hash}, function(data) {

          if(data.res=="ok"){
            $.notify(data.msg, {
              className:'success',
              globalPosition: 'top right'
            })
            lista_flota.ajax.reload();

          }else{
            $.notify(data.msg, {
              className:'danger',
              globalPosition: 'top right'
            })
          }
        },"json")
      }
    })

  })  
</script>

<div class="content" style="padding: 0px 10px;">

  <div class="form-row">

    <div class="col-1 col-lg-1"> 
      <div class="form-group">
        <button type="button" class="btn-block btn btn-sm btn-outline-primary btn_nueva_liquidacion btn_xr3">
        <i class="fa fa-plus-circle"></i>  Nuevo 
        </button>
      </div>
    </div>

    <div class="col-6 col-lg-2">  
       <div class="form-group">
        <input type="text" placeholder="Busqueda" id="buscador" class="buscador form-control form-control-sm">
       </div>
    </div>

  </div>      

  <div class="body">
    <div class="form-row mt-2">

      <div class="col-12">
        <div class="card">
          <div class="col-12">
            <span class="title_section">Tabla de patentes</span>
            <table id="lista_flota" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                <tr>    
                  <th class="centered">Acciones</th> 
                  <th class="centered">Id</th> 
                  <th class="centered">Patente</th> 
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>

      
    </div>
  </div>

<!--  NUEVO -->

<div id="modal_flota"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
    <div class="modal-dialog modal_flota">
      <div class="modal-content">
        <?php echo form_open_multipart("formMantenedorFlota",array("id"=>"formMantenedorFlota","class"=>"formMantenedorFlota"))?>
          <input type="hidden" name="hash_liqui" id="hash_liqui">

          <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>

          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Datos de vehículo</legend>
          <div class="form-row">
              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Patente</label>         
                  <input type="text" id="patente" name="patente" class="custom-select custom-select-sm" style="width:100%!important;"></input>
                </div>
              </div>
            </div>
          </fieldset><br>

          <div class="col-lg-12">
            <div class="form-row">

              <div class="col-6 col-lg-6">
                <div class="form-group">
                  <button type="submit" class="btn-block btn btn-sm btn-primary btn_ingreso">
                    <i class="fa fa-save"></i> Guardar
                  </button>
                </div>
              </div>

              <div class="col-6 col-lg-6">
                <button class="btn-block btn btn-sm btn-dark cierra_modal" data-dismiss="modal" aria-hidden="true">
                  <i class="fa fa-window-close"></i> Cerrar
                </button>
              </div>
            </div>
          </div>

          </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>



  