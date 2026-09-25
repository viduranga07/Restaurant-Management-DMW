<?php
require_once 'config.php';
if (isset($_SESSION['user'])) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = "Enter a valid email and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $u = $stmt->fetch();
        if ($u && password_verify($password, $u['password'])) {
            $_SESSION['user'] = ['id'=>$u['id'],'name'=>$u['name'],'role'=>$u['role']];
            header('Location: dashboard.php'); exit;
        }
        $error = "Invalid login details.";
    }
}
?>
<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta 
        name="viewport" 
        content="width=device-width, initial-scale=1"
    >

    <title>GastroNova | Restaurant Management</title>

    <link 
        rel="stylesheet" 
        href="assets/css/style.css"
    >

</head>


<body class="login-page">

    <main class="luxury-login">


        <!-- LEFT SIDE: RESTAURANT IMAGE -->

        <section class="login-visual">

            <div class="visual-overlay"></div>

            <div class="visual-content">

                <div class="luxury-mark">
                    ✦
                </div>

                <p class="eyebrow">
                    FINE DINING · SMART MANAGEMENT
                </p>

                <h1>
                    Where every<br>
                    detail matters.
                </h1>

                <div class="gold-line"></div>

                <p class="visual-description">
                    A refined management experience
                    designed for modern hospitality.
                </p>

                <div class="visual-footer">
                    <span>GASTRONOVA</span>
                    <span>EST. 2026</span>
                </div>

            </div>

        </section>


        <!-- RIGHT SIDE: LOGIN -->

        <section class="login-panel">

            <div class="login-content">


                <!-- BRAND -->

                <div class="login-brand">

                    <div class="brand-symbol">
                        ✦
                    </div>

                    <div>
                        <div class="brand-name">
                            GASTRONOVA
                        </div>

                        <div class="brand-subtitle">
                            RESTAURANT MANAGEMENT SYSTEM
                        </div>
                    </div>

                </div>


                <!-- WELCOME -->

                <div class="login-heading">

                    <p class="eyebrow dark">
                        STAFF PORTAL
                    </p>

                    <h2>
                        Welcome back.
                    </h2>

                    <p>
                        Sign in to manage today's
                        restaurant operations.
                    </p>

                </div>


                <!-- ERROR MESSAGE -->

                <?php if($error): ?>

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


                <!-- DEMO INFORMATION -->

                <div class="demo-info">

                    <span class="demo-dot"></span>

                    <div>

                        <strong>Demo access</strong>

                        <p>
                            manager@gastronova.com
                            ·
                            password
                        </p>

                    </div>

                </div>


                <!-- FOOTER -->

                <footer class="login-footer">

                    <span>
                        © 2026 GastroNova
                    </span>

                    <span>
                        Restaurant Operations Platform
                    </span>

                </footer>


            </div>

        </section>

    </main>


</body>

</html>