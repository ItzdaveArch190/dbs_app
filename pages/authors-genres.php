<?php
    require_once('../classes/database.php');
    $con =  new database();

    $getAuthor = $con->retrieve_Author();
    $getGenre = $con->extractGenres();
    $TotalGenre = $con->countGenre();
    $TotalAuthor = $con->countAuthor();



    if (isset($_POST["add_author"])) {
    $firstname   = $_POST["author_firstname"];
    $lastname    = $_POST["author_lastname"];
    $birthyear   = $_POST["author_birth_year"];
    $nationality = $_POST["author_nationality"];

    try {
        $addAuthor = $con->addAuthor($firstname,$lastname,$birthyear,$nationality
        );

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
        <li class="nav-item"><a class="nav-link active" href="authors-genres.html">Authors &amp; Genres</a></li>
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
            <button class="btn btn-outline-primary w-100" name="save_genre" type="submit">Save Genre</button>
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
              </tr>
              <?php }?>
            </tbody>
          </table>
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
              </tr>
            </thead>
            <tbody>
              <?php foreach($getGenre as $genre) {?>
              <tr>
                <td><?php echo $genre['Genre_ID'];?></td>
                <td><?php echo $genre['genre_name'];?></td>
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

</body>
</html>