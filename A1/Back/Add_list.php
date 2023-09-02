<?php
include 'database.php';
connectDatabase();
global $conn;

// 获取最大序列值
$sql = "SELECT MAX(序列) AS max_id FROM list";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$maxId = $row['max_id'];

// 添加拍卖专场
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sessionid = 0; // 默认为0，不显示
    $name = $_POST['name'];
    $tool = $_POST['tool'];
    $mode = $_POST['mode'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];
    $initialPrice = $_POST['initial_price'];
    $reservePrice = $_POST['reserve_price'];
    $increment = $_POST['increment'];
    $delay = $_POST['delay'];
    $session = $_POST['session'];
    $deposit = $_POST['deposit'];
    $repeat = $_POST['repeat'];
    $sync = $_POST['sync'];
    $liveroom = $_POST['liveroom'];

    $sql = "INSERT INTO list (序列, 专场序列, 名称, 方式, 拍卖模式, 起始时间, 结束时间, 起拍价, 保留价, 加价幅度, 延时周期, 所属专场, 保证金, 重复拍, 同步拍, 直播间) VALUES ($maxId + 1, '$sessionid', '$name', '$tool', '$mode', '$startTime', '$endTime', $initialPrice, $reservePrice, $increment, $delay, '$session', $deposit, '$repeat', '$sync', '$liveroom')";
    $result = $conn->query($sql);

    if ($result) {
        echo "<p>上传成功</p>";
    } else {
        echo "<p>上传失败: " . $conn->error . "</p>";
    }

    closeDatabase();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>添加拍卖专场</title>
</head>
<body>
<h2>添加拍卖专场</h2>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <!-- 序列不显示，使用隐藏域 -->
    <input type="hidden" name="id" value="<?php echo $maxId + 1; ?>">

    <!-- 专场序列不显示，使用隐藏域 -->
    <input type="hidden" name="sessionid" value="0">

    <label for="name">名称：</label>
    <input type="text" name="name" required><br><br>

    <label for="tool">方式：</label>
    <select name="tool">
        <option value="线上自动">线上自动</option>
        <option value="线下手动">线下手动</option>
    </select><br><br>

    <label for="mode">拍卖模式：</label>
    <select name="mode">
        <option value="升价拍">升价拍</option>
        <option value="降价拍">降价拍</option>
        <option value="密封拍">密封拍</option>
        <option value="多件拍">多件拍</option>
    </select><br><br>

    <label for="start_time">起始时间：</label>
    <input type="datetime-local" name="start_time" required><br><br>

    <label for="end_time">结束时间：</label>
    <input type="datetime-local" name="end_time" required><br><br>

    <label for="initial_price">起拍价：</label>
    <input type="number" name="initial_price" value="1" min="1" required><br><br>

    <label for="reserve_price">保留价：</label>
    <input type="number" name="reserve_price" value="1" min="1" required><br><br>

    <label for="increment">加价幅度：</label>
    <input type="number" name="increment" value="1" min="1" required><br><br>

    <label for="delay">延时周期：</label>
    <input type="number" name="delay" value="5" min="1" required><br><br>

    <label for="session">所属专场：</label>
    <select name="session">
        <option value="无">无</option>
        <option value="新建专场">新建专场</option>
        <option value="加入专场">加入专场</option>
    </select><br><br>

    <label for="deposit">保证金：</label>
    <input type="number" name="deposit" value="0" min="0" required><br><br>

    <label for="repeat">重复拍：</label>
    <select name="repeat">
        <option value="手动上拍">手动上拍</option>
        <option value="拍卖重复">拍卖重复</option>
    </select><br><br>

    <label for="sync">同步拍：</label>
    <select name="sync">
        <option value="是">是</option>
        <option value="否">否</option>
    </select><br><br>

    <label for="liveroom">直播间：</label>
    <input type="text" name="liveroom" required><br><br>

    <input type="submit" value="确定">
</form>
</body>
</html>
