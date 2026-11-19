<?php
/**
 * stream.php - Handles streaming of MP3 files with support for Range requests.
 * This is crucial for mobile apps to seek and buffer properly.
 */

if (!isset($_GET['file'])) {
    die("Aucun fichier spécifié.");
}

$file = basename($_GET['file']); // Security: only allow files in the current directory
$filePath = __DIR__ . DIRECTORY_SEPARATOR . $file;

if (!file_exists($filePath) || strtolower(pathinfo($filePath, PATHINFO_EXTENSION)) !== 'mp3') {
    header("HTTP/1.1 404 Not Found");
    die("Fichier non trouvé.");
}

$size = filesize($filePath);
$time = filemtime($filePath);

header('Accept-Ranges: bytes');
header('Content-Type: audio/mpeg');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $time) . ' GMT');

if (isset($_SERVER['HTTP_RANGE'])) {
    if (!preg_match('/bytes=\d*-\d*(,\d*-\d*)*$/i', $_SERVER['HTTP_RANGE'])) {
        header('HTTP/1.1 416 Requested Range Not Satisfiable');
        header('Content-Range: bytes */' . $size);
        exit;
    }

    $ranges = explode(',', substr($_SERVER['HTTP_RANGE'], 6));
    foreach ($ranges as $range) {
        $parts = explode('-', $range);
        $start = $parts[0];
        $end = $parts[1];

        if ($start === '') {
            $start = $size - $end;
            $end = $size - 1;
        } elseif ($end === '') {
            $end = $size - 1;
        } else {
            $end = min($end, $size - 1);
        }

        if ($start > $end || $start > $size - 1 || $end > $size - 1) {
            header('HTTP/1.1 416 Requested Range Not Satisfiable');
            header('Content-Range: bytes */' . $size);
            exit;
        }

        header('HTTP/1.1 206 Partial Content');
        header('Content-Range: bytes ' . $start . '-' . $end . '/' . $size);
        header('Content-Length: ' . ($end - $start + 1));

        $fp = fopen($filePath, 'rb');
        fseek($fp, $start);
        $buffer = 1024 * 8;
        while (!feof($fp) && ($pos = ftell($fp)) <= $end) {
            if ($pos + $buffer > $end) {
                $buffer = $end - $pos + 1;
            }
            set_time_limit(0);
            echo fread($fp, $buffer);
            flush();
        }
        fclose($fp);
        exit;
    }
} else {
    header('Content-Length: ' . $size);
    readfile($filePath);
    exit;
}
