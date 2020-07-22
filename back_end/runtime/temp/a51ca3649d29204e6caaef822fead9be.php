<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:83:"D:\phpstudy_pro\WWW\CQCQ\back_end\public/../application/index\view\login\index.html";i:1594866195;}*/ ?>
<!DOCTYPE html>
<html>

<head lang="en">
    <meta charset="UTF-8">
    <title>登录页面</title>
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="/tp5/public/css/mr-basic.css" />
    <link href="/tp5/public/css/mr-login.css" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="login-banner" style="position:fixed;top:0px;right:0px;left:0px;height:100%;background: url(/cqcq/images/b5.jpg) center center/cover no-repeat !important;">
        <div class="login-main" style="text-align: center;height: 100%; ">
            <div class="login-box" style="opacity: 0.6;right:30%;display: inline-block; vertical-align: middle; width: 400px; margin: 90px 0;border-radius: 4px; padding:40px; background: lightgray">
                <h3 class="title">后台管理系统</h3>
                <div class="login-form">
                    <!--用户账户和密码的表单-->
                    <form onSubmit="return check_login()" id="loginForm" action="index.php?s=index/login/valid"
                        name="loginForm" method="post" enctype="multipart/form-data">
                        <!-- 只有使用了multipart/form-data，才能完整地传递文件数据 -->
                        <div class="user-name">
                            <label for="user"><i class="mr-icon-user"></i></label>
                            <input type="text" id="user" name="uaccount" placeholder="请输入账号" />
                        </div>
                        <div class="user-pass">
                            <label for="password"><i class="mr-icon-lock"></i></label>
                            <input type="password" id='password' name="password" placeholder="请输入密码" />
                        </div>
                </div>

                <!--    <div class="login-links">
                    <label for="remember-me"><input class="checkbox" id="remember-me" type="checkbox">记住密码</label>
                    <br />
                </div>  -->

                <div class="mr-cf">
                    <input type="submit" name="" value="登 录" onclick="check_login()"
                        class="mr-btn mr-btn-primary mr-btn-sm">
                </div>
                </form>
            </div>
        </div>
    </div>
</body>

<script language="javascript">
    function check_login() {
        var user = document.getElementById("user");           //获取账户信息
        var password = document.getElementById("password");  //获取密码信息
        // console.log(user.value);
        //验证账号是否为空
        if (user.value === '' || user.value === null) {
            alert("账号不能为空！");
            user.focus();
            user.style.backgroundColor = 'bisque';
            return;
        }
        //验证密码是否为空
        if (password.value === '' || password.value === null) {
            alert("密码不能为空！");
            password.focus();
            password.style.backgroundColor = 'bisque';
            return;
        }
        //验证密码是否符合条件
        // if (password.value.length < 8 || password.value.length > 16) {
        //     alert('输入8-16位密码！');
        // } else {
        //     alert('登录成功！');
        // }

        var username = user.value;
        window.localStorage.name = username;
    }
</script>

</html>