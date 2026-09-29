<?php
declare(strict_types=1);

final class Repository
{
    public function __construct(private PDO $db)
    {
    }

    public function setting(string $key, string $default = ''): string
    {
        $stmt = $this->db->prepare('SELECT value FROM settings WHERE `key` = ? LIMIT 1');
        $stmt->execute([$key]);
        $value = $stmt->fetchColumn();
        return $value === false ? $default : (string) $value;
    }

    public function currentEvent(): array
    {
        return $this->db->query('SELECT * FROM events WHERE is_current = 1 AND published = 1 ORDER BY year DESC LIMIT 1')->fetch() ?: [];
    }

    public function events(): array
    {
        return $this->db->query('SELECT * FROM events WHERE published = 1 ORDER BY year DESC')->fetchAll();
    }

    public function eventByYear(int $year): array
    {
        $stmt = $this->db->prepare('SELECT * FROM events WHERE year = ? AND published = 1 LIMIT 1');
        $stmt->execute([$year]);
        return $stmt->fetch() ?: [];
    }

    public function speakers(?int $eventId = null, ?int $limit = null): array
    {
        $sql = 'SELECT * FROM speakers WHERE published = 1';
        $params = [];
        if ($eventId) {
            $sql .= ' AND event_id = ?';
            $params[] = $eventId;
        }
        $sql .= ' ORDER BY sort_order, name';
        if ($limit) $sql .= ' LIMIT ' . (int) $limit;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function sessions(int $eventId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM sessions WHERE event_id = ? AND published = 1 ORDER BY day_date, sort_order, start_time');
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }

    public function sponsors(int $eventId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM sponsors WHERE event_id = ? AND published = 1 ORDER BY CASE tier WHEN 'organizer' THEN 1 WHEN 'main' THEN 2 ELSE 3 END, sort_order");
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }

    public function gallery(int $eventId, ?int $limit = null): array
    {
        $sql = 'SELECT * FROM gallery WHERE event_id = ? AND published = 1 ORDER BY sort_order, id DESC';
        if ($limit) $sql .= ' LIMIT ' . (int) $limit;
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }

    public function videos(?int $eventId = null): array
    {
        $sql = 'SELECT videos.*, events.year FROM videos JOIN events ON events.id = videos.event_id WHERE videos.published = 1';
        $params = [];
        if ($eventId) {
            $sql .= ' AND videos.event_id = ?';
            $params[] = $eventId;
        }
        $sql .= ' ORDER BY events.year DESC, videos.sort_order, videos.id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function news(?int $limit = null): array
    {
        $sql = 'SELECT * FROM news WHERE published = 1 ORDER BY published_at DESC, id DESC';
        if ($limit) $sql .= ' LIMIT ' . (int) $limit;
        return $this->db->query($sql)->fetchAll();
    }

    public function newsBySlug(string $slug): array
    {
        $stmt = $this->db->prepare('SELECT * FROM news WHERE slug = ? AND published = 1 LIMIT 1');
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: [];
    }

    public function pages(bool $menuOnly = false): array
    {
        $sql = 'SELECT * FROM pages WHERE published = 1';
        if ($menuOnly) $sql .= ' AND show_in_menu = 1';
        $sql .= ' ORDER BY sort_order, title';
        return $this->db->query($sql)->fetchAll();
    }

    public function page(string $slug): array
    {
        $stmt = $this->db->prepare('SELECT * FROM pages WHERE slug = ? AND published = 1 LIMIT 1');
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: [];
    }

    public function competitions(?int $eventId = null): array
    {
        $sql = 'SELECT * FROM competitions WHERE published = 1';
        $params = [];
        if ($eventId) {
            $sql .= ' AND event_id = ?';
            $params[] = $eventId;
        }
        $sql .= ' ORDER BY sort_order, id';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function competition(string $slug): array
    {
        $stmt = $this->db->prepare('SELECT * FROM competitions WHERE slug = ? AND published = 1 LIMIT 1');
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: [];
    }
}
