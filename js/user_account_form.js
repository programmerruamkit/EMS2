$(function(){
AutoCompleteAjaxReturn2Value('empID','userName','../autocomplete/autocomplete_getdata_emp.php','emp_id','user_name',true)
autocompleteGetData('userName','empID','../autocomplete/autocomplete_getdata_emp.php','user_name','emp_id',true);
	$("#search").on("click",()=>{
		showData()
	})

	$("#copyGroupUser").on("click",()=>{
		copyGroupUser();
	})
});

function fetchDataShow(data){
	$('#tb_authority thead').empty()
	$('#tb_authority tbody').empty()
	var empID = $("#empID").val()
	var userName = $("#userName").val()
	if(empID==""){alert('กรุณากรอกรหัสหรือรายชื่อพนักงาน');return false;}
	if(userName==""){alert('กรุณากรอกรหัสหรือรายชื่อพนักงาน');return false;}
	var content;
	$("#tb_authority thead").html("<tr class='ui-state-default'><th>No.</th><th>Project</th><th>User Group</th><tr>")
	data.forEach((item,index) => {
		content += '<tr >';
		content += '<td width="10%" height="30" align="center">'+(index+1)+(".")+'</td>';
		content += '<td width="45%" height="30" align="left">'+item.project_name+'</td>';
		content += '<td width="45%" height="30" align="left">'+item.group_user_name+'</td>';
		content += '</tr>';
	});
	$('#tb_authority tbody').append(content)
}

function showData(){
		var empID = $("#empID").val();
		var url = "../controllers/user_account_form.php"; 
		$.ajax({ 
			type: "POST",
			url: url,
			data: {post_type:'searchData',empID },
			dataType: 'json',
			success: function(data){
				fetchDataShow(data);
			}
		});
	
}

function autocompleteGetData(idKeyup,idShow,url,fieldKeyUp,fieldShow,idShowStatus){
	
	if(url.indexOf(".php?")==-1){
		url += "?fieldKeyUp="+fieldKeyUp+"&fieldShow="+fieldShow
	}else{
		url += "&fieldKeyUp="+fieldKeyUp+"&fieldShow="+fieldShow
	}	
	var select = false;
	$( "#"+idKeyup).autocomplete({
			minLength:0,
			delay:1100,
			autoFocus: true,
			selectFirst: true,
			search:function(e,u){ // must use this option to correct customTerm sent via ajax. Normal 'source' option does not allow the current value of the target.
				$('#'+idKeyup).autocomplete({ 		
					source: url
				});
			},
			select: function( event, ui ) {
				console.log(ui)
				$("#"+idShow).val($.trim(html_entity_decode(ui.item.fieldShow)));
				var select = true;
			},
			change : function(event,ui){
						if(!ui.item){
							$("#"+idShow).val("");
							$(this).val("");
						}
			},							
		}).data( "autocomplete" )._renderItem = function( ul, item ) {
				if(idShowStatus == true){
					// style='width:auto;'
					return $( "<li></li>" )
					.data( "item.autocomplete", item )
					//.append( "<a>" + item.fieldKeyUp + " :: " + item.fieldShow + "</a>" )
					.append( "<a>" + item.fieldShow + " :: " + item.fieldKeyUp + "</a>" )				
					.appendTo( ul );
				}else{
					//console.log(item.fieldKeyUp)
					return $( "<li></li>" )
					.data( "item.autocomplete", item )
					.append( "<a>" + item.fieldKeyUp + "</a>" )
					.appendTo( ul );
				}
				
		}
					 
		$("#"+idKeyup).click(function(){
			$("#"+idKeyup).autocomplete('search'); 
		});
}
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

function copyGroupUser(){
	var empIdCurrent = $('#emp_id.account-current').val();
	var userIdCurrent = $('#user_id').val();
	var empIdFrom = $('#empID').val();
	var url = '../controllers/user_account_form.php';

	if(empIdFrom == '' || empIdFrom == undefined){
		alert('กรุณาเลือกพนักงานที่ต้องการ copy สิทธิ์');
		return false;
	}
	
	if(confirm('ต้องการ copy สิทธิ์ ใช่หรือไม่')){
		$.ajax({
			url : url,
			type : 'POST',
			data : {
				post_type : 'copyGroupUser',
				empIdCurrent,
				userIdCurrent,
				empIdFrom,
			},
			dataType : 'json',
			cache : false,
			beforeSend : function(){
	
			},
			success : function(res){
				//var data = JSON.parse(res);
				if(res.success == 1){
					loadViewdetail(global_path + 'manage/user_account.php');
					closeUI();
					alert('บันทึกข้อมูลสำเร็จ');
				}
				console.log(res);
			},
			error : function(){
	
			}
	
		})
	}
}