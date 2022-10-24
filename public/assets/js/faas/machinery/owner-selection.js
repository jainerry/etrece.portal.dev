$(function(){
    console.log('owner-selection.js')
    getPrimaryOwners()
    getSecondaryOwners()
})

function getPrimaryOwners() {
    $('select[name="machineryPrimaryOwner"]').select2({
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
    })
}

function getSecondaryOwners() {
    $('select[name="machinerySecondaryOwners[]"]').select2({
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
    })
}

function formatRepo (repo) {
    if (repo.loading) {
        return repo.citizenProfileData;
    }
    const $container = $(
        "<div class='select2-result-repository clearfix citizen-profile-search-results'>" +
            "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__citizenProfileData'>Full Name: <b>"+repo.citizenProfileData+"</b></div>" +
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
    return repo.citizenProfileData;
}
