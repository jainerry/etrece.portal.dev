$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Land Profile: select on change action
    $('#tab_main-information select[name="land_profile_id"]').on('change', function(){
        getLandProfileDetails($(this).val())
    })

    //Floor/s Area: Set Floor Fake values
    $('.repeatable-group[bp-field-name="floorsArea"] button.add-repeatable-element-button').addClass('hidden')
    $('.repeatable-group[bp-field-name="flooring"] button.add-repeatable-element-button').addClass('hidden')
    $('.repeatable-group[bp-field-name="walling"] button.add-repeatable-element-button').addClass('hidden')

    //No. of Storeys: input on change action
    $('#tab_general-description input[name="no_of_storeys"]').on('change', function(){
        setFloorsInputs($(this).val())
    })

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
                console.log(data)
                $('#tab_general-description input[name="totalFloorArea"]').val(data.totalArea)
            }
        }
    })
}

function setFloorsInputs(counter){
    let clicksCtr = parseInt(counter) - 1
    for (let index = 1; index <= clicksCtr; index++) {
        $('.repeatable-group[bp-field-name="floorsArea"] button.add-repeatable-element-button').trigger('click')
        $('.repeatable-group[bp-field-name="flooring"] button.add-repeatable-element-button').trigger('click')
        $('.repeatable-group[bp-field-name="walling"] button.add-repeatable-element-button').trigger('click')
    }
}