<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auction";

// 创建数据库连接
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

function closeDatabase() {
    global $conn;
    if ($conn !== null) {
        $conn->close();
        $conn = null;
    }
}

function checkDatabaseAndTables()
{
    global $servername, $username, $password, $dbname;
    $dbConnection = new mysqli($servername, $username, $password); // 创建数据库连接 因为$conn已经有定义，$dbConnection中没有dbname如果仍旧使用$conn会覆盖其他文件对$conn的使用所以换一个命名

    if ($dbConnection->connect_error) {
        die("数据库连接失败!");
    }

    $sql = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'";     // 检查数据库是否存在
    $result = $dbConnection->query($sql);

    if ($result->num_rows == 0) // 不存在就创建数据库
    {
        $createDbSql = "CREATE DATABASE $dbname";
        if ($dbConnection->query($createDbSql) !== TRUE) {
            $dbConnection->close();
            die("数据库创建失败!");
        }
    }

    $dbConnection->select_db($dbname); // 选择数据库

    $tables = array(
        'user'  => 'id INT(10), username VARCHAR(20), password VARCHAR(20)',
        'goods' => 'id INT(11) PRIMARY KEY, name VARCHAR(100), classid INT(11), introduce TEXT, price INT(10)',
        'class' => 'id INT(11) PRIMARY KEY, name VARCHAR(11)',
        'brand' => 'id INT(10), name VARCHAR(20)',
        'specs' => 'id INT(10), name VARCHAR(20)',
        'sift' => 'id INT(10), name VARCHAR(20)'
    );

    foreach ($tables as $tableName => $tableColumns) {
        // 检查表是否存在
        $checkTableSql = "SHOW TABLES LIKE '$tableName'";
        $result = $dbConnection->query($checkTableSql);

        if ($result->num_rows == 0) {
            // 创建表
            $createTableSql = "CREATE TABLE $tableName ($tableColumns)";
            if ($dbConnection->query($createTableSql) !== TRUE) {
                echo "创建表 '$tableName' 失败!";
            }
        }

        // 检查id字段是否为主键
        $checkPrimaryKeySql = "SHOW KEYS FROM $tableName WHERE Column_name = 'id' AND Key_name = 'PRIMARY'";
        $result = $dbConnection->query($checkPrimaryKeySql);

        if ($result->num_rows == 0) {
            // 将id字段设置为主键
            $alterTableSql = "ALTER TABLE $tableName MODIFY COLUMN id INT(11) PRIMARY KEY";
            if ($dbConnection->query($alterTableSql) !== TRUE) {
                echo "设置表 '$tableName' 的 id 字段为主键时发生错误!";
            }
        }
    }

    $dbConnection->close(); // 关闭数据库连接

    echo "初始化完毕！";
}



?>
