<?php
declare(strict_types=1);
require __DIR__ . '/app/bootstrap.php';

$error = null;
$success = false;
$db = null;

try {
    $db = Database::connection();
    $schemaFile = env('DB_CONNECTION', 'mysql') === 'sqlite'
        ? BASE_PATH . '/database/schema.sqlite.sql'
        : BASE_PATH . '/database/schema.mysql.sql';
    $schema = (string) file_get_contents($schemaFile);
    if (env('DB_CONNECTION', 'mysql') === 'sqlite') {
        $db->exec($schema);
    } else {
        foreach (preg_split('/;\s*(?:\r?\n|$)/', $schema) ?: [] as $statement) {
            if (trim($statement) !== '') $db->exec($statement);
        }
    }
    $alreadyInstalled = (int) $db->query('SELECT COUNT(*) FROM admins')->fetchColumn() > 0;
} catch (Throwable $e) {
    $alreadyInstalled = false;
    $error = 'Veritabanı bağlantısı kurulamadı: ' . $e->getMessage();
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && !$error && !$alreadyInstalled && $db) {
    verify_csrf();
    $name = trim((string) ($_POST['name'] ?? ''));
    $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
    $password = (string) ($_POST['password'] ?? '');

    if ($name === '' || !$email || strlen($password) < 10) {
        $error = 'Ad, geçerli e-posta ve en az 10 karakterli parola zorunludur.';
    } else {
        try {
            $db->beginTransaction();
            $admin = $db->prepare('INSERT INTO admins (name, email, password) VALUES (?, ?, ?)');
            $admin->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);

            $seed = require BASE_PATH . '/database/seed.php';
            $stmt = $db->prepare('INSERT INTO settings (`key`, value) VALUES (?, ?)');
            foreach ($seed['settings'] as $key => $value) $stmt->execute([$key, $value]);

            $eventStmt = $db->prepare('INSERT INTO events (year,title,subtitle,start_date,end_date,location,description,hero_image,registration_url,is_current,published) VALUES (?,?,?,?,?,?,?,?,?,?,1)');
            $eventIds = [];
            foreach ($seed['events'] as $event) {
                $eventStmt->execute($event);
                $eventIds[(int) $event[0]] = (int) $db->lastInsertId();
            }
            $currentId = $eventIds[2026];

            $speakerStmt = $db->prepare('INSERT INTO speakers (event_id,name,role,company,image_url,linkedin_url,sort_order,published) VALUES (?,?,?,?,?,?,?,1)');
            foreach ($seed['speakers'] as $i => $speaker) $speakerStmt->execute([$currentId, ...$speaker, $i + 1]);

            $sessionStmt = $db->prepare('INSERT INTO sessions (event_id,day_date,start_time,end_time,type,title,moderator,participants,venue,sort_order,published) VALUES (?,?,?,?,?,?,?,?,?,?,1)');
            foreach ($seed['sessions'] as $i => $session) $sessionStmt->execute([$currentId, ...$session, 'Mahzen', $i + 1]);
            foreach ($seed['archive_sessions'] as $i => $session) {
                $year = array_shift($session);
                $sessionStmt->execute([$eventIds[$year], ...$session, 'Mahzen', $i + 1]);
            }

            $competitionStmt = $db->prepare('INSERT INTO competitions (event_id,title,slug,audience,summary,content,image_url,application_url,document_url,deadline,sort_order,published) VALUES (?,?,?,?,?,?,?,?,?,?,?,1)');
            foreach ($seed['competitions'] as $i => $item) $competitionStmt->execute([$currentId, ...$item, $i + 1]);

            $galleryStmt = $db->prepare('INSERT INTO gallery (event_id,title,category,image_url,sort_order,published) VALUES (?,?,?,?,?,1)');
            foreach ($seed['gallery'] as $i => $item) $galleryStmt->execute([$currentId, ...$item, $i + 1]);

            $videoStmt = $db->prepare('INSERT INTO videos (event_id,title,youtube_url,sort_order,published) VALUES (?,?,?,?,1)');
            foreach ($seed['videos'] as $i => $item) {
                [$year, $title, $youtube] = $item;
                $videoStmt->execute([$eventIds[$year], $title, $youtube, $i + 1]);
            }

            $sponsorStmt = $db->prepare('INSERT INTO sponsors (event_id,name,tier,logo_url,website,sort_order,published) VALUES (?,?,?,?,?,?,1)');
            foreach ($seed['sponsors'] as $i => $item) $sponsorStmt->execute([$currentId, ...$item, $i + 1]);

            $pageStmt = $db->prepare('INSERT INTO pages (title,slug,eyebrow,summary,content,template,menu_group,show_in_menu,sort_order,published) VALUES (?,?,?,?,?,?,?,?,?,1)');
            foreach ($seed['pages'] as $i => $item) $pageStmt->execute([...$item, $i + 1]);

            $newsStmt = $db->prepare('INSERT INTO news (title,slug,summary,content,image_url,source_url,published_at,published) VALUES (?,?,?,?,?,?,?,1)');
            foreach ($seed['news'] as $item) $newsStmt->execute($item);

            $db->commit();
            $success = true;
            $alreadyInstalled = true;
        } catch (Throwable $e) {
            if ($db->inTransaction()) $db->rollBack();
            $error = 'Kurulum tamamlanamadı: ' . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>AI Kapadokya · Kurulum</title>
    <style>
        :root{color-scheme:dark}*{box-sizing:border-box}body{margin:0;min-height:100vh;display:grid;place-items:center;padding:24px;background:radial-gradient(circle at 10% 10%,#702b90 0,transparent 34%),#100b18;color:#f8f4ff;font:16px/1.5 system-ui,sans-serif}.card{width:min(540px,100%);padding:38px;border:1px solid #ffffff20;border-radius:24px;background:#191222e8;box-shadow:0 24px 70px #0008}h1{margin:0 0 8px;font-size:30px}p{color:#cfc3d9}.brand{color:#d8ff62;font-size:13px;font-weight:800;letter-spacing:.16em;text-transform:uppercase}label{display:block;margin:17px 0 6px;font-weight:700}input{width:100%;padding:13px 14px;border:1px solid #ffffff26;border-radius:11px;background:#0e0915;color:#fff;font:inherit}button,.button{display:inline-block;margin-top:22px;padding:14px 22px;border:0;border-radius:999px;background:#d8ff62;color:#191024;font-weight:900;text-decoration:none;cursor:pointer}.alert{padding:12px 14px;border-radius:10px;background:#ff6b8a22;border:1px solid #ff6b8a66;color:#ffd4de}.ok{background:#d8ff6215;border-color:#d8ff6255;color:#e8ffae}code{color:#d8ff62}
    </style>
</head>
<body><main class="card">
    <div class="brand">AI Kapadokya CMS</div>
    <h1><?= $success ? 'Kurulum tamamlandı' : 'Sistemi kur' ?></h1>
    <?php if ($error): ?><div class="alert"><?= e($error) ?></div><?php endif; ?>
    <?php if ($success): ?>
        <div class="alert ok">Veritabanı, örnek içerikler ve yönetici hesabı oluşturuldu.</div>
        <p>Güvenlik için <code>install.php</code> dosyasını sunucudan silin ve yönetim paneline geçin.</p>
        <a class="button" href="<?= e(url('admin')) ?>">Yönetim panelini aç</a>
    <?php elseif ($alreadyInstalled): ?>
        <div class="alert ok">Sistem daha önce kurulmuş. Yeniden kurulum engellendi.</div>
        <a class="button" href="<?= e(url('admin')) ?>">Yönetim panelini aç</a>
    <?php elseif (!$error || $db): ?>
        <p><code>.env</code> bağlantısı hazır. İlk yönetici hesabınızı oluşturun; örnek 2024–2026 içerikleri otomatik yüklenecek.</p>
        <form method="post">
            <?= csrf_field() ?>
            <label for="name">Ad Soyad</label><input id="name" name="name" required autocomplete="name">
            <label for="email">E-posta</label><input id="email" name="email" type="email" required autocomplete="email">
            <label for="password">Parola</label><input id="password" name="password" type="password" minlength="10" required autocomplete="new-password">
            <button type="submit">Kurulumu tamamla</button>
        </form>
    <?php else: ?>
        <p><code>.env.example</code> dosyasını <code>.env</code> adıyla kopyalayın ve veritabanı bilgilerini düzenleyin.</p>
    <?php endif; ?>
</main></body></html>
