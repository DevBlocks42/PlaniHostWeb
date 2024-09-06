<?php 

class Personne 
{
    private $id;
    private $nom;
    private $prenom;
    private $dateNaissance;
    private $nationalite;
    private $chambre;
    private $sejour;
    public function __construct($id, $nom, $prenom, $dateNaissance, $nationalite, $chambre, $sejour)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->dateNaissance = $dateNaissance;
        $this->nationalite = $nationalite;
        $this->chambre = $chambre;
        $this->sejour = $sejour;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getNom()
    {
        return $this->nom;
    }
    public function getPrenom()
    {
        return $this->prenom;
    }
    public function getChambre()
    {
        return $this->chambre;
    }
    public function setChambre($chambre)
    {
        $this->chambre = $chambre;
    }
    public function setNom($nom)
    {
        $this->nom = $nom;
    }
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }
}