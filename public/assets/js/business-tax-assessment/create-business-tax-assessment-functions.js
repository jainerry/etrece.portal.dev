$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('select[name="business_profiles_id"]').on('change', function(){
        getBusinessProfile($(this).val())
    })

})

function getBusinessProfile(id){
    $.ajax({
        url: '/admin/api/business-profile/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            if(data.length > 0) {
                data = data[0]
                $("[bp-field-name=fees_and_delinquency]").find('[data-repeatable-holder=fees_and_delinquency]').html('')
                $.each(data.line_of_business,async function(i, line_of_business) {
                    console.log(line_of_business.particulars)
                    await  getLineOfBusinessesCategories(line_of_business.particulars)
                   
                })
            }
        }
    })
}

function getLineOfBusinessesCategories(id){
    $.ajax({
        url: '/admin/api/business-profile/get-line-of-businesses-categories',
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            if(data.length > 0) {
                for(i=0; i<data.length;i++){
                    
                    $("[bp-field-name=fees_and_delinquency]").find('.add-repeatable-element-button').trigger('click')
                }
               
                //fetched all business tax fees with business category id
            }
        }
    })
}