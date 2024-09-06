function toggleModal()
{
    var modal = document.getElementById("edit_setting");
    if(modal.className == "modal is-active")
    {
        modal.className = "modal";
    }
    else 
    {
        modal.className = "modal is-active";
    }
}
function editSetting()
{
    toggleModal();
    var table = document.getElementById('table_rows');
    var checkboxes = table.getElementsByTagName("input");
    var sid = 0;
    var count = 0;
    for(var i = 0; i < checkboxes.length; i++)
    {
        if(checkboxes[i].checked == true)
        {
            if(count < 1)
            {
                sid = checkboxes[i].id;
                count++;
            }
            else 
            {
                count++;
                break;
            }
        }
    }
    if(count > 1)
    {
        alert("Erreur, vous ne pouvez séléctionner qu'un seul paramètre.");
        toggleModal();
    }
    else if(count == 0)
    {
        alert("Erreur, vous devez séléctionner un paramètre à modifier.");
        toggleModal();
    }
    else 
    {
        $.get("../getSetting/" + sid.toString(), function (data, status)
        {
            if(status)
            {
                var setting = JSON.parse(data);
                var _id = document.getElementById("id");
                var _clef = document.getElementById("clef");
                var _valeur = document.getElementById("valeur");
                _id.value = setting.id.toString();
                _clef.value = setting.clef.toString();
                _valeur.value = setting.valeur.toString();
            }
        });
        
    }
   
}
function next()
{
    var currentPage = parseInt(window.location.href.split('/')[6]);
    currentPage++;
    document.location.href = "./" + currentPage;
}
function previous()
{
    var currentPage = parseInt(window.location.href.split('/')[6]);
    if(currentPage > 1)
    {
        currentPage--;
        document.location.href = "./" + currentPage;
    }
}