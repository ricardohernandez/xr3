<style type="text/css">
  .rojo{
    color: red;
  }

  .grey{
    background-color: grey;
    color: #fff;
  }

  .btn_archivo{
    display: block;
    text-align: center!important;
    margin:0 auto!important;
    font-size: 14px!important;
  }

  @media(min-width: 768px){
    .btn_eliminar{
      font-size: 14px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
    }

    .btn_editar{
      text-align: center!important;
      font-size: 14px!important;
    }
    .modal_IA{
      width: 40%!important;
    }
    .table_head{
      font-size: 12px!important;
    }

  }
  @media(max-width: 768px){
    .btn_eliminar{
      font-size: 14px!important;
      color:#CD2D00;
      margin-left: 20px;
      text-decoration: none!important;
    }
    .btn_editar{
      display: block;
      text-align: center!important;
      font-size: 14px!important;
    }

    .modal_IA{
      width: 94%!important;
    }
    .table_head{
      font-size: 11px!important;
    }
  }

  .dataTables_paginate .paginate_button {
    margin-top: 20px!important;
    padding: 5px 11px!important;
    line-height: 1.42857143;
    text-decoration: none;
    font-size: 14px;
    color: #ffffff;
    background-color: #32477C!important;
    border: 1px solid transparent;
    margin-left: -1px;
    cursor: pointer;
  }

  div.dataTables_wrapper div.dataTables_info {
    padding-top: 0.1em!important;
    white-space: nowrap;
  }
  

  .select2-container--default .select2-selection--single {
      border: 1px solid #ced4da!important;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
      font-size:12px!important;
  }


</style>


<script type="text/javascript" charset="utf-8"> 
  $(function(){ 

    /*****DATATABLE*****/  
      const base = "<?php echo base_url() ?>";
      const p ="<?php echo $this->session->userdata('id_perfil'); ?>";
      console.log(p);

      var tabla_IA = $('#tabla_IA').DataTable({
        /*"sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',*/
        "iDisplayLength":-1, 
        "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
        "bPaginate": false,
        "aaSorting" : [[8,"desc"]],
        "scrollY": "60vh",
        "scrollX": true,
        "sAjaxDataProp": "result",        
        "bDeferRender": true,
        "select" : true,
        info:false,
        columnDefs: [
          { orderable: false, targets: 0 }
        ],
        "ajax": {
          "url":"<?php echo base_url();?>getInvestigacionesList",
          "dataSrc": function (json) {
            $(".btn_filtro_liquidacion").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_liquidacion").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            if(p==4){
              var trabajador = $("#trabajador_t").val();
            }else{
              var trabajador = $("#trabajadores_t").val();
            }
            var jefe = $("#jefe_t").val();
            var periodo = $('#Periodo_f').val();
            param.trabajador = trabajador;
            param.jefe = jefe;
            param.periodo = periodo;
          }
        },    
        "columns": [
          {
            "class":"centered margen-td","width" : "30px","data": function(row,type,val,meta){
              btn = "";
              if(p<=3){
                btn  =`<center><a  href="#!"   data-hash="${row.hash}"  title="Estado" class="btn_editar" style="font-size:14px!important;"><i class="fas fa-edit"></i> </a>`;
                btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar rojo"><i class="fa fa-trash"></i></a></center>';
              }
              return btn;
            }
          },
          {
            "class":"centered margen-td","data": function(row,type,val,meta){
              btn  =`<a  target="_blank" href="<?php echo base_url() ?>${row.archivo}" title="Archivo" class="btn_archivo"><i class="fas fa-file"></i> </a>`;
              return btn;
            }
          },
          { "data": "tipo" ,"class":"margen-td centered"},
          { "data": "fecha" ,"class":"margen-td centered"},
          { "data": "hora" ,"class":"margen-td centered"},
          { "data": "lugar" ,"class":"margen-td centered"},
          { "data": "direccion" ,"class":"margen-td centered"},
          { "data": "comuna" ,"class":"margen-td centered"},
          { "data": "nombre_informante" ,"class":"margen-td centered"},
          { "data": "cargo_informante" ,"class":"margen-td centered"},
          { "class":"margen-td centered" , "data": function(row,type,val,meta){
            if(row.descripcion!="" && row.descripcion!=null){
                   if(row.descripcion.length > 60) {
                     str = row.descripcion.substring(0,60)+"...";
                     return "<span class='btndesp2'>"+str+"</span><span title='Ver texto completo' class='ver_obs_desp' data-tit="+row.descripcion.replace(/ /g,"_")+" data-title="+row.descripcion.replace(/ /g,"_")+">Ver más</span>";
                   }else{
                     return "<span class='btndesp2' data-title="+row.descripcion.replace(/ /g,"_")+">"+row.descripcion+"</span>";
                  }
                }else{
                  return "-";
                }
            } 
          },
          { "data": "nombre_afectado" ,"class":"margen-td centered"},
          { "data": "rut_afectado" ,"class":"margen-td centered"},
          { "data": "cargo_afectado" ,"class":"margen-td centered"},
          { "data": "horas_trabajadas" ,"class":"margen-td centered"},
          { "data": "gravedad_lesion" ,"class":"margen-td centered"},
          { "data": "tipo_lesion" ,"class":"margen-td centered"},
          { "data": "testigo1" ,"class":"margen-td centered"},
          { "data": "testigo2" ,"class":"margen-td centered"},
          { "data": "testigo3" ,"class":"margen-td centered"},
          { "class":"margen-td centered" , "data": function(row,type,val,meta){
            if(row.observacion!="" && row.observacion!=null){
                   if(row.observacion.length > 60) {
                     str = row.observacion.substring(0,60)+"...";
                     return "<span class='btndesp2'>"+str+"</span><span title='Ver texto completo' class='ver_obs_desp' data-tit="+row.observacion.replace(/ /g,"_")+" data-title="+row.descripcion.replace(/ /g,"_")+">Ver más</span>";
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
      tabla_IA.search($(this).val().trim()).draw();
    });

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    setTimeout( function () {
      var tabla_IA = $.fn.dataTable.fnTables(true);
      if ( tabla_IA.length > 0 ) {
          $(tabla_IA).dataTable().fnAdjustColumnSizing();
    }}, 200 ); 

    setTimeout( function () {
      var tabla_IA = $.fn.dataTable.fnTables(true);
      if ( tabla_IA.length > 0 ) {
          $(tabla_IA).dataTable().fnAdjustColumnSizing();
    }}, 2000 ); 

    setTimeout( function () {
      var tabla_IA = $.fn.dataTable.fnTables(true);
      if ( tabla_IA.length > 0 ) {
          $(tabla_IA).dataTable().fnAdjustColumnSizing();
      }
    }, 4000 ); 
 
    $(document).off('click', '.btn_filtro_liquidacion').on('click', '.btn_filtro_liquidacion',function(event) {
      event.preventDefault();
      $(this).prop("disabled" , true);
      $(".btn_filtro_liquidacion").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
      tabla_IA.ajax.reload();
    });

    $(document).off('click', '.btn_nueva_liquidacion').on('click', '.btn_nueva_liquidacion', function(event) {
        $('#formInvestigaciones')[0].reset();
        $("#hash_liqui").val("");
        $('#modal_IA').modal('toggle'); 
        $("#formInvestigaciones input,#formInvestigaciones select,#formInvestigaciones button,#formInvestigaciones").prop("disabled", false);
        $(".btn_ingreso").attr("disabled", false);
        $(".cierra_modal").attr("disabled", false);
        $("#periodo").val(new Date().getFullYear() + '-' + ((new Date().getMonth()+1) < 10 ? '0' : '') + (new Date().getMonth()+1))
       
    });

    $(document).off('submit', '#formInvestigaciones').on('submit', '#formInvestigaciones',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formInvestigaciones");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formInvestigaciones').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_ingreso").attr("disabled", true);
              $(".cierra_modal").attr("disabled", true);
              $("#formInvestigaciones input,#formInvestigaciones select,#formInvestigaciones button,#formInvestigaciones").prop("disabled", true);
            },
            success: function (data) {

              if(data.res == "sess"){
                window.location="unlogin";

              }else if(data.res=="ok"){

                $('#modal_IA').modal('toggle'); 
                $("#formInvestigaciones input,#formInvestigaciones select,#formInvestigaciones button,#formInvestigaciones").prop("disabled", false);
                $(".btn_ingreso").attr("disabled", false);
                $(".cierra_modal").attr("disabled", false);

                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $('#formInvestigaciones')[0].reset();
                tabla_IA.ajax.reload();

              }else if(data.res=="error"){
                    
                $(".btn_ingreso").attr("disabled", false);
                $(".cierra_modal").attr("disabled", false);
                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });
                $("#formInvestigaciones input,#formInvestigaciones select,#formInvestigaciones button,#formInvestigaciones").prop("disabled", false);

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
                    /*  $('#modal_IA').modal("toggle"); */
                }    
                return;
              }
              if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                /* $('#modal_IA').modal("toggle"); */
              }
          },timeout:35000
        }); 
      return false; 
    })

    $(document).off('click', '.btn_editar').on('click', '.btn_editar',function(event) {
      event.preventDefault();
      $("#hash_liqui").val("")
      hash=$(this).data("hash")
      $('#formInvestigaciones')[0].reset()
      $("#hash_liqui").val(hash)
      $('#modal_IA').modal('toggle')
      $("#formInvestigaciones input,#formInvestigaciones select,#formInvestigaciones button,#formInvestigaciones").prop("disabled", true)
      $(".btn_ingreso").attr("disabled", true)
      $(".cierra_modal").attr("disabled", true)

      $.ajax({
        url: base+"getDataInvestigacion"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash:hash},
        dataType:"json",
        beforeSend:function(){
          $(".btn_ingreso").prop("disabled",true); 
          $(".cierra_modal").prop("disabled",true); 
        },
        success: function (data) {

          if(data.res=="ok"){

            for(dato in data.datos){

              $("#hash_liqui").val(data.datos[dato].hash);
              $("#fecha").val(data.datos[dato].fecha);
              $("#hora").val(data.datos[dato].hora);
              $('#tipo').val(data.datos[dato].tipo).trigger('change');
              $('#lugar').val(data.datos[dato].lugar).trigger('change');
              $("#direccion").val(data.datos[dato].direccion);
              $('#comuna').val(data.datos[dato].comuna).trigger('change');
              $('#nombre_informante').val(data.datos[dato].nombre_informante).trigger('change');
              $('#cargo_informante').val(data.datos[dato].cargo_informante).trigger('change');
              $("#descripcion").val(data.datos[dato].descripcion);
              $('#nombre_afectado').val(data.datos[dato].nombre_afectado).trigger('change');
              $('#cargo_afectado').val(data.datos[dato].cargo_afectado).trigger('change');
              $("#rut_afectado").val(data.datos[dato].rut_afectado);
              $('#horas_trabajadas').val(data.datos[dato].horas_trabajadas).trigger('change');
              $('#gravedad_lesion').val(data.datos[dato].gravedad_lesion).trigger('change');
              $('#tipo_lesion').val(data.datos[dato].tipo_lesion).trigger('change');
              $("#nombre_testigo_1").val(data.datos[dato].nombre_testigo_1);
              $("#relacion_testigo_1").val(data.datos[dato].relacion_testigo_1);
              $("#nombre_testigo_2").val(data.datos[dato].nombre_testigo_2);
              $("#relacion_testigo_2").val(data.datos[dato].relacion_testigo_2);
              $("#nombre_testigo_3").val(data.datos[dato].nombre_testigo_3);
              $("#relacion_testigo_3").val(data.datos[dato].relacion_testigo_3);
              $("#observacion").val(data.datos[dato].observacion);
            }
          
            $("#formInvestigaciones input,#formInvestigaciones select,#formInvestigaciones button,#formInvestigaciones").prop("disabled", false);
            $(".cierra_modal").prop("disabled", false);
            $(".btn_ingreso").prop("disabled", false);

          }else if(data.res == "sess"){
            window.location="../";
          }

          $(".btn_ingreso").prop("disabled",false); 
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
              $('#modal_IA').modal("toggle");
          }
        } , timeout:35000
      }) 
    })
    
    $(document).off('click', '.btn_eliminar').on('click', '.btn_eliminar',function(event) {
      hash=$(this).data("hash");
      if(confirm("¿Esta seguro que desea eliminar este registro?")){
        $.post('<?php echo base_url();?>eliminaInvestigacion'+"?"+$.now(),{"hash": hash}, function(data) {

          if(data.res=="ok"){
            $.notify(data.msg, {
              className:'success',
              globalPosition: 'top right'
            })
            tabla_IA.ajax.reload();

          }else{
            $.notify(data.msg, {
              className:'danger',
              globalPosition: 'top right'
            })
          }
        },"json")
      }
    })

    $.getJSON(base + "listaTrabajadoresFiltros", { jefe : $("#jefe_t").val() } , function(data) {
      response = data

      }).done(function() {
         $("#trabajadores_t").select2({
          placeholder: 'Seleccione Trabajador | Todos',
          data: response,
          width: 'resolve',
          allowClear:true,
        })
        $("#trabajador").select2({
          placeholder: 'Seleccione Trabajador | Todos',
          data: response,
          width: 'resolve',
          allowClear:true,
        })
    })

  
    
    $(document).off('change', '#jefe_t').on('change', '#jefe_t',function(event) {
      $.getJSON(base + "listaTrabajadoresFiltros", { 'jefe' : $(this).val() } , function(data) {
      response = data

      }).done(function() {

        $("#trabajadores_t").empty()
        $("#trabajadores_t").trigger('change')

        $("#trabajadores_t").select2({
          placeholder: 'Seleccione Trabajador | Todos',
          data: response,
          width: 'resolve',
          allowClear:true,
        })

        $("#trabajador").select2({
          placeholder: 'Seleccione Trabajador | Todos',
          data: response,
          width: 'resolve',
          allowClear:true,
        })

        tabla_IA.ajax.reload()
      })
    }) 

    $(document).off('change', '#trabajadores_t , #Periodo_f').on('change', '#trabajadores_t, #Periodo_f',function(event) {
      tabla_IA.ajax.reload()
    }) 

    $(document).off('change', '#nombre_informante').on('change', '#nombre_informante',function(event) {
      var trabajadores = <?php echo json_encode($trabajadores); ?>;
      trabajadores.forEach(trabajador => {
            if (trabajador.id === document.getElementById("nombre_informante").value) {
              document.getElementById("cargo_informante").value = trabajador.id_cargo;
            }
        });
    }) 

    $(document).off('change', '#nombre_afectado').on('change', '#nombre_afectado',function(event) {
      var trabajadores = <?php echo json_encode($trabajadores); ?>;
      trabajadores.forEach(trabajador => {
            if (trabajador.id === document.getElementById("nombre_afectado").value) {
              document.getElementById("rut_afectado").value = trabajador.rut_format;
              document.getElementById("cargo_afectado").value = trabajador.id_cargo; 
            }
        });
    }) 

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



  })
</script>
  
<!--FILTROS-->

  <div class="form-row">

    <div class="col-1 col-lg-1"> 
      <div class="form-group">
        <button type="button" class="btn-block btn btn-sm btn-outline-primary btn_nueva_liquidacion btn_xr3">
        <i class="fa fa-plus-circle"></i>  Nuevo 
        </button>
      </div>
    </div>

    <div class="col-2 col-lg-4">  
      <div class="form-group">
      <input type="text" placeholder="Ingrese su busqueda..." id="buscador" class="buscador form-control form-control-sm">
      </div>
    </div>

	</div>

  <div class="row">
    <div class="col-12">
      <table id="tabla_IA" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%!important">
          <thead>
            <tr>
              <th class="centered">Acciones</th>
              <th class="centered">Archivo</th>  
              <th class="centered">Tipo</th> 
              <th class="centered">Fecha</th>    
              <th class="centered">Hora</th>  
              <th class="centered">Lugar tipo</th>  
              <th class="centered">Lugar dirección</th>
              <th class="centered">Lugar comuna</th>    
              <th class="centered">Nombre informante</th>  
              <th class="centered">Cargo informante</th>   
              <th class="centered">Relato del accidente</th>
              <th class="centered">Nombre afectado</th>
              <th class="centered">Rut afectado</th> 
              <th class="centered">Cargo afectado</th>  
              <th class="centered">Hrs. trab.</th> 
              <th class="centered">Gravedad afectado</th>   
              <th class="centered">Tipo lesion</th>    
              <th class="centered">Testigo 1</th>    
              <th class="centered">Testigo 2</th>   
              <th class="centered">Testigo 3</th>      
              <th class="centered">Observaciones</th>    
            </tr>
          </thead>
      </table>
    </div>
  </div>

<!--  NUEVO -->

  <div id="modal_IA"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
    <div class="modal-dialog modal_IA">
      <div class="modal-content">
        <?php echo form_open_multipart("formInvestigaciones",array("id"=>"formInvestigaciones","class"=>"formInvestigaciones"))?>
          <input type="hidden" name="hash_liqui" id="hash_liqui">

          <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Datos de ingreso</legend>
          <div class="form-row">

            <div class="col-lg-6">  
              <div class="form-group">
              <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha</label>
              <input type="date"  placeholder="fecha" class="form-control form-control-sm"  name="fecha" id="fecha">
              </div>
            </div>

            <div class="col-lg-6">  
              <div class="form-group">
              <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Hora</label>
              <input type="time"  placeholder="hora" class="form-control form-control-sm"  name="hora" id="hora">
              </div>
            </div>

            <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tipo</label>
                  <select id="tipo" name="tipo" class="form-control form-control-sm" style="width:100%!important;">
                      <option selected value="">Seleccione tipo de accidente</option>
                      <option value="Incidente">Incidente</option>
                      <option value="Accidente laboral">Accidente laboral</option>
                      <option value="Accidente trayecto">Accidente trayecto</option>
                      <option value="Enfermedad profesional">Enfermedad profesional</option>
                  </select>
                </div>
            </div>

            <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Lugar</label>
                  <select id="lugar" name="lugar" class="form-control form-control-sm" style="width:100%!important;">
                      <option selected value="">Indique lugar de accidente</option>
                      <option value="Transp. emp.">Transp. emp.</option>
                      <option value="Trasp. part.">Trasp. part.</option>
                      <option value="Trasp. publ.">Trasp. publ.</option>
                      <option value="Dom. empresa">Dom. empresa</option>
                      <option value="Via publica">Via publica</option>
                      <option value="Dom. part.">Dom. part.</option>
                      <option value="Dom. Cliente">Dom. Cliente</option>
                  </select>
                </div>
            </div>

            <div class="col-lg-6">  
              <div class="form-group">
              <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Dirección del incidente o accidente</label>
              <input type="text"  placeholder="proporcione dirección del accidente " class="form-control form-control-sm"  name="direccion" id="direccion">
              </div>
            </div>

            <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Comuna</label>
                  <select id="comuna" name="comuna" class="form-control form-control-sm" style="width:100%!important;">
                  <option selected value="">Seleccione comuna</option>
                    <?php 
                      foreach($comunas as $key){
                      ?>
                        <option value="<?php echo $key["id"] ?>"><?php echo $key["titulo"] ?></option>
                      <?php
                      }
                    ?>
                  </select>
                </div>
            </div>

            <div class="col-lg-6">  
              <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nombre del informante</label>
                <select id="nombre_informante" class="form-control form-control-sm" name="nombre_informante" style="width:100%!important;">
                    <option value="">Seleccione Trabajador</option>
                    <?php 
                      foreach($trabajadores as $key){
                      ?>
                        <option value="<?php echo $key["id"] ?>"><?php echo $key["nombre_completo"] ?></option>
                      <?php
                      }
                    ?>
                </select>
              </div>
            </div>

            <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Cargo del informante</label>
                  <select disabled id="cargo_informante" class="form-control form-control-sm" name="cargo_informante" style="width:100%!important;">
                      <option selected value="">Seleccione cargo</option>
                      <?php 
                      foreach($cargos as $key){
                      ?>
                        <option value="<?php echo $key["id"] ?>"><?php echo $key["cargo"] ?></option>
                      <?php
                      }
                      ?>
                  </select>
                </div>
            </div>

            <div class="col-lg-12">  
              <div class="form-group">
              <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Descripción del incidente o accidente</label>
              <input type="text"  placeholder="Proporcione una descripción detallada de lo sucedido, incluyendo la fecha, hora, lugar y cualquier otra información relevante." class="form-control form-control-sm"  name="descripcion" id="descripcion">
              </div>
            </div>

          </div>
          </fieldset><br>

          <fieldset>
            <legend class="form-ing-border">Datos del técnico involucrado</legend>
            <div class="form-row">

              <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nombre del afectado</label>
                  <select id="nombre_afectado" class="form-control form-control-sm" name="nombre_afectado" style="width:100%!important;">
                      <option value="">Seleccione Trabajador</option>
                      <?php 
                        foreach($trabajadores as $key){
                        ?>
                          <option value="<?php echo $key["id"] ?>"><?php echo $key["nombre_completo"] ?></option>
                        <?php
                        }
                      ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Cargo del afectado</label>
                  <select id="cargo_afectado" class="form-control form-control-sm" name="cargo_afectado" style="width:100%!important;">
                      <option value="">Seleccione cargo</option>
                      <?php 
                      foreach($cargos as $key){
                      ?>
                        <option value="<?php echo $key["id"] ?>"><?php echo $key["cargo"] ?></option>
                      <?php
                      }
                      ?>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Rut del técnico afectado</label>
                  <input readonly type="text"  placeholder="ingrese rut del técnico" class="form-control form-control-sm"  id="rut_afectado" name="rut_afectado">
                </div>
              </div>

              <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Horas trabajadas</label>
                  <select id="horas_trabajadas" class="form-control form-control-sm" name="horas_trabajadas" style="width:100%!important;">
                      <option value="">Seleccione horas trabajadas</option>
                      <option value="0">0</option>
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                      <option value="4">4</option>
                      <option value="5">5</option>
                      <option value="6">6</option>
                      <option value="7">7</option>
                      <option value="8">8</option>
                      <option value="9">9</option>
                      <option value="10">10</option>
                      <option value="11">11</option>
                      <option value="12">12</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Gravedad de lesion </label>
                  <select id="gravedad_lesion" class="form-control form-control-sm" name="gravedad_lesion" style="width:100%!important;">
                      <option value="">Seleccione gravedad de lesion</option>
                      <option value="Sin lesión">Sin lesión</option>
                      <option value="Menor">Menor </option>
                      <option value="Moderada">Moderada </option>
                      <option value="Grave">Grave</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">  
                <div class="form-group">
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tipo de lesion</label>
                  <select id="tipo_lesion" class="form-control form-control-sm" name="tipo_lesion" style="width:100%!important;">
                      <option value="">Seleccione tipo de lesion</option>
                      <option value="Sin lesión">Sin lesión</option>
                      <option value="Rasguño/corte menor">Rasguño/corte menor</option>
                      <option value="Contusión">Contusión</option>
                      <option value="Torcedura">Torcedura</option>
                      <option value="Fractura">Fractura</option>
                      <option value="Quemadura">Quemadura</option>
                      <option value="Lesión muscular">Lesión muscular</option>
                      <option value="Amputación">Amputación</option>
                      <option value="Lesion interna">Lesion interna</option>
                      <option value="Hemorragia">Hemorragia</option>
                      <option value="Lesión organos">Lesión organos</option>
                      <option value="Esguince">Esguince</option>
                      <option value="Luxación">Luxación</option>
                  </select>
                </div>
              </div>

            </div>
          </fieldset><br>

          <fieldset>
            <legend class="form-ing-border">Testigos</legend>
            <div class="form-row">
              <table id="tabla_testigos" width="100%" class="dataTable table-striped  datatable_h table table-hover table-bordered table-condensed">
                <thead>
                  <tr style="background-color:#F9F9F9">
                      <th class="table_head desktop tablet">Testigos</th>
                      <th class="table_head all">Nombre</th>
                      <th class="table_head all">Relaci&oacute;n</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    for ($i = 1; $i <= 3; $i++):
                    ?>
                    <tr>
                        <td><p class="table_text">Testigo <?php echo $i ?></p></td>

                        <td><p class="table_text">
                            <p class="table_text">
                              <input type="text" name="nombre_testigo_<?php echo $i ?>" id="nombre_testigo_<?php echo $i ?>" placeholder="" size="50" maxlength="50" class="observacion form-control input-xs full-w" autocomplete="off">
                            </p>
                          </td>
                          
                          <td><p class="table_text">
                            <p class="table_text">
                              <input type="text" name="relacion_testigo_<?php echo $i ?>" id="relacion_testigo_<?php echo $i ?>" placeholder="" size="50" maxlength="50" class="observacion form-control input-xs full-w" autocomplete="off">
                            </p>
                          </td>
                        </tr>
                    <?php
                    endfor;
                  ?>
                </tbody>
              </table>
            </div>
          </fieldset><br>

          <fieldset class="form-ing-cont"> 
          <legend class="form-ing-border">Observaciones</legend>

            <div class="col-lg-12">  
              <div class="form-group">
              <input type="text"  placeholder="Ingrese información adicional" class="form-control form-control-sm"  name="observacion" id="observacion">
              </div>
            </div>
 
            <div class="col-lg-6"> 
              <div class="form-group"> 
              <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Archivos adjuntos</label>
                <input type="file" id="userfile" name="userfile">
              </div>
            </div>

          </fieldset><br>


          <div class="col-lg-12">
            <div class="form-row">
              
              <!-- <div class="col-6 col-lg-4">
                <div class="form-group">  
                  <div class="form-check">
                    <input type="checkbox" checked name="checkcorreo" class="form-check-input" id="checkcorreo">
                    <label class="form-check-label" for="checkcorreo">¿Enviar correo?</label>
                  </div>
                </div>
              </div> -->

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
