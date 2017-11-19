<?php
    function GetTags()
    {
        $userId = $_SESSION['idUser'];
        $BD = new BD('tag');
        $preferred_tags = $BD->selectPreferredTags($userId, 2);
        $unrated_tag = $BD->selectRandomUnratedTag($userId);
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
    
    function GetRecommandations($tags, $quantity = 4) {
        $BD = new BD('movie');

        // We get movies from DB with a list of tag
        $temp = array();
        foreach ($tags as &$tag) {
            $temp[] = $BD->selectWithTag($tag->id, 'rating', 1);
        }

        // We clean/simplify up the array to return
        $movies = array();
        foreach ($temp as &$arr) {
            foreach ($arr as &$data) {
                $movies[] = $data;
            }
        }
        return $movies;
    }
?>