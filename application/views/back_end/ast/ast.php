<style type="text/css">
  .red{
    background-color: #DC3545;
    color: #fff;
  }

  .grey{
    background-color: grey;
    color: #fff;
  }

  .contenedor_checklist{
    min-height: 600px;
  }
  @media(min-width: 768px){
    .borrar_ast{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
    }

    .btn_modificar_ast{
      display: block;
      text-align: center!important;
      margin:0 auto!important;
      font-size: 15px!important;
    }

    .pdf_chk{
      cursor: pointer;
      display: inline;
      font-size: 15px!important;
      margin-left: 15px;
      color: #000;
    }

    .modal_ast{
      width: 94%!important;
    }
    .table_head{
      font-size: 12px!important;
    }

  }
  @media(max-width: 768px){
    .borrar_ast{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 20px;
      text-decoration: none!important;
    }
    .btn_modificar_ast{
      display: block;
      text-align: center!important;
      font-size: 18px!important;
    }

    .pdf_chk{
        cursor: pointer;
      display: inline;
      font-size: 15px!important;
      margin-left: 15px;
      color: #000;
    }

    .modal_ast{
      width: 94%!important;
    }
    .table_head{
      font-size: 11px!important;
    }
  }

</style>

<script type="text/javascript">
  $(function(){
    var desde="<?php echo $desde; ?>";
    var hasta="<?php echo $hasta; ?>";
    $("#desde_f").val(desde);
    $("#hasta_f").val(hasta);
    const id_perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    const base = "<?php echo base_url() ?>";

  /*****DATATABLE*****/   
    var listaAst = $('#listaAst').DataTable({
       /*"sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',*/
       "iDisplayLength":-1, 
       "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
       "bPaginate": false,
       "aaSorting" : [[9,"desc"]],
       "scrollY": "60vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       "columnDefs" : [
          { orderable: false , targets: 0 }
       ],
       "ajax": {
          "url":"<?php echo base_url();?>listaAst",
          "dataSrc": function (json) {
            $(".btn_filtro_ast").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_ast").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            param.desde = $("#desde_f").val();
            param.hasta = $("#hasta_f").val();
          }
        },    
       "columns": [
          {
           "class":"centered center margen-td","data": function(row,type,val,meta){
              btn='<center><a data-toggle="modal" href="#modal_ast" data-hash="'+row.hash+'" data-placement="top" data-toggle="tooltip" title="Modificar" class="fa fa-edit btn_modificar_ast"></a>';
              
              if(id_perfil==1 || id_perfil==2){
                btn+='<a href="#" data-placement="top" data-toggle="tooltip" title="Eliminar" class="fa fa-trash borrar_ast" data-hash="'+row.hash+'"></a>';
              }

              btn+='<a href="#!" data-hash="'+row.hash+'" title="PDF" class="fa fa-file-pdf pdf_chk"></a></center>';

              return btn;
            }
          },
          { "data": "actividad" ,"class":"margen-td centered"},
          { "data": "tecnico" ,"class":"margen-td centered"},
          { "data": "auditor" ,"class":"margen-td centered"},
          { "data": "fecha" ,"class":"margen-td centered"},
          { "data": "hora" ,"class":"margen-td centered"},
          { "data": "estado_ast" ,"class":"margen-td centered"},
          { "data": "localidad" ,"class":"margen-td centered"},
          { "data": "direccion" ,"class":"margen-td centered"},
          { "data": "ultima_actualizacion" ,"class":"margen-td centered"}
        ]
      }); 

      $(document).on('keyup paste', '#buscador_ast', function() {
        listaAst.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var listaAst = $.fn.dataTable.fnTables(true);
        if ( listaAst.length > 0 ) {
            $(listaAst).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var listaAst = $.fn.dataTable.fnTables(true);
        if ( listaAst.length > 0 ) {
            $(listaAst).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var listaAst = $.fn.dataTable.fnTables(true);
        if ( listaAst.length > 0 ) {
            $(listaAst).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 

  /*********INGRESO************/

    $(document).off('click', '.btn_nuevo_ast').on('click', '.btn_nuevo_ast',function(event) {
      $('#modal_ast').modal('toggle'); 
      $(".btn_guardar_ast").html('<i class="fa fa-save"></i> Guardar');
      $(".btn_guardar_ast").attr("disabled", false);
      $(".cierra_modal_ast").attr("disabled", false);
      $('#formAst')[0].reset();
      $("#hash_ast").val("");
      $(".btn_guardar_ast").html('<i class="fa fa-save"></i> Guardar').attr("disabled", false);
      $("#formAst input,#formAst select,#formAst button,#formAst").prop("disabled", false);
      $(".estado").removeClass("red");
      $(".estado").removeClass("grey");
      $("#actividad").prop("disabled",false);
      $(".cont_mod").hide();
      $(".contenedor_checklist").html("")
      $("#checkcorreo").prop( "checked", true );
      $("#id").prop("disabled",true)
      $("#riesgos_no_controlados").prop("disabled",true)
      $("#auditor").prop("disabled",true)

    });     

    $(document).off('submit', '#formAst').on('submit', '#formAst',function(event) {
      if(hash_ast!=""){
        setTimeout(function(){ 
          $(".buscador_user_checklist").val("");
          $('#tabla_user_checklist').DataTable().search("").draw();
          $(".buscador_checklist_ast").val("");
          $('#tabla_checklist_ast').DataTable().search("").draw();
          hash = $("#hash_ast").val()
        }, 1);
      }

      setTimeout(function(){ 
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formAst");
        var formData = new FormData(formElement);
        $.ajax({
            url: $('#formAst').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_ast").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...').attr("disabled", true);
              $(".cierra_modal_ast").attr("disabled", true);
              $("#formAst input,#formAst select,#formAst button,#formAst").prop("disabled", true);
            },
            success: function (data) {
             if(data.res == "error"){
                $(".btn_guardar_ast").html('<i class="fa fa-save"></i> Guardar').attr("disabled", false);
                $(".cierra_modal_ast").attr("disabled", false);

                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $("#formAst input,#formAst select,#formAst button,#formAst").prop("disabled", false);
                $("#actividad").prop("disabled",false);    

                $("#id").prop("disabled",true)
                $("#riesgos_no_controlados").prop("disabled",true)
                $("#auditor").prop("disabled",true)

              }else if(data.res == "ok"){

                $(".btn_guardar_ast").html('<i class="fa fa-save"></i> Guardar').attr("disabled", false);
                $(".cierra_modal_ast").attr("disabled", false);

                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right',
                  autoHideDelay:15000,
                });

                getDataAst(data.hash)
                $("#hash_ast").val(data.hash);
                $("#formAst input,#formAst select,#formAst button,#formAst").prop("disabled", false);
                $("#actividad").prop("disabled",true);    
                $("#id").prop("disabled",true)
                $("#riesgos_no_controlados").prop("disabled",true)
                $("#auditor").prop("disabled",false)
              }

            $(".btn_guardar_ast").html('<i class="fa fa-save"></i> Guardar').attr("disabled", false);
            $(".cierra_modal_ast").attr("disabled", false);
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
                      $('#modal_ast').modal("toggle");
                  }    
                  return;
              }

              if (xhr.status == 500) {
                  $.notify("Problemas en el servidor, intente más tarde.", {
                    className:'warn',
                    globalPosition: 'top right'
                  });
                  $('#modal_ast').modal("toggle");
              }
            },timeout:105000

        });

      },500);

      return false; 
    });

    $(document).off('click', '.btn_modificar_ast').on('click', '.btn_modificar_ast',function(event) {
      $("#hash_ast").val("");
      hash = $(this).attr("data-hash");
      $("#hash_ast").val(hash);
      $(".estado").removeClass("red");
      $(".estado").removeClass("grey");
      $(".btn_guardar_ast").html('<i class="fa fa-save"></i> Guardar').attr("disabled", false);
      $("#checkcorreo").prop( "checked", true );
      getDataAst(hash)        
    });

    function getDataAst(hash){
      $.ajax({
        url: base+"getDataAst"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash : hash},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardar_ast").attr("disabled", true);
          $(".cierra_modal_ast").attr("disabled", true);
          $("#formAst input,#formAst select,#formAst button,#formAst").prop("disabled", true);
        },
        success: function (data) {
          $(".btn_guardar_ast").attr("disabled", false);
          $(".cierra_modal_ast").attr("disabled", false);
          $("#formAst input,#formAst select,#formAst button,#formAst").prop("disabled", false);
          
          if(data.res=="ok"){

            for(dato in data.datos){
              $("#tecnico  option[value='"+data.datos[dato].tecnico_id+"'").prop("selected", true);
              $("#auditor  option[value='"+data.datos[dato].auditor_id+"'").prop("selected", true);
              $("#actividad").val(data.datos[dato].id_actividad);
              $("#comuna").val(data.datos[dato].id_comuna);
              $("#fecha").val(data.datos[dato].fecha);
              $("#direccion").val(data.datos[dato].direccion);
              $("#hora").val(data.datos[dato].hora);
              $("#observaciones").val(data.datos[dato].observaciones);
              $("#estado_ast").val(data.datos[dato].id_estado);
              $(".estado_str").html(data.datos[dato].estado_ast);
              $("#id").val(data.datos[dato].id_astt).prop("disabled",true);
              $("#riesgos_no_controlados").val(data.datos[dato].riesgos_o_controles_norealizados).prop("disabled",true);
              $(".cont_mod").show();

              if(data.datos[dato].id_estado!="3"){
                $("#auditor").prop("disabled",true)
              }

            } 

            $("#actividad").prop("disabled",true);                          
            getUserChecklistView(hash)
            listaAst.ajax.reload();
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
                    $('#modal_ast').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_ast').modal("toggle");
            }
          },timeout:25000
      }); 
    }

    function getChecklistView(actividad){
      $(".contenedor_checklist").html("")
      $.post('getChecklistView', { "actividad" :  actividad }, function(data) {
        $(".contenedor_checklist").html(data).show();
      });
    }

    function getUserChecklistView(hash){
      $(".contenedor_checklist").html("")
      $.post('getUserChecklistView', { "hash" :  hash }, function(data) {
        $(".contenedor_checklist").html(data).show();
      });
    }
 
    $(document).off('change', '#actividad').on('change', '#actividad',function(event) {
      actividad = $(this).val();
      getChecklistView(actividad)
    })
  
    $(document).off('click', '.borrar_ast').on('click', '.borrar_ast',function(event) {
        var hash=$(this).attr("data-hash");
        if(confirm("¿Esta seguro que desea eliminar este registro?")){
          $.post('eliminaAst'+"?"+$.now(),{"hash": hash}, function(data) {
            if(data.res=="ok"){
              $.notify(data.msg, {
                className:'success',
                globalPosition: 'top right'
              });
             listaAst.ajax.reload();
            }else{
              $.notify(data.msg, {
                className:'danger',
                globalPosition: 'top right'
              });
            }
          },"json");
        }
    });

  /********OTROS**********/
    
    $(document).off('click', '.btn_filtro_ast').on('click', '.btn_filtro_ast',function(event) {
      event.preventDefault();
       $(this).prop("disabled" , true);
       $(".btn_filtro_ast").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
       listaAst.ajax.reload();
    });

    $(".fecha_normal").datetimepicker({
        format: "DD-MM-YYYY",
        locale:"es",
        maxDate:"now"
    });

    $(document).off('change', '.file_cs').on('change', '.file_cs',function(event) {
      var myFormData = new FormData();
      myFormData.append('userfile', $('#userfile').prop('files')[0]);

      $.ajax({
          url: "formCargaMasivaAst"+"?"+$.now(),  
          type: 'POST',
          data: myFormData,
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          processData: false,
          contentType : false,
          dataType:"json",
          beforeSend:function(){
            $(".btn_file_cs").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...');
          },  
          success: function (data) {
             $(".btn_file_cs").html('<span class="glyphicon glyphicon-folder-open" style="margin-right:3px!important;"></span> CSV');
             //activarTodo();
              if(data.res=="ok"){
                $.notify(data.msg, {
                    className:data.tipo,
                    globalPosition: 'top right',
                    autoHideDelay: 20000,
                });

                table_mat.ajax.reload();


              }else{
                $.notify(data.msg, {
                    className:data.tipo,
                    globalPosition: 'top right',
                    autoHideDelay: 5000,
                });
              }
          },
          error : function(xhr, textStatus, errorThrown ) {
            //activarTodo();
            if (textStatus == 'timeout') {
                this.tryCount++;
                if (this.tryCount <= this.retryLimit) {
                    $.notify("Reintentando...", {
                      className:'info',
                      globalPosition: 'top right'
                    });
                    $.ajax(this);
                    $(".btn_file_cs").attr('disabled', false);      
                    $(".btn_file_cs").html('<span class="glyphicon glyphicon-folder-open" style="margin-right:3px!important;"></span> Importar');
                    return;
                } else{
                   $.notify("Problemas cargando el archivo, intente nuevamente.", {
                      className:'warn',
                      globalPosition: 'top right'
                    });
                }    
                return;
            }

            if (xhr.status == 500) {
               $.notify("Problemas cargando el archivo, intente nuevamente.", {
                  className:'warn',
                  globalPosition: 'top right'
               });
            $(".btn_file_cs").attr('disabled', false); 
            $(".btn_file_cs").html('<span class="glyphicon glyphicon-folder-open" style="margin-right:3px!important;"></span> Importar series');
            }
        },timeout:120000
      });
    })

    $(document).off('click', '.btn_excel').on('click', '.btn_excel',function(event) {
       event.preventDefault();
       var desde = $("#desde_f").val();
       var hasta = $("#hasta_f").val();
       
       if(desde==""){
         $.notify("Debe seleccionar una fecha de inicio.", {
             className:'error',
             globalPosition: 'top right'
         });  
         return false;
       }
       if(hasta==""){
         $.notify("Debe seleccionar una fecha de término.", {
             className:'error',
             globalPosition: 'top right'
         });  
        return false;
       }

       window.location="excel_ast/"+desde+"/"+hasta;
    });

    $(document).off('click', '.pdf_chk').on('click', '.pdf_chk',function(event) {
      const url = $(this).data("url")
      const hash = $(this).data("hash")
      $.post('generaPdfAstURL'+"?"+$.now(),{"hash": hash}, function(data) {
        if(data.res=="ok"){

          window.open(base + data.url, '_blank');
                  
        }else{
          $.notify(data.msg, {
            className:'danger',
            globalPosition: 'top right'
          });
        }
      },"json");
    });  

  })
</script>

<!-- FILTROS -->
  
  <div class="form-row">

      <!-- <div class="col-xs-6 col-sm-6 col-md-1 col-lg-1 no-padding">  
         <input type="file" id="userfile" name="userfile" class="file_cs" style="display:none;" />
         <button type="button" class="allwidth btn btn-danger btn-sm btn_file_cs" value="" onclick="document.getElementById('userfile').click();">
         <span class="glyphicon glyphicon-folder-open" style="margin-right:5px!important;"></span> CSV</button>
      </div> -->

      <div class="col-6 col-lg-2">  
        <div class="form-group">
           <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_ast btn_xr3">
           <i class="fa fa-plus-circle"></i>  Crear
           </button>
        </div>
      </div>

      <div class="col-lg-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span>Fecha <span></span> 
            </div>
              <input type="text" placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="desde_f" id="desde_f">
              <input type="text" placeholder="Hasta" class="fecha_normal form-control form-control-sm"  name="hasta_f" id="hasta_f">
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4">  
       <div class="form-group">
        <input type="text" placeholder="Busqueda" id="buscador_ast" class="buscador_ast form-control form-control-sm">
       </div>
      </div>

      <div class="col-6 col-lg-1">
        <div class="form-group">
         <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_ast btn_xr3">
         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
         </button>
       </div>
      </div>

      <div class="col-6 col-lg-1">  
        <div class="form-group">
         <button type="button"  class="btn-block btn btn-sm btn-primary btn_excel btn_xr3">
         <i class="fa fa-save"></i>  Excel
         </button>
        </div>
      </div>
      
    </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="listaAst" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <th class="centered" style="width: 50px;"></th>    
            <th class="centered">Actividad</th>   
            <th class="centered">Técnico</th>   
            <th class="centered">Auditor</th>  
            <th class="centered">Fecha</th>   
            <th class="centered">Hora</th>  
            <th class="centered">Estado</th> 
            <th class="centered">Localidad</th>   
            <th class="centered">Dirección</th>   
            <th class="centered">Última actualización</th>   
          </tr>
        </thead>
      </table>
    </div>
  </div>


<!--  FORMULARIO-->
  <div id="modal_ast" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formAst",array("id"=>"formAst","class"=>"formAst"))?>
    <input type="hidden" name="hash" id="hash_ast">
    <div class="modal-dialog modal_ast modal-dialog-scrollable">
      <div class="modal-content">

       <div class="modal-header">
        <div class="col-xs-12 col-sm-12 col-lg-4 offset-lg-4 mt-0">
          <div class="form-row">

            <div class="col-4 col-lg-4">
              <div class="form-group">  
                <div class="form-check mt-1">
                  <input type="checkbox"  ckecked name="checkcorreo" class="form-check-input mt-2" id="checkcorreo">
                  <label class="form-check-label" style="font-size: 14px;" for="checkcorreo">¿Enviar correo?</label>
                </div>
              </div>
            </div>

            <div class="col-4 col-lg-4">
                <button type="submit" class="btn-block btn btn-sm btn-success btn_guardar_ast">
                 <i class="fa fa-save"></i> Guardar
                </button>
            </div>

            <div class="col-4 col-lg-4">
              <button class="btn-block btn btn-sm btn-danger cierra_modal_ast" data-dismiss="modal" aria-hidden="true">
               <i class="fa fa-window-close"></i> Cerrar
              </button>
            </div>
          </div>
        </div>

       </div>

        <div class="modal-body">
         <!--  <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button> -->
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Formulario AST</legend>

            <div class="form-row">

              <div class="col-lg-1">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">ID</label>
                <input placeholder="" type="text" name=""  id="id" disabled class="form-control form-control-sm" autocomplete="off"  />
                </div>
              </div>

              <div class="col-lg-2">  
                <div class="form-group">
                <label for="col-form-label-sm" class="col-sm-12 col-form-label col-form-label-sm">Técnico</label>
                  <select id="tecnico" name="tecnico" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione...</option>
                        <?php 
                        foreach($tecnicos as $t){
                          ?>
                            <option value="<?php echo $t["id"]; ?>"><?php echo $t["nombre_completo"]; ?></option>
                          <?php
                        }
                      ?>
                  </select>
                </div>
              </div>
              
              <div class="col-lg-2">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Actividad</label>
                  <select id="actividad" name="actividad" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione...</option>
                        <?php 
                        foreach($actividades as $a){
                          ?>
                            <option value="<?php echo $a["id"]; ?>"><?php echo $a["actividad"]; ?></option>
                          <?php
                        }
                      ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-2">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Localidad</label>
                  <select id="comuna" name="comuna" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione...</option>
                        <?php 
                        foreach($comunas as $c){
                          ?>
                            <option value="<?php echo $c["id"]; ?>"><?php echo $c["proyecto"]; ?></option>
                          <?php
                        }
                      ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-2 cont_mod">
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Estado (<span class="estado_str"></span>)</label>
                  <select id="estado_ast" name="estado_ast" class="custom-select custom-select-sm">
                    <?php 
                      foreach($estados as $e){
                        ?>
                          <option value="<?php echo $e["id"]; ?>"><?php echo $e["estado"]; ?></option>
                        <?php
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-2">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha </label>
                <input placeholder="Fecha" type="date" name="fecha"  id="fecha" class="form-control form-control-sm" autocomplete="off" value="<?php echo date("Y-m-d") ?>" />
                </div>
              </div>

              <div class="col-lg-1">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Hora </label>
                <input placeholder="Hora" type="time" name="hora"  id="hora" class="form-control form-control-sm" autocomplete="off" value="<?php echo date("H:m") ?>" />
                </div>
              </div>

              <div class="col-lg-2">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Número OT </label>
                <input placeholder="Número OT" type="text" name="direccion"  id="direccion" class="form-control form-control-sm" autocomplete="off"  />
                </div>
              </div>

            </div>
          </fieldset> 


          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Escalamientos</legend>
            <div class="form-row">
              
              <div class="col-lg-3">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm"> Riesgos no controlados o controles no realizados </label>
                  <input placeholder="" type="text"  disabled id="riesgos_no_controlados" class="form-control form-control-sm" autocomplete="off"  />
                </div>
              </div>

              <div class="col-lg-2">               
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Supervisor</label>
                <div class="form-group">
                 <div class="input-group mb-3">
                    <select id="auditor" name="auditor" disabled class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione...</option>
                        <?php 
                        foreach($auditores as $a){
                          ?>
                            <option value="<?php echo $a["id"]; ?>"><?php echo $a["nombre_completo"]; ?></option>
                          <?php
                        }
                      ?>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-lg-7">  
                <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Observaciones </label>
                <input placeholder="Observaciones" type="text" name="observaciones"  id="observaciones" class="form-control form-control-sm" autocomplete="off"  />
                </div>
              </div>

            </div>
          </fieldset>

                       


          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Checklist </legend>
            <div class="contenedor_checklist"></div>
          </fieldset>
        </div>
      </div>
    </div>
    <?php echo form_close(); ?>
  </div>



 

