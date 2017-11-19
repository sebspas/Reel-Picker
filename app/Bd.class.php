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


    private function selectImpl($query, $values = NULL)
    {
        $req = self::$db->prepare($query);
        $req->execute($values);
        $donnees = $req->fetchAll(PDO::FETCH_OBJ);
        $req->closeCursor();
        return $donnees;
    }

    private function selectOneImpl($query, $values = NULL)
    {
        $req = self::$db->prepare($query);
        $req->execute($values);
        $donnees = $req->fetch(PDO::FETCH_OBJ);
        $req->closeCursor();
        return $donnees;
    }

    /**
     * Function selectAttribut()
     *
     * Effectue une simple requete sur la table et recupere l'attribut choisi du tuple concerné
     * @param string $att_select Le nom de l'attribut selectionné (ex: NOM)
     * @param string $cond_att Le nom de la colonne (ex: NOM)
     * @param mixed $cond_val La valeur de la colonne rechercher (ex: Jean)
     */
    function selectAttribut($att_select, $cond_att, $cond_val) {
        return $this->selectImpl(
            "SELECT ? FROM $this->table WHERE $cond_att = ?",
            array($att_select, $cond_val)
        );
    } // select()

    function selectAllWithInfo($cond_att, $cond_val, $cond_att_t, $cond_val_t, $contenu_link) {
        return $this->selectImpl(
            "SELECT * FROM $this->table WHERE $cond_att = ? OR $cond_att_t = ? ORDER BY $contenu_link DESC",
            array($cond_val, $cond_val_t)
        );
    } // select()

    /**
     * Function selectAll()
     *
     * Recupere tous les tuples de la table sur laquel on effectue les operations, les renvoie dans 
     * un tableau ou chaque case et un tuple
     */
    function selectAll($orderatt) {
        if (isset($orderatt)) {
            $query = "SELECT * FROM $this->table ORDER BY $orderatt DESC"; 
        } else {
            $query = "SELECT * FROM $this->table";
        }
        return $this->selectImpl($query);
    } // selectAll()

    function selectTop($orderatt, $quantity) {
        if (isset($orderatt)) {
            $query = "SELECT * FROM $this->table ORDER BY $orderatt DESC LIMIT $quantity"; 
        } else {
            $query = "SELECT * FROM $this->table";
        }
        return $this->selectImpl($query);
    } // selectTop()

    /**
     * Function selectMult()
     *
     * Recupere tout les tuples de la table sur laquel on effectue les operations,les renvoie dans 
     * un tableau ou chaque case et un tuple
     */
    function selectMult($cond_att, $cond_val) {
        return $this->selectImpl("SELECT * FROM $this->table WHERE $cond_att = ?", array($cond_val));
    } // selectMult()

    /**
     * Function selectTwoParam()
     *
     * Recupere tout les tuples de la table sur laquel on effectue les operations,les renvoie dans 
     * un tableau ou chaque case et un tuple depuis la table trajet correspondant aux parametres
     */
    function selectTwoParam($cond_att,$cond_val,$cond_att2,$cond_val2,$orderatt) {
        if (isset($orderatt)) {
            $query = "SELECT * FROM $this->table WHERE $cond_att = ? AND $cond_att2 = ? ORDER BY $orderatt ASC";
        } else {
            $query = "SELECT * FROM $this->table WHERE $cond_att = ? AND $cond_att2 = ?";
        }
        return $this->selectImpl($query, array($cond_val,$cond_val2));
    } // selectTwoParam()


    function selectPreferredTags($iduser, $count = 10) {
        return $this->selectImpl(
            "SELECT t.name, ut.rating FROM tag t JOIN user_tags ut ON t.id = ut.tag_id WHERE t.id IN (SELECT ut.tag_id FROM user_tags WHERE ut.user_id = ?) ORDER BY ut.rating DESC LIMIT $count",
            array($iduser)
        );
    }

    function selectMostPopularTag($iduser) {
        return $this->selectOneImpl(
            "SELECT t.name, ut.rating FROM tag t JOIN user_tags ut ON t.id = ut.tag_id WHERE t.id IN (SELECT ut.tag_id FROM user_tags WHERE ut.user_id = ?) ORDER BY ut.tag_count DESC",
            array($iduser)
        );
    }

    function selectEmergingTag($iduser) {
        return $this->selectOneImpl(
            "SELECT t.name, ut.rating FROM tag t JOIN user_tags ut ON t.id = ut.tag_id WHERE t.id IN (SELECT ut.tag_id FROM user_tags WHERE ut.user_id = ?) ORDER BY ut.tag_count ASC",
            array($iduser)
        );
    }

    function selectRandomUnratedTag($iduser) {
        return $this->selectOneImpl(
            "SELECT t.name FROM tag t WHERE t.id NOT IN (SELECT ut.tag_id FROM user_tags ut WHERE ut.user_id = ?) ORDER BY RAND()",
            array($iduser)
        );
    }

    function selectRecentRatedMovies($iduser) {
        // TODO: order by timestamp instead of rating
        return $this->selectImpl(
            "SELECT m.image FROM movie m JOIN user_movies um ON m.id = um.movie_id WHERE m.id IN (SELECT um.movie_id FROM user_movies WHERE um.user_id = ?) ORDER BY um.rating DESC",
            array($iduser)
        );
    }


    function searchMovies($searchTerms) {
        $query = "SELECT title, idmovie, img FROM $this->table WHERE %s";
        $conditions = array();

        for($i =0; $i < sizeof($searchTerms); $i++){
            array_push($conditions, "title LIKE ?");
            $searchTerms[$i] = "%".$searchTerms[$i]."%";
        }

        $query = sprintf($query, join($conditions, " OR "));
        $req = self::$db->prepare($query);
        $req->execute($searchTerms);
        $results = $req->fetchAll(PDO::FETCH_OBJ);
        $req->closeCursor();

        return $results;
    }

    /**
     * Function addUser()
     *
     * Ajoute un utilisateur dans la base de donnees a l'aide des infos fournis
     * @param string $Nom Le nom de l'utilisateur
     * @param string $Mail L'adresse mail de l'utilisateur
     * @param string $Pass Le mot de passe non hasher
     */
    function addUser($Pseudo,$Pass,$Mail) {
        $req = self::$db->prepare("INSERT INTO `user`
            (pseudo, password, email)
             VALUES (?,?,?)");
        $Pass = sha1($Pass);
        $req->execute(array($Pseudo,$Pass,$Mail));
        $req->closeCursor();
    } // addUser()

    function addMovie($name, $rating, $desc, $image, $date, $runtime) {
        $req = self::$db->prepare("INSERT INTO `movie`
        (name, rating, `desc`, image, `date`, runtime)
         VALUES (?,?,?,?,?,?)");
        $req->execute(array($name, $rating, $desc, $image, $date, $runtime));
        $req->closeCursor();
    }

    function addTag($name) {
        $req = self::$db->prepare("INSERT INTO `tag`
        (name)
         VALUES (?)");
       
        $req->execute(array($name));
        $req->closeCursor();
    }

    function addUserMovieRating($idUser, $idMovie, $rating) {
        $req = self::$db->prepare("INSERT INTO `user_movies`
        (user_id, movie_id, rating)
         VALUES (?, ?, ?)");
       
        $req->execute(array($idUser, $idMovie, $rating));
        $req->closeCursor();
    }

    function addUserTag($idUser, $idTag, $rating) {
        $req = self::$db->prepare("INSERT INTO `user_tags`
        (user_id, tag_id, rating, tag_count)
         VALUES (?, ?, ?, ?)");
       
        $req->execute(array($idUser, $idTag, $rating, 1));
        $req->closeCursor();
    }

    function addMovieTag($idMovie, $idTag) {
        $req = self::$db->prepare("INSERT INTO `movie_tags`
        (movie_id, tag_id)
         VALUES (?, ?)");
       
        $req->execute(array($idMovie, $idTag));
        $req->closeCursor();
    }

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

    function updateTwoParam($att,$att_val,$cond_att,$cond_val, $cond_att2, $cond_val2) {
        
        $req = self::$db->prepare("UPDATE $this->table SET $att = ? WHERE $cond_att = ? AND $cond_att2 = ?");

        $req->execute(array($att_val,$cond_val, $cond_val2));

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