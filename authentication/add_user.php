<?php
    $db = new PDO('sqlite:users.db');

    $nume = $_POST['nume'];
    $pass = $_POST['pass'];

    $hash = password_hash($pass, PASSWORD_DEFAULT);

    $stm = $db->prepare("INSERT INTO users (nume, pass) values (?,?)");
    $stm->bindValue(1, $nume);
    $stm->bindValue(2, $hash);
    $stm->execute();

    echo "ok";


