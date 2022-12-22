$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('form div.card').addClass('hidden')

    fetchData($('form input[name="faasId"]').val())

    //Edit Page : if rpt is already approved
    let isApproved = $('.tab-container #tab_property-assessment input[name="isApproved"]').val()
    if(isApproved === '1') {
        $('.tab-container #tab_property-assessment .approve_items').removeClass('hidden')
    }
    else {
        $('.tab-container #tab_property-assessment .approve_items').addClass('hidden')
    }

    //Edit Page : if rpt Assessment Type is already set
    let assessmentType = $('.tab-container #tab_property-assessment select[name="assessmentType"]').val()
    if(assessmentType === 'Exempt') {
        $('.tab-container #tab_property-assessment .ifAssessmentTypeIsExempt').removeClass('hidden')
    }
    else {
        $('.tab-container #tab_property-assessment .ifAssessmentTypeIsExempt').addClass('hidden')
    }

    //isApproved
    $('.tab-container #tab_property-assessment input[name="isApproved"]').on('change', function(){
        if($(this).val() === '1') {
            $('.tab-container #tab_property-assessment .approve_items input[name="approvedBy"]').val('')
            $('.tab-container #tab_property-assessment .approve_items input[data-init-function="bpFieldInitDatePickerElement"]').val('')
            $('.tab-container #tab_property-assessment .approve_items input[data-init-function="bpFieldInitDatePickerElement"]').datepicker('update');
            $('.tab-container #tab_property-assessment .approve_items input[name="TDNo"]').val('')
            $('.tab-container #tab_property-assessment .approve_items').removeClass('hidden')
        }
        else {
            $('.tab-container #tab_property-assessment .approve_items input[name="approvedBy"]').val('')
            $('.tab-container #tab_property-assessment .approve_items input[data-init-function="bpFieldInitDatePickerElement"]').val('')
            $('.tab-container #tab_property-assessment .approve_items input[data-init-function="bpFieldInitDatePickerElement"]').datepicker('update');
            $('.tab-container #tab_property-assessment .approve_items input[name="TDNo"]').val('')
            $('.tab-container #tab_property-assessment .approve_items').addClass('hidden')
        }
    })

    //assessmentType
    $('#tab_property-assessment select[name="assessmentType"]').on('change', function(){
        if($(this).val() === 'Exempt') {
            $('#tab_property-assessment .ifAssessmentTypeIsExempt').removeClass('hidden')
        }
        else {
            $('#tab_property-assessment .ifAssessmentTypeIsExempt').addClass('hidden')
        }
    })

    $('#tab_property-appraisal input.originalCost').on('change', function(){
        let dataRowNumber = $(this).attr('data-row-number')
        propertyAppraisalComputation(dataRowNumber)
    })

    $('#tab_property-appraisal input.noOfYearsUsed').on('change', function(){
        let dataRowNumber = $(this).attr('data-row-number')
        setDepreciationValues(dataRowNumber)
    })

    $('#tab_property-appraisal input.rateOfDepreciation').on('change', function(){
        let dataRowNumber = $(this).attr('data-row-number')
        setDepreciationValues(dataRowNumber)
    })

    $('#tab_property-assessment select[name="propertyAssessment[0][actualUse]"]').on('change', function(){
        let actualUse = $(this).val()
        setPropertyAssessmentActualUse(actualUse)
    })

    $('.repeatable-group[bp-field-name="propertyAppraisal"] button.add-repeatable-element-button').on('click', function(){
        $('div[data-repeatable-holder="propertyAppraisal"] .repeatable-element input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
        $('div[data-repeatable-holder="propertyAppraisal"] .repeatable-element input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
    })

})

function fetchData(id){
    $.ajax({
        url: '/admin/api/faas-machinery/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            if(data.length > 0) {
                data = data[0]
                $('#tab_property-assessment input[name="faasId"]').val(data.id)
                
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

                $('#tab_main-information input[name="pin"]').val(data.pin)

                $('#tab_main-information select[name="primaryOwnerId"]').append('<option value="'+data.primaryOwnerId+'">'+primaryOwner+'</option>')
                $('#tab_main-information select[name="primaryOwnerId"]').val(data.primaryOwnerId)

                fetchSecondaryOwners(data.id)

                $('#tab_main-information textarea[name="ownerAddress"]').val(data.ownerAddress)
                $('#tab_main-information input[name="ownerTelephoneNo"]').val(data.ownerTelephoneNo)
                $('#tab_main-information input[name="ownerTin"]').val(data.ownerTin)
                $('#tab_main-information input[name="administrator"]').val(data.administrator)
                $('#tab_main-information textarea[name="administratorAddress"]').val(data.administratorAddress)
                $('#tab_main-information input[name="administratorTelephoneNo"]').val(data.administratorTelephoneNo)
                $('#tab_main-information input[name="administratorTin"]').val(data.administratorTin)
                
                $('#tab_main-information select[name="isActive"]').val(data.isActive)

                $('.rptModal').modal('hide');
                $('.tab-container').removeClass('hidden')
                $('#saveActions').removeClass('hidden')
            }
        }
    })
}

function fetchSecondaryOwners(machinery_profile_id){
    $.ajax({
        url: '/admin/api/faas-machinery/get-secondary-owners',
        type: 'GET',
        dataType: 'json',
        data: {
            machinery_profile_id: machinery_profile_id
        },
        success: function (data) {
            let secondaryOwnerIds = []
            $.each(data, function(i, value) {
                let secondaryOwner = ''
                let suffix = ''
                if(value.suffix !== null && value.suffix !== 'null') {
                    suffix = value.suffix
                }
                secondaryOwner = value.fName+' '+value.mName+' '+value.lName+' '+suffix
                $('#tab_main-information select[name="machinery_owner[]"]').append('<option value="'+value.machinery_profile_id+'">'+secondaryOwner+'</option>')
                secondaryOwnerIds.push(value.machinery_profile_id)
            })
            $('#tab_main-information select[name="machinery_owner[]"]').val(secondaryOwnerIds)
        }
    })
}

function setDepreciationValues(dataRowNumber){
    let noOfYearsUsed = $('#tab_property-appraisal input.noOfYearsUsed[data-row-number="'+dataRowNumber+'"]').val()
    let rateOfDepreciation = $('#tab_property-appraisal input.rateOfDepreciation[data-row-number="'+dataRowNumber+'"]').val()
    let originalCost = $('#tab_property-appraisal input.originalCost[data-row-number="'+dataRowNumber+'"]').val()
    noOfYearsUsed = parseInt(noOfYearsUsed)
    rateOfDepreciation = formatStringToInteger(rateOfDepreciation)
    originalCost = formatStringToFloat(originalCost)
    let totalDepreciationPercentage = noOfYearsUsed * rateOfDepreciation
    
    let totalDepreciationValue = (originalCost / 100) * totalDepreciationPercentage
    $('#tab_property-appraisal input.totalDepreciationPercentage[data-row-number="'+dataRowNumber+'"]').val(totalDepreciationPercentage)
    $('#tab_property-appraisal input.totalDepreciationValue[data-row-number="'+dataRowNumber+'"]').val(totalDepreciationValue)
    propertyAppraisalComputation(dataRowNumber)
}

function propertyAppraisalComputation(dataRowNumber) {
    let originalCost = $('#tab_property-appraisal input.originalCost[data-row-number="'+dataRowNumber+'"]').val()
    let totalDepreciationValue = $('#tab_property-appraisal input.totalDepreciationValue[data-row-number="'+dataRowNumber+'"]').val()
    originalCost = formatStringToFloat(originalCost)
    totalDepreciationValue = formatStringToFloat(totalDepreciationValue)
    let depreciatedValue = originalCost - totalDepreciationValue
    $('#tab_property-appraisal input.depreciatedValue[data-row-number="'+dataRowNumber+'"]').val(depreciatedValue)
    propertyAppraisalComputationTotal()
    let actualUse = $('#tab_property-assessment select[name="propertyAssessment[0][actualUse]"]').val()
    setPropertyAssessmentActualUse(actualUse)
}

function propertyAppraisalComputationTotal(){
    let totalOriginalCost = 0
    let totalTotalDepreciationValue = 0
    let totalDepreciatedValue = 0

    $('#tab_property-appraisal input.originalCost').each(function(){
        let originalCost = $(this).val()
        originalCost = formatStringToFloat(originalCost)
        totalOriginalCost += originalCost
    })

    $('#tab_property-appraisal input.totalDepreciationValue').each(function(){
        let totalDepreciationValue = $(this).val()
        totalDepreciationValue = formatStringToFloat(totalDepreciationValue)
        totalTotalDepreciationValue += totalDepreciationValue
    })

    $('#tab_property-appraisal input.depreciatedValue').each(function(){
        let depreciatedValue = $(this).val()
        depreciatedValue = formatStringToFloat(depreciatedValue)
        totalDepreciatedValue += depreciatedValue
    })

    $('#tab_property-appraisal input[name="totalOriginalCost"]').val(totalOriginalCost)
    $('#tab_property-appraisal input[name="totalTotalDepreciationValue"]').val(totalTotalDepreciationValue)
    $('#tab_property-appraisal input[name="totalDepreciatedValue"]').val(totalDepreciatedValue)

    setPropertyAssessmentMarketValue()
}

function setPropertyAssessmentMarketValue(){
    let marketValue = $('#tab_property-appraisal input[name="totalDepreciatedValue"]').val()
    marketValue = formatStringToFloat(marketValue)
    $('#tab_property-assessment input[name="propertyAssessment[0][marketValue]"]').val(marketValue)
}

function setPropertyAssessmentActualUse(actualUse){
    let actualUseCode = $('#tab_property-assessment select[name="propertyAssessment[0][actualUse]"] option:selected').text()
    $('#tab_property-assessment input[name="propertyAssessment[0][actualUse_fake]"]').val(actualUseCode)

    let marketValue = $('#tab_property-assessment input[name="propertyAssessment[0][marketValue]"]').val()
    marketValue = formatStringToFloat(marketValue)
    $.ajax({
        url: '/admin/api/faas-machinery-classification/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: actualUse
        },
        success: function (data) {
            if(data.length > 0) {
                data = data[0]
                let assessmentLevels = data.assessmentLevels
                let percentage = 0

                $.each(assessmentLevels, function(i, assessmentLevel) {
                    let rangeFrom = assessmentLevel.rangeFrom
                    let rangeTo = assessmentLevel.rangeTo
                    
                    rangeFrom = formatStringToFloat(rangeFrom)
                    rangeTo = formatStringToFloat(rangeTo)
                    
                    if(marketValue >= rangeFrom && rangeFrom < rangeTo) {
                        percentage = assessmentLevel.percentage
                        percentage = formatStringToInteger(percentage)
                    }
                })
                $('#tab_property-assessment input[name="propertyAssessment[0][assessmentLevel]"]').val(percentage)
                propertyAssessmentValueComputation()
            }
        }
    })
}

function propertyAssessmentValueComputation(){
    let marketValue = $('#tab_property-assessment input[name="propertyAssessment[0][marketValue]"]').val()
    marketValue = formatStringToFloat(marketValue)
    let assessmentLevel = $('#tab_property-assessment input[name="propertyAssessment[0][assessmentLevel]"]').val()
    assessmentLevel = formatStringToInteger(assessmentLevel)
    let assessmentValue = (marketValue / 100) * assessmentLevel
    $('#tab_property-assessment input[name="propertyAssessment[0][assessmentValue]"]').val(assessmentValue)
    $('#tab_property-assessment input[name="totalPropertyAssessmentMarketValue"]').val(marketValue)
    $('#tab_property-assessment input[name="totalPropertyAssessmentAssessmentValue"]').val(assessmentValue)
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