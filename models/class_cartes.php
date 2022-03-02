<?php

class Cartes { // On peut appeler class également "model"
    //connection
    private $conn;

    //Getter = Obtenir la "valeur" qu'il y a dans l'attribut. cf: "{->idCarte;}" par exemple.
    //Setter = Définir la "valeur" de l'attribut cf: "idCarte = $new" par exemple : $new = la valeur que l'on va assigner.

    private $idCarte;

    public function get_idCarte(){return $this->idCarte;}
    public function set_idCarte($new){$this->idCarte = $new;}
    private $color;
    public function get_color(){return $this->color;}
    public function set_color($new){$this->color = $new;}
    private $nom;
    public function get_nom(){return $this->nom;}
    public function set_nom($new){$this->nom = $new;}
    private $codeExtension;
    public function get_codeExtension(){return $this->codeExtension;}
    public function set_codeExtension($new){$this->codeExtension = $new;}
    private $type;
    public function get_type(){return $this->type;}
    public function set_type($new){$this->type = $new;}
    private $rarete;
    public function get_rarete(){return $this->rarete;}
    public function set_rarete($new){$this->rarete = $new;}
    private $coutConvertiMana;
    public function get_coutConvertiMana(){return $this->coutConvertiMana;}
    public function set_coutConvertiMana($new){$this->coutConvertiMana = $new;}
    private $coutManaTexte;
    public function get_coutManaTexte(){return $this->coutManaTexte;}
    public function set_coutManaTexte($new){$this->coutManaTexte = $new;}
    private $forceCreature;
    public function get_forceCreature(){return $this->forceCreature;}
    public function set_forceCreature($new){$this->forceCreature = $new;}
    private $enduranceCreature;
    public function get_enduranceCreature(){return $this->enduranceCreature;}
    public function set_enduranceCreature($new){$this->enduranceCreature = $new;}
    private $text;
    public function get_text(){return $this->text;}
    public function set_text($new){$this->text = $new;}
    private $urlImage;
    public function get_urlImage(){return $this->urlImage;}
    public function set_urlImage($new){$this->urlImage = $new;}


    public function __construct(){ //création constructeur pour instancier la BDD à chaque fois que je crée un objet (Tout le model en cours)
        $db = new Database(); // Instation de l'objet Database.
        $this->conn=$db->getConnection(); // On va appeler la fonction pour obtenir la connexion. 
    }

    //methods
    public function read_color(){ //création de la fonction "read_color" pour obtenir toutes les cartes en fonction d'une certaine couleur.
        $req = $this->conn->prepare("SELECT * FROM cartes WHERE color = :color"); // ":" signifie que tu vas attribuer une valeur dans l'execute.
        $req->execute(array("color"=>$this->get_color())); //On attribut aux paramètres de la requête la valeur de "color"
        if($req->rowcount() <= 0)
            echo 'Aucune carte n\'a été détécté !';
        else{
            $data=$req->fetchAll();
        } //fetch = résultat de la requête des "cartes" une par une qui sont correspondantes.
        var_dump($data);
        echo $req->rowcount();
        //return $files; renvoie le tableau contenant "toutes" les cartes de la query.
                        // $cartes uniquement dans la fonction "read_color"
    }
}