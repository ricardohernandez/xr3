<style type="text/css">
	.titulo_grafico{
	  color: #32477C;
      font-size: 16px; 
      font-weight:bold;
      text-align: center;
    }
</style>
<script type="text/javascript">
	base_url = "<?php echo base_url() ?>"
	google.charts.setOnLoadCallback(graficoEstadosChecklist);
	google.charts.setOnLoadCallback(graficoTecnicos);

    function graficoEstadosChecklist(){
	    $.ajax({
        url: base_url+"dataEstadosChecklist"+"?"+$.now(),  
        type: 'POST',
        data:{},
        dataType:"json",
        success: function (json) {
       		var data = google.visualization.arrayToDataTable(json);

        	var options = {
	          fontName: 'Nunito',
	          height:580,
	     	  sliceVisibilityThreshold:0,
	          format: 'short',
	          isStacked: 'percent',
	          pieSliceText: 'value',
		  
	          legend: {
	            position: 'labeled',
	            'alignment':'center',
	            textStyle: {
		            fontSize: 13,
		            bold:true,
		            color:'#32477C'
	            }
	          }, 

	  		  titlePosition: 'none',
	          titleTextStyle: {
		        color: '#32477C',
		        fontSize: '14', 
		        fontWidth: 'normal',
		        bold:true
		      },

	          colors: ['green', 'red'],
	          chartArea:{
	             left:150,
	             right:150,
	             bottom:150,
	             top:150,
	          },
	        };

            var chart = new google.visualization.PieChart(document.getElementById('graficoEstadosChecklist'));
			chart.draw(data, options);
        }
	    })
	}


	function graficoTecnicos(){
	    $.ajax({
        url: base_url+"dataTecnicos"+"?"+$.now(),  
        type: 'POST',
        data:{},
        dataType:"json",
        success: function (json) {
       		var data = google.visualization.arrayToDataTable(json);
       		data.sort([{column: 0, desc: false}])

        	 var options = {
        	 	fontName: 'Nunito',
        	 	fontColor:'#32477C',
		        colors: ['green', 'red'],
		        chartArea:{
		             left:210,
		             right:110,
		             bottom:30,
		             top:30,
		        },

		        height:580,
		        hAxis: {
		          title: '',
		          minValue: 0
		        },
		        vAxis: {
		          title: '',
		          textStyle: {
		            fontSize: 13,
		            bold:true,
		            color:'#32477C'
	              }
		        }
		      };

            var chart = new google.visualization.BarChart(document.getElementById('graficoTecnicos'));
			chart.draw(data, options);
        }
	    })
	}

</script>

	
<div class="row">
    <div class="col mb-2">
       <!--  <div class="card border-left-primary shadow mb-2"> -->

          <!--   <div class="card-body" style="padding: .4rem;"> -->
                <div class="row">
                  <div class="col-xs-12 col-lg-6">
					<h6 class="titulo_grafico">Resultados por técnico </h6>
			    	<div id="graficoTecnicos"></div>
				  </div>

                  <div class="col-xs-12 col-lg-6">
					<h6 class="titulo_grafico">Estados checklist</h6>
			    	<div id="graficoEstadosChecklist"></div>
				  </div>

                </div>
           <!--  </div> -->
        <!-- </div> -->
    </div>
</div>

