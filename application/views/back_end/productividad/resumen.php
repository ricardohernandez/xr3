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

  .finde_resumen{
    /*background-color: #EAEDED;*/
    color:#FF0000!important;
   /* z-index: 1!important;*/
  }

  table thead th, table tfoot , table tbody {
    font-size: 14px!important;
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

  @media (min-width: 768px){
	#tabla_resumen tbody td {
	    font-size: 12px!important;
	}
  }

  .actualizacion_productividad{
      display: inline-block;
      font-size: 11px;
  }

</style>
<script type="text/javascript">
  $(function(){
    var perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    const base = "<?php echo base_url() ?>";

  /*****DATATABLE*****/   
   const procesaDatatable = (reload) => {
      var periodo_resumen = $("#periodo_resumen").val();

      if(perfil==4){
        trabajador_resumen = $("#trabajador_resumen").val();
      }else{
        trabajador_resumen = $("#trabajadores_resumen").val();
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

      enviaDatos(base+"getCabeceras"+"?"+$.now(), {periodo:periodo_resumen,trabajador:trabajador_resumen})
        .then(data => {
          $(".btn_filtro_resumen").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar').prop("disabled",false);
          if(data.data.length!=0){

             if(reload){
                $('#tabla_resumen').html("");
                $('#tabla_resumen').DataTable().clear().destroy();
                $("#tabla_resumen tbody").html("");
                $("#tabla_resumen thead").html("");
                $("#tabla_resumen tfoot").html("");
                $("#tabla_resumen tfoot").html('<tr class="tfoot_table"></tr>')
              }else{
                $("#tabla_resumen").append('<tfoot><tr class="tfoot_table"></tr></tfoot>')
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

             var tabla_resumen = $('#tabla_resumen').DataTable({
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
                  sProcessing:"<i id='processingIconTable' class='fa-solid fa-circle-notch fa-spin fa-2x'></i>",
                },
                fixedColumns:   {
                   leftColumns: 4,
                   heightMatch: 'none'
                },
                columnDefs: [
                      { width: "2%", targets: 0 },
                      { width: "10%", targets: 1 },
                      // { visible: false, targets: -1},
                ],
              
                "ajax": {
                  "url":"<?php echo base_url();?>listaResumen",
                  "dataSrc": "data",
                   data: function(param){

                    var desde_actual="<?php echo $desde_actual; ?>"
                    var hasta_actual="<?php echo $hasta_actual; ?>"
                    var desde_anterior="<?php echo $desde_anterior; ?>"
                    var hasta_anterior="<?php echo $hasta_anterior; ?>"
                    var periodo =$("#periodo_resumen").val()
                    var jefe =$("#jefe_res").val()
                  
                    if(periodo=="actual"){
                      $("#fecha_f").val(`${desde_actual.substring(0,5)} - ${hasta_actual.substring(0,5)}`);
                    }else if(periodo=="anterior"){
                      $("#fecha_f").val(`${desde_actual.substring(0,5)} - ${hasta_actual.substring(0,5)}`);
                    }

                    param.periodo = periodo;
                    param.jefe = jefe;

  			            if(perfil==4){
  			              param.trabajador = $("#trabajador_resumen").val();
  			            }else{
  			              param.trabajador = $("#trabajadores_resumen").val();
  			            }
                 }
              },    
            });

          }else{
            $("#tabla_resumen").DataTable().clear().draw()
            $(".tfoot_table").html("");
            // $("#tabla_resumen tfoot").html('')
          }
        
        $(".btn_filtro_calidad").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar').prop("disabled",false);
      });
        
    }


    function capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    }

    procesaDatatable(false)


    $.getJSON(base + "listaTrabajadores", { jefe : $("#jefe_res").val() } , function(data) {
      response = data;
    }).done(function() {
        $("#trabajadores_resumen").select2({
         placeholder: 'Seleccione Trabajador | Todos',
         data: response,
         width: 'resolve',
         allowClear:true,
        });
    });  

    $(document).off('click', '.btn_filtro_resumen').on('click', '.btn_filtro_resumen',function(event) {
      event.preventDefault();
       $(this).prop("disabled" , true);
       $(".btn_filtro_resumen").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
       procesaDatatable(true)
    });

    
    actualizacionProductividad()

    function actualizacionProductividad(){
      $.ajax({
          url: "actualizacionProductividad"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          dataType:"json",
          beforeSend:function(){
          },
          success: function (data) {
            if(data.res=="ok"){
              $(".actualizacion_productividad").html("<b>Última actualización planilla : "+data.datos+"</b>");
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
                }    
                return;
            }

            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
            }
          },timeout:5000
      }); 
    }

    $(document).off('change', '#periodo_resumen , #trabajadores_resumen ,#jefe_res').on('change', '#periodo_resumen , #trabajadores_resumen ,#jefe_res', function(event) {
      procesaDatatable(true)
    }); 

    /*$(document).off('change', '#periodo_resumen').on('change', '#periodo_resumen',function(event) {
      $(".btn_filtro_resumen").prop("disabled" , true);
      $(".btn_filtro_resumen").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...');
      procesaDatatable(true)
    }); 

    $(document).off('change', '#trabajadores_resumen').on('change', '#trabajadores_resumen',function(event) {
      $(".btn_filtro_resumen").prop("disabled" , true);
      $(".btn_filtro_resumen").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...');
      procesaDatatable(true)
    }); */
     
  })
</script>

<!-- FILTROS -->
  
    <div class="form-row">
     
      <div class="col-lg-2">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="margin-left: 5px;margin-top: 2px;"> Periodo <span></span> 
            </div>
              <select id="periodo_resumen" name="periodo_resumen" class="custom-select custom-select-sm">
                <option value="actual" selected>Actual </option>
                <option value="anterior" >Anterior</option>
             </select>
          </div>
        </div>
      </div>

      <div class="col-lg-1">
        <div class="form-group">
          <div class="input-group">
            <input type="text" disabled placeholder="Periodo" class="fecha_normal form-control form-control-sm fecha_f"  name="fecha_f" id="fecha_f">
          </div>
        </div>
      </div>

      <?php  
        if($this->session->userdata('id_perfil')<3){
      ?>

        <div class="col-lg-2">
          <div class="form-group">
            <select id="jefe_res" name="jefe_det" class="custom-select custom-select-sm">
              <option value="" selected>Seleccione Jefe | Todos</option>
              <?php  
                foreach($jefes as $j){
                  if($j["id_jefe"]==22){
                    ?>
                     <option selected value="<?php echo $j["id_jefe"]?>" ><?php echo $j["nombre_jefe"]?> </option>
                    <?php
                  }else{
                     ?>
                      <option value="<?php echo $j["id_jefe"]?>" ><?php echo $j["nombre_jefe"]?> </option>
                    <?php
                  }
                  ?>
                   
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
              <select id="jefe_res" name="jefe_det" class="custom-select custom-select-sm">
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

      <?php  
       if($this->session->userdata('id_perfil')<>4){
          ?>
          <div class="col-lg-2">  
            <div class="form-group">
              <select id="trabajadores_resumen" name="trabajadores_resumen" style="width:100%!important;">
                  <option value="">Seleccione Trabajador | Todos</option>
              </select>
            </div>
          </div>
          <?php
       }else{
        ?>
          <div class="col-lg-2">  
            <div class="form-group">
              <select id="trabajador_resumen" name="trabajador_resumen" class="custom-select custom-select-sm" >
                  <option selected value="<?php echo $this->session->userdata('rut'); ?>"><?php echo $this->session->userdata('nombre_completo'); ?></option>
              </select>
            </div>
          </div>
        <?php
       }
      ?>

      

     <!--  <div class="col-6 col-lg-1">
        <div class="form-group">
         <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_resumen btn_xr3">
         <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
         </button>
       </div>
      </div> -->

     <!--  <div class="col-6 col-lg-1">  
        <div class="form-group">
         <button type="button"  class="btn-block btn btn-sm btn-primary excel_detalle btn_xr3">
         <i class="fa fa-save"></i> Excel
         </button>
        </div>
      </div> -->

      </div>            


    <div class="row">
      <div class="col-lg-6 offset-lg-3">
        <center><span class="titulo_fecha_actualizacion_dias">
          <div class="alert alert-primary actualizacion_productividad" role="alert" style="padding: .15rem 1.25rem;margin-bottom: .1rem;">
          </div>
        </span></center>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="row">
          <div class="col-lg-12">
            <table id="tabla_resumen" class="table-bordered dt-responsive nowrap dataTable stripe row-border order-column" style="width:100%"></table>
          </div>
        </div>
      </div>
    </div>