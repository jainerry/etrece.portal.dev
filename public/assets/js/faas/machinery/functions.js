$(function () {
    
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