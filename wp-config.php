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
define( 'DB_NAME', '' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

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
define( 'AUTH_KEY',         'FVb1d-{t<*=+O2HG%`q4URtxsduNLY++eNo:n>QbDl*&n-mQlfGq]`}F3=_V6kI!' );
define( 'SECURE_AUTH_KEY',  'kBZc]`|_g=r#&JXqG8Ht4tJJZKe7=lV.Olulh&1LIJHUEXK aA<8Y#$LX2:fPDOo' );
define( 'LOGGED_IN_KEY',    '@Z@pJmG~w|]_LiKD0u^Feb&k.aT*3,oh`ru?JI2}]e4lR}8*h0=fEv69VC+MXp_5' );
define( 'NONCE_KEY',        '[<boIOnQ#U_Yw~:G)93G*]6/}fnS.2h_,hRT`Pi)N_K=BibZf)5PAv{RHafTkcqe' );
define( 'AUTH_SALT',        '/NR ~jBT !k:F%!cXne/OnQQF>K38;q5#>mZn>iR81:fXKkizbSo6r(dSp3`RzTg' );
define( 'SECURE_AUTH_SALT', 'UbIhGx1/%%Q21pK]GC-=<MkAG^sPST~lB$|6YSrsR6>qDMW*a*%[X7@n[PXq%L#N' );
define( 'LOGGED_IN_SALT',   '33}5n}720;P`84UFl:_Rw4j=;iA/ffq7%L)^.(jiaPRp!zP1PaC%XEG2tS8gSJgI' );
define( 'NONCE_SALT',       'c(vjC3dJxZ# 0.rT3Y>5^gj;;n,><LS(^P_@pI/cf+I]J<Fyjh5<as6/LPxZ(>}0' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
