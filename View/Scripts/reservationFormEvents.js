var nbInvites = 1;

function addPerson(roomCapacite)
{
    if(nbInvites < roomCapacite)
    {
        document.getElementById("persons").innerHTML += "<form class = 'form'>\
                                                            <label for = 'nom'>Nom</label>\
                                                            <input name = 'nom' id = 'nom' class = 'input'></input>\
                                                            <label for = 'prenom'>Prenom</label>\
                                                            <input name = 'prenom' id = 'prenom' class = 'input'></input>\
                                                            <label for = 'dateNaissance'>Date de naissance</label>\
                                                            <input name = 'dateNaissance' id = 'dateNaissance' type = 'date' class = 'input'></input>\
                                                        </form>";
        nbInvites++;
    }
    else 
    {
        alert("Erreur, vous avez atteint le nombre de personnes maximum pour cette chambre.");
    }
}
function doReservation(id)
{
    var roomId = id;
    var dateDebut = document.getElementById("dateDebut").value;
    var dateFin = document.getElementById("dateFin").value;
    var prenomClient = document.getElementById("prenomClient").value;
    var nomClient = document.getElementById("nomClient").value;
    var emailClient = document.getElementById("email").value;
    var phoneClient = document.getElementById("phone").value;
    var legal = document.getElementById("cguv").value;
    var personsHolder = document.getElementById("persons");
    var forms = personsHolder.children; 
    var persons = [];
    for(var i = 0; i < forms.length; i++)
    {
        var inputs = forms[i].getElementsByClassName('input');
        var person = [];
        for(var j = 0; j < inputs.length; j++)
        {
            person.push(inputs[j].value);
        }
        persons.push(person);
    }
    $.post("/~sysadmin/PlaniHost/doReservation", {roomid : roomId, debut : dateDebut, fin : dateFin, cPrenom : prenomClient, cNom : nomClient, cEmail : emailClient, cPhone : phoneClient, cguv : legal, invites : persons}, function(data, result)
    {
        if(data.includes("Erreur"))
        {
            alert(data)
        }
        else 
        {
            document.location.href = data;
        }
    });
}