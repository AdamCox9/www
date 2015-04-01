<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link http://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wpbitcoinusd');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '123233abc');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'uX~Y7N!$o7qmFnKwrCTm Eb+%qG>?9%1_])W+`5Sy@6qVPL]V+H-k35*]Xmhje-k');
define('SECURE_AUTH_KEY',  'P)qp2s5doeOTF !D`)fOkk:@@q|&-H[i-Xe{}j+c%r,1|P>RfcA?!f}{ w]*SbW$');
define('LOGGED_IN_KEY',    'qm^I/Em8.p Oefz~{9Cqy^4PjrZ:+,QNjWWH43=kVAh}XK2Am/cQjfzV$o^&*SC,');
define('NONCE_KEY',        '|~M&2oTj,Eo=a~J*eBTqB7#7%uy/2gHJK>G,(heU {im}w^afRMKxVo+BjrepP,2');
define('AUTH_SALT',        'F`F%b39dnZ HBHbi|dTq*^m|!#WUe,}*y):@[K<UJEC9n)){SxwgV1j5+GmuU;}A');
define('SECURE_AUTH_SALT', 'JMk/Hrj}ll<6n#na@t=]w2u[ekc^1[X$V,MQ{lY. MJw B*^OM]B<}~7?OT*mV  ');
define('LOGGED_IN_SALT',   'kJI{iCFiLFm<h+;#_{Z[b8fjMP ,Ah_HW]n~C,C2QFQcBiOX$Jof?-JMNF:jyi5g');
define('NONCE_SALT',       '!g4^P+?B2er )DngH=b4&d<8ihK+]-o;/Lfegq}7Sm,vtkpwgxfX#hS0dAX7s!iZ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

