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
    z-index: 1!important;
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
                if(str[0]=="D"){
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
                  sProcessing:"<i id='processingIcon' class='fa fa-cog fa-spin fa-4x'></i>",
                },
                fixedColumns:   {
                   leftColumns: 2,
                   heightMatch: 'none'
                },
                columnDefs: [
                      { width: "2%", targets: 0 },
                      { width: "10%", targets: 1 },
                      // { visible: false, targets: -1},
                ],
                // "rowCallback": function( row, data, index ) {
                  //    count=0;
                  //    $.each(data, function(i, item) {
                  //     if(data[i]=="1"){
                  //      $(row).find('td:eq('+count+')').addClass("azul").html("")
                  //     }
                  //     count++;
                  //   });
                  // },
                  // "footerCallback": function ( row, data, start, end, display ) {
                  //   var api = this.api(), data;
                  //   var largo = api.columns(':visible').count();

                  //   for (var i = 1; i <= (largo); i++) {
                  //     if(i>2){
                  //       var intVal = function ( i ) {
                  //           return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
                  //       };

                  //       total = api .column( i )  .data()   .reduce( function (a, b) {
                  //          return intVal(a) + intVal(b);
                  //       },0);

                  //       if(total==0){
                  //       }else if(total==1){
                  //         $( api.column( i ).footer() ).html("<center><b style='color:green;font-size:10px;text-align:center;'>"+total+"</b></center>");
                  //       }else if(total>1){
                  //         $( api.column( i ).footer() ).html("<center><b style='color:#CE3735;font-size:10px;text-align:center;'>"+total+"</b></center>");
                  //       }
                  //     }

                  //   }
                // },
                "ajax": {
                  "url":"<?php echo base_url();?>listaResumen",
                  "dataSrc": "data",
                   data: function(param){

                    var desde_actual="<?php echo $desde_actual; ?>"
                    var hasta_actual="<?php echo $hasta_actual; ?>"
                    var desde_anterior="<?php echo $desde_anterior; ?>"
                    var hasta_anterior="<?php echo $hasta_anterior; ?>"
                    var periodo =$("#periodo_resumen").val()

                    if(periodo=="actual"){
                      $("#desde_f").val(desde_actual);
                      $("#hasta_f").val(hasta_actual);
                    }else if(periodo=="anterior"){
                      $(".desde_f").val(desde_anterior);
                      $(".hasta_f").val(hasta_anterior);
                    }

			              param.periodo = periodo;

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


     

  /*********INGRESO************/


    $.getJSON("listaTrabajadores", function(data) {
      response = data;
    }).done(function() {
        $("#trabajadores_resumen").select2({
         placeholder: 'Seleccione Trabajador | Todos',
         data: response,
         width: 'resolve',
         allowClear:true,
        });
    });

      
    $(document).off('change', '#periodo_resumen').on('change', '#periodo_resumen',function(event) {
      $(".btn_filtro_resumen").prop("disabled" , true);
      $(".btn_filtro_resumen").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...');
      procesaDatatable(true)
    }); 

    $(document).off('change', '#trabajadores_resumen').on('change', '#trabajadores_resumen',function(event) {
      $(".btn_filtro_resumen").prop("disabled" , true);
      $(".btn_filtro_resumen").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...');
      procesaDatatable(true)
    }); 
     


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

      <div class="col-lg-2">
        <div class="form-group">
          <div class="input-group">
              <input type="text" disabled placeholder="Desde" class="fecha_normal form-control form-control-sm desde_f"  name="desde_f" id="desde_f">
              <input type="text" disabled placeholder="Hasta" class="fecha_normal form-control form-control-sm hasta_f"  name="hasta_f" id="hasta_f">
          </div>
        </div>
      </div>

      <!-- <div class="col-lg-3">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span>Fecha <span></span> 
            </div>
              <input type="date" placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="desde_f" id="desde_f">
              <input type="date" placeholder="Hasta" class="fecha_normal form-control form-control-sm"  name="hasta_f" id="hasta_f">
          </div>
        </div>
      </div> -->

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

      <div class="col-12 col-lg-2">  
       <div class="form-group">
        <input type="text" placeholder="Busqueda" id="buscador_calidad" class="buscador_calidad form-control form-control-sm">
       </div>
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