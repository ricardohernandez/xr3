<style type="text/css">
  .titulo_grafico{
	  color: #32477C;
      font-size: 16px; 
      font-weight:bold;
      text-align: center;
    }
</style>

<script type="text/javascript" charset="utf-8"> 

  var url="<?php echo base_url()?>";
  google.charts.setOnLoadCallback(drawChart);
  google.charts.setOnLoadCallback(drawChart2);

  function drawChart() {
    $.ajax({
        url: url+"graphRequerimientos"+"?"+$.now(),  
        type: 'POST',
        data: {tipo:$("#tipo_r").val()},
        dataType:"json",
        success: function (datos) {
           var data = google.visualization.arrayToDataTable(datos.data);
           var options = {
            isStacked: true,
            fontName: 'ubuntu',
            fontColor:'#808080',
            bar: { groupWidth: '75%' },
            backgroundColor: { fill:'transparent' },
            colors: ['#32477C', 'red', 'grey'],
            chartArea:{
              left:50,
              right:50,
              bottom:30,
              top:60,
            },
            height:280,
            hAxis: {
            title: '',
            minValue: 0,
            textStyle: {
              fontSize: 12,
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
              fontSize: 12,
              bold:false,
              color:'#808080'
            },
            gridlines: {
              color: '#808080',
              count:0
                    }
            },
            annotations: {
              alwaysOutside: false,
              textStyle: {
                fontSize: 12,
                color:'#808080',
                auraColor: 'none'
              }
            },
            legend : {
              position: 'top', alignment: 'center' ,
              textStyle: {
                fontSize: 13,
                bold:false,
                color:'#808080'
              }
            },
            tooltip: { 
              textStyle: {  
                color:'#808080', 
                fontSize: 13
              }
            },       
				  };
           var chart = new google.visualization.ColumnChart(document.getElementById('requerimientos_mes'));
           chart.draw(data, options);
          }
        });
      }
      function drawChart2() {
        $.ajax({
        url: url+"graphRequerimientosSeg"+"?"+$.now(),  
        type: 'POST',
        data: {tipo:$("#tipo_r").val()},
        dataType:"json",
        success: function (datos) {
           var data = google.visualization.arrayToDataTable(datos.data);
           var options = {
					isStacked: true,
					fontName: 'ubuntu',
					fontColor:'#808080',
					bar: { groupWidth: '75%' },
					backgroundColor: { fill:'transparent' },
					colors: ['#32477C', 'red', 'grey'],
					chartArea:{
						left:50,
						right:50,
						bottom:30,
						top:60,
					},

					height:280,
					hAxis: {
					title: '',
					minValue: 0,
					textStyle: {
						fontSize: 12,
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
						fontSize: 12,
						bold:false,
						color:'#808080'
					},
					gridlines: {
						color: '#808080',
						count:0
                	}
					},
					annotations: {
						alwaysOutside: false,
						textStyle: {
							fontSize: 12,
							color:'#808080',
							auraColor: 'none'
						}
					},
					legend : {
						position: 'top', alignment: 'center' ,
						textStyle: {
							fontSize: 13,
							bold:false,
							color:'#808080'
						}
					},
					tooltip: { 
						textStyle: {  
							color:'#808080', 
							fontSize: 13
						}
					},       

				};
           
           var chart = new google.visualization.ColumnChart(document.getElementById('num_requerimientos'));
           chart.draw(data, options);
          }
        });
      }
      
      $(document).off('change', '#tipo_r').on('change', '#tipo_r',function(event) { //Función filtro por estado
        drawChart();
        drawChart2();
      }); 
      
    </script>

<!-- FILTROS -->

<div class="form-row">
  <div class="col-lg-2">  
    <div class="form-group">
      <select id="tipo_r" name="tipo_r" class="custom-select custom-select-sm">
          <option value="" selected>Seleccione tipo / Todos</option>
            <?php
            foreach($tipos as $t){
            ?>
            <option value="<?php echo $t["id"] ?>"><?php echo $t["tipo"] ?></option>
            <?php
            }
          ?>
      </select>
      </div>
  </div>
</div>  

<div>
  <center>
    <div class="short-div">
      <h6 class="titulo_grafico">Requerimientos por mes <?php echo $desde; ?> - <?php echo $hasta; ?></h6>
      <div id="requerimientos_mes"></div>
	  </div>
    <div class="short-div">
    <h6 class="titulo_grafico">Cantidad de requerimientos <?php echo $desde; ?> - <?php echo $hasta; ?></h6>
      <div id="num_requerimientos"></div>
	  </div>
  </center>
</div>