<?php
/**
 * Defines database credentials.
 *
 * Most of Elgg's configuration is stored in the database.  This file contains the
 * credentials to connect to the database, as well as a few optional configuration
 * values.
 *
 * The Elgg installation attempts to populate this file with the correct settings
 * and then rename it to settings.php.
 *
 * @todo Turn this into something we handle more automatically.
 * @package    Elgg.Core
 * @subpackage Configuration
 */

global $CONFIG;
if (!isset($CONFIG)) {
	$CONFIG = new stdClass;
}

$CONFIG->heroku = true;

/*
 * Standard configuration
 *
 * You will use the same database connection for reads and writes.
 * This is the easiest configuration, and will suit 99.99% of setups. However, if you're
 * running a really popular site, you'll probably want to spread out your database connections
 * and implement database replication.  That's beyond the scope of this configuration file
 * to explain, but if you know you need it, skip past this section.
 */

/**
 * The database username
 *
 * @global string $CONFIG->dbuser
 */
$CONFIG->dbuser = getenv('ELGG_DB_USER');

/**
 * The database password
 *
 * @global string $CONFIG->dbpass
 */
$CONFIG->dbpass = getenv('ELGG_DB_PASS');

/**
 * The database name
 *
 * @global string $CONFIG->dbname
 */
$CONFIG->dbname = getenv('ELGG_DB_NAME');

/**
 * The database host.
 *
 * For most installations, this is 'localhost'
 *
 * @global string $CONFIG->dbhost
 */
$CONFIG->dbhost = getenv('ELGG_DB_HOST');

/**
 * The database prefix
 *
 * This prefix will be appended to all Elgg tables.  If you're sharing
 * a database with other applications, use a database prefix to namespace tables
 * in order to avoid table name collisions.
 *
 * @global string $CONFIG->dbprefix
 */
$CONFIG->dbprefix = 'elgg_'.getenv('APP_ENV').'_';

/**
 * Multiple database connections
 *
 * Elgg supports master/slave MySQL configurations. The master should be set as
 * the 'write' connection and the slave(s) as the 'read' connection(s).
 *
 * To use, uncomment the below configuration and update for your site.
 */
//$CONFIG->db['split'] = true;

//$CONFIG->db['write']['dbuser'] = "";
//$CONFIG->db['write']['dbpass'] = "";
//$CONFIG->db['write']['dbname'] = "";
//$CONFIG->db['write']['dbhost'] = "";

//$CONFIG->db['read'][0]['dbuser'] = "";
//$CONFIG->db['read'][0]['dbpass'] = "";
//$CONFIG->db['read'][0]['dbname'] = "";
//$CONFIG->db['read'][0]['dbhost'] = "";
//$CONFIG->db['read'][1]['dbuser'] = "";
//$CONFIG->db['read'][1]['dbpass'] = "";
//$CONFIG->db['read'][1]['dbname'] = "";
//$CONFIG->db['read'][1]['dbhost'] = "";

/**
 * Memcache setup (optional)
 * This is where you may optionally set up memcache.
 *
 * Requirements:
 * 	1) One or more memcache servers (http://www.danga.com/memcached/)
 *  2) PHP memcache wrapper (http://php.net/manual/en/memcache.setup.php)
 *
 * Note: Multiple server support is only available on server 1.2.1
 * or higher with PECL library > 2.0.0
 */
$CONFIG->memcache = true;
include_once(dirname(__FILE__).'/classes/PHPMemcacheSASL/MemcacheSASL.php');

$m = new MemcacheSASL;
$m->addServer($_ENV["MEMCACHIER_SERVERS"], '11211');
$m->setSaslAuthData($_ENV["MEMCACHIER_USERNAME"], $_ENV["MEMCACHIER_PASSWORD"]);

$CONFIG->memcacheWrapper = $m;

/**
 * Use non-standard headers for broken MTAs.
 *
 * The default header EOL for headers is \r\n.  This causes problems
 * on some broken MTAs.  Setting this to TRUE will cause Elgg to use
 * \n, which will fix some problems sending email on broken MTAs.
 *
 * @global bool $CONFIG->broken_mta
 */
$CONFIG->broken_mta = FALSE;

/**
 * Disable the database query cache
 *
 * Elgg stores each query and its results in a query cache.
 * On large sites or long-running scripts, this cache can grow to be
 * large.  To disable query caching, set this to TRUE.
 *
 * @global bool $CONFIG->db_disable_query_cache
 */
$CONFIG->db_disable_query_cache = FALSE;

/**
 * Minimum password length
 *
 * This value is used when validating a user's password during registration.
 *
 * @global int $CONFIG->min_password_length
 */
$CONFIG->min_password_length = 6;

/**
 * Optional session cookie configuration.
 * 
 * This is especially useful if you are running your site on HTTPS and you want all 
 * cookies to have their secure bit set.
 * 
 * Defaults can be set in your php.ini file.
 * 
 * @link http://php.net/manual/en/function.session-set-cookie-params.php
 */
// session_set_cookie_params(0, '/elgg', '', false, false); // Good if Elgg's root is at /elgg
// session_set_cookie_params(0, '/', '', true, false); // Good for HTTPS-only sites
