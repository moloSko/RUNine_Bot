<?php
//$mysqli = new mysqli("127.0.0.1", "user", "password", "database", 3306); -- сохранить
// Создаем соединение
$mysqli = new mysqli("127.0.0.1", "root", "", "armabot", 3306);
// Проверяем соединение
if (!$mysqli) {
  printf("Сбой подключения: " . mysqli_connect_error());
}
elseif($mysqli){
  printf("Подключено успешно \n");
  printf("Информация о хосте: %s\n", mysqli_get_host_info($mysqli));
}


//$result = $mysqli->query("SELECT id FROM roleds ORDER BY id ASC");
/*
for ($row_no = $result->num_rows - 1; $row_no >= 0; $row_no--) {
    $result->data_seek($row_no);
    $row = $result->fetch_assoc();
    echo " id = " . $row['id'] . "\n";
}

echo "Исходный порядок строк...\n";
foreach ($result as $row) {
    echo " id = " . $row['id'] . "\n";
}*/

/*
$result = $mysqli->query("SELECT id, Name, UID, Rank FROM roleds WHERE id = 2");
$row = $result->fetch_assoc();

printf("id = %s (%s)\n", $row['id'], gettype($row['id']));
printf("Name = %s (%s)\n", $row['Name'], gettype($row['Name']));
printf("UID = %s (%s)\n", $row['UID'], gettype($row['UID']));
printf("Rank = %s (%s)\n", $row['Rank'], gettype($row['Rank']));
*/
/* закрытие соединения */
//$mysqli->close();

/*
$result = $mysqli->query("SELECT id, Name, UID, Rank FROM roleds WHERE id = 2");
$row = $result->fetch_assoc();
printf($row['UID']);
*/