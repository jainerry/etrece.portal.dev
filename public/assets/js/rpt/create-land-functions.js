let url = window.location.href
let protocol = new URL(url).protocol
let hostname = new URL(url).hostname

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
        let searchByPinId = $('input[name="searchByPinId"]').val()
        let searchBySurveyNo = $('input[name="searchBySurveyNo"]').val()
        let searchByNoOfStreet = $('input[name="searchByNoOfStreet"]').val()

        $.ajax({
            url: '/admin/api/rpt-land/apply-search-filters',
            type: 'GET',
            dataType: 'json',
            data: {
                searchByPrimaryOwner: searchByPrimaryOwner,
                searchByReferenceId: searchByReferenceId,
                searchByOCTTCTNo: searchByOCTTCTNo,
                searchByBarangayDistrict: searchByBarangayDistrict,
                searchByPinId: searchByPinId,
                searchBySurveyNo: searchBySurveyNo,
                searchByNoOfStreet: searchByNoOfStreet
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
                            <th scope="col">PIN</th>\n\
                            <th scope="col">Survey No.</th>\n\
                            <th scope="col">No. of Street</th>\n\
                            <th scope="col">Barangay/District</th>\n\
                            <th scope="col">Address</th>\n\
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
                        if(value.ARPNo !== null) { ARPNo = value.ARPNo }
                        if(value.isActive === '0') { isActive = 'Inactive' }
                        let address = value.ownerAddress

                        html += '<tr>\n\
                            <td>'+refID+'</td>\n\
                            <td>'+primaryOwner+'</td>\n\
                            <td>'+octTctNo+'</td>\n\
                            <td>'+value.pin+'</td>\n\
                            <td>'+value.survey_no+'</td>\n\
                            <td>'+noOfStreet+'</td>\n\
                            <td>'+barangay+'</td>\n\
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
        $('input[name="searchByPinId"]').val('')
        $('input[name="searchBySurveyNo"]').val('')
        $('input[name="searchByNoOfStreet"]').val('')
    })

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

    landAppraisalActions()
    otherImprovementsActions()
    marketValueActions()

    //Land Appraisal Tab > Land Appraisal add item action
    $('.repeatable-group[bp-field-name="landAppraisal"] button.add-repeatable-element-button').on('click', function(){
        $('div[data-repeatable-holder="landAppraisal"] .repeatable-element input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
        $('div[data-repeatable-holder="landAppraisal"] .repeatable-element input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
        landAppraisalActions()
    })

    //If Other Improvements Tab is being clicked
    $('a.nav-link[tab_name="other-improvements"]').on('click', function(){
        landAreaLeftComputation()
        landAppraisalCheckIfClassificationHasDuplicates()
    })

    //Other Improvements Tab > Other Improvements add item action
    $('.repeatable-group[bp-field-name="otherImprovements"] button.add-repeatable-element-button').on('click', function(){
        $('div[data-repeatable-holder="otherImprovements"] .repeatable-element input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
        $('div[data-repeatable-holder="otherImprovements"] .repeatable-element input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
        otherImprovementsActions()
    })

    //Market Value Tab > Market Value add item action
    $('.repeatable-group[bp-field-name="marketValue"] button.add-repeatable-element-button').on('click', function(){
        $('div[data-repeatable-holder="marketValue"] .repeatable-element input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
        $('div[data-repeatable-holder="marketValue"] .repeatable-element input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
        marketValueActions()
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

                $('#tab_main-information input[name="pin"]').val(data.pin)
                $('#tab_main-information input[name="octTctNo"]').val(data.octTctNo)
                $('#tab_main-information input[name="survey_no"]').val(data.survey_no)

                $('#tab_main-information input[name="lotNo"]').val(data.lotNo)
                $('#tab_main-information input[name="blkNo"]').val(data.blkNo)
                $('#tab_main-information input[name="totalArea"]').val(data.totalArea)

                $('#tab_main-information input[name="noOfStreet"]').val(data.noOfStreet)
                $('#tab_main-information select[name="barangayId"]').val(data.barangayId)

                $('#tab_main-information input[name="propertyBoundaryNorth"]').val(data.propertyBoundaryNorth)
                $('#tab_main-information input[name="propertyBoundaryEast"]').val(data.propertyBoundaryEast)
                $('#tab_main-information input[name="propertyBoundarySouth"]').val(data.propertyBoundarySouth)
                $('#tab_main-information input[name="propertyBoundaryWest"]').val(data.propertyBoundaryWest)

                if(data.landSketch !== '' && data.landSketch !== 'null' && data.landSketch !== null) {
                    let landSketchUrl = protocol+"//"+hostname+"/"+data.landSketch
                    $('#tab_main-information div.landSketch img').attr('src', landSketchUrl)
                }
                else {
                    let landSketchUrl = protocol+"//"+hostname+"/uploads/images/defaults/no-image-available.jpg"
                    $('#tab_main-information div.landSketch img').attr('src', landSketchUrl)
                }

                $('#tab_main-information select[name="primaryOwnerId"]').append('<option value="'+data.primaryOwnerId+'">'+primaryOwner+'</option>')
                $('#tab_main-information select[name="primaryOwnerId"]').val(data.primaryOwnerId)

                fetchSecondaryOwners(data.id)

                $('#tab_main-information textarea[name="ownerAddress"]').val(data.ownerAddress)
                $('#tab_main-information input[name="ownerTelephoneNo"]').val(data.ownerTelephoneNo)
                $('#tab_main-information input[name="ownerTinNo"]').val(data.ownerTinNo)

                $('#tab_main-information textarea[name="administrator"]').val(data.administrator)
                $('#tab_main-information textarea[name="administratorAddress"]').val(data.administratorAddress)
                $('#tab_main-information input[name="administratorTelephoneNo"]').val(data.administratorTelephoneNo)
                $('#tab_main-information input[name="administratorTinNo"]').val(data.administratorTinNo)

                $('#tab_main-information select[name="isActive"]').val(data.isActive)

                $('.repeatable-group[bp-field-name="propertyAssessment"] button.add-repeatable-element-button').addClass('hidden')

                $('.rptModal').modal('hide');
                $('.tab-container').removeClass('hidden')
                $('#saveActions').removeClass('hidden')
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

function setClassification(classification,dataRowNumber){
    $('#tab_land-appraisal select.actualUse[data-row-number="'+dataRowNumber+'"]').val(classification)
    let classificationCode = $('#tab_land-appraisal select.actualUse[data-row-number="'+dataRowNumber+'"] option:selected').text()
    $('#tab_land-appraisal input.actualUse_fake[data-row-number="'+dataRowNumber+'"]').val(classificationCode)
    $.ajax({
        url: '/admin/api/faas-land-classification/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: classification
        },
        success: function (data) {
            if(data.length > 0) {
                data = data[0]
                let unitValuePerArea = data.unitValuePerArea
                $('#tab_land-appraisal input.unitValuePerArea[data-row-number="'+dataRowNumber+'"]').val(unitValuePerArea)
                landAppraisalComputation(dataRowNumber)
            }
        }
    })
}

function landAppraisalComputation(dataRowNumber){
    let unitValuePerArea = $('#tab_land-appraisal input.unitValuePerArea[data-row-number="'+dataRowNumber+'"]').val()
    let area = $('#tab_land-appraisal input.area[data-row-number="'+dataRowNumber+'"]').val()
    unitValuePerArea = formatStringToFloat(unitValuePerArea)
    area = formatStringToFloat(area)
    let baseMarketValue = unitValuePerArea * area
    $('#tab_land-appraisal input.baseMarketValue[data-row-number="'+dataRowNumber+'"]').val(baseMarketValue)
    landAppraisalComputationTotal()
}

function landAppraisalComputationTotal(){
    let baseMarketValueTotal = 0
    $('#tab_land-appraisal input.baseMarketValue').each(function(){
        let baseMarketValue = $(this).val()
        baseMarketValue = formatStringToFloat(baseMarketValue)
        baseMarketValueTotal += baseMarketValue
    })
    $('#tab_land-appraisal input[name="totalLandAppraisalBaseMarketValue"]').val(baseMarketValueTotal)
    setPropertyAssessmentValues()
}

function landAreaLeftComputation(dataRowNumber=null){
    let landAreaLeft = 0
    let landApraisalTotalArea = 0
    let totalArea = $('#tab_main-information input[name="totalArea"]').val()
    totalArea = formatStringToFloat(totalArea)
    $('#tab_land-appraisal input.area').each(function(){
        let area = $(this).val()
        area = formatStringToFloat(area)
        landApraisalTotalArea += area
    })
    
    if(landApraisalTotalArea > totalArea) {
        let title = '<i class="la la-exclamation-triangle"></i> Warning Alert'
        let msg = 'Please check your inputs on Land Appraisal Tab. The sum of areas entered on Land Appraisal are more than the value of the Land actual total area, which is '+totalArea+' sqm.'
        if(dataRowNumber !== null) {
            $('#tab_land-appraisal input.area[data-row-number="'+dataRowNumber+'"]').val('')
            $('#tab_land-appraisal input.baseMarketValue[data-row-number="'+dataRowNumber+'"]').val('')
        }
        $('.alertMessageModal .modal-title').html(title)
        $('.alertMessageModal .modal-body').html(msg)
        $('.alertMessageModal').modal('show');
    }
    else {
        if(landApraisalTotalArea === totalArea) {
            $('.repeatable-group[bp-field-name="landAppraisal"] button.add-repeatable-element-button').addClass('hidden')
        }
        else {
            landAreaLeft = totalArea - landApraisalTotalArea
            $('.repeatable-group[bp-field-name="landAppraisal"] button.add-repeatable-element-button').removeClass('hidden')
        }
        $('#tab_land-appraisal input[name="landAreaLeft"]').val(landAreaLeft)
    }
}

function landAppraisalActions(){
    //Land Appraisal Tab > change classification action
    $('#tab_land-appraisal select.classification').on('change', function(){
        let classification = $(this).val()
        let dataRowNumber = $(this).attr('data-row-number')

        //check if classification already exist
        let isClassificationExist = 0
        $('#tab_land-appraisal select.classification').each(function(){
            let thisClassification = $(this).val()
            if(thisClassification === classification) {
                isClassificationExist++
            }
        })

        if(isClassificationExist > 1) {
            $(this).val('')
            let title = '<i class="la la-exclamation-triangle"></i> Warning Alert'
            let msg = 'Please check your inputs on Land Appraisal Tab. Classification on Land Appraisal must be unique and should not have a duplicate.'
            $('.alertMessageModal .modal-title').html(title)
            $('.alertMessageModal .modal-body').html(msg)
            $('.alertMessageModal').modal('show');
        }
        else {
            setClassification(classification,dataRowNumber)
        }
    })

    //Land Appraisal Tab > change area action
    $('#tab_land-appraisal input.area').on('change', function(){
        let dataRowNumber = $(this).attr('data-row-number')
        let unitValuePerArea = $('#tab_land-appraisal input.unitValuePerArea[data-row-number="'+dataRowNumber+'"]').val()
        let classification = $('#tab_land-appraisal select.classification[data-row-number="'+dataRowNumber+'"]').val()
        if(unitValuePerArea === '') {
            if(classification !== '') {
                setClassification(classification,dataRowNumber)
            }
        }
        else {
            landAppraisalComputation(dataRowNumber)
        }
        landAreaLeftComputation(dataRowNumber)
    })
}

function landAppraisalCheckIfClassificationHasDuplicates(){
    let classifications = []
    $('#tab_land-appraisal select.classification').each(function(){
        let thisClassification = $(this).val()
        if($.inArray(thisClassification,classifications) === -1) {
            classifications.push(thisClassification)
        }
        else {
            let title = '<i class="la la-exclamation-triangle"></i> Warning Alert'
            let msg = 'Please check your inputs on Land Appraisal Tab. Classification on Land Appraisal must be unique and should not have a duplicate.'
            $('.alertMessageModal .modal-title').html(title)
            $('.alertMessageModal .modal-body').html(msg)
            $('.alertMessageModal').modal('show');
        }
    })
}

function otherImprovementsActions(){
    $('#tab_other-improvements input.totalNumber').on('change', function(){
        let dataRowNumber = $(this).attr('data-row-number')
        otherImprovementsComputation(dataRowNumber)
    })

    $('#tab_other-improvements input.unitValue').on('change', function(){
        let dataRowNumber = $(this).attr('data-row-number')
        otherImprovementsComputation(dataRowNumber)
    })
}

function otherImprovementsComputation(dataRowNumber){
    let totalNumber = $('#tab_other-improvements input.totalNumber[data-row-number="'+dataRowNumber+'"]').val()
    let unitValue = $('#tab_other-improvements input.unitValue[data-row-number="'+dataRowNumber+'"]').val()
    let baseMarketValue = 0
    unitValue = formatStringToFloat(unitValue)
    totalNumber = formatStringToFloat(totalNumber)
    baseMarketValue = totalNumber * unitValue
    $('#tab_other-improvements input.baseMarketValue[data-row-number="'+dataRowNumber+'"]').val(baseMarketValue)
    otherImprovementsComputationTotal()
}

function otherImprovementsComputationTotal(){
    let baseMarketValueTotal = 0
    $('#tab_other-improvements input.baseMarketValue').each(function(){
        let baseMarketValue = $(this).val()
        baseMarketValue = formatStringToFloat(baseMarketValue)
        baseMarketValueTotal += baseMarketValue
    })
    $('#tab_other-improvements input[name="totalOtherImprovementsBaseMarketValue"]').val(baseMarketValueTotal)
    setPropertyAssessmentValues()
}

function marketValueActions(){
    $('#tab_market-value input.baseMarketValue').on('change', function(){
        let dataRowNumber = $(this).attr('data-row-number')
        marketValueComputation(dataRowNumber)
    })

    $('#tab_market-value input.adjustmentFactorPercentage').on('change', function(){
        let dataRowNumber = $(this).attr('data-row-number')
        marketValueComputation(dataRowNumber)
    })
}

function marketValueComputation(dataRowNumber){
    let baseMarketValue = $('#tab_market-value input.baseMarketValue[data-row-number="'+dataRowNumber+'"]').val()
    let adjustmentFactorPercentage = $('#tab_market-value input.adjustmentFactorPercentage[data-row-number="'+dataRowNumber+'"]').val()
    let valueAdjustment = 0
    let marketValue = 0
    baseMarketValue = formatStringToFloat(baseMarketValue)
    adjustmentFactorPercentage = formatStringToInteger(adjustmentFactorPercentage)
    valueAdjustment = (baseMarketValue / 100) * adjustmentFactorPercentage
    $('#tab_market-value input.valueAdjustment[data-row-number="'+dataRowNumber+'"]').val(valueAdjustment)
    if(baseMarketValue > valueAdjustment) {
        marketValue = baseMarketValue + valueAdjustment
        $('#tab_market-value input.marketValue[data-row-number="'+dataRowNumber+'"]').val(marketValue)
        marketValueComputationTotal()
    }
}

function marketValueComputationTotal(){
    let marketValueTotal = 0
    $('#tab_market-value input.marketValue').each(function(){
        let marketValue = $(this).val()
        marketValue = formatStringToFloat(marketValue)
        marketValueTotal += marketValue
    })
    $('#tab_market-value input[name="totalMarketValueMarketValue"]').val(marketValueTotal)
    setPropertyAssessmentValues()
}

function setPropertyAssessmentValues(){
    let landAppraisalRepeatable = $('.repeatable-group[bp-field-name="landAppraisal"] .repeatable-element')
    let landAppraisalRepeatableLen = landAppraisalRepeatable.length

    let propertyAssessmentRepeatable = $('.repeatable-group[bp-field-name="propertyAssessment"] .repeatable-element')
    let propertyAssessmentRepeatableLen = propertyAssessmentRepeatable.length

    for (let index = 0; index < landAppraisalRepeatableLen; index++) {
        let dataRowNumber = index + 1
        
        if(propertyAssessmentRepeatable[index]) {}
        else {
            $('.repeatable-group[bp-field-name="marketValue"] button.add-repeatable-element-button').trigger('click')
        }
        getClassificationAssessmentLevel(dataRowNumber)
    }
}

function getClassificationAssessmentLevel(dataRowNumber){
    let classification = $('#tab_land-appraisal select.classification[data-row-number="'+dataRowNumber+'"]').val()
    let actualUse_fake = $('#tab_land-appraisal input.actualUse_fake[data-row-number="'+dataRowNumber+'"]').val()
    
    $('#tab_property-assessment select.actualUse[data-row-number="'+dataRowNumber+'"]').val(classification)
    $('#tab_property-assessment input.actualUse_fake[data-row-number="'+dataRowNumber+'"]').val(actualUse_fake)

    let landAppraisalBaseMarketValue = $('#tab_land-appraisal input.baseMarketValue[data-row-number="'+dataRowNumber+'"]').val()
    let totalOtherImprovementsBaseMarketValue = $('#tab_other-improvements input.totalOtherImprovementsBaseMarketValue').val()
    let totalMarketValueMarketValue = $('#tab_market-value input.totalMarketValueMarketValue').val()

    landAppraisalBaseMarketValue = formatStringToFloat(landAppraisalBaseMarketValue)
    totalOtherImprovementsBaseMarketValue = formatStringToFloat(totalOtherImprovementsBaseMarketValue)
    totalMarketValueMarketValue = formatStringToFloat(totalMarketValueMarketValue)

    

    let propertyAssessmentMarketValue = 0

    $.ajax({
        url: '/admin/api/faas-land-classification/get-details',
        type: 'GET',
        dataType: 'json',
        data: {
            id: classification
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
                    
                    if(landAppraisalBaseMarketValue >= rangeFrom && rangeFrom < rangeTo) {
                        percentage = assessmentLevel.percentage
                        percentage = formatStringToInteger(percentage)
                    }
                })
                $('#tab_property-assessment input.assessmentLevel[data-row-number="'+dataRowNumber+'"]').val(percentage)
                propertyAssessmentMarketValue = landAppraisalBaseMarketValue + totalOtherImprovementsBaseMarketValue + totalMarketValueMarketValue
                $('#tab_property-assessment input.marketValue[data-row-number="'+dataRowNumber+'"]').val(propertyAssessmentMarketValue)
                propertyAssessmentComputation(dataRowNumber)
            }
        }
    })
}

function propertyAssessmentComputation(dataRowNumber){
    let marketValue = $('#tab_property-assessment input.marketValue[data-row-number="'+dataRowNumber+'"]').val()
    let assessmentLevel = $('#tab_property-assessment input.assessmentLevel[data-row-number="'+dataRowNumber+'"]').val()
    let assessmentValue = 0
    marketValue = formatStringToFloat(marketValue)
    assessmentLevel = formatStringToInteger(assessmentLevel)
    assessmentValue = (marketValue / 100) * assessmentLevel
    $('#tab_property-assessment input.assessmentValue[data-row-number="'+dataRowNumber+'"]').val(assessmentValue)
    propertyAssessmentComputationTotal()
}

function propertyAssessmentComputationTotal(){
    let assessmentValueTotal = 0
    let marketValueTotal = 0
    $('#tab_property-assessment input.assessmentValue').each(function(){
        let assessmentValue = $(this).val()
        assessmentValue = formatStringToFloat(assessmentValue)
        assessmentValueTotal += assessmentValue
    })

    $('#tab_property-assessment input.marketValue').each(function(){
        let marketValue = $(this).val()
        marketValue = formatStringToFloat(marketValue)
        marketValueTotal += marketValue
    })

    $('#tab_property-assessment input[name="totalPropertyAssessmentMarketValue"]').val(marketValueTotal)
    $('#tab_property-assessment input[name="totalPropertyAssessmentAssessmentValue"]').val(assessmentValueTotal)
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