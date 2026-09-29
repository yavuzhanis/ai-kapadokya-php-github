# AI Kapadokya — PHP Etkinlik Platformu

III. Ulusal Yapay Zekâ Buluşması için hazırlanmış, mobil uyumlu ön yüz ve güvenli PHP yönetim paneli. Composer, Node.js veya framework zorunluluğu yoktur; PHP 8.1+ ve MySQL/SQLite ile çalışır.

## Neler var?

- Modern, responsive ve erişilebilir etkinlik ana sayfası
- Tarihe göre filtrelenen iki günlük program
- Konuşmacı, moderatör ve keynote kartları
- Üniversite/lise poster yarışmaları ve Agentic AI Demo Day
- Haber merkezi, dinamik haber detayları
- Galeri ve tam ekran lightbox
- 2024, 2025 ve 2026 yıl arşivi
- Düzenleme kurulu, konaklama ve özel içerik sayfaları
- İletişim formu ve panelde mesaj kutusu
- Otomatik XML sitemap ve robots.txt
- Görsel yükleme ve harici görsel URL desteği
- CSRF, PDO prepared statements, güvenli oturum, giriş deneme sınırı, XSS temizliği ve yükleme türü kontrolü

## Yönetim paneli modülleri

`/admin` altında şu bölümler yer alır:

1. Etkinlik yılları
2. Konuşmacılar
3. Program / oturumlar
4. Dinamik sayfalar
5. Yarışmalar ve Demo Day
6. Haberler
7. Sponsorlar ve düzenleyiciler
8. Galeri
9. Video arşivi
10. İletişim mesajları
11. Site, iletişim ve sosyal medya ayarları

## Sunucu gereksinimleri

- PHP 8.1 veya üzeri
- `pdo_mysql` veya `pdo_sqlite`
- `mbstring` ve `fileinfo`
- Apache `mod_rewrite`
- MySQL 5.7+/MariaDB 10.3+ (MySQL kurulumu için)

## Kurulum

1. Paketi domainin belge köküne (`public_html`) çıkarın.
2. `.env.example` dosyasının bir kopyasını `.env` olarak adlandırın.
3. `.env` içindeki `APP_URL` ve veritabanı bilgilerini düzenleyin.
4. MySQL kullanıyorsanız DirectAdmin/cPanel üzerinden boş veritabanı ve kullanıcı oluşturun.
5. Tarayıcıdan `https://domaininiz.com/install.php` adresini açın.
6. İlk yönetici hesabını oluşturun. 2024–2026 örnek içerikleri otomatik yüklenir.
7. Kurulumdan sonra `install.php` dosyasını sunucudan silin.
8. `uploads` klasörünün yazılabilir olduğundan emin olun (`755`, gerekirse `775`).

### SQLite ile hızlı kurulum

`.env` içinde:

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

SQLite dosyası kurulum sırasında otomatik oluşturulur.

## Tasarım yaklaşımı

Mevcut sitedeki dağınık Elementor bileşenleri yerine tek tip boşluk, renk ve tipografi sistemi kullanıldı. Ana görsel ile metin ayrıldı; içerikler okunabilir kartlara, program ise gün sekmelerine dönüştürüldü. Renk paleti mevcut mor kimliği korurken asit yeşili vurgu rengiyle teknoloji hissi güçlendirildi.

## İlk içerikler hakkında

Kurulum verisi, `aikapadokya.com.tr` üzerindeki 2026 etkinlik yapısına göre hazırlanmıştır. Bazı başlangıç görselleri mevcut sitenin herkese açık medya adreslerini kullanır. Kalıcı yayında bunları yönetim panelinden sunucuya yüklemeniz önerilir; panel URL yerine yerel yüklemeyi otomatik olarak kullanır.

## Klasör yapısı

```text
admin/       Yönetim paneli
app/         Veritabanı, repository ve yardımcılar
assets/      Front-end ve panel CSS/JS
database/    MySQL/SQLite şemaları ve örnek veriler
templates/   Dinamik ön yüz şablonları
uploads/     Panelden yüklenen görseller
index.php    Front-end router
install.php  Tek seferlik kurulum
```
