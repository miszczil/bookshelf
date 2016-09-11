<?php ?>
<!DOCTYPE html>
<html lang="pl-PL">
    <head>
        <meta charset="UTF-8">
        <title>Books</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/JavaScript" src="js/app.js"></script>
    </head>
    <body>
        <div class="container">
            <br>
            <form class="form-inline addingForm" action="api/books.php" method="POST">
                <div class="form-group">
                    <label for="title">Tytuł:</label>
                    <input type="text" class="form-control" name="title">
                </div>
                <div class="form-group">
                    <label for="author">Autor:</label>
                    <input type="text" class="form-control" name="author">
                </div>
                <div class="form-group">
                    <label for="desc">Opis:</label>
                    <input type="text" class="form-control" name="desc">
                </div>
                <button type="submit" class="btn btn-primary" id="addBook">Dodaj</button>
            </form>
            <br><br>
            <div id="bookList" class="panel-group"></div>
        </div>



    </body>
</html>
