let url = window.location.href
let pathname = new URL(url).pathname
let paths = pathname.split('/')

$(function () {
    
    if(paths[3] !== 'create' && paths[3] !== undefined) {
        getDetails(paths[3])
    }

    //Roof
    $('.structural-roof-checklist .form-check input[type="radio"]').on('click', function(){
        let selectedText = $(this).parent().find('label').text()
        if(selectedText === 'Others'){
            $('input[name="other_roof"]').val('')
            $('.other_roof').removeClass('hidden')
        }
        else {
            $('input[name="other_roof"]').val('')
            $('.other_roof').addClass('hidden')
        }
    })

    //Flooring
    $('.flooringOptionItem .flooringCheckbox_1 input[name="floor1_flooring_radio"]').on('click', function(){
        $('input[name="floor1_flooring"]').val($(this).val())
        $('.flooringOtherWrapper .flooringOther_1 input[type="text"]').val('')
        $('.flooringOtherWrapper .flooringOther_1_wrapper').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.flooringOtherWrapper .flooringOther_1_wrapper').removeClass('hidden')
        }
    })

    $('.flooringOptionItem .flooringCheckbox_2 input[name="floor2_flooring_radio"]').on('click', function(){
        $('input[name="floor2_flooring"]').val($(this).val())
        $('.flooringOtherWrapper .flooringOther_2 input[type="text"]').val('')
        $('.flooringOtherWrapper .flooringOther_2_wrapper').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.flooringOtherWrapper .flooringOther_2_wrapper').removeClass('hidden')
        }
    })

    $('.flooringOptionItem .flooringCheckbox_3 input[name="floor3_flooring_radio"]').on('click', function(){
        $('input[name="floor3_flooring"]').val($(this).val())
        $('.flooringOtherWrapper .flooringOther_3 input[type="text"]').val('')
        $('.flooringOtherWrapper .flooringOther_3_wrapper').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.flooringOtherWrapper .flooringOther_3_wrapper').removeClass('hidden')
        }
    })

    $('.flooringOptionItem .flooringCheckbox_4 input[name="floor4_flooring_radio"]').on('click', function(){
        $('input[name="floor4_flooring"]').val($(this).val())
        $('.flooringOtherWrapper .flooringOther_4 input[type="text"]').val('')
        $('.flooringOtherWrapper .flooringOther_4_wrapper').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.flooringOtherWrapper .flooringOther_4_wrapper').removeClass('hidden')
        }
    })

    $('.flooringOther_1 .flooringOther_1_wrapper input[name="floor1_otherFlooring_temp"]').on('change', function(){
        let floor1_otherFlooring_temp = $(this).val()
        $('input[name="floor1_otherFlooring"]').val(floor1_otherFlooring_temp)
    })

    $('.flooringOther_2 .flooringOther_2_wrapper input[name="floor2_otherFlooring_temp"]').on('change', function(){
        let floor2_otherFlooring_temp = $(this).val()
        $('input[name="floor2_otherFlooring"]').val(floor2_otherFlooring_temp)
    })

    $('.flooringOther_3 .flooringOther_3_wrapper input[name="floor3_otherFlooring_temp"]').on('change', function(){
        let floor3_otherFlooring_temp = $(this).val()
        $('input[name="floor3_otherFlooring"]').val(floor3_otherFlooring_temp)
    })

    $('.flooringOther_4 .flooringOther_4_wrapper input[name="floor4_otherFlooring_temp"]').on('change', function(){
        let floor4_otherFlooring_temp = $(this).val()
        $('input[name="floor4_otherFlooring"]').val(floor4_otherFlooring_temp)
    })

    //Walling
    $('.wallingOptionItem .wallingCheckbox_1 input[name="floor1_walling_radio"]').on('click', function(){
        $('input[name="floor1_walling"]').val($(this).val())
        $('.wallingOtherWrapper .wallingOther_1 input[type="text"]').val('')
        $('.wallingOtherWrapper .wallingOther_1_wrapper').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.wallingOtherWrapper .wallingOther_1_wrapper').removeClass('hidden')
        }
    })

    $('.wallingOptionItem .wallingCheckbox_2 input[name="floor2_walling_radio"]').on('click', function(){
        $('input[name="floor2_walling"]').val($(this).val())
        $('.wallingOtherWrapper .wallingOther_2 input[type="text"]').val('')
        $('.wallingOtherWrapper .wallingOther_2_wrapper').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.wallingOtherWrapper .wallingOther_2_wrapper').removeClass('hidden')
        }
    })

    $('.wallingOptionItem .wallingCheckbox_3 input[name="floor3_walling_radio"]').on('click', function(){
        $('input[name="floor3_walling"]').val($(this).val())
        $('.wallingOtherWrapper .wallingOther_3 input[type="text"]').val('')
        $('.wallingOtherWrapper .wallingOther_3_wrapper').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.wallingOtherWrapper .wallingOther_3_wrapper').removeClass('hidden')
        }
    })

    $('.wallingOptionItem .wallingCheckbox_4 input[name="floor4_walling_radio"]').on('click', function(){
        $('input[name="floor4_walling"]').val($(this).val())
        $('.wallingOtherWrapper .wallingOther_4 input[type="text"]').val('')
        $('.wallingOtherWrapper .wallingOther_4_wrapper').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.wallingOtherWrapper .wallingOther_4_wrapper').removeClass('hidden')
        }
    })

    $('.wallingOther_1 .wallingOther_1_wrapper input[name="floor1_otherWalling_temp"]').on('change', function(){
        let floor1_otherWalling_temp = $(this).val()
        $('input[name="floor1_otherWalling"]').val(floor1_otherWalling_temp)
    })

    $('.wallingOther_2 .wallingOther_2_wrapper input[name="floor2_otherWalling_temp"]').on('change', function(){
        let floor2_otherWalling_temp = $(this).val()
        $('input[name="floor2_otherWalling"]').val(floor2_otherWalling_temp)
    })

    $('.wallingOther_3 .wallingOther_3_wrapper input[name="floor3_otherWalling_temp"]').on('change', function(){
        let floor3_otherWalling_temp = $(this).val()
        $('input[name="floor3_otherWalling"]').val(floor3_otherWalling_temp)
    })

    $('.wallingOther_4 .wallingOther_4_wrapper input[name="floor4_otherWalling_temp"]').on('change', function(){
        let floor4_otherWalling_temp = $(this).val()
        $('input[name="floor4_otherWalling"]').val(floor4_otherWalling_temp)
    })

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

})

function getDetails(id) {
    //roof
    let roof = $('input[name="roof"]').val()
    $('.structural-roof-checklist .form-check input[value="'+roof+'"]').prop('checked',true)
    if(roof === 'e9c99a01-e0bb-41ef-bf2a-d1cd01ef64a7') {
        $('.other_roof').removeClass('hidden')
    }

    //flooring
    let floor1_flooring = $('input[name="floor1_flooring"]').val()
    let floor1_otherFlooring = $('input[name="floor1_otherFlooring"]').val()
    $('.flooringOptionItem .flooringCheckbox_1 input[value="'+floor1_flooring+'"]').prop('checked',true)
    if(floor1_flooring === '972f792f-a70e-4b1c-93ba-9c5f9afb5f0b') {
        $('.flooringOther_1 .flooringOther_1_wrapper input[name="floor1_otherFlooring_temp"]').val(floor1_otherFlooring)
        $('.flooringOther_1 .flooringOther_1_wrapper').removeClass('hidden')
    }

    let floor2_flooring = $('input[name="floor2_flooring"]').val()
    let floor2_otherFlooring = $('input[name="floor2_otherFlooring"]').val()
    $('.flooringOptionItem .flooringCheckbox_2 input[value="'+floor2_flooring+'"]').prop('checked',true)
    if(floor2_flooring === '972f792f-a70e-4b1c-93ba-9c5f9afb5f0b') {
        $('.flooringOther_2 .flooringOther_2_wrapper input[name="floor2_otherFlooring_temp"]').val(floor2_otherFlooring)
        $('.flooringOther_2 .flooringOther_2_wrapper').removeClass('hidden')
    }

    let floor3_flooring = $('input[name="floor3_flooring"]').val()
    let floor3_otherFlooring = $('input[name="floor3_otherFlooring"]').val()
    $('.flooringOptionItem .flooringCheckbox_3 input[value="'+floor3_flooring+'"]').prop('checked',true)
    if(floor3_flooring === '972f792f-a70e-4b1c-93ba-9c5f9afb5f0b') {
        $('.flooringOther_3 .flooringOther_3_wrapper input[name="floor3_otherFlooring_temp"]').val(floor3_otherFlooring)
        $('.flooringOther_3 .flooringOther_3_wrapper').removeClass('hidden')
    }

    let floor4_flooring = $('input[name="floor4_flooring"]').val()
    let floor4_otherFlooring = $('input[name="floor4_otherFlooring"]').val()
    $('.flooringOptionItem .flooringCheckbox_4 input[value="'+floor4_flooring+'"]').prop('checked',true)
    if(floor4_flooring === '972f792f-a70e-4b1c-93ba-9c5f9afb5f0b') {
        $('.flooringOther_4 .flooringOther_4_wrapper input[name="floor4_otherFlooring_temp"]').val(floor4_otherFlooring)
        $('.flooringOther_4 .flooringOther_4_wrapper').removeClass('hidden')
    }

    //walling
    let floor1_walling = $('input[name="floor1_walling"]').val()
    let floor1_otherWalling = $('input[name="floor1_otherWalling"]').val()
    $('.wallingOptionItem .wallingCheckbox_1 input[value="'+floor1_walling+'"]').prop('checked',true)
    if(floor1_walling === '5e2d4ec8-09a7-4693-a0cb-a3c27c95a1b3') {
        $('.wallingOther_1 .wallingOther_1_wrapper input[name="floor1_otherWalling_temp"]').val(floor1_otherWalling)
        $('.wallingOther_1 .wallingOther_1_wrapper').removeClass('hidden')
    }

    let floor2_walling = $('input[name="floor2_walling"]').val()
    let floor2_otherWalling = $('input[name="floor2_otherWalling"]').val()
    $('.wallingOptionItem .wallingCheckbox_2 input[value="'+floor2_walling+'"]').prop('checked',true)
    if(floor2_walling === '5e2d4ec8-09a7-4693-a0cb-a3c27c95a1b3') {
        $('.wallingOther_2 .wallingOther_2_wrapper input[name="floor2_otherWalling_temp"]').val(floor2_otherWalling)
        $('.wallingOther_2 .wallingOther_2_wrapper').removeClass('hidden')
    }

    let floor3_walling = $('input[name="floor3_walling"]').val()
    let floor3_otherWalling = $('input[name="floor3_otherWalling"]').val()
    $('.wallingOptionItem .wallingCheckbox_3 input[value="'+floor3_walling+'"]').prop('checked',true)
    if(floor3_walling === '5e2d4ec8-09a7-4693-a0cb-a3c27c95a1b3') {
        $('.wallingOther_3 .wallingOther_3_wrapper input[name="floor3_otherWalling_temp"]').val(floor3_otherWalling)
        $('.wallingOther_3 .wallingOther_3_wrapper').removeClass('hidden')
    }

    let floor4_walling = $('input[name="floor4_walling"]').val()
    let floor4_otherWalling = $('input[name="floor4_otherWalling"]').val()
    $('.wallingOptionItem .wallingCheckbox_4 input[value="'+floor4_walling+'"]').prop('checked',true)
    if(floor4_walling === '5e2d4ec8-09a7-4693-a0cb-a3c27c95a1b3') {
        $('.wallingOther_4 .wallingOther_4_wrapper input[name="floor4_otherWalling_temp"]').val(floor4_otherWalling)
        $('.wallingOther_4 .wallingOther_4_wrapper').removeClass('hidden')
    }

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
    
}

function propertyAppraisalComputation(){
    let unitConstructionCost_temp = $('input[name="unitConstructionCost_temp"]').val()
    $('input[name="unitConstructionCost"]').val(unitConstructionCost_temp)
    if(unitConstructionCost_temp === '') {
        unitConstructionCost_temp = 0
    }
    else {
        unitConstructionCost_temp = parseFloat(unitConstructionCost_temp.replaceAll(',',''))
    }

    let totalFloorArea = $('input[name="totalFloorArea"]').val()
    if(totalFloorArea === '') {
        totalFloorArea = 0
    }
    else {
        totalFloorArea = parseFloat(totalFloorArea.replaceAll(',',''))
    }
    
    let unitConstructionSubTotal_temp = unitConstructionCost_temp * totalFloorArea
    $('input[name="unitConstructionSubTotal_temp"]').val(unitConstructionSubTotal_temp)
    $('input[name="unitConstructionSubTotal"]').val(unitConstructionSubTotal_temp)
    
    let costOfAdditionalItemsSubTotal_temp = $('input[name="costOfAdditionalItemsSubTotal_temp"]').val()
    if(costOfAdditionalItemsSubTotal_temp === '') {
        costOfAdditionalItemsSubTotal_temp = 0
    }
    else {
        costOfAdditionalItemsSubTotal_temp = parseFloat(costOfAdditionalItemsSubTotal_temp.replaceAll(',',''))
    }

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

    if(marketValue === '') {
        marketValue = 0
    }
    else {
        marketValue = parseFloat(marketValue.replaceAll(',',''))
    }
    
    assessedValue = (marketValue / 100) * assessmentLevel
    $('input[name="propertyAssessment[0][assessedValue]"]').val(assessedValue)
}