let url = window.location.href
let protocol = new URL(url).protocol
let hostname = new URL(url).hostname

$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Floor/s Area: Set Floor Fake values
    $('.repeatable-group[bp-field-name="floorsArea"] button.add-repeatable-element-button').addClass('hidden')
    $('.repeatable-group[bp-field-name="flooring"] button.add-repeatable-element-button').addClass('hidden')
    $('.repeatable-group[bp-field-name="walling"] button.add-repeatable-element-button').addClass('hidden')
    $('.repeatable-group[bp-field-name="floorsArea"] .repeatable-element').remove()
    $('.repeatable-group[bp-field-name="flooring"] .repeatable-element').remove()
    $('.repeatable-group[bp-field-name="walling"] .repeatable-element').remove()

    $('#tab_general-description input[name="no_of_storeys"]').val('1')
    setFloorsInputs($('#tab_general-description input[name="no_of_storeys"]').val())

    //Land Profile: select on change action
    $('#tab_main-information select[name="landProfileId"]').on('change', function(){
        getLandProfileDetails($(this).val())
        $('#tab_main-information .selectedLandProfile').html('')
        $('#tab_main-information .selectedLandProfileWrapper').addClass('hidden')
    })

    //No. of Storeys: input on change action
    $('#tab_general-description input[name="no_of_storeys"]').on('change', function(){
        setFloorsInputs($(this).val())
    })

    floorAreaAction()

    $('#tab_structural-characteristic select[name="roof"]').on('change', function(){
        let roof = $(this).val()
        if(roof === '7d066266-3b91-4174-b20b-857e986451fa') {
            $('#tab_structural-characteristic input[name="other_roof"]').val('')
            $('#tab_structural-characteristic .other_roof ').removeClass('hidden')
        }
        else {
            $('#tab_structural-characteristic input[name="other_roof"]').val('')
            $('#tab_structural-characteristic .other_roof ').addClass('hidden')
        }
    })

    flooringAction()
    wallingAction()

})

function getLandProfileDetails(id){
    $.ajax({
        url: '/admin/api/faas-land/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            if(data.length > 0) {
                data = data[0]
                let landProfileUrl = '<a href="'+protocol+'//'+hostname+'/admin/faas-land/'+data.id+'/edit'+'" target="_blank">View Selected Land Profile</a>'
                $('#tab_main-information .selectedLandProfile').html(landProfileUrl)
                $('#tab_main-information .selectedLandProfileWrapper').removeClass('hidden')
            }
        }
    })
}

function setFloorsInputs(numOfStoreys){
    $('.repeatable-group[bp-field-name="floorsArea"] .repeatable-element').remove()
    $('.repeatable-group[bp-field-name="flooring"] .repeatable-element').remove()
    $('.repeatable-group[bp-field-name="walling"] .repeatable-element').remove()
    $('#tab_general-description input[name="totalFloorArea"]').val('0')
    numOfStoreys = parseInt(numOfStoreys)
    if(numOfStoreys > 0) {
        let floorsArea = $('.repeatable-group[bp-field-name="floorsArea"] .repeatable-element')
        let floorsAreaLen = floorsArea.length
        floorsAreaLen = parseInt(floorsAreaLen)

        let clicksCtr = 0
        if(numOfStoreys > floorsAreaLen) {
            clicksCtr = numOfStoreys - floorsAreaLen
            for (let index = 1; index <= clicksCtr; index++) {
                $('.repeatable-group[bp-field-name="floorsArea"] button.add-repeatable-element-button').trigger('click')
                $('.repeatable-group[bp-field-name="flooring"] button.add-repeatable-element-button').trigger('click')
                $('.repeatable-group[bp-field-name="walling"] button.add-repeatable-element-button').trigger('click')

                $('#tab_general-description input.floorNo_fake[data-row-number="'+index+'"]').val('Floor '+index)
                $('#tab_general-description input.floorNo[data-row-number="'+index+'"]').val(index)

                $('#tab_structural-characteristic div[data-repeatable-holder="flooring"] input.floorNo_fake[data-row-number="'+index+'"]').val('Floor '+index)
                $('#tab_structural-characteristic div[data-repeatable-holder="flooring"] input.floorNo[data-row-number="'+index+'"]').val(index)

                $('#tab_structural-characteristic div[data-repeatable-holder="walling"] input.floorNo_fake[data-row-number="'+index+'"]').val('Floor '+index)
                $('#tab_structural-characteristic div[data-repeatable-holder="walling"] input.floorNo[data-row-number="'+index+'"]').val(index)

                $('#tab_general-description input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
                $('#tab_general-description input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
            }

            floorAreaAction()
            flooringAction()
            wallingAction()
        }
    }
    else {
        let title = '<i class="la la-exclamation-triangle"></i> Warning Alert'
        let msg = 'The number of storeys must be greater than 0.'
        $('.alertMessageModal .modal-title').html(title)
        $('.alertMessageModal .modal-body').html(msg)
        $('.alertMessageModal').modal('show');
    }
}

function floorAreaAction(){
    $('#tab_general-description input.area').on('change', function(){
        let totalArea = 0
        $('#tab_general-description input.area').each(function(){
            let area = $(this).val()
            area = formatStringToFloat(area)
            totalArea += area
        })
        $('#tab_general-description input[name="totalFloorArea"]').val(totalArea)
    })
}

function flooringAction(){
    $('#tab_structural-characteristic div[data-repeatable-holder="flooring"] select.type').on('change', function(){
        let type = $(this).val()
        let dataRowNumber = $(this).attr('data-row-number')
        if(type === '9f7bcd81-5fbc-4fd9-8cda-ee24cd0b6edb') {
            $('#tab_structural-characteristic div[data-repeatable-holder="flooring"] input.others[data-row-number="'+dataRowNumber+'"]').val('')
            $('#tab_structural-characteristic div[data-repeatable-holder="flooring"] .repeatable-element[data-row-number="'+dataRowNumber+'"] .others').removeClass('hidden')
        }
        else {
            $('#tab_structural-characteristic div[data-repeatable-holder="flooring"] input.others[data-row-number="'+dataRowNumber+'"]').val('')
            $('#tab_structural-characteristic div[data-repeatable-holder="flooring"] .repeatable-element[data-row-number="'+dataRowNumber+'"] .others').addClass('hidden')
        }
    })
}

function wallingAction(){
    $('#tab_structural-characteristic div[data-repeatable-holder="walling"] select.type').on('change', function(){
        let type = $(this).val()
        let dataRowNumber = $(this).attr('data-row-number')
        if(type === '629237bb-c562-43ac-a94a-414dea6e2bcc') {
            $('#tab_structural-characteristic div[data-repeatable-holder="walling"] input.others[data-row-number="'+dataRowNumber+'"]').val('')
            $('#tab_structural-characteristic div[data-repeatable-holder="walling"] .repeatable-element[data-row-number="'+dataRowNumber+'"] .others').removeClass('hidden')
        }
        else {
            $('#tab_structural-characteristic div[data-repeatable-holder="walling"] input.others[data-row-number="'+dataRowNumber+'"]').val('')
            $('#tab_structural-characteristic div[data-repeatable-holder="walling"] .repeatable-element[data-row-number="'+dataRowNumber+'"] .others').addClass('hidden')
        }
    })
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