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
                $.each(data.line_of_business, function(i, line_of_business) {
                    getLineOfBusinessesCategories(line_of_business.particulars)
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
                data = data[0]
                console.log(data)
                //fetched all business tax fees with business category id
            }
        }
    })
}