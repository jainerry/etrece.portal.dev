$(function(){
   const  checkSameHead   = $(".same_head_office");
   const buss_act_add = $(".buss_act_add")
   if(checkSameHead.length > 0){
      // checkSameHead.prependTo(buss_act_add)
   }
   crud.field("range_box").hide()
   crud.field('type').onChange(function(field){
      if(field.value == "Range"){
         crud.field('range_box').show()
      }else{
         crud.field('range_box').hide()
      }
   })
})