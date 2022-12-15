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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'nnucoin_jsintech' );

/** MySQL database username */
define( 'DB_USER', 'nnucoin_jsint' );

/** MySQL database password */
define( 'DB_PASSWORD', 'M]V*)ikXkuwg' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'ry4ta3wfue2zb7qdikowldt7wkcl0bi2wuivbm5wfifucvyd2yojptbatokxcgpw' );
define( 'SECURE_AUTH_KEY',  'wmz69er7h1lwqfexz9ce7fza6pn0to3ifuv2gfpyhzdza4w163orljh9aetpj5xo' );
define( 'LOGGED_IN_KEY',    'nanyykz5muoaw3xo4gxr9llewiffcuvtwmwvqsp531zguktgvpcagpuhwxf7szmt' );
define( 'NONCE_KEY',        '4es1yey728osdrxmoe57vuu6v5swisicdbc5ptzgcxtzbgkgpi1tc3tv9gxs0uha' );
define( 'AUTH_SALT',        'pp8p3k7xmkw7ekjkezxdunowkf4qyjenir4bjkkgjwx1ous11cpvcuznrxishhwh' );
define( 'SECURE_AUTH_SALT', 'keun2gfq1khkhbjllhuxhytoichutqv50mopwr5ffxvvkwnmdfw03bhplgdowr4q' );
define( 'LOGGED_IN_SALT',   'pn78wm7uqn19lt4phs1d56zwzqlymzdp0hdxmp6h5cckecl2lth4ziikbnys2ylr' );
define( 'NONCE_SALT',       'ddna0mednlbivh8ptqtybflinsimdph7khmqxjtzz0fwwxzch8hbhiwd3dei1osl' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpjy_';

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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
