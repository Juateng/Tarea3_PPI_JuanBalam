<?php

include('conn.php');

//Si pasamos datos a traves de la URL 
if(isset($_GET['accion'])){

    $accion = $_GET['accion'];

    //leer los datos de la tabla usuarios
    if($accion == 'leer_auto'){

        $sql = "select * from auto where 1";
        $result = $db->query($sql);

        if($result->num_rows>0){

            while($fila = $result->fetch_assoc()){
                $items['id']= $fila['id'];
                $items['marca']= $fila['marca'];
                $items['modelo']= $fila['modelo'];
                $items['año']= $fila['año'];
                $items['no_serie']= $fila['no_serie'];
                $arr1[] = $items;
            }
            $response['status'] = 'OK';
            $response['mensaje'] = $arr1;


        }
        else{
            $response["status"] = "Error";
            $response["mensaje"]= "no hay autos registrados";
        }
        echo json_encode($response);
    }

    if($accion == 'leer_dueño'){

        $sql = "select * from dueños where 1";
        $result = $db->query($sql);

        if($result->num_rows>0){

            while($fila = $result->fetch_assoc()){
                $items['id']= $fila['id'];
                $items['nombre']= $fila['nombre'];
                $items['email']= $fila['email'];
                $arr2[] = $items;
            }
            $response['status'] = 'OK';
            $response['mensaje'] = $arr2;
        }
        else{
            $response["status"] = "Error";
            $response["mensaje"]= "no hay dueños registrados";
        }
        echo json_encode($response);
    }

    if ($accion == 'leer_ventas') {
        $sql = "select ventas.id, Auto.marca, Auto.modelo, Auto.año, Auto.no_serie, Dueños.nombre
                FROM Auto
                JOIN ventas ON Auto.id = ventas.id_auto 
                JOIN dueños ON ventas.id_dueño = dueños.id";
    
        $result = $db->query($sql);
    
        if ($result->num_rows > 0) {
            $arr3 = array();
    
            while ($fila = $result->fetch_assoc()) {
                $items['id de venta'] = $fila['id'];
                $items['marca'] = $fila['marca'];
                $items['modelo'] = $fila['modelo'];
                $items['año'] = $fila['año'];
                $items['no_serie'] = $fila['no_serie'];
                $items['dueño'] = $fila['nombre'];
                $arr3[] = $items;
            }
    
            $response['status'] = 'OK';
            $response['mensaje'] = $arr3;
        } else {
            $response["status"] = "Error";
            $response["mensaje"] = "no hay ventas registradas";
        }
    
        echo json_encode($response);
    }
    
}

//leemos raw post data del request body
$data = json_decode(file_get_contents('php://input'), true);

//Autos
if(isset($data)){
    
    //Obtengo la accion
    $accion = $data['accion'];

    //Verifico el tipo de accion
    if($accion =='insertar_auto'){

        //Obtener los demas datos del body
        $marca=$data["marca"];
        $modelo=$data["modelo"];
        $año=$data["año"];
        $no_serie=$data["no_serie"];

        $qry = "insert into auto (marca, modelo, año, no_serie) values ('$marca','$modelo','$año','$no_serie')";
        if($db->query($qry)){
            $response["status"] = 'OK';
            $response["mensaje"] = 'El registro se creo correctamente';
        }
        else{
            $response["status"] = 'error';
            $response["mensaje"] = 'No se pudo hacer el registro';
        }

        header('Content-type: application/json');
        echo json_encode($response);
    }

    if($accion == 'modificar_auto'){

        //Obtener los demas datos del body
        $id = $data['id'];
        $marca=$data["marca"];
        $modelo=$data["modelo"];
        $año=$data["año"];
        $no_serie=$data["no_serie"];

        $qry = "update auto set marca = '$marca',modelo = '$modelo', año = '$año',no_serie = '$no_serie' where id = '$id'";
        if($db->query($qry)){
            $response["status"] = 'OK';
            $response["mensaje"] = 'El registro se modifico correctamente';
        }
        else{
            $response["status"] = 'error';
            $response["mensaje"] = 'No se pudo hacer la modificacion';
        }

        header('Content-type: application/json');
        echo json_encode($response);
    }

    if($accion == 'borrar_auto'){

        //Obtener los demas datos del body
        $id = $data['id'];

        $qry = "delete from auto where id = '$id'";
        if($db->query($qry)){
            $response["status"] = 'OK';
            $response["mensaje"] = 'El registro se elimino correctamente';
        }
        else{
            $response["status"] = 'error';
            $response["mensaje"] = 'No se pudo eliminar el registro';
        }

        header('Content-type: application/json');
        echo json_encode($response);
    }
    //---------------------------------------------------------------------------------------------
    //Verifico el tipo de accion
    if($accion =='insertar_dueño'){

        //Obtener los demas datos del body
        $nombre=$data["nombre"];
        $email=$data["email"];

        $qry = "insert into dueños (nombre, email) values ('$nombre','$email')";
        if($db->query($qry)){
            $response["status"] = 'OK';
            $response["mensaje"] = 'El registro se creo correctamente';
        }
        else{
            $response["status"] = 'error';
            $response["mensaje"] = 'No se pudo hacer el registro';
        }

        header('Content-type: application/json');
        echo json_encode($response);
    }

    if($accion == 'modificar_dueño'){

        //Obtener los demas datos del body
        $id = $data['id'];
        $nombre=$data["nombre"];
        $email=$data["email"];

        $qry = "update dueños set nombre = '$nombre',email = '$email' where id = '$id'";
        if($db->query($qry)){
            $response["status"] = 'OK';
            $response["mensaje"] = 'El registro se modifico correctamente';
        }
        else{
            $response["status"] = 'error';
            $response["mensaje"] = 'No se pudo hacer la modificacion';
        }

        header('Content-type: application/json');
        echo json_encode($response);
    }

    if($accion == 'borrar_dueño'){

        //Obtener los demas datos del body
        $id = $data['id'];

        $qry = "delete from dueños where id = '$id'";
        if($db->query($qry)){
            $response["status"] = 'OK';
            $response["mensaje"] = 'El registro se elimino correctamente';
        }
        else{
            $response["status"] = 'error';
            $response["mensaje"] = 'No se pudo eliminar el registro';
        }

        header('Content-type: application/json');
        echo json_encode($response);
    }

    //---------------------------------------------------------------------------------------------
    //Verifico el tipo de accion
    if($accion =='insertar_venta'){

        //Obtener los demas datos del body
        $id_dueño=$data["id_dueño"];
        $id_auto=$data["id_auto"];

        $qry = "insert into ventas (id_dueño, id_auto) values ('$id_dueño','$id_auto')";
        if($db->query($qry)){
            $response["status"] = 'OK';
            $response["mensaje"] = 'El registro se creo correctamente';
        }
        else{
            $response["status"] = 'error';
            $response["mensaje"] = 'No se pudo hacer el registro';
        }

        header('Content-type: application/json');
        echo json_encode($response);
    }

    if($accion == 'modificar_ventas'){

        //Obtener los demas datos del body
        $id = $data['id'];
        $id_dueño=$data["id_dueño"];
        $id_auto=$data["id_auto"];

        $qry = "update ventas set id_dueño = '$id_dueño',id_auto = '$id_auto' where id = '$id'";
        if($db->query($qry)){
            $response["status"] = 'OK';
            $response["mensaje"] = 'El registro se modifico correctamente';
        }
        else{
            $response["status"] = 'error';
            $response["mensaje"] = 'No se pudo hacer la modificacion';
        }

        header('Content-type: application/json');
        echo json_encode($response);
    }

    if($accion == 'borrar_venta'){

        //Obtener los demas datos del body
        $id = $data['id'];

        $qry = "delete from ventas where id = '$id'";
        if($db->query($qry)){
            $response["status"] = 'OK';
            $response["mensaje"] = 'El registro se elimino correctamente';
        }
        else{
            $response["status"] = 'error';
            $response["mensaje"] = 'No se pudo eliminar el registro';
        }

        header('Content-type: application/json');
        echo json_encode($response);
    }

}

