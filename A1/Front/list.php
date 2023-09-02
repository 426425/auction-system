<!--未被使用-->
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auction";

// 创建 mysqli 连接
$conn = new mysqli($servername, $username, $password, $dbname);

// 检查连接是否成功
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 获取专场的序列属性数值
$sequence = $_GET['sequence'];

// 查询与专场序列属性数值一致的元组
$listSql = "SELECT * FROM list WHERE 专场序列 = ?";
$listStmt = $conn->prepare($listSql);
$listStmt->bind_param('s', $sequence);
$listStmt->execute();
$result = $listStmt->get_result();

if ($result->num_rows > 0) {
    echo "<h1>拍卖列表：</h1>";

    // 遍历结果集并处理数据
    while ($row = $result->fetch_assoc()) {
        echo "<div style='border: 1px solid #ccc; padding: 10px; margin-bottom: 20px;'>";
        echo "<p>商品名称：{$row['名称']}</p>";

        // 计算倒计时时间差
        $endTime = strtotime($row['结束时间']);
        $currentTime = time();
        $timeDiff = $endTime - $currentTime;

        if ($timeDiff > 0) {
            $days = floor($timeDiff / (60 * 60 * 24));
            $hours = floor(($timeDiff % (60 * 60 * 24)) / (60 * 60));
            $minutes = floor(($timeDiff % (60 * 60)) / 60);
            $seconds = $timeDiff % 60;

            // 输出倒计时的HTML元素，并为其添加一个唯一的ID，以便后续用JavaScript更新倒计时
            echo "<p id='countdown-{$row['商品名称']}'>剩余时间：{$days}天 {$hours}小时 {$minutes}分钟 {$seconds}秒</p>";
            echo "</div>";

            // 在页面底部使用JavaScript来更新倒计时
            echo "<script>";
            echo "function updateCountdown() {";
            echo "    var endTime = new Date('" . $row['结束时间'] . "').getTime();";
            echo "    var currentTime = new Date().getTime();";
            echo "    var timeDiff = endTime - currentTime;";

            // 使用getElementById获取倒计时的HTML元素，并用innerHTML更新其显示内容
            echo "    document.getElementById('countdown-{$row['商品名称']}').innerHTML = formatTime(timeDiff);";
            echo "}";

            // 定义格式化时间的函数，将时间差转换为天、小时、分钟和秒的格式
            echo "function formatTime(timeDiff) {";
            echo "    var days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));";
            echo "    var hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));";
            echo "    var minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));";
            echo "    var seconds = Math.floor((timeDiff % (1000 * 60)) / 1000);";
            echo "    return '剩余时间：' + days + '天 ' + hours + '小时 ' + minutes + '分钟 ' + seconds + '秒';";
            echo "}";

            // 每隔一秒调用updateCountdown函数来更新倒计时
            echo "setInterval(updateCountdown, 1000);";
            echo "</script>";
        } else {
            echo "<p>已结束</p>";
            echo "</div>";
        }
    }
} else {
    echo "没有找到符合条件的元组";
}

closeDatabase();
?>
