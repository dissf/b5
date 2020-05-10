<html>
  <head>
  <link rel="stylesheet" href="style.css">
  </head>
  <body class="content">

<!--msgs-->
<?php
if (!empty($messages)) {
  print('<div id="messages">');
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
}
?>

<!--form-->
    <form action="" method="POST">
<!--name-->
      <p>name:</p>
        <input name="fio" <?php if ($errors['fio']) {print 'class="error"';} ?> value="<?php print $values['fio']; ?>" />
<!--email-->
      <p>email:</p>
        <input name="email" <?php if ($errors['email']) {print 'class="error"';} ?> value="<?php print $values['email']; ?>" />
<!--birthday-->
      <p>birthday:</p>
        <select name="year">
          <?php for($i = 1900; $i < 2020; $i++) { ?>
            <option value="<?php print $i; ?>"<?= $i == $values['year'] ? 'selected' : ""?>><?= $i; ?></option>
          <?php } ?>
          <?php if ($errors['year']) {print 'class="error"';}?>
        </select>
<!--sex-->
      <p>sex:
        <p> <input type="radio" name="sex" value="1" <?php echo $values['sex'] == "1" ? 'checked="checked"' :""?>>male</p>
        <p> <input type="radio" name="sex" value="2" <?php echo $values['sex'] == "2" ? 'checked="checked"' :""?>>female</p>
      </p>
<!--limbs-->
      <p>limbs:</p>
        <p><input type="radio" name="limbs" value="1" <?php echo $values['limbs'] == "1" ? 'checked="checked"' :""?>>1</p>
        <p><input type="radio" name="limbs" value="2" <?php echo $values['limbs'] == "2" ? 'checked="checked"' :""?>>2</p>
        <p><input type="radio" name="limbs" value="3" <?php echo $values['limbs'] == "3" ? 'checked="checked"' :""?>>3</p>
        <p><input type="radio" name="limbs" value="4" <?php echo $values['limbs'] == "4" ? 'checked="checked"' :""?>>4</p>
<!--abilities-->
      <p>abilities:</p>
        <select name="abilities[]" multiple <?php if ($errors['abilities']) {print 'class="error"';}?>>
        <?php foreach ($ability_labels as $key => $value) {?>
          <option value="<?= $key; ?>" <?php if (isset($_COOKIE[$key . '_value'])) {
                                          print 'selected';
                                        } ?>><?= $value; ?></option>
        <?php } ?>
        </select>
<!--biography-->
      <p>biography</p>
        <textarea rows="5" cols="30" name="bio" <?php if ($errors['bio']) {print 'class="error"';}?>><?php print $values['bio'];?></textarea><br>
<!--agree-->
      <p <?php if ($errors['contract']) {print 'class="error"';} ?>><input type="checkbox" name="contract"<?=$values['contract']=="on"? 'checked="checked"':"";?>>agree</p>
<!--ok-->
      <p><input type="submit" value="ok" /></p>
    </form>
  </body>
</html>
