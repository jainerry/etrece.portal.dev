$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('select[name="business_profiles_id"]').on('change', function () {
        getBusinessProfile($(this).val())
    })
    profval = $(`[name=business_profiles_id]`).val()
   if(profval != null){
    getBusinessProfile(profval)
   }
    checkAppType();
    $(`[type=submit]`).click(function(e){
        e.preventDefault();
        console.log( $(`[data-repeatable-holder=fees_and_delinquency]`).find('select'))
        $(`[data-repeatable-holder=fees_and_delinquency]`).find('select').prop('disabled',false)
        $('form').submit()
    })
})
crud.field('application_type').onChange(function(field){
    console.log(field)
    checkAppType()
});
function checkAppType(){
    // let appType = crud.field("application_type").value;
    // $(`[data-repeatable-holder=net_profit]`).html('')
    // if(appType == "Renewal"){
    //     crud.field('net_profit').show()
    // }else{
    //     crud.field('net_profit').hide()
    // }
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
          
            console.log(data)
            if(data.taxFees.length>0){
                $.each(data.taxFees,function(i,d){
                    repeaterparent.find('.add-repeatable-element-button').trigger('click');
                    repeaterparent.find(`[data-row-number=${(i+1)}]`).find('select').val(this.id)
                    repeaterparent.find(`[data-row-number=${(i+1)}]`).find('[data-repeatable-input-name=amount]').val(parseFloat(this.amount_value).toLocaleString())
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
                    net_profit.find(`[data-row-number=${(i+1)}]`).find("[data-repeatable-input-name=net_profit]").val(parseFloat(this.capital).toLocaleString())
                })
               
                net_profit.find('.delete-element').remove();
            }   
            generateSummary(data.total,accounts)
            
        }
    })
  
}
function showNetProfit(){
        appType = $(".application_type").val();

        if(appType == "Renewal"){

        }
}

function generateSummary(total,accounts){
    let tableParent = $('[bp-field-type=custom_html]')
    let tbody = tableParent.find('tbody');
    let tr = tbody.find('tr');
    tr.map(function(i,d){
       console.log(tr.length)
        if(i <(tr.length-1) ){
            this.remove();
        }
    })
    console.log(accounts)
    let ds = [];
    $.each(accounts,function(){
        ds =this[0]
        console.log(this)
        tbody.prepend(`
            <tr>
                <td>
                    ${ds.accountname}
                </td>
                <td>
                ${parseFloat(ds.amount).toLocaleString()}
             </td>
            </tr>
        `);
    })
    $(".total-amount").html("P"+total.toLocaleString())


}

