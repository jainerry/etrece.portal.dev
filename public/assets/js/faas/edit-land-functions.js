$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //primary and secondary owner validations
    $('#tab_main-information select[name="primaryOwnerId"]').on('change', function(){
        primaryAndSecondaryOwnersValidation()
    })

    $('#tab_main-information select[name="land_owner[]"]').on('change', function(){
        primaryAndSecondaryOwnersValidation()
    })

})

function primaryAndSecondaryOwnersValidation(){
    console.log('primaryAndSecondaryOwnersValidation')
    let primaryOwner = $('#tab_main-information select[name="primaryOwnerId"]').val()
    let secondaryOwners = $('#tab_main-information select[name="land_owner[]"]').val()

    console.log(primaryOwner)
    console.log(secondaryOwners)

    if($.inArray(primaryOwner,secondaryOwners) !== -1) {
        msg = 'Please check your inputs. The selected primary owner should not be selected as a sec'
    }
}