<?php
/**
 * Вызываемая функция задания, реализующая поиск значения по ключу в текстовом файле
 *
 * @param string $filename Имя файла
 * @param string $key Значение ключа
 * @return string Результат поиска
 */
function findInFile(string $filename, string $key)
{
    $file = new SplFileObject($filename);
    $file->seek(PHP_INT_MAX);
    $linesTotal = $file->key() + 1;
    return trim(fileBinSearch($file, $key, 0, $linesTotal));
}

/**
 * Основная функция бинарного поиска
 *
 * @param SplFileObject $file
 * @param string $fkey Значение ключа
 * @param int $begin Номер начальной строки для поиска
 * @param int $end Номер конечной строки для поиска
 * @return string Возвращаемый результат: значение, соответствующее ключу, если не найдено: undef
 */
function fileBinSearch(SplFileObject $file, string $fkey, int $begin, int $end)
{
    if (abs($begin - $end) == 1) {
        $file = null;
        return 'undef';
    }
    $middle = floor(($begin + $end) / 2);
    $file->seek($middle);
    $currentLine = explode("\t", $file->current());
    $key = $currentLine[0];
    $value = $currentLine[1];
    if ($fkey == $key) {
        $file = null;
        return $value;
    }
    if ($key < $fkey) {
        return fileBinSearch($file, $fkey, $middle, $end);
    } else {
        return fileBinSearch($file, $fkey, $begin, $middle);
    }
}