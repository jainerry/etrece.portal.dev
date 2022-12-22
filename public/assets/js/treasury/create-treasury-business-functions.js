$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.tab-container').addClass('hidden')
    $('#saveActions').addClass('hidden')

    $('#btnSearch').on('click', function(){
        let searchByReferenceId = $('input[name="searchByReferenceId"]').val()
        let searchByName = $('input[name="searchByName"]').val()
        let searchByOwner = $('input[name="searchByOwner"]').val()

        $.ajax({
            url: '/admin/api/treasury-business/apply-search-filters',
            type: 'GET',
            dataType: 'json',
            data: {
                searchByReferenceId: searchByReferenceId,
                searchByName: searchByName,
                searchByOwner: searchByOwner
            },
            success: function (data) {
                console.log(data)

                let html = ''
                if (data.length > 0) {
                    html = '\n\
                    <div class="table-responsive-sm">\n\
                        <table class="table table-striped table-hover border">\n\
                        <thead>\n\
                            <tr>\n\
                            <th scope="col">Reference ID</th>\n\
                            <th scope="col">Business Profile</th>\n\
                            <th scope="col">Primary Owner</th>\n\
                            <th scope="col">Owner Address</th>\n\
                            <th scope="col">Business Type</th>\n\
                            <th scope="col">Status</th>\n\
                            </tr>\n\
                        </thead>\n\
                        <tbody>'

                    $.each(data, function(i, value) {
                        let refID = '<a href="javascript:void(0)" onclick="fetchData(\''+value.id+'\')">'+value.refID+'</a>'
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
                        let buss_type = value.buss_type.name
                        let isActive = 'Active'
                        if(value.isActive === '0') { isActive = 'Inactive' }

                        let ownerAddress = value.buss_prof.main_office.ownerAddress

                        html += '<tr>\n\
                            <td>'+refID+'</td>\n\
                            <td>'+businessRefID+'</td>\n\
                            <td>'+primaryOwner+'</td>\n\
                            <td>'+ownerAddress+'</td>\n\
                            <td>'+buss_type+'</td>\n\
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
        })
    })

    $('#btnClear').on('click', function(){
        $('input[name="searchByReferenceId"]').val('')
        $('input[name="searchByName"]').val('')
        $('input[name="searchByOwner"]').val('')
    })

})

function fetchData(id){
    $.ajax({
        url: '/admin/api/business-tax-assessment/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            console.log(data)
            if(data.length > 0) {
                data = data[0]
                /*
                $('input[name="rptId"]').val(data.id)
                $('#tab_details select[name="rptType"]').val(searchByType).change()

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

                let lotNo = ''
                let totalArea = ''

                if(searchByType === 'Land'){
                    lotNo = data.lotNo
                    totalArea = data.totalArea
                }
                else if(searchByType === 'Building'){
                    lotNo = data.faas_building_profile.land_profile.lotNo
                    totalArea = data.faas_building_profile.totalFloorArea

                }
                else if(searchByType === 'Machinery'){
                    lotNo = data.faas_machinery_profile.land_profile.lotNo
                    totalArea = data.faas_machinery_profile.land_profile.totalArea
                }

                $('#tab_details input[name="TDNo"]').val(data.TDNo)
                $('#tab_details input[name="primaryOwner"]').val(primaryOwner)
                $('#tab_details textarea[name="ownerAddress"]').val(data.ownerAddress)
                $('#tab_details input[name="lotNo"]').val(lotNo)
                $('#tab_details input[name="area"]').val(totalArea)
                // $('#tab_details input[name="assessedValue"]').val(data.totalPropertyAssessmentAssessmentValue)
                $('#tab_details input[name="assessedValue"]').val(data.totalPropertyAssessmentMarketValue)
                $('#tab_details input[name="dateAssessed"]').val(data.assessedDate)

                //(Basic, Penalty, Discount, Total Basic, SEF, Penalty, Discount, Total SEF)
                getTreasuryRPTRates('980935f0-66d2-44b9-a3b2-360288a5d048')
                getTreasuryRPTRates('9809360c-1e78-40a8-b989-8b5979aecdd4')
                */

                $('.treasuryModal').modal('hide');
                $('.tab-container').removeClass('hidden')
                $('#saveActions').removeClass('hidden')
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
        return parseFloat(num.replaceAll('%',''))
    }
}