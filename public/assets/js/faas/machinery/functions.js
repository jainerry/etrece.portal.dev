let url = window.location.href
let pathname = new URL(url).pathname
let paths = pathname.split('/')

$(function () {

    if(paths[3] !== 'create' && paths[3] !== undefined) {
        getDetails(paths[3])
    }
    
    propertyAppraisalActions()

    $('select[name="propertyAssessment[0][actualUse]"]').on('change', function(){
        let actualUse = $(this).val()
        $('select[name="propertyAssessment[0][assessmentLevel]"]').val(actualUse)
        propertyAssessmentComputation()
    })

    $('button.add-repeatable-element-button').on('click', function(){
        propertyAppraisalActions()
        $('input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
        $('input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
    })

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

    $('#tab_property-assessment select[name="assessmentEffectivity"]').on('change', function(){
        if($(this).val() === 'Quarter') {
            $('#tab_property-assessment input[name="assessmentEffectivityValue"]').val('')
            $('#tab_property-assessment .assessmentEffectivityValue_input_fake').addClass('hidden')
            $('#tab_property-assessment .assessmentEffectivityValue_select_fake').removeClass('hidden')
        }
        else {
            $('#tab_property-assessment .assessmentEffectivityValue_input_fake').removeClass('hidden')
            $('#tab_property-assessment input[name="assessmentEffectivityValue"]').val($('#tab_property-assessment .assessmentEffectivityValue_input_fake input').val())
            $('#tab_property-assessment .assessmentEffectivityValue_select_fake').addClass('hidden')
        }
    })

    $('#tab_property-assessment .assessmentEffectivityValue_select_fake select').on('change', function(){
        $('#tab_property-assessment input[name="assessmentEffectivityValue"]').val($(this).val())
    })

    $('#tab_property-assessment .assessmentEffectivityValue_input_fake input').on('change', function(){
        $('#tab_property-assessment input[name="assessmentEffectivityValue"]').val($(this).val())
    })

    //barangay_id
    $('#tab_property-location select[name="barangayId"]').on('change', function(){
        $('#tab_property-location select[name="barangay_code"]').val($(this).val())
        $('#tab_property-location input[name="barangay_code_text"]').val($('#tab_property-location select[name="barangay_code"] option:selected').text())
    })

    //actualUse
    $('#tab_property-assessment select[name="propertyAssessment[0][actualUse]"]').on('change', function(){
        $('#tab_property-assessment select[name="propertyAssessment[0][actualUse_code]"]').val($(this).val())
        $('#tab_property-assessment input[name="propertyAssessment[0][actualUse_code_text]"]').val($('#tab_property-assessment select[name="propertyAssessment[0][actualUse_code]"] option:selected').text())
    })
})

function propertyAppraisalActions(){
    $('.originalCost').on('keyup', function(){
        let rowNumber = $(this).attr('data-row-number')
        propertyAppraisalComputation(rowNumber)
    })

    $('.noOfYearsUsed').on('keyup', function(){
        let rowNumber = $(this).attr('data-row-number')
        propertyAppraisalComputation(rowNumber)
    })

    $('.rateOfDepreciation').on('keyup', function(){
        let rowNumber = $(this).attr('data-row-number')
        propertyAppraisalComputation(rowNumber)
    })
}

function propertyAppraisalComputation(rowNumber) {
    let originalCost = $('#tab_property-appraisal .originalCost[data-row-number="'+rowNumber+'"]').val()
    let noOfYearsUsed = $('#tab_property-appraisal .noOfYearsUsed[data-row-number="'+rowNumber+'"]').val()
    let rateOfDepreciation = $('#tab_property-appraisal .rateOfDepreciation[data-row-number="'+rowNumber+'"]').val()
    let totalDepreciationValue = 0
    let totalDepreciationPercentage = 0

    originalCost = formatStringToFloat(originalCost)
    noOfYearsUsed = formatStringToInteger(noOfYearsUsed)
    rateOfDepreciation = formatStringToInteger(rateOfDepreciation)

    totalDepreciationPercentage = noOfYearsUsed * rateOfDepreciation
    $('#tab_property-appraisal .totalDepreciationPercentage[data-row-number="'+rowNumber+'"]').val(totalDepreciationPercentage)

    totalDepreciationValue = (originalCost / 100) * totalDepreciationPercentage
    $('#tab_property-appraisal .totalDepreciationValue[data-row-number="'+rowNumber+'"]').val(totalDepreciationValue)

    setDepreciatedValue(rowNumber)
}

function setDepreciatedValue(rowNumber){
    let originalCost = $('#tab_property-appraisal .originalCost[data-row-number="'+rowNumber+'"]').val()
    let totalDepreciationPercentage = $('#tab_property-appraisal .totalDepreciationValue[data-row-number="'+rowNumber+'"]').val()
    let depreciatedValue = 0

    originalCost = formatStringToFloat(originalCost)
    totalDepreciationPercentage = formatStringToFloat(totalDepreciationPercentage)

    depreciatedValue = originalCost - totalDepreciationPercentage
    $('#tab_property-appraisal .depreciatedValue[data-row-number="'+rowNumber+'"]').val(depreciatedValue)

    totalOriginalCost()
    totalTotalDepreciationValue()
    totalDepreciatedValue()
    propertyAssessmentComputation()
}

function totalOriginalCost() {
    let totalOriginalCost = 0
    $('.originalCost').each(function(){
        let originalCost = $(this).val()

        originalCost = formatStringToFloat(originalCost)

        totalOriginalCost = totalOriginalCost + originalCost
    })

    $('input[name="totalOriginalCost"]').val(totalOriginalCost)
}

function totalTotalDepreciationValue() {
    let totalTotalDepreciationValue = 0
    $('.totalDepreciationValue').each(function(){
        let totalDepreciationValue = $(this).val()

        totalDepreciationValue = formatStringToFloat(totalDepreciationValue)

        totalTotalDepreciationValue = totalTotalDepreciationValue + totalDepreciationValue
    })

    $('input[name="totalTotalDepreciationValue"]').val(totalTotalDepreciationValue)
}

function totalDepreciatedValue() {
    let totalDepreciatedValue = 0
    $('.depreciatedValue').each(function(){
        let depreciatedValue = $(this).val()

        depreciatedValue = formatStringToFloat(depreciatedValue)

        totalDepreciatedValue = totalDepreciatedValue + depreciatedValue
    })

    $('input[name="totalDepreciatedValue"]').val(totalDepreciatedValue)
    $('input[name="propertyAssessment[0][marketValue]"]').val(totalDepreciatedValue)
}

function propertyAssessmentComputation() {
    let assessmentLevel = $('select[name="propertyAssessment[0][assessmentLevel]"] option:selected').text()
    let marketValue = $('input[name="propertyAssessment[0][marketValue]"]').val()
    let assessedValue = 0

    assessmentLevel = formatStringToInteger(assessmentLevel)
    marketValue = formatStringToFloat(marketValue)
    
    assessedValue = (marketValue / 100) * assessmentLevel
    $('input[name="propertyAssessment[0][assessedValue]"]').val(assessedValue)
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
        return parseInt(num.replaceAll('%',''))
    }
}

function getDetails(id) {
    let isApproved = $('.tab-container #tab_property-assessment input[name="isApproved"]').val()
    if(isApproved === '1') {
        $('.tab-container #tab_property-assessment .approve_items').removeClass('hidden')
    }
    else {
        $('.tab-container #tab_property-assessment .approve_items').addClass('hidden')
    }

    //assessmentType
    let assessmentType = $('#tab_property-assessment select[name="assessmentType"]').val()
    if(assessmentType === 'Exempt') {
        $('#tab_property-assessment .ifAssessmentTypeIsExempt').removeClass('hidden')
    }
    else {
        $('#tab_property-assessment .ifAssessmentTypeIsExempt').addClass('hidden')
    }

    let assessmentEffectivity = $('#tab_property-assessment select[name="assessmentEffectivity"]').val()
    let assessmentEffectivityValue = $('#tab_property-assessment input[name="assessmentEffectivityValue"]').val()
    if(assessmentEffectivity === 'Quarter') {
        $('#tab_property-assessment .assessmentEffectivityValue_select_fake select').val(assessmentEffectivityValue)
        $('#tab_property-assessment .assessmentEffectivityValue_input_fake').addClass('hidden')
        $('#tab_property-assessment .assessmentEffectivityValue_select_fake').removeClass('hidden')
    }
    else {
        $('#tab_property-assessment .assessmentEffectivityValue_input_fake input').val(assessmentEffectivityValue)
        $('#tab_property-assessment .assessmentEffectivityValue_input_fake').removeClass('hidden')
        $('#tab_property-assessment .assessmentEffectivityValue_select_fake').addClass('hidden')
    }
}