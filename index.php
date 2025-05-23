<?php 

$host = "localhost";
$user = "root";
$password = "";
$DB = "dacnenawia";

$conection = new mysqli( $host, $user, $password, $DB );

if ( $conection->connect_error ) {
    die ( "Conexión no establecida" . $conection->connect_error );
}

header( "Content-Type: application/json" );

$method = $_SERVER['REQUEST_METHOD'];

$requestUri = $_SERVER['REQUEST_URI'];
$segments = explode('/', trim($requestUri, '/')); 
$id = is_numeric(end($segments)) ? end($segments) : null;

switch ( $method ) {
    case 'GET':
        select( $conection, $id );
        break;
    case 'POST':
        insert( $conection );
        break;
    case 'PUT':
        update( $conection, $id );
        break;
    case 'DELETE':
        deleteQuery( $conection, $id );
        break;
    default:
        echo 'Metodo no existente';
        break;
}

function select( $conection, $id ) {
    $query = ( $id === null ) ? "SELECT * FROM products" : "SELECT * FROM products WHERE id = $id";
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

function deleteQuery( $conection, $id ) {
    $query = "DELETE FROM products WHERE id = $id";
    $result = $conection->query($query);

    if ( $result ) {
        echo json_encode(array( 'message' => 'Producto eliminado' ));
    } else {
        echo json_encode(array( 'error' => 'Error al eliminar producto' ));
    }
}

function update( $conection, $id ) {
    $data = json_decode(file_get_contents( 'php://input' ), true);
    $name = $data['name'];
    $category = $data['category'];
    $price = $data['price'];
    $discount = $data['discount'];
    $description = $data['description'];
    $images = $data['images'];
    $stock = $data['stock'];

    $query = "UPDATE products SET
        name = '$name',
        category = '$category',
        price = '$price',
        discount = '$discount',
        description = '$description',
        images = '$images',
        stock = '$stock'
    WHERE id = $id";
    $result = $conection->query( $query );

    if ( $result ) {
        echo json_encode(array( 'message' => 'Producto actualizado' ));
    } else {
        echo json_encode(array( 'error' => 'Error al actualizar producto' ));
    }
} 

?>