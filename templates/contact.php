<?php $eyebrow='BİZİMLE İLETİŞİME GEÇİN'; $heading='İletişim'; $summary='Etkinlik, başvurular, basın ve iş birlikleri hakkındaki sorularınız için buradayız.'; require BASE_PATH . '/templates/page-hero.php'; ?>
<section class="contact-page section-pad"><div class="container contact-grid">
    <div class="contact-info reveal"><span>İletişim</span><h2>Birlikte geleceği<br>konuşalım.</h2><a href="mailto:<?= e($repo->setting('contact_email')) ?>"><?= e($repo->setting('contact_email')) ?></a><a href="tel:<?= e(preg_replace('/\D+/', '', $repo->setting('contact_phone'))) ?>"><?= e($repo->setting('contact_phone')) ?></a><p><?= e($repo->setting('address')) ?></p></div>
    <form class="contact-form reveal" method="post"><?= csrf_field() ?>
        <?php if ($message=flash('success')): ?><div class="form-alert success"><?= e($message) ?></div><?php endif; ?><?php if ($message=flash('error')): ?><div class="form-alert error"><?= e($message) ?></div><?php endif; ?>
        <div class="form-row"><label>Ad Soyad<input name="name" required autocomplete="name"></label><label>E-posta<input name="email" type="email" required autocomplete="email"></label></div>
        <div class="form-row"><label>Telefon<input name="phone" autocomplete="tel"></label><label>Konu<input name="subject"></label></div>
        <label>Mesajınız<textarea name="message" rows="6" minlength="10" required></textarea></label><label class="honeypot">Web sitesi<input name="website" tabindex="-1" autocomplete="off"></label>
        <button class="button button-lime" type="submit">Mesajı gönder ↗</button>
    </form>
</div></section>

