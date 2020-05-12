<head>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <!-- Файл login.php для не авторизованного пользователя выводит форму логина.
  При отправке формы проверяет логин/пароль и создает сессию,
  записывает в нее логин и id пользователя.
  После авторизации пользователь перенаправляется на главную страницу
  для изменения ранее введенных данных. -->

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
    $messages = array();

    // если были ошибки при попытке зайти
    if (isset($_COOKIE['login_error']) || isset($_COOKIE['pass_error']) || isset($_COOKIE['login_or_pass_error'])) {
      // заполняем массив с сообщениями об ошибках и удаляем куки с ошибками
      foreach (array_keys($_COOKIE) as $cookieName) {
        if (stristr($cookieName, '_error')) {
          $messages[$cookieName] = $_COOKIE[$cookieName];
          setcookie($cookieName, '', 1);
        }
      }

      // поля с ошибками
      $errors = array_keys($messages);

      // print('</br></br>MESSAGES</br>');
      // print_r($messages);
      // print('</br></br>ERRORS</br>');
      // print_r($errors);
    }
  ?>
    <form method="POST" action="">
      <h4>Login</h4>
      <input name="login" type="text" <?php if (in_array('login_error', $errors) || in_array('login_or_pass_error', $errors)) {
                                        print 'class="error"';
                                      } ?> value="<?php
                                                  if (in_array('login_or_pass_error', $errors) && isset($_COOKIE['login_value'])) {
                                                    print $_COOKIE['login_value'];
                                                  }
                                                  ?>">
      <?php
      if (in_array("login_error", array_keys($messages))) {
        print '<div class="error">' . $messages["login_error"] . '</div>';
      }
      ?>

      <h4>Password</h4>
      <input name="pass" type="password" <?php if (in_array('pass_error', $errors) || in_array('login_or_pass_error', $errors)) {
                                            print 'class="error_field"';
                                          } ?>>
      <?php
      if (in_array("pass_error", array_keys($messages))) {
        print '<div class="error">' . $messages["pass_error"] . '</div>';
      }
      if (in_array("login_or_pass_error", array_keys($messages))) {
        print '<div class="error">' . $messages["login_or_pass_error"] . '</div>';
      }
      ?>

      <!-- SUBMIT -->
      <input class="user-form__submit" type="submit" value="login" name="send">
    </form>

  <?php
  } else {
    // POST

    // VALIDATON
    $errors = false;

    $db = connectToDB();

    // LOGIN
    if (empty($_POST['login'])) {
      setcookie('login_error', 'Fill the "Login"');
      $errors = true;
    } else if (strlen($_POST['login']) != 8) {
      setcookie('login_error', 'Login have to include 8 symbols');
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
    } else if (strlen($_POST['pass']) != 8) {
      setcookie('pass_error', 'Password have to include 8 symbols');
      $errors = true;
    } else if (!isset($_COOKIE['login_or_pass_error'])) { // если логин есть в бд, проверяем пароль
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
    } else {
      // удаляем куки с ошибками
      foreach (array_keys($_COOKIE) as $cookieName) {
        if (stristr($cookieName, '_error')) {
          setcookie($cookieName, '', 1);
        }
      }
    }

    header('Location: ./index.php?do=update');
  }

  ?>
</body>
