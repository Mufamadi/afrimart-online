<?php
define('WP_CACHE', true);
define('WP_HOME','https://afrimart.net.za/');
define('WP_SITEURL','https://afrimart.net.za/');
define('WP_MEMORY_LIMIT', '500M');
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
define('DB_NAME', 'afrimjxqac_db1');

/** MySQL database username */
define('DB_USER', 'afrimjxqac_1');

/** MySQL database password */
define('DB_PASSWORD', 'U49@(pUS9G1');

/** MySQL hostname */
define('DB_HOST', 'dedi515.flk1.host-h.net');

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
define('AUTH_KEY',         'rb4jurdbldp1kzbv9etkovgkmhosdot64xo2qpxa42freqjjg6ixnv71t1oxrw0y');
define('SECURE_AUTH_KEY',  'su8ov4kb5flbnn6ondydj0uwxki4gmnvj07yyc0jgtq8scbjbvavxqtfihqtsij8');
define('LOGGED_IN_KEY',    'npucchvp22aj4bmrwcn8wswcbfujvyd7gvraepjlhtwsxxarknp0ogjt8jzpyjtp');
define('NONCE_KEY',        'xcgonxro8k2nfpmtk8qdzjoofwqwsucr5xphxmdrivlg6k5fk4nynhmqj5su9jbp');
define('AUTH_SALT',        'pdhaepy2lanc9cxozpgaf8zo9z1qgwgyvdjsivxr22puz5an2vsaxdognmgrmlu9');
define('SECURE_AUTH_SALT', 'xuhydxg5g6rksbhyw8n7zowftioms2zg2iks7rwbbgwhonu90t0ayhsiy07rbcya');
define('LOGGED_IN_SALT',   'ym7ohdmm3wvcuevqqi4dpvf5jszqu9onzwkkew7dquxdmfbcai6shcsjuqfhoto5');
define('NONCE_SALT',       '8ew0qfiirjrpiwqqvmsmo7orf4adg54tumje3vujuunp2kmeseemacw4resqamdf');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpw9_';

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
