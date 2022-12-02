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
        let searchByBuildingClassification = $('select[name="searchByBuildingClassification"]').val()
        let searchByStructuralType = $('select[name="searchByStructuralType"]').val()
        let searchByBarangayDistrict = $('select[name="searchByBarangayDistrict"]').val()

        $.ajax({
            url: '/admin/api/rpt-building/apply-search-filters',
            type: 'GET',
            dataType: 'json',
            data: {
                searchByPrimaryOwner: searchByPrimaryOwner,
                searchByReferenceId: searchByReferenceId,
                searchByOCTTCTNo: searchByOCTTCTNo,
                searchByBuildingClassification: searchByBuildingClassification,
                searchByStructuralType: searchByStructuralType,
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
                            <th scope="col">Classification</th>\n\
                            <th scope="col">Structural Type</th>\n\
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
                        let oct_tct_no = value.oct_tct_no
                        let classification = value.building_classification.name
                        let barangay = value.barangay.name
                        let no_of_street = value.no_of_street
                        let structural_type_id = value.structural_type.name
                        let isActive = 'Active'
                        if(value.TDNo !== null) { TDNo = value.TDNo }
                        if(value.ARPNo !== null) { ARPNo = value.ARPNo }
                        if(value.isActive === '0') { isActive = 'Inactive' }

                        html += '<tr>\n\
                            <td>'+refID+'</td>\n\
                            <td>'+primaryOwner+'</td>\n\
                            <td>'+oct_tct_no+'</td>\n\
                            <td>'+value.ownerAddress+'</td>\n\
                            <td>'+no_of_street+'</td>\n\
                            <td>'+barangay+'</td>\n\
                            <td>'+classification+'</td>\n\
                            <td>'+structural_type_id+'</td>\n\
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
        $('select[name="searchByBuildingClassification"]').val('')
        $('select[name="searchByStructuralType"]').val('')
        $('select[name="searchByBarangayDistrict"]').val('')
    })

    $('select[name="propertyAssessment[0][actualUse]"]').on('change', function(){
        let actualUse = $(this).val()
        $('select[name="propertyAssessment[0][assessmentLevel]"]').val(actualUse)
        propertyAssessmentComputation()
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
                $('#tab_property-assessment input[name="barangayCode"]').val(data.barangay.code)
                $('#tab_property-assessment input[name="buildingClassificationCode"]').val(data.building_classification.code)

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
                $('#tab_main-information textarea[name="ownerAddress"]').val(data.ownerAddress)
                $('#tab_main-information input[name="tel_no"]').val(data.tel_no)
                $('#tab_main-information input[name="owner_tin_no"]').val(data.owner_tin_no)
                $('#tab_main-information input[name="administrator"]').val(data.administrator)
                $('#tab_main-information textarea[name="admin_address"]').val(data.admin_address)
                $('#tab_main-information input[name="admin_tel_no"]').val(data.admin_tel_no)
                $('#tab_main-information input[name="admin_tin_no"]').val(data.admin_tin_no)
                $('#tab_main-information select[name="isActive"]').val(data.isActive)
                
                $('#tab_building-location input[name="no_of_street"]').val(data.no_of_street)
                $('#tab_building-location select[name="barangay_id"]').val(data.barangay_id)

                $('#tab_land-reference input[name="oct_tct_no"]').val(data.oct_tct_no)
                $('#tab_land-reference input[name="survey_no"]').val(data.survey_no)
                $('#tab_land-reference input[name="lot_no"]').val(data.lot_no)
                $('#tab_land-reference input[name="block_no"]').val(data.block_no)
                $('#tab_land-reference input[name="area"]').val(data.area)

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

                $('#tab_property-appraisal input[name="unitConstructionCost"]').val(data.unitConstructionCost)
                $('#tab_property-appraisal input[name="unitConstructionSubTotal"]').val(data.unitConstructionSubTotal)
                $('#tab_property-appraisal input[name="costOfAdditionalItemsSubTotal"]').val(data.costOfAdditionalItemsSubTotal)
                $('#tab_property-appraisal input[name="totalConstructionCost"]').val(data.totalConstructionCost)
                $('#tab_property-appraisal input[name="depreciationRate"]').val(data.depreciationRate)
                $('#tab_property-appraisal input[name="depreciationCost"]').val(data.depreciationCost)
                $('#tab_property-appraisal input[name="totalPercentDepreciation"]').val(data.totalPercentDepreciation)
                $('#tab_property-appraisal input[name="marketValue"]').val(data.marketValue)

                $('#tab_property-assessment input[name="propertyAssessment[0][marketValue]"]').val(data.marketValue)

                $('.rptModal').modal('hide');
                $('.tab-container').removeClass('hidden')
                $('#saveActions').removeClass('hidden')

                fetchSecondaryOwners(data.id)
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

function propertyAssessmentComputation(){
    let assessmentLevel = $('select[name="propertyAssessment[0][assessmentLevel]"] option:selected').text()
    let marketValue = $('input[name="marketValue"]').val()
    let assessedValue = 0

    assessmentLevel = parseFloat(assessmentLevel.replaceAll('%',''))
    marketValue = formatStringToFloat(marketValue)
    
    assessedValue = (marketValue / 100) * assessmentLevel
    $('input[name="propertyAssessment[0][assessedValue]"]').val(assessedValue)
}