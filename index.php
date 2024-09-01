<?php
session_start();
if(!isset($_SESSION['kontrol'])){
    $_SESSION['kontrol'] = 1;
}

function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

#Sql taraflı korumanın sağlanması : 
$baglan = mysqli_connect("localhost", "root", "", "currency");
$ip = getUserIP();
$hata = 0;
$query = "SELECT * FROM currency WHERE ip = '".$ip."';";
$result = mysqli_query($baglan, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    if ($row) {
        $tekrar = $row['tekrar'];
        if ($tekrar < 3) {
            $tekrar++;
            $query = "UPDATE currency SET tekrar = '".$tekrar."' WHERE ip = '".$ip."';";
            $result = mysqli_query($baglan, $query);
        } else {
            $hata = 1;
        }
    } else {
        $query = "INSERT INTO currency (ip, tekrar) VALUES ('".$ip."', 1);";
        $result = mysqli_query($baglan, $query);
    }
} else {
    $hata = 1;
}

mysqli_close($baglan);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerçek Zamanlı Döviz Çevirici</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="shortcut icon" href="coinexchange.png" type="image/x-icon">
<?php
    require "currencies.php";
    $api = "https://api.exchangerate-api.com/v4/latest/USD";
    $api = file_get_contents($api);
    $api = json_decode($api,true);
    $result = "";
    $kontrol = $_SESSION['kontrol'];

    if( isset($_POST['convert']) && ($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['fromCurrency']) && isset($_POST['toCurrency'])))){
        $kontrol++;
        $_SESSION['kontrol'] = $kontrol;
        if($kontrol <=3){
            $pcs = htmlspecialchars(trim(stripslashes($_POST['userInput'])));
            $data = "https://api.exchangerate-api.com/v4/latest/".$_POST['fromCurrency'];
            $data = file_get_contents($data);
            $data = json_decode($data,true);
            if(array_key_exists($_POST['fromCurrency'],$currencies)) 
            {
                $from =  $currencies[$_POST['fromCurrency']];
            } 
            else{ 
                $from = $_POST['fromCurrency'];
            }
            if(array_key_exists($_POST['toCurrency'],$currencies)) 
            {
                $to =  $currencies[$_POST['toCurrency']];
            } 
            else{ 
                $to = $_POST['toCurrency'];
            }
            $result = $pcs." ".$from." = ".$data['rates'][$_POST['toCurrency']]*$pcs." ".$to;
        }
        else{
            $result = "Sorgu hakkınızı doldurdunuz.";
        }
        if($hata){
            $result = "Sorgu hakkınızı doldurdunuz.";   
        }
    }
?>
</head>
<body>
    
<form method="POST">

<div class="container">
        <h1 id="title">Gerçek Zamanlı Döviz Çevirici</h1>
        <img src="coinexchange.png" id="coinSticker" alt="coin exchange sticker">
        
        <input type="number" id="userValue" min="1" name="userInput" placeholder="100">
        
        <div class="selecterContainer">
            <select id="fromCurrency" name="fromCurrency" title="Convert From">
                <?php foreach($api['rates'] as $key => $value):?>
                    <option value="<?php echo $key?>"><?php if(array_key_exists($key,$currencies)) {echo $key." - ".$currencies[$key];} else{ echo $key;}?></option>
                <?php endforeach;?>
            </select>
            
            <button type="button" id="switchCurrency">🔁</button>

            
            
            <select id="toCurrency" name="toCurrency" title="Convert To">
                <?php foreach($api['rates'] as $key => $value):?>
                    <option value="<?php echo $key?>"><?php if(array_key_exists($key,$currencies)) {echo $key." - ".$currencies[$key];} else{ echo $key;}?></option>
                <?php endforeach;?>
            </select>
        </div>
        
        <p id="status"></p>

        <button type="submit" id="btn" name="convert">Convert</button>
        <p id="result"><?php echo $result;?></p>
    </div>

</form>

<script>
var now = new Date();

var day = now.getDate();
var month = now.getMonth() + 1; 
var year = now.getFullYear();
var hours = now.getHours();
var minutes = now.getMinutes();

var formattedDate = day + '/' + month + '/' + year + ' ' + hours + ':' + minutes;
document.getElementById('status').textContent = formattedDate;

document.getElementById('printButton').onclick = function() {
        window.print();
    };
</script>
</body>
</html>
