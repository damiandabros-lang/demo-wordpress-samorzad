<?php
/* Metryczka (BIP): wyświetlana dla stron działu BIP. */
$bip = get_page_by_path('bip');
if (!$bip || isv_section_root() !== $bip->ID) return;
$id = get_the_ID();
$wytworzyl = get_post_meta($id, '_isv_wytworzyl', true) ?: 'Sekretarz Gminy (osoba fikcyjna)';
$opublikowal = get_post_meta($id, '_isv_opublikowal', true) ?: 'Redakcja BIP (demo)';
?>
<section class="metryczka" aria-labelledby="metryczka-h">
  <h2 id="metryczka-h">Metryczka</h2>
  <dl>
    <dt>Informację wytworzył:</dt><dd><?php echo esc_html($wytworzyl); ?></dd>
    <dt>Data wytworzenia:</dt><dd><time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('Y-m-d')); ?></time></dd>
    <dt>Wprowadził do BIP:</dt><dd><?php echo esc_html($opublikowal); ?></dd>
    <dt>Data publikacji:</dt><dd><time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('Y-m-d H:i')); ?></time></dd>
    <dt>Ostatnia zmiana:</dt><dd><time datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>"><?php echo esc_html(get_the_modified_date('Y-m-d H:i')); ?></time></dd>
    <dt>Liczba wersji:</dt><dd><?php echo (int) max(1, count(wp_get_post_revisions($id))); ?></dd>
  </dl>
</section>
