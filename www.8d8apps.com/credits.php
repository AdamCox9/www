<?PHP

	require_once 'local.php';

	$DB = new DatabaseConnection();
	$conn = $DB->getConn();

	$MDP = "http://www.8d8apps.com/downloadrapbeatsapp.php";

	/*
		Give user a credit for each click through...
	*/

	if( isset( $_GET['ref'] ) ) {
		$ref = $_GET['ref'];
		$ip = GetVisitorIP();
		
		if( is_numeric( $ref ) ) {
			if( CheckValidRef( $ref ) ) {
				//Make sure there is only one click per IP address...
				if( CheckRefForIp( $ref, $ip ) ) {
					//Update the database for ref...
					InsertRef( $ref, $ip );
					//echo "REF OK";
				} else {
					//This IP address already used for Ref for this device...
					//echo "IP ALREADY USED";
				}
				echo file_get_contents( $MDP );
			} else {
				echo file_get_contents( $MDP );
				//echo "DEVICE REF DOES NOT EXIST";
			}
		} else {
			echo file_get_contents( $MDP );
		}

	} else	if( isset( $_GET['getref'] ) ) {

		//Return the number of references for getref
		echo GetRefCountForDevice( $_GET['getref'] );

	} else if( isset( $_GET['uniqueId'] ) ) {
		$uniqueId = $_GET['uniqueId'];

		//Length of the unique id is always 36:
		if( strlen( $uniqueId ) == 36 ) {

			if( ! GetValidRef( $uniqueId ) ) {
				//Need to store this uniqueId to a table if it has not been already...
				echo InsertDevice( $uniqueId );
			}

		} else {
			//Invalid id length:
			echo -1;
		}

	} else {

		//This will be default page for search engines to find...
		//All incoming traffic should eventually be 403'd here...

		echo file_get_contents( $MDP );

	}

?>