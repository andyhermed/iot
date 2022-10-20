<?php

require_once "connection.php";
require_once "jwt.php";

if(isset($_REQUEST['user']) && isset($_REQUEST['pass'])){
    $conexion = connection();
    $user = $_REQUEST['user'];
    $pass = $_REQUEST['pass'];
    $consulta = $conexion->prepare("SELECT user, role FROM users WHERE user=:u AND pass= :p");
    $consulta->bindValue(":u", $user);
    $consulta->bindValue(":p", md5($pass));
    $consulta->execute();
    $consulta->setFetchMode(PDO::FETCH_ASSOC);
    $resultado = $consulta->fetch();
    if($resultado){
        $resultado = [
            "status" => "ok",
            "jwt" => JWT::create($resultado, "12345678")
        ];
    }else{
        $resultado = ["status" => "error"];

    }
    echo json_encode($resultado);

}else{

    header(("HTTP/1.1 400 Bad Request"));
}

?>