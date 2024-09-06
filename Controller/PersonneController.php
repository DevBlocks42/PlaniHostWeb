<?php 

class PersonneController extends Controller
{
    public function addPerson($nom, $prenom, $dateNaissance, $nationalite, $idChambre, $idSejour)
    {
        return $this->access->addPerson($nom, $prenom, $dateNaissance, $nationalite, $idChambre, $idSejour);
    }
    public function getPersonneBySejour($sejour)
    {
        return $this->access->getPersonneBySejour($sejour);
    }
    public function getPersonnesBySejour($sejour)
    {
        return $this->access->getPersonnesBySejour($sejour);
    }
    public function updateChambrePersonne($personne)
    {
        return $this->access->updateChambrePersonne($personne);
    }
    public function updatePersonne($personne)
    {
        return $this->access->updatePersonne($personne);
    }
}