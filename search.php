<?php
include 'includes/db.php';

$search = mysqli_real_escape_string($koneksi, $_GET['s']);

$query = "SELECT articles.*, users.username AS author_name 
          FROM articles 
          JOIN users ON articles.user_id = users.user_id 
          WHERE articles.title LIKE '%$search%' OR users.username LIKE '%$search%' 
          ORDER BY created_at DESC";

$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <article class="brick entry">
            <div class="entry__thumb">
                <a href="single-standard.php?id=<?= $row['id'] ?>" class="thumb-link" title="">
                    <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="">
                </a>
            </div>
            <div class="entry__text">
                <div class="entry__header">
                    <h1 class="entry__title">
                        <a href="single-standard.php?id=<?= $row['id'] ?>">
                            <?= htmlspecialchars($row['title']) ?>
                        </a>
                    </h1>
                    <div class="entry__meta">
                        <span class="entry__author"><?= htmlspecialchars($row['author_name']) ?></span>
                    </div>
                </div>
                <div class="entry__excerpt">
                    <p><?= htmlspecialchars(substr($row['content'], 0, 100)) ?>...</p>
                </div>
            </div>
        </article>
        <?php
    }
} else {
    echo "<p>Tidak ada artikel ditemukan.</p>";
}
?>
