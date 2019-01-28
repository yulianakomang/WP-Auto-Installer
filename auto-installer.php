<?php 
/*Setup WP*/

$WEBROOT = dirname ( __FILE__ ).'/';
$MYSQLDB = 'db_wp';
$MYSQLHOST = 'localhost';
$MYSQLUSER = 'root';
$MYSQLPWD = '';
$admin_username = 'username_kamu';
$admin_password = 'password_kamu';
$admin_email = 'email_kamu';
$web_title = 'web_title_kamu';
$pbn = true;


download('http://wordpress.org/latest.zip','wp');
extract_zip('wp.zip');
recurse_copy('wordpress', $WEBROOT);
if (is_dir('wordpress')) {
    delete_folder('wordpress');        
}



$config_file = file($WEBROOT . 'wp-config-sample.php');
$secret_keys = file_get_contents( 'https://api.wordpress.org/secret-key/1.1/salt/' );
$secret_keys = explode( "\n", $secret_keys );
foreach ( $secret_keys as $k => $v ) {
    $secret_keys[$k] = substr( $v, 28, 64 );
}
array_pop($secret_keys);
$config_file = str_replace('database_name_here', $MYSQLDB, $config_file);
$config_file = str_replace('username_here', $MYSQLUSER, $config_file);
$config_file = str_replace('password_here', $MYSQLPWD, $config_file);
$config_file = str_replace('localhost', $MYSQLHOST, $config_file);
$config_file = str_replace("'AUTH_KEY',         'put your unique phrase here'", "'AUTH_KEY',         '{$secret_keys[0]}'", $config_file);
$config_file = str_replace("'SECURE_AUTH_KEY',  'put your unique phrase here'", "'SECURE_AUTH_KEY',  '{$secret_keys[1]}'", $config_file);
$config_file = str_replace("'LOGGED_IN_KEY',    'put your unique phrase here'", "'LOGGED_IN_KEY',    '{$secret_keys[2]}'", $config_file);
$config_file = str_replace("'NONCE_KEY',        'put your unique phrase here'", "'NONCE_KEY',        '{$secret_keys[3]}'", $config_file);
$config_file = str_replace("'AUTH_SALT',        'put your unique phrase here'", "'AUTH_SALT',        '{$secret_keys[4]}'", $config_file);
$config_file = str_replace("'SECURE_AUTH_SALT', 'put your unique phrase here'", "'SECURE_AUTH_SALT', '{$secret_keys[5]}'", $config_file);
$config_file = str_replace("'LOGGED_IN_SALT',   'put your unique phrase here'", "'LOGGED_IN_SALT',   '{$secret_keys[6]}'", $config_file);
$config_file = str_replace("'NONCE_SALT',       'put your unique phrase here'", "'NONCE_SALT',       '{$secret_keys[7]}'", $config_file);
if(file_exists($WEBROOT .'wp-config.php')) {
    unlink($WEBROOT .'wp-config.php');
}
$fw = fopen($WEBROOT . 'wp-config.php', "a");
foreach ( $config_file as $line_num => $line ) {
    fwrite($fw, $line);
}

/* Add Plugin PreSet */
// Yoast SEO
download('https://downloads.wordpress.org/plugin/wordpress-seo.8.3.zip','plugin-yoast');
extract_zip('plugin-yoast.zip');
recurse_copy('wordpress-seo', 'wp-content/plugins/wordpress-seo');
if (is_dir('wordpress-seo')) {
    delete_folder('wordpress-seo');        
}

if ($pbn == true) {
    // Spider Blocker
    download('https://downloads.wordpress.org/plugin/spiderblocker.1.0.19.zip','plugin-blocker');
    extract_zip('plugin-blocker.zip');
    recurse_copy('spiderblocker', 'wp-content/plugins/spiderblocker');
    if (is_dir('spiderblocker')) {
        delete_folder('spiderblocker');        
    }
    // Auto Add Thumbnail from first Image content
    download('https://downloads.wordpress.org/plugin/easy-add-thumbnail.1.1.1.zip','plugin-thumbnail');
    extract_zip('plugin-thumbnail.zip');
    recurse_copy('easy-add-thumbnail', 'wp-content/plugins/easy-add-thumbnail');
    if (is_dir('easy-add-thumbnail')) {
        delete_folder('easy-add-thumbnail');        
    }
}


// Contact 7
download('https://downloads.wordpress.org/plugin/contact-form-7.5.0.4.zip','plugin-contact');
extract_zip('plugin-contact.zip');
recurse_copy('contact-form-7', 'wp-content/plugins/contact-form-7');
if (is_dir('contact-form-7')) {
    delete_folder('contact-form-7');        
}
// AMP
download('https://downloads.wordpress.org/plugin/accelerated-mobile-pages.0.9.97.18.zip','plugin-amp');
extract_zip('plugin-amp.zip');
recurse_copy('accelerated-mobile-pages', 'wp-content/plugins/accelerated-mobile-pages');
if (is_dir('accelerated-mobile-pages')) {
    delete_folder('accelerated-mobile-pages');        
}
// Cache 
download('https://downloads.wordpress.org/plugin/w3-total-cache.0.9.7.zip','plugin-cache');
extract_zip('plugin-cache.zip');
recurse_copy('w3-total-cache', 'wp-content/plugins/w3-total-cache');
if (is_dir('w3-total-cache')) {
    delete_folder('w3-total-cache');        
}
// Compression
download('https://downloads.wordpress.org/plugin/wp-smushit.2.8.1.zip','plugin-compression');
extract_zip('plugin-compression.zip');
recurse_copy('wp-smushit', 'wp-content/plugins/wp-smushit');
if (is_dir('wp-smushit')) {
    delete_folder('wp-smushit');        
}
// Compression
download('https://downloads.wordpress.org/plugin/a3-lazy-load.1.9.1.zip','plugin-lazyload');
extract_zip('plugin-lazyload.zip');
recurse_copy('a3-lazy-load', 'wp-content/plugins/a3-lazy-load');
if (is_dir('a3-lazy-load')) {
    delete_folder('a3-lazy-load');        
}

// Delete Plugin doesn't wanted
delete_folder('wp-content/plugins/akismet');
delete_folder('wp-content/plugins/hello.php');

/* Installing Wordpress */

define('ABSPATH', $WEBROOT);
define('WP_CONTENT_DIR', 'wp-content/');
define('WPINC', 'wp-includes');
define( 'WP_LANG_DIR', WP_CONTENT_DIR . '/languages' );
define('WP_USE_THEMES', true);
define('DB_NAME', $MYSQLDB);
define('DB_USER', $MYSQLUSER);
define('DB_PASSWORD', $MYSQLPWD);
define('DB_HOST', $MYSQLHOST);
$_GET['step'] = 2;
$_POST['weblog_title'] = $web_title;
$_POST['user_name'] = $admin_username;
$_POST['admin_email'] = $admin_email;
$_POST['blog_public'] = true;
$_POST['admin_password'] = $admin_password;
$_POST['admin_password2'] = $admin_password;
require_once(ABSPATH . 'wp-admin/install.php');
require_once(ABSPATH . 'wp-load.php');
require_once(ABSPATH . WPINC . '/class-wp-walker.php');
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

/*Remove myself*/
// unlink('auto-installer.php');


function download($url='',$file)
{
    set_time_limit(0);
    //This is the file where we save the    information
    $fp = fopen (dirname(__FILE__) . '/'.$file.'.zip', 'w+');
    //Here is the file we are downloading, replace spaces with %20
    $ch = curl_init(str_replace(" ","%20",$url));
    curl_setopt($ch, CURLOPT_TIMEOUT, 50);
    // write curl response to file
    curl_setopt($ch, CURLOPT_FILE, $fp); 
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    // get curl response
    curl_exec($ch); 
    curl_close($ch);
    fclose($fp);
}

function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 

function extract_zip($zip_name='')
{
    $path = pathinfo(realpath($zip_name), PATHINFO_DIRNAME);
    $zip = new ZipArchive;
    if ($zip->open($zip_name) === TRUE) {
        $zip->extractTo($path);
        $zip->close();
    }
    // Remove zip
    unlink($zip_name);
}

function delete_folder($path)
{
    if (is_dir($path) === true)
    {
        $files = array_diff(scandir($path), array('.', '..'));

        foreach ($files as $file)
        {
            delete_folder(realpath($path) . '/' . $file);
        }

        return rmdir($path);
    }

    else if (is_file($path) === true)
    {
        return unlink($path);
    }

    return false;
}



?>