<style type="text/css">
  .rojo{
    color: red;
  }

  .grey{
    background-color: grey;
    color: #fff;
  }

  .btn_archivo{
    display: block;
    text-align: center!important;
    margin:0 auto!important;
    font-size: 14px!important;
  }

  @media(min-width: 768px){
    .btn_eliminar{
      font-size: 14px!important;
      color:#CD2D00;
      margin-left: 10px;
      text-decoration: none!important;
    }

    .btn_editar{
      text-align: center!important;
      font-size: 14px!important;
    }
    .modal_liquidacion{
      width: 40%!important;
    }
    .table_head{
      font-size: 12px!important;
    }

  }
  @media(max-width: 768px){
    .btn_eliminar{
      font-size: 14px!important;
      color:#CD2D00;
      margin-left: 20px;
      text-decoration: none!important;
    }
    .btn_editar{
      display: block;
      text-align: center!important;
      font-size: 14px!important;
    }

    .modal_liquidacion{
      width: 94%!important;
    }
    .table_head{
      font-size: 11px!important;
    }
  }

  .dataTables_paginate .paginate_button {
    margin-top: 20px!important;
    padding: 5px 11px!important;
    line-height: 1.42857143;
    text-decoration: none;
    font-size: 14px;
    color: #ffffff;
    background-color: #32477C!important;
    border: 1px solid transparent;
    margin-left: -1px;
    cursor: pointer;
  }

  div.dataTables_wrapper div.dataTables_info {
    padding-top: 0.1em!important;
    white-space: nowrap;
  }
  

</style>


<script type="text/javascript" charset="utf-8"> 
  $(function(){ 

    /*****DATATABLE*****/  
      const base = "<?php echo base_url() ?>";
      const p ="<?php echo $this->session->userdata('id_perfil'); ?>";

      var tabla_liquidaciones = $('#tabla_liquidaciones').DataTable({
        /*"sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',*/
        "iDisplayLength":-1, 
        "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
        "bPaginate": false,
        "aaSorting" : [[8,"desc"]],
        "scrollY": "60vh",
        "scrollX": true,
        "sAjaxDataProp": "result",        
        "bDeferRender": true,
        "select" : true,
        info:false,
        columnDefs: [
          { orderable: false, targets: 0 }
        ],
        "ajax": {
          "url":"<?php echo base_url();?>getLiquidacionesList",
          "dataSrc": function (json) {
            $(".btn_filtro_liquidacion").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_liquidacion").prop("disabled" , false);
            return json;
          },       
          data: function(param){

            if(p==4){
              var trabajador = $("#trabajador_t").val();
            }else{
              var trabajador = $("#trabajadores_t").val();
            }

            var jefe = $("#jefe_t").val()

            param.trabajador = trabajador;
            param.jefe = jefe;
          }
        },    
        "columns": [
          {
            "class":"centered margen-td","width" : "30px","data": function(row,type,val,meta){
              btn = "";
              if(p<=2){
                btn  =`<center><a  href="#!"   data-hash="${row.hash}"  title="Estado" class="btn_editar" style="font-size:14px!important;"><i class="fas fa-edit"></i> </a>`;
                btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar rojo"><i class="fa fa-trash"></i></a></center>';
              }
              return btn;

            }
          },
          { "data": "usuario" ,"class":"margen-td centered"},
          { "data": "rut_usuario" ,"class":"margen-td centered"},
          { "data": "cargo" ,"class":"margen-td centered"},
          { "data": "jefe" ,"class":"margen-td centered"},
          { "data": "digitador" ,"class":"margen-td centered"},
          { "data": "periodo" ,"class":"margen-td centered"},
          {
            "class":"centered margen-td","data": function(row,type,val,meta){
              btn  =`<a  target="_blank" href="${row.archivo}" title="Archivo" class="btn_archivo"><i class="fas fa-file"></i> </a>`;
              return btn;
            }
          },

          { "data": "ultima_actualizacion" ,"class":"margen-td centered"},
        ]
    }); 

    $(document).on('keyup paste', '#buscador', function() {
      tabla_liquidaciones.search($(this).val().trim()).draw();
    });

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    setTimeout( function () {
      var tabla_liquidaciones = $.fn.dataTable.fnTables(true);
      if ( tabla_liquidaciones.length > 0 ) {
          $(tabla_liquidaciones).dataTable().fnAdjustColumnSizing();
    }}, 200 ); 

    setTimeout( function () {
      var tabla_liquidaciones = $.fn.dataTable.fnTables(true);
      if ( tabla_liquidaciones.length > 0 ) {
          $(tabla_liquidaciones).dataTable().fnAdjustColumnSizing();
    }}, 2000 ); 

    setTimeout( function () {
      var tabla_liquidaciones = $.fn.dataTable.fnTables(true);
      if ( tabla_liquidaciones.length > 0 ) {
          $(tabla_liquidaciones).dataTable().fnAdjustColumnSizing();
      }
    }, 4000 ); 
 
    $(document).off('click', '.btn_filtro_liquidacion').on('click', '.btn_filtro_liquidacion',function(event) {
     event.preventDefault();
      $(this).prop("disabled" , true);
      $(".btn_filtro_liquidacion").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
       tabla_liquidaciones.ajax.reload();
    });

    $(document).off('click', '.btn_nueva_liquidacion').on('click', '.btn_nueva_liquidacion', function(event) {
        $('#formLiquidaciones')[0].reset();
        $("#hash_liqui").val("");
        $('#modal_liquidacion').modal('toggle'); 
        $("#formLiquidaciones input,#formLiquidaciones select,#formLiquidaciones button,#formLiquidaciones").prop("disabled", false);
        $(".btn_ingreso").attr("disabled", false);
        $(".cierra_modal").attr("disabled", false);
        $("#periodo").val(new Date().getFullYear() + '-' + ((new Date().getMonth()+1) < 10 ? '0' : '') + (new Date().getMonth()+1))
    });

    $(document).off('submit', '#formLiquidaciones').on('submit', '#formLiquidaciones',function(event) {
        var url="<?php echo base_url()?>";
        var formElement = document.querySelector("#formLiquidaciones");
        var formData = new FormData(formElement);
          $.ajax({
              url: $('#formLiquidaciones').attr('action')+"?"+$.now(),  
              type: 'POST',
              data: formData,
              cache: false,
              processData: false,
              dataType: "json",
              contentType : false,
              beforeSend:function(){
                /* $(".btn_ingreso").attr("disabled", true);
                $(".cierra_modal").attr("disabled", true);
                $("#formLiquidaciones input,#formLiquidaciones select,#formLiquidaciones button,#formLiquidaciones").prop("disabled", true); */
              },
              success: function (data) {

                if(data.res == "sess"){
                  window.location="unlogin";

                }else if(data.res=="ok"){

                  $('#modal_liquidacion').modal('toggle'); 
                  $("#formLiquidaciones input,#formLiquidaciones select,#formLiquidaciones button,#formLiquidaciones").prop("disabled", false);
                  $(".btn_ingreso").attr("disabled", false);
                  $(".cierra_modal").attr("disabled", false);

                  $.notify(data.msg, {
                    className:'success',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });

                  $('#formLiquidaciones')[0].reset();
                  tabla_liquidaciones.ajax.reload();

                }else if(data.res=="error"){
                      
                  $(".btn_ingreso").attr("disabled", false);
                  $(".cierra_modal").attr("disabled", false);
                  $.notify(data.msg, {
                    className:'error',
                    globalPosition: 'top right',
                    autoHideDelay:5000,
                  });
                  $("#formLiquidaciones input,#formLiquidaciones select,#formLiquidaciones button,#formLiquidaciones").prop("disabled", false);

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
                     /*  $('#modal_liquidacion').modal("toggle"); */
                  }    
                  return;
                }
                if (xhr.status == 500) {
                  $.notify("Problemas en el servidor, intente más tarde.", {
                    className:'warn',
                    globalPosition: 'top right'
                  });
                  /* $('#modal_liquidacion').modal("toggle"); */
                }
            },timeout:35000
          }); 
        return false; 
    });

    $(document).off('click', '.btn_editar').on('click', '.btn_editar',function(event) {
        event.preventDefault();
        $("#hash_liqui").val("");
        hash=$(this).data("hash");
        $('#formLiquidaciones')[0].reset();
        $("#hash_liqui").val(hash);
        $('#modal_liquidacion').modal('toggle'); 
        $("#formLiquidaciones input,#formLiquidaciones select,#formLiquidaciones button,#formLiquidaciones").prop("disabled", true);
        $(".btn_ingreso").attr("disabled", true);
        $(".cierra_modal").attr("disabled", true);

        $.ajax({
          url: base+"getDataLiquidaciones"+"?"+$.now(),  
          type: 'POST',
          cache: false,
          tryCount : 0,
          retryLimit : 3,
          data:{hash:hash},
          dataType:"json",
          beforeSend:function(){
           $(".btn_ingreso").prop("disabled",true); 
           $(".cierra_modal").prop("disabled",true); 
          },
          success: function (data) {
            if(data.res=="ok"){

              for(dato in data.datos){
                $("#hash_liqui").val(data.datos[dato].hash);
                $('#trabajadores').val(data.datos[dato].id_usuario).trigger('change');
                $("#periodo").val(data.datos[dato].periodo);
              }

              $("#formLiquidaciones input,#formLiquidaciones select,#formLiquidaciones button,#formLiquidaciones").prop("disabled", false);
              $(".cierra_modal").prop("disabled", false);
              $(".btn_ingreso").prop("disabled", false);

            }else if(data.res == "sess"){
              window.location="../";
            }

            $(".btn_ingreso").prop("disabled",false); 
            $(".cierra_modal").prop("disabled",false); 
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
                $('#modal_liquidacion').modal("toggle");
            }
        },timeout:35000
      }); 
    });

    $(document).off('click', '.btn_eliminar').on('click', '.btn_eliminar',function(event) {
      hash=$(this).data("hash");
      if(confirm("¿Esta seguro que desea eliminar este registro?")){
        $.post('eliminaLiquidaciones'+"?"+$.now(),{"hash": hash}, function(data) {

          if(data.res=="ok"){

            $.notify(data.msg, {
              className:'success',
              globalPosition: 'top right'
            });
            
            tabla_liquidaciones.ajax.reload();

          }else{

            $.notify(data.msg, {
              className:'danger',
              globalPosition: 'top right'
            });

          }
        },"json");
      }
    });

    $.getJSON(base + "listaTrabajadoresFiltros", { jefe : $("#jefe_t").val() } , function(data) {
      response = data;

    }).done(function() {

         $("#trabajadores_t").select2({
          placeholder: 'Seleccione Trabajador | Todos',
          data: response,
          width: 'resolve',
          allowClear:true,
        });

        $("#trabajador").select2({
          placeholder: 'Seleccione Trabajador | Todos',
          data: response,
          width: 'resolve',
          allowClear:true,
        });
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


    

    $(document).off('change', '#jefe_t').on('change', '#jefe_t',function(event) {
      $.getJSON(base + "listaTrabajadoresFiltros", { 'jefe' : $(this).val() } , function(data) {
      response = data;
      }).done(function() {

        $("#trabajadores_t").empty(); // Vaciar el select2
        $("#trabajadores_t").trigger('change'); // Forzar un cambio para actualizar la vista

        tabla_liquidaciones.ajax.reload()

        $("#trabajadores_t").select2({
          placeholder: 'Seleccione Trabajador | Todos',
          data: response,
          width: 'resolve',
          allowClear:true,
        });

        $("#trabajador").select2({
          placeholder: 'Seleccione Trabajador | Todos',
          data: response,
          width: 'resolve',
          allowClear:true,
        });
      });
      
    }); 


   /*  $(document).off('change', '#trabajadores_t').on('change', '#trabajadores_t',function(event) {
      tabla_liquidaciones.ajax.reload()
    });  */

  });
</script>
  
<!--FILTROS-->

  <div class="form-row">

    <?php  
     if($this->session->userdata('id_perfil')<=3){
    ?>

      <div class="col-1 col-lg-2"> 
        <div class="form-group">
          <button type="button" class="btn-block btn btn-sm btn-outline-primary btn_nueva_liquidacion btn_xr3">
          <i class="fa fa-plus-circle"></i>  Nuevo 
          </button>
        </div>
      </div>

    <?php
      }
    ?>

    <?php  
      if($this->session->userdata('id_perfil')<3){
    ?>

      <div class="col-lg-2">
        <div class="form-group">
          <select id="jefe_t" name="jefe_t" class="custom-select custom-select-sm">
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
        
        <div class="col-lg-2">
          <div class="form-group">
            <select id="jefe_t" name="jefe_t" class="custom-select custom-select-sm">
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
          <div class="col-lg-3">  
            <div class="form-group">
              <select id="trabajadores_t" name="trabajadores_t" style="width:100%!important;">
                  <option value="">Seleccione Trabajador | Todos</option>
              </select>
            </div>
          </div>
          <?php
       }else{
        ?>
          <div class="col-lg-2">  
            <div class="form-group">
              <select id="trabajador_t" name="trabajador_t" class="custom-select custom-select-sm" >
                  <option selected value="<?php echo $this->session->userdata('id'); ?>"><?php echo $this->session->userdata('nombre_completo'); ?></option>
              </select>
            </div>
          </div>
        <?php
       }
    ?>

    <div class="col-2 col-lg-4">  
      <div class="form-group">
      <input type="text" placeholder="Ingrese su busqueda..." id="buscador" class="buscador form-control form-control-sm">
      </div>
    </div>
                              
	</div>

  <div class="row">
    <div class="col-12">
      <table id="tabla_liquidaciones" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%!important">
          <thead>
            <tr>
              <th class="centered">Acciones</th> 
              <th class="centered">Nombre colaborador</th>    
              <th class="centered">RUT</th>    
              <th class="centered">Cargo</th>    
              <th class="centered">Jefe</th> 
              <th class="centered">Digitador</th> 
              <th class="centered">Periodo</th> 
              <th class="centered">Archivo</th> 
              <th class="centered">Última actualización</th> 
            </tr>
          </thead>
      </table>
    </div>
  </div>

<!--  NUEVO -->

  <div id="modal_liquidacion"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">
    <div class="modal-dialog modal_liquidacion">
      <div class="modal-content">
        <?php echo form_open_multipart("formLiquidaciones",array("id"=>"formLiquidaciones","class"=>"formLiquidaciones"))?>
          <input type="hidden" name="hash_liqui" id="hash_liqui">

          <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>
          <fieldset class="form-ing-cont">
          <legend class="form-ing-border">Datos de ingreso</legend>
          
          <div class="form-row">

            <div class="col-lg-6">  
              <div class="form-group">
                <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Trabajador</label>
                <select id="trabajadores" name="trabajadores" style="width:100%!important;">
                    <option value="">Seleccione Trabajador</option>
                </select>
              </div>
            </div>

            <div class="col-lg-6">  
              <div class="form-group">
              <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Periodo</label>
              <input type="month"  placeholder="Periodo" class="form-control form-control-sm"  name="periodo" id="periodo">
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

          <div class="col-lg-12">
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
                  <button type="submit" class="btn-block btn btn-sm btn-primary btn_ingreso">
                    <i class="fa fa-save"></i> Guardar
                  </button>
                </div>
              </div>

              <div class="col-6 col-lg-6">
                <button class="btn-block btn btn-sm btn-dark cierra_modal" data-dismiss="modal" aria-hidden="true">
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
