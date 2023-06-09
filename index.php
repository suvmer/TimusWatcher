<?php

function getTasks($id) {
	$homepage = file_get_contents("https://acm.timus.ru/author.aspx?id=".$id);
	$cnt = 0;
	$i = 2500;
	for(; $i < strlen($homepage) - 8; $i++) {
		if($homepage[$i] == '_' && $homepage[$i+1] = 's' && $homepage[$i+2] = 't' && $homepage[$i+6] = '_' && $homepage[$i+7] == 'v') {
			$cnt++;
			if($cnt == 2) {
				$ind1 = 0;
				$res = "";
				for($j = $i + 12; $j < $i + 100; $j++) {
					if($ind1 == 0 && $homepage[$j] == '>') {
						$ind1 = $j + 1;
						while($homepage[$ind1] != ' ') {
							$res = $res.$homepage[$ind1];
							$ind1++;
						}
					}
				}
				return intval($res);
				break;
			}
		}
	}
}

$ids = [339438, 339576, 339419];
$names = ["egurt", "Demon076", "Kseniyapal"];
$rur = ["Жмышенко Г", "Nate H", "John B"];
$res = []; //строки таблицы
$kk = [];  //количество задач для буфера
foreach($ids as $wh => $cur) {
	$kk[] = getTasks($cur);
	if($wh == count($names) - 1)
		$res[] = '<td style="background-color: red;">'.$rur[$wh].'</td><td style="background-color: red;">'.$names[$wh].'</td><td style="background-color: red;"><a href="https://acm.timus.ru/author.aspx?id='.$cur.'">Ссылка на профиль</a></td><td style="background-color: red;">'.$kk[count($kk)-1]."</td>";
	else
		$res[] = "<td>".$rur[$wh]."</td><td>".$names[$wh].'</td><td><a href="https://acm.timus.ru/author.aspx?id='.$cur.'">Ссылка на профиль</a></td><td>'.$kk[count($kk)-1]."</td>";
}
$id = implode('\n ', $kk);
$tabl = "<tr>".implode('</tr><tr>', $res)."</tr>";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Page for my master</title>
<script type="text/javascript">
//удобная функция замены всех вхождений в строку
String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};

let tocopy = "<?php echo $id; ?>"; //то, что в буфер

let toht = tocopy.replaceAll("\n", "<br>");

const copyListener = (e) => {
  //ниже происходит замена буфера обмена
  const range = window.getSelection().getRangeAt(0),
    rangeContents = range.cloneContents(),
	
    helper = document.createElement("div");

  helper.appendChild(rangeContents);
  //буфер текстовый
  event.clipboardData.setData("text/plain", `${tocopy}`);
  //буфер "страничный" - именно то, что идёт в эксель
  event.clipboardData.setData("text/html", `${toht}`);
  
  //показываем "Скопировано"
  document.getElementById("copied").style.display = 'block';
  
  //отменяем происходящее без наших изменений
  event.preventDefault();
};
document.addEventListener("copy", copyListener);
</script>
<style type="text/css">
.styled-table {
    border-collapse: collapse;
    margin: 25px 0;
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 400px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}
.styled-table thead tr {
    background-color: #009879;
    color: #ffffff;
    text-align: left;
}
.styled-table th,
.styled-table td {
    padding: 12px 15px;
}
.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
}

.styled-table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
}

.styled-table tbody tr:last-of-type {
    border-bottom: 2px solid #009879;
}
.styled-table tbody tr.active-row {
    font-weight: bold;
    color: #009879;
}
p {
    font-weight: bold;
    color: #009879;
}
#copied {
	display: none;
	font-size: 30px;
}
</style>
</head>
<body>
<table class="styled-table">
    <thead>
        <tr>
            <th>ФИО</th>
            <th>Хэндл</th>
            <th>URL</th>
            <th>Решено</th>
        </tr>
    </thead>
    <tbody>
        <?php echo $tabl; ?>
    </tbody>
	<p id="copied">Скопировано</p>
</table>
</body>
</html>