<?php

    class database{

    
    

    function opencon(): PDO {
        return new PDO(
            'mysql:host=localhost;
            dbname=besoriolab01',
            username:'root',
            password: '');
    }


        //Insert User 
    function insertUser($Username,$User_password_hash,$isActive,$Created_At) {
        $con = $this->opencon();
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO users (Username, User_password_hash, isActive, created_At) VALUES(?,?,?,?)');
            $stmt->execute([$Username, $User_password_hash, $isActive,$Created_At]);
            $User_id = $con->lastInsertId();
            $con->commit();
            return $User_id;
        } catch(PDOEXCEPTION $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;
        }
    }


        //Add Borrower
    function Add_Borrower($firstname, $lastname, $email, $phone, $member_since, $is_Active) {
        $con = $this->opencon();
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO borrowers(Borrower_firstname, Borrower_lastname, Borrower_email, Borrower_phone, Borrower_member_since, is_Active) VALUES(?,?,?,?,?,?)');
            $stmt->execute([$firstname, $lastname, $email, $phone, $member_since, $is_Active]);
            $borrower_ID = $con->lastInsertId();
            $con->commit();
            return $borrower_ID;
        } catch(PDOEXCEPTION $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;
        }
    }


        //Add Borrower Address
    function Add_Borrower_Address($borrower_ID, $HouseNumber, $Street, $Barangay, $City, $Province, $postalCode, $isPrimary,$country) {
        $con = $this->opencon();
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO borrower_address(Borrower_ID, BA_House_Number, BA_Street, BA_Barangay, BA_city, BA_Province, BA_PostalCode, isPrimary) VALUES(?,?,?,?,?,?,?,?)');
            $stmt->execute([$borrower_ID, $HouseNumber, $Street, $Barangay, $City, $Province, $postalCode, $isPrimary]);
            $borrower_Add = $con->lastInsertId();
            $con->commit();
            return $borrower_Add;
        } catch(PDOEXCEPTION $e){
            if($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    //Add Borrower User
    function AddBorrowerUser($User_ID, $Borrower_ID) {
        $con = $this->opencon();
        try{
            $con->beginTransaction();
            $stmt =  $con->prepare('INSERT INTO borrower_user(User_ID,Borrower_ID) VALUES(?,?)');
            $stmt->execute([$User_ID, $Borrower_ID]);
            $Add_Borrower_User = $con->lastInsertId();
            $con->commit();
            return $Add_Borrower_User;
        } catch(PDOEXCEPTION $e){
            if($con->inTransaction()){
                $con->rollBack();
            } 
                throw $e;
        }
    }

    //Check Existing Borrower Users
    function ViewBorrowerUser(){
        $con =  $this->opencon();
        return $con->query('SELECT * FROM borrowers');
    }

    //Add new Books
    function AddBook($title, $isbn, $publicationYear, $edition, $publisher){
        $con = $this->opencon();
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO book(Book_Title, Book_ISBN, Book_publicationyear, Book_Edition, Book_Publisher) VALUES(?, ?, ?, ?, ?)');
            $stmt->execute([$title, $isbn, $publicationYear, $edition, $publisher]);
            $Get_Book = $con->lastInsertId();
            $con->commit();
            return $Get_Book;
        } catch(PDOEXCEPTION $e){
            if($con->inTransaction()) {
                $con->rollBack();
            } 
            throw $e;
        }
    }

    //Checks the Book lists
    function viewBooks(){
        $con = $this->opencon();
        $query = $con->query('SELECT * FROM book');
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    //Add more Copies
    function bookCopy($bookID, $status){
        $con = $this->opencon();

        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO book_copy(Book_ID, Copy_status) VALUES(?, ?)');
            $stmt->execute([$bookID, $status]);
            $bookcopy = $con->lastInsertID();
            $con->commit();
            return $bookcopy;
        }catch(PDOEXCEPTION $e) {
            if($con->inTransaction()) {
                $con->rollback();
            }
            throw $e;
        }
    }

    //Get author fistname lastname
    function retrieve_Author(){
        $con = $this->opencon();
        $query = $con->query('SELECT * FROM author');
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    //Pangkuha Genre
    function extractGenres(){
        $con = $this->opencon();
        $query = $con->query('SELECT * FROM genre');
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    function insertBookGenre($genreId, $bookId){
        $con = $this->opencon();
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO book_genre(Genre_ID, Book_ID) VALUES(?, ?)');
            $stmt->execute([$genreId, $bookId]);
            $bookGenre = $con->lastInsertId();
            $con->commit();
            return $bookGenre;
        } catch(PDOEXCEPTION $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;
        }
    }


    //INSERT BOOK AUTHOR
    function insertBookAuthor($bookid,$bookauth){
        $con = $this->opencon();
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO book_genre(Book_ID, Author_ID) VALUES(?, ?)');
            $stmt->execute([$bookid, $bookauth]);
            $insertBookAuth = $con->lastInsertId();
            $con->commit();
            return $insertBookAuth;
        } catch(PDOEXCEPTION $e) {
            if($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }
    }

    function viewBook()
    {
        $con = $this->opencon();
        return $con->query("SELECT
        book.Book_ID,
        book.Book_Title,
        book.Book_ISBN,
        book.Book_publicationyear,
        book.Book_Publisher,
        COUNT(book_copy.Copy_ID) AS Copies,
        SUM(book_copy.Copy_status = 'Available') AS Available_Copies
        FROM
        book
        JOIN book_copy ON book.Book_id = book_copy.Book_ID
        GROUP BY 1
        ")->fetchAll();
    }

    
}
?>