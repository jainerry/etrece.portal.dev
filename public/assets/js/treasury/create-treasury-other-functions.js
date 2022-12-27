$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.tab-container').addClass('hidden')
    $('#saveActions').addClass('hidden')

    $('#btnSearch').on('click', function(){
        let searchByType = $('select[name="searchByType"]').val()
        let searchByReferenceId = $('input[name="searchByReferenceId"]').val()
        let searchByName = $('input[name="searchByName"]').val()
        let searchByOwner = $('input[name="searchByOwner"]').val()

        $.ajax({
            url: '/admin/api/treasury-other/apply-search-filters',
            type: 'GET',
            dataType: 'json',
            data: {
                searchByType: searchByType,
                searchByReferenceId: searchByReferenceId,
                searchByName: searchByName,
                searchByOwner: searchByOwner
            },
            success: function (data) {
                if(searchByType === 'Business') {
                    getBusiness(data)
                }
                else {
                    getOtherProfile(data)
                }
            }
        })
    })

    $('#btnClear').on('click', function(){
        $('select[name="searchByType"]').val()
        $('input[name="searchByReferenceId"]').val('')
        $('input[name="searchByName"]').val('')
        $('input[name="searchByOwner"]').val('')
    })

    $('input[name="type"]').val($('select[name="searchByType"]').val())

    $('select[name="searchByType"]').on("change", function(){
        let searchByType = $(this).val()
        $('input[name="type"]').val(searchByType)
        if(searchByType === "Business") {
            $('input[name="searchByOwner"]').val('')
            $('.searchByOwnerWrapper').removeClass('hidden')
        }
        else {
            $('input[name="searchByOwner"]').val('')
            $('.searchByOwnerWrapper').addClass('hidden')
        }
    })

    feesActions()
    computeTotalFeesAmount()

    $('.repeatable-group[bp-field-name="fees"] button.add-repeatable-element-button').on('click', function(){
        $('div[data-repeatable-holder="fees"] .repeatable-element input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
        $('div[data-repeatable-holder="fees"] .repeatable-element input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
        feesActions()
    })

})

function fetchBusinessData(id){
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

                $('input[name="businessAssessmentId"]').val(id)
                $('input[name="citizenProfileId"]').val('')
                $('input[name="nameProfileId"]').val('')

                let primaryOwner = ''
                let suffix = ''
                if(data.suffix !== null && data.suffix !== 'null') {
                    suffix = data.suffix
                }
                if(data.ownerType === 'CitizenProfile') {
                    primaryOwner = data.fName+' '+data.mName+' '+data.lName+' '+suffix
                }
                else {
                    primaryOwner = data.first_name+' '+data.middle_name+' '+data.last_name+' '+suffix
                }

                $('#tab_details .businessNameWrapper input[name="businessName"]').val(data.business_name)
                let businessAddress = data.main_office.ownerAddress
                $('#tab_details .businessAddressWrapper textarea[name="businessAddress"]').val(businessAddress)
                $('#tab_details .ownerWrapper input[name="owner"]').val(primaryOwner)
                $('#tab_details .businessNameWrapper').removeClass('hidden')
                $('#tab_details .businessAddressWrapper').removeClass('hidden')
                $('#tab_details .ownerWrapper').removeClass('hidden')

                //hide other profile fields
                $('#tab_details .nameWrapper input[name="name"]').val('')
                $('#tab_details .addressWrapper textarea[name="address"]').val('')
                $('#tab_details .nameWrapper').addClass('hidden')
                $('#tab_details .addressWrapper').addClass('hidden')

                $('.treasuryModal').modal('hide');
                $('.tab-container').removeClass('hidden')
                $('#saveActions').removeClass('hidden')
            }
        }
    })
}

function fetchOtherProfileData(id, ownerType){
    let url = '/admin/api/name-profile/get-details'
    if(ownerType === 'CitizenProfile') {
        url = '/admin/api/citizen-profile/get-details'
    }

    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            if(data.length > 0) {
                data = data[0]

                $('input[name="businessAssessmentId"]').val('')
                if(ownerType === 'CitizenProfile') {
                    $('input[name="citizenProfileId"]').val(id)
                    $('input[name="nameProfileId"]').val('')
                }
                else {
                    $('input[name="nameProfileId"]').val(id)
                    $('input[name="citizenProfileId"]').val('')
                }

                let primaryOwner = ''
                let suffix = ''
                if(data.suffix !== null && data.suffix !== 'null') {
                    suffix = data.suffix
                }
                if(ownerType === 'CitizenProfile') {
                    primaryOwner = data.fName+' '+data.mName+' '+data.lName+' '+suffix
                }
                else {
                    primaryOwner = data.first_name+' '+data.middle_name+' '+data.last_name+' '+suffix
                }

                $('#tab_details .nameWrapper input[name="name"]').val(primaryOwner)
                $('#tab_details .addressWrapper textarea[name="address"]').val(data.address)
                $('#tab_details .nameWrapper').removeClass('hidden')
                $('#tab_details .addressWrapper').removeClass('hidden')

                //hide business fields
                $('#tab_details .businessNameWrapper input[name="businessName"]').val('')
                $('#tab_details .businessAddressWrapper textarea[name="businessAddress"]').val('')
                $('#tab_details .ownerWrapper input[name="owner"]').val('')
                $('#tab_details .businessNameWrapper').addClass('hidden')
                $('#tab_details .businessAddressWrapper').addClass('hidden')
                $('#tab_details .ownerWrapper').addClass('hidden')

                $('.treasuryModal').modal('hide');
                $('.tab-container').removeClass('hidden')
                $('#saveActions').removeClass('hidden')
            }
        }
    })
}

function getBusiness(data){
    let html = ''
    if (data.length > 0) {
        html = '\n\
        <div class="table-responsive-sm">\n\
            <table class="table table-striped table-hover border">\n\
            <thead>\n\
                <tr>\n\
                <th scope="col">Reference ID</th>\n\
                <th scope="col">Business Name</th>\n\
                <th scope="col">Business Profile Ref ID</th>\n\
                <th scope="col">Primary Owner</th>\n\
                <th scope="col">Owner Address</th>\n\
                <th scope="col">Application Type</th>\n\
                <th scope="col">Status</th>\n\
                </tr>\n\
            </thead>\n\
            <tbody>'

        $.each(data, function(i, value) {
            let refID = '<a href="javascript:void(0)" onclick="fetchBusinessData(\''+value.business_profiles_id+'\')">'+value.refID+'</a>'
            let businessRefID = value.businessRefID
            let primaryOwner = '-'
            let suffix = ''
            if(value.suffix !== null && value.suffix !== 'null') {
                suffix = value.suffix
            }

            if(value.ownerType === 'CitizenProfile') {
                primaryOwner = value.fName+' '+value.mName+' '+value.lName+' '+suffix
            }
            else {
                primaryOwner = value.first_name+' '+value.middle_name+' '+value.last_name+' '+suffix
            }

            let buss_type = ''
            if(value.buss_type !== '' && value.buss_type !== null && value.buss_type !== 'null') {
                buss_type = value.buss_type.name
            }

            let application_type = value.application_type

            let isActive = 'Active'
            if(value.isActive === '0') { isActive = 'Inactive' }

            let ownerAddress = value.buss_prof.main_office.ownerAddress

            html += '<tr>\n\
                <td>'+refID+'</td>\n\
                <td>'+value.business_name+'</td>\n\
                <td>'+businessRefID+'</td>\n\
                <td>'+primaryOwner+'</td>\n\
                <td>'+ownerAddress+'</td>\n\
                <td>'+application_type+'</td>\n\
                <td>'+isActive+'</td>\n\
            </tr>'
        });

        html += '</tbody>\n\
            </table>\n\
        </div>'
    }
    else {
        html = '<p>No Result Found</p>'
        
    }
    $('.treasuryModal .modal-body').html(html)
    $('.treasuryModal').modal('show');
}

function getOtherProfile(data){
    let html = ''
    if (data.length > 0) {
        html = '\n\
        <div class="table-responsive-sm">\n\
            <table class="table table-striped table-hover border">\n\
            <thead>\n\
                <tr>\n\
                <th scope="col">Reference ID</th>\n\
                <th scope="col">Full Name</th>\n\
                <th scope="col">Civil Status</th>\n\
                <th scope="col">Gender</th>\n\
                <th scope="col">Birth Date</th>\n\
                <th scope="col">Birth Place</th>\n\
                <th scope="col">Address</th>\n\
                <th scope="col">Status</th>\n\
                </tr>\n\
            </thead>\n\
            <tbody>'

        $.each(data, function(i, value) {
            let refID = '<a href="javascript:void(0)" onclick="fetchOtherProfileData(\''+value.id+'\', \''+value.ownerType+'\')">'+value.refID+'</a>'
            let primaryOwner = '-'
            let suffix = ''

            let address = '-'
            let placeOfOrigin = '-'
            let bdate = '-'
            let sex = '-'
            let civilStatus = '-'

            if(value.suffix !== null && value.suffix !== 'null') {
                suffix = value.suffix
            }

            if(value.ownerType === 'CitizenProfile') {
                primaryOwner = value.fName+' '+value.mName+' '+value.lName+' '+suffix
            }
            else {
                primaryOwner = value.first_name+' '+value.middle_name+' '+value.last_name+' '+suffix
            }
            let isActive = 'Active'
            if(value.isActive === '0') { isActive = 'Inactive' }

            if(value.address){ address = value.address }
            if(value.placeOfOrigin){ placeOfOrigin = value.placeOfOrigin }
            if(value.bdate){ bdate = value.bdate }
            if(value.sex){ sex = value.sex }
            if(value.civilStatus){ civilStatus = value.civilStatus }

            html += '<tr>\n\
                <td>'+refID+'</td>\n\
                <td>'+primaryOwner+'</td>\n\
                <td>'+civilStatus+'</td>\n\
                <td>'+sex+'</td>\n\
                <td>'+bdate+'</td>\n\
                <td>'+placeOfOrigin+'</td>\n\
                <td>'+address+'</td>\n\
                <td>'+isActive+'</td>\n\
            </tr>'
        });

        html += '</tbody>\n\
            </table>\n\
        </div>'
    }
    else {
        html = '<p>No Result Found</p>'
        
    }
    $('.treasuryModal .modal-body').html(html)
    $('.treasuryModal').modal('show');
}

function feesActions(){
    $('select.particulars').on("change", function(){
        computeTotalFeesAmount()
    })

    $('input.amount').on("change", function(){
        computeTotalFeesAmount()
    })
}

function computeTotalFeesAmount(){
    let totalFeesAmount = 0
    $('select.particulars').each(function(){
        let dataRowNumber = $(this).attr('data-row-number')
        let feeAmount = $('input.amount[data-row-number="'+dataRowNumber+'"]').val()
        feeAmount = formatStringToFloat(feeAmount)
        totalFeesAmount = totalFeesAmount + feeAmount
    })

    $('input[name="totalFeesAmount"]').val(totalFeesAmount)
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
        return parseFloat(num.replaceAll('%',''))
    }
}