                <!-- Post Feed -->
                <div class="space-y-6">
                    <div class="max-w-4xl mx-auto p-4 space-y-4">
                        <?php

                        $fetchPosts = fetchAllPosts($conn, $sortBy);
                        if (!empty($fetchPosts)) {
                            foreach ($fetchPosts as $post) {
                        ?>
                                <div class="card-white">
                                    <div class="flex items-center space-x-4">
                                        <?php if (!empty($post['profileImage']) && file_exists("img/profile_users/" . $post['profileImage'])): ?>
                                            <img src="img/profile_users/<?php echo $post['profileImage']; ?>" alt="Profile" class="w-10 h-10 rounded-full">
                                        <?php else: ?>
                                            <img src="img/profile_users/profile_default/default.webp" alt="Profile Default" class="w-10 h-10 rounded-full">
                                        <?php endif; ?>
                                        <div>
                                            <h2 class="font-semibold"><?php echo $post['username']; ?></h2>
                                            <span class="text-sm text-gray-500"><?php echo $post['createdAt']; ?></span>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <p class="text-gray-800"><?php echo $post['title']; ?></p>
                                        <?php if (!empty($post['imagePost']) && file_exists("img/posts_image/" . $post['imagePost'])): ?>
                                            <img src="img/posts_image/<?php echo $post['imagePost']; ?>" alt="Post image" class="mt-2 rounded-lg w-full">
                                        <?php endif; ?>
                                        <p class="text-gray-800"><?php echo $post['content']; ?></p>
                                    </div>

                                    <!-- Post Actions -->
                                    <div class="mt-4 flex items-center justify-between">
                                        <span id="loveCount" class="text-gray-500">Loves: <?php echo $post['loveCount']; ?></span>
                                        <?php
                                        if (isset($_SESSION['username'])):
                                        ?>
                                            <button class="love-btn" check-btnLove="btnLove" data-postid="<?php echo $post['postId']; ?>">
                                                <span class="heart-icon fa-lg"><?php echo userHasLoved($conn, $post['postId'], $_SESSION['username']) ? "<i class='text-red-400 fa-solid fa-heart'></i>" : "<i class='text-red-300 fa-solid fa-heart'></i>"; ?></span>
                                            </button>
                                            <button id="toggleComment_<?php echo $post['postId']; ?>" class="btn-blue-500">
                                                comment
                                            </button>
                                        <?php else: ?>
                                            <button disabled>
                                                <i class='text-red-300 fa-solid fa-heart'></i>
                                            </button>
                                            <button disabled class="disabled:opacity-75 cursor-not-allowed btn-blue-500">
                                                comment
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                    <!-- Form Comments -->
                                    <form action="" id="commentForm_<?php echo $post['postId']; ?>" data-postid="<?php echo $post['postId']; ?>" class="commentForm hidden bg-white shadow-md rounded m-4 p-4" enctype="multipart/form-data" method="post">
                                        <div class="mb-4">
                                            <label for="comment" class="block text-gray-700 text-sm font-bold mb-2">comment</label>
                                            <input type="text" name="text" class="input-form" placeholder="Enter Comment" required>
                                            <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="text-center">
                                            <button type="submit" name="btnComment">
                                                <i class="fa-solid fa-comment fa-lg text-sky-300"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <div class="card-white overflow-y-scroll max-h-64 hidden" id="commentsBoxs_<?php echo $post['postId']; ?>">
                                        <h2 class="text-center font-semibold">Comments</h2>
                                        <?php
                                        $getComment = getCommentByPostId($conn, $post['postId']);
                                        foreach ($getComment as $comment):
                                        ?>
                                            <div class="mb-2">
                                                <p class="text-sm font-semibold"><?php echo $comment['username']; ?> <span class="text-xs text-gray-400"><?php echo $comment['commentDate']; ?></span></p>
                                                <p class="text-gray-700"><?php echo $comment['text']; ?></p>
                                            </div>
                                            <hr>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>