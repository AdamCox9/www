<?PHP

	die('test');

	ini_set( 'display_errors', 1 );
	error_reporting( E_ALL );

	//List all files in 'files' directory:

	removeOldFiles( 'cache/amazon/item' );
	removeOldFiles( 'cache/amazon/search' );

	function removeOldFiles( $dir ) {
		if ( $handle = opendir( $dir ) ) {
			while ( false !== ( $entry = readdir( $handle ) ) ) {
				if ($entry != "." && $entry != "..") {
					if( time() - filemtime( "$dir/$entry" ) > 28*60*60*24 ) {
						unlink( "$dir/$entry" );
					}
				}
			}
			closedir($handle);
		}
	}

?>
