<style type="text/css">
  .separador{
    background-color: #7C7C7C;
  }
</style>
<script type="text/javascript">
   var tabla_checklist_ast = $('#tabla_checklist_ast').DataTable({
     "sDom": '<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"text-center"ip>>>',
     "iDisplayLength":-1, 
     "lengthMenu": [[5, 15, 50, -1], [5, 15, 50, "Todos"]],
     "bPaginate": false,
     "aaSorting" : [],
     "select" : true,
      "columnDefs" : [
        { orderable: false , targets: 0 ,width:"25%"},
        { orderable: false , targets: 1 ,width:"50%"},
        { orderable: false , targets: 2 ,width:"25%"},
      ],

      /*createdRow: function( row, data, type ) {
          if (data[1] == '<p class="table_text">-</p>') {
              $(row).hide();
          }
      },*/
     // "scrollY": "60vh",
     // "scrollX": true,
     "sAjaxDataProp": "result",        
     "bDeferRender": true

    }); 

    /*if($(window).width() > 560) {
       tabla_checklist_ast.column(0).visible(true);  
    }else{
       tabla_checklist_ast.column(0).visible(false);  
    }*/

    String.prototype.capitalize = function() {
        return this.charAt(0).toUpperCase() + this.slice(1);
    }

    setTimeout( function () {
      $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    }, 500 );

    $(document).on('keyup paste', '.buscador_checklist_ast', function() {
      tabla_checklist_ast.search($(this).val().trim()).draw();
    });

 </script>

  <div class="col-12 col-lg-4 offset-lg-4">  
     <div class="form-group">
      <input type="text" placeholder="Busqueda" id="buscador_checklist_ast" class="buscador_checklist_ast form-control form-control-sm">
     </div>
  </div>
  
  <table id="tabla_checklist_ast" width="100%" class="dataTable datatable_h table table-hover table-bordered table-condensed">
  <thead>
    <tr style="background-color:#F9F9F9">
        <th class="table_head desktop tablet">Tipo</th>
        <th class="table_head all">Descripci&oacute;n</th>
        <th class="table_head all">Resultado</th>
        <!-- <th class="table_head all">Observaci&oacute;n</th> -->
    </tr>
  </thead>
  <tbody>
    <?php 
      if($checklist!=FALSE){
        $contador=0;
        foreach($checklist as $key){
        
          if($contador==0 || $contador==19 || $contador==38 ){
            $style="background-color:#7C7C7C;color:#fff;";
          }else{
            $style="";
          }
          ?>
          <tr style="<?php echo $style;?>">
            <td><?php echo $key["tipo"]; ?> <input type="hidden" name="item[]" value="<?php echo $key["id"] ?>" id="item<?php echo $key["id"] ?>" ></td>
            <td><?php echo $key["descripcion"] ?></td>
            <td><p class="table_text">
              <?php  
                if($key["aplica"]=="si"){ $clase = "";  }
                if($key["aplica"]=="no"){ $clase = "red";  }
                if($key["aplica"]=="no_ap"){ $clase = "grey";  }

                if($key["id_tipo"]=="2"){
                   $opcionok = "Controlado";  
                   $opcionnook = "No controlado";  
                   
                }elseif($key["id_tipo"]<>"2"){
                   $opcionok = "Si";  
                   $opcionnook = "No";  
                }


                /* if($key["id_tipo"]=="2"){
                   $opcionok = "Controlado";  
                   $opcionnook = "No controlado";  
                   $clase2 = "riesgo";
          
                }elseif($key["id_tipo"]=="1"){
                   $opcionok = "Si";  
                   $opcionnook = "No";  
                   $clase2 = "subactividades";

                }elseif($key["id_tipo"]=="3"){
                   $opcionok = "Si";  
                   $opcionnook = "No";  
                   $clase2 = "controles";

                }elseif($key["id_tipo"]=="4"){
                   $opcionok = "Si";  
                   $opcionnook = "No";  
                   $clase2 = "escalamientos";  
                }*/


              ?>
              <select  name="estado_<?php echo $key["id"] ?>[]" id="estado_<?php echo $key["id"] ?>"  class="estado input-xs <?php echo $clase?>">
                <?php  
                  if($key["aplica"]=="si"){
                    ?>
                      <option selected value="si"><?php echo $opcionok?></option>
                      <option value="no"><?php echo $opcionnook?></option>
                      <option value="no_ap">No aplica</option>
                    <?php
                  }
                ?>
                <?php  
                  if($key["aplica"]=="no_ap"){
                    ?>
                      <option value="si"><?php echo $opcionok?></option>
                      <option value="no"><?php echo $opcionnook?></option>
                      <option selected value="no_ap">No aplica</option>
                    <?php
                  }
                ?>
              </select>
            </td>
            <!-- <td>
              <p class="table_text">
                <input type="text" name="observacion_<?php echo $key["id"] ?>[]" id="observacion_<?php echo $key["id"] ?>" placeholder="" size="50" maxlength="50" class="observacion form-control input-xs full-w" autocomplete="off">
              </p>
            </td> -->
          </tr>
        <?php
        $contador++;
        }
      }
    ?>
  </tbody>

  <tfoot>
    <tr>
      <td colspan="3">
        <div class="row ml-1 mr-2">
          <div class="col-1">
            <center><input type="checkbox" checked name="firmado" id="firmado"  class="mt-3"></center>
          </div>
          <div class="col-11">
             <p style="font-size: 13px;font-weight: bold;margin-top: 5px;">NOTA : En caso de no poder controlar los riesgos  que podrian originar un accidente debe llamar inmediatamente al Supervisor
             y en ningún caso comenzar a trabajar sin que el riesgo esté controlado. <br>Declaro bajo juramento que he identificado, 
           evaluado y controlado los riesgos de mi trabajo y que los datos contenidos en el presente documento son la expresión fiel de la verdad, por lo que asumo la responsabilidad
            correspondiente </p>
          </div>
        </div>
      </td>
    </tr>
  </tfoot>
  </table>