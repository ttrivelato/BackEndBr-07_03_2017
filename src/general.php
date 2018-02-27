<?php
require_once(__DIR__ . '/../global.php');

/**
 * Classe de general do sistema
 * 
 *
 * @category  Class
 * @access    public
 * @package   Curl
 * @version   0.1
 * @author    L22
 * @author    Thiago Trivelato <ttrivelato@layer7.com.br>
 * @see       http://www.layer7.com.br
 * @copyright Copyright (c)
 */

class general
{
    public function randomString($length) {
        $keys = array_merge(range(0,9), range('a', 'z'));

        $key = "";
        for($i=0; $i < $length; $i++) {
            $key .= $keys[mt_rand(0, count($keys) - 1)];
        }
        return $key;
    }
    
    function randomFloat($min, $max) {
        return number_format(random_int($min, $max - 1) + (random_int(0, PHP_INT_MAX - 1) / PHP_INT_MAX ), 2, ',', '.');
    }
}