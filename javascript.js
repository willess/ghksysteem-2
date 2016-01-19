
//wanneer er op de + button is geklikt voeg je een nieuwe rij toe
function addRow(tableID) {
    var table = document.getElementById(tableID);
    var rowCount = table.rows.length;
    if(rowCount < 25){
        var row = table.insertRow(rowCount);
        var colCount = table.rows[0].cells.length;
        for(var i=0; i<colCount; i++) {
            var newcell = row.insertCell(i);
            newcell.innerHTML = table.rows[0].cells[i].innerHTML;
        }
    }else{
        alert("Je kan niet meer dan 25 ritten toevoegen!");
    }
}

//wanneer er op verwijder rit is geklikt, verwijder je de aangevinkte rit
function deleteRow(tableID) {
    var table = document.getElementById(tableID);
    var rowCount = table.rows.length;
    for(var i=0; i<rowCount; i++) {
        var row = table.rows[i];
        var chkbox = row.cells[0].childNodes[0];
        if(null != chkbox && true == chkbox.checked) {
            if(rowCount <= 1) {
                alert("Je kan niet alle ritten verwijderen!");
                break;
            }
            table.deleteRow(i);
            rowCount--;
            i--;
        }
    }
}
