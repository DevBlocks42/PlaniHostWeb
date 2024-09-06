<?php
class Room implements JsonSerializable
{
    private $id;
    private $prix;
    private $capacite;
    private $numero;
    private $etage;
    private $titre;
    private $description;
    private $stripeProductId;
    private $images;

    function __construct($id, $prix, $capacite, $numero, $etage, $titre, $description, $stripeProductId, $images)
    {
        $this->id = $id;
        $this->prix = $prix;
        $this->capacite = $capacite;
        $this->numero = $numero;
        $this->etage = $etage;
        $this->titre = $titre;
        $this->description = $description;
        $this->stripeProductId = $stripeProductId;
        $this->images = $images;
    }
    function __tostring()
    {
        return "ID : ". $this->id . " Prix : ". $this->prix . " Capacité : " . $this->capacite . " Numéro : " . $this->numero . " Étage : " . $this->etage . " Titre : " . $this->titre;
    }
    public function getNumero()
    {
        return $this->numero;
    }
    public function getEtage()
    {
        return $this->etage;
    }
    public function getPrix()
    {
        return $this->prix;
    }
    public function getTitre()
    {
        return $this->titre;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getCapacite()
    {
        return $this->capacite; 
    }
    public function getImages()
    {
        return $this->images;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getStripeProductId()
    {
        return $this->stripeProductId;
    }
    public function jsonSerialize() : mixed
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}