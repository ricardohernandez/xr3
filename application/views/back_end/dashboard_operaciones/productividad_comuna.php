<style>
  .body{
    display: none;
  }
  .no_data_found{
    font-size:1.4rem;
    margin:250px auto;
    text-align:center;
  }

  #graficoXComuna {
    overflow-x: scroll;
    overflow-y: hidden;
    width: 100%;
  }

 </style>
<script>
  const base_url = "<?php echo base_url() ?>"
  const mes_inicio_prod_comuna= "<?php echo $mes_inicio ?>"
  $("#mes_inicio_x_comuna").val(mes_inicio_prod_comuna) 
  const mes_termino_prod_comuna= "<?php echo $mes_termino ?>"
  $("#mes_termino_x_comuna").val(mes_termino_prod_comuna) 

  cargarGrafico()

  function cargarGrafico () {
    const mes_inicio = $("#mes_inicio_x_comuna").val()
    const mes_termino = $("#mes_termino_x_comuna").val()
    const comuna = $("#comuna_x_comuna").val()
    const tecnologia = $("#tecnologia_x_comuna").val()
    const zona = $("#zona_x_comuna").val()
    const empresa = $("#empresa").val()
    
    $.ajax({
        url: base_url+'graficoXComuna',
        type: 'POST',
        data: {
          'mes_inicio': mes_inicio,
          'mes_termino': mes_termino,
          'comuna': comuna,
          'zona': zona,
          'tecnologia': tecnologia,
          'empresa': empresa
        },
        dataType: "json",
        beforeSend:function(){
          $("#load").show()
          $(".body").hide()  
        }, 
        success: function (response) {
          $("#load").hide()
          $(".body").fadeIn(500)

          if(response.res=="error"){
              $.notify(response.msg, {
                  className:response.res,
                  globalPosition: 'top center',
                  autoHideDelay: 20000,
              });
              $("#graficoXComuna").html('<p class="no_data_found">Sin datos encontrados</p>')

          }else{
            const empresa = $("#empresa").val();
            crearGrafico('prod_comuna', response.prod_comuna, 'column', empresa === "xr3" ? "XR3" : (empresa === "emetel" ? "EMETEL" : "XR3 y EMETEL"));
          }
        
        },
        error: function (error) {
            console.log(error);
        }
    });
  }
 
  function crearGrafico(divId, data, tipoGrafico,title) {
    let size = Object.keys(data).length

    switch (true) {
      case size < 10:
        cant = 0;
        break;
      case size >= 11 && size <= 40:
        cant = 500;
        break;
      case size >= 41 && size <= 80:
        cant = 1000;
        break;
      case size >= 81 && size <= 120:
        cant = 2000;
        break;
      default:
        cant = 2500;
    }

    if(size==1){
      $("#graficoXComuna").html('<p class="no_data_found">Sin datos encontrados</p>')
      return
    }
     
    const cont = document.getElementById('contenedor_graficos')
    const ancho = cont.offsetWidth+cant

    var data = google.visualization.arrayToDataTable(data)

    const options = {
      fontName: 'ubuntu',
      fontColor: '#32477C',
      backgroundColor: { fill: 'transparent' },
      bar: { groupWidth: '90%' },
      colors: ['#12239E', '#E66C37'],
      chartArea: {
        left: 70,
        right: 40,
        bottom: 170,
        top: 40,
      },
      width: ancho,
      height: 550,
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
        position: 'top',
        alignment: 'start',
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
          textStyle:{color: '#808080',bold:false,fontSize: 12},
          //gridlines: {color:'#ccc', count:5},
          viewWindowMode:'explicit',
          viewWindow: {
            min: 0,
            max: 10
          },
        },
       },

    };

    if(title == "EMETEL"){
      options.series = 
      {
        0: {
          lineDashStyle: [4, 4], 
          color: '#E66C37',
           lineWidth: 1,
          pointSize: 1,
          pointShape: 'square',
          targetAxisIndex: 1,

        }
      }
      
    }
    else if(title == "XR3"){
      options.series =
      {
        0: {
          lineDashStyle: [4, 4], 
          color: '#12239E',
           lineWidth: 1,
          pointSize: 1,
          pointShape: 'square',
          targetAxisIndex: 1,
        }
      }
    }

    var chart = new google.visualization.ColumnChart(document.getElementById("graficoXComuna"));

    chart.draw(data, options);
  }

  $(document).off('change', '#mes_inicio_x_comuna, #mes_termino_x_comuna, #comuna_x_comuna, #zona_x_comuna, #tecnologia_x_comuna, #empresa').on('change', '#mes_inicio_x_comuna, #mes_termino_x_comuna, #comuna_x_comuna, #zona_x_comuna, #tecnologia_x_comuna, #empresa', function (event) {
    cargarGrafico();
  });



</script>

<!-- FILTROS -->
  
<div class="form-row" id="contenedor_filtros_c">

  <div class="col-12 col-lg-3">
    <div class="form-group">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="font-size:12px;margin-left:5px;"> Meses<span></span> 
        </div>
        <input type="month" placeholder="Desde" class=" form-control form-control-sm"  name="mes_inicio_x_comuna" id="mes_inicio_x_comuna">
        <input type="month" placeholder="Hasta" class=" form-control form-control-sm"  name="mes_termino_x_comuna" id="mes_termino_x_comuna">
      </div>
    </div>
  </div>

  <div class="col-12 col-lg-2">
    <div class="form-group">
    <select id="comuna_x_comuna" name="comuna_x_comuna" class="custom-select custom-select-sm">
      <option value="">Seleccione comuna</option>
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
    <select id="zona_x_comuna" name="zona_x_comuna" class="custom-select custom-select-sm">
      <option value="">Seleccione zona</option>
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
    <select id="tecnologia_x_comuna" name="tecnologia_x_comuna" class="custom-select custom-select-sm">
      <option value="" selected>Seleccione Tecnología</option>
      <?php 
        foreach($tecnologias as $t){
          ?>
          <option value="<?php echo $t["tecnologia"]?>"><?php echo $t["tecnologia"]?></option>
          <?php
        }
      ?>
    </select>
    </div>
  </div>

  <div class="col-12 col-lg-2">
    <div class="form-group">
    <select id="empresa" name="empresa" class="custom-select custom-select-sm">
      <option value="" selected>Seleccione empresa</option>
      <option value="xr3">XR3</option>
      <option value="emetel">EMETEL</option>
    </select>
    </div>
  </div>

</div>   

<div style="text-align: center;;">
  <i id="load" class="fa-solid fa-circle-notch fa-spin fa-8x" style="color: #1A56DB; opacity: .4;margin-top:250px"></i>
</div>

<div class="body">
  <div class="form-row mt-2 contenedor_graficos" id="contenedor_graficos">
    <div class="col-12">
      <div class="card">
        <div class="col-12">
            <p class="titulo_grafico"> Comparación de producción por comuna y empresa </p>
              <div id="graficoXComuna"></div>
          </div>
        </div>
    </div>
 
  </div>
</div>