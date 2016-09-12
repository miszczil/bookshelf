<?php

//klasa Book musi mieć zaimplementowany interfejs, żeby przekazać obiekt tej klasy
//do json_encode
class Book implements JsonSerializable {

    private $id;
    private $title;
    private $author;
    private $description;

    public function __construct() {
        $this->id = -1;
        $this->title = '';
        $this->author = '';
        $this->description = '';
    }

    public function create(mysqli $connection) {

        $result = $connection->query("INSERT INTO Book(title, author, description)
            VALUES ('$this->title','$this->author', '$this->description')");
        
        if($result) {
            return true;
        } else {
            return false;
        }
    }

    public function update(mysqli $connection, $id) {
        
        $newTitle = $this->getTitle();
        $newAuthor = $this->getAuthor();
        $newDesc = $this->getDescription();
        
        $result = $connection->query("UPDATE Book
                                    SET title = '$newTitle',
                                    author =  '$newAuthor',
                                    description =  '$newDesc'
                                    WHERE  id = $id;
                                    ");
        if($result) {
            return true;
        } else {
            return false;
        }
        
    }

    //jeżeli id=null zwrócimy wszystkie książki, jeżeli nie - zwrócimy konkretną
    static public function loadFromDB(mysqli $conn, $id = null) {

        if (!is_null($id)) {
            $result = $conn->query("SELECT * FROM Book WHERE id=$id");
        } else {
            $result = $conn->query("SELECT * FROM Book");
        }

        if ($result && $result->num_rows > 0) {
            foreach ($result as $row) {
                $dbBook = new Book();
                $dbBook->id = $row['id'];
                $dbBook->title = $row['title'];
                $dbBook->author = $row['author'];
                $dbBook->description = $row['description'];
                $booksList[] = json_encode($dbBook);
            }
        }

        return $booksList;
    }

    static public function deleteFromDB(mysqli $conn, $id = null) {

        if ($id != null) {
            $result = $conn->query("DELETE FROM Book WHERE id=$id");
        }
        
        if($result) {
            return true;
        } else {
            return false;
        }
    }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'description' => $this->description
        ];
    }

    function getId() {
        return $this->id;
    }

    function getTitle() {
        return $this->title;
    }

    function getAuthor() {
        return $this->author;
    }

    function getDescription() {
        return $this->description;
    }

    function setTitle($title) {
        $this->title = $title;
    }

    function setAuthor($author) {
        $this->author = $author;
    }

    function setDescription($description) {
        $this->description = $description;
    }

}
