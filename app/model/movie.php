<?php
    function getMovieData($movieTitle) {
        $imdb = new IMDB($movieTitle);
        $movie = array();
        if($imdb->isReady){
            $movie['title'] = $movieTitle;
            $movie['image'] = $imdb->getPoster();
            $movie['runtime'] = $imdb->getRuntime();
            $movie['rating'] = $imdb->getRating();
            $movie['year'] = $imdb->getYear();
            $movie['desc'] = $imdb->getDescription();
            $movie['tags'] = $imdb->getGenreArray();
            
            $arr = $imdb->getDirectorArray();
            if (empty($arr)){
                $movie['directors'] = "N/A";
            }
            else{
                $dirArr = array();
                foreach ($arr as &$dir) {
                    $dirArr[] = $dir['name'];
                }
                $movie['directors'] = trim(implode(", ", $dirArr), ", ");
            }
            
            $arr = $imdb->getCastArray();
            if (empty($arr)){
                $movie['cast'] = "N/A";
            }
            else{
                $castArr = array();
                foreach ($arr as &$cast) {
                    $castArr[] = $cast['name'];
                }
                $movie['cast'] = trim(implode(", ", $castArr), ", ");
            }
            
            // we check if the movie is already in the db
            $BD = new BD('movie');
            if (empty($BD->select("name", $movie['title']))) {
                // if the movie is not there we add it
                $BD->addMovie($movie['title'], $movie['rating'], $movie['desc'], $movie['image'], $movie['year'], $movie['runtime']);
            }
            
            // we get the movie id from the db
            $idMovie = $BD->select('name', $movieTitle);
            $idMovie = $idMovie->id;

            // we check if all the tags for this movie are in the database
            // at the same time we link them to the movie
            foreach ($movie['tags'] as $tag) {
                $BD->setUsedTable("tag");
                if (empty($tagDB = $BD->select("name", $tag))) {
                    // we add the tag
                    $BD->addTag($tag);
                }

                // we get the tag id
                $tagDB = $BD->select("name", $tag);

                // we add link it to the movie tag if needed
                $BD->setUsedTable("movie_tags");
                if (empty($BD->selectTwoParam("movie_id", $idMovie, "tag_id", $tagDB->id, "movie_id"))) {                    
                    $BD->addMovieTag($idMovie, $tagDB->id);
                }
            }
        }
        return $movie;
    }
?>