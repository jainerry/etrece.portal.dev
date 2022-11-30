$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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
                //console.log(data)
                //return false
                if (data.length > 0) {
                    //disableInputs()
                    let html = ''
                    // html += '\n\
                    // <div class="container propertyListWrapper">\n\
                    //     <div class="row propertyListHeader">\n\
                    //         <div class="col-md-2"><h6>Reference ID<h6></div>\n\
                    //         <div class="col-md-2"><h6>Primary Owner<h6></div>\n\
                    //         <div class="col-md-2"><h6>OCT/TCT No.<h6></div>\n\
                    //         <div class="col-md-2"><h6>Owner Address<h6></div>\n\
                    //         <div class="col-md-2"><h6>Classification<h6></div>\n\
                    //         <div class="col-md-2"><h6>Barangay/District<h6></div>\n\
                    //     </div>\n\
                    // '

                    // html += '\n\
                    // <div class="container propertyListWrapper">\n\
                    // '

                    $.each(data, function(i, value) {
                        let refID = '<a href="javascript:void(0)" onclick="fetchData(\''+value.id+'\')">'+value.refID+'</a>'
                        // let TDNo = '-'
                        // let ARPNo = '-'
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
                        let classification = value.kind_of_building_id
                        let barangay = value.barangay_id
                        let no_of_street = value.no_of_street
                        let structural_type_id = value.structural_type_id
                        
                        let isActive = 'Active'
                        //let isApproved = 'No'
                        if(value.TDNo !== null) { TDNo = value.TDNo }
                        if(value.ARPNo !== null) { ARPNo = value.ARPNo }
                        if(value.isActive === '0') { isActive = 'Inactive' }
                        //if(value.isApproved === '1') { isApproved = 'Yes' }
                        // html += '\n\
                        // <div class="row propertyListItem">\n\
                        //     <div class="col-md-2">\n\
                        //         '+refID+'\n\
                        //     </div>\n\
                        //     <div class="col-md-2">\n\
                        //         '+primaryOwner+'\n\
                        //     </div>\n\
                        //     <div class="col-md-2">\n\
                        //         '+oct_tct_no+'\n\
                        //     </div>\n\
                        //     <div class="col-md-2">\n\
                        //         '+value.ownerAddress+'\n\
                        //     </div>\n\
                        //     <div class="col-md-2">\n\
                        //         '+classification+'\n\
                        //     </div>\n\
                        //     <div class="col-md-2">\n\
                        //         '+barangay+'\n\
                        //     </div>\n\
                        // </div>'

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
                    //html += '</div>'
                    //disableInputs()
                    $('.rptModal .modal-body table tbody').html(html)
                    $('.rptModal').modal('show');
                }
                else {
                    //enableInputs()
                }
            }
        })
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
            
            data = data[0]

            //console.log(data)

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

            //$('#tab_main-information select[name="building_owner[]"]').val(data.primary_owner)

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
            // floorsArea[0][floorNo_fake]
            // floorsArea[0][area]
            $('#tab_general-description input[name="totalFloorArea"]').val(data.totalFloorArea)
            
            
            $('#tab_structural-characteristic select[name="roof"]').val(data.roof)

            //$('.rptModal').modal('hide');

            fetchSecondaryOwners(data.id)
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
            console.log(data)
        }
    })
}