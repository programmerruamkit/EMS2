function ajaxPopup2(url,proc,con,w,h,title){
	var url = url+"?proc="+proc;
	var id = getIdSelect();
	if(proc != 'add' && proc !=""){
		if(!id){// แก้ไขข้อมูล หรือ ลบข้อมูล
			Swal.fire({
				position: 'center',
				icon: 'warning',
				title: 'กรุณาเลือกข้อมูล',
				showConfirmButton: false,
				timer: 1500
			  })
				// alert("กรุณาเลือกข้อมูล");
				return false();
		}
	// }else{
	// 	url+="&id="+id;
	}	
	/**/
	url+="&id="+id;	
	url+="&"+con;
	$.get(url,function(data){
		$("#dialog_popup").dialog("option","width",w ).dialog("option","height",h);
		$('#dialog_popup').empty().dialog("open").append(data);
		$("#dialog_popup").dialog( { title: title } );
		//$('#dialog_popup').empty().dialog("open").append(data);
	});
}
function ajaxPopup2post(url, proc, con, w, h, title, method = "GET") {
    var id = getIdSelect();
    if (proc != 'add' && proc != "") {
        if (!id) {
            Swal.fire({
                position: 'center',
                icon: 'warning',
                title: 'กรุณาเลือกข้อมูล',
                showConfirmButton: false,
                timer: 1500
            });
            return false;
        }
    }
    if (method === "POST") {
        // con ควรเป็น object (data) สำหรับ POST
        var data = con;
        data.proc = proc;
        data.id = id;
        $.post(url, data, function (data) {
            $("#dialog_popup").dialog("option", "width", w).dialog("option", "height", h);
            $('#dialog_popup').empty().dialog("open").append(data);
            $("#dialog_popup").dialog({ title: title });
        });
    } else {
        // GET แบบเดิม
        url = url + "?proc=" + proc;
        url += "&id=" + id;
        url += "&" + con;
        $.get(url, function (data) {
            $("#dialog_popup").dialog("option", "width", w).dialog("option", "height", h);
            $('#dialog_popup').empty().dialog("open").append(data);
            $("#dialog_popup").dialog({ title: title });
        });
    }
}
function ajaxPopup4(url,proc,id,con,w,h,title){
	var url = url+"?proc="+proc;
	var id = id;
	if(proc != 'add' && proc !=""){
		if(!id){// แก้ไขข้อมูล หรือ ลบข้อมูล
			Swal.fire({
				position: 'center',
				icon: 'warning',
				title: 'กรุณาเลือกข้อมูล',
				showConfirmButton: false,
				timer: 1500
			  })
				// alert("กรุณาเลือกข้อมูล");
				return false();
		}
	// }else{
	// 	url+="&id="+id;
	}	
	/**/
	url+="&id="+id;	
	url+="&"+con;
	$.get(url,function(data){
		// $("#dialog_popup").dialog("option","width",w ).dialog("option","height",h);
		$("#dialog_popup").dialog("option","width",w ).dialog("option","height",h).dialog("option","position","center");
		$('#dialog_popup').empty().dialog("open").append(data);
		$("#dialog_popup").dialog( { title: title } );
		//$('#dialog_popup').empty().dialog("open").append(data);
	});
}
function dataTable(id){
	$("#"+id+" tbody tr").click( function( e ) {
		//alert(e);
		if ( $(this).hasClass('row_selected') ) {
			$(this).removeClass('row_selected');
		} else {
			oTable.$('tr.row_selected').removeClass('row_selected');
			$(this).addClass('row_selected');
		}
	});
	oTable = $('#'+id).dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bLengthChange": false,
		"iDisplayLength": 20
	});	
}
function MultiDataTable(id, oTable, search_keyword =''){
	$("#"+id+" tbody tr").click( function( e ) {
		//alert();
		if ( $(this).hasClass('row_selected') ) {
			$(this).removeClass('row_selected');
		} else {
			oTable.$('tr.row_selected').removeClass('row_selected');
			$(this).addClass('row_selected');
		}
	});
	oTable = $('#'+id).dataTable({
		"bJQueryUI": true,
		"sPaginationType": "full_numbers",
		"bLengthChange": false,
		"iDisplayLength": 20,
		"oSearch": {"sSearch": search_keyword}
	});	
}
function treeMenu(){
	$("#browser").treeview({persist: "cookie" });
}
function block(id){
	$('#'+id).block({ message: '<h4><img src="'+global_path+'css/images/ui-anim_basic_16x16.gif"> กำลังโหลดข้อมูล...</h4>' });
}
function blockUI(data){
	$.blockUI({
		message:data
		,css:{
			width:'40%'
			,top:'80px'
			,backgroundColor:'#FFFFFF'				
			}
	 });
}
function closeUI(){
	$.unblockUI();
	$("#dialog_popup").dialog("close"); 
}
function genMenuSidebar(menu_id){
	var url = global_path+"include/sidebarleft.php?menu_id="+menu_id;
	$.get(url,function(data){
		$("#sidebar").html(data);
		treeMenu();
	});
}
function loadViewdetail(url,menu_id){		
	if(url!="" && url!="#"){
		//if($('#current_menu_id').val() != menu_id){
			block("show_detail");
			$.get(url,function(data){
					$("#show_detail").html(data);
					dataTable('datatable');
					dataTable('datatable1');
					dataTable('datatable2');
					dataTable('datatable3');
					dataTable('datatable4');
					dataTable('datatable5');
					dataTable('datatable6');
					dataTable('datatable7');
					dataTable('datatable8');
					dataTable('datatable9');
					dataTable('datatable10');
					genTabMenu("tabs_menu");
			});
			$('#current_menu_id').val(menu_id);
		///}
	}
}
function loadOvertime(url, menu_id, name_edit) {
	if (url != "" && url != "#") {
		//if($('#current_menu_id').val() != menu_id){
		block("show_detail");
		$.get(url, function (data) {
			$("#show_detail").html(data);
			MultiDataTable("datatable",20, name_edit );
			genTabMenu("tabs_menu");
		});
		$('#current_menu_id').val(menu_id);
		///}
	}
}
function loadViewdetailBYId(url,menu_id,id){		
	if(id=="") id=currentTabDivId;
	if(url!="" && url!="#"){
		block(currentTabDivId);
		$.get(url,function(data){
				$("#"+id).html(data);
				closeUI();
		});
	}
}
function deleteData(url,proc,return_url){
	if(confirm("ยืนยันการลบข้อมูล")){
		var id = getIdSelect();
			if(!id){
				Swal.fire({
					position: 'center',
					icon: 'warning',
					title: 'กรุณาเลือกข้อมูล',
					showConfirmButton: false,
					timer: 1500
				  })
					// alert("กรุณาเลือกข้อมูล");
				return false;
			}
		$.post(url,{ proc: proc, id: id},function(data){
			alert(data);
			loadViewdetail(return_url);
		});
	  }
}
function getIdSelect(){
	return $("#datatable tbody tr.row_selected").attr("id");
}
function toggleTreeMenu(){
var v = $("#toggle_menu").val();
	if(v=="ปิด"){
		$("#main tr td#td_sidebar").css("display","none");
		$("#main tr td#td_detail").attr("width","100%");
		$("#toggle_menu").val("เปิด");
		$("#toggle").html('<img src="../images/middle_toggle_right.gif" style="cursor:pointer">');
	}else{
		$("#main tr td#td_sidebar").css("display","");
		$("#main tr td#td_detail").attr("width","85%");
		$("#toggle_menu").val("ปิด");
		$("#toggle").html('<img src="../images/middle_toggle_left.gif" style="cursor:pointer">');
	}
}
function ajaxLoadpage(url,proc,con){
	var url = url+"?proc="+proc;
	if(proc=="edit"){// แก้ไขข้อมูล
	  var id = getIdSelect();
		if(!id){
			Swal.fire({
				position: 'center',
				icon: 'warning',
				title: 'กรุณาเลือกข้อมูล',
				showConfirmButton: false,
				timer: 1500
			  })
				// alert("กรุณาเลือกข้อมูล");
			return false;
		}else{
			url+="&id="+id;
		}
	}
	block("show_detail");
	url+="&"+con;
	$.get(url,function(data){
		$("#show_detail").html(data);
		 dataTable('datatable');
	});
}
var currentTabDivId; //สร้างไว้สำหรับมีการแบบหน้าโดยใช้ Tabmenu เพื่อเก็บค่า ID ของ Div เพื่อนำข้อมูลมาแสดงผล
function genTabMenu(id){
	$( "#"+id ).tabs({
		show: function(event, ui) {
				currentTabDivId = ui.panel.id;
				/*if(ui.panel.id=="ui-tabs-3"){
					$("div[id2=tr_showTable] ,#before_bottoms").hide();
				}else{
					$("div[id2=tr_showTable] ,#before_bottoms").show();	
				}*/
		   }
	});	
}



//##############################

function AutoCompleteNormal(id,source){
	$('#'+id).autocomplete({
		minLength:0, delay:0,
		search:function(e,u){ $('#'+id).autocomplete({ source:source });},
		select:function(e,u){ $('#'+id).val(u.item.value); }
	});
	$("#normal").click(function(){ $('#'+id).autocomplete('search');});
}
//var i = [{"show":"xxx"},{"show":"xxx"}];
function AutoCompleteAjaxReturn2Value(idKeyup,idShow,url,fieldKeyUp,fieldShow,idShowStatus){
	if(url.indexOf(".php?")==-1){
		url += "?fieldKeyUp="+fieldKeyUp+"&fieldShow="+fieldShow
	}else{
		url += "&fieldKeyUp="+fieldKeyUp+"&fieldShow="+fieldShow
	}
	$( "#"+idKeyup).autocomplete({
			minLength:0,
			delay:0,
			search:function(e,u){ // must use this option to correct customTerm sent via ajax. Normal 'source' option does not allow the current value of the target.
				$('#'+idKeyup).autocomplete({ 		
					source: url
				});
			},
			select: function( event, ui ) {
								$("#"+idShow).val($.trim(html_entity_decode(ui.item.fieldShow)));
						},
			change : function(event,ui){
							if(!ui.item){
									$("#"+idShow).val("");
									$(this).val("");
							}
						}
		}).data( "autocomplete" )._renderItem = function( ul, item ) {
				if(idShowStatus == true){
					// style='width:auto;'
					return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.fieldKeyUp + " :: " + item.fieldShow + "</a>" )
					.appendTo( ul );
				}else{
					return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.fieldKeyUp+"</a>" )
					.appendTo( ul );
				}
		};		
		$("#"+idKeyup).click(function(){ $("#"+idKeyup).autocomplete('search'); });
}

function AutoCompleteAjax(url,objAuto){
	var fieldShow = ""
	if(objAuto.elementOther.length>0){
		$.each(objAuto.elementOther,function(){
			fieldShow += "&fShow[]="+this.fieldName;
		});
	}
	if(url.indexOf(".php?")==-1){
		url += "?fKey="+objAuto.elementKeyUp.fieldName+fieldShow
	}else{
		url += "&fKey="+objAuto.elementKeyUp.fieldName+fieldShow
	}
	$( "#"+objAuto.elementKeyUp.elementId).autocomplete({
			minLength:0,
			delay:0,
			search:function(e,u){
				$('#'+objAuto.elementKeyUp.elementId).autocomplete({ 		
					source: url
				});
			},
			select: function( event, ui ) {
								if(objAuto.elementOther.length>0){
									$.each(objAuto.elementOther,function(i){
										$("#"+this.elementId).val($.trim(html_entity_decode(ui.item.fShow[i])));
									});
								}
						},
			change : function(event,ui){
							if(!ui.item){
									$(this).val("");
									if(objAuto.elementOther.length>0){
										$.each(objAuto.elementOther,function(i){
											$("#"+this.elementId).val("");
										});
									}
							}
						}
		}).data( "autocomplete" )._renderItem = function( ul, item ) {
				if(objAuto.elementOther.length>0){
					var fShow = "";
					$.each(objAuto.elementOther,function(i){
						if(this.showDetail==true){
							fShow += " :: "+item.fShow[i];
						}
					});
					return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.fKey + fShow + "</a>" )
					.appendTo( ul );
				}else{
					return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.fieldKeyUp+"</a>" )
					.appendTo( ul );
				}
		};	
		$("#"+objAuto.elementKeyUp.elementId).click(function(){ $("#"+objAuto.elementKeyUp.elementId).autocomplete('search'); });
}

function html_entity_decode(string, quote_style) {
	 var hash_map = {},symbol = '',tmp_str = '',entity = '';
	  tmp_str = string.toString();
	  if (false === (hash_map = this.get_html_translation_table('HTML_ENTITIES', quote_style))) {
		return false;
	  }
	  delete(hash_map['&']);
	  hash_map['&'] = '&amp;';
	  for (symbol in hash_map) {
		entity = hash_map[symbol];
		tmp_str = tmp_str.split(entity).join(symbol);
	  }
	  tmp_str = tmp_str.split('&#039;').join("'");
	  return tmp_str;
}

function get_html_translation_table (table, quote_style) {
  // http://kevin.vanzonneveld.net
  // +   original by: Philip Peterson
  // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +   bugfixed by: noname
  // +   bugfixed by: Alex
  // +   bugfixed by: Marco
  // +   bugfixed by: madipta
  // +   improved by: KELAN
  // +   improved by: Brett Zamir (http://brett-zamir.me)
  // +   bugfixed by: Brett Zamir (http://brett-zamir.me)
  // +      input by: Frank Forte
  // +   bugfixed by: T.Wild
  // +      input by: Ratheous
  // %          note: It has been decided that we're not going to add global
  // %          note: dependencies to php.js, meaning the constants are not
  // %          note: real constants, but strings instead. Integers are also supported if someone
  // %          note: chooses to create the constants themselves.
  // *     example 1: get_html_translation_table('HTML_SPECIALCHARS');
  // *     returns 1: {'"': '&quot;', '&': '&amp;', '<': '&lt;', '>': '&gt;'}
  var entities = {},
	hash_map = {},
	decimal;
  var constMappingTable = {},
	constMappingQuoteStyle = {};
  var useTable = {},
	useQuoteStyle = {};

  // Translate arguments
  constMappingTable[0] = 'HTML_SPECIALCHARS';
  constMappingTable[1] = 'HTML_ENTITIES';
  constMappingQuoteStyle[0] = 'ENT_NOQUOTES';
  constMappingQuoteStyle[2] = 'ENT_COMPAT';
  constMappingQuoteStyle[3] = 'ENT_QUOTES';

  useTable = !isNaN(table) ? constMappingTable[table] : table ? table.toUpperCase() : 'HTML_SPECIALCHARS';
  useQuoteStyle = !isNaN(quote_style) ? constMappingQuoteStyle[quote_style] : quote_style ? quote_style.toUpperCase() : 'ENT_COMPAT';

  if (useTable !== 'HTML_SPECIALCHARS' && useTable !== 'HTML_ENTITIES') {
	throw new Error("Table: " + useTable + ' not supported');
	// return false;
  }

  entities['38'] = '&amp;';
  if (useTable === 'HTML_ENTITIES') {
	entities['160'] = '&nbsp;';
	entities['161'] = '&iexcl;';
	entities['162'] = '&cent;';
	entities['163'] = '&pound;';
	entities['164'] = '&curren;';
	entities['165'] = '&yen;';
	entities['166'] = '&brvbar;';
	entities['167'] = '&sect;';
	entities['168'] = '&uml;';
	entities['169'] = '&copy;';
	entities['170'] = '&ordf;';
	entities['171'] = '&laquo;';
	entities['172'] = '&not;';
	entities['173'] = '&shy;';
	entities['174'] = '&reg;';
	entities['175'] = '&macr;';
	entities['176'] = '&deg;';
	entities['177'] = '&plusmn;';
	entities['178'] = '&sup2;';
	entities['179'] = '&sup3;';
	entities['180'] = '&acute;';
	entities['181'] = '&micro;';
	entities['182'] = '&para;';
	entities['183'] = '&middot;';
	entities['184'] = '&cedil;';
	entities['185'] = '&sup1;';
	entities['186'] = '&ordm;';
	entities['187'] = '&raquo;';
	entities['188'] = '&frac14;';
	entities['189'] = '&frac12;';
	entities['190'] = '&frac34;';
	entities['191'] = '&iquest;';
	entities['192'] = '&Agrave;';
	entities['193'] = '&Aacute;';
	entities['194'] = '&Acirc;';
	entities['195'] = '&Atilde;';
	entities['196'] = '&Auml;';
	entities['197'] = '&Aring;';
	entities['198'] = '&AElig;';
	entities['199'] = '&Ccedil;';
	entities['200'] = '&Egrave;';
	entities['201'] = '&Eacute;';
	entities['202'] = '&Ecirc;';
	entities['203'] = '&Euml;';
	entities['204'] = '&Igrave;';
	entities['205'] = '&Iacute;';
	entities['206'] = '&Icirc;';
	entities['207'] = '&Iuml;';
	entities['208'] = '&ETH;';
	entities['209'] = '&Ntilde;';
	entities['210'] = '&Ograve;';
	entities['211'] = '&Oacute;';
	entities['212'] = '&Ocirc;';
	entities['213'] = '&Otilde;';
	entities['214'] = '&Ouml;';
	entities['215'] = '&times;';
	entities['216'] = '&Oslash;';
	entities['217'] = '&Ugrave;';
	entities['218'] = '&Uacute;';
	entities['219'] = '&Ucirc;';
	entities['220'] = '&Uuml;';
	entities['221'] = '&Yacute;';
	entities['222'] = '&THORN;';
	entities['223'] = '&szlig;';
	entities['224'] = '&agrave;';
	entities['225'] = '&aacute;';
	entities['226'] = '&acirc;';
	entities['227'] = '&atilde;';
	entities['228'] = '&auml;';
	entities['229'] = '&aring;';
	entities['230'] = '&aelig;';
	entities['231'] = '&ccedil;';
	entities['232'] = '&egrave;';
	entities['233'] = '&eacute;';
	entities['234'] = '&ecirc;';
	entities['235'] = '&euml;';
	entities['236'] = '&igrave;';
	entities['237'] = '&iacute;';
	entities['238'] = '&icirc;';
	entities['239'] = '&iuml;';
	entities['240'] = '&eth;';
	entities['241'] = '&ntilde;';
	entities['242'] = '&ograve;';
	entities['243'] = '&oacute;';
	entities['244'] = '&ocirc;';
	entities['245'] = '&otilde;';
	entities['246'] = '&ouml;';
	entities['247'] = '&divide;';
	entities['248'] = '&oslash;';
	entities['249'] = '&ugrave;';
	entities['250'] = '&uacute;';
	entities['251'] = '&ucirc;';
	entities['252'] = '&uuml;';
	entities['253'] = '&yacute;';
	entities['254'] = '&thorn;';
	entities['255'] = '&yuml;';
  }

  if (useQuoteStyle !== 'ENT_NOQUOTES') {
	entities['34'] = '&quot;';
  }
  if (useQuoteStyle === 'ENT_QUOTES') {
	entities['39'] = '&#39;';
  }
  entities['60'] = '&lt;';
  entities['62'] = '&gt;';


  // ascii decimals to real symbols
  for (decimal in entities) {
	if (entities.hasOwnProperty(decimal)) {
	  hash_map[String.fromCharCode(decimal)] = entities[decimal];
	}
  }

  return hash_map;
}

function datepicker_thai(elementClass){
	$(elementClass).removeClass('hasDatepicker');
	$(elementClass).datepicker('destroy').datepicker({ 
		changeMonth: true, 
		changeYear: true,
		dateFormat: 'dd/mm/yy', 
		isBuddhist: true, 
		dayNames: ['อาทิตย์','จันทร์','อังคาร','พุธ','พฤหัสบดี','ศุกร์','เสาร์'],
		dayNamesMin: ['อา.','จ.','อ.','พ.','พฤ.','ศ.','ส.'],
		monthNames: ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'],
		monthNamesShort: ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.']
	}); 
	$("div#ui-datepicker-div").css("display","none");
}

function noWeekends(date) {
	var day = date.getDay();
	// ถ้าวันเป็นวันอาทิตย์ (0) หรือวันเสาร์ (6)
	if (day === 1 || day === 2 || day === 3 || day === 4 || day === 5 || day === 6) {
		// เลือกไม่ได้
		return [false, "", "วันนี้เป็นวันหยุด"];
	}
	// เลือกได้ตามปกติ
	return [true, "", ""];
}

function datepicker_thai_between(elementClass){
	$(elementClass).removeClass('hasDatepicker');
	$(elementClass).datepicker('destroy').datepicker({ 
		changeMonth: true, 
		changeYear: true,
		dateFormat: 'dd/mm/yy', 
		isBuddhist: true, 
		dayNames: ['อาทิตย์','จันทร์','อังคาร','พุธ','พฤหัสบดี','ศุกร์','เสาร์'],
		dayNamesMin: ['อา.','จ.','อ.','พ.','พฤ.','ศ.','ส.'],
		monthNames: ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'],
		monthNamesShort: ['ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.'],
		beforeShowDay: noWeekends
	}); 
	$("div#ui-datepicker-div").css("display","none");
}

function isNumberKey(evt) {
	var charCode = (evt.which) ? evt.which : evt.keyCode;
	if (charCode != 46 && charCode > 31 &&
		(charCode < 48 || charCode > 57))
		return false;

	return true;
}