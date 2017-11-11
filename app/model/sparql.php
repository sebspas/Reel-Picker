<?php
// to use sparql
use BorderCloud\SPARQL\SparqlClient;

function getMovieDataWithName($name) {

    // convert the string into lower case to the sparql
    $name = strtolower($name);

    // set the database to ask question 
    //$endpoint = "http://data.linkedmdb.org/sparql";
    $endpoint = "http://dbpedia.org/sparql";
    $sc = new SparqlClient();
    $sc->setEndpointRead($endpoint);

    // the query itself
    /*$q = "
        prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#>
        prefix fn: <https://www.w3.org/TR/xpath-functions-3/>
        
        SELECT ?movieLabel ?date ?runtime ?actorLabel WHERE {
          ?movie rdfs:label ?movieLabel.
          ?actors rdfs:label ?actorLabel.
          ?movie <http://data.linkedmdb.org/resource/movie/actor> ?actors.
          ?movie <http://data.linkedmdb.org/resource/movie/runtime> ?runtime.
          ?movie <http://purl.org/dc/terms/date> ?date.
          ?movie  <http://purl.org/dc/terms/title> \"" . $name . "\".
        }";*/
    $q = "        
        SELECT DISTINCT ?movies ?film_title ?date
        WHERE {
        ?movies rdf:type <http://dbpedia.org/ontology/Film>.
        ?movies rdfs:label ?film_title.
        FILTER (CONTAINS (lcase(str(?film_title)), \"". $name ."\") || ?film_title = \"". $name ."\").
        FILTER langMatches(lang(?film_title),'en').        
        } group by ?film_title
        LIMIT 30
    ";
    
    // execute the query on the ontology
    $rows = $sc->query($q, 'rows');
    $err = $sc->getErrors();
    if ($err) {
        // show the error if there is some
        print_r($err);
        throw new Exception(print_r($err, true));
    }

    // return the results
    return $rows;
}

function getMovieImage($moviesDataSPARQL) {
    foreach ($moviesDataSPARQL as $dataSPARQL) {
        $imdb = new IMDB($dataSPARQL['film_title']);
        $movie = array();
        if($imdb->isReady){
            $movie['title'] = $dataSPARQL['film_title'];
            $movie['image'] = $imdb->getPoster();
            $movie['rating'] = $imdb->getRating();
            $movie['year'] = $imdb->getYear();

            $moviesData[] = $movie;
        } 
    }

   
    // sort by year
    foreach ($moviesData as $key => $row) {
        $year[$key]  = $row['year'];
    }
    array_multisort($year, SORT_DESC, $moviesData);
    return $moviesData;
}
?>