<style type="text/css">
  .DTFC_LeftBodyLiner {
      overflow-x: hidden;
  }

  .finde_resumen{
     color:#FF0000!important;
  }
   
  .actualizacion_productividad{
      display: inline-block;
      font-size: 12px;
  }
  table.dataTable thead .sorting:before, table.dataTable thead .sorting:after, table.dataTable thead .sorting_asc:before, table.dataTable thead .sorting_asc:after, table.dataTable thead .sorting_desc:before, table.dataTable thead .sorting_desc:after, table.dataTable thead .sorting_asc_disabled:before, table.dataTable thead .sorting_asc_disabled:after, table.dataTable thead .sorting_desc_disabled:before, table.dataTable thead .sorting_desc_disabled:after {
    bottom: 2px;
    display: none!important;
  }

  /* table.dataTable thead>tr>th.sorting_asc, table.dataTable thead>tr>th.sorting_desc, table.dataTable thead>tr>th.sorting, table.dataTable thead>tr>td.sorting_asc, table.dataTable thead>tr>td.sorting_desc, table.dataTable thead>tr>td.sorting {
    padding-right: 5px!important; 
  }
 */

</style>
<script type="text/javascript">
  $(function(){
   
      const perfil = "<?php echo $this->session->userdata('id_perfil'); ?>";
      const base = "<?php echo base_url() ?>";

      const procesaDatatable = async (reload) => {

      const periodo_resumen = $("#periodo_resumen").val();
      const trabajador_resumen = perfil === "4" ? $("#trabajador_resumen").val() : $("#trabajadores_resumen").val();

      const response = await fetch(`${base}getCabeceras?${$.now()}`, { 
        method: 'POST',
        headers: {
           'Content-Type': 'application/json' 
        }, 
        body: JSON.stringify({
          periodo: periodo_resumen,
          trabajador: trabajador_resumen 
        }) 
      });

      const data = await response.json();

      $(".btn_filtro_resumen").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar').prop("disabled", false);

      if (data.data.length !== 0) {
      if (reload) { $('#tabla_resumen').DataTable().clear().destroy(); $("#tabla_resumen").html(""); }

      const columns = data.data.map(column => ({ data: column, class: column[0] === "D" && column !== "Días" ? "finde_resumen" : "", title: column.charAt(0).toUpperCase() + column.slice(1) }));

      $("#tabla_resumen").append('<tfoot><tr class="tfoot_table"></tr></tfoot>');

      columns.forEach(() => $(".tfoot_table").append('<th class="tfoot"></th>'));

      const tabla_resumen = $('#tabla_resumen').DataTable({
        columns,
        info: false,
        destroy: true,
        processing: true,
        iDisplayLength: -1,
        aaSorting: [[1, "asc"]],
        scrollY: "65vh",
        scrollX: true,
        select: true,
        responsive: false,
        bSort: true,
        scrollCollapse: true,
        paging: false,
        oLanguage: { sProcessing: "<i id='processingIconTable' class='fa-solid fa-circle-notch fa-spin fa-2x'></i>" },
        initComplete: function () {
          if (window.innerWidth > 768) {
            new $.fn.dataTable.FixedColumns(tabla_resumen, { leftColumns: 3, heightMatch: 'none' });
          }
        },
        columnDefs: [{ width: "2%", targets: 0 }, { width: "10%", targets: 1 }],
        ajax: {
          url: "<?php echo base_url();?>listaResumen",
          data: { periodo: periodo_resumen, jefe: $("#jefe_res").val(), trabajador: trabajador_resumen },
          dataSrc: json => { $("#fecha_f").val(json.periodo); $(".actualizacion_productividad").html(`<b>Última actualización planilla : ${json.actualizacion}</b>`); return json.data; }
        }
      });

      } else {
        $("#tabla_resumen").DataTable().clear().draw();
        $(".tfoot_table").html("");
      }

      $(".btn_filtro_calidad").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar').prop("disabled", false);
    };

    procesaDatatable(false);

    $.getJSON(base + "listaTrabajadoresProd", { jefe : $("#jefe_res").val() } , function(data) {
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


    $(document).off('change', '#periodo_resumen , #trabajadores_resumen ,#jefe_res').on('change', '#periodo_resumen , #trabajadores_resumen ,#jefe_res', function(event) {
      procesaDatatable(true)
    }); 
     
  })
</script>

<!-- FILTROS -->
  
    <div class="form-row">
     
      <div class="col-6 col-lg-2">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="margin-left: 10px;font-size:13px;"> Periodo <span></span> 
            </div>
              <select id="periodo_resumen" name="periodo_resumen" class="custom-select custom-select-sm">
                <option value="actual" selected>Actual - <?php echo $mes_actual ?> </option>
                <option value="anterior">Anterior - <?php echo $mes_anterior ?> </option>
                <option value="anterior_2">Anterior 2 - <?php echo $mes_anterior2 ?> </option>
             </select>
          </div>
        </div>
      </div>

      <div class="col-6 col-lg-1">
        <div class="form-group">
          <div class="input-group">
            <input type="text" disabled placeholder="Periodo" class="fecha_normal form-control form-control-sm fecha_f"  name="fecha_f" id="fecha_f">
          </div>
        </div>
      </div>

      <?php  
        if($this->session->userdata('id_perfil')<3){
      ?>

        <div class="col-6 col-lg-2">
          <div class="form-group">
            <select id="jefe_res" name="jefe_det" class="custom-select custom-select-sm">
              <option value="" selected>Seleccione Jefe | Todos</option>
              <?php  
                foreach($jefes as $j){
                  if($j["id_jefe"]==22){
                    ?>
                     <option  value="<?php echo $j["id_jefe"]?>" ><?php echo $j["nombre_jefe"]?> </option>
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
          <div class="col-6 col-lg-2">
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
          <div class="col-6 col-lg-2">  
            <div class="form-group">
              <select id="trabajadores_resumen" name="trabajadores_resumen" style="width:100%!important;">
                  <option value="">Seleccione Trabajador | Todos</option>
              </select>
            </div>
          </div>
          <?php
       }else{
        ?>
          <div class="col-6 col-lg-2">  
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
            <table id="tabla_resumen" class="table-bordered dt-responsive nowrap dataTable table-striped row-border order-column" style="width:100%"></table>
          </div>
        </div>
      </div>
    </div>