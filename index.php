<?php
header('Content-Type: text/html; charset=UTF-8');
include('db.php');
$ability_labels = ['god' => 'god', 'fly' => 'fly', 'idclip' => 'idclip'];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();
  $errors = array();
  $values = array();

  if (empty($errors) && isset($_COOKIE[session_name()]) && session_start() && isset($_SESSION['login'])) {
    // загружаем данные пользователя из БД и заполняем переменную $values
    $db = connectToDB();
    try {
      $stmt = $db->prepare("SELECT * FROM user WHERE login = ?");
      $stmt->execute([$_SESSION['login']]);
      $response = $stmt->fetch(PDO::FETCH_ASSOC);
      // print 'Response from db:</br>';
      // print_r($response);
      foreach ($response as $key => $value) {
        switch ($key) {
          case 'fio':
            $values[$key] = $value;
            break;
          case 'email':
            $values[$key] = $value;
            break;
          case 'year':
            $values[$key] = $value;
            break;
          case 'sex':
            $values[$key] = $value;
            break;
          case 'limbs':
            $values[$key] = $value;
            break;
          case 'god':
            if ($value == 1) {
              $values[substr($key, 6)] = $value;
            }
            break;
          case 'fly':
            if ($value == 1) {
              $values[substr($key, 6)] = $value;
            }
            break;
          case 'idclip':
            if ($value == 1) {
              $values[substr($key, 6)] = $value;
            }
            break;
          case 'bio':
            $values[$key] = $value;
            break;
        }
      }
    } catch (PDOException $e) {
      exit($e->getMessage());
    }
  } else {
    $values['fio'] = empty($_COOKIE['fio_value']) ? '' : $_COOKIE['fio_value'];
    $values['email'] = empty($_COOKIE['email_value']) ? '' : $_COOKIE['email_value'];
    $values['year'] = empty($_COOKIE['year_value']) ? '' : $_COOKIE['year_value'];
    $values['sex'] = empty($_COOKIE['sex_value']) ? '' : $_COOKIE['sex_value'];
    $values['limbs'] = empty($_COOKIE['limbs_value']) ? '' : $_COOKIE['limbs_value'];
    $values['abilities'] = empty($_COOKIE['abilities_value']) ? '' : $_COOKIE['abilities_value'];
    $values['god'] = empty($_COOKIE['god_value']) ? '' : $_COOKIE['god_value'];
    $values['fly'] = empty($_COOKIE['fly_value']) ? '' : $_COOKIE['fly_value'];
    $values['idclip'] = empty($_COOKIE['idclip_value']) ? '' : $_COOKIE['idclip_value'];
    $values['bio'] = empty($_COOKIE['bio_value']) ? '' : $_COOKIE['bio_value'];
    $values['contract'] = empty($_COOKIE['contract_value']) ? '' : $_COOKIE['contract_value'];
  }

  $errors['fio'] = !empty($_COOKIE['fio_error']);
  $errors['email'] = !empty($_COOKIE['email_error']);
  $errors['year'] = !empty($_COOKIE['year_error']);
  $errors['sex'] = !empty($_COOKIE['sex_error']);
  $errors['limbs'] = !empty($_COOKIE['limbs_error']);
  $errors['abilities'] = !empty($_COOKIE['abilities_error']);
  $errors['bio'] = !empty($_COOKIE['bio_error']);
  $errors['contract'] = !empty($_COOKIE['contract_error']);
  // Выдаем сообщения об ошибках.
  if ($errors['fio']) {
    setcookie('fio_error', '', 100000);
    $messages[] = '<div class="error">fill the name</div>';
  }
  if ($errors['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">fill the email</div>';
  }
  if ($errors['year']) {
    setcookie('year_error', '', 100000);
    $messages[] = '<div class="error">choose the year</div>';
  }
  if ($errors['sex']) {
    setcookie('sex_error', '', 100000);
    $messages[] = '<div class="error">choose the sex</div>';
  }
  if ($errors['limbs']) {
    setcookie('limbs_error', '', 100000);
    $messages[] = '<div class="error">choose the limbs</div>';
  }
  if ($errors['abilities']) {
    setcookie('abilities_error', '', 100000);
  if ($_COOKIE['abilities_error'] == "1") {
    $messages[] = '<div class="error">choose the abilities</div>';
      }
    }
  if ($errors['bio']) {
    setcookie('bio_error', '', 100000);
    $messages[] = '<div class="error">fill the biography</div>';
  }
  if ($errors['contract']) {
    setcookie('contract_error', '', 100000);
    $messages[] = '<div class="error">accept the contract</div>';
  }
///////////
if (isset($_COOKIE['save'])) {
  if ($_COOKIE['save'] == 1) {
    $popup_messages[] = 'thank you, result are saved';
    // Если в куках есть пароль, то выводим сообщение.
    if (isset($_COOKIE['pass'])) {
      $reg_data_msg = sprintf(
        'You can <a href="login.php">log in</a> with:</br>
        login - <strong>%s</strong></br>
        password - <strong>%s</strong></br>',
        strip_tags($_COOKIE['login']),
        strip_tags($_COOKIE['pass'])
      );
    }
    setcookie('login', '', 1);
    setcookie('pass', '', 1);
  }


  setcookie('save', '', 1); // удаляем куку о сохранении
}
  include('form.php');
}
else {
  $errors = FALSE;
//fio
  if (empty($_POST['fio'])) {
    setcookie('fio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('fio_value', $_POST['fio'], time() + 30 * 24 * 60 * 60);
  }

//email
  if (empty($_POST['email'])) {
    setcookie('email_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    setcookie('email_error', 'Uncorrect Email');
    $errors = true;
  }
  else {
    setcookie('email_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
  }

//year
  if (empty($_POST['year'])) {
    setcookie('year_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('year_value', $_POST['year'], time() + 30 * 24 * 60 * 60);
  }
  //sex
  if (empty($_POST['sex'])) {
      setcookie('sex_error', '1', time() + 24 * 60 * 60);
      $errors = TRUE;
    }
    else {
      setcookie('sex_value', $_POST['sex'], time() + 30 * 24 * 60 * 60);
    }
// limbs
    if (empty($_POST['limbs'])) {
        setcookie('limbs_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
      }
    else {
        setcookie('limbs_value', $_POST['limbs'], time() + 12 * 30 * 24 * 60 * 60);
      }
// abilities
  $abilities_id = array_keys($ability_labels);
    if (empty($_POST['abilities'])) {
          setcookie('abilities_error', '1', time() + 24 * 60 * 60);
          $errors = TRUE;
        }
      else {
        foreach ($_POST['abilities'] as $ability) {
          if (!in_array($ability, $abilities_id)) {
            setcookie('abilities_error', '1', time() + 24 * 60 * 60);
            $errors = true;
          } else {
            setcookie($ability . '_value', '1', time() + 365 * 24 * 60 * 60);
          }
        }
      }
        foreach ($abilities_id as $ability) {
          $abilities_insert[$ability] = in_array($ability, $_POST['abilities']) ? 1 : 0;
        }
//bio
  if (empty($_POST['bio'])) {
    setcookie('bio_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('bio_value', $_POST['bio'], time() + 30 * 24 * 60 * 60);
  }
//contract
  if (empty($_POST['contract'])) {
    setcookie('contract_error', '1', time() + 24 * 60 * 60);
    $errors = TRUE;
  }
  else {
    setcookie('contract_value', $_POST['contract'], time() + 30 * 24 * 60 * 60);
  }

// ****************************************************************************

  if ($errors) {
    setcookie('save_form', 0);
    header('Location: index.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('fio_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('year_error', '', 100000);
    setcookie('sex_error', '', 100000);
    setcookie('limbs_error', '', 100000);
    setcookie('abilities_error', '', 100000);
    setcookie('bio_error', '', 100000);
    setcookie('contract_error', '', 100000);

    // TODO: тут необходимо удалить остальные Cookies.
    setcookie('fio_value', '', 100000);
    setcookie('email_value', '', 100000);
    setcookie('year_value', '', 100000);
    setcookie('sex_value', '', 100000);
    setcookie('limbs_value', '', 100000);
    setcookie('god_value', '', 100000);
    setcookie('fly_value', '', 100000);
    setcookie('idclip_value', '', 100000);
    setcookie('bio_value', '', 100000);
    setcookie('contract_value', '', 100000);
  }
  $db = connectToDB();
  if (isset($_COOKIE[session_name()]) && session_start() && isset($_SESSION['login'])) {
    // одновляем данные пользователя в бд
    // TODO 0: проверять, какие данные надо обновить, а какие остались неизменными
    try {
      $stmt = $db->prepare("UPDATE user SET fio = ?, email = ?, year = ?, sex = ?, limbs = ?,  god = ?, fly = ?, idclip = ?, bio = ? WHERE login = ?");
      $stmt->execute([$_POST['fio'], $_POST['email'], intval($_POST['year']), $_POST['sex'], intval($_POST['limbs']), $abilities_insert['god'], $abilities_insert['fly'], $abilities_insert['idclip'], $_POST['bio'], $_SESSION['login'] ]);
    } catch (PDOException $e) {
      exit($e->getMessage());
    }
  } else {
    // Generate unique login and password
    // TODO 0: проверять, есть ли уже такие логин / пароль в базе
    $login = substr(md5(time()), 0, 8);
    $pass = substr(md5(time() + 1), 0, 8);
    $pass_hash = password_hash($pass, PASSWORD_DEFAULT);
    setcookie('login', $login);
    setcookie('pass', $pass);

    try {
      $stmt = $db->prepare("INSERT INTO user SET login = ?, pass_hash = ?, fio = ?, email = ?, year = ?, sex = ?, limbs = ?,  god = ?, fly = ?, idclip = ?, bio = ?");
      $stmt->execute([$login, $pass_hash, $_POST['fio'], $_POST['email'], intval($_POST['year']), $_POST['sex'], intval($_POST['limbs']), $abilities_insert['god'], $abilities_insert['fly'], $abilities_insert['idclip'], $_POST['bio'] ]);
    } catch (PDOException $e) {
      exit($e->getMessage());
    }
  }
  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: index.php');
}
