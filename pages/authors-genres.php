<?php
    require_once('../classes/database.php');
    $con =  new database();
    session_start();
    


    $getAuthor = $con->retrieve_Author();
    $getGenre = $con->extractGenres();
    $TotalGenre = $con->countGenre();
    $TotalAuthor = $con->countAuthor();
    
    



    if(isset($_POST["add_author"])) {
    $firstname = $_POST["author_firstname"];
    $lastname = $_POST["author_lastname"];
    $birthyear = $_POST["author_birth_year"];
    $nationality = $_POST["author_nationality"];

    try {
        $addAuthor = $con->addAuthor($firstname,$lastname,$birthyear,$nationality);

        if ($addAuthor) {
            $authorstatus  = 'success';
            $authormessage = 'Author added successfully!';
        } else {
            $authorstatus  = 'error';
            $authormessage = 'Failed to add author.';
        }

    } catch (Exception $e) {
        $authorstatus  = 'error';
        $authormessage = 'An unexpected error occurred.';
    }
}


    if(isset($_POST["save_genre"])){
        $genre = $_POST["genre_name"];
      try{
        $insertGenre = $con->insertGenre($genre);

          if ($insertGenre) {
              $genrestatus  = 'success';
              $genremessage = 'Genre added successfully!';
          } 
        } catch(EXCEPTION $e){
            $genrestatus  = 'error';
            $genremessage = 'Failed to add genre.';
          }   
    }
    if(isset($_POST["delete"])){

        $authorID = $_POST["deletebyID"];
        $authorName = $_POST["delete__NAME"];
        try{
            $con->deleteAuthor($authorID);
            $_SESSION['success_message'] = $authorName . " " . "has been deleted.";
            header("Location: authors-genres.php");
            exit();
        } catch(EXCEPTION $e){
            $error_message = 'Cannot delete this author, It may have active books existing.';
        }
    }

    if(isset($_POST['delete_genre'])){
        $genreid = $_POST["genre_id"];
        $genrename = $_POST["genre_name"];

        try{
          $con->deleteGenre($genreid);
          $_SESSION['success_message'] = $authorName . " " . "has been deleted.";
            header("Location: authors-genres.php");
            exit();
        }catch(EXCEPTION $e){
            $error_message = 'Cannot delete this genre, It may have active books existing.';
        }
    }

    if(isset($_POST['update_author'])){
    $id = $_POST['AuthorID'];
    $fname = $_POST['author_fname'];
    $lname = $_POST['author_lname'];
    $year = $_POST['BirthYear'];
    $nationality = $_POST['authorNationality'];

    try{
        $result = $con->updateAuthor($id, $fname, $lname, $year, $nationality);

        $_SESSION['success_message'] = "Author updated successfully!";
        
    } catch(Exception $e){
        $error_message = "Failed to update author.";
    }
}
    if(isset($_POST['update_genre'])){
      $genreId = $_POST['genID'];
      $genreName = $_POST['genname'];

      try{
        $result = $con->updateGenre($genreId,$genreName);
        $_SESSION['success_message'] = "Genre Updated Successfully.";

      }catch(EXCEPTION $e){
          $error_message = "Failed to update author.";
      }
    }
?>


<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Authors and Genres - Admin (Teaching Demo)</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <link rel="stylesheet" href="../sweetalert/dist/sweetalert2.css">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
  <div class="container">
    <a class="navbar-brand fw-semibold" href="admin-dashboard.php">Library Admin</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navAdminStatic">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="navAdminStatic" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto gap-lg-1">
        <li class="nav-item"><a class="nav-link" href="admin-dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="books.php">Books</a></li>
          <li class="nav-item"><a class="nav-link" href="books.php">Books</a></li>
        <li class="nav-item"><a class="nav-link active" href="authors-genres.php">Authors &amp; Genres</a></li>
        <li class="nav-item"><a class="nav-link" href="borrowers.php">Borrowers</a></li>
        <li class="nav-item"><a class="nav-link" href="checkout.php">Checkout</a></li>
        <li class="nav-item"><a class="nav-link" href="return.php">Return</a></li>
        <li class="nav-item"><a class="nav-link" href="catalog.php">Catalog</a></li>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <span class="badge badge-soft">Role: ADMIN</span>
        <a class="btn btn-sm btn-outline-secondary" href="login.php">Logout</a>
      </div>
    </div>
  </div>
</nav>

<main class="container py-4">
  <?php if(isset($error_message)){ ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error</strong><?php echo $error_message;?>
    <button type="button" class="btn-close" data-bs-dismissible="alert" aria-label="Close">
    </button>
  </div>
<?php } ?>

<?php  if(isset($_SESSION['success_message'])){?>

    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Success!</strong> <?php echo $_SESSION['success_message'];?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php 
      unset($_SESSION['success_message']);
    }?>


  <div class="row g-3">

    <div class="col-12 col-lg-6">
      <div class="card p-4 h-100">
        <h5 class="mb-1">Add Author</h5>
        <p class="small-muted mb-3">Sample form for the Authors table.</p>

        <form action="#" method="POST" class="row g-2">
          <div class="col-12 col-md-6">
            <label class="form-label">First Name</label>
            <input class="form-control" name="author_firstname" placeholder="e.g., Jose" required />
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Last Name</label>
            <input class="form-control" name="author_lastname" placeholder="e.g., Rizal" required />
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Birth Year</label>
            <input class="form-control" name="author_birth_year" type="number" min="1" max="2100" placeholder="optional" />
          </div>
          <div class="col-12 col-md-6">
            <label class="form-label">Nationality</label>
            <input class="form-control" name="author_nationality" placeholder="optional" />
          </div>
          <div class="col-12">
            <button class="btn btn-primary w-100" name="add_author" type="submit">Save Author</button>
          </div>
          
        </form>
      </div>
    </div>

    


    <div class="col-12 col-lg-6">
      <div class="card p-4 h-100">
        <h5 class="mb-1">Add Genre</h5>
        <p class="small-muted mb-3">Sample form for the Genres table.</p>

        <form action="#" method="POST" class="row g-2">
          <div class="col-12">
            <label class="form-label">Genre Name</label>
            <input class="form-control" name="genre_name" placeholder="e.g., Classic" required />
          </div>
          <div class="col-12">
            <button class="btn btn-primary w-100" name="save_genre" type="submit">Save Genre</button>
          </div>
        </form>
      </div>
    </div>

    <div class="col-12 col-lg-8">
      <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Authors List</h5>
          
          <span class="small-muted"><?php echo "Total of $TotalAuthor";?></span>

        </div>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Author ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Birth Year</th>
                <th>Nationality</th>
                <th>Action</th>
                
              </tr>
            </thead>
            <tbody>
              <?php
                  foreach($getAuthor as $author){?> 
              <tr>
                <td><?php echo $author['Author_ID'];?></td>
                <td><?php echo $author['author_firstname'];?></td>
                <td><?php echo $author['author_lastname'];?></td>
                <td><?php echo $author['author_birthyear'];?></td>
                <td><?php echo $author['author_nationality'];?></td>
                <td>
                  <button type="button" class="btn btn-outline-primary"
                  data-bs-toggle="modal"
                  data-bs-target="#editAuthorModal"
                  data-bs-author-id="<?php echo $author['Author_ID']; ?>"
                  data-bs-author-firstname="<?php echo $author['author_firstname']; ?>"
                  data-bs-author-lastname="<?php echo $author['author_lastname']; ?>"
                  data-bs-author-birthyear="<?php echo $author['author_birthyear']; ?>"
                  data-bs-author-nationality="<?php echo $author['author_nationality']; ?>">Edit</button>
               
                  <button type="button" class="btn btn-outline-danger" 
                  data-bs-toggle="modal"
                  data-bs-target="#ondeleteTarget"
                  data-bs-author-id = <?php echo $author['Author_ID'];?>
                  data-bs-author-name = "<?php echo $author['author_firstname'] .' '.$author['author_lastname']; ?>" >Delete</button>
                </td>
              </tr>
              <?php }?>
            </tbody>
          </table>
        </div>
        
      </div>
    </div>

<div class="modal fade" id="editGenreModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
 
      <div class="modal-header">
        <h5 class="modal-title">Edit Genre list</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Later in PHP: load existing values -->
        <form action="" method="POST">
          <div class="mb-3">
            <label class="form-label">Genre ID</label>
            <input class="form-control" name="genID" id="genID" value="" readonly>
          </div>

          <div class="mb-3">
            <label class="form-label">Genre Name</label>
            <input class="form-control" name="genname" id="genreName" value="">
          </div>

          <button class="btn btn-primary w-100" name="update_genre" type="submit">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!--UI inEdit Author Info-->
<div class="modal fade" id="editAuthorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
 
      <div class="modal-header">
        <h5 class="modal-title">Edit Author</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Later in PHP: load existing values -->
        <form action="" method="POST">
 
          <div class="mb-3">
            <label class="form-label">Author ID</label>
            <input class="form-control" name="AuthorID" id="edit_author_ID" value="" readonly>
          </div>
 
          <div class="mb-3">
            <label class="form-label">Firstname</label>
            <input class="form-control" name="author_fname" id="edit_authorFirstname">
          </div>
 
          <div class="mb-3">
            <label class="form-label">Lastname</label>
            <input class="form-control" name="author_lname" id="edit_authorLastname">
          </div>
 
          <div class="mb-3">
            <label class="form-label">Birth Year</label>
            <input class="form-control" name="BirthYear" id="edit_authoryear">
          </div>
 
          <div class="mb-3">
            <label class="form-label">Nationality</label>
            <input class="form-control" name="authorNationality" id="editAuthorNationality">
          </div>
 
          <button class="btn btn-primary w-100" name="update_author" type="submit">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

    <!--UI On Delete Author Modal-->
<div class="modal fade" id="ondeleteTarget" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p> Are you sure you want delete <strong id="delete_Author"></strong></p>

        <form action="#" method="POST">
            <input type="hidden" id="delete_by_ID" name="deletebyID">
            <input type="hidden" id="delete_by_Name" name="delete__NAME">

            <div class="modal-footer">
              <button type="button" name="cancel" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" name="delete" class="btn btn-primary">Save changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!--UI Delete Genre-->
<div class="modal fade" id="ondeleteGenre" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p> Are you sure you want delete <strong id="delete_genre"></strong></p>

        <form action="#" method="POST">
            <input type="hidden" id="delete_genreId" name="genre_id">
            <input type="hidden" id="delete_genre" name="genre_name">

            <div class="modal-footer">
              <button type="button" name="cancel" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" name="delete_genre" class="btn btn-primary">Save changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

    <div class="col-12 col-lg-4">
      <div class="card p-4 h-100">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0">Genres List</h5>
          
          <span class="small-muted"><?php echo "Total of $TotalGenre";?></span>
          
        </div>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <thead class="table-light">
              <tr>
                <th>Genre ID</th>
                <th>Genre Name</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($getGenre as $genre) {?>
              <tr>
                <td><?php echo $genre['Genre_ID'];?></td>
                <td><?php echo $genre['genre_name'];?></td>
                
                  <td>
                  <button type="button" class="btn btn-outline-primary" 
                  data-bs-toggle="modal"
                  data-bs-target="#editGenreModal"
                  data-bs-genre-id = <?php echo $genre['Genre_ID'];?>
                  data-bs-genre-name = <?php echo $genre['genre_name']; ?> >Edit</button>
              
                  <button type="button" class="btn btn-outline-danger" 
                  data-bs-toggle="modal"
                  data-bs-target="#ondeleteGenre"
                  data-bs-genre-id = <?php echo $genre['Genre_ID'];?>
                  data-bs-genre-name = <?php echo $genre['genre_name']; ?> >Delete</button>
                </td>
                
              </tr>
              <?php }?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="../sweetalert/dist/sweetalert2.min.js"></script>
<script>
  const authorstatus  = <?php echo json_encode($authorstatus ?? null); ?>;
  const authormessage = <?php echo json_encode($authormessage ?? null); ?>;

  const genrestatus   = <?php echo json_encode($genrestatus ?? null); ?>;
  const genremessage  = <?php echo json_encode($genremessage ?? null); ?>;

  if (authorstatus === 'success') {
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: authormessage,
      confirmButtonText: 'OK'
    });
  } else if (authorstatus === 'error') {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: authormessage,
      confirmButtonText: 'OK'
    });
  }

  if (genrestatus === 'success') {
    Swal.fire({
      icon: 'success',
      title: 'Success',
      text: genremessage,
      confirmButtonText: 'OK'
    });
  } else if (genrestatus === 'error') {
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: genremessage,
      confirmButtonText: 'OK'
    });
  }
</script>

<script>

  //para sa pag delete ng author
  const deleteAuthorModal = document.getElementById('ondeleteTarget');

  deleteAuthorModal.addEventListener('show.bs.modal', function(event){

    const btn = event.relatedTarget;
    const AuthorId = btn.getAttribute('data-bs-author-id');
    const authorName = btn.getAttribute('data-bs-author-name');

    document.getElementById('delete_by_ID').value = AuthorId || '';
    document.getElementById('delete_by_Name').value = authorName || '';
    document.getElementById('delete_Author').textContent = authorName || '';
});
</script>

<!--Para sa genre-->
<script>
    const deleteGenreModal =  document.getElementById('ondeleteGenre');
    deleteGenreModal.addEventListener('show.bs.modal', function(event){

    const genre = event.relatedTarget;
    const genreId =  genre.getAttribute('data-bs-genre-id');
    const genreName =  genre.getAttribute('data-bs-genre-name');

    document.getElementById('delete_genreId').value = genreId || '';
    document.getElementById('delete_genre').textContent = genreName || '';
    });
</script>

<!--Edit para sa authors-->
<script>
const editModal = document.getElementById('editAuthorModal');

editModal.addEventListener('shown.bs.modal', function (event) {
    const button = event.relatedTarget;

    document.getElementById('edit_author_ID').value = button.getAttribute('data-bs-author-id');
    document.getElementById('edit_authorFirstname').value = button.getAttribute('data-bs-author-firstname');
    document.getElementById('edit_authorLastname').value = button.getAttribute('data-bs-author-lastname');
    document.getElementById('edit_authoryear').value = button.getAttribute('data-bs-author-birthyear');
    document.getElementById('editAuthorNationality').value = button.getAttribute('data-bs-author-nationality');
});
</script>

<script>
  const editgenre =  document.getElementById('editGenreModal');

  editgenre.addEventListener('shown.bs.modal', function(event){
    const button =  event.relatedTarget;
    document.getElementById('genID').value = button.getAttribute('data-bs-genre-id');
    document.getElementById('genreName').value = button.getAttribute('data-bs-genre-name');
  });
</script>

</body>
</html>