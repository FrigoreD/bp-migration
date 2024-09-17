<?php
namespace QSOFT\BizprocMigration;

/**
 * Класс для парсинга массива для передачи в файл
 */
class ArrayParser
{
    /**
     * Генерирует строку для PHP файла
     * @param array $array
     * @param string $variableName
     * @param null $level
     * @return string
     */
    public static function arrayToPhp(array $array, string $variableName = 'array', $level = null): string
    {
        $out = $margin ='';
        $nr  = "\n";
        $tab = "\t";

        if (is_null($level)) {
            $out .= '<?php' . "\n";
            $out .= '$' . $variableName . ' = ';
            if (!empty($array)) {
                $out .= self::arrayToPhp($array, $variableName, 0);
            }
            $out .= ';';
        } else {
            for ($n = 1; $n <= $level; $n++) {
                $margin .= $tab;
            }
            $level++;

            if (is_array($array)) {
                $i = 1;
                $count = count($array);
                $out .= '[' . $nr;
                foreach ($array as $key => $row) {
                    /* Для активити php-код проставляем отступы в коде */
                    if ($key === 'ExecuteCode') {
                        $row = str_replace("\t", "    ", $row);
                        $row = str_replace(PHP_EOL, PHP_EOL . $margin . "\t", $row);
                    }

                    $out .= $margin . $tab;
                    if (!is_numeric($key)) {
                        $out .= "'" . $key . "' => ";
                    }

                    if (is_array($row)) {
                        $out .= self::arrayToPhp($row, $variableName, $level);
                    } elseif (is_null($row)) {
                        $out .= 'null';
                    } elseif (!is_string($row) && is_numeric($row)) {
                        $out .= $row;
                    } else {
                        $row = htmlspecialchars_decode($row);
                        $out .= "'" . addcslashes($row, "\'\\") . "'";
                    }

                    if ($count > $i) {
                        $out .= ',';
                    }

                    $out .= $nr;
                    $i++;
                }

                $out .= $margin . ']';
            } else {
                $out .= "'" .  addcslashes($array, "\'\\") . "'";
            }
        }

        return $out;
    }


    /**
     * @param $array
     * @return void
     */
    public static function phpToArray(&$array): void
    {

        array_walk_recursive( $array, function(&$value, $key) {
            if ($key === 'ExecuteCode') {
                $value = str_replace("\t", "", $value);
            }
            $value = str_replace('\\"', '"', $value);
        });
    }

}
