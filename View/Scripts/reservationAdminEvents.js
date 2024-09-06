function nextPage()
{
    var currentPeriod = parseInt(window.location.href.split('/')[6]);
    console.log(currentPeriod);
    currentPeriod++;
    document.location.href = "./" + currentPeriod;
}
function previousPage()
{
    var currentPeriod = parseInt(window.location.href.split('/')[6]);
    console.log(currentPeriod);
    if(currentPeriod > 1)
    {
        currentPeriod--;
        document.location.href = "./" + currentPeriod;
    }
}
function editRoom()
{
    var table = document.getElementById("table_rows");
    var checkboxes = table.getElementsByTagName("input");
    var count = 0;
    var uniqueID = -1;
    for(var i = 0; i < checkboxes.length; i++)
    {
        if(checkboxes[i].checked == true)
        {
            if(count < 1)
            {
                uniqueID = checkboxes[i].id;
                count++;
            }
            else 
            {
                count = 9999;
                break;
            }
        }
    }
    if(count == 1)
    {
        document.location.href = "../editReservationForm/" + uniqueID;
    }
    else if(count > 1)
    {
        alert("Erreur : vous ne pouvez séléctionner qu'une seule réservation à la fois.");
    }
    else 
    {
        alert("Erreur : vous devez séléctionner une chambre avant de pouvoir la modifier.");
    }
}
function deleteReservations()
{
    var table = document.getElementById("table_rows");
    var checkboxes = table.getElementsByTagName("input");
    var uniqueIDS = [];
    for(var i = 0; i < checkboxes.length; i++)
    {
        if(checkboxes[i].checked == true)
        {
            uniqueIDS.push(checkboxes[i].id);
        }
    }
    if(uniqueIDS.length > 0)
    {
        if(confirm("Attention, êtes vous certain de vouloir supprimer la séléction ? Cette action est irréversible."))
        {
            $.post("../deleteReservations", {ids : uniqueIDS}, function (data, result)
            {
                if(result)
                {
                    if(data == 1)
                    {
                        alert("La séléction a été supprimée avec succès.");
                        document.location.reload();
                    }
                    else 
                    {
                        alert("Erreur interne lors de la suppression, veuillez réessayer.");
                    }
                }
            });
        }
    }
    else 
    {
        alert("Erreur : vous devez séléctionner au minimum une chambre pour effectuer une suppression.");
    }
}