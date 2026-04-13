<?php

    class database{

        protected $country =  1;
    

    function opencon(): PDO {
        return new PDO(
            'mysql:host=localhost;
            dbname=besoriolab01',
            username:'root',
            password: '');
    }

    function insertUser($Username,$User_password_hash,$isActive) {
        $con = $this->opencon();

        try{
            $con->beginTransaction();
            $stmt = $con->prepare('INSERT INTO users (Username,User_password_hash,isActive) VALUES(?,?,?)');
            $stmt->execute([$Username, $User_password_hash, $isActive]);
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

    function AddBorrower($borrower_ID, $HouseNumber, $Street, $Barangay, $City, $Province, $postalCode, $isPrimary) {
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

    

    
        function ViewBorrowerUser(){
            $con =  $this->opencon();
            return $con->query('SELECT * FROM borrowers');
        }

    
}
?>