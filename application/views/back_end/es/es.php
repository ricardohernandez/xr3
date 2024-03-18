<style type="text/css">
  .actualizacion_calidad{
      display: inline-block;
      font-size: 13px;
  }
  .btn_eliminar_rop{

      display: inline;

      font-size: 15px!important;

      color:#CD2D00;

      margin-left: 10px;

      text-decoration: none!important;

  }

  .btn_editar_rop{

      display: inline;

      text-align: center!important;

      margin:0 auto!important;

      font-size: 15px!important;

  }



  @media(min-width: 768px){

    .modal_es{

      width: 74%!important;

    }

  }



  @media(max-width: 768px){

    .modal_es{

      width: 95%!important;

    }

  }



  .cont_edicion{

    display:none;

  }

</style>



<script type="text/javascript" charset="utf-8"> 

  $(function(){ 



    var desde="<?php echo $desde; ?>";

    var hasta="<?php echo $hasta; ?>";

    $("#desde_f").val(desde);

    $("#hasta_f").val(hasta);



    

    /*****DATATABLE*****/  

      const base = "<?php echo base_url() ?>";

      const p ="<?php echo $this->session->userdata('id_perfil'); ?>";

      const i ="<?php echo $this->session->userdata('id'); ?>";



      var tabla_es = $('#tabla_es').DataTable({

         "scrollY": "65vh",

         "responsive":false,

         "scrollX": true,

         "sAjaxDataProp": "result",        

         "bDeferRender": true,

         "select" : true,

          columnDefs: [

              { orderable: false, targets: 0 }

          ],

          "ajax": {

            "url":"<?php echo base_url();?>getEsList",

            "dataSrc": function (json) {

              $(".btn_filtro_rop").html('<i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar');

              $(".btn_filtro_rop").prop("disabled" , false);

              return json;

            },       

            data: function(param){

              param.estado = $("#estado_f").val();

              param.desde = $("#desde_f").val();

              param.hasta = $("#hasta_f").val();

              param.responsable = $("#responsable_f").val();

            }

          },    

          "columns": [

            {

              "class":"centered margen-td","data": function(row,type,val,meta){

                var color = row.id_estado === "0" ? "red" :

                  row.id_estado === "1" ? "#007BFF" :

                  row.id_estado === "2" ? "green" :

                  row.id_estado === "3" ? "black" :

                  "";

                

                btn =`<center><a  href="#!"   data-hash="${row.hash}"  title="Estado" class="btn_editar_rop" style="color:${color};font-size:12px!important;"><i class="fas fa-edit"></i> </a>`;

              

                if(row.id_estado==="0" || row.id_estado==="1"){

                  btn+='<a href="#!" title="Eliminar" data-hash="'+row.hash+'" class="btn_eliminar_rop rojo"><i class="fa fa-trash"></i></a></center>';

                }



                /* btn='<center><a href="#!" title="Modificar" data-hash="'+row.hash+'" class="btn_editar_rop"><i class="fa fa-edit"></i></a>'; */





                return btn;

              }

            },

            { "data": "id_rop" ,"class":"margen-td centered"},



            {

              "class":"centered margen-td","data": function(row,type,val,meta){

                botonera="";

                if(row.adjunto_requerimiento_1!=null && row.adjunto_requerimiento_1!=""){

                  botonera+='<center><a title="Ver archivo" style="margin-left:10px;" target="_blank" href="'+base+'archivos/es/'+row.adjunto_requerimiento_1+'"><i class="fa fa-file verde"></i></a></center>';

                }else{

                  botonera="<center>-</center>";

                }

                return botonera;

              }

            },



            {

              "class":"centered margen-td","data": function(row,type,val,meta){

                botonera="";

                if(row.adjunto_respuesta_1!=null && row.adjunto_respuesta_1!=""){

                  botonera+='<center><a title="Ver archivo" style="margin-left:10px;" target="_blank" href="'+base+'archivos/es/'+row.adjunto_respuesta_1+'"><i class="fa fa-file verde"></i></a></center>';

                }else{

                  botonera="<center>-</center>";

                }

                return botonera;

              }

            },

            { "data": "estado" ,"class":"margen-td centered"},

            { "data": "tecnico" ,"class":"margen-td centered"},

            { "data": "zona" ,"class":"margen-td centered"},



            { "data": "tipo" ,"class":"margen-td centered"},

            { "data": "ciudad" ,"class":"margen-td centered"},

            { "data": "descripcion" ,"class":"margen-td centered"},



            { "data": "cierre" ,"class":"margen-td centered"},

            { "data": "observacion_fin" ,"class":"margen-td centered"},

            { "data": "ultima_actualizacion" ,"class":"margen-td centered"},

          

          ]

    }); 



    $(document).on('keyup paste', '#buscador', function() {

      tabla_es.search($(this).val().trim()).draw();

    });



    String.prototype.capitalize = function() {

        return this.charAt(0).toUpperCase() + this.slice(1);

    }



    setTimeout( function () {

      var tabla_es = $.fn.dataTable.fnTables(true);

      if ( tabla_es.length > 0 ) {

          $(tabla_es).dataTable().fnAdjustColumnSizing();

    }}, 200 ); 



    setTimeout( function () {

      var tabla_es = $.fn.dataTable.fnTables(true);

      if ( tabla_es.length > 0 ) {

          $(tabla_es).dataTable().fnAdjustColumnSizing();

    }}, 2000 ); 



    setTimeout( function () {

      var tabla_es = $.fn.dataTable.fnTables(true);

      if ( tabla_es.length > 0 ) {

          $(tabla_es).dataTable().fnAdjustColumnSizing();

      }

    }, 4000 ); 



    $(document).on('click', '.ver_obs_desp', function(event) {

      event.preventDefault();

      val=$(this).attr("data-tit");

      elem=$(this);

      if(elem.text()=="Ver más"){

        elem.html("Ocultar");     

        elem.attr("title","Acortar texto");

        elem.prev(".btndesp2").text(val.replace(/_/g, ' '));

        var table = $.fn.dataTable.fnTables(true);

        if ( table.length > 0 ) {

            $(table).dataTable().fnAdjustColumnSizing();

        }

      }else if(elem.text()=="Ocultar"){

        val = val.substring(0,60)+"...";

        elem.prev(".btndesp2").text(val.replace(/_/g, ' '));     

        elem.html("Ver más");

        elem.attr("title","Ver texto completo");

        var table = $.fn.dataTable.fnTables(true);

        if ( table.length > 0 ) {

            $(table).dataTable().fnAdjustColumnSizing();

        }

      }

    });



    $(document).off('click', '.btn_filtro_rop').on('click', '.btn_filtro_rop',function(event) {

     event.preventDefault();

      $(this).prop("disabled" , true);

      $(".btn_filtro_rop").html('<i class="fa fa-cog fa-spin fa-1x fa-fw"></i><span class="sr-only"></span> Filtrando');

       tabla_es.ajax.reload();

    });



    $(document).off('click', '.btn_nuevo_rop').on('click', '.btn_nuevo_rop', function(event) {

        $('#formEs')[0].reset();

        $("#hash_rop").val("");

        $(".cont_edicion").hide();

        $('#modal_es').modal('toggle'); 

        $("#formEs input,#formEs select,#formEs button,#formEs").prop("disabled", false);

        $(".btn_ingreso_rop").attr("disabled", false);

        $(".cierra_modal_es").attr("disabled", false);



        $(".finalizado_cont").show()

        $(".guardar_cont").show()

        $(".cont_correo").show()

        $(".requiere_validar_cont").hide()

        $(".finalizado_cont").hide()

        $('#checkcorreo').prop('checked', true);



        $.getJSON(base + "listaRequerimientos" , {tipo: ""},function(data) {

          response = data;

         

        }).done(function() {

            if(response!=""){

              $("#requerimiento").empty().select2('destroy');



              var init = $('<option>', {

                  value: '',

                  text: 'Seleccione Requerimiento | Todos'

              });



              $('#requerimiento').append(init);



              $("#requerimiento").select2({

                placeholder: 'Seleccione requerimiento',

                data: response,

                width: '100%',

                allowClear:true,

              });

            }else{

              $("#requerimiento").empty()

              $('#requerimiento').val("").trigger('change');

            }

        });



        $.getJSON(base + "listaPersonas" , function(data) {

            response = data;

        }).done(function() {

          if(response!=""){

            $("#tecnico").empty().select2('destroy');



            var init = $('<option>', {

                value: '',

                text: 'Seleccione tecnico | Todos'

            });



            $('#tecnico').append(init);



            $("#tecnico").select2({

              placeholder: 'Seleccione tecnico',

              data: response,

              width: '100%',

              allowClear:true,

            });

            $('#tecnico').val("<?php echo $this->session->userdata('id'); ?>").trigger('change');



          }else{

            $("#tecnico").empty()

            $('#tecnico').val("").trigger('change');

          }

      });

    });



    $(document).off('submit', '#formEs').on('submit', '#formEs',function(event) {

        var url="<?php echo base_url()?>";

        var formElement = document.querySelector("#formEs");

        var formData = new FormData(formElement);

          $.ajax({

              url: $('#formEs').attr('action')+"?"+$.now(),  

              type: 'POST',

              data: formData,

              cache: false,

              processData: false,

              dataType: "json",

              contentType : false,

              beforeSend:function(){

                $(".btn_ingreso_rop").attr("disabled", true);

                $(".cierra_modal_es").attr("disabled", true);

                $("#formEs input,#formEs select,#formEs button,#formEs").prop("disabled", true);

              },



              success: function (data) {

                if(data.res == "sess"){

                  window.location="unlogin";



                }else if(data.res=="ok"){

                

                  $("#formEs input,#formEs select,#formEs button,#formEs").prop("disabled", false);

                  $(".btn_ingreso_rop").attr("disabled", false);

                  $(".cierra_modal_es").attr("disabled", false);



                  $.notify(data.msg, {

                    className:'success',

                    globalPosition: 'top right',

                    autoHideDelay:5000,

                  });



                  $('#formEs')[0].reset();

                  tabla_es.ajax.reload(); 

                  $('#modal_es').modal('toggle'); 

                



                }else if(data.res=="error"){



                  $(".btn_ingreso_rop").attr("disabled", false);

                  $(".cierra_modal_es").attr("disabled", false);

                  $.notify(data.msg, {

                    className:'error',

                    globalPosition: 'top right',

                    autoHideDelay:5000,

                  });

                  $("#formEs input,#formEs select,#formEs button,#formEs").prop("disabled", false);



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

                        $('#modal_es').modal("toggle");

                    }    

                    return;

                }



                if (xhr.status == 500) {

                    $.notify("Problemas en el servidor, intente más tarde.", {

                      className:'warn',

                      globalPosition: 'top right'

                    });

                    $('#modal_es').modal("toggle");

                }



            },timeout:35000

          }); 

        return false; 

    });

 

    $(document).off('click', '.btn_editar_rop').on('click', '.btn_editar_rop',function(event) {

        event.preventDefault();

        $("#hash_rop").val("");

        hash=$(this).data("hash");

        $('#formEs')[0].reset();

        $(".cont_edicion").show();

        $('#modal_es').modal('toggle'); 

        $("#formEs input,#formEs select,#formEs button,#formEs").prop("disabled", true);

        $(".btn_ingreso_rop").attr("disabled", true);

        $(".cierra_modal_es").attr("disabled", true);

        $('#checkcorreo').prop('checked', true);



        $.ajax({

          url: base+"getDataEs"+"?"+$.now(),  

          type: 'POST',

          cache: false,

          tryCount : 0,

          retryLimit : 3,

          data:{hash:hash},

          dataType:"json",

          beforeSend:function(){

           $(".btn_ingreso_rop").prop("disabled",true); 

           $(".cierra_modal_es").prop("disabled",true); 

          },

          success: function (data) {

            if(data.res=="ok"){

              for(dato in data.datos){



                  $("#hash_rop").val(data.datos[dato].hash);

                  $("#id_rop").val(data.datos[dato].id_rop);

                  $("#titulo").val(data.datos[dato].titulo);

                  $("#descripcion").val(data.datos[dato].descripcion);

                  $("#tipo option[value='"+data.datos[dato].id_tipo+"'").prop("selected", true);

                  $("#estado option[value='"+data.datos[dato].id_estado+"'").prop("selected", true);

                  $('#usuario_asignado').val(data.datos[dato].id_usuario_asignado).trigger('change');

                  $('#tecnico').val(data.datos[dato].id_tecnico).trigger('change');

                  $("#observacion").val(data.datos[dato].observacion);



                  $("#fecha_ingreso").val(data.datos[dato].fecha_ingreso+" "+data.datos[dato].hora_ingreso);

                  $("#responsable").val(data.datos[dato].responsable1);

                  $("#validador_sistema").val(data.datos[dato].validador_sistema);

                  $("#validador_real").val(data.datos[dato].validador_real);

                  

                  if(data.datos[dato].fecha_validacion!="No aplica"){

                    $("#fecha_validacion").val(data.datos[dato].fecha_validacion+" "+data.datos[dato].hora_validacion);

                  }else{

                    $("#fecha_validacion").val('No aplica');

                  }



                  $("#horas_estimadas").val(data.datos[dato].horas_estimadas);

                  $("#horas_pendiente").val(data.datos[dato].horas_pendientes);

                  $("#fecha_finalizado").val(data.datos[dato].fecha_fin+" "+data.datos[dato].hora_fin);

                  $("#observacion").val(data.datos[dato].observacion_fin);

                  /* $("#checkvalidar").val(data.datos[dato].titulo);

                  $("#checkfin").val(data.datos[dato].checkfin); */

                  $("#estado_str").text(data.datos[dato].estado);

                  

                  data.datos[dato].requiere_validacion == 1 ? $(".requiere_validar_cont").show() : $(".requiere_validar_cont").hide();

                

                  if(data.datos[dato].id_estado==2 || data.datos[dato].id_estado==3){

                     $(".requiere_validar_cont").hide()

                     $(".finalizado_cont").hide()

                     $(".guardar_cont").hide()

                     $(".cont_correo").hide()

                  }else{

                    $(".guardar_cont").show()

                    $(".finalizado_cont").show()

                    $(".cont_correo").show()

                  }



                  $.getJSON(base + "listaRequerimientos" , {tipo:data.datos[dato].id_tipo},function(data) {

                      response = data;

                  }).done(function() {



                      $("#requerimiento").empty().select2('destroy');

                      

                      $("#requerimiento").select2({

                        placeholder: 'Seleccione requerimiento',

                        data: response,

                        width: '100%',

                        allowClear:true,

                      });



                      $('#requerimiento').val(data.datos[dato].id_requerimiento).trigger('change');



                  });



              }



              $("#formEs input,#formEs select,#formEs button,#formEs").prop("disabled", false);

              $(".cierra_modal_es").prop("disabled", false);

              $(".btn_ingreso_rop").prop("disabled", false);

            

            }else if(data.res == "sess"){

              window.location="../";

            }



            $(".btn_ingreso_rop").prop("disabled",false); 

            $(".cierra_modal_es").prop("disabled",false); 

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

                $('#modal_es').modal("toggle");

            }

        },timeout:35000

      }); 

    });



    $(document).off('click', '.btn_eliminar_rop').on('click', '.btn_eliminar_rop',function(event) {

        hash=$(this).data("hash");

        if(confirm("¿Esta seguro que desea eliminar este registro?")){

            $.post('eliminaEs'+"?"+$.now(),{"hash": hash}, function(data) {

              if(data.res=="ok"){

                $.notify(data.msg, {

                  className:'success',

                  globalPosition: 'top right'

                });

               tabla_es.ajax.reload();

              }else{

                $.notify(data.msg, {

                  className:'danger',

                  globalPosition: 'top right'

                });

              }

            },"json");

          }

    });



    $(document).off('change', '#estado_f, #desde_f, #hasta_f, #responsable_f').on('change', '#estado_f, #desde_f, #hasta_f, #responsable_f', function(event) {

      tabla_es.ajax.reload();

    });





    $(document).off('change', '#tipo').on('change', '#tipo',function(event) {

       

      $.getJSON(base + "listaRequerimientos" , {tipo: $(this).val()},function(data) {

          response = data;

         

      }).done(function() {

          if(response!=""){

            $("#requerimiento").empty().select2('destroy');



            var init = $('<option>', {

                value: '',

                text: 'Seleccione Requerimiento | Todos'

            });



            $('#requerimiento').append(init);



            $("#requerimiento").select2({

              placeholder: 'Seleccione requerimiento',

              data: response,

              width: '100%',

              allowClear:true,

            });

          }else{

            /* $("#requerimiento").empty().select2('destroy'); */

            $("#requerimiento").empty()

            $('#requerimiento').val("").trigger('change');

          }

      });



    }); 



    $.getJSON(base + "listaRequerimientos" , function(data) {

	      response = data;

		}).done(function() {

		    $("#requerimiento").select2({

          placeholder: 'Seleccione requerimiento',

		       data: response,

		       width: '100%',

	         allowClear:true,

		    });

	  });



    $.getJSON(base + "listaPersonas" , function(data) {

	      response = data;

		}).done(function() {

		    $("#usuario_asignado").select2({

          placeholder: 'Seleccione persona',

		       data: response,

		       width: '100%',

	         allowClear:true,

		    });



        $("#tecnico").select2({

           placeholder: 'Seleccione técnico',

		       data: response,

		       width: '100%',

	         allowClear:true,

		    });



        $("#responsable_f").select2({

          placeholder: 'Seleccione tecnico',

		       data: response,

		       width: '100%',

	         allowClear:true,

		    });



        $('#usuario_asignado').val("<?php echo $this->session->userdata('id'); ?>").trigger('change');

        $('#tecnico').val("<?php echo $this->session->userdata('id'); ?>").trigger('change');

        if("<?php echo $this->session->userdata('id_perfil'); ?>" > 3){

          $('#responsable_f').val("<?php echo $this->session->userdata('id'); ?>").prop('disabled', true).trigger('change');

        }



	  });



    $(document).off('click', '.btn_excel_rop').on('click', '.btn_excel_rop',function(event) {

      event.preventDefault();

      const desde = $("#desde_f").val()

      const hasta = $("#hasta_f").val()

      let estado =$("#estado_f").val()

      let responsable =$("#responsable_f").val()



      if(desde==""){

        $.notify("Debe seleccionar una fecha de inicio.", {

            className:'error',

            globalPosition: 'top right'

        });  

       return false;

      }



      if(hasta==""){

        $.notify("Debe seleccionar una fecha de término.", {

            className:'error',

            globalPosition: 'top right'

        });  

       return false;

      }



      const estado_f = estado === "" ? "-" : estado;

      const responsable_f  = responsable === "" ? "-" : responsable;



      window.location="excel_es/"+desde+"/"+hasta+"/"+estado_f+"/"+responsable_f;

    });



    

    

  });

</script>

  

<!--FILTROS-->


  <div class="form-row">

	  <div class="col-6  col-lg-1"> 

	      <div class="form-group">

	         <button type="button" class="btn-block btn btn-sm btn-primary btn_nuevo_rop btn_xr3">

	         <i class="fa fa-plus-circle"></i>  Nuevo 

	         </button>

	      </div>

	    </div>



      

      <div class="col-6 col-lg-1">  

        <div class="form-group">

          <select id="estado_f" name="estado_f" class="custom-select custom-select-sm">

            <option value="" selected>Estado | Todos</option>

            <option value="0">Pendiente</option>

            <option value="1">Asignado</option>

            <option value="2">Finalizado</option>

            <option value="3">Cancelado</option>

          </select>

        </div>

      </div>



      <div class="col-12 col-lg-3">

        <div class="form-group">

          <div class="input-group">

            <div class="input-group-prepend">

              <span class="input-group-text" id=""><i class="fa fa-calendar-alt"></i> <span style="font-size:12px;margin-left:5px;"> Fecha ingreso<span></span> 

            </div>

            <input type="date" placeholder="Desde" class="fecha_normal form-control form-control-sm"  name="desde_f" id="desde_f">

            <input type="date" placeholder="Hasta" class="fecha_normal form-control form-control-sm"  name="hasta_f" id="hasta_f">

          </div>

        </div>

      </div>





      <div class="col-6 col-lg-2">  

        <div class="form-group">

          <select id="responsable_f" name="responsable_f" style="width:100%!important;">

              <option value="">Seleccione Responsable | Todos</option>

          </select>

        </div>

      </div>





	    <div class="col-6 col-lg-3">  

	      <div class="form-group">

	      <input type="text" placeholder="Ingrese su busqueda..." id="buscador" class="buscador form-control form-control-sm">

	      </div>

	    </div>



      <!-- 

      <div class="col-6 col-lg-1">

        <div class="form-group">

          <button type="button" class="btn-block btn btn-sm btn-primary btn_excel_rop btn_xr3">

          <i class="fa fa-save"></i><span class="sr-only"></span> Excel

          </button>

        </div>

      </div>

      -->

 

	    <!-- <div class="col-2 col-lg-2">

	       <div class="form-group">

	          <button type="button" class="btn-block btn btn-sm btn-outline-primary btn-primary btn_filtro_rop btn_xr3">

	       <i class="fa fa-cog fa-1x"></i><span class="sr-only"></span> Filtrar

	       </button>

	       </div>

	    </div> -->

	</div>

  <div class="col-lg-12">
      <center>
      <span class="titulo_fecha_actualizacion_dias">
      <div class="alert alert-primary actualizacion_calidad" role="alert" style="padding: .15rem 1.25rem;margin-bottom: .1rem;"><b>Se permite el ingreso de solicitudes hasta las 17:15 hrs</b></div>
  </span></center></div>


<!--TABLA-->



  <div class="row">

    <div class="col-12">

      <table id="tabla_es" class="table table-striped table-hover table-bordered dt-responsive nowrap" style="width:100%!important">

          <thead>

            <tr>

              <th class="centered">Acciones</th> 

              <th class="centered">ID</th>    

              <th class="centered">Arch. Req. </th>    

              <th class="centered">Arch. Resp.</th>    

              <th class="centered">Estado </th>      

              <th class="centered">Técnico </th>    

              <th class="centered">Zona </th>    

              <th class="centered">Tipo</th> 

              <th class="centered">Ciudad </th> 

              <th class="centered">Observacion de requerimiento  </th> 



              <th class="centered">Cierre </th> 

              <th class="centered">Observación de soporte   </th> 

              <th class="centered">Última actualización</th> 

            </tr>

          </thead>

      </table>

    </div>

  </div>



<!--  NUEVO -->



    <div id="modal_es"  class="modal fade bd-example-modal-lg" data-backdrop="static"   aria-labelledby="myModalLabel" role="dialog">

	    <div class="modal-dialog modal_es">

	      <div class="modal-content">

	        <?php echo form_open_multipart("formEs",array("id"=>"formEs","class"=>"formEs"))?>

	           <input type="hidden" name="hash" id="hash_rop">

	           <button type="button" title="Cerrar Ventana" class="close" data-dismiss="modal" aria-hidden="true">X</button>

	           <fieldset class="form-ing-cont">

              <legend class="form-ing-border">Solicitud de requerimiento</legend>

                <div class="form-row">



                  <div class="col-lg-1">  

                    <div class="form-group">

                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">ID  </label>

                    <input type="text" placeholder="ID" id="id_rop"  readonly class="form-control form-control-sm">

                    </div>

                  </div>



                  <div class="col-lg-3">  

                    <div class="form-group">

                      <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Nombre</label>

                      <select id="tecnico" name="tecnico" style="width:100%!important;">

                          <option value="">Seleccione técnico</option>

                      </select>

                    </div>

                  </div>



                  <div class="col-lg-2">  

                    <div class="form-group">

                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Zona </label>

                    <select id="zona" name="zona" class="custom-select custom-select-sm">

                          <option value="" selected>Seleccione Zona</option>

                          <?php  

                          foreach($zonas as $t){

                            ?>

                                <option value="<?php echo $t["id"] ?>"><?php echo $t["area"] ?></option>



                            <?php

                          }

                          ?>

                    </select>

                    </div>

                  </div>



                  <div class="col-lg-3">  

                    <div class="form-group">

                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Ciudad </label>

                    <input placeholder="Ciudad"  type="text" name="ciudad"  id="ciudad" class="form-control form-control-sm" autocomplete="off" />

                    </div>

                  </div>



                  <div class="col-lg-2">  

                    <div class="form-group">

                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Tipo </label>

                    <select id="tipo" name="tipo" class="custom-select custom-select-sm">

                          <option value="" selected>Seleccione Tipo</option>

                          <?php  

                          foreach($tipos as $t){

                            ?>

                                <option value="<?php echo $t["id"] ?>"><?php echo $t["tipo"] ?></option>



                            <?php

                          }

                          ?>

                    </select>

                    </div>

                  </div>



                  <div class="col-lg-9">  

                    <div class="form-group">

                      <label for="">Observación</label>

                      <input type="text" placeholder="observacion" id="observacion"  name="observacion" class="form-control form-control-sm">

                      <!-- <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea> -->

                    </div>

                  </div> 



                  <div class="col-lg-6"> 

                    <div class="form-group"> 

                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Archivo adjunto</label>

                      <input type="file" id="adjunto_req1" name="adjunto_req1">

                    </div>

                  </div>



                </div>

              </fieldset>



              <div class="cont_edicion">

                <fieldset class="form-ing-cont">

                <legend class="form-ing-border">Respuesta de soporte</legend>

                <div class="form-row">





                  <div class="col-lg-2">  

                    <div class="form-group">

                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Cierre </label>

                    <select id="cierre" name="cierre" class="custom-select custom-select-sm">

                          <option value="" selected>Seleccione Tipo</option>

                          <?php  

                          foreach($cierres as $t){

                            ?>

                                <option value="<?php echo $t["id"] ?>"><?php echo $t["cierre"] ?></option>



                            <?php

                          }

                          ?>

                    </select>

                    </div>

                  </div>



                  <div class="col-lg-12">  

                    <div class="form-group">

                      <label for="">Observación</label>

                      <input type="text" placeholder="observacion" id="observacion_respuesta"  name="observacion_respuesta" class="form-control form-control-sm">

                    </div>

                  </div>



                  <div class="col-lg-3"> 

                    <div class="form-group"> 

                    <label for="colFormLabelSm" class="col-sm-12 col-form-label col-form-label-sm">Archivo respuesta</label>

                      <input type="file" id="adjunto_respuesta" name="adjunto_respuesta">

                    </div>

                  </div>



                  </div>



                </div>



               </fieldset>



	            <br>



              <div class="col-lg-12">

                <div class="form-row justify-content-center">

                      

                  <div class="col-lg-2 requiere_validar_cont cont_edicion">

                    <div class="form-group">

                      <div class="form-check">

                        <input type="checkbox" name="checkvalidar" class="form-check-input" id="checkvalidar">

                        <label class="form-check-label" for="checkvalidar">Validar</label>

                      </div>

                    </div>

                  </div>



                  <div class="col-lg-2 finalizado_cont cont_edicion">

                    <div class="form-group">

                      <div class="form-check">

                        <input type="checkbox" name="checkfin" class="form-check-input" id="checkfin">

                        <label class="form-check-label" for="checkfin">Finalizar </label>

                      </div>

                    </div>

                  </div>



 

                  <div class="col-6 col-lg-2 guardar_cont">

                    <div class="form-group">

                      <button type="submit" class="btn-block btn btn-sm btn-primary btn_ingreso_rop">

                        <i class="fa fa-save"></i> Guardar

                      </button>

                    </div>

                  </div>



                  <div class="col-6 col-lg-2">

                    <button class="btn-block btn btn-sm btn-dark cierra_modal_es" data-dismiss="modal" aria-hidden="true">

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

