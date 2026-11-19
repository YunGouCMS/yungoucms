<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>正在开发中</title>
<style type="text/css">
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    overflow: hidden;
}

.container {
    text-align: center;
    background: rgba(255, 255, 255, 0.95);
    padding: 60px 40px;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    max-width: 500px;
    width: 100%;
    position: relative;
    animation: fadeInUp 0.8s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 30px;
    position: relative;
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);
    }
}

.icon::before {
    content: "🚧";
    font-size: 100px;
    display: block;
    animation: rotate 2s linear infinite;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

h1 {
    font-size: 32px;
    color: #333;
    margin-bottom: 15px;
    font-weight: 600;
    letter-spacing: -0.5px;
}

.message {
    font-size: 18px;
    color: #666;
    line-height: 1.6;
    margin-bottom: 30px;
}

.progress-bar {
    width: 100%;
    height: 6px;
    background: #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    margin: 30px 0;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    animation: progress 2s ease-in-out infinite;
    width: 60%;
}

@keyframes progress {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(400%);
    }
}

.footer {
    margin-top: 30px;
    font-size: 14px;
    color: #999;
}

.decorative-circle {
    position: absolute;
    border-radius: 50%;
    background: rgba(102, 126, 234, 0.1);
    animation: pulse 4s ease-in-out infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 0.5;
    }
    50% {
        transform: scale(1.2);
        opacity: 0.8;
    }
}

.circle-1 {
    width: 200px;
    height: 200px;
    top: -100px;
    right: -100px;
}

.circle-2 {
    width: 150px;
    height: 150px;
    bottom: -75px;
    left: -75px;
    animation-delay: 2s;
}

@media (max-width: 600px) {
    .container {
        padding: 40px 30px;
    }
    
    h1 {
        font-size: 26px;
    }
    
    .message {
        font-size: 16px;
    }
    
    .icon::before {
        font-size: 80px;
    }
}
</style>
</head>
<body>
    <div class="container">
        <div class="decorative-circle circle-1"></div>
        <div class="decorative-circle circle-2"></div>
        
        <div class="icon"></div>
        
        <h1>正在开发中</h1>
        
        <p class="message">
            我们正在努力完善此功能，<br>
            敬请期待！
        </p>
        
        <div class="progress-bar">
            <div class="progress-fill"></div>
        </div>
        
        <div class="footer">
            感谢您的耐心等待
        </div>
    </div>
</body>
</html>