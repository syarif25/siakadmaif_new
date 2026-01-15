<table class="table">
    <thead>
        <tr>
            <th>NIS</th>
            <th>Nama Mahasiswa</th>
            <th>Nilai</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($mahasiswa as $m): ?>
        <tr>
            <td><?= $m->nis ?></td>
            <td><?= $m->nama_mahasiswa ?></td>
            <td>
                <input type="text" class="form-control" name="nilai[<?= $m->id_krs ?>]" value="<?= $m->nilai_angka ?>">
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
