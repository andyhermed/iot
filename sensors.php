<?php
require_once "connection.php";

$metodo = $_SERVER["REQUEST_METHOD"];

switch($metodo){
    //ARQUITECTURA REST
    case "GET";
    //CONSULTA
        $conexion = connection(); //CONEXIÓN A LA DB

        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $comando = $conexion->prepare("SELECT * FROM sensors WHERE id=:pid");
            $comando->bindValue(":pid",$id);
            $comando->execute();
            $comando->setFetchMode(PDO::FETCH_ASSOC);
            $resultado = $comando->fetch();
        }else{
            $comando = $conexion->prepare("SELECT * FROM sensors");
            $comando->execute();
            $comando->setFetchMode(PDO::FETCH_ASSOC);
            $resultado = $comando->fetchAll();
        }
        echo json_encode($resultado);
        
        break;

    case "POST";
    //INSERTAR
        if(!isset($_POST['type']) || !isset($_POST['value'])){
            header("HTTP/1.1 400 Bad Request");
            return;
        }
        $conexion = connection();
        $comando = $conexion->prepare("INSERT INTO sensors(user, type, value, date) VALUES(:u,:t,:v,:d)");
        $comando->bindValue(":u", "admin");
        $comando->bindValue(":t", $_POST['type']);
        $comando->bindValue(":v", $_POST['value']);
        $comando->bindValue(":d", date("Y-m-d H:i:s"));
        $comando->execute();
        if($comando->rowCount()==0){
            header("HTTP/1.1 400 Bad Request");
            return;
        }
        echo json_encode(["status"=>"ok","id"=>$conexion->lastInsertId()]);
        break;
    
    case "PUT";//También puede ser PATCH
    //ACTUALIZAR
        
    
        break;
    
    case "DELETE";
    //ELIMINAR
        break;

}
?>