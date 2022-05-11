<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(
           'formOTS' => array(
               array(
                     'field'   => 'tecnico',
                     'label'   => 'Técnico',
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

            'formChecklistFTTH' => array(
               array(
                     'field'   => 'tecnico',
                     'label'   => 'Técnico',
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
                   'field'   => 'perfil',
                   'label'   => 'Perfil',
                   'rules'   => 'trim|required'
                  ),

               array(
                   'field'   => 'rut',
                   'label'   => 'Rut',
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
                     'label'   => 'Áreas',
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

            
               
 );

