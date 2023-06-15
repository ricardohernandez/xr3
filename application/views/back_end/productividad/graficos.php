<style type="text/css">
    #rankingTecnicos ,#puntosTipoOrden {
       max-height: 400px;
	   overflow-x: hidden;    
	   overflow-y: scroll;    
	}

	.puntos_cont{
		text-align: center;
	}
	.actualizacion_productividad{
		display: inline-block;
		font-size: 11px;
	}
</style>
<script type="text/javascript">
	var base_url = "<?php echo base_url() ?>"
	var perfil="<?php echo $this->session->userdata('id_perfil'); ?>";

	var desde_actual="<?php echo $desde_actual; ?>"
	var hasta_actual="<?php echo $hasta_actual; ?>"
	var desde_anterior="<?php echo $desde_anterior; ?>"
	var hasta_anterior="<?php echo $hasta_anterior; ?>"
	var periodo =$("#periodo").val()

	if(periodo=="actual"){
		$("#fecha_f").val(`${desde_actual.substring(0,5)} - ${hasta_actual.substring(0,5)}`);
	}else if(periodo=="anterior"){
		$("#fecha_f").val(`${desde_actual.substring(0,5)} - ${hasta_actual.substring(0,5)}`);
	}

    dataGraficos()
	
	function dataGraficos(){
	if(perfil==4){
		var trabajador = $("#trabajador").val();
	}else{
		var trabajador = $("#trabajadores").val();
	}

    var jefe = $("#jefe_graficos").val();
    var periodo = $("#periodo").val();

    $.ajax({
      url: base_url+"dataGraficos"+"?"+$.now(),  
      type: 'POST',
      data:{
      	periodo:periodo,
      	trabajador:trabajador,
      	jefe:jefe
      },
      dataType:"json",
      success: function (json) {
      	$("#fecha_f").val(`${json.desde.substring(0,5)} - ${json.hasta.substring(0,5)}`);
     	$(".puntos_cont").html(`<span class="total_puntos">Total puntos : ${json.totalpuntos}</span>`)
		
		google.charts.setOnLoadCallback(rankingTecnicos);
		google.charts.setOnLoadCallback(puntosPorFechas);
		google.charts.setOnLoadCallback(puntosTipoOrden);
		google.charts.setOnLoadCallback(distribucionTipos);
		google.charts.setOnLoadCallback(distribucionOt);
		
		function rankingTecnicos(){
			var ranking = google.visualization.arrayToDataTable(json.ranking);
			ranking.sort([{column: 1, desc: true}])

			if(trabajador!=""){
				var height = 400
			}else{
				if(jefe!=""){
					var height = 1800
				}else{
					var height = 3800
				}
				
			}

			var options = {
				// isStacked: true,
				fontName: 'ubuntu',
				fontColor:'#32477C',
				fontSize: 13,
				colors: ['#2f81f7','#F48432','#A5A5A5'],
				chartArea:{
					left:160,
					right:80,
					bottom:30,
					top:50,
				},
				height:height,
				backgroundColor: { fill:'transparent' },
				hAxis: {
					title: '',
					minValue: 0,
					gridlines: {
						color: '#808080',
						count:0
					},
					textStyle : {
						  fontSize: 12,
						bold:false,
						color:'#808080'
					}
				},
				vAxis: {
				title: '',
				textStyle: {
					fontSize: 12,
					bold:false,
					color:'#808080'
				}
				},

				legend : {  
				position: 'top',
				alignment: 'center',
				textStyle: {
					fontSize: 13,
					bold: false,
					color: '#808080'
				}
				},
			
			};

			var chart = new google.visualization.BarChart(document.getElementById('rankingTecnicos'));
			chart.draw(ranking, options);
			$(".btn_filtro_graficos").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar').prop("disabled" , false);
		}

		function puntosPorFechas(){
			var puntosPorFechas = google.visualization.arrayToDataTable(json.puntosPorFechas);
			puntosPorFechas.sort([{column: 3, desc: false}])

			var options = {
				// isStacked: true,
				title: '',
				width: "100%",
				height: 220,
				is3D:true,
				colors:["#2f81f7"],
				fontName: 'ubuntu',
				bar: {groupWidth: "25%"},
				backgroundColor: { fill:'transparent' },
				annotations: {
					textStyle: {
					fontSize: 12,
					color: '#808080',
					auraColor: 'transparent'
					},
					alwaysOutside: false,  
					stem:{
						color: 'transparent',
						length: 8
					},   
				},
				chartArea:{
					left:60,
					right:40,
					bottom:40,
					top:10,
					width:"100%",
					height:"100%",
				},

				titleTextStyle: {
					color: '#808080',
					fontSize: 13, 
					fontWidth: 'normal',
					bold:false
				},

				legend: {
					'position':'none',
					'alignment':'center',
					textStyle: {
						fontSize: 12,
						bold:false,
						color:'#808080'
					}
				}, 

				hAxis: {
					textStyle:{
						color: '#808080', 
						fontSize: 12,
						bold:false,
					},

					gridlines: {
						color: '#808080',
						count:0
					},
				},

				vAxis: {
					textStyle:{
						color: '#808080',
						bold:false,
						fontSize: 12
					},

					gridlines: {
						color: '#808080',
						count:0
					},
				},

				tooltip:{textStyle:{fontSize:13},isHtml: true},
			};

         	var chart = new google.visualization.ColumnChart(document.getElementById('puntosPorFechas'));
       	  	chart.draw(puntosPorFechas, options);
		}
			
		function puntosTipoOrden() {
			var puntosTipoOrden = google.visualization.arrayToDataTable(json.puntosTipoOrden);
			puntosTipoOrden.sort([{column: 0, desc: false}])		   

			var options = {
				showRowNumber: false,
				backgroundColor: { fill:'transparent' },

				width: '100%', 
				height: 400
			}
			var table = new google.visualization.Table(document.getElementById('puntosTipoOrden'));
			table.draw(puntosTipoOrden, options);
		}

		function distribucionTipos(){
			var data = google.visualization.arrayToDataTable(json.distribucionTipos);
			var options = {
				fontName: 'ubuntu',
				height:160,
				sliceVisibilityThreshold:0,
				format: 'short',
				isStacked: 'percent',
				pieSliceText: 'value',
				backgroundColor: { fill:'transparent' },
				legend: {
				position: 'labeled',
				'alignment':'center',
				textStyle: {
					fontSize: 12,
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

				colors: ['#2f81f7','#F48432','#A5A5A5'],
				chartArea:{
					left:10,
					right:10,
					bottom:5,
					top:5,
				},
			};
			var chart = new google.visualization.PieChart(document.getElementById('distribucionTipos'));
			chart.draw(data, options);
		}

		 function distribucionOt(){
			var data = google.visualization.arrayToDataTable(json.distribucionOt);
			var options = {
				fontName: 'ubuntu',
				height:160,
				sliceVisibilityThreshold:0,
				format: 'short',
				isStacked: 'percent',
				pieSliceText: 'value',
				backgroundColor: { fill:'transparent' },
				legend: {
				position: 'labeled',
				'alignment':'center',
				textStyle: {
					fontSize: 11,
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

				colors: ['green', 'red'],
				chartArea:{
					left:10,
					right:10,
					bottom:5,
					top:5,
				},
	        };
	        var chart = new google.visualization.PieChart(document.getElementById('distribucionOt'));
	        chart.draw(data, options);
		}
      }
    })
	}

	$(document).off('click', '.btn_filtro_graficos').on('click', '.btn_filtro_graficos',function(event) {
      event.preventDefault();
      $(".btn_filtro_graficos").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando').prop("disabled" , true);
      dataGraficos()
	});

	$.getJSON("listaTrabajadores", function(data) {
      response = data;
	}).done(function() {
	    $("#trabajadores").select2({
		     placeholder: 'Seleccione Trabajador | Todos',
		     data: response,
		     width: 'resolve',
	       allowClear:true,
	    });
	});

	$(document).off('click', '.btn_excel_graficos').on('click', '.btn_excel_graficos',function(event) {
	   event.preventDefault();
     
     if(perfil==4){
      trabajador = $("#trabajador").val()
     }else{
      trabajador = $("#trabajadores").val();
     }

     if(trabajador==""){
       trabajador="-"
     }

     var periodo = $("#periodo").val();  
     var jefe = perfil<=3 ? $("#jefe_graficos").val() : "-";
     jefe = jefe=="" ? "-" : jefe;

     if(periodo==""){
       periodo="actual"
     }

     window.location="excel_detalle/"+periodo+"/"+trabajador+"/"+jefe;
  });

  actualizacionProductividad()

  function actualizacionProductividad(){
    $.ajax({
        url: "actualizacionProductividad"+"?"+$.now(),  
        type: 'POST',
        cache: false,
        tryCount : 0,
        retryLimit : 3,
        dataType:"json",
        beforeSend:function(){
        },
        success: function (data) {
          if(data.res=="ok"){
            $(".actualizacion_productividad").html("<b>Última actualización planilla : "+data.datos+"</b>");
          }
        },
        error : function(xhr, textStatus, errorThrown ) {
          if (textStatus == 'timeout') {
              this.tryCount++;
              if (this.tryCount <= this.retryLimit) {
                  $.notify("Reintentando...", {
                    className:'info',
                    globalPosition: 'top right'
                  });
                  $.ajax(this);
                  return;
              } else{
                 $.notify("Problemas en el servidor, intente nuevamente.", {
                    className:'warn',
                    globalPosition: 'top right'
                  });     
              }    
              return;
          }

          if (xhr.status == 500) {
              $.notify("Problemas en el servidor, intente más tarde.", {
                className:'warn',
                globalPosition: 'top right'
              });
          }
        },timeout:5000
    }); 
  }


  $(document).off('change', '#periodo').on('change', '#periodo', function(event) {
      dataGraficos()
  }); 

  $(document).off('change', '#jefe_graficos').on('change', '#jefe_graficos', function(event) {
      dataGraficos()
  }); 

  $(document).off('change', '#trabajadores').on('change', '#trabajadores', function(event) {
      dataGraficos()
  }); 
   

</script>

<div class="form-row cont_graficos">

    <div class="col-12 col-lg-3">
      <div class="form-group">
      	<div class="alert alert-primary2" role="alert">
			<p class="alert-heading puntos_cont"></p>
		</div>
      </div>
    </div>

    <div class="col-6  col-lg-2">
        <div class="form-group">
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="margin-left: 5px;font-size:13px;"> Periodo <span></span> 
            </div>
              <select id="periodo" name="periodo" class="custom-select custom-select-sm">
                <option value="actual" selected>Actual - <?php echo $mes_actual ?> </option>
                <option value="anterior">Anterior - <?php echo $mes_anterior?> </option>
             </select>
          </div>
        </div>
    </div>

    <div class="col-6 col-lg-1">
      <div class="form-group">
        <div class="input-group">
            <input type="text" disabled placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="fecha_f" id="fecha_f">
        </div>
      </div>
    </div>

    <?php  
      if($this->session->userdata('id_perfil')<3){
    ?>

      <div class="col-6 col-lg-2">
        <div class="form-group">
          <select id="jefe_graficos" name="jefe_graficos" class="custom-select custom-select-sm">
            <option value="" selected>Seleccione Jefe | Todos</option>
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
        <div class="col-6 col-lg-2">
          <div class="form-group">
            <select id="jefe_graficos" name="jefe_graficos" class="custom-select custom-select-sm">
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
	   if($this->session->userdata('id_perfil')<>4){
	      ?>

        <div class="col-6 col-lg-2">  
          <div class="form-group">
            <select id="trabajadores" name="trabajadores" style="width:100%!important;">
                <option value="">Seleccione Trabajador | Todos</option>
            </select>
          </div>
        </div>

	    <?php
	    	}else{
	    ?>
	        <div class="col-6 col-lg-2">  
	          <div class="form-group">
	            <select id="trabajador" name="trabajador" class="custom-select custom-select-sm" >
	                <option selected value="<?php echo $this->session->userdata('rut'); ?>"><?php echo $this->session->userdata('nombre_completo'); ?></option>
	            </select>
	          </div>
	        </div>
	    <?php
	  	  }
		?>

   <!--  <div class="col-6 col-lg-1">
        <div class="form-group">
	        <button type="button" class="btn-block btn btn-sm btn-primary btn_filtro_graficos btn_xr3">
	            <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
	        </button>
        </div>
    </div> -->

    <div class="col-6 col-lg-1">
        <div class="form-group">
	        <button type="button" class="btn-block btn btn-sm btn-primary btn_excel_graficos btn_xr3">
	            <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span>  Excel
	        </button>
        </div>
    </div>
</div>     

<div class="row">
  <div class="col-lg-6 offset-lg-3 mb-2">
    <center><span class="titulo_fecha_actualizacion_dias">
      <div class="alert alert-primary actualizacion_productividad" role="alert">
      </div>
    </span></center>
  </div>
</div>

<div class="row">
	<div class="col mb-2">
	    <div class="card border-left-primary shadow mb-2">
	        <div class="card-body card_productividad" >

	            <div class="row">
	              <div class="col-xs-12 col-lg-9">
					<p class="title_section">Productividad de técnicos en periodo </p>
					<div id="rankingTecnicos"></div>
					</div>

					<!-- <div class="col-xs-12 col-lg-3">
					<h6 class="title_section">Puntos por tipo órden</h6>
					<div id="puntosTipoOrden"></div>
					</div> -->

					<div class="col-xs-12 col-lg-3">
					<p class="title_section">Distribución por tipo</p>
					<div id="distribucionTipos"></div>
					<p class="title_section">Estado OT</p>
					<div id="distribucionOt"></div>
					</div>
	            </div>

	            <div class="row">
	                <div class="col-lg-12 mt-3">
						<p class="title_section">Puntos por fecha  </p>
						<div id="puntosPorFechas"></div>
					</div>
	            </div>

	        </div>
	    </div>
	</div>
</div>

