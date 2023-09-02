<!DOCTYPE html>
<html>
<head>
    <title>查看图片</title>
    <style>
        table {
            border-collapse: collapse;
        }

        td, th {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<h1>查看图片</h1>

<table style="border-spacing: 0 105px;">
    <tr>
        <th>序列</th>
        <th>图片</th>
        <th>名称</th>
        <th>标签</th>
        <th>设置保证金</th>
        <th>金额</th>
        <th>修改重复拍</th>
        <th>直播间</th>
    </tr>

    <?php
    require_once 'database.php'; // 调用database
    global $conn;
    connectDatabase();
    $classSql = "SELECT * FROM session"; // 从session表读取所有数据
    $result = $conn->query($classSql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $sequence = $row['序列'];
            $imageData = $row['图'];
            $name = $row['名称'];
            $label = $row['标签'];
            $deposit = $row['设置保证金'];
            $amount = $row['金额'];
            $repeat = $row['修改重复拍'];
            $livestream = $row['直播间'];

            echo "<tr>";
            echo "<td>$sequence</td>";
            echo '<td><img src="data:image/jpeg;base64,' . base64_encode($imageData) . '" width="100" height="100" /></td>';
            echo "<td>$name</td>";
            echo "<td>$label</td>";
            echo "<td>$deposit</td>";
            echo "<td>$amount</td>";
            echo "<td>$repeat</td>";
            echo "<td>$livestream</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='8'>没有图片</td></tr>";
    }
    $conn->close();
    ?>
</table>

</body>
</html>
