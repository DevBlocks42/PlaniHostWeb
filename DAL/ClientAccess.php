<?php

class ClientAccess extends Access 
{
    public function addClient($nom, $prenom, $email, $tel)
    {
        $sql = "INSERT INTO clients (nom, prenom, email, tel) VALUES(?, ?, ?, ?);";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("ssss", $nom, $prenom, $email, $tel);
        $stmt->execute();
        return $this->database->insert_id;   
    }
    public function getClient($id)
    {
        $sql = "SELECT * FROM clients WHERE id = ?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = $result->fetch_assoc())
        {
            $client = new Client($row['id'], $row['nom'], $row['prenom'], $row['email'], $row['tel'], $row['stripe_client_id']);
            return $client;
        }
        return null;
    }
    public function updateClient($client)
    {
        $sql = "UPDATE clients SET nom = ?, prenom = ?, email = ?, tel = ? WHERE id = ?;";
        $stmt = $this->database->prepare($sql);
        $nom = $client->getNom();
        $prenom = $client->getPrenom();
        $email = $client->getEmail();
        $tel = $client->getTel();
        $id = $client->getId();
        $stmt->bind_param("ssssi", $nom, $prenom, $email, $tel, $id);
        $stmt->execute();
    }
    public function getClientsOptimized($limit, $offset)
    {
        $sql = "SELECT * FROM clients LIMIT ? OFFSET ?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $clients = [];
        while($row = $result->fetch_assoc())
        {
            $client = new Client($row['id'], $row['nom'], $row['prenom'], $row['email'], $row['tel'], $row['stripe_client_id']);
            array_push($clients, $client);
        }
        return $clients;
    }
    public function deleteClient($id)
    {
        $sql = "DELETE FROM clients WHERE id = ?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    public function setStripeClientId($clientId, $stripeClientId)
    {
        $sql = "UPDATE clients SET stripe_client_id=? WHERE id=?;";
        $stmt = $this->database->prepare($sql);
        $stmt->bind_param("si", $stripeClientId, $clientId);
        $stmt->execute();
    }
}