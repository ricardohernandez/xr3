<style type="text/css">
  .red{
    background-color: #DC3545;
    color: #fff;
  }
  .modal-header{
    padding:0.1rem 0.1rem!important;
    border-bottom: none!important;
  }

  .form-control-sm {
    height: calc(1.9em + 0.5rem + 2px)!important;
  }
  .modal-body {
    position: relative;
    -ms-flex: 1 1 auto;
    flex: 1 1 auto;
    padding:0.1rem 0.1rem!important;
  }

  .btn_xr3{
    color: #fff!important;
    background-color: #32477C!important;
    border-color: #32477C!important;
  }

  .btn_eliminar_licencias{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
  }

  .btn_editar_licencias{
      display: inline-block;
      text-align: center!important;
      margin:0 auto!important;
      font-size: 15px!important;
  }

  @media(min-width: 768px){
    .custom-select-sm {
     height: calc(1.90rem + 2px)!important; 
    }

    .input-xs {
      height: 22px!important;
      line-height: 1.5;
      padding: 1px 6px!important;
      font-size: 11px!important;
    }
    .form-group{
      margin-bottom:5px!important;
    }
    .centered{
      font-size: 11px;
      text-align: left;
      white-space:nowrap;
    }
    .borrar_ots{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
    }

    table.dataTable tbody th, table.dataTable tbody td {
      padding: 0px 7px!important;
      font-size: 12px;
    }

    table td, .table th {
      padding: 0.75rem;
      vertical-align: middle!important;
      border-top: 1px solid #dee2e6;
    }

    .btn_delete_linea:hover{
      cursor: pointer;
    }
    
    .btn_modificar_ots{
      display: block;
      text-align: center!important;
      margin:0 auto!important;
      font-size: 15px!important;
    }

    .modal_ingreso_licencias{
      width: 44%!important;
    }

    fieldset {
      padding: .15em .625em .15em!important;
    }


    .tabla_listado2 #tabla_listado2 > tbody > tr > td {
        padding: 1px!important;
    }
    .table_text{
      text-align: left!important;
      font-size: 10.5px!important;
      margin: 0px!important;
      padding-left: 3px;
      padding-right: 3px;
     }
    .observacion_chk:focus,.observacion_chk:active{
      background-color: #fff!important;
    }
    .table_head{
      font-size: 12px!important;
      text-align: center!important;
    }
   
    .full-w{
      width: 90%!important;
    }
    .tabla_listado2 #tabla_listado2 .header th {
       height: 22px;
       font-size: 12.5px;
    }

    .tabla_listado2 #tabla_listado2 thead tr th{
      font-size: 11px!important;
    }
    .tabla_listado2 #tabla_listado2 tbody tr td{
      font-size: 10px!important;
    }

    .tabla_listado2 .dataTables_filter {
     display: none;
    }

    .form-control {
      font-size: 12px!important;
      padding: .375rem .75rem!important;
    }

    .custom-select{
      font-size: 12px!important;
    }

  }

  @media(max-width: 768px){
    .input-xs {
      height: 32px!important;
      line-height: 1.5;
      padding: 1px 1px!important;
      font-size: 11px!important;
    }
    .custom-select-sm {
     height: calc(2.6rem + 2px)!important; 
    }
    table.dataTable tbody th, table.dataTable tbody td {
      padding: 1px 3px!important;
      font-size: 14px!important;
    }

    .form-group{
      margin-bottom:5px!important;
    }
    .centered{
      font-size: 13px;
      text-align: left;
      white-space:nowrap;
    }
    .custom-select{
      font-size: 14px!important;
    }
    .form-control {
        font-size: 14px!important;
        padding: 0.575rem 0.75rem!important;
    }

    .borrar_ots{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 20px;
      text-decoration: none!important;
    }

    table.dataTable tbody th, table.dataTable tbody td {
      padding: 0px 7px!important;
      font-size: 14px;
    }

    table td, .table th {
      padding: 1.75rem;
      vertical-align: middle!important;
      border-top: 1px solid #dee2e6;
    }

    .btn_delete_linea:hover{
      cursor: pointer;
    }
    
    .btn_modificar_ots{
      display: block;
      text-align: center!important;
      font-size: 18px!important;
    }

    fieldset {
      padding: .15em .625em .15em!important;
    }

    .tabla_listado2 #tabla_listado2 > tbody > tr > td {
        padding: 2px!important;
    }
    .table_text{
      font-size: 12px!important;
      margin: 0px!important;
      padding-left: 1px;
      padding-right: 1px;
     }
    .observacion_chk:focus,.observacion_chk:active{
      background-color: #fff!important;
    }
    .table_head{
      font-size: 14px!important;
      text-align: center!important;
    }

    .full-w{
      width: 90%!important;
    }
    .tabla_listado2 #tabla_listado2 .header th {
       height: 22px;
       font-size: 12.5px;
    }

    .tabla_listado2 #tabla_listado2 thead tr th{
      font-size: 13px!important;
    }
    .tabla_listado2 #tabla_listado2 tbody tr td{
      font-size: 13px!important;
    }

    .tabla_listado2 .dataTables_filter {
     display: none;
    }

  }
</style>

<script type="text/javascript" charset="utf-8"> 
  $(function(){ 

    /*****DATATABLE*****/  
      base = "<?php echo base_url() ?>";
      var tb_licencias = $('#tb_licencias').DataTable({
         "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
         "iDisplayLength":-1, 
         "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
         "bPaginate": false,
         "aaSorting" : [[12,"desc"]],
         "scrollY": "60vh",
         "scrollX": true,
         "sAjaxDataProp": "result",        
         "bDeferRender": true,
         "select" : true,
         columnDefs: [
            { orderable: false, targets: 0 }
         ],
          "ajax": {
            "url":"<?php echo base_url();?>getLicenciasList",
            "dataSrc": function (json) {
              $(".btn_filtro_licencias").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
              $(".btn_filtro_licencias").prop("disabled" , false);
              return json;
            },       
            data: function(param){


              if($("#checkinactivos").is(':checked')){
                  inactivos="on";
              }else{
                  inactivos="off";
              }
              param.inactivos = inactivos;

            }
          },    
          "columns": [
            {
              "class":"centered margen-td","data": function(row,type,val,meta){
                btn='<center><a href="#!" title="Modificar" data-hash="'+row.hash_id+'" class="btn_editar_licencias"><i class="fa fa-edit"></i></a>';
                btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash_id+'" class="btn_eliminar_licencias rojo"><i class="fa fa-trash"></i></a></center>';
                return btn;
              }
            },

            {
              "class":"centered margen-td","data": function(row,type,val,meta){
                botonera="";
                if(row.adjunto!=null && row.adjunto!=""){
                  botonera+='<center><a title="Ver archivo" style="margin-left:10px;" target="_blank" href="'+base+'archivos_licencias/'+row.adjunto+'"><i class="fa fa-file verde"></i></a></center>';
                }else{
                  botonera="<center>-</center>";
                }
                return botonera;
              }
            },

            { "data": "usuario" ,"class":"margen-td centered"},
            { "data": "rut" ,"class":"margen-td centered"},
            { "data": "jefe" ,"class":"margen-td centered"},
            { "data": "empresa" ,"class":"margen-td centered"},
            { "data": "tipo_licencia" ,"class":"margen-td centered"},
            { "data": "fecha_inicio" ,"class":"margen-td centered"},
            { "data": "fecha_termino" ,"class":"margen-td centered"},
            { "data": "vigencia" ,"class":"margen-td centered"},
            { "data": "dias" ,"class":"margen-td centered"},
            { "data": "digitador" ,"class":"margen-td centered"},
            { "data": "ultima_actualizacion" ,"class":"margen-td centered"},
          ]
    }); 


    $(document).on('keyup paste', '#buscador_licencias', function() {
      tb_licencias.search($(this).val().trim()).draw();
    });

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    setTimeout( function () {
      var tb_licencias = $.fn.dataTable.fnTables(true);
      if ( tb_licencias.length > 0 ) {
          $(tb_licencias).dataTable().fnAdjustColumnSizing();
    }}, 200 ); 

    setTimeout( function () {
      var tb_licencias = $.fn.dataTable.fnTables(true);
      if ( tb_licencias.length > 0 ) {
          $(tb_licencias).dataTable().fnAdjustColumnSizing();
    }}, 2000 ); 

    setTimeout( function () {
      var tb_licencias = $.fn.dataTable.fnTables(true);
      if ( tb_licencias.length > 0 ) {
          $(tb_licencias).dataTable().fnAdjustColumnSizing();
      }
    }, 4000 ); 


    $(document).off('click', '.btn_filtro_licencias').on('click', '.btn_filtro_licencias',function(event) {
     event.preventDefault();
      $(this).prop("disabled" , true);
      $(".btn_filtro_licencias").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
       tb_licencias.ajax.reload();
    });

    $(document).off('click', '.btn_nuevo_licencias').on('click', '.btn_nuevo_licencias', function(event) {
        $('#formIngresoLicencias')[0].reset();
        $("#hash_id_licencias").val("");
        $('#modal_ingreso_licencias').modal('toggle'); 
        $("#formIngresoLicencias input,#formIngresoLicencias select,#formIngresoLicencias button,#formIngresoLicencias").prop("disabled", false);
        $(".btn_ingreso_licencias").attr("disabled", false);
        $(".cierra_modal_licencias").attr("disabled", false);
        $('#usuarios').val("").trigger('change');

    });

    $(document).off('submit', '#formIngresoLicencias').on('submit', '#formIngresoLicencias',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formIngresoLicencias");
        var formData = new FormData(formElement);
          $.ajax({
              url: $('#formIngresoLicencias').attr('action')+"?"+$.now(),  
              type: 'POST',
              data: formData,
              cache: false,
              processData: false,
              dataType: "json",
              contentType : false,
              beforeSend:function(){
                $(".btn_ingreso_licencias").attr("disabled", true);
                $(".cierra_modal_licencias").attr("disabled", true);
                $("#formIngresoLicencias input,#formIngresoLicencias select,#formIngresoLicencias button,#formIngresoLicencias").prop("disabled", true);
              },
              success: function (data) {
                if(data.res == "sess"){
                  window.location="unlogin";

                }else if(data.res=="ok"){
                  $('#modal_ingreso_licencias').modal('toggle'); 
                  $("#formIngresoLicencias input,#formIngresoLicencias select,#formIngresoLicencias button,#formIngresoLicencias").prop("disabled", false);
                  $(".btn_ingreso_licencias").attr("disabled", false);
                  $(".cierra_modal_licencias").attr("disabled", false);

                  $.notify(data.msg, {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $('#formIngresoLicencias')[0].reset();
                  tb_licencias.ajax.reload();
                }else if(data.res=="error"){

                  $(".btn_ingreso_licencias").attr("disabled", false);
                  $(".cierra_modal_licencias").attr("disabled", false);
                  $.notify(data.msg, {
                    className:'error',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                  $("#formIngresoLicencias input,#formIngresoLicencias select,#formIngresoLicencias button,#formIngresoLicencias").prop("disabled", false);

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
                        $('#modal_ingreso_licencias').modal("toggle");
                    }    
                    return;
                }

                if (xhr.status == 500) {
                    $.notify("Problemas en el servidor, intente más tarde.", {
                      className:'warn',
                      globalPosition: 'top right'
                    });
                    $('#modal_ingreso_licencias').modal("toggle");
                }

            },timeout:35000
          }); 
        return false; 
    });


    $(document).off('click', '.btn_editar_licencias').on('click', '.btn_editar_licencias',function(event) {
        event.preventDefault();
        hash_id=$(this).attr("data-hash");
        $('#formIngresoLicencias')[0].reset();
        $("#hash_id_licencias").val("");
        $("#hash_id_licencias").val(hash_id);
        $('#modal_ingreso_licencias').modal('toggle'); 
        
        $("#formIngresoLicencias input,#formIngresoLicencias select,#formIngresoLicencias button,#formIngresoLicencias").prop("disabled", true);
        $(".btn_ingreso_licencias").attr("disabled", true);
        $(".cierra_modal_licencias").attr("disabled", true);


        $.ajax({
          url: base+"getDataRegistroLicencias"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          data:{hash_id:hash_id},
          dataType:"json",
          beforeSend:function(){
           $(".btn_ingreso_licencias").prop("disabled",true); 
           $(".cierra_modal_licencias").prop("disabled",true); 
          },
          success: function (data) {
            if(data.res=="ok"){
              for(dato in data.datos){
                  $("#hash_id_licencias").val(data.datos[dato].hash_id);
                  $('#usuarios').val(data.datos[dato].id_usuario).trigger('change');
                  $("#fecha_inicio").val(data.datos[dato].fecha_inicio);
                  $("#fecha_termino").val(data.datos[dato].fecha_termino);
                  $("#tipo_licencia  option[value='"+data.datos[dato].id_tipo+"'").prop("selected", true);
              }

              $("#formIngresoLicencias input,#formIngresoLicencias select,#formIngresoLicencias button,#formIngresoLicencias").prop("disabled", false);
              $(".cierra_modal_licencias").prop("disabled", false);
              $(".btn_ingreso_licencias").prop("disabled", false);

            }else if(data.res == "sess"){
              window.location="../";
            }

            $(".btn_ingreso_licencias").prop("disabled",false); 
            $(".cierra_modal_licencias").prop("disabled",false); 
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
                    $('#modal_nuevo_usuario').modal("toggle");
                }    
                return;
            }
            if (xhr.status == 500) {
                $.notify("Problemas en el servidor, intente más tarde.", {
                  className:'warn',
                  globalPosition: 'top right'
                });
                $('#modal_ingreso_licencias').modal("toggle");
            }
        },timeout:35000
      }); 
    });

    $(document).off('click', '.btn_eliminar_licencias').on('click', '.btn_eliminar_licencias',function(event) {
        hash=$(this).data("hash");
        if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaLicencias'+"?"+$.now(),{"hash": hash}, function(data) {
              
              if(data.res=="ok"){

                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right',
                  autoHideDelay:15000
                });
               tb_licencias.ajax.reload();

              }else{

                $.notify(data.msg, {
                  className:'danger',
                  globalPosition: 'top right'
                });
                
              }

            },"json");
          }
    });


    $(".fecha_normal").datetimepicker({
        format: "DD-MM-YYYY",
        locale:"es"
        //maxDate:"now"
    });


    $(".fecha_mes").datetimepicker({
        format: "MM-YYYY",
        locale:"es"
        //maxDate:"now"
    });


    $.getJSON("listaUsuariosS2", function(data) {
        response = data;
    }).done(function() {
      $("#usuarios").select2({
           placeholder: 'Buscar...',
           data: response,
           allowClear: true,
          });
    });  


    document.querySelector('#checkinactivos').addEventListener('click', (event) => {
        tb_licencias.ajax.reload();
    });

  });
</script>
  

<!--FILTROS-->

    <div class="form-row">
	    <div class="col-1 col-lg-1"> 
	      <div class="form-group">
	         <button type="button" class="btn-block btn btn-sm btn-outline-primary btn_nuevo_licencias btn_xr3">
	         <i class="fa fa-plus-circle"></i>  Nuevo 
	         </button>
	      </div>
	    </div>
	    <div class="col-2 col-lg-4">  
	      <div class="form-group">
	      <input type="text" placeholder="Ingrese su busqueda..." id="buscador_licencias" class="buscador_licencias form-control form-control-sm">
	      </div>
	    </div>
        <div class="col-md-2">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="checkinactivos" name="checkinactivos">
                <label class="form-check-label" for="checkinactivos">Incluir inactivos</label>
            </div>
        </div>
	    <div class="col-2 col-lg-2">
	       <div class="form-group">
	          <button type="button" class="btn-block btn btn-sm btn-outline-primary btn-primary btn_filtro_licencias btn_xr3">
	      	  <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
	          </button>
	       </div>
	    </div>
	</div>

    <div class="row">
	    <div class="col-12">
	        <table id="tb_licencias" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%!important">
	          <thead>
	            <tr>
	              <th class="centered">Acciones</th> 
	              <th class="centered">Archivo</th>    
	              <th class="centered">Nombre</th>    
	              <th class="centered">Rut</th>    
	              <th class="centered">Jefe</th>    
	              <th class="centered">Empresa</th>   
	              <th class="centered">Tipo licencia</th> 
	              <th class="centered">Fecha inicio</th> 
	              <th class="centered">Fecha término</th> 
	              <th class="centered">Vigente</th> 
	              <th class="centered">Días</th> 
	              <th class="centered">Digitador</th> 
	              <th class="centered">Última actualización</th> 
	            </tr>
	          </thead>
	        </table>
	    </div>
    </div>

<!--  NUEVO -->

    <div id="modal_ingreso_licencias"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
	    <div class="modal-dialog modal_ingreso_licencias">
	      <div class="modal-content">
	        <?php echo form_open_multipart("formIngresoLicencias",array("id"=>"formIngresoLicencias","class"=>"formIngresoLicencias"))?>
	           <input type="hidden" name="hash_id" id="hash_id_licencias">

	           <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
	           <fieldset class="form-ing-cont">
	              <legend class="form-ing-border">Datos de ingreso</legend>
	                
	                <div class="form-row">

	                  <div class="col-lg-12">  
  				            <div class="form-group">
  	    	                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Usuario</label>
  				              <select id="usuarios" name="usuarios" style="width:100%!important;">
  				                  <option value="">Seleccione Usuario</option>
  				              </select>
  				            </div>
		              	</div>
	                 
                    <div class="col-lg-12">  
                      <div class="form-group">
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tipo licencia</label>
                      <select id="tipo_licencia" name="tipo_licencia" class="tipo custom-select custom-select-sm">
                          <option value="" selected>Seleccione</option>
                          <?php 
                          foreach($tipos as $t){
                            ?>
                            <option value="<?php echo $t["id"]?>"><?php echo $t["tipo"]?></option>
                            <?php
                          }
                          ?>
                      </select>
                      </div>
                    </div>
                      
                    <div class="col-lg-12">
	                    <div class="form-group"> 
	                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha Inicio </label>
	                       <input  type="text" placeholder="Ingrese fecha inicio" name="fecha_inicio" id="fecha_inicio" class="fecha_normal form-control form-control-sm" autocomplete="off"/>
	                    </div>
                    </div>

                    <div class="col-lg-12">
                      <div class="form-group"> 
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Fecha Término </label>
                         <input  type="text" placeholder="Ingrese fecha Término" name="fecha_termino" id="fecha_termino" class="fecha_normal form-control form-control-sm" autocomplete="off"/>
                      </div>
                    </div>

                    <div class="col-lg-12"> 
                      <div class="form-group"> 
                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Adjunto</label>
                        <input type="file" id="userfile" name="userfile">
                      </div>
                    </div>
	                    
	                </div>
	            </fieldset>

	            <br>

	            <div class="col-lg-8 offset-lg-2">
	              <div class="form-row">
	                
	                <!-- <div class="col-6 col-lg-4">
	                  <div class="form-group">  
	                    <div class="form-check">
	                      <input type="checkbox" checked name="checkcorreo" class="form-check-input" id="checkcorreo">
	                      <label class="form-check-label" for="checkcorreo">¿Enviar correo?</label>
	                    </div>
	                  </div>
	                </div> -->

	                <div class="col-6 col-lg-6">
	                  <div class="form-group">
	                    <button type="submit" class="btn-block btn btn-sm btn-primary btn_ingreso_licencias">
	                     <i class="fa fa-save"></i> Guardar
	                    </button>
	                  </div>
	                </div>

	                <div class="col-6 col-lg-6">
	                  <button class="btn-block btn btn-sm btn-dark cierra_modal_licencias" data-dismiss="modal" aria-hidden="true">
	                   <i class="fa fa-window-close"></i> Cerrar
	                  </button>
	                </div>
	              </div>
	            </div>
	           </div>
	         <?php echo form_close(); ?>
	      </div>
	    </div>
    </div>


