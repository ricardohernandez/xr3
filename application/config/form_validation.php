<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
           'formOTS' => array(
               array(
                     'field'   => 'tecnico',
                     'label'   => 'Técnico',
                     'rules'   => 'trim|required'
                    )
            ),

            'formAst' => array(
               array(
                     'field'   => 'tecnico',
                     'label'   => 'Técnico',
                     'rules'   => 'trim|required'
                    ),
               
            ),
            'formMantActividades' => array(
               array(
                     'field'   => 'aplica',
                     'label'   => 'Aplica',
                     'rules'   => 'trim|required'
                    )
            ),
            'formChecklistHFC' => array(
               array(
                     'field'   => 'tecnico',
                     'label'   => 'Técnico',
                     'rules'   => 'trim|required'
                    )
            ),

            'formMantChecklistHFC' => array(
               array(
                     'field'   => 'tipo',
                     'label'   => 'Tipo',
                     'rules'   => 'trim|required'
                    ),
                array(
                     'field'   => 'descripcion',
                     'label'   => 'Descripción',
                     'rules'   => 'trim|required'
                    )
            ),

            'formMantChecklistFTTH' => array(
               array(
                     'field'   => 'tipo',
                     'label'   => 'Tipo',
                     'rules'   => 'trim|required'
                    ),
                array(
                     'field'   => 'descripcion',
                     'label'   => 'Descripción',
                     'rules'   => 'trim|required'
                    )
            ),

           'formFH' => array(
               array(
                     'field'   => 'solucion_fecha',
                     'label'   => 'Fecha solución',
                     'rules'   => 'trim|required'
               )
            ),

            'formFHFC' => array(
               array(
                     'field'   => 'solucion_fecha',
                     'label'   => 'Fecha solución',
                     'rules'   => 'trim|required'
                    )
            ),
           

            'formChecklistFTTH' => array(
               array(
                     'field'   => 'tecnico',
                     'label'   => 'Técnico',
                     'rules'   => 'trim|required'
                    )
            ),
           
            'formFFTTH' => array(
               array(
                     'field'   => 'solucion_fecha',
                     'label'   => 'Fecha solución',
                     'rules'   => 'trim|required'
                    )
            ),
            
          	'formHerramientas' => array(
               array(
                     'field'   => 'tipo',
                     'label'   => 'Tipo',
                     'rules'   => 'trim|required'
                    ),
                array(
                     'field'   => 'descripcion',
                     'label'   => 'Descripción',
                     'rules'   => 'trim|required'
                    )
            ),

            'formUsuario' => array(
               array(
                     'field'   => 'nombres',
                     'label'   => 'Nombres',
                     'rules'   => 'trim|required'
                    ),
               array(
                  'field'   => 'apellidos',
                  'label'   => 'Apellidos',
                  'rules'   => 'trim|required'
                  ),
               array(
                   'field'   => 'perfil',
                   'label'   => 'Perfil',
                   'rules'   => 'trim|required'
                  ),

                  array(
                        'field'   => 'rut',
                        'label'   => 'Rut',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'jefe',
                        'label'   => 'Jefe',
                        'rules'   => 'trim|required'
                        ),
                  array(
                        'field'   => 'nacionalidad',
                        'label'   => 'Nacionalidad',
                        'rules'   => 'trim|required'
                        ),
                  array(
                        'field'   => 'area',
                        'label'   => 'Zona',
                        'rules'   => 'trim|required'
                        ),
                  array(
                        'field'   => 'fecha_ingreso',
                        'label'   => 'Fecha ingreso',
                        'rules'   => 'trim|required'
                        ),
                  array(
                        'field'   => 'cargo',
                        'label'   => 'Cargo',
                        'rules'   => 'trim|required'
                        ),
                  array(
                        'field'   => 'plaza',
                        'label'   => 'Plaza',
                        'rules'   => 'trim|required'
                        ),
                  array(
                        'field'   => 'domicilio',
                        'label'   => 'Domicilio',
                        'rules'   => 'trim|required'
                        ),
      

                  
            ),

            'formCargos' => array(
               array(
                     'field'   => 'cargo',
                     'label'   => 'Cargo',
                     'rules'   => 'trim|required'
                    )
            ),

            'formProyectos' => array(
               array(
                     'field'   => 'proyecto',
                     'label'   => 'Proyecto',
                     'rules'   => 'trim|required'
                    )
            ),

            'formAreas' => array(
               array(
                     'field'   => 'area',
                     'label'   => 'Zona',
                     'rules'   => 'trim|required'
                    )
            ),

            'formPlazas' => array(
                  array(
                        'field'   => 'plaza',
                        'label'   => 'Plaza',
                        'rules'   => 'trim|required'
                       )
            ),

            'formPerfiles' => array(
               array(
                     'field'   => 'perfil',
                     'label'   => 'Perfil',
                     'rules'   => 'trim|required'
                    )
            ),

            'formJefes' => array(
               array(
                     'field'   => 'jefe',
                     'label'   => 'Jefe',
                     'rules'   => 'trim|required'
                    )
            ),


            'formMetasIgt' => array(
               array(
                     'field'   => 'nivel',
                     'label'   => 'Nivel',
                     'rules'   => 'trim|required'
                    ),

               array(
                     'field'   => 'indicador',
                     'label'   => 'Indicador',
                     'rules'   => 'trim|required'
                    ),
               array(
                     'field'   => 'meta_actual',
                     'label'   => 'Met actual',
                     'rules'   => 'trim|required'
                    ),
               array(
                     'field'   => 'meta_anterior',
                     'label'   => 'Meta anterior',
                     'rules'   => 'trim|required'
                    )
            ),
            

            'nuevaNoticiaAdmin' => array(
               array(
                     'field'   => 'hash',
                     'label'   => 'Error',
                     'rules'   => 'trim'
                    ),
               array(
                     'field'   => 'titulo',
                     'label'   => 'Título ',
                     'rules'   => 'trim|required'
                    ),
               array(
                     'field'   => 'categoria',
                     'label'   => 'Categoría',
                     'rules'   => 'trim'
                    ),
             
               array(
                     'field'   => 'descripcion',
                     'label'   => 'Descripción',
                     'rules'   => 'trim|required'
                    )
            ),

            'nuevaInformacion' => array(
               array(
                     'field'   => 'hash',
                     'label'   => 'Error',
                     'rules'   => 'trim'
                    ),
               array(
                     'field'   => 'titulo',
                     'label'   => 'Título ',
                     'rules'   => 'trim|required'
                    )
            ),

           
            'formTicket' => array(
               array(
                     'field'   => 'titulo',
                     'label'   => 'Título',
                     'rules'   => 'trim|required'
                    ),
               array(
                     'field'   => 'descripcion',
                     'label'   => 'Descripción',
                     'rules'   => 'trim|required'
                    ),
                array(
                     'field'   => 'tipo',
                     'label'   => 'Tipo',
                     'rules'   => 'trim|required'
                    )
            ),

            'formTurnos' => array(
               array(
                     'field'   => 'trabajador',
                     'label'   => 'Trabajador',
                     'rules'   => 'trim|required'
                    ),
               array(
                     'field'   => 'fecha',
                     'label'   => 'Fecha',
                     'rules'   => 'trim|required'
                    ),
                array(
                     'field'   => 'turno',
                     'label'   => 'Turno',
                     'rules'   => 'trim|required'
                    )
            ),

            'formIngresoLicencia' => array(
               array(
                     'field'   => 'usuarios',
                     'label'   => 'Usuario',
                     'rules'   => 'trim|required'
                    ),
               array(
                     'field'   => 'tipo_licencia',
                     'label'   => 'Tipo licencia',
                     'rules'   => 'trim|required'
                    )
            ),

            'formIngresoVacaciones' => array(
               array(
                     'field'   => 'usuarios',
                     'label'   => 'Usuario',
                     'rules'   => 'trim|required'
                    )
            ),

            'formMantenedorTurnos' => array(
               array(
                     'field'   => 'codigo',
                     'label'   => 'Código',
                     'rules'   => 'trim|required'
                    ),
               array(
                     'field'   => 'rango_horario',
                     'label'   => 'Rango horario',
                     'rules'   => 'trim|required'
                    ),
               array(
                     'field'   => 'estado',
                     'label'   => 'Estado',
                     'rules'   => 'trim|required'
                    ),
               array(
                     'field'   => 'suma',
                     'label'   => 'Suma',
                     'rules'   => 'trim|required'
                    ),
            ),

            'formIngresoCapacitacion' => array(
               array(
                     'field'   => 'nombre_archivo',
                     'label'   => 'Nombre de archivo',
                     'rules'   => 'trim|required'
                    )
            ),

            'formIngresoReportes' => array(
               array(
                     'field'   => 'nombre_archivo',
                     'label'   => 'Nombre de archivo',
                     'rules'   => 'trim|required'
                    )
            ),

            'formIngresoPrevencion' => array(
               array(
                     'field'   => 'nombre_archivo',
                     'label'   => 'Nombre de archivo',
                     'rules'   => 'trim|required'
                    )
            ),


            'formIngresoDatas' => array(
               array(
                     'field'   => 'nombre_archivo',
                     'label'   => 'Nombre de archivo',
                     'rules'   => 'trim|required'
                    )
            ),


            'formResponsablesFallosHerramientas' => array(
               array(
                     'field'   => 'item_fallos',
                     'label'   => 'Item descripción',
                     'rules'   => 'trim|required'
                    ),

               array(
                     'field'   => 'proyecto_fallos',
                     'label'   => 'Proyecto',
                     'rules'   => 'trim|required'
                    ),

               array(
                     'field'   => 'responsable_fallos',
                     'label'   => 'Responsable',
                     'rules'   => 'trim|required'
                    ),
               array(
                     'field'   => 'plazo_fallos',
                     'label'   => 'Plazo',
                     'rules'   => 'trim|required'
                    ),
                  ),

                  
            'formLiquidaciones' => array(
                  array(
                        'field'   => 'trabajadores',
                        'label'   => 'Trabajador',
                        'rules'   => 'trim|required'
                       ),
   
                  array(
                        'field'   => 'periodo',
                        'label'   => 'Periodo',
                        'rules'   => 'trim|required'
                       ),
   
                  ),
                  
            'formRop' => array(
                  array(
                        'field'   => 'tipo',
                        'label'   => 'Tipo',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'requerimiento',
                        'label'   => 'Requerimiento',
                        'rules'   => 'trim|required'
                  ), 
                  array(
                        'field'   => 'descripcion',
                        'label'   => 'Descripción',
                        'rules'   => 'trim|required'
                  ), 
                  
            ),

            'formMantenedorReq' => array(
                  array(
                        'field'   => 'tipo',
                        'label'   => 'Tipo',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'estado',
                        'label'   => 'Estado',
                        'rules'   => 'trim|required'
                  ), 
                  array(
                        'field'   => 'requerimiento',
                        'label'   => 'Requerimiento',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'requiere_validacion',
                        'label'   => 'Requiere validación',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'horas_estimadas',
                        'label'   => 'Horas estimadas',
                        'rules'   => 'trim|required'
                  ),
                  /*
                  array(
                        'field'   => 'responsable1',
                        'label'   => 'Responsable 1',
                        'rules'   => 'trim|required'
                  ),
                  */
            ),

            'formIngresoPrevencionModulos' => array(
                  array(
                        'field'   => 'titulo',
                        'label'   => 'Título',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'descripcion',
                        'label'   => 'Descripción',
                        'rules'   => 'trim|required'
                  )
             
            ),

             'formCondiciones' => array(
                  array(
                        'field'   => 'responsable_inspeccion',
                        'label'   => 'Responsable de inspeccion',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'cargo',
                        'label'   => 'Cargo del responsable ',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'fecha_inspeccion',
                        'label'   => 'Fecha de inspección',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'fecha_generacion',
                        'label'   => 'Fecha de generación de reporte',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'tecnico_auditado',
                        'label'   => 'Nombre de técnico auditado',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'rut_tecnico_auditado',
                        'label'   => 'Rut de técnico auditado',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'zona',
                        'label'   => 'Zona',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'plaza',
                        'label'   => 'Plaza',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'proyecto',
                        'label'   => 'Proyecto',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'herramientas[]',
                        'label'   => 'EPPS',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'riesgos[]',
                        'label'   => 'Riesgos del entorno',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'acciones[]',
                        'label'   => 'Acciones recomendadas',
                        'rules'   => 'trim|required'
                  )
             
                  ),
             'formInvestigaciones' => array(
                  array(
                        'field'   => 'fecha',
                        'label'   => 'Fecha',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'hora',
                        'label'   => 'Hora',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'tipo',
                        'label'   => 'Tipo',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'lugar',
                        'label'   => 'Lugar',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'direccion',
                        'label'   => 'Dirección',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'comuna',
                        'label'   => 'Comuna',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'nombre_informante',
                        'label'   => 'Nombre del informante',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'cargo_informante',
                        'label'   => 'Cargo del informante',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'descripcion',
                        'label'   => 'Descripción del incidente',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'nombre_afectado',
                        'label'   => 'Nombre del afectado',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'cargo_afectado',
                        'label'   => 'Cargo del afectado',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'rut_afectado',
                        'label'   => 'RUT del afectado',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'horas_trabajadas',
                        'label'   => 'Horas trabajadas',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'gravedad_lesion',
                        'label'   => 'Gravedad de lesión',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'tipo_lesion',
                        'label'   => 'Tipo de lesión',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'nombre_testigo_1',
                        'label'   => 'Nombre del testigo 1',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'relacion_testigo_1',
                        'label'   => 'Relación del testigo 1',
                        'rules'   => 'trim|required'
                  ),
                  ),
             'formReuniones' => array(
                  array(
                        'field'   => 'fecha',
                        'label'   => 'Fecha',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'area',
                        'label'   => 'Area',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'inicio',
                        'label'   => 'Hora de inicio',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'termino',
                        'label'   => 'Hora de termino',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'nombre_asistentes[]',
                        'label'   => 'Nombre de asistentes',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'cargos[]',
                        'label'   => 'Cargo de asistentes',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'fecha_generacion',
                        'label'   => 'Fecha de registro',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'tema_1',
                        'label'   => 'Tema 1',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'responsable_inspeccion',
                        'label'   => 'Prevencionista responsable',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'cargo_prevencionista',
                        'label'   => 'Cargo del prevencionista',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'fecha_generacion',
                        'label'   => 'Fecha de registro',
                        'rules'   => 'trim|required'
                  ),
             ) , 
             'formRcdc' => array(
                  array(
                        'field'   => 'fecha',
                        'label'   => 'Fecha',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'id_tramo',
                        'label'   => 'Tramo',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'zona',
                        'label'   => 'Zona',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'comuna',
                        'label'   => 'Comuna',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'id_tecnico',
                        'label'   => 'Tecnico',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'id_coordinador',
                        'label'   => 'Coordinador',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'codigo',
                        'label'   => 'Codigo OT/IBS',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'id_tipo',
                        'label'   => 'Tipo',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'estado',
                        'label'   => 'Estado',
                        'rules'   => 'trim|required'
                  ),
                  array(
                        'field'   => 'costo',
                        'label'   => 'Costo',
                        'rules'   => 'trim|required'
                  ),
             )       
 );

