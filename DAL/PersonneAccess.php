<?php 

class PersonneAccess extends Access
{
    public function addPerson($nom, $prenom, $dateNaissance, $nationalite, $idChambre, $idSejour)
    {
        $sql = "INSERT INTO personnes (nom, prenom, dateNaissance, nationalite, idChambre, idSejour) VALUES(?, ?, ?, ?, ?, ?);";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("ssssii", $nom, $prenom, $dateNaissance, $nationalite, $idChambre, $idSejour);
        $stmt->execute();
    }
    public function getPersonneBySejour($sejour)
    {
        $sql = "SELECT * FROM personnes WHERE idSejour = ?;";
        $stmt = $this->database->prepare($sql);
        $idSejour = $sejour->getId();
        $stmt->bind_param("i", $idSejour);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc())
        {
            $sql2 = "SELECT * FROM chambres WHERE id=?;";
            $stmt2 = $this->database->prepare($sql2);
            $stmt2->bind_param("i", $row['idChambre']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            while($row2 = $result2->fetch_assoc())
            {
                $chambre = new Room($row2['id'], $row2['prix'], $row2['capacite'], $row2['numero'], $row2['etage'], $row2['titre'], $row2['description'], $row2['stripe_prod_id'], $row2['images']);
                $personne = new Personne($row['id'], $row['nom'], $row['prenom'], $row['dateNaissance'], $row['nationalite'], $chambre, $sejour);
                return $personne;
            }
        }
        return null;
    }
    public function getPersonnesBySejour($sejour)
    {
        $sql = "SELECT * FROM personnes WHERE idSejour = ?;";
        $stmt = $this->database->prepare($sql);
        $idSejour = $sejour->getId();
        $stmt->bind_param("i", $idSejour);
        $stmt->execute();
        $result = $stmt->get_result();
        $personnes = [];
        while($row = $result->fetch_assoc())
        {
            $sql2 = "SELECT * FROM chambres WHERE id=?;";
            $stmt2 = $this->database->prepare($sql2);
            $stmt2->bind_param("i", $row['idChambre']);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            while($row2 = $result2->fetch_assoc())
            {
                $chambre = new Room($row2['id'], $row2['prix'], $row2['capacite'], $row2['numero'], $row2['etage'], $row2['titre'], $row2['description'], $row2['stripe_prod_id'], $row2['images']);
                $personne = new Personne($row['id'], $row['nom'], $row['prenom'], $row['dateNaissance'], $row['nationalite'], $chambre, $sejour);
                array_push($personnes, $personne);
            }
        }
        return $personnes;
    }
    public function updateChambrePersonne($personne)
    {
        $sql = "UPDATE personnes SET idChambre = ? WHERE id = ?";
        $stmt = $this->database->prepare($sql);
        $idChambre = $personne->getChambre()->getId();
        $idPersonne = $personne->getId();
        $stmt->bind_param("ii", $idChambre, $idPersonne);
        $stmt->execute();
    }
    public function updatePersonne($personne)
    {
        $sql = "UPDATE personnes SET prenom = ?, nom = ? WHERE id = ?;";
        $stmt = $this->database->prepare($sql);
        $nom = $personne->getNom();
        $prenom = $personne->getPrenom();
        $id = $personne->getId();
        $stmt->bind_param("ssi", $prenom, $nom, $id);
        $stmt->execute();
    }
}