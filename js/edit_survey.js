$(document).ready(function(){
   var $is_required=$(".is_required"), $qrequired=$(".qrequired"), $is_visible=$(".is_visible"), $qvisible=$(".qvisible");
   var $qtype=$(".qtype"), $addOptionModal=$("#addOptionModal"), $add_option_text=$("#add-option-text");
   var $editOptionModal=$("#editOptionModal"), $edit_option_text=$("#edit-option-text");
   var $confirmDeleteOptionModal=$("#confirmDeleteOptionModal");

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
      $add_option_text.val("");
      $addOptionModal.data({"panel":$panel}).modal('show');
   });


   $("ul.list-group").on("mouseenter","li.list-group-item",function(){
      let $this=$(this);
      if(!$this.hasClass("empty-list")){
         $(this).addClass("list-group-item-hover");
      }
   }).on("mouseleave","li.list-group-item",function(){
      $(this).removeClass("list-group-item-hover");
   }).on("click","li.list-group-item",function(){
      let $this=$(this);
      if(!$this.hasClass("empty-list")){
         $edit_option_text.val("");
         let option_value=$this.html().replace(/&lt;/g,"<").replace(/&gt;/g,">").replace(/<br>/g,"\n");
         $edit_option_text.val(option_value);
         $editOptionModal.data({"list-item":$this}).modal('show');
      }
   });

   $("#saveAddOptionModal").click(function(){
      let $panel=$addOptionModal.data("panel");
      let $list_group=$("ul.list-group",$panel);
      let option_value=$add_option_text.val().replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br>");
      $("li.empty-list", $list_group).remove();
      let $li=$("<li></li>").addClass("list-group-item").append(option_value);
      $list_group.append($li);
      $addOptionModal.modal('hide');
      return false;
   });

   $("#saveEditOptionModal").click(function(){
      let $list_item=$editOptionModal.data("list-item");
      let option_value=$edit_option_text.val().replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/\n/g,"<br>");
      $list_item.html(option_value);
      $editOptionModal.modal('hide');
      return false;
   });
   
   $("#deleteEditOptionModal").click(function(){
      let $list_item=$editOptionModal.data("list-item");
      $("#deleteOptionText").html($list_item.html().replace(/\n/g,"<br>"));
      $confirmDeleteOptionModal.modal('show').data({"list-item":$editOptionModal.data("list-item")});
      return false;
   });

   $("#yesDeleteModalOptionModal").click(function(){
      let $list_item=$editOptionModal.data("list-item");
      $list_item.remove();
      $editOptionModal.modal('hide');
      $confirmDeleteOptionModal.modal('hide');
   });
})