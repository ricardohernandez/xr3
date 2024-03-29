<style type="text/css">
  
  .btn_eliminar_vacaciones{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
  }

  .btn_editar{
      display: inline-block;
      text-align: center!important;
      margin:0 auto!important;
      font-size: 15px!important;
  }

  @media (max-width: 768px){
    .modal_vacaciones{
      width: 95%!important;
    }
  }

  @media (min-width: 768px){
    .modal_vacaciones{
      width: 35%!important;
    }
  }
</style>

<script type="text/javascript" charset="utf-8"> 
  $(function(){ 

    /*****DATATABLE*****/  
      base = "<?php echo base_url() ?>";
      var tb_vacaciones = $('#tb_vacaciones').DataTable({
         "aaSorting" : [[9,"desc"]],
         "scrollY": "65vh",
         "scrollX": true,
         "sAjaxDataProp": "result",  
         "responsive": false,      
         "bDeferRender": true,
         "select" : true,
         columnDefs: [
            { orderable: false, targets: 0 }
         ],
          "ajax": {
            "url":"<?php echo base_url();?>getVacacionesList",
            "dataSrc": function (json) {
              $(".btn_filtro_vacaciones").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
              $(".btn_filtro_vacaciones").prop("disabled" , false);
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
                btn='<center><a href="#!" title="Modificar" data-hash="'+row.hash_id+'" class="btn_editar"><i class="fa fa-edit"></i></a>';
                btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash_id+'" class="btn_eliminar_vacaciones rojo"><i class="fa fa-trash"></i></a></center>';
                return btn;
              }
            },

            {
              "class":"centered margen-td","data": function(row,type,val,meta){
                botonera="";
                if(row.adjunto!=null && row.adjunto!=""){
                  botonera+='<center><a title="Ver archivo" style="margin-left:10px;" target="_blank" href="'+base+'archivos_vacaciones/'+row.adjunto+'"><i class="fa fa-file verde"></i></a></center>';
                }else{
                  botonera="<center>-</center>";
                }
                return botonera;
              }
            },

            { "data": "usuario" ,"class":"margen-td centered"},
            { "data": "rut" ,"class":"margen-td centered"},
            { "data": "jefe" ,"class":"margen-td centered"},
            { "data": "fecha_inicio" ,"class":"margen-td centered"},
            { "data": "fecha_termino" ,"class":"margen-td centered"},
            { "data": "dias" ,"class":"margen-td centered"},
            { "data": "digitador" ,"class":"margen-td centered"},
            { "data": "ultima_actualizacion" ,"class":"margen-td centered"},
          ]
    }); 


    $(document).on('keyup paste', '#vacaciones', function() {
      tb_vacaciones.search($(this).val().trim()).draw();
    });

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    setTimeout( function () {
      var tb_vacaciones = $.fn.dataTable.fnTables(true);
      if ( tb_vacaciones.length > 0 ) {
          $(tb_vacaciones).dataTable().fnAdjustColumnSizing();
    }}, 200 ); 

    setTimeout( function () {
      var tb_vacaciones = $.fn.dataTable.fnTables(true);
      if ( tb_vacaciones.length > 0 ) {
          $(tb_vacaciones).dataTable().fnAdjustColumnSizing();
    }}, 2000 ); 

    setTimeout( function () {
      var tb_vacaciones = $.fn.dataTable.fnTables(true);
      if ( tb_vacaciones.length > 0 ) {
          $(tb_vacaciones).dataTable().fnAdjustColumnSizing();
      }
    }, 4000 ); 


    $(document).off('click', '.btn_filtro_vacaciones').on('click', '.btn_filtro_vacaciones',function(event) {
     event.preventDefault();
      $(this).prop("disabled" , true);
      $(".btn_filtro_vacaciones").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
       tb_vacaciones.ajax.reload();
    });

    $(document).off('click', '.btn_nuevo_vacaciones').on('click', '.btn_nuevo_vacaciones', function(event) {
        $('#formIngresoVacaciones')[0].reset();
        $("#hash_id_vacaciones").val("");
        $('#modal_vacaciones').modal('toggle'); 
        $("#formIngresoVacaciones input,#formIngresoVacaciones select,#formIngresoVacaciones button,#formIngresoVacaciones").prop("disabled", false);
        $(".btn_ingreso_vacaciones").attr("disabled", false);
        $(".cierra_modal_vacaciones").attr("disabled", false);
        $('#usuarios').val("").trigger('change');

    });

    $(document).off('submit', '#formIngresoVacaciones').on('submit', '#formIngresoVacaciones',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formIngresoVacaciones");
        var formData = new FormData(formElement);
          $.ajax({
              url: $('#formIngresoVacaciones').attr('action')+"?"+$.now(),  
              type: 'POST',
              data: formData,
              cache: false,
              processData: false,
              dataType: "json",
              contentType : false,
              beforeSend:function(){
                $(".btn_ingreso_vacaciones").attr("disabled", true);
                $(".cierra_modal_vacaciones").attr("disabled", true);
                $("#formIngresoVacaciones input,#formIngresoVacaciones select,#formIngresoVacaciones button,#formIngresoVacaciones").prop("disabled", true);
              },

              success: function (data) {
                if(data.res == "sess"){
                  window.location="unlogin";

                }else if(data.res=="ok"){
                  $('#modal_vacaciones').modal('toggle'); 
                  $("#formIngresoVacaciones input,#formIngresoVacaciones select,#formIngresoVacaciones button,#formIngresoVacaciones").prop("disabled", false);
                  $(".btn_ingreso_vacaciones").attr("disabled", false);
                  $(".cierra_modal_vacaciones").attr("disabled", false);

                  $.notify(data.msg, {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $('#formIngresoVacaciones')[0].reset();
                  tb_vacaciones.ajax.reload();
                }else if(data.res=="error"){

                  $(".btn_ingreso_vacaciones").attr("disabled", false);
                  $(".cierra_modal_vacaciones").attr("disabled", false);
                  $.notify(data.msg, {
                    className:'error',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                  $("#formIngresoVacaciones input,#formIngresoVacaciones select,#formIngresoVacaciones button,#formIngresoVacaciones").prop("disabled", false);

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
                        $('#modal_vacaciones').modal("toggle");
                    }    
                    return;
                }

                if (xhr.status == 500) {
                    $.notify("Problemas en el servidor, intente más tarde.", {
                      className:'warn',
                      globalPosition: 'top right'
                    });
                    $('#modal_vacaciones').modal("toggle");
                }

            },timeout:35000
          }); 
        return false; 
    });


    $(document).off('click', '.btn_editar').on('click', '.btn_editar',function(event) {
        event.preventDefault();
        hash_id=$(this).attr("data-hash");
        $('#formIngresoVacaciones')[0].reset();
        $("#hash_id_vacaciones").val("");
        $("#hash_id_vacaciones").val(hash_id);
        $('#modal_vacaciones').modal('toggle'); 
        $("#formIngresoVacaciones input,#formIngresoVacaciones select,#formIngresoVacaciones button,#formIngresoVacaciones").prop("disabled", true);
        $(".btn_ingreso_vacaciones").attr("disabled", true);
        $(".cierra_modal_vacaciones").attr("disabled", true);

        $.ajax({
          url: base+"getDataRegistroVacaciones"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          data:{hash_id:hash_id},
          dataType:"json",
          beforeSend:function(){
           $(".btn_ingreso_vacaciones").prop("disabled",true); 
           $(".cierra_modal_vacaciones").prop("disabled",true); 
          },
          success: function (data) {
            if(data.res=="ok"){
              for(dato in data.datos){
                  $("#hash_id_vacaciones").val(data.datos[dato].hash_id);
                  $('#usuarios').val(data.datos[dato].id_usuario).trigger('change');
                  $("#fecha_inicio").val(data.datos[dato].fecha_inicio);
                  $("#fecha_termino").val(data.datos[dato].fecha_termino);
              }

              $("#formIngresoVacaciones input,#formIngresoVacaciones select,#formIngresoVacaciones button,#formIngresoVacaciones").prop("disabled", false);
              $(".cierra_modal_vacaciones").prop("disabled", false);
              $(".btn_ingreso_vacaciones").prop("disabled", false);

            }else if(data.res == "sess"){
              window.location="../";
            }

            $(".btn_ingreso_vacaciones").prop("disabled",false); 
            $(".cierra_modal_vacaciones").prop("disabled",false); 
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
                $('#modal_vacaciones').modal("toggle");
            }
        },timeout:35000
      }); 
    });

    $(document).off('click', '.btn_eliminar_vacaciones').on('click', '.btn_eliminar_vacaciones',function(event) {
        hash=$(this).data("hash");
        if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post('eliminaVacaciones'+"?"+$.now(),{"hash": hash}, function(data) {
              
              if(data.res=="ok"){
                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right',
                  autoHideDelay:15000
                });
                tb_vacaciones.ajax.reload();

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
           placeholder: 'Seleccione Trabajador...',
           data: response,
           allowClear: true,
          });
    });  

    document.querySelector('#checkinactivos').addEventListener('click', (event) => {
        tb_vacaciones.ajax.reload();
    });

  });
</script>
  

<!--FILTROS-->

  <div class="form-row">
	  <div class="col-6 col-lg-1"> 
	      <div class="form-group">
	         <button type="button" class="btn-block btn btn-sm btn-primary btn_nuevo_vacaciones btn_xr3">
	         <i class="fa fa-plus-circle"></i>  Nuevo 
	         </button>
	      </div>
	    </div>


	    <div class="col-6 col-lg-4">  
	      <div class="form-group">
	      <input type="text" placeholder="Ingrese su busqueda..." id="vacaciones" class="vacaciones form-control form-control-sm">
	      </div>
	    </div>

      <div class="col-6 col-md-2">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="checkinactivos" name="checkinactivos">
                <label class="form-check-label" for="checkinactivos">Incluir inactivos</label>
            </div>
      </div>

	    <div class="col-6 col-lg-1">
	       <div class="form-group">
	          <button type="button" class="btn-block btn btn-sm btn-outline-primary btn-primary btn_filtro_vacaciones btn_xr3">
	       <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar
	       </button>
	       </div>
	    </div>
	</div>

  <div class="row">
    <div class="col-12">
      <table id="tb_vacaciones" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%!important">
          <thead>
            <tr>
              <th class="centered">Acciones</th> 
              <th class="centered">Archivo</th>    
              <th class="centered">Nombre</th>    
              <th class="centered">Rut</th>    
              <th class="centered">Jefe</th>    
              <th class="centered">Fecha inicio</th> 
              <th class="centered">Fecha término</th> 
              <th class="centered">Días</th> 
              <th class="centered">Digitador</th> 
              <th class="centered">Última actualización</th> 
            </tr>
          </thead>
      </table>
    </div>
  </div>

<!--  NUEVO -->
  <div id="modal_vacaciones"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
    <div class="modal-dialog modal_vacaciones">
      <div class="modal-content">
        <?php echo form_open_multipart("formIngresoVacaciones",array("id"=>"formIngresoVacaciones","class"=>"formIngresoVacaciones"))?>
           <input type="hidden" name="hash_id" id="hash_id_vacaciones">
           <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
           <fieldset class="form-ing-cont">
               <legend class="form-ing-border">Datos de ingreso</legend>
                
                <div class="form-row">

                    <div class="col-lg-12">  
    			            <div class="form-group">
        	                  <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Trabajador</label>
    			              <select id="usuarios" name="usuarios" style="width:100%!important;">
    			                  <option value="">Seleccione Trabajador</option>
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
                    <button type="submit" class="btn-block btn btn-sm btn-primary btn_ingreso_vacaciones">
                     <i class="fa fa-save"></i> Guardar
                    </button>
                  </div>
                </div>

                <div class="col-6 col-lg-6">
                  <button class="btn-block btn btn-sm btn-secondary cierra_modal_vacaciones" data-dismiss="modal" aria-hidden="true">
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
