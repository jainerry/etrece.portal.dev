$(function () {

    disableInputs()

    $('.tab-container #tab_main-information select[name="primaryOwnerId"]').on('change', function(){
        let owner = $(this).val()
        console.log(owner)
    })
})

function disableInputs() {
    $('.tab-container input').attr('disabled',true)
    $('.tab-container select').attr('disabled',true)
    $('.tab-container textarea').attr('disabled',true)
    $('#saveActions button').attr('disabled',true)

    $('.tab-container .nav .nav-item .nav-link').removeClass('active')
    $('.tab-container .tab-content .tab-pane').removeClass('active')

    $('.tab-container .nav .nav-item .nav-link').addClass('disabled')
    $('.tab-container .nav .nav-item .nav-link').attr('aria-disabled',true)

    $('.tab-container .nav .nav-item:first-child .nav-link').removeClass('disabled')
    $('.tab-container .nav .nav-item:first-child .nav-link').removeAttr('aria-disabled')

    $('.tab-container .nav .nav-item:first-child .nav-link').addClass('active')
    $('.tab-container .tab-content .tab-pane:first-child').addClass('active')

    $('.tab-container #tab_main-information input.isOwnerNonTreceResident_checkbox').removeAttr('disabled')
    $('.tab-container #tab_main-information input[name="isOwnerNonTreceResident"]').removeAttr('disabled')

    $('.tab-container #tab_main-information select[name="primaryOwnerId"]').removeAttr('disabled')
}