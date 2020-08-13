<?php
    // DSNサーバへ接続
    $dsn = 'mysql:dbname=tb******db;host=localhost';
    $user = 'tb-******';
    $password = 'PASSWORD';
    $pdo = new PDO ($dsn, $user, $password, array (PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
?>

<!DOCTYPE HTML>
<html lang = "ja">
    <head>
        <meta charset = "utf-8">
        <title>mission_5-1</title>
    </head>
    <body>
        <h1>Mission_5-1　デバッグ＆レビュー</h1>
        <h2>皆さんの名前と好きなことを教えてください！複数でも構いません！あればおすすめポイントも！</h2>
        <p>
        名前とコメントを登録してください。<br>
        その際パスワードも一緒に登録してください。<br>
        編集と削除に必要になります。<br>
        編集と削除を行う場合は、それぞれ対象の番号と登録してあるパスワードを入力し、ボタンを押してください。<br>
        編集の場合は投稿フォームから編集を行ってください。<br>
        編集ののちは新たなパスワードを入力し、送信ボタンを押してください。<br>
        最初にパスワードを登録しなかった場合は、削除と編集はできません。<br>
        </p>
        
        <?php
            $sub_name = $_POST["name"];
            $sub_comment = $_POST["comment"];
            $submit = $_POST["submit"];
            $date = date("Y年m月d日 H時i分s秒");
            // $count = 1;
            $delete_num = $_POST["delete_num"];
            $delete = $_POST["delete"];
            $edit_num = $_POST["edit_num"];
            $edit = $_POST["edit"];
            $edit_number = $_POST["edit_number"];
            $sub_pass = $_POST["pass"];
            $del_pass = $_POST["passDel"];
            $edit_pass = $_POST["passEdit"];
            

            // 書き込み & 編集実行
            if (isset($submit)){
                if (empty($sub_name) || empty($sub_comment)){  //名前のフォームとコメントのフォームどちらかが空欄の場合
                    echo "<hr>名前とコメントを入力してください。<hr>";
                }else{ //どちらも入力されている場合
                    if (empty($edit_number)){
                        // 投稿番号を決める処理
                        
                        // 書き込み
                        $sql = $pdo -> prepare('INSERT INTO mission5 (name,comment,pass,time) VALUES(:name,:comment,:pass,:time)');
                        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                        $sql -> bindParam(':time', $time, PDO::PARAM_STR);
                        $name = $sub_name;
                        $comment = $sub_comment;
                        $pass = $sub_pass;
                        $time = $date;
                        $sql -> execute();
                        
                        echo "<hr>入力ありがとう<hr>";
                        
                    }else{  //edit_numberが入っているとき
                    
                        // 上書き
                        
                        $id = $edit_number;
                        $name = $sub_name;
                        $comment = $sub_comment;
                        $pass = $sub_pass;
                        $time = $date;
                        
                        $sql = 'UPDATE mission5 SET name = :name, comment = :comment, pass =:pass, time = :time WHERE id = :id';
                        $stmt = $pdo -> prepare($sql);
                        $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
                        $stmt -> bindParam(':time', $time, PDO::PARAM_STR);
                        $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt -> execute();
                        
                        echo "<hr>編集が完了しました。<hr>";
                        
                    }
                }    
            
            // 削除
            }elseif (isset($delete)){
                if (empty($delete_num) || empty($del_pass)){
                    echo "<hr>削除する番号を指定し、パスワードを入力してください。<hr>";
                }else{
                    $id = $delete_num;
                    
                    $sql = 'SELECT * FROM mission5 WHERE id = :id';
                    $stmt = $pdo -> prepare($sql);
                    $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt -> execute();
                    $results = $stmt -> fetchAll();
                    foreach($results as $row){
                        $db_pass = $row['pass'];
                        
                        if($db_pass != $del_pass){ //パスが間違っている
                            echo "<hr>パスワードが間違っています。<hr>";
                        }elseif(empty($db_pass)){  //パスがない
                            echo "<hr>パスワードが設定されていないため、削除できません。<hr>";
                        }else{  //削除可能
                            $sql = 'DELETE FROM mission5 WHERE id = :id';
                            $stmt = $pdo -> prepare($sql);
                            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt -> execute();
                            echo "<hr>削除が完了しました。<hr>";
                        }
                    }
                }
                    

            // 編集番号の指定と呼び出し
            }elseif(isset($edit)){
                if(empty($edit_num) || empty($edit_pass)){
                    echo "<hr>編集する番号を指定し、パスワードを入力してください。<hr>";
                }else{
                    $id = $edit_num;
                    $sql = 'SELECT *FROM mission5 WHERE id = :id';
                    $stmt = $pdo -> prepare($sql);
                    $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt -> execute();
                    $results = $stmt -> fetchAll();
                    foreach($results as $row){
                        $db_pass = $row['pass'];
                        if($db_pass != $passEdit){ //パスが間違っている
                            echo "<hr>パスワードが間違っています。<hr>";
                        }elseif(empty($db_pass)){  //パスがない
                            echo "<hr>パスワードが設定されていないため、編集できません。<hr>";
                        }else{  //編集可能
                    
                            $stmt = $pdo -> prepare($sql);
                            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                            $stmt -> execute();
                            $results = $stmt -> fetchAll();
                            foreach($results as $row){
                                $editNum = $row['id'];
                                $editName = $row['name'];
                                $editComment = $row['comment'];
                                $editPass = $row['pass'];
                            }
                            echo "<hr>編集を行ってください。<hr>";
                        }
                    }
                }
            
            //初期画面
            }else{
                echo "<hr>フォームに入力してください。<hr>";
            }
            
        ?>
        
        <form action ="" method = "POST">
            名前：<br>
            <input type = "text" name = "name" value = '<?php echo $editName; ?>' placeholder ="名前"><br>
            感想：<br>
            <textarea name = "comment" cols ="50" rows = "5" placeholder = "感想を入力してください。"> <?php echo $editComment; ?> </textarea><br>
            パスワード入力：<br>
            <input type = "text" name = "pass" value = '<?php echo $editPass; ?>' placeholder = "パスワード"><br>
            <input type = "submit" name = "submit">
            <input type ="hidden" name = "edit_number" value = '<?php echo $editNum; ?>'><br>

            <br>
            
            削除対象番号：
            <input type = "text" name = "delete_num" placeholder = "1"><br>
            パスワード入力：
            <input type = "text" name = "passDel" placeholder = "パスワード"><br>
            <input type = "submit" name = "delete" value = "削除"><br>
            
            <br>
            
            編集対象番号：
            <input type = "text" name = "edit_num" placeholder = "2"><br>
            パスワード入力：
            <input type = "text" name = "passEdit" placeholder = "パスワード"><br>
            <input type = "submit" name = "edit" value = "編集"><br>
            
            <hr>
            
        </form>
        
<?php
    // テーブル内の要素の表示（投稿画面への表示）
    $sql = 'SELECT * FROM mission5';
    $stmt = $pdo -> query($sql);
    $results = $stmt -> fetchAll();
    foreach ($results as $row){
        echo $row['id'].' ';
        echo $row['name'].' ';
        echo $row['comment'].' ';
        echo $row['time'].'<br>';
    }
    echo '<hr>';
?>
        
    </body>
</html>