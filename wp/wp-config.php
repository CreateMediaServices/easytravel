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
define('DB_NAME', 'easytravel_wp');

/** MySQL database username */
define('DB_USER', 'theproje_cmguser');

/** MySQL database password */
define('DB_PASSWORD', '89I5M-817&P*I5g');

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
define('AUTH_KEY',         'bSbn8?3iO*%CiR0^viT)lHS<&g<WY{#cE92A>6fYF7$fk6wy-A8CXSpdjs3YfoDK');
define('SECURE_AUTH_KEY',  'mmIv%Oy4-NEabDkrut27rP?, yG4yFP2C,kHMc+<x`OLeIUlQX@)NPMxk+3)=Bp+');
define('LOGGED_IN_KEY',    'T9.Y)u@drBA8+2]uge7h8Q7d/yy_gA/).v/YQ$?On/EQMB?Bg6y?M844alf`ihm?');
define('NONCE_KEY',        'C[N.]#wv?@<yfaN9O!NeQuQZ0z;co%^ Y^}qUW)A{)?7:sss&$giC%ivJ{H<7V+ ');
define('AUTH_SALT',        '<vmWA8(DbrSvUm1_Flp&?>_{huup(f rvz5W?KAlE]fKNQ4]E>=nvs#[PGd&Ft;i');
define('SECURE_AUTH_SALT', '>q>/Y3l;u=guUHB3ybpuH4/-jNIsh:fjp;B$Q_?>i*)|7n~M}P*qR+}Ykc7zFwTj');
define('LOGGED_IN_SALT',   '&&&dO+xN XBW0pl5</#[4r&|6))Nn,&P}j_y>>.EX]$>0iUD,9>sSYB)pF16ip+-');
define('NONCE_SALT',       'Z$GuX^1&;=1q#-6VeWj;UKQ5F.Njj,o5A_&:/8X+lUJ L&XUY]`~-M_dM!Qo,,7w');

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
