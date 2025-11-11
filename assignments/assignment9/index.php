<?php
require_once 'classes/Pdo_methods.php';

//Why does StickyForm extend Validation instead of including validation logic directly? What are the benefits of this design?
//Explain what "sticky form" means. How does it improve user experience compared to a non-sticky form?
//Describe the validation process. When does validation occur, and what happens if validation fails?
//Explain the purpose of the $formConfig array. What information does it store, and how is it used throughout the form lifecycle?
//What is the purpose of masterStatus['error'] in the form configuration? How does it coordinate validation across multiple 
//form fields?
$pdo = new PdoMethods();

$values = [
  'firstName' => '',
  'lastName' => '',
  'email' => '',
  'password1' => '',
  'password2' => ''
];

$errors = [
  'firstName' => '',
  'lastName' => '',
  'email' => '',
  'password1' => '',
  'password2' => ''
];

$message = '';
$output = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $values['firstName'] = trim($_POST['firstName']);
  $values['lastName'] = trim($_POST['lastName']);
  $values['email'] = trim($_POST['email']);
  $values['password1'] = trim($_POST['password1']);
  $values['password2'] = trim($_POST['password2']);

  if (empty($values['firstName'])) {
    $errors['firstName'] = 'This field is required.';
  } elseif (!preg_match("/^[A-Za-z' ]+$/", $values['firstName'])) {
    $errors['firstName'] = 'Invalid name format.';
  }

  if (!empty($values['lastName']) && !preg_match("/^[A-Za-z' ]+$/", $values['lastName'])) {
    $errors['lastName'] = 'Invalid last name format.';
  }

  if (!empty($values['email']) && !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Invalid email format.';
  }

  if (empty($values['password1'])) {
    $errors['password1'] = 'Password must be at least 8 characters long and include 1 uppercase letter, 1 symbol, and 1 number.';
  } elseif (!preg_match("/^(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*(),.?\":{}|<>]).{8,}$/", $values['password1'])) {
    $errors['password1'] = 'Password must be at least 8 characters long and include 1 uppercase letter, 1 symbol, and 1 number.';
  }

  if (
    empty($errors['firstName']) &&
    empty($errors['password1']) &&
    (!empty($values['password1']) || !empty($values['password2']))
  ) {
    if (empty($values['password2']) || $values['password1'] !== $values['password2']) {
      $errors['password2'] = 'Passwords do not match.';
    }
  }

  if (
    empty($errors['firstName']) &&
    empty($errors['lastName']) &&
    empty($errors['email']) &&
    empty($errors['password1']) &&
    empty($errors['password2'])
  ) {
    if (!empty($values['email'])) {
  $sql = "INSERT INTO users (first_name, last_name, email, password)
          VALUES (:first, :last, :email, :pass)";
  $bindings = [
    [':first', $values['firstName'], 'str'],
    [':last', $values['lastName'], 'str'],
    [':email', $values['email'], 'str'],
    [':pass', password_hash($values['password1'], PASSWORD_DEFAULT), 'str']
  ];
} else {
  $sql = "INSERT INTO users (first_name, last_name, password)
          VALUES (:first, :last, :pass)";
  $bindings = [
    [':first', $values['firstName'], 'str'],
    [':last', $values['lastName'], 'str'],
    [':pass', password_hash($values['password1'], PASSWORD_DEFAULT), 'str']
  ];
}

    $result = $pdo->otherBinded($sql, $bindings);

    if ($result === 'noerror') {
      $message = '<p class="text-success">You have been added to the database.</p>';
      $values = ['firstName' => '', 'lastName' => '', 'email' => '', 'password1' => '', 'password2' => ''];
    } else {
      $message = '<p class="text-danger">Database error occurred.</p>';
    }
  }
}

$users = $pdo->selectNotBinded("SELECT first_name, last_name, email, password FROM users ORDER BY id DESC");

if ($users !== 'error' && count($users) > 0) {
  foreach ($users as $u) {
    $output .= '<tr>';
    $output .= '<td>' . htmlspecialchars($u['first_name']) . '</td>';
    $output .= '<td>' . htmlspecialchars($u['last_name']) . '</td>';
    $output .= '<td>' . htmlspecialchars($u['email']) . '</td>';
    $output .= '<td>' . htmlspecialchars($u['password']) . '</td>';
    $output .= '</tr>';
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Sticky Form Assignment</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>

<div class="container mt-5">
  <p>&nbsp;</p>
  <?= $message ?>

  <form method="post" action="">
    <div class="row">
      <div class="col-md-6">
        <div class="mb-3">
          <label for="first_name">First Name</label>
          <input type="text" class="form-control" id="first_name" name="firstName" value="<?= htmlspecialchars($values['firstName']) ?>">
          <?php if ($errors['firstName']): ?><p class="text-danger"><?= $errors['firstName'] ?></p><?php endif; ?>
        </div>
      </div>

      <div class="col-md-6">
        <div class="mb-3">
          <label for="last_name">Last Name</label>
          <input type="text" class="form-control" id="last_name" name="lastName" value="<?= htmlspecialchars($values['lastName']) ?>">
          <?php if ($errors['lastName']): ?><p class="text-danger"><?= $errors['lastName'] ?></p><?php endif; ?>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-4">
        <div class="mb-3">
          <label for="email">Email</label>
          <input type="text" class="form-control" id="email" name="email" value="<?= htmlspecialchars($values['email']) ?>">
          <?php if ($errors['email']): ?><p class="text-danger"><?= $errors['email'] ?></p><?php endif; ?>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label for="password1">Password</label>
          <input type="text" class="form-control" id="password1" name="password1" value="<?= htmlspecialchars($values['password1']) ?>">
          <?php if ($errors['password1']): ?><p class="text-danger"><?= $errors['password1'] ?></p><?php endif; ?>
        </div>
      </div>
      <div class="col-md-4">
        <div class="mb-3">
          <label for="password2">Confirm Password</label>
          <input type="text" class="form-control" id="password2" name="password2" value="<?= htmlspecialchars($values['password2']) ?>">
          <?php if ($errors['password2']): ?><p class="text-danger"><?= $errors['password2'] ?></p><?php endif; ?>
        </div>
      </div>
    </div>

    <input type="submit" class="btn btn-primary" value="Register">
  </form>

  <table class="table table-bordered mt-4">
    <tr>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Email</th>
      <th>Password</th>
    </tr>
    <tr>
      <td>Scott</td>
      <td>Shaper</td>
      <td>sshaper@wccnet.edu</td>
      <td>$2y$10$qla5z5ydrGEeUWAf5MahFetNBzxA/i71qa2IMUvCg8pxpNVvRfoVq</td>
    </tr>
    <tr>
      <td>Chase</td>
      <td>Miller</td>
      <td>chaumiller@wccnet.edu</td>
      <td>$2y$10$4Uuc348ZqzYEBYjDI6dOkO2MxadGR147M4ka3GXN9JwzwQkghJUhK</td>
    </tr>
    <?= $output ?>
  </table>
</div>

</body>
</html>








