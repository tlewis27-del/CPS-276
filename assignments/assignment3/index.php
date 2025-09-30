<?php
$output = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once 'processNames.php';
    $output = addClearNames();
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Name List Project</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-light">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-8 offset-lg-2 col-md-10 offset-md-1 pt-3">

        <h1 class="fw-bold mb-3">Add Names</h1>

        <form method="POST" class="mb-4">

          <div class="mb-3 d-flex gap-2">
            <button type="submit" name="add" class="btn btn-primary">Add Name</button>
            <button type="submit" name="clear" class="btn btn-primary">Clear Names</button>
          </div>
          <div class="mb-3">
            <label for="name" class="form-label">Enter Name</label>
            <input type="text" name="name" id="name" class="form-control">
          </div>
          <div class="mb-3">
            <label for="namelist" class="form-label">List of Names</label>
            <textarea readonly class="form-control" id="namelist" name="namelist" rows="18"><?php echo $output ?></textarea>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
