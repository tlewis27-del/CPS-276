<?php
require_once "classes/Directories.php";

$msg = "";
$link = "";
$style = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $folder = trim($_POST["dirname"]);
    $content = trim($_POST["filecontent"]);

    $dir = new Directories();
    $outcome = $dir->makeDirectory($folder, $content);

    if ($outcome["ok"]) {
        $msg = "File and directory were created";
        $style = "text-success";
        $link = "directories/" . htmlspecialchars($folder) . "/readme.txt";
    } else {
        $msg = $outcome["msg"];
        $style = "text-danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>File and Directory Assignment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-white">
<div class="container py-5">
    <div class="card mx-auto shadow-sm" style="max-width:700px;">
        <div class="card-body">
            <h3 class="card-title mb-3">File and Directory Assignment</h3>
            <p class="text-muted">
                Enter a folder name and the contents of a file. Folder names should contain alpha numeric characters only.
            </p>

            <?php if (!empty($msg)): ?>
                <div class="<?php echo $style; ?> fw-semibold mb-3">
                    <?php echo htmlspecialchars($msg); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($link)): ?>
                <div class="mb-3">
                    <a href="<?php echo $link; ?>" target="_blank">Path where file is located</a>
                </div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-3">
                    <label for="dirname" class="form-label">Folder Name</label>
                    <input type="text" name="dirname" id="dirname" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="filecontent" class="form-label">File Content</label>
                    <textarea name="filecontent" id="filecontent" rows="6" class="form-control" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
