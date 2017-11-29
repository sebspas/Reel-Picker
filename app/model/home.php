<?php
    function GetTags()
    {
        $userId = $_SESSION['idUser'];
        $BD = new BD('tag');
        $preferred_tags = $BD->selectPreferredTags($userId, 2);
        $unrated_tag = $BD->selectRandomUnratedTag($userId);

        if (empty($preferred_tags)) $preferred_tags[0] = NULL;

        $tags = array(
            $preferred_tags[0],
            $BD->selectMostPopularTag($userId),
            $BD->selectEmergingTag($userId),
            $unrated_tag != NULL ? $unrated_tag : $preferred_tags[1]
        );

        if($tags[0] == NULL) {
            // This means the user has not rated a single movie yet (or there is a really obscure bug).
        }

        return $tags;
    }
    
    function GetRecommandations($tags, $quantity = 4, $movies_per_tag = 100) {
        $userId = $_SESSION['idUser'];
        $BD = new BD('movie');

        // We get movies from DB using a list of tag
        $temp = array();
        foreach ($tags as &$tag) {
            if ($tag != null)
                $temp[] = $BD->selectMovieIDWithTag($tag->id, $userId, 'rating', $movies_per_tag);
            }

        // We clean/simplify up the array of movie ids
        // to get an array of int (ids)
        $movies_id = array();
        foreach ($temp as &$arr) {
            foreach ($arr as &$a) {
                foreach ($a as &$data) {
                    $movies_id[] = $data;
                }
            }
        }

        // We eliminate duplicated ids
        $movies_id = array_unique($movies_id);

        // We get an array of of movies (id,name,image)
        // from our array of unique ids
        $temp = array();
        foreach ($movies_id as &$id) {
            $temp[] = $BD->selectMovieWithId($id);
        }

        // We clean/simplify up the array of movies
        // to get display-able array
        $movies = array();
        foreach ($temp as &$arr) {
            foreach ($arr as &$data) {
                $movies[] = $data;
            }
        }

        // We fix the max number of movies we want to display
        $max_index = sizeof($movies);
        if ($quantity < $max_index){
            $max_index = $quantity;
        }

        // We return our final sub-array
        return array_slice($movies, 0, $max_index);
    }
?>