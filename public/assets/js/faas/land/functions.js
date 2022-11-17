$(function () {
    //actualUse
    $('.propertyAssessment_actualUse select').on('change', function(){
        $(this).parent().parent().find('.propertyAssessment_assessmentLevel select').val($(this).val()).change()
        let assessmentLevel = $(this).parent().parent().find('.propertyAssessment_assessmentLevel select option:selected').text()
        let marketValue = $(this).parent().parent().find('.propertyAssessment_marketValue input').val()
        let assessedValue = 0

        assessmentLevel = parseFloat(assessmentLevel.replace('%',''))
        marketValue = parseFloat(marketValue.replace(',',''))

        if(isNaN(marketValue)) {
            marketValue = 0
        }
        else {
            marketValue = parseFloat(marketValue)
        }
        
        assessedValue = (marketValue / 100) * assessmentLevel
        $(this).parent().parent().find('.propertyAssessment_assessmentValue input').val(assessedValue)
    })

    //marketValue
    $('.propertyAssessment_marketValue input').on('keyup', function(){
        let assessmentLevel = $(this).parent().parent().find('.propertyAssessment_assessmentLevel select option:selected').text()
        let marketValue = $(this).val()
        let assessedValue = 0

        assessmentLevel = parseFloat(assessmentLevel.replace('%',''))
        marketValue = parseFloat(marketValue.replace(',',''))

        if(isNaN(marketValue)) {
            marketValue = 0
        }
        else {
            marketValue = parseFloat(marketValue)
        }
        
        assessedValue = (marketValue / 100) * assessmentLevel
        $(this).parent().parent().find('.propertyAssessment_assessmentValue input').val(assessedValue)
    })

    //landAppraisal_classification
    $('.landAppraisal_classification select').on('change', function(){
        $(this).parent().parent().find('.landAppraisal_actualUse select').val($(this).val()).change()
    })

    //isCitizenFromTrece_checkbox
    $('input[name="isCitizenFromTrece"]').on('change', function(){
        $('select[name="primaryOwnerId"]').val('').change()
        $('select[name="land_owner[]"]').val('').change()
        $('textarea[name="primaryOwnerText"]').val('')
        $('textarea[name="secondaryOwnersText"]').val('')
        if($(this).val() === '0') {
            $('.primaryOwnerId_select').addClass('hidden')
            $('.land_owner_select').addClass('hidden')
            $('.primaryOwnerText').removeClass('hidden')
            $('.secondaryOwnersText').removeClass('hidden')
        }
        else {
            $('.primaryOwnerId_select').removeClass('hidden')
            $('.land_owner_select').removeClass('hidden')
            $('.primaryOwnerText').addClass('hidden')
            $('.secondaryOwnersText').addClass('hidden')
        }
    })
})