<?php

//wyciągamy część katalogową aktualnej ścieżki tego pliku
$dir = dirname(__FILE__);

//nie używamy require, bo do require nie można wrzucić zmiennej (bo wywołuje się
//przed kompilacją kodu
include($dir . '/src/DB.php');
include($dir . '/src/Book.php');

$conn = DB::connect();
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (isset($_GET['id']) && intval($_GET['id']) > 0) {
//pobieramy pojedyczną książkę
        $books = Book::loadFromDB($conn, intval($_GET['id']));
    } else {
//pobieramy wszystkie książki
        $books = Book::loadFromDB($conn);
    }

    echo json_encode($books);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['title']) &&
        isset($_POST['author']) &&
        isset($_POST['desc'])) {

        $newBook = new Book();
        $newBook->setTitle($_POST['title']);
        $newBook->setAuthor($_POST['author']);
        $newBook->setDescription($_POST['desc']);

        $newBook->create($conn);

        header('Location: ../index.php');
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    parse_str(file_get_contents("php://input"), $put_vars);

    $id = $put_vars['id'];
    $title = $put_vars['title'];
    $author = $put_vars['author'];
    $desc = $put_vars['desc'];

    $editedBook = new Book();
    $editedBook->setTitle($title);
    $editedBook->setAuthor($author);
    $editedBook->setDescription($desc);
    
    $editedBook->update($conn, $id);
    
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    parse_str(file_get_contents("php://input"), $del_vars);

    $id = $del_vars['id'];

    Book::deleteFromDB($conn, $id);
    header('Location: ../index.php');
}