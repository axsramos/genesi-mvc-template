<?php
$progress_wbar = 'width: ' . $data['FormDesign']['Tabs']['ProgressBar'] . '%;';

if (isset($data['FormDesign']['Tabs']['TotalPages'])) {
    echo '<div class="progress mb-3" style="height: 40px;">';
    echo '    <div class="progress-bar" role="progressbar" style="' . $progress_wbar . '" aria-valuenow="' . $data['FormDesign']['Tabs']['ProgressBar'] . '" aria-valuemin="0" aria-valuemax="100">Passo ' . $data['FormDesign']['Tabs']['Current'] . ' de ' . $data['FormDesign']['Tabs']['TotalPages'] . '</div>';
    echo '</div>';
}
