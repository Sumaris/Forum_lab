<?php
function getDb($sql) {
    $db = new PDO("mysql:host=localhost;dbname=db", 'root', 'root');
    //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $db->prepare($sql);
    $db = NULL;
    return $stmt;
}
function makeUser($salt){
    $hashed_pass = sha1($_POST['pass'].$salt);
    $sql = "INSERT INTO user (mail, pass, salt) VALUES (:mail, :hashpass, :salt)";
    try {
        $stmt = getDb($sql);
        $stmt->execute([
            ':mail' => $_POST['mail'],
            ':hashpass' => $hashed_pass,
            ':salt' => $salt
        ]);
        return $stmt;
    } catch (PDOException $e) {
         handle_sql_errors($sql, $e->getMessage());
    }
}
function createPost($tempID = 0){
    $sql = "INSERT INTO comments (name, mail, comm, parent) VALUES (:name, :mail, :comm, :tempId)";
    try {
        $stmt = getDb($sql);
        $stmt->execute([
            ':name'    => $_POST['name'],
            ':mail'    => $_POST['mail'],
            ':comm'    => $_POST['text'],
            ':tempId'  => $tempID
        ]);
        return $stmt;
    } catch (PDOException $e) {
         handle_sql_errors($sql, $e->getMessage());
    }
}
function updatePost($tempID){
    $sql = "UPDATE comments SET parent= :tempId WHERE id= :tempId";
    try {
        $stmt = getDb($sql);
        $stmt->execute([
            ':tempId'  => $tempID
        ]);
    } catch (PDOException $e) {
         handle_sql_errors($sql, $e->getMessage());
    }
}
function getMaxId(){
    $sql = "SELECT MAX(id) AS p_id FROM comments";
    try {
        $stmt = getDb($sql);
        $stmt->execute();
        return $stmt;
    } catch (PDOException $e) {
         handle_sql_errors($sql, $e->getMessage());
    }
}
function getUsers(){
    $sql = "SELECT * from user";
    try {
        $stmt = getDb($sql);
        $stmt->execute();
        return $stmt;
    } catch (PDOException $e) {
         handle_sql_errors($sql, $e->getMessage());
    }
}
function getComments(){
    $sql = "SELECT * from comments";
    try {
        $stmt = getDb($sql);
        $stmt->execute();
        return $stmt;
    } catch (PDOException $e) {
         handle_sql_errors($sql, $e->getMessage());
    }
}
function updatePass(){
    $sql = "UPDATE user SET pass='$token' WHERE mail= :mail";
    try {
        $stmt = getDb($sql);
        $stmt->execute([
            ':mail' => $_POST['mail']
        ]);
        return $stmt;
    } catch (PDOException $e) {
         handle_sql_errors($sql, $e->getMessage());
    }
}
function deletePost($id){
    $sql = "DELETE FROM comments WHERE id= :id ";
    try {
        $stmt = getDb($sql);
        $stmt->execute([
            ':id' => $id
        ]);
        return $stmt;
    } catch (PDOException $e) {
         handle_sql_errors($sql, $e->getMessage());
    }
}
function deleteThread($id){
    $sql = "DELETE FROM comments WHERE parent= :id ";
    try {
        $stmt = getDb($sql);
        $stmt->execute([
            ':id' => $id
        ]);
        return $stmt;
    } catch (PDOException $e) {
         handle_sql_errors($sql, $e->getMessage());
    }
}
function handle_sql_errors($query, $error_message) {
    echo '"'.$query.'"';
    echo '<br>';
    echo $error_message;
    die;
}
?>
