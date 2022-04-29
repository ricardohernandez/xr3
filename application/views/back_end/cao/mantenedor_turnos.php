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

  .btn_eliminar_mantenedor_turno{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
  }

  .btn_editar_mantenedor_turnos{
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

    .modal_mantenedor_turnos{
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

  	 $(document).off('keydown', '.numbersOnly').on('keydown', '.numbersOnly',function(e) {
      if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
          (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
          (e.keyCode >= 35 && e.keyCode <= 40)) {
               return;
      }
      if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
          e.preventDefault();
      }
    });

    /*****DATATABLE*****/  
      base = "<?php echo base_url() ?>";
      var tb_mantenedor_turnos = $('#tb_mantenedor_turnos').DataTable({
         "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
         "iDisplayLength":-1, 
         "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
         "bPaginate": false,
         "aaSorting" : [[1,"asc"]],
         "scrollY": "60vh",
         "scrollX": true,
         "sAjaxDataProp": "result",        
         "bDeferRender": true,
         "select" : true,
         columnDefs: [
            { orderable: false, targets: 0 }
         ],
          "ajax": {
            "url":"<?php echo base_url();?>getMantenedorTurnosList",
            "dataSrc": function (json) {
              $(".btn_filtro_mantenedor_turnos").html('  <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
              $(".btn_filtro_mantenedor_turnos").prop("disabled" , false);
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
              "class":"centered margen-td", "width" : "5%" , "data": function(row,type,val,meta){
                if(row.codigo!="LIC" && row.codigo!="VAC"){
                  btn='<center><a href="#!" title="Modificar" data-hash="'+row.hash_id+'" class="btn_editar_mantenedor_turnos"><i class="fa fa-edit"></i></a></center>';
                }else{
                  btn = "<center>-</center>"
                }
                //btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash_id+'" class="btn_eliminar_mantenedor_turno rojo"><i class="fa fa-trash"></i></a></center>';
                return btn;
              }
            },

            { "data": "codigo" ,"class":"margen-td centered"},
            { "data": "rango_horario" ,"class":"margen-td centered"},
            { "data": "estado" ,"class":"margen-td centered"},
            { "data": "suma" ,"class":"margen-td centered"},
          ]
    }); 


    $(document).on('keyup paste', '#buscador_vacaciones', function() {
      tb_mantenedor_turnos.search($(this).val().trim()).draw();
    });

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    setTimeout( function () {
      var tb_mantenedor_turnos = $.fn.dataTable.fnTables(true);
      if ( tb_mantenedor_turnos.length > 0 ) {
          $(tb_mantenedor_turnos).dataTable().fnAdjustColumnSizing();
    }}, 200 ); 

    setTimeout( function () {
      var tb_mantenedor_turnos = $.fn.dataTable.fnTables(true);
      if ( tb_mantenedor_turnos.length > 0 ) {
          $(tb_mantenedor_turnos).dataTable().fnAdjustColumnSizing();
    }}, 2000 ); 

    setTimeout( function () {
      var tb_mantenedor_turnos = $.fn.dataTable.fnTables(true);
      if ( tb_mantenedor_turnos.length > 0 ) {
          $(tb_mantenedor_turnos).dataTable().fnAdjustColumnSizing();
      }
    }, 4000 ); 


    $(document).off('click', '.btn_filtro_mantenedor_turnos').on('click', '.btn_filtro_mantenedor_turnos',function(event) {
     event.preventDefault();
      $(this).prop("disabled" , true);
      $(".btn_filtro_mantenedor_turnos").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
       tb_mantenedor_turnos.ajax.reload();
    });

    $(document).off('click', '.btn_nuevo_mantenedor_turnos').on('click', '.btn_nuevo_mantenedor_turnos', function(event) {
        $('#formMantenedorTurnos')[0].reset();
        $("#hash_id_mantenedor_turnos").val("");
        $('#modal_mantenedor_turnos').modal('toggle'); 
        $("#formMantenedorTurnos input,#formMantenedorTurnos select,#formMantenedorTurnos button,#formMantenedorTurnos").prop("disabled", false);
        $(".btn_mantenedor_turnos").attr("disabled", false);
        $(".cierra_modal_mantenedor_turnos").attr("disabled", false);
    });

    $(document).off('submit', '#formMantenedorTurnos').on('submit', '#formMantenedorTurnos',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formMantenedorTurnos");
        var formData = new FormData(formElement);
          $.ajax({
              url: $('#formMantenedorTurnos').attr('action')+"?"+$.now(),  
              type: 'POST',
              data: formData,
              cache: false,
              processData: false,
              dataType: "json",
              contentType : false,
              beforeSend:function(){
                $(".btn_mantenedor_turnos").attr("disabled", true);
                $(".cierra_modal_mantenedor_turnos").attr("disabled", true);
                $("#formMantenedorTurnos input,#formMantenedorTurnos select,#formMantenedorTurnos button,#formMantenedorTurnos").prop("disabled", true);
              },

              success: function (data) {
                if(data.res == "sess"){
                  window.location="unlogin";

                }else if(data.res=="ok"){
                  $('#modal_mantenedor_turnos').modal('toggle'); 
                  $("#formMantenedorTurnos input,#formMantenedorTurnos select,#formMantenedorTurnos button,#formMantenedorTurnos").prop("disabled", false);
                  $(".btn_mantenedor_turnos").attr("disabled", false);
                  $(".cierra_modal_mantenedor_turnos").attr("disabled", false);

                  $.notify(data.msg, {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $('#formMantenedorTurnos')[0].reset();
                  tb_mantenedor_turnos.ajax.reload();
                }else if(data.res=="error"){

                  $(".btn_mantenedor_turnos").attr("disabled", false);
                  $(".cierra_modal_mantenedor_turnos").attr("disabled", false);
                  $.notify(data.msg, {
                    className:'error',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                  $("#formMantenedorTurnos input,#formMantenedorTurnos select,#formMantenedorTurnos button,#formMantenedorTurnos").prop("disabled", false);

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
                        $('#modal_mantenedor_turnos').modal("toggle");
                    }    
                    return;
                }

                if (xhr.status == 500) {
                    $.notify("Problemas en el servidor, intente más tarde.", {
                      className:'warn',
                      globalPosition: 'top right'
                    });
                    $('#modal_mantenedor_turnos').modal("toggle");
                }

            },timeout:35000
          }); 
        return false; 
    });


    $(document).off('click', '.btn_editar_mantenedor_turnos').on('click', '.btn_editar_mantenedor_turnos',function(event) {
        event.preventDefault();
        hash_id=$(this).attr("data-hash");
        $('#formMantenedorTurnos')[0].reset();
        $("#hash_id_mantenedor_turnos").val("");
        $("#hash_id_mantenedor_turnos").val(hash_id);
        $('#modal_mantenedor_turnos').modal('toggle'); 
        $("#formMantenedorTurnos input,#formMantenedorTurnos select,#formMantenedorTurnos button,#formMantenedorTurnos").prop("disabled", true);
        $(".btn_mantenedor_turnos").attr("disabled", true);
        $(".cierra_modal_mantenedor_turnos").attr("disabled", true);

        $.ajax({
          url: base+"getDataMantenedorTurnos"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          data:{hash_id:hash_id},
          dataType:"json",
          beforeSend:function(){
           $(".btn_mantenedor_turnos").prop("disabled",true); 
           $(".cierra_modal_mantenedor_turnos").prop("disabled",true); 
          },
          success: function (data) {
            if(data.res=="ok"){
              for(dato in data.datos){
                  $("#codigo").val(data.datos[dato].codigo);
                  $("#rango_horario").val(data.datos[dato].rango_horario);
            	  $("#estado  option[value='"+data.datos[dato].estado+"'").prop("selected", true);
                  $("#suma").val(data.datos[dato].suma);
              }

              $("#formMantenedorTurnos input,#formMantenedorTurnos select,#formMantenedorTurnos button,#formMantenedorTurnos").prop("disabled", false);
              $(".cierra_modal_mantenedor_turnos").prop("disabled", false);
              $(".btn_mantenedor_turnos").prop("disabled", false);

            }else if(data.res == "sess"){
              window.location="../";
            }

            $(".btn_mantenedor_turnos").prop("disabled",false); 
            $(".cierra_modal_mantenedor_turnos").prop("disabled",false); 
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
                $('#modal_mantenedor_turnos').modal("toggle");
            }
        },timeout:35000
      }); 
    });

    $(document).off('click', '.btn_eliminar_mantenedor_turno').on('click', '.btn_eliminar_mantenedor_turno',function(event) {
        hash=$(this).data("hash");
        if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaMantenedorTurnos'+"?"+$.now(),{"hash": hash}, function(data) {
              
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right',
                  autoHideDelay:15000
                });
                tb_mantenedor_turnos.ajax.reload();

              }else{

                $.notify(data.msg, {
                  className:'danger',
                  globalPosition: 'top right'
                });

              }
            },"json");
          }
    });


  });
</script>
  

<!--FILTROS-->

  <div class="form-row">
	  <div class="col-1 col-lg-1"> 
	      <div class="form-group">
	         <button type="button" class="btn-block btn btn-sm btn-outline-primary btn_nuevo_mantenedor_turnos btn_xr3">
	         <i class="fa fa-plus-circle"></i>  Nuevo 
	         </button>
	      </div>
	    </div>

	    <div class="col-2 col-lg-4">  
	      <div class="form-group">
	      <input type="text" placeholder="Ingrese su busqueda..." id="buscador_vacaciones" class="buscador_vacaciones form-control form-control-sm">
	      </div>
	    </div>

	    <div class="col-2 col-lg-2">
	       <div class="form-group">
	          <button type="button" class="btn-block btn btn-sm btn-outline-primary btn-primary btn_filtro_mantenedor_turnos btn_xr3">
	            <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
	          </button>
	       </div>
	    </div>
	</div>

  <div class="row">
    <div class="col-12">
      <table id="tb_mantenedor_turnos" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%!important">
          <thead>
            <tr>
              <th class="centered">Acciones</th> 
              <th class="centered">Código</th>    
              <th class="centered">Rango horario</th>    
              <th class="centered">Estado</th>    
              <th class="centered">Suma</th>    
            </tr>
          </thead>
      </table>
    </div>
  </div>

<!--  NUEVO -->
  <div id="modal_mantenedor_turnos"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
    <div class="modal-dialog modal_mantenedor_turnos">
      <div class="modal-content">
        <?php echo form_open_multipart("formMantenedorTurnos",array("id"=>"formMantenedorTurnos","class"=>"formMantenedorTurnos"))?>
           <input type="hidden" name="hash_id" id="hash_id_mantenedor_turnos">
           <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
           <fieldset class="form-ing-cont">
           <legend class="form-ing-border">Datos de ingreso</legend>
            
            <div class="form-row">

            	<div class="col-lg-12">
                    <div class="form-group"> 
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Código </label>
                       <input  type="text" placeholder="Ingrese código" name="codigo"  id="codigo" size="3" maxlength="3" class="form-control form-control-sm" autocomplete="off"/>
                    </div>
                </div>
                  
                <div class="col-lg-12">
                    <div class="form-group"> 
                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Rango horario </label>
                       <input  type="text" placeholder="Ingrese Rango horario"  name="rango_horario" id="rango_horario" size="50" maxlength="50" class="form-control form-control-sm" autocomplete="off"/>
                    </div>
                </div>

                <div class="col-lg-12">
	                <div class="form-group">
		                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Estado</label>
		                <select id="estado" name="estado" class="custom-select custom-select-sm">
		                  <option value="">Seleccione </option>
		                  <option value="activo" selected>Activo</option>
		                  <option value="no activo">No activo</option>
		                </select>
	                </div>
	            </div>

                <div class="col-lg-12">
                  <div class="form-group"> 
                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Suma </label>
                     <input  type="text" placeholder="Ingrese Suma" name="suma" id="suma" size="1" maxlength="1" class="numbersOnly form-control form-control-sm" autocomplete="off"/>
                  </div>
                </div>
                
            </div>
            </fieldset>

            <br>

            <div class="col-lg-8 offset-lg-2">
              <div class="form-row">
                
                <div class="col-6 col-lg-6">
                  <div class="form-group">
                    <button type="submit" class="btn-block btn btn-sm btn-primary btn_mantenedor_turnos">
                     <i class="fa fa-save"></i> Guardar
                    </button>
                  </div>
                </div>

                <div class="col-6 col-lg-6">
                  <button class="btn-block btn btn-sm btn-dark cierra_modal_mantenedor_turnos" data-dismiss="modal" aria-hidden="true">
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
