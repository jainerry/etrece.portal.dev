$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    disableInputs()

    $('.tab-container #tab_main-information select[name="primaryOwnerId"]').on('change', function(){
        let primaryOwnerId = $(this).val()
        let primaryOwner = $('.tab-container #tab_main-information div.form-group[bp-field-name="primaryOwnerId"] .select2-selection__rendered').text()
        primaryOwner = primaryOwner.replaceAll('× ','')
        $.ajax({
            url: '/admin/api/rpt-machinery/check-if-primary-owner-exist',
            type: 'GET',
            dataType: 'json',
            data: {
                primaryOwnerId: primaryOwnerId
            },
            success: function (data) {
                console.log(data)
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
                        let editUrl = '/admin/rpt-building/'+value.id+'/edit'
                        let refID = '<a href="'+editUrl+'">'+value.refID+'</a>'
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

    $('.tab-container #tab_main-information select[name="primaryOwnerId"]').removeAttr('disabled')
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

    $('.tab-container #tab_main-information select[name="primaryOwnerId"]').removeAttr('disabled')
}