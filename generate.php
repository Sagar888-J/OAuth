<?php

require __DIR__ . '/vendor/autoload.php';

use Sagarj\OauthModule\OAuthClient;

$oauth = new OAuthClient();

$refresh_token_file = __DIR__ . "/refreshToken.txt";
$access_token_file = __DIR__ . "/accessToken.txt";

$refresh_token = file_exists($refresh_token_file) ? trim(file_get_contents($refresh_token_file)) : "";
$access_token = file_exists($access_token_file) ? trim(file_get_contents($access_token_file)) : "";

$hasRefreshToken = !empty($refresh_token);
$hasAccessToken = !empty($access_token);

if ($hasRefreshToken) {
    $tokens = $oauth->refreshAccessToken();

    if (isset($tokens['access_token'])) {
        file_put_contents($access_token_file, $tokens['access_token']);
        $access_token = $tokens['access_token'];
        $hasAccessToken = true;
    }
}

$authUrl = $oauth->getAuthUrl();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OAuth Tokens</title>
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

    <?php if (!$hasRefreshToken): ?>
        <a href="<?php echo $authUrl; ?>" class="btn">Authenticate & Get Tokens</a>
    <?php elseif ($hasAccessToken): ?>
        <h3>Access Token</h3>
        <pre><?php echo htmlspecialchars($access_token); ?></pre>

        <h3>Refresh Token</h3>
        <pre><?php echo htmlspecialchars($refresh_token); ?></pre>
        <br>
        <a href="generate.php" class="btn">Generate Access Token</a>
    <?php endif; ?>
</body>

</html>