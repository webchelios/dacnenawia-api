<?php 

$host = "localhost";
$user = "root";
$password = "4445";
$DB = "dacnenawia";

$conection = new mysqli( $host, $user, $password, $DB );

if ( $conection->connect_error ) {
    die ( "Conexión no establecida" . $conection->connect_error );
}

header( "Content-Type: application/json" );

$method = $_SERVER['REQUEST_METHOD'];
$path = isset( $_SERVER['PATH_INFO'] ) ? $_SERVER['PATH_INFO'] : '/';
$findId = explode( '/', $path );
$id = ( $path !== '/' ) ? end( $findId ) : null;

switch ( $method ) {
    case 'GET':
        echo 'Metodo get';
        select( $conection );
        break;
    case 'POST':
        echo 'Metodo post';
        insert( $conection );
        break;
    case 'PUT':
        echo 'Metodo put';
        break;
    case 'DELETE':
        echo 'Metodo delete';
        delete( $conection, $id );
        break;
    default:
        echo 'Metodo no existente';
        break;
}

function select( $conection ) {
    $query = "SELECT * FROM products";
    $result = $conection->query( $query );

    if ( $result ) {
        $data = array();
        while ( $row = $result->fetch_assoc() ) {
            $data[] = $row;
        }
        echo json_encode( $data );
    }
}

function insert( $conection ) {
    $data = json_decode( file_get_contents('php://input'), true );
    $name = $data['name'];
    $category = $data['category'];
    $price = $data['price'];
    $discount = $data['discount'];
    $description = $data['description'];
    $images = $data['images'];
    $stock = $data['stock'];
    
    $query = "INSERT INTO products(
        name,
        category,
        price,
        discount,
        description,
        images,
        stock
    ) VALUES ('$name','$category','$price','$discount','$description','$images','$stock')";
    $result = $conection->query($query);

    if ( $result ) {
        $data['id'] = $conection->insert_id;
        echo json_encode( $data );
    } else {
        echo json_encode(array('error' => 'Error al crear producto'));
    }
}

function delete( $conection, $id ) {
    echo 'El id a borrar es: ' . $id;
}


?>