<?php
    require_once('../classes/database.php');
    $con = new database();

    $Viewbook = $con->viewBooks();
    $retrieveAuthor = $con->retrieve_Author();
    $extractGenre = $con->extractGenres();

    $getbook = $con->viewBook();

    $addNewBook = 'null';
    $newBookMessage = '';
    
    $AddNewCopy = 'null';
    $newCopyMessage = '';

    if(isset($_POST["Save-book"])) {

        $bookTitle = $_POST['book_title'];
        $bookIsbn = $_POST['book_isbn'];
        $bookPublicationYear = $_POST['book_publication_year'];
        $bookEdition = $_POST['book_edition'];
        $bookPublisher = $_POST['book_publisher'];
        try{
            $addBook = $con->AddBook($bookTitle, $bookIsbn, $bookPublicationYear, $bookEdition, $bookPublisher);  

          } catch(Exception $e){

            echo "Error: " . $e->getMessage();
        }
        
    }

    if(isset($_POST['btn_copy'])){

      $bookCopy = $_POST['book_id'];
      $status = $_POST['status'];

      $bookCopy = $con->bookCopy($bookCopy, $status);
    }

    if(isset($_POST['assignAuth'])){
      $Auth_ID = $_POST['author_id'];
      $book_id = $_POST['book_id'];

      $bookauth = $con->insertBookAuthor($book_id, $Auth_ID);
    }


    if(isset($_POST['save-genre'])) {
      $book_id = $_POST['book_id'];
      $genre_id = $_POST['genre_id'];

      $insertBookGenre = $con->insertBookGenre($book_id, $genre_id);
    }
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Books — Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../bootstrap-5.3.3-dist/css/bootstrap.css">
  <link rel="stylesheet" href="../sweetalert/dist/sweetalert2.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="admin-dashboard.html">Library Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navBooks">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navBooks" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="admin-dashboard.html">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link active" href="books.html">Books</a></li>
        <li class="nav-item"><a class="nav-link" href="borrowers.html">Borrowers</a></li>
        <li class="nav-item"><a class="nav-link" href="checkout.html">Checkout</a></li>
        <li class="nav-item"><a class="nav-link" href="return.html">Return</a></li>
        <li class="nav-item"><a class="nav-link" href="catalog.html">Catalog</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <span class="badge badge-soft">Role: ADMIN</span>
        <a class="btn btn-sm btn-outline-secondary" href="login.html">Logout</a>
      </div>
    </div>
  </div>
</nav>

<main class="container py-4">
  <div class="row g-3">
    <div class="col-12 col-lg-4">
      <div class="card p-4">
        <h5 class="mb-1">Add Book</h5>
        <p class="small-muted mb-3">Creates a row in <b>Books</b>.</p>

        <!-- Later in PHP: action="../php/books/create.php" method="POST" -->
        <form action="#" method="POST">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input class="form-control" name="book_title" required> 
          </div>
          <div class="mb-3">
            <label class="form-label">ISBN</label>
            <input class="form-control" name="book_isbn" placeholder="optional">
          </div>
          <div class="mb-3">
            <label class="form-label">Publication Year</label>
            <input class="form-control" name="book_publication_year" type="number" min="1500" max="2100" placeholder="optional">
          </div>
          <div class="mb-3">
            <label class="form-label">Edition</label>
            <input class="form-control" name="book_edition" placeholder="optional">
          </div>
          <div class="mb-3">
            <label class="form-label">Publisher</label>
            <input class="form-control" name="book_publisher" placeholder="optional">
          </div>
          <button class="btn btn-primary w-100" name="Save-book"type="submit">Save Book</button>
        </form>
      </div>

      <div class="card p-4 mt-3">
        <h6 class="mb-2">Add Copy</h6>
        <p class="small-muted mb-3">Creates a row in <b>BookCopy</b>.</p>
        <!-- Later in PHP: action="../php/copies/create.php" method="POST" -->
        <form action="#" method="POST">
          <div class="mb-3">
            <label class="form-label">Book</label>
            <select class="form-select" name="book_id" required>
              <option value="">Select book</option>
              
              <?php 
                foreach($Viewbook as $Book){
                  echo '<option value="'. $Book['Book_ID'] .'">' .$Book['Book_ID'].' - ' .$Book['Book_Title'] . '</option>';
                }
              ?>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" required>
              <option value="AVAILABLE">AVAILABLE</option>
              <option value="ON_LOAN">ON_LOAN</option>
              <option value="LOST">LOST</option>
              <option value="DAMAGED">DAMAGED</option>
              <option value="REPAIR">REPAIR</option>
            </select>
          </div>
          <button class="btn btn-outline-primary w-100" name="btn_copy" type="submit">Add Copy</button>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <div class="card p-4">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-end mb-3">
          <div>
            <h5 class="mb-1">Books List</h5>
            <div class="small-muted">Placeholder rows. Replace with PHP + MySQL output.</div>
          </div>
          <div class="d-flex gap-2">
            <input class="form-control" style="max-width: 260px;" placeholder="Search title / ISBN...">
            <button class="btn btn-outline-secondary">Search</button>
          </div>
        </div>

        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Book ID</th>
                <th>Title</th>
                <th>ISBN</th>
                <th>Year</th>
                <th>Publisher</th>
                <th>Copies</th>
                <th>Available</th>
                <th class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
          

              <?php
                  foreach($getbook as $vb){
                    echo'<tr>';
                    echo '<td>'.$vb['Book_ID'].'</td>';
                    echo '<td>'.$vb['Book_Title'].'</td>';
                    echo '<td>'.$vb['Book_ISBN'].'</td>';
                    echo '<td>'.$vb['Book_publicationyear'].'</td>';
                    echo '<td>'.$vb['Copies'].'</td>';
                    echo'<td>'.$vb['Available_Copies'].'</td>';
                    echo '<td><span class="badge text-bg-success">2</span></td>';
                    echo'<td class="text-end">';
                    echo '<button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBookModal">Edit</button>';
                    echo '<button class="btn btn-sm btn-outline-danger">Delete</button>';
                    echo'</td>';
                    echo'</tr>';
                  }
              ?>
                
              
              
            </tbody>
          </table>
        </div>

        <hr class="my-4">

        <div class="row g-3">
          <div class="col-12 col-lg-6">
            <div class="border rounded p-3">
              <h6 class="mb-2">Assign Author to Book</h6>
              <p class="small-muted mb-3">Creates a row in <b>BookAuthors</b>.</p>
              <!-- Later in PHP: action="../php/bookauthors/create.php" method="POST" -->
              <form action="#" method="POST" class="row g-2">
                <div class="col-12 col-md-6">
                  <select class="form-select" name="book_id" required>
                    <option value="">Select book</option>
                      <?php 
                          foreach($Viewbook as $Book){
                            echo '<option value="'. $Book['Book_ID'].' ">' .$Book['Book_ID'].' ' .$Book['Book_Title'] . '</option>';
                      }
                      ?>
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <select class="form-select" name="author_id" required>
                    <option value="">Select author</option>
                    <?php
                      foreach($retrieveAuthor as $author){
                        echo'<option value="'. $author['Author_ID'].'">'. $author['author_firstname'] .' '. $author['author_lastname'] . '</option>';
                      }
                    ?>
                  </select>
                </div>
                <div class="col-12">
                  <button class="btn btn-outline-primary w-100" name="assignAuth" type="submit">Assign</button>
                </div>
              </form>
              <div class="small-muted mt-2">Unique constraint prevents duplicate (book_id, author_id).</div>
            </div>
          </div>

          <div class="col-12 col-lg-6">
            <div class="border rounded p-3">
              <h6 class="mb-2">Assign Genre to Book</h6>
              <p class="small-muted mb-3">Creates a row in <b>BookGenre</b>.</p>
              <!-- Later in PHP: action="../php/bookgenre/create.php" method="POST" -->
              <form action="#" method="POST" class="row g-2">
                <div class="col-12 col-md-6">
                  <select class="form-select" name="book_id" required>
                    <option value="">Select book</option>
                    <?php
                    foreach($Viewbook as $Book){
                      echo '<option value="'. $Book['Book_ID'] .'">' .$Book['Book_ID'].' - ' .$Book['Book_Title'] . '</option>';
                    }
                  ?>
                  </select>
                </div>
                <div class="col-12 col-md-6">
                  <select class="form-select" name="genre_id" required>
                    <option value="">Select genre</option>
                    <?php
                      foreach($extractGenre as $genre) {
                        echo'<option value="' . $genre['Genre_ID'] . '">'. $genre['genre_name'] . '</option>';
                      }
                    ?>
                  </select>
                </div>
                <div class="col-12">
                  <button class="btn btn-outline-primary w-100" name="save-genre" type="submit">Assign</button>
                </div>
              </form>
              <div class="small-muted mt-2">Unique constraint prevents duplicate (genre_id, book_id).</div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</main>

<!-- Edit Book Modal (UI only) -->
<div class="modal fade" id="editBookModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Book</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Later in PHP: load existing values -->
        <form action="#" method="POST">
          <div class="mb-3">
            <label class="form-label">Title</label>
            <input class="form-control" value="Noli Me Tangere">
          </div>
          <div class="mb-3">
            <label class="form-label">ISBN</label>
            <input class="form-control" value="9789710810736">
          </div>
          <div class="mb-3">
            <label class="form-label">Publisher</label>
            <input class="form-control" value="National Book Store">
          </div>
          <button class="btn btn-primary w-100" type="button">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>-->
<script src ="../bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
<script src="../sweetalert/dist/sweetalert2.min.js"></script>

<script>
  const newBookAdd = <?php json_encode($addNewBook)?>;
  const BookMessage = <?php json_encode($newBookMessage)?>;

  if(newBookAdd === 'success') {
    Swal.fire({
      icon:'success',
      title: 'Success',
      text: BookMessage,
      confirmButtonText: 'OK'
    });
  } else if(newBookAdd ===  'error'){
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text:BookMessage,
      confirmButtonText: 'OK'
    });
  }

</script>

<script>
  const newBookCopy = <?php json_encode($addNewCopy)?>;
  const CopyMessage = <?php json_encode($newCopyMessage)?>;

  if(newBookCopy === 'success') {
    Swal.fire({
      icon:'success',
      title: 'Success',
      text: CopyMessage,
      confirmButtonText: 'OK'
    });
  } else if(newBookCopy ===  'error'){
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text:CopyMessage,
      confirmButtonText: 'OK'
    });
  }
    
    

</script>

</body>
</html>