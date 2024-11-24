<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <title>Peaceful Network</title>
</head>
<script src="js/script.js"></script>

<body style="background-image: url('../img/bg.webp');">

    <?php include_once("../components/header-navbar.php"); ?>

    <!-- header navbar -->
    <div class="flex items-center justify-center h-screen">
        <div class="card-white w-full max-w-md">
            <h2 class="text-xl font-semibold text-center">Forgot Password</h2>
            <form action="../system/sendEmail.php" method="post" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" class="input-form" placeholder="Enter email" required>
                    <input type="hidden" name="type" value="reset">
                </div>
                <div>
                    <input type="submit" class="btn-green-500" value="Send">
                </div>
            </form>
        </div>
    </div>

</body>

</html>