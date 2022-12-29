
$(function () {
    
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const input_nameBusinessProfileId  = $(`[name=business_profiles_id]`)
    const input_applicationType = $('[name=application_type]')
    const body = $('body');
    input_nameBusinessProfileId.on('change',function(){
        getDetails($(this).val());
    })

    input_applicationType.on('change',function(){
        if(input_nameBusinessProfileId.val() != ""){
            getDetails(input_nameBusinessProfileId.val())
        }
    })

    body.on('change','[data-repeatable-input-name=tax_withheld_discount], [data-repeatable-input-name=discount_amount]',function(){
        if(input_nameBusinessProfileId.val() != ""){
            if($('[data-repeatable-input-name=tax_withheld_discount]').val() != "" && $('[data-repeatable-input-name=discount_amount]').val() != ""){
                getDetails(input_nameBusinessProfileId.val())
            }
            
        }
    })
    $("#saveActions button.btn-success").on('click',function(e){
        e.preventDefault();
        $('[data-repeatable-input-name=business_tax_fees]').attr('disabled',false);
        $('form').submit()
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
            let temp = [];
            $.each(tax,function(i,d){
                if(this.name == "bustax"){
                    if(this.data.length > 0){
                         $.each(this.data,function(){
                         
                            // fees_and_deliquency_holder.find(`[data-row-number=${i}]`).find(`[data-repeatable-input-name=business_tax_fees]`).val(this.id)
                            // // fees_and_deliquency_holder.find(`[data-row-number=${i}]`).find(`[data-repeatable-input-name=amount]`).val(this.amount)
                            // fees_and_deliquency_holder.find(`[data-repeatable-identifier=fees_and_delinquency][data-row-number=${i}]`).find(`[data-repeatable-input-name=business_tax_fees]`).val(this.id)
                            temp.push(this);
                        })
                    }
                   
                }else{
                    temp.push(this);
                  
                    // fees_and_deliquency_holder.find(`[data-repeatable-identifier=fees_and_delinquency][data-row-number=${(i+1)}]`).find(`[data-repeatable-input-name=business_tax_fees]`).val(this.id)
                    
                }
            })
            $.each(temp,function(i,d){
                fees_and_deliquency_holder.find('.add-repeatable-element-button').trigger('click')
                fees_and_deliquency_holder.find(`[data-row-number=${(i+1)}]`).find(`[data-repeatable-input-name=amount]`).val(this.amount)
                fees_and_deliquency_holder.find(`[data-repeatable-identifier=fees_and_delinquency][data-row-number=${(i+1)}]`).find(`[data-repeatable-input-name=business_tax_fees]`).val(this.id)
                

            
            })
            fees_and_deliquency_holder.find('.delete-element').remove()
            $(".total-fees_and_delinquency").html(data.total.toLocaleString())

            generateSummary();
          }
        }

    })
}

function generateSummary(){
    const fees_and_delinquency= $(`[data-repeatable-identifier=fees_and_delinquency]`);
    const summaryTable = $(".summaryTable");
    const tbody = summaryTable.find('tbody');
    const tr = tbody.find('tr');
    let total = 0;
    const taxWithHeldDiscount = $('[data-repeatable-identifier=tax_withheld_discount]')
    $.each(tr,function(i){
        if(!((i+1) == tr.length)){
            $(this).remove()
        }
    })
    $.each(fees_and_delinquency,function(){
        select = $(this).find('option:selected').html();
       
        value = $(this).find('[data-repeatable-input-name=amount]').val();
        total = total+ parseFloat(value);
        tbody.prepend(`
        <tr>
         <td> ${select}  </td>
         <td> ${(parseFloat(value).toLocaleString())}</td?
        </tr>
        `)
    })
    $.each(taxWithHeldDiscount,function(){
        type = $('[data-repeatable-input-name=tax_withheld_discount]')
        amount = $('[data-repeatable-input-name=discount_amount]')

        if(type.val() != "" && amount != ""){
            total = total- parseFloat(amount.val());
            $(`
            <tr>
             <td> ${type.val()}  </td>
             <td> ${(parseFloat(amount.val()).toLocaleString())}</td?
            </tr>
            `).insertBefore('tbody>tr:last-child')
        }

    })
    $(".total-amount").html("P "+parseFloat(total).toLocaleString());
    
}
