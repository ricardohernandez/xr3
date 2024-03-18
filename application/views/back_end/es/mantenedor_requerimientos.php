<style type="text/css">
    .btn_eliminar_mant_req{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
  }
  .btn_editar_mant_req{
      display: inline;
      text-align: center!important;
      margin:0 auto!important;
      font-size: 15px!important;
  }

  @media(min-width: 768px){
    .modal_mant_req{
      width: 34%!important;
    }
  }

  @media(max-width: 768px){
    .modal_mant_req{
      width: 95%!important;
    }
  }
</style>

<script type="text/javascript">
  $(function(){
    var perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    var user="<?php echo $this->session->userdata('id'); ?>";
    const base = "<?php echo base_url() ?>";

    $(document).off('keydown', '.numbersOnly').on('keydown', '.numbersOnly',function(e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                return;
        }
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
      });

  /**** MANTENEDOR REQUERIMIENTOS ****/
    /*****DATATABLE*****/   
      var lista_mant_req = $('#lista_mant_req').DataTable({
        "aaSorting" : [[1,"desc"]],
        "scrollY": "65vh",
        "scrollX": true,
        "sAjaxDataProp": "result",        
        "bDeferRender": true,
        "select" : true,
        "responsive":false,
        // "columnDefs": [{ orderable: false, targets: 0 }  ],
        "ajax": {
            "url":"<?php echo base_url();?>getMantenedorReqList",
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
              "class":"centered margen-td","data": function(row,type,val,meta){
                btn =`<center><a  href="#!"   data-hash="${row.hash}"  title="Estado" class="btn_editar_mant_req" style="font-size:12px!important;"><i class="fas fa-edit"></i></a>`;
                btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar_mant_req rojo"><i class="fa fa-trash"></i></a></center>';
                return btn;
              }
            },
 
            { "data": "tipo" ,"class":"margen-td centered"},
            { "data": "requerimiento" ,"class":"margen-td centered"},
            { "data": "responsable1" ,"class":"margen-td centered"},
            { "data": "correo_responsable1" ,"class":"margen-td centered"},
            { "data": "responsable2" ,"class":"margen-td centered"},
            { "data": "correo_responsable2" ,"class":"margen-td centered"},
            { "data": "horas_estimadas" ,"class":"margen-td centered"},
            { "data": "requiere_validacion" ,"class":"margen-td centered"},
            { "data": "estado" ,"class":"margen-td centered"},
            { "data": "ultima_actualizacion" ,"class":"margen-td centered"},
          ]
        }); 
    

        $(document).on('keyup paste', '#buscador_mant_req', function() {
          lista_mant_req.search($(this).val().trim()).draw();
        });


        String.prototype.capitalize = function() {
            return this.charAt(0).toUpperCase() + this.slice(1);
        }

        setTimeout( function () {
          var lista_mant_req = $.fn.dataTable.fnTables(true);
          if ( lista_mant_req.length > 0 ) {
              $(lista_mant_req).dataTable().fnAdjustColumnSizing();
        }}, 200 ); 

        setTimeout( function () {
          var lista_mant_req = $.fn.dataTable.fnTables(true);
          if ( lista_mant_req.length > 0 ) {
              $(lista_mant_req).dataTable().fnAdjustColumnSizing();
        }}, 2000 ); 

        setTimeout( function () {
          var lista_mant_req = $.fn.dataTable.fnTables(true);
          if ( lista_mant_req.length > 0 ) {
              $(lista_mant_req).dataTable().fnAdjustColumnSizing();
          }
        }, 4000 ); 

        $.getJSON(base + "listaPersonas" , function(data) {
	       response = data;
        }).done(function() {
              $("#responsable1").select2({
                placeholder: 'Seleccione responsable1',
                data: response,
                width: 'resolve',
                allowClear:true,
              });

              $("#responsable2").select2({
                placeholder: 'Seleccione responsable2',
                data: response,
                width: 'resolve',
                allowClear:true,
              });

        });

    /*********INGRESO************/

      $(document).off('click', '.btn_nuevo_mant_req').on('click', '.btn_nuevo_mant_req', function(event) {
        $('#formMantReq')[0].reset();
        $("#hash_mant_req").val("");
        $('#modal_mant_req').modal('toggle'); 
        $("#formMantReq input,#formMantReq select,#formMantReq button,#formMantReq").prop("disabled", false);
        $(".btn_ingreso_mant_req").attr("disabled", false);
        $(".cierra_modal_mant_req").attr("disabled", false);
        $('#responsable1').val("").trigger('change');
        $('#responsable2').val("").trigger('change');
      });    

      $(document).off('submit', '#formMantReq').on('submit', '#formMantReq',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formMantReq");
        var formData = new FormData(formElement);
          $.ajax({
              url: $('#formMantReq').attr('action')+"?"+$.now(),  
              type: 'POST',
              data: formData,
              cache: false,
              processData: false,
              dataType: "json",
              contentType : false,
              beforeSend:function(){
                $(".btn_ingreso_mant_req").attr("disabled", true);
                $(".cierra_modal_mant_req").attr("disabled", true);
                $("#formMantReq input,#formMantReq select,#formMantReq button,#formMantReq").prop("disabled", true); 
              },

              success: function (data) {
                if(data.res == "sess"){
                  window.location="unlogin";

                }else if(data.res=="ok"){
                
                  $("#formMantReq input,#formMantReq select,#formMantReq button,#formMantReq").prop("disabled", false);
                  $(".btn_ingreso_mant_req").attr("disabled", false);
                  $(".cierra_modal_mant_req").attr("disabled", false);

                  $.notify(data.msg, {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $('#formMantReq')[0].reset();
                  lista_mant_req.ajax.reload(); 
                  $('#modal_mant_req').modal('toggle'); 
                

                }else if(data.res=="error"){

                  $(".btn_ingreso_mant_req").attr("disabled", false);
                  $(".cierra_modal_mant_req").attr("disabled", false);
                  $.notify(data.msg, {
                    className:'error',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                  $("#formMantReq input,#formMantReq select,#formMantReq button,#formMantReq").prop("disabled", false);

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
                        $('#modal_mant_req').modal("toggle");
                    }    
                    return;
                }

                if (xhr.status == 500) {
                    $.notify("Problemas en el servidor, intente más tarde.", {
                      className:'warn',
                      globalPosition: 'top right'
                    });
                    $('#modal_mant_req').modal("toggle");
                }

            },timeout:35000
          }); 
        return false; 
      });
 
      $(document).off('click', '.btn_editar_mant_req').on('click', '.btn_editar_mant_req',function(event) {
        event.preventDefault();
        $("#hash_mant_req").val("");
        hash=$(this).data("hash");
        $('#formMantReq')[0].reset();
        $('#modal_mant_req').modal('toggle'); 
        $("#formMantReq input,#formMantReq select,#formMantReq button,#formMantReq").prop("disabled", true);
        $(".btn_ingreso_mant_req").attr("disabled", true);
        $(".cierra_modal_mant_req").attr("disabled", true);
        $('#responsable1').val("").trigger('change');
        $('#responsable2').val("").trigger('change');
        

        $.ajax({
          url: base+"getDataMantReq"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          data:{hash:hash},
          dataType:"json",
          beforeSend:function(){
           $(".btn_ingreso_mant_req").prop("disabled",true); 
           $(".cierra_modal_mant_req").prop("disabled",true); 
          },
          success: function (data) {
            if(data.res=="ok"){
              for(dato in data.datos){

                  $("#hash_mant_req").val(data.datos[dato].hash);
                  $("#horas_estimadas").val(data.datos[dato].horas_estimadas);
                  $("#tipo_m option[value='"+data.datos[dato].id_tipo+"'").prop("selected", true);
                  $("#estado_m option[value='"+data.datos[dato].id_estado+"'").prop("selected", true);
                  $("#requerimiento").val(data.datos[dato].requerimiento);
                  $('#responsable1').val(data.datos[dato].id_responsable1).trigger('change');
                  $('#responsable2').val(data.datos[dato].id_responsable2).trigger('change');
                  
                  var valorRequiereValidacion = data.datos[dato].requiere_validacion;


                  if (valorRequiereValidacion == "No") {
                    $('#radioNo').prop('checked', true);
                  } else if (valorRequiereValidacion == "Si") {
                    $('#radioSi').prop('checked', true);
                  }

              }

              $("#formMantReq input,#formMantReq select,#formMantReq button,#formMantReq").prop("disabled", false);
              $(".cierra_modal_mant_req").prop("disabled", false);
              $(".btn_ingreso_mant_req").prop("disabled", false);
            
            }else if(data.res == "sess"){
              window.location="../";
            }

            $(".btn_ingreso_mant_req").prop("disabled",false); 
            $(".cierra_modal_mant_req").prop("disabled",false); 
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
                $('#modal_mant_req').modal("toggle");
            }
        },timeout:35000
        }); 
      });

      $(document).off('click', '.btn_eliminar_mant_req').on('click', '.btn_eliminar_mant_req',function(event) {
        hash=$(this).data("hash");
        if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaMantenedorReq'+"?"+$.now(),{"hash": hash}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
               lista_mant_req.ajax.reload();
              }else{
                $.notify(data.msg, {
                  className:'danger',
                  globalPosition: 'top right'
                });
              }
            },"json");
          }
      });

      $(document).off('change', '#estado_fm').on('change', '#estado_fm', function(event) {
        lista_mant_req.ajax.reload();
    });
      
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
          "url":"<?php echo base_url();?>getMantenedorReqTipoList",
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
          $('#formMantenedorReqTipo')[0].reset();
          $("#hash_tipo").val("");
          $("#formMantenedorReqTipo input,#formMantenedorReqTipo select,#formMantenedorReqTipo button,#formMantenedorReqTipo").prop("disabled", false);
      });     

      $(document).off('submit', '#formMantenedorReqTipo').on('submit', '#formMantenedorReqTipo',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formMantenedorReqTipo");
        var formData = new FormData(formElement);
          $.ajax({
              url: $('#formMantenedorReqTipo').attr('action')+"?"+$.now(),  
              type: 'POST',
              data: formData,
              cache: false,
              processData: false,
              dataType: "json",
              contentType : false,
              beforeSend:function(){
                $(".btn_guardar_tipo").attr("disabled", true);
                $(".cierra_modal_tipos").attr("disabled", true);
                $("#formMantenedorReqTipo input,#formMantenedorReqTipo select,#formMantenedorReqTipo button,#formMantenedorReqTipo").prop("disabled", true);
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

                  $("#formMantenedorReqTipo input,#formMantenedorReqTipo select,#formMantenedorReqTipo button,#formMantenedorReqTipo").prop("disabled", false);

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
              $("#formMantenedorReqTipo input,#formMantenedorReqTipo select,#formMantenedorReqTipo button,#formMantenedorReqTipo").prop("disabled", false);
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
        $('#formMantenedorReqTipo')[0].reset()
        $("#hash_tipo").val(hash)
        $('#modal_tipos').modal('toggle')
        $("#formMantenedorReqTipo input,#formMantenedorReqTipo select,#formMantenedorReqTipo button,#formMantenedorReqTipo").prop("disabled", true)
        $(".btn_guardar_tipo").attr("disabled", true)
        $(".cierra_modal").attr("disabled", true)

        $.ajax({
          url: base+"getDataMantReqTipo"+"?"+$.now(),  
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
            
              $("#formMantenedorReqTipo input,#formMantenedorReqTipo select,#formMantenedorReqTipo button,#formMantenedorReqTipo").prop("disabled", false);
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
          $.post('eliminaMantenedorReqTipo'+"?"+$.now(),{"hash": hash}, function(data) {

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
    <div class="col-lg-9">
      <div class="form-row">
        <div class="col-6 col-lg-1"> 
          <div class="form-group">
              <button type="button" class="btn-block btn btn-sm btn-primary btn_nuevo_mant_req btn_xr3">
              <i class="fa fa-plus-circle"></i>  Nuevo 
              </button>
          </div>
        </div>

        <div class="col-6 col-lg-1">  
          <div class="form-group">
            <select id="estado_fm" name="estado_fm" class="custom-select custom-select-sm">
              <option value="" >Estado | Todos</option>
              <option value="1" selected>Activo</option>
              <option value="0">No activo</option> 
            </select>
          </div>
        </div>
    
        <div class="col-6 col-lg-3">  
          <div class="form-group">
          <input type="text" placeholder="Ingrese su busqueda..." id="buscador_mant_req" class="buscador_mant_req form-control form-control-sm">
          </div>
        </div>

        <div class="col-6 col-lg-1">
          <div class="form-group">
            <button type="button" class="btn-block btn btn-sm btn-primary btn_excel_mant_req btn_xr3">
            <i class="fa fa-save"></i><span class="sr-only"></span> Excel
            </button>
          </div>
        </div>
      </div>

      <table id="lista_mant_req" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
          <thead>
            <tr>
            <th class="centered">Acciones </th>    
            <th class="centered">Tipo </th>    
						<th class="centered">Requerimiento </th>    
						<th class="centered">Responsable 1 </th>    
						<th class="centered">Correo responsable 1 </th>    
						<th class="centered">Responsable 2 </th> 
            <th class="centered">Correo responsable 2 </th>    
						<th class="centered">Horas estimadas</th> 
						<th class="centered">Requiere validación</th> 
						<th class="centered">Estado</th> 
						<th class="centered">&Uacute;ltima actualizaci&oacute;n</th> 
            </tr>
          </thead>
      </table>
    </div>
    <div class="col-lg-3">
      <center>
        <div class="col-lg-6">
          <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_tipo btn_xr3">
            <i class="fa fa-plus-circle"></i>  Nuevo tipo
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

    <div id="modal_mant_req"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
	    <div class="modal-dialog modal_mant_req">
	      <div class="modal-content">
	        <?php echo form_open_multipart("formMantenedorReq",array("id"=>"formMantReq","class"=>"formMantReq"))?>
	           <input type="hidden" name="hash" id="hash_mant_req">
	           <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
	           <fieldset class="form-ing-cont">
              <legend class="form-ing-border">Datos del requerimiento</legend>
                <div class="form-row">
                 
                  <div class="col-lg-12">  
                    <div class="form-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tipo </label>
                    <select id="tipo_m" name="tipo" class="custom-select custom-select-sm">
                      <option value="" selected>Seleccione Tipo</option>
                      <?php  
                      foreach($tipos as $t){
                        ?>
                            <option value="<?php echo $t["id"] ?>"><?php echo $t["tipo"] ?></option>

                        <?php
                      }
                      ?>
                    </select>
                    </div>
                  </div>

                  <div class="col-lg-12">  
                    <div class="form-group">
                      <label for="">Requerimiento</label>
                      <input type="text" placeholder="requerimiento" id="requerimiento"  name="requerimiento" class="form-control form-control-sm">
                    </div>
                  </div> 

                  <div class="col-lg-12">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Responsable 1 </label>
                      <select id="responsable1" name="responsable1" style="width:100%!important;">
                          <option value="">Seleccione responsable 1</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-12">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Responsable 2 </label>
                      <select id="responsable2" name="responsable2" style="width:100%!important;">
                          <option value="">Seleccione responsable 1</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-12">  
                    <div class="form-group">
                      <label for="">Horas estimadas</label>
                      <input type="text" placeholder="Horas estimadas" id="horas_estimadas"  name="horas_estimadas" class="numbersOnly form-control form-control-sm">
                    </div>
                  </div> 

                  <div class="col-lg-12">  
                    <div class="form-group">
                    <label for="">Requiere validación</label>

                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="requiere_validacion" id="radioSi" value="1">
                        <label class="form-check-label" for="radioSi">
                          Sí
                        </label>
                      </div>

                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="requiere_validacion" id="radioNo" value="0">
                        <label class="form-check-label" for="radioNo">
                          No
                        </label>
                      </div>

                    </div>
                  </div>
                  
                  <div class="col-lg-12">  
                    <div class="form-group">
                      <label for="">Estado </label>
                      <select id="estado_m" name="estado" class="custom-select custom-select-sm">
                        <option value="1" selected>Activo</option>
                        <option value="0">No activo</option> 
                      </select>
                    </div>
                  </div>

                </div>
              </fieldset>

	            <br>

              <div class="col-lg-8 offset-lg-2">
                <div class="form-row justify-content-center">
 
                  <div class="col-lg-6 guardar_cont">
                    <div class="form-group">
                      <button type="submit" class="btn-block btn btn-sm btn-primary btn_ingreso_mant_req">
                        <i class="fa fa-save"></i> Guardar
                      </button>
                    </div>
                  </div>

                  <div class="col-lg-6">
                    <button class="btn-block btn btn-sm btn-dark cierra_modal_mant_req" data-dismiss="modal" aria-hidden="true">
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

  <div id="modal_tipos" data-backdrop="static"  data-keyboard="false"   class="modal fade">
   <?php echo form_open_multipart("formMantenedorReqTipo",array("id"=>"formMantenedorReqTipo","class"=>"formMantenedorReqTipo"))?>

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