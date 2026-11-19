<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>渠道商后台登录</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            padding: 40px 30px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-size: 14px;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: opacity 0.3s;
        }

        .btn-login:hover {
            opacity: 0.9;
        }

        .btn-login:active {
            opacity: 0.8;
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
            }

            .login-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>渠道商后台</h1>
            <p>Channel Admin System</p>
        </div>
        <form method="post" action="" id="loginForm">
            <div class="form-group">
                <label for="username">用户名/手机号</label>
                <input type="text" id="username" name="username" required autocomplete="username" placeholder="请输入用户名或手机号">
            </div>
            <div class="form-group">
                <label for="password">密码</label>
                <input type="password" id="password" name="password" required autocomplete="current-password" placeholder="请输入密码">
            </div>
            <button type="button" class="btn-login" id="loginBtn">登录</button>
            <input type="hidden" name="submit" value="1">
        </form>
        <script type="text/javascript">
            (function() {
                var form = document.getElementById('loginForm');
                var submitBtn = document.getElementById('loginBtn');
                var usernameInput = document.getElementById('username');
                var passwordInput = document.getElementById('password');
                
                if (!form || !submitBtn) {
                    console.error('表单或按钮未找到');
                    return;
                }
                
                // 直接绑定 onclick 事件
                submitBtn.onclick = function() {
                    var username = usernameInput.value.trim();
                    var password = passwordInput.value.trim();
                    
                    if (!username || !password) {
                        alert('请输入用户名和密码！');
                        return false;
                    }
                    
                    submitBtn.disabled = true;
                    submitBtn.textContent = '登录中...';
                    
                    // 创建隐藏的 submit 按钮并点击
                    var hiddenSubmit = document.createElement('input');
                    hiddenSubmit.type = 'submit';
                    hiddenSubmit.name = 'submit';
                    hiddenSubmit.value = '1';
                    hiddenSubmit.style.display = 'none';
                    form.appendChild(hiddenSubmit);
                    
                    // 使用 setTimeout 确保按钮已添加到 DOM
                    setTimeout(function() {
                        hiddenSubmit.click();
                    }, 10);
                    
                    return false;
                };
            })();
        </script>
    </div>
</body>
</html>

