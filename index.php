<?php
declare(strict_types=1);
require __DIR__ . '/app/bootstrap.php';

try {
    $db = Database::connection();
    $repo = new Repository($db);
    $currentEvent = $repo->currentEvent();
    if (!$currentEvent) throw new RuntimeException('Kurulum henüz tamamlanmamış.');
} catch (Throwable $e) {
    http_response_code(503);
    $message = env('APP_DEBUG', false) ? $e->getMessage() : 'Sistem kurulumu tamamlanmadı.';
    exit('<!doctype html><html lang="tr"><meta charset="utf-8"><meta name="viewport" content="width=device-width"><title>Kurulum gerekli</title><body style="margin:0;display:grid;place-items:center;min-height:100vh;background:#120c1b;color:#fff;font:16px system-ui"><main style="max-width:620px;padding:40px"><h1>AI Kapadokya</h1><p>' . e($message) . '</p><a style="color:#d8ff62" href="install.php">Kurulum ekranını aç →</a></main></body></html>');
}

$path = request_path();
$base = [
    'repo' => $repo,
    'currentEvent' => $currentEvent,
    'menuPages' => $repo->pages(true),
    'currentPath' => $path,
];

if ($path === '/robots.txt') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /install.php\nSitemap: " . url('sitemap.xml');
    exit;
}

if ($path === '/sitemap.xml') {
    header('Content-Type: application/xml; charset=utf-8');
    $locations = [url(), url('program'), url('konusmacilar'), url('yarismalar'), url('haberler'), url('videolar'), url('gecmis'), url('iletisim')];
    foreach ($repo->pages() as $row) $locations[] = url($row['slug']);
    foreach ($repo->competitions() as $row) $locations[] = url('yarismalar/' . $row['slug']);
    foreach ($repo->news() as $row) $locations[] = url('haberler/' . $row['slug']);
    foreach ($repo->events() as $row) $locations[] = url('gecmis/' . $row['year']);
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
    foreach (array_unique($locations) as $location) echo '  <url><loc>' . e($location) . "</loc></url>\n";
    echo '</urlset>';
    exit;
}

if ($path === '/') {
    render('home', $base + [
        'pageTitle' => $currentEvent['title'],
        'speakers' => $repo->speakers((int) $currentEvent['id'], 8),
        'sessions' => $repo->sessions((int) $currentEvent['id']),
        'competitions' => $repo->competitions((int) $currentEvent['id']),
        'gallery' => $repo->gallery((int) $currentEvent['id'], 5),
        'newsItems' => $repo->news(3),
        'sponsors' => $repo->sponsors((int) $currentEvent['id']),
        'events' => $repo->events(),
    ]);
    exit;
}

if ($path === '/program' || $path === '/ana-oturumlar') {
    $sessions = $repo->sessions((int) $currentEvent['id']);
    render('program', $base + ['pageTitle' => 'Program', 'sessions' => $sessions]);
    exit;
}

if ($path === '/konusmacilar') {
    render('speakers', $base + ['pageTitle' => 'Konuşmacılar', 'speakers' => $repo->speakers((int) $currentEvent['id'])]);
    exit;
}

if ($path === '/haberler') {
    render('news', $base + ['pageTitle' => 'Haberler', 'newsItems' => $repo->news()]);
    exit;
}

if ($path === '/videolar') {
    render('videos', $base + ['pageTitle' => 'Videolar', 'videos' => $repo->videos()]);
    exit;
}

if (preg_match('#^/haberler/([a-z0-9-]+)$#', $path, $match)) {
    $item = $repo->newsBySlug($match[1]);
    if (!$item) { http_response_code(404); render('404', $base + ['pageTitle' => 'Sayfa bulunamadı']); exit; }
    render('news-detail', $base + ['pageTitle' => $item['title'], 'item' => $item]);
    exit;
}

if ($path === '/yarismalar') {
    render('competitions', $base + ['pageTitle' => 'Yarışmalar', 'competitions' => $repo->competitions((int) $currentEvent['id'])]);
    exit;
}

if (preg_match('#^/yarismalar/([a-z0-9-]+)$#', $path, $match)) {
    $item = $repo->competition($match[1]);
    if (!$item) { http_response_code(404); render('404', $base + ['pageTitle' => 'Sayfa bulunamadı']); exit; }
    render('competition-detail', $base + ['pageTitle' => $item['title'], 'item' => $item]);
    exit;
}

if ($path === '/gecmis') {
    render('archive', $base + ['pageTitle' => 'Geçmiş Buluşmalar', 'events' => $repo->events()]);
    exit;
}

if (preg_match('#^/gecmis/(20\d{2})$#', $path, $match)) {
    $event = $repo->eventByYear((int) $match[1]);
    if (!$event) { http_response_code(404); render('404', $base + ['pageTitle' => 'Sayfa bulunamadı']); exit; }
    render('archive-detail', $base + [
        'pageTitle' => $event['title'],
        'event' => $event,
        'speakers' => $repo->speakers((int) $event['id']),
        'sessions' => $repo->sessions((int) $event['id']),
        'gallery' => $repo->gallery((int) $event['id']),
        'videos' => $repo->videos((int) $event['id']),
    ]);
    exit;
}

if ($path === '/iletisim') {
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
        verify_csrf();
        if (!empty($_POST['website'])) redirect('iletisim');
        $lastContact = (int) ($_SESSION['last_contact_at'] ?? 0);
        if (time() - $lastContact < 30) {
            flash('error', 'Yeni mesaj göndermeden önce lütfen kısa bir süre bekleyin.');
            redirect('iletisim');
        }
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $phone = trim((string) ($_POST['phone'] ?? ''));
        $subject = trim((string) ($_POST['subject'] ?? ''));
        $message = trim((string) ($_POST['message'] ?? ''));
        if ($name === '' || !$email || mb_strlen($message) < 10) {
            flash('error', 'Ad, geçerli e-posta ve en az 10 karakterlik mesaj zorunludur.');
        } else {
            $stmt = $db->prepare('INSERT INTO contacts (name,email,phone,subject,message,ip_hash) VALUES (?,?,?,?,?,?)');
            $stmt->execute([$name, $email, $phone, $subject, $message, hash('sha256', ($_SERVER['REMOTE_ADDR'] ?? '') . csrf_token())]);
            $_SESSION['last_contact_at'] = time();
            flash('success', 'Mesajınız alındı. En kısa sürede sizinle iletişime geçeceğiz.');
        }
        redirect('iletisim');
    }
    render('contact', $base + ['pageTitle' => 'İletişim']);
    exit;
}

$slug = trim($path, '/');
$page = $repo->page($slug);
if ($page) {
    render('page', $base + ['pageTitle' => $page['title'], 'page' => $page]);
    exit;
}

http_response_code(404);
render('404', $base + ['pageTitle' => 'Sayfa bulunamadı']);
