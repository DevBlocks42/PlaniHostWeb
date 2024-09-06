function toggleModal(modalId)
{
    var modal = document.getElementById(modalId);
    if(modal.className == "modal is-active")
    {
        modal.className = "modal";
    }
    else 
    {
        modal.className = "modal is-active";
    }
}
async function deleteImage(roomId, imageName)
{
    $.post("removeRoomImage/", { id : roomId, name : imageName}, function(data, status)
    {
        if(data == 1)
        {
            alert('L\'image a bien été supprimée.');
            toggleModal('edit_add_room');
            editRoom();
        }
    });
    return false;
}
function addRoom()
{
    document.getElementById('modal-title').textContent = "Ajouter une chambre";
    document.getElementById('modal-form').action = 'addRoom';
    document.getElementById("numero").value = "";
    document.getElementById("etage").value = "";
    document.getElementById("prix").value = "";
    document.getElementById("titre").value = "";
    document.getElementById("description").value = "";
    document.getElementById("capacite").value = "";
    document.getElementById("image-placeholder").innerHTML = "";
    toggleModal('edit_add_room');
}
function editRoom()
{
    document.getElementById('modal-title').textContent = "Modifier une chambre";
    document.getElementById('modal-form').action = 'editRoom';
    var table = document.getElementById('table_rows');
    var checkboxes = table.getElementsByTagName("input");
    var count = 0;
    var ids = [];
    for(var i = 0; i < checkboxes.length; i++)
    {
        if(checkboxes[i].checked == true)
        {
            ids.push(checkboxes[i].id);
            if(count > 1)
            {
                count = 2;
                break;
            }
            count++;
        }
    }
    if(count == 1)
    {
        $.get("getRoomInfos/" + ids[0].toString(), function (data, status)
        {
            if(status)
            {
                var room = JSON.parse(data);
                document.getElementById("numero").value = room.numero.toString();
                document.getElementById("etage").value = room.etage.toString();
                document.getElementById("prix").value = room.prix.toString();
                document.getElementById("titre").value = room.titre.toString();
                document.getElementById("description").value = room.description.toString();
                document.getElementById("capacite").value = room.capacite.toString();
                var imgs = room.images.split(";");
                document.getElementById("image-placeholder").innerHTML = "";  
                for(var i = 0; i < imgs.length; i++)
                {
                    document.getElementById("image-placeholder").innerHTML += "<div class = 'block has-text-centered'><button type = 'button' onclick = 'deleteImage(" + room.id + ",\"" + imgs[i].toString() + "\");' id = 'delete_image' class = 'button'>🗑 Supprimer</button></div><div class = 'block'><figure class = 'figure image is-fullwidth'><img src = '/~sysadmin/PlaniHost/Images/" + imgs[i] + "'></figure></div>";
                }
                toggleModal('edit_add_room');
            } 
        });
    }
    else if(count == 0)
    {
        alert('Vous devez séléctionner une chambre afin de pouvoir la modifier.');
    }
    else 
    {
        alert("Erreur, vous ne pouvez séléctionner qu'une seule chambre afin de pouvoir la modifier.");
    }
}
function deleteRooms()
{
    var table = document.getElementById('table_rows');
    var checkboxes = table.getElementsByTagName("input");
    var count = 0;
    var ids = [];
    for(var i = 0; i < checkboxes.length; i++)
    {
        if(checkboxes[i].checked == true)
        {
            ids.push(checkboxes[i].id);
            count++;
        }
    }
    if(count > 0)
    {
        if(confirm("Êtes-vous certain de vouloir supprimer la séléction ? Cette action est irreversible."))
        {
            $.post("removeRooms", {roomids : ids}, function(data, result)
            {
                if(data == 1)
                {
                    alert('Chambre(s) supprimée(s) avec succès.');
                    document.location.href = 'adminChambres';
                }
                else 
                {
                    alert('Erreur inattendue lors de la suppression, veuillez réessayer.');
                }
            });
        }
    }
    else 
    {
        alert('Vous devez séléctionner au moins une chambre pour pouvoir lancer une suppression.');
    }
}
function nextImage(order)
{
    var images = document.getElementsByTagName("img");
    var currentIndex = -1;
    for(var i = 0; i < images.length; i++)
    {
        if(images[i].checkVisibility())
        {
            currentIndex = i;
            break;
        }
    }
    if(order == 'droite')
    {
        if(currentIndex + 1 >= images.length)
        {
            currentIndex = 0;
            if(images.length != 1)
            {
                images[currentIndex].hidden = false;
                images[images.length - 1].hidden = true;
            }
            else 
            {
                images[0].hidden = false;
            }
        }
        else 
        {
            images[currentIndex].hidden = true;
            images[currentIndex + 1].hidden = false;
        }
    }
    else 
    {
        if(currentIndex > 0)
        {
            if(images.length != 1)
            {
                images[currentIndex].hidden = true;
                images[currentIndex - 1].hidden = false;
            }
            else 
            {
                images[0].hidden = false;
            }
        }
        else 
        {
            images[currentIndex].hidden = true;
            images[images.length - 1].hidden = false;
        }
    }
    document.getElementById("image-anchor").scrollIntoView();
}