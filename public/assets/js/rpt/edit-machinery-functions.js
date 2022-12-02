$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('form div.card').addClass('hidden')

    fetchData($('form input[name="faasId"]').val())

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
    let isApproved = $('.tab-container #tab_property-assessment input[name="isApproved"]').val()
    if(isApproved === '1') {
        $('.tab-container #tab_property-assessment .approve_items').removeClass('hidden')
    }
    else {
        $('.tab-container #tab_property-assessment .approve_items').addClass('hidden')
    }

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
        url: '/admin/api/faas-machinery/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            if(data.length > 0) {
                data = data[0]
                $('#tab_property-assessment input[name="faasId"]').val(data.id)
                $('#tab_property-assessment input[name="barangayCode"]').val(data.barangay.code)
                
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

                $('#tab_main-information select[name="primaryOwnerId"]').append('<option value="'+data.primaryOwnerId+'">'+primaryOwner+'</option>')
                $('#tab_main-information select[name="primaryOwnerId"]').val(data.primaryOwnerId)
                $('#tab_main-information textarea[name="ownerAddress"]').val(data.ownerAddress)
                $('#tab_main-information input[name="ownerTelephoneNo"]').val(data.ownerTelephoneNo)
                $('#tab_main-information input[name="ownerTin"]').val(data.ownerTin)
                $('#tab_main-information input[name="administrator"]').val(data.administrator)
                $('#tab_main-information textarea[name="administratorAddress"]').val(data.administratorAddress)
                $('#tab_main-information input[name="administratorTelephoneNo"]').val(data.administratorTelephoneNo)
                $('#tab_main-information input[name="administratorTin"]').val(data.administratorTin)
                $('#tab_main-information select[name="isActive"]').val(data.isActive)
                
                $('#tab_property-location input[name="noOfStreet"]').val(data.noOfStreet)
                $('#tab_property-location select[name="barangayId"]').val(data.barangayId)

                let landOwner = ''
                let landOwnerSuffix = ''
                if(data.land_owner_citizen_profile.suffix !== null && data.land_owner_citizen_profile.suffix !== 'null') {
                    landOwnerSuffix = data.land_owner_citizen_profile.suffix
                }
                if(data.ownerType === 'CitizenProfile') {
                    landOwner = data.land_owner_citizen_profile.fName+' '+data.land_owner_citizen_profile.mName+' '+data.land_owner_citizen_profile.lName+' '+landOwnerSuffix
                }
                else {
                    landOwner = data.land_owner_citizen_profile.first_name+' '+data.land_owner_citizen_profile.middle_name+' '+data.land_owner_citizen_profile.last_name+' '+landOwnerSuffix
                }

                let buildingOwner = ''
                let buildingOwnerSuffix = ''
                if(data.building_owner_citizen_profile.suffix !== null && data.building_owner_citizen_profile.suffix !== 'null') {
                    buildingOwnerSuffix = data.building_owner_citizen_profile.suffix
                }
                if(data.ownerType === 'CitizenProfile') {
                    buildingOwner = data.building_owner_citizen_profile.fName+' '+data.building_owner_citizen_profile.mName+' '+data.building_owner_citizen_profile.lName+' '+buildingOwnerSuffix
                }
                else {
                    buildingOwner = data.building_owner_citizen_profile.first_name+' '+data.building_owner_citizen_profile.middle_name+' '+data.building_owner_citizen_profile.last_name+' '+buildingOwnerSuffix
                }

                $('#tab_property-location select[name="landOwnerId"]').append('<option value="'+data.landOwnerId+'">'+landOwner+'</option>')
                $('#tab_property-location select[name="landOwnerId"]').val(data.landOwnerId)

                $('#tab_property-location select[name="buildingOwnerId"]').append('<option value="'+data.buildingOwnerId+'">'+buildingOwner+'</option>')
                $('#tab_property-location select[name="buildingOwnerId"]').val(data.buildingOwnerId)

                $('#tab_property-location input[name="landOwnerPin"]').val(data.landOwnerPin)
                $('#tab_property-location input[name="buildingOwnerPin"]').val(data.buildingOwnerPin)

                $('.repeatable-group[bp-field-name="propertyAppraisal"] button.add-repeatable-element-button').addClass('hidden')
                if(data.propertyAppraisal.length > 0) {
                    const propertyAppraisal = data.propertyAppraisal
                    let propertyAppraisalLen = propertyAppraisal.length
                    let propertyAppraisalCtr = 0
                    $.each(propertyAppraisal, function(j, value1) {
                        propertyAppraisalCtr++
                        if($('div[data-repeatable-holder="propertyAppraisal"] .repeatable-element[data-row-number="'+propertyAppraisalCtr+'"]').length > 0){}
                        else {
                            if(propertyAppraisalCtr <= propertyAppraisalLen) {
                                $('.repeatable-group[bp-field-name="propertyAppraisal"] button.add-repeatable-element-button').click()
                            }
                        }
                        $('div[data-repeatable-holder="propertyAppraisal"] .repeatable-element[data-row-number="'+propertyAppraisalCtr+'"] button.delete-element').addClass('hidden')
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][kindOfMachinery]"]').val(value1.kindOfMachinery)
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][brandModel]"]').val(value1.brandModel)
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][capacity]"]').val(value1.capacity)
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][dateAcquired]"]').val(value1.dateAcquired)
                        $('#tab_property-appraisal select[name="propertyAppraisal['+j+'][conditionWhenAcquired]"]').val(value1.conditionWhenAcquired)
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][economicLifeEstimated]"]').val(value1.economicLifeEstimated)
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][economicLifeRemain]"]').val(value1.economicLifeRemain)
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][yearInstalled]"]').val(value1.yearInstalled)
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][yearOfInitialOperation]"]').val(value1.yearOfInitialOperation)
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][originalCost]"]').val(value1.originalCost)
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][conversionFactor]"]').val(value1.conversionFactor)
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][rcn]"]').val(value1.rcn)
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][noOfYearsUsed]"]').val(value1.noOfYearsUsed)
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][rateOfDepreciation]"]').val(value1.rateOfDepreciation)
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][totalDepreciationPercentage]"]').val(value1.totalDepreciationPercentage)
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][totalDepreciationValue]"]').val(value1.totalDepreciationValue)
                        $('#tab_property-appraisal input[name="propertyAppraisal['+j+'][depreciatedValue]"]').val(value1.depreciatedValue)
                    })
                }
                $('#tab_property-appraisal input[name="totalOriginalCost"]').val(data.totalOriginalCost)
                $('#tab_property-appraisal input[name="totalTotalDepreciationValue"]').val(data.totalTotalDepreciationValue)
                $('#tab_property-appraisal input[name="totalDepreciatedValue"]').val(data.totalDepreciatedValue)

                $('.rptModal').modal('hide');
                $('.tab-container').removeClass('hidden')
                $('#saveActions').removeClass('hidden')

                fetchSecondaryOwners(data.id)
            }
        }
    })
}

function fetchSecondaryOwners(machinery_profile_id){
    $.ajax({
        url: '/admin/api/faas-machinery/get-secondary-owners',
        type: 'GET',
        dataType: 'json',
        data: {
            machinery_profile_id: machinery_profile_id
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
                $('#tab_main-information select[name="machinery_owner[]"]').append('<option value="'+value.machinery_profile_id+'">'+secondaryOwner+'</option>')
                secondaryOwnerIds.push(value.machinery_profile_id)
            })
            $('#tab_main-information select[name="machinery_owner[]"]').val(secondaryOwnerIds)
        }
    })
}

function propertyAssessmentComputation(rowNumber){
    let assessmentLevel = $('#tab_property-assessment select.assessmentLevel[data-row-number="'+rowNumber+'"] option:selected').text()
    let marketValue = $('#tab_property-assessment input.marketValue[data-row-number="'+rowNumber+'"]').val()
    let assessedValue = 0

    assessmentLevel = parseFloat(assessmentLevel.replaceAll('%',''))
    marketValue = formatStringToFloat(marketValue)
    
    assessedValue = (marketValue / 100) * assessmentLevel

    $('#tab_property-assessment input.assessedValue[data-row-number="'+rowNumber+'"]').val(assessedValue)
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