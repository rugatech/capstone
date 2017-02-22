$(document).ready(function(){
	var $survey_name=$("#survey_name"), $survey_description=$("#survey_description");
	$("#submitBtn").click(function(){
		$jqxhr=hermesAjax('ajax.php',1,{"survey_name":$survey_name.val(),"survey_description":$survey_description.val()});
		$jqxhr.done(function(data){
			$("#newSurvey").modal('hide');
		})
	});
})