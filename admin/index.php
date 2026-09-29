<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';

try {
    $db = Database::connection();
} catch (Throwable $e) {
    http_response_code(503);
    exit('Veritabanına bağlanılamadı. Önce /install.php adresinden kurulumu tamamlayın.');
}

$adminBase = url('admin');
$rawPath = trim((string) ($_GET['path'] ?? ''), '/');
if ($rawPath === '') {
    $uri = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '', '/');
    $position = strpos($uri, 'admin');
    $rawPath = $position === false ? '' : trim(substr($uri, $position + 5), '/');
}
$parts = $rawPath === '' ? [] : explode('/', $rawPath);

function admin_user(): ?array { return $_SESSION['admin'] ?? null; }
function require_admin(): void { if (!admin_user()) redirect('admin/login'); }
function admin_slug(string $value): string {
    $map=['ş'=>'s','Ş'=>'s','ı'=>'i','İ'=>'i','ç'=>'c','Ç'=>'c','ü'=>'u','Ü'=>'u','ö'=>'o','Ö'=>'o','ğ'=>'g','Ğ'=>'g'];
    $value=strtr(mb_strtolower(trim($value)), $map); $value=preg_replace('/[^a-z0-9]+/','-',$value)??''; return trim($value,'-');
}
function handle_upload(string $field): ?string {
    $key=$field.'_upload'; if (empty($_FILES[$key]['tmp_name']) || $_FILES[$key]['error']!==UPLOAD_ERR_OK) return null;
    if ($_FILES[$key]['size'] > 8*1024*1024) throw new RuntimeException('Görsel 8 MB sınırını aşıyor.');
    $mime=(new finfo(FILEINFO_MIME_TYPE))->file($_FILES[$key]['tmp_name']);
    $types=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp','image/gif'=>'gif'];
    if (!isset($types[$mime])) throw new RuntimeException('Yalnızca JPG, PNG, WebP veya GIF yükleyebilirsiniz.');
    $relative='uploads/'.date('Y/m'); $directory=BASE_PATH.'/'.$relative;
    if (!is_dir($directory) && !mkdir($directory,0775,true)) throw new RuntimeException('Yükleme klasörü oluşturulamadı.');
    $name=bin2hex(random_bytes(12)).'.'.$types[$mime];
    if (!move_uploaded_file($_FILES[$key]['tmp_name'],$directory.'/'.$name)) throw new RuntimeException('Dosya kaydedilemedi.');
    return url($relative.'/'.$name);
}

$modules = [
 'events'=>['title'=>'Etkinlik Yılları','table'=>'events','list'=>['year'=>'Yıl','title'=>'Başlık','start_date'=>'Başlangıç','is_current'=>'Güncel','published'=>'Yayında'],'fields'=>['year'=>['Yıl','number'],'title'=>['Başlık','text'],'subtitle'=>['Alt Başlık','text'],'start_date'=>['Başlangıç','date'],'end_date'=>['Bitiş','date'],'location'=>['Yer','text'],'description'=>['Açıklama','textarea'],'hero_image'=>['Hero Görseli','image'],'registration_url'=>['Kayıt Linki','url'],'is_current'=>['Güncel Etkinlik','boolean'],'published'=>['Yayında','boolean']]],
 'speakers'=>['title'=>'Konuşmacılar','table'=>'speakers','list'=>['name'=>'Ad Soyad','role'=>'Rol','company'=>'Kurum','published'=>'Yayında'],'fields'=>['event_id'=>['Etkinlik','event'],'name'=>['Ad Soyad','text'],'role'=>['Rol','select',['Moderatör','Konuşmacı','Keynote']],'company'=>['Kurum','text'],'bio'=>['Kısa Biyografi','textarea'],'image_url'=>['Fotoğraf','image'],'linkedin_url'=>['LinkedIn','url'],'sort_order'=>['Sıra','number'],'published'=>['Yayında','boolean']]],
 'sessions'=>['title'=>'Program / Oturumlar','table'=>'sessions','list'=>['day_date'=>'Tarih','start_time'=>'Saat','title'=>'Oturum','type'=>'Tür','published'=>'Yayında'],'fields'=>['event_id'=>['Etkinlik','event'],'day_date'=>['Tarih','date'],'start_time'=>['Başlangıç','time'],'end_time'=>['Bitiş','time'],'type'=>['Tür','select',['panel','keynote','opening','demoday','award','break','closing']],'title'=>['Başlık','text'],'venue'=>['Salon','text'],'moderator'=>['Moderatör','text'],'participants'=>['Konuşmacılar (· ile ayırın)','textarea'],'description'=>['Açıklama','textarea'],'sort_order'=>['Sıra','number'],'published'=>['Yayında','boolean']]],
 'pages'=>['title'=>'Sayfalar','table'=>'pages','list'=>['title'=>'Başlık','slug'=>'Adres','menu_group'=>'Menü','published'=>'Yayında'],'fields'=>['title'=>['Başlık','text'],'slug'=>['URL Kısaltması','slug'],'eyebrow'=>['Üst Etiket','text'],'summary'=>['Özet','textarea'],'content'=>['İçerik (HTML)','html'],'hero_image'=>['Kapak Görseli','image'],'template'=>['Şablon','select',['page','committee']],'menu_group'=>['Menü Grubu','select',['Hakkında','Paneller','Geçmiş']],'show_in_menu'=>['Menüde Göster','boolean'],'sort_order'=>['Sıra','number'],'published'=>['Yayında','boolean']]],
 'competitions'=>['title'=>'Yarışmalar ve Demo Day','table'=>'competitions','list'=>['title'=>'Başlık','audience'=>'Hedef Kitle','deadline'=>'Son Başvuru','published'=>'Yayında'],'fields'=>['event_id'=>['Etkinlik','event'],'title'=>['Başlık','text'],'slug'=>['URL Kısaltması','slug'],'audience'=>['Hedef Kitle','text'],'summary'=>['Özet','textarea'],'content'=>['İçerik (HTML)','html'],'image_url'=>['Görsel','image'],'application_url'=>['Başvuru Linki','url'],'document_url'=>['Belge/Şablon Linki','url'],'deadline'=>['Son Başvuru','date'],'sort_order'=>['Sıra','number'],'published'=>['Yayında','boolean']]],
 'news'=>['title'=>'Haberler','table'=>'news','list'=>['title'=>'Başlık','published_at'=>'Tarih','published'=>'Yayında'],'fields'=>['title'=>['Başlık','text'],'slug'=>['URL Kısaltması','slug'],'summary'=>['Özet','textarea'],'content'=>['İçerik (HTML)','html'],'image_url'=>['Görsel','image'],'source_url'=>['Kaynak Linki','url'],'published_at'=>['Yayın Tarihi','datetime-local'],'published'=>['Yayında','boolean']]],
 'sponsors'=>['title'=>'Sponsorlar','table'=>'sponsors','list'=>['name'=>'Kurum','tier'=>'Kategori','sort_order'=>'Sıra','published'=>'Yayında'],'fields'=>['event_id'=>['Etkinlik','event'],'name'=>['Kurum Adı','text'],'tier'=>['Kategori','select',['organizer','main','support']],'logo_url'=>['Logo','image'],'website'=>['Web Sitesi','url'],'sort_order'=>['Sıra','number'],'published'=>['Yayında','boolean']]],
 'gallery'=>['title'=>'Galeri','table'=>'gallery','list'=>['title'=>'Başlık','category'=>'Kategori','sort_order'=>'Sıra','published'=>'Yayında'],'fields'=>['event_id'=>['Etkinlik','event'],'title'=>['Başlık','text'],'category'=>['Kategori','text'],'image_url'=>['Görsel','image'],'sort_order'=>['Sıra','number'],'published'=>['Yayında','boolean']]],
 'videos'=>['title'=>'Videolar','table'=>'videos','list'=>['title'=>'Başlık','youtube_url'=>'YouTube Embed','sort_order'=>'Sıra','published'=>'Yayında'],'fields'=>['event_id'=>['Etkinlik','event'],'title'=>['Başlık','text'],'youtube_url'=>['YouTube Embed Linki','url'],'sort_order'=>['Sıra','number'],'published'=>['Yayında','boolean']]],
 'contacts'=>['title'=>'İletişim Mesajları','table'=>'contacts','readonly'=>true,'list'=>['name'=>'Gönderen','email'=>'E-posta','subject'=>'Konu','status'=>'Durum','created_at'=>'Tarih'],'fields'=>['name'=>['Ad Soyad','text'],'email'=>['E-posta','text'],'phone'=>['Telefon','text'],'subject'=>['Konu','text'],'message'=>['Mesaj','textarea'],'status'=>['Durum','select',['new','read','replied','archived']],'created_at'=>['Tarih','text']]],
];

if (($parts[0] ?? '') === 'login') {
    if (admin_user()) redirect('admin');
    $error=null;
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
        verify_csrf();
        $waitUntil=(int)($_SESSION['login_wait_until']??0);
        if ($waitUntil>time()) $error='Çok fazla deneme yapıldı. Lütfen '.($waitUntil-time()).' saniye bekleyin.';
        else {
            $stmt=$db->prepare('SELECT * FROM admins WHERE email=? LIMIT 1'); $stmt->execute([trim((string)($_POST['email']??''))]); $user=$stmt->fetch();
            if ($user && password_verify((string)($_POST['password']??''),$user['password'])) {
                session_regenerate_id(true); $_SESSION['admin']=['id'=>$user['id'],'name'=>$user['name'],'email'=>$user['email']]; unset($_SESSION['login_attempts'],$_SESSION['login_wait_until']);
                $db->prepare('UPDATE admins SET last_login_at=CURRENT_TIMESTAMP WHERE id=?')->execute([$user['id']]); redirect('admin');
            }
            $_SESSION['login_attempts']=(int)($_SESSION['login_attempts']??0)+1;
            if ($_SESSION['login_attempts']>=5) { $_SESSION['login_wait_until']=time()+60; $_SESSION['login_attempts']=0; }
            $error='E-posta veya parola hatalı.';
        }
    }
    ?><!doctype html><html lang="tr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Yönetim Girişi · AI Kapadokya</title><link rel="stylesheet" href="<?=e(asset('admin.css'))?>"></head><body class="login-body"><main class="login-card"><a class="admin-brand" href="<?=e(url())?>"><b>AI</b><span>AI KAPADOKYA<small>İÇERİK YÖNETİM SİSTEMİ</small></span></a><h1>Tekrar hoş geldiniz.</h1><p>İçerikleri ve etkinlik programını yönetmek için giriş yapın.</p><?php if($error):?><div class="alert error"><?=e($error)?></div><?php endif;?><form method="post"><?=csrf_field()?><label>E-posta<input type="email" name="email" required autofocus autocomplete="username"></label><label>Parola<input type="password" name="password" required autocomplete="current-password"></label><button type="submit">Giriş yap →</button></form><a class="back-link" href="<?=e(url())?>">← Siteye dön</a></main></body></html><?php exit;
}

if (($parts[0] ?? '') === 'logout') { require_admin(); if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') verify_csrf(); $_SESSION=[]; session_destroy(); redirect('admin/login'); }
require_admin();

$module=$parts[0]??''; $action=$parts[1]??''; $id=isset($parts[2])?(int)$parts[2]:0;
if ($module && !isset($modules[$module]) && $module !== 'settings') { http_response_code(404); $module=''; }

if ($module && $action==='delete' && $id && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    verify_csrf(); $cfg=$modules[$module]; $db->prepare("DELETE FROM {$cfg['table']} WHERE id=?")->execute([$id]); flash('admin_success','Kayıt silindi.'); redirect('admin/'.$module);
}

if ($module && in_array($action,['create','edit'],true)) {
    $cfg=$modules[$module]; $record=[];
    if ($action==='edit' && $id) { $stmt=$db->prepare("SELECT * FROM {$cfg['table']} WHERE id=?"); $stmt->execute([$id]); $record=$stmt->fetch()?:[]; if(!$record){http_response_code(404);exit('Kayıt bulunamadı.');} }
    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
        verify_csrf(); $values=[]; $columns=[];
        try {
            foreach ($cfg['fields'] as $field=>$meta) {
                if ($field==='created_at') continue;
                $type=$meta[1]; $value=$_POST[$field]??'';
                if ($type==='boolean') $value=isset($_POST[$field])?1:0;
                elseif ($type==='number' || $type==='event') $value=(int)$value;
                elseif ($type==='html') $value=clean_html((string)$value);
                elseif ($type==='slug') $value=admin_slug((string)($value?:($_POST['title']??'')));
                elseif ($type==='datetime-local' && $value) $value=str_replace('T',' ',(string)$value).':00';
                else $value=trim((string)$value);
                if ($type==='image') { $uploaded=handle_upload($field); if($uploaded) $value=$uploaded; }
                $columns[]=$field; $values[]=$value===''?null:$value;
            }
            if ($module==='events' && !empty($_POST['is_current'])) $db->exec('UPDATE events SET is_current=0');
            if ($action==='edit') { $set=implode(',',array_map(fn($c)=>"$c=?",$columns)); $values[]=$id; $db->prepare("UPDATE {$cfg['table']} SET $set WHERE id=?")->execute($values); }
            else { $marks=implode(',',array_fill(0,count($columns),'?')); $db->prepare("INSERT INTO {$cfg['table']} (".implode(',',$columns).") VALUES ($marks)")->execute($values); }
            flash('admin_success','Kayıt başarıyla kaydedildi.'); redirect('admin/'.$module);
        } catch(Throwable $e){ $formError='Kayıt kaydedilemedi: '.$e->getMessage(); $record=array_merge($record,$_POST); }
    }
}

if ($module==='settings' && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    verify_csrf();
    foreach ($_POST['settings']??[] as $key=>$value) {
        if(!preg_match('/^[a-z0-9_]+$/',$key))continue;
        $stmt=$db->prepare('UPDATE settings SET value=? WHERE `key`=?'); $stmt->execute([trim((string)$value),$key]);
        if($stmt->rowCount()===0){try{$db->prepare('INSERT INTO settings (`key`,value) VALUES (?,?)')->execute([$key,trim((string)$value)]);}catch(Throwable){} }
    }
    flash('admin_success','Ayarlar güncellendi.'); redirect('admin/settings');
}

$admin=$modules[$module]??null;
?><!doctype html><html lang="tr"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($admin['title']??'Panel')?> · AI Kapadokya</title><link rel="stylesheet" href="<?=e(asset('admin.css'))?>"></head><body class="admin-body">
<aside class="sidebar"><a class="admin-brand" href="<?=e($adminBase)?>"><b>AI</b><span>AI KAPADOKYA<small>YÖNETİM PANELİ</small></span></a><nav><a class="<?=$module===''?'active':''?>" href="<?=e($adminBase)?>"><i>⌂</i>Genel Bakış</a><?php foreach($modules as $key=>$item):?><a class="<?=$module===$key?'active':''?>" href="<?=e(url('admin/'.$key))?>"><i><?=['events'=>'◫','speakers'=>'◎','sessions'=>'≡','pages'=>'□','competitions'=>'◇','news'=>'◧','sponsors'=>'◉','gallery'=>'▦','videos'=>'▶','contacts'=>'✉'][$key]?></i><?=e($item['title'])?></a><?php endforeach;?><a class="<?=$module==='settings'?'active':''?>" href="<?=e(url('admin/settings'))?>"><i>⚙</i>Site Ayarları</a></nav><div class="sidebar-foot"><a target="_blank" href="<?=e(url())?>">Siteyi görüntüle ↗</a><form method="post" action="<?=e(url('admin/logout'))?>"><?=csrf_field()?><button>Çıkış yap</button></form></div></aside>
<main class="admin-main"><header class="admin-top"><button class="sidebar-toggle" type="button">☰</button><div><small><?=date('d.m.Y')?></small><strong><?=e(admin_user()['name'])?></strong></div></header><div class="admin-content"><?php if($message=flash('admin_success')):?><div class="alert success"><?=e($message)?></div><?php endif;?>
<?php if($module===''): $counts=[]; foreach($modules as $key=>$cfg){$counts[$key]=(int)$db->query("SELECT COUNT(*) FROM {$cfg['table']}")->fetchColumn();}?>
<div class="page-title"><div><span>YÖNETİM MERKEZİ</span><h1>İyi günler, <?=e(explode(' ',admin_user()['name'])[0])?>.</h1><p>Etkinlik sitesinin güncel durumunu buradan takip edebilirsiniz.</p></div><a class="primary" href="<?=e(url('admin/sessions/create'))?>">+ Oturum ekle</a></div>
<section class="stat-grid"><a href="<?=e(url('admin/sessions'))?>"><span>Program</span><b><?=$counts['sessions']?></b><small>oturum</small></a><a href="<?=e(url('admin/speakers'))?>"><span>Konuşmacılar</span><b><?=$counts['speakers']?></b><small>kişi</small></a><a href="<?=e(url('admin/news'))?>"><span>Haberler</span><b><?=$counts['news']?></b><small>içerik</small></a><a href="<?=e(url('admin/contacts'))?>"><span>Yeni mesaj</span><b><?=$db->query("SELECT COUNT(*) FROM contacts WHERE status='new'")->fetchColumn()?></b><small>bekliyor</small></a></section>
<section class="dashboard-grid"><div class="panel"><div class="panel-head"><h2>Yaklaşan Oturumlar</h2><a href="<?=e(url('admin/sessions'))?>">Tümü →</a></div><?php $recent=$db->query('SELECT * FROM sessions ORDER BY day_date,start_time LIMIT 6')->fetchAll(); foreach($recent as $row):?><div class="mini-row"><time><?=e(substr($row['start_time'],0,5))?></time><div><b><?=e($row['title'])?></b><small><?=e(format_date_tr($row['day_date']))?></small></div></div><?php endforeach;?></div><div class="panel"><div class="panel-head"><h2>Hızlı İşlemler</h2></div><div class="quick-grid"><a href="<?=e(url('admin/news/create'))?>">Haber ekle <b>+</b></a><a href="<?=e(url('admin/speakers/create'))?>">Konuşmacı ekle <b>+</b></a><a href="<?=e(url('admin/gallery/create'))?>">Görsel ekle <b>+</b></a><a href="<?=e(url('admin/sponsors/create'))?>">Sponsor ekle <b>+</b></a></div></div></section>
<?php elseif($module==='settings'): $settings=$db->query('SELECT * FROM settings ORDER BY `key`')->fetchAll();?>
<div class="page-title"><div><span>SİSTEM</span><h1>Site Ayarları</h1><p>İletişim, sosyal medya ve footer bilgilerini yönetin.</p></div></div><form class="edit-form" method="post"><?=csrf_field()?><?php foreach($settings as $setting):?><label><?=e(ucwords(str_replace('_',' ',$setting['key'])))?><input name="settings[<?=e($setting['key'])?>]" value="<?=e($setting['value'])?>"></label><?php endforeach;?><button class="primary" type="submit">Değişiklikleri kaydet</button></form>
<?php elseif(in_array($action,['create','edit'],true)):?>
<div class="page-title"><div><span><?=e(mb_strtoupper($admin['title']))?></span><h1><?=$action==='create'?'Yeni kayıt':'Kaydı düzenle'?></h1></div><a class="secondary" href="<?=e(url('admin/'.$module))?>">← Listeye dön</a></div><?php if(!empty($formError)):?><div class="alert error"><?=e($formError)?></div><?php endif;?><form class="edit-form" method="post" enctype="multipart/form-data"><?=csrf_field()?>
<?php foreach($admin['fields'] as $field=>$meta): $value=$record[$field]??''; $type=$meta[1];?><label class="<?=$type==='html'||$type==='textarea'?'wide':''?>"><?=e($meta[0])?><?php if($type==='boolean'):?><input type="checkbox" name="<?=e($field)?>" value="1" <?=!empty($value)?'checked':''?>><span class="switch"></span><?php elseif($type==='select'):?><select name="<?=e($field)?>"><?php foreach($meta[2] as $option):?><option value="<?=e($option)?>" <?=$value===$option?'selected':''?>><?=e($option)?></option><?php endforeach;?></select><?php elseif($type==='event'): $eventOptions=$db->query('SELECT id,year,title FROM events ORDER BY year DESC')->fetchAll();?><select name="<?=e($field)?>" required><option value="">Seçin</option><?php foreach($eventOptions as $option):?><option value="<?=$option['id']?>" <?=(string)$value===(string)$option['id']?'selected':''?>><?=e($option['year'].' · '.$option['title'])?></option><?php endforeach;?></select><?php elseif($type==='textarea'||$type==='html'):?><textarea name="<?=e($field)?>" rows="<?=$type==='html'?14:5?>"><?=e($value)?></textarea><?php elseif($type==='image'):?><input type="url" name="<?=e($field)?>" value="<?=e($value)?>" placeholder="https://..."><span class="upload"><input type="file" name="<?=e($field)?>_upload" accept="image/jpeg,image/png,image/webp,image/gif">Bilgisayardan görsel seç</span><?php if($value):?><img class="image-preview" src="<?=e($value)?>" alt=""><?php endif;?><?php else: $htmlType=$type==='slug'?'text':$type; $displayValue=$type==='datetime-local'&&$value?str_replace(' ','T',substr((string)$value,0,16)):$value;?><input type="<?=e($htmlType)?>" name="<?=e($field)?>" value="<?=e($displayValue)?>" <?=$field==='title'||$field==='name'?'required':''?>><?php endif;?></label><?php endforeach;?>
<div class="form-actions"><button class="primary" type="submit">Kaydet</button><a class="secondary" href="<?=e(url('admin/'.$module))?>">Vazgeç</a></div></form>
<?php else: $order=in_array($module,['sessions','speakers','sponsors','gallery','videos'],true)?'sort_order,id':'id'; $rows=$db->query("SELECT * FROM {$admin['table']} ORDER BY $order DESC LIMIT 500")->fetchAll();?>
<div class="page-title"><div><span>İÇERİK YÖNETİMİ</span><h1><?=e($admin['title'])?></h1><p><?=count($rows)?> kayıt listeleniyor.</p></div><?php if(empty($admin['readonly'])):?><a class="primary" href="<?=e(url('admin/'.$module.'/create'))?>">+ Yeni ekle</a><?php endif;?></div><div class="table-wrap"><table><thead><tr><?php foreach($admin['list'] as $label):?><th><?=e($label)?></th><?php endforeach;?><th></th></tr></thead><tbody><?php foreach($rows as $row):?><tr><?php foreach($admin['list'] as $field=>$label):?><td><?php if($field==='published'||$field==='is_current'):?><span class="status <?=$row[$field]?'on':'off'?>"><?=$row[$field]?'Evet':'Hayır'?></span><?php elseif($field==='status'):?><span class="status <?=e($row[$field])?>"><?=e($row[$field])?></span><?php else:?><?=e(mb_strlen((string)$row[$field])>75?mb_substr((string)$row[$field],0,72).'…':$row[$field])?><?php endif;?></td><?php endforeach;?><td class="actions"><a href="<?=e(url('admin/'.$module.'/edit/'.$row['id']))?>"><?=$module==='contacts'?'Görüntüle':'Düzenle'?></a><form method="post" action="<?=e(url('admin/'.$module.'/delete/'.$row['id']))?>" onsubmit="return confirm('Bu kayıt kalıcı olarak silinsin mi?')"><?=csrf_field()?><button>Sil</button></form></td></tr><?php endforeach;?></tbody></table><?php if(!$rows):?><div class="empty-table">Henüz kayıt bulunmuyor.</div><?php endif;?></div>
<?php endif;?></div></main><script>const b=document.querySelector('.sidebar-toggle'),s=document.querySelector('.sidebar');b?.addEventListener('click',()=>s.classList.toggle('open'));</script></body></html>
