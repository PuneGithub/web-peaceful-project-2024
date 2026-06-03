<?php
require_once '../system/administratorSystem.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
}


$msgPost = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['postId'])) {
    $postId = $_POST['postId'];
    $result = deletePost($conn, $postId);

    if ($result['status']) {
        $msgPost = "<div class='alert-green text-center'>" . htmlspecialchars($result['message']) . "</div>";
    } else {
        $msgPost = "<div class='alert-danger text-center'>" . htmlspecialchars($result['message']) . "</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once __DIR__ . '/../components/favicon.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../css/output.css">
    <link rel="stylesheet" href="../css/style.css">
    <title>Manage Posts</title>
</head>

<body>
    <div class="flex">
        <?php include_once('../components/header-admin.php'); ?>

        <!-- Content -->
        <div class="flex-1 p-6">
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-12 sm:col-span-4">
                    <div class="card-white">
                        <?php $totalPosts = countPosts($conn); ?>
                        <h2 class="font-bold text-lg">จำนวนโพสต์: <?php echo $totalPosts; ?> โพสต์</h2>
                    </div>
                </div>
                <div class="col-span-12 sm:col-span-8">
                    <div class="card-white">
                        <?php
                        if ($msgPost) {
                            echo $msgPost;
                        }
                        ?>
                        <div class="overflow-x-auto overflow-y-auto h-96">
                            <table class="table-auto w-full border border-slate-400 border-collapse text-center">
                                <thead>
                                    <tr>
                                        <th class="border border-slate-300">postId</th>
                                        <th class="border border-slate-300">userId</th>
                                        <th class="border border-slate-300">username</th>
                                        <th class="border border-slate-300">title</th>
                                        <th class="border border-slate-300">content</th>
                                        <th class="border border-slate-300">createAt</th>
                                        <th class="border border-slate-300">imagePost</th>
                                        <th class="border border-slate-300">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $fetchAllPosts = fetchAllPosts($conn);
                                    foreach ($fetchAllPosts as $post) {
                                    ?>
                                        <tr>
                                            <td class="border border-slate-300"><?php echo htmlspecialchars($post['postId']); ?></td>
                                            <td class="border border-slate-300"><?php echo htmlspecialchars($post['userId']); ?></td>
                                            <td class="border border-slate-300"><?php echo htmlspecialchars($post['username']); ?></td>
                                            <td class="border border-slate-300"><?php echo htmlspecialchars($post['title']); ?></td>
                                            <td class="border border-slate-300"><?php echo htmlspecialchars($post['content']); ?></td>
                                            <td class="border border-slate-300"><?php echo htmlspecialchars($post['createdAt']); ?></td>
                                            <td class="border border-slate-300"><img src="../img/posts_image/<?php echo htmlspecialchars($post['imagePost']); ?>" class="w-32 h-32 object-cover rounded-sm" alt="profile"></td>
                                            <td class="border border-slate-300">
                                                <form action="" method="post" onsubmit="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบผู้ใช้นี้?');">
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
            </div>
        </div>
    </div>
</body>

</html>