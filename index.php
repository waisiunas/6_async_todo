<?php require_once './database/connection.php'; ?>

<?php
session_start();

if (isset($_SESSION['user'])) {
    header('location: ./dashboard.php');
}
$email = "";
if (isset($_POST['submit'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    if (empty($email)) {
        $error = "Provide your email!";
    } elseif (empty($password)) {
        $error = "Provide your password!";
    } else {
        $hashed_password = sha1($password);
        $sql = "SELECT * FROM `users` WHERE `email` = '$email' AND `password` = '$hashed_password'";
        $result = $conn->query($sql);
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $_SESSION['user'] = $user;
            header('location: ./dashboard.php');
        } else {
            $error = "Invalid login details!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="./assets/img/icons/icon-48x48.png" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/pages-sign-in.html" />

    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="./assets/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">

                        <div class="text-center mt-4">
                            <h1 class="h2">Welcome back!</h1>
                            <p class="lead">
                                Login to your account to continue
                            </p>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <?php
                                if (isset($error)) { ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <?php echo $error ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php
                                }

                                if (isset($success)) { ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <?php echo $success ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php
                                }
                                ?>
                                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" id="email" placeholder="Enter your email!" value="<?php echo $email ?>" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" name="password" id="password" placeholder="Enter your password!" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <input type="submit" value="Login" name="submit" class="btn btn-primary">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            Do not have an account? <a href="./register.php">Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="./assets/js/app.js"></script>

</body>

</html>