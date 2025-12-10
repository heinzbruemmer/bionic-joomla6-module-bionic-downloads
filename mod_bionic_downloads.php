<?php
defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;

require_once __DIR__ . '/helper.php';

// AJAX-Request für Download-Counter
$app = Factory::getApplication();
$input = $app->getInput();

if ($input->get('task') === 'bionic_download_count') {
    $folder = $input->getString('folder', '');
    $file = $input->getString('file', '');
    
    if ($folder && $file) {
        $count = ModBionicDownloadsHelper::incrementDownload($folder, $file);
        echo json_encode(['success' => true, 'count' => $count]);
        $app->close();
    }
}

// Parameter
$folderPath = $params->get('folder_path', 'images/webpdf');
$sortOrder = $params->get('sort_order', 'name_asc');

// Dateien laden
$files = ModBionicDownloadsHelper::getFiles($folderPath, $sortOrder);

// Modul-ID für eindeutige IDs
$moduleId = $module->id;

// Template laden
require ModuleHelper::getLayoutPath('mod_bionic_downloads', $params->get('layout', 'default'));
