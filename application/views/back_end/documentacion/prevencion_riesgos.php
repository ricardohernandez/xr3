<style type="text/css">
  .btn_eliminar_prevencion{
      display: inline;
      font-size: 15px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
  }
  .btn_editar_prevencion{
      display: inline-block;
      text-align: center!important;
      margin:0 auto!important;
      font-size: 15px!important;
  }

  @media(min-width: 768px){
    .modal_ingreso_prevencion{
      width: 44%!important;
    }
  }

  @media(max-width: 768px){
    .modal_ingreso_prevencion{
      width: 95%!important;
    }
  }
</style>

<script type="text/javascript" charset="utf-8"> 
  $(function(){ 

    /*****DATATABLE*****/  
      base_url = "<?php echo base_url() ?>";
      const p = "<?php echo $this->session->userdata('id_perfil') ?>";

      var tb_prevencion = $('#tb_prevencion').DataTable({
         
         "aaSorting" : [[5,"desc"]],
         "scrollY": "65vh",
         "scrollX": true,
         "sAjaxDataProp": "result",        
         "bDeferRender": true,
         "select" : true,
         columnDefs: [
            { orderable: false, targets: 0 }
         ],
          "ajax": {
            "url":"<?php echo base_url();?>getPrevencionList",
            "dataSrc": function (json) {
              $(".btn_filtro_prevencion").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
              $(".btn_filtro_prevencion").prop("disabled" , false);
              return json;
            },       
            data: function(param){
            }
          },    
          "columns": [
            {
              "class":"centered margen-td","width" : "5%" , "data": function(row,type,val,meta){
                btn = "";
                if(p<=2){
                 btn+='<center><a href="#!" title="Modificar" data-hash="'+row.hash_id+'" class="btn_editar_prevencion"><i class="fa fa-edit"></i></a>';
                 btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash_id+'" class="btn_eliminar_prevencion rojo"><i class="fa fa-trash"></i></a></center>';
                }
                return btn;
              }
            },
            {
              "class":"centered margen-td",  "width" : "5%","data": function(row,type,val,meta){
                botonera="";
                if(row.archivo!=null && row.archivo!=""){
                  botonera+='<center><a title="Ver archivo" style="margin-left:10px;" target="_blank" href="'+base_url+'archivos_documentacion/prevencion/'+row.archivo+'"><i class="fa fa-file verde"></i></a></center>';
                }else{
                  botonera="<center>-</center>";
                }
                return botonera;
              }
            },
            { "data": "nombre" ,"width" : "25%" ,"class":"margen-td centered"},
            { "data": "fecha" ,"class":"margen-td centered"},
            { "data": "digitador" ,"class":"margen-td centered"},
            { "data": "ultima_actualizacion" ,"class":"margen-td centered"},

          ]
    }); 


    $(document).on('keyup paste', '#buscador_prevencion', function() {
      tb_prevencion.search($(this).val().trim()).draw();
    });

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    setTimeout( function () {
      var tb_prevencion = $.fn.dataTable.fnTables(true);
      if ( tb_prevencion.length > 0 ) {
          $(tb_prevencion).dataTable().fnAdjustColumnSizing();
    }}, 200 ); 

    setTimeout( function () {
      var tb_prevencion = $.fn.dataTable.fnTables(true);
      if ( tb_prevencion.length > 0 ) {
          $(tb_prevencion).dataTable().fnAdjustColumnSizing();
    }}, 2000 ); 

    setTimeout( function () {
      var tb_prevencion = $.fn.dataTable.fnTables(true);
      if ( tb_prevencion.length > 0 ) {
          $(tb_prevencion).dataTable().fnAdjustColumnSizing();
      }
    }, 4000 ); 


    $(document).off('click', '.btn_filtro_prevencion').on('click', '.btn_filtro_prevencion',function(event) {
     event.preventDefault();
      $(this).prop("disabled" , true);
      $(".btn_filtro_prevencion").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
       tb_prevencion.ajax.reload();
    });

    $(document).off('click', '.btn_nuevo_prevencion').on('click', '.btn_nuevo_prevencion', function(event) {
        $('#formIngresoPrevencion')[0].reset();
        $("#hash_id_prevencion").val("");
        $('#modal_ingreso_prevencion').modal('toggle'); 
        $("#formIngresoPrevencion input,#formIngresoPrevencion select,#formIngresoPrevencion button,#formIngresoPrevencion").prop("disabled", false);
        $(".btn_ingreso_prevencion").attr("disabled", false);
        $(".cierra_modal_prevencion").attr("disabled", false);
    });

    $(document).off('submit', '#formIngresoPrevencion').on('submit', '#formIngresoPrevencion',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formIngresoPrevencion");
        var formData = new FormData(formElement);
          $.ajax({
              url: $('#formIngresoPrevencion').attr('action')+"?"+$.now(),  
              type: 'POST',
              data: formData,
              cache: false,
              processData: false,
              dataType: "json",
              contentType : false,
              beforeSend:function(){
                $(".btn_ingreso_prevencion").attr("disabled", true);
                $(".cierra_modal_prevencion").attr("disabled", true);
                $("#formIngresoPrevencion input,#formIngresoPrevencion select,#formIngresoPrevencion button,#formIngresoPrevencion").prop("disabled", true);
                $(".btn_ingreso_prevencion").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...').prop("disabled",true);
              },
              success: function (data) {
                if(data.res == "sess"){
                  window.location="unlogin";

                }else if(data.res=="ok"){
                  $('#modal_ingreso_prevencion').modal('toggle'); 
                  $("#formIngresoPrevencion input,#formIngresoPrevencion select,#formIngresoPrevencion button,#formIngresoPrevencion").prop("disabled", false);
                  $(".btn_ingreso_prevencion").attr("disabled", false);
                  $(".cierra_modal_prevencion").attr("disabled", false);
                  $(".btn_ingreso_prevencion").html(' <i class="fa fa-save"></i> Guardar').prop("disabled",true);

                  $.notify(data.msg, {  
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $('#formIngresoPrevencion')[0].reset();
                  tb_prevencion.ajax.reload();
                }else if(data.res=="error"){
                  $(".btn_ingreso_prevencion").html(' <i class="fa fa-save"></i> Guardar').prop("disabled",true);
                  $(".btn_ingreso_prevencion").attr("disabled", false);
                  $(".cierra_modal_prevencion").attr("disabled", false);
                  $.notify(data.msg, {
                    className:'error',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                  $("#formIngresoPrevencion input,#formIngresoPrevencion select,#formIngresoPrevencion button,#formIngresoPrevencion").prop("disabled", false);

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
                        $('#modal_ingreso_prevencion').modal("toggle");
                    }    
                    return;
                }

                if (xhr.status == 500) {
                    $.notify("Problemas en el servidor, intente más tarde.", {
                      className:'warn',
                      globalPosition: 'top right'
                    });
                    $('#modal_ingreso_prevencion').modal("toggle");
                }

            },timeout:235000
          }); 
        return false; 
    });


    $(document).off('click', '.btn_editar_prevencion').on('click', '.btn_editar_prevencion',function(event) {
        event.preventDefault();
        hash_id=$(this).attr("data-hash");
        $('#formIngresoPrevencion')[0].reset();
        $("#hash_id_prevencion").val("");
        $("#hash_id_prevencion").val(hash_id);
        $('#modal_ingreso_prevencion').modal('toggle'); 
        $("#formIngresoPrevencion input,#formIngresoPrevencion select,#formIngresoPrevencion button,#formIngresoPrevencion").prop("disabled", true);
        $(".btn_ingreso_prevencion").attr("disabled", true);
        $(".cierra_modal_prevencion").attr("disabled", true);

        $.ajax({
          url: base_url+"getDataRegistroPrevencion"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          data:{hash_id:hash_id},
          dataType:"json",
          beforeSend:function(){
           $(".btn_ingreso_prevencion").prop("disabled",true); 
           $(".cierra_modal_prevencion").prop("disabled",true); 
          },
          success: function (data) {
            if(data.res=="ok"){
              for(dato in data.datos){
                  $("#hash_id_prevencion").val(data.datos[dato].hash_id);
                  $("#nombre_archivo").val(data.datos[dato].nombre);
              }

              $("#formIngresoPrevencion input,#formIngresoPrevencion select,#formIngresoPrevencion button,#formIngresoPrevencion").prop("disabled", false);
              $(".cierra_modal_prevencion").prop("disabled", false);
              $(".btn_ingreso_prevencion").prop("disabled", false);

            }else if(data.res == "sess"){
              window.location="../";
            }

            $(".btn_ingreso_prevencion").prop("disabled",false); 
            $(".cierra_modal_prevencion").prop("disabled",false); 
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
                $('#modal_ingreso_prevencion').modal("toggle");
            }
        },timeout:135000
      }); 
    });

    $(document).off('click', '.btn_eliminar_prevencion').on('click', '.btn_eliminar_prevencion',function(event) {
        hash=$(this).data("hash");
        if(confirm("¿Esta seguro que desea eliminar este registro?")){
            $.post(base_url+'eliminaPrevencion'+"?"+$.now(),{"hash": hash}, function(data) {
              
              if(data.res=="ok"){

                $.notify(data.msg, {
                  className:'success',
                  globalPosition: 'top right',
                  autoHideDelay:5000
                });
               tb_prevencion.ajax.reload();

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
    <div class="col-6 col-lg-2"> 
      <div class="form-group">
         <button type="button" class="btn-block btn btn-sm btn-primary btn_nuevo_prevencion btn_xr3">
         <i class="fa fa-plus-circle"></i>  Nuevo archivo prevenci&oacute;n
         </button>
      </div>
    </div>

    <div class="col-6 col-lg-4">  
      <div class="form-group">
      <input type="text" placeholder="Ingrese su busqueda..." id="buscador_prevencion" class="buscador_prevencion form-control form-control-sm">
      </div>
    </div>
    
  </div>

  <div class="row">
    <div class="col-12">
        <table id="tb_prevencion" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%!important">
          <thead>
            <tr>
              <th class="centered">Acciones</th> 
              <th class="centered">Archivo</th>    
              <th class="centered">Nombre</th>    
              <th class="centered">Fecha</th>    
              <th class="centered">Digitador</th> 
              <th class="centered">Última actualización</th> 
            </tr>
          </thead>
        </table>
    </div>
  </div>

<!--  NUEVO -->

    <div id="modal_ingreso_prevencion"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
	    <div class="modal-dialog modal_ingreso_prevencion">
	      <div class="modal-content">
	        <?php echo form_open_multipart("formIngresoPrevencion",array("id"=>"formIngresoPrevencion","class"=>"formIngresoPrevencion"))?>
	           <input type="hidden" name="hash_id" id="hash_id_prevencion">

	           <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
	           <fieldset class="form-ing-cont">
	              <legend class="form-ing-border">Registro de archivos</legend>
	                
	                <div class="form-row">

                    <div class="col-lg-12">
	                    <div class="form-group"> 
	                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nombre </label>
	                       <input  type="text" placeholder="Ingrese nombre archivo" name="nombre_archivo" id="nombre_archivo" class="form-control form-control-sm" autocomplete="off"/>
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
	                    <button type="submit" class="btn-block btn btn-sm btn-primary btn_xr3 btn_ingreso_prevencion">
	                     <i class="fa fa-save"></i> Guardar
	                    </button>
	                  </div>
	                </div>

	                <div class="col-6 col-lg-6">
	                  <button class="btn-block btn btn-sm btn-dark cierra_modal_prevencion" data-dismiss="modal" aria-hidden="true">
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


