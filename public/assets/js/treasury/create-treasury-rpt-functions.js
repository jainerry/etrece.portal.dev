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
        let searchByTDNo = $('input[name="searchByTDNo"]').val()
        let searchByOwner = $('input[name="searchByOwner"]').val()

        $.ajax({
            url: '/admin/api/treasury-rpt/apply-search-filters',
            type: 'GET',
            dataType: 'json',
            data: {
                searchByType: searchByType,
                searchByReferenceId: searchByReferenceId,
                searchByTDNo: searchByTDNo,
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
                            <th scope="col">TD No.</th>\n\
                            <th scope="col">'+searchByType+' Reference ID</th>\n\
                            <th scope="col">Primary Owner</th>\n\
                            <th scope="col">Owner Address</th>\n\
                            <th scope="col">Status</th>\n\
                            </tr>\n\
                        </thead>\n\
                        <tbody>'

                    $.each(data, function(i, value) {
                        let refID = '<a href="javascript:void(0)" onclick="fetchData(\''+value.id+'\',\''+searchByType+'\')">'+value.refID+'</a>'
                        let TDNo = value.TDNo
                        let faasRefId = value.faasRefId
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
                        let isActive = 'Active'
                        if(value.isActive === '0') { isActive = 'Inactive' }

                        html += '<tr>\n\
                            <td>'+refID+'</td>\n\
                            <td>'+TDNo+'</td>\n\
                            <td>'+faasRefId+'</td>\n\
                            <td>'+primaryOwner+'</td>\n\
                            <td>'+value.ownerAddress+'</td>\n\
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
        $('select[name="searchByType"]').val('Land')
        $('input[name="searchByReferenceId"]').val('')
        $('input[name="searchByTDNo"]').val('')
        $('input[name="searchByOwner"]').val('')
    })

})

function fetchData(id,searchByType){
    console.log(id)
    console.log(searchByType)

    let api_url = ''
    if(searchByType === 'Land'){
        api_url = '/admin/api/rpt-land-assessment/get-details'
    }
    else if(searchByType === 'Building'){
        api_url = '/admin/api/rpt-building-assessment/get-details'
    }
    else if(searchByType === 'Machinery'){
        api_url = '/admin/api/rpt-machinery-assessment/get-details'
    }

    $.ajax({
        url: api_url,
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            console.log(data)
            if(data.length > 0) {
                data = data[0]
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

                $('#tab_details input[name="TDNo"]').val(data.TDNo)
                $('#tab_details input[name="primaryOwner"]').val(primaryOwner)
                $('#tab_details textarea[name="ownerAddress"]').val(data.ownerAddress)
                $('#tab_details input[name="lotNo"]').val(data.lotNo)
                $('#tab_details input[name="area"]').val(data.totalArea)
                $('#tab_details input[name="assessedValue"]').val(data.totalPropertyAssessmentAssessmentValue)
                $('#tab_details input[name="dateAssessed"]').val(data.assessedDate)

                //8 items on Summary Defaults
                
                for (let index = 0; index < 8; index++) {
                    let dataRowNumber = index + 1
                    if(index === 0) {
                        $('#tab_details input.particulars[data-row-number="'+dataRowNumber+'"]').val("Basic")
                    }
                    else {
                        $('.repeatable-group[bp-field-name="summary"] button.add-repeatable-element-button').trigger('click')
                    }
                }
                

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
        return parseInt(num.replaceAll('%',''))
    }
}