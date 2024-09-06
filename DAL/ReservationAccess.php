<?php 

class ReservationAccess extends Access 
{
    public function getReservationsOptimised($limit, $offset)
    {
        $sql = "SELECT * FROM reservations LIMIT ? OFFSET ?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $reservations = [];
        while($row = $result->fetch_assoc())
        {
            $idClient = $row['idClient'];
            $sql2 = "SELECT * FROM clients WHERE id = ?;";
            $stmt2 = $this->database->prepare($sql2);
            $stmt2->bind_param("i", $idClient);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            while($cRow = $result2->fetch_assoc())
            {
                $client = new Client($cRow['id'], $cRow['nom'], $cRow['prenom'], $cRow['email'], $cRow['tel'], $cRow['stripe_client_id']);
                $reservation = new Reservation($row['id'], $row['nbPersonnes'], $client);
                array_push($reservations, $reservation);
            } 
        }
        return $reservations;
    }
    public function getReservationById($resId)
    {
        $sql = "SELECT * FROM reservations WHERE id = ?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $resId);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc())
        {
            $idClient = $row['idClient'];
            $sql2 = "SELECT * FROM clients WHERE id = ?;";
            $stmt2 = $this->database->prepare($sql2);
            $stmt2->bind_param("i", $idClient);
            $stmt2->execute();
            $result = $stmt2->get_result();
            while($row2 = $result->fetch_assoc())
            {
                $client = new Client($row2['id'], $row2['nom'], $row2['prenom'], $row2['email'], $row2['tel'], $row2['stripe_client_id']);
                $resa = new Reservation($row['id'], $row['nbPersonnes'], $client);
                return $resa;
            }
        }
        return null;
    }
    public function getClientReservations($client)
    {
        $sql = "SELECT * FROM reservations WHERE idClient = ?;";
        $stmt = $this->database->prepare($sql);
        $idClient = $client->getId();
        $stmt->bind_param("i", $idClient);
        $stmt->execute();
        $result = $stmt->get_result();
        $reservations = [];
        while($row = $result->fetch_assoc())    
        {
            $resa = new Reservation($row['id'], $row['nbPersonnes'], $client);
            array_push($reservations, $resa);
        }
        return $reservations;
    }
    public function getRoomCalendar($roomId)
    {
        $sql = "SELECT 
                    dateDebut, dateFin 
                FROM 
                    chambres, reservations, sejours, personnes
                WHERE
                    (chambres.id = ?)
                AND
                    (personnes.idChambre = chambres.id)
                AND 
                    (personnes.idSejour = sejours.id)
                AND 
                    (reservations.id = sejours.idReservation)    
                ;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $roomId);
        $stmt->execute();
        $result = $stmt->get_result();
        $calendar = [];
        while($row = $result->fetch_assoc())
        {
            $dates = [strtotime($row['dateDebut']), strtotime($row['dateFin'])];
            array_push($calendar, $dates);
        }
        var_dump($calendar);
        return $calendar;
    }
    public function getRoomCalendarOptimised($roomId, $from, $to)
    {  
        $sql = "SELECT 
            dateDebut, dateFin 
        FROM 
            sejours, reservations, chambres, personnes
        WHERE
            (dateDebut >= ? OR dateDebut <= ?)
        AND
            (chambres.id = ?)
        AND
            (personnes.idChambre = chambres.id)
        AND 
            (personnes.idSejour = sejours.id)
        AND 
            (reservations.id = sejours.idReservation) 
        ORDER BY 
            dateDebut, dateFin
        ASC;"; 
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("ssi", $from, $to, $roomId); 
        $stmt->execute();
        $result = $stmt->get_result();
        $calendar = [];
        while($row = $result->fetch_assoc())
        {
            $dates = [strtotime($row['dateDebut']), strtotime($row['dateFin'])];
            array_push($calendar, $dates);
        }
        return $calendar;
    }
    public function isRoomAvailable($roomId, $from, $to)
    {
        $sql = "SELECT 
                    chambres.id 
                FROM 
                    sejours, reservations, chambres, personnes
                WHERE
                    (? >= dateDebut AND ? <= dateFin)
                AND 
                    (? >= dateDebut AND ? <= dateFin)
                AND
                    (chambres.id = ?)
                AND
                    (personnes.idChambre = chambres.id)
                AND 
                    (personnes.idSejour = sejours.id)
                AND 
                    (reservations.id = sejours.idReservation) 
                ;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("ssssi", $from, $from, $to, $to, $roomId); 
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows == 0;
    }
    public function addReservation($nbPersonnes, $client)
    {
        $sql = "INSERT INTO reservations (nbPersonnes, idClient) VALUES(?, ?);";
        $stmt = $this->database->prepare($sql);
        $idClient = $client->getId();
        $stmt->bind_param("ii", $nbPersonnes, $idClient);
        $stmt->execute();
        return $this->database->insert_id;
    } 
    public function deleteReservation($id)
    {
        $sql = "DELETE FROM reservations WHERE id = ?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }  
}