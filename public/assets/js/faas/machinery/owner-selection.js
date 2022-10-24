$(function(){
    //console.log('owner-selection.js')

    // 
    let id = $('form input[name="id"]').val()

    if(id === undefined) {
        getPrimaryOwners()
        getSecondaryOwners()
    }
    else {
        let ids = $('select[name="machinery_owner[]"]').val()
        //console.log($('select[name="machinery_owner[]"]').val())
        $('select[name="machinery_owner[]"] option').each(function(){
            console.log($(this).attr('value'))
            $(this).text('abc')
        })
        
        // $('select[name="machinery_owner[]"]').select2("val", ['416', '247', '381'])

        setTimeout(function(){ 

            var arrayOfValues = ['416', '247', '381']
            //$("#elementid").select2("val",arrayOfValues)
            // $('select[name="machinery_owner[]"]').val(['416', '247', '381']).trigger('change');
            $('select[name="machinery_owner[]"]').select2("val",arrayOfValues)

        }

            , 2000);
    }

    
})

function getPrimaryOwners() {
    $('select[name="primaryOwner"]').select2({
        ajax: {
            url: '/admin/api/citizen-profile/ajaxsearch',
            type: "GET",
            dataType: 'text',
            data: function (params) {
                console.log(params)
                let searchPhrase = ''
                if(params.term !== undefined) { searchPhrase = params.term}
                const query = {
                    q: searchPhrase
                };
                return query;
            },
            processResults: function (data) {
                const arr = JSON.parse(data);
                console.log(arr)
                if(arr.length > 0){
                    return {
                        results:  arr
                    };
                }else{
                    return {
                        results:  []
                    };
                }
            },
            cache: true
        },
        language: {
            noResults: function (params) {
                return "No Result Found";
            }
        },
        placeholder: 'Select Primary Owner',
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection,
        multiple: false,
        theme: 'bootstrap',
        allowClear: true,
    })
}

function getSecondaryOwners() {
    $('select[name="machinery_owner[]"]').select2({
        ajax: {
            url: '/admin/api/citizen-profile/ajaxsearch',
            type: "GET",
            dataType: 'text',
            data: function (params) {
                console.log(params)
                let searchPhrase = ''
                if(params.term !== undefined) { searchPhrase = params.term}
                const query = {
                    q: searchPhrase
                };
                return query;
            },
            processResults: function (data) {
                const arr = JSON.parse(data);
                console.log(arr)
                if(arr.length > 0){
                    return {
                        results:  arr
                    };
                }else{
                    return {
                        results:  []
                    };
                }
            },
            cache: true
        },
        language: {
            noResults: function (params) {
                return "No Result Found";
            }
        },
        placeholder: 'Select Secondary Owners',
        minimumInputLength: 1,
        templateResult: formatRepo,
        templateSelection: formatRepoSelection,
        multiple: true,
        theme: 'bootstrap',
        allowClear: true,
    })
}

function formatRepo (repo) {
    if (repo.loading) {
        return repo.fullname;
    }
    const $container = $(
        "<div class='select2-result-repository clearfix citizen-profile-search-results'>" +
            "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__citizenProfileData'>Full Name: <b>"+repo.fullname+"</b></div>" +
                "<div class='select2-result-repository__suffix'>Suffix: <b>"+repo.suffix+"</b></div>" +
                "<div class='select2-result-repository__refId'>Reference ID: <b>"+repo.refId+"</b></div>" +
                "<div class='select2-result-repository__bdate'>Birth Date: <b>"+repo.bdate+"</b></div>" +
                "<div class='select2-result-repository__barangay'>Barangay: <b>"+repo.barangay.name+"</b></div>" +
                "<div class='select2-result-repository__address'>Address: <b>"+repo.address+"</b></div>" +
            "</div>" +
        "</div>"
    );
    return $container;
}

function formatRepoSelection (repo) {
    return repo.fullname;
}
