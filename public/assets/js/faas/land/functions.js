$(function () {

    landAppraisalActions()
    otherImprovementsActions()
    marketValueActions()

    $('#tab_land-appraisal button.add-repeatable-element-button').on('click', function(){
        landAppraisalActions()
        $('input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
        $('input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
    })

    $('#tab_other-improvements button.add-repeatable-element-button').on('click', function(){
        otherImprovementsActions()
        $('input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
        $('input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
    })

    $('#tab_market-value button.add-repeatable-element-button').on('click', function(){
        marketValueActions()
        $('input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
        $('input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
    })

    $('#tab_property-assessment button.add-repeatable-element-button').on('click', function(){
        $('input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
        $('input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
    })
})

function landAppraisalActions(){
    $('#tab_land-appraisal .classification').on('change', function(){
        let classification = $(this).val()
        let rowNumber = $(this).attr('data-row-number')
        $('#tab_land-appraisal .actualUse[data-row-number="'+rowNumber+'"]').val(classification)
    })

    $('#tab_land-appraisal .area').on('keyup', function(){
        let rowNumber = $(this).attr('data-row-number')
        landAppraisalComputation(rowNumber)
    })

    $('#tab_land-appraisal .unitValue').on('keyup', function(){
        let rowNumber = $(this).attr('data-row-number')
        landAppraisalComputation(rowNumber)
    })
}

function landAppraisalComputation(rowNumber){
    let area = $('#tab_land-appraisal .area[data-row-number="'+rowNumber+'"]').val()
    let unitValue = $('#tab_land-appraisal .unitValue[data-row-number="'+rowNumber+'"]').val()
    let baseMarketValue = 0

    area = formatStringToFloat(area)
    unitValue = formatStringToFloat(unitValue)

    baseMarketValue = area * unitValue
    $('#tab_land-appraisal .baseMarketValue[data-row-number="'+rowNumber+'"]').val(baseMarketValue)

    totalLandAppraisalBaseMarketValue()
}

function totalLandAppraisalBaseMarketValue(){
    let totalLandAppraisalBaseMarketValue = 0
    $('#tab_land-appraisal .baseMarketValue').each(function(){
        let baseMarketValue = $(this).val()

        baseMarketValue = formatStringToFloat(baseMarketValue)

        totalLandAppraisalBaseMarketValue = totalLandAppraisalBaseMarketValue + baseMarketValue

        let rowNumber = $(this).attr('data-row-number')
        updatePropertyAssessment(rowNumber)
    })

    $('input[name="totalLandAppraisalBaseMarketValue"]').val(totalLandAppraisalBaseMarketValue)
}

function otherImprovementsActions(){
    $('#tab_other-improvements .totalNumber').on('keyup', function(){
        let rowNumber = $(this).attr('data-row-number')
        otherImprovementsComputation(rowNumber)
    })

    $('#tab_other-improvements .unitValue').on('keyup', function(){
        let rowNumber = $(this).attr('data-row-number')
        otherImprovementsComputation(rowNumber)
    })
}

function otherImprovementsComputation(rowNumber){
    let totalNumber = $('#tab_other-improvements .totalNumber[data-row-number="'+rowNumber+'"]').val()
    let unitValue = $('#tab_other-improvements .unitValue[data-row-number="'+rowNumber+'"]').val()
    let baseMarketValue = 0

    totalNumber = formatStringToInteger(totalNumber)
    unitValue = formatStringToFloat(unitValue)

    baseMarketValue = totalNumber * unitValue
    $('#tab_other-improvements .baseMarketValue[data-row-number="'+rowNumber+'"]').val(baseMarketValue)

    totalOtherImprovementsBaseMarketValue()
}

function totalOtherImprovementsBaseMarketValue(){
    let totalOtherImprovementsBaseMarketValue = 0
    $('#tab_other-improvements .baseMarketValue').each(function(){
        let baseMarketValue = $(this).val()

        baseMarketValue = formatStringToFloat(baseMarketValue)

        totalOtherImprovementsBaseMarketValue = totalOtherImprovementsBaseMarketValue + baseMarketValue
    })

    $('input[name="totalOtherImprovementsBaseMarketValue"]').val(totalOtherImprovementsBaseMarketValue)
}

function marketValueActions(){
    $('#tab_market-value .baseMarketValue').on('keyup', function(){
        let rowNumber = $(this).attr('data-row-number')
        marketValueComputation(rowNumber)
    })

    $('#tab_market-value .adjustmentFactorPercentage').on('keyup', function(){
        let rowNumber = $(this).attr('data-row-number')
        marketValueComputation(rowNumber)
    })
}

function marketValueComputation(rowNumber){
    let baseMarketValue = $('#tab_market-value .baseMarketValue[data-row-number="'+rowNumber+'"]').val()
    let adjustmentFactorPercentage = $('#tab_market-value .adjustmentFactorPercentage[data-row-number="'+rowNumber+'"]').val()
    let valueAdjustment = 0
    let marketValue = 0

    baseMarketValue = formatStringToFloat(baseMarketValue)
    adjustmentFactorPercentage = formatStringToInteger(adjustmentFactorPercentage)

    valueAdjustment = (baseMarketValue / 100) * adjustmentFactorPercentage
    $('#tab_market-value .valueAdjustment[data-row-number="'+rowNumber+'"]').val(valueAdjustment)

    let valueAdjustment2 = $('#tab_market-value .valueAdjustment[data-row-number="'+rowNumber+'"]').val()
    valueAdjustment2 = formatStringToFloat(valueAdjustment2)

    marketValue = baseMarketValue + valueAdjustment2
    $('#tab_market-value .marketValue[data-row-number="'+rowNumber+'"]').val(marketValue)

    totalMarketValueMarketValue()
}

function totalMarketValueMarketValue(){
    let totalMarketValueMarketValue = 0
    $('#tab_market-value .marketValue').each(function(){
        let marketValue = $(this).val()

        marketValue = formatStringToFloat(marketValue)

        totalMarketValueMarketValue = totalMarketValueMarketValue + marketValue

        let rowNumber = $(this).attr('data-row-number')
        updatePropertyAssessment(rowNumber)
    })

    $('input[name="totalMarketValueMarketValue"]').val(totalMarketValueMarketValue)
}

function updatePropertyAssessment(rowNumber){
    console.log('updatePropertyAssessment')
    let element = $('#tab_property-assessment .repeatable-element[data-row-number="'+rowNumber+'"]')
    if(element.length > 0) {
        let actualUse = $('#tab_land-appraisal .classification[data-row-number="'+rowNumber+'"]').val()
        let baseMarketValue = $('#tab_land-appraisal .baseMarketValue[data-row-number="'+rowNumber+'"]').val()
        baseMarketValue = formatStringToFloat(baseMarketValue)
        let element2 = $('#tab_market-value .repeatable-element[data-row-number="'+rowNumber+'"]')
        console.log(element2)
        let marketValue = 0
        if(element2.length > 0) {
            marketValue = $('#tab_market-value .marketValue[data-row-number="'+rowNumber+'"]').val()
            marketValue = formatStringToFloat(marketValue)
        }
        
        console.log(baseMarketValue)
        console.log(marketValue)

        let propertyAssessmentMarketValue = baseMarketValue + marketValue
        $('#tab_property-assessment .actualUse[data-row-number="'+rowNumber+'"]').val(actualUse)
        $('#tab_property-assessment .assessmentLevel[data-row-number="'+rowNumber+'"]').val(actualUse)
        $('#tab_property-assessment .marketValue[data-row-number="'+rowNumber+'"]').val(propertyAssessmentMarketValue)
        propertyAssessmentComputation(rowNumber)
    }
    else {
        addItemInPropertyAssessment()
    }
}

function addItemInPropertyAssessment() {
    $('#tab_property-assessment button.add-repeatable-element-button').trigger('click')
}

function propertyAssessmentComputation(rowNumber){
    let marketValue = $('#tab_property-assessment .marketValue[data-row-number="'+rowNumber+'"]').val()
    let assessmentLevel = $('#tab_property-assessment .assessmentLevel[data-row-number="'+rowNumber+'"] option:selected').text()
    let assessmentValue = 0

    marketValue = formatStringToFloat(marketValue)
    assessmentLevel = formatStringToInteger(assessmentLevel)

    assessmentValue = (marketValue / 100) * assessmentLevel
    $('#tab_property-assessment .assessmentValue[data-row-number="'+rowNumber+'"]').val(assessmentValue)

    totalPropertyAssessmentAssessmentValue()
    totalPropertyAssessmentMarketValue()
}

function totalPropertyAssessmentAssessmentValue(){
    let totalPropertyAssessmentAssessmentValue = 0
    $('#tab_property-assessment .assessmentValue').each(function(){
        let assessmentValue = $(this).val()

        assessmentValue = formatStringToFloat(assessmentValue)

        totalPropertyAssessmentAssessmentValue = totalPropertyAssessmentAssessmentValue + assessmentValue
    })

    $('input[name="totalPropertyAssessmentAssessmentValue"]').val(totalPropertyAssessmentAssessmentValue)
}

function totalPropertyAssessmentMarketValue(){
    let totalPropertyAssessmentMarketValue = 0
    $('#tab_property-assessment .marketValue').each(function(){
        let marketValue = $(this).val()

        marketValue = formatStringToFloat(marketValue)

        totalPropertyAssessmentMarketValue = totalPropertyAssessmentMarketValue + marketValue
    })

    $('input[name="totalPropertyAssessmentMarketValue"]').val(totalPropertyAssessmentMarketValue)
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