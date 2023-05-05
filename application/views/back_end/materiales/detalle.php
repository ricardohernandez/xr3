<style type="text/css">
  .ejemplo_planilla{
    display: inline;
    cursor: pointer;
    color: #17A2B8;
    margin-top:7px;
  }

  .modal-ejemplo{
    width:60%!important;
  }

  .actualizacion_productividad{
      display: inline-block;
      font-size: 11px;
  }
</style>

<script type="text/javascript">
  $(function(){

    var perfil="<?php echo $this->session->userdata('id_perfil'); ?>";
    const base = "<?php echo base_url() ?>";
   
    $('#rut').Rut({
      on_error: function(){ alert('Rut incorrecto'); },
      format_on: 'keyup'
    });

  /*****DATATABLE*****/   
    var lista_detalle = $('#lista_detalle').DataTable({
       /*"sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',*/
       "iDisplayLength":100, 
       "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
       "bPaginate": true,
       "aaSorting" : [[0,"asc"]],
       "scrollY": "65vh",
       "scrollX": true,
       "sAjaxDataProp": "result",        
       "bDeferRender": true,
       "select" : true,
       // "columnDefs": [{ orderable: false, targets: 0 }  ],
       "ajax": {
          "url":"<?php echo base_url();?>listaDetalleMateriales",
          "dataSrc": function (json) {
            $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_detalle").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            
            param.periodo = $("#periodo").val();

            if(perfil==4){
              param.trabajador = $("#trabajador").val();
            }else{
              param.trabajador = $("#trabajadores").val();
            }
          }
        },    
        "columns": [
          { "data": "tecnico" ,"class":"margen-td centered"},
          { "data": "material" ,"class":"margen-td centered"},
          { "data": "serie" ,"class":"margen-td centered"},
          { "data": "tipo" ,"class":"margen-td centered"},
        ]
      }); 

      $(document).on('keyup paste', '#buscador_detalle', function() {
        lista_detalle.search($(this).val().trim()).draw();
      });

      $(document).off('click', '.btn_filtro_detalle').on('click', '.btn_filtro_detalle',function(event) {
        event.preventDefault();
         $(this).prop("disabled" , true);
         $(".btn_filtro_detalle").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
         lista_detalle.ajax.reload();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var lista_detalle = $.fn.dataTable.fnTables(true);
        if ( lista_detalle.length > 0 ) {
            $(lista_detalle).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var lista_detalle = $.fn.dataTable.fnTables(true);
        if ( lista_detalle.length > 0 ) {
            $(lista_detalle).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var lista_detalle = $.fn.dataTable.fnTables(true);
        if ( lista_detalle.length > 0 ) {
            $(lista_detalle).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 


  /********OTROS**********/
    
    $(document).off('click', '.excel_detalle_materiales').on('click', '.excel_detalle_materiales',function(event) {
       event.preventDefault();
      // var desde = $("#desde_f").val();
      // var hasta = $("#hasta_f").val();  
      if(perfil==4){
        trabajador = $("#trabajador").val()
      }else{
        trabajador = $("#trabajadores").val();
      }

      var jefe = perfil<=3 ? $("#jefe_det").val() : "-";
      jefe = jefe=="" ? "-" : jefe;

      if(trabajador==""){
         trabajador="-"
      }

  
      // window.location="excel_detalle_materiales/"+desde+"/"+hasta+"/"+trabajador;
      window.location="excel_detalle_materiales/"+trabajador+"/"+jefe;

    });

    $.getJSON(base + "listaTrabajadoresMateriales", function(data) {
      response = data;
    }).done(function() {
        $("#trabajadores").select2({
         placeholder: 'Seleccione Trabajador | Todos',
         data: response,
         width: 'resolve',
         allowClear:true,
        });
    });


    $(document).off('change', '.file_cs').on('change', '.file_cs',function(event) {
        var myFormData = new FormData();
        myFormData.append('userfile', $('#userfile').prop('files')[0]);
        $.ajax({
            url: "cargaPlanillaMateriales"+"?"+$.now(),  
            type: 'POST',
            data: myFormData,
            cache: false,
            tryCount : 0,
            retryLimit : 3,
            processData: false,
            contentType : false,
            dataType:"json",
            beforeSend:function(){
              $(".btn_file_cs").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Cargando...').prop("disabled",true);
            },  
            success: function (data) {
               $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base ').prop("disabled",false);
                if(data.res=="ok"){
                  $.notify(data.msg, {
                      className:data.tipo,
                      globalPosition: 'top center',
                      autoHideDelay: 20000,
                  });
                  lista_detalle.ajax.reload();
                  actualizacionProductividad()
                }else{
                  $.notify(data.msg, {
                      className:data.tipo,
                      globalPosition: 'top center',
                      autoHideDelay: 10000,
                  });
                }

                $("#userfile").val(null);

            },
            error : function(xhr, textStatus, errorThrown ) {
              $("#userfile").val(null);
              if (textStatus == 'timeout') {
                  this.tryCount++;
                  if (this.tryCount <= this.retryLimit) {
                      $.notify("Reintentando...", {
                        className:'info',
                        globalPosition: 'top center'
                      });
                      $.ajax(this);
                      $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base productividad').prop("disabled",false);
                      return;
                  } else{
                     $.notify("Problemas cargando el archivo, intente nuevamente.", {
                        className:'warn',
                        globalPosition: 'top center',
                        autoHideDelay: 10000,
                      });
                  }    
                  return;
              }

              if (xhr.status == 500) {
                 $.notify("Problemas cargando el archivo, intente nuevamente.", {
                    className:'warn',
                    globalPosition: 'top center',
                    autoHideDelay: 10000,
                 });
              $(".btn_file_cs").html('<i class="fa fa-file-import"></i> Cargar base').prop("disabled",false);
              }
          },timeout:120000
        });
    })

      
    $(document).off('change', '#periodo , #trabajadores ,#jefe_det').on('change', '#periodo , #trabajadores ,#jefe_det', function(event) {
      lista_detalle.ajax.reload()
    }); 


  })
</script>

<!-- FILTROS -->
  
    <div class="form-row">

      <?php
        if($this->session->userdata('id_perfil')<=2){
          ?>
          <div class="col-xs-6 col-sm-6 col-md-1 col-lg-1">  
             <input type="file" id="userfile" name="userfile" class="file_cs" style="display:none;" />
             <button type="button"  class="btn-block btn btn-sm btn-primary btn_file_cs btn_xr3" onclick="document.getElementById('userfile').click();">
             <i class="fa fa-file-import"></i> Cargar base  
          </div>
          <!-- <i class="fa-solid fa-circle-info ejemplo_planilla" title="Ver ejemplo" ></i> -->
          <?php
        }
      ?>
       
      <?php  
       if($this->session->userdata('id_perfil')<>4){
          ?>
            <div class="col-lg-3">  
              <div class="form-group">
                <select id="trabajadores" name="trabajadores" style="width:100%!important;">
                    <option value="">Seleccione Trabajador | Todos</option>
                </select>
              </div>
            </div>
          <?php
       }else{
        ?>
           <div class="col-lg-2">  
              <div class="form-group">
                <select id="trabajador" name="trabajador" class="custom-select custom-select-sm" >
                    <option selected value="<?php echo $this->session->userdata('rut'); ?>"><?php echo $this->session->userdata('nombre_completo'); ?></option>
                </select>
              </div>
            </div>
        <?php
       }
      ?>

      <div class="col-12 col-lg-2">  
       <div class="form-group">
        <input type="text" placeholder="Busqueda" id="buscador_detalle" class="buscador_detalle form-control form-control-sm">
       </div>
      </div>
 
      <div class="col-6 col-lg-1">  
        <div class="form-group">
         <button type="button"  class="btn-block btn btn-sm btn-primary excel_detalle_materiales btn_xr3">
         <i class="fa fa-save"></i> Excel
         </button>
        </div>
      </div>
      
    </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="lista_detalle" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <th class="centered">Técnico</th> 
            <th class="centered">Descripción</th> 
            <th class="centered">Serie</th> 
            <th class="centered">Tipo </th> 
          </tr>
        </thead>
      </table>
    </div>
  </div>

