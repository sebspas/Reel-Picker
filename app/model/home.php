<?php
    function GetTags()
    {
        $userId = $_SESSION['idUser'];
        $BD = new BD('tag');
        $tags = array(
            $BD->selectPreferredTags($userId, 1)[0],
            $BD->selectMostPopularTag($userId),
            $BD->selectEmergingTag($userId),
            $BD->selectRandomUnratedTag($userId)
        );
        return $tags;
    }
    
    function GetRecommandations($quantity) {
        $BD = new BD('movie');
        return $BD->selectTop('title', $quantity);
    }
?>