<style type="text/css">
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
    var lista_tecnico = $('#lista_tecnico').DataTable({
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
          "url":"<?php echo base_url();?>listaTecnico",
          "dataSrc": function (json) {
            $(".btn_filtro_tecnico").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');
            $(".btn_filtro_tecnico").prop("disabled" , false);
            return json;
          },       
          data: function(param){
            
            if(perfil==4){
              param.trabajador = $("#trabajador").val();
            }else{
              param.trabajador = $("#trabajadores").val();
            }

          }
        },    
        "columns": [
          /* { "data": "tipo" ,"class":"margen-td centered"}, */
          { "data": "tecnico" ,"class":"margen-td centered"},
          { "data": "directa" ,"class":"margen-td centered"},
          { "data": "reversa" ,"class":"margen-td centered"},
        ]
      }); 

      $(document).on('keyup paste', '#buscador_tecnico', function() {
        lista_tecnico.search($(this).val().trim()).draw();
      });

      $(document).off('click', '.btn_filtro_tecnico').on('click', '.btn_filtro_tecnico',function(event) {
        event.preventDefault();
         $(this).prop("disabled" , true);
         $(".btn_filtro_tecnico").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');
         lista_tecnico.ajax.reload();
      });

      String.prototype.capitalize = function() {
          return this.charAt(0).toUpperCase() + this.slice(1);
      }

      setTimeout( function () {
        var lista_tecnico = $.fn.dataTable.fnTables(true);
        if ( lista_tecnico.length > 0 ) {
            $(lista_tecnico).dataTable().fnAdjustColumnSizing();
      }}, 200 ); 

      setTimeout( function () {
        var lista_tecnico = $.fn.dataTable.fnTables(true);
        if ( lista_tecnico.length > 0 ) {
            $(lista_tecnico).dataTable().fnAdjustColumnSizing();
      }}, 2000 ); 

      setTimeout( function () {
        var lista_tecnico = $.fn.dataTable.fnTables(true);
        if ( lista_tecnico.length > 0 ) {
            $(lista_tecnico).dataTable().fnAdjustColumnSizing();
        }
      }, 4000 ); 


  /********OTROS**********/
    
    $(document).off('click', '.excel_detalle').on('click', '.excel_detalle',function(event) {
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

      window.location="excel_tecnico/"+trabajador+"/"+jefe;

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
 
    $(document).off('change', '#periodo , #trabajadores ,#jefe_det').on('change', '#periodo , #trabajadores ,#jefe_det', function(event) {
      lista_tecnico.ajax.reload()
    }); 


  })
</script>

<!-- FILTROS -->
  
    <div class="form-row">
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
        <input type="text" placeholder="Busqueda" id="buscador_tecnico" class="buscador_tecnico form-control form-control-sm">
       </div>
      </div>
 
      <div class="col-6 col-lg-1">  
        <div class="form-group">
         <button type="button"  class="btn-block btn btn-sm btn-primary excel_detalle btn_xr3">
         <i class="fa fa-save"></i> Excel
         </button>
        </div>
      </div>
      
    </div>            

<!-- LISTADO -->

  <div class="row">
    <div class="col-lg-12">
      <table id="lista_tecnico" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
          <tr>    
            <!-- <th class="centered">Tipo</th>  -->
            <th class="centered">Técnico</th> 
            <th class="centered">Directa</th> 
            <th class="centered">Reversa </th> 
          </tr>
        </thead>
      </table>
    </div>
  </div>
