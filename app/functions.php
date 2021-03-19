<?php

use App\Services\Session;

/**
 * Skip accents in string
 * @param string $str
 * @param string $charset
 * @return string
 */
function skip_accents(string $str, string $charset = 'utf-8'): string
{
    $str = trim($str);
    $str = htmlentities($str, ENT_NOQUOTES, $charset);

    $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
    $str = preg_replace('#&[^;]+;#', '', $str);
    $str = preg_replace('/[^A-Za-z0-9\-]/', ' ', $str);

    return $str;
}

/**
 * custom var_dump() values
 * @param mixed $values
 * @return void
 */
function dump(...$values)
{
    echo "<pre style='border-radius: .175rem;
            background-color: #efefef;
            color: #70AC9B;
            box-shadow: .1rem .22rem .3rem 0 rgba(175,190,180,.4);
            padding: 10px 1.95rem;
            color: #2f718d;
            border-radius: 5px;
            min-width: 50%;
            max-width: 1200px;
            width: auto;
            box-sizing: content-box;
            height: auto;
            min-height: 5vh;
            overflow-x: scroll;
            text-align: justify;
            margin : 10px auto;
            white-space: pre-wrap;
            white-space: -moz-pre-wrap;
            white-space: -pre-wrap;
            white-space: -o-pre-wrap;
            word-wrap: break-word;'
            >";
    var_dump(func_get_args());
    echo "</pre>";
}

/**
 * dump() values and die
 * @param [type] $values
 * @return void
 */
function dd(...$values)
{
    dump(func_get_args());
    die();
}

/**
 * Refresh page
 * @return void
 */
function refresh_page()
{
    header('refresh:0');
}

/**
 * Redirect to url
 * @param [type] $url
 * @return void
 */
function redirect($url)
{
    header("location:$url");
}

/**
 * Get templates path form /views
 * @param string $path
 * @return string
 */
function get_template(string $path): string
{
    return "../views/{$path}.php";
}

/**
 * Get stylesheets form /assets
 * @param string $path
 * @return string
 */
function get_stylesheet(string $path): string
{
    return "<link href='assets/css/{$path}.css' rel='stylesheet'>\n";
}

/**
 * Get path of vendor files from /assets
 * @param string $path
 * @return string
 */
function get_vendor(string $path): string
{
    return "assets/vendor/{$path}";
}

/**
 * Get path of images from /assets
 * @param string $path
 * @return string
 */
function get_image(string $path): string
{
    return "assets/img/{$path}";
}

/**
 * Get JS script form /assets
 * @param string $path
 * @return string
 */
function get_script(string $path): string
{
    return "<script src=\"assets/js/{$path}.js\"></script>\n";
}

/**
 * Encrypt the password
 * @param string $pass
 * @return string
 */
function encrypt_password(string $pass): string
{
    return password_hash($pass, PASSWORD_ARGON2I);
}

/**
 * Check if the password match with pattern
 * @param string $pass
 * @return boolean
 */
function pass_valid(string $pass): bool
{
    return preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]){8,}#', $pass) ? true : false;
}

/**
 * check if email is valid
 * @param string $email
 * @return boolean
 */
function email_valid(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;
}

/**
 * Generate url
 * @param string $name
 * @param array $params
 * @return string
 */
function generate_url(string $name, array $params = []): string
{
    $routes = require 'config/routes.php';
    foreach ($routes as $value) {
        if (array_key_exists($name, $value) === true) {
            $path = $value[$name]['path'];
            if (count($params) > 0) {
                foreach ($params as $k => $p) {
                    if (preg_match("#:{$k}#", $path)) {
                        $path = preg_replace("#:{$k}#", $p, $path);
                    }
                }
            }
        }
    }
    return $path;
}

/**
 * Generate a csrf token for form
 * @return string
 */
function generate_csrf(): string
{
    $session = new Session();
    // Check if a token is present for the current session
    $token = $session->get('csrf_token');
    if ($token === null) {
        // No token present, generate a new one
        $token = generate_token(50);
        $session->set('csrf_token', $token);
        ?> <script> localStorage.setItem('csrf_token', '<?php echo $token ?>'); </script> <?php
}
    return $token;
}

/**
 * Generate a token
 * @param integer $length
 * @return string
 */
function generate_token(int $length): string
{
    $char_to_shuffle = 'azertyuiopqsdfghjklwxcvbnAZERTYUIOPQSDFGHJKLLMWXCVBN1234567890';
    return substr(str_shuffle($char_to_shuffle), 0, $length) . (new \DateTime)->format('YmwdHsiu');
}

/**
 * Generate a filename
 * @return string
 */
function generate_filename(): string
{
    $char_to_shuffle = 'azertyuiopqsdfghjklwxcvbnAZERTYUIOPQSDFGHJKLLMWXCVBN1234567890';
    return substr(str_shuffle($char_to_shuffle), 0, 20);
}

/**
 * Transform array to string for strip_tags
 * @param array $data
 * @return string
 */
function array_to_string(array $data): string
{
    $str = '';
    foreach ($data as $v) {
        $str .= "&lt;{$v}&gt;";
    }
    return $str;
}

/**
 * init_session
 *
 * @return mixed
 */
function init_session()
{
    if (session_status() === PHP_SESSION_NONE || session_status() === PHP_SESSION_DISABLED) {
        return session_start();
    }
    return session_start();
}

/**
 * french date
 *
 * @param string $date
 * @return string
 */
function fr_date(string $date = ''): string
{
    return utf8_encode(strftime("%d %B %Y", strtotime($date ?? (new DateTime())->format('d/m/Y à H:m'))));
}

/**
 * date diff
 * @param string $date
 * @return string
 */
function diff(string $date): string
{
    $now = new DateTime('now');
    $date = new DateTime($date);
    $diff = $date->diff($now, false);
    $str = 'il y a ';
    if ($diff->y > 0) {
        return $str . "{$diff->y} " . ($diff->y === 1 ? "an" : "ans");
    }

    if ($diff->m > 0) {
        return $str . "{$diff->m} mois";
    }

    if ($diff->d > 0) {
        return " $str {$diff->d} " . ($diff->d === 1 ? "jour" : "jours");
    }

    if ($diff->h > 0) {
        return " $str {$diff->h} " . ($diff->h === 1 ? "heure" : "heures");
    }

    if ($diff->i > 0) {
        return " $str{$diff->i} " . ($diff->i === 1 ? "minute" : "minutes");
    }

    return "à l'instant";
}

/**
 * now
 * @return string
 */
function now(): string
{
    return (new DateTime('now'))->format('Y-m-d H:i:s');
}