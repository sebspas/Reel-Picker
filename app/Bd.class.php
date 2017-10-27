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
                self::$db->exec('SET CHARACTER SET utf16');
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

    function selectAmis($iduser) {
        $req = self::$db->prepare("SELECT A1.iduser2 FROM amis A1
                            WHERE A1.iduser1 = ? AND A1.iduser1 IN ( SELECT A2.iduser2
                                                                     FROM amis A2
                                                                     WHERE A2.iduser2 = ? ) ");
        $req->execute(array($iduser,$iduser));

        $donnees = $req->fetchAll(PDO::FETCH_OBJ);

        $req->closeCursor();

        return $donnees;
    }
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


    function addSection($Nom,$couleur,$image,$desc) {
        $req = self::$db->prepare("INSERT INTO `section` 
            (nom, couleur, image, description)
            VALUES (?,?,?,?)");
        $req->execute(array($Nom,$couleur,$image,$desc));

        $req->closeCursor();
    }

    function addNews($Nom,$image,$jeux,$desc) {
        $req = self::$db->prepare("INSERT INTO `news` 
            (titre,`text`, image, `date`, idauteur, idjeux)
            VALUES (?,?,?,?,?,?)");
        $req->execute(array($Nom,$desc,$image,date('y/m/d H:i:s'),$_SESSION['iduser'],$jeux));

        $req->closeCursor();
    }

    function addEvent($nom,$type,$date,$localisation,$image,$jeux,$desc) {
        $req = self::$db->prepare("INSERT INTO `event` 
            (nom,type,`desc`,image,localisation,`date`,idorganisateur,idjeux)
            VALUES (?,?,?,?,?,?,?,?)");
        $req->execute(array($nom,$type,$desc,$image,$localisation,$date,$_SESSION['iduser'],$jeux));

        $req->closeCursor();
    }

    function addJoueur($idjeux,$iduser) {
        $req = self::$db->prepare("INSERT INTO `joueur` 
            (idjeux, iduser)
            VALUES (?,?)");
        $req->execute(array($idjeux,$iduser));

        $req->closeCursor();
    }

    function addParticipant($idevent,$iduser) {
        $req = self::$db->prepare("INSERT INTO `participant` 
            (idevent, iduser)
            VALUES (?,?)");
        $req->execute(array($idevent,$iduser));

        $req->closeCursor();
    }

    function addAmis($iduser,$iduser2) {
        $req = self::$db->prepare("INSERT INTO `amis` 
            (iduser1, iduser2)
            VALUES (?,?)");
        $req->execute(array($iduser,$iduser2));

        $req->closeCursor();
    }

    function addCom($texte,$idnews) {
        $req = self::$db->prepare("INSERT INTO `commentaire` 
            (`date`, texte, iduser, idnews)
            VALUES (?,?,?,?)");
        $req->execute(array(date('y/m/d H:i:s'),$texte,$_SESSION['iduser'],$idnews));

        $req->closeCursor();
    }
    function addCategorie($nom) {
        $req = self::$db->prepare("INSERT INTO `categorie` 
            (nom)
            VALUES (?)");
        $req->execute(array($nom));

        $req->closeCursor();
    }

    function addForum($nom, $desc, $idcategorie = null, $idpere = null) {
        $req = self::$db->prepare("INSERT INTO `forum` 
            (nom, description, idpere, idcategorie)
            VALUES (?,?,?,?)");
        $req->execute(array($nom, $desc, $idpere, $idcategorie));

        $req->closeCursor();
    }
    
    function addRep($message, $idtopic) {
        $req = self::$db->prepare("INSERT INTO `message` 
            (`message`, `timestamp`, `iduser`, `idtopic`)
            VALUES (?,?,?,?)");
        $req->execute(array($message, time(), $_SESSION['iduser'], $idtopic));

        $req->closeCursor();
    }

    function addTopic($nom, $texte, $idforum) {
        $req = self::$db->prepare("INSERT INTO `topic` 
            (`nom`, `message`, `timestamp`, `iduser`, `forum_idForum`)
            VALUES (?,?,?,?,?)");
        $req->execute(array($nom, $texte, time(), $_SESSION['iduser'], $idforum));

        $req->closeCursor();
    }

    function addMessage($nom, $file) {
        $req = self::$db->prepare("INSERT INTO `messagerie` 
            (`link`, `idauteur`, `iddestinataire`, `timestamp`)
            VALUES (?,?,?,?)");
        $req->execute(array($file, $_SESSION['iduser'], $nom, time()));

        $req->closeCursor();
    }

    function addMessageInTchat($pseudo,$message) {
        $req = self::$db->prepare("INSERT INTO `tchat` 
            (`auteur`, `message`, `timestamp`)
            VALUES (?,?,?)");
        $req->execute(array($pseudo,$message,time()));

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

    function checkMsgAuteur() {
        $req = self::$db->prepare("SELECT COUNT(*) as nbr FROM messagerie m1, messagerie m2 
                                    WHERE m1.idauteur = ? 
                                    AND m2.luAuteur = 1 
                                    AND m2.idmessage = m1.idmessage");
        $req->execute(array($_SESSION['iduser']));
        $donnees = $req->fetch(PDO::FETCH_OBJ);

        $req->closeCursor();

        return $donnees->nbr;
    }

    function checkMsgDestinataire() {
        $req = self::$db->prepare("SELECT COUNT(*) as nbr FROM messagerie m1, messagerie m2 
                                    WHERE m1.iddestinataire = ? 
                                    AND m2.luDestinataire = 1 
                                    AND m2.idmessage = m1.idmessage");
        $req->execute(array($_SESSION['iduser']));
        $donnees = $req->fetch(PDO::FETCH_OBJ);

        $req->closeCursor();

        return $donnees->nbr;
    }

    function recupTchat($lastid) {
        $req = self::$db->prepare("SELECT * FROM tchat WHERE id > ? ORDER BY `timestamp` ASC");
        
        $req->execute(array($lastid));
        $donnees = $req->fetchAll(PDO::FETCH_OBJ);

        $req->closeCursor();

        return $donnees;
    }
} // class bd()

?>