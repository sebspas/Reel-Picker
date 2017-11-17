<?php
    function GetRecommandations($quantity) {
        $BD = new BD('movie');
        return $BD->selectTop('title', $quantity);
    }

    function GetTags()
    {
        $userId = $_SESSION['idUser'];
        $BD = new BD('tag');
        $tags = array(
            $BD->selectPreferredTag($userId),
            $BD->selectMostPopularTag($userId),
            $BD->selectEmergingTag($userId),
            $BD->selectRandomUnratedTag($userId)
        );
        return $tags;
    }
?>