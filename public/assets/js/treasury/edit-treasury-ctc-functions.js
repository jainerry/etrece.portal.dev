let isBusinessProfileRequired = false
let isIndividualProfileRequired = false
let isAnnualIncomeRequired = false
let isProfessionRequired = false

let otherFeesArray = []
let duplicatedOtherFee = ''

$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    citizenProfileData($('select[name="individualProfileId"]').val())

    console.log($('select[name="businessProfileId"]').val())
    
    businessProfileData($('select[name="businessProfileId"]').val())

    $('select[name="individualProfileId"]').on("change", function(){
        citizenProfileData($(this).val())
    })

    $('select[name="businessProfileId"]').on("change", function(){
        businessProfileData($(this).val())
    })

    $('select[name="ctcType"]').on("change", function(){
        let ctcType = $(this).val()
        if(ctcType === 'be9ba3b9-e6e8-46fa-828b-b57efd92a83a'){ //Individual
            isBusinessProfileRequired = false
            isIndividualProfileRequired = true
        }
        else if(ctcType === '4a89ee8c-0aae-426e-83ec-998e25692724' || ctcType === 'ae3f579e-d491-4635-80aa-49172e22cb47'){
            isBusinessProfileRequired = true
            isIndividualProfileRequired = true
        }
    })

    $('select[name="employmentStatus"]').on("change", function(){
        let employmentStatus = $(this).val()
        if(employmentStatus === 'Employed'){
            isAnnualIncomeRequired = true
            isProfessionRequired = true
        }
        else if(employmentStatus === 'Unemployed'){
            isAnnualIncomeRequired = false
            isProfessionRequired = false
        }
    })

    $('form').on("submit", function(e){
        let warningCtr = 0

        let title = '<i class="la la-exclamation-triangle"></i> Warning Alert'
        let msg = ''

        if(isIndividualProfileRequired) {
            let individualProfileId = $('select[name="individualProfileId"]').val()
            if(individualProfileId !== '' && individualProfileId !== 'null' && individualProfileId !== null) {}
            else {
                msg += '<p>Name Field is Required.</p>'
                warningCtr++
            }
        }
        if(isBusinessProfileRequired) {
            let businessProfileId = $('select[name="businessProfileId"]').val()
            if(businessProfileId !== '' && businessProfileId !== 'null' && businessProfileId !== null) {}
            else {
                msg += '<p>Business Name Field is Required.</p>'
                warningCtr++
            }
        }
        if(isAnnualIncomeRequired){
            let annualIncome = $('input[name="annualIncome"]').val()
            if(annualIncome !== '' && annualIncome !== 'null' && annualIncome !== null) {}
            else {
                msg += '<p>Anual Income Field is Required.</p>'
                warningCtr++
            }
        }
        if(isProfessionRequired){
            let profession = $('input[name="profession"]').val()
            if(profession !== '' && profession !== 'null' && profession !== null) {}
            else {
                msg += '<p>Profession Field is Required.</p>'
                warningCtr++ 
            }
        }
        
        if(warningCtr > 0){
            $('.alertMessageModal .modal-title').html(title)
            $('.alertMessageModal .modal-body').html(msg)
            $('.alertMessageModal').modal('show');
        }
        else {
            //checks if other fees have duplicates
            let haveDuplicates = checkForDuplicatesOtherFees()
            if(haveDuplicates) {
                otherFeeHaveDuplicatesAction()
                e.preventDefault()
                $('form #saveActions button[type="submit"]').attr('disabled',false)
            }
            else {
                $('form').submit()
            }
        }
    })

    feesActions()
    //computeTotalFeesAmount()

    $('.repeatable-group[bp-field-name="fees"] button.add-repeatable-element-button').on('click', function(){
        //checks if other fees have duplicates
        let haveDuplicates = checkForDuplicatesOtherFees()
        if(haveDuplicates) {
            otherFeeHaveDuplicatesAction()
            $('.repeatable-group[bp-field-name="fees"] .repeatable-element:last-child').remove()
        }

        $('div[data-repeatable-holder="fees"] .repeatable-element input.text_input_mask_currency').inputmask({ alias : "currency", prefix: '' })
        $('div[data-repeatable-holder="fees"] .repeatable-element input.text_input_mask_percent').inputmask({ alias : "numeric", min:0, max:100, suffix: '%' })
        feesActions()
    })
})

function citizenProfileData(id){
    if(id !== '' && id !== null && id !== 'null') {
        $.ajax({
            url: '/admin/api/citizen-profile/get-details',
            type: 'GET',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (data) {
                if(data.length > 0) {
                    data = data[0]
                    $('table#citizenProfileTable tbody td#address').text(data.address)
                    $('table#citizenProfileTable tbody td#gender').text(data.sex)
                    $('table#citizenProfileTable tbody td#citizenship').text('-')
                    $('table#citizenProfileTable tbody td#civilStatus').text(data.civilStatus)
                    $('table#citizenProfileTable tbody td#tin').text('-')
                    $('table#citizenProfileTable tbody td#birthDate').text(data.bdate)
                    $('table#citizenProfileTable tbody td#birthPlace').text(data.placeOfOrigin)
                    $('table#citizenProfileTable tbody td#height').text('-')
                    $('table#citizenProfileTable tbody td#weight').text('-')
                    $('table#citizenProfileTable tbody td#refId').text(data.refID)
                }
                else {
                    nameProfileData(id)
                }
            }
        })
    }
    else {
        resetCitizenProfileTable()
    }
}

function nameProfileData(id){
    if(id !== '' && id !== null && id !== 'null') {
        $.ajax({
            url: '/admin/api/name-profile/get-details',
            type: 'GET',
            dataType: 'json',
            data: {
                id: id
            },
            success: function (data) {
                if(data.length > 0) {
                    data = data[0]
                    $('table#citizenProfileTable tbody td#address').text(data.address)
                    $('table#citizenProfileTable tbody td#gender').text(data.sex)
                    $('table#citizenProfileTable tbody td#citizenship').text('-')
                    $('table#citizenProfileTable tbody td#civilStatus').text('-')
                    $('table#citizenProfileTable tbody td#tin').text('-')
                    $('table#citizenProfileTable tbody td#birthDate').text(data.bdate)
                    $('table#citizenProfileTable tbody td#birthPlace').text('-')
                    $('table#citizenProfileTable tbody td#height').text('-')
                    $('table#citizenProfileTable tbody td#weight').text('-')
                    $('table#citizenProfileTable tbody td#refId').text(data.refID)
                }
            }
        })
    }
    else {
        resetCitizenProfileTable()
    }
}

function resetCitizenProfileTable(){
    $('table#citizenProfileTable tbody td#address').text('')
    $('table#citizenProfileTable tbody td#gender').text('')
    $('table#citizenProfileTable tbody td#citizenship').text('')
    $('table#citizenProfileTable tbody td#civilStatus').text('')
    $('table#citizenProfileTable tbody td#tin').text('')
    $('table#citizenProfileTable tbody td#birthDate').text('')
    $('table#citizenProfileTable tbody td#birthPlace').text('')
    $('table#citizenProfileTable tbody td#height').text('')
    $('table#citizenProfileTable tbody td#weight').text('')
    $('table#citizenProfileTable tbody td#refId').text('')
}

function businessProfileData(id){
    if(id !== '' && id !== null && id !== 'null') {
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

                    console.log(data)

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

                    let mainOfficeAddress = data.main_office.lotNo+' '+data.main_office.noOfStreet+' '+data.main_office.barangay.name

                    $('table#businessProfileTable tbody td#owner').text(primaryOwner)
                    $('table#businessProfileTable tbody td#businessAddress').text(mainOfficeAddress)
                    $('table#businessProfileTable tbody td#naturOfBusiness').text('-')
                    $('table#businessProfileTable tbody td#refId').text(data.refID)
                }
            }
        })
    }
    else {
        resetBusinessProfileTable()
    }
}

function resetBusinessProfileTable(){
    $('table#businessProfileTable tbody td#owner').text('')
    $('table#businessProfileTable tbody td#businessAddress').text('')
    $('table#businessProfileTable tbody td#naturOfBusiness').text('')
    $('table#businessProfileTable tbody td#refId').text('')
}

function feesActions(){
    $('select.particulars').on("change", function(){ 
        let haveDuplicates = checkForDuplicatesOtherFees()
        if(haveDuplicates) {
            otherFeeHaveDuplicatesAction()
        }
        else {
            computeTotalFeesAmount()
        }
    })

    $('input.amount').on("change", function(){
        let haveDuplicates = checkForDuplicatesOtherFees()
        if(haveDuplicates) {
            otherFeeHaveDuplicatesAction()
        }
        else {
            computeTotalFeesAmount()
        }
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

function checkForDuplicatesOtherFees(){
    otherFeesArray = []
    let duplicatesCtr = 0

    $('select.particulars').each(function(){
        let otherFee = $(this).find('option:selected').text()

        if($.inArray(otherFee,otherFeesArray) !== -1) {
            duplicatesCtr++
            duplicatedOtherFee = otherFee
        }
        else {
            otherFeesArray.push(otherFee)
        }
    })

    if(duplicatesCtr > 0) { return true }
    else { return false }
}

function otherFeeHaveDuplicatesAction(){
    let title = '<i class="la la-exclamation-triangle"></i> Warning Alert'
    let msg = duplicatedOtherFee+' (Particulars) already exist. Please change, duplicate fees are not allowed.'
    $('.alertMessageModal .modal-title').html(title)
    $('.alertMessageModal .modal-body').html(msg)
    $('.alertMessageModal').modal('show');
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