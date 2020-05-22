<head>
  <link rel="stylesheet" href="style.css">
</head>
<body class="content">

  <?php
  header('Content-Type: text/html; charset=UTF-8');
  include('db.php');
  session_start();
  // если пользователь уже вошел, то кидаем его на стр с формой
  if (!empty($_SESSION['login'])) {
    header('Location: ./');
  }

  if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $errors = array();
        // если были ошибки при попытке зайти
        if (isset($_COOKIE['login_error']) || isset($_COOKIE['pass_error']) || isset($_COOKIE['login_or_pass_error'])) {
          $errors['login'] = !empty($_COOKIE['login_error']);
          $errors['pass'] = !empty($_COOKIE['pass_error']);
          $errors['login_or_pass'] = !empty($_COOKIE['login_or_pass_error']);
        }
  ?>
    <form action="" method="POST">
      <p>login</p>
      <input name="login" type="text" >
      <p>password</p>
      <input name="pass" type="text" >
      <p><input class="user-form__submit" type="submit" value="try" name="send"></p>
    </form>

  <?php
  } else {

    $errors = false;
    $db = connectToDB();

    // LOGIN
    if (empty($_POST['login'])) {
      setcookie('login_error', 'Fill the "Login"');
      $errors = true;
    } else {
      // сравниваем логины из бд и логин, введенный юзером
      try {
        $stmt = $db->prepare("SELECT login FROM user WHERE login = ?");
        $stmt->execute([$_POST['login']]);
        if (!empty($stmt->fetch(PDO::FETCH_ASSOC))) {
          // если есть такой логин
          setcookie('login_value', $_POST['login']);
        } else {
          // если нет такого логина
          setcookie('login_or_pass_error', 'Uncorrect login or password');
          $errors = true;
        }
      } catch (PDOException $e) {
        exit($e->getMessage());
      }
    }

    // PASS
    if (empty($_POST['pass'])) {
      setcookie('pass_error', 'Fill the "Password"');
      $errors = true;
    } else if (!isset($_COOKIE['login_or_pass_error'])) {
      // если логин есть в бд, проверяем пароль
      // сравниваем пароль из бд и пароль, введенный юзером
      try {
        $stmt = $db->prepare("SELECT pass_hash FROM user WHERE login = ?");
        $stmt->execute([$_POST['login']]);
        $response = $stmt->fetch(PDO::FETCH_ASSOC);
        $result = password_verify($_POST['pass'], $response['pass_hash']); // проверка пароля
        if ($result) {
          // если пароль верный, авторизуем юзера
          $_SESSION['login'] = $_POST['login'];
          // и записываем его ID
          $stmt = $db->prepare("SELECT id FROM user WHERE login = ?");
          $stmt->execute([$_POST['login']]);
          $response = $stmt->fetch(PDO::FETCH_ASSOC);
          $_SESSION['uid'] = $response['id'];
        } else {
          // если пароль неверный
          setcookie('login_or_pass_error', 'Uncorrect login or password');
          $errors = true;
        }
      } catch (PDOException $e) {
        exit($e->getMessage());
      }
    }
    if (!empty($errors)) {
      // если есть ошибки, то перезагружаем страницу(там покажем сообщения об ошибках)
      header('Location: login.php');
      exit();
    }

    header('Location: ./index.php?do=update');
  }
  ?>
</body>
