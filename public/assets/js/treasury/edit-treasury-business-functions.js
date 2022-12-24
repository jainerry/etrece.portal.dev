$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('form div.card').addClass('hidden')

    fetchData($('form input[name="businessTaxAssessmentId"]').val())

    otherFeesActions()
    computeTotalOtherFeesAmount()

    $('.repeatable-group[bp-field-name="otherFees"] button.add-repeatable-element-button').on('click', function(){
        $('div[data-repeatable-holder="otherFees"] .repeatable-element input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
        $('div[data-repeatable-holder="otherFees"] .repeatable-element input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
        otherFeesActions()
    })

})

function fetchData(id){
    $.ajax({
        url: '/admin/api/business-tax-assessment/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            if(data.length > 0) {
                data = data[0]
                
                $('input[name="businessTaxAssessmentId"]').val(data.id)

                $('table#summaryTable tbody').html('')

                let primaryOwner = ''
                let suffix = ''
                if(data.suffix !== null && data.suffix !== 'null') {
                    suffix = data.suffix
                }
                if(data.ownerType === 'CitizenProfile') {
                    primaryOwner = data.fName+' '+data.mName+' '+data.lName+' '+suffix
                }
                else {
                    primaryOwner = data.first_name+' '+data.middle_name+' '+data.last_name+' '+suffix
                }

                let mainOfficeAddress = data.buss_prof.main_office.lotNo+' '+data.buss_prof.main_office.noOfStreet+' '+data.buss_prof.main_office.barangay.name

                $('#tab_details input[name="businessName"]').val(data.business_name)
                $('#tab_details textarea[name="mainOfficeAddress"]').val(mainOfficeAddress)
                $('#tab_details input[name="owner"]').val(primaryOwner)
                $('#tab_details textarea[name="ownerAddress"]').val(data.buss_prof.main_office.ownerAddress)

                let fees_and_delinquency = data.fees_and_delinquency
                let tax_withheld_discount = data.tax_withheld_discount

                
                $.each(fees_and_delinquency, function(i, fee) {
                    console.log(fee)
                    //getFeeDetails(i, fee.business_tax_fees, fee.amount)
                    $('table#summaryTable tbody').append('\n\
                        <tr>\n\
                            <td>'+fee.name+'</td>\n\
                            <td class="fee" id="fee_'+i+'">'+fee.amount+'</td>\n\
                        </tr>'
                    )
                })

                $.each(tax_withheld_discount, function(j, discount) {
                    console.log(discount)
                    //getDiscountDetails(discount.tax_withheld_discount, discount.amount) //if and only if given is id
                    $('table#summaryTable tbody').append('\n\
                        <tr>\n\
                            <td>'+discount.name+'</td>\n\
                            <td class="discount" id="discount_'+j+'">'+discount.amount+'</td>\n\
                        </tr>'
                    )
                })

                $('table#summaryTable tbody').append('\n\
                    <tr class="totalSummaryAmountWrapper" style="background-color: #fafafa; font-weight: 600; font-size: 20px; text-transform: uppercase;">\n\
                        <td>Total</td>\n\
                        <td class="totalSummaryAmount" id="totalSummaryAmount"></td>\n\
                    </tr>'
                )

                addNewOtherFeesToSummary()

                computeTotalSummaryAmount()

                $('.treasuryModal').modal('hide');
                $('.tab-container').removeClass('hidden')
                $('#saveActions').removeClass('hidden')
            }
        }
    })
}

function getFeeDetails(i, id, amount){
    $.ajax({
        url: '/admin/api/business-tax-fees/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            if(data.length > 0) {
                data = data[0]
                let otherFeesWrapper = $('table#summaryTable tr.otherFeesWrapper')
                let html = '<tr>\n\
                        <td>'+data.business_fees.name+'</td>\n\
                        <td class="fee" id="fee_'+i+'">'+amount+'</td>\n\
                    </tr>'
                $(html).insertBefore('table#summaryTable tr.totalSummaryAmountWrapper')
            }
        }
    })
}

function computeTotalSummaryAmount(){
    let totalSummaryAmount = 0
    $('table#summaryTable tbody tr td.fee').each(function(){
        let fee = $(this).text()
        fee = formatStringToFloat(fee)
        totalSummaryAmount = totalSummaryAmount + fee
    })

    $('table#summaryTable tbody tr td.discount').each(function(){
        let discount = $(this).text()
        discount = formatStringToFloat(discount)
        totalSummaryAmount = totalSummaryAmount + discount
    })

    $('table#summaryTable tbody tr td.otherFees').each(function(){
        let otherFees = $(this).text()
        otherFees = formatStringToFloat(otherFees)
        totalSummaryAmount = totalSummaryAmount + otherFees
    })
    
    $('#tab_details input[name="totalSummaryAmount"]').val(totalSummaryAmount)
    $('table#summaryTable tbody tr.totalSummaryAmountWrapper td.totalSummaryAmount').text($('#tab_details input[name="totalSummaryAmount"]').val())
}

function otherFeesActions(){
    $('#tab_details select.particulars').on("change", function(){
        addNewOtherFeesToSummary()
    })

    $('#tab_details input.amount').on("change", function(){
        addNewOtherFeesToSummary()
    })
}

function addNewOtherFeesToSummary(){
    $('table#summaryTable tbody tr.otherFeesWrapper').remove()

    $('#tab_details select.particulars').each(function(){
        let dataRowNumber = $(this).attr('data-row-number')
        let otherFee = $('#tab_details select.particulars[data-row-number="'+dataRowNumber+'"] option:selected').text()
        let otherFeeAmount = $('#tab_details input.amount[data-row-number="'+dataRowNumber+'"]').val()

        let otherFeeRow = '<tr class="otherFeesWrapper">\n\
                <td>(Other Fees) '+otherFee+'</td>\n\
                <td class="otherFees" id="otherFee_'+dataRowNumber+'">'+otherFeeAmount+'</td>\n\
            </tr>'

        $(otherFeeRow).insertBefore('table#summaryTable tbody tr.totalSummaryAmountWrapper')
    })
    computeTotalSummaryAmount()
    computeTotalOtherFeesAmount()
}

function computeTotalOtherFeesAmount(){
    let totalOtherFeesAmount = 0
    $('#tab_details select.particulars').each(function(){
        let dataRowNumber = $(this).attr('data-row-number')
        let otherFeeAmount = $('#tab_details input.amount[data-row-number="'+dataRowNumber+'"]').val()
        otherFeeAmount = formatStringToFloat(otherFeeAmount)

        totalOtherFeesAmount = totalOtherFeesAmount + otherFeeAmount
        
    })

    $('#tab_details input[name="totalOtherFeesAmount"]').val(totalOtherFeesAmount)
}

function formatStringToFloat(num){
    if(num === '') {
        return 0
    }
    else {
        return parseFloat(num.replaceAll(',',''))
    }
}

function formatStringToInteger(num){
    if(num === '') {
        return 0
    }
    else {
        return parseFloat(num.replaceAll('%',''))
    }
}