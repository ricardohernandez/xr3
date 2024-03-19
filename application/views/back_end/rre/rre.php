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

  /*****DATATABLE*****/   
    var lista_rre = $('#lista_rre').DataTable({
       "aaSorting" : [[1,"desc"]],
       "scrollY": "65vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "responsive":false,
       // "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>getRreList",
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
                btn  =`<center><a  href="#!"   data-hash="${row.hash}"  title="Estado" class="btn_editar" style="font-size:14px!important;"><i class="fas fa-edit"></i> </a>`;
                if(perfil==1){
                  btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar rojo"><i class="fa fa-trash"></i></a>';
                }
                btn+='</center>';
                return btn;
            }
          },
          { "data": "fecha" ,"class":"margen-td centered"},
          { "data": "nombre_completo" ,"class":"margen-td centered"},
          { "data": "comuna" ,"class":"margen-td centered"},
          { "data": "serie" ,"class":"margen-td centered"},
          { "data": "modelo" ,"class":"margen-td centered"},
          { "data": "num_cliente" ,"class":"margen-td centered"},
          { "data": "proyecto" ,"class":"margen-td centered"},
          { "class":"margen-td centered", "data": function(row,type,val,meta){
            if(row.observacion!="" && row.observacion!=null){
                   if(row.observacion.length > 30) {
                     str = row.observacion.substring(0,30)+"...";
                     return "<span class='btndesp2'>"+str+"</span><span title='Ver texto completo' class='ver_obs_desp' data-tit="+row.observacion.replace(/ /g,"_")+" data-title="+row.observacion.replace(/ /g,"_")+">Ver más</span>";
                   }else{
                     return "<span class='btndesp2' data-title="+row.observacion.replace(/ /g,"_")+">"+row.observacion+"</span>";
                  }
                }else{
                  return "-";
                }
            }
          },
        ]
      }); 
  

      $(document).on('keyup paste', '#buscador', function() {
        lista_rre.search($(this).val().trim()).draw();
      });

      $(document).off('click', '.btn_filtro_detalle').on('click', '.btn_filtro_detalle',function(event) {
        event.preventDefault();
         $(this).prop("disabled" , true);
         $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
         lista_rre.ajax.reload();
      });


      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var lista_rre = $.fn.dataTable.fnTables(true);
        if ( lista_rre.length > 0 ) {
            $(lista_rre).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var lista_rre = $.fn.dataTable.fnTables(true);
        if ( lista_rre.length > 0 ) {
            $(lista_rre).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var lista_rre = $.fn.dataTable.fnTables(true);
        if ( lista_rre.length > 0 ) {
            $(lista_rre).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 


     

  /*********INGRESO************/

    $(document).off('click', '.btn_nuevo_rre').on('click', '.btn_nuevo_rre',function(event) {
        $('#modal_rre').modal('toggle'); 
        $(".btn_guardar_detalle").html('<i class="fa fa-save"></i> Guardar');
        $(".btn_guardar_detalle").attr("disabled", false);
        $(".cierra_modal_rre").attr("disabled", false);
        $('#formRre')[0].reset();
        $("#hash_detalle").val("");
        $("#id_coordinador").val(user);
        document.getElementById("label_codigo").textContent = "Codigo";
        $("#formRre input,#formRre select,#formRre button,#formRre").prop("disabled", false);
    });     

    $(document).off('submit', '#formRre').on('submit', '#formRre',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formRre");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formRre').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_detalle").attr("disabled", true);
              $(".cierra_modal_rre").attr("disabled", true);
              $("#formRre input,#formRre select,#formRre button,#formRre").prop("disabled", true);
            },
            success: function (data) {
             if(data.res == "error"){

                $(".btn_guardar_detalle").attr("disabled", false);
                $(".cierra_modal_rre").attr("disabled", false);

                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $("#formRre input,#formRre select,#formRre button,#formRre").prop("disabled", false);

              }else if(data.res == "ok"){
                  $(".btn_guardar_detalle").attr("disabled", false);
                  $(".cierra_modal_rre").attr("disabled", false);

                  $.notify("Datos ingresados correctamente.", {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                
                  $('#modal_rre').modal("toggle");
                  lista_rre.ajax.reload();
            }

            $(".btn_guardar_detalle").attr("disabled", false);
            $(".cierra_modal_rre").attr("disabled", false);
            $("#formRre input,#formRre select,#formRre button,#formRre").prop("disabled", false);
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
                    $('#modal_rre').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_rre').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

    $(document).off('click', '.btn_editar').on('click', '.btn_editar',function(event) {
      event.preventDefault();
      $("#hash_detalle").val("")
      hash=$(this).data("hash")
      $('#formRre')[0].reset()
      $("#hash_detalle").val(hash)
      $('#modal_rre').modal('toggle')
      $("#formRre input,#formRre select,#formRre button,#formRre").prop("disabled", true)
      $(".btn_guardar_detalle").attr("disabled", true)
      $(".cierra_modal").attr("disabled", true)

      $.ajax({
        url: base+"getDataRre"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash:hash},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardar_detalle").prop("disabled",true); 
          $(".cierra_modal").prop("disabled",true); 
        },
        success: function (data) {

          if(data.res=="ok"){

            for(dato in data.datos){
              $("#hash_detalle").val(data.datos[dato].hash);
              $("#fecha").val(data.datos[dato].fecha);
              $("#comuna").val(data.datos[dato].comuna);
              $('#tecnico').val(data.datos[dato].id_usuario).trigger('change');
              $("#serie").val(data.datos[dato].serie);
              $("#modelo").val(data.datos[dato].modelo);
              $("#num_cliente").val(data.datos[dato].num_cliente);
              $('#proyecto').val(data.datos[dato].id_proyecto).trigger('change');
              $("#observacion").val(data.datos[dato].observacion);
            }
          
            $("#formRre input,#formRre select,#formRre button,#formRre").prop("disabled", false);
            $(".cierra_modal").prop("disabled", false);
            $(".btn_guardar_detalle").prop("disabled", false);

          }else if(data.res == "sess"){
            window.location="../";
          }

          $(".btn_guardar_detalle").prop("disabled",false); 
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
              $('#modal_rre').modal("toggle");
          }
        } , timeout:35000
      }) 
    })

    $(document).off('click', '.btn_eliminar').on('click', '.btn_eliminar',function(event) {
      hash=$(this).data("hash");
      if(confirm("¿Esta seguro que desea eliminar este registro?")){
        $.post('eliminaRre'+"?"+$.now(),{"hash": hash}, function(data) {

          if(data.res=="ok"){
            $.notify(data.msg, {
              className:'success',
              globalPosition: 'top right'
            })
            lista_rre.ajax.reload();

          }else{
            $.notify(data.msg, {
              className:'danger',
              globalPosition: 'top right'
            })
          }
        },"json")
      }
    })
  /********OTROS**********/

  $(document).on('click', '.ver_obs_desp', function(event) { //Función de botón "Ver Texto" en tabla
      event.preventDefault();
      val=$(this).attr("data-tit");
      elem=$(this);
      if(elem.text()=="Ver más"){
        elem.html("Ocultar");     
        elem.attr("title","Acortar texto");
        elem.prev(".btndesp2").text(val.replace(/_/g, ' '));
        var table = $.fn.dataTable.fnTables(true);
        if ( table.length > 0 ) {
            $(table).dataTable().fnAdjustColumnSizing();
        }
      }else if(elem.text()=="Ocultar"){
        val = val.substring(0,60)+"...";
        elem.prev(".btndesp2").text(val.replace(/_/g, ' '));     
        elem.html("Ver más");
        elem.attr("title","Ver texto completo");
        var table = $.fn.dataTable.fnTables(true);
        if ( table.length > 0 ) {
            $(table).dataTable().fnAdjustColumnSizing();
        }
      }
    });
    
  $(document).off('click', '.excelrre').on('click', '.excelrre',function(event) {
      event.preventDefault();
      window.location="excelRre";
    });


  })

  $(document).off('keydown', '.numbersOnly').on('keydown', '.numbersOnly',function(e) {
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 188]) !== -1 ||
          (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
          (e.keyCode >= 35 && e.keyCode <= 40)) { 
               return;
      }
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
          e.preventDefault();
      }
    });

</script>

<!-- FILTROS -->
  
    <div class="form-row">

      <div class="col-12 col-lg-1">  
        <div class="form-group">
           <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_rre btn_xr3">
           <i class="fa fa-plus-circle"></i>  Crear
           </button>
        </div>
      </div>

      <div class="col-6 col-lg-2">  
       <div class="form-group">
        <input type="text" placeholder="Busqueda" id="buscador" class="buscador form-control form-control-sm">
       </div>
      </div>

      <div class="col-6 col-lg-1">  
        <div class="form-group">
         <button type="button"  class="btn-block btn btn-sm btn-primary excelrre btn_xr3">
         <i class="fa fa-save"></i> Excel
         </button>
        </div>
      </div>
      
    </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="lista_rre" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>  
            <th class="centered">Acciones</th>
            <th class="centered">Fecha</th> 
            <th class="centered">Usuario</th> 
            <th class="centered">Comuna</th> 
            <th class="centered">Serie(Macc)</th> 
            <th class="centered">Modelo equipo</th> 
            <th class="centered">Número de cliente</th> 
            <th class="centered">Proyecto</th>  
            <th class="centered">Observación</th> 
          </tr>
        </thead>
      </table>
    </div>
  </div>


<!--  FORMULARIO-->
  <div id="modal_rre" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formRre",array("id"=>"formRre","class"=>"formRre"))?>

    <div class="modal-dialog modal_rre modal-dialog-scrollable">
      <div class="modal-content">

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <input type="hidden" name="hash_detalle" id="hash_detalle">
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Registro retiro de equipos</legend>

            <div class="form-row">

              <div class="col-12 col-lg-3">
                  <div class="form-group">
                    <div class="input-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha</label>
                       <input type="date" placeholder="Fecha" class="form-control form-control-sm"  value="<?php echo date('Y-m-d')?>" name="fecha" id="fecha">
                    </div>
                  </div>
              </div>

              <div class="col-12 col-lg-3">
                  <div class="form-group">
                    <div class="input-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Usuario</label>
                    <select id="tecnico" name="tecnico" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione usuario</option>
                        <?php 
                        foreach($usuarios as $u){
                          ?>
                            <option value="<?php echo $u["id"]; ?>"><?php echo $u["nombre_completo"]?></option>
                          <?php
                        }
                      ?>
                    </select>
                    </div>
                  </div>
              </div>

              <div class="col-12 col-lg-3">
                  <div class="form-group">
                    <div class="input-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Comuna</label>
                       <input type="text" placeholder="Ingrese comuna..." class="form-control form-control-sm" name="comuna" id="comuna">
                    </div>
                  </div>
              </div>

              <div class="col-12 col-lg-3">
                  <div class="form-group">
                    <div class="input-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Serie (macc)</label>
                       <input type="text" placeholder="Ingrese serie..." class="form-control form-control-sm" name="serie" id="serie">
                    </div>
                  </div>
              </div>

              <div class="col-12 col-lg-3">
                  <div class="form-group">
                    <div class="input-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Modelo equipo</label>
                       <input type="text" placeholder="Ingrese modelo..." class="form-control form-control-sm" name="modelo" id="modelo">
                    </div>
                  </div>
              </div>

              <div class="col-12 col-lg-3">
                  <div class="form-group">
                    <div class="input-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Número de cliente</label>
                       <input type="text" placeholder="Ingrese número de cliente..." class="form-control form-control-sm" name="num_cliente" id="num_cliente">
                    </div>
                  </div>
              </div>

              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Proyecto </label>
                  <select id="proyecto" name="proyecto" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                    <?php 
                      foreach($proyectos as $p){
                        ?>
                          <option value="<?php echo $p["id"]; ?>"><?php echo $p["proyecto"]?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-12">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Observaciones</label>         
                <input type="text"  placeholder="Ingrese información adicional" class="form-control form-control-sm"  name="observacion" id="observacion">
                </div>
              </div>

            </div>
          </fieldset> 
         
        </div>

        <div class="modal-footer" style="border-top: none;">
            <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
              <div class="form-row">

                <div class="col-4 col-lg-3">
                  <button type="submit" class="btn-block btn btn-sm btn-primary btn_guardar_detalle">
                   <i class="fa fa-save"></i> Guardar
                  </button>
                </div>

                <div class="col-4 col-lg-3">
                  <button class="btn-block btn btn-sm btn-secondary cierra_modal_rre" data-dismiss="modal">
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