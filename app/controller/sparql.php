<?php
    // include the model file
    //require_once(Config::$path['model'].'sparql.php');

    /*$rows = getMovieDataWithName("Sherlock");

    $moviesData = getMovieImage($rows["result"]["rows"]);
    
    //print_r($moviesData);
    echo $twig->render('sparql.twig', array(
        'name' => $_SESSION['pseudo'],
        'connected' => $_SESSION['login'],
        'page' => $_GET['page'],
        'movie_col' => $rows["result"]["variables"],
        'movie_data' => $rows["result"]["rows"],
        'movie_dataIMDB' => $moviesData
        ));*/     

    function makeRegexFromString($string) {
        $tmp = explode(" ", $string);

        $ret = "";

        foreach ($tmp as $s) {
            $ret = "(" . $s[0] . "|" . strtoupper($s[0]) . ")" . substr($s, 1) . " ";
        }

        return $ret;
    }

    $config = array(
        /* remote endpoint */
        'remote_store_endpoint' => 'http://dbpedia.org/sparql'
    );
        
    /* instantiation */
    $dbpedia = ARC2::getRemoteStore($config);

    $name = "sherlock";
    $name = makeRegexFromString($name);

    //print_r($dbpedia);
    $q = "
        SELECT ?title
        WHERE {
            ?movie rdf:type <http://dbpedia.org/ontology/Film>.
            ?movie rdfs:label ?title.
            FILTER REGEX(?title, '" . $name . "').
            FILTER langMatches(lang(?title),'en').                
        } group by ?title
        LIMIT 200
    ";
    
    print($q);
    $rows = $dbpedia->query($q, 'rows');
    print_r($rows);           
?>