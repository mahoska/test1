<?php

class BookModel extends Model
{

    public function getAllBooks()
    {
        try{
            $sth = $this->pdo->query("SELECT id, name, price, discount FROM book"); 
            $collection = $this->getFetchAccoss($sth);
            return ['status'=>200, 'data'=>$collection];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        }
    }

    public function filterBooksByGanre($ganre_id)
    {
        try{
            $sth = $this->pdo->prepare("SELECT id, name, price, discount "
                . "FROM book_ganre JOIN book  ON book.id = book_ganre.book_id "
                . "WHERE book_ganre.ganre_id = :ganre_id");
            $sth->execute(['ganre_id'=>$ganre_id]);
            $collection = $this->getFetchAccoss($sth);
            return ['status'=>200, 'data'=>$collection];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        }
    }
    
    public function filterBooksByAuthor($author_id)
    {
        try{
            $sth = $this->pdo->prepare("SELECT id, name, price, discount "
                . "FROM book_author JOIN book  ON book.id = book_author.book_id "
                . "WHERE book_author.author_id = :author_id");
            $sth->execute(['author_id'=>$author_id]);
            $collection = $this->getFetchAccoss($sth);
            return ['status'=>200, 'data'=>$collection];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        }
    }
    
    public function fullFilterBooks($search){
        try{
            //if wants search in title book and fio author
            /*
             $sth = $this->pdo->query("SELECT id, name, price, discount"
                 . " FROM  book "
                 . " WHERE name LIKE '%$search%'"
                 . " UNION"
                 . " SELECT book.id, book.name, price, discount" 
                 . " FROM author JOIN (book_author JOIN book  ON book.id = book_author.book_id) ON  author.id = book_author.author_id"
                 . " WHERE author.name LIKE '%$search%' OR author.surname LIKE '%$search%'");
             */
            $sth = $this->pdo->query("SELECT id, name, price, discount"
                 . " FROM  book "
                 . " WHERE name LIKE '%$search%'");
            $collection = $this->getFetchAccoss($sth);
            return ['status'=>200, 'data'=>$collection];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        }
    }
    
    public function getfullInfoBook($book_id)
    {
        try{
            /*$sth = $this->pdo->prepare(
                "SELECT book.id, book.name, price, discount, description,"
                ." GROUP_CONCAT(DISTINCT CONCAT(author.name, ' ', author.surname)"
                ." ORDER BY author.surname ASC SEPARATOR ', ') AS authors,"
                ." GROUP_CONCAT(DISTINCT ganre.name ORDER BY ganre.name ASC SEPARATOR ', ') AS ganres"
                ." FROM author RIGHT JOIN (book_author LEFT JOIN"
                ." (book RIGHT JOIN (book_ganre LEFT JOIN"
                ." ganre ON book_ganre.ganre_id = ganre.id) ON book.id = book_ganre.book_id)"
                ." ON book_author.book_id = book.id)  ON author.id=book_author.author_id"
                ." WHERE book.id = :book_id"
                ." GROUP BY book.id;");
             */
             $sth = $this->pdo->prepare(
                "SELECT book.id, book.name, price, discount, description,"
                ." GROUP_CONCAT(DISTINCT CONCAT(author.name, ' ', author.surname)" 
                ." ORDER BY author.surname ASC SEPARATOR ', ') AS authors,"
                ." GROUP_CONCAT(DISTINCT ganre.name ORDER BY ganre.name ASC SEPARATOR ', ') AS ganres"
                ." FROM author LEFT JOIN (book_author RIGHT JOIN"
                ." (book LEFT JOIN (book_ganre RIGHT JOIN "
                ." ganre ON book_ganre.ganre_id = ganre.id) ON book.id = book_ganre.book_id)"
                ." ON book_author.book_id = book.id)  ON author.id=book_author.author_id"
                ." WHERE book.id = :book_id"
                ." GROUP BY book.id");
            $sth->execute(['book_id' => $book_id]);
            $info = $sth->fetch(\PDO::FETCH_ASSOC);
            return ['status'=>200, 'data'=>$info];
        }catch(PDOException $err){
            file_put_contents('errors.txt', $err->getMessage(), FILE_APPEND); 
            return ['status'=>500, 'clientCode'=>'0006'];
        }
    }
    

}
