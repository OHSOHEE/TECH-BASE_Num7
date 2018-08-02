<?php
$dsn='mysql:dbname=データベース名;host=localhost';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password);
//テーブル作成
 $sql="CREATE TABLE mission4_4"
 ."("
 ."num INT AUTO_INCREMENT PRIMARY KEY,"
 ."name VARCHAR(255),"
 ."comment TEXT,"
 ."day DATETIME,"
 ."pa CHAR(32)"
 .");";
 $stmt=$pdo->query($sql);
 //テータ受入
  ini_set('default_charset', 'UTF-8');
  if($_SERVER["REQUEST_METHOD"] == "POST") {
     $name=$_POST['名前'];
     $name=htmlspecialchars($name);
     $comment=$_POST['コメント'];
     $comment=htmlspecialchars($comment);
     $day=date("Y/m/d H:i:s");
     $pa=$_POST['パスワード'];
     $pa=htmlspecialchars($pa);
     $d=$_POST['削除'];
     $d=htmlspecialchars($d);
     $dpa=$_POST['password'];
     $dpa=htmlspecialchars($dpa);
     $h=$_POST['編集'];
     $h=htmlspecialchars($h);
     $epa=$_POST['pass'];
     $epa=htmlspecialchars($epa);
     $ed=$_POST['edit'];
     $ed=htmlspecialchars($ed);
     $data=explode("\n",$comment);
     $t=implode("_",$data);
  }
?>
<?php
   if (!empty($name) && !empty($comment) && empty($ed) && !empty($pa)) {
   $p=$pdo -> prepare("INSERT INTO mission4_4 (name,comment,day,pa) VALUES (:name,:comment,:day,:pa)");
   $name=$_POST['名前'];
   $name=htmlspecialchars($name);
   $comment=$_POST['コメント'];
   $comment=htmlspecialchars($comment);
   $data=explode("\n",$comment);
   $t=implode("_",$data);
   $day=date("Y/m/d H:i:s");
   $pa=$_POST['パスワード'];
   $pa=htmlspecialchars($pa);
   $p -> bindParam(':name',$name,PDO::PARAM_STR);
   $p -> bindParam(':comment',$t,PDO::PARAM_STR);
   $p -> bindParam(':day',$day,PDO::PARAM_STR);
   $p -> bindParam(':pa',$pa,PDO::PARAM_STR);
   $p -> execute();
   }
   //編集データ取得
   if(!empty($h) && !empty($epa)) {
    $e_sql="SELECT*FROM mission4_4 WHERE num = $h";
    $e_stmt=$pdo -> query($e_sql);
    foreach ($e_stmt as $e_row) {
      $e_num=$e_row['num'];
      $e_name=$e_row['name'];
      $e_comment=$e_row['comment'];
      $e_pa=$e_row['pa'];
    }
   }
   //編集データ入力
   if(!empty($name) && !empty($comment) && !empty($ed) && !empty($pa) && ($epa==$e_pa)){
    $e_p="UPDATE mission4_4 SET name='$name', comment='$t', day='$day', pa='$pa' WHERE num=$ed";
    $e_result=$pdo -> query($e_p);
   }
   //データ削除
   if(!empty($d) && !empty($dpa)){
     $d_sql="SELECT*FROM mission4_4 WHERE num = $d";
     $d_stmt=$pdo -> query($d_sql);
     foreach ($d_stmt as $d_row) {
      $d_num=$d_row['num'];
      $d_name=$d_row['name'];
      $d_comment=$d_row['comment'];
      $d_pa=$d_row['pa'];
      if($dpa==$d_pa){
        $d_p="DELETE FROM mission4_4 WHERE num=$d";
        $d_result=$pdo -> query($d_p);
      }
     }
   }
 ?>
 <!DOCTYPE html>
 <html>
 <body>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
    <input type="text" placeholder="名前" name="名前" 
    value="<?php echo $e_name; ?>"><br><br>
    <textarea placeholder="コメント" name="コメント" size="10" cols="50" rows="10" wrap="soft"><?php $hs=explode("_",$e_comment);
     $hc=implode("",$hs); 
     echo $hc; ?></textarea><br><br>
     <input type="text" placeholder="パスワード" name="パスワード" value="<?php echo $e_pa; ?>"><br><br>
     <input type="hidden" name="edit" value="<?php echo $e_num; ?>">
    <input type="submit" value="送信">
  </form>
  <br>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
    <input type="text" placeholder="編集対象番号" name="編集"><br>
    <input type="text" placeholder="パスワード" name="pass">
    <input type="submit" value="編集">
  <br><br>
  <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>" >
     <input type="text" placeholder="削除対象番号" name="削除"><br>
     <input type="text" placeholder="パスワード" name="password">
     <input type="submit" value="削除" ><br><br>
  </form>
   <?php
     $file='SELECT*FROM mission4_4 ORDER BY num DESC';
     $results=$pdo -> query($file);
     foreach ($results as $file_n){
       $a=explode("_",$file_n['comment']);
       $b=implode("<br>",$a);
        echo "#".$file_n['num']."  &nbsp;&nbsp;&nbsp;&nbsp;  ";
        echo $file_n['name']."  &nbsp;&nbsp;&nbsp;&nbsp;  ";
        echo $file_n['day']."<br>";
        echo $b."<br>";
     }
 ?>
</body>
</html>