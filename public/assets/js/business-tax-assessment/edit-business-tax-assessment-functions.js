let totalFees = 0;
let totalDiscount = 0;
let finalAccounts = [];
let finalDiscountAccounts = [];
let finalDiscount = 0;
let taxWithheldInput;
const net_profit =  $(`[data-repeatable-identifier=net_profit]`);
$(function () {
    taxWithheldInput =$(`[bp-field-name=tax_withheld_discount] select, [bp-field-name=tax_withheld_discount] [data-repeatable-input-name=amount]`);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    const inputBusProfID = $('select[name="business_profiles_id"]');
    inputBusProfID.on('change', function () {
        
        getBusinessProfile($(this).val())
    })
    $('[name=application_type]').on('change',function(){
        if(inputBusProfID.val() != null || inputBusProfID != ""){
            getBusinessProfile(inputBusProfID.val())
        }
       
    })

    profval = $.trim($(`[name=business_profiles_id]`).val())

    if(profval != null && profval != ""){
     getBusinessProfile(profval)
    }
    checkAppType();
    $(`[type=submit]`).click(function(e){
        e.preventDefault();

        $(`[data-repeatable-holder=fees_and_delinquency]`).find('select').prop('disabled',false)
        $('form').submit()
    })
    
    $('body').on('change','[bp-field-name=tax_withheld_discount] select, [bp-field-name=tax_withheld_discount] [data-repeatable-input-name=amount]',function(){
        generateSummary();
    })
    $(`body`).on('change','[data-repeatable-input-name=net_profit]',function(){

        getBusinessProfile(inputBusProfID.val(),true);
    })
    

})
$(`[name=application_type]`).on("change",function(field){
  
    checkAppType(this)
});

function checkAppType(ds){
      
    if($(ds).val() =="New"){
        $(`[data-repeatable-input-name=net_profit]`).prop('disabled',true);
    }else{
        $(`[data-repeatable-input-name=net_profit]`).prop('disabled',false);
    }
    }
function genereateLineOfBusinessArray(){
   
  
    return result;
}
async function getBusinessProfile(id,$exclude_net_profit = false) {
    if(id==""){
        return;
    }
    finalAccounts = [];
    let repeaterparent = $("[bp-field-name=fees_and_delinquency]");
    let accounts = [];
    let net_profit =  $(`[bp-field-name=net_profit]`);
    if(!$exclude_net_profit){
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
        
    }
    
    
    let lobarray = [];

    let accountLOB,amount =null;
    $.each(net_profit,function(){
       accountLOB =  $(this).find('.lineOfBusiness').text()
       amount = $(this).find('[data-repeatable-input-name=net_profit]').val() == "" ? 0 :$(this).find('[data-repeatable-input-name=net_profit]').val();
       lobarray.push({'accountLOB':accountLOB,"amount":amount});
    });

    console.log(lobarray)

    await $.ajax({
        url: '/admin/api/business-profile/get-details-v2',
        type: 'POST',
        dataType: 'json',
        data: {
            id: id,
            appType:$.trim($('[name=application_type]').val()),
            lob:lobarray,
            action:'edit'
        },
        success: function (data) {
            $('[data-repeatable-holder=fees_and_delinquency]').html('')
            
            if(data.taxFees.length>0){
                $.each(data.taxFees,function(i,d){
                    repeaterparent.find('.add-repeatable-element-button').trigger('click');
                    repeaterparent.find(`[data-row-number=${(i+1)}]`).find('select').val(this.id)
                    repeaterparent.find(`[data-row-number=${(i+1)}]`).find('[data-repeatable-input-name=amount]').val(this.amount_value)
                    accounts.push([{accountname:this.business_fees_name,amount:this.amount_value.toLocaleString()}])
                })
               
                repeaterparent.find(".total").html("P"+data.total.toLocaleString())
              
                repeaterparent.find('.delete-element').remove();
            }
            totalFees =data.total;
            finalAccounts = accounts;
            generateSummary()
            
        },
        error:function(e){
          
            let message = e.responseJSON.result
            $('[name=application_type]').val('Renewal').trigger('change')
            net_profit.find('[data-repeatable-holder=net_profit]').html('')
            swal({
                title: "Invalid Application Type",
                text: message,
                icon: "warning",
                timer: 4000,
                buttons: false,
            });
        }
    })
  
}
function showNetProfit(){
        appType = $(".application_type").val();

        if(appType == "Renewal"){

        }
}

function appendTaxWithheldSummary(){


}
function generateSummary(){
    let tableParent = $('[bp-field-type=custom_html]')
    let tbody = tableParent.find('tbody');
    let tr = tbody.find('tr');
    tr.map(function(i,d){
        if(i <(tr.length-1) ){
            this.remove();
        }
    })
    let ds = [];
    let select,text;
    let discount =[];
    finalDiscount = 0;
    finalDiscountAccounts = [];
    let taxWithheldContainer = $(`[bp-field-name=tax_withheld_discount] [data-repeatable-identifier=tax_withheld_discount]`);
    let TWHD_isexist = $(".summaryTable .tax-discount");
    if(TWHD_isexist.length>0){
        TWHD_isexist.remove()
    }
    $.each(taxWithheldContainer,function(){
         select = $(this).find('[data-repeatable-input-name=tax_withheld_discount]');
         text = $(this).find('[data-repeatable-input-name=amount]');
        if($.trim(select.val()) != "" && Math.abs(text.val()) != 0){
            finalDiscountAccounts.push([{"accountname":select.val(),"amount":-Math.abs(text.val()),"taxWithHeldClass":'tax-discount'}])
            finalDiscount = finalDiscount + Math.abs(text.val());
        }  
    })

    $.each(finalAccounts,function(){
        ds =this[0]
      
        tbody.prepend(`
            <tr >
                <td>
                    ${ds.accountname}
                </td>
                <td>
                ${ds.amount.toLocaleString()}
             </td>
            </tr>
        `);
    })
    // finalDiscountAccounts.sort((a,b)=> a>b);
    console.log(finalDiscountAccounts.sort((a,b)=> {return a.accountname>b.accountname}))
    $.each(finalDiscountAccounts.sort((a,b)=> a.accountname>b.accountname),function(){
        ds =this[0]
        $(`
            <tr class="${ds.taxWithHeldClass != null ? ds.taxWithHeldClass:''}">
                <td>
                    ${ds.accountname}
                </td>
                <td>
                ${ds.amount.toLocaleString()}
             </td>
            </tr>
        `).insertBefore('tbody tr:last-child');
    })
    // $fnum =totalFees - finalDiscount;
   
    $(".total-amount").html("P"+(totalFees - finalDiscount).toLocaleString())


}

