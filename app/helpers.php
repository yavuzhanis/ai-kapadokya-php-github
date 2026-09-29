<?php
declare(strict_types=1);

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function url(string $path = ''): string
{
    $base = rtrim((string) env('APP_URL', ''), '/');
    if ($base === '') {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $base = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    }
    return $base . '/' . ltrim($path, '/');
}

function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

function redirect(string $path): never
{
    header('Location: ' . url($path));
    exit;
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $token = (string) ($_POST['_csrf'] ?? '');
    if ($token === '' || !hash_equals((string) ($_SESSION['_csrf'] ?? ''), $token)) {
        http_response_code(419);
        exit('Oturum doğrulanamadı. Sayfayı yenileyip tekrar deneyin.');
    }
}

function request_path(): string
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    if ($scriptDir && $scriptDir !== '/' && str_starts_with($path, $scriptDir)) {
        $path = substr($path, strlen($scriptDir)) ?: '/';
    }
    return '/' . trim($path, '/');
}

function clean_html(string $html): string
{
    $allowed = '<p><br><strong><b><em><i><u><h2><h3><h4><ul><ol><li><blockquote><a><table><thead><tbody><tr><th><td><span><div><figure><figcaption><img><hr>';
    $html = strip_tags($html, $allowed);
    $html = preg_replace('/\s+on\w+\s*=\s*(["\']).*?\1/iu', '', $html) ?? $html;
    $html = preg_replace('/javascript\s*:/iu', '', $html) ?? $html;
    return $html;
}

function excerpt(string $html, int $length = 150): string
{
    $text = trim(preg_replace('/\s+/', ' ', strip_tags($html)) ?? '');
    return mb_strlen($text) > $length ? mb_substr($text, 0, $length - 1) . '…' : $text;
}

function flash(string $key, ?string $value = null): ?string
{
    if ($value !== null) {
        $_SESSION['_flash'][$key] = $value;
        return null;
    }
    $message = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $message;
}

function render(string $template, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require BASE_PATH . '/templates/header.php';
    require BASE_PATH . '/templates/' . $template . '.php';
    require BASE_PATH . '/templates/footer.php';
}

function format_date_tr(string $date, bool $withYear = true): string
{
    $months = [1 => 'Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
    $timestamp = strtotime($date);
    if (!$timestamp) return $date;
    return date('j', $timestamp) . ' ' . $months[(int) date('n', $timestamp)] . ($withYear ? ' ' . date('Y', $timestamp) : '');
}

