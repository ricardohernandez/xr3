<style type = "text/css" >
    .titulo_grafico {
        color: #32477C;
    font-size: 16px; 
    font-weight:bold;
    text-align: left;
    padding:10px 2px;
  }
  .tfoot_totales{
     /* background-color: # 1 A56DB;*/
        color: #808080;
  }
  .tfoot_totales th{
    font-size: 13px!important;
  }
  .actualizacion_calidad{
      display: inline-block;
      font-size: 11px;
  }
  .centered{
	text-align:center;
  }
</style>
<script type= "text/javascript" >
	var base_url = "<?php echo base_url() ?>" 
	var periodo = $("#periodo").val()
	var perfil = "<?php echo $this->session->userdata('id_perfil'); ?>";

	const mes= "<?php echo $mes ?>"
	$("#mes").val(mes) 
  	
	/*****GRAFICOS**********/

	cargarGraficos()

	function cargarGraficos () {
		var mes = $("#mes").val();
		var jefe = $("#jefe").val();
		var proyecto = $("#proyecto").val();
		var trabajador = perfil <= 3 ? $("#trabajadores_g").val() : $("#trabajador_g").val();

		$.ajax({
			url: base_url+'graficosProductividad',
			type: 'POST',
			data: {
				'mes': mes,
				'trabajador': trabajador,
				'jefe': jefe,
				'proyecto': proyecto
			},
			dataType: "json",
			beforeSend:function(){
				$("#load").show()
				$(".body").hide()  
			}, 
			success: function (response) {
				$("#load").hide()
				$(".body").fadeIn(500)

				crearGrafico('graficoCumplimientoProduccion', response.produccion);
				crearGrafico('graficoAsistencia', response.asistencia);
				crearGrafico('graficoDerivaciones', response.derivaciones);

			},
			error: function (error) {
				console.log(error);
			}
		});
	}

	function crearGrafico(divId, data) {
		var data = google.visualization.arrayToDataTable(data);

		const options = {
		/* 	isStacked:true, */
			fontName: 'ubuntu',
			curveType: 'function',
			fontColor: '#32477C',
			backgroundColor: { fill: 'transparent' },
			colors: ['#F48432', '#2f81f7'],
			chartArea: {
				left: 40,
				right: 40,
				bottom: 40,
				top: 40,
			},
			height: 210,
			hAxis: {
				title: '',
				minValue: 0,
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
		chart = new google.visualization.ColumnChart(document.getElementById(divId));
		chart.draw(data, options);
	}


	/*****DATATABLE*****/
		var tabla_resumen_productividad = $('#tabla_resumen_productividad').DataTable({
			/*"sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',*/
			"iDisplayLength": 200,
			"lengthMenu": [
				[5, 15, 50, -1],
				[5, 15, 50, "Todos"]
			],
			"bPaginate": false,
			"responsive": false,
			/*"aaSorting" : [[0,"asc"]],*/
			"scrollY": "65vh",
			"scrollX": true,
			"sAjaxDataProp": "result",
			"bDeferRender": true,
			"select": true,
			"info": true,
			/*"columnDefs": [{ orderable: false, targets: 0 }  ],*/
			"ajax": {
				"url": "<?php echo base_url();?>listaResumenProductividad",
				"dataSrc": function(json) {
					$(".btn_filtro_calidad").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
					$(".btn_filtro_calidad").prop("disabled", false);
					return json;
				},
				data: function(param) {
					param.mes = $("#mes").val();
					param.jefe = $("#jefe").val();
					param.tipo_red = $("#tipo_red").val();
					param.proyecto = $("#proyecto").val();

					if (perfil == 4) {
						param.trabajador = $("#trabajador_g").val();
					} else {
						param.trabajador = $("#trabajadores_g").val();
					}
				}
			},

			"columns": [{
					"data": "trabajador",
					"width": "20%",
					"class": "margen-td centered"
				},
				{
					"data": "rut",
					"width": "5%",
					"class": "margen-td centered"
				},
				{
					"data": "cumplimiento_ot",
					"class": "margen-td centered"
				},
				{
					"data": "porcentaje_produccion_hfc",
					"class": "margen-td centered"
				},
				{
					"data": "porcentaje_produccion_ftth",
					"class": "margen-td centered"
				},
				{
					"data": "indice_asistencia",
					"class": "margen-td centered"
				},
				{
					"data": "derivaciones",
					"class": "margen-td centered"
				},
		
				/*{ "data": "productividad" ,"class":"margen-td centered"},*/

			]
		});

		$(document).on('keyup paste', '#buscador_detalle_calidad', function() {
			tabla_resumen_productividad.search($(this).val().trim()).draw();
		});

		$.getJSON(base_url + "listaTrabajadoresProd", function(data) {
			response = data;
		}).done(function() {
			$("#trabajadores_g").select2({
				placeholder: 'Seleccione Trabajador | Todos',
				data: response,
				width: 'resolve',
				allowClear: true,
			});
		});

	
		actualizacionCalidad()

		function actualizacionCalidad() {
			$.ajax({
				url: "actualizacionCalidad" + "?" + $.now(),
				type: 'POST',
				cache: false,
				tryCount: 0,
				retryLimit: 3,
				dataType: "json",
				beforeSend: function() {},
				success: function(data) {
					if (data.res == "ok") {
						$(".actualizacion_calidad").html("<b>Última actualización planilla : " + data.datos + "</b>");
					}
				},
				error: function(xhr, textStatus, errorThrown) {
					if (textStatus == 'timeout') {
						this.tryCount++;
						if (this.tryCount <= this.retryLimit) {
							$.notify("Reintentando...", {
								className: 'info',
								globalPosition: 'top right'
							});
							$.ajax(this);
							return;
						} else {
							$.notify("Problemas en el servidor, intente nuevamente.", {
								className: 'warn',
								globalPosition: 'top right'
							});
						}
						return;
					}

					if (xhr.status == 500) {
						$.notify("Problemas en el servidor, intente más tarde.", {
							className: 'warn',
							globalPosition: 'top right'
						});
					}
				},
				timeout: 5000
			});
		}

		$(document).off('change', '#mes,#jefe,#trabajadores_g,#proyecto').on('change', '#mes,#jefe,#trabajadores_g,#proyecto', function (event) {
			tabla_resumen_productividad.ajax.reload()
			cargarGraficos()
		});


</script>

<div class="form-row cont_graficos">
    <div class="col-6 col-lg-2">
      <div class="form-group">
	  <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="font-size:12px;margin-left:5px;"> Meses<span></span> 
        </span></span></div>
        <input type="month" placeholder="Desde" class=" form-control form-control-sm" name="mes" id="mes">
       </div>
      </div>
    </div>

   <?php  
      if($this->session->userdata('id_perfil')<3){
      ?>
   <div class="col-6 col-lg-3">
      <div class="form-group">
         <select id="jefe" name="jefe" class="custom-select custom-select-sm">
            <option value="" selected>Seleccione Jefe | Todos </option>
            <?php  
               foreach($jefes as $j){
               	?>
            <option value="<?php echo $j["id_jefe"]?>" ><?php echo $j["nombre_jefe"]?> </option>
            <?php
               }
               ?>
         </select>
      </div>
   </div>
   <?php
      }elseif($this->session->userdata('id_perfil')==3){
        ?>
   <div class="col-6 col-lg-3">
      <div class="form-group">
         <select id="jefe" name="jefe" class="custom-select custom-select-sm">
            <?php  
               foreach($jefes as $j){
                 ?>
            <option selected value="<?php echo $j["id_jefe"]?>" ><?php echo $j["nombre_jefe"]?> </option>
            <?php
               }
               ?>
         </select>
      </div>
   </div>
   <?php
      }
      ?>
   <?php  
      if($this->session->userdata('id_perfil')<=3){
      ?>
   <div class="col-6 col-lg-3">
      <div class="form-group">
         <select id="trabajadores_g" name="trabajadores_g" style="width:100%!important;">
            <option value="">Seleccione Trabajador | Todos</option>
         </select>
      </div>
   </div>
   <?php
      }else{
      ?>
   <div class="col-6 col-lg-3">
      <div class="form-group">
         <select id="trabajador_g" name="trabajador_g" class="custom-select custom-select-sm" >
            <option selected value="<?php echo $this->session->userdata('id'); ?>"><?php echo $this->session->userdata('nombre_completo'); ?></option>
         </select>
      </div>
   </div>
   <?php
      }
      ?>
   <div class="col-6 col-lg-2">
      <div class="form-group">
         <select id="proyecto" name="proyecto" class="custom-select custom-select-sm">
            <option value="" selected>Seleccione proyecto | Todos </option>
            <?php  
               foreach($proyectos as $p){
                 ?>
            <option  value="<?php echo $p["id"]?>" ><?php echo $p["proyecto"]?> </option>
            <?php
               }
               ?>
         </select>
      </div>
   </div>
   
   <!-- <div class="col-12 col-lg-2">  
      <div class="form-group">
          <input type="text" placeholder="Busqueda" id="buscador_detalle_calidad" class="buscador_detalle_calidad form-control form-control-sm">
      </div>
      </div> -->
   <!-- <div class="col-6 col-lg-1">
      <div class="form-group">
        <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_calidad btn_xr3">
            <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
        </button>
      </div>
      </div> -->
</div>
<div class="row">
   <div class="col">
      <div class="card  shadow">
         <div class="card-body" style="padding: .4rem;">
            <div class="row">
               <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <div class="col-lg-12">
                     <center>
                     <span class="titulo_fecha_actualizacion_dias">
                     <div class="alert alert-primary actualizacion_calidad" role="alert" style="padding: .15rem 1.25rem;margin-bottom: .1rem;">
                     </div>
                  </div>
                  <table id="tabla_resumen_productividad" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
                     <thead>
                        <tr>
						   <th class="centered">Nombre</th>
						   <th class="centered">RUT</th>
						   <th class="centered">% OT digital</th>
                           <th class="centered">% Producción HFC</th>
                           <th class="centered">% Producción FTTH</th>
                           <th class="centered">% Asistencia</th>
                           <th class="centered">Derivaciones</th>
                        </tr>
                     </thead>
                  </table>
               </div>
               <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                  <div class="short-div">
                     <p class="section_titulo">% Cumplimiento producción &uacute;timos 6 meses</p>
                     <div id="graficoCumplimientoProduccion"></div>
                  </div>
                  <div class="short-div">
                     <p class="section_titulo">% Cumplimiento Asistencia &uacute;timos 6 meses</p>
                     <div id="graficoAsistencia"></div>
                  </div>
				  <div class="short-div">
                     <p class="section_titulo">Derivaciones &uacute;timos 6 meses</p>
                     <div id="graficoDerivaciones"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>