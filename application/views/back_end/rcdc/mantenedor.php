<style type="text/css">
  .ejemplo_planilla{
    display: inline;
    cursor: pointer;
    color: #17A2B8;
    margin-top:7px;
  }

  .ver_obs_desp{
    cursor: pointer;
    display: inline;
    margin-left: 5px;
    font-size: 11px;
    color: #2780E3;
  }

  .modal-ejemplo{
    width:60%!important;
  }

  .actualizacion_productividad{
      display: inline-block;
      font-size: 11px;
  }
</style>

<script type="text/javascript">
  $(function(){
    var perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    var user="<?php echo $this->session->userdata('id'); ?>";
    const base = "<?php echo base_url() ?>";

  /**** TRAMO ****/
    /*****DATATABLE*****/   
      var lista_tramos = $('#lista_tramos').DataTable({
        "aaSorting" : [[1,"desc"]],
        "scrollY": "65vh",
        "scrollX": true,
        "sAjaxDataProp": "result",        
        "bDeferRender": true,
        "select" : true,
        "responsive":false,
        // "columnDefs": [{ orderable: false, targets: 0 }  ],
        "ajax": {
            "url":"<?php echo base_url();?>getTramosRcdcList",
            "dataSrc": function (json) {
              $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
              $(".btn_filtro_detalle").prop("disabled" , false);
              return json;
            },       
            data: function(param){
            }
          },    
        "columns": [
            {
              "class":"centered margen-td","width" : "30px","data": function(row,type,val,meta){
                  btn = "";
                  btn  =`<center><a  href="#!"   data-hash="${row.hash}"  title="Estado" class="btn_editar_tramo" style="font-size:14px!important;"><i class="fas fa-edit"></i> </a>`;
                  if(perfil==1){
                    btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar_tramo rojo"><i class="fa fa-trash"></i></a>';
                  }
                  btn+='</center>';
                  return btn;
              }
            },
            { "data": "id" ,"class":"margen-td centered"},
            { "data": "tramo" ,"class":"margen-td centered"},
          ]
        }); 
    

        $(document).on('keyup paste', '#buscador', function() {
          lista_tramos.search($(this).val().trim()).draw();
        });

        $(document).off('click', '.btn_filtro_detalle').on('click', '.btn_filtro_detalle',function(event) {
          event.preventDefault();
          $(this).prop("disabled" , true);
          $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
          lista_tramos.ajax.reload();
        });


        String.prototype.capitalize = function() {
            return this.charAt(0).toUpperCase() + this.slice(1);
        }

        setTimeout( function () {
          var lista_tramos = $.fn.dataTable.fnTables(true);
          if ( lista_tramos.length > 0 ) {
              $(lista_tramos).dataTable().fnAdjustColumnSizing();
        }}, 200 ); 

        setTimeout( function () {
          var lista_tramos = $.fn.dataTable.fnTables(true);
          if ( lista_tramos.length > 0 ) {
              $(lista_tramos).dataTable().fnAdjustColumnSizing();
        }}, 2000 ); 

        setTimeout( function () {
          var lista_tramos = $.fn.dataTable.fnTables(true);
          if ( lista_tramos.length > 0 ) {
              $(lista_tramos).dataTable().fnAdjustColumnSizing();
          }
        }, 4000 ); 


      

    /*********INGRESO************/

      $(document).off('click', '.btn_nuevo_tramo').on('click', '.btn_nuevo_tramo',function(event) {
          $('#modal_tramos').modal('toggle'); 
          $(".btn_guardar_tramo").html('<i class="fa fa-save"></i> Guardar');
          $(".btn_guardar_tramo").attr("disabled", false);
          $(".cierra_modal_tramos").attr("disabled", false);
          $('#formTramosRcdc')[0].reset();
          $("#hash_tramo").val("");
          $("#formTramosRcdc input,#formTramosRcdc select,#formTramosRcdc button,#formTramosRcdc").prop("disabled", false);
      });     

      $(document).off('submit', '#formTramosRcdc').on('submit', '#formTramosRcdc',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formTramosRcdc");
        var formData = new FormData(formElement);
          $.ajax({
              url: $('#formTramosRcdc').attr('action')+"?"+$.now(),  
              type: 'POST',
              data: formData,
              cache: false,
              processData: false,
              dataType: "json",
              contentType : false,
              beforeSend:function(){
                $(".btn_guardar_tramo").attr("disabled", true);
                $(".cierra_modal_tramos").attr("disabled", true);
                $("#formTramosRcdc input,#formTramosRcdc select,#formTramosRcdc button,#formTramosRcdc").prop("disabled", true);
              },
              success: function (data) {
              if(data.res == "error"){

                  $(".btn_guardar_tramo").attr("disabled", false);
                  $(".cierra_modal_tramos").attr("disabled", false);

                  $.notify(data.msg, {
                    className:'error',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $("#formTramosRcdc input,#formTramosRcdc select,#formTramosRcdc button,#formTramosRcdc").prop("disabled", false);

                }else if(data.res == "ok"){
                    $(".btn_guardar_tramo").attr("disabled", false);
                    $(".cierra_modal_tramos").attr("disabled", false);

                    $.notify("Datos ingresados correctamente.", {
                      className:'success',
                      globalPosition: 'top right',
                      autoHideDelay:5000,
                    });
                  
                    $('#modal_tramos').modal("toggle");
                    lista_tramos.ajax.reload();
              }

              $(".btn_guardar_tramo").attr("disabled", false);
              $(".cierra_modal_tramos").attr("disabled", false);
              $("#formTramosRcdc input,#formTramosRcdc select,#formTramosRcdc button,#formTramosRcdc").prop("disabled", false);
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
                      $('#modal_tramos').modal("toggle");
                  }    
                  return;
              }

              if (xhr.status == 500) {
                  $.notify("Problemas en el servidor, intente más tarde.", {
                    className:'warn',
                    globalPosition: 'top right'
                  });
                  $('#modal_tramos').modal("toggle");
              }
            },timeout:25000
          });
        return false; 
      });

      $(document).off('click', '.btn_editar_tramo').on('click', '.btn_editar_tramo',function(event) {
        event.preventDefault();
        $("#hash_tramo").val("")
        hash=$(this).data("hash")
        $('#formTramosRcdc')[0].reset()
        $("#hash_tramo").val(hash)
        $('#modal_tramos').modal('toggle')
        $("#formTramosRcdc input,#formTramosRcdc select,#formTramosRcdc button,#formTramosRcdc").prop("disabled", true)
        $(".btn_guardar_tramo").attr("disabled", true)
        $(".cierra_modal").attr("disabled", true)

        $.ajax({
          url: base+"getDataTramosRcdc"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          data:{hash:hash},
          dataType:"json",
          beforeSend:function(){
            $(".btn_guardar_tramo").prop("disabled",true); 
            $(".cierra_modal").prop("disabled",true); 
          },
          success: function (data) {

            if(data.res=="ok"){

              for(dato in data.datos){
                $("#hash_tramo").val(data.datos[dato].hash);
                $("#tramo").val(data.datos[dato].tramo);
              }
            
              $("#formTramosRcdc input,#formTramosRcdc select,#formTramosRcdc button,#formTramosRcdc").prop("disabled", false);
              $(".cierra_modal").prop("disabled", false);
              $(".btn_guardar_tramo").prop("disabled", false);

            }else if(data.res == "sess"){
              window.location="../";
            }

            $(".btn_guardar_tramo").prop("disabled",false); 
            $(".cierra_modal").prop("disabled",false); 
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
                $('#modal_tramos').modal("toggle");
            }
          } , timeout:35000
        }) 
      })

      $(document).off('click', '.btn_eliminar_tramo').on('click', '.btn_eliminar_tramo',function(event) {
        hash=$(this).data("hash");
        if(confirm("¿Esta seguro que desea eliminar este registro?")){
          $.post('eliminaTramosRcdc'+"?"+$.now(),{"hash": hash}, function(data) {

            if(data.res=="ok"){
              $.notify(data.msg, {
                className:'success',
                globalPosition: 'top right'
              })
              lista_tramos.ajax.reload();

            }else{
              $.notify(data.msg, {
                className:'danger',
                globalPosition: 'top right'
              })
            }
          },"json")
        }
      })
      
  /**** TIPO ****/
    /*****DATATABLE*****/   
    var lista_tipos = $('#lista_tipos').DataTable({
       "aaSorting" : [[1,"desc"]],
       "scrollY": "65vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "responsive":false,
       // "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>getTiposRcdcList",
          "dataSrc": function (json) {
            $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_detalle").prop("disabled" , false);
            return json;
          },       
          data: function(param){
          }
        },    
       "columns": [
          {
            "class":"centered margen-td","width" : "30px","data": function(row,type,val,meta){
                btn = "";
                btn  =`<center><a  href="#!"   data-hash="${row.hash}"  title="Estado" class="btn_editar_tipo" style="font-size:14px!important;"><i class="fas fa-edit"></i> </a>`;
                if(perfil==1){
                  btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar_tipo rojo"><i class="fa fa-trash"></i></a>';
                }
                btn+='</center>';
                return btn;
            }
          },
          { "data": "id" ,"class":"margen-td centered"},
          { "data": "tipo" ,"class":"margen-td centered"},
        ]
      }); 
  

      $(document).on('keyup paste', '#buscador', function() {
        lista_tipos.search($(this).val().trim()).draw();
      });

      $(document).off('click', '.btn_filtro_detalle').on('click', '.btn_filtro_detalle',function(event) {
        event.preventDefault();
         $(this).prop("disabled" , true);
         $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
         lista_tipos.ajax.reload();
      });


      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var lista_tipos = $.fn.dataTable.fnTables(true);
        if ( lista_tipos.length > 0 ) {
            $(lista_tipos).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var lista_tipos = $.fn.dataTable.fnTables(true);
        if ( lista_tipos.length > 0 ) {
            $(lista_tipos).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var lista_tipos = $.fn.dataTable.fnTables(true);
        if ( lista_tipos.length > 0 ) {
            $(lista_tipos).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 


     

    /*********INGRESO************/

      $(document).off('click', '.btn_nuevo_tipo').on('click', '.btn_nuevo_tipo',function(event) {
          $('#modal_tipos').modal('toggle'); 
          $(".btn_guardar_tipo").html('<i class="fa fa-save"></i> Guardar');
          $(".btn_guardar_tipo").attr("disabled", false);
          $(".cierra_modal_tipos").attr("disabled", false);
          $('#formTiposRcdc')[0].reset();
          $("#hash_tipo").val("");
          $("#formTiposRcdc input,#formTiposRcdc select,#formTiposRcdc button,#formTiposRcdc").prop("disabled", false);
      });     

      $(document).off('submit', '#formTiposRcdc').on('submit', '#formTiposRcdc',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formTiposRcdc");
        var formData = new FormData(formElement);
          $.ajax({
              url: $('#formTiposRcdc').attr('action')+"?"+$.now(),  
              type: 'POST',
              data: formData,
              cache: false,
              processData: false,
              dataType: "json",
              contentType : false,
              beforeSend:function(){
                $(".btn_guardar_tipo").attr("disabled", true);
                $(".cierra_modal_tipos").attr("disabled", true);
                $("#formTiposRcdc input,#formTiposRcdc select,#formTiposRcdc button,#formTiposRcdc").prop("disabled", true);
              },
              success: function (data) {
              if(data.res == "error"){

                  $(".btn_guardar_tipo").attr("disabled", false);
                  $(".cierra_modal_tipos").attr("disabled", false);

                  $.notify(data.msg, {
                    className:'error',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $("#formTiposRcdc input,#formTiposRcdc select,#formTiposRcdc button,#formTiposRcdc").prop("disabled", false);

                }else if(data.res == "ok"){
                    $(".btn_guardar_tipo").attr("disabled", false);
                    $(".cierra_modal_tipos").attr("disabled", false);

                    $.notify("Datos ingresados correctamente.", {
                      className:'success',
                      globalPosition: 'top right',
                      autoHideDelay:5000,
                    });
                  
                    $('#modal_tipos').modal("toggle");
                    lista_tipos.ajax.reload();
              }

              $(".btn_guardar_tipo").attr("disabled", false);
              $(".cierra_modal_tipos").attr("disabled", false);
              $("#formTiposRcdc input,#formTiposRcdc select,#formTiposRcdc button,#formTiposRcdc").prop("disabled", false);
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
                      $('#modal_tipos').modal("toggle");
                  }    
                  return;
              }

              if (xhr.status == 500) {
                  $.notify("Problemas en el servidor, intente más tarde.", {
                    className:'warn',
                    globalPosition: 'top right'
                  });
                  $('#modal_tipos').modal("toggle");
              }
            },timeout:25000
          });
        return false; 
      });

      $(document).off('click', '.btn_editar_tipo').on('click', '.btn_editar_tipo',function(event) {
        event.preventDefault();
        $("#hash_tipo").val("")
        hash=$(this).data("hash")
        $('#formTiposRcdc')[0].reset()
        $("#hash_tipo").val(hash)
        $('#modal_tipos').modal('toggle')
        $("#formTiposRcdc input,#formTiposRcdc select,#formTiposRcdc button,#formTiposRcdc").prop("disabled", true)
        $(".btn_guardar_tipo").attr("disabled", true)
        $(".cierra_modal").attr("disabled", true)

        $.ajax({
          url: base+"getDataTiposRcdc"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          data:{hash:hash},
          dataType:"json",
          beforeSend:function(){
            $(".btn_guardar_tipo").prop("disabled",true); 
            $(".cierra_modal").prop("disabled",true); 
          },
          success: function (data) {

            if(data.res=="ok"){

              for(dato in data.datos){
                $("#hash_tipo").val(data.datos[dato].hash);
                $("#tipo").val(data.datos[dato].tipo);
              }
            
              $("#formTiposRcdc input,#formTiposRcdc select,#formTiposRcdc button,#formTiposRcdc").prop("disabled", false);
              $(".cierra_modal").prop("disabled", false);
              $(".btn_guardar_tipo").prop("disabled", false);

            }else if(data.res == "sess"){
              window.location="../";
            }

            $(".btn_guardar_tipo").prop("disabled",false); 
            $(".cierra_modal").prop("disabled",false); 
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
                $('#modal_tipos').modal("toggle");
            }
          } , timeout:35000
        }) 
      })

      $(document).off('click', '.btn_eliminar_tipo').on('click', '.btn_eliminar_tipo',function(event) {
        hash=$(this).data("hash");
        if(confirm("¿Esta seguro que desea eliminar este registro?")){
          $.post('eliminaTiposRcdc'+"?"+$.now(),{"hash": hash}, function(data) {

            if(data.res=="ok"){
              $.notify(data.msg, {
                className:'success',
                globalPosition: 'top right'
              })
              lista_tipos.ajax.reload();

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

<!-- LISTADO -->
  <div class="row">
    <div class="col-lg-6">
      <center>
        <div class="col-lg-3">
          <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_tramo btn_xr3">
            <i class="fa fa-plus-circle"></i>  Crear
          </button>
        </div>
      </center>
      <table id="lista_tramos" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>  
            <th class="centered">Acciones</th>   
            <th class="centered">id</th> 
            <th class="centered">tramo</th> 
          </tr>
        </thead>
      </table>
    </div>

    <div class="col-lg-6">
      <center>
        <div class="col-lg-3">
          <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_tipo btn_xr3">
            <i class="fa fa-plus-circle"></i>  Crear
          </button>
        </div>
      </center>
      <table id="lista_tipos" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>  
            <th class="centered">Acciones</th>   
            <th class="centered">id</th> 
            <th class="centered">tipo</th> 
          </tr>
        </thead>
      </table>
    </div>
  </div>



<!--  FORMULARIO-->
  <div id="modal_tramos" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formTramosRcdc",array("id"=>"formTramosRcdc","class"=>"formTramosRcdc"))?>

    <div class="modal-dialog modal_tramos modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-body">
          <input type="hidden" name="hash_tramo" id="hash_tramo">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro de tramos</legend>
            <div class="form-row">
              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nombre de tramo</label>         
                <input type="text"  placeholder="Ingrese información adicional" class="form-control form-control-sm"  name="tramo" id="tramo">
                </div>
              </div>
            </div>
          </fieldset> 
        </div>

        <div class="modal-footer" style="border-top: none;">
            <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
              <div class="form-row">

                <div class="col-4 col-lg-3">
                  <button type="submit" class="btn-block btn btn-sm btn-primary btn_guardar_tramo">
                   <i class="fa fa-save"></i> Guardar
                  </button>
                </div>

                <div class="col-4 col-lg-3">
                  <button class="btn-block btn btn-sm btn-secondary cierra_modal_tramos" data-dismiss="modal">
                   <i class="fa fa-window-close"></i> Cerrar
                  </button>
                </div>
              </div>
            </div>
          </div>

      </div>
    </div>
    <?php echo form_close(); ?>
  </div>

  <div id="modal_tipos" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formTiposRcdc",array("id"=>"formTiposRcdc","class"=>"formTiposRcdc"))?>

    <div class="modal-dialog modal_tipos modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-body">
          <input type="hidden" name="hash_tipo" id="hash_tipo">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro de tipos</legend>
            <div class="form-row">
              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nombre de tipo</label>         
                <input type="text"  placeholder="Ingrese información adicional" class="form-control form-control-sm"  name="tipo" id="tipo">
                </div>
              </div>
            </div>
          </fieldset> 
        </div>

        <div class="modal-footer" style="border-top: none;">
            <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
              <div class="form-row">

                <div class="col-4 col-lg-3">
                  <button type="submit" class="btn-block btn btn-sm btn-primary btn_guardar_tipo">
                   <i class="fa fa-save"></i> Guardar
                  </button>
                </div>

                <div class="col-4 col-lg-3">
                  <button class="btn-block btn btn-sm btn-secondary cierra_modal_tipos" data-dismiss="modal">
                   <i class="fa fa-window-close"></i> Cerrar
                  </button>
                </div>
              </div>
            </div>
          </div>

      </div>
    </div>
    <?php echo form_close(); ?>
  </div>