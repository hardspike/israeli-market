<?php
define( 'WP_CACHE', true );
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
define( 'DB_NAME', 'u117482040_ARQ6L' );

/** MySQL database username */
define( 'DB_USER', 'u117482040_KEcgq' );

/** MySQL database password */
define( 'DB_PASSWORD', 'DijEbwDOWF' );

/** MySQL hostname */
define( 'DB_HOST', 'mysql' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'yjGZkj1@GDMz_</1,P<o^#SFso,-MO!%e6%`3,q/XpLz>2(VbDuj9>{_PRIs>QG$' );
define( 'SECURE_AUTH_KEY',   ':6tG)Y>Zmg?YjjV^>9Y5, yiDIN)s6xyi`lbj4eK,nB0rILtj[KaDmlT)*GK79Cm' );
define( 'LOGGED_IN_KEY',     'Hy2&q?7c`/ci=g[nbUdedHJQ,}{E8$hA}GTW7HM4 }/sY!}61A`6Mise1sMXg5F<' );
define( 'NONCE_KEY',         'A_nNI2|I_CrYs6O8bNWP!iC{(PciY@S(57?;XA|)9_&4[|8 ,vfNb:mFaL82GYy]' );
define( 'AUTH_SALT',         '_l4_A5mN)H5$> Xt1C,7Mi/Gx6QWYS9w]h(K=@AlNNFQRSxiFS%kM<vH^G1IZ~]O' );
define( 'SECURE_AUTH_SALT',  '$lOCofgyhl4p@<<h6t`,I&-/c$LnaSTzB3IP 4YSFs.5*V=hExRH75At!U#5&DWc' );
define( 'LOGGED_IN_SALT',    '20nP|~^U]*](333?U7lSn~83#Ghjs$GebHe3vG09M}!KT?_zg$S<dXa%}up3x}*>' );
define( 'NONCE_SALT',        '}>+yeL6gIY*n-o_niVg0f^i v3%;M^.8O8i/S)+SbmZPWswqh/[RnA<vidrm8jHf' );
define( 'WP_CACHE_KEY_SALT', 'Acss/DO~-$ 9!N9Gn_ ?dU~ gpC4ol+b2P%bcR:C`R+Jj:Bbf,$3}/,^kFLYyKT.' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
