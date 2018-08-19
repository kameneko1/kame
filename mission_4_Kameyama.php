<?php
//sql接続
$dsn = 'データベース名';
$user = 'ユーザー名';
define(PASS,'パスワード');
$pdo = new PDO ($dsn,$user,PASS);


$sql = "CREATE TABLE tbtestk"
  ."("
  ."id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,"
  ."name char(32),"
  ."comment TEXT,"
  ."day DATE"
  .")";

$stmt = $pdo -> query($sql);

$password = $_POST['pass'];
$table_name = "tbtestk";

$name = $_POST['name'];
$comm = $_POST['comment'];
$ddate=$_POST["ddate"];
$edi_num = $_POST['edi_num'];
$edi_row = $_POST['edi_row'];
$edi_bool = $_POST['edi_bool'];

if(empty($edi_row))
{
  $edi_bool = false;
}

$del_num = $_POST['del_num'];

function check_password($password)
{
		$bool = false;if(empty($password))
			{
			echo 'パスワードを入力してください<br />';
	}
elseif(strpos($password,'パスワード') == true)
	{
		$bool = true;
	}
return $bool;
}

if(!empty($password))
{
	$pdo = new PDO($dsn,$user,$password,array
		(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_EMULATE_PREPARES => false));
  }
//edition
   if($edi_bool){
     if(strstr($password,'パスワード')){
       $sql = "UPDATE $table_name SET name = '$name',comment='$comm', day='$ddate' WHERE id = $edi_row";
       $result = $pdo -> query($sql);
      }
    }
    if(ctype_digit($edi_num)){
     if(strstr($password,'パスワード')){
       $sql = "SELECT * FROM $table_name";
       $result = $pdo -> query($sql);
       foreach($result as $row){
         if($edi_num == $row['id']){
           $edi_row = $edi_num;
           $edi_bool = true;
           $edi_name = $row['name'];
           $edi_comm = $row['comment'];
	   $edi_ddate=$row['ddate'];
         }
       }
      }
    }

//delete
if(ctype_digit($del_num))
	{
		if(strstr($password,'パスワード'))
			{
				$sql = "DELETE FROM $table_name WHERE id = $del_num";
				$result = $pdo -> query($sql);
				echo $del_num.'を削除しました';
			}
	}

//register
if(!empty($name) and !empty($comm) and !$edi_bool)
{
	if(strstr($password,'パスワード'))
		{
			$sql = $pdo -> prepare("INSERT INTO tbtestk (id,name,comment,day) VALUES (:id,:name,:comment,:day)");
			$sql -> bindParam(':name',$name,PDO::PARAM_STR);
			$sql -> bindParam(':comment',$comm,PDO::PARAM_STR);
			$sql -> bindParam(':day',$ddate,PDO::PARAM_STR);
			$sql -> execute();
			$date = date("Y/m/d H:i");
			echo "ようこそ!".$name."さん"."<br/>". $comment."を受け付けました。". $ddate;
		}
}


?>

<!DOCTYPE html>
<html>
<body>

<form action = "mission_4_Kameyama.php" method = "post">
	ユーザー名<input type = "text" name = "name" value = <?php echo $edi_name; ?>><br/>
	パスワード<input type="text" name="pass" value="" placeholder="※必須"><br/>
	<br/>
	コメント <input type = "text" name = "comment" value =<?php echo $edi_comm;?>><br/>
	<br/>
	削除番号<input type = "text" name = "del_num" value = ""><br/>
	編集番号<input type = "text" name = "edi_num" value = ""><br/>
	<br/>

    <input type = "hidden" name = "edi_row" value = <?php echo $edi_row;?>>
    <input type = "hidden" name = "edi_bool" value = <?php echo $edi_bool;?>>
    <input type = "submit" value = "送信">


</form>

</body>
</html>

<?php
 //データベース削除
  //$sql = 'DELETE FROM '.$table_name;
  //$result = $pdo -> query($sql);
 //データベースの要素追加
 //$sql = "INSERT INTO $table_name VALUES('1','boss','test1');

 //データベースの要素削除
 //$sql = "DELETE FROM $table_name where id = 3";

 //型を変更，オートインクリメントに
 //$sql = "ALTER TABLE mybbs CHANGE id id INT AUTO_INCREMENT PRIMARY KEY";
 //$result = $pdo -> query($sql);


 //表示
 $sql = "SELECT * FROM $table_name";
 $result = $pdo -> query($sql);
 echo 'データベース内部<br />';
 echo 'id |'.'name |'.'  comment | '.'day'.'<br>';
	$db_count = 0;
	foreach($result as $row){
	/*
	print_r($row);
	echo '<br/>';
	*/
	echo $row['id'].'|';
	echo $row['name'].'|';
	echo $row['comment'].'<br>';
	echo $row['day'].'<br>';
	$db_count++;
}

	//投稿番号リセット
	if(!$db_count){
	$sql = "ALTER TABLE $table_name AUTO_INCREMENT = 0";
	$result = $pdo -> query($sql);
}
?>
 



