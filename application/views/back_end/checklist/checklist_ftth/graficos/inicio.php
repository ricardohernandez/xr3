 
<script type="text/javascript">
	base_url = "<?php echo base_url() ?>"
	/* google.charts.setOnLoadCallback(graficoEstadosChecklistFTTH); */
	google.charts.setOnLoadCallback(graficoTecnicosFTTH);
	google.charts.setOnLoadCallback(graficoTecnicosFTTH);
	google.charts.setOnLoadCallback(graficoAuditorias);
	google.charts.setOnLoadCallback(graficoAuditoriasq);
	google.charts.setOnLoadCallback(graficoAuditoriasTecnicosq);
	google.charts.setOnLoadCallback(graficoAuditoriasTecnicos);
	google.charts.setOnLoadCallback(graficoAuditoresFTTH);
	
	$(document).off('change', '#auditor_gm').on('change', '#auditor_gm', function(event) {
        graficoAuditorias()
        graficoAuditoriasq()
    }); 

	$(document).off('change', '#zona_gm').on('change', '#zona_gm', function(event) {
        graficoAuditorias()
		graficoAuditoriasq()
    }); 

	$(document).off('change', '#comuna_gm').on('change', '#comuna_gm', function(event) {
        graficoAuditorias()
		graficoAuditoriasq()
    }); 

	$(document).off('change', '#tecnico_gmt').on('change', '#tecnico_gmt', function(event) {
        graficoAuditoriasTecnicosq()
        graficoAuditoriasTecnicos()
    }); 

	$(document).off('change', '#zona_gmt').on('change', '#zona_gmt', function(event) {
        graficoAuditoriasTecnicosq()
		graficoAuditoriasTecnicos()
    }); 

	$(document).off('change', '#comuna_gmt').on('change', '#comuna_gmt', function(event) {
        graficoAuditoriasTecnicosq()
		graficoAuditoriasTecnicos()
    }); 


	function graficoAuditoriasq(){
		const auditor_gm = $("#auditor_gm").val()
		const zona_gm = $("#zona_gm").val()
		const comuna_gm = $("#comuna_gm").val()

	    $.ajax({
        url: base_url+"graficoAuditoriasDataFTTHQ"+"?"+$.now(),  
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
				data.sort([{column: 3, desc: false}])

				var options = {
					fontName: 'ubuntu',
					fontColor:'#808080',
					bar: { groupWidth: '75%' },

					colors: ['#32477C'],
					backgroundColor: { fill:'transparent' },
					chartArea:{
						left:50,
						right:50,
						bottom:30,
						top:20,
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
						position: 'none', alignment: 'center' ,
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
				var chart = new google.visualization.ColumnChart(document.getElementById('graficoAuditoriasq'));
				chart.draw(data, options);

			}  else {

				$.notify('Sin datos para los parametros especificados.', {
					className:'error',
					globalPosition: 'top right',
					autoHideDelay:5000,
				});

			} 

       		
        }
	    })
	}

	function graficoAuditorias(){
		const auditor_gm = $("#auditor_gm").val()
		const zona_gm = $("#zona_gm").val()
		const comuna_gm = $("#comuna_gm").val()

	    $.ajax({
        url: base_url+"graficoAuditoriasDataFTTH"+"?"+$.now(),  
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
				data.sort([{column: 5, desc: false}])

				var options = {
					isStacked: true,
					fontName: 'ubuntu',
					fontColor:'#808080',
					bar: { groupWidth: '75%' },

					colors: ['#32477C', 'red', 'grey'],
					backgroundColor: { fill:'transparent' },
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
				var chart = new google.visualization.ColumnChart(document.getElementById('graficoAuditorias'));
				chart.draw(data, options);

			} else {

				/* $.notify('Sin datos para los parametros especificados.', {
					className:'error',
					globalPosition: 'top right',
					autoHideDelay:5000,
				}); */

			}

       		
        }
	    })
	}

	function graficoAuditoriasTecnicosq(){
		const tecnico_gmt = $("#tecnico_gmt").val()
		const zona_gmt = $("#zona_gmt").val()
		const comuna_gmt = $("#comuna_gmt").val()

	    $.ajax({
        url: base_url+"graficoAuditoriasDataFTTHTecnicoQ"+"?"+$.now(),  
        type: 'POST',
        data:{'tecnico_gmt':tecnico_gmt,'zona_gmt':zona_gmt,'comuna_gmt':comuna_gmt},
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
				data.sort([{column: 3, desc: false}])

				var options = {
					fontName: 'ubuntu',
					fontColor:'#808080',
					bar: { groupWidth: '75%' },

					colors: ['#32477C'],
					backgroundColor: { fill:'transparent' },
					chartArea:{
						left:50,
						right:50,
						bottom:30,
						top:20,
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
						position: 'none', alignment: 'center' ,
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
				var chart = new google.visualization.ColumnChart(document.getElementById('graficoAuditoriasTecnicosq'));
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

	function graficoAuditoriasTecnicos(){
		const tecnico_gmt = $("#tecnico_gmt").val()
		const zona_gmt = $("#zona_gmt").val()
		const comuna_gmt = $("#comuna_gmt").val()

	    $.ajax({
        url: base_url+"graficoAuditoriasDataFTTHTecnico"+"?"+$.now(),  
        type: 'POST',
        data:{'tecnico_gmt':tecnico_gmt,'zona_gmt':zona_gmt,'comuna_gmt':comuna_gmt},
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
				data.sort([{column: 5, desc: false}])

				var options = {
					isStacked: true,
					fontName: 'ubuntu',
					fontColor:'#808080',
					bar: { groupWidth: '75%' },

					colors: ['#32477C', 'red', 'grey'],
					backgroundColor: { fill:'transparent' },
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
				var chart = new google.visualization.ColumnChart(document.getElementById('graficoAuditoriasTecnicos'));
				chart.draw(data, options);

			} else {
				/* $.notify('Sin datos para los parametros especificados.', {
					className:'error',
					globalPosition: 'top right',
					autoHideDelay:5000,
				}); */
			}
        }
	    })
	}

	function graficoTecnicosFTTH(){
	    $.ajax({
        url: base_url+"dataTecnicosChecklistFTTH"+"?"+$.now(),  
        type: 'POST',
        data:{},
        dataType:"json",
        success: function (json) {
       		var data = google.visualization.arrayToDataTable(json);
       		data.sort([{column: 1, desc: true}])

        	var options = {
        	 	fontName: 'ubuntu',
        	 	fontColor:'#808080',
        	 	bar: { groupWidth: '75%' },

	            colors: ['#32477C'],
				backgroundColor: { fill:'transparent' },
		        chartArea:{
		             left:280,
		             right:50,
		             bottom:30,
		             top:20,
		        },

		        height:1700,
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
			        alwaysOutside: true,
			        textStyle: {
			            fontSize: 12,
						color:'#808080',
			            auraColor: 'none'
			        }
			    },
			    legend : {
					position: 'none', alignment: 'center' ,
			        textStyle: {
			            fontSize: 14,
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

            var chart = new google.visualization.BarChart(document.getElementById('graficoTecnicos'));
			chart.draw(data, options);
        }
	    })
	}

	function graficoAuditoresFTTH(){
	    $.ajax({
        url: base_url+"dataAuditoresChecklistFTTH"+"?"+$.now(),  
        type: 'POST',
        data:{},
        dataType:"json",
        success: function (json) {
       		var data = google.visualization.arrayToDataTable(json);
       		data.sort([{column: 1, desc: true}])

        	var options = {
        	 	fontName: 'ubuntu',
        	 	fontColor:'#808080',
        	 	bar: { groupWidth: '75%' },

	            colors: ['#32477C'],
				backgroundColor: { fill:'transparent' },
		        chartArea:{
		             left:280,
		             right:50,
		             bottom:30,
		             top:20,
		        },

		        height:380,
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
			        alwaysOutside: true,
			        textStyle: {
			            fontSize: 12,
						color:'#808080',
			            auraColor: 'none'
			        }
			    },
			    legend : {
					position: 'none', alignment: 'center' ,
			        textStyle: {
			            fontSize: 14,
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

            var chart = new google.visualization.BarChart(document.getElementById('graficoAuditores'));
			chart.draw(data, options);
        }
	    })
	}

    function graficoEstadosChecklistFTTH(){
	    $.ajax({
        url: base_url+"dataEstadosChecklistFTTH"+"?"+$.now(),  
        type: 'POST',
        data:{},
        dataType:"json",
        success: function (json) {
       		var data = google.visualization.arrayToDataTable(json);

        	var options = {
	          fontName: 'ubuntu',
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
		            bold:false,
		            color:'#808080'
	            }
	          }, 

	  		  titlePosition: 'none',
	          titleTextStyle: {
		        color: '#808080',
		        fontSize: '14', 
		        fontWidth: 'normal',
		        bold:false
		      },

	          colors: ['#32477C', 'red', 'grey'],
			  backgroundColor: { fill:'transparent' },
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


</script>

<div class="card p-2">
	
<div class="row">
    <div class="col mb-2">
		<div class="row">
			<div class="col-12">
				<div class="form-row">
					<div class="col-md-6 offset-md-3">
						<div class="form-row">
							<div class="col-12 col-lg-4">
								<div class="form-group">
								<select id="tecnico_gmt" name="tecnico_gmt" class="custom-select custom-select-sm">
									<option value="" selected>Seleccione Técnico | Todos </option>
									<?php  
										foreach($tecnicos as $tec){
											?>
												<option value="<?php echo $tec["id"]?>" ><?php echo $tec["nombre_completo"]?> </option>
											<?php
										}
									?>
								</select>
								</div>
							</div>

							<div class="col-12 col-lg-4">
								<div class="form-group">
								<select id="zona_gmt" name="zona_gmt" class="custom-select custom-select-sm">
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

							<div class="col-12 col-lg-4">
								<div class="form-group">
								<select id="comuna_gmt" name="comuna_gmt" class="custom-select custom-select-sm">
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
			
			<div class="row">
				<div class="col-12 col-lg-12">
					<p class="title_section mt-3">Auditorias por técnico mensuales cantidad</p>
					<div id="graficoAuditoriasTecnicosq"></div>
				</div>

				<div class="col-6 col-lg-12">
					<p class="title_section mt-3">Auditorias por técnico mensuales detalle</p>
					<div id="graficoAuditoriasTecnicos"></div>
				</div>
			</div>

			</div>
		</div>

		<div class="row mt-3">
			<div class="col-12">
				<div class="form-row">
					<div class="col-md-6 offset-md-3">
						<div class="form-row">
							<div class="col-12 col-lg-4">
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

							<div class="col-12 col-lg-4">
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

							<div class="col-12 col-lg-4">
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
			</div>
		</div>
		
		<div class="row">
			<div class="col-12 col-lg-6">
				<p class="title_section mt-3">Auditorias por auditor mensuales cantidad</p>
				<div id="graficoAuditoriasq"></div>
			</div>

			<div class="col-12 col-lg-6">
				<p class="title_section mt-3">Auditorias por auditor mensuales detalle</p>
				<div id="graficoAuditorias"></div>
			</div>
		</div>

		<div class="row">
			<div class="col-12 col-lg-6mt-3">
				<p class="title_section">Auditorias por técnico </p>
				<div id="graficoTecnicos"></div>
			</div>

			<div class="col-12 col-lg-6mt-3">
				<p class="title_section">Auditorias por auditor </p>
				<div id="graficoAuditores"></div>
				<!-- <p class="title_section">Estados checklist</p>
				<div id="graficoEstadosChecklist"></div> -->
			</div>
		</div>
    </div>
</div>
</div>