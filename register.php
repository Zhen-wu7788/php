<?php
    include ("config.php");

    if ($_POST) {
        $nickname = $_POST['nickname'];
        $gender = $_POST['gender'];
        $birthday = $_POST['year'] . "-" . $_POST['month'] . "-" . $_POST['day'];
        $total_score = array_sum($_POST['q']); // 加總所有題目分數

        // 等級判定
        if ($total_score <= 10) { $identity = "嬰兒"; $start_score = 0; }
        elseif ($total_score <= 20) { $identity = "幼兒"; $start_score = 1001; }
        elseif ($total_score <= 35) { $identity = "初級小孩"; $start_score = 4001; }
        elseif ($total_score <= 50) { $identity = "中級小孩"; $start_score = 15001; }
        elseif ($total_score <= 60) { $identity = "高級小孩"; $start_score = 24001; }
        elseif ($total_score <= 70) { $identity = "初級大人"; $start_score = 48001; }
        elseif ($total_score <= 80) { $identity = "中級大人"; $start_score = 98001; }
        elseif ($total_score <= 90) { $identity = "高級大人"; $start_score = 248001; }
        else { $identity = "頂級大人"; $start_score = 448001; }

        //SQL連線-插入新使用者資料
        $sql = "INSERT INTO users (nickname, gender, birthday, identity, score) VALUES (?, ?, ?, ?, ?)";
        $stmt = $link->prepare($sql);
        $stmt->execute([$nickname, $gender, $birthday, $identity, $start_score]);

        header("Location: index.php");
        exit;
    }

    $survey = [
        [
            'question' => '外送員打電話給你時…',
            'options' => [
                'A. 不接',
                'B. 接了但超慌',
                'C. 正常應對',
                'D. 還會順便確認餐點備註',
            ],
        ],
        [
            'question' => '房間亂掉時你通常…',
            'options' => [
                'A. 等別人受不了',
                'B. 想整理但拖很久',
                'C. 會定期整理',
                'D. 已經有自己的收納邏輯',
            ],
        ],
        [
            'question' => '遇到情緒低潮時…',
            'options' => [
                'A. 完全爆炸',
                'B. 狂滑手機逃避',
                'C. 會慢慢消化',
                'D. 能分析原因並調整狀態',
            ],
        ],
        [
            'question' => '有人請你處理事情時…',
            'options' => [
                'A. 我會裝死',
                'B. 查半天還是不懂',
                'C. 能慢慢完成',
                'D. 已經熟到能教別人',
            ],
        ],
        [
            'question' => '你對金錢的態度比較像…',
            'options' => [
                'A. 花到沒錢再說',
                'B. 偶爾記帳',
                'C. 知道自己收入支出',
                'D. 有長期規劃與存款目標',
            ],
        ],
        [
            'question' => '朋友突然情緒崩潰找你…',
            'options' => [
                'A. 我比他更慌',
                'B. 不知道怎麼安慰',
                'C. 能陪伴傾聽',
                'D. 能安撫又給實際幫助',
            ],
        ],
        [
            'question' => '你做錯事時第一反應是…',
            'options' => [
                'A. 先找理由',
                'B. 想逃避',
                'C. 承認問題',
                'D. 立刻想補救方案',
            ],
        ],
        [
            'question' => '半夜馬桶堵住時…',
            'options' => [
                'A. 等爸媽來處理',
                'B. Google 到崩潰',
                'C. 嘗試自己處理',
                'D. 熟練到像水電師傅',
            ],
        ],
        [
            'question' => '面對不喜歡的人…',
            'options' => [
                'A. 情緒直接寫臉上',
                'B. 勉強裝正常',
                'C. 能維持禮貌',
                'D. 還能穩定社交與合作',
            ],
        ],
        [
            'question' => '你的作息通常…',
            'options' => [
                'A. 完全混亂',
                'B. 偶爾正常',
                'C. 大致穩定',
                'D. 穩定到像人體時鐘',
            ],
        ],
        [
            'question' => '突然被取消約時…',
            'options' => [
                'A. 大暴怒，直接公審',
                'B. 心情差很久',
                'C. 有點失落但還好',
                'D. 直接安排新行程',
            ],
        ],
        [
            'question' => '看到爸媽變老時…',
            'options' => [
                'A. 沒什麼感覺',
                'B. 偶爾想到會難過',
                'C. 開始意識責任',
                'D. 已經主動分擔事',
            ],
        ],
        [
            'question' => '你的手機通知…',
            'options' => [
                'A. 999+',
                'B. 很亂但不會超過999+',
                'C. 大致有整理',
                'D. 幾乎都即時處理完',
            ],
        ],
        [
            'question' => '被批評時…',
            'options' => [
                'A. 直接不爽',
                'B. 表面沒事內心爆炸',
                'C. 能聽進部分內容',
                'D. 能冷靜分析是否有道理',
            ],
        ],
        [
            'question' => '做飯對你來說…',
            'options' => [
                'A. 泡麵就是極限',
                'B. 會簡單料理',
                'C. 能照食譜完成',
                'D. 已進入「冰箱有什麼做什麼」境界',
            ],
        ],
        [
            'question' => '突然停電時…',
            'options' => [
                'A. 我先大聲叫爸媽',
                'B. 開手機手電筒發呆',
                'C. 先檢查狀況',
                'D. 已知道總開關在哪',
            ],
        ],
        [
            'question' => '面對人生未來…',
            'options' => [
                'A. 完全沒想過',
                'B. 想到會焦慮',
                'C. 有大概方向',
                'D. 已經開始一步步執行',
            ],
        ],
        [
            'question' => '你最常拖延的是…',
            'options' => [
                'A. 所有事情',
                'B. 重要事情',
                'C. 少數麻煩事',
                'D. 幾乎不拖',
            ],
        ],
        [
            'question' => '和別人吵架時…',
            'options' => [
                'A. 情緒先爆',
                'B. 一定要贏',
                'C. 願意溝通',
                'D. 能控制情緒並解決問題',
            ],
        ],
        [
            'question' => '看到別人比自己厲害時…',
            'options' => [
                'A. 超嫉妒',
                'B. 有點自卑',
                'C. 當成激勵自己的目標',
                'D. 會去研究對方怎麼做到的',
            ],
        ],
        [
            'question' => '你的網購習慣…',
            'options' => [
                'A. 衝動亂買',
                'B. 常後悔',
                'C. 會比價',
                'D. 能克制非必要消費',
            ],
        ],
        [
            'question' => '突然要獨自出國…',
            'options' => [
                'A. 我不行',
                'B. 很焦慮',
                'C. 可以努力完成',
                'D. 已經能自己規劃行程',
            ],
        ],
        [
            'question' => '對「孤獨」的感覺…',
            'options' => [
                'A. 無法接受',
                'B. 很容易空虛',
                'C. 偶爾享受獨處',
                'D. 能自在跟自己相處',
            ],
        ],
        [
            'question' => '你的情緒穩定度…',
            'options' => [
                'A. 像雲霄飛車',
                'B. 很容易被影響',
                'C. 大致穩定',
                'D. 壓力來時也能冷靜',
            ],
        ],
        [
            'question' => '你覺得真正的大人是…',
            'options' => [
                'A. 很無聊的人',
                'B. 有錢的人',
                'C. 能照顧自己的人',
                'D. 能照顧自己也照顧別人的人',
            ],
        ],
    ];
?>
<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>人生階級測驗</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body class="page-register">
        <div class="container">
            <!-- 基本資料 -->
            <div id="step1">
                <div class="progress-indicator"><!-- 進度條 -->
                    <div class="progress-step active"></div>
                    <div class="progress-step"></div>
                    <div class="progress-step"></div>
                </div>
                <h2>歡迎來到小目標系統</h2>
                <p class="description-text">請先輸入您的基本資料</p>
                
                <form id="basicForm">
                    <input type="text" id="u-name" placeholder="請輸入暱稱" required>
                    
                    <select id="u-gender" required>
                        <option value="">請選擇性別</option>
                        <option value="男">男</option>
                        <option value="女">女</option>
                        <option value="其他">其他</option>
                    </select>
                    
                    <label>生日</label>
                    <div class="inline-inputs">
                        <input type="number" id="u-year" placeholder="西元年份" min="1900" max="2030" required>
                        <input type="number" id="u-month" placeholder="月份" min="1" max="12" required>
                        <input type="number" id="u-day" placeholder="日期" min="1" max="31" required>
                    </div>
                    
                    <button type="button" onclick="startQuiz()">開始測驗</button>
                </form>
            </div>

            <!-- 測驗 -->
            <div id="step2" class="hidden"><!-- 進度條 -->
                <div class="progress-indicator">
                    <div class="progress-step active"></div>
                    <div class="progress-step active"></div>
                    <div class="progress-step"></div>
                </div>
                <h2>人生階級測驗</h2>
                <p class="description-text">請根據您的真實情況回答以下25個問題</p>
                
                <form id="quizForm">
                    <?php foreach ($survey as $i => $item): ?>
                        <div class="quiz-item">
                            <p><?php echo ($i + 1) . ". " . $item['question']; ?></p>
                            <select name="q[]" required>
                                <option value="">請選擇答案</option>
                                <?php foreach ($item['options'] as $optionIndex => $optionText): ?>
                                    <option value="<?php echo $optionIndex; ?>"><?php echo $optionText; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endforeach; ?>
                    
                    <button type="button" onclick="calculateQuiz()">提交測驗結果</button>
                </form>
            </div>

            <!-- 結果展示 -->
            <div id="step3" class="hidden">
                <div class="progress-indicator"><!-- 進度條 -->
                    <div class="progress-step active"></div>
                    <div class="progress-step active"></div>
                    <div class="progress-step active"></div>
                </div>
                <h2>您的測驗結果</h2>
                <div id="result-text"></div>
                <button type="button" onclick="submitForm()">進入系統</button>
            </div>
        </div>

        <form id="hiddenForm" method="post" class="hidden">
            <input type="hidden" id="form-nickname" name="nickname">
            <input type="hidden" id="form-gender" name="gender">
            <input type="hidden" id="form-year" name="year">
            <input type="hidden" id="form-month" name="month">
            <input type="hidden" id="form-day" name="day">
            <div id="form-questions"></div>
        </form>

        <script>//js都是AI 
            let userData = { name: "", gender: "", year: "", month: "", day: "", quizScore: 0 };

            function startQuiz() {
                const name = document.getElementById('u-name').value.trim();
                const gender = document.getElementById('u-gender').value;
                const year = document.getElementById('u-year').value;
                const month = document.getElementById('u-month').value;
                const day = document.getElementById('u-day').value;
                
                if (!name || !gender || !year || !month || !day) {
                    alert("請填寫所有必填欄位");
                    return;
                }
                
                userData.name = name;
                userData.gender = gender;
                userData.year = year;
                userData.month = month;
                userData.day = day;
                
                document.getElementById('step1').classList.add('hidden');
                document.getElementById('step2').classList.remove('hidden');
            }
            // 計算測驗結果                        
            function calculateQuiz() {
                const selects = document.querySelectorAll('#quizForm select');
                let score = 0;
                
                for (let select of selects) {
                    if (!select.value) {
                        alert("請完成所有題目");
                        return;
                    }
                    score += parseInt(select.value);
                }
                
                userData.quizScore = score;
                
                // 判定等級與分析
                let identity = "嬰兒", analysis = "";
                if (score <= 10) { identity = "嬰兒"; analysis = "你目前還處在「世界會自己運轉吧？」的階段。責任感還沒完全開機。"; }
                else if (score <= 20) { identity = "幼兒"; analysis = "你開始接觸「真實世界」，但常常被現實暴擊。"; }
                else if (score <= 35) { identity = "初級小孩"; analysis = "你有一些生活能力，但穩定度不高，偶爾像走失兒童。"; }
                else if (score <= 50) { identity = "中級小孩"; analysis = "你已經開始有成熟感。很多事情能自己處理。"; }
                else if (score <= 60) { identity = "高級小孩"; analysis = "別人開始覺得你可靠，但你自己知道其實還在硬撐。"; }
                else if (score <= 70) { identity = "初級大人"; analysis = "恭喜，你正式踏入大人世界。你能獨立處理多數事情。"; }
                else if (score <= 80) { identity = "中級大人"; analysis = "你開始有「扛事能力」。情緒、生活、金錢已達平衡。"; }
                else if (score <= 90) { identity = "高級大人"; analysis = "你具備成熟穩定的內核，是團體裡最讓人安心的人。"; }
                else { identity = "頂級大人"; analysis = "你根本是「人生 NPC 指導員」。情緒穩定、邏輯成熟的超成熟狀態。"; }
                
                // 顯示結果
                document.getElementById('step2').classList.add('hidden');
                document.getElementById('step3').classList.remove('hidden');
                document.getElementById('result-text').innerHTML = 
                    `恭喜你在測驗中獲得 <strong>${score} 分</strong>！<br>
        初始身份：<strong>${identity}</strong><br><br>
        ${analysis}`;
            }
                                    
            function submitForm() {
                const form = document.getElementById('hiddenForm');
                document.getElementById('form-nickname').value = userData.name;
                document.getElementById('form-gender').value = userData.gender;
                document.getElementById('form-year').value = userData.year;
                document.getElementById('form-month').value = userData.month;
                document.getElementById('form-day').value = userData.day;
                
                //   添加所有測驗答案
                const selects = document.querySelectorAll('#quizForm select');
                let questionsHtml = '';
                selects.forEach(select => {
                    questionsHtml += `<input type="hidden" name="q[]" value="${select.value}">`;
                });
                document.getElementById('form-questions').innerHTML = questionsHtml;
                
                form.submit();
            }
        </script>

    </body>
</html>