<?php
include 'database.php';
global $conn;
connectDatabase();


if ($_SERVER["REQUEST_METHOD"] == "POST") { // 添加专场
    $name = $_POST['name'];
    $label = $_POST['label'];
    $deposit = isset($_POST['deposit']) ? $_POST['deposit'] : '否'; //保障金默认为否
    $amount = isset($_POST['amount']) ? $_POST['amount'] : 0; //保证金为否保证金额默认为0

    $image = $_FILES['image']['tmp_name'];  //获取图片文件存储路径,'image' 键访问上传的图片文件, $_FILES 包含了上传文件信息的关联数组
    $imageData = $conn->real_escape_string(file_get_contents($image)); //file_get_contents()读取$image图片文件内容，$conn->real_escape_string() 读取文件的内容返回字符串形式。
                                                                        //$imageData包含转义处理的图片二进制数据

    $repeat = $_POST['repeat'];
    $livestream = $_POST['livestream'];

    $sequence = generateSequence();
    $sql = "INSERT INTO session (序列, 名称, 标签, 设置保证金, 金额, 图, 修改重复拍, 直播间) VALUES('$sequence', '$name', '$label', '$deposit', '$amount', '$imageData', '$repeat', '$livestream')";
    $result = $conn->query($sql);
    if ($result) {
        echo "<p>上传成功</p>";
    } else {
        echo "<p>上传失败: " . $conn->error . "</p>";
    }

    closeDatabase();
}

// 自动生成序列保证序列id唯一方便拍卖与其关联
function generateSequence() {
    global $conn;
    $result = $conn->query("SELECT COUNT(*) FROM session");
    $row = $result->fetch_row();
    $count = $row[0] + 1;
    return $count;
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
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
    <label for="name">名称：</label>
    <input type="text" name="name" required><br><br>

    <label for="label">标签：</label>
    <input type="text" name="label" required><br><br>

    <label for="deposit">设置保证金：</label>
    <select name="deposit">
        <option value="否" selected>否</option>
        <option value="是">是</option>
    </select><br><br>

    <label for="amount">金额：</label>
    <input type="number" name="amount" value="0" min="0"><br><br>

    <label for="image">图片：</label>
    <input type="file" name="image"><br><br>

    <label for="repeat">修改重复拍：</label>
    <select name="repeat">
        <option value="不修改" selected>不修改</option>
        <option value="手动上拍">手动上拍</option>
        <option value="拍卖重复">拍卖重复</option>
        <option value="专场重复">专场重复</option>
    </select><br><br>

    <label for="livestream">直播间：</label>
    <input type="text" name="livestream"><br><br>

    <input type="submit" value="确定">
</form>


</body>
</html>
