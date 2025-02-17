<?php

require __DIR__ . '/vendor/autoload.php';

use Sagarj\OauthModule\OAuthClient;

$oauth = new OAuthClient();

$refresh_token_file = __DIR__ . "/refreshToken.txt";
$access_token_file = __DIR__ . "/accessToken.txt";

if (!file_exists($refresh_token_file)) {
    file_put_contents($refresh_token_file, "");
}
if (!file_exists($access_token_file)) {
    file_put_contents($access_token_file, "");
}

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $tokens = $oauth->getAccessToken($code);

    if (isset($tokens['access_token']) && isset($tokens['refresh_token'])) {
        file_put_contents($access_token_file, $tokens['access_token']);
        file_put_contents($refresh_token_file, $tokens['refresh_token']);
    }
}

$refresh_token = (filesize($refresh_token_file) > 0) ? file_get_contents($refresh_token_file) : "No Refresh Token Available!";
$access_token = (filesize($access_token_file) > 0) ? file_get_contents($access_token_file) : "No Access Token Available!";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tokens</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        pre {
            background: #b4e9d5;
            padding: 10px;
            border-radius: 5px;
            text-align: left;
            display: inline-block;
            word-wrap: break-word;
            white-space: pre-wrap;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: blue;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <h2>OAuth Tokens</h2>

    <h3>Refresh Token</h3>
    <pre>
        <?php echo htmlspecialchars($refresh_token); ?>
    </pre>

    <h3>Access Token</h3>
    <pre><?php echo htmlspecialchars($access_token); ?></pre>

    <br>
    <a href="test.php" class="btn">Get New Access Token</a>
</body>

</html>