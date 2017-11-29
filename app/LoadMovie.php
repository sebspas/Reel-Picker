<?php
session_start();
ini_set('error_reporting', E_ALL);
ini_set('display_errors',1);
require_once('Config.class.php');
require_once('imdbapi.class.php');
require_once('Bd.class.php');

header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');

    $movieName = $_GET['title'];

    $BD = new BD('movie');
    $moviesData = array();
        
    // we check if the movie is in our database
    if (!empty($movieInDB = $BD->select("name", $movieName))) {
        // if true we get our own data 
        $movie['name'] = $movieName;
        $movie['image'] = $movieInDB->image;
        $movie['rating'] = $movieInDB->rating;
        $movie['year'] = $movieInDB->year;
        $movie['num_votes'] = $movieInDB->num_votes;
        $movie['runtime'] = $movieInDB->runtime;

        $moviesData[] = $movie;
    } else {
        // we get the data from IMDB
        $imdb = new IMDB($movieName);                
        if($imdb->isReady){
            $movie['name'] = $movieName;
            $movie['image'] = $imdb->getPoster();                                        
            $movie['rating'] = $imdb->getRating();
            $movie['year'] = $imdb->getYear();
            $movie['num_votes'] = $imdb->getNumVotes();
            $movie['runtime'] = $imdb->getRuntime();
            
            // if the movie got a rating we can add it to the result
            // and the number of votes is superior to 250
            if (isset($movie['rating']) && $movie['rating'] != "N/A" && $movie['num_votes'] != "N/A" && $movie['num_votes'] > 250)
                $moviesData[] = $movie;
        } 

        // then we add the movie to our database if the grade is >= 7.0
        if (isset($movie['rating']) && $movie['rating'] != "N/A" && $movie['rating'] >= 7.0) {
            // we get the missing data to add in db
            $desc = $imdb->getDescription();
            
            // we add the movie to the DB (so next time we don't need to get the data from IMDB)
            $BD->addMovie($movie['name'], $movie['rating'], $desc, $movie['image'], $movie['year'], $runtime, $movie['num_votes']);
        }                
    }            

    if (!empty($moviesData)) {
        echo json_encode($moviesData[0]);
    } else {
        $error = "null";
        echo json_encode($error);
    }

?>