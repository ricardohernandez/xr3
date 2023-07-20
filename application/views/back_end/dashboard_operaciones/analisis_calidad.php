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
  const mes_inicio_analisis_cal = "<?php echo $mes_inicio ?>"
  $("#mes_inicio_analisis_cal").val(mes_inicio_analisis_cal) 
  const mes_termino_analisis_cal = "<?php echo $mes_termino ?>"
  $("#mes_termino_analisis_cal").val(mes_termino_analisis_cal) 

  cargarGraficoAnalisisCalidad()

  function cargarGraficoAnalisisCalidad() {
    var mes_inicio_analisis_cal = $("#mes_inicio_analisis_cal").val();
    var mes_termino_analisis_cal = $("#mes_termino_analisis_cal").val();
    var zona = $("#zona_analisis_cal").val();
    var comuna = $("#comuna_analisis_cal").val();
    var supervisor = $("#supervisor_analisis_cal").val();
    var tecnologia = $("#tecnologia_analisis_cal").val();

    $.ajax({
      url: base_url+'graficoAnalisisCalidad',
      type: 'POST',
      data: {
          'mes_inicio_analisis_cal': mes_inicio_analisis_cal,
          'mes_termino_analisis_cal': mes_termino_analisis_cal,
          'zona': zona,
          'comuna': comuna,
          'supervisor': supervisor,
          'tecnologia': tecnologia,
      },
      dataType: "json",
      beforeSend:function(){
        $("#load").show()
        $(".body").hide()  
      }, 
      success: function (response) {
        $("#load").hide()
        $(".body").fadeIn(500)
        crearGraficoAnalisisCalidad(response.data);
        crearGraficoAnalisisCalidadTotal(response.total);
      },
      error: function (error) {
          console.log(error);
      }
  });
  }
 
  function crearGraficoAnalisisCalidad(data) {
    let size = Object.keys(data).length
    console.log(size)

    if(size==1){
      $("#analisis_calidad").html('<p class="no_data_found">Sin datos encontrados</p>')
      return
    }
    
    var data = google.visualization.arrayToDataTable(data);

    const options = {
    /*   isStacked: true, */
      fontName: 'ubuntu',
      curveType: 'function',
      fontColor: '#32477C',
      backgroundColor: { fill: 'transparent' },
      colors: ['#F48432', '#2f81f7'],
      /*   bar: {groupWidth: "50%"}, */
      chartArea: {
        left: 60,
        right: 40,
        bottom: 180,
        top: 40,
      },
      height: 480,
      hAxis: {
        title: '',
        minValue: 0,
        slantedText: true, 
        slantedTextAngle: 90 ,
        textStyle: {
          fontSize: 13,
          bold: false,
          color: '#808080'
        },
        gridlines: {
          color: '',
          count: 0
        }
      },
      vAxis: {
        title: '',
        titleTextStyle: {color: '#808080',italic:false} ,
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

      legend: {
        position: 'top',
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
      vAxes : {
       0: 
        {
        title:'HFC y FTTH',
        textStyle:{color: '#808080',bold:false,fontSize: 12},
          gridlines: {
            color:'',
            count:0,   
            /* minSpacing: 2, 
            interval : [1, 15,20],  */
          },
          
          minorGridlines:{
            color:'#ccc',
            count:5,
          },

          viewWindow: {
            min: 0,
            max: 30 
          },
        },
        1: 
        {
          textStyle:{color: '#808080',bold:false,fontSize: 12},
            gridlines: {color:'', count:0},
            viewWindow: {
              min: 0,
            max: 10
            },
          }
      },
    };
  
    chart = new google.visualization.ColumnChart(document.getElementById("analisis_calidad"));
    chart.draw(data, options);
  }


  function crearGraficoAnalisisCalidadTotal(data) {
    let size = Object.keys(data).length

    if(size==1){
      $("#analisis_calidad_total").html('<p class="no_data_found">Sin datos encontrados</p>')
      return
    }
    
    var data = google.visualization.arrayToDataTable(data);

    const options = {
      /* isStacked: true, */
      fontName: 'ubuntu',
      curveType: 'function',
      fontColor: '#32477C',
      backgroundColor: { fill: 'transparent' },
      colors: ['#12239E'],
      /*   bar: {groupWidth: "50%"}, */
      chartArea: {
        left: 60,
        right: 40,
        bottom: 180,
        top: 40,
      },
      height: 480,
      hAxis: {
        title: 'asd',
        minValue: 0,
       /*  direction: -1,  */
        slantedText: true, 
        slantedTextAngle: 90 ,
        textStyle: {
          fontSize: 13,
          bold: false,
          color: '#808080'
        },
        gridlines: {
          color: '',
          count: 0
        }
      },
      vAxis: {
        title: 'asd',
        titleTextStyle: {color: '#808080',italic:false} ,

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

      legend: {
        position: 'top',
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

         
      vAxes : {
       0: 
        {
        title:'Total general',
        textStyle:{color: '#808080',bold:false,fontSize: 12},
          gridlines: {
            color:'',
            count:0,   
            /* minSpacing: 2, 
            interval : [1, 15,20],  */
          },
          
          minorGridlines:{
            color:'#ccc',
            count:5,
          },

          viewWindow: {
            min: 0,
            max: 30 
          },
        },
        1: 
        {
            title:'Comuna Mes',
            textStyle:{color: '#808080',bold:false,fontSize: 12},
            gridlines: {color:'', count:0},
            viewWindow: {
              min: 0,
              max: 10
            },
          }
      },
    };
  
    chart = new google.visualization.ColumnChart(document.getElementById("analisis_calidad_total"));
    chart.draw(data, options);
  }

  $(document).off('change', '#mes_inicio_analisis_cal,#mes_termino_analisis_cal,#zona_analisis_cal,#comuna_analisis_cal,#supervisor_analisis_cal,#tecnologia_analisis_cal').on('change', '#mes_inicio_analisis_cal,#mes_termino_analisis_cal,#zona_analisis_cal,#comuna_analisis_cal,#supervisor_analisis_cal,#tecnologia_analisis_cal', function (event) {
    cargarGraficoAnalisisCalidad();
    cargarGraficoAnalisisCalidadTotal();
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
        <input type="month" placeholder="Desde" class=" form-control form-control-sm"  id="mes_inicio_analisis_cal">
        <input type="month" placeholder="Hasta" class=" form-control form-control-sm"  id="mes_termino_analisis_cal">
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-2">
    <div class="form-group">
    <select id="zona" name="zona" class="custom-select custom-select-sm">
      <option value="">Zona</option>
      <?php 
        foreach($zonas as $z){
          ?>
           <option value="<?php echo $z["zona"]?>"><?php echo $z["zona"]?></option>
          <?php
        }
      ?>
    </select>
    </div>
  </div>

  <div class="col-12 col-lg-2">
    <div class="form-group">
    <select id="comuna_analisis_cal" name="comuna" class="custom-select custom-select-sm">
      <option value="">Comuna</option>
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
    <select id="supervisor_analisis_cal" name="supervisor" class="custom-select custom-select-sm">
      <option value="">Supervisor</option>
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
    <select id="tecnologia_analisis_cal" name="tecnologia" class="custom-select custom-select-sm">
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

<div class="row mt-2 contenedor_graficos">
  <div class="col-12">
    <div class="card">
      <div class="col-12">
          <p class="titulo_grafico">% Analisis de calidad</p>
          <div id="analisis_calidad"></div>
        </div>
      </div>
  </div>
  
  <div class="col-12 mt-2">
    <div class="card">
      <div class="col-12">
          <p class="titulo_grafico">% Total general de calidad</p>
          <div id="analisis_calidad_total"></div>
        </div>
      </div>
  </div>

</div>