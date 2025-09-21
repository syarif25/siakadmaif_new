<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: "Arial", serif; font-size: 12pt; }
        .kop { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; }
        .kop img { float: left; width: 80px; height: 80px; }
        .title { text-align: center; font-weight: bold; text-decoration: underline; margin-top: 10px; }
        .content { margin: 20px 40px; text-align: justify; }
        .footer { margin-top: 50px; text-align: right; }
    </style>
</head>
<body>

<div class="kop">
    <img src="<?= base_url('assets/images/kop2.png') ?>" alt="Logo">
    <!-- <div>
        <h3>LEMBAGA KADER AHLI FIKIH</h3>
        <h2>MA’HAD ALY SALAFIYAH SYAFI’IYAH</h2>
        <p>PONDOK PESANTREN SALAFIYAH SYAFI’IYAH SUKOREJO SITUBONDO</p>
        <small>NSM: 2412351200X01 | SK Dirjen Pendis (M.I) 3002 Tahun 2016 | SK Dirjen Pendis (M.2) 3844 Tahun 2017</small>
    </div> -->
</div>

<div class="title">
    SURAT KETERANGAN AKTIF <br>
    <small>Nomor: 0290/MA-IF/T.12/X/<?= date('Y') ?></small>
</div>

<div class="content">
    <p>Assalamualaikum Warahmatullah Wabarakatuh</p>
    <p>Disampaikan dengan hormat, melalui surat ini kami menerangkan dengan sebenar-benarnya bahwa:</p>

    <table>
        <tr><td>Nama</td><td>:</td><td> ->nama_mahasiswa </td></tr>
        <tr><td>NIM</td><td>:</td><td> ->nim </td></tr>
        <tr><td>Jenjang</td><td>:</td><td> ->jenjang </td></tr>
        <tr><td>Semester</td><td>:</td><td> ->semester </td></tr>
    </table>

    <p>
        Yang bersangkutan benar-benar <b>Sebagai Mahasantri Aktif Ma’had Aly ->jenjang ?></b> 
        tahun akademik <?= date('Y') ?>/<?= date('Y')+1 ?>.
    </p>

    <p>
        Demikian pemberitahuan ini. Atas perhatian dan partisipasi pihak terkait, disampaikan terima kasih. <br>
        Wassalamualaikum Warahmatullah Wabarakatuh
    </p>
</div>

<div class="footer">
    <p>Situbondo, <?= format_tanggal_indonesia(date('Y-m-d')) ?></p>
    <p>Katib Ma’had Aly,</p><br><br><br>
    <p><b>Khairuddin Habziz, M.H.I.</b></p>
</div>

</body>
</html>
