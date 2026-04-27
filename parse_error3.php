<?php
$html = file_get_contents('/root/.vscode-server/data/User/workspaceStorage/6bf39087472d5a43918d83c4b053321f-1/GitHub.copilot-chat/chat-session-resources/e5b3f6f4-bcb1-41b1-b8f4-34ba7feadc1a/2fe3f219-ba5f-432c-bf98-9968897429ba__vscode-1777295813024/content.txt');
$pos = strpos($html, '<script  id="debugbar_loader"');
if ($pos !== false) {
    echo "Found debugbar at $pos\n";
    $body = substr($html, 0, $pos);
    echo "Body length: " . strlen($body) . "\n";
    echo $body;
}
