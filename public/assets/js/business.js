$(function(){
   inputs = $(".notintrece");
   inputs.hide();
   $("[name=non_trece_resident]").on('change',function(){
      if($(this).val() =="1"){

         inputs.show();
      }else{
         inputs.hide();
      }
   })

})