<?php
namespace AFD;

/**
 * Collection of utility methods.
 *
 * @author Armin Reichert
 */
class Utils
{

    /**
     * Reads the specified property file and returns an array of the contained properties.
     *
     * @param string $filePath
     *            the property file path
     * @return array properties as an array of (key, value)-pairs
     */
    public static function readPropertyFile($filePath)
    {
        $properties = [];
        if (is_file($filePath)) {
            $file = fopen($filePath, "r");
            if (false !== $file) {
                while (($line = fgets($file)) !== false) {
                    if (preg_match('/^[#!]/', $line)) {
                        continue; // skip lines starting with '#' or '!'
                    }
                    if (($pos = strpos($line, '=')) !== false) {
                        $key = trim(substr($line, 0, $pos));
                        $value = trim(substr($line, $pos + 1));
                        if (! empty($key)) {
                            $properties[$key] = $value;
                        }
                    }
                }
                fclose($file);
            }
        }
        return $properties;
    }

    /**
     * Reads a CSV file into an array.
     * The first line of the file should contain the column IDs.
     *
     * @param string $filePath
     *            path to the CSV file
     * @return array content as array of line arrays
     */
    public static function readCsvFile($filePath)
    {
        $rows = [];
        $rowIndex = 0;
        $file = fopen($filePath, "r");
        if (false !== $file) {
            while (false !== ($line = fgets($file))) {
                $row = preg_split('/,/', trim($line));
                if ($rowIndex == 0) {
                    $columns = $row;
                } else {
                    $newRow = [];
                    for ($i = 0; $i < count($columns); ++ $i) {
                        $newRow[$columns[$i]] = trim($row[$i]);
                    }
                    $rows[$newRow['ID']] = $newRow;
                }
                ++ $rowIndex;
            }
            fclose($file);
        }
        return $rows;
    }

    /**
     * Returns a random entry from the given array.
     *
     * @param array $array
     *            any array
     * @return random entry or NULL if the array is empty
     */
    public static function randomArrayEntry(array $array)
    {
        return empty($array) ? null : $array[mt_rand(0, count($array) - 1)];
    }

    /**
     * Returns a random entry from the given directory.
     *
     * @param string $dir
     *            a directory
     * @param boolean $onlyFiles
     *            if only files, not subdirectories, should be considered
     * @return real path of random directory entry or <code>null</code>
     */
    public static function randomDirEntry($dir, $onlyFiles = true)
    {
        $dirEntries = array();
        $directoryIterator = new \DirectoryIterator($dir);
        foreach ($directoryIterator as $entry) {
            if (! $entry->isDot() && ($entry->isFile() || ($entry->isDir() && ! $onlyFiles))) {
                $dirEntries[] = $entry->getRealPath();
            }
        }
        return self::randomArrayEntry($dirEntries);
    }

    /**
     * Returns the paths of all files in a given directory with filename matching given pattern.
     *
     * @param string $dir
     *            path to directory
     * @param string $pattern
     *            regex for filename
     * @return array(string) array of paths of matching files
     */
    public static function getAllFiles($dir, $pattern = '/.*/')
    {
        $dirEntries = [];
        $directoryIterator = new \DirectoryIterator($dir);
        foreach ($directoryIterator as $entry) {
            if (! $entry->isDot() && $entry->isFile() && preg_match($pattern, $entry->getFilename())) {
                $dirEntries[] = $entry->getRealPath();
            }
        }
        return $dirEntries;
    }

    /**
     * Computes age in years for given birthdate (format 'dd.mm.YYYY').
     *
     * @param string $birthdate
     *            birthdate formatted like '03.09.1966' ('d.m.Y')
     * @return integer age
     */
    public static function computeAge($birthdate)
    {
        list ($d, $m, $y) = explode('.', date('d.m.Y'));
        list ($bd, $bm, $by) = explode('.', $birthdate);
        if ($bm > $m) {
            return $y - $by - 1;
        }
        if ($bm < $m) {
            return $y - $by;
        }
        if ($bd > $d) {
            return $y - $by - 1;
        }
        return $y - $by;
    }

    /**
     * Checks if the given path points to a file/directory under the given root path.
     *
     * @param string $path
     *            path
     * @param string $path
     *            root path
     * @return boolean tells if the given path is under the root path
     */
    public static function isUnder($path, $rootPath)
    {
        $path = realpath($path);
        if (false === $path) {
            throw new \Exception("Invalid path: " . $path);
        }
        $rootPath = realpath($rootPath);
        if (false === $rootPath) {
            throw new \Exception("Invalid path: " . $rootPath);
        }
        return 0 === strpos($path, $rootPath);
    }
}
