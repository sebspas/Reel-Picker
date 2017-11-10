<?php
// to use sparql
use BorderCloud\SPARQL\SparqlClient;

function getMovieDataWithName($name) {
    // set the database to ask question 
    $endpoint = "http://data.linkedmdb.org/sparql";
    $sc = new SparqlClient();
    $sc->setEndpointRead($endpoint);

    // the query itself
    $q = "
        prefix rdfs: <http://www.w3.org/2000/01/rdf-schema#>
        prefix fn: <https://www.w3.org/TR/xpath-functions-3/>
        
        SELECT ?movieLabel ?date ?runtime ?actorLabel WHERE {
          ?movie rdfs:label ?movieLabel.
          ?actors rdfs:label ?actorLabel.
          ?movie <http://data.linkedmdb.org/resource/movie/actor> ?actors.
          ?movie <http://data.linkedmdb.org/resource/movie/runtime> ?runtime.
          ?movie <http://purl.org/dc/terms/date> ?date.
          ?movie  <http://purl.org/dc/terms/title> \"" . $name . "\".
        }";
    
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
?>