$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.tab-container').addClass('hidden')
    $('#saveActions').addClass('hidden')

    $('#btnSearch').on('click', function(){
        let searchByPrimaryOwner = $('input[name="searchByPrimaryOwner"]').val()
        let searchByReferenceId = $('input[name="searchByReferenceId"]').val()
        let searchByOCTTCTNo = $('input[name="searchByOCTTCTNo"]').val()
        let searchByBarangayDistrict = $('select[name="searchByBarangayDistrict"]').val()

        $.ajax({
            url: '/admin/api/rpt-land/apply-search-filters',
            type: 'GET',
            dataType: 'json',
            data: {
                searchByPrimaryOwner: searchByPrimaryOwner,
                searchByReferenceId: searchByReferenceId,
                searchByOCTTCTNo: searchByOCTTCTNo,
                searchByBarangayDistrict: searchByBarangayDistrict
            },
            success: function (data) {
                let html = ''
                if (data.length > 0) {
                    html = '\n\
                    <div class="table-responsive-sm">\n\
                        <table class="table table-striped table-hover border">\n\
                        <thead>\n\
                            <tr>\n\
                            <th scope="col">Reference ID</th>\n\
                            <th scope="col">Primary Owner</th>\n\
                            <th scope="col">OCT/TCT No.</th>\n\
                            <th scope="col">Owner Address</th>\n\
                            <th scope="col">No. of Street</th>\n\
                            <th scope="col">Barangay/District</th>\n\
                            <th scope="col">Status</th>\n\
                            </tr>\n\
                        </thead>\n\
                        <tbody>'

                    $.each(data, function(i, value) {
                        let refID = '<a href="javascript:void(0)" onclick="fetchData(\''+value.id+'\')">'+value.refID+'</a>'
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
                        let octTctNo = value.octTctNo
                        let barangay = value.barangay.name
                        let noOfStreet = value.noOfStreet
                        let isActive = 'Active'
                        if(value.TDNo !== null) { TDNo = value.TDNo }
                        if(value.ARPNo !== null) { ARPNo = value.ARPNo }
                        if(value.isActive === '0') { isActive = 'Inactive' }

                        html += '<tr>\n\
                            <td>'+refID+'</td>\n\
                            <td>'+primaryOwner+'</td>\n\
                            <td>'+octTctNo+'</td>\n\
                            <td>'+value.ownerAddress+'</td>\n\
                            <td>'+noOfStreet+'</td>\n\
                            <td>'+barangay+'</td>\n\
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

                $('.rptModal .modal-body').html(html)
                $('.rptModal').modal('show');
            }
        })
    })

    $('#btnClear').on('click', function(){
        $('input[name="searchByPrimaryOwner"]').val('')
        $('input[name="searchByReferenceId"]').val('')
        $('input[name="searchByOCTTCTNo"]').val('')
        $('select[name="searchByBarangayDistrict"]').val('')
    })

    $('#tab_property-assessment select.actualUse').on('change', function(){
        let actualUse = $(this).val()
        let rowNumber = $(this).attr('data-row-number')
        $('#tab_property-assessment .assessmentLevel[data-row-number="'+rowNumber+'"]').val(actualUse)
        propertyAssessmentComputation(rowNumber)
    })

    $('#tab_property-assessment input.marketValue').on('change', function(){
        let rowNumber = $(this).attr('data-row-number')
        propertyAssessmentComputation(rowNumber)
    })

    //isApproved
    $('.tab-container #tab_property-assessment input[name="isApproved"]').on('change', function(){
        if($(this).val() === '1') {
            $('.tab-container #tab_property-assessment .approve_items input[name="approvedBy"]').val('')
            $('.tab-container #tab_property-assessment .approve_items input[data-init-function="bpFieldInitDatePickerElement"]').val('')
            $('.tab-container #tab_property-assessment .approve_items input[data-init-function="bpFieldInitDatePickerElement"]').datepicker('update');
            $('.tab-container #tab_property-assessment .approve_items input[name="TDNo"]').val('')
            $('.tab-container #tab_property-assessment .approve_items').removeClass('hidden')
        }
        else {
            $('.tab-container #tab_property-assessment .approve_items input[name="approvedBy"]').val('')
            $('.tab-container #tab_property-assessment .approve_items input[data-init-function="bpFieldInitDatePickerElement"]').val('')
            $('.tab-container #tab_property-assessment .approve_items input[data-init-function="bpFieldInitDatePickerElement"]').datepicker('update');
            $('.tab-container #tab_property-assessment .approve_items input[name="TDNo"]').val('')
            $('.tab-container #tab_property-assessment .approve_items').addClass('hidden')
        }
    })

    //assessmentType
    $('#tab_property-assessment select[name="assessmentType"]').on('change', function(){
        if($(this).val() === 'Exempt') {
            $('#tab_property-assessment .ifAssessmentTypeIsExempt').removeClass('hidden')
        }
        else {
            $('#tab_property-assessment .ifAssessmentTypeIsExempt').addClass('hidden')
        }
    })

    $('#tab_property-assessment select[name="assessmentEffectivity"]').on('change', function(){
        if($(this).val() === 'Quarter') {
            $('#tab_property-assessment input[name="assessmentEffectivityValue"]').val('')
            $('#tab_property-assessment .assessmentEffectivityValue_input_fake').addClass('hidden')
            $('#tab_property-assessment .assessmentEffectivityValue_select_fake').removeClass('hidden')
        }
        else {
            $('#tab_property-assessment .assessmentEffectivityValue_input_fake').removeClass('hidden')
            $('#tab_property-assessment input[name="assessmentEffectivityValue"]').val($('#tab_property-assessment .assessmentEffectivityValue_input_fake input').val())
            $('#tab_property-assessment .assessmentEffectivityValue_select_fake').addClass('hidden')
        }
    })

    $('#tab_property-assessment .assessmentEffectivityValue_select_fake select').on('change', function(){
        $('#tab_property-assessment input[name="assessmentEffectivityValue"]').val($(this).val())
    })

    $('#tab_property-assessment .assessmentEffectivityValue_input_fake input').on('change', function(){
        $('#tab_property-assessment input[name="assessmentEffectivityValue"]').val($(this).val())
    })
    
})

function fetchData(id){
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
                console.log(data)
                $('#tab_property-assessment input[name="faasId"]').val(data.id)
                $('#tab_property-assessment input[name="barangayCode"]').val(data.barangay.code)

                console.log(data.isIdleLand === 1)
                if(data.isIdleLand === 1) {
                    $('#tab_main-information input.isIdleLand_checkbox').attr('checked',true)
                    $('#tab_main-information input.isIdleLand_checkbox').prop('checked',true)
                }
                if(data.isOwnerNonTreceResident === 1) {
                    $('#tab_main-information input.isOwnerNonTreceResident_checkbox').attr('checked',true)
                    $('#tab_main-information input.isOwnerNonTreceResident_checkbox').prop('checked',true)
                }
                

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

                $('#tab_main-information input[name="pin"]').val(data.pin)
                $('#tab_main-information input[name="octTctNo"]').val(data.octTctNo)
                $('#tab_main-information input[name="lotNo"]').val(data.lotNo)
                $('#tab_main-information input[name="blkNo"]').val(data.blkNo)

                $('#tab_main-information select[name="primaryOwnerId"]').append('<option value="'+data.primaryOwnerId+'">'+primaryOwner+'</option>')
                $('#tab_main-information select[name="primaryOwnerId"]').val(data.primaryOwnerId)
                $('#tab_main-information textarea[name="ownerAddress"]').val(data.ownerAddress)
                $('#tab_main-information input[name="ownerTelephoneNo"]').val(data.ownerTelephoneNo)
                $('#tab_main-information input[name="ownerTinNo"]').val(data.ownerTinNo)
                $('#tab_main-information input[name="administrator"]').val(data.administrator)
                $('#tab_main-information textarea[name="administratorAddress"]').val(data.administratorAddress)
                $('#tab_main-information input[name="administratorTelephoneNo"]').val(data.administratorTelephoneNo)
                $('#tab_main-information input[name="administratorTinNo"]').val(data.administratorTinNo)
                $('#tab_main-information select[name="isActive"]').val(data.isActive)
                
                $('#tab_main-information input[name="noOfStreet"]').val(data.noOfStreet)
                $('#tab_main-information select[name="barangayId"]').val(data.barangayId)

                $('#tab_property-boundaries input[name="propertyBoundaryEast"]').val(data.propertyBoundaryEast)
                $('#tab_property-boundaries input[name="propertyBoundaryNorth"]').val(data.propertyBoundaryNorth)
                $('#tab_property-boundaries input[name="propertyBoundarySouth"]').val(data.propertyBoundarySouth)
                $('#tab_property-boundaries input[name="propertyBoundaryWest"]').val(data.propertyBoundaryWest)

                $('.repeatable-group[bp-field-name="landAppraisal"] button.add-repeatable-element-button').addClass('hidden')
                if(data.landAppraisal.length > 0) {
                    const landAppraisal = data.landAppraisal
                    let landAppraisalLen = landAppraisal.length
                    let landAppraisalCtr = 0
                    $.each(landAppraisal, function(j, value1) {
                        landAppraisalCtr++
                        if($('div[data-repeatable-holder="landAppraisal"] .repeatable-element[data-row-number="'+landAppraisalCtr+'"]').length > 0){}
                        else {
                            if(landAppraisalCtr <= landAppraisalLen) {
                                $('.repeatable-group[bp-field-name="landAppraisal"] button.add-repeatable-element-button').click()
                            }
                        }
                        $('div[data-repeatable-holder="landAppraisal"] .repeatable-element[data-row-number="'+landAppraisalCtr+'"] button.delete-element').addClass('hidden')
                        $('#tab_land-appraisal select[name="landAppraisal['+j+'][classification]"]').val(value1.classification)
                        $('#tab_land-appraisal input[name="landAppraisal['+j+'][subClass]"]').val(value1.subClass)
                        $('#tab_land-appraisal select[name="landAppraisal['+j+'][actualUse]"]').val(value1.actualUse)
                        $('#tab_land-appraisal select[name="landAppraisal['+j+'][area]"]').val(value1.area)
                        $('#tab_land-appraisal input[name="landAppraisal['+j+'][unitValue]"]').val(value1.unitValue)
                        $('#tab_land-appraisal select[name="landAppraisal['+j+'][baseMarketValue]"]').val(value1.baseMarketValue)
                    })
                }
                $('#tab_land-appraisal input[name="totalLandAppraisalBaseMarketValue"]').val(data.totalLandAppraisalBaseMarketValue)

                $('.repeatable-group[bp-field-name="otherImprovements"] button.add-repeatable-element-button').addClass('hidden')
                if(data.otherImprovements.length > 0) {
                    const otherImprovements = data.otherImprovements
                    let otherImprovementsLen = otherImprovements.length
                    let otherImprovementsCtr = 0
                    $.each(otherImprovements, function(k, value2) {
                        otherImprovementsCtr++
                        if($('div[data-repeatable-holder="otherImprovements"] .repeatable-element[data-row-number="'+otherImprovementsCtr+'"]').length > 0){}
                        else {
                            if(otherImprovementsCtr <= otherImprovementsLen) {
                                $('.repeatable-group[bp-field-name="otherImprovements"] button.add-repeatable-element-button').click()
                            }
                        }
                        $('div[data-repeatable-holder="otherImprovements"] .repeatable-element[data-row-number="'+otherImprovementsCtr+'"] button.delete-element').addClass('hidden')
                        $('#tab_other-improvements input[name="otherImprovements['+k+'][baseMarketValue]"]').val(value2.baseMarketValue)
                        $('#tab_other-improvements input[name="otherImprovements['+k+'][kind]"]').val(value2.kind)
                        $('#tab_other-improvements input[name="otherImprovements['+k+'][totalNumber]"]').val(value2.totalNumber)
                        $('#tab_other-improvements input[name="otherImprovements['+k+'][unitValue]"]').val(value2.unitValue)
                    })
                }
                $('#tab_other-improvements input[name="totalOtherImprovementsBaseMarketValue"]').val(data.totalOtherImprovementsBaseMarketValue)

                $('.repeatable-group[bp-field-name="marketValue"] button.add-repeatable-element-button').addClass('hidden')
                if(data.marketValue.length > 0) {
                    const marketValue = data.marketValue
                    let marketValueLen = marketValue.length
                    let marketValueCtr = 0
                    $.each(marketValue, function(l, value3) {
                        marketValueCtr++
                        if($('div[data-repeatable-holder="marketValue"] .repeatable-element[data-row-number="'+marketValueCtr+'"]').length > 0){}
                        else {
                            if(marketValueCtr <= marketValueLen) {
                                $('.repeatable-group[bp-field-name="marketValue"] button.add-repeatable-element-button').click()
                            }
                        }
                        $('div[data-repeatable-holder="marketValue"] .repeatable-element[data-row-number="'+marketValueCtr+'"] button.delete-element').addClass('hidden')
                        $('#tab_market-value input[name="marketValue['+l+'][baseMarketValue]"]').val(value3.baseMarketValue)
                        $('#tab_market-value input[name="marketValue['+l+'][adjustmentFactor]"]').val(value3.adjustmentFactor)
                        $('#tab_market-value input[name="marketValue['+l+'][adjustmentFactorPercentage]"]').val(value3.adjustmentFactorPercentage)
                        $('#tab_market-value input[name="marketValue['+l+'][valueAdjustment]"]').val(value3.valueAdjustment)
                        $('#tab_market-value input[name="marketValue['+l+'][marketValue]"]').val(value3.marketValue)
                    })
                }
                $('#tab_market-value input[name="totalMarketValueMarketValue"]').val(data.totalMarketValueMarketValue)

                $('.rptModal').modal('hide');
                $('.tab-container').removeClass('hidden')
                $('#saveActions').removeClass('hidden')

                fetchSecondaryOwners(data.id)
            }
        }
    })
}

function fetchSecondaryOwners(land_profile_id){
    $.ajax({
        url: '/admin/api/faas-land/get-secondary-owners',
        type: 'GET',
        dataType: 'json',
        data: {
            land_profile_id: land_profile_id
        },
        success: function (data) {
            let secondaryOwnerIds = []
            $.each(data, function(i, value) {
                let secondaryOwner = ''
                let suffix = ''
                if(value.suffix !== null && value.suffix !== 'null') {
                    suffix = value.suffix
                }
                secondaryOwner = value.fName+' '+value.mName+' '+value.lName+' '+suffix
                $('#tab_main-information select[name="land_owner[]"]').append('<option value="'+value.land_profile_id+'">'+secondaryOwner+'</option>')
                secondaryOwnerIds.push(value.land_profile_id)
            })
            $('#tab_main-information select[name="land_owner[]"]').val(secondaryOwnerIds)
        }
    })
}

function propertyAssessmentComputation(rowNumber){
    let assessmentLevel = $('#tab_property-assessment select.assessmentLevel[data-row-number="'+rowNumber+'"] option:selected').text()
    let marketValue = $('#tab_property-assessment input.marketValue[data-row-number="'+rowNumber+'"]').val()
    let assessmentValue = 0

    assessmentLevel = parseFloat(assessmentLevel.replaceAll('%',''))
    marketValue = formatStringToFloat(marketValue)
    
    assessmentValue = (marketValue / 100) * assessmentLevel

    $('#tab_property-assessment input.assessmentValue[data-row-number="'+rowNumber+'"]').val(assessmentValue)

    totalPropertyAssessmentMarketValue()
    totalPropertyAssessmentAssessmentValue()
}

function totalPropertyAssessmentAssessmentValue(){
    let totalPropertyAssessmentAssessmentValue = 0
    $('#tab_property-assessment .assessmentValue').each(function(){
        let assessmentValue = $(this).val()
        assessmentValue = formatStringToFloat(assessmentValue)
        totalPropertyAssessmentAssessmentValue = totalPropertyAssessmentAssessmentValue + assessmentValue
    })
    $('#tab_property-assessment input[name="totalPropertyAssessmentAssessmentValue"]').val(totalPropertyAssessmentAssessmentValue)
}

function totalPropertyAssessmentMarketValue(){
    let totalPropertyAssessmentMarketValue = 0
    $('#tab_property-assessment .marketValue').each(function(){
        let marketValue = $(this).val()
        marketValue = formatStringToFloat(marketValue)
        totalPropertyAssessmentMarketValue = totalPropertyAssessmentMarketValue + marketValue
    })
    $('#tab_property-assessment input[name="totalPropertyAssessmentMarketValue"]').val(totalPropertyAssessmentMarketValue)
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