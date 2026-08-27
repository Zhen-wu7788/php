<?php
    include ("config.php");

    if (isset($_GET['id'])) {
        $t_id = $_GET['id'];

        //  取得該任務的分數
        $sql_task = "SELECT points FROM tasks WHERE t_id = ?";
        $stmt_task = $link->prepare($sql_task);
        $stmt_task->execute([$t_id]);
        $task = $stmt_task->fetch();

        if ($task) {
            $pts = $task['points'];

            // 更新總分
            $sql_user = "UPDATE users SET score = score + ? ORDER BY id DESC LIMIT 1";
            $stmt_user = $link->prepare($sql_user);
            $stmt_user->execute([$pts]);

            // 刪除已完成的任務
            $sql_del = "DELETE FROM tasks WHERE t_id = ?";
            $stmt_del = $link->prepare($sql_del);
            $stmt_del->execute([$t_id]);
        }

        header("Location: index.php");
        exit;
    }
?>