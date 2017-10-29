<?php
/**
 * Class bd()
 *
 * Classe permetant de se connecter et d'effectuer des opérations sur la base de donnée
 * @package Core
 * @subpackage Class
 * @author Team
 */
class BD {
    /** @var PDO Connexion a la base de donner */
    private static $db = null;
    /** @var string Nom de la table a laquelle est connecter la classe bd */
    var $table;
    
    /**
     * Function __construct()
     *
     * Constructeur par défaut de la class bd
     * @param string $table Le nom de la table a laquel se connecter.
     */
    public function __construct($table){
        if (self::$db == null) {
            try {
                self::$db = new PDO(Config::$dbInfo['driver'], Config::$dbInfo['username'], Config::$dbInfo['password']);
                self::$db->exec('SET CHARACTER SET utf8');
                if(Config::$debug) 
                    self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
            } catch(Exception $e) {
                exit('Errror to DB connection : ' . $e->getMessage());
            }
        }
        $this->table = $table;
    }

    /**
     * Function getUsedTable()
     *
     * Permet de connaitre la table sur laquel on travail
     */
    function getUsedTable() {
        return $this->table;
    } // getusedTable()

    /**
     * Function setUsedTable()
     *
     * Permet de modifier la table sur laquel on effectue les opérations
     * @param string $table Le nom de la nouvelle table a laquel se connecter
     */
    function setUsedTable($table) {
        $this->table = $table;
    }// setUsedTable()


    /**
     * Function select()
     *
     * Effectue une simple requete sur la table et recupere le tuple concerner, pour acceder
     * a un element de resultat il suffit de faire $result->ID_U par exemple
     * @param string $cond_att Le nom de la colonne (ex: NOM)
     * @param mixed $cond_val La valeur de la colonne rechercher (ex: Jean)
     */
    function select($cond_att,$cond_val) {

        $req = self::$db->prepare("SELECT * FROM $this->table WHERE $cond_att = ?");

        $req->execute(array($cond_val));
        $donnees = $req->fetch(PDO::FETCH_OBJ);

        $req->closeCursor();
        
        return $donnees;
    } // select()

    function selectNumber($orderatt, $start, $number,$order = 'DESC') {
    	$stop = $number;
        $req = self::$db->prepare("SELECT * FROM $this->table ORDER BY $orderatt $order LIMIT $start,$stop");

        $req->execute();
        $donnees = $req->fetchAll(PDO::FETCH_OBJ);

        $req->closeCursor();
        
        return $donnees;
    } // selectNumber()

    function selectNumberWhere($orderatt, $start, $number, $cond_att, $cond_val, $order = 'DESC') {
    	$stop = $number;
        $req = self::$db->prepare("SELECT * FROM $this->table WHERE $cond_att = ? ORDER BY $orderatt $order LIMIT $start,$stop");

        $req->execute(array($cond_val));
        $donnees = $req->fetchAll(PDO::FETCH_OBJ);

        $req->closeCursor();
        
        return $donnees;
    } // selectNumberWhere()

    /**
     * Function count()
     *
     * Effectue une simple requete sur la table, 
     * elle renvoie le nbr d'element
     * @param string $cond_att Le nom de la colonne (ex: NOM)
     * @param mixed $cond_val La valeur de la colonne rechercher (ex: Jean)
     */
    function count($cond_att,$cond_val) {

        $req = self::$db->prepare("SELECT COUNT(*) as nb FROM $this->table WHERE $cond_att = ?");

        $req->execute(array($cond_val));
        $donnees = $req->fetch(PDO::FETCH_OBJ);

        $req->closeCursor();

        return $donnees->nb;
    } // count()

    function countAll() {

        $req = self::$db->prepare("SELECT COUNT(*) as nb FROM $this->table");

        $req->execute();
        $donnees = $req->fetch(PDO::FETCH_OBJ);

        $req->closeCursor();

        return $donnees->nb;
    } // count()

    /**
     * Function selectAttribut()
     *
     * Effectue une simple requete sur la table et recupere l'attribut choisi du tuple concerné
     * @param string $att_select Le nom de l'attribut selectionné (ex: NOM)
     * @param string $cond_att Le nom de la colonne (ex: NOM)
     * @param mixed $cond_val La valeur de la colonne rechercher (ex: Jean)
     */
    function selectAttribut($att_select, $cond_att, $cond_val) {

        $req = self::$db->prepare("SELECT ? FROM $this->table WHERE $cond_att = ?");

        $req->execute(array($att_select, $cond_val));
        $donnees = $req->fetch(PDO::FETCH_OBJ);

        $req->closeCursor();

        return $donnees;
    } // select()

    function selectAllWithInfo($cond_att, $cond_val, $cond_att_t, $cond_val_t, $contenu_link) {

        $req = self::$db->prepare("SELECT * FROM $this->table WHERE $cond_att = ? OR $cond_att_t = ? ORDER BY $contenu_link DESC");

        $req->execute(array($cond_val, $cond_val_t));
        $donnees = $req->fetchAll(PDO::FETCH_OBJ);
        $req->closeCursor();

        return $donnees;
    } // select()

    /**
     * Function selectAll()
     *
     * Recupere tout les tuples de la table sur laquel on effectue les operations,les renvoie dans 
     * un tableau ou chaque case et un tuple
     */
    function selectAll($orderatt) {
        if (isset($orderatt)) {
            $req = self::$db->prepare("SELECT * FROM $this->table ORDER BY $orderatt DESC"); 
            $req->execute();

            $donnees = $req->fetchAll(PDO::FETCH_OBJ);

            $req->closeCursor();

            return $donnees;
        }

        $req = self::$db->prepare("SELECT * FROM $this->table");

        $req->execute();

        $donnees = $req->fetchAll(PDO::FETCH_OBJ);

        $req->closeCursor();

        return $donnees;
    } // selectAll()

    function selectAllAsc($orderatt) {
        if (isset($orderatt)) {
            $req = self::$db->prepare("SELECT * FROM $this->table ORDER BY $orderatt ASC"); 
            $req->execute();

            $donnees = $req->fetchAll(PDO::FETCH_OBJ);

            $req->closeCursor();

            return $donnees;
        }

        $req = self::$db->prepare("SELECT * FROM $this->table");

        $req->execute();

        $donnees = $req->fetchAll(PDO::FETCH_OBJ);

        $req->closeCursor();

        return $donnees;
    } // selectAll()

    /**
     * Function selectMult()
     *
     * Recupere tout les tuples de la table sur laquel on effectue les operations,les renvoie dans 
     * un tableau ou chaque case et un tuple
     */
    function selectMult($cond_att,$cond_val) {

        $req = self::$db->prepare("SELECT * FROM $this->table WHERE $cond_att = ?");

        $req->execute(array($cond_val));

        $donnees = $req->fetchAll(PDO::FETCH_OBJ);

        $req->closeCursor();

        return $donnees;
    } // selectMult()

    /**
     * Function selectTwoParam()
     *
     * Recupere tout les tuples de la table sur laquel on effectue les operations,les renvoie dans 
     * un tableau ou chaque case et un tuple depuis la table trajet correspondant aux parametres
     */
    function selectTwoParam($cond_att,$cond_val,$cond_att2,$cond_val2,$orderatt) {
        if (isset($orderatt)) {

            $req = self::$db->prepare("SELECT * FROM $this->table WHERE $cond_att = ? AND $cond_att2 = ? ORDER BY $orderatt ASC");

            $req->execute(array($cond_val,$cond_val2));

            $donnees = $req->fetchAll(PDO::FETCH_OBJ);

            $req->closeCursor();

            return $donnees;
        }

        $req = self::$db->prepare("SELECT * FROM $this->table WHERE $cond_att = ? AND $cond_att2 = ?");

        $req->execute(array($cond_val,$cond_val2));

        $donnees = $req->fetchAll(PDO::FETCH_OBJ);

        $req->closeCursor();

        return $donnees;
    } // selectTwoParam()


    function selectMovieTag($idmovie) {
        
        $req = self::$db->prepare("SELECT * FROM `tag` WHERE `idtag` IN (SELECT `tag_idtag` FROM `movie_has_tag` WHERE `movie_idmovie` = ?) ORDER BY 'name' ASC");
        
        $req->execute(array($idmovie));

        $donnees = $req->fetchAll(PDO::FETCH_OBJ);

        $req->closeCursor();

        return $donnees;
    }

    /**
     * Function addUser()
     *
     * Ajoute un utilisateur dans la base de donnees a l'aide des infos fournis
     * @param string $Nom Le nom de l'utilisateur
     * @param string $Nom Le prenom de l'utilisateur
     * @param int $Age L'age de l'utilisateur
     * @param int $Tel Le tel de l'utilisateur
     * @param string $Mail L'adresse mail de l'utilisateur
     * @param string $Pass Le mot de passe non hasher
     */
    function addUser($Pseudo,$Nom,$Prenom,$Sexe,$Mail,$Pass) {
        $req = self::$db->prepare("INSERT INTO `user`
            (pseudo, nom, prenom, sexe, mail, pass)
             VALUES (?,?,?,?,?,?)");
        $Pass = sha1($Pass);
        $req->execute(array($Pseudo,$Nom,$Prenom,$Sexe,$Mail,$Pass));
        $req->closeCursor();
    } // addUser()

    /**
     * Function update()
     *
     * Met a jour la valeur de l'attribut passer en parametre, pour le tuple respectant la condition
     * elle aussi donnee en parametre
     * @param string $att Le nom de l'attribut a modifier (ex: "Age")
     * @param mixed $att_val La valeur de l'attribut a modifier' (ex: 19)
     * @param string $cond_att Le nom de la colonne (ex: "NOM")
     * @param mixed $cond_val La valeur de la colonne rechercher (ex: "Jean")
     */
    function update($att,$att_val,$cond_att,$cond_val) {
        
        $req = self::$db->prepare("UPDATE $this->table SET $att = ? WHERE $cond_att = ?");

        $req->execute(array($att_val,$cond_val));

        $req->closeCursor();
    } // update()

    /**
     * Function inc()
     *
     * Met a jour la valeur de l'attribut passer en parametre, pour le tuple respectant la condition
     * elle aussi donnee en parametre
     * @param string $att Le nom de l'attribut a modifier (ex: "Age")
     * @param string $cond_att Le nom de la colonne (ex: "NOM")
     * @param mixed $cond_val La valeur de la colonne rechercher (ex: "Jean")
     */
    function inc($att,$cond_att,$cond_val) {
        
        $req = self::$db->prepare("UPDATE $this->table SET $att = $att + 1 WHERE $cond_att = ?");

        $req->execute(array($cond_val));

        $req->closeCursor();
    } // inc()

    /**
     * Function decr()
     *
     * Met a jour la valeur de l'attribut passer en parametre, pour le tuple respectant la condition
     * elle aussi donnee en parametre
     * @param string $att Le nom de l'attribut a modifier (ex: "Age")
     * @param string $cond_att Le nom de la colonne (ex: "NOM")
     * @param mixed $cond_val La valeur de la colonne rechercher (ex: "Jean")
     */
    function decr($att,$cond_att,$cond_val) {
        
        $req = self::$db->prepare("UPDATE $this->table SET $att = $att - 1 WHERE $cond_att = ?");

        $req->execute(array($cond_val));

        $req->closeCursor();
    } // decr()

    /**
     * Function delete()
     *
     * Supprime le tuple pour la condition donnee
     * @param string $cond_att Le nom de la colonne (ex: "NOM")
     * @param mixed $cond_val La valeur de la colonne rechercher (ex: "Jean")
     */
    function delete($cond_att,$cond_val = null) {
    
        $req = self::$db->prepare("DELETE FROM $this->table WHERE $cond_att = ?");

        $req->execute(array($cond_val));

        $req->closeCursor();
    } // delete()

    function deleteTwoParam($cond_att,$cond_val = null,$cond_att2,$cond_val2 = null) {
        $req = self::$db->prepare("DELETE FROM $this->table WHERE $cond_att = ? AND $cond_att2 = ?");

        $req->execute(array($cond_val,$cond_val2));

        $req->closeCursor();
    }

    /**
     * Function isInBd()
     *
     * Renvoie vrai ou faux si le tuple est présent dans la base pour la condition donnée
     * @param string $cond_att Le nom de la colonne (ex: "NOM")
     * @param mixed $cond_val La valeur de la colonne rechercher (ex: "Jean")
     */
    function isInDb($cond_att,$cond_val)
    {
        $req = self::$db->prepare("SELECT COUNT(*) as nbr FROM $this->table WHERE $cond_att = ?");
        $req->execute(array($cond_val));
        $donnees = $req->fetch(PDO::FETCH_OBJ);
        if ($donnees->nbr)
        {
            $req->closeCursor();
            return true;
        }
        else
        {
            $req->closeCursor();
            return false;
        }
    } // isInDb()
} // class bd()

?>