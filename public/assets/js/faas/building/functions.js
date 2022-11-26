let url = window.location.href
let pathname = new URL(url).pathname
let paths = pathname.split('/')

$(function () {
    
    if(paths[3] !== 'create' && paths[3] !== undefined) {
        getDetails(paths[3])
    }

    setFloorsArea()
    setFloorsFlooring()
    setFloorsWalling()

    /*Property Appraisal*/
    //unitConstructionCost
    $('input[name="unitConstructionCost_temp"]').on('keyup', function(){
        propertyAppraisalComputation()
    })

    //costOfAdditionalItemsSubTotal
    $('input[name="costOfAdditionalItemsSubTotal_temp"]').on('keyup', function(){
        propertyAppraisalComputation()
    })

    //kind_of_building_id
    $('select[name="kind_of_building_id"]').on('change', function(){
        let kind_of_building_id = $(this).val()
        $('select[name="propertyAssessment[0][actualUse]"]').val(kind_of_building_id).change()
        $('select[name="propertyAssessment[0][assessmentLevel]"]').val(kind_of_building_id).change()
        propertyAssessmentComputation()
    })

    //totalFloorArea
    $('input[name="totalFloorArea"]').on('keyup', function(){
        propertyAppraisalComputation()
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

    $('.repeatable-group[bp-field-name="floorsArea"] button.add-repeatable-element-button').on('click', function(){
        setFloorsArea()
    })

    $('.repeatable-group[bp-field-name="flooring"] button.add-repeatable-element-button').on('click', function(){
        setFloorsFlooring()
        floorFlooringActions()
    })

    $('.repeatable-group[bp-field-name="walling"] button.add-repeatable-element-button').on('click', function(){
        setFloorsWalling()
        floorWallingActions()
    })

    $('#tab_structural-characteristic select[name="roof"]').on('change', function(){
        if($(this).val() === '7d066266-3b91-4174-b20b-857e986451fa') {
            $('#tab_structural-characteristic .other_roof input[name="other_roof"]').val('')
            $('#tab_structural-characteristic .other_roof').removeClass('hidden')
        }
        else {
            $('#tab_structural-characteristic .other_roof input[name="other_roof"]').val('')
            $('#tab_structural-characteristic .other_roof').addClass('hidden')
        }
    })

    floorFlooringActions()
    floorWallingActions()

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

    $('#tab_building-location select[name="barangay_id"]').on('change', function(){
        $('#tab_building-location select[name="barangay_code"]').val($(this).val())
        $('#tab_building-location input[name="barangay_code_text"]').val($('#tab_building-location select[name="barangay_code"] option:selected').text())
    })

    $('#tab_general-description select[name="kind_of_building_id"]').on('change', function(){
        $('#tab_general-description select[name="kind_of_building_code"]').val($(this).val())
        $('#tab_general-description input[name="kind_of_building_code_text"]').val($('#tab_general-description select[name="kind_of_building_code"] option:selected').text())
    })

})

function getDetails(id) {
    //roof
    let roof = $('#tab_structural-characteristic select[name="roof"]').val()
    if(roof === '7d066266-3b91-4174-b20b-857e986451fa') {
        $('.other_roof').removeClass('hidden')
    }

    //flooring
    $('.repeatable-group[bp-field-name="flooring"] .repeatable-element .type select').each(function(){
        let dataRowNumber = $(this).attr('data-row-number')
        let ctr = parseInt(dataRowNumber) - 1
        let value = $(this).val()
        if(value === '9f7bcd81-5fbc-4fd9-8cda-ee24cd0b6edb') {
            $('.repeatable-group[bp-field-name="flooring"] .repeatable-element[data-row-number="'+dataRowNumber+'"] .others').removeClass('hidden')
        }
        else {
            $('.repeatable-group[bp-field-name="flooring"] .repeatable-element[data-row-number="'+dataRowNumber+'"] .others').addClass('hidden')
        }
    })

    //walling
    $('.repeatable-group[bp-field-name="walling"] .repeatable-element .type select').each(function(){
        let dataRowNumber = $(this).attr('data-row-number')
        let ctr = parseInt(dataRowNumber) - 1
        let value = $(this).val()
        if(value === '629237bb-c562-43ac-a94a-414dea6e2bcc') {
            $('.repeatable-group[bp-field-name="walling"] .repeatable-element[data-row-number="'+dataRowNumber+'"] .others').removeClass('hidden')
        }
        else {
            $('.repeatable-group[bp-field-name="walling"] .repeatable-element[data-row-number="'+dataRowNumber+'"] .others').addClass('hidden')
        }
    })
    

    //unitConstructionCost
    let unitConstructionCost = $('input[name="unitConstructionCost"]').val()
    $('input[name="unitConstructionCost_temp"]').val(unitConstructionCost)

    //unitConstructionSubTotal
    let unitConstructionSubTotal = $('input[name="unitConstructionSubTotal"]').val()
    $('input[name="unitConstructionSubTotal_temp"]').val(unitConstructionSubTotal)

    //costOfAdditionalItemsSubTotal
    let costOfAdditionalItemsSubTotal = $('input[name="costOfAdditionalItemsSubTotal"]').val()
    $('input[name="costOfAdditionalItemsSubTotal_temp"]').val(costOfAdditionalItemsSubTotal)

    //totalConstructionCost
    let totalConstructionCost = $('input[name="totalConstructionCost"]').val()
    $('input[name="totalConstructionCost_temp"]').val(totalConstructionCost)

    let isApproved = $('.tab-container #tab_property-assessment input[name="isApproved"]').val()
    if(isApproved === '1') {
        $('.tab-container #tab_property-assessment .approve_items').removeClass('hidden')
    }
    else {
        $('.tab-container #tab_property-assessment .approve_items').addClass('hidden')
    }
    
    let assessmentType = $('#tab_property-assessment select[name="assessmentType"]').val()
    if(assessmentType === 'Exempt') {
        $('#tab_property-assessment .ifAssessmentTypeIsExempt').removeClass('hidden')
    }
    else {
        $('#tab_property-assessment .ifAssessmentTypeIsExempt').addClass('hidden')
    }

    let assessmentEffectivity = $('#tab_property-assessment select[name="assessmentEffectivity"]').val()
    let assessmentEffectivityValue = $('#tab_property-assessment input[name="assessmentEffectivityValue"]').val()
    console.log(assessmentEffectivityValue)
    console.log(assessmentEffectivity)
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

function propertyAppraisalComputation(){
    let unitConstructionCost_temp = $('input[name="unitConstructionCost_temp"]').val()
    $('input[name="unitConstructionCost"]').val(unitConstructionCost_temp)
    unitConstructionCost_temp = formatStringToFloat(unitConstructionCost_temp)

    let totalFloorArea = $('input[name="totalFloorArea"]').val()
    totalFloorArea = formatStringToFloat(totalFloorArea)
    
    let unitConstructionSubTotal_temp = unitConstructionCost_temp * totalFloorArea
    $('input[name="unitConstructionSubTotal_temp"]').val(unitConstructionSubTotal_temp)
    $('input[name="unitConstructionSubTotal"]').val(unitConstructionSubTotal_temp)
    
    let costOfAdditionalItemsSubTotal_temp = $('input[name="costOfAdditionalItemsSubTotal_temp"]').val()
    $('input[name="costOfAdditionalItemsSubTotal"]').val(costOfAdditionalItemsSubTotal_temp)
    costOfAdditionalItemsSubTotal_temp = formatStringToFloat(costOfAdditionalItemsSubTotal_temp)

    let totalConstructionCost_temp = unitConstructionSubTotal_temp + costOfAdditionalItemsSubTotal_temp
    $('input[name="totalConstructionCost_temp"]').val(totalConstructionCost_temp)
    $('input[name="totalConstructionCost"]').val(totalConstructionCost_temp)

    let marketValue = totalConstructionCost_temp
    $('input[name="marketValue"]').val(marketValue)
    $('input[name="propertyAssessment[0][marketValue]"]').val(marketValue)

    propertyAssessmentComputation()
}

function propertyAssessmentComputation(){
    let assessmentLevel = $('select[name="propertyAssessment[0][assessmentLevel]"] option:selected').text()
    let marketValue = $('input[name="marketValue"]').val()
    let assessedValue = 0

    assessmentLevel = parseFloat(assessmentLevel.replaceAll('%',''))
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

function setFloorsArea(){
    $('input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
    $('input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
    $('.repeatable-group[bp-field-name="floorsArea"] .repeatable-element').each(function(){
        let dataRowNumber = $(this).attr('data-row-number')
        let ctr = parseInt(dataRowNumber) - 1
        $('input[name="floorsArea['+ctr+'][floorNo_fake]"]').val('Floor '+dataRowNumber)
        $('input[name="floorsArea['+ctr+'][floorNo]"]').val(dataRowNumber)
    })
}

function setFloorsFlooring(){
    $('.repeatable-group[bp-field-name="flooring"] .repeatable-element').each(function(){
        let dataRowNumber = $(this).attr('data-row-number')
        let ctr = parseInt(dataRowNumber) - 1
        $('input[name="flooring['+ctr+'][floorNo_fake]"]').val('Floor '+dataRowNumber)
        $('input[name="flooring['+ctr+'][floorNo]"]').val(dataRowNumber)
    })
}

function setFloorsWalling(){
    $('.repeatable-group[bp-field-name="walling"] .repeatable-element').each(function(){
        let dataRowNumber = $(this).attr('data-row-number')
        let ctr = parseInt(dataRowNumber) - 1
        $('input[name="walling['+ctr+'][floorNo_fake]"]').val('Floor '+dataRowNumber)
        $('input[name="walling['+ctr+'][floorNo]"]').val(dataRowNumber)
    })
}

function floorFlooringActions(){
    $('.repeatable-group[bp-field-name="flooring"] .repeatable-element .type select').on('change', function(){
        let dataRowNumber = $(this).attr('data-row-number')
        let ctr = parseInt(dataRowNumber) - 1
        let value = $(this).val()
        if(value === '9f7bcd81-5fbc-4fd9-8cda-ee24cd0b6edb') {
            $('.repeatable-group[bp-field-name="flooring"] input[name="flooring['+ctr+'][others]"]').val('')
            $('.repeatable-group[bp-field-name="flooring"] .repeatable-element[data-row-number="'+dataRowNumber+'"] .others').removeClass('hidden')
        }
        else {
            $('.repeatable-group[bp-field-name="flooring"] input[name="flooring['+ctr+'][others]"]').val('')
            $('.repeatable-group[bp-field-name="flooring"] .repeatable-element[data-row-number="'+dataRowNumber+'"] .others').addClass('hidden')
        }
    })
}

function floorWallingActions(){
    $('.repeatable-group[bp-field-name="walling"] .repeatable-element .type select').on('change', function(){
        let dataRowNumber = $(this).attr('data-row-number')
        let ctr = parseInt(dataRowNumber) - 1
        let value = $(this).val()
        if(value === '629237bb-c562-43ac-a94a-414dea6e2bcc') {
            $('.repeatable-group[bp-field-name="walling"] input[name="walling['+ctr+'][others]"]').val('')
            $('.repeatable-group[bp-field-name="walling"] .repeatable-element[data-row-number="'+dataRowNumber+'"] .others').removeClass('hidden')
        }
        else {
            $('.repeatable-group[bp-field-name="walling"] input[name="walling['+ctr+'][others]"]').val('')
            $('.repeatable-group[bp-field-name="walling"] .repeatable-element[data-row-number="'+dataRowNumber+'"] .others').addClass('hidden')
        }
    })
}