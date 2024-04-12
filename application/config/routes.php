<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');





/*******INICIO *******/

	

	$route['default_controller'] = 'inicio';

	$route['404_override'] = "";

	$route['nojs'] = "inicio/nojs";

	$route['validaLogin'] = "inicio/validaLogin";

	$route['login'] = "inicio/login";

	$route['unlogin'] = "inicio/unlogin";

	$route['inicio'] = "inicio/inicio";

	$route['cargaInicio'] = "inicio/cargaInicio";

	$route['cargaInformaciones'] = "inicio/cargaInformaciones";

	$route['cargaVistaNoticias'] = "inicio/cargaVistaNoticias";

	$route['cargaVistaNoticia'] = "inicio/cargaVistaNoticia";

	$route['cargaCategorias'] = "inicio/cargaCategorias";

	$route['cargaIngresos'] = "inicio/cargaIngresos";

	$route['infoUsuario'] = "inicio/infoUsuario";

	$route['listaUsuariosInicio'] = "inicio/listaUsuarios";

	$route['listaJefaturaInicio'] = "inicio/listaJefatura";

	$route['cambiarPass'] = "inicio/cambiarPass";

	$route['verComo'] = "inicio/verComo";

	$route['recuperarPass'] = "inicio/recuperarPass";



/*************CRON******************/

	

	$route['saludoCumpleanios'] = "inicio/saludoCumpleanios";

	$route['admin_xr3'] = "admin";

	$route['cargaVistaNoticiasAdmin'] = "admin/cargaVistaNoticiasAdmin";

	$route['listaNoticiasAdmin'] = "admin/listaNoticiasAdmin";

	$route['nuevaNoticiaAdmin'] = "admin/nuevaNoticiaAdmin";

	$route['getDataNoticia'] = "admin/getDataNoticia";

	$route['eliminaNoticia'] = "admin/eliminaNoticia";

	$route['eliminaImagen'] = "admin/eliminaImagen";

	$route['cargaVistaInformaciones'] = "admin/cargaVistaInformaciones";

	$route['listaInformaciones'] = "admin/listaInformaciones";

	$route['nuevaInformacion'] = "admin/nuevaInformacion";

	$route['getDataInformacion'] = "admin/getDataInformacion";

	$route['eliminaInformacion'] = "admin/eliminaInformacion";



/*******IGT*******/



	$route['igt'] = "back_end/igt/index";

	$route['getIgtInicio'] = "back_end/igt/getIgtInicio";

	$route['listaDetalleIgt'] = "back_end/igt/listaDetalleIgt";

	$route['excel_detalle_prod_igt/(:any)/(:any)/(:any)'] = "back_end/igt/excel_detalle_prod_igt/$1/$2/$3";

	$route['listaDetalleOtsDrive'] = "back_end/igt/listaDetalleOtsDrive";

	$route['excel_detalle_ots_drive/(:any)/(:any)/(:any)'] = "back_end/igt/excel_detalle_ots_drive/$1/$2/$3";

	$route['listaDtv'] = "back_end/igt/listaDtv";

	$route['excel_dtv/(:any)/(:any)/(:any)'] = "back_end/igt/excel_dtv/$1/$2/$3";

	$route['dataIgt'] = "back_end/igt/dataIgt";

	$route['graficoProductividad'] = "back_end/igt/graficoProductividad";

	$route['listaTrabajadoresIGT'] = "back_end/igt/listaTrabajadoresIGT";

	$route['listaCalidadIGT'] = "back_end/igt/listaCalidadIGT";

	$route['getProyectoTecnicoRut'] = "back_end/igt/getProyectoTecnicoRut";

	$route['dataGraficosIgt'] = "back_end/igt/dataGraficosIgt";

	$route['formCargaMasivaIgt'] = "back_end/igt/formCargaMasivaIgt";


/*******FLOTA*******/

	$route['flota'] = "back_end/flota/index";

	$route['getFlotaInicio'] = "back_end/flota/getFlotaInicio";
	$route['getActualizacionCombustible'] = "back_end/flota/getActualizacionCombustible";
	$route['listaCombustible'] = "back_end/flota/listaCombustible";
	$route['listaCarga'] = "back_end/flota/listaCarga";
	$route['listaMax'] = "back_end/flota/listaMax";
	$route['GastoZona'] = "back_end/flota/GastoZona";
	$route['GastoRegion'] = "back_end/flota/GastoRegion";
	$route['GastoSemana'] = "back_end/flota/GastoSemana";
	$route['GastoCombustibleRegion'] = "back_end/flota/GastoCombustibleRegion";
	$route['GastoCombustibleZona'] = "back_end/flota/GastoCombustibleZona";
	$route['GastoCombustibleSemana'] = "back_end/flota/GastoCombustibleSemana";

	/***** GPS *****/

	$route['getGPSInicio'] = "back_end/flota/getGPSInicio";
	$route['getActualizacionGPS'] = "back_end/flota/getActualizacionGPS";
	$route['listaGPS'] = "back_end/flota/listaGPS";
	$route['listaDetalleFlota'] = "back_end/flota/listaDetalleFlota";
	$route['listaTotal'] = "back_end/flota/listaTotal";

	$route['getGPSInicioMuevo'] = "back_end/flota/getGPSInicioMuevo";
	$route['getActualizacionGPSMuevo'] = "back_end/flota/getActualizacionGPSMuevo";
	$route['listaGPSMuevo'] = "back_end/flota/listaGPSMuevo";
	$route['listaMontoMuevo'] = "back_end/flota/listaMontoMuevo";
	$route['listaOdometroMuevo'] = "back_end/flota/listaOdometroMuevo";
	$route['GastoRegionMuevo'] = "back_end/flota/GastoRegionMuevo";
	$route['GastoSemanaMuevo'] = "back_end/flota/GastoSemanaMuevo";

	$route['formCargaMasivaFlota'] = "back_end/flota/formCargaMasivaFlota";

	/*******FLOTA - DOCUMENTACION*******/

	$route['flota/documentacion'] = "back_end/flota/docindex";
	$route['getDocumentoFlotaInicio'] = "back_end/flota/getDocumentoFlotaInicio";

	$route['listaDocumentoFlota'] = "back_end/flota/listaDocumentoFlota"; 
	$route['getDocumentoFlota'] = "back_end/flota/getDocumentoFlota";
	$route['formDocumentoFlota'] = "back_end/flota/formDocumentoFlota";
	$route['eliminaDocumentoFlota'] = "back_end/flota/eliminaDocumentoFlota";

		/** MANTENEDOR **/

		$route['getMantenedorFlotaInicio'] = "back_end/flota/getMantenedorFlotaInicio";

		$route['listaMantenedorFlota'] = "back_end/flota/listaMantenedorFlota"; 
		$route['getMantenedorFlota'] = "back_end/flota/getMantenedorFlota";
		$route['formMantenedorFlota'] = "back_end/flota/formMantenedorFlota";
		$route['eliminaMantenedorFlota'] = "back_end/flota/eliminaMantenedorFlota";

	

/*******DOCUMENTACION*******/



	$route['documentacion/capacitacion'] = "back_end/documentacion/indexCapacitacion";

	$route['vistaCapacitacion'] = "back_end/documentacion/vistaCapacitacion";

	$route['getCapacitacionList'] = "back_end/documentacion/getCapacitacionList";

	$route['getDataRegistroCapacitacion'] = "back_end/documentacion/getDataRegistroCapacitacion";

	$route['formIngresoCapacitacion'] = "back_end/documentacion/formIngresoCapacitacion";

	$route['eliminaCapacitacion'] = "back_end/documentacion/eliminaCapacitacion";

	$route['documentacion/reportes'] = "back_end/documentacion/indexReportes";

	$route['vistaReportes'] = "back_end/documentacion/vistaReportes";

	$route['getReportesList'] = "back_end/documentacion/getReportesList";

	$route['getDataRegistroReportes'] = "back_end/documentacion/getDataRegistroReportes";

	$route['formIngresoReportes'] = "back_end/documentacion/formIngresoReportes";

	$route['eliminaReportes'] = "back_end/documentacion/eliminaReportes";

	$route['documentacion/prevencion_riesgos'] = "back_end/documentacion/indexPrevencion";

	$route['vistaPrevencion'] = "back_end/documentacion/vistaPrevencion";

	$route['getPrevencionList'] = "back_end/documentacion/getPrevencionList";

	$route['getDataRegistroPrevencion'] = "back_end/documentacion/getDataRegistroPrevencion";

	$route['formIngresoPrevencion'] = "back_end/documentacion/formIngresoPrevencion";

	$route['eliminaPrevencion'] = "back_end/documentacion/eliminaPrevencion";

	$route['documentacion/datas_mandante'] = "back_end/documentacion/indexDatas";



	$route['vistaDatas'] = "back_end/documentacion/vistaDatas";

	$route['getDatasList'] = "back_end/documentacion/getDatasList";

	$route['getDataRegistroDatas'] = "back_end/documentacion/getDataRegistroDatas";

	$route['formIngresoDatas'] = "back_end/documentacion/formIngresoDatas";

	$route['eliminaDatas'] = "back_end/documentacion/eliminaDatas";



	$route['prevencion_riesgos/normativas'] = "back_end/Prevencion_modulos/indexPrevencionModulos";

	$route['prevencion_riesgos/identificacion_riesgos'] = "back_end/Prevencion_modulos/indexPrevencionModulos";

	$route['prevencion_riesgos/medidas_proteccion'] = "back_end/Prevencion_modulos/indexPrevencionModulos";

	$route['prevencion_riesgos/seguridad_equipos_herramientas'] = "back_end/Prevencion_modulos/indexPrevencionModulos";

	$route['prevencion_riesgos/primeros_auxilios'] = "back_end/Prevencion_modulos/indexPrevencionModulos";

	$route['prevencion_riesgos/ergonomia_y_cuidado'] = "back_end/Prevencion_modulos/indexPrevencionModulos";

	$route['prevencion_riesgos/comunicacion_conciencia'] = "back_end/Prevencion_modulos/indexPrevencionModulos";



	$route['prevencion_riesgos'] = "back_end/Prevencion_modulos/indexPrevencionModulos"; //NUEVO



	$route['vistaPrevencionModulos'] = "back_end/Prevencion_modulos/vistaPrevencionModulos";

	

	$route['getListPrevencionModulos'] = "back_end/Prevencion_modulos/getList";

	$route['getDataPrevencionModulos'] = "back_end/Prevencion_modulos/getData";

	$route['formIngresoPrevencionModulos'] = "back_end/Prevencion_modulos/formIngreso";

	$route['eliminarPrevencionModulos'] = "back_end/Prevencion_modulos/eliminar";



	$route['dashboard/dashboard_operaciones'] = "back_end/Dashboard_operaciones/indexDashboardOP";

	$route['dashboard/produccion_calidad_xr3'] = "back_end/Dashboard_operaciones/productividadCalidadXr3";

	$route['dashboard/produccion_calidad_EPS'] = "back_end/Dashboard_operaciones/produccionCalidadEPS";

	$route['dashboard/dotacion'] = "back_end/Dashboard_operaciones/dotacion";

	$route['dashboard/analisis_calidad'] = "back_end/Dashboard_operaciones/analisisCalidad";

	$route['dashboard/prod_cal_claro'] = "back_end/Dashboard_operaciones/prodCalClaro";

	$route['dashboard/prod_x_comuna'] = "back_end/Dashboard_operaciones/prodXComuna";

	$route['dashboard/cumpl_factura'] = "back_end/Dashboard_operaciones/cumpl_factura";

	$route['dashboard/cargaDashboardProductividadXR3'] = "back_end/Dashboard_operaciones/cargaDashboardProductividadXR3";

	$route['graficosProductividadXR3'] = "back_end/Dashboard_operaciones/graficosProductividadXR3";

	$route['graficosProductividadEps'] = "back_end/Dashboard_operaciones/graficosProductividadEps";

	$route['graficoDotacion'] = "back_end/Dashboard_operaciones/graficoDotacion";

	$route['graficoAnalisisCalidad'] = "back_end/Dashboard_operaciones/graficoAnalisisCalidad";

	$route['graficoProdxEps'] = "back_end/Dashboard_operaciones/graficoProdxEps";

	$route['graficoXComuna'] = "back_end/Dashboard_operaciones/graficoXComuna";

	$route['graficoCumpFact'] = "back_end/Dashboard_operaciones/graficoCumpFact";

	$route['getCabecerasCumplimientoFacturacion'] = "back_end/Dashboard_operaciones/getCabecerasCumplimientoFacturacion";

 	

	$route['dashboard/capacitacion'] = "back_end/documentacion/indexCapacitacion";

	$route['getDataPrevencionModulos'] = "back_end/Prevencion_modulos/getData";

	$route['formIngresoPrevencionModulos'] = "back_end/Prevencion_modulos/formIngreso";

	$route['eliminarPrevencionModulos'] = "back_end/Prevencion_modulos/eliminar";



	/********** PREVENCION TERRENO ***********/

		$route['prevencion_riesgos/checklist_prevencion'] = "back_end/Prevencion_checklist/index";



		/****** EPPS Y CONDICIONES ENCONTRADAS *******/

			$route['getEPPSInicio'] = "back_end/Prevencion_checklist/getPrevencion_EPPSInicio";

			$route['getCondicionesList'] = "back_end/Prevencion_checklist/getCondicionesList";

			$route['formCondiciones'] = "back_end/Prevencion_checklist/formCondiciones";

			$route['eliminaCondicion'] = "back_end/Prevencion_checklist/eliminaCondicion";

			$route['getDataCondicion'] = "back_end/Prevencion_checklist/getDataCondicion";



		/****** INVESTIGACION ACCIDENTES *******/

			$route['getIAInicio'] = "back_end/Prevencion_checklist/getPrevencion_IAInicio";

			$route['getInvestigacionesList'] = "back_end/Prevencion_checklist/getInvestigacionesList";

			$route['formInvestigaciones'] = "back_end/Prevencion_checklist/formInvestigaciones";

			$route['eliminaInvestigacion'] = "back_end/Prevencion_checklist/eliminaInvestigacion";

			$route['getDataInvestigacion'] = "back_end/Prevencion_checklist/getDataInvestigacion";



		/****** REUNIONES DE EQUIPO *******/

			$route['getREInicio'] = "back_end/Prevencion_checklist/getPrevencion_REInicio";

			$route['getReunionesList'] = "back_end/Prevencion_checklist/getReunionesList";

			$route['formReuniones'] = "back_end/Prevencion_checklist/formReuniones";

			$route['eliminaReunion'] = "back_end/Prevencion_checklist/eliminaReunion";

			$route['getDataReunion'] = "back_end/Prevencion_checklist/getDataReunion";





/*******AST*******/



	$route['ast'] = "back_end/ast/index";

	$route['vistaAst'] = "back_end/ast/vistaAst";

	$route['listaAst'] = "back_end/ast/listaAst";

	$route['getDataAst'] = "back_end/ast/getDataAst";

	$route['formAst'] = "back_end/ast/formAst";

	$route['eliminaAst'] = "back_end/ast/eliminaAst";

	$route['excel_ast/(:any)/(:any)/(:any)'] = "back_end/ast/excel_ast/$1/$2/$3";

	$route['generaPdfAstURL'] = "back_end/ast/generaPdfAstURL";

	$route['formCargaMasivaAst'] = "back_end/ast/formCargaMasivaAst";

	$route['vistaMantActividades'] = "back_end/ast/vistaMantActividades";

	$route['listaMantActividades'] = "back_end/ast/listaMantActividades";

	$route['getDataMantActividades'] = "back_end/ast/getDataMantActividades";

	$route['formMantActividades'] = "back_end/ast/formMantActividades";

	$route['eliminaMantActividades'] = "back_end/ast/eliminaMantActividades";

	$route['getChecklistView'] = "back_end/ast/getChecklistView";

	$route['getUserChecklistView'] = "back_end/ast/getUserChecklistView";

	$route['llenarMant'] = "back_end/ast/llenarMant";



	/*******GRAFICOS*******/

	

	$route['vistaGraficosAst'] = "back_end/ast/vistaGraficosAst";

	$route['graficoAstTecnico'] = "back_end/ast/graficoAstTecnico";

	$route['graficoAstDetalleTecnico'] = "back_end/ast/graficoAstDetalleTecnico";

	$route['graficoTotalTecnicos'] = "back_end/ast/graficoTotalTecnicos";

	$route['graficoTotalItems'] = "back_end/ast/graficoTotalItems";

	$route['excel_items_ast/(:any)/(:any)/(:any)/(:any)'] = "back_end/ast/excel_items_ast/$1/$2/$3/$4";

	$route['excel_ast_totales/(:any)/(:any)/(:any)/(:any)/(:any)'] = "back_end/ast/excel_ast_totales/$1/$2/$3/$4/$5";

	

/*******CHECKLIST*******/



	$route['checklist_herramientas'] = "back_end/checklist/checklist/index";

	$route['vistaChecklist'] = "back_end/checklist/checklist/vistaChecklist";

	$route['listaOTS'] = "back_end/checklist/checklist/listaOTS";

	$route['getDataChecklistHerramientas'] = "back_end/checklist/checklist/getDataChecklistHerramientas";

	$route['formOTS'] = "back_end/checklist/checklist/formOTS";

	$route['eliminaOTS'] = "back_end/checklist/checklist/eliminaOTS";

	$route['datosAuditor'] = "back_end/checklist/checklist/datosAuditor";

	$route['datosTecnico'] = "back_end/checklist/checklist/datosTecnico";

	$route['formCargaMasiva'] = "back_end/checklist/checklist/formCargaMasiva";

	$route['excel_checklist/(:any)/(:any)'] = "back_end/checklist/checklist/excel_checklist/$1/$2";

	$route['vistaGraficos'] = "back_end/checklist/checklist/vistaGraficos";

	$route['dataEstadosChecklist'] = "back_end/checklist/checklist/dataEstadosChecklist";

	$route['dataTecnicos'] = "back_end/checklist/checklist/dataTecnicos";

	$route['eliminaImagenChecklist'] = "back_end/checklist/checklist/eliminaImagenChecklist";

	$route['vistaGraficosH'] = "back_end/checklist/checklist/vistaGraficosH";

	$route['graficoFallosH'] = "back_end/checklist/checklist/graficoFallosH";

	$route['listaTrabajadoresCH'] = "back_end/checklist/checklist/listaTrabajadoresCH";

	$route['generaPdfChecklistHerramientasURL'] = "back_end/checklist/checklist/generaPdfChecklistHerramientasURL";



	/*******GRAFICOS*******/

		

		$route['graficoAuditoriasDataCH'] = "back_end/checklist/checklist/graficoAuditoriasDataCH";

		$route['graficoAuditoriasDataCHQ'] = "back_end/checklist/checklist/graficoAuditoriasDataCHQ";

		$route['graficoAuditoriasDataCHTecnico'] = "back_end/checklist/checklist/graficoAuditoriasDataCHTecnico";

		$route['graficoAuditoriasDataCHTecnicoQ'] = "back_end/checklist/checklist/graficoAuditoriasDataCHTecnicoQ";

		$route['dataAuditoresChecklistCH'] = "back_end/checklist/checklist/dataAuditoresChecklistCH";

		$route['dataEstadosChecklistCH'] = "back_end/checklist/checklist/dataEstadosChecklistCH";

		$route['dataTecnicosChecklistCH'] = "back_end/checklist/checklist/dataTecnicosChecklistCH";



	/*******FALLOS*******/

	

		$route['vistaFH'] = "back_end/checklist/checklist/vistaFH";

		$route['listaFH'] = "back_end/checklist/checklist/listaFH";

		$route['getDataFH'] = "back_end/checklist/checklist/getDataFH";

		$route['formFH'] = "back_end/checklist/checklist/formFH";





/*******CHECKLIST HFC*******/



	$route['checklistHFC'] = "back_end/checklist/ChecklistHFC/index";

	$route['vistaChecklistHFC'] = "back_end/checklist/ChecklistHFC/vistaChecklistHFC";

	$route['listaChecklistHFC'] = "back_end/checklist/ChecklistHFC/listaChecklistHFC";

	$route['getDataChecklistHFC'] = "back_end/checklist/ChecklistHFC/getDataChecklistHFC";

	$route['formChecklistHFC'] = "back_end/checklist/ChecklistHFC/formChecklistHFC";

	$route['eliminaChecklistHFC'] = "back_end/checklist/ChecklistHFC/eliminaChecklistHFC";

	$route['datosAuditorChecklistHFC'] = "back_end/checklist/ChecklistHFC/datosAuditorChecklistHFC";

	$route['datosTecnicoChecklistHFC'] = "back_end/checklist/ChecklistHFC/datosTecnicoChecklistHFC";

	$route['formCargaMasivaChecklistHFC'] = "back_end/checklist/ChecklistHFC/formCargaMasivaChecklistHFC";

	$route['excel_checklistHFC/(:any)/(:any)'] = "back_end/checklist/ChecklistHFC/excel_checklistHFC/$1/$2";

	$route['vistaGraficosChecklistHFC'] = "back_end/checklist/ChecklistHFC/vistaGraficosChecklistHFC";

	$route['dataEstadosChecklistHFC'] = "back_end/checklist/ChecklistHFC/dataEstadosChecklistHFC";

	$route['dataTecnicosChecklistHFC'] = "back_end/checklist/ChecklistHFC/dataTecnicosChecklistHFC";

	$route['eliminaImagenChecklistHFC'] = "back_end/checklist/ChecklistHFC/eliminaImagenChecklistHFC";

	$route['vistaGraficosFallosHFC'] = "back_end/checklist/ChecklistHFC/vistaGraficosFallosHFC";

	$route['graficoFallosHFC'] = "back_end/checklist/ChecklistHFC/graficoFallosHFC";

	$route['listaTrabajadoresHFC'] = "back_end/checklist/ChecklistHFC/listaTrabajadoresHFC";

	$route['generaPdfChecklistHFCURL'] = "back_end/checklist/ChecklistHFC/generaPdfChecklistHFCURL";





	$route['graficoAuditoriasDataHFC'] = "back_end/checklist/ChecklistHFC/graficoAuditoriasDataHFC";

	$route['graficoAuditoriasDataHFCQ'] = "back_end/checklist/ChecklistHFC/graficoAuditoriasDataHFCQ";

	$route['graficoAuditoriasDataHFCTecnico'] = "back_end/checklist/ChecklistHFC/graficoAuditoriasDataHFCTecnico";

	$route['graficoAuditoriasDataHFCTecnicoQ'] = "back_end/checklist/ChecklistHFC/graficoAuditoriasDataHFCTecnicoQ";

	$route['dataAuditoresChecklistHFC'] = "back_end/checklist/ChecklistHFC/dataAuditoresChecklistHFC";



	

	

	$route['vistaFHFC'] = "back_end/checklist/ChecklistHFC/vistaFHFC";

	$route['listaFHFC'] = "back_end/checklist/ChecklistHFC/listaFHFC";

	$route['getDataFHFC'] = "back_end/checklist/ChecklistHFC/getDataFHFC";

	$route['formFHFC'] = "back_end/checklist/ChecklistHFC/formFHFC";

	

/*******CHECKLIST FTTH*******/



	$route['checklistFTTH'] = "back_end/checklist/ChecklistFTTH/index";

	$route['vistaChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/vistaChecklistFTTH";

	$route['listaChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/listaChecklistFTTH";

	$route['getDataChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/getDataChecklistFTTH";

	$route['formChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/formChecklistFTTH";

	$route['eliminaChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/eliminaChecklistFTTH";

	$route['datosAuditorChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/datosAuditorChecklistFTTH";

	$route['datosTecnicoChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/datosTecnicoChecklistFTTH";

	$route['formCargaMasivaChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/formCargaMasivaChecklistFTTH";

	$route['excel_checklistFTTH/(:any)/(:any)'] = "back_end/checklist/ChecklistFTTH/excel_checklistFTTH/$1/$2";

	$route['vistaGraficosChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/vistaGraficosChecklistFTTH";

	$route['eliminaImagenChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/eliminaImagenChecklistFTTH";

	$route['vistaGraficosFallosFTTH'] = "back_end/checklist/ChecklistFTTH/vistaGraficosFallosFTTH";

	$route['graficoFallosFTTH'] = "back_end/checklist/ChecklistFTTH/graficoFallosFTTH";

	$route['listaTrabajadoresFTTH'] = "back_end/checklist/ChecklistFTTH/listaTrabajadoresFTTH";

	$route['generaPdfChecklistFTTHURL'] = "back_end/checklist/ChecklistFTTH/generaPdfChecklistFTTHURL";



	$route['graficoAuditoriasDataFTTH'] = "back_end/checklist/ChecklistFTTH/graficoAuditoriasDataFTTH";

	$route['graficoAuditoriasDataFTTHQ'] = "back_end/checklist/ChecklistFTTH/graficoAuditoriasDataFTTHQ";

	$route['graficoAuditoriasDataFTTHTecnico'] = "back_end/checklist/ChecklistFTTH/graficoAuditoriasDataFTTHTecnico";

	$route['graficoAuditoriasDataFTTHTecnicoQ'] = "back_end/checklist/ChecklistFTTH/graficoAuditoriasDataFTTHTecnicoQ";

	$route['dataAuditoresChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/dataAuditoresChecklistFTTH";

	$route['dataEstadosChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/dataEstadosChecklistFTTH";

	$route['dataTecnicosChecklistFTTH'] = "back_end/checklist/ChecklistFTTH/dataTecnicosChecklistFTTH";



	$route['vistaFFTTH'] = "back_end/checklist/ChecklistFTTH/vistaFFTTH";

	$route['listaFFTTH'] = "back_end/checklist/ChecklistFTTH/listaFFTTH";

	$route['getDataFFTTH'] = "back_end/checklist/ChecklistFTTH/getDataFFTTH";

	$route['formFFTTH'] = "back_end/checklist/ChecklistFTTH/formFFTTH";



/*******CHECKLIST REPORTES*******/



	$route['checklist_reportes'] = "back_end/checklist/checklist_reportes/index";	

	$route['getChecklistReportesInicio'] = "back_end/checklist/checklist_reportes/getChecklistReportesInicio";

	$route['listaReporteChecklist'] = "back_end/checklist/checklist_reportes/listaReporteChecklist";



	$route['graficoReporteChecklist'] = "back_end/checklist/checklist_reportes/graficoReporteChecklist";

	$route['excel_reporte_checklist/(:any)/(:any)'] = "back_end/checklist/checklist_reportes/excel_reporte_checklist/$1/$2";





/*******PRODUCTIVIDAD*******/



	$route['productividad'] = "back_end/productividad/index";

	$route['vistaDetalle'] = "back_end/productividad/vistaDetalle";

	$route['listaDetalle'] = "back_end/productividad/listaDetalle";

	$route['getDataDetalle'] = "back_end/productividad/getDataDetalle";

	$route['excel_detalle/(:any)/(:any)/(:any)'] = "back_end/productividad/excel_detalle/$1/$2/$3";

	$route['formCargaMasivaDetalle'] = "back_end/productividad/formCargaMasivaDetalle";

	$route['actualizacionProductividad'] = "back_end/productividad/actualizacionProductividad";

	$route['vistaGraficosProd'] = "back_end/productividad/vistaGraficosProd";

	$route['dataGraficos'] = "back_end/productividad/dataGraficos";



	$route['listaResumenProductividad'] = "back_end/productividad/listaResumenProductividad";

	$route['graficosProductividad'] = "back_end/productividad/graficosProductividad";

 

	$route['listaTrabajadoresProd'] = "back_end/productividad/listaTrabajadoresProd";

	

	$route['vistaResumen'] = "back_end/productividad/vistaResumen";

	$route['getCabeceras'] = "back_end/productividad/getCabeceras";

	$route['listaResumen'] = "back_end/productividad/listaResumen";

	$route['listaResumen2'] = "back_end/productividad/listaResumen2";

	$route['cargaPlanillaProductividad'] = "back_end/productividad/cargaPlanillaProductividad";



/******************CALIDAD*************************/



	$route['calidad'] = "back_end/calidad/index";

	$route['vistaCalidad'] = "back_end/calidad/vistaCalidad";

	$route['listaCalidad'] = "back_end/calidad/listaCalidad";

	$route['getDataCalidad'] = "back_end/calidad/getDataCalidad";

	$route['excel_calidad/(:any)/(:any)/(:any)'] = "back_end/calidad/excel_calidad/$1/$2/$3";

	$route['formCargaMasivaCalidad'] = "back_end/calidad/formCargaMasivaCalidad";

	$route['actualizacionCalidad'] = "back_end/calidad/actualizacionCalidad";

	$route['vistaGraficosCalidad'] = "back_end/calidad/vistaGraficosCalidad";

	$route['dataGraficosCalidad'] = "back_end/calidad/dataGraficosCalidad";

	$route['getCabecerasCalidad'] = "back_end/calidad/getCabecerasCalidad";

	$route['listaResumenCalidad'] = "back_end/calidad/listaResumenCalidad";

	$route['graficoHFC'] = "back_end/calidad/graficoHFC";

	$route['graficoFTTH'] = "back_end/calidad/graficoFTTH";

	$route['listaTrabajadoresCalidad'] = "back_end/calidad/listaTrabajadoresCalidad";





/*******MATERIALES*******/



	$route['materiales'] = "back_end/materiales/index";

	$route['listaTrabajadoresMateriales'] = "back_end/materiales/listaTrabajadoresMateriales";

	$route['vistaMaterialesDetalle'] = "back_end/materiales/vistaMaterialesDetalle";

	$route['cargaPlanillaMateriales'] = "back_end/materiales/cargaPlanillaMateriales";

	$route['listaDetalleMateriales'] = "back_end/materiales/listaDetalleMateriales";

	$route['excel_detalle_materiales/(:any)/(:any)'] = "back_end/materiales/excel_detalle_materiales/$1/$2";



	$route['vistaMaterialesPorTecnico'] = "back_end/materiales/vistaMaterialesPorTecnico";

	$route['listaTecnico'] = "back_end/materiales/listaTecnico";

	$route['excel_tecnico/(:any)/(:any)'] = "back_end/materiales/excel_tecnico/$1/$2";



	$route['vistaMaterialesPorMaterial'] = "back_end/materiales/vistaMaterialesPorMaterial";

	$route['listaMaterial'] = "back_end/materiales/listaMaterial";

	$route['excel_material/(:any)/(:any)'] = "back_end/materiales/excel_material/$1/$2";



	$route['vistaSeriesPorTecnico'] = "back_end/materiales/vistaSeriesPorTecnico";

	$route['listaSeriesDevolucion'] = "back_end/materiales/listaSeriesDevolucion";

	$route['listaSeriesOperativos'] = "back_end/materiales/listaSeriesOperativos";

	$route['excel_series_devolucion/(:any)/(:any)'] = "back_end/materiales/excel_series_devolucion/$1/$2";

	$route['excel_series_operativos/(:any)/(:any)'] = "back_end/materiales/excel_series_operativos/$1/$2";

 

	$route['actualizacionMateriales'] = "back_end/materiales/actualizacionMateriales";



	

/******************TICKET*************************/



	$route['ticket'] = "back_end/ticket/index";

	$route['getTicketInicio'] = "back_end/ticket/getTicketInicio";

	$route['getTicketList'] = "back_end/ticket/getTicketList";

	$route['formTicket'] = "back_end/ticket/formTicket";

	$route['eliminaTicket'] = "back_end/ticket/eliminaTicket";

	$route['getDataTicket'] = "back_end/ticket/getDataTicket";



/******************SYR*************************/



	$route['syr'] = "back_end/rop/index";

	$route['getRopInicio'] = "back_end/rop/getRopInicio";

	$route['getRopList'] = "back_end/rop/getRopList";

	$route['formRop'] = "back_end/rop/formRop";

	$route['eliminaRop'] = "back_end/rop/eliminaRop";

	$route['getDataRop'] = "back_end/rop/getDataRop";

	$route['listaRequerimientosRop'] = "back_end/rop/listaRequerimientos";

	$route['listaPersonas'] = "back_end/rop/listaPersonas";

	$route['listaResponsables'] = "back_end/rop/listaResponsables";

	$route['solicitudesVencidas'] = "back_end/rop/solicitudesVencidas";

	$route['excel_rop/(:any)/(:any)/(:any)/(:any)'] = "back_end/rop/excel_rop/$1/$2/$3/$4";



	/******************MANTENEDOR******************/

	

	$route['getMantenedorReq'] = "back_end/rop/getMantenedorReq";

	$route['getMantenedorReqList'] = "back_end/rop/getMantenedorReqList";

	$route['formMantenedorReq'] = "back_end/rop/formMantenedorReq";

	$route['eliminaMantenedorReq'] = "back_end/rop/eliminaMantenedorReq";

	$route['getDataMantReq'] = "back_end/rop/getDataMantReq";

	$route['excelMantReq/(:any)'] = "back_end/rop/excelMantReq/$1";



	$route['getMantenedorReqTipoList'] = "back_end/rop/getMantenedorReqTipoList";

	$route['formMantenedorReqTipo'] = "back_end/rop/formMantenedorReqTipo";

	$route['eliminaMantenedorReqTipo'] = "back_end/rop/eliminaMantenedorReqTipo";

	$route['getDataMantReqTipo'] = "back_end/rop/getDataMantReqTipo";



	/******************RESUMEN******************/



	$route['getResumenSyr'] = "back_end/rop/getResumenSyr";

	$route['graphRequerimientos'] = "back_end/rop/graphRequerimientos";

	$route['graphRequerimientosSeg'] = "back_end/rop/graphRequerimientosSeg";



/*****************TURNOS*******/

	$route['cao'] = "back_end/cao/index";

	$route['vistaTurnos'] = "back_end/cao/vistaTurnos";

	$route['listaTrabajadoresTurnos'] = "back_end/cao/listaTrabajadoresTurnos";

	$route['getCabecerasTurnos'] = "back_end/cao/getCabecerasTurnos";

	$route['listaTurnos'] = "back_end/cao/listaTurnos";

	$route['formTurnos'] = "back_end/cao/formTurnos";

	$route['getDataTurnos'] = "back_end/cao/getDataTurnos";

	$route['eliminarTurnos'] = "back_end/cao/eliminarTurnos";

	$route['excel_turnos/(:any)/(:any)/(:any)/(:any)/(:any)'] = "back_end/cao/excel_turnos/$1/$2/$3/$4/$5";



/******************LICENCIAS*************************/

	$route['vistaLicencias'] = "back_end/cao/vistaLicencias";

	$route['getLicenciasList'] = "back_end/cao/getLicenciasList";

	$route['formIngresoLicencias'] = "back_end/cao/formIngresoLicencias";

	$route['eliminaLicencias'] = "back_end/cao/eliminaLicencias";

	$route['getDataRegistroLicencias'] = "back_end/cao/getDataRegistroLicencias";

	$route['listaUsuariosS2'] = "back_end/cao/listaUsuariosS2";



/******************VACACIONES*************************/

	$route['vistaVacaciones'] = "back_end/cao/vistaVacaciones";

	$route['getVacacionesList'] = "back_end/cao/getVacacionesList";

	$route['formIngresoVacaciones'] = "back_end/cao/formIngresoVacaciones";

	$route['eliminaVacaciones'] = "back_end/cao/eliminaVacaciones";

	$route['getDataRegistroVacaciones'] = "back_end/cao/getDataRegistroVacaciones";



/******************MANTENEDOR TURNOS*************************/



	$route['vistaMantenedorTurnos'] = "back_end/cao/vistaMantenedorTurnos";

	$route['getMantenedorTurnosList'] = "back_end/cao/getMantenedorTurnosList";

	$route['formMantenedorTurnos'] = "back_end/cao/formMantenedorTurnos";

	$route['eliminaMantenedorTurnos'] = "back_end/cao/eliminaMantenedorTurnos";

	$route['getDataMantenedorTurnos'] = "back_end/cao/getDataMantenedorTurnos";





/*******MANTENEDORES*******/



	$route['mantenedor_usuarios'] = "back_end/mantenedores/usuarios/index";

	$route['vistaUsuarios'] = "back_end/mantenedores/usuarios/vistaUsuarios";

	$route['listaUsuarios'] = "back_end/mantenedores/usuarios/listaUsuarios";

	$route['getDataUsuarios'] = "back_end/mantenedores/usuarios/getDataUsuarios";

	$route['formUsuario'] = "back_end/mantenedores/usuarios/formUsuario";

	$route['formCargaMasiva'] = "back_end/mantenedores/usuarios/formCargaMasiva";

	$route['formCargaMasivaUsuarios'] = "back_end/mantenedores/usuarios/formCargaMasivaUsuarios";

	$route['excelUsuarios/(:any)'] = "back_end/mantenedores/usuarios/excelUsuarios/$1";

	$route['formCargaMasivaUsuarios'] = "back_end/mantenedores/usuarios/formCargaMasivaUsuarios";

	$route['eliminaUsuario'] = "back_end/mantenedores/usuarios/eliminaUsuario";

	$route['correoDatosFaltantes'] = "back_end/mantenedores/usuarios/correoDatosFaltantes";

	

	$route['vistaCargos'] = "back_end/mantenedores/usuarios/vistaCargos";

	$route['listaCargos'] = "back_end/mantenedores/usuarios/listaCargos";

	$route['getDataCargos'] = "back_end/mantenedores/usuarios/getDataCargos";

	$route['formCargos'] = "back_end/mantenedores/usuarios/formCargos";

	$route['eliminaCargo'] = "back_end/mantenedores/usuarios/eliminaCargo";



	$route['vistaProyectos'] = "back_end/mantenedores/usuarios/vistaProyectos";

	$route['listaProyectos'] = "back_end/mantenedores/usuarios/listaProyectos";

	$route['getDataProyectos'] = "back_end/mantenedores/usuarios/getDataProyectos";

	$route['formProyectos'] = "back_end/mantenedores/usuarios/formProyectos";

	$route['eliminaProyectos'] = "back_end/mantenedores/usuarios/eliminaProyectos";



	$route['vistaAreas'] = "back_end/mantenedores/usuarios/vistaAreas";

	$route['listaAreas'] = "back_end/mantenedores/usuarios/listaAreas";

	$route['getDataAreas'] = "back_end/mantenedores/usuarios/getDataAreas";

	$route['formAreas'] = "back_end/mantenedores/usuarios/formAreas";

	$route['eliminaAreas'] = "back_end/mantenedores/usuarios/eliminaAreas";





	$route['vistaPlazas'] = "back_end/mantenedores/usuarios/vistaPlazas";

	$route['listaPlazas'] = "back_end/mantenedores/usuarios/listaPlazas";

	$route['getDataPlazas'] = "back_end/mantenedores/usuarios/getDataPlazas";

	$route['formPlazas'] = "back_end/mantenedores/usuarios/formPlazas";

	$route['eliminaPlazas'] = "back_end/mantenedores/usuarios/eliminaPlazas";



	$route['vistaJefes'] = "back_end/mantenedores/usuarios/vistaJefes";

	$route['listaJefes'] = "back_end/mantenedores/usuarios/listaJefes";

	$route['getDataJefes'] = "back_end/mantenedores/usuarios/getDataJefes";

	$route['formJefes'] = "back_end/mantenedores/usuarios/formJefes";

	$route['eliminaJefes'] = "back_end/mantenedores/usuarios/eliminaJefes";



	$route['vistaPerfiles'] = "back_end/mantenedores/usuarios/vistaPerfiles";

	$route['listaPerfiles'] = "back_end/mantenedores/usuarios/listaPerfiles";

	$route['getDataPerfiles'] = "back_end/mantenedores/usuarios/getDataPerfiles";

	$route['formPerfiles'] = "back_end/mantenedores/usuarios/formPerfiles";

	$route['eliminaPerfiles'] = "back_end/mantenedores/usuarios/eliminaPerfiles";



/*MANTENEDORES*/



	$route['mantenedor_herramientas'] = "back_end/mantenedores/herramientas/index";

	$route['vistaHerramientas'] = "back_end/mantenedores/herramientas/vistaHerramientas";

	$route['listaHerramientas'] = "back_end/mantenedores/herramientas/listaHerramientas";

	$route['getDataHerramientas'] = "back_end/mantenedores/herramientas/getDataHerramientas";

	$route['formHerramientas'] = "back_end/mantenedores/herramientas/formHerramientas";

	$route['eliminaHerramienta'] = "back_end/mantenedores/herramientas/eliminaHerramienta";

	$route['excelHerramientas/(:any)'] = "back_end/mantenedores/herramientas/excelHerramientas/$1";

	

	$route['mantenedor_responsables_fallos'] = "back_end/mantenedores/responsable_fallos/index";

	$route['vistaResponsablesFallosHerramientas'] = "back_end/mantenedores/responsable_fallos/vistaResponsablesFallosHerramientas";

	$route['listaResponsablesFallosHerramientas'] = "back_end/mantenedores/responsable_fallos/listaResponsablesFallosHerramientas";

	$route['formResponsablesFallosHerramientas'] = "back_end/mantenedores/responsable_fallos/formResponsablesFallosHerramientas";

	$route['getDataResponsableFallosHerramientas'] = "back_end/mantenedores/responsable_fallos/getDataResponsableFallosHerramientas";

	$route['eliminaResponsableFallosHerramientas'] = "back_end/mantenedores/responsable_fallos/eliminaResponsableFallosHerramientas";

	

	$route['mantenedor_checklist_hfc'] = "back_end/mantenedores/checklistHFC/index";

	$route['vistaMantChecklistHFC'] = "back_end/mantenedores/checklistHFC/vistaMantChecklistHFC/index";

	$route['listaMantChecklistHFC'] = "back_end/mantenedores/checklistHFC/listaMantChecklistHFC";

	$route['getDataMantChecklistHFC'] = "back_end/mantenedores/checklistHFC/getDataMantChecklistHFC";

	$route['formMantChecklistHFC'] = "back_end/mantenedores/checklistHFC/formMantChecklistHFC";

	$route['eliminaMantChecklistHFC'] = "back_end/mantenedores/checklistHFC/eliminaMantChecklistHFC";

	$route['excelMantChecklistHFC/(:any)'] = "back_end/mantenedores/checklistHFC/excelMantChecklistHFC/$1";



    $route['mantenedor_checklist_ftth'] = "back_end/mantenedores/checklistFTTH/index";

	$route['vistaMantChecklistFTTH'] = "back_end/mantenedores/checklistFTTH/vistaMantChecklistFTTH/index";

	$route['listaMantChecklistFTTH'] = "back_end/mantenedores/checklistFTTH/listaMantChecklistFTTH";

	$route['getDataMantChecklistFTTH'] = "back_end/mantenedores/checklistFTTH/getDataMantChecklistFTTH";

	$route['formMantChecklistFTTH'] = "back_end/mantenedores/checklistFTTH/formMantChecklistFTTH";

	$route['eliminaMantChecklistFTTH'] = "back_end/mantenedores/checklistFTTH/eliminaMantChecklistFTTH";

	$route['excelMantChecklistFTTH/(:any)'] = "back_end/mantenedores/checklistFTTH/excelMantChecklistFTTH/$1";

	

	$route['mantenedor_metas_igt'] = "back_end/mantenedores/metasIgt/index";

	$route['vistaMetasIgt'] = "back_end/mantenedores/metasIgt/vistaMetasIgt";

	$route['listaMetasIgt'] = "back_end/mantenedores/metasIgt/listaMetasIgt";

	$route['getDataMetasIgt'] = "back_end/mantenedores/metasIgt/getDataMetasIgt";

	$route['formMetasIgt'] = "back_end/mantenedores/metasIgt/formMetasIgt";

	$route['eliminaMetasIgt'] = "back_end/mantenedores/metasIgt/eliminaMetasIgt";

	$route['actualizarMetaActual'] = "back_end/mantenedores/metasIgt/actualizarMetaActual";



	

/******************LIQUIDACIONES*************************/



	$route['liquidaciones'] = "back_end/liquidaciones/index";

	$route['getLiquidacionesInicio'] = "back_end/liquidaciones/getLiquidacionesInicio";

	$route['getLiquidacionesList'] = "back_end/liquidaciones/getLiquidacionesList";

	$route['formLiquidaciones'] = "back_end/liquidaciones/formLiquidaciones";

	$route['eliminaLiquidaciones'] = "back_end/liquidaciones/eliminaLiquidaciones";

	$route['getDataLiquidaciones'] = "back_end/liquidaciones/getDataLiquidaciones";

	$route['listaTrabajadores'] = "back_end/liquidaciones/listaTrabajadores";

	$route['listaTrabajadoresFiltros'] = "back_end/liquidaciones/listaTrabajadores";

	

	$route['carga_masiva'] = "back_end/carga_masiva/index";

	$route['getCargamasivaInicio'] = "back_end/carga_masiva/getCargamasivaInicio";

	$route['getCargamasivaList'] = "back_end/carga_masiva/getCargamasivaList";

	$route['formMasivo'] = "back_end/carga_masiva/formMasivo";

	$route['eliminaCargamasiva'] = "back_end/carga_masiva/eliminaCargamasiva";

	$route['descargarTemplate'] = "back_end/carga_masiva/descargarTemplate";



/************ RCDC - REGISTRO CENTRO DE COMANDO *****************/



	$route['rcdc'] = "back_end/rcdc/index";

	$route['getRcdcInicio'] = "back_end/rcdc/getRcdcInicio";

	$route['getRcdcList'] = "back_end/rcdc/getRcdcList";



	$route['formRcdc'] = "back_end/rcdc/formRcdc";

	$route['eliminaRcdc'] = "back_end/rcdc/eliminaRcdc";

	$route['getDataRcdc'] = "back_end/rcdc/getDataRcdc";



	$route['excelrcdc/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = "back_end/rcdc/excelrcdc/$1/$2/$3/$4/$5/$6";



	/************ RCDC - MANTENEDOR *****************/



	$route['getMantenedorRcdc'] = "back_end/rcdc/getMantenedorRcdc";

	$route['getMotivosRcdcList'] = "back_end/rcdc/getMotivosRcdcList";
	$route['formMotivosRcdc'] = "back_end/rcdc/formMotivosRcdc";
	$route['eliminaMotivosRcdc'] = "back_end/rcdc/eliminaMotivosRcdc";
	$route['getDataMotivosRcdc'] = "back_end/rcdc/getDataMotivosRcdc";

	$route['getComunasRcdcList'] = "back_end/rcdc/getComunasRcdcList";
	$route['formComunasRcdc'] = "back_end/rcdc/formComunasRcdc";
	$route['eliminaComunasRcdc'] = "back_end/rcdc/eliminaComunasRcdc";
	$route['getDataComunasRcdc'] = "back_end/rcdc/getDataComunasRcdc";

	$route['getTiposRcdcList'] = "back_end/rcdc/getTiposRcdcList";
	$route['formTiposRcdc'] = "back_end/rcdc/formTiposRcdc";
	$route['eliminaTiposRcdc'] = "back_end/rcdc/eliminaTiposRcdc";
	$route['getDataTiposRcdc'] = "back_end/rcdc/getDataTiposRcdc";

	$route['listaMotivos'] = "back_end/rcdc/listaMotivos";
	$route['listaTipos'] = "back_end/rcdc/listaTipos";

	/**** GRAFICOS ****/

	$route['getGraficosRcdc'] = "back_end/rcdc/getGraficosRcdc";
	
	$route['getDataGraficosRcdc'] = "back_end/rcdc/getDataGraficosRcdc";

/************ RRE *************/



	$route['rre'] = "back_end/Rre/index";

	$route['getRreInicio'] = "back_end/Rre/getRreInicio";

	$route['getRreList'] = "back_end/Rre/getRreList";



	$route['formRre'] = "back_end/Rre/formRre";

	$route['eliminaRre'] = "back_end/Rre/eliminaRre";

	$route['getDataRre'] = "back_end/Rre/getDataRre";



	$route['excelRre'] = "back_end/Rre/excelRre";



/*************** TCT *****************/



	$route['tct'] = "back_end/Tct/index";

	$route['getTctInicio'] = "back_end/Tct/getTctInicio";

	$route['getTctList'] = "back_end/Tct/getTctList";



	$route['api_tct'] = "back_end/Tct/api";

	$route['api_tct2'] = "back_end/Tct/api2";

	$route['TCTdiario'] = "back_end/Tct/TCTdiario"; //DATOS FORMATEADOS



/****************** ES *************************/



	$route['es'] = "back_end/es/index";

	$route['getEsInicio'] = "back_end/es/getEsInicio";

	$route['getEsList'] = "back_end/es/getEsList";

	$route['formEs'] = "back_end/es/formEs";

	$route['eliminaEs'] = "back_end/es/eliminaEs";

	$route['getDataEs'] = "back_end/es/getDataEs";

	$route['listaRequerimientos'] = "back_end/es/listaRequerimientos";

	$route['listaPersonas'] = "back_end/es/listaPersonas";

	$route['listaResponsables'] = "back_end/es/listaResponsables";

	$route['solicitudesVencidas'] = "back_end/es/solicitudesVencidas";

	$route['excel_es/(:any)/(:any)/(:any)/(:any)'] = "back_end/es/excel_es/$1/$2/$3/$4";

/************ MAD - MODULO AUDITORIA DESPACHO *****************/

	$route['mad'] = "back_end/mad/index";
	$route['getMadInicio'] = "back_end/mad/getMadInicio";
	$route['getMadList'] = "back_end/mad/getMadList";
	$route['formMad'] = "back_end/mad/formMad";
	$route['eliminaMad'] = "back_end/mad/eliminaMad";
	$route['getDataMad'] = "back_end/mad/getDataMad";
	$route['excelmad/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'] = "back_end/mad/excelmad/$1/$2/$3/$4/$5/$6";

	$route['formCargaMasivaMad'] = "back_end/mad/formCargaMasivaMad";

	/************ MAD - MANTENEDOR *****************/

		$route['getMantenedorMad'] = "back_end/mad/getMantenedorMad";

		$route['getMotivosMadList'] = "back_end/mad/getMotivosMadList";
		$route['formMotivosMad'] = "back_end/mad/formMotivosMad";
		$route['eliminaMotivosMad'] = "back_end/mad/eliminaMotivosMad";
		$route['getDataMotivosMad'] = "back_end/mad/getDataMotivosMad";

		$route['getComunasMadList'] = "back_end/mad/getComunasMadList";
		$route['formComunasMad'] = "back_end/mad/formComunasMad";
		$route['eliminaComunasMad'] = "back_end/mad/eliminaComunasMad";
		$route['getDataComunasMad'] = "back_end/mad/getDataComunasMad";

		$route['getTiposMadList'] = "back_end/mad/getTiposMadList";
		$route['formTiposMad'] = "back_end/mad/formTiposMad";
		$route['eliminaTiposMad'] = "back_end/mad/eliminaTiposMad";
		$route['getDataTiposMad'] = "back_end/mad/getDataTiposMad";	

		$route['listaMotivosMad'] = "back_end/mad/listaMotivos";
		$route['listaTiposMad'] = "back_end/mad/listaTipos";




/* End of file routes.php */

/* Location: ./application/config/routes.php */