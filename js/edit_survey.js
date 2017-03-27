$(document).ready(function(){
   var $addQuestionModal=$("#addQuestionModal"), $add_qrequired=$("#add_qrequired"), $add_qvisible=$("#add_qvisible");
   var $add_question_no=$("#add_question_no", $addQuestionModal), $add_question=$("#add_question", $addQuestionModal);
   var $add_qtype=$("#add_qtype",$addQuestionModal), $add_option1=$("#add_option1",$addQuestionModal), $add_option2=$("#add_option2",$addQuestionModal);
   var $add_option3=$("#add_option3",$addQuestionModal), $add_option4=$("#add_option4",$addQuestionModal);
   var $add_qrequired_condition=$("#add_qrequired_condition",$addQuestionModal), $add_qvisible_condition=$("#add_qvisible_condition",$addQuestionModal);
   var $add_qrequired_option=$("#add_qrequired_option",$addQuestionModal), $add_qvisible_option=$("#add_qvisible_option",$addQuestionModal);
   
   var $editQuestionModal=$("#editQuestionModal"), $edit_qrequired=$("#edit_qrequired"), $edit_qvisible=$("#edit_qvisible");
   var $edit_question_no=$("#edit_question_no", $editQuestionModal), $edit_question=$("#edit_question", $editQuestionModal);
   var $edit_qtype=$("#edit_qtype",$editQuestionModal), $edit_option1=$("#edit_option1",$editQuestionModal), $edit_option2=$("#edit_option2",$editQuestionModal);
   var $edit_option3=$("#edit_option3",$editQuestionModal), $edit_option4=$("#edit_option4",$editQuestionModal);
   var $edit_qrequired_condition=$("#edit_qrequired_condition",$editQuestionModal), $edit_qvisible_condition=$("#edit_qvisible_condition",$editQuestionModal);
   var $edit_qrequired_option=$("#edit_qrequired_option",$editQuestionModal), $edit_qvisible_option=$("#edit_qvisible_option",$editQuestionModal);

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
   $edit_qrequired.change(function(){
      switch($(this).val()){
         case 'Y':
         case 'N':
            $(".required_conditionB",$editQuestionModal).hide();
         break;
         default:
            $(".required_conditionB",$editQuestionModal).show();
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
   $edit_qvisible.change(function(){
      switch($(this).val()){
         case 'Y':
         case 'N':
            $(".visible_conditionB",$editQuestionModal).hide();
         break;
         default:
            $(".visible_conditionB",$editQuestionModal).show();
         break;
      }
   });

   $add_qtype.on("change",function(){
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
   $edit_qtype.on("change",function(){
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
      $(".visible_options", $qDiv).hide();
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

   $("#questionsDiv").on("click","a.edit-question",function(){
      var pkey=$(this).closest('div.panel').data("pkey");
      $("input,select,textarea", $editQuestionModal).val("");
      let $options='<option value="Y">Yes</option><option value="N">No</option>';
      $.each($("#questionsDiv div.panel"),function(){
         if($(this).data("has-options")=='Y'){
            $options+='<option value="q'+$(this).data("pkey")+'">Yes, If Question (ID='+$(this).data("pkey")+')</option>';
         }
      });
      $edit_qrequired.html($options);
      $edit_qvisible.html($options);
      $(".required_conditionB, .visible_conditionB",$editQuestionModal).hide();
      $jqxhr=hermesAjax('ajax.php',4,{"pkey":pkey});
      $jqxhr.done(function(data){
         console.log(data['results']);
         $edit_question_no.val(data['results']['question_number']);
         $edit_question.val(data['results']['question']);
         $edit_qtype.val(data['results']['question_type']);
         $edit_qtype.trigger('change');
         if(data['results']['options']!==null){
            let $o=$.parseJSON(data['results']['options']);
            $edit_option1.val($o['1']);
            $edit_option2.val($o['2']);
            $edit_option3.val($o['3']);
            $edit_option4.val($o['4']);

         }
         //if(data['errmsg']==""){
         //   $row.remove();
         //   alert(data['results']);
         //}
         //else{
         //   alert(data['errmsg']);
         //}
      });
      $jqxhr.fail(function(jqXHR, e ){
         console.log(e);
      });
      $editQuestionModal.modal('show');
   });
})