<?php
    function GetRecommandations($quantity) {
        $BD = new BD('movie');
        return $BD->selectTop('title', $quantity);
    }
?>