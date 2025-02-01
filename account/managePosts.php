<?php
//connect database
require_once("../system/conn.php");
require_once("../system/postSystem.php");
session_start();

$getCategory = getCategory($conn);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <link rel="icon" href="data:,">
    <title>Peaceful Network</title>
</head>

<body>
    <!-- header navbar -->
    <?php
    include_once("../components/header-navbar.php");
    ?>

    <!-- Hero Section -->
    <section class="relative bg-cover bg-center h-96" style="background-image: url('../img/bg.webp');">

        <div class="absolute inset-0 bg-black opacity-50"></div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center text-white">
            <h1 class="text-4xl font-bold mb-4">Minecraft Peaceful Network</h1>
            <p class="text-lg mb-8">Lorem ipsum dolor sit amet consectetur adipisicing elit. Laborum, consectetur.</p>
            <div class="space-x-4">
                <?php
                if (isset($_SESSION['userId'])) {
                ?>
                    <h4 class="font-bold text-2xl">Welcome! <?php echo $_SESSION['username']; ?></h4><br>
                    <a href="account/managePosts.php" class="btn-blue-500"><i class="fa-solid fa-pen-to-square"></i> Manage Posts</a>
                <?php } else { ?>
                    <a href="/account/signup.php" class="btn-blue-400-outline">SIGN UP</a>
                    <a href="/account/login.php" class="btn-green-400-outline">LOGIN</a>
                <?php } ?>
            </div>
        </div>
    </section>

    <div class="bg-gray-100 min-h-screen p-6">
        <div class="card-white">
            <h1 class="text-4xl text-center font-bold">จัดการโพสต์</h1>
            <div class="overflow-x-auto overflow-y-auto h-96">
                <table class="table-auto w-full border border-slate-400 border-collapse text-center">
                    <thead>
                        <tr>
                            <th class="">title</th>
                            <th class="">content</th>
                            <th class="">createAt</th>
                            <th class="">loveCount</th>
                            <th class="">imagePost</th>
                            <th class="">Edit</th>
                            <th class="">Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $userId = $_SESSION['userId'];
                        $fetchPostUser = fetchPostUser($conn, $userId);
                        foreach ($fetchPostUser as $post) {
                        ?>
                            <tr class="table-hover">
                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                <td><?php echo htmlspecialchars($post['content']); ?></td>
                                <td><?php echo htmlspecialchars($post['createdAt']); ?></td>
                                <td><i class="text-red-400 fa-solid fa-heart"></i> <?php echo htmlspecialchars($post['loveCount']); ?></td>
                                <td class="flex justify-center items-center"><img src="../img/posts_image/<?php echo htmlspecialchars($post['imagePost']); ?>" class="w-32 h-32 object-cover rounded" alt="post"></td>
                                <td>
                                    <button id="modalToggle" class="btn-blue-500 ">
                                        Edit
                                    </button>

                                    <div id="modalBackdrop" class="fixed inset-0 bg-gray-800 bg-opacity-50 hidden flex items-center justify-center z-50">
                                        <!-- Modal Content -->
                                         <div class="bg-white rounded-lg overflow-hidden shadow-xl max-w-lg w-full">
                                            <!-- header -->
                                             <div class="px-4 py-2 border-b">
                                                <h2 class="text-xl">Edit Post</h2>
                                             </div>
                                         </div>
                                    </div>
                                </td>
                                <td>
                                    <form action="" method="post">
                                        <input type="hidden" name="postId" value="<?php echo $post['postId']; ?>">
                                        <input type="submit" class="btn-red-500 inline-block" value="Delete">
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- footer -->
    <?php include_once("../components/footer.php"); ?>
    <script src="../js/script.js"></script>
</body>

</html>