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
      

      $("#patente").select2({
            placeholder: 'Seleccione Patente | Todas',
            data: <?php echo $patentes; ?>,
            allowClear: true,
      });

      $(document).off('click', '.btn_nueva_liquidacion').on('click', '.btn_nueva_liquidacion', function(event) {
        $('#formDocumentoFlota')[0].reset();
        $(".form_detalle_asistentes").html("");
        $("#hash_liqui").val("");
        $('#modal_flota').modal('toggle'); 
        $("#formDocumentoFlota input,#formDocumentoFlota select,#formDocumentoFlota button,#formDocumentoFlota").prop("disabled", false);
        $(".btn_ingreso").attr("disabled", false);
        $(".cierra_modal").attr("disabled", false);
       
    });

    $(document).off('submit', '#formDocumentoFlota').on('submit', '#formDocumentoFlota',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formDocumentoFlota");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formDocumentoFlota').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_ingreso").attr("disabled", true);
              $(".cierra_modal").attr("disabled", true);
              $("#formDocumentoFlota input,#formDocumentoFlota select,#formDocumentoFlota button,#formDocumentoFlota").prop("disabled", true);
            },
            success: function (data) {

              if(data.res == "sess"){
                window.location="unlogin";

              }else if(data.res=="ok"){

                $('#modal_flota').modal('toggle'); 
                $("#formDocumentoFlota input,#formDocumentoFlota select,#formDocumentoFlota button,#formDocumentoFlota").prop("disabled", false);
                $(".btn_ingreso").attr("disabled", false);
                $(".cierra_modal").attr("disabled", false);

                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $('#formDocumentoFlota')[0].reset();
                lista_flota.ajax.reload();

              }else if(data.res=="error"){
                    
                $(".btn_ingreso").attr("disabled", false);
                $(".cierra_modal").attr("disabled", false);
                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });
                $("#formDocumentoFlota input,#formDocumentoFlota select,#formDocumentoFlota button,#formDocumentoFlota").prop("disabled", false);

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
            "url":"<?php echo base_url();?>listaDocumentoFlota",    
            "dataSrc": function (json) {
              return json;
            },     
            data: function(param){
              param.patente = $("#patente").val();
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
            { "data": "patente" ,"class":"margen-td centered","width" : "100px"},
            {
              "class":"centered margen-td","data": function(row,type,val,meta){
                btn  =`<center><a  target="_blank" href="<?php echo base_url() ?>/archivos/flota/${row.archivo}" title="Archivo" class="btn_archivo"><i class="fas fa-file"></i> </a></center>`;
                return btn;
              }
            },
            { "data": "titulo" ,"class":"margen-td centered","width" : "400px"},
            { "data": "ultima_actualizacion" ,"class":"margen-td centered"},
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

      $(document).off('change', '#patente').on('change', '#patente',function(event) {
        lista_flota.ajax.reload();

      }); 

      $(document).on('keyup paste', '#buscador', function() {
        lista_flota.search($(this).val().trim()).draw();
      });

          
    $(document).off('click', '.btn_eliminar').on('click', '.btn_eliminar',function(event) {
      hash=$(this).data("hash");
      if(confirm("¿Esta seguro que desea eliminar este registro?")){
        $.post('<?php echo base_url();?>eliminaDocumentoFlota'+"?"+$.now(),{"hash": hash}, function(data) {

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

      $(document).off('click', '#agrega_linea').on('click', '#agrega_linea',function(event) {
        event.preventDefault();
        AgregarAsistente();
      });
    
      function AgregarAsistente($data = null){
        if($data != null){
          for(var i = 0; i < $data.length; i++){
            var html ='<tr>'+
                      '<td><p class="table_text corr">'+
                          '<p class="table_text">'+
                            '<select id="patentes_'+i+'" name="patentes[]" style="width:100%!important;" class="form-control form-control-sm">'+
                              '<option></option>'+
                            '</select>'+
                        '</p>'+
                      '</td>'+
                      '<td><p class="table_text">'+
                          '<p class="table_text">'+
                            '<input type="text" id="titulo_archivos_'+i+'" name="titulo_archivos[]" placeholder="Ingrese título de archivo" style="width:100%!important;" class="form-control form-control-sm">'+
                            '</input>'+
                        '</p>'+
                      '</td>'+
                      '<td><p class="table_text">'+
                          '<p class="table_text">'+
                            '<input type="file" id="archivos_'+i+'" name="archivos[]" placeholder="Ingrese título de archivo" style="width:100%!important;" class="form-control form-control-sm">'+
                            '</input>'+
                        '</p>'+
                      '</td>'+
                  '</tr>';
            $(".form_detalle_asistentes").append(html);
            document.getElementById("patentes_"+i).value=$data[i]['nombre'];
            document.getElementById("cargos_"+i).value=$data[i]['cargo'];
            var correlativo = $('.corr').length;
            $(".corr:last").val(correlativo);
            $(".corr").prop("disabled",true);
            $(".agrega_linea_cont_ingresos").show();
            $("#patentes_"+i.toString()).select2({
              placeholder: 'Seleccione Patente | Todas',
              data: <?php echo ($patentes); ?>,
              allowClear: true,
            });
          }
        }else{
          var correlativo = $('.corr').length;
          var html ='<tr>'+
                    '<td><p class="table_text corr">'+
                        '<p class="table_text">'+
                          '<select id="patentes_'+correlativo+'" name="patentes[]" style="width:100%!important;" class="form-control form-control-sm">'+
                            '<option></option>'+
                          '</select>'+
                        '</p>'+
                    '</td>'+
                    '<td><p class="table_text">'+
                          '<p class="table_text">'+
                            '<input type="text" id="titulo_archivos_'+correlativo+'" name="titulo_archivos[]" placeholder="Ingrese título de archivo" style="width:100%!important;" class="form-control form-control-sm">'+
                            '</input>'+
                        '</p>'+
                      '</td>'+
                      '<td><p class="table_text">'+
                          '<p class="table_text">'+
                            '<input type="file" id="archivos_'+correlativo+'" name="archivos[]" placeholder="Ingrese título de archivo" style="width:100%!important;" class="form-control form-control-sm">'+
                            '</input>'+
                        '</p>'+
                      '</td>'+
                  '</tr>';
          $(".form_detalle_asistentes").append(html);
          $(".corr:last").val(correlativo);
          $(".corr").prop("disabled",true);
          $(".agrega_linea_cont_ingresos").show();
          $("#patentes_"+correlativo.toString()).select2({
            placeholder: 'Seleccione Patente | Todas',
            data: <?php echo ($patentes); ?>,
            allowClear: true,
          });
        }
      }


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
    
    <!-- 
      <div class="col-12 col-lg-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="margin-left:5px;font-size:13px;">Fecha <span></span> 
            </div>
            <input type="date" placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="desde_t" id="desde_t">
            <input type="date" placeholder="Hasta" class="fecha_normal form-control form-control-sm"  name="hasta_t" id="hasta_t">
          </div>
        </div>
      </div>
    -->

    <div class="col-6 col-lg-2">
      <div class="form-group">
        <select id="patente" name="patente" class="custom-select custom-select-sm"style="width:100%!important;">
        <option></option>
        </select>
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
            <span class="title_section">Tabla de Documentos</span>
            <table id="lista_flota" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                <tr>    
                  <th class="centered">Acciones</th> 
                  <th class="centered">Patente</th> 
                  <th class="centered">Documento</th> 
                  <th class="centered">Titulo</th> 
                  <th class="centered">Última Actualización</th> 
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
        <?php echo form_open_multipart("formDocumentoFlota",array("id"=>"formDocumentoFlota","class"=>"formDocumentoFlota"))?>
          <input type="hidden" name="hash_liqui" id="hash_liqui">

          <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>

          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Datos de asistentes</legend>
            <div class="form-row">
                <table id="tabla_asistentes" width="100%" class="dataTable table-striped  datatable_h table table-hover table-bordered table-condensed">
                  <thead>
                    <tr style="background-color:#F9F9F9">
                        <th class="table_head desktop tablet">Patente</th>
                        <th class="table_head all">Título de documento</th>
                        <th class="table_head all">Archivo</th>
                    </tr>
                  </thead>
                  <tbody class="form_detalle_asistentes">
                  </tbody>
                </table>

                <div class="col text-center agrega_linea_cont_entrega">
                <div class="form-group">
                  <button class="btn btn-sm btn-primary" id="agrega_linea"><i class="fa fa-plus-circle"></i> Agregar m&aacute;s documentos</button>
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



  