<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

		function load_jquey_spinner() {
		wp_enqueue_script( 'jquery-ui-spinner' );

		}

		add_action('wp_enqueue_scripts', 'load_jquey_spinner');


?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<script>(function(){document.documentElement.className='js'})();</script>
	<?php wp_head(); ?>
	<script type="text/javascript" src="wp-content/themes/twentyfifteen/js/bitcoin-price.js"></script>

	<style type="text/css">
		body {
			font-family:arial;
		}
	</style>

	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
	<link rel="icon" href="/favicon.ico" type="image/x-icon">

</head>

<body <?php body_class(); ?>>

<!-- Go to www.addthis.com/dashboard to customize your tools -->
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=AdamCox9" async="async"></script>


<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'twentyfifteen' ); ?></a>

	<div id="sidebar" class="sidebar">
		<header id="masthead" class="site-header" role="banner">
			<div class="site-branding" >
				<div style="max-width:190px;text-align:center;" />
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="/img/bitcoinusdlogo.gif" alt="Bitcoin USD" /></a>
					<h1 class="site-title" style="font-size:12pt;"><a href="/">Bitcoin USD<br>Information</a></h1>

					<?php
						if ( is_front_page() && is_home() ) : ?>
							<!--<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>-->
						<?php else : ?>
							<!--<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>-->
						<?php endif;

						$description = get_bloginfo( 'description', 'display' );
						if ( $description || is_customize_preview() ) : ?>
							<p class="site-description"><?php echo $description; ?></p>
						<?php endif;
					?>

						<p style='font-family:arial;font-size:7pt;'>BTC:1KifpBCL6pKRjSVhkUj1cPrB3tMiBdhaKV</p>

						<div style='border:2px solid #666;width:185px;margin-top:10px;'>
							<div style='background:#CCC;text-align:center;font-weight:bold;border-bottom:2px solid #666;'>$<span id='bitcoin_price' style='color:green;'>-</span></div>
							<div style='font-size:8pt;'>
								<div>ask: $<span id='bitcoin_ask'>-</span></div>
								<div>bid: $<span id='bitcoin_bid'>-</span></div>
								<div>avg 24h: $<span id='bitcoin_avg'>-</span></div>
								<div>vol 24h: <span id='bitcoin_vol'>-</span> btc</div>
								<div style='font-size:6pt;'><a href="https://bitcoinaverage.com/">BitcoinAverage Price Index</a></div>
							</div>
						</div>

				</div>
				<button class="secondary-toggle"><?php _e( 'Menu and widgets', 'twentyfifteen' ); ?></button>
			</div><!-- .site-branding -->
		</header><!-- .site-header -->

		<?php get_sidebar(); ?>
	</div><!-- .sidebar -->

	<div id="content" class="site-content">
