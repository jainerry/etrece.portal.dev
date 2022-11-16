$(function(){
   if($('[name=id]').length > 0){
      getSelectedCluster()
   }else{
      getCluster()
   }
   
   crud.field('brgyID').onChange(function(field){
      getCluster();
   })
})

function getCluster(){
   const barangay = crud.field('brgyID').value
   
   tpl = [];
   $.get('/admin/citizen-profile/cluster',{barangay_id:barangay},function(e){
      
     $.each(e,function(){
      tpl.push(`<option value="${this.id}">${this.name} </option>`);
     })
      $("[name=purokID]").html(tpl.join(''))
   })
}
function getSelectedCluster(){
   const barangay = crud.field('brgyID').value
   const id = $('[name=id]').val();
   tpl = [];
   $.get('/admin/citizen-profile/cluster',{barangay_id:barangay,selected:true,id:id},function(e){
      
     $.each(e.data,function(){
      tpl.push(`<option value="${this.id}" ${e.selected.purokID ==this.id ? 'selected':'' }>${this.name} </option>`);
     })
      $("[name=purokID]").html(tpl.join(''))
   })
}