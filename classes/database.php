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

    //add author
    function addAuthor($authorFirstname, $authorLastname, $authorbirthyear, $authornationality){
        $con = $this->opencon();
        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO author(author.author_firstname,
                                            author.author_lastname,
                                            author.author_birthyear,
                                            author.author_nationality) 
                                            VALUES(?,?,?,?)');
            $stmt->execute([$authorFirstname, $authorLastname, $authorbirthyear, $authornationality]);
            $insertAuthor = $con->lastInsertId();
            $con->commit();
            return $insertAuthor;
        } catch(PDOEXCEPTION $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;
        }
    }

    function insertGenre($genre){
        $con = $this->opencon();
    try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO genre(genre_name) VALUES(?)');
            $stmt->execute([$genre]);
            $insertGenre = $con->lastInsertId();
            $con->commit();
            return $insertGenre;
        }catch(PDOEXCEPTION $e){
            if($con->inTransaction()){
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


    // Not working..still on the process
    function UpdateBook($book_id,$title, $ISBN, $publication_year, $Publisher){
        $con = $this->opencon();
        try{
            $con->beginTransaction();
            $stmt = $con->prepare("UPDATE book 
                                   SET  Book_Title = ?, 
                                        Book_ISBN = ?, 
                                        Book_publicationyear = ?,
                                        Book_Publisher = ?
                                    WHERE Book_ID = ?");

            $stmt->execute([$book_id,$title, $ISBN,$publication_year ,$Publisher]);
            $con->commit();
            return true;
        } catch(PDOEXCEPTION $e){
            if($con->inTransaction()) {
                $con->rollBack();
            }
            throw $e;
        }   
    }

    function recentLoanbyProcessor(){
        $con = $this->opencon();
        return $con->query("
            SELECT
                loan.loan_ID,
                CONCAT(borrowers.Borrower_firstname, ' ',borrowers.Borrower_lastname) AS borrower_fullname,
                loan.loan_status,
                loan.loan_date,
                users.Username AS processed_by
            FROM loan
                JOIN borrowers ON loan.Borrower_ID = borrowers.Borrower_ID
                JOIN loan_item ON loan.loan_ID = loan_item.loan_ID
                JOIN users ON loan.processed_by = users.User_ID
                WHERE users.User_ID = 1;"


        )->fetchAll();
    }

    function countBook(){
        $con =  $this->opencon();
        return $con->query("
            SELECT COUNT(*) FROM book")->fetchColumn();
    }

    function countAuthor(){
        $con = $this->opencon();
        return $con->query("SELECT COUNT(*) FROM author")->fetchColumn();
    }

    function countGenre(){
        $con =  $this->opencon();
        return $con->query("SELECT COUNT(*) FROM genre")->fetchColumn();
    }

    function deletebooks($book_id){
        $con = $this->opencon();
        try{
            $con->beginTransaction();
            $stmtCopies = $con->prepare("DELETE FROM book_copy WHERE Book_ID = ?");
            $stmtCopies->execute([$book_id]);

            $stmtGenre = $con->prepare("DELETE FROM book_genre WHERE Book_ID = ?");
            $stmtGenre->execute([$book_id]);

            $stmtAuthor = $con->prepare("DELETE FROM book_author WHERE Book_ID = ?");
            $stmtAuthor->execute([$book_id]);

            $stmtBook = $con->prepare("DELETE FROM book WHERE Book_ID = ?");
            $stmtBook->execute([$book_id]);
 
            $con->commit();
            return true;
        } catch(EXCEPTION $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;
        }
    }

    function deleteAuthor($author_ID){
        $con = $this->opencon();

        try{
            $con->beginTransaction();

            $stmtAuthor = $con->prepare("DELETE FROM author WHERE Author_ID = ?");
            $stmtAuthor->execute([$author_ID]);

            $stmtBookAuthor = $con->prepare("DELETE FROM book_author WHERE Author_ID = ?");
            $stmtBookAuthor->execute([$author_ID]);
            $con->commit();
        } catch(PDOEXCEPTION $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;
        }
    }

    function deleteGenre($genreID){
        $con =  $this->opencon();
        try {
            $con->beginTransaction();

            $stmtGenre = $con->prepare("DELETE FROM genre WHERE Genre_ID = ?");
            $stmtGenre->execute([$genreID]);

            $stmtBookGenre = $con->prepare("DELETE FROM book_genre WHERE Genre_ID = ?");
            $stmtBookGenre->execute([$genreID]);
            $con->commit();
        } catch(EXCEPTION $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;
        }
    }

    function updateAuthor($ID, $authorfname, $authorlname, $birthYear, $nationality){
    $con = $this->opencon();

        try{
            $con->beginTransaction();

            $stmt = $con->prepare("
                UPDATE author 
                SET 
                    author_firstname = ?, 
                    author_lastname = ?, 
                    author_birthyear = ?, 
                    author_nationality = ?
                WHERE Author_ID = ?
            ");

            $stmt->execute([
                $authorfname,
                $authorlname,
                $birthYear,
                $nationality,
                $ID]);
            $con->commit();
            return true;
        } catch(PDOException $e){
            if($con->inTransaction()){
                $con->rollBack();
            }
            throw $e;
        }
    }

    function updateGenre($genreid,$genrename){
        $con = $this->opencon();
            try{
                $con->beginTransaction();
                $stmt = $con->prepare("UPDATE genre SET genre_name = ? WHERE genre_ID = ?");
                $stmt->execute([$genrename,$genreid]);
                $con->commit();
                return true;
            } catch(PDOEXCEPTION $e){
                if($con->inTransaction()){
                    $con->rollBack();
                }
                throw $e;
            }
    }

    function getActiveBorrowers(){
        $con = $this->opencon();
        return $con->query("SELECT Borrower_ID,
                                    CONCAT(borrowers.Borrower_firstname,' ',borrowers.Borrower_lastname)
                                    AS borrowerName FROM borrowers 
                                    WHERE borrowers.is_Active = 1 ")->fetchAll();
    }

  
    function getAvailableCopies(){
        $con = $this->opencon();
        return $con->query("
            SELECT book_copy.Copy_ID, book.Book_ID,book.Book_Title FROM book
            JOIN book_copy ON book.Book_ID = book_copy.Book_ID
            WHERE book_copy.Copy_status = 'AVAILABLE'
            ORDER BY book.Book_Title
        ")->fetchAll();
    }

    function createLoanwithItems( $borrower_id, $processed_by_user_id, $copy_ids, $li_duedate, $condition_out){
            $con = $this->opencon();
            try{        
                $con->beginTransaction();
                $insertLoanStmt = $con->prepare("INSERT INTO loan(Borrower_ID, 
                                                                processed_by, 
                                                                loan_status, 
                                                                loan_date)
                                                                VALUES (?, ?, 'Open', NOW())");             
                $insertLoanStmt->execute([$borrower_id,$processed_by_user_id]);
                $loan_id = $con->lastInsertId();

                $checkCopyStmt = $con->prepare("SELECT Copy_status FROM book_copy WHERE Copy_ID = ?");

                $activeLoanStmt = $con->prepare("
                    SELECT COUNT(*) as active_count FROM loan_item
                    JOIN loan ON loan_item.loan_ID = loan.loan_ID
                    WHERE loan_item.Copy_ID = ?
                    AND loan_item.return_at IS NULL
                    AND loan.loan_status = 'Open'
                ");
                $insertLoanItemStmt = $con->prepare("INSERT INTO loan_item(loan_ID, Copy_ID, duedate, condition_out) VALUES(?, ?, ?, ?)");
                $updateCopyStmt = $con->prepare("UPDATE book_copy SET Copy_Status ='On Loan' WHERE Copy_ID = ?");

            foreach ($copy_ids as $copy_id) {

                        $checkCopyStmt->execute([$copy_id]);
                        $copyStatus = $checkCopyStmt->fetch();

                        if (!$copyStatus) {
                            throw new Exception("Copy ID $copy_id does not exist.");
                        }

                        if ($copyStatus['Copy_status'] !== 'Available') {
                            throw new Exception("Copy ID $copy_id is not available.");
                        }

                        $activeLoanStmt->execute([$copy_id]);
                        $activeLoan = $activeLoanStmt->fetch();

                        if ($activeLoan['active_count'] > 0) {
                            throw new Exception("Copy already on active loan.");
                        }

                        $insertLoanItemStmt->execute([$loan_id, $copy_id, $li_duedate, $condition_out]);
                        $updateCopyStmt->execute([$copy_id]);
                    }

            $con->commit();
            return $loan_id;

            } catch (Exception $e) {
                if ($con->inTransaction()) {
                    $con->rollBack();
                }
                throw $e;
            }
    
    }


    

    
}
?>