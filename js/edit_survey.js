$(document).ready(function(){
   var $is_required=$(".is_required"), $qrequired=$(".qrequired"), $is_visible=$(".is_visible"), $qvisible=$(".qvisible");
   var $qtype=$(".qtype");

   $is_required.on("change",function(){
      let $panel=$(this).closest('div.panel-body');
      switch($(this).val()){
         case 'Y':
            $(".required_conditionA",$panel).show();
         break;
         case 'N':
            $(".required_conditionA",$panel).hide();
         break;
      }
   });

   $qrequired.on("change",function(){
      let $panel=$(this).closest('div.panel-body');
      switch($(this).val()){
         case 'q0':
            $(".required_conditionB",$panel).hide();
         break;
         default:
            $(".required_conditionB",$panel).show();
         break;
      }
   });

   $is_visible.on("change",function(){
      let $panel=$(this).closest('div.panel-body');
      switch($(this).val()){
         case 'Y':
            $(".visible_conditionA",$panel).show();
         break;
         case 'N':
            $(".visible_conditionA",$panel).hide();
         break;
      }
   });

   $qvisible.on("change",function(){
      let $panel=$(this).closest('div.panel-body');
      switch($(this).val()){
         case 'q0':
            $(".visible_conditionB",$panel).hide();
         break;
         default:
            $(".visible_conditionB",$panel).show();
         break;
      }
   });
   
   $qtype.on("change",function(){
      let $panel=$(this).closest('div.panel-body');
      switch($(this).val()){
         case 'radio':
         case 'checkbox':
         case 'dropdown':
            $(".visible_options",$panel).show();
         break;
         default:
            $(".visible_options",$panel).hide();
         break;
      }
   });

   $(".add-option").click(function(){
      let $panel=$(this).closest('div.panel-body');
      $(".add-option-group", $panel).toggle();
   });

   $(".save-add-option").click(function(){
      let $panel=$(this).closest('div.panel-body');
      let $list_group=$("ul.list-group",$panel);
      let option_value=$(".add-option-text",$panel).val().replace(/</g,"&lt;").replace(/>/g,"&gt;");
      $("li.empty-list", $list_group).remove();
      $list_group.append('<li class="list-group-item">'+option_value+'</li>');
   });

})