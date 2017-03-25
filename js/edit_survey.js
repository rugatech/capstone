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

   String.prototype.htmlEncode = function() {
      return this.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br>");
   }
   String.prototype.htmlDecode = function() {
      return this.replace(/&lt;/g,"<").replace(/&gt;/g,">").replace(/<br>/g,"\n");
   }
   
   function serializeJson($form) { 
      let retval={};
      $.each($("input, select, textarea",$form),function(){
         if($(this).val()===null){retval[$(this).attr("id")]="";}
         else{retval[$(this).attr("id")]=$(this).val();}
      });
      return retval;
   }
   
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
      let $qDiv=$("#questionsDiv div.row:eq(0)").clone();
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
            if($add_option1.val()!=""){$ul_group.append('<li class="list-group-item">'+$add_option1.val().htmlEncode()+'</li>');}
            if($add_option2.val()!=""){$ul_group.append('<li class="list-group-item">'+$add_option2.val().htmlEncode()+'</li>');}
            if($add_option3.val()!=""){$ul_group.append('<li class="list-group-item">'+$add_option3.val().htmlEncode()+'</li>');}
            if($add_option4.val()!=""){$ul_group.append('<li class="list-group-item">'+$add_option4.val().htmlEncode()+'</li>');}
         break;
         default:
            $(".form-horizontal .form-group:eq(3)", $qDiv).css("display","none");
         break;
      }
      let req=$("option:selected",$add_qrequired).text();
      if($add_qrequired_condition.val()!=''&&$add_qrequired_condition.val()!=null){req+=' ['+$("option:selected",$add_qrequired_condition).text()+']';}
      if($add_qrequired_option.val()!=""){req+=' [Option #'+$add_qrequired_option.val()+']';}
      $(".required-p", $qDiv).html(req);

      let vis=$("option:selected",$add_qvisible).text();
      if($add_qvisible_condition.val()!=''&&$add_qvisible_condition.val()!=null){vis+=' ['+$("option:selected",$add_qvisible_condition).text()+']';}
      if($add_qvisible_option.val()!=""){vis+=' [Option #'+$add_qvisible_option.val()+']';}
      $(".visible-p", $qDiv).html(vis);

      $("#questionsDiv").prepend($qDiv);
      $addQuestionModal.modal('hide');

      console.log(serializeJson($addQuestionModal));
      //$.each($("input, select, textarea",$addQuestionModal),function(){
      //   console.log($(this).attr("id")+" "+$(this).val());
      //});
      
   });
})