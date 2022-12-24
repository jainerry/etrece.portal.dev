$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('select[name="business_profiles_id"]').on('change', function () {
        getBusinessProfile($(this).val())
    })

})

async function getBusinessProfile(id) {
    let busCat = [];
    let busTaxFees = [];
    let repeaterparent = $("[bp-field-name=fees_and_delinquency]");
    let total = 0;
    let accountname,amount = null;
    let accounts = [];
    let totalFeesCotainer = $("[bp-field-name=fees_and_delinquency]").find('.total');
    await $.ajax({
        url: '/admin/api/business-profile/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            if (data.length > 0) {
                data = data[0]
                $("[bp-field-name=fees_and_delinquency]").find('[data-repeatable-holder=fees_and_delinquency]').html('')
             
                if (data.business_category.length > 0) {
                    $.each(data.business_category, function () {
                       if(this.length>0){
                        if (this[0].business_tax_fees.length>0 ) {
                            $.each(this[0].business_tax_fees, function () {
                                busTaxFees.push(this)
                            })
                        }
                       }
                        
                    })
                }
                busCat = data.business_category;
            }
        }
    })
    if (busTaxFees.length > 0) {
        $.each(busTaxFees, function (i, d) {
            console.log(this.amount_value)
            repeaterparent.find('.add-repeatable-element-button').trigger('click');
            repeaterparent.find(`[data-row-number=${i+1}]`).find(`option[value=${this.id}]`).attr('selected', 'true')
            repeaterparent.find(`[data-row-number=${i+1}]`).find('[data-repeatable-input-name=amount]').val(this.amount_value)
            amount = this.amount_value;
            total  += parseInt(this.amount_value);
            accountname = repeaterparent.find(`[data-row-number=${i+1}]`).find(`option[value=${this.id}]`).html();
          
            accounts.push([
                {"accountname": accountname,"amount":amount}
            ])
        })

        totalFeesCotainer.html("P"+(total.toLocaleString()))
        generateSummary(total,accounts)
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
        tbody.prepend(`
            <tr>
                <td>
                    ${ds.accountname}
                </td>
                <td>
                ${ds.amount}
             </td>
            </tr>
        `);
    })
    $(".total-amount").html("P"+total.toLocaleString())


}

