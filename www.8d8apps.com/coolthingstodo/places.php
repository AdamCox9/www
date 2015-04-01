<?PHP

	if( isset( $_GET['latitude'] ) && isset( $_GET['longitude'] ) ) {

		$latitude = $_GET['latitude'];
		$longitude = $_GET['longitude'];
		$type = $_GET['type'];
		$radius = isset( $_GET['radius'] ) ? $_GET['radius'] : 5000;

		$url = "https://maps.googleapis.com/maps/api/place/search/json?location=$latitude,$longitude&radius=$radius&types=$type&sensor=false&key=AIzaSyDuCpFFXnVlH11SOXyQVVZH3ObNsWLfw3U";

//		echo $url;

		echo file_get_contents( $url );

	}

?>