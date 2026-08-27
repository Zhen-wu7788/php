<!--
    看前須知：
    1.CSS/JS大多是由AI修改，AI部分皆已註記'AI'，其中'*'為自己寫的部分
    2.影片內容為任務如果超過7天未完成，下次新增任務時會自動刪除
-->
<?php
    include ("config.php");

    // 取得最新的使用者資料
    $user_query = $link->query("SELECT * FROM users ORDER BY id DESC LIMIT 1");
    $user = $user_query->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        header("Location: register.php"); // 若無資料則去註冊/測驗
        exit;
    }

    // 設定等級
    $levels = [
        "嬰兒" => 0, "幼兒" => 1001, "初級小孩" => 4001, "中級小孩" => 15001, 
        "高級小孩" => 24001, "頂級小孩" => 33001, "初級大人" => 48001, 
        "中級大人" => 98001, "高級大人" => 248001, "頂級大人" => 448001
    ];

    // 自動判定目前等級 ，當積分超過門檻時更新等級
    $identity = "嬰兒"; // 預設等級
    foreach (array_reverse($levels) as $lv_name => $min_score) {
        if ($user['score'] >= $min_score) {
            $identity = $lv_name;
            $now_lv_name = $lv_name;
            break;
        }
    }

    // 如果計算出的等級與資料庫不同，執行 Update
    if ($identity !== $user['identity']) {
        $update_sql = "UPDATE `users` SET `identity` = :iden WHERE `id` = :id";
        $update_stmt = $link->prepare($update_sql);
        $update_stmt->execute([':iden' => $identity, ':id' => $user['id']]);
        $user['identity'] = $identity; // 更新當前變數
    }


    // 取得任務列表
    $tasks = $link->query("SELECT * FROM tasks ORDER BY t_id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>小目標系統 - 主介面</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="page-index">
        <div class="container">
            <h2>小目標系統</h2>
            
            <!-- 使用者卡片 -->
            <div class="user-info-card">
                <div class="avatar"></div>
                <div class="user-details">
                    <div class="user-name"><?php echo htmlspecialchars($user['nickname']); ?></div>
                    <div class="user-rank"><?php echo htmlspecialchars($user['identity']); ?></div>
                    <div class="exp-bar-bg">
                        <div id="exp-fill" class="exp-bar-fill"></div>
                    </div>
                    <div class="score-info">
                        累計積分: <strong><?php echo $user['score']; ?></strong>
                        <?php 
                            //AI：計算下一階門檻
                            $scores_higher = array_values(array_filter(array_values($levels), function($v) use ($user) { return $v > $user['score']; }));
                            $next_level_score = !empty($scores_higher) ? reset($scores_higher) : null;
                            if ($next_level_score) echo " / 下一階門檻: <strong>$next_level_score</strong>";
                        ?>
                    </div>
                </div>
            </div>

            <!-- 新增任務 -->
            <div class="add-task-section">
                <a href="add_task.php"><button>新增任務</button></a>
            </div>

            <!-- 任務列表 -->
            <div class="task-grid">
                <?php 
                    // 按類型分類任務
                    $categorized_tasks = ['weekly' => [], 'monthly' => [], 'special' => []];
                    foreach($tasks as $t) {
                        if(isset($categorized_tasks[$t['type']])) {
                            $categorized_tasks[$t['type']][] = $t;
                        }
                    }
                ?>
                
                <div class="task-column">
                    <h4>每週任務</h4>
                    <?php foreach($categorized_tasks['weekly'] as $t): ?>
                        <div class="task-item">
                            <div class="task-name"><?php echo htmlspecialchars($t['task_name']); ?></div><!--任務名稱-->
                            <div class="task-points">+<?php echo $t['points']; ?> 分</div><!--任務積分-->
                            <div class="task-btns">
                                <a href='edit_task.php?id=<?php echo $t['t_id']; ?>'><img src='修改圖.png' alt='編輯' class="btn-icon"></a><!--編輯-->
                                <a href='done.php?id=<?php echo $t['t_id']; ?>'><button class="btn-done">完成</button></a><!--完成-->
                                <a href='delete_task.php?id=<?php echo $t['t_id']; ?>' onclick="return confirm('確定刪除？')"><button class="btn-del">刪除</button></a><!--刪除-->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="task-column">
                    <h4>每月任務</h4>
                    <?php foreach($categorized_tasks['monthly'] as $t): ?>
                        <div class="task-item">
                            <div class="task-name"><?php echo htmlspecialchars($t['task_name']); ?></div><!--任務名稱-->
                            <div class="task-points">+<?php echo $t['points']; ?> 分</div><!--任務積分-->
                            <div class="task-btns">
                                <a href='edit_task.php?id=<?php echo $t['t_id']; ?>'><img src='修改圖.png' alt='編輯' class="btn-icon"></a><!--編輯-->
                                <a href='done.php?id=<?php echo $t['t_id']; ?>'><button class="btn-done">完成</button></a><!--完成-->
                                <a href='delete_task.php?id=<?php echo $t['t_id']; ?>' onclick="return confirm('確定刪除？')"><button class="btn-del">刪除</button></a><!--刪除-->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="task-column">
                    <h4>特殊任務</h4>
                    <?php foreach($categorized_tasks['special'] as $t): ?>
                        <div class="task-item">
                            <div class="task-name"><?php echo htmlspecialchars($t['task_name']); ?></div><!--任務名稱-->
                            <div class="task-points">+<?php echo $t['points']; ?> 分</div><!--任務積分-->
                            <div class="task-btns">
                                <a href='edit_task.php?id=<?php echo $t['t_id']; ?>'><img src='修改圖.png' alt='編輯' class="btn-icon"></a><!--編輯-->
                                <a href='done.php?id=<?php echo $t['t_id']; ?>'><button class="btn-done">完成</button></a><!--完成-->
                                <a href='delete_task.php?id=<?php echo $t['t_id']; ?>' onclick="return confirm('確定刪除？')"><button class="btn-del">刪除</button></a><!--刪除-->
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <script>//js都是AI，*=自己寫
                // 計算經驗條進度
                document.addEventListener('DOMContentLoaded', function() {
                    const score = <?php echo $user['score']; ?>;//*php自己寫
                    const levels = <?php echo json_encode(array_values($levels)); ?>;
                    
                    let currentLevel = 0;
                    let nextLevel = 1001;
                    
                    // 找出當前等級和下一等級
                    for(let i = 0; i < levels.length; i++) {
                        if(score >= levels[i]) {
                            currentLevel = levels[i];
                            if(i < levels.length - 1) nextLevel = levels[i + 1];
                        }
                    }
                    
                    // 計算進度條佔比
                    const progress = Math.min(((score - currentLevel) / (nextLevel - currentLevel)) * 100, 100);
                    document.getElementById('exp-fill').style.width = progress + '%';
                });
            </script>
        </div>
    </body>
</html>