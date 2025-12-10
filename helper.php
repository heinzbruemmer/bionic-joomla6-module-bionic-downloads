<?php
defined('_JEXEC') or die;

class ModBionicDownloadsHelper
{
    /**
     * PDFs aus dem Ordner laden
     */
    public static function getFiles($folderPath, $sortOrder = 'name_asc')
    {
        $fullPath = JPATH_ROOT . '/' . trim($folderPath, '/');
        
        if (!is_dir($fullPath)) {
            return [];
        }
        
        $files = [];
        $descriptions = self::loadDescriptions($fullPath);
        $downloads = self::loadDownloads($fullPath);
        
        foreach (glob($fullPath . '/*.pdf') as $file) {
            $filename = basename($file);
            $files[] = [
                'name'        => $filename,
                'path'        => $folderPath . '/' . $filename,
                'fullpath'    => $file,
                'size'        => filesize($file),
                'size_formatted' => self::formatSize(filesize($file)),
                'date'        => filemtime($file),
                'date_formatted' => date('d.m.Y', filemtime($file)),
                'description' => $descriptions[$filename] ?? '',
                'downloads'   => $downloads[$filename] ?? 0,
            ];
        }
        
        // Sortierung
        usort($files, function($a, $b) use ($sortOrder) {
            switch ($sortOrder) {
                case 'name_desc':
                    return strcasecmp($b['name'], $a['name']);
                case 'size_asc':
                    return $a['size'] - $b['size'];
                case 'size_desc':
                    return $b['size'] - $a['size'];
                case 'date_asc':
                    return $a['date'] - $b['date'];
                case 'date_desc':
                    return $b['date'] - $a['date'];
                case 'name_asc':
                default:
                    return strcasecmp($a['name'], $b['name']);
            }
        });
        
        return $files;
    }
    
    /**
     * Beschreibungen aus JSON laden
     */
    private static function loadDescriptions($folderPath)
    {
        $jsonFile = $folderPath . '/descriptions.json';
        
        if (!file_exists($jsonFile)) {
            return [];
        }
        
        $content = file_get_contents($jsonFile);
        $data = json_decode($content, true);
        
        return is_array($data) ? $data : [];
    }
    
    /**
     * Download-Counter laden
     */
    private static function loadDownloads($folderPath)
    {
        $jsonFile = $folderPath . '/downloads.json';
        
        if (!file_exists($jsonFile)) {
            return [];
        }
        
        $content = file_get_contents($jsonFile);
        $data = json_decode($content, true);
        
        return is_array($data) ? $data : [];
    }
    
    /**
     * Download-Counter erhöhen
     */
    public static function incrementDownload($folderPath, $filename)
    {
        $fullPath = JPATH_ROOT . '/' . trim($folderPath, '/');
        $jsonFile = $fullPath . '/downloads.json';
        
        $downloads = self::loadDownloads($fullPath);
        $downloads[$filename] = ($downloads[$filename] ?? 0) + 1;
        
        file_put_contents($jsonFile, json_encode($downloads, JSON_PRETTY_PRINT));
        
        return $downloads[$filename];
    }
    
    /**
     * Dateigröße formatieren
     */
    private static function formatSize($bytes)
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 1, ',', '.') . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 0, ',', '.') . ' KB';
        }
        return $bytes . ' B';
    }
}
