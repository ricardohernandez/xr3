 <style>
  .body{
    display: none;
  }
  .no_data_found{
    font-size:1.4rem;
    margin:250px auto;
    text-align:center;
  }
 </style>
<script>
  const base_url = "<?php echo base_url() ?>"
  const mes_inicio_cal_claro = "<?php echo $mes_inicio ?>"
  $("#mes_inicio__al_claro").val(mes_inicio_cal_claro) 
  const mes_termino_cal_claro = "<?php echo $mes_termino ?>"
  $("#mes_termino_cal_claro").val(mes_termino_cal_claro) 

  cargarGrafico()

  function cargarGrafico () {
    const mes_inicio = $("#mes_inicio_cal_claro").val()
    const mes_termino = $("#mes_termino_cal_claro").val()
    const comuna = $("#comuna_cal_claro").val()
    const supervisor = $("#supervisor_cal_claro").val()
    const tecnologia = $("#tecnologia_cal_claro").val()

    $.ajax({
        url: base_url+'graficoProdxEps',
        type: 'POST',
        data: {
            'mes_inicio_cal_claro': mes_inicio,
            'mes_termino_cal_claro': mes_termino,
            'comuna': comuna,
            'supervisor': supervisor,
            'tecnologia': tecnologia
        },
        dataType: "json",
        beforeSend:function(){
          $("#load").show()
          $(".body").hide()  
        }, 
        success: function (response) {
          $("#load").hide()
          $(".body").fadeIn(500)
          crearGrafico('prod_ciudad', response.prod_ciudad, 'column','Suma de PX');
          crearGrafico('prod_general', response.prod_general, 'column','PX XR3,PX RED CELL,PX ALIANZA SUR');
          crearGrafico('prod_hfc', response.prod_hfc, 'column','PX XR3 HFC,PX HFC RED CELL,PX HFC ALIANZA SUR');
          crearGrafico('prod_ftth', response.prod_ftth, 'column','PX XR3 FTTH,PX FTTH RED CELL,PX FTTH ALIANZA SUR');
          crearGrafico('calidad_hfc_eps', response.calidad_hfc, 'line','CA XR3 HFC,CA HFC ALIANZA SUR');
          crearGrafico('calidad_ftth_eps', response.calidad_ftth, 'line','CA XR3 HFC,CA HFC ALIANZA SUR');
        },
        error: function (error) {
            console.log(error);
        }
    });
  }
 
  function crearGrafico(divId, data, tipoGrafico,title) {

    let size = Object.keys(data).length
    console.log(size)

    if(size==1){
      $("#prod_ciudad").html('<p class="no_data_found">Sin datos encontrados</p>')
      return
    }
    

    var data = google.visualization.arrayToDataTable(data);

  
    const options = {
      fontName: 'ubuntu',
      curveType: 'function',
      fontColor: '#32477C',
      backgroundColor: { fill: 'transparent' },
      
      colors: (tipoGrafico === 'column') ? 
      ['#12239E', '#E66C37', '#118DFF'] : 
      ['#12239E', '#E66C37'],

      chartArea: {
        left: 70,
        right: 40,
        bottom: (divId === 'prod_ciudad') ? 140 : 40,
        top: (divId === 'prod_ciudad') ? 10 : 40,
      },
 
      height: (divId === 'prod_ciudad') ? 350 : 250,
      hAxis: {
        title: '',
        minValue: 0, 
        slantedText: (divId === 'prod_ciudad') ? true : false, 
        slantedTextAngle: 90 ,
        textStyle: {
          fontSize: (divId === 'prod_ciudad') ? 12 : 13,
          bold: false,
          color: '#808080'
        },
        gridlines: {
          color: '',
          count: 0
        }
      },
      vAxis: {
        title: title,
        titleTextStyle:{
          fontSize: 13,
          bold: false,
          color: '#808080'
        },
        textStyle: {
          fontSize: 13,
          bold: false,
          color: '#808080'
        },
        gridlines: {
          color: '',
          count: 0
        },

      },
      annotations: {
        alwaysOutside: false,
        textStyle: {
          fontSize: 13,
          color: '#808080',
          auraColor: 'none'
        }
      },
      avoidOverlappingGridLines: true,

      legend: {
        position: (divId === 'prod_ciudad') ? 'none' : 'top',
        alignment: 'center',
        textStyle: {
          fontSize: 14,
          bold: true,
          color: '#808080'
        }
      },
      tooltip: {
        textStyle: {
          color: '#ffffff96',
          fontSize: 13
        }
      },
    };

    var chart;
    
    if (tipoGrafico === 'line') {
      chart = new google.visualization.LineChart(document.getElementById(divId));
    } else if (tipoGrafico === 'column') {
      chart = new google.visualization.ColumnChart(document.getElementById(divId));
    }

    chart.draw(data, options);
  }

  $(document).off('change', '#mes_inicio_cal_claro,#mes_termino_cal_claro,#comuna_cal_claro,#supervisor_cal_claro,#tecnologia_cal_claro').on('change', '#mes_inicio_cal_claro,#mes_termino_cal_claro,#comuna_cal_claro,#supervisor_cal_claro,#tecnologia_cal_claro', function (event) {
    cargarGrafico();
  });

</script>

<!-- FILTROS -->
  
<div class="form-row">

  <div class="col-12 col-lg-3">
    <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="font-size:12px;margin-left:5px;"> Meses<span></span> 
        </div>
        <input type="month" placeholder="Desde" class=" form-control form-control-sm"  name="mes_inicio_cal_claro" id="mes_inicio_cal_claro">
        <input type="month" placeholder="Hasta" class=" form-control form-control-sm"  name="mes_termino_cal_claro" id="mes_termino_cal_claro">
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-2">
    <div class="form-group">
    <select id="comuna_cal_claro" name="comuna" class="custom-select custom-select-sm">
      <option value="">Comuna | Todas</option>
      <?php 
        foreach($comunas as $c){
          ?>
           <option value="<?php echo $c["comuna"]?>"><?php echo $c["comuna"]?></option>
          <?php
        }
      ?>
    </select>
    </div>
  </div>

  <div class="col-12 col-lg-2">
    <div class="form-group">
    <select id="supervisor_cal_claro" name="supervisor" class="custom-select custom-select-sm">
      <option value="">Supervisor | Todos</option>
      <?php 
        foreach($supervisores as $s){
          ?>
           <option value="<?php echo $s["supervisor"]?>"><?php echo $s["supervisor"]?></option>
          <?php
        }
      ?>
    </select>
    </div>
  </div>

  <div class="col-12 col-lg-2">
    <div class="form-group">
    <select id="tecnologia_cal_claro" name="tecnologia" class="custom-select custom-select-sm">
      <?php 
        foreach($tecnologias as $t){
          ?>
          <option value="<?php echo $t["tecnologia"]?>" <?php if ($t["tecnologia"] === "HFC") echo "selected"?>><?php echo $t["tecnologia"]?></option>
          <?php
        }
      ?>
    </select>
    </div>
  </div>

</div>   

<div style="text-align: center;;">
  <i id="load" class="fa-solid fa-circle-notch fa-spin fa-8x" style="color: #1A56DB; opacity: .4;margin-top:250px"></i>
</div>

<div class="body">
  <div class="form-row mt-2 contenedor_graficos">
    <div class="col-12">
      <div class="card">
        <div class="col-12">
            <p class="titulo_grafico"> Producción por ciudad </p>
            <div id="prod_ciudad"></div>
          </div>
        </div>
    </div>

    <div class="col-12 mt-2">
      <div class="card">
        <div class="col-12">
            <p class="titulo_grafico"> Producción por EPS </p>
            <div id="prod_general"></div>
          </div>
        </div>
    </div>


    <div class="col-12 col-lg-6 mt-2">
      <div class="card">
        <div class="col-12">
            <p class="titulo_grafico">Producción HFC X EPS </p>
            <div id="prod_hfc"></div>
          </div>
        </div>
    </div>

    <div class="col-12 col-lg-6  mt-2">
      <div class="card">
        <div class="col-12">
            <p class="titulo_grafico">Producción FTTH X EPS</p>
            <div id="prod_ftth"></div>
        </div>
      </div>
    </div>


    <div class="col-12 col-lg-6 mt-2">
      <div class="card">
        <div class="col-12">
            <p class="titulo_grafico">Calidad HFC X EPS </p>
            <div id="calidad_hfc_eps"></div>
          </div>
        </div>
    </div>

    <div class="col-12 col-lg-6  mt-2">
      <div class="card">
        <div class="col-12">
            <p class="titulo_grafico">Calidad FTTH X EPS</p>
            <div id="calidad_ftth_eps"></div>
        </div>
      </div>
    </div>
  </div>
</div>