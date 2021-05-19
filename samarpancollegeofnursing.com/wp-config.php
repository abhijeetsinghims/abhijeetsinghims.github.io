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
define('DB_NAME', 'narmadav_wp49767');

/** MySQL database username */
define('DB_USER', 'narmadav_wp49767');

/** MySQL database password */
define('DB_PASSWORD', ']SsQ5p94-3');

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
define('AUTH_KEY',         'bu5yzpzheydubkqnxizhdmc1lwdic5ng94yydrhygufbsdehfhocpqlcmyfhe9ap');
define('SECURE_AUTH_KEY',  'udtrihebqkjsyhc7yk6djnehzj9isqn2s2ch7gwppsunjbivcmtmkzq2jsqw4c5q');
define('LOGGED_IN_KEY',    'snh9lyamdfzymmpohd7s7war2ci0qkgrbrbmcdwz6riipgmibkhmovsg1bqfpt4x');
define('NONCE_KEY',        'jsqkuot3diw9rwqclqk9ba7fpqlz68ykxacxo7cqxysqzohpajmcariu8bk9j0ac');
define('AUTH_SALT',        'qo0mgzufvvpuuklo9fot5cfawgdlshbw1wgrnxehdtasf1peirq5m0xovr0fsjdf');
define('SECURE_AUTH_SALT', 'woqc4ln4aa0c18mz9apfrex208n6tyt2zg0a46a2vmozmwmq4ypyq6aebvhwyj7s');
define('LOGGED_IN_SALT',   'vwnkr0gm0guiyi00acd6opdpmhpxiib67y79tyj32vzennszpaettllgwh2k8mv1');
define('NONCE_SALT',       'u0vexjf8vqnkwniqimgl6glvbajsy36sxyiasltttja3n5mz0xzulwos1vvwf5s7');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wpke_';

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
