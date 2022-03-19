<?php
session_start();
require_once('library.php');
require_once('functions.php');
$error = [];
$username = '';
$password = '';
$_SESSION['username'] = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //FILTER_SANITIZE_STRINGでfilteリング化
    $username = filter_input(INPUT_POST,'username',FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password',FILTER_SANITIZE_STRING);
    if(($username === '') && ($password === '')){
        $error['login'] = 'blind';
    }
    if(($username === '') && !($password === '')){
        $error['login'] = 'name_blind';
    }elseif(!($username === '') && ($password === '')){
        $error['login'] = 'pass_blind';
    }
    if($username !== '' && $password !== ''){
        //ログインチェック
        $db = dbconnect();
        $stmt = $db->prepare('SELECT username,password FROM members WHERE username=? limit 1');
        if(!$stmt){
            die($db->error);
        }
        $stmt->bind_param('s', $username);
        $success = $stmt->execute();
        if(!$success){
            die($db->error);
        }
        $stmt->bind_result($username,$hash);
        $stmt->fetch();
        if(is_null($username)){
            $error['login'] = 'failed';
        } elseif(password_verify($password, $hash)){
            //login成功
            $_SESSION['username'] = $username;
          header('Location: index.php');
            exit();
        }else{
            $error['login'] = 'failed';
        }
    }

}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href=""/>
    <title>ログイン画面</title>
</head>

<body>
<div id="wrap">
    <div id="head">
        <h1>ログイン画面</h1>
    </div>
    <div id="content">
        <div id="Read">
            <p>ユーザー名とパスワードを記入してログインしてください。</p>
        </div>
        <form action="" method="post">
            <dl>
                <dt>ユーザー名</dt>
                <dd>
                    <?php if(is_null($usernames) && $error['login'] === 'not_exist'){ ?>
                        <p class="error">* アカウントが存在しません</p>
                    <?php } ?>
                    <?php if(isset($error['login']) && $error['login'] === 'failed'){ ?>
                        <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
                    <?php } ?>
                    <input type="text" name="username" size="35" maxlength="255" value="<?php echo h($_SESSION['username']); ?>"/>
                    <?php if(isset($error['login']) && (($error['login'] === 'name_blind') || ($error['login'] === 'blind'))){ ?>
                        <p class="error">* ユーザー名をご記入ください</p>
                    <?php } ?>
                </dd>
                <dt>パスワード</dt>
                <dd>
                    <input type="password" name="password" size="35" maxlength="255" />
                    <?php if(isset($error['login']) && (($error['login'] === 'pass_blind') || ($error['login'] === 'blind'))){ ?>
                        <p class="error">* パスワードをご記入ください</p>
                    <?php } ?>
                </dd>
            </dl>
            <div>
                <input type="submit" value="ログインする"/>
            </div>
        </form>
        <div id="lead">
            <br/>
            <p>入会手続きがまだの方はこちらからどうぞ。</p>
            <p>&raquo;<a href="account.php">入会手続きをする</a></p>
        </div>
    </div>
</div>
</body>
</html>