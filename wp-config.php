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
define('DB_NAME', 'histroypDBehh9h');

/** MySQL database username */
define('DB_USER', 'histroypDBehh9h');

/** MySQL database password */
define('DB_PASSWORD', 'vBmbvSKsh6');

/** MySQL hostname */
define('DB_HOST', '127.0.0.1');

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
define('AUTH_KEY',         '^cQ-oZ8[~oG1#lVK5:sgR0!vO8[@dNCPA]*ePD<mXL6+qiS2_xWH;*ePD;tM7$nbM');
define('SECURE_AUTH_KEY',  'O2xlP9*peD;nY7<$nM^vUF3,$bPA*qfI<ynM6<gR0|vVF[@cRC}skY7>$nM0,vUF');
define('LOGGED_IN_KEY',    'xX0vgUF,vcB}^rQB7<nXM7$fUF,vjU@sdC:@sO:!sRG1>oYN}@rV4|zkJ8teD;_hS');
define('NONCE_KEY',        'G[wVG4|wVRB!rgR0zoZ8>@o+pa9]*pH6<mWH6_thSD_wh9]+pO9;jI3<yXI{^fQE');
define('AUTH_SALT',        '9_hSG1_aO;~tdOymX6<$mE^ufE3{qaLA*qXI2ymXHrcQ0^rgJ>@nN7>,jUI3,jQB');
define('SECURE_AUTH_SALT', 'N0^K5[oZO8[hWG#-lRC:!gRG0-ZO1!g9]paL9*iXH2xmWD;_tSD1_aL9]paO.jTI3');
define('LOGGED_IN_SALT',   'L]*eaK]-pZ9_xWH5#x,yYI7<yQF^fQF{$qbM{*jI7<yXI!gRF0!gN8[oZN},zjJ3');
define('NONCE_SALT',       'VJ>@gG4|kVFB^rgQB^nY7>znN_wVG1|wO9:~dOD[-oZ8[@VG1|wVK*qP9;teL6<ma');

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
define('WP_DEBUG', false);define('FS_METHOD', 'direct');

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
