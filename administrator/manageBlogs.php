<?php
require_once '../system/administratorSystem.php';
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        <div class="flex items-center">
                            <a href="addBlog.php" class="btn btn-blue-500">New Blog</a>
                            <h2 class="font-bold flex-1 text-center text-xl">Manage Blogs</h2>
                        </div>
                        <div class="overflow-x-auto mt-3">
                            <table class="table-auto w-full border border-slate-400 border-collapse text-center">
                                <thead>
                                    <tr>
                                        <th class="border border-slate-300">blogId</th>
                                        <th class="border border-slate-300">userId</th>
                                        <th class="border border-slate-300">blogTitle</th>
                                        <th class="border border-slate-300">blogContent</th>
                                        <th class="border border-slate-300">blogImage</th>
                                        <th class="border border-slate-300">createAt</th>
                                        <th class="border border-slate-300">edit</th>
                                        <th class="border border-slate-300">delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-slate-300"></td>
                                        <td class="border border-slate-300"></td>
                                        <td class="border border-slate-300"></td>
                                        <td class="border border-slate-300"></td>
                                        <td class="border border-slate-300"></td>
                                        <td class="border border-slate-300"></td>
                                        <td class="border border-slate-300">
                                            <a href="editBlog.php?blogId=" class="btn-orange-500 inline-block">Edit</a>
                                        </td>
                                        <td class="border border-slate-300">
                                            <form action="" method="post">
                                                <input type="hidden" name="userId" value="<?php ?>">
                                                <input type="submit" class="btn-red-500 inline-block" value="Delete">
                                            </form>
                                        </td>
                                    </tr>
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