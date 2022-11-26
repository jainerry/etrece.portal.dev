$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    disableInputs()

    hideAddress()

    $('.tab-container #tab_main-information select[name="primary_owner"]').on('change', function(){
        let primaryOwnerId = $(this).val()
        let primaryOwner = $('.tab-container #tab_main-information div.form-group[bp-field-name="primary_owner"] .select2-selection__rendered').text()
        primaryOwner = primaryOwner.replaceAll('× ','')
        $.ajax({
            url: '/admin/api/rpt-building/check-if-primary-owner-exist',
            type: 'GET',
            dataType: 'json',
            data: {
                primaryOwnerId: primaryOwnerId
            },
            success: function (data) {
                if (data.length > 0) {
                    disableInputs()
                    let html = '<p>A property whom the primary owner is "'+primaryOwner+'" already exist. <br/>Please see information below:</p>'
                    html += '\n\
                    <div class="container propertyListWrapper">\n\
                        <div class="row propertyListHeader">\n\
                            <div class="col-md-2"><h6>Reference ID<h6></div>\n\
                            <div class="col-md-2"><h6>TD No<h6></div>\n\
                            <div class="col-md-2"><h6>ARP No<h6></div>\n\
                            <div class="col-md-4"><h6>Address<h6></div>\n\
                            <div class="col-md-1"><h6>Status<h6></div>\n\
                            <div class="col-md-1"><h6>Approved<h6></div>\n\
                        </div>\n\
                    '
                    $.each(data, function(i, value) {
                        let refID = '<a href="javascript:void(0)" onclick="fetchData(\''+value.id+'\')">'+value.refID+'</a>'
                        let TDNo = '-'
                        let ARPNo = '-'
                        let isActive = 'Active'
                        let isApproved = 'No'
                        if(value.TDNo !== null) { TDNo = value.TDNo }
                        if(value.ARPNo !== null) { ARPNo = value.ARPNo }
                        if(value.isActive !== 1) { isActive = 'Inactive' }
                        if(value.isApproved !== 0) { isApproved = 'Yes' }
                        html += '\n\
                        <div class="row propertyListItem">\n\
                            <div class="col-md-2">\n\
                                '+refID+'\n\
                            </div>\n\
                            <div class="col-md-2">\n\
                                '+TDNo+'\n\
                            </div>\n\
                            <div class="col-md-2">\n\
                                '+ARPNo+'\n\
                            </div>\n\
                            <div class="col-md-4">\n\
                                '+value.ownerAddress+'\n\
                            </div>\n\
                            <div class="col-md-1">\n\
                                '+isActive+'\n\
                            </div>\n\
                            <div class="col-md-1">\n\
                                '+isApproved+'\n\
                            </div>\n\
                        </div>'
                    });
                    html += '</div>'
                    disableInputs()
                    $('.rptModal .modal-body').html(html)
                    $('.rptModal').modal('show');
                }
                else {
                    enableInputs()
                }
            }
        })
    })

    $('.rptModal button#btnCreateNew').on('click', function(){
        enableInputs()
        $('.rptModal').modal('hide');
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

    $('.tab-container #tab_main-information select[name="primary_owner"]').removeAttr('disabled')
}

function enableInputs() {
    $('.tab-container input').removeAttr('disabled')
    $('.tab-container select').removeAttr('disabled')
    $('.tab-container textarea').removeAttr('disabled')
    $('#saveActions button').removeAttr('disabled')

    $('.tab-container .nav .nav-item .nav-link').removeClass('active')
    $('.tab-container .tab-content .tab-pane').removeClass('active')

    $('.tab-container .nav .nav-item .nav-link').removeClass('disabled')
    $('.tab-container .nav .nav-item .nav-link').removeAttr('aria-disabled')

    $('.tab-container .nav .nav-item:first-child .nav-link').removeClass('disabled')
    $('.tab-container .nav .nav-item:first-child .nav-link').removeAttr('aria-disabled')

    $('.tab-container .nav .nav-item:first-child .nav-link').addClass('active')
    $('.tab-container .tab-content .tab-pane:first-child').addClass('active')

    $('.tab-container #tab_main-information select[name="primary_owner"]').removeAttr('disabled')
}

function fetchData(id){
    $.ajax({
        url: '/admin/api/rpt-building/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            enableInputs()

            $('.rptModal').modal('hide');
            $('#tab_main-information .ownerAddress_fake select option').remove()
            $('#tab_main-information .ownerAddress_fake select').append('<option value="'+data.ownerAddress+'" data-type="propertyAddress">Property Address: '+data.ownerAddress+'</option>')
            $('#tab_main-information .ownerAddress_fake select').append('<option value="'+data.citizen_profile.address+'" data-type="ownerAddress">Owner Address: '+data.citizen_profile.address+'</option>')
            $('#tab_main-information .ownerAddress_fake').removeClass('hidden')
            
            $('#tab_building-location input[name="no_of_street"]').val(data.no_of_street)
            $('#tab_building-location select[name="barangay_id"]').val(data.barangay_id)
            $('#tab_building-location select[name="barangay_code"]').val(data.barangay_id)
            $('#tab_building-location input[name="barangay_code_text"]').val($('#tab_building-location select[name="barangay_code"] option:selected').text())
            $('#tab_building-location input[name="no_of_street"]').attr('readonly','readonly')
            $('#tab_building-location select[name="barangay_id"]').attr('readonly','readonly')

            $('#tab_land-reference input[name="oct_tct_no"]').val(data.oct_tct_no)
            $('#tab_land-reference input[name="survey_no"]').val(data.survey_no)
            $('#tab_land-reference input[name="lot_no"]').val(data.lot_no)
            $('#tab_land-reference input[name="block_no"]').val(data.block_no)
            $('#tab_land-reference input[name="area"]').val(data.area)
            $('#tab_land-reference input[name="oct_tct_no"]').attr('readonly','readonly')
            $('#tab_land-reference input[name="survey_no"]').attr('readonly','readonly')
            $('#tab_land-reference input[name="lot_no"]').attr('readonly','readonly')
            $('#tab_land-reference input[name="block_no"]').attr('readonly','readonly')
            $('#tab_land-reference input[name="area"]').attr('readonly','readonly')

            selectAddressTypeAction()

            $('form .tab-container #form_tabs ul.nav-tabs li.nav-item:nth-child(2)').addClass('hidden')
            $('form .tab-container #form_tabs ul.nav-tabs li.nav-item:nth-child(3)').addClass('hidden')
        }
    })
}

function hideAddress(){
    $('#tab_main-information .ownerAddress').addClass('hidden')
}

function selectAddressTypeAction(){
    $('#tab_main-information textarea[name="ownerAddress"]').val($('#tab_main-information .ownerAddress_fake select').val())
    $('#tab_main-information .ownerAddress_fake select').on('change', function(){
        $('#tab_main-information textarea[name="ownerAddress"]').val($(this).val())
    })
}