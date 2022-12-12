let url = window.location.href
let protocol = new URL(url).protocol
let hostname = new URL(url).hostname

$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    getLandProfileDetails($('#tab_main-information select[name="landProfileId"]').val())
    getBuildingProfileDetails($('#tab_main-information select[name="buildingProfileId"]').val())

    //Land Profile: select on change action
    $('#tab_main-information select[name="landProfileId"]').on('change', function(){
        getLandProfileDetails($(this).val())
        $('#tab_main-information .selectedLandProfile').html('')
        $('#tab_main-information .selectedLandProfileWrapper').addClass('hidden')
    })

    //Building Profile: select on change action
    $('#tab_main-information select[name="buildingProfileId"]').on('change', function(){
        getBuildingProfileDetails($(this).val())
        $('#tab_main-information .selectedBuildingProfile').html('')
        $('#tab_main-information .selectedBuildingProfileWrapper').addClass('hidden')
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
                let landProfileUrl = '<a href="'+protocol+'//'+hostname+'/admin/faas-land/'+data.id+'/edit'+'" target="_blank">View Selected Land Profile</a>'
                $('#tab_main-information .selectedLandProfile').html(landProfileUrl)
                $('#tab_main-information .selectedLandProfileWrapper').removeClass('hidden')
            }
        }
    })
}

function getBuildingProfileDetails(id){
    $.ajax({
        url: '/admin/api/faas-building/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            if(data.length > 0) {
                data = data[0]
                let buildingProfileUrl = '<a href="'+protocol+'//'+hostname+'/admin/building-profile/'+data.id+'/edit'+'" target="_blank">View Selected Building Profile</a>'
                $('#tab_main-information .selectedBuildingProfile').html(buildingProfileUrl)
                $('#tab_main-information .selectedBuildingProfileWrapper').removeClass('hidden')
            }
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
        return parseInt(num.replaceAll('%',''))
    }
}