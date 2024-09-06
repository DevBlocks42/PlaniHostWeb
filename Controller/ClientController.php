<?php

class ClientController extends Controller 
{
    public function addClient($nom, $prenom, $email, $tel)
    {
        return $this->access->addClient($nom, $prenom, $email, $tel);
    }
    public function getClient($id)
    {
        return $this->access->getClient($id);
    }
    public function updateClient($client)
    {
        return $this->access->updateClient($client);
    }
    public function getClientsOptimized($limit, $offset)
    {
        return $this->access->getClientsOptimized($limit, $offset);
    }
    public function deleteClient($id)
    {
        return $this->access->deleteClient($id);
    }
    public function setStripeClientId($clientId, $stripeClientId)
    {
        return $this->access->setStripeClientId($clientId, $stripeClientId);
    }
}