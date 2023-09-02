<?php
include 'database.php';
global $conn;
connectDatabase();

// 更新list表中对应元组的"专场序列"属性的值
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['special_sequence'])) {
        $specialSequence = $_POST['special_sequence'];

        // 获取选中的Session的序列值
        $selectedSession = $_POST['selected_session'];

        $updateSql = "UPDATE list SET 专场序列 = '$selectedSession' WHERE 序列 = '$specialSequence'";
        $updateResult = $conn->query($updateSql);
        if ($updateResult) {
            exit("更新成功");
        } else {
            exit("更新失败: " . $conn->error);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .highlight {
            background-color: lightyellow;
        }
    </style>
    <script>
        function updateSpecialSession(specialSequence) {
            var selectedSession = document.querySelector('.highlight').getAttribute('data-session');

            if (!selectedSession) {
                alert('请先选择一个Session');
                return;
            }

            var data = new FormData();
            data.append('special_sequence', specialSequence);
            data.append('selected_session', selectedSession);

            fetch('<?php echo $_SERVER['PHP_SELF']; ?>', {
                method: 'POST',
                body: data
            })
                .then(response => response.text())
                .then(result => {
                    alert(result);
                    location.reload(); // 刷新页面重新读取数据
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
</head>
<body>
<h3>Session</h3>
<table>
    <tr>
        <th>序列</th>
        <th>名称</th>
    </tr>
    <?php
    $sql = "SELECT 序列, 名称 FROM session";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr onclick='highlightSession(this)' data-session='".$row['序列']."'>";
            echo "<td>".$row['序列']."</td>";
            echo "<td>".$row['名称']."</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan=\"2\">没有数据</td></tr>";
    }
    ?>
</table>

<h3>List</h3>
<table>
    <tr>
        <th>序列</th>
        <th>名称</th>
        <th>专场序列</th>
        <th>操作</th>
    </tr>
    <?php
    $listSql = "SELECT 序列, 名称, 专场序列 FROM list";
    $listResult = $conn->query($listSql);

    if ($listResult->num_rows > 0) {
        while ($listRow = $listResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$listRow['序列']."</td>";
            echo "<td>".$listRow['名称']."</td>";
            echo "<td>".$listRow['专场序列']."</td>";
            echo "<td><button onclick='updateSpecialSession(".$listRow['序列'].")'>添加</button></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan=\"4\">没有数据</td></tr>";
    }
    ?>
</table>

<script>
    function highlightSession(tr) {
        var rows = document.querySelectorAll('tr');
        for (var i = 0; i < rows.length; i++) {
            rows[i].classList.remove('highlight');
        }

        tr.classList.add('highlight');
    }
</script>

</body>
</html>
