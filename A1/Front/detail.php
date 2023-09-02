<!DOCTYPE html>
<html>
<head>
    <title>商品详情</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
        }

        .container {
            display: flex;
        }

        .image-container {
            flex-basis: 50%;
            padding-right: 20px;
        }

        .image-container img {
            width: 100%;
        }

        .details-container {
            flex-basis: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .details-container h2 {
            text-align: center;
            margin-bottom: 10px;
        }

        .details-container p {
            text-align: center;
            margin: 0;
            font-size: 16px;
        }

        .bid-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        .bid-input {
            width: 200px;
            height: 30px;
            margin-bottom: 10px;
            padding: 5px;
            font-size: 16px;
        }

        .bid-button {
            background-color: #8B0000;
            color: white;
            border: none;
            width: 150px;
            height: 40px;
            font-size: 16px;
            cursor: pointer;
        }

        .list-container {
            margin-top: 20px;
            font-size: 8px;
            column-count: 2;
        }

        .list-container ul {
            list-style-type: none;
        }

        .list-container li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
<?php
require_once 'database.php';
global $conn;
connectDatabase();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 获取商品详情
    $goodsSql = "SELECT * FROM goods WHERE id = " . $id;
    $goodsResult = $conn->query($goodsSql);

    if ($goodsResult->num_rows > 0) {
        $goodsRow = $goodsResult->fetch_assoc();
        $name = $goodsRow['name'];
        $price = $goodsRow['price'];
        $imageData = $goodsRow['image'];

        echo '<h1>商品详情</h1>';
        echo '<div class="container">';
        echo '  <div class="image-container">';
        echo '    <img src="data:image/jpeg;base64,' . base64_encode($imageData) . '">';
        echo '  </div>';
        echo '  <div class="details-container">';
        echo '    <h2>' . $name . '</h2>';
        echo '    <p>价格：' . $price . '</p>';

        // 添加出价表单
        echo '    <form action="" method="post" class="bid-form">'; // 修改 action 属性为当前页面
        echo '      <input type="hidden" name="id" value="' . $id . '">';
        echo '      <input type="number" name="bidPrice" class="bid-input" step="0.01" required placeholder="请输入出价">';
        echo '      <button type="submit" class="bid-button">提交出价</button>';
        echo '    </form>';
        echo '  </div>';
        echo '</div>';

        // 根据 listid 获取 list 表中与 goods 表 listid 一致的序列详情
        $listId = $goodsRow['listid'];
        $listSql = "SELECT * FROM list WHERE 序列 = " . $listId;
        $listResult = $conn->query($listSql);

        if ($listResult->num_rows > 0) {
            echo '<div class="list-container">';
            while ($listRow = $listResult->fetch_assoc()) {
                echo '<ul>';
                foreach ($listRow as $key => $value) {
                    if ($key != '序列' && $key != '专场名称') {
                        echo '<li>' . $key . ': ' . $value . '</li>';
                    }
                }
                echo '</ul>';
            }
            echo '</div>';
        } else {
            echo '<p>找不到对应的专场</p>';
        }

        // 处理用户提交的出价
        if (isset($_POST['bidPrice'])) {
            $bidPrice = $_POST['bidPrice'];

            // 获取商品的sessionid
            $getSessionIdSql = "SELECT sessionid FROM goods WHERE id = " . $id;
            $sessionIdResult = $conn->query($getSessionIdSql);

            if ($sessionIdResult->num_rows > 0) {
                $sessionIdRow = $sessionIdResult->fetch_assoc();
                $sessionId = $sessionIdRow['sessionid'];

                // 更新商品价格
                $updatePriceSql = "UPDATE goods SET price = '" . $bidPrice . "' WHERE sessionid = '" . $sessionId . "'";
                if ($conn->query($updatePriceSql) === TRUE) {
                    // 更新竞拍次数
                    $updateAuctionCountSql = "UPDATE session SET 竞拍次数 = 竞拍次数 + 1 WHERE 序列 = " . $sessionId;
                    if ($conn->query($updateAuctionCountSql) === TRUE) {
                        echo '<p>出价成功，竞拍次数已更新。</p>';
                    } else {
                        echo '<p>出价成功，但竞拍次数更新失败。</p>';
                    }
                } else {
                    echo '<p>更新商品价格失败。</p>';
                }
            } else {
                echo '<p>获取商品的sessionid失败。</p>';
            }
        }
    } else {
        echo "<p>找不到对应的商品</p>";
    }
} else {
    echo "<p>参数错误</p>";
}

closeDatabase();
?>
</body>
</html>
