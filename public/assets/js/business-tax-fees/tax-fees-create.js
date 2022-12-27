const basis = crud.field('basis');
$(function(){
 
   console.log(basis)
   if(basis.value ==="05" ){
      crud.field('vehicle_type').show()
   }else{
      crud.field('vehicle_type').hide()
   }
 
   crud.field("range_box").hide()
   if(crud.field('type').value == "02"){
      crud.field('range_box').show()
   }else{
      crud.field('range_box').hide()
   }

   
   crud.field('type').onChange(function(field){
      if(field.value == "02"){
         crud.field('range_box').show()
      }else{
         crud.field('range_box').hide()
      }
   })

   basis.onChange(function(field){
      if(field.value ==="05" ){
         crud.field('vehicle_type').show()
      }else{
         crud.field('vehicle_type').hide()
      }
   })
   
   

})

