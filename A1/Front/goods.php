<!DOCTYPE html>
<html>
<head>
    <title>专场商品</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 70%;
            margin: 0 auto;
        }

        .item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f0f0f0;
            transition: background-color 0.3s ease;
        }

        .item:hover {
            background-color: #e0e0e0;
        }

        .item img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            margin-right: 20px;
        }

        .item .info {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            margin-right: 20px;
        }
    </style>

</head>
<body>
<div class="container">
    <h1>专场商品</h1>

    <?php
    require_once 'database.php';
    global $conn;
    connectDatabase();

    if (isset($_GET['sequence'])) {
        $sequence = $_GET['sequence'];

        $sessionSql = "SELECT * FROM session WHERE 序列 = " . $sequence;
        $sessionResult = $conn->query($sessionSql);

        if ($sessionResult->num_rows > 0) {
            $sessionRow = $sessionResult->fetch_assoc();
            $sessionId = $sessionRow['序列'];

            $goodsSql = "SELECT * FROM goods WHERE sessionid = " . $sessionId;
            $goodsResult = $conn->query($goodsSql);

            if ($goodsResult->num_rows > 0) {
                while ($goodsRow = $goodsResult->fetch_assoc()) {
                    $name = $goodsRow['name'];
                    $price = $goodsRow['price'];
                    $imageData = $goodsRow['image'];
                    $id = $goodsRow['id'];

                    echo '<a href="detail.php?id=' . $id . '">';
                    echo '<div class="item">';
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($imageData) . '">';
                    echo '<div class="info">';
                    echo '<h2>' . $name . '</h2>';
                    echo '<p>价格：' . $price . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                }
            } else {
                echo "<p>没有商品</p>";
            }
        } else {
            echo "<p>找不到对应的专场</p>";
        }
    } else {
        echo "<p>参数错误</p>";
    }

    $conn->close();
    ?>
</div>
</body>
</html>
