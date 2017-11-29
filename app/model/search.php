<?php
    function GetMovies() {
        $BD = new BD('movie');
        return $BD->selectTop('rating', 30);
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
            LIMIT 50
        ";
        
        $rows = $dbpedia->query($q, 'rows');

        return $rows;
    }

    function getMovieImage($moviesDataSPARQL) {

        $BD = new BD('movie');
        
        foreach ($moviesDataSPARQL as $dataSPARQL) {
            
            // we check if the movie is in our database
            if (!empty($movieInDB = $BD->select("name", $dataSPARQL['title']))) {
                // if true we get our own data 
                $movie['name'] = $dataSPARQL['title'];
                $movie['image'] = $movieInDB->image;
                $movie['rating'] = $movieInDB->rating;
                $movie['year'] = $movieInDB->year;
                $movie['num_votes'] = $movieInDB->num_votes;
    
                $moviesData[] = $movie;

            } else {
                // we get the data from IMDB
                $imdb = new IMDB($dataSPARQL['title']);
                $movie = array();
                if($imdb->isReady){
                    $movie['name'] = $dataSPARQL['title'];
                    $movie['image'] = $imdb->getPoster();                                        
                    $movie['rating'] = $imdb->getRating();
                    $movie['year'] = $imdb->getYear();
                    $movie['num_votes'] = $imdb->getNumVotes();
                    
                    // if the movie got a rating we can add it to the result
                    // and the number of votes is superior to 250
                    if (isset($movie['rating']) && $movie['rating'] != "N/A" && $movie['num_votes'] != "N/A" && $movie['num_votes'] > 250)
                        $moviesData[] = $movie;
                } 

                // then we add the movie to our database if the grade is >= 7.0
                if (isset($movie['rating']) && $movie['rating'] != "N/A" && $movie['rating'] >= 7.0) {
                    // we get the missing data to add in db
                    $desc = $imdb->getDescription();
                    $runtime = $imdb->getRuntime();
                    // we add the movie to the DB (so next time we don't need to get the data from IMDB)
                    $BD->addMovie($movie['name'], $movie['rating'], $desc, $movie['image'], $movie['year'], $runtime, $movie['num_votes']);
                }                
            }            
        }
       
        // sort by year
        foreach ($moviesData as $key => $row) {
            $year[$key]  = $row['year'];
            $rating[$key]  = $row['rating'];
            $nbvotes[$key] = $row['num_votes'];
        }
        array_multisort($year, SORT_DESC, $rating, SORT_DESC, $nbvotes, SORT_DESC, $moviesData);
        return $moviesData;
    }
?>    