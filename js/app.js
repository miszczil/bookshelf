$(function () {
    //pobieramy cała listę book z bazy i dodajemy na stronę
    $.ajax({
        url: 'api/books.php',
        //bez data - nie podajemy id, bo chcemy all books
        type: 'GET',
        dataType: 'json'
    }).done(function (result) {
        //powinniśmy otrzymać tablicę z książkami (książki są w formacie json)

        for (var i = 0; i < result.length; i++) {
            var book = JSON.parse(result[i]);
            //po sparsowaniu otrzymujemy obiekt Book

            //tworzymy diva z daną książką
            var bookDiv = $('<div>');
            bookDiv.addClass('singleBook').addClass('panel').addClass('panel-info');
            var titleDiv = $('<div>');
            titleDiv.addClass('panel-heading').addClass('clearfix');
            var title = $('<h4>');
            title.addClass('panel-title');
            title.attr('data-id', book.id);
            title.attr('style', 'display: inline-block');
            title.html('<a class="bookTitle" data-toggle="collapse" data-parent="#bookList" href="#desc'
                    + book.id + '">' + book.title + '</a>');
            var deleteBtn = $('<button>').addClass('btn').addClass('btn-primary').addClass('pull-right');
            deleteBtn.addClass('deleteBook');
            deleteBtn.text('Usuń');
            titleDiv.append(title);
            titleDiv.append(deleteBtn);
            bookDiv.append(titleDiv);


            //w którego wstawiamy diva z opisem
            var descDiv = $('<div>');
            descDiv.attr('id', 'desc' + book.id);
            descDiv.addClass('panel-collapse').addClass('collapse');
            var desc = $('<div>').addClass("panel-body").addClass('description');

            descDiv.append(desc);
            bookDiv.append(descDiv);

            //a jego samego wstawiamy do diva z listą książek
            $('#bookList').append(bookDiv);
        }

    }).fail(function (result) {
        console.log('Error - nie mogę wczytać tytułów');
    });


    //drugi arguement -> $('.bookTitle'):
    //event będzie działał tylko na podanym w drugim argumencie elementacie
    //(czyli tylko kiedy NA NIEGO klikniemy)
    //nie mogliśmy założyć listenera od razu na te elementy, bo są one ładowane
    //z ajaxa asynchronicznie, więc nie istnieją jeszcze, kiedy tego listenera zakładamy
    //(dlatego zakładamy go na rodzica)
    $('#bookList').on('click', $('.bookTitle'), function (e) {


        var target = $(e.target);
        var bookTitle = $(e.target).closest('.singleBook').find('.bookTitle');

        if (target[0] == bookTitle[0]) {

            var desc = target.closest('.singleBook').find('.description');

            //pobieram id książki, w którą klikam
            var bookId = target.closest('.singleBook').find('.panel-title').attr('data-id');

            //wysyłamy zapytanie ajaxem o książkę o podanym id
            $.ajax({
                url: 'api/books.php',
                type: 'GET',
                data: 'id=' + bookId,
                dataType: 'json'

            }).done(function (result) {
                var book = JSON.parse(result[0]);

                var form = '<form class="form-inline editingForm" action="api/books.php" method="PUT">\n\
                    <div class="form-group">\n\
                        <label for="title">Nowy tytuł:</label>\n\
                        <input type="text" class="form-control" name="title">\n\
                    </div>\n\
                    <div class="form-group">\n\
                        <label for="author">Nowy autor:</label>\n\
                        <input type="text" class="form-control" name="author">\n\
                    </div>\n\
                    <div class="form-group">\n\
                        <label for="desc">Nowy opis:</label>\n\
                        <input type="text" class="form-control" name="desc">\n\
                    </div>\n\
                    <button type="submit" class="btn btn-primary" id="editBook">Zmień</button>\n\
                </form>';

                desc.html('<p class="text-info">' + book.author + '</p><p>'
                        + book.description + '</p>' + form);

            }).fail(function (result) {
                console.log('Error - nie mogę wczytać opisu');
            });

        }

    });


    $('#bookList').on('click', $('.deleteBook'), function (e) {

        var target = $(e.target);
        var deleteBtn = $(e.target).closest('.singleBook').find('.deleteBook');

        if (target[0] == deleteBtn[0]) {

            var bookId = $(e.target).prev().attr('data-id');

            $.ajax({
                url: 'api/books.php',
                type: 'DELETE',
                data: 'id=' + bookId,
                
            }).done(function (result) {
                console.log('Usunięto książkę');
                location.reload();
            }).fail(function (result) {
                console.log('Error - nie mogę usunąć książki');
            });

        }

    });

    $('#bookList').on('click', $('#editBook'), function (e) {

        e.preventDefault();

        var target = $(e.target);
        var editBtn = $(e.target).closest('.singleBook').find('#editBook');

        if (target[0] == editBtn[0]) {
            var bookId = $(e.target).closest('div.panel-collapse').prev().find('h4').attr('data-id');
            var newTitle = $(e.target).parent().find('input[name="title"]').val();
            var newAuthor = $(e.target).parent().find('input[name="author"]').val();
            var newDesc = $(e.target).parent().find('input[name="desc"]').val();

            $.ajax({
                url: 'api/books.php',
                type: 'PUT',
                data: 'id=' + bookId + '&title=' + newTitle + '&author=' + newAuthor + '&desc=' + newDesc,
            }).done(function (result) {
                console.log('Edytowano książkę');
                location.reload();
            }).fail(function (result) {
                console.log('Error - nie mogę edytować książki');
            });
        }

    });


});


