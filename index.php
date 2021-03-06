<?php

// Configure some security features
// These already have sane defaults in recent PHP versions,
// so consider this as documentation

ini_set("display_errors", 1);

ini_set('session.use_cookies', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.use_trans_sid', '0');
// This does have a default value listed?
ini_set('session.cookie_httponly', '1');

// Load constants defined in config
require_once('config.php');

// HTTPS only!
if (parse_url(MY_URL, PHP_URL_SCHEME) === "https") {
    ini_set('session.cookie_secure', '1');
}

// A special bit of configuration for our host:
// An SSL proxy is provided at https://sslsites.de/your.domain/
// By default, a cookie is set for sslsites.de which means
// other websites available over that proxy can read the cookies!

ini_set('session.cookie_path', parse_url(MY_URL, PHP_URL_PATH));

// Sessions valid for one hour
session_set_cookie_params(COOKIE_SESSION_DURATION);

session_start();

require_once('include/flight/flight/Flight.php');

require_once('tokens.php');

init_token_db();

require_once('handlers/fb_handlers.php');


Flight::route('/', 'handle_root');
Flight::route('/login', 'handle_login');
Flight::route('/fb_callback', 'handle_fb_callback');
Flight::route('/checkin', 'handle_checkin');
Flight::route('/access_code', 'handle_access_code');
Flight::route('/privacy', 'handle_privacy');
Flight::route('/rerequest_permission/', 'handle_rerequest_permission');

require_once('handlers/gw_handlers.php');

Flight::route('/ping', 'handle_ping');
Flight::route('/auth', 'handle_auth');

// Once login is done, the gateway redirects
// the user to MY_URL . 'portal'
// We don't serve this here, so use external page

Flight::route('/portal', function() { Flight::redirect(PORTAL_URL); });


Flight::start();



?>
