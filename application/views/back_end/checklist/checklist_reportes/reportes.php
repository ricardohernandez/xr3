<style type="text/css">

  .titulo{
      display: inline-block;
      font-size: 13px;
  }
  .card{
    border: none!important;
    padding: 0px!important;;
    -webkit-box-shadow: 0 0 1px 0 rgb(183 192 206 / 20%);
    border-radius: 5px;
  }
  .s2{
    font-size:1rem!important;
  }
    /* for gauge indicators text */
  .gauge svg g text {
    font-size: 12px;
  }
  /* for middle text */
  .gauge svg g g text {
    font-size: 14px;
    font-weight: bold!important;
    color: red!important;
  }

</style>

<script type="text/javascript">

  $(function(){


    const perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    const r = "<?php echo $this->session->userdata('rut'); ?>";
    const base_url = "<?php echo base_url() ?>";
	  google.charts.setOnLoadCallback(graficoReporteChecklist);

    $(document).off('change', '#periodo, #supervisor, #zona').on('change', '#periodo, #supervisor, #zona', function(event) {
        tabla_checklistreporte.ajax.reload();
        graficoReporteChecklist();
        

        setTimeout( function () {
        var listaChecklistHFC = $.fn.dataTable.fnTables(true);
          if ( listaChecklistHFC.length > 0 ) {
              $(listaChecklistHFC).dataTable().fnAdjustColumnSizing();
        }}, 200 ); 

        setTimeout( function () {
          var listaChecklistHFC = $.fn.dataTable.fnTables(true);
          if ( listaChecklistHFC.length > 0 ) {
              $(listaChecklistHFC).dataTable().fnAdjustColumnSizing();
        }}, 2000 ); 

        setTimeout( function () {
          var listaChecklistHFC = $.fn.dataTable.fnTables(true);
          if ( listaChecklistHFC.length > 0 ) {
              $(listaChecklistHFC).dataTable().fnAdjustColumnSizing();
          }
        }, 4000 ); 


    });

    function graficoReporteChecklist(){

      var periodo = $("#periodo").val();
      var supervisor = $("#supervisor").val();
      var zona = $("#zona").val();

	    $.ajax({
        url: base_url+"graficoReporteChecklist"+"?"+$.now(),  
        type: 'POST',
        data:{periodo:periodo,supervisor:supervisor,zona:zona},
        dataType:"json",
        beforeSend:function(){
            $("#load").show()
            $(".body").hide()
        },
        success: function (json) {
            $("#load").hide()
            $(".body").fadeIn(500)

            let size = Object.keys(json).length
            let height = 600

            var data = google.visualization.arrayToDataTable(json);
            data.sort([{column: 0, desc: false}])

            var options = {
                isStacked : true,
                fontName: 'ubuntu',
                fontColor:'#32477C',
                bar: { groupWidth: '75%' },
                backgroundColor: { fill:'transparent' },

                colors: ['#2f81f7','#F48432','#A5A5A5'],
                chartArea:{
                    left:270,
                    right:50,
                    bottom:130,
                    top:20,
                },

                height:height,
                hAxis: {
                  title: '',
                  minValue: 0,
                  textStyle: {
                      fontSize: 13,
                      bold:false,
                      color:'#808080'
                  }, 

                  gridlines: {
                    color: '#808080',
                    count:0
                  }
                  
                },
                vAxis: {
                  title: '',
                  textStyle: {
                      fontSize: 13,
                      bold:false,
                      color:'#808080'
                  },

 
                },
 
                annotations: {
                    alwaysOutside: false,
                    textStyle: {
                        fontSize: 13,
                        auraColor: 'none'
                    }
                },
                legend : {  
                  position: 'bottom',
                  alignment: 'center',
                  textStyle: {
                      fontSize: 14,
                      bold: true,
                      color: '#808080'
                  }
                },
                tooltip: { 
                    textStyle: {  
                        color:'#ffffff96', 
                        fontSize: 13
                    }
                },       

            };

            var chart = new google.visualization.BarChart(document.getElementById('graficoChecklistReporte'));
            chart.draw(data, options);
        }
	    })
	}
 

    var tabla_checklistreporte = $('#tabla_checklistreporte').DataTable({
        dom: "<'row '<'col-sm-12'f>>" +
            "<'row'<'col-sm-12'tr>> <'bottom' <'row  mt-3' <'col-4' l><'col-4 text-center' i>  <'col-4' p>> >",
       "iDisplayLength":-1, 
       "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
       "bPaginate": false,
       "info":false,
       "aaSorting" : [[0,"asc"]],
       "scrollY": "60vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       // "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>listaReporteChecklist",
          "dataSrc": function (json) { 
            return json;
  
          },       
          data: function(param){ 
            param.periodo = $("#periodo").val();
            param.supervisor = $("#supervisor").val(); 
            param.zona = $("#zona").val(); 
          }
        },    
       
       "columns": [
          { "data": "lider" ,"class":"margen-td centered"},
          { "data": "suma_herramientas" ,"class":"margen-td centered"},
          { "data": "suma_hfc" ,"class":"margen-td centered"},
          { "data": "suma_ftth" ,"class":"margen-td centered"},
        ]
      });

      $(document).on('keyup paste', '#buscador', function() {
        tabla_checklistreporte.search($(this).val().trim()).draw();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }
      
      setTimeout( function () {
        var listaChecklistHFC = $.fn.dataTable.fnTables(true);
        if ( listaChecklistHFC.length > 0 ) {
            $(listaChecklistHFC).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var listaChecklistHFC = $.fn.dataTable.fnTables(true);
        if ( listaChecklistHFC.length > 0 ) {
            $(listaChecklistHFC).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var listaChecklistHFC = $.fn.dataTable.fnTables(true);
        if ( listaChecklistHFC.length > 0 ) {
            $(listaChecklistHFC).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 

      $(document).off('click', '.excel_reporte_checklist').on('click', '.excel_reporte_checklist',function(event) {
         event.preventDefault();
        var supervisor = perfil<=3 ? $("#supervisor").val() : "-";
        supervisor = supervisor=="" ? "-" : supervisor;

        var periodo = $("#periodo").val();  

        if(periodo==""){
           periodo="actual"
        }

        window.location="excel_reporte_checklist/"+periodo+"/"+supervisor+"/-";

      });
  })  

</script>

<div class="content" style="padding: 0px 10px;">
  <div class="form-row">
    <div class="col-12  col-md-6 col-lg-3">
        <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page" ><a href="">RCH - Reporte checklist</a></li>
        </ol>
        </nav>
    </div>

    <div class="col-8 col-lg-3">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-prepend">
                <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="margin-left: 5px;margin-top: 2px;font-size: 1rem!important"> Periodo </span> </span> 
                </div>
                <select id="periodo" name="periodo" class="custom-select custom-select-sm" style="font-size: 1rem!important;">
                <option value="actual" selected><?php echo $mes_actual ?></option>
                <option value="anterior"><?php echo $mes_anterior ?></option>
                <option value="anterior2"><?php echo $mes_anterior2 ?></option>
                </select>
            </div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="form-group">
            <select id="supervisor" name="supervisor" class="custom-select custom-select-sm" style="font-size: 1rem!important;">
            <option value="">Supervisor | Todos</option>
            <?php  
                foreach($supervisores as $s){
                ?>
                    <option  value="<?php echo $s["id"]?>" ><?php echo $s["nombre_completo"]?> </option>
                <?php
                }
            ?>
            </select>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="form-group">
            <select id="zona" name="zona" class="custom-select custom-select-sm" style="font-size: 1rem!important;">
            <option value="">Zona | Todos</option>
            <?php  
                foreach($areas as $a){
                ?>
                    <option  value="<?php echo $a["id"]?>" ><?php echo $a["area"]?> </option>
                <?php
                }
            ?>
            </select>
        </div>
     </div>
    </div>       

<div class="row p-2">

  <div class="col-12 col-lg-6">
    <div class="card">
      <div class="form-row">
        <div class="col-lg-12 p-2">
          <table id="tabla_checklistreporte" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
            <thead>
              <tr>    
                <th class="centered">Lider</th> 
                <th class="centered">Suma CLH</th> 
                <th class="centered">Suma HFC</th> 
                <th class="centered">Suma FTTH</th> 
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>   

  <div class="col-12 col-lg-6">
    <div class="card">
      <div class="form-row">
        <div class="col-12 graficoChecklistReporte">
          <div id="graficoChecklistReporte"></div>
        </div>
      </div>
    </div>
  </div>

</div>

<br><br><br><br>