<!DOCTYPE html>
<html>
<head>
    <title>Perpustakaan Mini</title>
</head>
<body>
    <h1>Form Peminjaman Buku</h1>
    <form method="post" action="">
        <label>Nama Peminjam:</label>
        <input type="text" name="nama_peminjam" required><br><br>
        <label>Kode Jenis Buku:</label>
        <select name="kode_buku">
            <option value="C">Cerpen</option>
            <option value="K">Komik</option>
            <option value="N">Novel</option>
        </select><br><br>
        <label>Banyak Pinjam:</label>
        <input type="number" name="banyak_pinjam" required min="0"><br><br>
        <input type="submit" name="submit" value="Submit">
    </form>

    <?php
    session_start();

    if (isset($_POST['submit'])) {
        $nama = $_POST['nama_peminjam'];
        $kode = $_POST['kode_buku'];
        $banyak = $_POST['banyak_pinjam'];

        if ($kode == 'C') {
            $judul = 'Cerpen';
            $tarif = 500;
        } elseif ($kode == 'K') {
            $judul = 'Komik';
            $tarif = 700;
        } elseif ($kode == 'N') {
            $judul = 'Novel';
            $tarif = 1000;
        }

        $jumlah_bayar = $banyak * $tarif;

        $data = array("Nama Peminjam" => $nama, "Kode Jenis Buku" => $kode, "Judul Buku" => $judul, "Banyak Pinjam" => $banyak, "Tarif" => $tarif, "Jumlah Bayar" => $jumlah_bayar);

        $_SESSION['peminjaman_buku'][] = $data;

        usort($_SESSION['peminjaman_buku'], function($a, $b) {
            return $a['Nama Peminjam'] <=> $b['Nama Peminjam'];
        });
    }

    if (isset($_SESSION['peminjaman_buku'])) {
        echo "<form method='post' action=''>";
        echo "<table border='1'>";
        echo "<tr><th>No.</th><th>Nama Peminjam</th><th>Jenis Buku</th><th>Jlh</th><th>Tarif</th><th>Bayar sewa</th><th>hapus</th><th>edit</th></tr>";
        $no = 1;
        foreach ($_SESSION['peminjaman_buku'] as $index => $row) {
            echo "<tr>";
            echo "<td>" . $no . "</td>";
            echo "<td>" . $row['Nama Peminjam'] . "</td>";
            echo "<td>" . $row['Judul Buku'] . "</td>";
            echo "<td>" . $row['Banyak Pinjam'] . "</td>";
            echo "<td>" . $row['Tarif'] . "</td>";
            echo "<td>" . $row['Jumlah Bayar'] . "</td>";
            echo "<td><input type='checkbox' name='hapus[]' value='" . $index . "'></td>";
            echo "<td><input type='checkbox' name='edit[]' value='" . $index . "'></td>";
            echo "</tr>";
            $no++;
        }
        echo "</table>";
        echo "<input type='submit' name='edit_button' value='Edit'>";
        echo "<input type='submit' name='hapus_button' value='Hapus'>";
        echo "</form>";
        if (isset($_POST['hapus_button'])) {
            if (!empty($_POST['hapus'])) {
                foreach ($_POST['hapus'] as $index) {
                    unset($_SESSION['peminjaman_buku'][$index]);
                }
                $_SESSION['peminjaman_buku'] = array_values($_SESSION['peminjaman_buku']); // reset array index
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit();
            }
        }
        if (isset($_POST['edit_button'])) {
            if (!empty($_POST['edit'])) {
                foreach ($_POST['edit'] as $index) {
                    $edit_data = $_SESSION['peminjaman_buku'][$index];
                    echo "<form method='post' action=''>";
                    echo "<input type='hidden' name='edited_index' value='" . $index . "'>";
                    echo "<label>Nama Peminjam:</label>";
                    echo "<input type='text' name='edit_nama_peminjam' value='" . $edit_data['Nama Peminjam'] . "' required><br><br>";
                    echo "<label>Kode Jenis Buku:</label>";
                    echo "<select name='edit_kode_buku'>
                        <option value='C' " . ($edit_data['Kode Jenis Buku'] == 'C' ? 'selected' : '') . ">Cerpen</option>
                        <option value='K' " . ($edit_data['Kode Jenis Buku'] == 'K' ? 'selected' : '') . ">Komik</option>
                        <option value='N' " . ($edit_data['Kode Jenis Buku'] == 'N' ? 'selected' : '') . ">Novel</option>
                        </select><br><br>";
                    echo "<label>Banyak Pinjam:</label>";
                    echo "<input type='number' name='edit_banyak_pinjam' value='" . $edit_data['Banyak Pinjam'] . "' required min='0'><br><br>";
                    echo "<input type='submit' name='submit_edit' value='Submit Edit'>";
                    echo "</form>";
                }
            }
        }

        if (isset($_POST['submit_edit'])) {
            $edited_index = $_POST['edited_index'];
            $edit_nama = $_POST['edit_nama_peminjam'];
            $edit_kode = $_POST['edit_kode_buku'];
            $edit_banyak = $_POST['edit_banyak_pinjam'];

            if ($edit_kode == 'C') {
                $edit_judul = 'Cerpen';
                $edit_tarif = 500;
            } elseif ($edit_kode == 'K') {
                $edit_judul = 'Komik';
                $edit_tarif = 700;
            } elseif ($edit_kode == 'N') {
                $edit_judul = 'Novel';
                $edit_tarif = 1000;
            }

            $edit_jumlah_bayar = $edit_banyak * $edit_tarif;

            $_SESSION['peminjaman_buku'][$edited_index]['Nama Peminjam'] = $edit_nama;
            $_SESSION['peminjaman_buku'][$edited_index]['Kode Jenis Buku'] = $edit_kode;
            $_SESSION['peminjaman_buku'][$edited_index]['Judul Buku'] = $edit_judul;
            $_SESSION['peminjaman_buku'][$edited_index]['Banyak Pinjam'] = $edit_banyak;
            $_SESSION['peminjaman_buku'][$edited_index]['Tarif'] = $edit_tarif;
            $_SESSION['peminjaman_buku'][$edited_index]['Jumlah Bayar'] = $edit_jumlah_bayar;

            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    }
    ?>
</body>
</html>
