<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u425776359_3I3O4' );

/** Database username */
define( 'DB_USER', 'u425776359_zwfPD' );

/** Database password */
define( 'DB_PASSWORD', '9OnHPOWzbr' );

/** Database hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'w@pA@ R4C*VMgh0K#<GuvKUUpXd2K1`L8o<qO)ZqlAJgK/hmp*e0/MKx+ocyOry?' );
define( 'SECURE_AUTH_KEY',   'stb,E68}4F%W+:r5>dDii$VU}f!ckP.o&$iMXmQTXs<01%`oKTzV_~V~E@M&QZBQ' );
define( 'LOGGED_IN_KEY',     '/=<>ElZ?-8VkNCiWQ4sQLj[L<gP-+Q* ~=?F|%8ZoG)no+;BEmX%M99Rhd5=0Yl+' );
define( 'NONCE_KEY',         '3x %NSo6#XPux+_jv$Ef)mdovHkL?)Lwj4p/J_=W#5{O&Lv3e;byxGs0^1W[e e|' );
define( 'AUTH_SALT',         '}RdmVcS}IR^&62##ts;@W&P[d/g{l`{6(21H&|e;}OmSi@e~y*RdB((r8#:1N9Hx' );
define( 'SECURE_AUTH_SALT',  'L/%4C$z*{D[:0Q}b#L!~viUeTz~?crchh ]t=x[|7}g#M}_,H$z2h,t~`5X#F2X.' );
define( 'LOGGED_IN_SALT',    'S-rQ2Iqt+BZzw?q}ZPoG38QPy!B8Rft0y$qXD(Z:#}HxY&9 M6oGppdXsK6KupDT' );
define( 'NONCE_SALT',        'ikww8spGnQ&+JE*MJ|=loU*1t8nqqAtRst-%7WC&MI?|y@I)X*F=![{{V~>o% ;A' );
define( 'WP_CACHE_KEY_SALT', 'N oiK;s{M!UmSi70[)HFNn,FQ}E5 G)C]4}lf_>u]g5!`]vr0]OK.,eD4PT([wOj' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );


/* Add any custom values between this line and the "stop editing" line. */



define( 'FS_METHOD', 'direct' );
define( 'WP_AUTO_UPDATE_CORE', 'minor' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
