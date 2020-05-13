<meta http-equiv="refresh" content="20"> <!每20秒自動刷新>
<?php

$db_host = "localhost";
$db_username = "root";
$db_password = "";
$conn = mysqli_connect($db_host, $db_username, $db_password);
if(!$conn) dis("連線失敗!");	
mysqli_select_db($conn,"test");
if(!mysqli_select_db($conn,"test")) dis("無法開啟資料庫");

$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");

$filter = ['id' => ['$gt' => 0]]; //欄位名及匹配條件
$options = [
'projection' => ['_id' => 0], // 指定返回鍵，
'sort' => ['id' => 1], //排序欄位，1是正序，-1是倒序
];

$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('health.usertest', $query);

foreach ($cursor as $document) {	
	$ans = $document->id;
	$sql = "SELECT * FROM healthTable WHERE id = '{$ans}'";
	$bol = MYSQLI_QUERY($conn ,$sql);	
	if (!mysqli_affected_rows($conn)){
		$username = $document->username;
		$id = $document->id;
		$height = $document->height;
		$weight = $document->weight;
		$add ="INSERT INTO `healthTable` (username,id,height,weight)
		VALUES('{$username}','{$id}','{$height}','{$weight}')";
		MYSQLI_QUERY($conn ,$add);
	}
	print_r($document);
	echo "<br>";
}
?>