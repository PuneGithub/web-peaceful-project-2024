<?php
/**
 * Dynamic XML sitemap — ใช้ query ตรงจาก DB (ไม่พึ่ง fetchAllBlogs JOIN หนัก)
 * เพื่อลดจุดพังบน production และไม่ output ข้อความ error ปน XML
 */
require_once __DIR__ . '/system/conn.php';
require_once __DIR__ . '/system/config.php';

header('Content-Type: application/xml; charset=utf-8');

function sitemapLastmod(?string $updatedAt, ?string $createdAt = null): ?string
{
    $date = $updatedAt ?: $createdAt;
    if ($date === null || $date === '') {
        return null;
    }

    $ts = strtotime($date);
    if ($ts === false) {
        return null;
    }

    return date('Y-m-d', $ts);
}

function sitemapUrl(string $loc, ?string $lastmod = null): string
{
    $xml = "  <url>\n";
    $xml .= '    <loc>' . htmlspecialchars($loc, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
    if ($lastmod !== null) {
        $xml .= '    <lastmod>' . htmlspecialchars($lastmod, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</lastmod>\n";
    }
    $xml .= "  </url>\n";

    return $xml;
}

function sitemapFetchRows(PDO $conn, string $sql): array
{
    try {
        $stmt = $conn->query($sql);

        return $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    } catch (PDOException $e) {
        error_log('Sitemap query failed: ' . $e->getMessage() . ' | SQL: ' . $sql);

        return [];
    }
}

$urls = [];

$staticPages = ['', 'servers', 'blogs', 'resources', 'about', 'report', 'legal'];
foreach ($staticPages as $page) {
    $urls[] = sitemapUrl(absolute_url($page));
}

$blogRows = sitemapFetchRows(
    $conn,
    "SELECT slug, updatedAt, createdAt FROM blogs
     WHERE status = 'published' AND slug IS NOT NULL AND TRIM(slug) != ''"
);
if ($blogRows === []) {
    $blogRows = sitemapFetchRows(
        $conn,
        "SELECT slug, updatedAt, createdAt FROM blogs
         WHERE slug IS NOT NULL AND TRIM(slug) != ''"
    );
}
foreach ($blogRows as $blog) {
    $slug = trim($blog['slug'] ?? '');
    if ($slug === '') {
        continue;
    }
    $lastmod = sitemapLastmod($blog['updatedAt'] ?? null, $blog['createdAt'] ?? null);
    $urls[] = sitemapUrl(absolute_url('blog/' . $slug), $lastmod);
}

foreach (sitemapFetchRows(
    $conn,
    "SELECT serverSlug, updatedAt, createdAt FROM servers
     WHERE status = 'approved' AND serverSlug IS NOT NULL AND TRIM(serverSlug) != ''"
) as $server) {
    $slug = trim($server['serverSlug'] ?? '');
    if ($slug === '') {
        continue;
    }
    $lastmod = sitemapLastmod($server['updatedAt'] ?? null, $server['createdAt'] ?? null);
    $urls[] = sitemapUrl(absolute_url('server/' . $slug), $lastmod);
}

foreach (sitemapFetchRows(
    $conn,
    "SELECT resourceId, updatedAt, createdAt FROM resources WHERE status = 'approved'"
) as $resource) {
    $resourceId = (int) ($resource['resourceId'] ?? 0);
    if ($resourceId <= 0) {
        continue;
    }
    $lastmod = sitemapLastmod($resource['updatedAt'] ?? null, $resource['createdAt'] ?? null);
    $urls[] = sitemapUrl(absolute_url('resource/' . $resourceId), $lastmod);
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
echo implode('', $urls);
echo '</urlset>';
