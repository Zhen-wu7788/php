<?php
    include ("config.php");

    $error = '';
    $task = null;

    // 取得任務資料
    if (isset($_GET['id'])) {
        $t_id = $_GET['id'];
        $sql = "SELECT * FROM tasks WHERE t_id = ?";
        $stmt = $link->prepare($sql);
        $stmt->execute([$t_id]);
        $task = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$task) {
            header("Location: index.php");
            exit;
        }
    }

    if ($_POST && isset($_POST['task_name'])) {
        $t_id = $_POST['t_id'];
        $name = trim($_POST['task_name']);
        $type = $_POST['type'];
        $pts = $_POST['points'];

        if (empty($name)) {
            $error = '任務名稱不能為空';
        } else {
            // 更新任務資料
            $sql = "UPDATE tasks SET task_name = :name, type = :type, points = :pts WHERE t_id = :id";
            $stmt = $link->prepare($sql);
            $stmt->execute([':name' => $name, ':type' => $type, ':pts' => $pts, ':id' => $t_id]);

            header("Location: index.php");
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>編輯任務</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="page-edit-task">
        <div class="container">
            <h2>編輯任務</h2>

            <?php if (!empty($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($task): ?><!-- 如果有資料則顯示 -->
            <form action="edit_task.php" method="post">
                <input type="hidden" name="t_id" value="<?php echo $task['t_id']; ?>">

                <div class="form-group">
                    <label for="task_name">任務</label>
                    <input type="text" id="task_name" name="task_name" value="<?php echo htmlspecialchars($task['task_name']); ?>" placeholder="請輸入任務內容" required>
                    <div class="help-text">輸入要完成的任務</div>
                </div>

                <div class="form-group">
                    <label for="type">任務屬性</label>
                    <select id="type" name="type" required>
                        <option value="">請選擇任務屬性</option>
                        <option value="weekly" <?php echo $task['type'] === 'weekly' ? 'selected' : ''; ?>>每週任務 (最多10個，7天後重置)</option>
                        <option value="monthly" <?php echo $task['type'] === 'monthly' ? 'selected' : ''; ?>>每月任務 (最多20個，30天後重置)</option>
                        <option value="special" <?php echo $task['type'] === 'special' ? 'selected' : ''; ?>>特殊任務 (無限制)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="points">積分</label>
                    <input type="number" id="points" name="points" value="<?php echo $task['points']; ?>" placeholder="請輸入積分" required>
                    <div class="help-text">完成任務時獲得的積分數</div>
                </div>

                <div class="button-group">
                    <input type="submit" value="更新任務">
                    <a href="index.php" class="btn-back">返回</a>
                </div>
            </form>
            <?php else: ?>
                <p>找不到該任務</p>
            <?php endif; ?>
        </div>



    </body>
</html>
