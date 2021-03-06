<?php

namespace Studentlist\Helpers;

use Studentlist\Exceptions\ConfigException;

/**
 * Class Util
 * Collection of utility and security functions
 * @package Studentlist\Helpers
 */
class Util
{
    /**
     * @param $JSONpath
     * @return array
     * @throws ConfigException
     */
    public static function readJSON($JSONpath): array
    {
        if (!file_exists($JSONpath)) {
            throw new ConfigException('Файл конфигурации не существует');
        }
        $fileContent = file_get_contents($JSONpath);
        $fileContent = json_decode($fileContent, true);
        if ($fileContent == null) {
            throw new ConfigException('Ошибка в файле конфигурации. Ошибка: ' . json_last_error_msg());
        }
        return $fileContent;
    }

    /**
     * @param int $length
     * @return string
     * @throws \Exception
     */
    public static function generateToken(int $length = 16): string
    {
        return $token = bin2hex(random_bytes($length));
    }

    /**
     * @param string $str
     * @return string
     */
    public static function mbUcfirst(string $str): string
    {
        return mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1, mb_strlen($str));
    }

    /**
     * @return string
     * @throws \Exception
     */
    public static function setCSRFToken(): string
    {
        $token = isset($_POST['CSRFToken']) ? strval($_POST['CSRFToken']) : self::generateToken();
        setcookie('CSRFToken', $token, strtotime('2 hours'), '/', null, false, true);
        return $token;
    }

    /**
     * @return bool
     */
    public static function checkCSRFToken(): bool
    {
        if (!isset($_COOKIE['CSRFToken']) || !isset($_POST['CSRFToken']) || $_COOKIE['CSRFToken'] !== $_POST['CSRFToken'] || ($_COOKIE['CSRFToken'] == null && $_POST['CSRFToken'] == null)) {
            return false;
        }
        return true;
    }
}