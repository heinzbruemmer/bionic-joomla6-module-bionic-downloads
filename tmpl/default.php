<?php
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

// Sprachdatei laden
$lang = JFactory::getLanguage();
$lang->load('mod_bionic_downloads', JPATH_SITE . '/modules/mod_bionic_downloads');

// Parameter
$tableTitle      = $params->get('table_title', 'Downloads');
$showDownloads   = (bool) $params->get('show_downloads', 1);
$showDescription = (bool) $params->get('show_description', 1);
$showSize        = (bool) $params->get('show_size', 1);
$showDate        = (bool) $params->get('show_date', 0);

// Styling
$bgColor     = $params->get('bg_color', '#1a1a2e');
$headerBg    = $params->get('header_bg', '#16213e');
$textColor   = $params->get('text_color', '#ffffff');
$accentColor = $params->get('accent_color', '#0d6efd');
$hoverColor  = $params->get('hover_color', '#0f3460');

$baseUrl = Uri::root();
$uniqueId = 'bionic-dl-' . $moduleId;

// Sprachstrings
$txtFile     = Text::_('MOD_BIONIC_DOWNLOADS_FILE');
$txtDesc     = Text::_('MOD_BIONIC_DOWNLOADS_DESCRIPTION');
$txtSize     = Text::_('MOD_BIONIC_DOWNLOADS_SIZE');
$txtDate     = Text::_('MOD_BIONIC_DOWNLOADS_DATE');
$txtCount    = Text::_('MOD_BIONIC_DOWNLOADS_COUNT');
$txtActions  = Text::_('MOD_BIONIC_DOWNLOADS_ACTIONS');
$txtView     = Text::_('MOD_BIONIC_DOWNLOADS_VIEW');
$txtDownload = Text::_('MOD_BIONIC_DOWNLOADS_DOWNLOAD');
$txtClose    = Text::_('MOD_BIONIC_DOWNLOADS_CLOSE');

if (empty($files)) {
    $noFilesMsg = Text::sprintf('MOD_BIONIC_DOWNLOADS_NO_FILES', htmlspecialchars($folderPath));
    echo '<div style="padding:20px;background:#f8d7da;color:#721c24;border-radius:8px;">' . $noFilesMsg . '</div>';
    return;
}
?>

<style>
#<?php echo $uniqueId; ?> {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}
/* Scrollbar Styling */
#<?php echo $uniqueId; ?> ::-webkit-scrollbar {
    width: 12px;
    height: 12px;
}
#<?php echo $uniqueId; ?> ::-webkit-scrollbar-track {
    background: #e0e0e0;
}
#<?php echo $uniqueId; ?> ::-webkit-scrollbar-thumb {
    background: <?php echo $accentColor; ?>;
    border-radius: 6px;
}
#<?php echo $uniqueId; ?> ::-webkit-scrollbar-thumb:hover {
    background: #0b5ed7;
}
#<?php echo $uniqueId; ?> .dl-container {
    background: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
#<?php echo $uniqueId; ?> .dl-header {
    background: <?php echo $headerBg; ?>;
    color: #ffffff;
    padding: 15px 20px;
    font-size: 1.2em;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}
#<?php echo $uniqueId; ?> .dl-header svg {
    width: 24px;
    height: 24px;
    fill: #ffffff;
}
#<?php echo $uniqueId; ?> .table-wrapper {
    overflow-x: auto;
}
#<?php echo $uniqueId; ?> table {
    width: 100%;
    border-collapse: collapse;
    background: #ffffff;
    min-width: 500px;
}
#<?php echo $uniqueId; ?> th {
    background: <?php echo $headerBg; ?>;
    color: #ffffff;
    padding: 12px 15px;
    text-align: left;
    font-weight: 500;
    font-size: 0.85em;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid <?php echo $accentColor; ?>;
    white-space: nowrap;
}
#<?php echo $uniqueId; ?> td {
    padding: 12px 15px;
    color: #222222 !important;
    border-bottom: 1px solid #dddddd;
    vertical-align: middle;
}
#<?php echo $uniqueId; ?> tbody tr:nth-child(odd) td {
    background: #f5f5f5 !important;
}
#<?php echo $uniqueId; ?> tbody tr:nth-child(even) td {
    background: #e8e8e8 !important;
}
#<?php echo $uniqueId; ?> tbody tr:hover td {
    background: #d0e4ff !important;
}
#<?php echo $uniqueId; ?> .dl-icon {
    width: 40px;
    text-align: center;
}
#<?php echo $uniqueId; ?> .dl-icon svg {
    width: 28px;
    height: 28px;
    fill: #e74c3c;
}
#<?php echo $uniqueId; ?> .dl-name {
    font-weight: 500;
    color: #111111 !important;
    word-break: break-word;
}
#<?php echo $uniqueId; ?> .dl-desc {
    color: #555555 !important;
    font-size: 0.9em;
}
#<?php echo $uniqueId; ?> .dl-size,
#<?php echo $uniqueId; ?> .dl-date,
#<?php echo $uniqueId; ?> .dl-count {
    text-align: center;
    color: #444444 !important;
    font-size: 0.9em;
    white-space: nowrap;
}
#<?php echo $uniqueId; ?> .dl-actions {
    text-align: center;
    white-space: nowrap;
}
#<?php echo $uniqueId; ?> .dl-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    margin: 0 3px;
}
#<?php echo $uniqueId; ?> .dl-btn svg {
    width: 18px;
    height: 18px;
    fill: #fff;
}
#<?php echo $uniqueId; ?> .dl-btn-view {
    background: <?php echo $accentColor; ?>;
}
#<?php echo $uniqueId; ?> .dl-btn-view:hover {
    background: #0b5ed7;
    transform: scale(1.1);
}
#<?php echo $uniqueId; ?> .dl-btn-download {
    background: #198754;
}
#<?php echo $uniqueId; ?> .dl-btn-download:hover {
    background: #157347;
    transform: scale(1.1);
}

/* Modal */
#<?php echo $uniqueId; ?>-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.9);
    z-index: 10000;
    align-items: center;
    justify-content: center;
}
#<?php echo $uniqueId; ?>-modal.active {
    display: flex;
}
#<?php echo $uniqueId; ?>-modal .modal-content {
    background: #ffffff;
    border-radius: 12px;
    width: 95%;
    max-width: 1200px;
    height: 90%;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.5);
}
#<?php echo $uniqueId; ?>-modal .modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px 20px;
    background: <?php echo $headerBg; ?>;
    color: #ffffff;
    border-bottom: 2px solid <?php echo $accentColor; ?>;
    flex-wrap: wrap;
    gap: 10px;
}
#<?php echo $uniqueId; ?>-modal .modal-title {
    font-size: 1.1em;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
    word-break: break-word;
    flex: 1;
    min-width: 200px;
}
#<?php echo $uniqueId; ?>-modal .modal-title svg {
    width: 24px;
    height: 24px;
    fill: #e74c3c;
    flex-shrink: 0;
}
#<?php echo $uniqueId; ?>-modal .modal-actions {
    display: flex;
    gap: 10px;
    flex-shrink: 0;
}
#<?php echo $uniqueId; ?>-modal .modal-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9em;
    color: #fff;
    transition: all 0.2s;
    white-space: nowrap;
}
#<?php echo $uniqueId; ?>-modal .modal-btn svg {
    width: 16px;
    height: 16px;
    fill: #fff;
}
#<?php echo $uniqueId; ?>-modal .modal-btn-download {
    background: #198754;
}
#<?php echo $uniqueId; ?>-modal .modal-btn-download:hover {
    background: #157347;
}
#<?php echo $uniqueId; ?>-modal .modal-btn-close {
    background: #6c757d;
}
#<?php echo $uniqueId; ?>-modal .modal-btn-close:hover {
    background: #5c636a;
}
#<?php echo $uniqueId; ?>-modal .modal-body {
    flex: 1;
    overflow: hidden;
}
#<?php echo $uniqueId; ?>-modal iframe {
    width: 100%;
    height: 100%;
    border: none;
}

/* Responsive */
@media (max-width: 768px) {
    #<?php echo $uniqueId; ?> .dl-header {
        padding: 12px 15px;
        font-size: 1em;
    }
    #<?php echo $uniqueId; ?> th,
    #<?php echo $uniqueId; ?> td {
        padding: 10px 8px;
        font-size: 0.85em;
    }
    #<?php echo $uniqueId; ?> .dl-icon {
        width: 30px;
    }
    #<?php echo $uniqueId; ?> .dl-icon svg {
        width: 22px;
        height: 22px;
    }
    #<?php echo $uniqueId; ?> .dl-btn {
        width: 32px;
        height: 32px;
        margin: 0 2px;
    }
    #<?php echo $uniqueId; ?> .dl-btn svg {
        width: 16px;
        height: 16px;
    }
    #<?php echo $uniqueId; ?>-modal .modal-content {
        width: 98%;
        height: 95%;
        border-radius: 8px;
    }
    #<?php echo $uniqueId; ?>-modal .modal-header {
        padding: 10px 15px;
    }
    #<?php echo $uniqueId; ?>-modal .modal-title {
        font-size: 0.95em;
        min-width: 150px;
    }
    #<?php echo $uniqueId; ?>-modal .modal-btn {
        padding: 6px 12px;
        font-size: 0.85em;
    }
    #<?php echo $uniqueId; ?>-modal .modal-btn span {
        display: none;
    }
}

@media (max-width: 480px) {
    #<?php echo $uniqueId; ?> .dl-desc-col,
    #<?php echo $uniqueId; ?> .dl-date-col,
    #<?php echo $uniqueId; ?> .dl-count-col {
        display: none;
    }
    #<?php echo $uniqueId; ?> table {
        min-width: 300px;
    }
}
</style>

<div id="<?php echo $uniqueId; ?>">
    <div class="dl-container">
        <div class="dl-header">
            <svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
            <?php echo htmlspecialchars($tableTitle); ?>
        </div>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width:40px;"></th>
                        <th><?php echo $txtFile; ?></th>
                        <?php if ($showDescription): ?><th class="dl-desc-col"><?php echo $txtDesc; ?></th><?php endif; ?>
                        <?php if ($showSize): ?><th style="width:100px;"><?php echo $txtSize; ?></th><?php endif; ?>
                        <?php if ($showDate): ?><th class="dl-date-col" style="width:100px;"><?php echo $txtDate; ?></th><?php endif; ?>
                        <?php if ($showDownloads): ?><th class="dl-count-col" style="width:80px;">⬇</th><?php endif; ?>
                        <th style="width:100px;"><?php echo $txtActions; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($files as $file): ?>
                    <tr>
                        <td class="dl-icon">
                            <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 2l5 5h-5V4zM6 20V4h6v6h6v10H6z"/></svg>
                        </td>
                        <td class="dl-name"><?php echo htmlspecialchars(pathinfo($file['name'], PATHINFO_FILENAME)); ?></td>
                        <?php if ($showDescription): ?>
                        <td class="dl-desc dl-desc-col"><?php echo htmlspecialchars($file['description']); ?></td>
                        <?php endif; ?>
                        <?php if ($showSize): ?>
                        <td class="dl-size"><?php echo $file['size_formatted']; ?></td>
                        <?php endif; ?>
                        <?php if ($showDate): ?>
                        <td class="dl-date dl-date-col"><?php echo $file['date_formatted']; ?></td>
                        <?php endif; ?>
                        <?php if ($showDownloads): ?>
                        <td class="dl-count dl-count-col" data-file="<?php echo htmlspecialchars($file['name']); ?>"><?php echo $file['downloads']; ?></td>
                        <?php endif; ?>
                        <td class="dl-actions">
                            <button class="dl-btn dl-btn-view" onclick="bionicDlView<?php echo $moduleId; ?>('<?php echo $baseUrl . $file['path']; ?>', '<?php echo htmlspecialchars($file['name']); ?>')" title="<?php echo $txtView; ?>">
                                <svg viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                            </button>
                            <button class="dl-btn dl-btn-download" onclick="bionicDlDownload<?php echo $moduleId; ?>('<?php echo $baseUrl . $file['path']; ?>', '<?php echo htmlspecialchars($file['name']); ?>')" title="<?php echo $txtDownload; ?>">
                                <svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="<?php echo $uniqueId; ?>-modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">
                <svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm-1 2l5 5h-5V4zM6 20V4h6v6h6v10H6z"/></svg>
                <span id="<?php echo $uniqueId; ?>-modal-filename"></span>
            </div>
            <div class="modal-actions">
                <button class="modal-btn modal-btn-download" id="<?php echo $uniqueId; ?>-modal-dl">
                    <svg viewBox="0 0 24 24"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                    <span><?php echo $txtDownload; ?></span>
                </button>
                <button class="modal-btn modal-btn-close" onclick="bionicDlClose<?php echo $moduleId; ?>()">
                    <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                    <span><?php echo $txtClose; ?></span>
                </button>
            </div>
        </div>
        <div class="modal-body">
            <iframe id="<?php echo $uniqueId; ?>-modal-iframe"></iframe>
        </div>
    </div>
</div>

<script>
(function(){
    var modId = '<?php echo $moduleId; ?>';
    var uniqueId = '<?php echo $uniqueId; ?>';
    var folderPath = '<?php echo $folderPath; ?>';
    var modal = document.getElementById(uniqueId + '-modal');
    var iframe = document.getElementById(uniqueId + '-modal-iframe');
    var filename = document.getElementById(uniqueId + '-modal-filename');
    var dlBtn = document.getElementById(uniqueId + '-modal-dl');
    var currentUrl = '';
    var currentFile = '';
    
    // View function
    window['bionicDlView' + modId] = function(url, file) {
        currentUrl = url;
        currentFile = file;
        iframe.src = url;
        filename.textContent = file;
        dlBtn.onclick = function() {
            window['bionicDlDownload' + modId](currentUrl, currentFile);
        };
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    };
    
    // Close function
    window['bionicDlClose' + modId] = function() {
        modal.classList.remove('active');
        iframe.src = '';
        document.body.style.overflow = '';
    };
    
    // Download function with counter
    window['bionicDlDownload' + modId] = function(url, file) {
        // Increment counter via AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('GET', window.location.pathname + '?task=bionic_download_count&folder=' + encodeURIComponent(folderPath) + '&file=' + encodeURIComponent(file), true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Update counter in table
                        var container = document.getElementById(uniqueId);
                        var countCell = container.querySelector('td[data-file="' + file + '"]');
                        if (countCell) {
                            countCell.textContent = response.count;
                        }
                    }
                } catch(e) {}
            }
        };
        xhr.send();
        
        // Trigger download
        var a = document.createElement('a');
        a.href = url;
        a.download = file;
        a.target = '_blank';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    };
    
    // Close on background click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            window['bionicDlClose' + modId]();
        }
    });
    
    // Close on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            window['bionicDlClose' + modId]();
        }
    });
})();
</script>
