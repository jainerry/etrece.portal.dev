$(function(){
    setTimeout(function(){ 
        console.log('removeBulkCheckboxesOnDatatable.js')
        console.log($('#crudTable_wrapper #crudTable .crud_bulk_actions_checkbox, #crudTable_wrapper table.dataTable .crud_bulk_actions_checkbox'))
        $('#crudTable_wrapper #crudTable .crud_bulk_actions_checkbox, #crudTable_wrapper table.dataTable .crud_bulk_actions_checkbox').text('');
        $('#crudTable_wrapper #crudTable .crud_bulk_actions_checkbox, #crudTable_wrapper table.dataTable .crud_bulk_actions_checkbox').remove();
 
    }, 1000);
})
    