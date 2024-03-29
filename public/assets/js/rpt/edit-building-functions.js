$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('form div.card').addClass('hidden')

    fetchData($('form input[name="faasId"]').val())
    fetchRptDetails($('form input[name="id"]').val())

    //Edit Page : if rpt is already approved
    let isApproved = $('.tab-container #tab_property-assessment input[name="isApproved"]').val()
    if(isApproved === '1') {
        $('.tab-container #tab_property-assessment .approve_items').removeClass('hidden')
    }
    else {
        $('.tab-container #tab_property-assessment .approve_items').addClass('hidden')
    }

    //Edit Page : if rpt Assessment Type is already set
    let assessmentType = $('.tab-container #tab_property-assessment select[name="assessmentType"]').val()
    if(assessmentType === 'Exempt') {
        $('.tab-container #tab_property-assessment .ifAssessmentTypeIsExempt').removeClass('hidden')
    }
    else {
        $('.tab-container #tab_property-assessment .ifAssessmentTypeIsExempt').addClass('hidden')
    }

    //Property Assessment Tab > isApproved
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

    //Property Assessment Tab > assessmentType
    $('#tab_property-assessment select[name="assessmentType"]').on('change', function(){
        if($(this).val() === 'Exempt') {
            $('#tab_property-assessment .ifAssessmentTypeIsExempt').removeClass('hidden')
        }
        else {
            $('#tab_property-assessment .ifAssessmentTypeIsExempt').addClass('hidden')
        }
    })

    $('#tab_property-appraisal input[name="unitConstructionSubTotal_fake"]').on('change', function(){
        let unitConstructionSubTotal_fake = $(this).val()
        $('#tab_property-appraisal input[name="unitConstructionSubTotal"]').val(unitConstructionSubTotal_fake)
        totalConstructionCostComputation()
    })

    $('#tab_property-appraisal input[name="costOfAdditionalItemsSubTotal_fake"]').on('change', function(){
        let costOfAdditionalItemsSubTotal_fake = $(this).val()
        $('#tab_property-appraisal input[name="costOfAdditionalItemsSubTotal"]').val(costOfAdditionalItemsSubTotal_fake)
        totalConstructionCostComputation()
    })

    $('#tab_property-appraisal input[name="depreciationRate"]').on('change', function(){
        propertyAppraisalComputation()
    })

    $('#tab_property-appraisal input[name="depreciationCost"]').on('change', function(){
        propertyAppraisalComputation()
    })

    $('#tab_property-appraisal input[name="totalPercentDepreciation"]').on('change', function(){
        propertyAppraisalComputation()
    })

})

function fetchData(id){
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
                $('#tab_property-assessment input[name="faasId"]').val(data.id)

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

                $('#tab_main-information select[name="primary_owner"]').append('<option value="'+data.primary_owner+'">'+primaryOwner+'</option>')
                $('#tab_main-information select[name="primary_owner"]').val(data.primary_owner)

                fetchSecondaryOwners(data.id)

                $('#tab_main-information textarea[name="ownerAddress"]').val(data.ownerAddress)
                $('#tab_main-information input[name="tel_no"]').val(data.tel_no)
                $('#tab_main-information input[name="owner_tin_no"]').val(data.owner_tin_no)
                $('#tab_main-information input[name="administrator"]').val(data.administrator)
                $('#tab_main-information textarea[name="admin_address"]').val(data.admin_address)
                $('#tab_main-information input[name="admin_tel_no"]').val(data.admin_tel_no)
                $('#tab_main-information input[name="admin_tin_no"]').val(data.admin_tin_no)
                
                $('#tab_main-information select[name="isActive"]').val(data.isActive)

                $('#tab_general-description select[name="kind_of_building_id"]').val(data.kind_of_building_id)

                $('#tab_general-description input[name="buildingAge"]').val(data.buildingAge)
                $('#tab_general-description select[name="structural_type_id"]').val(data.structural_type_id)
                $('#tab_general-description input[name="building_permit_no"]').val(data.building_permit_no)
                $('#tab_general-description input[name="building_permit_date_issued"]').val(data.building_permit_date_issued)
                $('#tab_general-description input[name="condominium_certificate_of_title"]').val(data.condominium_certificate_of_title)
                $('#tab_general-description input[name="certificate_of_completion_issued_on"]').val(data.certificate_of_completion_issued_on)
                $('#tab_general-description input[name="certificate_of_occupancy_issued_on"]').val(data.certificate_of_occupancy_issued_on)
                $('#tab_general-description input[name="date_constructed"]').val(data.date_constructed)
                $('#tab_general-description input[name="date_occupied"]').val(data.date_occupied)
                
                $('#tab_general-description input[name="no_of_storeys"]').val(data.no_of_storeys)
                $('#tab_general-description input[name="totalFloorArea"]').val(data.totalFloorArea)
                
                $('#tab_structural-characteristic select[name="roof"]').val(data.roof)
                $('#tab_structural-characteristic input[name="other_roof"]').val(data.other_roof)

                
                $('.repeatable-group[bp-field-name="floorsArea"] button.add-repeatable-element-button').addClass('hidden')
                if(data.floorsArea.length > 0) {
                    const floorsArea = data.floorsArea
                    let floorsAreaLen = floorsArea.length
                    let floorsAreaCtr = 0
                    $.each(floorsArea, function(j, value1) {
                        floorsAreaCtr++
                        if($('div[data-repeatable-holder="floorsArea"] .repeatable-element[data-row-number="'+floorsAreaCtr+'"]').length > 0){}
                        else {
                            if(floorsAreaCtr <= floorsAreaLen) {
                                $('.repeatable-group[bp-field-name="floorsArea"] button.add-repeatable-element-button').click()

                                $('#tab_general-description input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
                                $('#tab_general-description input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
                
                            }
                        }
                        $('div[data-repeatable-holder="floorsArea"] .repeatable-element[data-row-number="'+floorsAreaCtr+'"] button.delete-element').addClass('hidden')
                        $('#tab_general-description input[name="floorsArea['+j+'][floorNo_fake]"]').val(value1.floorNo_fake)
                        $('#tab_general-description input[name="floorsArea['+j+'][floorNo]"]').val(value1.floorNo)
                        $('#tab_general-description input[name="floorsArea['+j+'][area]"]').val(value1.area)
                    })
                }

                $('.repeatable-group[bp-field-name="flooring"] button.add-repeatable-element-button').addClass('hidden')
                if(data.flooring.length > 0) {
                    const flooring = data.flooring
                    let flooringLen = flooring.length
                    let flooringCtr = 0
                    $.each(flooring, function(k, value2) {
                        flooringCtr++
                        if($('div[data-repeatable-holder="flooring"] .repeatable-element[data-row-number="'+flooringCtr+'"]').length > 0){}
                        else {
                            if(flooringCtr <= flooringLen) {
                                $('.repeatable-group[bp-field-name="flooring"] button.add-repeatable-element-button').click()
                            }
                        }
                        $('div[data-repeatable-holder="flooring"] .repeatable-element[data-row-number="'+flooringCtr+'"] button.delete-element').addClass('hidden')
                        $('#tab_structural-characteristic input[name="flooring['+k+'][floorNo_fake]"]').val(value2.floorNo_fake)
                        $('#tab_structural-characteristic input[name="flooring['+k+'][floorNo]"]').val(value2.floorNo)
                        $('#tab_structural-characteristic select[name="flooring['+k+'][type]"]').val(value2.type)
                        $('#tab_structural-characteristic input[name="flooring['+k+'][others]"]').val(value2.others)
                    })
                }

                $('.repeatable-group[bp-field-name="walling"] button.add-repeatable-element-button').addClass('hidden')
                if(data.walling.length > 0) {
                    const walling = data.walling
                    let wallingLen = walling.length
                    let wallingCtr = 0
                    $.each(walling, function(l, value3) {
                        wallingCtr++
                        if($('div[data-repeatable-holder="walling"] .repeatable-element[data-row-number="'+wallingCtr+'"]').length > 0){}
                        else {
                            if(wallingCtr <= wallingLen) {
                                $('.repeatable-group[bp-field-name="walling"] button.add-repeatable-element-button').click()
                            }
                        }
                        $('div[data-repeatable-holder="walling"] .repeatable-element[data-row-number="'+wallingCtr+'"] button.delete-element').addClass('hidden')
                        $('#tab_structural-characteristic input[name="walling['+l+'][floorNo_fake]"]').val(value3.floorNo_fake)
                        $('#tab_structural-characteristic input[name="walling['+l+'][floorNo]"]').val(value3.floorNo)
                        $('#tab_structural-characteristic select[name="walling['+l+'][type]"]').val(value3.type)
                        $('#tab_structural-characteristic input[name="walling['+l+'][others]"]').val(value3.others)
                    })
                }

                $('.repeatable-group[bp-field-name="additionalItems"] button.add-repeatable-element-button').addClass('hidden')
                if(data.additionalItems.length > 0) {
                    const additionalItems = data.additionalItems
                    let additionalItemsLen = additionalItems.length
                    let additionalItemsCtr = 0
                    $.each(additionalItems, function(m, value4) {
                        additionalItemsCtr++
                        if($('div[data-repeatable-holder="additionalItems"] .repeatable-element[data-row-number="'+additionalItemsCtr+'"]').length > 0){}
                        else {
                            if(additionalItemsCtr <= additionalItemsLen) {
                                $('.repeatable-group[bp-field-name="additionalItems"] button.add-repeatable-element-button').click()
                            }
                        }
                        $('div[data-repeatable-holder="additionalItems"] .repeatable-element[data-row-number="'+additionalItemsCtr+'"] button.delete-element').addClass('hidden')
                        $('#tab_structural-characteristic input[name="additionalItems['+m+'][additionalItem1]"]').val(value4.additionalItem1)
                        $('#tab_structural-characteristic input[name="additionalItems['+m+'][additionalItem2]"]').val(value4.additionalItem2)
                        $('#tab_structural-characteristic input[name="additionalItems['+m+'][additionalItem3]"]').val(value4.additionalItem3)
                        $('#tab_structural-characteristic input[name="additionalItems['+m+'][additionalItem4]"]').val(value4.additionalItem4)
                    })
                }

                $('.rptModal').modal('hide');
                $('.tab-container').removeClass('hidden')
                $('#saveActions').removeClass('hidden')
            }
        }
    })
}

function fetchRptDetails(id){
    $.ajax({
        url: '/admin/api/rpt-building/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            if(data.length > 0) {
                data = data[0]

                $('#tab_property-appraisal input[name="unitConstructionCost_fake"]').val(data.unitConstructionCost)
                $('#tab_property-appraisal input[name="unitConstructionSubTotal_fake"]').val(data.unitConstructionSubTotal)
                $('#tab_property-appraisal input[name="costOfAdditionalItemsSubTotal_fake"]').val(data.costOfAdditionalItemsSubTotal)
                $('#tab_property-appraisal input[name="totalConstructionCost_fake"]').val(data.totalConstructionCost)
            }
        }
    })
}

function fetchSecondaryOwners(building_profile_id){
    $.ajax({
        url: '/admin/api/faas-building/get-secondary-owners',
        type: 'GET',
        dataType: 'json',
        data: {
            building_profile_id: building_profile_id
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
                $('#tab_main-information select[name="building_owner[]"]').append('<option value="'+value.building_profile_id+'">'+secondaryOwner+'</option>')
                secondaryOwnerIds.push(value.building_profile_id)
            })
            $('#tab_main-information select[name="building_owner[]"]').val(secondaryOwnerIds)
        }
    })
}

function totalConstructionCostComputation() {
    let unitConstructionCost = $('#tab_property-appraisal input[name="unitConstructionCost"]').val()
    let unitConstructionSubTotal = $('#tab_property-appraisal input[name="unitConstructionSubTotal"]').val()
    let costOfAdditionalItemsSubTotal = $('#tab_property-appraisal input[name="costOfAdditionalItemsSubTotal"]').val()
    unitConstructionCost = formatStringToFloat(unitConstructionCost)
    unitConstructionSubTotal = formatStringToFloat(unitConstructionSubTotal)
    costOfAdditionalItemsSubTotal = formatStringToFloat(costOfAdditionalItemsSubTotal)

    let totalConstructionCost = unitConstructionCost + unitConstructionSubTotal + costOfAdditionalItemsSubTotal

    $('#tab_property-appraisal input[name="totalConstructionCost_fake"]').val(totalConstructionCost)
    $('#tab_property-appraisal input[name="totalConstructionCost"]').val(totalConstructionCost)
    propertyAppraisalComputation()
}

function propertyAppraisalComputation(){
    let totalConstructionCost =  $('#tab_property-appraisal input[name="totalConstructionCost"]').val()
    let depreciationRate =  $('#tab_property-appraisal input[name="depreciationRate"]').val()
    let depreciationCost =  $('#tab_property-appraisal input[name="depreciationCost"]').val()
    let totalPercentDepreciation =  $('#tab_property-appraisal input[name="totalPercentDepreciation"]').val()

    totalConstructionCost = formatStringToFloat(totalConstructionCost)
    depreciationRate = formatStringToInteger(depreciationRate)
    depreciationCost = formatStringToFloat(depreciationCost)
    totalPercentDepreciation = formatStringToInteger(totalPercentDepreciation)

    let marketValue = totalConstructionCost - depreciationCost
    $('#tab_property-appraisal input[name="marketValue"]').val(marketValue)
    $('#tab_property-assessment input[name="propertyAssessment[0][marketValue]"]').val(marketValue)

    setPropertyAssessmentLevel()
}

function setPropertyAssessmentLevel(){
    let kind_of_building_id = $('#tab_general-description select[name="kind_of_building_id"]').val()
    let marketValue = $('#tab_property-assessment input[name="propertyAssessment[0][marketValue]"]').val()
    marketValue = formatStringToFloat(marketValue)
    

    $.ajax({
        url: '/admin/api/faas-building-classification/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: kind_of_building_id
        },
        success: function (data) {
            if(data.length > 0) {
                data = data[0]
                let assessmentLevels = data.assessmentLevels
                let percentage = 0

                $.each(assessmentLevels, function(i, assessmentLevel) {
                    let rangeFrom = assessmentLevel.rangeFrom
                    let rangeTo = assessmentLevel.rangeTo
                    
                    rangeFrom = formatStringToFloat(rangeFrom)
                    rangeTo = formatStringToFloat(rangeTo)
                    
                    if(marketValue >= rangeFrom && rangeFrom < rangeTo) {
                        percentage = assessmentLevel.percentage
                        percentage = formatStringToInteger(percentage)
                    }
                })
                
                $('#tab_property-assessment input[name="propertyAssessment[0][assessmentLevel]').val(percentage)
                propertyAssessmentComputation()
            }
        }
    })
}

function propertyAssessmentComputation(){
    let marketValue = $('#tab_property-assessment input[name="propertyAssessment[0][marketValue]"]').val()
    let assessmentLevel = $('#tab_property-assessment input[name="propertyAssessment[0][assessmentLevel]"]').val()
    marketValue = formatStringToFloat(marketValue)
    assessmentLevel = formatStringToInteger(assessmentLevel)

    let assessmentValue = (marketValue / 100) * assessmentLevel
    $('#tab_property-assessment input[name="propertyAssessment[0][assessmentValue]"]').val(assessmentValue)
    $('#tab_property-assessment input[name="totalPropertyAssessmentMarketValue"]').val(marketValue)
    $('#tab_property-assessment input[name="totalPropertyAssessmentAssessmentValue"]').val(assessmentValue)
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