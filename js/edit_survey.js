$(document).ready(function(){
   var $is_required=$(".is_required"), $qrequired=$(".qrequired"), $is_visible=$(".is_visible"), $qvisible=$(".qvisible");
   var $qtype=$(".qtype"), $addOptionModal=$("#addOptionModal"), $add_option_text=$("#add-option-text");
   var $editOptionModal=$("#editOptionModal"), $edit_option_text=$("#edit-option-text");
   var $confirmDeleteOptionModal=$("#confirmDeleteOptionModal"), $addQuestionModal=$("#addQuestionModal");
   var $add_qrequired=$("#add_qrequired"), $add_qvisible=$("#add_qvisible"), $add_question_no=$("#add_question_no", $addQuestionModal);
   var $add_question=$("#add_question", $addQuestionModal), $add_qtype=$("#add_qtype",$addQuestionModal);
   var $add_option1=$("#add_option1",$addQuestionModal), $add_option2=$("#add_option2",$addQuestionModal);
   var $add_option3=$("#add_option3",$addQuestionModal), $add_option4=$("#add_option4",$addQuestionModal);
   var $add_qrequired_condition=$("#add_qrequired_condition",$addQuestionModal), $add_qvisible_condition=$("#add_qvisible_condition",$addQuestionModal);
   var $add_qrequired_option=$("#add_qrequired_option",$addQuestionModal), $add_qvisible_option=$("#add_qvisible_option",$addQuestionModal);
   
   $add_qrequired.change(function(){
      switch($(this).val()){
         case 'Y':
         case 'N':
            $(".required_conditionB",$addQuestionModal).hide();
         break;
         default:
            $(".required_conditionB",$addQuestionModal).show();
         break;
      }
   });

   $add_qvisible.change(function(){
      switch($(this).val()){
         case 'Y':
         case 'N':
            $(".visible_conditionB",$addQuestionModal).hide();
         break;
         default:
            $(".visible_conditionB",$addQuestionModal).show();
         break;
      }
   });
   
   $qtype.on("change",function(){
      let $panel=$(this).closest('div.panel-body');
      switch($(this).val()){
         case 'radio':
         case 'checkbox':
         case 'dropdown':
            $(".visible_options").show();
         break;
         default:
            $(".visible_options").hide();
         break;
      }
   });

   $("#add_question").click(function(){
      $("input,select,textarea", $addQuestionModal).val("");
      let $options='<option value="Y">Yes</option><option value="N">No</option>';
      $.each($("#questionsDiv div.panel"),function(){
         if($(this).data("has-options")=='Y'){
            $options+='<option value="q'+$(this).data("pkey")+'">Yes, If Question (ID='+$(this).data("pkey")+')</option>';
         }
      });
      $add_qrequired.html($options);
      $add_qvisible.html($options);
      $(".required_conditionB, .visible_conditionB",$addQuestionModal).hide();
      $addQuestionModal.modal('show');
   });

   $("#saveAddQuestionModal").click(function(){
      var $qDiv=$("#questionsDiv div.row:eq(0)").clone(), errmsg="", valid_option_count=false;
      if($add_question_no.val()===null||$add_question_no.val()==""){errmsg="You must provide the \"Question No\"\r\n";}
      if($add_question.val()===null||$add_question.val()==""){errmsg+="You must provide the \"Question\"\r\n";}
      if($add_qtype.val()===null||$add_qtype.val()==""){errmsg+="You must provide the \"Type\"\r\n";}
      $(".question-number-p", $qDiv).html($add_question_no.val());
      $(".question-p", $qDiv).html($add_question.val().htmlEncode());
      $(".question-type-p", $qDiv).html($("option:selected",$add_qtype).text());
      switch($add_qtype.val()){
         case 'radio':
         case 'checkbox':
         case 'dropdown':
            $(".form-horizontal .form-group:eq(3)", $qDiv).css("display","block");
            let $ul_group=$("ul.list-group", $qDiv);
            $ul_group.html("");
            if($add_option1.val()!=""){$ul_group.append('<li class="list-group-item">'+$add_option1.val().htmlEncode()+'</li>');valid_option_count=true;}
            if($add_option2.val()!=""){$ul_group.append('<li class="list-group-item">'+$add_option2.val().htmlEncode()+'</li>');valid_option_count=true;}
            if($add_option3.val()!=""){$ul_group.append('<li class="list-group-item">'+$add_option3.val().htmlEncode()+'</li>');valid_option_count=true;}
            if($add_option4.val()!=""){$ul_group.append('<li class="list-group-item">'+$add_option4.val().htmlEncode()+'</li>');valid_option_count=true;}
         break;
         default:
            $(".form-horizontal .form-group:eq(3)", $qDiv).css("display","none");
            valid_option_count=true;
         break;
      }
      if(!valid_option_count){errmsg+="You must provide at least one \"Option\"\r\n";}
      let req=$("option:selected",$add_qrequired).text();
      if(req!='Yes'&&req!='No'){
         if($add_qrequired_condition.val()==''||$add_qrequired_condition.val()===null){errmsg+="You must provide the \"Required => Condition Equality\"\r\n";}
         if($add_qrequired_option.val()==''||$add_qrequired_option.val()===null){errmsg+="You must provide the \"Required => Option ID\"\r\n";}
         else{
            if(isNaN($add_qrequired_option.val())){errmsg+="Invalid value for \"Required => Option ID\" (numbers only)\r\n";}
         }
      }
      if($add_qrequired_condition.val()!=''&&$add_qrequired_condition.val()!==null){req+=' ['+$("option:selected",$add_qrequired_condition).text()+']';}
      if($add_qrequired_option.val()!=""){req+=' [Option #'+$add_qrequired_option.val()+']';}
      $(".required-p", $qDiv).html(req);

      let vis=$("option:selected",$add_qvisible).text();
      if(vis!='Yes'&&vis!='No'){
         if($add_qvisible_condition.val()==''||$add_qvisible_condition.val()===null){errmsg+="You must provide the \"Visible => Condition Equality\"\r\n";}
         if($add_qvisible_option.val()==''||$add_qvisible_option.val()===null){errmsg+="You must provide the \"Visible => Option ID\"\r\n";}
         else{
            if(isNaN($add_qvisible_option.val())){errmsg+="Invalid value for \"Visible => Option ID\" (numbers only)\r\n";}
         }
      }
      if($add_qvisible_condition.val()!=''&&$add_qvisible_condition.val()!==null){vis+=' ['+$("option:selected",$add_qvisible_condition).text()+']';}
      if($add_qvisible_option.val()!=""){vis+=' [Option #'+$add_qvisible_option.val()+']';}
      $(".visible-p", $qDiv).html(vis);
      
      if(errmsg==""){
         let postvars={};
         postvars=serializeJson($addQuestionModal);
         postvars['survey']=urlVars['token'];
         $jqxhr=hermesAjax('ajax.php',2,postvars);
         $jqxhr.done(function(data){
            if(data['errmsg']==""){
               $("div.panel",$qDiv).attr("data-pkey",data['results']['pkey']);
               $("div.panel",$qDiv).attr("data-has-options",data['results']['has-options']);
               console.log(data['results']['pkey']);
               $("span.question_pkey",$qDiv).html(data['results']['pkey']);
               $("#questionsDiv").prepend($qDiv);
               $addQuestionModal.modal('hide');
               alert(data['results']['text']);
            }
            else{alert(data['errmsg']);}
         });
      }
      else{alert(errmsg);}
   });

   $("#questionsDiv").on("click","a.delete-question",function(){
      var b=confirm('Are you sure you want to delete this Question?');
      if(b){
         var $row=$(this).closest("div.row");
         $jqxhr=hermesAjax('ajax.php',3,{"pkey":$(".panel", $row).data("pkey")});
         $jqxhr.done(function(data){
            if(data['errmsg']==""){
               $row.remove();
               alert(data['results']);
            }
            else{
               alert(data['errmsg']);
            }
         });
      }
   });
})