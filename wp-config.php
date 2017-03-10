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
define('AUTH_KEY',         '|icX5BOoP~Op-lm>f>F#$p7/S~pXeT8A%g&J9JRpLK/3-$-Niy?J/b4Sj[@HW18_');
define('SECURE_AUTH_KEY',  'C>JekU|Wh1i.2}S?/0,=Ahn,8T^^*JCt,GdlJRw-8cy:UVfN|&m *4jJ%mmCF[hV');
define('LOGGED_IN_KEY',    ':DSc(^3Jhg7@0f3a2v]N{nc8x0zc_2U0-x3&,D3PU>Ic,r^mO;+Y?, Fr3{,k,bF');
define('NONCE_KEY',        '6dmAgw]h^MUme$Pe$#=TXmmtpuduc;}}Dl?I.w_i+3:A88diLGg#A)HdzV{q*(.V');
define('AUTH_SALT',        '~##4j@Jadw?E{8LS~/s-|6F2hANhHk3peDY]* N40/3UG&lL5*:_o3h,LJ2ftXz&');
define('SECURE_AUTH_SALT', '/:~HBmOVfof]cHWg+CoGD9tK6b+pAlG|}t]d=l6~S)dg@t.7|{N^V~Udn[4L=jN!');
define('LOGGED_IN_SALT',   '234x@B0d wD/*@t`OSaxsH.O+JL*ifUqEC*{h)PzR)BM#@Ep@ bXCpB2p?wYFjh<');
define('NONCE_SALT',       '&/f(nAQOb!W/6QO`>;Y]on,prY/WB;4YMX2KES,l2%!^g}-X@o=Z{xny%:86Z_;7');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'ttd_';

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
