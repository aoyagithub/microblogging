<?php
ini_set('display_errors', 0);
session_start();
require('dbconnect.php');

if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();

    $members = $db->prepare('SELECT * FROM members WHERE id=?');
    $members->execute(array($_SESSION['id']));
    $member = $members->fetch();

    if ($_REQUEST['quit'] === 'true' && $member['id'] == $_SESSION['id']) {
        $id = $_SESSION['id'];
        $del = $db->prepare('DELETE FROM members WHERE id=?');
        $del->execute(array($id));


        $_SESSION = array();
        if (ini_set('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name() . '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();

        setcookie('email', '', time() - 3600);

        header('Location: quit.php?has_quit=true');
    }
} elseif ($_REQUEST['has_quit'] === 'true') {
} else {
    header('Location: login.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Microblogging</title>

    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <header class="head">
        <h1>Microblogging</h1>
    </header>
    <div id="wrap">
        <div id="content">
            <?php if ($_REQUEST['has_quit'] === 'true') : ?>
                <p>退会しました。</p>
                <a href="index.php">TOPに戻る</a>
            <?php else : ?>
                <div style="text-align: right"><a href="logout.php">ログアウト</a></div>
                <p>退会しますか？</p>
                <a href="quit.php?quit=true">退会する</a>
                <a href="index.php">退会しない</a>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>