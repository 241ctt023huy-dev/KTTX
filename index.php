<?php
session_start();

if (!isset($_SESSION['students'])) {
    $_SESSION['students'] = [];
}

$error = "";

/* Thêm sinh viên */
if (isset($_POST['add'])) {

    $mssv = trim($_POST['mssv']);
    $hoten = trim($_POST['hoten']);
    $php = $_POST['php'];
    $mysql = $_POST['mysql'];
    $html = $_POST['html'];

    if (
        empty($mssv) ||
        empty($hoten) ||
        $php === "" ||
        $mysql === "" ||
        $html === ""
    ) {
        $error = "Không được để trống dữ liệu!";
    }
    elseif (
        $php < 0 || $php > 10 ||
        $mysql < 0 || $mysql > 10 ||
        $html < 0 || $html > 10
    ) {
        $error = "Điểm phải từ 0 đến 10!";
    }
    else {

        $_SESSION['students'][] = [
            'mssv' => $mssv,
            'hoten' => $hoten,
            'php' => $php,
            'mysql' => $mysql,
            'html' => $html
        ];
    }
}

/* Xóa sinh viên */
if (isset($_GET['delete'])) {

    $index = $_GET['delete'];

    if (isset($_SESSION['students'][$index])) {
        unset($_SESSION['students'][$index]);
        $_SESSION['students'] = array_values($_SESSION['students']);
    }
}

/* Thống kê */
$tongSV = count($_SESSION['students']);
$tongHocBong = 0;

$gioi = 0;
$kha = 0;
$trungbinh = 0;
$yeu = 0;
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Quản Lý Điểm Sinh Viên</title>

<style>

body{
    font-family: Arial;
    width: 1100px;
    margin: auto;
}

h2{
    text-align:center;
}

table{
    border-collapse: collapse;
    width:100%;
    margin-top:20px;
}

table, th, td{
    border:1px solid black;
}

th, td{
    padding:8px;
    text-align:center;
}

.error{
    color:red;
    margin-bottom:10px;
    font-weight:bold;
}

.form-box{
    border:2px solid #333;
    padding:15px;
    margin-bottom:20px;
    border-radius:8px;
    background:#f5f5f5;
}

form{
    display:flex;
    align-items:center;
    gap:8px;
    flex-wrap:wrap;
}

input{
    padding:5px;
    width:90px;
}

button{
    padding:8px 15px;
    background:#28a745;
    color:white;
    border:none;
    border-radius:5px;
    cursor:pointer;
    font-weight:bold;
}

button:hover{
    background:#218838;
}

.thongke{
    border:2px solid #333;
    padding:15px;
    margin-top:20px;
    width:350px;
    border-radius:8px;
    background:#f5f5f5;
}

</style>

</head>
<body>

<h2>QUẢN LÝ ĐIỂM SINH VIÊN</h2>

<?php
if($error != ""){
    echo "<div class='error'>$error</div>";
}
?>

<div class="form-box">

<h3>THÔNG TIN SINH VIÊN</h3>

<form method="POST">

<label>MSSV:</label>
<input type="text" name="mssv" required>

<label>Họ tên:</label>
<input type="text" name="hoten" required>

<label>Điểm PHP:</label>
<input type="number" name="php" min="0" max="10" step="0.1" required>

<label>Điểm MySQL:</label>
<input type="number" name="mysql" min="0" max="10" step="0.1" required>

<label>Điểm HTML:</label>
<input type="number" name="html" min="0" max="10" step="0.1" required>

<button type="submit" name="add">
    Thêm sinh viên
</button>

</form>

</div>

<table>

<tr>
    <th>MSSV</th>
    <th>Họ tên</th>
    <th>ĐTB</th>
    <th>Xếp loại</th>
    <th>Cao nhất</th>
    <th>Thấp nhất</th>
    <th>TB các môn</th>
    <th>Học bổng</th>
    <th>Xóa</th>
</tr>

<?php

foreach($_SESSION['students'] as $index => $sv){

    $dtb = ($sv['php'] * 2 + $sv['mysql'] * 2 + $sv['html']) / 5;

    if($dtb >= 8){
        $xeploai = "Giỏi";
        $gioi++;
    }
    elseif($dtb >= 6.5){
        $xeploai = "Khá";
        $kha++;
    }
    elseif($dtb >= 5){
        $xeploai = "Trung bình";
        $trungbinh++;
    }
    else{
        $xeploai = "Yếu";
        $yeu++;
    }

    if($xeploai == "Giỏi"){
        $hocbong = "Đủ điều kiện";
        $tongHocBong++;
    }
    else{
        $hocbong = "Không đủ điều kiện";
    }

    $max = max($sv['php'], $sv['mysql'], $sv['html']);
    $min = min($sv['php'], $sv['mysql'], $sv['html']);
    $tbMon = ($sv['php'] + $sv['mysql'] + $sv['html']) / 3;

    echo "<tr>";

    echo "<td>".$sv['mssv']."</td>";
    echo "<td>".$sv['hoten']."</td>";
    echo "<td>".number_format($dtb,2)."</td>";
    echo "<td>".$xeploai."</td>";
    echo "<td>".$max."</td>";
    echo "<td>".$min."</td>";
    echo "<td>".number_format($tbMon,2)."</td>";
    echo "<td>".$hocbong."</td>";
    echo "<td><a href='?delete=$index'>Xóa</a></td>";

    echo "</tr>";
}
?>

</table>

<div class="thongke">

<h3>THỐNG KÊ</h3>

<p>Tổng sinh viên: <?php echo $tongSV; ?></p>

<p>Tổng sinh viên có học bổng:
<?php echo $tongHocBong; ?>
</p>

<p>Sinh viên giỏi:
<?php echo $gioi; ?>
</p>

<p>Sinh viên khá:
<?php echo $kha; ?>
</p>

<p>Sinh viên trung bình:
<?php echo $trungbinh; ?>
</p>

<p>Sinh viên yếu:
<?php echo $yeu; ?>
</p>

</div>

</body>
</html>