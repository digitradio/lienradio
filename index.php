<?php
// Function to handle streaming URLs via stream.php
function get_friendly_url($file)
{
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $requestUri = $_SERVER['REQUEST_URI'];
    $directoryPath = str_replace('\\', '/', dirname($requestUri));
    $baseUrl = $protocol . "://" . $host . rtrim($directoryPath, '/') . '/';

    // Direct link to the streaming proxy
    return $baseUrl . 'stream.php?file=' . rawurlencode($file);
}

// Scan for .mp3 files
$mp3Files = glob("*.mp3");

// Sort by date
usort($mp3Files, function ($a, $b) {
    return filemtime($b) - filemtime($a);
});
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecteur MP3 Direct</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --secondary: #a855f7;
            --bg: #0f172a;
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
            --text: #f8fafc;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: #0f172a;
            color: var(--text);
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            width: 100%;
            max-width: 800px;
        }

        h1 {
            text-align: center;
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 30px;
        }

        .file-card {
            background: var(--glass);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 15px;
            transition: 0.3s;
        }

        .file-card:hover {
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.1);
        }

        .file-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .file-name {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .url-display {
            background: rgba(0, 0, 0, 0.4);
            padding: 10px;
            border-radius: 8px;
            font-family: monospace;
            font-size: 0.85rem;
            color: #818cf8;
            word-break: break-all;
            margin-bottom: 15px;
            border: 1px solid var(--glass-border);
        }

        audio {
            width: 100%;
            height: 40px;
            filter: invert(100%) hue-rotate(180deg) brightness(1.5);
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .btn {
            background: var(--primary);
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
        }

        .btn:hover {
            opacity: 0.9;
        }

        .btn-copy {
            background: #4b5563;
        }

        .btn-download {
            background: #10b981;
        }

        .success-msg {
            color: #10b981;
            font-size: 0.8rem;
            display: none;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Ma Musique MP3</h1>

        <?php if (empty($mp3Files)): ?>
            <p style="text-align: center; color: #94a3b8;">Aucun fichier MP3 trouvé.</p>
        <?php else: ?>
            <?php foreach ($mp3Files as $index => $file):
                $url = get_friendly_url($file);
                $displayName = htmlspecialchars($file);
                ?>
                <div class="file-card">
                    <div class="file-header">
                        <span class="file-name"><?php echo $displayName; ?></span>
                        <span class="success-msg" id="msg-<?php echo $index; ?>">Copié !</span>
                    </div>

                    <div class="url-display" id="url-<?php echo $index; ?>"><?php echo $url; ?></div>

                    <audio controls preload="none">
                        <source src="<?php echo $url; ?>" type="audio/mpeg">
                        Votre navigateur ne supporte pas l'élément audio.
                    </audio>

                    <div class="btn-group">
                        <button class="btn btn-copy"
                            onclick="copyToClipboard('url-<?php echo $index; ?>', 'msg-<?php echo $index; ?>')">Copier le
                            lien</button>
                        <a href="<?php echo $url; ?>" download class="btn btn-download">Télécharger</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <script>
        function copyToClipboard(elementId, msgId) {
            const text = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(text).then(() => {
                const msg = document.getElementById(msgId);
                msg.style.display = 'inline';
                setTimeout(() => { msg.style.display = 'none'; }, 2000);
            });
        }
    </script>

</body>

</html>