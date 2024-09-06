<?php

class SejourAccess extends Access
{
    public function addSejour($dateDebut, $dateFin, $idReservation)
    {
        $sql = "INSERT INTO sejours (dateDebut, dateFin, idReservation) VALUES(?, ?, ?);";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("ssi", $dateDebut, $dateFin, $idReservation);
        $stmt->execute();
        return $this->database->insert_id;
    }
    public function getSejourByReservation($reservation)
    {
        $sql = "SELECT * FROM sejours WHERE idReservation = ?;";
        $stmt = $this->database->prepare($sql);
        $idResa = $reservation->getId();
        $stmt->bind_param("i", $idResa);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc())
        {
            $sejour = new Sejour($row['id'], DateTime::createFromFormat('Y-m-d', $row['dateDebut']), DateTime::createFromFormat('Y-m-d', $row['dateFin']), $reservation);
            return $sejour;
        }
        return null;
    }
    public function updateSejour($sejour)
    {
        $sql = "UPDATE sejours SET dateDebut = ?, dateFin = ? WHERE id = ?;";
        $stmt = $this->database->prepare($sql);
        $dateDebut = $sejour->getDateDebut()->format('Y-m-d');
        $dateFin = $sejour->getDateFin()->format("Y-m-d");
        $idSejour = $sejour->getId();
        $stmt->bind_param("ssi", $dateDebut, $dateFin, $idSejour);
        $stmt->execute();
    }
    public function getSejourById($id)
    {
        $sql = "SELECT * FROM sejours WHERE id = ?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc())
        {
            $sejour = new Sejour($row['id'], $row['dateDebut'], $row['dateFin'], $row['idReservation']);
            return $sejour;
        }
    }
}