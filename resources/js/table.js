function addRow(){
    var row = $('#row_example').clone();
    row.removeAttr('id')
    row.removeAttr('hidden')
    $('.table').append(row)
    initAutocomplete()

}