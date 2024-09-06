<?php 

class client 
{
    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $tel;
    private $stripeClientId;
    public function __construct($id, $nom, $prenom, $email, $tel, $stripeClientId)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->tel = $tel;
        $this->stripeClientId = $stripeClientId;
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
    public function getEmail()
    {
        return $this->email;
    }
    public function getTel()
    {
        return $this->tel;
    }
    public function setNom($nom)
    {
        $this->nom = $nom;
    }
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setTel($tel)
    {
        $this->tel = $tel;
    }
    public function getStripeClientId()
    {
        return $this->stripeClientId;
    }
}