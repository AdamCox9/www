<?php
/**
 * The sidebar containing the main widget area
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

if ( has_nav_menu( 'primary' ) || has_nav_menu( 'social' ) || is_active_sidebar( 'sidebar-1' )  ) : ?>
	<div id="secondary" class="secondary">

		<?php if ( has_nav_menu( 'primary' ) ) : ?>
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<?php
					// Primary navigation menu.
					wp_nav_menu( array(
						'menu_class'     => 'nav-menu',
						'theme_location' => 'primary',
					) );
				?>
			</nav><!-- .main-navigation -->
		<?php endif; ?>

		<?php if ( has_nav_menu( 'social' ) ) : ?>
			<nav id="social-navigation" class="social-navigation" role="navigation">
				<?php
					// Social links navigation menu.
					wp_nav_menu( array(
						'theme_location' => 'social',
						'depth'          => 1,
						'link_before'    => '<span class="screen-reader-text">',
						'link_after'     => '</span>',
					) );
				?>
			</nav><!-- .social-navigation -->
		<?php endif; ?>

		<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
			<div id="widget-area" class="widget-area" role="complementary">
				<?php dynamic_sidebar( 'sidebar-1' ); ?>
				<aside id="recent-posts-2" class="widget widget_recent_entries">

					<div style='margin-bottom:25px; margin-top:-25px;'>
						<script charset="utf-8" type="text/javascript">
							amzn_assoc_ad_type = "responsive_search_widget";
							amzn_assoc_tracking_id = "ezstbu-20";
							amzn_assoc_link_id = "O2QL5B5NFD7C3LN7";
							amzn_assoc_marketplace = "amazon";
							amzn_assoc_region = "US";
							amzn_assoc_placement = "";
							amzn_assoc_search_type = "search_widget";
							amzn_assoc_width = "auto";
							amzn_assoc_height = "auto";
							amzn_assoc_default_search_category = "";
							amzn_assoc_default_search_key = "bitcoin";
							amzn_assoc_theme = "light";
							amzn_assoc_bg_color = "FFFFFF";
						</script>
						<script src="//z-na.amazon-adsystem.com/widgets/q?ServiceVersion=20070822&Operation=GetScript&ID=OneJS&WS=1&MarketPlace=US"></script>
					</div>


					<h2 class="widget-title">Articles</h2>
					<ul>
					<?php
						$postslist = get_posts('numberposts=50&order=ASC');
						foreach ($postslist as $post) :
						setup_postdata($post);
						$id = $post->ID;
					?>
					<li>
						<a href="?p=<?php echo $id; ?>">
							<?php the_title(); ?>
						</a>
					</li>
						<?php endforeach; ?>
					</ul>

					<div style='padding-top:25px;'>
						<a href="https://bitcoin.org/bitcoin.pdf">Bitcoin PDF</a>
					</div>

				</aside>
			</div><!-- .widget-area -->
		<?php endif; ?>

	</div><!-- .secondary -->

<?php endif; ?>
