
<script type="text/javascript">
	const base_url = "<?php echo base_url() ?>"
	const desde="<?php echo $desde; ?>";
    const hasta="<?php echo $hasta; ?>";
    $("#desde_astf").val(desde);
    $("#hasta_astf").val(hasta);

	google.charts.setOnLoadCallback(graficoAstTecnico);
	google.charts.setOnLoadCallback(graficoAstDetalleTecnico);
	google.charts.setOnLoadCallback(graficoTotalTecnicos);
	google.charts.setOnLoadCallback(graficoTotalItems);
						
	$(document).off('change', '#tecnico_gmt').on('change', '#tecnico_gmt', function(event) {
        graficoAstDetalleTecnico()
        graficoAstTecnico()
		graficoTotalTecnicos()
		graficoTotalItems()
    }); 

	$(document).off('change', '#zona_gmt').on('change', '#zona_gmt', function(event) {
        graficoAstDetalleTecnico()
		graficoAstTecnico()
		graficoTotalTecnicos()
		graficoTotalItems()
    }); 

	$(document).off('change', '#comuna_gmt').on('change', '#comuna_gmt', function(event) {
        graficoAstDetalleTecnico()
		graficoAstTecnico()
		graficoTotalTecnicos()
		graficoTotalItems()
    }); 

	
	$(document).off('change', '#comuna_gmt').on('change', '#comuna_gmt', function(event) {
        graficoAstDetalleTecnico()
		graficoAstTecnico()
		graficoTotalTecnicos()
		graficoTotalItems()
    }); 


	$(document).off('change', '#desde_astf').on('change', '#desde_astf', function(event) {
		graficoTotalTecnicos()
    }); 

	$(document).off('change', '#hasta_astf').on('change', '#hasta_astf', function(event) {
		graficoTotalTecnicos()
    }); 

	
	function graficoAstTecnico(){
		const tecnico_gmt = $("#tecnico_gmt").val()
		const zona_gmt = $("#zona_gmt").val()
		const comuna_gmt = $("#comuna_gmt").val()

	    $.ajax({
        url: base_url+"graficoAstTecnico"+"?"+$.now(),  
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
					backgroundColor: { fill:'transparent' },
					colors: ['#1A56DB'],
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
							color: '#808080',
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
				var chart = new google.visualization.ColumnChart(document.getElementById('graficoAstTecnico'));
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

	function graficoAstDetalleTecnico(){
		const tecnico_gmt = $("#tecnico_gmt").val()
		const zona_gmt = $("#zona_gmt").val()
		const comuna_gmt = $("#comuna_gmt").val()

	    $.ajax({
        url: base_url+"graficoAstDetalleTecnico"+"?"+$.now(),  
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
					backgroundColor: { fill:'transparent' },
					colors: ['1A56DB', 'red', 'grey'],
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
							color: '#808080',
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
				var chart = new google.visualization.ColumnChart(document.getElementById('graficoAstDetalleTecnico'));
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

	function calculateValue(arraySize) {
		const maxValue = 1200;
		const minValue = 300;
		return Math.max(minValue, minValue + (maxValue - minValue) * (arraySize / 100));
	}

	function graficoTotalTecnicos(){

		const desde = $("#desde_astf").val()
		const hasta = $("#hasta_astf").val()
		const tecnico_gmt = $("#tecnico_gmt").val()
		const zona_gmt = $("#zona_gmt").val()
		const comuna_gmt = $("#comuna_gmt").val()
		

	    $.ajax({
        url: base_url+"graficoTotalTecnicos"+"?"+$.now(),  
        type: 'POST',
		data:{'tecnico_gmt':tecnico_gmt,'zona_gmt':zona_gmt,'comuna_gmt':comuna_gmt,'desde':desde,'hasta':hasta},
        dataType:"json",
        success: function (json) {

			let numArrays = 0;

			for (const element of json) {
				if (Array.isArray(element)) {
					numArrays++;
				}
			}

			if (numArrays > 1) {
					
				let size = Object.keys(json).length

				let height = calculateValue(size)
				var data = google.visualization.arrayToDataTable(json);
				data.sort([{column: 1, desc: true}])
			
				var options = {
					fontName: 'ubuntu',
					fontColor:'#808080',
					bar: { groupWidth: '75%' },
					backgroundColor: { fill:'transparent' },
					colors: ['1A56DB'],
					chartArea:{
						left:280,
						right:50,
						bottom:30,
						top:20,
					},

					height: height,

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
							color: '#808080',
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

				var chart = new google.visualization.BarChart(document.getElementById('graficoTotalTecnicos'));
				chart.draw(data, options);
			}
        }
	    })
	}

	function graficoTotalItems(){

		const tecnico_gmt = $("#tecnico_gmt").val()
		const zona_gmt = $("#zona_gmt").val()
		const comuna_gmt = $("#comuna_gmt").val()
		const item_gmt = $("#item_gmt").val()

	    $.ajax({
        url: base_url+"graficoTotalItems"+"?"+$.now(),  
        type: 'POST',
		data:{'tecnico_gmt':tecnico_gmt,'zona_gmt':zona_gmt,'comuna_gmt':comuna_gmt,'item_gmt':item_gmt},
        dataType:"json",
        success: function (json) {

			let numArrays = 0;

			for (const element of json) {
				if (Array.isArray(element)) {
					numArrays++;
				}
			}

			if (numArrays > 1) {

				let size = Object.keys(json).length
				let height = calculateValue(size)
				height += 200;

				var data = google.visualization.arrayToDataTable(json);
				data.sort([{column: 3, desc: true}])

				var options = {
					fontName: 'ubuntu',
					fontColor:'#808080',
					bar: { groupWidth: '75%' },
					backgroundColor: { fill:'transparent' },
					colors: ['1A56DB','red'],
					chartArea:{
						left:480,
						right:50,
						bottom:30,
						top:20,
					},

					height:height,
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
							color: '#808080',
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

				var chart = new google.visualization.BarChart(document.getElementById('graficoTotalItems'));
				chart.draw(data, options);
			}
        }
	    })
	}

	function encodeText(text) {
		var encoded = '';
		for (var i = 0; i < text.length; i++) {
			var char = text[i];
			if (char.match(/[a-zA-Z0-9.~_-]/)) {
			encoded += char;
			} else {
			encoded += '%' + char.charCodeAt(0).toString(16).toUpperCase();
			}
		}
		return encoded;
	}

	$(document).off('click', '.excel_items').on('click', '.excel_items',function(event) {
       event.preventDefault();
	   const tecnico_gmt = $("#tecnico_gmt").val() || "-"
	   const zona_gmt = $("#zona_gmt").val() || "-"
	   const comuna_gmt = $("#comuna_gmt").val() || "-"
	   const item_gmt = $("#item_gmt").val() || "-"
	   var encodedText = encodeText(item_gmt);
       window.location="excel_items_ast/"+tecnico_gmt+"/"+zona_gmt+"/"+comuna_gmt+"/"+(item_gmt);
    });

	$(document).off('click', '.excel_ast_totales').on('click', '.excel_ast_totales',function(event) {
       event.preventDefault();
	   const desde = $("#desde_astf").val() || "-" 
	   const hasta = $("#hasta_astf").val() || "-" 
	   const tecnico_gmt = $("#tecnico_gmt").val() || "-"
	   const zona_gmt = $("#zona_gmt").val() || "-"
	   const comuna_gmt = $("#comuna_gmt").val() || "-"
       window.location="excel_ast_totales/"+tecnico_gmt+"/"+zona_gmt+"/"+comuna_gmt+"/"+desde+"/"+hasta;
    });



	
</script>
<div class="card p-2">
<div class="row">
    <div class="col mb-2">
		<div class="row">
			<div class="col-12">
				<div class="form-row">
					<div class="col-md-6 offset-md-3">
						<div class="form-row">
							<div class="col">
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
							<div class="col">
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
							<div class="col">
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
					<div class="col-6">
						<p class="title_section mt-3">AST por técnico mensuales cantidad</p>
						<div id="graficoAstTecnico"></div>
					</div>

					<div class="col-6">
						<p class="title_section mt-3">AST por técnico mensuales detalle</p>
						<div id="graficoAstDetalleTecnico"></div>
					</div>
				</div>
				
			</div>
		</div>

		<div class="row">
			<div class="col-6 mt-3">
				<p class="title_section">AST Totales por técnico </p>

				<div class="form-row">
					<div class="col-md-8 offset-md-2">
						<div class="form-row">
							<div class="col-10">
								<div class="form-group">
									<div class="input-group">
										<div class="input-group-prepend">
										<span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="margin-left:5px;font-size:12px;"> Fecha <span></span> 
										</div>
										<input type="date" placeholder="Desde" class="form-control form-control-sm"  name="desde_astf" id="desde_astf">
										<input type="date" placeholder="Hasta" class="form-control form-control-sm"  name="hasta_astf" id="hasta_astf">
									</div>
								</div>
							</div>

							<div class="col-2">  
								<div class="form-group">
									<button type="button"  class="btn-block btn btn-sm btn-primary excel_ast_totales btn_xr3">
									<i class="fa fa-save"></i>  Excel
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>  
				
				<div id="graficoTotalTecnicos"></div>
			</div>

			<div class="col-6 mt-3">
				<p class="title_section">Estados por item </p>

				<div class="form-row">
					<div class="col-md-8 offset-md-2">
						<div class="form-row">
							<div class="col">
								<div class="form-group">
									<select id="item_gmt" name="item_gmt" class="custom-select custom-select-sm">
										<option value="" selected>Seleccione Item | Todos </option>
										<?php  
											foreach($items as $i){
												?>
													<option value="<?php echo $i["descripcion"]?>" ><?php echo $i["descripcion"]?> </option>
												<?php
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-2">  
								<div class="form-group">
									<button type="button"  class="btn-block btn btn-sm btn-primary excel_items btn_xr3">
									<i class="fa fa-save"></i>  Excel
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>  
				<div id="graficoTotalItems"></div>
			</div>
		</div>
    </div>
</div>

