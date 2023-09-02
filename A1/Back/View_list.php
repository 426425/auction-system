<?php
include 'database.php';
connectDatabase();
global $conn;

$sql = "SELECT * FROM list";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>拍卖专场列表</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h2>拍卖专场列表</h2>
<table>
    <tr>
        <th>序列</th>
        <th>名称</th>
        <th>方式</th>
        <th>拍卖模式</th>
        <th>起始时间</th>
        <th>结束时间</th>
        <th>起拍价</th>
        <th>保留价</th>
        <th>加价幅度</th>
        <th>延时周期</th>
        <th>所属专场</th>
        <th>保证金</th>
        <th>重复拍</th>
        <th>同步拍</th>
        <th>直播间</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['序列'] . "</td>";
            echo "<td>" . $row['名称'] . "</td>";
            echo "<td>" . $row['方式'] . "</td>";
            echo "<td>" . $row['拍卖模式'] . "</td>";
            echo "<td>" . $row['起始时间'] . "</td>";
            echo "<td>" . $row['结束时间'] . "</td>";
            echo "<td>" . $row['起拍价'] . "</td>";
            echo "<td>" . $row['保留价'] . "</td>";
            echo "<td>" . $row['加价幅度'] . "</td>";
            echo "<td>" . $row['延时周期'] . "</td>";
            echo "<td>" . $row['所属专场'] . "</td>";
            echo "<td>" . $row['保证金'] . "</td>";
            echo "<td>" . $row['重复拍'] . "</td>";
            echo "<td>" . $row['同步拍'] . "</td>";
            echo "<td>" . $row['直播间'] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='15'>暂无数据</td></tr>";
    }
    closeDatabase();
    ?>
</table>
</body>
</html>
