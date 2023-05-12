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

      var tabla_rop = $('#tabla_rop').DataTable({
         /*"sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',*/
         "iDisplayLength":-1, 
         "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
         "bPaginate": false,
         "aaSorting" : [[22,"desc"]],
         "scrollY": "65vh",
         "scrollX": true,
         "sAjaxDataProp": "result",        
         "bDeferRender": true,
         "select" : true,
          info:false,
          columnDefs: [
              { orderable: false, targets: 0 }
          ],
          "ajax": {
            "url":"<?php echo base_url();?>getRopList",
            "dataSrc": function (json) {
              $(".btn_filtro_rop").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
              $(".btn_filtro_rop").prop("disabled" , false);
              return json;
            },       
            data: function(param){
              param.estado = $("#estado_f").val();
              param.desde = $("#desde_f").val();
              param.hasta = $("#hasta_f").val();
              param.responsable = $("#responsable_f").val();
            }
          },    
          "columns": [
            {
              "class":"centered margen-td","data": function(row,type,val,meta){
                var color = row.id_estado === "0" ? "red" :
                  row.id_estado === "1" ? "#007BFF" :
                  row.id_estado === "2" ? "green" :
                  row.id_estado === "3" ? "black" :
                  "";
                
                btn =`<center><a  href="#!"   data-hash="${row.hash}"  title="Estado" class="btn_editar_rop" style="color:${color};font-size:12px!important;"><i class="fas fa-edit"></i> ${row.estado}</a>`;
              
                if(row.id_estado==="0" || row.id_estado==="1"){
                  btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar_rop rojo"><i class="fa fa-trash"></i></a></center>';
                }

                /* btn='<center><a href="#!" title="Modificar" data-hash="'+row.hash+'" class="btn_editar_rop"><i class="fa fa-edit"></i></a>'; */


                return btn;
              }
            },

            {
              "class":"centered margen-td","data": function(row,type,val,meta){
                botonera="";
                if(row.adjunto_requerimiento_1!=null && row.adjunto_requerimiento_1!=""){
                  botonera+='<center><a title="Ver archivo" style="margin-left:10px;" target="_blank" href="'+base+'archivos/rop/'+row.adjunto_requerimiento_1+'"><i class="fa fa-file verde"></i></a></center>';
                }else{
                  botonera="<center>-</center>";
                }
                return botonera;
              }
            },

            {
              "class":"centered margen-td","data": function(row,type,val,meta){
                botonera="";
                if(row.adjunto_respuesta_1!=null && row.adjunto_respuesta_1!=""){
                  botonera+='<center><a title="Ver archivo" style="margin-left:10px;" target="_blank" href="'+base+'archivos/rop/'+row.adjunto_respuesta_1+'"><i class="fa fa-file verde"></i></a></center>';
                }else{
                  botonera="<center>-</center>";
                }
                return botonera;
              }
            },
            { "data": "estado" ,"class":"margen-td centered"},
            { "data": "solicitante" ,"class":"margen-td centered"},
            { "data": "comuna" ,"class":"margen-td centered"},
            { "data": "fecha_ingreso" ,"class":"margen-td centered"},
            { "data": "hora_ingreso" ,"class":"margen-td centered"},
            { "data": "tipo" ,"class":"margen-td centered"},
            { "data": "requerimiento" ,"class":"margen-td centered"},
            { "data": "responsable1" ,"class":"margen-td centered"},
            { "data": "validador_sistema" ,"class":"margen-td centered"},
            { "data": "validador_real" ,"class":"margen-td centered"},
            { "data": "fecha_validacion" ,"class":"margen-td centered"},
            { "data": "hora_validacion" ,"class":"margen-td centered"},
            { "data": "usuario_asignado" ,"class":"margen-td centered"},
            { "data": "horas_estimadas" ,"class":"margen-td centered"},
            { "data": "horas_pendientes" ,"class":"margen-td centered"},
            { "data": "descripcion" ,"class":"margen-td centered"},
            { "data": "fecha_fin" ,"class":"margen-td centered"},
            { "data": "hora_fin" ,"class":"margen-td centered"},
            { "data": "observacion_fin" ,"class":"margen-td centered"},
            { "data": "ultima_actualizacion" ,"class":"margen-td centered"},
           /*  {
             "class":"centered margen-td","data": function(row,type,val,meta){
                if(row.titulo!="" && row.titulo!=null){
                   if(row.titulo.length > 60) {
                     str = row.titulo.substring(0,60)+"...";
                     return "<span class='btndesp2'>"+str+"</span><span title='Ver texto completo' class='ver_obs_desp' data-tit="+row.titulo.replace(/ /g,"_")+" data-title="+row.titulo.replace(/ /g,"_")+">Ver más</span>";
                   }else{
                     return "<span class='btndesp2' data-title="+row.titulo.replace(/ /g,"_")+">"+row.titulo+"</span>";
                  }
                }else{
                  return "-";
                }
              }
            },   */
          
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
        $('#formRop')[0].reset();
        $("#hash_rop").val("");
        $(".cont_edicion").hide();
        $('#modal_rop').modal('toggle'); 
        $("#formRop input,#formRop select,#formRop button,#formRop").prop("disabled", false);
        $(".btn_ingreso_rop").attr("disabled", false);
        $(".cierra_modal_rop").attr("disabled", false);

        $(".finalizado_cont").show()
        $(".guardar_cont").show()
        $(".requiere_validar_cont").hide()
        $(".finalizado_cont").hide()
        
    });

    $(document).off('submit', '#formRop').on('submit', '#formRop',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formRop");
        var formData = new FormData(formElement);
          $.ajax({
              url: $('#formRop').attr('action')+"?"+$.now(),  
              type: 'POST',
              data: formData,
              cache: false,
              processData: false,
              dataType: "json",
              contentType : false,
              beforeSend:function(){
                $(".btn_ingreso_rop").attr("disabled", true);
                $(".cierra_modal_rop").attr("disabled", true);
                $("#formRop input,#formRop select,#formRop button,#formRop").prop("disabled", true);
              },

              success: function (data) {
                if(data.res == "sess"){
                  window.location="unlogin";

                }else if(data.res=="ok"){
                
                  $("#formRop input,#formRop select,#formRop button,#formRop").prop("disabled", false);
                  $(".btn_ingreso_rop").attr("disabled", false);
                  $(".cierra_modal_rop").attr("disabled", false);

                  $.notify(data.msg, {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $('#formRop')[0].reset();
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
                  $("#formRop input,#formRop select,#formRop button,#formRop").prop("disabled", false);

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
        $('#formRop')[0].reset();
        $(".cont_edicion").show();
        $('#modal_rop').modal('toggle'); 
        $("#formRop input,#formRop select,#formRop button,#formRop").prop("disabled", true);
        $(".btn_ingreso_rop").attr("disabled", true);
        $(".cierra_modal_rop").attr("disabled", true);

        $.ajax({
          url: base+"getDataRop"+"?"+$.now(),  
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
                  $("#titulo").val(data.datos[dato].titulo);
                  $("#descripcion").val(data.datos[dato].descripcion);
                  $("#tipo option[value='"+data.datos[dato].id_tipo+"'").prop("selected", true);
                  $("#estado option[value='"+data.datos[dato].id_estado+"'").prop("selected", true);
                  $('#usuario_asignado').val(data.datos[dato].id_usuario_asignado).trigger('change');
                  $("#observacion").val(data.datos[dato].observacion);

                  $("#fecha_ingreso").val(data.datos[dato].fecha_hora_ingreso);
                  $("#responsable").val(data.datos[dato].responsable1);
                  $("#validador_sistema").val(data.datos[dato].validador_sistema);
                  $("#validador_real").val(data.datos[dato].validador_real);
                  $("#fecha_validacion").val(data.datos[dato].fecha_validacion);
                  $("#horas_estimadas").val(data.datos[dato].horas_estimadas);
                  $("#horas_pendiente").val(data.datos[dato].horas_pendientes);
                  $("#fecha_finalizado").val(data.datos[dato].fecha_hora_fin);
                  $("#observacion").val(data.datos[dato].observacion_fin);
                  /* $("#checkvalidar").val(data.datos[dato].titulo);
                  $("#checkfin").val(data.datos[dato].checkfin); */
                  $("#estado_str").text(data.datos[dato].estado);
                  
                  data.datos[dato].requiere_validacion == 1 ? $(".requiere_validar_cont").show() : $(".requiere_validar_cont").hide();
                
                  if(data.datos[dato].id_estado==2 || data.datos[dato].id_estado==3){
                     $(".requiere_validar_cont").hide()
                     $(".finalizado_cont").hide()
                     $(".guardar_cont").hide()
                  }else{
                    $(".guardar_cont").show()
                    $(".finalizado_cont").show()
                  }

                  $.getJSON(base + "listaRequerimientos" , {tipo:data.datos[dato].id_tipo},function(data) {
                      response = data;
                  }).done(function() {

                      $("#requerimiento").empty().select2('destroy');

                      $("#requerimiento").select2({
                        placeholder: 'Seleccione requerimiento',
                        data: response,
                        width: 'resolve',
                        allowClear:true,
                      });

                      $('#requerimiento').val(data.datos[dato].id_requerimiento).trigger('change');

                  });

              }

              $("#formRop input,#formRop select,#formRop button,#formRop").prop("disabled", false);
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


    $(document).off('change', '#tipo').on('change', '#tipo',function(event) {
       
      $.getJSON(base + "listaRequerimientos" , {tipo: $(this).val()},function(data) {
          response = data;
         
      }).done(function() {
          if(response!=""){
            $("#requerimiento").empty().select2('destroy');
            $("#requerimiento").select2({
              placeholder: 'Seleccione requerimiento',
              data: response,
              width: 'resolve',
              allowClear:true,
            });
          }else{
            /* $("#requerimiento").empty().select2('destroy'); */
            $("#requerimiento").empty()
            $('#requerimiento').val("").trigger('change');
          }
         
      });
      

    }); 

    $.getJSON(base + "listaRequerimientos" , function(data) {
	      response = data;
		}).done(function() {
		    $("#requerimiento").select2({
          placeholder: 'Seleccione requerimiento',
		       data: response,
		       width: 'resolve',
	         allowClear:true,
		    });
	  });

    $.getJSON(base + "listaPersonas" , function(data) {
	      response = data;
		}).done(function() {
		    $("#usuario_asignado").select2({
          placeholder: 'Seleccione persona',
		       data: response,
		       width: 'resolve',
	         allowClear:true,
		    });
	  });

    $.getJSON(base + "listaResponsables" , function(data) {
	      response = data;
		}).done(function() {
		    $("#responsable_f").select2({
          placeholder: 'Seleccione responsable',
		       data: response,
		       width: 'resolve',
	         allowClear:true,
		    });
	  });


    $(document).off('click', '.btn_excel_rop').on('click', '.btn_excel_rop',function(event) {
      event.preventDefault();
      const desde = $("#desde_f").val()
      const hasta = $("#hasta_f").val()
      let estado =$("#estado_f").val()
      let responsable =$("#responsable_f").val()

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


      const estado_f = estado === "" ? "-" : estado;
      const responsable_f  = responsable === "" ? "-" : responsable;

      window.location="excel_rop/"+desde+"/"+hasta+"/"+estado_f+"/"+responsable_f;
    });

    
    
  });
</script>
  

<!--FILTROS-->

  <div class="form-row">
	  <div class="col-1 col-lg-2"> 
	      <div class="form-group">
	         <button type="button" class="btn-block btn btn-sm btn-outline-primary btn_nuevo_rop btn_xr3">
	         <i class="fa fa-plus-circle"></i>  Nuevo 
	         </button>
	      </div>
	    </div>

      <div class="col-lg-3">
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

      <div class="col-lg-1">  
        <div class="form-group">
          <select id="estado_f" name="estado_f" class="custom-select custom-select-sm">
            <option value="" selected>Estado | Todos</option>
            <option value="0">Pendiente</option>
            <option value="1">Asignado</option>
            <option value="2">Finalizado</option>
            <option value="3">Cancelado</option>
          </select>
        </div>
      </div>

      <div class="col-lg-2">  
        <div class="form-group">
          <select id="responsable_f" name="responsable_f" style="width:100%!important;">
              <option value="">Seleccione Responsable | Todos</option>
          </select>
        </div>
      </div>


	    <div class="col-3">  
	      <div class="form-group">
	      <input type="text" placeholder="Ingrese su busqueda..." id="buscador" class="buscador form-control form-control-sm">
	      </div>
	    </div>

      <div class="col-6 col-lg-1">
        <div class="form-group">
          <button type="button" class="btn-block btn btn-sm btn-primary btn_excel_rop btn_xr3">
          <i class="fa fa-save"></i><span class="sr-only"></span> Excel
          </button>
        </div>
      </div>
 
	    <!-- <div class="col-2 col-lg-2">
	       <div class="form-group">
	          <button type="button" class="btn-block btn btn-sm btn-outline-primary btn-primary btn_filtro_rop btn_xr3">
	       <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
	       </button>
	       </div>
	    </div> -->
	</div>

<!--TABLA-->

  <div class="row">
    <div class="col-12">
      <table id="tabla_rop" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%!important">
          <thead>
            <tr>
              <th class="centered">Acciones</th> 
              <th class="centered">Archivo Req. </th>    
              <th class="centered">Archivo Resp.</th>    
              <th class="centered">Estado </th>    
              <th class="centered">Solicitante </th>    
              <th class="centered">Comuna </th>    
              <th class="centered">Fecha ingreso </th> 
              <th class="centered">Hora de ingreso </th> 
              <th class="centered">Tipo</th> 
              <th class="centered">Requerimiento </th> 
              <th class="centered">Responsable </th> 
              <th class="centered">Validador por sistema  </th> 
              <th class="centered">Validador real  </th> 
              <th class="centered">Fecha  Validación  </th> 
              <th class="centered">Hora Validación  </th> 
              <th class="centered">Persona asignada  </th> 
              <th class="centered">Horas estimadas  </th> 
              <th class="centered">Horas pendiente  </th> 
              <th class="centered">Detalle de requerimiento  </th> 
              <th class="centered">Fecha finalizado  </th> 
              <th class="centered">Hora Finalizado   </th> 
              <th class="centered">Observación de finalizado   </th> 
              <th class="centered">Última actualización</th> 
            </tr>
          </thead>
      </table>
    </div>
  </div>

<!--  NUEVO -->

    <div id="modal_rop"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
	    <div class="modal-dialog modal_rop">
	      <div class="modal-content">
	        <?php echo form_open_multipart("formRop",array("id"=>"formRop","class"=>"formRop"))?>
	           <input type="hidden" name="hash" id="hash_rop">
	           <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
	           <fieldset class="form-ing-cont">
              <legend class="form-ing-border">Solicitud de requerimiento</legend>
                <div class="form-row">
                  <div class="col-lg-4">  
                    <div class="form-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tipo </label>
                    <select id="tipo" name="tipo" class="custom-select custom-select-sm">
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

                  <div class="col-lg-4">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Requerimiento </label>
                      <select id="requerimiento" name="requerimiento" style="width:100%!important;">
                          <option value="">Seleccione Requerimiento | Todos</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-4"> 
                    <div class="form-group"> 
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Archivo requerimiento adj.1</label>
                      <input type="file" id="adjunto_req1" name="adjunto_req1">
                    </div>
                  </div>
  
                  <div class="col-lg-12">  
                    <div class="form-group">
                      <label for="">Descripción</label>
                      <input type="text" placeholder="descripcion" id="descripcion"  name="descripcion" class="form-control form-control-sm">
                      <!-- <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea> -->
                    </div>
                  </div> 
                </div>
              </fieldset>

              <div class="cont_edicion">
                <fieldset class="form-ing-cont">
                <legend class="form-ing-border">Respuesta del requerimiento</legend>
                <div class="form-row">

                  <div class="col-lg-3">  
                    <div class="form-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Estado (<span id="estado_str"></span>)</label>
                    <select id="estado" name="estado" class="custom-select custom-select-sm">
                      <option value="" selected>Estado </option>
                      <option value="3" >Cancelado </option>
                    </select>
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha ingreso </label>
                    <input type="text" placeholder="Fecha ingreso" id="fecha_ingreso"  readonly class="form-control form-control-sm">
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Responsable  </label>
                    <input type="text" placeholder="Responsable" id="responsable"  readonly class="form-control form-control-sm">
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Validador por sistema   </label>
                    <input type="text" placeholder="Validador por sistema" id="validador_sistema"readonly class="form-control form-control-sm">
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Validador real </label>
                    <input type="text" placeholder="Validador real" id="validador_real"  readonly class="form-control form-control-sm">
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha validación </label>
                    <input type="text" placeholder="Fecha validación" id="fecha_validacion" readonly class="form-control form-control-sm">
                    </div>
                  </div>
 
                  <div class="col-lg-3">  
                    <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Persona asignada </label>
                      <select id="usuario_asignado" name="usuario_asignado" style="width:100%!important;">
                          <option value="">Seleccione persona asignada</option>
                      </select>
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Horas estimadas  </label>
                    <input type="text" placeholder="Horas estimadas" id="horas_estimadas"  readonly class="form-control form-control-sm">
                    </div>
                  </div>

                  <div class="col-lg-3">  
                    <div class="form-group">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Horas pendiente  </label>
                    <input type="text" placeholder="Horas pendiente" id="horas_pendiente" readonly class="form-control form-control-sm">
                    </div>
                  </div>

                  <div class="col-lg-2">  
                    <div class="form-3">
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha finalizado </label>
                    <input type="text" placeholder="Fecha finalizado" id="fecha_finalizado" readonly class="form-control form-control-sm">
                    </div>
                  </div>

             
                  <div class="col-lg-3"> 
                    <div class="form-group"> 
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Archivo respuesta adj.1</label>
                      <input type="file" id="adjunto_respuesta" name="adjunto_respuesta">
                    </div>
                  </div>

                  <div class="col-lg-3"> 
                    <div class="form-group"> 
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Archivo requerimiento adj.2</label>
                      <input type="file" id="adjunto_req2" name="adjunto_req2">
                    </div>
                  </div>

                  <div class="col-lg-12">  
                    <div class="form-group">
                      <label for="">Observación de finalizado </label>
                      <input type="text" placeholder="observacion" id="observacion"  name="observacion" class="form-control form-control-sm">
                    </div>
                  </div>

                  </div>

                </div>

               </fieldset>

	            <br>

              <div class="col-lg-8 offset-lg-2">
                <div class="form-row justify-content-center">
                  
                  <div class="col-lg-2 requiere_validar_cont cont_edicion">
                    <div class="form-group">
                      <div class="form-check">
                        <input type="checkbox" name="checkvalidar" class="form-check-input" id="checkvalidar">
                        <label class="form-check-label" for="checkvalidar">Validar</label>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-2 finalizado_cont cont_edicion">
                    <div class="form-group">
                      <div class="form-check">
                        <input type="checkbox" name="checkfin" class="form-check-input" id="checkfin">
                        <label class="form-check-label" for="checkfin">Finalizar </label>
                      </div>
                    </div>
                  </div>

 
                  <div class="col-lg-2 guardar_cont">
                    <div class="form-group">
                      <button type="submit" class="btn-block btn btn-sm btn-primary btn_ingreso_rop">
                        <i class="fa fa-save"></i> Guardar
                      </button>
                    </div>
                  </div>

                  <div class="col-lg-2">
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
