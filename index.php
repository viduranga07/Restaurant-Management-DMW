<?php

require_once 'config.php';

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (
        !filter_var($email, FILTER_VALIDATE_EMAIL)
        || $password === ''
    ) {

        $error = "Enter a valid email and password.";

    } else {

        $stmt = $pdo->prepare(
            "SELECT * FROM users WHERE email = ?"
        );

        $stmt->execute([$email]);

        $u = $stmt->fetch();

        if (
            $u
            && password_verify(
                $password,
                $u['password']
            )
        ) {

            $_SESSION['user'] = [
                'id'   => $u['id'],
                'name' => $u['name'],
                'role' => $u['role']
            ];

            header('Location: dashboard.php');
            exit;
        }

        $error = "Invalid login details.";
    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        GastroNova | Staff Portal
    </title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

</head>


<body class="login-page">


    <main class="luxury-login">


        <!-- =========================================
             FULL RESTAURANT BACKGROUND
             ========================================= -->

        <div class="login-background">


            <div class="background-overlay"></div>


            <!-- =====================================
                 LEFT RESTAURANT MESSAGE
                 ===================================== -->

            <section class="restaurant-message">

                <div class="gold-symbol">
                    ✦
                </div>


                <p class="restaurant-eyebrow">
                    FINE DINING · SMART MANAGEMENT
                </p>


                <h1>
                    Where every<br>
                    detail matters.
                </h1>


                <div class="gold-line"></div>


                <p class="restaurant-description">
                    A refined management experience
                    designed for modern hospitality.
                </p>


                <div class="restaurant-footer">

                    <span>
                        GASTRONOVA
                    </span>

                    <span>
                        EST. 2026
                    </span>

                </div>

            </section>



            <!-- =====================================
                 RIGHT LOGIN AREA
                 ===================================== -->

            <section class="login-area">


                <div class="login-box">


                    <!-- LOGO -->

                    <div class="login-logo">

                        <img
                            src="assets/images/gastronova-logo.png"
                            alt="GastroNova Logo"
                        >

                    </div>



                    <!-- STAFF PORTAL -->

                    <p class="portal-label">
                        STAFF PORTAL
                    </p>



                    <!-- HEADING -->

                    <h2>
                        Welcome back.
                    </h2>


                    <p class="login-intro">
                        Sign in to manage today's
                        restaurant operations.
                    </p>



                    <!-- ERROR -->

                    <?php if ($error): ?>

                        <div class="login-error">

                            <span>!</span>

                            <?= e($error) ?>

                        </div>

                    <?php endif; ?>



                    <!-- LOGIN FORM -->

                    <form
                        method="post"
                        id="loginForm"
                        class="luxury-form"
                    >


                        <!-- EMAIL -->

                        <div class="form-group">

                            <label for="email">
                                EMAIL ADDRESS
                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                placeholder="manager@gastronova.com"
                                autocomplete="email"
                                required
                            >

                        </div>



                        <!-- PASSWORD -->

                        <div class="form-group">

                            <div class="password-label">

                                <label for="password">
                                    PASSWORD
                                </label>

                                <span>
                                    SECURE ACCESS
                                </span>

                            </div>


                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >

                        </div>



                        <!-- SIGN IN -->

                        <button
                            type="submit"
                            class="luxury-button"
                        >

                            <span>
                                SIGN IN TO DASHBOARD
                            </span>

                            <span class="button-arrow">
                                →
                            </span>

                        </button>


                    </form>



                    <!-- DEMO ACCESS -->

                    <div class="demo-info">

                        <span class="demo-dot"></span>

                        <div>

                            <strong>
                                Demo access
                            </strong>

                            <p>
                                manager@gastronova.com · password
                            </p>

                        </div>

                    </div>



                    <!-- FOOTER -->

                    <div class="login-footer">

                        <span>
                            © 2026 GastroNova
                        </span>

                        <span>
                            Restaurant Operations Platform
                        </span>

                    </div>


                </div>

            </section>


        </div>

    </main>


</body>

</html>