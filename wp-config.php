<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'toantuduy');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'sp7NWB4&TmI0h<BQ{LjY^nlZDG)bDo&se;SZ,Nib,h)s$e9 :g_lri#F>:~s/al[');
define('SECURE_AUTH_KEY',  '$h/W%54|{>0iK3W3*mN%y=]8WEdCXd0F=?w+.yL=G[8ItH!D6qsimLHyOLA|C`7i');
define('LOGGED_IN_KEY',    'mCJs)}J$#=t-iV&!e0BR*c;)ZYE5WSVW(`&SirN[.R_:3+=EvdJqC/F9cE )1;(#');
define('NONCE_KEY',        'cB4x-X[(bP%c<oVbYjIo{}GNy8RPfg^0zs ;=Za7g@tU6/Q8!-B{My~Y@MY7u$<P');
define('AUTH_SALT',        'r~hXN9Z_a>}c`<a-=D2}*4Ew=|#EBF?#pMX:Co2y6$Hmd?/DLL];tWzPFL}eKtm:');
define('SECURE_AUTH_SALT', '$ath5yabUG#.hKg79-];L}Us:72E2f~}=%a|Ll;a+WPC{%m+%#8X<WH8)C@2wWoZ');
define('LOGGED_IN_SALT',   'Pd@Vj_t8Xg{P1-uTdh#lC&5.-dw?zkg>sleGUENnUw5lW kHN$yL1aGd/j#04a@o');
define('NONCE_SALT',       'sJe4`]<maz-$4~4k0p+EV;z%>!2e&01!#*+[2e]:-t<V~vgk`JQ-2qsym0PKN$TX');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
