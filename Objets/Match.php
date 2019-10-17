<?php
class Match {
    // constructor
    public function __construct($id, $equipe1, $equipe2) {
        $this->id = $id;
        $this->equipe1 = $equipe1;
        $this->equipe2 = $equipe2;
    }

    public function getId(){
        return $this->id;
    }
    public function getEquipe1(){
        return $this->equipe1;
    }
    public function getEquipe2(){
        return $this->equipe2;
    }
}
?>