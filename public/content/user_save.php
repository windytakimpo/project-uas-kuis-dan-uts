<?php

$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
$password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
$level = filter_var($_POST['level'], FILTER_SANITIZE_STRING);

$pass_hash = password_hash($password, PASSWORD_DEFAULT);

// cek data kosong
if (empty($username) || empty($password) || empty($level)) {
    echo "<script>
            alert('Data tidak boleh kosong');
            window.location.href = 'index.php?page=user';
    </script>";
} else {
    //cek username
    $cek = $con->prepare("SELECT * FROM user WHERE username = ?");
    $cek->bindParam(1, $username);
    $cek->execute();

    if ($cek->rowCount() == 0) {
        # nim tidak ada -> insert ke db
        $sql = "INSERT INTO user (username, password, level) VALUES (?,?,?)";
        //$simpan = $con->exec($sql);   
        $simpan = $con->prepare($sql);
        $simpan->bindParam(1, $username);
        $simpan->bindParam(2, $pass_hash);
        $simpan->bindParam(3, $level);
        $simpan->execute();

        if ($simpan->rowCount() > 0) {
            echo "<script>
                alert('Data Berhasil disimpan');
                window.location.href = 'index.php?page=user';
            </script>";
        } else {
            echo "<script>
                alert('Data Gagal disimpan');
                window.location.href = 'index.php?page=user';
            </script>";
        }
    } else {
        # nim ada
        echo "<script>
                alert('Username sudah ada!');
                window.location.href = 'index.php?page=user';
            </script>";
    }
}
