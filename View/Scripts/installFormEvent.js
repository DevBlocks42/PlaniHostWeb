function init()
{
    var username = document.getElementById("username").value;
    var password = document.getElementById("pwd").value;
    var passwordConfirm = document.getElementById("pwd_confirm").value;
    var stripeApiKey = document.getElementById("stripe_api_key").value;
    var roomPerPage = document.getElementById("room_per_page").value;
    var settingsPerPage = document.getElementById("settings_per_page").value;
    var clientsPerPage = document.getElementById("clients_per_page").value;
    var reservationsPerPage = document.getElementById("reservations_per_page").value;
    if(username.length > 0)
    {
        if(password.length > 0)
        {
            if(password.length >= 6)
            {
                if(password == passwordConfirm)
                {
                    $.post("doInstall", {uname : username, pwd : password, pwd_confirm : passwordConfirm, stripe_key : stripeApiKey, rooms_per_page : roomPerPage, settings_per_page : settingsPerPage, clients_per_page : clientsPerPage, reservations_per_page : reservationsPerPage}, function(data, result)
                    {
                        if(result)
                        {
                            if(data == 1)
                            {
                                alert("L'application web a été initialisée avec succès, vous pouvez désormais ajouter des chambres via le gestionnaire d'administration.");
                                document.location.href = './admin';
                            }
                            else if(data == "PASSWORD_MISMATCH_ERROR")
                            {
                                alert("Erreur, les deux mots de passes saisits sont différents.");
                            }
                            else if(data == "PASSWORD_LENGTH_ERROR")
                            {
                                alert("Erreur, la taille du mot de passe doit être d'au moins 6 caractères.");
                            }
                            else if(data == "USERNAME_LENGTH_ERROR")
                            {
                                alert("Erreur, la taille du nom d'utilisateur est insuffisante.");
                            }
                            else if(data == "INCOMPLETE_FORM_ERROR")
                            {
                                alert("Erreur, vous n'avez pas saisit tous les champs du formulaire.");
                            }
                            else 
                            {
                                alert("Une erreur inconnue s'est produite, veuillez réessayer. Si le problème persiste, veuillez contacter votre administrateur.");
                            }
                        }
                    });
                }
                else 
                {
                    alert("Erreur, les deux mots de passes saisits sont différents.");
                }
            }
            else 
            {
                alert("La taille du mot de passe doit être au moins de 6 caractères.");
            }
        }
        else 
        {
            alert("Vous devez saisir le mot de passe du compte administrateur.");
        }
    }
    else 
    {
        alert("Veuillez saisir un nom d'utilisateur pour la création d'un compte administrateur.");
    }
}