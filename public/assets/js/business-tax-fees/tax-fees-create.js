const basis = crud.field('basis');
$(function(){
 
   console.log(basis)
   if(basis.value ==="No & Type of Vehicle" ){
      crud.field('vehicle_type').show()
   }else{
      crud.field('vehicle_type').hide()
   }
 
   crud.field("range_box").hide()
   if(crud.field('type').value == "Range"){
      crud.field('range_box').show()
   }else{
      crud.field('range_box').hide()
   }

   
   crud.field('type').onChange(function(field){
      if(field.value == "Range"){
         crud.field('range_box').show()
      }else{
         crud.field('range_box').hide()
      }
   })

   basis.onChange(function(field){
      if(field.value ==="No & Type of Vehicle" ){
         crud.field('vehicle_type').show()
      }else{
         crud.field('vehicle_type').hide()
      }
   })
   
   

})

