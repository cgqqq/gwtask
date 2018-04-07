<!doctype html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
<p style="font-weight: 600;color: #0C0C0C;font-size: 18px;">
    Dear user : {{$user_name}} ! Your user id is {{$user_id}}. Please click the link below to reset your password !
</p>
<a href="{{ URL('mail/resetPassword?user_id='.$user_id) }}" target="_blank">Reset Password</a>
</body>
</html>