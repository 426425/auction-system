<!DOCTYPE html>
<html>
<head>
    <title>查看图片</title>
    <style>
        body {
            margin: 0;
            padding: 20px;
        }

        .container {
            width: 70%;
            margin: 0 auto;
        }

        .auction-label /*竞拍标签*/
        {
            position: absolute;
            font-size: 22px;
            top: 0;
            right: 0;
            width: 100px;
            height: 150px;
            margin-right: 20px;
            background-color: red;
            color: white;
            display: flex;
            flex-direction: column;
            text-align: center;  /*内容居中*/
            align-items: center; /*横向居中*/
            justify-content: center; /*纵向居中*/
            writing-mode: horizontal-tb; /* 使用writing-mode属性实现纵向文本 */
        }

        .item  /*背景格子样式*/
        {
            display: flex;
            position: relative; /* 添加 position: relative 以使竞拍状态位置正确 */
            align-items: center;
            margin-bottom: 10px;
            padding: 10px;
            background-color: #f0f0f0;
            transition: background-color 0.3s ease;
        }

        .item:hover {background-color: #e0e0e0;} /*鼠标移动到格子上方时高亮*/
        .item img {width: 200px;height: 200px;object-fit: cover;margin-right: 20px;} /*图片大小*/
        /*.item .info {flex: 1;display: flex;flex-direction: column;justify-content: center;margin-right: 20px;}*/
        .auction-count {font-size: 18px;font-weight: bold;} /*竞拍次数字体*/
        .item h2, .item p {margin-bottom: 0; color: #333333;} /*名称与标签*/
        .info {margin-right: 30px; /* 将文字内容向右移动一点 */text-decoration: none; /* 去掉下划线 */}
    </style>
</head>
<body>
<div class="container">
    <h1>查看专场</h1>

    <?php
    require_once 'database.php';
    global $conn;
    connectDatabase();
    $classSql = "SELECT * FROM session";
    $result = $conn->query($classSql);

    if ($result->num_rows > 0)
    {
        while ($row = $result->fetch_assoc()) {
            $sequence = $row['序列'];
            $imageData = $row['图'];
            $name = $row['名称'];
            $tags = $row['标签'];

            echo '<a href="goods.php?sequence=' . $sequence . '" class="item" style="text-decoration: none">';
            echo '<img src="data:image/jpeg;base64,' . base64_encode($imageData) . '">';
            echo '<div class="info">';
            echo '<h2>' . $name . '</h2>';
            echo '<p>标签：' . $tags . '</p>';
            echo '</div>';

            $listSql = "SELECT * FROM list WHERE 专场序列 = " . $sequence;   // 从 list 表获取起始时间和结束时间
            $listResult = $conn->query($listSql);

            $isAuctionInProgress = false; // 初始化竞拍状态为 false

            if ($listResult->num_rows > 0) //根据时间比较是否是拍卖中
            {
                $listRow = $listResult->fetch_assoc();
                $startTime = strtotime($listRow['起始时间']);
                $endTime = strtotime($listRow['结束时间']);
                $currentTime = time();

                if ($currentTime < $startTime) {
                    $auctionStatus = '未开始';
                } elseif ($currentTime <= $endTime) {
                    $auctionStatus = '拍卖中';
                } else {
                    $auctionStatus = '已结束';
                }

                $sessionSql = "SELECT 竞拍次数 FROM session WHERE 序列 = " . $sequence; // 从 session 表获取竞拍次数
                $sessionResult = $conn->query($sessionSql);
                $auctionCount = 0;

                if ($sessionResult->num_rows > 0) {
                    while ($sessionRow = $sessionResult->fetch_assoc()) {
                        $auctionCount += $sessionRow['竞拍次数'];
                    }
                }

                echo '<div class="auction-label">'; // 显示竞拍状态
                if ($auctionStatus == '未开始') { echo '已结束'; }
                elseif ($auctionStatus == '拍卖中') { echo '竞拍中'; }
                elseif ($auctionStatus == '已结束') { echo '已结束'; }

                echo '<div class="auction-count">'; // 竞拍次数
                echo $auctionCount;
                echo '<br>';
                echo '次出价</div>';
                echo '</div>';

                echo '<div class="countdown" id="countdown-' . $sequence . '">'; // 显示倒计时
                echo '倒计时：加载中...';
                echo '</div>';

                // 添加JavaScript动态倒计时
                echo '<script>';
                echo '(function() {';
                echo '    var endTime = ' . strtotime($listRow['结束时间']) . ';';
                echo '    var countdownElement = document.getElementById("countdown-' . $sequence . '");';

                echo '    function updateCountdown() {';
                echo '        var currentTime = Math.floor(Date.now() / 1000);';
                echo '        var remainingTime = endTime - currentTime;';

                echo '        if (remainingTime > 0) {';
                echo '            var hours = Math.floor(remainingTime / 3600);';
                echo '            var minutes = Math.floor((remainingTime % 3600) / 60);';
                echo '            var seconds = remainingTime % 60;';
                echo '            var countdownFormatted = ("0" + hours).slice(-2) + ":" + ("0" + minutes).slice(-2) + ":" + ("0" + seconds).slice(-2);';
                echo '            countdownElement.innerText = "倒计时：" + countdownFormatted;';
                echo '        } else {';
                echo '            countdownElement.innerText = "";'; // 清空显示的倒计时内容
                echo '        }';

                echo '        setTimeout(updateCountdown, 1000);'; // 每秒钟更新一次倒计时
                echo '    }';

                echo '    updateCountdown();'; // 立即开始倒计时
                echo '})();';
                echo '</script>';
            } else {
                echo '<div class="auction-label">'; // 显示竞拍状态
                echo '未开始';
                echo '<div class="auction-count">'; // 竞拍次数
                echo '0';
                echo '<br>';
                echo '次出价</div>';
                echo '</div>';

                echo '<div class="countdown" id="countdown-' . $sequence . '">'; // 显示倒计时
                echo '没有商品拍卖';
                echo '</div>';
            }

            echo '</a>';
        }
    } else {
        echo "<p>没有专场</p>";
    }
    closeDatabase();
    ?>
</div>
</body>
</html>
