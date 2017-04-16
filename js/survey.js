$(document).ready(function(){
	var is_visible={}, is_required={}, option_triggers={'required':{},'visible':{}};
	var $questionsDiv=$("#questionsDiv");
	
	function update_visible(ctrl, option_value){
		$.each(option_triggers['visible'][ctrl],function(key,val){
			let vis=val.split(':');
			switch(vis[0]){
				case 'neq':
					if(option_value!=vis[1]){$("#row-"+key).show();}
					else{$("#row-"+key).hide();}
				break;
				case 'eq':
					if(option_value==vis[1]){$("#row-"+key).show();}
					else{$("#row-"+key).hide();}
				break;
			}
		});
	}

	$(".panel-green").each(function(){
		let $this=$(this), reg=[], vis=[];
		let  pkey=$this.data("pkey");
		is_required[pkey]=$this.data("required");
		is_visible[pkey]=$this.data("visible");
		option_triggers['visible'][pkey]={};
		if($this.data("visible")!='Y'&&$this.data("visible")!='N'){
			vis=$this.data("visible").split(':');
			option_triggers['visible'][vis[0].replace('q','')][pkey]=vis[1]+':'+vis[2];
		}
	});

	$("input:radio").click(function(){
		update_visible($(this).closest("div.panel").data("pkey"),$(this).val());
	});

	$("select").change(function(){
		update_visible($(this).closest("div.panel").data("pkey"),$(this).val());
	});

	$("#saveSurvey").click(function(){
		//console.log($("input:radio,input:checkbox,input:text,textarea,select",$questionsDiv).serialize());
		$("#form1").submit();
	});
})