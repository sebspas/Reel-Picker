<?php
$imdb = new IMDB("le seigneur des anneaux: la communauté de l'anneau");
if($imdb->isReady){

	$imdb_api = array();
	$imdb_api['castArray'] = $imdb->getCastArray();

	// Check to see if the media is a movie or tv series
	if ($imdb->isTvShow()) {
		$imdb_api['creators'] = $imdb->getCreatorArray();
	} else if ($imdb->isVideo()) {
		$imdb_api['directorArray'] = $imdb->getDirectorArray();
	}

	$imdb_api['genreArray'] = $imdb->getGenreArray();
	$imdb_api['genreString'] = $imdb->getGenreString();
	$imdb_api['mpaa'] = $imdb->getMpaa();
	$imdb_api['description'] = $imdb->getDescription();
	$imdb_api['plot'] = $imdb->getPlot();
	$imdb_api['imdbID'] = $imdb->getImdbID();
	$imdb_api['imdbURL'] = $imdb->getUrl();
	$imdb_api['poster'] = $imdb->getPoster();
	$imdb_api['rating'] = $imdb->getRating();
	$imdb_api['runtime'] = $imdb->getRuntime();
	$imdb_api['title'] = $imdb->getTitle();
	$imdb_api['AKA'] = $imdb->getAka();
	$imdb_api['languagesArray'] = $imdb->getLanguagesArray();
	$imdb_api['languagesString'] = $imdb->getLanguagesString();
	//$imdb_api['trailer'] = $imdb->getTrailer();
	$imdb_api['type'] = $imdb->getType();
	$imdb_api['year'] = $imdb->getYear();
	//$imdb_api['userComments'] = $imdb->getUserComments();
	//$imdb_api['parentalGuide'] = $imdb->getParentalGuide();

	echo "<PRE>";
	print_r($imdb_api);
	echo "</PRE>";

}else{
	echo $imdb->status;
}


?>
