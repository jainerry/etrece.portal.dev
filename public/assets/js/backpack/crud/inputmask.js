$(function(){
    $('input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
    $('input.text_input_mask_telephone').inputmask('(999)-999-9999')
    $('input.text_input_mask_cellphone').inputmask('(+639)-999-9999-99')
    $('input.text_input_mask_tin').inputmask('999-999-999-999')
    $('input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
})