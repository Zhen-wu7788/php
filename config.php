<?php
    $hostname = "localhost";		/* MySQL的主機名稱 */
	$username = "root";		/* MySQL的使用者名稱 */
	$password = "";		/* MySQL的使用者密碼 */
	$database = "db_test";

	// 建立 PDO 數據庫連接
	try {
		$link = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $password);
		$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (PDOException $e) {
		die("數據庫連接失敗: " . $e->getMessage());
	}
?>