let totalFees = 0;
let totalDiscount = 0;
let finalAccounts = [];
let taxWithheldInput;

$(function () {
    taxWithheldInput =$(`[bp-field-name=tax_withheld_discount] select, [bp-field-name=tax_withheld_discount] [data-repeatable-input-name=amount]`);
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('select[name="business_profiles_id"]').on('change', function () {
        getBusinessProfile($(this).val())
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
   
async function getBusinessProfile(id) {
    let busCat = [];
    let busTaxFees = [];
    let repeaterparent = $("[bp-field-name=fees_and_delinquency]");
    let total = 0;
    let accountname,amount = null;
    let accounts = [];
    let numOfEmployee = 0;
    let totalFeesCotainer = $("[bp-field-name=fees_and_delinquency]").find('.total');
    await $.ajax({
        url: '/admin/api/business-profile/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
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
            if(data.line_of_business.length>0){
                let net_profit =  $(`[bp-field-name=net_profit]`);
                netprofitTotal ="" ;
                net_profit.find('[data-repeatable-holder=net_profit]').html('')
                $.each(data.line_of_business,function(i,d){
                    net_profit.find('.add-repeatable-element-button').trigger('click');
                
                    net_profit.find(`[data-row-number=${(i+1)}]`).find(".lineOfBusiness").html(this.particulars[0].name)
                    net_profit.find(`[data-row-number=${(i+1)}]`).find("[data-repeatable-input-name=net_profit]").val(this.capital.toLocaleString())
                    if($(`[name=application_type]`).val() =="New"){
                        net_profit.find(`[data-row-number=${(i+1)}]`).find("[data-repeatable-input-name=net_profit]").prop('disabled','disabled');
                    }
                })
               
                net_profit.find('.delete-element').remove();
            }   
            totalFees =data.total;
            finalAccounts = accounts;
            generateSummary()
            
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
    let taxWithheldContainer = $(`[bp-field-name=tax_withheld_discount] [data-repeatable-identifier=tax_withheld_discount]`);
    $.each(taxWithheldContainer,function(){
         select = $(this).find('[data-repeatable-input-name=tax_withheld_discount]');
         text = $(this).find('[data-repeatable-input-name=amount]');
        if($.trim(select.val()) != "" && Math.abs(text.val()) != 0){
            finalAccounts.push([{"accountname":select.val(),"amount":-Math.abs(text.val())}])
            totalFees = totalFees - Math.abs(text.val());
        }
       
        
    })
    console.log(finalAccounts)
    $.each(finalAccounts.reverse(),function(){
        ds =this[0]
        console.log(ds)
        tbody.prepend(`
            <tr>
                <td>
                    ${ds.accountname}
                </td>
                <td>
                ${ds.amount.toLocaleString()}
             </td>
            </tr>
        `);
    })
    $(".total-amount").html("P"+totalFees.toLocaleString())


}

