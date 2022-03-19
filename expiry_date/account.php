<?php
session_start();
require_once('library.php');
require_once('functions.php');

$mode = 'input';
$errmessage = array();
if( isset($_POST['back']) && $_POST['back'] ){
    // 何もしない
} else if( isset($_POST['confirm']) && $_POST['confirm'] ){
    if( !$_POST['username'] || !$_POST['password']) {
        $errmessage[] = "ユーザー名かパスワードを入力してください";
    } else if( mb_strlen($_POST['username']) > 4 ){
        $errmessage[] = "ユーザー名は4文字以下にしてください";
    } else if( mb_strlen($_POST['password']) > 4 ){
        $errmessage[] = "パスワードは4文字以下にしてください";
    }

    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $_SESSION['username']  = h($_POST['username']);
    $_SESSION['password'] = h($password);
    var_dump($password);
    $result = account_exist($_SESSION['username']);
    var_dump($result);
    if($result === 'true'){
        $errmessage[] = 'すでに登録されています';
    }

    if( $errmessage ){
        $mode = 'input';
    } else {
        $token = bin2hex(random_bytes(32));
        $_SESSION['token']  = $token;
        $mode = 'confirm';
    }
} else if( isset($_POST['send']) && $_POST['send'] ){
    // 送信ボタンを押したとき
  if( !$_POST['token'] || !$_SESSION['token'] || !$_SESSION['username'] || !$_SESSION['password'] ){
      $errmessage[] = '不正な処理が行われました';
      $_SESSION     = array();
      $mode         = 'input';
  } else if( $_POST['token'] != $_SESSION['token'] ){
    $errmessage[] = '不正な処理が行われました';
    $_SESSION     = array();
    $mode         = 'input';
  } else {
    $mode     = 'send';
    $success = account_regist($_SESSION['username'],$_SESSION['password']);
    var_dump($success);
  }
}else {
    $_SESSION['username']    = "";
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アカウント作成</title>
    <link rel="stylesheet" type="text/css" href="./css/fin_style.css"/>
</head>
<body>
<div id="wrap">
    <div id="head">
        <h1>アカウント作成</h1>
    </div>
    <div id="content">
        <div id="Read">
        </div>
        <?php if( $mode === 'input' ){ 
            if( $errmessage ){ ?>
                <p class="error"><?php echo implode('<br>', $errmessage ); ?></p>
        <?php } ?>
            <!-- 入力画面 -->
            <p>ユーザー名とパスワードを入力してください</p>
            <form action="" method="post">
                ユーザー名： <br>
                <input type="text"   name="username" size="10" maxlength="20" value="<?php echo $_SESSION['username']; ?>" /><br>
                パスワード：<br>
                <input type="password" name="password" size="10" maxlength="20" /><br>
                <input type="submit" name="confirm" value="確認"/>
                <p>すでにアカウントお持ちの方は<a href="login.php">こちら</a></p>
            </form>
        <?php } else if( $mode == 'confirm' ){ ?>
            <!-- 確認画面 -->
            <form action="" method="post">
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                ユーザー名： <?php echo $_SESSION['username'] ?><br>
                パスワード：【表示されません】<br>
                <input type="submit" name="back" value="戻る"/>
                <input type="submit" name="send" value="送信"/>
            </form>
        <?php }else { ?>
            <!-- 完了画面 -->
            アカウント作成しました！<br>
            <p><a href="index.php">トップページへ</a></p>
        <?php } ?>
    </div>
</div>
</body>
</html>