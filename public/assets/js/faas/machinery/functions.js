$(function () {
    //actualUse
    $('.actualUse select').on('change', function(){
        $(this).parent().parent().find('.assessmentLevel select').val($(this).val()).change()
        let assessmentLevel = $(this).parent().parent().find('.assessmentLevel select option:selected').text()
        let marketValue = $(this).parent().parent().find('.marketValue input').val()
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
        $(this).parent().parent().find('.assessedValue input').val(assessedValue)
    })

    //marketValue
    $('.marketValue input').on('keyup', function(){
        let assessmentLevel = $(this).parent().parent().find('.assessmentLevel select option:selected').text()
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
        $(this).parent().parent().find('.assessedValue input').val(assessedValue)
    })
})