<?php 

class RoomAccess extends Access
{
    public function getRoomList($limit, $offset)
    {
        $rooms = array();
        $sql = "SELECT * FROM chambres LIMIT ? OFFSET ?;";
        $stmt = $this->database->prepare($sql); 
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result)   
        {
            while($row = $result->fetch_assoc())
            {
                $room = new Room($row['id'], $row['prix'], $row['capacite'], $row['numero'], $row['etage'], $row['titre'], $row['description'], $row['stripe_prod_id'] ,explode(";", $row['images']));
                array_push($rooms, $room);
            }
        }
        return $rooms;
    }
    public function getFullRoomList()
    {
        $rooms = array();
        $sql = "SELECT * FROM chambres;";
        $result = $this->database->query($sql);
        if($result->num_rows > 0)
        {
            while($row = $result->fetch_assoc())
            {
                $room = new Room($row['id'], $row['prix'], $row['capacite'], $row['numero'], $row['etage'], $row['titre'], $row['description'], $row['stripe_prod_id'], explode(";", $row['images']));
                array_push($rooms, $room);
            }
        }
        return $rooms;
    }
    public function getMaxRooms()
    {
        $sql = "SELECT count(*) as total FROM chambres;";
        $result = $this->database->query($sql);
        foreach($result as $row)
        {
            return $row['total'];
        }
    }
    public function getRoomInfos($id)
    {
        $sql = "SELECT * FROM chambres WHERE id = ?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result)
        {
            while($row = $result->fetch_assoc())
            {
                $room = new Room($row['id'], $row['prix'], $row['capacite'], $row['numero'], $row['etage'], $row['titre'], $row['description'], $row['stripe_prod_id'], $row['images']);
                return $room;
            }
        }
        return null;
    }
    public function addRoom($room)
    {
        //TODO ajouter un produit stripe basé sur les caractéristiques de la chambre
        require_once 'vendor/autoload.php';
        require_once 'stripeSecrets/secrets.php';
        $stripe = new \Stripe\StripeClient($stripeSecretKey);
        $product = $stripe->products->create
        ([
            'name' => htmlspecialchars($room->getTitre()),
            'default_price_data' => 
            [
                'unit_amount' => htmlspecialchars($room->getPrix() * 100),
                'currency' => 'eur'
            ],
            'expand' => ['default_price']
        ]);
        $stripeProductId = $product->id;
        $sql = "INSERT INTO chambres (prix, capacite, numero, etage, titre, `description`, stripe_prod_id, images) VALUES(?, ?, ?, ?, ?, ?, ?, ?);";
        $stmt = $this->database->prepare($sql);
        $images = $room->getImages();
        $prix = $room->getPrix();
        $capacite = $room->getCapacite();
        $numero = $room->getNumero();
        $etage = $room->getEtage();
        $titre = $room->getTitre();
        $description = $room->getDescription();
        $stmt->bind_param("diiissss", $prix, $capacite, $numero, $etage, $titre, $description, $stripeProductId, $images);
        $result = $stmt->execute();
        if($result)
        {
            return true;
        }
        return false;
    }
    public function removeRoomImage($roomid, $imageName)
    {
        $sql = "SELECT images FROM chambres WHERE id=?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $roomid);
        $stmt->execute();
        $result = $stmt->get_result();
        $images = "";
        while($row = $result->fetch_assoc())
        {
            $images = $row["images"];
        }
        $images = explode(";", $images);
        for($i = 0; $i < count($images); $i++)
        {
            if($images[$i] == $imageName)
            {
                unset($images[$i]);
            }
        }
        $images = implode(";", $images);
        $sql = "UPDATE chambres SET images = ? WHERE id=?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("si", $images, $roomid);
        $result = $stmt->execute();
        if($result)
        {
            return true;
        }
        return false;
    }
    public function removeRooms($roomids)
    {
        $sql = "DELETE FROM chambres WHERE id=?;";
        $stmt = $this->database->prepare($sql);
        $state = true;
        $ids = &$roomids;
        for($i = 0; $i < count($roomids); $i++)
        {
            $stmt->bind_param("i", $ids[$i]);
            $result = $stmt->execute();
            if(!$result)
            {
                $state = false;
            }
        }
        return $state;
    }
    public function getRoomBySejour($sejour)
    {
        $sql = "SELECT chambres.id, chambres.prix, chambres.capacite, chambres.numero, chambres.etage, chambres.titre, chambres.description, chambres.images, chambres.stripe_prod_id FROM personnes, chambres WHERE idSejour = ? AND personnes.idChambre = chambres.id;";
        $stmt = $this->database->prepare($sql);
        $idSejour = $sejour->getId();
        $stmt->bind_param("i", $idSejour);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc())
        {
            $room = new Room($row['id'], $row['prix'], $row['capacite'], $row['numero'], $row['etage'], $row['titre'], $row['description'], $row['stripe_prod_id'], $row['images']);
            return $room;
        }
    }
}