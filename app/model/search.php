<?php
    function GetMovies() {
        $BD = new BD('movie');
        return $BD->selectAll('title');
    }
    
    /*function SearchForMovies($searchTerms) {
        $BD = new BD('movie');
        return $BD->searchMovies($searchTerms);
    }*/

    function makeRegexFromString($string) {
        $tmp = explode(" ", $string);

        $ret = "";
        $cpt = 0;
        foreach ($tmp as $s) {
            if ($cpt != 0 )
                $ret .= " ";
            $ret .= "(" . $s[0] . "|" . strtoupper($s[0]) . ")" . substr($s, 1);
            $cpt++;
        }

        return $ret;
    }

    function SearchForMovies($name) {
        $config = array(
            /* remote endpoint */
            'remote_store_endpoint' => 'http://dbpedia.org/sparql'
        );
            
        /* instantiation */
        $dbpedia = ARC2::getRemoteStore($config);
    
        $name = makeRegexFromString($name);
    
        //print_r($dbpedia);
        $q = "
            SELECT DISTINCT ?movie ?title
            WHERE {
                ?movie rdf:type <http://dbpedia.org/ontology/Film>.
                ?movie rdfs:label ?title.
                FILTER REGEX(?title, '" . $name . "').
                FILTER (langMatches(lang(?title),'en')).                
            } group by ?title
            LIMIT 100
        ";
        
        $rows = $dbpedia->query($q, 'rows');

        return $rows;
    }

    function getMovieImage($moviesDataSPARQL) {
        foreach ($moviesDataSPARQL as $dataSPARQL) {
            $imdb = new IMDB($dataSPARQL['title']);
            $movie = array();
            if($imdb->isReady){
                $movie['title'] = $dataSPARQL['title'];
                $movie['image'] = $imdb->getPoster();
                $movie['rating'] = $imdb->getRating();
                $movie['year'] = $imdb->getYear();
    
                $moviesData[] = $movie;
            } 
        }
       
        // sort by year
        /*foreach ($moviesData as $key => $row) {
            $year[$key]  = $row['year'];
        }
        array_multisort($year, SORT_DESC, $moviesData);*/
        return $moviesData;
    }
?>    