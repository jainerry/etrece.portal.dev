$(function () {
    //Floor Area
    $('input.nth-floor-area').on('keyup', function(){
        let totalFloorArea = 0
        $('input.nth-floor-area').each(function(){
            let floorArea = parseFloat($(this).val())
            if(isNaN(floorArea)) {
                floorArea = 0
            }
            totalFloorArea = totalFloorArea + floorArea
        })
        $('input[name="totalFloorArea"]').val(totalFloorArea).change()
    })

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

    //actualUse
    $('.actualUse select').on('change', function(){
        $(this).parent().parent().find('.assessmentLevel select').val($(this).val()).change()
        let assessmentLevel = $(this).parent().parent().find('.assessmentLevel select option:selected').text()
        let marketValue = $(this).parent().parent().find('.marketValue input').val()
        let assessedValue = 0

        assessmentLevel = parseFloat(assessmentLevel.replace('%',''))
        marketValue = parseFloat(marketValue.replace(',',''))

        if(isNaN(marketValue)) {
            marketValue = 0
        }
        else {
            marketValue = parseFloat(marketValue)
        }
        
        assessedValue = (marketValue / 100) * assessmentLevel
        $(this).parent().parent().find('.assessedValue input').val(assessedValue)
    })

    //marketValue
    $('.marketValue input').on('keyup', function(){
        let assessmentLevel = $(this).parent().parent().find('.assessmentLevel select option:selected').text()
        let marketValue = $(this).val()
        let assessedValue = 0

        assessmentLevel = parseFloat(assessmentLevel.replace('%',''))
        marketValue = parseFloat(marketValue.replace(',',''))

        if(isNaN(marketValue)) {
            marketValue = 0
        }
        else {
            marketValue = parseFloat(marketValue)
        }
        
        assessedValue = (marketValue / 100) * assessmentLevel
        $(this).parent().parent().find('.assessedValue input').val(assessedValue)
    })
})