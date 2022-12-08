$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //Assessment Levels
    $('.repeatable-group[bp-field-name="assessmentLevels"] button.add-repeatable-element-button').on('click', function(){
        $('div[data-repeatable-holder="assessmentLevels"] .repeatable-element input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
        $('div[data-repeatable-holder="assessmentLevels"] .repeatable-element input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
    })

})