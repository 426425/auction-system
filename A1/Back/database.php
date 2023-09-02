<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auction";

$conn = null;
function connectDatabase() //连接数据库
{
    global $conn, $servername, $username, $password, $dbname;
    if ($conn === null)   // 创建数据库连接
    {
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error)   // 检查数据库连接是否成功
        {
            die("数据库连接失败: " . $conn->connect_error);
        }
    }
}
// 关闭数据库连接
function closeDatabase() {
    global $conn;
    if ($conn !== null) {
        $conn->close();
        $conn = null;
    }
}
?>
