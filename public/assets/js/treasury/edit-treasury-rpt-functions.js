let errorInPeriodCoveredMsg = ''
let rptPrecedingPaymentsArray = []
let hasErrorInPeriodCovered = false

$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('form div.card').addClass('hidden')

    fetchData($('form input[name="rptId"]').val(),$('form select[name="rptType"]').val())

    $('select[name="rptType"]').on('change', function(){
        getPrecedingPayments($('select[name="rptType"]').val(), $('input[name="rptId"]').val())
    })

    $('input[name="rptId"]').on('change', function(){
        getPrecedingPayments($('select[name="rptType"]').val(), $('input[name="rptId"]').val())
    })

    $('select[name="periodCovered"]').on('change', function(){
        checkRPTPrecedingPayments()
    })

    $('input[name="year"]').on('change', function(){
        checkRPTPrecedingPayments()
    })

    $('form').on("submit", function(e){
        let currentYear = $('input[name="year"]').val()
        let currentPeriodCovered = $('select[name="periodCovered"]').val()

        let title = '<i class="la la-exclamation-triangle"></i> Warning Alert'
        let msg = ''

        if(currentYear === ''){
            e.preventDefault()
            msg = 'Please check your inputs. The field Year is Required.'
            $('.alertMessageModal .modal-title').html(title)
            $('.alertMessageModal .modal-body').html(msg)
            $('.alertMessageModal').modal('show');
            $('form #saveActions button[type="submit"]').attr('disabled',false)
        }
        else if (currentPeriodCovered === '') {
            e.preventDefault()
            msg = 'Please check your inputs. The field Period Covered is Required.'
            $('.alertMessageModal .modal-title').html(title)
            $('.alertMessageModal .modal-body').html(msg)
            $('.alertMessageModal').modal('show');
            $('form #saveActions button[type="submit"]').attr('disabled',false)
        }

        if(currentYear !== '' && currentPeriodCovered !== '') {
            if(hasErrorInPeriodCovered) {
                e.preventDefault()
                let title = '<i class="la la-exclamation-triangle"></i> Warning Alert'
                let msg = errorInPeriodCoveredMsg
                $('.alertMessageModal .modal-title').html(title)
                $('.alertMessageModal .modal-body').html(msg)
                $('.alertMessageModal').modal('show');
                $('form #saveActions button[type="submit"]').attr('disabled',false)
            }
            else {
                $('form').submit()
                //openPrintModal()
            }
        }
        
    })

})

function fetchData(id,searchByType){
    let api_url = ''
    if(searchByType === 'Land'){
        api_url = '/admin/api/rpt-land-assessment/get-details'
    }
    else if(searchByType === 'Building'){
        api_url = '/admin/api/rpt-building-assessment/get-details'
    }
    else if(searchByType === 'Machinery'){
        api_url = '/admin/api/rpt-machinery-assessment/get-details'
    }

    $.ajax({
        url: api_url,
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            if(data.length > 0) {
                data = data[0]
                $('input[name="rptId"]').val(data.id)
                $('#tab_details select[name="rptType"]').val(searchByType).change()

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

                let lotNo = ''
                let totalArea = ''

                if(searchByType === 'Land'){
                    lotNo = data.lotNo
                    totalArea = data.totalArea
                }
                else if(searchByType === 'Building'){
                    lotNo = data.faas_building_profile.land_profile.lotNo
                    totalArea = data.faas_building_profile.totalFloorArea

                }
                else if(searchByType === 'Machinery'){
                    lotNo = data.faas_machinery_profile.land_profile.lotNo
                    totalArea = data.faas_machinery_profile.land_profile.totalArea
                }

                $('#tab_details input[name="TDNo"]').val(data.TDNo)
                $('#tab_details input[name="primaryOwner"]').val(primaryOwner)
                $('#tab_details textarea[name="ownerAddress"]').val(data.ownerAddress)
                $('#tab_details input[name="lotNo"]').val(lotNo)
                $('#tab_details input[name="area"]').val(totalArea)
                $('#tab_details input[name="assessedValue"]').val(data.totalPropertyAssessmentAssessmentValue)
                $('#tab_details input[name="dateAssessed"]').val(data.assessedDate)

                //(Basic, Penalty, Discount, Total Basic, SEF, Penalty, Discount, Total SEF)
                getTreasuryRPTRates('980935f0-66d2-44b9-a3b2-360288a5d048')
                getTreasuryRPTRates('9809360c-1e78-40a8-b989-8b5979aecdd4')

                $('.treasuryModal').modal('hide');
                $('.tab-container').removeClass('hidden')
                $('#saveActions').removeClass('hidden')
            }
        }
    })
}

function getTreasuryRPTRates(id){
    $.ajax({
        url: '/admin/api/rpt-rates/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            if(data.length > 0) {
                data = data[0]
                setSummaryDetails(data)
            }
        }
    })
}

function setSummaryDetails(data){
    if(data.id === '980935f0-66d2-44b9-a3b2-360288a5d048') { //Basic
        let percentage = data.percentage
        let assessedValue = $('#tab_details input[name="assessedValue"]').val()
        let basic_amount = 0
        assessedValue = formatStringToFloat(assessedValue)
        percentage = formatStringToInteger(percentage)
        basic_amount = (assessedValue / 100) * percentage
        $('input[name="basic_amount"]').val(basic_amount)
        computeTotalBasic()
    }
    else if(data.id === '9809360c-1e78-40a8-b989-8b5979aecdd4') { //SEF
        let percentage = data.percentage
        let assessedValue = $('#tab_details input[name="assessedValue"]').val()
        let sef_amount = 0
        assessedValue = formatStringToFloat(assessedValue)
        percentage = formatStringToInteger(percentage)
        sef_amount = (assessedValue / 100) * percentage
        $('input[name="sef_amount"]').val(sef_amount)
        computeTotalSEF()
    }
}

function computeTotalBasic(){
    let basic_amount = $('input[name="basic_amount"]').val()
    let basicPenalty_amount = $('input[name="basicPenalty_amount"]').val()
    let basicDiscount_amount = $('input[name="basicDiscount_amount"]').val()

    if($('input[name="basic_amount"]').val() !== '0') { $('table#summaryTable tbody tr td#basic_amount').text($('input[name="basic_amount"]').val()) }
    else { $('table#summaryTable tbody tr td#basic_amount').text('0.00') }

    if($('input[name="basicPenalty_amount"]').val() !== '') { $('table#summaryTable tbody tr td#basicPenalty_amount').text($('input[name="basicPenalty_amount"]').val()) }
    else { $('table#summaryTable tbody tr td#basicPenalty_amount').text('0.00') }

    if($('input[name="basicDiscount_amount"]').val() !== '') { $('table#summaryTable tbody tr td#basicDiscount_amount').text($('input[name="basicDiscount_amount"]').val()) }
    else { $('table#summaryTable tbody tr td#basicDiscount_amount').text('0.00') }

    let totalBasic_amount = 0

    basic_amount = formatStringToFloat(basic_amount)
    basicPenalty_amount = formatStringToFloat(basicPenalty_amount)
    basicDiscount_amount = formatStringToFloat(basicDiscount_amount)

    totalBasic_amount = basic_amount + basicPenalty_amount + basicDiscount_amount

    $('input[name="totalBasic_amount"]').val(totalBasic_amount)
    $('table#summaryTable tbody tr td#totalBasic_amount').text($('input[name="totalBasic_amount"]').val())

    computeTotalSummaryAmount()
}

function computeTotalSEF(){
    let sef_amount = $('input[name="sef_amount"]').val()
    let sefPenalty_amount = $('input[name="sefPenalty_amount"]').val()
    let sefDiscount_amount = $('input[name="sefDiscount_amount"]').val()

    if($('input[name="sef_amount"]').val() !== '') { $('table#summaryTable tbody tr td#sef_amount').text($('input[name="sef_amount"]').val()) }
    else { $('table#summaryTable tbody tr td#sef_amount').text('0.00') }

    if($('input[name="sefPenalty_amount"]').val() !== '') { $('table#summaryTable tbody tr td#sefPenalty_amount').text($('input[name="sefPenalty_amount"]').val()) }
    else { $('table#summaryTable tbody tr td#sefPenalty_amount').text('0.00') }

    if($('input[name="sefDiscount_amount"]').val() !== '') { $('table#summaryTable tbody tr td#sefDiscount_amount').text($('input[name="sefDiscount_amount"]').val()) }
    else { $('table#summaryTable tbody tr td#sefDiscount_amount').text('0.00') }

    let totalSef_amount = 0

    sef_amount = formatStringToFloat(sef_amount)
    sefPenalty_amount = formatStringToFloat(sefPenalty_amount)
    sefDiscount_amount = formatStringToFloat(sefDiscount_amount)

    totalSef_amount = sef_amount + sefPenalty_amount + sefDiscount_amount

    $('input[name="totalSef_amount"]').val(totalSef_amount)
    $('table#summaryTable tbody tr td#totalSef_amount').text($('input[name="totalSef_amount"]').val())

    computeTotalSummaryAmount()
}

function computeTotalSummaryAmount(){
    let totalBasic_amount = $('input[name="totalBasic_amount"]').val()
    let totalSef_amount = $('input[name="totalSef_amount"]').val()
    let totalSummaryAmount = 0

    totalBasic_amount = formatStringToFloat(totalBasic_amount)
    totalSef_amount = formatStringToFloat(totalSef_amount)

    totalSummaryAmount = totalBasic_amount + totalSef_amount

    $('input[name="totalSummaryAmount"]').val(totalSummaryAmount)
    $('table#summaryTable tbody tr td#totalSummaryAmount').text($('input[name="totalSummaryAmount"]').val())
}

function getPrecedingPayments(rptType, rptId){
    rptPrecedingPaymentsArray = []

    $.ajax({
        url: '/admin/api/treasury-rpt/get-preceding-payments',
        type: 'GET',
        dataType: 'json',
        data: {
            rptId: rptId,
            rptType: rptType
        },
        success: function (data) {
            if(data.length > 0) {
                rptPrecedingPaymentsArray = data
            }
        }
    })
}

function checkRPTPrecedingPayments(){
    let currentYear = $('input[name="year"]').val()
    let currentPeriodCovered = $('select[name="periodCovered"]').val()
    let quarterlyCtr = 0
    let semiAnnuallyCtr = 0
    let annuallyCtr = 0
    hasErrorInPeriodCovered = false
    errorInPeriodCoveredMsg = ''

    $.each(rptPrecedingPaymentsArray, function(i, payment) {
        if(currentPeriodCovered === 'Quarterly'){
            if(payment.year === currentYear) {
                if(payment.periodCovered === currentPeriodCovered) {
                    quarterlyCtr++
                }
            }
        }
        else if(currentPeriodCovered === 'Semi-Annually'){
            if(payment.year === currentYear) {
                if(payment.periodCovered === currentPeriodCovered) {
                    semiAnnuallyCtr++
                }
            }
        }
        else if(currentPeriodCovered === 'Annually'){
            if(payment.year === currentYear) {
                if(payment.periodCovered === currentPeriodCovered) {
                    annuallyCtr++
                }
            }
        }
    })

    if(currentPeriodCovered === 'Quarterly'){
        if(quarterlyCtr === 4) {
            hasErrorInPeriodCovered = true 
            errorInPeriodCoveredMsg = 'Payment Record cannot be saved. The period covered ('+currentPeriodCovered+') for year ('+currentYear+') has been exhausted.'
        }
    }
    else if(currentPeriodCovered === 'Semi-Annually'){
        if(semiAnnuallyCtr === 2) {
            hasErrorInPeriodCovered = true 
            errorInPeriodCoveredMsg = 'Payment Record cannot be saved. The period covered ('+currentPeriodCovered+') for year ('+currentYear+') has been exhausted.'
        }
    }
    else if(currentPeriodCovered === 'Annually'){
        if(annuallyCtr === 1) {
            hasErrorInPeriodCovered = true 
            errorInPeriodCoveredMsg = 'Payment Record cannot be saved. The period covered ('+currentPeriodCovered+') for year ('+currentYear+') has been exhausted.'
        }
    }
}

/*function openPrintModal(){
    let html = '\n\
    <style>\n\
        body {\n\
            font-size: 14px;\n\
            font-family:Calibri;\n\
        }\n\
        table {\n\
            font-size: 14px;\n\
            font-family:Calibri;\n\
        }\n\
    </style>\n\
    <table style="width:100%">\n\
        <tr>\n\
            <td align ="left">SALE ORDER NO</td>\n\
            <td align ="right">S01</td>\n\
        </tr>\n\
        <tr>\n\
            <td align ="left">SALE ORDER D/TIME</td>\n\
            <td align ="right">2009/01/01</td>\n\
        </tr>\n\
        <tr>\n\
            <td align ="left">CUSTOMER</td>\n\
            <td align ="right">JOHN DOE</td>\n\
        </tr>\n\
    </table>\n\
    ';

    Popup(html)
}

function Popup(data) 
{
    var myWindow = window.open('', 'Receipt');
    myWindow.document.write('<html><head><title>Receipt</title>');
    myWindow.document.write('<style type="text/css"> *, html {margin:0;padding:0;} </style>');
    myWindow.document.write('</head><body>');
    myWindow.document.write(data);
    myWindow.document.write('</body></html>');
    myWindow.document.close();

    myWindow.onload=function(){
        myWindow.focus();
        myWindow.print();
        myWindow.close();
    };
}*/

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