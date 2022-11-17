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
    $('.flooringOptionItem .flooringCheckbox_1 input[name="floor1_flooring"]').on('click', function(){
        $('.flooringOtherWrapper .flooringOther_1 input[type="text"]').val('')
        $('.flooringOtherWrapper .flooringOther_1').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.flooringOtherWrapper .flooringOther_1').removeClass('hidden')
        }
    })

    $('.flooringOptionItem .flooringCheckbox_2 input[name="floor2_flooring"]').on('click', function(){
        $('.flooringOtherWrapper .flooringOther_2 input[type="text"]').val('')
        $('.flooringOtherWrapper .flooringOther_2').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.flooringOtherWrapper .flooringOther_2').removeClass('hidden')
        }
    })

    $('.flooringOptionItem .flooringCheckbox_3 input[name="floor3_flooring"]').on('click', function(){
        $('.flooringOtherWrapper .flooringOther_3 input[type="text"]').val('')
        $('.flooringOtherWrapper .flooringOther_3').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.flooringOtherWrapper .flooringOther_3').removeClass('hidden')
        }
    })

    $('.flooringOptionItem .flooringCheckbox_4 input[name="floor4_flooring"]').on('click', function(){
        $('.flooringOtherWrapper .flooringOther_4 input[type="text"]').val('')
        $('.flooringOtherWrapper .flooringOther_4').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.flooringOtherWrapper .flooringOther_4').removeClass('hidden')
        }
    })

    //Walling
    $('.wallingOptionItem .wallingCheckbox_1 input[name="floor1_walling"]').on('click', function(){
        $('.wallingOtherWrapper .wallingOther_1 input[type="text"]').val('')
        $('.wallingOtherWrapper .wallingOther_1').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.wallingOtherWrapper .wallingOther_1').removeClass('hidden')
        }
    })

    $('.wallingOptionItem .wallingCheckbox_2 input[name="floor2_walling"]').on('click', function(){
        $('.wallingOtherWrapper .wallingOther_2 input[type="text"]').val('')
        $('.wallingOtherWrapper .wallingOther_2').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.wallingOtherWrapper .wallingOther_2').removeClass('hidden')
        }
    })

    $('.wallingOptionItem .wallingCheckbox_3 input[name="floor3_walling"]').on('click', function(){
        $('.wallingOtherWrapper .wallingOther_3 input[type="text"]').val('')
        $('.wallingOtherWrapper .wallingOther_3').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.wallingOtherWrapper .wallingOther_3').removeClass('hidden')
        }
    })

    $('.wallingOptionItem .wallingCheckbox_4 input[name="floor4_walling"]').on('click', function(){
        $('.wallingOtherWrapper .wallingOther_4 input[type="text"]').val('')
        $('.wallingOtherWrapper .wallingOther_4').addClass('hidden')
        if($(this).attr('data-name') === 'Others'){
            $('.wallingOtherWrapper .wallingOther_4').removeClass('hidden')
        }
    })
})