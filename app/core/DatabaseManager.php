<?php

namespace Europa\Core;

class DatabaseManager
{
    public static $IS_ERROR = false;
    public static $INSTANCE = null;
    public static $IS_INIT = false;

    public static function init($exceptionMode = true)
    {
        self::checkError();

        if (isset(\Europa\Core\Kernel::$DATABASE['port']) && \Europa\Core\Kernel::$DATABASE['port'] != null) {
            $dsn = "mysql:dbname=" . \Europa\Core\Kernel::$DATABASE['database'] . ";host=" . \Europa\Core\Kernel::$DATABASE['host'] . ";port=" . \Europa\Core\Kernel::$DATABASE['port'];
        } else {
            $dsn = "mysql:dbname=" . \Europa\Core\Kernel::$DATABASE['database'] . ";host=" . \Europa\Core\Kernel::$DATABASE['host'];
        }
        $a = array();
        if ($exceptionMode) {
            $a = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);
        }
        self::$INSTANCE = new \PDO($dsn, \Europa\Core\Kernel::$DATABASE['username'], \Europa\Core\Kernel::$DATABASE['password'], $a);
        self::$INSTANCE->query('SET NAMES "utf8";');
        self::$IS_INIT = true;
    }

    public static function setError()
    {
        self::$IS_ERROR = "Vous n'avez pas renseigné suffisamment d'informations pour initialiser la base de données";
    }

    public static function checkError()
    {
        if (self::$IS_ERROR) {
            throw new \Europa\Core\Exceptions\CoreException((gettype(self::$IS_ERROR) === 'string') ? self::$IS_ERROR : "Une erreur est survenue lors de l'initialisation (Europa\Core\DatabaseManager)");
        }
    }

    public static function SQL($command, $arguments = null, $autoFetch = true)
    {
        if (!self::$IS_INIT) {
            self::init();
        }
        $req = self::$INSTANCE->prepare($command);

        try {
            $exec = $req->execute($arguments);
        } catch (\PDOException $e) {
            $exec = null;
        }

        $errInfo = $req->errorInfo();
        $errInfo = array("SQLSTATE" => $errInfo[0], "errorCode" => $errInfo[1], "message" => $errInfo[2]);

        if (!$autoFetch) {
            return array(
                "success" => $exec ? $exec : false,
                "request" => $req->queryString,
                "error" => $errInfo,
                "lastInsertId" => self::$INSTANCE->lastInsertId()
            );
        }
        $f = self::fetchSQL(array($exec, $req));
        return array(
            "success" => $exec ? $exec : false,
            "request" => $req->queryString,
            "error" => $errInfo,
            "lastInsertId" => self::$INSTANCE->lastInsertId(),
            "results" => $f
        );
    }

    public static function fetchSQL($reqArray)
    {
        if (!self::$IS_INIT) {
            self::init();
        }
        return $reqArray[1]->fetchAll(\PDO::FETCH_ASSOC);
    }


    private static function verify($string)
    {
        if (!preg_match("#^([A-Za-z0-9 @\_\-\.\+\,\'\&\n\r\;\:\!\?\=ÂÊÎÔÛâêûîôÄËÜÏÖäëüïöùàèéÈÉÇËÌÍœ©€\(\)\"\/])+$#i", $string) && !preg_match("#^$#", $string) || preg_match('#DELETE |ALTER |INSERT |SET |WHERE |INTO |FROM |DROP | \-\-|\'\;|\;|\##', $string)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function validateValues($parameter, $errorMsg = null)
    {
        if (is_numeric($parameter)) {
            if ($parameter > 1000000) {
                throw new \Exception($errorMsg ? $errorMsg : "Le nombre saisi excède l'espace mémoire disponible.");
            }
        } else if (\gettype($parameter) == 'string') {
            if (self::verify($parameter)) {
                throw new \Exception($errorMsg ? $errorMsg : "La valeur saisie est incorrecte.");
            }
        } else if (\gettype($parameter) == 'NULL' || \gettype($parameter) == null) {
            return true;
        } else if (\gettype($parameter) == 'array') {
            \array_walk_recursive($parameter, function ($value, $key) {
                if (\gettype($value) == 'integer') {
                    if ($value > 1000000) {
                        throw new \Exception($errorMsg ? $errorMsg : "Le nombre saisi ($key) excède l'espace mémoire disponible.");
                    }
                } else if (\gettype($value) == 'string') {
                    if (self::verify($value)) {
                        //var_dump($value);
                        throw new \Exception($errorMsg ? $errorMsg : "La valeur saisie (" . $key . ") est incorrecte.");
                    }
                } else if (\gettype($value) == 'NULL' || \gettype($value) == null) {
                    return true;
                } else {
                    throw new \Exception("Aucun filtre n'est prévu pour traiter le type " . \gettype($value) . " ($key)");
                }
            });
        } else {
            throw new \Exception("Aucun filtre n'est prévu pour traiter le type " . \gettype($parameter));
        }
        return true;
    }

    public static function sanitizeArray(array $arr, array $model, $strictMode = true, $autoValidate = true): array
    {
        if ($autoValidate) {
            self::validateValues($autoValidate);
        }
        foreach ($model as $key => $value) {
            if (!isset($arr[$key])) {
                if ($value === true) {
                    throw new \Exception("Certaines informations essentielles n'ont pas été saisies ($key).");
                } else if ($value !== false) {
                    $arr[$key] = $value;
                } else if ($value === null) {
                    $arr[$key] = null;
                }
            } else {
                if ($arr[$key] == "") {
                    $arr[$key] = $value;
                } else if ($value === null) {
                } else if ($value === false) {
                    unset($arr[$key]);
                }
            }
        }
        if ($strictMode) {
            foreach ($arr as $key => $value) {
                if (!array_key_exists($key, $model)) {
                    unset($arr[$key]);
                }
            }
        }
        return $arr;
    }

    public static function slugify($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        if (empty($text)) {
            return '';
        }
        return $text;
    }
}
