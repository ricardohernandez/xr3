<style type="text/css">
  .DTFC_LeftBodyLiner {
    overflow-x: hidden;
  }

  .azul{
    background-color: #233294;
    color:white;
  }
  .centered2{
      text-align: center!important;
  }
  .btn-edit-turnos{
    cursor: pointer;
  }
  .eliminar_turnos{
      display: inline;
      font-size: 15px!important;
      color:#fff!important;
      text-decoration: none!important;
  }
  .eliminar_turnos_contenedor{
    display: none;
  }
  .finde_resumen{
    /*background-color: #EAEDED;*/
    color:#FF0000!important;
    z-index: 1!important;
  }

  .table thead th , .table tfoot th  {
    font-size: 12px!important;
  }

  .table tbody td {
    font-size: 11px!important;
  }

  .table thead th ,.table tbody td , .table tfoot th  {
    padding-left: 1rem!important;
    padding-right: 1rem!important;
  }

  table.dataTable thead .sorting:before, table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:before, table.dataTable thead .sorting_desc_disabled:after {
    bottom: 2px;
    display: none!important;
  }

  table.dataTable thead>tr>th.sorting_asc, table.dataTable thead>tr>th.sorting_desc, table.dataTable thead>tr>th.sorting, table.dataTable thead>tr>td.sorting_asc, table.dataTable thead>tr>td.sorting_desc, table.dataTable thead>tr>td.sorting {
    padding-right: 5px!important;
  }
  
  .dataTables_wrapper {
      clear: both;
      min-height: 302px;
      position: relative;
  }

  @media (max-width: 768px){
    #tabla_turnos tbody td {
      font-size: 11px!important;
    }
    .modal_turnos{
      width: 95%!important;
    }
  }

  @media (min-width: 768px){
    #tabla_turnos tbody td {
      font-size: 11px!important;
    }
    .modal_turnos{
      width: 55%!important;
    }
  }
</style>

<script type="text/javascript">
  $(function(){

    var desde="<?php echo $desde; ?>";
    var hasta="<?php echo $hasta; ?>";
    $("#desde_t").val(desde);
    $("#hasta_t").val(hasta);
    const p = "<?php echo $this->session->userdata('id_perfil'); ?>";
    const base = "<?php echo base_url() ?>";

  /*****DATATABLE*****/   

   const procesaDatatable = (reload) => {
      var desde = $("#desde_t").val()
      var hasta = $("#hasta_t").val()
      var jefe = $("#jefe_t").val()
      var nivel_tecnico = $("#nivel_tecnico").val()

      if(p==4){
         var trabajador = $("#trabajador_t").val();
      }else{
         var trabajador = $("#trabajadores_t").val();
      }

      async function enviaDatos(url = '', data = {}) {
          const response = await fetch(url, {
            method: 'POST', 
            mode: 'cors', 
            cache: 'no-cache',
            credentials: 'same-origin', 
            headers: {
              'Content-Type': 'application/json'
            },
            redirect: 'follow',
            referrerPolicy: 'strict-origin-when-cross-origin',
            body: JSON.stringify(data)
          });

          return response.json(); 
      }

      enviaDatos(base+"getCabecerasTurnos"+"?"+$.now(), 
        {
          desde:desde,
          hasta:hasta,
          trabajador:trabajador,
          jefe:jefe,
          nivel_tecnico:nivel_tecnico

        })
        .then(data => {
          $(".btn_filtro_turnos").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar').prop("disabled",false);
          if(data.data.length!=0){

             if(reload){
                $('#tabla_turnos').html("");
                $('#tabla_turnos').DataTable().clear().destroy();
                $("#tabla_turnos tbody").html("");
                $("#tabla_turnos thead").html("");
                $("#tabla_turnos tfoot").html("");
                $("#tabla_turnos tfoot").html('<tr class="tfoot_table"></tr>')
              }else{
                $("#tabla_turnos").append('<tfoot><tr class="tfoot_table"></tr></tfoot>')
              }
              
              columns = [];
              columnNames = (data.data);

              for (var i in columnNames) {
                let str = columnNames[i];
                if(str[0]=="D" && columnNames[i]!="Días"){
                  clase = "finde_resumen"
                }else{
                  clase = ""
                }

                $(".tfoot_table").append('<th class="tfoot"></th>')
                columns.push({
                    data: columnNames[i],
                    class : clase,
                    title: capitalizeFirstLetter(columnNames[i])
                })
              }

             var tabla_turnos = $('#tabla_turnos').DataTable({
                columns: columns,
                info:false, 
                destroy: true,
                processing: true,  
                iDisplayLength:-1, 
                aaSorting : [[1,"asc"]],
                scrollY: "65vh",
                scrollX: true,
                select:true,
                bSort: true,
                scrollCollapse: true,
                paging:false,
                oLanguage: { 
                  sProcessing:"<i id='processingIcon' class='fa fa-cog fa-spin fa-4x'></i>",
                },
                /*fixedColumns:   {
                   leftColumns: 2,
                   heightMatch: 'none'
                },*/
                columnDefs: [
                      { width: "5%", targets: 0 },
                      { width: "5%", targets: 1 },
                      { width: "5%", targets: 2 },
                      { width: "5%", targets: 3 },
                      // { visible: false, targets: -1},
                ],
                "ajax": {
                  "url":"<?php echo base_url();?>listaTurnos",
                  "dataSrc": "data",
                   data: function(param){
                    var desde=$("#desde_t").val()
                    var hasta=$("#hasta_t").val()
                    var jefe =$("#jefe_t").val()
                    var nivel_tecnico =$("#nivel_tecnico").val()
                    

                    param.desde = desde;
                    param.hasta = hasta;
                    param.jefe = jefe;
                    param.nivel_tecnico = nivel_tecnico;
                    param.trabajador = trabajador;
                 }
              },    
            });

           setTimeout( function () {
              var tabla_turnos = $.fn.dataTable.fnTables(true);
              if ( tabla_turnos.length > 0 ) {
                  $(tabla_turnos).dataTable().fnAdjustColumnSizing();
            }}, 200 ); 

            setTimeout( function () {
              var tabla_turnos = $.fn.dataTable.fnTables(true);
              if ( tabla_turnos.length > 0 ) {
                console.log("asd");
                  $(tabla_turnos).dataTable().fnAdjustColumnSizing();
            }}, 2000 ); 

            setTimeout( function () {
              var tabla_turnos = $.fn.dataTable.fnTables(true);
              if ( tabla_turnos.length > 0 ) {
                  $(tabla_turnos).dataTable().fnAdjustColumnSizing();
              }
            }, 4000 ); 

          }else{
            $("#tabla_turnos").DataTable().clear().draw()
            $(".tfoot_table").html("");
            // $("#tabla_turnos tfoot").html('')
          }
        
        $(".btn_filtro_turnos").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar').prop("disabled",false);
      });
    }

    function capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }

    procesaDatatable(false)
    
  /*********INGRESO************/

    $(document).off('click', '.btn_nuevo_turnos').on('click', '.btn_nuevo_turnos',function(event) {
      $('#modal_turnos').modal('toggle'); 
      $(".btn_guardar_turnos").html('<i class="fa fa-save"></i> Guardar');
      $(".btn_guardar_turnos").attr("disabled", false);
      $(".cierra_modal_turnos").attr("disabled", false);
      $('#formTurnos')[0].reset();
      $("#hash_turnos").val("");
      $("#trabajador").val("").trigger('change');
      $(".eliminar_turnos_contenedor").hide();
      $("#formTurnos input,#formTurnos select,#formTurnos button,#formTurnos").prop("disabled", false);
    });     

    $(document).off('submit', '#formTurnos').on('submit', '#formTurnos',function(event) {
      var url="<?php echo base_url()?>";
      var formElement = document.querySelector("#formTurnos");
      var formData = new FormData(formElement);
        $.ajax({
            url: $('#formTurnos').attr('action')+"?"+$.now(),  
            type: 'POST',
            data: formData,
            cache: false,
            processData: false,
            dataType: "json",
            contentType : false,
            beforeSend:function(){
              $(".btn_guardar_turnos").attr("disabled", true);
              $(".cierra_modal_turnos").attr("disabled", true);
              $("#formTurnos input,#formTurnos select,#formTurnos button,#formTurnos").prop("disabled", true);
            },
            success: function (data) {
             if(data.res == "error"){

                $(".btn_guardar_turnos").attr("disabled", false);
                $(".cierra_modal_turnos").attr("disabled", false);

                $.notify(data.msg, {
                  className:'error',
                  globalPosition: 'top right',
                  autoHideDelay:5000,
                });

                $("#formTurnos input,#formTurnos select,#formTurnos button,#formTurnos").prop("disabled", false);

              }else if(data.res == "ok"){
                  $(".btn_guardar_turnos").attr("disabled", false);
                  $(".cierra_modal_turnos").attr("disabled", false);

                  $.notify(data.msg, {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                 
                  /*$('#modal_turnos').modal("toggle");*/
                 /* procesaDatatable(true)*/
            }

            $(".btn_guardar_turnos").attr("disabled", false);
            $(".cierra_modal_turnos").attr("disabled", false);
            $("#formTurnos input,#formTurnos select,#formTurnos button,#formTurnos").prop("disabled", false);
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
                    $('#modal_turnos').modal("toggle");
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_turnos').modal("toggle");
            }
          },timeout:25000
        });
      return false; 
    });

    $(document).off('click', '.btn-edit-turnos').on('click', '.btn-edit-turnos',function(event) {
      $("#hash_turnos").val("");
      hash_turnos = $(this).data("hash_turnos");
      $("#hash_turnos").val(hash_turnos);
      $("#modal_turnos").modal("toggle");
      $(".eliminar_turnos_contenedor").show();
      $.ajax({
        url: "getDataTurnos"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        data:{hash_turnos : hash_turnos},
        dataType:"json",
        beforeSend:function(){
          $(".btn_guardar_turnos").attr("disabled", true);
          $(".cierra_modal_turnos").attr("disabled", true);
          $("#formTurnos input,#formTurnos select,#formTurnos button,#formTurnos").prop("disabled", true);
        },
        success: function (data) {
          $(".btn_guardar_turnos").attr("disabled", false);
          $(".cierra_modal_turnos").attr("disabled", false);
          $("#formTurnos input,#formTurnos select,#formTurnos button,#formTurnos").prop("disabled", false);
          if(data.res=="ok"){
            for(dato in data.datos){
              $("#trabajador").val(data.datos[dato].rut_tecnico).trigger('change');
              $("#fecha").val(data.datos[dato].fecha);
              $("#turno  option[value='"+data.datos[dato].id_turno+"'").prop("selected", true);
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
                  $('#modal_turnos').modal("toggle");
              }    
              return;
          }
          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
              $('#modal_turnos').modal("toggle");
          }
        },timeout:25000
      }); 
    });

    $(document).off('click', '.eliminar_turnos').on('click', '.eliminar_turnos',function(event) {
        hash=$("#hash_turnos").val()

        if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminarTurnos'+"?"+$.now(),{"hash": hash}, function(data) {
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right'
                });
                $('#modal_turnos').modal('toggle'); 
                procesaDatatable(true)
              }else{
                $.notify(data.msg, {
                  className:'danger',
                  globalPosition: 'top right'
                });
              }
            },"json");
          }
    });

    $(document).off('click', '.cierra_modal_turnos').on('click', '.cierra_modal_turnos',function(event) {
      event.preventDefault();
       procesaDatatable(true)
    });

    $(document).off('click', '.btn_filtro_turnos').on('click', '.btn_filtro_turnos',function(event) {
      event.preventDefault();
       $(this).prop("disabled" , true);
       $(".btn_filtro_turnos").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
       procesaDatatable(true)
    });

    $.getJSON(base + "listaTrabajadoresTurnos", { jefe : $("#jefe_t").val() } , function(data) {
      response = data;
    }).done(function() {
        $("#trabajadores_t").select2({
         placeholder: 'Seleccione Trabajador | Todos',
         data: response,
         width: 'resolve',
         allowClear:true,
        });

        $("#trabajador").select2({
         placeholder: 'Seleccione Trabajador | Todos',
         data: response,
         width: 'resolve',
         allowClear:true,
        });
    });

    $(document).off('click', '.btn_excel_turnos').on('click', '.btn_excel_turnos',function(event) {
      event.preventDefault();
      let desde=$("#desde_t").val()
      let hasta=$("#hasta_t").val()
      let nivel_tecnico =$("#nivel_tecnico").val()

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

      if(p==4){
        trabajador = $("#trabajador_t").val()
      }else{
        trabajador = $("#trabajadores_t").val();
      }

      var jefe = p<=3 ? $("#jefe_t").val() : "-";
      jefe = jefe=="" ? "-" : jefe;

      

      if(jefe==""){
        jefe = "-"
      }

      if(nivel_tecnico==""){
        nivel_tecnico = "-"
      }

      if(trabajador==""){
        trabajador = "-"
      }

      window.location="excel_turnos/"+desde+"/"+hasta+"/"+jefe+"/"+nivel_tecnico+"/"+trabajador;
    });

    $(document).off('change', '#desde_t ,#hasta_t , #trabajadores_t ,#jefe_t,#nivel_tecnico').on('change', '#desde_t ,#hasta_t , #trabajadores_t ,#jefe_t,#nivel_tecnico', function(event) {
       procesaDatatable(true)
    }); 

  })
</script>

<!-- FILTROS -->
  
  <div class="form-row">

    <?php  
      if($this->session->userdata('id_perfil')<=3){
    ?>

      <div class="col-6 col-lg-1">  
        <div class="form-group">
           <button type="button" class="btn btn-block btn-sm btn-primary btn_nuevo_turnos btn_xr3">
           <i class="fa fa-plus-circle"></i>  Crear
           </button>
        </div>
      </div>

    <?php
      }
    ?>

    <div class="col-lg-3">
      <div class="form-group">
        <div class="input-group">
          <div class="input-group-prepend">
            <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span>Fecha <span></span> 
          </div>
          <input type="date" placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="desde_t" id="desde_t">
          <input type="date" placeholder="Hasta" class="fecha_normal form-control form-control-sm"  name="hasta_t" id="hasta_t">
        </div>
      </div>
    </div>

    <?php  
       if($this->session->userdata('id_perfil')<>4){
          ?>
          <div class="col-lg-3">  
            <div class="form-group">
              <select id="trabajadores_t" name="trabajadores_t" style="width:100%!important;">
                  <option value="">Seleccione Trabajador | Todos</option>
              </select>
            </div>
          </div>
          <?php
       }else{
        ?>
          <div class="col-lg-2">  
            <div class="form-group">
              <select id="trabajador_t" name="trabajador_t" class="custom-select custom-select-sm" >
                  <option selected value="<?php echo $this->session->userdata('rut'); ?>"><?php echo $this->session->userdata('nombre_completo'); ?></option>
              </select>
            </div>
          </div>
        <?php
       }
    ?>

    <?php  
      if($this->session->userdata('id_perfil')<3){
    ?>
      <div class="col-lg-2">
        <div class="form-group">
          <select id="jefe_t" name="jefe_t" class="custom-select custom-select-sm">
            <option value="" selected>Seleccione Jefe | Todos</option>
            <?php  
              foreach($jefes as $j){
                ?>
                  <option value="<?php echo $j["id_jefe"]?>" ><?php echo $j["nombre_jefe"]?> </option>
                <?php
              }
            ?>
          </select>
        </div>
      </div>
    <?php
      }elseif($this->session->userdata('id_perfil')==3){
        ?>
        <div class="col-lg-2">
          <div class="form-group">
            <select id="jefe_t" name="jefe_t" class="custom-select custom-select-sm">
              <?php  
                foreach($jefes as $j){
                  ?>
                    <option selected value="<?php echo $j["id_jefe"]?>" ><?php echo $j["nombre_jefe"]?> </option>
                  <?php
                }
              ?>
            </select>
          </div>
        </div>
        <?php
      }
    ?>

    <div class="col-lg-2">
      <div class="form-group">
        <select id="nivel_tecnico" name="nivel_tecnico" class="custom-select custom-select-sm">
          <option value="" selected>Seleccione Nivel técnico | Todos</option>
          <?php  
            foreach($nivelesTecnico as $n){
              ?>
                <option value="<?php echo $n["id"]?>" ><?php echo $n["nivel"]?> </option>
              <?php
            }
          ?>
        </select>
      </div>
    </div>

    <!--  <div class="col-6 col-lg-1">
      <div class="form-group">
       <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_turnos btn_xr3">
       <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
       </button>
     </div>
    </div> -->

    <div class="col-6 col-lg-1">
      <div class="form-group">
       <button type="button" class="btn-block btn btn-sm btn-primary btn_excel_turnos btn_xr3">
       <i class="fa fa-save"></i><span class="sr-only"></span> Excel
       </button>
     </div>
    </div>
  </div>            

  <div class="row">
    <div class="col-lg-12">
      <div class="row">
        <div class="col-lg-12">
          <table id="tabla_turnos" class="table-bordered dt-responsive nowrap dataTable stripe row-border order-column" style="width:100%"></table>
        </div>
      </div>
    </div>
  </div>

<!--  FORMULARIO-->
  <div id="modal_turnos" data-backdrop="static"  data-keyboard="false"   class="modal fade">
    <?php echo form_open_multipart("formTurnos",array("id"=>"formTurnos","class"=>"formTurnos"))?>
      <div class="modal-dialog modal_turnos modal-dialog-scrollable">
        <div class="modal-content">
       
          <div class="modal-body">
            <button type="button" title="Cerrar Ventana" class="close cierra_modal_turnos" data-dismiss="modal" aria-hidden="true">X</button>
            <input type="hidden" name="hash_turnos" id="hash_turnos">
            <fieldset class="form-ing-cont">
            <legend class="form-ing-border">Ingreso de turnos </legend>
              <div class="form-row">

                <div class="col-lg-4">  
                  <div class="form-group">
                    <select id="trabajador" name="trabajador" style="width:100%!important;">
                        <option value="">Seleccione Trabajador | Todos</option>
                    </select>
                  </div>
                </div>
              
                <div class="col-lg-4">
                  <div class="form-group">
                   <input type="date" placeholder="Fecha" class="form-control form-control-sm"  value="<?php echo date('Y-m-d')?>" name="fecha" id="fecha">
                  </div>
                </div>

                <div class="col-lg-4">               
                  <div class="form-group">
                    <select id="turno" name="turno" class="custom-select custom-select-sm">
                    <option value="" selected>Seleccione </option>
                        <?php 
                        foreach($turnos as $t){
                          ?>
                            <option value="<?php echo $t["id"]; ?>"><?php echo $t["codigo"]; ?></option>
                          <?php
                        }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
            </fieldset> 
          </div>

          <div class="modal-footer" style="border-top: none;">
            <div class="col-xs-12 col-sm-12 col-lg-8 offset-lg-2 mt-0">
              <div class="form-row">
                <div class="col-9 col-lg-4">
                  <button type="submit" class="btn-block btn btn-sm btn-success btn_guardar_turnos">
                   <i class="fa fa-save"></i> Guardar
                  </button>
                </div>

                <div class="col-3 col-lg-4 eliminar_turnos_contenedor">
                  <a class="btn-block btn btn-sm btn-danger eliminar_turnos" aria-hidden="true">
                   <i class="fa fa-trash"></i> Eliminar
                  </a>
                </div>

                <div class="col-3 col-lg-4">
                  <button class="btn-block btn btn-sm btn-secondary cierra_modal_turnos" data-dismiss="modal" aria-hidden="true">
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



 

