<?php
    //DEBUG
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL); 
    //BDD
    include "Database/Database.php";
    //Controller
    include "Controller/Controller.php";
    //Access
    include "DAL/Access.php";
    //Room
    include "Model/Room.php";
    include "DAL/RoomAccess.php";
    include "Controller/RoomController.php";
    //Admin
    include "Model/Admin.php";
    include "DAL/AdminAccess.php";
    include "Controller/AdminController.php";
    //Client
    include "Model/Client.php";
    include "DAL/ClientAccess.php";
    include "Controller/ClientController.php";
    //Calendar
    include "Model/Calendar.php";
    //Réservation
    include "Model/Reservation.php";
    include "DAL/ReservationAccess.php";
    include "Controller/ReservationController.php";
    //Séjour
    include "Model/Sejour.php";
    include "DAL/SejourAccess.php";
    include "Controller/SejourController.php";
    //Personnes
    include "Model/Personne.php";
    include "DAL/PersonneAccess.php";
    include "Controller/PersonneController.php";
    //Settings
    include "Model/Setting.php";
    include "DAL/SettingAccess.php";
    include "Controller/SettingController.php";
    //Config
    $database = new Database("127.0.0.1", "dbadmin", "P@$\$word1", "planihost");
    $SERVER_ROOT = "/~sysadmin/PlaniHost/";
    $DOCUMENT_HEAD = "<!DOCTYPE html><html lang = 'fr' data-theme = 'light'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width, initial-scale=1'><link rel='stylesheet' href = '" . $SERVER_ROOT . "Style/bulma/css/bulma.css'></head><body>";
    $INSTALL_PASSWORD_HASH = "$2y$10$/fblVWKHOpGyFiP56kk6HeJEAb2CPnq37qkHDhv6vkCPDtXKZ6tVC";
    switch($_GET["route"])
    {
        case "doInstall":
        {
            $settingAccess = new SettingAccess($database->getConnection());
            $settingController = new SettingController($settingAccess);
            $setting = $settingController->getSettingByClef("admin_install_success");
            if($setting == null)
            {
                session_start();
                if(isset($_SESSION['admin_install']) && !empty($_SESSION['admin_install']))
                {
                    if($_SESSION['admin_install'])
                    {
                        if(isset($_POST['uname']) && !empty($_POST['uname']) && isset($_POST['pwd']) && !empty($_POST['pwd']) && isset($_POST['pwd_confirm']) && !empty($_POST['pwd_confirm']) && isset($_POST['stripe_key']) && !empty($_POST['stripe_key']) && isset($_POST['rooms_per_page']) && !empty($_POST['rooms_per_page']) && isset($_POST['settings_per_page']) && !empty($_POST['settings_per_page']) && isset($_POST['clients_per_page']) && !empty($_POST['clients_per_page']) && isset($_POST['reservations_per_page']) && !empty($_POST['reservations_per_page']))
                        {
                            $username = $_POST['uname'];
                            $password = $_POST['pwd'];
                            $passwordConfirm = $_POST['pwd_confirm'];
                            $stripeKey = $_POST['stripe_key'];
                            $rooms = intval($_POST['rooms_per_page']);
                            $settings = intval($_POST['settings_per_page']);
                            $clients = intval($_POST['clients_per_page']);
                            $reservations = intval($_POST['reservations_per_page']);
                            if(strlen($username) > 0)
                            {
                                if(strlen($password) >= 6)
                                {
                                    if($password === $passwordConfirm)
                                    {
                                        $adminAccess = new AdminAccess($database->getConnection());
                                        $adminController = new AdminController($adminAccess);
                                        $settingAccess = new SettingAccess($database->getConnection());
                                        $settingController = new SettingController($settingAccess);
                                        $roomSetting = new Setting(-1, "catalogue_chambres_par_page", $rooms);
                                        $settingsSetting = new Setting(-1, "admin_settings_par_pages", $settings);
                                        $clientSetting = new Setting(-1, "admin_clients_par_pages", $clients);
                                        $reservationSetting = new Setting(-1, "admin_reservations_par_pages", $reservations);
                                        $installDoneSetting = new Setting(-1, "admin_install_success", 1);
                                        $settingController->addSetting($roomSetting);
                                        $settingController->addSetting($settingsSetting);
                                        $settingController->addSetting($clientSetting);
                                        $settingController->addSetting($reservationSetting);
                                        $adminController->createAdmin($username, password_hash($password, PASSWORD_DEFAULT));
                                        $secrets = fopen("./stripeSecrets/secrets.php", "w+");
                                        fwrite($secrets, "<?php \$stripeSecretKey=\"" . $stripeKey . "\";");
                                        fclose($secrets);
                                        $settingController->addSetting($installDoneSetting);
                                        echo 1;
                                    }
                                    else 
                                    {
                                        echo "PASSWORD_MISMATCH_ERROR";
                                    }
                                }
                                else 
                                {
                                    echo "PASSWORD_LENGTH_ERROR";
                                }
                            }
                            else 
                            {
                                echo "USERNAME_LENGTH_ERROR";
                            }
                        }
                        else 
                        {
                            echo "INCOMPLETE_FORM_ERROR";
                        }
                    }
                }
            }
            break; 
        }
        case "installation":
        {
            $settingAccess = new SettingAccess($database->getConnection());
            $settingController = new SettingController($settingAccess);
            $setting = $settingController->getSettingByClef("admin_install_success");
            if($setting == null)
            {
                if(isset($_POST['pwd']) && !empty($_POST['pwd']))
                {
                    $password = $_POST['pwd'];
                    if(password_verify($password, $INSTALL_PASSWORD_HASH))
                    {
                        session_start();
                        $_SESSION['admin_install'] = true;
                        $pageData = $DOCUMENT_HEAD;
                        $pageData .= "<script src = '" . $SERVER_ROOT . "View/Scripts/jquery.min.js'></script>";
                        $pageData .= "<script src = '" . $SERVER_ROOT . "View/Scripts/installFormEvent.js'></script>";
                        $pageData .= file_get_contents("View/Partials/header.html");
                        $pageData .= "
                                        <section class = 'section'>
                                            <div class = 'box has-text-centered'>
                                                <p class = 'block is-size-3'><b>(1) Création du compte administrateur</b></p><br>
                                                <div class = 'block'>
                                                    <form class = 'control'>
                                                        <div class = 'block'>
                                                            <label for = 'username' class = 'label'>Nom d'utilisateur</label>
                                                            <input class = 'input' type = 'text' name = 'username' id = 'username'></input>
                                                        </div>
                                                        <div class = 'block'>
                                                            <label for = 'pwd' class = 'label'>Mot de passe</label>
                                                            <input class = 'input' type = 'password' name = 'pwd' id = 'pwd'></input>
                                                        </div>
                                                        <div class = 'block'>
                                                            <label for = 'pwd_confirm' class = 'label'>Confirmation du mot de passe</label>
                                                            <input class = 'input' type = 'password' name = 'pwd_confirm' id = 'pwd_confirm'></input>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class = 'box has-text-centered'>
                                                <p class = 'block is-size-3'><b>(2) Intégration du paiement avec Stripe</b></p><br>
                                                <div class = 'block'>
                                                    <form class = 'control'>
                                                        <div class = 'block'>
                                                            <label for = 'stripe_api_key' class = 'label'>Clef d'API Stripe</label>
                                                            <input class = 'input' type = 'text' name = 'stripe_api_key' id = 'stripe_api_key'></input>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class = 'box has-text-centered'>
                                                <p class = 'block is-size-3'><b>(3) Choix des paramètres par défaut du site</b></p><br>
                                                <div class = 'block'>
                                                    <form class = 'control'>
                                                        <div class = 'block'>
                                                            <label for = 'room_per_page' class = 'label'>Nombre de chambre par page du catalogue</label>
                                                            <input class = 'input' type = 'number' name = 'room_per_page' id = 'room_per_page' value = 4></input>
                                                        </div>
                                                        <div class = 'block'>
                                                            <label for = 'settings_per_page' class = 'label'>Nombre de paramètres par page du gestionnaire de paramètres</label>
                                                            <input class = 'input' type = 'number' name = 'settings_per_page' id = 'settings_per_page' value = 10></input>
                                                        </div>
                                                        <div class = 'block'>
                                                            <label for = 'clients_per_page' class = 'label'>Nombre de clients par page du gestionnaire de clients</label>
                                                            <input class = 'input' type = 'number' name = 'clients_per_page' id = 'clients_per_page' value = 10></input>
                                                        </div>
                                                        <div class = 'block'>
                                                            <label for = 'reservations_per_page' class = 'label'>Nombre de réservations par page du gestionnaire de réservations</label>
                                                            <input class = 'input' type = 'number' name = 'reservations_per_page' id = 'reservations_per_page' value = 10></input>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class = 'box has-text-centered'>
                                                <div class = 'block'>
                                                    <button onclick = 'init();' class = 'button is-size-3'>Initialiser l'application</button>
                                                </div>
                                            </div>
                                        </section>";
                        $pageData .= "<section class = 'section is-large'></section>";
                        $pageData .= file_get_contents("View/Partials/footer.html");
                        echo $pageData; 
                    }
                    else 
                    {
                        echo "<script>alert('Le mot de passe est incorrect.'); window.location.href = '" . $SERVER_ROOT . "accueil'</script>";
                    }
                }
                else 
                {
                    header("location: " . $SERVER_ROOT . "404");
                }
            }
            break;
        }
        case "install":
        {
            $settingAccess = new SettingAccess($database->getConnection());
            $settingController = new SettingController($settingAccess);
            $setting = $settingController->getSettingByClef("admin_install_success");
            if($setting == null)
            {
                $pageData = $DOCUMENT_HEAD;
                $pageData .= file_get_contents("View/Partials/header.html");
                $pageData .= "
                                <section class = 'section is-medium'>
                                    <div class = 'box has-text-centered'>
                                        <p class = 'block'><b>🔒 Authentification requise</b></p>
                                        <form class = 'control' action = '" . $SERVER_ROOT . "installation' method = 'POST'>
                                            <div class = 'block'><label class = 'label' for = 'pwd'>Mot de passe</label>
                                            <input class = 'input' type = 'password' name = 'pwd' id = 'pwd'></input></div>
                                            <div class = 'block'><input type = 'submit' class = 'input' value = 'Valider'></input></div>
                                        </form>
                                    </div>
                                </section>";
                $pageData .= "<section class = 'section is-medium'></section>";
                $pageData .= file_get_contents("View/Partials/footer.html");
                echo $pageData;
            }
            break;
        }
        case "accueil":
            $pageData = $DOCUMENT_HEAD;
            $pageData .= file_get_contents("View/Partials/header.html");
            $pageData .= file_get_contents("View/accueil.html");
            $pageData .= file_get_contents("View/Partials/footer.html");
            $pageData .= "</body></html>";
            echo $pageData;
            break;
        case "contact":
            $pageData = $DOCUMENT_HEAD;
            $pageData .= file_get_contents("View/Partials/header.html");
            $pageData .= file_get_contents("View/contact.html");
            $pageData .= file_get_contents("View/Partials/footer.html");
            $pageData .= "</body></html>";
            echo $pageData;
            break;
        case "mailer":
            if(isset($_POST['email']) && isset($_POST['title']) && isset($_POST['message']) && isset($_POST['rgpd']))
            {
                if(preg_match("/[a-zA-Z0-9\-\_]*@[a-zA-Z0-9\-\_]*\.[a-zA-Z]+/", $_POST['email']))
                {
                    $email = $_POST['email'];
                    if(strlen($_POST['title']) > 3)
                    {
                        if(strlen($_POST['message']) > 5 && strlen($_POST['message']) < 256)
                        {
                            $email = htmlspecialchars($_POST['email']);
                            $title = htmlspecialchars($_POST['title']);
                            $message = "De : " . $email . " " . htmlspecialchars($_POST['message']);
                            //TODO : Configurer un domaine et une adresse email pour la récéption
                            mail("NOT_IMPLEMENTED_YET", $title, $message);
                            echo "<script>alert('Votre message a été envoyé avec succès.'); window.location.href = '" . $SERVER_ROOT . "accueil'</script>";
                        }
                        else 
                        {
                            echo "Erreur, la taille du message doit être comprise entre 5 et 256 caractères.";
                        }
                    }
                    else 
                    {
                        echo "Erreur, le titre doit faire au minimum 3 caractères.";
                    }
                }
                else 
                {
                    echo "Erreur, le format de l'adresse email saisie est invalide.";
                }
            }
            else 
            {
                echo "Erreur, vous devez saisir tous les champs du formulaire pour pouvoir nous contacter . Veuillez réessayer.";
            }
            break;
        case "catalogue":
            $pageData = $DOCUMENT_HEAD;
            $pageData .= file_get_contents("View/Partials/header.html");
            $pageData .= file_get_contents("View/catalogue.html");
            $pageData .= file_get_contents("View/Partials/footer.html");
            echo $pageData;
            break;
        case "rooms": 
            $roomAccess = new RoomAccess($database->getConnection());
            $controller = new RoomController($roomAccess);
            $maxRooms = $controller->getMaxRooms();
            $pageData = $DOCUMENT_HEAD;
            $pageData .= file_get_contents("View/Partials/header.html");
            $pageData .= "<section class = 'section is-medium'>";
            $settingAccess = new SettingAccess($database->getConnection());
            $settingController = new SettingController($settingAccess);
            $setting = $settingController->getSettingByClef("catalogue_chambres_par_page");
            $roomsPerPage = intval($setting->getValeur());
            $pagesRequired = $maxRooms % $roomsPerPage == 0 ? intdiv($maxRooms, $roomsPerPage) : intdiv($maxRooms, $roomsPerPage) + 1; //intdiv($maxRooms, $roomsPerPage); 
            $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
            if($currentPage <= $pagesRequired && $currentPage > 0)
            {
                $rooms = $controller->getRoomList($roomsPerPage, $roomsPerPage * ($currentPage - 1));
                foreach($rooms as $room)
                {
                    $pageData .= "<div class = 'box'> 
                                    <div class = 'media'>
                                        <div class = 'media-left'>
                                            <figure class = 'image is-128x128'>
                                                <img src = '" . $SERVER_ROOT . "Images/" . htmlspecialchars($room->getImages()[0]) . "'>
                                            </figure>                                    
                                        </div>
                                        <div class = 'media-content'>
                                            <div class = 'content'>
                                                <b>" . htmlspecialchars($room->getTitre()) . "</b><br><p>" . htmlspecialchars($room->getDescription()) . "</p> <button class = 'button'><a href = '" . $SERVER_ROOT . "roomDetails/" . htmlspecialchars($room->getId()) . "'>🔎 Voir</a></button>
                                            </div>
                                            <div class = 'box'>
                                                💶 " . htmlspecialchars($room->getPrix()) . " € / nuit  👥 " . htmlspecialchars($room->getCapacite()) . " personne(s)
                                            </div>
                                        </div>
                                    </div>
                                </div>";
                }
                $pageData .= "<nav class = 'pagination is-centered'>";
                $pageData .= "<ul class = 'pagination-list'>";
                for($i = $currentPage - 2; $i <= $currentPage + 2; $i++)
                {
                    if($i > 0 && $i <= $pagesRequired)
                    {
                        if($i == $currentPage)
                        {
                            $pageData .= "<li><a class = 'pagination-link is-current' href = '" . $SERVER_ROOT . "rooms/" . $i . "'>" . $i . "</a></li>";
                        }
                        else 
                        {
                            $pageData .= "<li><a class = 'pagination-link' href = '" . $SERVER_ROOT . "rooms/" . $i . "'>" . $i . "</a></li>";
                        }
                    }
                }
                $pageData .= "</ul></nav>";
            }
            else 
            {
                header("location: " . $SERVER_ROOT . "404");
            }
            $pageData .= "</section><section class = 'section is-small mt-6'></section>";
            $pageData .= file_get_contents("View/Partials/footer.html");
            echo $pageData;
            break;
        case "roomDetails":
            if(isset($_GET['room']))
            {
                $roomAccess = new RoomAccess($database->getConnection());
                $roomController = new RoomController($roomAccess);
                $room = $roomController->getRoomInfos(intval($_GET['room']));
                $reservationAccess = new ReservationAccess($database->getConnection());
                $reservationController = new ReservationController($reservationAccess);
                if($room != null)
                {
                    $pageData = $DOCUMENT_HEAD;
                    $pageData .= "<script src = '" . $SERVER_ROOT . "View/Scripts/jquery.min.js'></script>";
                    $pageData .= "<script src = '" . $SERVER_ROOT . "View/Scripts/roomFormEvents.js'></script>";
                    $pageData .= file_get_contents("View/Partials/header.html");
                    $pageData .= "<section class = 'section'>";
                    $images = explode(";", $room->getImages());
                    $pageData .= "  <div class = 'card'>
                                        <p class = 'content is-left pl-4 pt-4 is-size-3'><b>" . htmlspecialchars($room->getTitre()) . "</b></p>
                                        <div class = 'card-image has-text-centered'>
                                            <a id = 'image-anchor'></a>
                                            <div class = 'block pt-4'>
                                                <button onclick = 'nextImage(\"gauche\");' class = 'button is-left'>⬅</button>
                                                <button onclick = 'nextImage(\"droite\");'class = 'button is-right'>➡</button>
                                            </div>
                                            ";
                    for($i = 0; $i < count($images); $i++)
                    {   
                        if($i > 0)
                        { 
                            $pageData .= "<figure>";
                            $pageData .= "  <img id = '" . $i . "' hidden src = '" . $SERVER_ROOT . "Images/" . htmlspecialchars($images[$i]) . "'/>";
                            $pageData .= "</figure>";
                        }
                        else 
                        {
                            $pageData .= "<figure>";
                            $pageData .= "  <img id = '" . $i . "' src = '" . $SERVER_ROOT . "Images/" . htmlspecialchars($images[$i]) . "'/>";
                            $pageData .= "</figure>";
                        }
                    }
                    $roomId = $room->getId();
                    
                    $dateDebut = DateTime::createFromFormat("Y-m-d", date('Y-m-d'));
                    $dateFin = DateTime::createFromFormat("Y-m-d", date('Y-m-d'));
                    $dateFin->modify('+1 month');
                    $dateFin->modify('+1 day');
                    $roomCalendar = $reservationController->getRoomCalendarOptimised($roomId, $dateDebut->format('Y-m-d'), $dateFin->format('Y-m-d'));
                    $calendar = new Calendar($dateDebut, $dateFin, $roomCalendar, true);
                    $pageData .= "</div><div class = 'block pb-4 pt-4 has-text-centered'>
                                    <button onclick = 'nextImage(\"gauche\");' class = 'button is-left'>⬅</button>
                                    <button onclick = 'nextImage(\"droite\");' class = 'button is-right'>➡</button>
                                  </div>";                                   
                    $pageData .= "</div>
                                  <div class = 'box mt-4'>
                                    <div class = 'block'>
                                        <b>Description</b>
                                    </div>
                                    <div class = 'block'>
                                        " . htmlspecialchars($room->getDescription()) . "
                                    </div>
                                  </div>
                                  <div class = 'box mt-4'>
                                    <div class = 'block'>
                                        <b>Détails</b>
                                    </div>
                                    <div class = 'block'>
                                        <div class = 'content'>
                                            <ul class = 'ul'>
                                                <li class = 'li'>Prix : <b>" . htmlspecialchars($room->getPrix()) . "</b> € / nuit</li>
                                                <li class = 'li'>Capacité : <b>" . htmlspecialchars($room->getCapacite()) . "</b> personne(s)</li>
                                            </ul>
                                        </div>
                                    </div>
                                  </div>
                                  <div class = 'box mt-4'>
                                    <p class = 'block is-size-3'><b>Calendrier des disponibilités</b></p>
                                    " . $calendar->toString() . "
                                    <div class = 'content has-text-centered'>
                                        <button onclick = \"document.location.href = '" . $SERVER_ROOT . "calendarDetails/" . $roomId . "/1'\" class = 'button is-centered'>Calendrier complet</button>
                                    </div>
                                  </div>
                                  <div class = 'box mt-4 has-text-centered'>
                                    <button onclick = 'document.location.href = \"" . $SERVER_ROOT . "reservation/" . htmlspecialchars($room->getId()) . "/1\"' class = 'button is-size-4'>Réserver cette chambre.</button>
                                  </div>
                                </section>
                                  <section class = 'section is-medium'></section>";
                    
                    $pageData .= file_get_contents("View/Partials/footer.html");
                    echo $pageData;
                }
                else 
                {
                    header("location: " . $SERVER_ROOT . "404");
                }
            }
            break;
        case "calendarDetails":
            if(isset($_GET['room']) && isset($_GET['period']))
            {
                $roomId = intval($_GET['room']) == 0 ? -1 : intval($_GET['room']);
                $period = intval($_GET['period']) == 0 ? -1 : intval($_GET['period']);
                if($roomId != -1 && $period != -1)
                {
                    $dateDebut = DateTime::createFromFormat("Y-m-d", date('Y-m-d'));
                    $dateDebut->modify('+' . ($period - 1) * 1 . ' month');
                    $dateFin = DateTime::createFromFormat("Y-m-d", date('Y-m-d'));
                    $dateFin->modify('+' . $period * 1 . ' month');
                    $dateFin->modify('+1 day');
                    $reservationAccess = new ReservationAccess($database->getConnection());
                    $reservationController = new ReservationController($reservationAccess);
                    $roomCalendar = $reservationController->getRoomCalendarOptimised($roomId, $dateDebut->format("Y-m-d"), $dateFin->format("Y-m-d"));
                    $calendar = new Calendar($dateDebut, $dateFin, $roomCalendar, false);
                    $pageData = $DOCUMENT_HEAD;
                    $pageData .= "<script src = '" . $SERVER_ROOT . "View/Scripts/calendarEvents.js'></script>";
                    $pageData .= file_get_contents("View/Partials/header.html");
                    $pageData .= "<section class = 'section'><div class = 'box'>";
                    $pageData .= $calendar->toString();
                    $pageData .= "</div></section>";
                    $pageData .= file_get_contents("View/Partials/footer.html");
                    echo $pageData;
                }
                else 
                {
                    header("location: " . $SERVER_ROOT . "404");
                }
            }
            break;
        case "reservation": 
            if(isset($_GET['room']))
            {
                $pageData = $DOCUMENT_HEAD;
                $pageData .= "<script src = '" . $SERVER_ROOT . "View/Scripts/jquery.min.js'></script>";
                $pageData .= "<script src = '" . $SERVER_ROOT . "View/Scripts/calendarEvents.js'></script>";
                $pageData .= "<script src = '" . $SERVER_ROOT . "View/Scripts/reservationFormEvents.js'></script>";
                $pageData .= file_get_contents("View/Partials/header.html");
                $roomId = intval($_GET['room']) == 0 ? -1 : intval($_GET['room']);
                $period = intval($_GET['period']) == 0 ? -1 : intval($_GET['period']);
                $roomAccess = new RoomAccess($database->getConnection());
                $roomController = new RoomController($roomAccess);
                $room = $roomController->getRoomInfos($roomId);
                $roomCapacite = $room->getCapacite();
                //Calendrier
                $dateDebut = DateTime::createFromFormat("Y-m-d", date('Y-m-d'));
                $dateDebut->modify('+' . ($period - 1) * 1 . ' month');
                $dateFin = DateTime::createFromFormat("Y-m-d", date('Y-m-d'));
                $dateFin->modify('+' . $period * 1 . ' month');
                $dateFin->modify('+1 day');
                $reservationAccess = new ReservationAccess($database->getConnection());
                $reservationController = new ReservationController($reservationAccess);
                $roomCalendar = $reservationController->getRoomCalendarOptimised($roomId, $dateDebut->format("Y-m-d"), $dateFin->format("Y-m-d"));
                $calendar = new Calendar($dateDebut, $dateFin, $roomCalendar, false);
                $pageData .= "<section class = 'section is-medium'> 
                                <div class = 'box'>
                                    <div class = 'block is-size-3'><p><b>Informations séjour.</b></p></div>
                                    " . $calendar->toString() . "
                                    <form class = 'control'>
                                        <label for = 'dateDebut'>Date de début</label>
                                        <input class = 'input' type = 'date' id = 'dateDebut'>
                                        <label for = 'dateFin'>Date de fin</label>
                                        <input class = 'input' type = 'date' id = 'dateFin'>
                                    </form>
                                </div>
                                <div class = 'box'>
                                    <div class = 'block is-size-3'><p><b>Informations client.</b></p></div>
                                    <form class = 'form'>
                                        <label class = 'label' for = 'nomClient'>Nom</label>
                                        <input id = 'nomClient' name = 'nomClient' class = 'input' type = 'text'>
                                        <label class = 'label' for = 'prenomClient'>Prénom</label>
                                        <input id = 'prenomClient' name = 'prenomClient' class = 'input' type = 'text'>
                                        <label class = 'label' for = 'email'>Adresse email</label>
                                        <input id = 'email' name = 'email' class = 'input' type = 'email'>
                                        <label class = 'label' for = 'phone'>Téléphone</label>
                                        <input id = 'phone' name = 'phone' class = 'input' type = 'tel'>
                                    </form>
                                </div>
                                <div class = 'box'>
                                    <div class = 'block is-size-3'><p><b>Informations invité(s).</b></p></div>
                                    <button onclick = 'addPerson(" . htmlspecialchars($roomCapacite) . ");' class = 'button'>Ajouter un invité</button>
                                    <div id = 'persons'>

                                    </div>
                                </div>
                                <div class = 'box'>
                                <input id = 'cguv' name = 'cguv' type = 'checkbox'>
                                    <label for = 'cguv'>En cochant cette case, vous confirmez être en accord avec notre <a href = ''>politique de confidentialité</a> et nos <a href = ''>conditions générales de vente</a>.</label>
                                    
                                </div>
                                <div class = 'box has-text-centered'>
                                    <button onclick = 'doReservation(" . htmlspecialchars($roomId). ");'class = 'button'>Réserver</button>
                                </div>
                            </section>
                            <section class = 'section is-medium'></section>
                ";
                $pageData .= file_get_contents("View/Partials/footer.html");
                echo $pageData;
            }
            break;
        case "success":
        {
            if(session_start() != false)
            {
                if(isset($_SESSION['checkout_id']) && !empty($_SESSION['checkout_id']) && isset($_SESSION['email']) && !empty($_SESSION['email']) && isset($_SESSION['nom']) && isset($_SESSION['prenom']) && isset($_SESSION['dateDebut']) && isset($_SESSION['dateFin']))
                {
                    require_once 'vendor/autoload.php';
                    require_once 'stripeSecrets/secrets.php';
                    \Stripe\Stripe::setApiKey($stripeSecretKey);
                    $checkout_session = \Stripe\Checkout\Session::retrieve($_SESSION['checkout_id']);
                    $pageData = $DOCUMENT_HEAD;
                    $pageData .= file_get_contents("View/Partials/header.html");
                    if($checkout_session->id === $_SESSION['checkout_id'])
                    {
                        if($checkout_session->payment_status == "paid")
                        {
                            $pageData .= "
                                <section class = 'section is-large'>
                                    <div class = 'box'>
                                        <p class = 'block is-size-3'>Félicitations M/Mme " . htmlspecialchars($_SESSION['nom']) . ", le paiement a bien été effectué, vous allez recevoir un mail récapitulatif de votre commande.<br>Nous vous remercions pour votre confiance.</p>
                                    </div>
                                </section>";
                            $to = $_SESSION['email'];
                            $headers = "Content-Type: text/html; charset=UTF-8\r\n";
                            $message = "Bonjour " . htmlspecialchars($_SESSION['prenom']) . " " . htmlspecialchars($_SESSION['nom']) . ".<br>Votre réservations a bien été acceptée, vous trouverez les détails de votre séjour ci-dessous.<br>Merci pour votre confiance et à bientôt.<br>Séjour du " . htmlspecialchars($_SESSION['dateDebut']->format('d-m-Y')). " au " . htmlspecialchars($_SESSION['dateFin']->format('d-m-Y')) . ".<br>Commande payée.";
                            mail($to, "Réservation Hôtel PlaniHost", $message, $headers);
                        }
                        else 
                        {
                            $pageData .= "
                                <section class = 'section is-large'>
                                    <div class = 'box'>
                                        <p class = 'block is-size-3'>Une erreur s'est produite lors de la récéption du paiement. Veuillez réessayer, si le problème persiste, veuillez contacter votre banque.</p>
                                    </div>
                                </section>";
                        }
                    }
                    else 
                    {
                        $pageData .= "
                            <section class = 'section is-large'>
                                <div class = 'box'>
                                    <p class = 'block is-size-3'>Une erreur s'est produite lors de la récéption du paiement. Veuillez contacter le support en cliquant <a href = '" . $SERVER_ROOT . "contact'>ici</a></p>
                                </div>
                            </section>";
                    }
                    $pageData .= file_get_contents("View/Partials/footer.html");
                    echo $pageData;
                }
            }
            break;
        }
        case "cancel":
        {
            echo 1;
            break;
        }
        case "doReservation":
            $pageData = "";
            if(isset($_POST['roomid']) && isset($_POST['debut']) && isset($_POST['fin']) && isset($_POST['cPrenom']) && isset($_POST['cNom']) && isset($_POST['cEmail']) && isset($_POST['cPhone']) && isset($_POST['cguv']) && isset($_POST['invites']))
            {
                $roomId = intval($_POST['roomid']) == 0 ? -1 : intval($_POST['roomid']);
                $dateDebut = DateTime::createFromFormat('Y-m-d', $_POST['debut']);
                $dateFin = DateTime::createFromFormat('Y-m-d', $_POST['fin']);
                $prenomClient = $_POST['cPrenom'];
                $nomClient = $_POST['cNom'];
                $emailClient = $_POST['cEmail'];
                $phoneClient = $_POST['cPhone'];
                $cguv = $_POST['cguv'];
                $invites = $_POST['invites'];
                if($roomId != -1)
                {
                    $reservationAccess = new ReservationAccess($database->getConnection());
                    $reservationController = new ReservationController($reservationAccess);
                    if($reservationController->isRoomAvailable($roomId, $dateDebut->format('Y-m-d'), $dateFin->format('Y-m-d')))
                    { 
                        $clientAccess = new ClientAccess($database->getConnection());
                        $clientController = new ClientController($clientAccess);
                        $clientId = $clientController->addClient($nomClient, $prenomClient, $emailClient, $phoneClient);
                        $client = $clientController->getClient($clientId);
                        if($client != null)
                        {
                            $roomAccess = new RoomAccess($database->getConnection());
                            $roomController = new RoomController($roomAccess);
                            $room = $roomController->getRoomInfos($roomId);
                            $capacite = $room->getCapacite();
                            $reservationId = $reservationController->addReservation($capacite, $client);
                            $sejourAccess = new SejourAccess($database->getConnection());
                            $sejourController = new SejourController($sejourAccess);
                            $sejourId = $sejourController->addSejour($dateDebut->format("Y-m-d"), $dateFin->format("Y-m-d"), $reservationId);
                            if($sejourId > 0)
                            {
                                $personneAccess = new PersonneAccess($database->getConnection());
                                $personneController = new PersonneController($personneAccess);
                                if(count($invites) <= $capacite - 1)
                                {
                                    foreach($invites as $person)
                                    {
                                        $nom = $person[0];
                                        $prenom = $person[1];
                                        $dateNaissance = $person[2];
                                        $personneController->addPerson($nom, $prenom, $dateNaissance, NULL, $roomId, $sejourId);
                                    }
                                    //Intégration stripe
                                    $sejour = $sejourController->getSejourById($sejourId);
                                    $duree = $sejour->getDureeSejour();
                                    require_once 'vendor/autoload.php';
                                    require_once 'stripeSecrets/secrets.php';
                                    \Stripe\Stripe::setApiKey($stripeSecretKey);
                                    $product = \Stripe\Product::retrieve(htmlspecialchars($room->getStripeProductId()));
                                    $priceId = $product->default_price;
                                    $YOUR_DOMAIN = 'http://192.168.122.133' . $SERVER_ROOT;
                                    $checkout_session = \Stripe\Checkout\Session::create
                                    ([
                                        'line_items' => 
                                        [[
                                            'price' => $priceId,
                                            'quantity' => $duree,
                                        ]],
                                        'mode' => 'payment',
                                        'success_url' => $YOUR_DOMAIN . 'success',
                                        'cancel_url' => $YOUR_DOMAIN . 'cancel',
                                    ]);
                                    if(session_start() != false)
                                    {
                                        $stripeClientId = $checkout_session->id;
                                        $_SESSION['checkout_id'] = $stripeClientId;
                                        $_SESSION['email'] = $client->getEmail();
                                        $_SESSION['nom'] = $client->getNom();
                                        $_SESSION['prenom']  = $client->getPrenom();
                                        $_SESSION['dateDebut'] = $dateDebut;
                                        $_SESSION['dateFin'] = $dateFin;
                                        $clientController->setStripeClientId($clientId, $stripeClientId);
                                        echo($checkout_session->url);
                                    }
                                }
                                else 
                                {
                                    echo("Erreur, le nombre d'invité(s) est trop grand.");
                                }
                            }
                            else 
                            {
                                echo "Erreur lors de l'enregistrement des informations du séjour.";
                            }
                        }
                        else 
                        {
                            echo "Erreur lors de l'enregistrement des informations client.";
                        }
                    }
                    else 
                    {
                        echo "Erreur, la chambre n'est pas disponible pour la période séléctionnée.";
                    }
                }
            }
            else 
            {
                echo "Erreur, vous n'avez pas saisit toutes les informations nécéssaires à la réservation.";
            }
            //echo $pageData;
            break;
        case "admin":
            $pageData = $DOCUMENT_HEAD;
            $pageData .= file_get_contents("View/Partials/header.html");
            if(isset($_POST['username']) && isset($_POST['password']))
            {
                $adminAccess = new AdminAccess($database->getConnection());
                $adminController = new AdminController($adminAccess);
                if($adminController->verifyCredentials($_POST['username'], $_POST['password']))
                {
                    $admin = $adminController->getAdmin($_POST['username']);
                    session_start();
                    $_SESSION['admin'] = $admin;
                    header("location: " . $SERVER_ROOT . "adminDashboard");
                } 
            }
            else 
            {
                session_start();
                if(!isset($_SESSION['admin']))
                {
                    $pageData .= file_get_contents("View/admin.html");
                }
                else 
                {
                    header("location: " . $SERVER_ROOT . "adminDashboard");
                }
            }
            $pageData .= file_get_contents("View/Partials/footer.html");
            echo $pageData;
            break;
        case "adminDashboard":
            session_start();
            $pageData = "";
            if(isset($_SESSION['admin']))
            {
                $pageData .= $DOCUMENT_HEAD;
                $pageData .= file_get_contents("View/Partials/header.html");
                $pageData .= "<div class = 'section is-medium'><div class = 'box has-background-blue mt-6 pt-6 has-text-centered'>Bienvenue sur l'espace d'administration <b>" . $_SESSION['admin']->getUserName() . "</b>";
                $pageData .= "
                            <div class = 'block mt-6'>
                                <div class = 'columns'>
                                    <div class = 'column is-half'>
                                        <button class = 'button' onclick = \"document.location.href = '" . $SERVER_ROOT . "adminChambres'\">🛏 Gestionnaire des chambres</button>
                                    </div>
                                    <div class = 'column'>
                                        <button class = 'button' onclick = \"document.location.href = '" . $SERVER_ROOT . "adminReservations/1'\">📓 Planning des réservations</button>
                                    </div>
                                </div>
                                <div class = 'columns'>
                                    <div class = 'column is-half'>
                                        <button onclick = \"document.location.href='" . $SERVER_ROOT . "adminClients/1'\" class = 'button'>👥 Gestionnaire des clients</button>
                                    </div>
                                    <div class = 'column'>
                                        <button onclick = \"document.location.href='" . $SERVER_ROOT . "adminSettings/1'\" class = 'button'>⚙ Paramètres du site</button>
                                    </div>
                                </div>
                            </div></div></div>
                            <div class = 'section is-medium'></div>";
                $pageData .= file_get_contents("View/Partials/footer.html");
                echo $pageData;
                break;
            }
        case "getSetting":
        {
            session_start();
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin']))
            {
                if(isset($_GET['id']))
                {
                    $id = intval($_GET['id']) == 0 ? -1 : intval($_GET['id']);
                    $settingAccess = new SettingAccess($database->getConnection());
                    $settingController = new SettingController($settingAccess);
                    $setting = $settingController->getSettingById($id);
                    if($setting != null)
                    {
                        $obj = json_encode($setting);
                        echo $obj;
                    }
                }
            }
            break;
        }
        case "editSetting":
        {
            session_start();
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin']))
            {
                if(isset($_POST['id']) && !empty($_POST['id']) && isset($_POST['clef']) && !empty($_POST['clef']) && isset($_POST['valeur']) && !empty($_POST['valeur']))
                {
                    $id = intval($_POST['id']) == 0 ? -1 : intval($_POST['id']);
                    $clef = $_POST['clef'];
                    $valeur = $_POST['valeur'];
                    $settingAccess = new SettingAccess($database->getConnection());
                    $settingController = new SettingController($settingAccess);
                    $setting = new Setting($id, $clef, $valeur);
                    $settingController->updateSetting($setting);
                    echo "<script>alert('Le paramètre a bien été mis à jour.'); document.location.href = '" . $SERVER_ROOT . "adminSettings/1'</script>";
                }
                else 
                {
                    echo "<script>alert('Erreur lors de la mise à jour du paramètre, veuillez réessayer.'); document.location.href = '" . $SERVER_ROOT . "adminSettings/1'</script>";
                }
            }
            break;
        }
        case "adminSettings":
        {
            session_start();
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin']))
            {
                //
                $settingAccess = new SettingAccess($database->getConnection());
                $settingController = new SettingController($settingAccess);
                $setting = $settingController->getSettingByClef("admin_settings_par_pages");
                //
                $rowsPerPage = intval($setting->getValeur());
                $pageId = 1;
                if(isset($_GET['page']))
                {
                    $pageId = intval($_GET['page']) == 0 ? 1 : intval($_GET['page']);
                }
                $pageData = $DOCUMENT_HEAD;
                $pageData .= file_get_contents("View/Partials/header.html");
                $pageData .= "<script src='" . $SERVER_ROOT . "View/Scripts/jquery.min.js'></script>";
                $pageData .= "<script src='" . $SERVER_ROOT . "View/Scripts/settingsFormEvents.js'></script>";
                $pageData .= "<div class='modal' id = 'edit_setting'>
                                <div class='modal-background'></div>
                                <div class='modal-content'>
                                    <div id = 'modal-title' class = 'block has-text-white'>Modifier le paramètre</div>
                                    <div class = 'box'>
                                    <form id = 'modal-form' class = 'control' method = 'POST' action = '" . $SERVER_ROOT . "editSetting'>
                                        <input type = 'hidden' name = 'id' id = 'id'></input>
                                        <label class = 'label' for = 'clef'>Clef</label>
                                        <input id = 'clef' class = 'input' name = 'clef' type = 'text'>
                                        <label class = 'label' for = 'valeur'>Valeur</label>
                                        <input id = 'valeur' class = 'input' name = 'valeur' type = 'text'><br><br>
                                        <input id = 'modal-submit' class = 'input' type = 'submit' value = 'Valider'>
                                    </form></div>
                                </div>
                <button onclick = \"toggleModal();\" id = 'close_modal' class='modal-close is-large' aria-label='close'></button>
              </div>";
                $pageData .= "
                            <section class = 'section'>
                                <div class = 'box has-text-centered'>
                                    <p class = 'block'><b>Gestion des paramètres</b></p>
                                    <button onclick = 'editSetting();' class = 'button'>Modifier</button>
                                </div>
                                <div class = 'columns is-centered'>
                                    <table class = 'table mt-6'>
                                        <thead>
                                            <tr>
                                                <th>/</th>
                                                <th>ID</th>
                                                <th>Clef</th>
                                                <th>Valeur</th>
                                            </tr>
                                        </thead>
                                        <tbody id = 'table_rows'>";
                $settingAccess = new SettingAccess($database->getConnection());
                $settingController = new SettingController($settingAccess);
                $settings = $settingController->getSettings($rowsPerPage, $rowsPerPage * ($pageId - 1));
                foreach($settings as $setting)
                {
                    $pageData .= "
                                    <tr>
                                        <th><input id = '" . htmlspecialchars($setting->getId()) . "' type = 'checkbox'></th>
                                        <th>" . htmlspecialchars($setting->getId()) . "</th>
                                        <th>" . htmlspecialchars($setting->getClef()) . "</th>
                                        <th>" . htmlspecialchars($setting->getValeur()) . "</th>
                                    </tr>";
                }

                $pageData.= "           </tbody>
                                    </table>
                                </div>
                                <div class = 'content has-text-centered mt-6'>
                                    <div class = 'block'>
                                        <button onclick = 'previous();' class = 'button'>Précédent</button> <button onclick = 'next();' class = 'button'>Suivant</button>
                                    </div>
                                </div>
                            </section><section class = 'section is-medium'></section>";
                $pageData .= file_get_contents("View/Partials/footer.html");
                echo $pageData;
            }
            else 
            {
                header("location: " . $SERVER_ROOT . "404");
            }
            break;
        }
        case "adminClients":
        {
            session_start();
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin']))
            {
                if(isset($_GET['page']))
                {
                    $page = intval($_GET['page']) == 0 ? -1 : intval($_GET['page']);
                    if($page != -1)
                    {
                        //
                        $settingAccess = new SettingAccess($database->getConnection());
                        $settingController = new SettingController($settingAccess);
                        $setting = $settingController->getSettingByClef("admin_clients_par_pages");
                        //
                        $clientsPerPage = intval($setting->getValeur());
                        $clientAccess = new ClientAccess($database->getConnection());
                        $clientController = new ClientController($clientAccess);
                        $clients = $clientController->getClientsOptimized($clientsPerPage, ($clientsPerPage * ($page - 1)));
                        $pageData = $DOCUMENT_HEAD;
                        $pageData .= "<script src = '" . $SERVER_ROOT . "View/Scripts/jquery.min.js'></script>";
                        $pageData .= "<script src = '" . $SERVER_ROOT . "View/Scripts/clientAdminFormEvents.js'></script>";
                        $pageData .= file_get_contents("View/Partials/header.html");
                        $pageData .= "<section class = 'section is-large'>";
                        $pageData .= "  <div class = 'box has-text-centered'>
                                            <p class = 'block'><b>Gestion des clients</b></p>
                                            <button onclick = 'editClient();' class = 'button'>Modifier</button> <button onclick = 'deleteClient();' class = 'button'>Supprimer</button>
                                        </div>";
                        $pageData .= "<div class = 'columns is-centered'>
                                        <table class = 'table'>
                                        <thead>
                                            <tr>
                                                <th>/</th>
                                                <th>ID</th>
                                                <th>Nom</th>
                                                <th>Prénom</th>
                                                <th>Email</th>
                                                <th>Tel</th>
                                            </tr>
                                        </thead><tbody id = 'table_rows'>";
                        foreach($clients as $client)
                        {
                            $pageData .= "
                                        <tr>
                                            <th><input id = '" . htmlspecialchars($client->getId()) . "' type = 'checkbox'></input></th>
                                            <th>" . htmlspecialchars($client->getId()) . "</th>
                                            <th>" . htmlspecialchars($client->getNom()) . "</th>
                                            <th>" . htmlspecialchars($client->getPrenom()) . "</th>
                                            <th>" . htmlspecialchars($client->getEmail()) . "</th>
                                            <th>" . htmlspecialchars($client->getTel()) . "</th>
                                        </tr>";
                        }
                        if(count($clients) > 0)
                        {
                            $pageData .= "</tbody></table></div>
                            <div class = 'block has-text-centered mt-6'>
                                <button onclick = 'previousPage();' class = 'button'>Précédent</button> <button onclick = 'nextPage();' class = 'button'>Suivant</button>
                            </div>
                            </section>";
                        }
                        else 
                        {
                            $pageData .= "</tbody></table></div></section><section class = 'section is-medium'></section>";  
                        }
                        $pageData .= file_get_contents("View/Partials/footer.html");
                        echo $pageData;
                    }
                }
            }
            break;
        }
        case "adminChambres":
            session_start();
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin']))
            {
                $roomAccess = new RoomAccess($database->getConnection());
                $controller = new RoomController($roomAccess);
                $roomList = $controller->getFullRoomList();
                $pageData = $DOCUMENT_HEAD;
                $pageData .= "<script src = '" . $SERVER_ROOT . "View/Scripts/jquery.min.js'></script>";
                $index = 0;
                $pageData .= file_get_contents("View/Partials/header.html");
                $pageData .= "<script src='" . $SERVER_ROOT . "View/Scripts/roomFormEvents.js'></script>";
                $pageData .= "<div class='modal' id = 'edit_add_room'>
                                <div class='modal-background'></div>
                                <div class='modal-content'>
                                    <div id = 'modal-title' class = 'block has-text-white'>Modifier la chambre</div>
                                    <div class = 'box'>
                                    <form id = 'modal-form' class = 'control' method = 'POST' enctype='multipart/form-data'>
                                        <label class = 'label' for = 'numero'>Numéro</label>
                                        <input id = 'numero' class = 'input' name = 'numero' type = 'text'>
                                        <label class = 'label' for = 'etage'>Étage</label>
                                        <input id = 'etage' class = 'input' name = 'etage' type = 'text'>
                                        <label class = 'label' for = 'prix'>Prix</label>
                                        <input id = 'prix' class = 'input' name = 'prix' type = 'text'>
                                        <label class = 'label' for = 'titre'>Titre</label>
                                        <input id = 'titre' class = 'input' name = 'titre' type = 'text'>
                                        <label class = 'label' for = 'description'>Description</label>
                                        <textarea id = 'description' class = 'textarea' name = 'description' type = 'text'></textarea>
                                        <label class = 'label' for = 'capacite'>Capacité</label>
                                        <input id = 'capacite' class = 'input' name = 'capacite' type = 'text'>
                                        <label class = 'label' for = 'images'>Images</label>
                                        <input id = 'images' name = 'images[]' class = 'input' type = 'file' multiple><br>
                                        <div id = 'image-placeholder' class = 'box'>
                                              
                                        </div>
                                        <input id = 'modal-submit' class = 'input' type = 'submit' value = 'Valider'>
                                    </form></div>
                                </div>
                <button onclick = \"toggleModal('edit_add_room');\" id = 'close_modal' class='modal-close is-large' aria-label='close'></button>
              </div>";
                $pageData .= "
                            <section class = 'section is-medium'>
                                <div class = 'box has-text-centered'>
                                    <p class = 'block'><b>Gestion des chambres</b></p>
                                    <button class = 'button' onclick = \"addRoom('edit_add_room');\">Ajouter</button>
                                    <button class = 'button' onclick = \"editRoom();\">Modifier</button>
                                    <button class = 'button' onclick = \"deleteRooms();\">Supprimer</button>
                                </div>
                                <div class = 'columns is-centered mt-6'>
                                <table class = 'table'>
                                    <thead>
                                        <tr>
                                            <th>/</th>
                                            <th>ID</th>
                                            <th>Numéro</th>
                                            <th>Étage</th>
                                            <th>Prix</th>
                                            <th>Titre</th>
                                            <th>Capacité</th>
                                        </tr>
                                    </thead>
                                    <tbody id = 'table_rows'>"; 
                                    
                foreach($roomList as $room)
                {
                    $pageData .= "
                                <tr>
                                    <td><input id = '" . htmlspecialchars($room->getId()) . "' type = 'checkbox'></td>
                                    <td>" . htmlspecialchars($room->getId()) . "</td>
                                    <td>" . htmlspecialchars($room->getNumero()) . "</td>
                                    <td>" . htmlspecialchars($room->getEtage()) . "</td>
                                    <td>" . htmlspecialchars($room->getPrix()) . "</td>
                                    <td>" . htmlspecialchars($room->getTitre()) . "</td>
                                    <td>" . htmlspecialchars($room->getCapacite()) . "</td>
                                </tr>";
                    $index += 1;
                }
                $pageData .= "      </tbody>
                                </table></div>
                            </section>";
                $pageData .= "<section class = 'section is-medium'></section>";
                $pageData .= file_get_contents("View/Partials/footer.html");
                echo $pageData;
                break;
            }
        case "adminReservations":
        {
            session_start();
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin']))
            {
                $pageData = $DOCUMENT_HEAD;
                $pageData .= "<script src = '" . $SERVER_ROOT . "View/Scripts/jquery.min.js'></script>";
                $pageData .= "<script src = '" . $SERVER_ROOT . "View/Scripts/reservationAdminEvents.js'></script>";
                $pageData .= file_get_contents("View/Partials/header.html");
                $pageData .= "
                            <section class = 'section is-large'>
                                <div class = 'box has-text-centered'>
                                    <p class = 'block'><b>Planning des réservations</b></p>
                                    <div class = 'block'>
                                        <button onclick = \"document.location.href = '" . $SERVER_ROOT . "addReservationForm'\" class = 'button'>Ajouter</button> <button onclick = 'editRoom();' class = 'button'>Modifier</button> <button onclick = 'deleteReservations();' class = 'button'>Supprimer</button>
                                    </div>
                                    <center><table class = 'table'>
                                        <thead>
                                            <tr>
                                                <th>/</th>
                                                <th>ID de réservation</th>
                                                <th>Nom du client</th>
                                                <th>Prénom du client</th>
                                                <th>ID de séjour</th>
                                                <th>Date de début</th>
                                                <th>Date de fin</th>
                                                <th>Numéro de chambre</th>
                                                <th>ID de chambre</th>
                                            </tr>
                                        </thead>
                                        <tbody id = 'table_rows'>";
                $reservationAccess = new ReservationAccess($database->getConnection());
                $reservationController = new ReservationController($reservationAccess);
                $sejourAccess = new SejourAccess($database->getConnection());
                $sejourController = new SejourController($sejourAccess);
                //
                $settingAccess = new SettingAccess($database->getConnection());
                $settingController = new SettingController($settingAccess);
                $setting = $settingController->getSettingByClef("admin_reservations_par_pages");
                //
                $reservationsPerPage = intval($setting->getValeur());
                $currentPage = -1;
                if(isset($_GET['page']))
                {
                    $currentPage = intval($_GET['page']) == 0 ? -1 : intval($_GET['page']);
                }
                else 
                {
                    $currentPage = 1;
                }
                $reservations = $reservationController->getReservationsOptimised($reservationsPerPage, $reservationsPerPage * ($currentPage - 1));
                foreach($reservations as $resa)
                {
                    $sejour = $sejourController->getSejourByReservation($resa);
                    $roomAccess = new RoomAccess($database->getConnection());
                    $roomController = new RoomController($roomAccess);
                    $room = $roomController->getRoomBySejour($sejour);
                    $pageData .= "          <tr>
                                                <th><input id = '" . htmlspecialchars($resa->getId()) . "' type = 'checkbox'></input></th>
                                                <th>" . htmlspecialchars($resa->getId()) . "</th>
                                                <th>" . htmlspecialchars($resa->getClient()->getNom()) . "</th>
                                                <th>" . htmlspecialchars($resa->getClient()->getPrenom()) . "</th>
                                                <th>" . htmlspecialchars($sejour->getId()) . "</th>
                                                <th>" . htmlspecialchars($sejour->getDateDebut()->format('d-m-Y')) . "</th>
                                                <th>" . htmlspecialchars($sejour->getDateFin()->format('d-m-Y')) . "</th>
                                                <th>" . htmlspecialchars($room->getNumero()) . "</th>
                                                <th>" . htmlspecialchars($room->getId()) . "</th>
                                            </tr>";
                }
                $pageData .= "          </tbody>
                                    </table></center>
                                    <div class = 'block mt-6'>
                                        <button onclick = 'previousPage();' class = 'button'>Précédent</button><button onclick = 'nextPage();' class = 'button'>Suivant</button>
                                    </div>
                                </div>
                            </section>";
                $pageData .= file_get_contents("View/Partials/footer.html");
                echo $pageData;
            }
            break;
        }
        case "addReservationForm":
        {
            session_start();
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin']))
            {
                $pageData = $DOCUMENT_HEAD;
                $pageData .= file_get_contents("View/Partials/header.html");
                $pageData .= "
                                <section class = 'section'>
                                    <form class = 'control' method = 'POST' action = '" . $SERVER_ROOT . "addReservation'>
                                        <div class = 'box'>
                                            <p class = 'block is-size-3'><b>Informations séjour</b></p>
                                            <label for = 'roomid'>ID de chambre*</label>
                                            <input id = 'roomid' name = 'roomid' class = 'input' type = 'number'></input>
                                            <label for = 'dateDebut'>Date de début*</label>
                                            <input id = 'dateDebut' name = 'dateDebut' class = 'input' type = 'date'></input>
                                            <label for = 'dateFin'>Date de fin*</label>
                                            <input id = 'dateFin' name = 'dateFin' class = 'input' type = 'date'></input>
                                        </div>
                                        <div class = 'box'>
                                            <p class = 'block is-size-3'><b>Informations client</b></p>
                                            <label for = 'nomClient'>Nom*</label>
                                            <input id = 'nomClient' name = 'nomClient' class = 'input' type = 'text'></input>
                                            <label for = 'prenomClient'>Prénom*</label>
                                            <input id = 'prenomClient' name = 'prenomClient' class = 'input' type = 'text'></input>
                                            <label for = 'emailClient'>Email</label>
                                            <input id = 'emailClient' name = 'emailClient' class = 'input' type = 'email'></input>
                                            <label for = 'telClient'>Téléphone</label>
                                            <input id = 'telClient' name = 'telClient' class = 'input' type = 'tel'></input>
                                        </div>
                                        <div class = 'box'>
                                            <input class = 'input' type = 'submit' value = 'Valider'></input>
                                        </div>
                                    </form>
                                </section>";
                $pageData .= file_get_contents("View/Partials/footer.html");
                echo $pageData;
            }
            break;
        }
        case "editReservationForm":
        {
            session_start();
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin']))
            {
                if(isset($_GET['resid']))
                {
                    $reservationId = intval($_GET['resid']) == 0 ? -1 : intval($_GET['resid']);
                    $pageData = $DOCUMENT_HEAD;
                    if($reservationId != -1)
                    {
                        $pageData .= file_get_contents("View/Partials/header.html");
                        $reservationAccess = new ReservationAccess($database->getConnection());
                        $reservationController = new ReservationController($reservationAccess);
                        $reservation = $reservationController->getReservationById($reservationId);
                        if($reservation != null)
                        {
                            $sejourAccess = new SejourAccess($database->getConnection());
                            $sejourController = new SejourController($sejourAccess);
                            $sejour = $sejourController->getSejourByReservation($reservation);
                            $client = $reservation->getClient();
                            if($sejour != null)
                            {
                                $personneAccess = new PersonneAccess($database->getConnection());
                                $personneController = new PersonneController($personneAccess);
                                $personne = $personneController->getPersonneBySejour($sejour);
                                $pageData .= "
                                            <section class = 'section'>
                                                <form class = 'control' method = 'POST' action = '" . $SERVER_ROOT . "editReservation'>
                                                    <div class = 'box'>
                                                        <input name = 'reservationid' type = 'hidden' value = '" . htmlspecialchars($reservation->getId()) . "'></input>
                                                        <p class = 'block is-size-3'><b>Informations séjour</b></p>
                                                        <label for = 'roomid'>ID de chambre*</label>
                                                        <input value = '" . htmlspecialchars($personne->getChambre()->getId()) . "' id = 'roomid' name = 'roomid' class = 'input' type = 'number'></input>
                                                        <label for = 'dateDebut'>Date de début*</label>
                                                        <input value = '" . htmlspecialchars($sejour->getDateDebut()->format('Y-m-d')) . "'id = 'dateDebut' name = 'dateDebut' class = 'input' type = 'date'></input>
                                                        <label for = 'dateFin'>Date de fin*</label>
                                                        <input value = '" . htmlspecialchars($sejour->getDateFin()->format('Y-m-d')) . "' id = 'dateFin' name = 'dateFin' class = 'input' type = 'date'></input>
                                                    </div>
                                                    <div class = 'box'>
                                                        <p class = 'block is-size-3'><b>Informations client</b></p>
                                                        <label for = 'nomClient'>Nom*</label>
                                                        <input value = '" . htmlspecialchars($client->getNom()) . "' id = 'nomClient' name = 'nomClient' class = 'input' type = 'text'></input>
                                                        <label for = 'prenomClient'>Prénom*</label>
                                                        <input value = '" . htmlspecialchars($client->getPrenom()) . "' id = 'prenomClient' name = 'prenomClient' class = 'input' type = 'text'></input>
                                                        <label for = 'emailClient'>Email</label>
                                                        <input value = '" . htmlspecialchars($client->getEmail()) . "' id = 'emailClient' name = 'emailClient' class = 'input' type = 'email'></input>
                                                        <label for = 'telClient'>Téléphone</label>
                                                        <input value = '" . htmlspecialchars($client->getTel()) . "' id = 'telClient' name = 'telClient' class = 'input' type = 'tel'></input>
                                                    </div>
                                                    <div class = 'box'>
                                                        <input class = 'input' type = 'submit' value = 'Valider'></input>
                                                    </div>
                                                </form> 
                                            </section>";
                                $pageData .= file_get_contents("View/Partials/footer.html");
                            }
                            else 
                            {
                                $pageData .= "<script>alert('Erreur interne.');</script>";
                            }
                        }
                        else 
                        {
                            $pageData .= "<script>alert('Erreur interne.');</script>";
                        }
                    }
                    else 
                    {
                        $pageData .= "<script>alert('Erreur : id de réservation invalide.'); document.location.href='" . $SERVER_ROOT . "adminReservations/1'</script>";
                    }
                    echo $pageData;
                }
                else 
                {
                    header("location:" . $SERVER_ROOT . "404");
                }
            }
            else 
            {
                header("location:" . $SERVER_ROOT . "404");
            }
            break;
        }
        case "editReservation":
        {
            session_start();
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin']))
            {
                if(isset($_POST['reservationid']) && isset($_POST['roomid']) && isset($_POST['dateDebut']) && isset($_POST['dateFin']) && isset($_POST['nomClient']) && isset($_POST['prenomClient']))
                {
                    $roomId = intval($_POST['roomid']) == 0 ? -1 : intval($_POST['roomid']);
                    $reservationId = intval($_POST['reservationid']) == 0 ? -1 : intval($_POST['reservationid']);
                    $dateDebut = $_POST['dateDebut'];
                    $dateFin = $_POST['dateFin'];
                    $nomClient = $_POST['nomClient'];
                    $prenomClient = $_POST['prenomClient'];
                    $emailClient = $_POST['emailClient'];
                    $telClient = $_POST['telClient'];
                    if($roomId != -1 && $reservationId != -1)
                    {
                        $reservationAccess = new ReservationAccess($database->getConnection());
                        $reservationController = new ReservationController($reservationAccess);
                        $reservation = $reservationController->getReservationById($reservationId);
                        $client = $reservation->getClient();
                        if($reservation != null && $client != null)
                        {
                            $sejourAccess = new SejourAccess($database->getConnection());
                            $sejourController = new SejourController($sejourAccess);
                            $sejour = $sejourController->getSejourByReservation($reservation);
                            if($sejour != null)
                            {
                                $personneAccess = new PersonneAccess($database->getConnection());
                                $personneController = new PersonneController($personneAccess);
                                $personnes = $personneController->getPersonnesBySejour($sejour);
                                if(count($personnes) > 0)
                                {
                                    $chambre = $personnes[0]->getChambre();
                                    //Modification des dates
                                    if($dateDebut != $sejour->getDateDebut()->format('Y-m-d') || $dateFin != $sejour->getDateFin()->format('Y-m-d'))
                                    {
                                        if($reservationController->isRoomAvailable($chambre->getId(), $dateDebut, $dateFin)) 
                                        {
                                            $db = DateTime::createFromFormat("Y-m-d", $dateDebut);
                                            $df = DateTime::createFromFormat("Y-m-d", $dateFin);
                                            $sejour->setDateDebut($db);
                                            $sejour->setDateFin($df);
                                            $sejourController->updateSejour($sejour);
                                        }
                                        else 
                                        {
                                            echo "<script>alert('Erreur, la chambre est déjà réservée par un autre client pour la période séléctionnée.'); document.location.href='" . $SERVER_ROOT . "adminReservations/1'</script>";
                                        }
                                    }
                                    //Modification de la chambre
                                    if($roomId != $chambre->getId())
                                    {
                                        if($reservationController->isRoomAvailable($roomId, $dateDebut, $dateFin)) 
                                        {
                                            $roomAccess = new RoomAccess($database->getConnection());
                                            $roomController = new RoomController($roomAccess);
                                            $nouvChambre = $roomController->getRoomInfos($roomId); 
                                            foreach($personnes as $p)
                                            {
                                                $p->setChambre($nouvChambre);
                                                $personneController->updateChambrePersonne($p);
                                            }
                                        }
                                        else 
                                        {
                                            echo "<script>alert('Erreur, la nouvelle chambre est déjà réservée par un autre client pour la période séléctionnée.'); document.location.href='" . $SERVER_ROOT . "adminReservations/1'</script>";
                                        }
                                    }
                                    if($prenomClient != $client->getPrenom() || $nomClient != $client->getNom() || $emailClient != $client->getEmail() || $telClient != $client->getTel())
                                    {
                                        $clientAccess = new ClientAccess($database->getConnection());
                                        $clientController = new ClientController($clientAccess);
                                        $client->setPrenom($prenomClient);
                                        $client->setNom($nomClient);
                                        $client->setEmail($emailClient);
                                        $client->setTel($telClient);
                                        $clientController->updateClient($client);
                                        $personnes[0]->setPrenom($prenomClient);
                                        $personnes[0]->setNom($nomClient); 
                                        $personneController->updatePersonne($personnes[0]);
                                    }
                                    echo "<script>alert('Modifications enregistrées avec succès.'); document.location.href='" . $SERVER_ROOT . "adminReservations/1'</script>";
                                }
                            }
                        }
                    }
                    else 
                    {
                        echo "<script>alert('Erreur interne.');</script>";
                    }
                }
                else 
                {
                    echo "<script>alert('Erreur de saisie : des informations sont manquantes dans le formulaire de modification de la réservation, veuillez vérifier la saisie.');</script>";
                }
            }
            else 
            {
                header("location: " . $SERVER_ROOT . "404");
            }
            break;
        }
        case "adminEditClient":
        {
            session_start();
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin']))
            {
                if(isset($_GET['clientid']) && !empty($_GET['clientid']))
                {
                    $clientId = intval($_GET['clientid']) == 0 ? -1 : intval($_GET['clientid']);
                    if($clientId != -1)
                    {
                        $pageData = $DOCUMENT_HEAD;
                        $pageData .= file_get_contents("View/Partials/header.html");
                        $clientAccess = new ClientAccess($database->getConnection());
                        $clientController = new ClientController($clientAccess);
                        $client = $clientController->getClient($clientId);
                        if($client != null)
                        {
                            $pageData .= 
                            "
                                <section class = 'section is-medium'>
                                    <div class = 'box'>
                                        <form method = 'POST' action = '" . $SERVER_ROOT . "doEditClient' class = 'control'>
                                            <input name = 'clientid' class = 'input' type = 'hidden' value = '" . htmlspecialchars($clientId) . "'></input>
                                            <label for = 'nom'>Nom</label>
                                            <input class = 'input' type = 'text' name = 'nom' id = 'nom' value = '" . htmlspecialchars($client->getNom()) . "'></input>
                                            <label for = 'prenom'>Prénom</label>
                                            <input value = '" . htmlspecialchars($client->getPrenom()) . "' class = 'input' type = 'text' name = 'prenom' id = 'prenom'></input>
                                            <label for = 'email'>Email</label>
                                            <input value = '" . htmlspecialchars($client->getEmail()) . "' class = 'input' type = 'email' name = 'email' id = 'email'></input>
                                            <label for = 'tel'>Tel</label>
                                            <input value = '" . htmlspecialchars($client->getTel()) . "' class = 'input' type = 'text' name = 'tel' id = 'tel'></input>
                                            <div class = 'block mt-3'>
                                            <input class = 'input' type = 'submit' value = 'Valider'></input></div>
                                        </form>
                                    </div>
                                </section>";
                            $pageData .= file_get_contents("View/Partials/footer.html");
                            echo $pageData;
                        }
                    }
                }
            }
            break;
        }
        case "doEditClient":
        {
            session_start();
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin']))
            {
                if(isset($_POST['clientid']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['email']) && isset($_POST['tel']))
                {
                    $clientId = intval($_POST['clientid']) == 0 ? -1 : intval($_POST['clientid']);
                    $nom = $_POST['nom'];
                    $prenom = $_POST['prenom'];
                    $email = $_POST['email'];
                    $tel = $_POST['tel'];
                    if($clientId != -1)
                    {
                        $clientAccess = new ClientAccess($database->getConnection());
                        $clientController = new ClientController($clientAccess);
                        $client = $clientController->getClient($clientId);
                        if($client != null)
                        {
                            $client->setNom($nom);
                            $client->setPrenom($prenom);
                            $client->setEmail($email);
                            $client->setTel($tel);
                            $clientController->updateClient($client);
                            echo "<script>alert('Modifications enregistrées avec succès.'); document.location.href='" . $SERVER_ROOT . "adminClients/1'</script>";
                        }
                        else 
                        {
                            echo "<script>alert('Erreur interne.');</script>";
                        }
                    }
                    else 
                    {
                        echo "<script>alert('Erreur interne.');</script>";
                    }
                }
                else 
                {
                    echo "<script>alert('Erreur de saisie du formulaire.');</script>";
                }
            }
            break;
        }
        case "addReservation":
        {
            session_start();
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin']))
            {
                $roomId = intval($_POST['roomid']) == 0 ? -1 : intval($_POST['roomid']);
                $dateDebut = DateTime::createFromFormat("Y-m-d", $_POST['dateDebut']);
                $dateFin = DateTime::createFromFormat('Y-m-d', $_POST['dateFin']);
                $nomClient = $_POST['nomClient'];
                $prenomClient = $_POST['prenomClient'];
                $emailClient = $_POST['emailClient'];
                $telClient = $_POST['telClient'];
                $pageData = $DOCUMENT_HEAD;
                $pageData .= file_get_contents("View/Partials/header.html");
                if($roomId != -1)
                {
                    if(strlen($nomClient) > 0)
                    {
                        if(strlen($prenomClient) > 0)
                        {
                            $reservationAccess = new ReservationAccess($database->getConnection());
                            $reservationController = new ReservationController($reservationAccess);
                            if($reservationController->isRoomAvailable($roomId, $dateDebut->format('Y-m-d'), $dateFin->format('Y-m-d')))
                            {
                                $clientAccess = new ClientAccess($database->getConnection());
                                $clientController = new ClientController($clientAccess);
                                $clientid = $clientController->addClient($nomClient, $prenomClient, $emailClient, $telClient);
                                $client = $clientController->getClient($clientid);
                                if($client != null)
                                {
                                    $roomAccess = new RoomAccess($database->getConnection());
                                    $roomController = new RoomController($roomAccess);
                                    $room = $roomController->getRoomInfos($roomId);
                                    $capacite = $room->getCapacite();
                                    $reservationId = $reservationController->addReservation($capacite, $client);
                                    if($reservationId > 0)
                                    {
                                        $sejourAccess = new SejourAccess($database->getConnection());
                                        $sejourController = new SejourController($sejourAccess);
                                        $sejourId = $sejourController->addSejour($dateDebut->format('Y-m-d'), $dateFin->format('Y-m-d'), $reservationId);
                                        if($sejourId > 0)
                                        {
                                            $personneAccess = new PersonneAccess($database->getConnection());
                                            $personneController = new PersonneController($personneAccess);
                                            $personneController->addPerson($nomClient, $prenomClient, null, "", $roomId, $sejourId);
                                            $pageData .= "<script>alert('La réservation a bien été ajoutée.'); document.location.href = '" . $SERVER_ROOT . "adminReservations/1'</script>";
                                        }
                                        else  
                                        {
                                            $pageData .= "<script>alert('Erreur interne lors de l\'enregistrement du séjour.');</script>";
                                        }
                                    }
                                    else 
                                    {
                                        $pageData .= "<script>alert('Erreur interne lors de l\'enregistrement de la réservation.');</script>";
                                    }
                                }
                                else 
                                {
                                    $pageData .= "<script>alert('Erreur interne lors de l\'enregistrement du client.');</script>";
                                }
                            } 
                            else 
                            {
                                $pageData .= "<script>alert('Erreur : la chambre est déjà réservée pour la période séléctionnée.');</script>";
                            }
                        }
                        else 
                        {
                            $pageData .= "<script>alert('Erreur de saisie : le prénom du client est vide.');</script>";
                        }
                    }
                    else 
                    {
                        $pageData .= "<script>alert('Erreur de saisie : le nom du client est vide.');</script>";
                    }
                }
                else 
                {
                    $pageData .= "<script>alert('Erreur de saisie : l\'identifiant de chambre saisit est invalide.');</script>";
                }
                $pageData .= file_get_contents("View/Partials/footer.html");
                echo $pageData;
            }  
            break;
        }
        case "removeClients":
        {
            session_start();
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin']))
            {
                if(isset($_POST['clientids']))
                {
                    $ids = $_POST['clientids'];
                    $clientAccess = new ClientAccess($database->getConnection());
                    $clientController = new ClientController($clientAccess);
                    foreach($ids as $id)
                    {
                        $clientController->deleteClient($id);
                    }
                    echo 1;
                }
                else 
                {
                    echo 0;
                }
            }
            break;
        }
        case "deleteReservations":
        {
            session_start();
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin']))
            {
                if(isset($_POST['ids']))
                {
                    $ids = $_POST['ids'];
                    foreach($ids as $id)
                    {
                        $reservationAccess = new ReservationAccess($database->getConnection());
                        $reservationController = new ReservationController($reservationAccess);
                        $reservationController->deleteReservation(intval($id));
                    }
                    echo 1;
                }
                else 
                {
                    echo 0;
                }
            }
            break;
        }
        case "addRoom":
        {
            session_start();
            if(isset($_SESSION['admin']) && !empty($_SESSION['admin']))
            {
                $pageData = $DOCUMENT_HEAD;
                $pageData .= file_get_contents("View/Partials/header.html");
                if(isset($_POST['capacite']) && isset($_POST['numero']) && isset($_POST['etage']) && isset($_POST['prix']) && isset($_POST['titre']) && isset($_POST['description']))
                {
                    $numero = intval($_POST['numero']) !== 0 ? intval($_POST['numero']) : -1;
                    $etage = intval($_POST['etage']) !== 0 ? intval($_POST['etage']) : -1;
                    $prix = floatval($_POST['prix']) !== 0 ? floatval($_POST['prix']) : -1; 
                    $titre = $_POST['titre'];
                    $description = $_POST['description'];
                    $capacite = intval($_POST['capacite']) !== 0 ? intval($_POST['capacite']) : -1;
                    if($numero === -1)
                    {
                        $pageData .= "<script>alert('Erreur de saisie sur le numéro de la chambre, veuillez réessayer.'); window.location.href = 'adminChambres';</script>";
                        break;
                    }
                    else if($etage === -1)
                    {
                        $pageData .= "<script>alert('Erreur de saisie sur l'étage de la chambre, veuillez réessayer.'); window.location.href = 'adminChambres';</script>";
                        break;
                    }
                    else if($prix === -1)
                    {
                        $pageData .= "<script>alert('Erreur de saisie sur le prix de la chambre, veuillez réessayer.'); window.location.href = 'adminChambres';</script>";
                        break;
                    }
                    else if(strlen($titre) === 0)
                    {
                        $pageData .= "<script>alert('Erreur de saisie sur le titre de la chambre, veuillez réessayer.'); window.location.href = 'adminChambres';</script>";
                        break;
                    }
                    else if(strlen($description) === 0)
                    {
                        $pageData .= "<script>alert('Erreur de saisie sur la description de la chambre, veuillez réessayer.'); window.location.href = 'adminChambres';</script>";
                        break;
                    }
                    $roomAccess = new RoomAccess($database->getConnection());
                    $controller = new RoomController($roomAccess);
                    $targetDir = "Images/";
                    $validFileType = true;
                    $names = array();
                    for($i = 0; $i < count($_FILES); $i++)
                    {
                        $check = getimagesize($_FILES["images"]["tmp_name"][$i]);
                        if(!$check)
                        {
                            $validFileType = false;
                            break;
                        }
                    }
                    if($validFileType)
                    {
                        $success = true;
                        for($i = 0; $i < count($_FILES['images']['name']); $i++)
                        {
                            $to = "Images/". rand(0, 1024) . date("s") . basename($_FILES['images']['name'][$i]);
                            if(!move_uploaded_file($_FILES['images']['tmp_name'][$i], $to))
                            { 
                                $success = false;
                                break;
                            }
                            array_push($names, str_replace("Images/", "", $to));
                        }
                        if($success)
                        {
                            $images = "";
                            for($i = 0; $i < count($names); $i++)
                            {
                                if($i !== count($names) - 1)
                                {
                                    $images .= $names[$i] . ";";
                                }
                                else 
                                {
                                    $images .= $names[$i];
                                }
                            }
                            $room = new Room(-1, $prix, $capacite, $numero, $etage, $titre, $description, null, $images);
                            if($controller->addRoom($room))
                            {
                                $pageData .= "<script>alert('La chambre a bien été ajoutée à la base de données.'); window.location.href = '" . $SERVER_ROOT . "adminChambres'</script>"; 
                            }
                            else 
                            {
                                $pageData .= "<script>alert('Une erreur est survenue lors de l\'ajout de la chambre dans la base de données.');</script>";
                            }
                        }
                        else 
                        {
                            $pageData .= "<script>alert('Impossible de téléverser les images.'); window.location.href = 'adminChambres';</script>";
                            break;
                        }
                    }
                }
                else 
                {
                    $pageData .= "<script>alert('Vous devez saisir tous les champs du formulaire afin d\'ajouter une nouvelle chambre.'); window.location.href = 'adminChambres';</script>";
                }
                $pageData .= file_get_contents("View/Partials/footer.html");
                echo $pageData;
                break;
            }
        }
        case "getRoomInfos":
            session_start();
            if(isset($_SESSION['admin']) && isset($_GET['room']))
            {
                $roomAccess = new RoomAccess($database->getConnection());
                $controller = new RoomController($roomAccess);
                $room = $controller->getRoomInfos(intval($_GET['room']));
                $json = json_encode($room);
                echo $json;
                break;
            }
        case "removeRoomImage":
            session_start();
            if(isset($_SESSION['admin']) && isset($_POST['id']) && isset($_POST['name']))
            {
                $access = new RoomAccess($database->getConnection());
                $controller = new RoomController($access);
                if($controller->removeRoomImage(intval($_POST['id']), $_POST['name']))
                {
                    echo 1;
                }
                else 
                {
                    echo 0;
                }
            }
            break;
        case "removeRooms":
            session_start();
            if(isset($_SESSION['admin']) && isset($_POST['roomids']))
            {
                $access = new RoomAccess($database->getConnection());
                $controller = new RoomController($access);
                if($controller->removeRooms($_POST['roomids']))
                {
                    echo 1;
                }
                else 
                {
                    echo 0;
                }
            }
            break;
        case "checkout":
        {
            /*require_once 'vendor/autoload.php';
            require_once 'secrets.php';
            \Stripe\Stripe::setApiKey($stripeSecretKey);
            header('Content-Type: application/json');
            $YOUR_DOMAIN = "http://192.168.122.232/~sysadmin/PlaniHost/";
            $checkout_session = \Stripe\Checkout\Session::create
            ([
                'line_items' => [[
                    # Provide the exact Price ID (e.g. pr_1234) of the product you want to sell
                    'price' => '{{PRICE_ID}}',
                    'quantity' => 1,
                  ]],
                  'mode' => 'payment',
                  'success_url' => $YOUR_DOMAIN . '/success.html',
                  'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
              ]);
            header("HTTP/1.1 303 See Other");
            header("Location: " . $checkout_session->url);*/
            break;
        }
        default:
            $pageData = $DOCUMENT_HEAD;
            $pageData .= "<section class = 'secion is-large has-text-centered mt-6'><h1 class = 'title'>Erreur HTTP 404, le document auquel vous souhaitez accéder n'existe pas ou plus sur le serveur.</h1></section>";
            $pageData .= "<section class = 'section has-text-centered'><a href = './accueil'>Retour à l'accueil.</a></section></body></html>";
            echo $pageData;
            break;
    }