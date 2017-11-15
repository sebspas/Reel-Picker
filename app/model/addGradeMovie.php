<?php
	session_start();
	ini_set('error_reporting', E_ALL);
    ini_set('display_errors',1);
    require_once('../Config.class.php');
	require_once('../Bd.class.php');

	extract($_POST);
    print_r($_POST);

    $BD = new BD('movie');
    $idMovie = $BD->select('name', $movieTitle);
    $idMovie = $idMovie->id;

    $oldGrade = null;

    $BD->setUsedTable('user_movies');
    // we check if there is already a grade for this movie and this user
    if (!empty($oldGrade = $BD->selectTwoParam('user_id', $_SESSION['idUser'], 'movie_id', $idMovie, 'user_id'))) {
        $oldGrade = $oldGrade[0];
        // we update the grade        
        $BD->updateTwoParam('rating', $star, 'user_id', $_SESSION['idUser'], 'movie_id', $idMovie);
    } else {
        // if the rating is not in the db yet we add it
        $BD->addUserMovieRating($_SESSION['idUser'], $idMovie, $star);        
    }

     // we get all the tag linked to the movie
     $BD->setUsedTable('movie_tags');
     $tags = $BD->selectMult('movie_id', $idMovie);

     // for each tag we check if it's exist
     $BD->setUsedTable('user_tags');
     foreach ($tags as $tag) {
         
        // we check if the user already has a rating for this tag
        if (!empty($oldTag = $BD->selectTwoParam('user_id', $_SESSION['idUser'], 'tag_id', $tag->tag_id, 'user_id'))) {
            $oldTag = $oldTag[0];
            // if we already put a grade for this movie
            if (!empty($oldGrade)) {
                // we calculate the new rating value for this tag
                $globalRating = $oldTag->rating + (($star-$oldGrade->rating)/$oldTag->tag_count);
            } else {
                // if we didn't
                // we calculate the new rating value for this tag
                $globalRating = (($oldTag->rating * $oldTag->tag_count)+$star)/($oldTag->tag_count+1);              
                // we add +1 to the number of time that the use give a grade to a movie with this tag
                $BD->updateTwoParam("tag_count", ($oldTag->tag_count+1), 'user_id', $_SESSION['idUser'], 'tag_id', $tag->tag_id);
            }
            // we update the rating value in the db
            $BD->updateTwoParam("rating", $globalRating, 'user_id', $_SESSION['idUser'], 'tag_id', $tag->tag_id);
          
        } else {
            // if he doesn't have one we just add it with the correct rating
            $BD->addUserTag($_SESSION['idUser'], $tag->tag_id, $star);
        }
     }
?>