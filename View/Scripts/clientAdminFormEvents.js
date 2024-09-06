function editClient()
{
    var table = document.getElementById("table_rows");
    var checkboxes = table.getElementsByTagName("input");
    var count = 0;
    var id = -1;
    for(var i = 0; i < checkboxes.length; i++)
    {
        if(checkboxes[i].checked == true)
        {
            if(count < 1)
            {
                id = checkboxes[i].id;
                count++;
            }
            else 
            {
                count++;
                break;
            }
        }
    }
    if(count == 1)
    {
        document.location.href = '../adminEditClient/' + id;
    }
    else if(count > 1)
    {
        alert("Erreur : vous ne pouvez séléctionner qu'un seul client à la fois pour modifier ses informations."); 
    }
    else
    {
        alert("Erreur : vous devez séléctionner un client afin de pouvoir modifier ses informations.");
    }
}
function deleteClient()
{
    var table = document.getElementById("table_rows");
    var checkboxes = table.getElementsByTagName("input");
    var ids = [];
    for(var i = 0; i < checkboxes.length; i++)
    {
        if(checkboxes[i].checked == true)
        {
            ids.push(checkboxes[i].id);
        }
    }
    if(ids.length > 0)
    {
        if(confirm("Attention, êtes-vous sûr de vouloir supprimer la séléction ? Cette action est irréversible."))
        {
            $.post("../removeClients", {clientids : ids}, function(data, result)
            {
                if(result)
                {
                    if(data == 1)
                    {
                        alert("La séléction a bien été supprimée.");
                        document.location.reload();
                    }
                    else 
                    {
                        alert("Erreur interne lors de la suppression.");
                    }
                }
            });
        }
    }
    else 
    {
        alert("Erreur : vous devez au minimum séléctionner un client pour lancer une suppression.");
    }
}
function nextPage()
{
    var currentPeriod = parseInt(window.location.href.split('/')[6]);
    currentPeriod++;
    document.location.href = "./" + currentPeriod;
}
function previousPage()
{
    var currentPeriod = parseInt(window.location.href.split('/')[6]);
    if(currentPeriod > 1)
    {
        currentPeriod--;
        document.location.href = "./" + currentPeriod;
    }
}