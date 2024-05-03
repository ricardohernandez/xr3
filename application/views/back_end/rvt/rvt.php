<style type="text/css">
  .btn_eliminar_rop{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
  }
  .btn_editar_rop{
      display: inline;
      text-align: center!important;
      margin:0 auto!important;
      font-size: 15px!important;
  }

  @media(min-width: 768px){
    .modal_rop{
      width: 74%!important;
    }
  }

  @media(max-width: 768px){
    .modal_rop{
      width: 95%!important;
    }
  }

  .cont_edicion{
    display:none;
  }
</style>

<script type="text/javascript" charset="utf-8"> 
  $(function(){ 

    var desde="<?php echo $desde; ?>";
    var hasta="<?php echo $hasta; ?>";
    $("#desde_f").val(desde);
    $("#hasta_f").val(hasta);

    
    /*****DATATABLE*****/  
      const base = "<?php echo base_url() ?>";
      const p ="<?php echo $this->session->userdata('id_perfil'); ?>";
      const ide ="<?php echo $this->session->userdata('id'); ?>";

      var tabla_rop = $('#tabla_rop').DataTable({
         //"aaSorting" : [[22,"desc"]],
         "scrollY": "65vh",
         "responsive":false,
         "scrollX": true,
         "sAjaxDataProp": "result",        
         "bDeferRender": true,
         "select" : true,
          columnDefs: [
              { orderable: false, targets: 0 }
          ],
          "ajax": {
            "url":"<?php echo base_url();?>getRvtList",
            "dataSrc": function (json) {
              $(".btn_filtro_rop").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
              $(".btn_filtro_rop").prop("disabled" , false);
              return json;
            },       
            data: function(param){
              param.estado = $("#estado_f").val();
              param.desde = $("#desde_f").val();
              param.hasta = $("#hasta_f").val();
            }
          },    
          "columns": [
            {
              "class":"centered margen-td","data": function(row,type,val,meta){
                btn =`<center><a  href="#!"   data-hash="${row.hash}"  title="Estado" class="btn_editar_rop" style="color:#007BFF;font-size:12px!important;"><i class="fas fa-eye"></i> </a>`;
                return btn;
              }
            },
            { "data": "estado" ,"class":"margen-td centered"},
            { "data": "fecha_ingreso" ,"class":"margen-td centered"},
            { "data": "nombre_solicitante" ,"class":"margen-td centered"},
            { "data": "titular_nombre" ,"class":"margen-td centered"},
            { "data": "titular_rut" ,"class":"margen-td centered"},
            { "data": "titular_direccion" ,"class":"margen-td centered"},
            { "data": "titular_comuna" ,"class":"margen-td centered"},
            { "data": "titular_telefono1" ,"class":"margen-td centered"},
            { "data": "titular_telefono2" ,"class":"margen-td centered"},
            { "data": "marca" ,"class":"margen-td centered"},
            { "data": "pack" ,"class":"margen-td centered"},
            { "data": "observacion_solicitante" ,"class":"margen-td centered"},
            { "data": "nombre_responsable1" ,"class":"margen-td centered"},
            { "data": "nombre_responsable2" ,"class":"margen-td centered"},
            { "data": "fecha_respuesta" ,"class":"margen-td centered"},
            { "data": "numero_ot" ,"class":"margen-td centered"},
            { "data": "observacion_responsable" ,"class":"margen-td centered"},
            { "data": "ultima_actualizacion" ,"class":"margen-td centered"},
          ]
    }); 

    $(document).on('keyup paste', '#buscador', function() {
      tabla_rop.search($(this).val().trim()).draw();
    });

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    setTimeout( function () {
      var tabla_rop = $.fn.dataTable.fnTables(true);
      if ( tabla_rop.length > 0 ) {
          $(tabla_rop).dataTable().fnAdjustColumnSizing();
    }}, 200 ); 

    setTimeout( function () {
      var tabla_rop = $.fn.dataTable.fnTables(true);
      if ( tabla_rop.length > 0 ) {
          $(tabla_rop).dataTable().fnAdjustColumnSizing();
    }}, 2000 ); 

    setTimeout( function () {
      var tabla_rop = $.fn.dataTable.fnTables(true);
      if ( tabla_rop.length > 0 ) {
          $(tabla_rop).dataTable().fnAdjustColumnSizing();
      }
    }, 4000 ); 

    $(document).on('click', '.ver_obs_desp', function(event) {
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

    $(document).off('click', '.btn_filtro_rop').on('click', '.btn_filtro_rop',function(event) {
     event.preventDefault();
      $(this).prop("disabled" , true);
      $(".btn_filtro_rop").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
       tabla_rop.ajax.reload();
    });

    $(document).off('click', '.btn_nuevo_rop').on('click', '.btn_nuevo_rop', function(event) {
        $('#formRvt')[0].reset();
        $("#hash_rop").val("");
        $(".cont_edicion").hide();
        $('#modal_rop').modal('toggle'); 
        $("#formRvt input,#formRvt select,#formRvt button,#formRvt").prop("disabled", false);
        $(".btn_ingreso_rop").attr("disabled", false);
        $(".cierra_modal_rop").attr("disabled", false);
        $("#fecha_ingreso").prop("readonly", true);
        
        $("#tecnico").val(ide).trigger('change');
        $("#marca").val("").trigger('change');

        $("#estado").val("").trigger('change');
        $("#responsable1").val("").trigger('change');
        $("#responsable2").val("").trigger('change');

        $(".finalizado_cont").show()
        $(".guardar_cont").show()
        $(".respuesta_cont").hide()
        $(".cont_correo").show()
        $(".requiere_validar_cont").hide()
        $(".finalizado_cont").hide()

        $.getJSON(base + "listaPersonas" , function(data) {
            response = data;
        }).done(function() {
          if(response!=""){
            $("#tecnico #responsable1 #responsable2").empty().select2('destroy');

            var init = $('<option>', {
                value: '',
                text: 'Seleccione tecnico | Todos'
            });

            $('#tecnico #responsable1 #responsable2').append(init);

            $("#tecnico #responsable1 #responsable2").select2({
              placeholder: 'Seleccione tecnico',
              data: response,
              width: '100%',
              allowClear:true,
            });
          }else{
            $("#tecnico #responsable1 #responsable2").empty()
            $('#tecnico #responsable1 #responsable2').val("").trigger('change');
          }
      });
    });

    $(document).off('submit', '#formRvt').on('submit', '#formRvt',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formRvt");
        var formData = new FormData(formElement);
          $.ajax({
              url: $('#formRvt').attr('action')+"?"+$.now(),  
              type: 'POST',
              data: formData,
              cache: false,
              processData: false,
              dataType: "json",
              contentType : false,
              beforeSend:function(){
                $(".btn_ingreso_rop").attr("disabled", true);
                $(".cierra_modal_rop").attr("disabled", true);
                $("#formRvt input,#formRvt select,#formRvt button,#formRvt").prop("disabled", true);
              },

              success: function (data) {
                if(data.res == "sess"){
                  window.location="unlogin";

                }else if(data.res=="ok"){
                
                  $("#formRvt input,#formRvt select,#formRvt button,#formRvt").prop("disabled", false);
                  $(".btn_ingreso_rop").attr("disabled", false);
                  $(".cierra_modal_rop").attr("disabled", false);

                  $.notify(data.msg, {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $('#formRvt')[0].reset();
                  tabla_rop.ajax.reload(); 
                  $('#modal_rop').modal('toggle'); 
                

                }else if(data.res=="error"){

                  $(".btn_ingreso_rop").attr("disabled", false);
                  $(".cierra_modal_rop").attr("disabled", false);
                  $.notify(data.msg, {
                    className:'error',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                  $("#formRvt input,#formRvt select,#formRvt button,#formRvt").prop("disabled", false);

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
                        $('#modal_rop').modal("toggle");
                    }    
                    return;
                }

                if (xhr.status == 500) {
                    $.notify("Problemas en el servidor, intente más tarde.", {
                      className:'warn',
                      globalPosition: 'top right'
                    });
                    $('#modal_rop').modal("toggle");
                }

            },timeout:35000
          }); 
        return false; 
    });
 
    $(document).off('click', '.btn_editar_rop').on('click', '.btn_editar_rop',function(event) {
        event.preventDefault();
        $("#hash_rop").val("");
        hash=$(this).data("hash");
        $('#formRvt')[0].reset();
        $(".cont_edicion").show();
        $('#modal_rop').modal('toggle'); 
        $("#formRvt input,#formRvt select,#formRvt button,#formRvt").prop("disabled", true);
        $(".btn_ingreso_rop").attr("disabled", true);
        $(".cierra_modal_rop").attr("disabled", true);
        $(".guardar_cont").hide()
        $(".respuesta_cont").show()

        $.ajax({
          url: base+"getDataRvt"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          data:{hash:hash},
          dataType:"json",
          beforeSend:function(){
           $(".btn_ingreso_rop").prop("disabled",true); 
           $(".cierra_modal_rop").prop("disabled",true); 
          },
          success: function (data) {
            if(data.res=="ok"){
              for(dato in data.datos){
                  $("#hash_rop").val(data.datos[dato].hash);
                  $("#id_rop").val(data.datos[dato].id_rop);
                  $("#fecha_ingreso").val(data.datos[dato].fecha_ingreso);
                  $("#tecnico option[value='"+data.datos[dato].id_solicitante+"'").prop("selected", true).trigger('change');
                  $("#titular_nombre").val(data.datos[dato].titular_nombre);
                  $("#titular_rut").val(data.datos[dato].titular_rut);
                  $("#titular_direccion").val(data.datos[dato].titular_direccion);
                  $("#titular_comuna").val(data.datos[dato].titular_comuna);
                  $("#titular_telefono1").val(data.datos[dato].titular_telefono1);
                  $("#titular_telefono2").val(data.datos[dato].titular_telefono2);
                  $("#marca option[value='"+data.datos[dato].id_marca+"'").prop("selected", true).trigger('change');
                  $('#pack').val(data.datos[dato].pack).trigger('change');
                  $("#observacion_solicitante").val(data.datos[dato].observacion_solicitante);

                  $("#estado option[value='"+data.datos[dato].estado+"'").prop("selected", true).trigger('change');
                  $("#responsable1 option[value='"+data.datos[dato].id_responsable1+"'").prop("selected", true).trigger('change');
                  $("#responsable2 option[value='"+data.datos[dato].id_responsable2+"'").prop("selected", true).trigger('change');

                  $("#observacion_responsable").val(data.datos[dato].observacion_responsable);
                  $("#numero_ot").val(data.datos[dato].numero_ot);

              }

              $("#formRvt input,#formRvt select,#formRvt button,#formRvt").prop("disabled", true).trigger('change');
              $(".cierra_modal_rop").prop("disabled", false);
              $(".btn_ingreso_rop").prop("disabled", false);
            
            }else if(data.res == "sess"){
              window.location="../";
            }

            $(".btn_ingreso_rop").prop("disabled",false); 
            $(".cierra_modal_rop").prop("disabled",false); 
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
                $('#modal_rop').modal("toggle");
            }
        },timeout:35000
      }); 
    });

    $(document).off('click', '.btn_eliminar_rop').on('click', '.btn_eliminar_rop',function(event) {
        hash=$(this).data("hash");
        if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaRop'+"?"+$.now(),{"hash": hash}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
               tabla_rop.ajax.reload();
              }else{
                $.notify(data.msg, {
                  className:'danger',
                  globalPosition: 'top right'
                });
              }
            },"json");
          }
    });

    $(document).off('change', '#estado_f, #desde_f, #hasta_f, #responsable_f').on('change', '#estado_f, #desde_f, #hasta_f, #responsable_f', function(event) {
      tabla_rop.ajax.reload();
    });

    $.getJSON(base + "listaPersonas" , function(data) {
	      response = data;
		}).done(function() {
		    $("#responsable1").select2({
          placeholder: 'Seleccione persona',
		       data: response,
		       width: '100%',
	         allowClear:true,
		    });
		    $("#responsable2").select2({
          placeholder: 'Seleccione persona',
		       data: response,
		       width: '100%',
	         allowClear:true,
		    });

        $("#tecnico").select2({
           placeholder: 'Seleccione técnico',
		       data: response,
		       width: '100%',
	         allowClear:true,
		    });
	  });

    $.getJSON(base + "listaMarcasRvt" , function(data) {
	      response = data;
		}).done(function() {
		    $("#marca").select2({
          placeholder: 'Seleccione marca',
		       data: response,
		       width: '100%',
	         allowClear:true,
		    });
	  });

  });
</script>
  
<!--FILTROS-->

  <div class="form-row">
	  <div class="col-6  col-lg-1"> 
	      <div class="form-group">
	         <button type="button" class="btn-block btn btn-sm btn-primary btn_nuevo_rop btn_xr3">
	         <i class="fa fa-plus-circle"></i>  Nuevo 
	         </button>
	      </div>
	    </div>

      <div class="col-6 col-lg-1">  
        <div class="form-group">
          <select id="estado_f" name="estado_f" class="custom-select custom-select-sm">
            <option value="" selected>Estado | Todos</option>
            <option value="Venta ingresada">Venta ingresada</option>
            <option value="Infactible">Infactible</option>
            <option value="Sin contacto">Sin contacto</option>
            <option value="Rechazado">Rechazado</option>
          </select>
        </div>
      </div>

      <div class="col-12 col-lg-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="font-size:12px;margin-left:5px;"> Fecha ingreso<span></span> 
            </div>
            <input type="date" placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="desde_f" id="desde_f">
            <input type="date" placeholder="Hasta" class="fecha_normal form-control form-control-sm"  name="hasta_f" id="hasta_f">
          </div>
        </div>
      </div>

	    <div class="col-6 col-lg-3">  
	      <div class="form-group">
	      <input type="text" placeholder="Ingrese su busqueda..." id="buscador" class="buscador form-control form-control-sm">
	      </div>
	    </div>

	</div>

<!--TABLA-->

  <div class="row">
    <div class="col-12">
      <table id="tabla_rop" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%!important">
          <thead>
            <tr>
              <th class="centered">Acciones</th>  
              <th class="centered">Estado</th>  
              <th class="centered">Fecha ingreso</th>  
              <th class="centered">Nombre solicitante </th>  
              <th class="centered">Nombre titular</th>  
              <th class="centered">Rut titular</th>  
              <th class="centered">Dirección titular</th>  
              <th class="centered">Comuna titular</th>  
              <th class="centered">Teléfono titular 1</th>  
              <th class="centered">Teléfono titular 2</th>  
              <th class="centered">Marca</th>  
              <th class="centered">Pack</th>  
              <th class="centered">Observacion solicitante</th>  
              <th class="centered">Nombre de responsable 1</th>  
              <th class="centered">Nombre de responsable 2</th>  
              <th class="centered">Fecha respuesta</th>  
              <th class="centered">número ot</th>  
              <th class="centered">Observacion responsable</th>  
              <th class="centered">ultima actualización</th>  
            </tr>
          </thead>
      </table>
    </div>
  </div>

<!--  NUEVO -->

    <div id="modal_rop"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
	    <div class="modal-dialog modal_rop">
	      <div class="modal-content">
	        <?php echo form_open_multipart("formRvt",array("id"=>"formRvt","class"=>"formRvt"))?>
	           <input type="hidden" name="hash" id="hash_rop">
	           <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
	           <fieldset class="form-ing-cont">
              <legend class="form-ing-border">Solicitud de requerimiento</legend>
                <div class="form-row">

                  <div hidden class="col-lg-1">  
                    <div class="form-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">ID  </label>
                    <input type="text" placeholder="ID" id="id_rop"  readonly class="form-control form-control-sm">
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha</label>
                      <input type="date" id="fecha_ingreso" name="fecha_ingreso" class="form-control form-control-sm" style="width:100%!important;" value="<?php echo date("Y-m-d")?>">
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Técnico solicitante</label>
                      <select id="tecnico" name="tecnico" style="width:100%!important;">
                          <option value="">Seleccione técnico solicitante</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nombre titular </label>
                      <input type="text" placeholder="Ingrese nombre de titular." id="titular_nombre"  name="titular_nombre" class="form-control form-control-sm"> 
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Rut titular </label>
                      <input type="text" placeholder="Ingrese rut de titular." id="titular_rut"  name="titular_rut" class="form-control form-control-sm"> 
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Dirección titular </label>
                      <input type="text" placeholder="Ingrese dirección de titular." id="titular_direccion"  name="titular_direccion" class="form-control form-control-sm"> 
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Comuna titular </label>
                      <input type="text" placeholder="Ingrese comuna de titular." id="titular_comuna"  name="titular_comuna" class="form-control form-control-sm"> 
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Teléfono contacto 1</label>
                      <input type="text" placeholder="Ingrese teléfono de titular." id="titular_telefono1"  name="titular_telefono1" class="form-control form-control-sm"> 
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Teléfono contacto 2</label>
                      <input type="text" placeholder="Ingrese teléfono de titular." id="titular_telefono2"  name="titular_telefono2" class="form-control form-control-sm"> 
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Marca</label>
                      <select id="marca" name="marca" class="form-control form-control-sm" style="width:100%!important;">
                          <option selected value="">Seleccione marca</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Pack</label>
                      <select id="pack" name="pack" class="form-control form-control-sm" style="width:100%!important;">
                          <option selected value="">Seleccione pack</option>
                          <option value="1-play">1-Play</option>
                          <option value="2-play">2-Play</option>
                          <option value="3-play">3-Play</option>
                      </select>
                    </div>
                  </div>
  
                  <div class="col-lg-12">  
                    <div class="form-group">
                      <label for="">Observaciones</label>
                      <input type="text" placeholder="ingrese observaciones..." id="observacion_solicitante"  name="observacion_solicitante" class="form-control form-control-sm">
                    </div>
                  </div> 

                </div>
              </fieldset>

              <fieldset class="form-ing-cont respuesta_cont">
                <legend class="form-ing-border">Respuesta de solicitud</legend>

                <div class="form-row">

                    <div class="col-lg-3">  
                      <div class="form-group">
                        <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Estado</label>
                        <select id="estado" name="estado" class="form-control form-control-sm" style="width:100%!important;">
                          <option value="" selected>N/A</option>
                          <option value="Venta ingresada">Venta ingresada</option>
                          <option value="Infactible">Infactible</option>
                          <option value="Sin contacto">Sin contacto</option>
                          <option value="Rechazado">Rechazado</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-lg-3">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Responsable 1</label>
                      <select id="responsable1" name="responsable1" style="width:100%!important;">
                          <option value=""></option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Responsable 2</label>
                      <select id="responsable2" name="responsable2" style="width:100%!important;">
                          <option value=""></option>
                      </select>
                    </div>
                  </div>
      
                  <div class="col-lg-3">  
                    <div class="form-group">
                      <label for="">Número servicio</label>
                      <input type="text" placeholder="ingrese número servicio..." id="numero_ot"  name="numero_ot" class="form-control form-control-sm">
                    </div>
                  </div> 

                  <div class="col-lg-12">  
                    <div class="form-group">
                      <label for="">Observaciones</label>
                      <input type="text" placeholder="ingrese observaciones..." id="observacion_responsable"  name="observacion_responsable" class="form-control form-control-sm">
                    </div>
                  </div> 
                  
                </div>

              </fieldset>

              <div class="col-lg-12">
                <div class="form-row justify-content-center">
                 
                  <div class="col-6 col-lg-2 guardar_cont">
                    <div class="form-group">
                      <button type="submit" class="btn-block btn btn-sm btn-primary btn_ingreso_rop">
                        <i class="fa fa-save"></i> Guardar
                      </button>
                    </div>
                  </div>

                  <div class="col-6 col-lg-2">
                    <button class="btn-block btn btn-sm btn-dark cierra_modal_rop" data-dismiss="modal" aria-hidden="true">
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