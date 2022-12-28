
$(function () {
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const input_nameBusinessProfileId  = $(`[name=business_profiles_id]`)
    
    input_nameBusinessProfileId.on('change',function(){
        getDetails($(this).val());
    })



})


async function getDetails(id){
    const net_profit = $(`[bp-field-name=net_profit]`);
    await $.ajax({
        url: '/admin/api/business-profile/get-line-of-business',
        type: 'POST',
        dataType: 'json',
        data: {
            id: id,
            appType:$.trim($('[name=application_type]').val()),
        
        },
        success:function(data){
           
            if(data.line_of_business.length>0){
              
                net_profit.find('[data-repeatable-holder=net_profit]').html('')
                $.each(data.line_of_business,function(i,d){
                    net_profit.find('.add-repeatable-element-button').trigger('click');
                    
                    net_profit.find(`[data-row-number=${(i+1)}]`).find(".lineOfBusiness").html(this.particulars[0].name)
                    // net_profit.find(`[data-row-number=${(i+1)}]`).find("[data-repeatable-input-name=net_profit]").val(this.capital.toLocaleString())
                    if($(`[name=application_type]`).val() =="New"){
                        net_profit.find(`[data-row-number=${(i+1)}]`).find("[data-repeatable-input-name=net_profit]").prop('disabled','disabled');
                    }
                })
               
                net_profit.find('.delete-element').remove();
            }   
        }
    })
   await $.ajax({
        url: '/admin/api/business-profile/get-details-v2',
        dataType: 'json',
        type:'POST',
        data:{
            id:id
        },
        success:function(data){
          if(data.taxFeeCollection.length > 0){
            let tax =data.taxFeeCollection;
            let fees_and_deliquency_holder = $(`[bp-field-name=fees_and_delinquency]`);
            fees_and_deliquency_holder.find(`[data-repeatable-holder=fees_and_delinquency]`).html('')
            $.each(tax,function(i,d){
                if(this.name == "bustax"){
                    $.each(this.data,)
                }else{
                    
                    fees_and_deliquency_holder.find('.add-repeatable-element-button').trigger('click')
                }
            })
          }
        }

    })

}