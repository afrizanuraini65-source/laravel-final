<?php
$file = 'C:/Users/ASUS/.gemini/antigravity-ide/brain/b607918f-901b-4f05-bfc8-936dd0fcfefb/.system_generated/logs/transcript.jsonl';
$lines = file($file);
foreach ([109, 113] as $idx) {
    if (isset($lines[$idx])) {
        $data = json_decode($lines[$idx], true);
        echo "=== INDEX $idx ===\n";
        print_r($data['tool_calls']);
    }
}
