<?php
    include ("config.php");

    $error = '';

    if (isset($_POST['task_name'])) {
        $name = trim($_POST['task_name']);
        $type = $_POST['type'];
        $pts = $_POST['points'];

        // 驗證輸入
        if (empty($name)) {
            $error = '任務名稱不能為空';
        } else {
            // 清除已過期的任務
            $clean_sql = "DELETE FROM tasks WHERE (type = 'weekly' AND created_at < DATE_SUB(NOW(), INTERVAL 7 DAY)) OR (type = 'monthly' AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY))";
            $link->exec($clean_sql);

            // 檢查任務數量限制
            $task_count = 0;
            
            if ($type === 'weekly') {
                // 檢查週任務數
                $count_sql = "SELECT COUNT(*) as cnt FROM tasks WHERE type = 'weekly' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                $task_count = $link->query($count_sql)->fetch(PDO::FETCH_ASSOC)['cnt'];
                
                if ($task_count >= 10) {
                    $error = '每週任務已達上限 (10個)，請在7天後重試';
                }
            } elseif ($type === 'monthly') {
                // 檢查月任務數
                $count_sql = "SELECT COUNT(*) as cnt FROM tasks WHERE type = 'monthly' AND created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
                $task_count = $link->query($count_sql)->fetch(PDO::FETCH_ASSOC)['cnt'];
                
                if ($task_count >= 20) {
                    $error = '每月任務已達上限 (20個)，請在30天後重試';
                }
            }
            
            // 如果沒有錯誤，添加任務
            if (empty($error)) {
                $sql = "INSERT INTO tasks (task_name, type, points, created_at) VALUES (:name, :type, :pts, NOW())";
                $stmt = $link->prepare($sql);
                $stmt->execute([':name' => $name, ':type' => $type, ':pts' => $pts]);

                header("Location: index.php");
                exit;
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>新增任務</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="page-add-task">
        <div class="container">
            <h2>新增任務</h2>
            
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form action="add_task.php" method="post">
                <div class="form-group">
                    <label for="task_name">任務</label>
                    <input type="text" id="task_name" name="task_name" placeholder="請輸入任務內容" required>
                    <div class="help-text">輸入要完成的任務</div>
                </div>
                
                <div class="form-group">
                    <label for="type">任務屬性</label>
                    <select id="type" name="type" required onchange="updatePointsOptions()">
                        <option value="">請選擇任務屬性</option>
                        <option value="weekly">每週任務 (最多10個，7天後重置)</option>
                        <option value="monthly">每月任務 (最多20個，30天後重置)</option>
                        <option value="special">特殊任務 (無限制)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="points">積分</label>
                    <select id="points" name="points" required>
                        <option value="">請先選擇任務屬性</option>
                    </select>
                    <div class="help-text">完成任務時獲得的積分數</div>
                </div>
                
                <div class="button-group">
                    <input type="submit" value="新增任務">
                    <a href="index.php" class="btn-back">返回</a>
                </div>
            </form>
        </div>

        <script>//js都是AI
            function updatePointsOptions() {
                const typeSelect = document.getElementById('type');
                const pointsSelect = document.getElementById('points');
                const type = typeSelect.value;
                
                pointsSelect.innerHTML = '';
                
                if (type === 'weekly') {
                    // 每週任務：1-10 分
                    for (let i = 1; i <= 10; i++) {
                        const option = document.createElement('option');
                        option.value = i;
                        option.text = i + ' 分';
                        pointsSelect.appendChild(option);
                    }
                } else if (type === 'monthly') {
                    // 每月任務：10-100 分
                    const values = [10, 20, 30, 40,50, 60,70, 80, 90, 100];
                    values.forEach(val => {
                        const option = document.createElement('option');
                        option.value = val;
                        option.text = val + ' 分';
                        pointsSelect.appendChild(option);
                    });
                } else if (type === 'special') {
                    // 特殊任務：100-1000 分
                    const values = [100, 200, 300,400,500,600, 700,800, 900, 1000];
                    values.forEach(val => {
                        const option = document.createElement('option');
                        option.value = val;
                        option.text = val + ' 分';
                        pointsSelect.appendChild(option);
                    });
                }
            }
        </script>

    </body>
</html>