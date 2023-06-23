<?php

include __DIR__.'/vendor/autoload.php';

/*--Основные--*/
use Discord\Discord;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
/*--Кнопки--*/
use Discord\Builders\MessageBuilder;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
/*--Меню--*/
use Discord\Builders\Components\SelectMenu;
use Discord\Builders\Components\Option;


date_default_timezone_set('Europe/Moscow');

$discord = new Discord([
    'token' => 'OTc4NjIxNDczNzg4OTUyNjk2.GSIuG2.odL1XyrlYUZqhms0xEQsteu3x3BT2MrTEdhOLY',
]);

$discord->on('ready', function (Discord $discord) {
    echo "Бот запущен и готов к работе!", PHP_EOL;

    //Слушать сообщения(вывод сообщений в логи).
    $discord->on('message', function (Message $message, Discord $discord) {
        $Week = date('j F Y G:i:s');
        $new_str = "{$message->guild->name} - {$message->channel->name} - {$Week} - {$message->author->username}: {$message->content}";
        $filename = __DIR__ . '/logi/log.txt';
        $fh = fopen($filename, 'c');
        fseek($fh, 0, SEEK_END); 
        fwrite($fh, PHP_EOL . $new_str); 
        fclose($fh);
    });
});

$discord->on('message', function (Message $message, Discord $discord){
    switch (mb_strtolower($message->content) !== 0){
      case mb_strtolower($message->content) == '!звание':
        $message->member->addRole('978626633458130994');
        echo "Добавил", PHP_EOL;
      break;
      case mb_strtolower($message->content) == '!разжаловать':
        $message->member->removeRole('978626633458130994');
        echo "Удалил", PHP_EOL;
      break;
    }
});


$discord->run();


/*---------Код №1---------*/

include __DIR__.'/vendor/autoload.php';

/*--Основные--*/
use Discord\Discord;
use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use Discord\Parts\Interactions\Command\Command;
/*--Роли--*/
use Discord\Parts\Guild\Guild;
use Discord\Parts\Guild\Role;
use Discord\Parts\User\Member;


$discord = new DiscordCommandClient([
    'token' => 'OTc4NjIxNDczNzg4OTUyNjk2.GSIuG2.odL1XyrlYUZqhms0xEQsteu3x3BT2MrTEdhOLY',
    'prefix' => '!',
]);

$discord->on('ready', function (Discord $discord) {
    echo "Бот запущен и готов к работе!", PHP_EOL;
});

$discord->registerCommand('ping', function (Message $message, $params) {
  if ($user = $message->mentions->first()) {
      $message->reply("Ответ {$user}!");
  }
  else{
    $message->reply("Используйте: !ping <@user>");
  }
});

$discord->registerCommand('должность', function (Message $message, $params) {
  if ($user = $message->mentions->first()) {
    $message->guild->members->fetch($user->id)->done(function (Member $member) {
      $member->addRole('978626633458130994');
    });
    $message->reply("Были выданы роли по должности!");    
  }
  else{
    $message->reply("Используйте: !должность <UID> или вашей должности нет в базе данных");
  }
});

$discord->run();


/*------Код №2---------*/

<?php

include __DIR__.'/vendor/autoload.php';

/*--Основные--*/
use Discord\Discord;
use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use Discord\Parts\Interactions\Command\Command;
/*--Роли--*/
use Discord\Parts\Guild\Guild;
use Discord\Parts\Guild\Role;
use Discord\Parts\User\Member;

$discord = new DiscordCommandClient([
    'token' => 'OTc4NjIxNDczNzg4OTUyNjk2.GSIuG2.odL1XyrlYUZqhms0xEQsteu3x3BT2MrTEdhOLY',
    'prefix' => '!',
    'discordOptions' => [
      'loadAllMembers' => true,
      'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS,
    ]
]);


$discord->on('ready', function (Discord $discord) {
    echo "Бот запущен и готов к работе!", PHP_EOL;
});

$discord->registerCommand('должность', function (Message $message, $params) {
  if ($user = $message->mentions->first()) {
      $message->guild->members->fetch($user->id)->done(function ($member) {
      $member->addRole('978626633458130994');
    });
    $message->reply("Были выданы роли по должности!");    
  }
  else{
    $message->reply("Используйте: !должность <UID> или вашей должности нет в базе данных");
  }
});

$discord->registerCommand('разжаловать', function (Message $message, $params) {
  if(($message->member->getPermissions()->administrator) == 1){
    if ($user = $message->mentions->first()) {
        $message->guild->members->fetch($user->id)->done(function ($member) {
        $member->removeRole('978626633458130994');
      });
      $message->reply("У вас забрали занимаемую должность!");    
    }
    else{
      $message->reply("Используйте: !разжаловать <UID> или такой должности нет в базе данных");
    }
  }
  else{
    $message->reply("Писька ещё не выросла!"); 
  }
});

$discord->run();



/*--------Код №3-----------*/


<?php

include __DIR__.'/vendor/autoload.php';

/*--Основные--*/
use Discord\Discord;
use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use Discord\Parts\Interactions\Command\Command;
/*--Роли--*/
use Discord\Parts\Guild\Guild;
use Discord\Parts\Guild\Role;
use Discord\Parts\User\Member;

$discord = new DiscordCommandClient([
    'token' => 'OTc4NjIxNDczNzg4OTUyNjk2.GSIuG2.odL1XyrlYUZqhms0xEQsteu3x3BT2MrTEdhOLY',
    'prefix' => '!',
    'discordOptions' => [
      'loadAllMembers' => true,
      'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS,
    ]
]);


$discord->on('ready', function (Discord $discord) {
  echo "Бот запущен и готов к работе!", PHP_EOL;
});

/*-------Проверка добавление роли по заготовленному значению---------*/

$discord->registerCommand('тест', function (Message $message, $params) {
  if(($user = mb_strstr($message->content, ' ', false)) == "978626633458130994"){
    $message->member->addRole('978626633458130994');
    $message->reply("Ваши роли были выданы");
  }
  else{
    $message->reply("Ваш UID не обнаружен или попробуйте ```!тест <UID>```");
  }
});

/*-------Добавить роль---------*/

$discord->registerCommand('должность', function (Message $message, $params) {
  if ($user = $message->mentions->first()) {
      $message->guild->members->fetch($user->id)->done(function ($member) {
      $member->addRole('978626633458130994');
    });
    $message->reply("Ваши роли по должности!");   
  }
  else{
    $message->reply("Используйте: !должность <UID> или вашей должности нет в базе данных");
  }
});

/*-------Удалить роль---------*/

$discord->registerCommand('разжаловать', function (Message $message, $params) {
  if(($message->member->getPermissions()->administrator) == 1){
    if ($user = $message->mentions->first()) {
        $message->guild->members->fetch($user->id)->done(function ($member) {
        $member->removeRole('978626633458130994');
      });
      $message->reply("У вас забрали занимаемую должность!");    
    }
    else{
      $message->reply("Используйте: !разжаловать <UID> или такой должности нет в базе данных");
    }
  }
  else{
    $message->reply("Ваше звание слишком низкое!"); 
  }
});

$discord->run();

/*---------------Код №4--------------------*/

<?php

include __DIR__.'/vendor/autoload.php';

/*--Основные--*/
use Discord\Discord;
use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use Discord\Parts\Interactions\Command\Command;
/*--Роли--*/
use Discord\Parts\Guild\Guild;
use Discord\Parts\Guild\Role;
use Discord\Parts\User\Member;

$discord = new DiscordCommandClient([
    'token' => 'OTc4NjIxNDczNzg4OTUyNjk2.GSIuG2.odL1XyrlYUZqhms0xEQsteu3x3BT2MrTEdhOLY',
    'prefix' => '!',
    'discordOptions' => [
      'loadAllMembers' => true,
      'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS,
    ]
]);


$discord->on('ready', function (Discord $discord) {
  echo "Бот запущен и готов к работе!", PHP_EOL;
});

/*-------Проверка добавление роли по заготовленному значению---------*/

$discord->registerCommand('тест', function (Message $message, $params) {

  $roleid = ['1234567890','1234567891'];

  $roleidi = ['1234567890' => 'Тест роль','1234567891' => 'Новобранец'];

  $addrole = [
    'Тест роль' => 978626633458130994,
    'Новобранец' => 979205530813878383,
  ];

  //echo ($addrole["Тест роль"]), PHP_EOL;

  if($message->channel->id == '972169881732657202'){
    $textinput = substr(mb_strstr($message->content, ' ', false), 1);
    //echo $textinput , PHP_EOL; -- вывод id роли
    if(in_array($textinput, $roleid)){
      if($roleidi[$textinput]){
        //echo $roleidi[$textinput], PHP_EOL; -- вывод названия роли
        $rolename = $roleidi[$textinput];
        $message->member->addRole($addrole[$rolename]);
      }
      $message->reply("Ваши роли были выданы");
    }
    else{
      $message->reply("Ваш UID не обнаружен или попробуйте ```!тест <UID>```");
    }
  }
  else{
    $message->reply('Я не могу писать в этом канале!');
  }

});

/*-------Удалить роль---------*/
$discord->registerCommand('разжаловать', function (Message $message, $params) {
  if(($message->member->getPermissions()->administrator) == 1){
    if ($user = $message->mentions->first()) {
        $message->guild->members->fetch($user->id)->done(function ($member) {
        $member->removeRole('978626633458130994');
      });
      $message->reply("У вас забрали занимаемую должность!");    
    }
    else{
      $message->reply("Используйте: !разжаловать <UID> или такой должности нет в базе данных");
    }
  }
  else{
    $message->reply("Ваше звание слишком низкое!"); 
  }
});

$discord->run();



/*------------------Код №5-----------------*/

<?php

include __DIR__.'/vendor/autoload.php';

require "db.php";

/*--Основные--*/
use Discord\Discord;
use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use Discord\Parts\Interactions\Command\Command;
/*--Роли--*/
use Discord\Parts\Guild\Guild;
use Discord\Parts\Guild\Role;
use Discord\Parts\User\Member;

$discord = new DiscordCommandClient([
    'token' => 'OTc4NjIxNDczNzg4OTUyNjk2.GSIuG2.odL1XyrlYUZqhms0xEQsteu3x3BT2MrTEdhOLY',
    'prefix' => '!',
    'discordOptions' => [
      'loadAllMembers' => true,
      'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS,
    ]
]);

$discord->on('ready', function (Discord $discord) {
  echo "Бот запущен и готов к работе!", PHP_EOL;
});

/*-------Проверка добавление роли по заготовленному значению---------*/

$discord->registerCommand('тест', function (Message $message, $params) {

  $roleid = ['1234567890','1234567891'];

  $roleidi = ['1234567890' => 'Тест роль','1234567891' => 'Новобранец'];

  $addrole = [
    'Тест роль' => 978626633458130994,
    'Новобранец' => 979205530813878383,
  ];

  if($message->channel->id == '972169881732657202'){
    $textinput = substr(mb_strstr($message->content, ' ', false), 1);
    //echo $textinput , PHP_EOL; //-- вывод id роли
    if(in_array($textinput, $roleid)){
      if($roleidi[$textinput]){
        //echo $roleidi[$textinput], PHP_EOL; -- вывод названия роли
        $rolename = $roleidi[$textinput];
        $message->member->addRole($addrole[$rolename]);
      }
      $message->reply("Ваши роли были выданы");
    }
    else{
      $message->reply("Ваш UID не обнаружен или попробуйте ```!тест <UID>```");
    }
  }
  else{
    $message->reply('Я не могу писать в этом канале!');
  }
});

/*-------Добавление роль SQL---------*/
$discord->registerCommand('звание', function (Message $message, $params) use ($mysqli) {
  if($message->channel->id == '972169881732657202'){
    //951724193706291210 - id канала привязка steam
    $textinput = substr(mb_strstr($message->content, ' ', false), 1);
    //echo $textinput , PHP_EOL; //-- вывод вводимый UID STEAM
    if (is_numeric($textinput)){
      $result = $mysqli->query("SELECT UID, Rank FROM roleds WHERE UID = $textinput");
      $row = $result->fetch_assoc();
      $message->reply("UID = {$row['UID']}");
      $message->reply("Rank = {$row['Rank']}");
    }
    else{
      $message->reply("Ваш UID не обнаружен или попробуйте ```!тест <UID>```");
    }
  }
  else{
    $message->reply('Я не могу писать в этом канале!');
  }
});

/*-------Удалить роль---------*/
$discord->registerCommand('разжаловать', function (Message $message, $params) {
  if(($message->member->getPermissions()->administrator) == 1){
    if ($user = $message->mentions->first()) {
        $message->guild->members->fetch($user->id)->done(function ($member) {
        $member->removeRole('978626633458130994');
      });
      $message->reply("У вас забрали занимаемую должность!");    
    }
    else{
      $message->reply("Используйте: !разжаловать <UID> или такой должности нет в базе данных");
    }
  }
  else{
    $message->reply("Ваше звание слишком низкое!"); 
  }
});

$discord->run();



/*----------------------Код №6-----------------------*/

<?php

include __DIR__.'/vendor/autoload.php';

require "db.php";

/*--Основные--*/
use Discord\Discord;
use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use Discord\Parts\Interactions\Command\Command;
/*--Роли--*/
use Discord\Parts\Guild\Guild;
use Discord\Parts\Guild\Role;
use Discord\Parts\User\Member;

$discord = new DiscordCommandClient([
    'token' => 'OTc4NjIxNDczNzg4OTUyNjk2.GSIuG2.odL1XyrlYUZqhms0xEQsteu3x3BT2MrTEdhOLY',
    'prefix' => '!',
    'discordOptions' => [
      'loadAllMembers' => true,
      'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS,
    ]
]);

$discord->on('ready', function (Discord $discord) {
  echo "Бот запущен и готов к работе!", PHP_EOL;
});

/*-------Добавление роль SQL---------*/
$discord->registerCommand('звание', function (Message $message, $params) use ($mysqli) {
  $addrole = [
    'Тест роль' => 978626633458130994,
    'Новобранец' => 979205530813878383,
    'Тестер' => 968198654978580550,
  ];

  if($message->channel->id == '972169881732657202'){
    //951724193706291210 - id канала привязка steam
    $textinput = substr(mb_strstr($message->content, ' ', false), 1);
    //echo $textinput , PHP_EOL; //-- вывод вводимый UID STEAM
    if (is_numeric($textinput)){
      $result = $mysqli->query("SELECT UID, Rank FROM roleds WHERE UID = $textinput");
      $row = $result->fetch_assoc();
      if($row['UID'] != ''){
        $rank = $row['Rank'];
        $message->member->addRole($addrole[$rank]);
        $message->reply("Вам было выдано ваше звание в соответствие со звание на сервере {$rank}");
        //$message->reply("RankM = {$addrole[$rank]}"); -- вывод id роли из дискорда
        //$message->reply("UID = {$row['UID']}"); -- вывод UID в бд 
        //$message->reply("Rank = {$rank}"); -- вывод звания пользователя
        //$message->member->addRole($addrole[$rolename]); -- выдача роли
      }else{
        $message->reply("Ваш UID не обнаружен.");
      }
    }
    else{
      $message->reply("Попробуйте ```!звание <UID>```");
    }
  }
  else{
    $message->reply('Я не могу писать в этом канале!');
  }
});

/*-------Удалить роль---------*/
$discord->registerCommand('разжаловать', function (Message $message, $params) {
  if(($message->member->getPermissions()->administrator) == 1){
    if ($user = $message->mentions->first()) {
        $message->guild->members->fetch($user->id)->done(function ($member) {
        $member->removeRole('978626633458130994');
      });
      $message->reply("У вас забрали занимаемую должность!");    
    }
    else{
      $message->reply("Используйте: !разжаловать <UID> или такой должности нет в базе данных");
    }
  }
  else{
    $message->reply("Ваше звание слишком низкое!"); 
  }
});

$discord->run();


/*----------------------------Код ИТОГ!-----------------------------------*/


<?php

include __DIR__.'/vendor/autoload.php';

require "db.php";

/*--Основные--*/
use Discord\Discord;
use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use Discord\Parts\Interactions\Command\Command;
/*--Роли--*/
use Discord\Parts\Guild\Guild;
use Discord\Parts\Guild\Role;
use Discord\Parts\User\Member;

$discord = new DiscordCommandClient([
    'token' => 'OTc4NjIxNDczNzg4OTUyNjk2.GSIuG2.odL1XyrlYUZqhms0xEQsteu3x3BT2MrTEdhOLY',
    'prefix' => '!',
    'discordOptions' => [
      'loadAllMembers' => true,
      'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS,
    ]
]);

$discord->on('ready', function (Discord $discord) {
  echo "Бот запущен и готов к работе!", PHP_EOL;
});

/*-------Добавление роль SQL---------*/

$discord->registerCommand('звание', function (Message $message, $params) use ($mysqli) {
  $addrole = [
    'Тест роль' => 978626633458130994,
    'Новобранец' => 979205530813878383,
    'Тестер' => 968198654978580550,
  ];

  $prrole = $mysqli->query("ALTER TABLE roleds ADD vd int");

  if($message->channel->id == '972169881732657202'){
    $textinput = substr(mb_strstr($message->content, ' ', false), 1);
    if (is_numeric($textinput)){
      $result = $mysqli->query("SELECT UID, Rank, vd FROM roleds WHERE UID = $textinput AND vd IS NULL");
      $row = $result->fetch_assoc();
      if($row['UID'] != ''){
        $rank = $row['Rank'];
        $message->member->addRole($addrole[$rank]);
        $message->reply(">>> Вам было выдано ваше звание в соответствие со звание на сервере **{$rank}**");
        $vdo = $mysqli->query("UPDATE roleds SET vd = '1' WHERE UID = '$textinput'");
      }else{
        $message->reply("Ваш UID не обнаружен или данная UID уже привязан к другому.");
      }
    }
    else{
      $message->reply("Попробуйте ```!звание <UID>```");
    }
  }
  else{
    $message->reply('Я не могу писать в этом канале!');
  }
});

/*-------Удалить роль---------*/

$discord->run();


/*----------------------------Код обновление v1.!-----------------------------------*/


<?php

include __DIR__.'/vendor/autoload.php';

require "db.php";

/*--Основные--*/
use Discord\Discord;
use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use Discord\Parts\Interactions\Command\Command;
/*--Роли--*/
use Discord\Parts\Guild\Guild;
use Discord\Parts\Guild\Role;
use Discord\Parts\User\Member;

date_default_timezone_set('Europe/Moscow');

$discord = new DiscordCommandClient([
    'token' => 'OTc4NjIxNDczNzg4OTUyNjk2.GSIuG2.odL1XyrlYUZqhms0xEQsteu3x3BT2MrTEdhOLY',
    'prefix' => '!',
    'discordOptions' => [
      'loadAllMembers' => true,
      'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS,
    ]
]);

$discord->on('ready', function (Discord $discord) {
  echo "Бот запущен и готов к работе!", PHP_EOL;
});

/*-------Добавление роль SQL---------*/

$discord->registerCommand('звание', function (Message $message, $params) use ($mysqli) {
  $namerole = [
    '1' => "Рядовой",
    '2' => "Ефрейтор",
    '3' => "Младший Сержант",
    '4' => "Сержант",
    '5' => "Старший Сержант",
    '6' => "Старшина",
    '7' => "Прапорщик",
    '8' => "Старший Прапощик",
    '9' => "Младший Лейтенант",
    '10' => "Лейтенант",
    '11' => "Старший Лейтенант",
    '12' => "Капитан",
    '13' => "Майор",
    '14' => "Подполковник",
    '15' => "Полковник",
    '16' => "Генерал-майор",
    '17' => "Генерал-лейтенант",
    '18' => "Генерал-полковник",
    '19' => "Генерал-армии",
    '20' => "Маршал ВС РФ",
  ];

  $addrole = [
    '1' => 951724558656892969,
    '2' => 959046714034098209,
    '3' => 951786333951631400,
    '4' => 951786335243485184,
    '5' => 951786337705529344,
    '6' => 959095031665426502,
    '7' => 951786342965190656,
    '8' => 951786344626151424,
    '9' => 951786346521964554,
    '10' => 951786347490856981,
    '11' => 951786348912738334,
    '12' => 952108992186966046,
    '13' => 952108996683239455,
    '14' => 952108999556354088,
    '15' => 952109001074692097,
    '16' => 952109003083767868,
    '17' => 952109283875643402,
    '18' => 952109285280710686,
    '19' => 952109286589354004,
    '20' => 952109288288026634,
  ];

  $prrole = $mysqli->query("ALTER TABLE players ADD pDiscrank int");
  $pdiscid = $mysqli->query("ALTER TABLE players ADD pDiscID varchar(50)");

  if($message->channel->id == '951724193706291210'){
    $textinput = substr(mb_strstr($message->content, ' ', false), 1);
    if (is_numeric($textinput)){
      $result = $mysqli->query("SELECT pUID, pLevel, pDiscrank FROM players WHERE pUID = $textinput AND pDiscrank IS NULL OR pDiscrank = 0");
      $row = $result->fetch_assoc();
      if($row['pUID'] != ''){
        $rank = $row['pLevel'];
        $message->member->addRole($addrole[$rank]);
        $message->member->addRole('959168899671277658');
        $message->reply(">>> Вам было выдано ваше звание в соответствие с званием на сервере **{$namerole[$rank]}**");
        $vdo = $mysqli->query("UPDATE players SET pDiscrank = '1' WHERE pUID = '$textinput'");
        $idDisc = $message->author->id;
        $vdc = $mysqli->query("UPDATE players SET pDiscID = '$idDisc' WHERE pUID = '$textinput'");
      }else{
        $message->reply(">>> Ваш UID не обнаружен или данный UID уже привязан к другому пользователю.");
      }
    }
    else{
      $message->reply("Попробуйте ```!звание <UID>```");
    }
  }
  else{
    $message->reply('>>> Я не могу писать в этом канале!');
  }
});

/*-------Помощь---------*/
$discord->registerCommand('доложить', function (Message $message, $params){
  if($message->channel->id == '951724193706291210'){
  $message->channel->sendMessage(">>> **{$message->guild->name}** \n \n**🗒️Привязка Steam к дискорд🗒️** \n | Узнать SteamID64(UID):\n 1. Открыть стим->об аккаунте ``https://ibb.co/VC8CTPX``.\n 2. Копируем данные цифры ``https://ibb.co/MfkNmhc`` это и есть UID.\n \n");
  }
  else{
    $message->reply('>>> Я не могу писать в этом канале!');
  }
});

$discord->run();


/*----------------------Код №8------------------------------*/

<?php

include __DIR__.'/vendor/autoload.php';

require "db.php";

/*--Основные--*/
use Discord\Discord;
use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Message;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use Discord\Parts\Interactions\Command\Command;
/*--Роли--*/
use Discord\Parts\Guild\Guild;
use Discord\Parts\Guild\Role;
use Discord\Parts\User\Member;
/*------Эмбед Сообщение-------*/
use Discord\Parts\Embed\Embed;
//use Discord\Builders\MessageBuilder;

date_default_timezone_set('Europe/Moscow');

$discord = new DiscordCommandClient([
    'token' => 'OTc5NzY1MTk0NTU1MjY5MTUw.GpCT7M.nSnSoIaH84sEMjeuifawFzlVh8uwOBWMY4uwjI',
    'prefix' => '!',
    'discordOptions' => [
      'loadAllMembers' => true,
      'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS,
    ]
]);

$discord->on('ready', function (Discord $discord) {
  echo "Бот запущен и готов к работе!", PHP_EOL;
});

/*-------Добавление роль SQL---------*/

$discord->registerCommand('тест', function (Message $message, $params) use ($mysqli) {
  $namerole = [
    '1' => "Рядовой",
    '2' => "Ефрейтор",
    '3' => "Младший Сержант",
    '4' => "Сержант",
    '5' => "Старший Сержант",
    '6' => "Старшина",
    '7' => "Прапорщик",
  ];

  $addrole = [
    '1' => '964518066832691230',
    '2' => '981020163547738163',
    '3' => '981020586740449360',
    '4' => '981021926229164083',
    '5' => '981022206433832970',
    '6' => '981023397750390826',
    '7' => '981023817793159178',
  ];

  $prrole = $mysqli->query("ALTER TABLE players ADD pDiscrank int");
  $pdiscid = $mysqli->query("ALTER TABLE players ADD pDiscID varchar(50)");
  $oldrole = $mysqli->query("ALTER TABLE players ADD pOldRank varchar(50)");

  if($message->channel->id == '972169881732657202'){//изменено
    $textinput = substr(mb_strstr($message->content, ' ', false), 1);
    if (is_numeric($textinput)){
      $result = $mysqli->query("SELECT pName, pUID, pLevel, pDiscrank FROM players WHERE pUID = $textinput AND pDiscrank IS NULL");
      if($row = mysqli_fetch_assoc($result)){
        if($row['pLevel'] > 0){
          $rank = $row['pLevel'];
          $namememb = $row['pName'];
          $message->member->setNickname($namememb);
          $message->member->addRole($addrole[$rank]);
          $message->member->addRole('959168899671277658');
          $message->reply(">>> Вам было выдано ваше звание в соответствие с званием на сервере **{$namerole[$rank]}**");
          $vdo = $mysqli->query("UPDATE players SET pDiscrank = '1' WHERE pUID = '$textinput'");
          $idDisc = $message->author->id;
          $vdc = $mysqli->query("UPDATE players SET pDiscID = '$idDisc' WHERE pUID = '$textinput'");
          $vdor = $mysqli->query("UPDATE players SET pOldRank = '$rank' WHERE pUID = '$textinput'");
        }
        else{
          $message->reply(">>> Пройдите Курс Молодого Бойца (проводится 'Инструктором КМБ') на сервере и повторите попытку");
        }
      }else{
        $message->reply(">>> Ваш UID не обнаружен или данный UID уже привязан к другому пользователю.");
      }
    }
    else{
      $message->reply("Попробуйте ```!звание <UID>```");
    }
  }
  else{
    $message->reply('>>> Я не могу писать в этом канале!');
  }
});

/*-------Помощь---------*/
$discord->registerCommand('доложить', function (Message $message, $params){
  if($message->channel->id == '972169881732657202'){//изменено
  $message->channel->sendMessage(">>> **{$message->guild->name}** \n \n**🗒️Привязка Steam к дискорд🗒️** \n Узнать SteamID64(UID):\n 1. Открыть стим->об аккаунте ``https://ibb.co/VC8CTPX``.\n 2. Копируем данные цифры ``https://ibb.co/MfkNmhc`` это и есть UID.\n \n**🔒Информация по званиям🔒** \n В звание..\n");
  }
  else{
    $message->reply('>>> Я не могу писать в этом канале!');
  }
});

/*-------Обновление ролей---------*/
$discord->on('message', function (Message $message, Discord $discord) use ($mysqli){
  $namerole = [
    '1' => "Рядовой",
    '2' => "Ефрейтор",
    '3' => "Младший Сержант",
    '4' => "Сержант",
    '5' => "Старший Сержант",
    '6' => "Старшина",
    '7' => "Прапорщик",
  ];

  $role = [
    '1' => '964518066832691230',
    '2' => '981020163547738163',
    '3' => '981020586740449360',
    '4' => '981021926229164083',
    '5' => '981022206433832970',
    '6' => '981023397750390826',
    '7' => '981023817793159178',
  ];
  
  $idauth = $message->author->id;
  $kaskad = $mysqli->query("SELECT pName, pLevel, pDiscID, pOldRank FROM players WHERE pDiscID = $idauth AND pDiscrank IS NOT NULL");
  if ($row = mysqli_fetch_assoc($kaskad)){
    $rank = $row['pLevel'];
    $orank = $row['pOldRank'];
    $namememb = $row['pName'];
    if ($rank != $orank){
      $message->member->setNickname($namememb);
      for($r = 1; $r <= 7; $r++){
      $message->member->removeRole($role[$r]);          
      };
      $message->member->addRole($role[$rank]);
      $updt = $mysqli->query("UPDATE players SET pOldRank = '$rank' WHERE pDiscID = $idauth");
      $message->guild->channels->get('id', '972169881732657202')->sendMessage("{$message->author} Ваше новое звание - **{$namerole[$row['pLevel']]}**\n");
    };//изменено
  };
});

/*-------Уровни---------*/
$discord->on('message', function (Message $message, Discord $discord) use ($mysqlife){
  if($message->guild->id == '964517705791184986'){//ID СЕРВЕРА
    if($message->author->bot == null){
      $autorID = $message->author->id;
      $authName = $message->author->username;
      $provDiscID = $mysqlife->query("SELECT dID, dName, dExp, dLvl, dmaxExp FROM discordlvl WHERE dID = $autorID");
      if($trid = mysqli_fetch_assoc($provDiscID)){
        $upExp = $mysqlife->query("UPDATE discordlvl SET dExp = dExp + '0.5' WHERE dID = $autorID");
        $lvl = $trid['dLvl'];
        $lvlupp = $trid['dLvl'] + 1;
        /*$lvlrole = [
          '1' => '959088058072973322', // Балтун
          '2' => '957613850407096380', // Критик  
          '3' => '957613522039210014', // Голос народа
          '4' => '957613715572818012', // Оратор 
        ];*/
        $lvlrole = [
          '1' => '964518066832691230', // Балтун
          '2' => '981020163547738163', // Критик  
          '3' => '981020586740449360', // Голос народа
          '4' => '981021926229164083', // Оратор 
        ];
        if($lvl >= 0 and $lvl < 9){
          if($trid['dExp'] >= '500'){
            $updtexp = $mysqlife->query("UPDATE discordlvl SET dExp = '0' WHERE dID = $autorID");
            $updtlvl = $mysqlife->query("UPDATE discordlvl SET dLvl = dLvl+'1' WHERE dID = $autorID");
            $message->guild->channels->get('id', '972169881732657202')->sendMessage("{$message->author} Ваш уровень вырос до **{$lvlupp}**\n");
          }
        }
        if ($lvl == 9){
          if($trid['dExp'] >= '500'){
            $updtexp = $mysqlife->query("UPDATE discordlvl SET dExp = '0' WHERE dID = $autorID");
            $updtlvl = $mysqlife->query("UPDATE discordlvl SET dLvl = dLvl+'1' WHERE dID = $autorID");
            for($l = 1; $l <= 4; $l++){
              $message->member->removeRole($lvlrole[$l]);                 
            };
            $updtexp = $mysqlife->query("UPDATE discordlvl SET dmaxExp = '1000' WHERE dID = $autorID");
            $message->member->addRole($lvlrole[1]); 
            $message->guild->channels->get('id', '972169881732657202')->sendMessage("{$message->author} Ваш уровень вырос до **{$lvlupp}**\n");
          }
        }
        if($lvl >= 10 and $lvl < 29){
          if($trid['dExp'] >= '1000'){
            $updtexp = $mysqlife->query("UPDATE discordlvl SET dExp = '0' WHERE dID = $autorID");
            $updtlvl = $mysqlife->query("UPDATE discordlvl SET dLvl = dLvl+'1' WHERE dID = $autorID");
            $message->guild->channels->get('id', '972169881732657202')->sendMessage("{$message->author} Ваш уровень вырос до **{$lvlupp}**\n");
          }
        }
        if ($lvl == 29){
          if($trid['dExp'] >= '1000'){
            $updtexp = $mysqlife->query("UPDATE discordlvl SET dExp = '0' WHERE dID = $autorID");
            $updtlvl = $mysqlife->query("UPDATE discordlvl SET dLvl = dLvl+'1' WHERE dID = $autorID");
            for($l = 1; $l <= 4; $l++){
              $message->member->removeRole($lvlrole[$l]);                 
            };
            $updtexp = $mysqlife->query("UPDATE discordlvl SET dmaxExp = '1500' WHERE dID = $autorID");
            $message->member->addRole($lvlrole[2]); 
            $message->guild->channels->get('id', '972169881732657202')->sendMessage("{$message->author} Ваш уровень вырос до **{$lvlupp}**\n");
          }
        }
        if($lvl >= 30 and $lvl < 69){
          if($trid['dExp'] >= '1500'){
            $updtexp = $mysqlife->query("UPDATE discordlvl SET dExp = '0' WHERE dID = $autorID");
            $updtlvl = $mysqlife->query("UPDATE discordlvl SET dLvl = dLvl+'1' WHERE dID = $autorID");
            $message->guild->channels->get('id', '972169881732657202')->sendMessage("{$message->author} Ваш уровень вырос до **{$lvlupp}**\n");
          }
        }
        if ($lvl == 69){
          if($trid['dExp'] >= '1500'){
            $updtexp = $mysqlife->query("UPDATE discordlvl SET dExp = '0' WHERE dID = $autorID");
            $updtlvl = $mysqlife->query("UPDATE discordlvl SET dLvl = dLvl+'1' WHERE dID = $autorID");
            for($l = 1; $l <= 4; $l++){
              $message->member->removeRole($lvlrole[$l]);                 
            };
            $updtexp = $mysqlife->query("UPDATE discordlvl SET dmaxExp = '2000' WHERE dID = $autorID");
            $message->member->addRole($lvlrole[3]); 
            $message->guild->channels->get('id', '972169881732657202')->sendMessage("{$message->author} Ваш уровень вырос до **{$lvlupp}**\n");
          }
        }
        if($lvl >= 70 and $lvl < 99){
          if($trid['dExp'] >= '2000'){
            $updtexp = $mysqlife->query("UPDATE discordlvl SET dExp = '0' WHERE dID = $autorID");
            $updtlvl = $mysqlife->query("UPDATE discordlvl SET dLvl = dLvl+'1' WHERE dID = $autorID");
            $message->guild->channels->get('id', '972169881732657202')->sendMessage("{$message->author} Ваш уровень вырос до **{$lvlupp}**\n");
          }
        }
        if ($lvl == 99){
          if($trid['dExp'] >= '2000'){
            $updtexp = $mysqlife->query("UPDATE discordlvl SET dExp = '0' WHERE dID = $autorID");
            $updtlvl = $mysqlife->query("UPDATE discordlvl SET dLvl = dLvl+'1' WHERE dID = $autorID");
            for($l = 1; $l <= 4; $l++){
              $message->member->removeRole($lvlrole[$l]);                 
            };
            $updtexp = $mysqlife->query("UPDATE discordlvl SET dmaxExp = '3000' WHERE dID = $autorID");
            $message->member->addRole($lvlrole[4]); 
            $message->guild->channels->get('id', '972169881732657202')->sendMessage("{$message->author} Ваш уровень вырос до **{$lvlupp}**\n");
          }
        }
      }
      else{
        echo ("{$authName} добавлен в БД\n");
        $newdisc = mysqli_query($mysqlife, "INSERT INTO `discordlvl` (`dID`, `dName`, `dExp`, `dLvl`, `dmaxExp`) VALUES ('{$autorID}', '{$authName}', '0.5', '0', '500')");
      }
    };
  };
});


/*----------Вывод уровня-------------*/
$discord->registerCommand('лвл', function (Message $message, $params) use ($discord, $mysqlife) {
  $discauth = $message->author;
  $autorID = $message->author->id;
  $authName = $message->author->username;
  $authImg = $message->author->avatar;
  $Week = date('j F Y G:i:s');

  $expmin = $mysqlife->query("SELECT dLvl, dExp, dmaxExp FROM discordlvl WHERE dID = $autorID");
  $row = mysqli_fetch_assoc($expmin);
  $opt = $row['dmaxExp'] - $row['dExp'];

  $myEmbededMessage = new Embed($discord);
  $myEmbededMessage->setAuthor("{$authName}", "{$authImg}");
  $myEmbededMessage->setColor('#ec8e22');
  //$myEmbededMessage->setTitle('``⭐ «ШТАБ» ⭐``');
  //$myEmbededMessage->setDescription("**Ваш уровень - {$row['dLvl']}**\n\n**Ваш опыт - {$row['dExp']}**\n\n**До нового уровня вам требуется опыта - {$opt}**\n");
  //$myEmbededMessage->addFieldValues("!help", 'Prints this help message.');
  $myEmbededMessage->setFooter(sprintf('Время отправки %s', "{$Week}"));
  $message->channel->sendMessage('', false, $myEmbededMessage);
});

$discord->run();