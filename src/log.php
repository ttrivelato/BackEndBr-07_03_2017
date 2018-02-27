<?php
require_once(__DIR__ . '/../global.php');

/**
 * Classe de logs do sistema
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

//Set timezone
date_default_timezone_set('America/Sao_Paulo');

/**
 * Classe para log
 * com log em log/
 * 
 * @access public
 */

class log
{
    public function logar($text, $folder = '')
    {
        $log = date(DATE_RFC822) . ' - ' . preg_replace('/\s+/', ' ', trim($text)) . "\n";
        $logFile = PROD_PATH . 'log/' . $folder . date('Ymd') . '.log';

        $folder = dirname($logFile);
        $explFolder = explode('/', $folder);
        $currentAll = '';
        foreach ($explFolder as $key => $current)
        {
            if ($key == 0) continue;

            $currentAll .= '/' . $current;
            if (!file_exists($currentAll))
                mkdir($currentAll);
        }
        
        file_put_contents($logFile, $log, FILE_APPEND | LOCK_EX);
    }
}