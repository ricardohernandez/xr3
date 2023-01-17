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
	google.charts.setOnLoadCallback(graficoEstadosChecklistHFC);
	google.charts.setOnLoadCallback(graficoTecnicosHFC);
	google.charts.setOnLoadCallback(graficoAuditorias);

	$(document).off('change', '#auditor_gm').on('change', '#auditor_gm', function(event) {
        graficoAuditorias()
    }); 

	$(document).off('change', '#zona_gm').on('change', '#zona_gm', function(event) {
        graficoAuditorias()
    }); 

	$(document).off('change', '#comuna_gm').on('change', '#comuna_gm', function(event) {
        graficoAuditorias()
    }); 

	function graficoAuditorias(){
		const auditor_gm = $("#auditor_gm").val()
		const zona_gm = $("#zona_gm").val()
		const comuna_gm = $("#comuna_gm").val()

	    $.ajax({
        url: base_url+"graficoAuditoriasDataHFC"+"?"+$.now(),  
        type: 'POST',
        data:{'auditor_gm':auditor_gm,'zona_gm':zona_gm,'comuna_gm':comuna_gm},
        dataType:"json",
        success: function (json) {

			let numArrays = 0;

			for (const element of json) {
				if (Array.isArray(element)) {
					numArrays++;
				}
			}

			if (numArrays > 1) {

				var data = google.visualization.arrayToDataTable(json);
				data.sort([{column: 7, desc: false}])

				var options = {
					isStacked: true,
					fontName: 'Nunito',
					fontColor:'#32477C',
					bar: { groupWidth: '75%' },

					colors: ['green', 'red', 'grey'],
					chartArea:{
						left:50,
						right:50,
						bottom:30,
						top:60,
					},

					height:380,
					hAxis: {
					title: '',
					minValue: 0,
					textStyle: {
						fontSize: 13,
						bold:true,
						color:'#32477C'
					}
					},
					vAxis: {
					title: '',
					textStyle: {
						fontSize: 13,
						bold:true,
						color:'#32477C'
					}
					},
					annotations: {
						alwaysOutside: false,
						textStyle: {
							fontSize: 12,
							auraColor: 'none'
						}
					},
					legend : {
						position: 'top', alignment: 'center' ,
						textStyle: {
							fontSize: 14,
							bold:true,
							color:'#32477C'
						}
					},
					tooltip: { 
						textStyle: {  
							color:'#32477C', 
							fontSize: 13
						}
					},       

				};
				var chart = new google.visualization.ColumnChart(document.getElementById('graficoAuditorias'));
				chart.draw(data, options);

			} else {

				$.notify('Sin datos para los parametros especificados.', {
					className:'error',
					globalPosition: 'top right',
					autoHideDelay:5000,
				});

			}

       		
        }
	    })
	}

    function graficoEstadosChecklistHFC(){
	    $.ajax({
        url: base_url+"dataEstadosChecklistHFC"+"?"+$.now(),  
        type: 'POST',
        data:{},
        dataType:"json",
        success: function (json) {
       		var data = google.visualization.arrayToDataTable(json);

        	var options = {
	          fontName: 'Nunito',
	          height:320,
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

	          colors: ['green', 'red', 'grey'],
	          chartArea:{
	             left:50,
	             right:50,
	             bottom:50,
	             top:50,
	          },
	        };

            var chart = new google.visualization.PieChart(document.getElementById('graficoEstadosChecklist'));
			chart.draw(data, options);
        }
	    })
	}


	function graficoTecnicosHFC(){
	    $.ajax({
        url: base_url+"dataTecnicosChecklistHFC"+"?"+$.now(),  
        type: 'POST',
        data:{},
        dataType:"json",
        success: function (json) {
       		var data = google.visualization.arrayToDataTable(json);
       		data.sort([{column: 1, desc: true}])

        	var options = {
        	 	fontName: 'Nunito',
        	 	fontColor:'#32477C',
        	 	bar: { groupWidth: '75%' },

	            colors: ['green', 'red', 'grey'],
		        chartArea:{
		             left:280,
		             right:50,
		             bottom:30,
		             top:60,
		        },

		        height:2580,
		        hAxis: {
		          title: '',
		          minValue: 0,
		          textStyle: {
		            fontSize: 13,
		            bold:true,
		            color:'#32477C'
	              }
		        },
		        vAxis: {
		          title: '',
		          textStyle: {
		            fontSize: 13,
		            bold:true,
		            color:'#32477C'
	              }
		        },
		        annotations: {
			        alwaysOutside: true,
			        textStyle: {
			            fontSize: 12,
			            auraColor: 'none'
			        }
			    },
			    legend : {
					position: 'top', alignment: 'center' ,

			        textStyle: {
			            fontSize: 14,
			            bold:true,
			            color:'#32477C'
		            }
	            },
	            tooltip: { 
	            	textStyle: {  
	            		color:'#32477C', 
	            		fontSize: 13
	            	}
	            },       

		    };

            var chart = new google.visualization.BarChart(document.getElementById('graficoTecnicos'));
			chart.draw(data, options);
        }
	    })
	}

</script>

	
<div class="row">
    <div class="col mb-2">
		<div class="row">
			<div class="col">
			<div class="form-row">
				<div class="col-md-6 offset-md-3">
				<div class="form-row">
				<div class="col">
					<div class="form-group">
					<select id="auditor_gm" name="auditor_gm" class="custom-select custom-select-sm">
						<option value="" selected>Seleccione Auditor | Todos </option>
						<?php  
							foreach($auditores as $a){
								?>
									<option value="<?php echo $a["id"]?>" ><?php echo $a["nombre_completo"]?> </option>
								<?php
							}
						?>
					</select>
					</div>
				</div>

				<div class="col">
					<div class="form-group">
					<select id="zona_gm" name="zona_gm" class="custom-select custom-select-sm">
						<option value="" selected>Seleccione Zona | Todos </option>
						<?php  
							foreach($zonas as $z){
								?>
									<option value="<?php echo $z["id"]?>" ><?php echo $z["area"]?> </option>
								<?php
							}
						?>
					</select>
					</div>
				</div>

				<div class="col">
					<div class="form-group">
					<select id="comuna_gm" name="comuna_gm" class="custom-select custom-select-sm">
						<option value="" selected>Seleccione Comuna | Todos </option>
						<?php  
							foreach($comunas as $c){
								?>
									<option value="<?php echo $c["id"]?>" ><?php echo $c["proyecto"]?> </option>
								<?php
							}
						?>
					</select>
				</div>
				</div>
				</div>

				</div>
			</div>  

			<h6 class="titulo_grafico mt-3">Auditorias mensuales </h6>
			<div id="graficoAuditorias"></div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12 col-lg-7 mt-3">
			<h6 class="titulo_grafico">Resultados por técnico </h6>
			<div id="graficoTecnicos"></div>
			</div>

			<div class="col-xs-12 col-lg-5 mt-3">
			<h6 class="titulo_grafico">Estados checklist</h6>
			<div id="graficoEstadosChecklist"></div>
			</div>
		</div>
    </div>
</div>

