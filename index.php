<?php
header('Content-Type: text/html; charset=UTF-8');
$ability_labels = ['god' => 'god', 'fly' => 'fly', 'idclip' => 'idclip'];

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    $messages[] = 'thank you, the results are saved';
  }

  // Складываем признак ошибок в массив.
  $errors = array();
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
  // Складываем предыдущие значения полей в массив, если есть.

  $values = array();
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

  // Включаем содержимое файла form.php.
  // В нем будут доступны переменные $messages, $errors и $values для вывода
  // сообщений, полей с ранее заполненными данными и признаками ошибок.
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
      setcookie('sex_value', $_POST['email'], time() + 30 * 24 * 60 * 60);
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
  $abid = array_keys($abilities_labels);
    if (empty($_POST['abilities'])) {
          setcookie('abilities_error', '1', time() + 24 * 60 * 60);
          $errors = TRUE;
        }
      else {
        foreach ($_POST['abilities'] as $abilities) {
          if (in_array($abilities, $abid)) {
            setcookie('abilities_error', '1', time() + 24 * 60 * 60);
            $errors = true;
          } else {
            setcookie($abilities . '_value', '1', time() + 365 * 24 * 60 * 60);
          }
        }
      }
        foreach ($abid as $abilities) {
          $abilities_insert[$abilities] = in_array($abilities, $_POST['abilities']) ? 1 : 0;
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
    header('Location: index.php');
    exit();
  }
  else {
    // Удаляем Cookies с признаками ошибок.
    setcookie('fio_error', '', 100000);
    setcookie('email_error', '', 100000);
    setcookie('bio_error', '', 100000);
    setcookie('contract_error', '', 100000);
    setcookie('year_error', '', 100000);
    setcookie('sex_error', '', 100000);
    setcookie('limbs_error', '', 100000);
    // TODO: тут необходимо удалить остальные Cookies.
  }

  $user = 'u20490';
  $pass = '3080615';
  try {
  $db = new PDO('mysql:host=localhost;dbname=u20490', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
  }  catch(PDOException $e) {
  exit($e->getMessage());
  }
  // Подготовленный запрос. Не именованные метки.
  try {
    $stmt = $db->prepare("INSERT INTO user SET fio = ?, email = ?, year = ?, sex = ?, limbs = ?,  god = ?, fly = ?, idclip = ?, fireball = ?, bio = ?, contract = ?");
    $stmt->execute([ $_POST['fio'], $_POST['email'], intval($_POST['year']), $_POST['sex'], intval($_POST['limbs']), $abilities_insert['god'], $abilities_insert['fly'], $abilities_insert['idclip'], $_POST['biography'], intval($_POST['contract']) ]);
  } catch (PDOException $e) {
    exit($e->getMessage());
  }

  // Сохраняем куку с признаком успешного сохранения.
  setcookie('save', '1');

  // Делаем перенаправление.
  header('Location: index.php');
}
