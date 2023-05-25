<style type="text/css">
 
  div.dataTables_info {
    padding-top: 0.05em!important;
  }

  div.dataTables_paginate {
    margin-top:1px!important;
    margin-bottom:10px!important;
  }

  .nav__holder {
    background-color: #ffffff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.0)!important; 
  }

  footer{
    box-shadow: 0 0 2px 3px rgb(0 0 0 / 0%)!important; 
  }
  .titulo{
      display: inline-block;
      font-size: 13px;
  }
  .red2{
    color: #F05252;
  }
  .green{
    color: #0E9F6E;
  }
  .card{
    border: none!important;
    padding: 0px!important;;
    -webkit-box-shadow: 0 0 10px 0 rgb(183 192 206 / 20%);
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
  .card-header{
    font-size: 14px; 
    color:#32477C!important;
    background-color: #E9ECEF!important;
  }

  .card-footer{
    font-size: 14px; 
    color:#32477C!important;
    background-color: #E9ECEF!important;
  }

  .card_dash{
    /* background-color: #32477C!important;*/
    /* color:#fff!important;*/
    /* border-top: none!important;
     border-bottom: none!important;*/
     border: none!important;
     border-top: none!important;
     border-bottom: none!important
   /*  background-color: #fff!important;*/
     color:#32477C!important;
     padding: 0.25rem 0.75rem!important;
     font-size: 14px;
     font-weight: bold;
  }
  .card-body{
   /* background-color: #F7F7F7!important;*/
    padding: 0.15rem!important;
  }
  hr {
    margin-top: 1rem!important;
    margin-bottom: 1rem!important;
    border: 0;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
  }
  .titulo_seccion{
    display: inline-block;
    color: #32477C;
    font-size: 14px; 
    font-weight:bold;
    text-align: left;
    padding:0px 2px;
    margin-top: 5px;
  }
  .desc_seccion{
    color: #32477C;
    font-size: 12px; 
    font-weight:bold;
    text-align: left;
    padding:7px 2px;
  }

  .dataTables_paginate .paginate_button {
    margin-top: 3px!important;
    padding: 2px 4px!important;
    text-decoration: none;
    font-size: 12px!important; 
    color: #fff!important; 
    background-color: #32477C!important; 
    cursor: pointer;
  }

  .select2-container--default .select2-selection--single {
      border: 1px solid #ced4da!important;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
      font-size:1rem!important;
  }

  .body{
    display: none;
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
        setTimeout(function() {
          var tabla_checklistreporte = $.fn.dataTable.tables({visible: true});
          if (tabla_checklistreporte.length > 0) {
              $(tabla_checklistreporte).DataTable().columns.adjust().draw();
          }
      }, 200);
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
            let height = 500

            var data = google.visualization.arrayToDataTable(json);
            data.sort([{column: 0, desc: false}])

            var options = {
                isStacked : true,
                fontName: 'Nunito',
                fontColor:'#32477C',
                bar: { groupWidth: '75%' },

                colors: ['#172969','#F48432','#A5A5A5'],
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
                    fontSize: 12,
                    bold:true,
                    color:'#32477C'
                }
                },
                vAxis: {
                title: '',
                textStyle: {
                    fontSize: 12,
                    bold:true,
                    color:'#32477C'
                }
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
                      color: '#32477C'
                  }
                },
                tooltip: { 
                    textStyle: {  
                        color:'#32477C', 
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

      setTimeout(function() {
          var tabla_checklistreporte = $.fn.dataTable.tables({visible: true});
          if (tabla_checklistreporte.length > 0) {
              $(tabla_checklistreporte).DataTable().columns.adjust().draw();
          }
      }, 200);

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

<div class="content mt-2" style="padding: 2px 10px; background-color: #F9FAFB;">
    <div class="form-row">
        <div class="col-12  col-md-6 col-lg-3">
            <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="padding: 0.15rem 1rem!important;">
                <li class="breadcrumb-item active" aria-current="page" style="padding: 0.15rem 1rem!important;"><a href="" style="color:#32477C;font-size: 1rem;font-weight: bold;">RCH - Reporte checklist</a></li>
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
</div>       

<center><i id='load' class='fa-solid fa-circle-notch fa-spin fa-8x text-center'  style='margin-top:170px;color:#32477C;opacity: .8;margin-bottom: 800px;'></i></center>
    <div class="mt-2">
        <div class="form-row body no-gutters">
            <div class="col-12 col-lg-6">
                <div class="card">
               <!--  <div class="card-header card_dash">
                    <div class="form-row">
                        <div class="col-12 col-lg-3">
                            <span class="titulo_seccion"></span>
                        </div>

                        <div class="col-8 col-lg-6">  
                            <input type="text" placeholder="Busqueda" id="buscador" class="buscador form-control form-control-sm">
                        </div>

                        <div class="col-4 col-lg-3">  
                            <button type="button"  class="btn-block btn btn-sm btn-primary excel_calidad btn_xr3">
                            <i class="fa fa-save"></i> Excel
                            </button>
                        </div>
                    </div>
                </div> -->

                <div class="form-row">
                
                    <div class="col-lg-12 px-3">
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

            <div class="col-12 col-lg-6 pl-lg-2">
                <div class="card">
                <div class="form-row">
                    
                    <div class="col-lg-12 graficoChecklistReporte">
                   <!--  <div class="card-header card_dash">
                        <span class="titulo_seccion"></span>
                    </div> -->
                    <div id="graficoChecklistReporte"></div>
                    </div>

                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br><br><br><br>