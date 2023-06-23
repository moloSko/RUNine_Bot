<?php

include __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/lidrs/nas.php';
include __DIR__ . '/Cimges/imagecreate.php';

require "reddb_2.php";

/*--Основные--*/
use Discord\Discord;
use \Night\NAS;
use Discord\DiscordCommandClient;
use Discord\Parts\Channel\Message;
use Discord\Helpers\Collection;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use Discord\Parts\Interactions\Command\Command;
use Discord\Parts\Interactions\Interaction;
/*--Роли--*/
use Discord\Parts\Guild\Guild;
use Discord\Parts\Guild\Role;
use Discord\Parts\User\Member;
/*------Эмбед Сообщение-------*/
use Discord\Parts\Embed\Embed;
/*-----Вэб--------------*/
use React\Http\Browser;
use React\EventLoop\Factory;
use Psr\Http\Message\ResponseInterface;
use Discord\Builders\MessageBuilder;
/*--Кнопки--*/
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
/*--Меню--*/
use Discord\Builders\Components\SelectMenu;
use Discord\Builders\Components\Option;
/*-----Активность бота--------------*/
use Discord\Parts\User\Activity;
/*--Формы - Modals & Text Inputs--*/
use Discord\Builders\Components\TextInput;
/*-----Время Мута--------------*/
use Carbon\Carbon;

date_default_timezone_set('Europe/Moscow');
$loop = Factory::create();
$browser = new Browser($loop);

try{
  $rcon = new NAS('109.248.200.91', 'rBWFprWIKO6a!!!', 2310, [
    'timeoutSec' => 5,
    'debug' => true
  ]);
} catch (Exception $e){
  echo 'Исключение подключение к RCON: ',  $e->getMessage(), "\n";
}

$discord = new Discord([
  'token' => 'OTc4NjIxNDczNzg4OTUyNjk2.GH55YQ.oSm8q-n9VhEqSnBpVdCp-NzgwSokOnovEk7cII',
  'loadAllMembers' => true,
  'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS,
  'loop' => $loop,
]);

$discord->on('ready', function (Discord $discord) {
  echo "Бот запущен и готов к работе!" . PHP_EOL;

  $discord->getLoop()->addPeriodicTimer(60, function ($timer) use ($discord) {
    try{
      $channel = $discord->getChannel('951372490935001118');
      $channel->messages->fetch('991299771430076467')->then(function (Message $message) use ($discord) {
        global $browser;
        $browser->get('https://api.battlemetrics.com/servers/17389558?include=player')->done(function (ResponseInterface $response) use ($discord, $message) {
          $info = json_decode($response->getBody());
          $Sname = $info->data->attributes->name;
          $stat = $info->data->attributes->status;
          $mstat = [
            "online" => "🟢 Онлайн",
            "offline" => "🔴 Выключен",
            "dead" => "🔴 Выключен",
            "removed" => "🗑️ Удалён",
          ];
          $mstatt = [
            "online" => "🟢",
            "offline" => "🔴",
            "dead" => "🔴",
            "removed" => "🗑️",
          ];
          $namespec = [
            'Танкист - медик' => "Танкист",
            'Танкист - механик' => "Танкист",
            'Лётчик - медик' => "Лётчик",
            'Начальник Штаба' => "НШ",
            'Начальник Личного состава' => "НЛС"
          ];
          $IpServer = $info->data->attributes->ip;
          $maxnumplayers = $info->data->attributes->maxPlayers;
          $builder = MessageBuilder::new();
          $builder->setContent($message->content);
          $monitorEmbededMessage = new Embed($discord);
          $monitorEmbededMessage->setColor('#2c1d9a');
          $monitorEmbededMessage->setTimestamp();
          $monitorEmbededMessage->addFieldValues("ᅠ","`ᅠᅠᅠᅠᅠᅠᅠᅠᅠ    И Н Ф О Р М А Ц И Я   С Е Р В Е Р А  ᅠᅠᅠᅠ  ᅠ  ᅠ`");
          $monitorEmbededMessage->addField(['name' => 'IP адрес:','value' => "{$IpServer}:2302",'inline' => 'true']);

          if($stat == 'online'){
            $monitorEmbededMessage->setTitle("🟢 {$Sname}");
            $searchinfo = R::getRow('select count(1) where exists (select * FROM info)');
            $searchinfo = implode(',', $searchinfo);
            if($searchinfo == '1'){
              $updplayerinfo = R::getAll('SELECT * FROM stats ORDER BY `pLvlSort` DESC');
              $updstatsinfo = R::getAll('SELECT * FROM info LIMIT 1');
              foreach ($updplayerinfo as $player) {
                $playernames[] = $player['pName'];
                $playerrangs[] = $player['pLvl'];
                if($player['Slot'] != 'headlessclient'){
                  if($player['Slot'] == 'Танкист - медик' OR $player['Slot'] == 'Танкист - механик' OR $player['Slot'] == 'Лётчик - медик' OR $player['Slot'] == 'Начальник Штаба' OR $player['Slot'] == 'Начальник Личного состава'){
                    $player['Slot'] = $namespec[$player['Slot']];
                  }
                  $playerslots[] = str_replace('[*]', '🦅', $player['Slot']);
                }
              }
              if (isset($playernames)){
                $playername = implode("\n", $playernames);
                $playerrang = implode("\n", $playerrangs);
                if(isset($playerslots)){
                  $playerslote = implode("\n", $playerslots);
                }else{
                  $playerslote = '0';
                }
                $numplayers = count($playernames); 
              }else{
                $playername = ("Бойцов нет");
                $playerrang = ("Пусто");
                $playerslote = ("Пусто");
                $numplayers = ("0"); 
              }
              foreach ($updstatsinfo as $info){
                $miss = $info['Version'];
                $countcity = $info['CountCity'];
                $maps = $info['City'];
                $mapscount = $info['KT'];
                $timezbds = $info['Time'];
                $timeserver = $info['TimeInGame'];
              }
              if ($countcity == 0){
                $countcity = "**0** городов";
              }else{
                $countcity = "**{$countcity}** город";
              };
              $timeserver = gmdate("H:i", $timeserver);
              $map = "{$maps} ({$mapscount}/3)";
              $timezbd = gmdate("H:i:s", $timezbds);
              if ($timezbds == '0' OR $maps == '0'){
                $map = '[Брифинг]';
              };
              $monitorEmbededMessage->addField(['name' => 'Версия:','value' => $miss,'inline' => 'true']);
              $monitorEmbededMessage->addField(['name' => 'Онлайн:','value' => "{$numplayers}/{$maxnumplayers}",'inline' => 'true']);
              $monitorEmbededMessage->addField(['name' => 'Город:','value' => "{$map}",'inline' => 'true']);
              $monitorEmbededMessage->addField(['name' => 'ЗБД идёт:','value' => $timezbd,'inline' => 'true']);
              $monitorEmbededMessage->addField(['name' => 'Зачищается:','value' => "{$countcity}",'inline' => 'true']);
              $monitorEmbededMessage->addFieldValues("ᅠ","`ᅠᅠᅠᅠᅠᅠᅠᅠᅠᅠᅠ    С П И С О К   Б О Й Ц О В  ᅠᅠᅠᅠᅠᅠᅠᅠᅠᅠᅠᅠ`");
              $monitorEmbededMessage->addField(['name' => 'Позывной:','value' => "```\n{$playername}\n```",'inline' => 'true']);
              $monitorEmbededMessage->addField(['name' => 'Звание:','value' => "```\n{$playerrang}\n```",'inline' => 'true']);
              if ($playerslote != '0'){
                $monitorEmbededMessage->addField(['name' => 'Специализация :','value' => "```\n{$playerslote}\n```",'inline' => 'true']);
              }
              $monitorEmbededMessage->setFooter("🕐 Время на сервере {$timeserver}");
              $builder->addEmbed($monitorEmbededMessage);
              $message->edit($builder);
              $massinfo = array('Version' => $miss, 'CountCity' => $countcity, 'City' => $map, 'Time' => $timezbd, 'TimeInGame' => $timeserver);
              $jmassinfo = json_encode($massinfo);
              $file = 'js/inform.json';
              file_put_contents($file, $jmassinfo);
            }else{
              $file = 'js/inform.json';
              $data = file_get_contents($file);
              $data = json_decode($data, true);
              $updplayerinfo = R::getAll('SELECT * FROM stats ORDER BY `pLvlSort` DESC');
              foreach ($updplayerinfo as $player) {
                $playernames[] = $player['pName'];
                $playerrangs[] = $player['pLvl'];
                if($player['Slot'] != 'headlessclient'){
                  $playerslots[] = $player['Slot'];
                }
              }
              if (isset($playernames)){
                $playername = implode("\n", $playernames);
                $playerrang = implode("\n", $playerrangs);
                if(isset($playerslots)){
                $playerslote = implode("\n", $playerslots);
                }else{
                  $playerslote = '0';
                }
                $numplayers = count($playernames); 
              }else{
                $playername = ("Бойцов нет");
                $playerrang = ("Пусто");
                $playerslote = ("Пусто");
                $numplayers = ("0"); 
              }
              $monitorEmbededMessage->addField(['name' => 'Версия:','value' => $data['Version'],'inline' => 'true']);
              $monitorEmbededMessage->addField(['name' => 'Онлайн:','value' => "{$numplayers}/{$maxnumplayers}",'inline' => 'true']);
              $monitorEmbededMessage->addField(['name' => 'Город:','value' => $data['City'],'inline' => 'true']);
              $monitorEmbededMessage->addField(['name' => 'ЗБД идёт:','value' => $data['Time'],'inline' => 'true']);
              $monitorEmbededMessage->addField(['name' => 'Зачищено:','value' => "**{$data['CountCity']}** город",'inline' => 'true']);
              $monitorEmbededMessage->addFieldValues("ᅠ","`ᅠᅠᅠᅠᅠᅠᅠᅠᅠᅠᅠ    С П И С О К   Б О Й Ц О В  ᅠᅠᅠᅠᅠᅠᅠᅠᅠᅠᅠᅠ`");
              $monitorEmbededMessage->addField(['name' => 'Позывной:','value' => "```\n{$playername}\n```",'inline' => 'true']);
              $monitorEmbededMessage->addField(['name' => 'Звание:','value' => "```\n{$playerrang}\n```",'inline' => 'true']);
              if ($playerslote != '0'){
                $monitorEmbededMessage->addField(['name' => 'Специализация :','value' => "```\n{$playerslote}\n```",'inline' => 'true']);
              }
              $monitorEmbededMessage->setFooter("🕐 Время на сервере {$data['TimeInGame']}");
              $builder->addEmbed($monitorEmbededMessage);
              $message->edit($builder);
            }
            R::close();

            $game = new Activity($discord, ['name' => "{$mstatt[$stat]}{$numplayers}/{$maxnumplayers}", 'type' => Activity::TYPE_WATCHING]);
            $discord->updatePresence($game, true, Activity::STATUS_ONLINE);

          }else{
            $monitorEmbededMessage->setTitle("🔴 {$Sname}");
            $monitorEmbededMessage->addField(['name' => 'Триггер:','value' => "Ожидание",'inline' => 'true']);
            $monitorEmbededMessage->addField(['name' => 'Онлайн:','value' => "0/{$maxnumplayers}",'inline' => 'true']);
            $monitorEmbededMessage->addFieldValues("ᅠ","`ᅠᅠᅠᅠᅠᅠᅠᅠᅠᅠᅠ    С П И С О К   Б О Й Ц О В  ᅠᅠᅠᅠᅠᅠᅠᅠᅠᅠᅠᅠ`");
            $monitorEmbededMessage->addField(['name' => 'ᅠ','value' => "**Нет бойцов на базе**",'inline' => 'false']);
            $builder->addEmbed($monitorEmbededMessage);
            $message->edit($builder);

            $game = new Activity($discord, ['name' => "{$mstatt[$stat]}0/{$maxnumplayers}", 'type' => Activity::TYPE_WATCHING]);
            $discord->updatePresence($game, true, Activity::STATUS_ONLINE);
          };
          
        }, function (Throwable $e) {
          return true;
        });
      });
    } catch (Exception $e){
      echo 'Исключение обновление мониторинга: ',  $e->getMessage(), "\n";
    }
  });

  $discord->on('message', function (Message $message, Discord $discord) {
    try{
      $lvlrole = [
        '1' => '959088058072973322',
        '2' => '957613850407096380',
        '3' => '957613522039210014',
        '4' => '957613715572818012',
      ];
      if ($message->guild_id == '947296697669812274') {
        if ($message->author->bot == null) {
          $autorID = $message->member->user->id;
          $authName = $message->member->user->username;
          $channel = $discord->getChannel('957611344964763728');
          if ($userlvl = R::getCell('SELECT * FROM discordlvl WHERE `dID` = :dID', ['dID' => $autorID])) {
            $userlvl = R::load('discordlvl', $userlvl);
            $userlvl = $userlvl->export();
            $randomint = rand(3, 6);
            R::exec("UPDATE discordlvl set `dExp` = `dExp` + $randomint WHERE `dID` = $autorID");
            $userflvl = $userlvl['dLvl'];
            $userfexp = $userlvl['dExp'];
            $userfmexm = $userlvl['dmaxExp'];
            $uplvl = $userflvl + 1;
            if ($userflvl == 9) {
              if ($userfexp >= $userfmexm) {
                R::exec("UPDATE discordlvl set `dExp` = '0', `dLvl` = `dLvl` + '1', `dmaxExp` = `dmaxExp` + '500' WHERE `dID` = $autorID");
                $message->member->addRole($lvlrole[1]);
                $LvlEmbedMess = new Embed($discord);
                $LvlEmbedMess->setColor('#59c157');
                $LvlEmbedMess->setDescription("{$message->author} теперь твой уровень: **{$uplvl}**\n");
                $channel->sendEmbed($LvlEmbedMess);
              };
            } elseif ($userflvl == 29) {
              if ($userfexp >= $userfmexm) {
                R::exec("UPDATE discordlvl set `dExp` = '0', `dLvl` = `dLvl` + '1', `dmaxExp` = `dmaxExp` + '1000' WHERE `dID` = $autorID");
                $message->member->removeRole($lvlrole[1])->then(function ($remove_roles) use ($message, $lvlrole) {
                  $message->member->addRole($lvlrole[2]);
                  return true;
                });
                $LvlEmbedMess = new Embed($discord);
                $LvlEmbedMess->setColor('#59c157');
                $LvlEmbedMess->setDescription("{$message->author} теперь твой уровень: **{$uplvl}**\n");
                $channel->sendEmbed($LvlEmbedMess);
              };
            } elseif ($userflvl == 69) {
              if ($userfexp >= $userfmexm) {
                R::exec("UPDATE discordlvl set `dExp` = '0', `dLvl` = `dLvl` + '1', `dmaxExp` = `dmaxExp` + '1500' WHERE `dID` = $autorID");
                $message->member->removeRole($lvlrole[2])->then(function ($remove_roles) use ($message, $lvlrole) {
                  $message->member->addRole($lvlrole[3]);
                  return true;
                });
                $LvlEmbedMess = new Embed($discord);
                $LvlEmbedMess->setColor('#59c157');
                $LvlEmbedMess->setDescription("{$message->author} теперь твой уровень: **{$uplvl}**\n");
                $channel->sendEmbed($LvlEmbedMess);
              };
            } elseif ($userflvl == 99) {
              if ($userfexp >= $userfmexm) {
                R::exec("UPDATE discordlvl set `dExp` = '0', `dLvl` = `dLvl` + '1', `dmaxExp` = `dmaxExp` + '2000' WHERE `dID` = $autorID");
                $message->member->removeRole($lvlrole[3])->then(function ($remove_roles) use ($message, $lvlrole) {
                  $message->member->addRole($lvlrole[4]);
                  return true;
                });
                $LvlEmbedMess = new Embed($discord);
                $LvlEmbedMess->setColor('#59c157');
                $LvlEmbedMess->setDescription("{$message->author} теперь твой уровень: **{$uplvl}**\n");
                $channel->sendEmbed($LvlEmbedMess);
              };
            } else {
              if ($userfexp >= $userfmexm) {
                R::exec("UPDATE discordlvl set `dExp` = '0', `dLvl` = `dLvl` + '1', `dmaxExp` = `dmaxExp` + '100' WHERE `dID` = $autorID");
                $LvlEmbedMess = new Embed($discord);
                $LvlEmbedMess->setColor('#59c157');
                $LvlEmbedMess->setDescription("{$message->author} теперь твой уровень: **{$uplvl}**");
                $channel->sendEmbed($LvlEmbedMess);
              };
            };
          } else {
            $res = R::exec("INSERT INTO discordlvl (dID, dName, dExp, dLvl, dmaxExp) VALUES (?,?,?,?,?)", [$autorID, $authName, 1, 0, 500]);
            echo ("Добавлен новый пользователь в систему уровней").PHP_EOL;
          };
          R::close();
        };
      };
    } catch (Exception $e){
      echo 'Исключение накопление уровня: ',  $e->getMessage(), "\n";
    }
  });

  $discord->on('message', function (Message $message, Discord $discord){
    if($message->channel_id == '951373925089181797'){
      if (count($message->attachments) === 0) {
        $message->delete();
      };
    };

  });

  $discord->on('message', function (Message $message, Discord $discord){
    if($message->channel_id == '951373120932028436'){
      $message->react("👍");
      $message->react("👎");
    };
    
    if(in_array($message->channel_id, ['951404948791369748', '951404983289536522', '951405267675922443', '951405127439364116', '952112606250954752', '978227066518536212',  '959106510531731506' , '959106532837064714' , '959106486242508830' , '966705198703710279', '951404478244999208', '959198189444010037', '959198221530439822', '959198301540982784', '978227480303370290', '978227527187316748', '978227579695812628', '978227625417924638'])){
      $message->react("✅");
    };
  });

  $discord->on(Event::INTERACTION_CREATE, function ($interaction, $discord){
    if ($interaction->data->custom_id === "create_ticket") {
      $guild = $interaction->guild;
      $user = $interaction->member;
      $ticketId = rand(13928, 53902);
      $newchannel = $interaction->guild->channels->create([
          'name' => '🔓 Тикет -' . $ticketId,
          'type' => '0',
          'topic' => 'Тикет открыт',
          'parent_id' => '1110093088950587392',
          'nsfw' => false
      ]);
      $interaction->guild->channels->save($newchannel)->then(function ($newchannel) use ($user, $interaction, $discord){
        $newchannel->setPermissions($user, ['send_messages', 'view_channel', 'add_reactions', 'read_message_history', 'attach_files']);
        $interaction->respondWithMessage(MessageBuilder::new()->setContent("Тикет создан - перейдите в канал <#{$newchannel->id}>!"), true);
        $informmessage = new Embed($discord);
        $informmessage->setColor('#B00000');
        $informmessage->setDescription("```⭐ [RU] «Девятка» | Поддержка```\n__Как сообщить о проблеме__\n\n**ФОРМА:**\n```1. Тема вашего обращение - ИДЕЯ | ВОПРОС | ДРУГОЕ.\n2. Описание вашего обращения.```\n\nЕсли вы создали этот тикет по ошибке, нажмите кнопку **\"Закрыть тикет\"**\nВаш тикет в очереди на решение, ожидайте!");
        $builder = MessageBuilder::new();
        $builder->addEmbed($informmessage);
        $actionRow = ActionRow::new();
        $closeticket = Button::new(Button::STYLE_DANGER);
        $closeticket->setLabel('Закрыть тикет');
        $closeticket->setCustomId("close_ticket");
        $actionRow->addComponent($closeticket);
        $builder->addComponent($actionRow);
        $newchannel->sendMessage($builder);
      });
    };
    if($interaction->data->custom_id === "close_ticket"){
      $guild = $interaction->guild;
      $user = $interaction->member;
      $channel = $discord->getChannel($interaction->channel_id);
      $namechan = substr($channel->name, 5);
      $interaction->respondWithMessage(MessageBuilder::new()->setContent("🔒 Тикет закрыть. Рад был помочь!"));
      $channel->setPermissions($user, [''])->then(function($rename_ch) use ($channel, $namechan, $guild){
        $channel->name = "🔒 {$namechan}"->then(function($savechanell) use ($guild, $channel){
          $guild->channels->save($channel);
        });
        return $rename_ch;
      });
    };
    if ($interaction->data->custom_id === "frequent_questions") {
      $iformmenu = MessageBuilder::new();
      $selectmenu = SelectMenu::new();  
      $helpupexp = Option::new('1️⃣ Как получить опыт', 'helpupexp');
      $helpeterm = Option::new('2️⃣ Термины проекта', 'helpeterm');
      $helpconnect = Option::new('3️⃣ Как подключиться', 'helpconnect');
      $helpmod = Option::new('4️⃣ Какие модификации нужны', 'helpmod');
      $helpdopmod = Option::new('5️⃣ Допуска и техника', 'helpdopmod');
      $helpadm = Option::new('6️⃣ Команды для админов', 'helpadm');
      $selectmenu->addOption($helpupexp);
      $selectmenu->addOption($helpeterm);
      $selectmenu->addOption($helpconnect);
      $selectmenu->addOption($helpmod);
      $selectmenu->addOption($helpdopmod);
      $selectmenu->addOption($helpadm);
      $iformmenu->addComponent($selectmenu);
      $interaction->respondWithMessage($iformmenu, true);

      $selectmenu->setListener(function ($interaction, Collection $options){         
        if ($options[0]->getValue() == 'helpupexp') {
          $interaction->respondWithMessage(MessageBuilder::new()->setContent("**1.	Получить опыт можно за:**\n```1.1.	Убийство врагов (НАТО)\n1.2.	Уничтожение техники (любой, в которой вражеская пехота)\n1.3.	КНГ\n1.4.	Перевязку своих товарищей\n1.5.	Поднятие своих товарищей\n1.6.	Подрыв ключевых точек\n1.7.	Каждые 5 часов отыгрыша на сервере\n1.8.	Минирование дорог (инженер)\n1.9.	Время, проведенное в технике (БТВ)\n1.10.	Время, проведенное в технике в воздухе (ВВС)\n1.11.	Рапорты (как КНГ, или если вас отличили в лучшую сторону)\n1.12.	Проведение КМБ новобранцам или курса (только инструктора)\n```\n**2.	Получить бонус (буст) к опыту можно за:**\n```2.1.	Связку SteamID на нашем Discord сервере\n2.2.	Усложнения (стамина, 1 лицо, тряска прицела, иконки бойцов на карте, использование: оптики, карты, GPS)\n2.3.	Пройденные курсы (на слоте специальности курса)\n2.4.	Кол-во бойцов на базе (меньше 10)\n2.5.	Кадетский корпус\n2.6.	Ночное время триггера\n```\n**3.	Потерять опыт можно за:**\n```3.1.	ТК своего (ранение, добивание)\n3.2.	Состояние 300\n3.3.	Убийство гражданского\n3.4.	Рапорты (если вас отличили в худшую сторону)\n3.5.	Систематические нарушения правил проекта\n3.6.	Звание МОС (буст к отнимаемому опыту)\n```\n**4.	Не получите опыт если вы:**\n```4.1.	В группе \"ШТАБ\"\n4.2.	Соло в группе (исключение: БТВ и ВВС и Начальники)\n4.3.	Будучи Новобранцем\n4.4.	На базе «Девятка»\n4.5.	В состоянии 300\n```\n``P.s. Отрицательный опыт вы получите при ЛЮБЫХ вышесказанных условиях, поэтому соблюдайте правила.``"), true);                                         
        }
        if ($options[0]->getValue() == 'helpeterm') {
          $interaction->respondWithMessage(MessageBuilder::new()->setContent("**1.	Военные термины**\n```1.1.	КНГ – Командир Наземной Группы\n1.2.	КСГ – Командир Смежной Группы\n1.3.	ЗамКом – Заместитель КНГ или КСГ (может быть несколько), отвечают за группу, когда основной командир 300 (трехсотый)\n1.4.	НШ – Начальник Штаба - Главный на базе (от \"Лейтенанта\" и выше)\n1.5.	ВриоНШ – Временно исполняющий обязанности Начальника Штаба (любой боец, знающий правила \"Штаба\")\n1.6.	ВВС – Военно-воздушные силы\n1.7.	БТВ – Бронетанковые войска\n1.8.	КМБ – Курс Молодого Бойца (проводится \"Инструктором КМБ\")\n1.9.	ВС РФ – Вооруженные Силы Российской Федерации\n1.10.	ЗБД – Зона Боевых Действий\n1.11.	300 (трехсотый) – Ранен, ожидает перевязку и медицинской помощи\n1.12.	200 (двухсотый) – Убит (потеря крови, казнен, возрождение, утонул)\n1.13.	ЗБД – Зона Боевых Действий\n1.14.	Штрафбат – провинившиеся солдаты. % - не начисляются\n1.15.	Брифинг – Ознакомление, введение в курс дела, проведение инструктажа перед выездом в ЗБД\n1.16.	Дебрифинг – Доклад по выполненной миссии\n1.17.	БК – Боекомплект\n1.18.	ОДКБ – Организация Договора о Коллективной Безопасности. Союзническая организация. Вызывается в \"Дополнительный канал\" при наличии Зевса на сервере\n```\n**2.	РП термины**\n```2.1.	Кемпер – Сидеть в одном месте и поджидать противника\n2.2.	Респавн – Точка/зона появления\n2.3.	АФК – Отошел от компьютера, не активный игрок\n2.4.	КИК – Остуди пыл и заходи снова\n2.5.	БАН – Бесплатный Авиабилет в Норильск\n2.6.	Девятка – База ВС РФ на острове\n```\n**3.	Игровые режимы**\n```3.1.	PvE – Игра против ботов (ИИ)\n3.2.	PvP – Игра против реальных игроков\n3.3.	TvT – Игра команда на команду (\"Тренировочная база\")\n```\n**4.	Составы**\n```4.1.	РС – Рядовой состав\n4.2.	ССС – Сержантский Состав и Старшины\n4.3.	ПП – Прапорщики\n4.4.	МОС – Младший Офицерский Состав\n4.5.	СОС – Старший Офицерский Состав\n4.6.	ВОС – Высший Офицерский Состав\n4.7.	КК – Кадетский Корпус\n```"), true);
        }
        if ($options[0]->getValue() == 'helpconnect') {
          $interaction->respondWithMessage(MessageBuilder::new()->setContent("**Подключение на сервер**\n**```1. Открываем лаунчер ARMA3\n2. В лаунчере переходим во вкладку \"СЕРВЕРЫ\"\n3. Автоматически у вас будет открыт раздел \"ИЗБРАННОЕ\", в нижнем правом углу есть кнопка \"ПРЯМОЕ СОЕДИНЕНИЕ\"\n4. В строку \"Имя сервера или адрес\" вводим: 109.248.200.91\n5. В строку \"Порт\" вводим: 2302 и нажимаем \"Подключиться\"```**"), true);
        }
        if ($options[0]->getValue() == 'helpmod') {
          $interaction->respondWithMessage(MessageBuilder::new()->setContent("**На нашем проекте для входа на сервер не требуются обязательные модификации**\n**```Но для тех кто хочет немного разнообразить - вооружение/снаряжение и т.п:```**\n**CBA**\n<https://steamcommunity.com/sharedfiles/filedetails/?id=450814997>\n\n**\"Девятка\" - Наш мод**\n<https://steamcommunity.com/sharedfiles/filedetails/?id=2822708896>\n\n**CUP Units - Униформа ВС РФ**\n<https://steamcommunity.com/sharedfiles/filedetails/?id=497661914>\n\n**CUP Weapons - Оружие ВС РФ**\n<https://steamcommunity.com/sharedfiles/filedetails/?id=497660133>\n\n**SOUNDDMOD JSRS - Новые звуки на ванилу**\n<https://steamcommunity.com/sharedfiles/filedetails/?id=861133494>\n\n**SOUNDDMOD JSRS CUP - Новые звуки на CUP Weapons**\n<https://steamcommunity.com/sharedfiles/filedetails/?id=1624803912>\n\n**DynaSound 2 - Доп. звуковые эффекты для оружия**\n<https://steamcommunity.com/sharedfiles/filedetails/?id=825181638>\n\n**Enhanced Soundscape - Доп. \"ЭХОвые\" звуковые эффекты**\n<https://steamcommunity.com/sharedfiles/filedetails/?id=825179978>\n\n**Enhanced Map - Новый вид карты**\n\n<https://steamcommunity.com/sharedfiles/filedetails/?id=2467589125>\n\n**Enhanced GPS - Новый вид карты в GPS**\n<https://steamcommunity.com/sharedfiles/filedetails/?id=2480263219>\n\n**Enhanced Movement - Паркур**\n<https://steamcommunity.com/sharedfiles/filedetails/?id=333310405>\n\n**Cinematic Lens Flare - Кинематический свет**\n<https://steamcommunity.com/sharedfiles/filedetails/?id=878502647>\n\n**Enhanced Video Settings - Детальные настройки графики**\n<https://steamcommunity.com/sharedfiles/filedetails/?id=1223309664>\n\n**Blastcore Edited (standalone version) - Новые эффекты взрывов**\n<https://steamcommunity.com/sharedfiles/filedetails/?id=767380317>\n\n**Achilles: - Дополнение к ZEUS**\n<https://steamcommunity.com/sharedfiles/filedetails/?id=723217262>"), true);
        }
        if ($options[0]->getValue() == 'helpdopmod') {
          $interaction->respondWithMessage(MessageBuilder::new()->setContent("**Танкист**\n```1-Допуск: БТР-К Камыш\n2-Допуск: Т-100 Варсук\n3-Допуск: Т-140 \"Ангара\" / ZSU-39 Tigris\n4-Допуск: Zamak MRL / 2S9 Сохор2S9 Сохор```\n**Лётчик**\n```1-Допуск: CH-49 Mohawk / PO-30 Orca (без оружия) / MH-9 Hummingbird / Mi-290 Taru\n2-Допуск: Mi-48 Kajman / PO-30 Orca\n3-Допуск: Y-32 Xian (автотранспорт) / Y-32 Xian (пехота)\n4-Допуск: A-149 Gryphon / To-199 Neophron (CAS) / To-201 Shikra```"), true);
        }
        if ($options[0]->getValue() == 'helpadm') {
          $interaction->respondWithMessage(MessageBuilder::new()->setContent("```⚠️Команды Администраторов⚠️```\n**📜Одобрение(Создание) нового отряда на сервере📜**\n1. Переходим в канал <#952663640836804619>\n2. Вводим команду `` /создать_отряд [название отряда] [тег отряда] [Steam ID - Лидера] [Discord ID - Лидера] [ID из UNITS] [Ссылка на UNITS] ``\n\n**🔇Выдать мут игроку на сервере Discord🔇**\n1. В любом доступном канале пишем `` /тишина [Участник] [Время] `` (если хотите снять мут Время указываем **0**)\n\n**↕️Выдача внеочередного звания/понижение/изменение звания↕️**\n1. Переходим в канал <#983806752426455075>\n2. Пишем команду `` /ранг ``(работает только у администрации) -> выбираем бойца с которым требуется взаимодействие -> __выбираем операцию__ : [Внеочередное/Разжалование/Повышение] -> в случае если вы выбрали __Повышение__ то вам нужно будет заполнить ещё одно поле [звание] (выбрать звание из списка)!\n```Перед изменением звания для бойца, помните что он не должен в этот момент находится на сервере(должен выйти в лобби или из игры)```"), true);
        }
      }, $discord);
    };
  });
});

$namesrole = [
  '0' => "Новобранец",
  '1' => "Рядовой",
  '2' => "Ефрейтор",
  '3' => "Младший Сержант",
  '4' => "Сержант",
  '5' => "Старший Сержант",
  '6' => "Старшина",
  '7' => "Прапорщик",
  '8' => "Старший Прапорщик",
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
  '0' => '959051497226780683',
  '1' => '951724558656892969',
  '2' => '959046714034098209',
  '3' => '951786333951631400',
  '4' => '951786335243485184',
  '5' => '951786337705529344',
  '6' => '959095031665426502',
  '7' => '951786342965190656',
  '8' => '951786344626151424',
  '9' => '951786346521964554',
  '10' => '951786347490856981',
  '11' => '951786348912738334',
  '12' => '952108992186966046',
  '13' => '952108996683239455',
  '14' => '952108999556354088',
  '15' => '952109001074692097',
  '16' => '952109003083767868',
  '17' => '952109283875643402',
  '18' => '952109285280710686',
  '19' => '952109286589354004',
  '20' => '952109288288026634',
];

$imgotr = [
  '3255' => 'https://media.discordapp.net/attachments/1049567414842572881/1084375364681355274/9._.png',
  '210635' => 'https://media.discordapp.net/attachments/1049567414842572881/1084375366862376980/sparta._.png',
  '209550' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1084375365641850920/1._.png',
  '212220' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1084404859207422033/2G2FyTzmIF.png',
  '212214' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1090959285615337593/1._.png',
  '201744' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1084375366543626240/taypan._.png',
  '215072' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1086947732805124116/PicsArt_03-13-09.13.07.png',
  '203036' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1110081019618537583/3.png',
  '215968' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1089474559767482408/pnTmsniega.png',
  '151022' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1114553694424408084/image.png',
  '218446' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1117399186820845588/4ea9a9135a4c81f6.png',
  '205421' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1110497713742692372/Patch-3.png'
];

$imgrank = [
  '0' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575039552360508/1.png',
  '1' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575039552360508/1.png',
  '2' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575040068259991/2.png',
  '3' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575040827445329/3.png',
  '4' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575041490124810/4.png',
  '5' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575042089910402/5.png',
  '6' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575063145336872/6.png',
  '7' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575063376011324/7.png',
  '8' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575063669620836/8.png',
  '9' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575063984197692/9.png',
  '10' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575064298758195/10.png',
  '11' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575064583979048/11.png',
  '12' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575064877584414/12.png',
  '13' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575065217310811/13.png',
  '14' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575065510920343/14.png',
  '15' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575062889480212/15.png',
  '16' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575073803042956/16.png',
  '17' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575074226688030/17.png',
  '18' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575074558017587/18.png',
  '19' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064575073337479198/19.png',
  '20' => 'https://cdn.discordapp.com/attachments/1049567414842572881/1064580807567605820/20.png',
];

$discord->getLoop()->addPeriodicTimer(65, function ($timer) use ($discord, $imgrank, $namesrole, $addrole) {
  try{
    $guild = $discord->guilds->get('id', '947296697669812274');
    $channel = $discord->getChannel('983806752426455075');
    if ($updplayerinfo = R::getAll("SELECT `ID`, `pLvl`, `dOldRank`, `pName`, `DiscID` FROM players WHERE `pDiscord`='1' AND `pLvl`!=`dOldRank`")) {
      foreach ($updplayerinfo as $upi) {
        $rank = $upi['pLvl'];
        $oldrank = $upi['dOldRank'];
        $namememb = $upi['pName'];
        $userdid = $upi['DiscID'];
        $member = $guild->members;
        $memberd = $member[$userdid];
        if ($oldrank < $rank) {
          R::exec("UPDATE players SET `dOldRank` = `pLvl` WHERE `DiscID` = $userdid");
          $UprangEmbededMessage = new Embed($discord);
          $UprangEmbededMessage->setColor('#1d9a32');
          $UprangEmbededMessage->setThumbnail($imgrank[$rank]);
          $UprangEmbededMessage->setDescription("ᅠ\nᅠ\n**{$namememb}** был повышен до звания **{$namesrole[$rank]}**");
          $channel->sendEmbed($UprangEmbededMessage);
          $memberd->removeRole($addrole[$oldrank])->then(function ($addnewrole) use ($memberd, $addrole, $rank, $namememb) {
            $memberd->addRole($addrole[$rank])->then(function ($addnewrole) use ($memberd, $addrole, $rank, $namememb) {
              if ($memberd->roles->get('id', '1024053627893063750')) {
                $memberd->setNickname("⭐ {$namememb}");
              } else {
                $memberd->setNickname($namememb);
              };
              return $addnewrole;
            });
            return $addnewrole;
          });
        } else {
          R::exec("UPDATE players SET `dOldRank` = `pLvl` WHERE `DiscID` = $userdid");
          $DownrangEmbededMessage = new Embed($discord);
          $DownrangEmbededMessage->setColor('#f64747');
          $DownrangEmbededMessage->setThumbnail($imgrank[$rank]);
          $DownrangEmbededMessage->setDescription("ᅠ\nᅠ\n**{$namememb}** был понижен до звания **{$namesrole[$rank]}**");
          $channel->sendEmbed($DownrangEmbededMessage);
          $memberd->removeRole($addrole[$oldrank])->then(function ($addnewrole) use ($memberd, $addrole, $rank, $namememb) {
            $memberd->addRole($addrole[$rank])->then(function ($addnewrole) use ($memberd, $addrole, $rank, $namememb) {
              if ($memberd->roles->get('id', '1024053627893063750')) {
                $memberd->setNickname("⭐ {$namememb}");
              } else {
                $memberd->setNickname($namememb);
              };
              return $addnewrole;
            });
            return $addnewrole;
          });
        };
      };
    };
    R::close();
  } catch (Exception $e){
    echo 'Исключение обновление званий: ',  $e->getMessage(), "\n";
  }
});

$discord->getLoop()->addPeriodicTimer(30, function ($timer) use ($discord) {
  try{
    $channekadet = $discord->getChannel('1051027289497931777');
    $channelog = $discord->getChannel('1080772564898566214');
    $guild = $discord->guilds->get('id', '947296697669812274');
    if ($log_servers = R::getAll('SELECT * FROM log_game WHERE `dCheck` = "0" ORDER BY Date DESC LIMIT 50')) {
      foreach ($log_servers as $log_server) {
        $dataID = $log_server['Date'];
        $type = $log_server['Type'];
        $text = $log_server['Desc'];
        $palyer1 = $log_server['pUID1'];
        $palyer2 = $log_server['pUID2'];
        if (!in_array($type, ['KK IN', 'KK OUT', 'KK'])){
          $logServerEmbeded = new Embed($discord);
          $logServerEmbeded->setColor('#313338');
          $logServerEmbeded->setDescription("**{$log_server['Desc']}**");
          if ($log_server['pUID2'] != ''){
            $logServerEmbeded->setFooter("(PID1 - {$log_server['pUID1']}) / (PID2 - {$log_server['pUID2']})");
          }else{
            $logServerEmbeded->setFooter("(PID1 - {$log_server['pUID1']})");
          };        
          $channelog->sendEmbed($logServerEmbeded)->then(function ($logserv) use ($dataID) {
            R::exec("UPDATE `log_game` SET `dCheck` = '1' WHERE `dCheck` = '0' ORDER BY Date DESC LIMIT 50");
            return $logserv;
          });
        };
        if ($type == 'KK IN'){
          R::exec("UPDATE `log_game` SET `dCheck` = '1' WHERE `dCheck` = '0'");
          $KKEmbededMessage = new Embed($discord);
          $KKEmbededMessage->setColor('#a6caf0');
          $KKEmbededMessage->setThumbnail("https://media.discordapp.net/attachments/1049567414842572881/1073484441743261736/KK.png");
          $KKEmbededMessage->setDescription("\nᅠ\n{$text}");
          $channekadet->sendEmbed($KKEmbededMessage)->then(function ($memblog) use ($palyer2, $guild){
            $Discusers = R::getCell("SELECT `DiscID` FROM players WHERE `pUID` = '$palyer2' LIMIT 1");
            if ($Discusers != '0' OR $Discusers != NULL){
              $member = $guild->members;
              $memberd = $member[$Discusers];
              $memberd->addRole('1051025900268957716');
            };
            return $memblog;
          });
          $logServerEmbeded = new Embed($discord);
          $logServerEmbeded->setColor('#313338');
          $logServerEmbeded->setDescription("**{$log_server['Desc']}**");
          if ($log_server['pUID2'] != ''){
            $logServerEmbeded->setFooter("(PID1 - {$log_server['pUID1']}) / (PID2 - {$log_server['pUID2']})");
          }else{
            $logServerEmbeded->setFooter("(PID1 - {$log_server['pUID1']})");
          };        
          $channelog->sendEmbed($logServerEmbeded);
        };
        if ($type == 'KK OUT'){
          R::exec("UPDATE `log_game` SET `dCheck` = '1' WHERE `dCheck` = '0'");
          $KKEmbededMessage = new Embed($discord);
          $KKEmbededMessage->setColor('#ff0000');
          $KKEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462798144409630/level_down.png");
          $KKEmbededMessage->setDescription("\nᅠ\n{$text}");
          $channekadet->sendEmbed($KKEmbededMessage)->then(function ($memblog) use ($palyer1, $palyer2, $guild){
            if ($palyer2 == ''){
              $Discusers = R::getCell("SELECT `DiscID` FROM players WHERE `pUID` = '$palyer1' LIMIT 1");
              if ($Discusers != '0' OR $Discusers != NULL){
                $member = $guild->members;
                $memberd = $member[$Discusers];
                $memberd->removeRole('1051025900268957716');
              };
            }else{
              $Discusers = R::getCell("SELECT `DiscID` FROM players WHERE `pUID` = '$palyer2' LIMIT 1");
              if ($Discusers != '0' OR $Discusers != NULL){
                $member = $guild->members;
                $memberd = $member[$Discusers];
                $memberd->removeRole('1051025900268957716');
              };
            }
            return $memblog;
          });
          $logServerEmbeded = new Embed($discord);
          $logServerEmbeded->setColor('#313338');
          $logServerEmbeded->setDescription("**{$log_server['Desc']}**");
          if ($log_server['pUID2'] != ''){
            $logServerEmbeded->setFooter("(PID1 - {$log_server['pUID1']}) / (PID2 - {$log_server['pUID2']})");
          }else{
            $logServerEmbeded->setFooter("(PID1 - {$log_server['pUID1']})");
          };        
          $channelog->sendEmbed($logServerEmbeded);
        };
        if ($type == 'KK'){
          R::exec("UPDATE `log_game` SET `dCheck` = '1' WHERE `dCheck` = '0'");
          $KKEmbededMessage = new Embed($discord);
          $KKEmbededMessage->setColor('#1d9a32');
          $KKEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462781300080650/achive.png");
          $KKEmbededMessage->setDescription("\nᅠ\n{$text}");
          $channekadet->sendEmbed($KKEmbededMessage)->then(function ($memblog) use ($palyer1, $guild){
            $Discusers = R::getCell("SELECT `DiscID` FROM players WHERE `pUID` = '$palyer1' LIMIT 1");
            if ($Discusers != '0' OR $Discusers != NULL){
              $member = $guild->members;
              $memberd = $member[$Discusers];
              $memberd->addRole('1083456336370618471')->then(function($kkrole) use ($memberd){
                $memberd->removeRole('1051025900268957716');
                return $kkrole;
              });
            };
            return $memblog;
          });
          $logServerEmbeded = new Embed($discord);
          $logServerEmbeded->setColor('#313338');
          $logServerEmbeded->setDescription("**{$log_server['Desc']}**");
          if ($log_server['pUID2'] != ''){
            $logServerEmbeded->setFooter("(PID1 - {$log_server['pUID1']}) / (PID2 - {$log_server['pUID2']})");
          }else{
            $logServerEmbeded->setFooter("(PID1 - {$log_server['pUID1']})");
          };        
          $channelog->sendEmbed($logServerEmbeded);
        };
      };
    };
    R::close();
  } catch (Exception $e){
    echo 'Исключение логи сервер: ',  $e->getMessage(), "\n";
  }
});

$discord->getLoop()->addPeriodicTimer(80, function ($timer) use ($discord, $namesrole) {
  try{
    $guild = $discord->guilds->get('id', '947296697669812274');
    $channel = $discord->getChannel('951456221322420274');
    $Kchannel = $discord->getChannel('1051027289497931777');
    $allowances = R::getAll('SELECT * FROM `players` WHERE `pDiscord` = 1');
    foreach ($allowances as $allowance) {
      $name = $allowance['pName'];
      $rank = $allowance['pLvl'];
      $dID = $allowance['DiscID'];
      $btv = $allowance['pBTV'];
      $cup = $allowance['pCYP'];
      $kmb = $allowance['pKMB'];
      $skill = $allowance['pSkill'];
      $boss = $allowance['pBoss'];
      $rp = $allowance['pRP'];
      $oldbtv = $allowance['dOldBTV'];
      $oldcup = $allowance['dOldCYP'];
      $oldkmb = $allowance['dOldKMB'];
      $oldskill = $allowance['dOldSkill'];
      $oldboss = $allowance['dOldBoss'];
      $oldrp = $allowance['dOldpRP'];
      if ($oldbtv != $btv) {
        if ($btv[1] == '1' || $btv[3] == '1' || $btv[5] == '1' || $btv[7] == '1') {
          $guild->members->fetch($dID)->then(function (Member $memberd) {
            $memberd->addRole('978556133650333696');
          });
          if ($btv[1] > $oldbtv[1]  && $btv[1] != $oldbtv[1]) {
            $btvEmbededMessage = new Embed($discord);
            $btvEmbededMessage->setColor('#00ff2b');
            $btvEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462843392577586/v_tank_1.png");
            $btvEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** присвоена квалификация на управление **Легкой Гусеничной Технике** \n");
            $channel->sendEmbed($btvEmbededMessage);
            $oldbtv[1] = 1;
            R::exec("UPDATE `players` SET `dOldBTV` = '$oldbtv' WHERE `DiscID` = $dID");
          } elseif ($btv[1] < $oldbtv[1] && $btv[1] != $oldbtv[1]) {
            $btvEmbededMessage = new Embed($discord);
            $btvEmbededMessage->setColor('#ff0000');
            $btvEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462843392577586/v_tank_1.png");
            $btvEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён квалификации на управление **Легкой Гусеничной Технике** \n");
            $channel->sendEmbed($btvEmbededMessage);
            $oldbtv[1] = 0;
            R::exec("UPDATE `players` SET `dOldBTV` = '$oldbtv' WHERE `DiscID` = $dID");
          };
          if ($btv[3] > $oldbtv[3]  && $btv[3] != $oldbtv[3]) {
            $btvEmbededMessage = new Embed($discord);
            $btvEmbededMessage->setColor('#00ff2b');
            $btvEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462839546396712/v_tank_2.png");
            $btvEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** присвоена квалификация на управление **Средней Гусеничной Технике** \n");
            $channel->sendEmbed($btvEmbededMessage);
            $oldbtv[3] = 1;
            R::exec("UPDATE `players` SET `dOldBTV` = '$oldbtv' WHERE `DiscID` = $dID");
          } elseif ($btv[3] < $oldbtv[3] && $btv[3] != $oldbtv[3]) {
            $btvEmbededMessage = new Embed($discord);
            $btvEmbededMessage->setColor('#ff0000');
            $btvEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462839546396712/v_tank_2.png");
            $btvEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён квалификации на управление **Средней Гусеничной Технике** \n");
            $channel->sendEmbed($btvEmbededMessage);
            $oldbtv[3] = 0;
            R::exec("UPDATE `players` SET `dOldBTV` = '$oldbtv' WHERE `DiscID` = $dID");
          };
          if ($btv[5] > $oldbtv[5] && $btv[5] != $oldbtv[5]) {
            $btvEmbededMessage = new Embed($discord);
            $btvEmbededMessage->setColor('#00ff2b');
            $btvEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462853194653706/v_tank_3.png");
            $btvEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** присвоена квалификация на управление **Тяжёлой Гусеничной Технике** \n");
            $channel->sendEmbed($btvEmbededMessage);
            $oldbtv[5] = 1;
            R::exec("UPDATE `players` SET `dOldBTV` = '$oldbtv' WHERE `DiscID` = $dID");
          } elseif ($btv[5] < $oldbtv[5] && $btv[5] != $oldbtv[5]) {
            $btvEmbededMessage = new Embed($discord);
            $btvEmbededMessage->setColor('#ff0000');
            $btvEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462853194653706/v_tank_3.png");
            $btvEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён квалификации на управление **Тяжёлой Гусеничной Технике** \n");
            $channel->sendEmbed($btvEmbededMessage);
            $oldbtv[5] = 0;
            R::exec("UPDATE `players` SET `dOldBTV` = '$oldbtv' WHERE `DiscID` = $dID");
          };
          if ($btv[7] > $oldbtv[7] && $btv[7] != $oldbtv[7]) {
            $btvEmbededMessage = new Embed($discord);
            $btvEmbededMessage->setColor('#00ff2b');
            $btvEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462842457239552/v_arta.png");
            $btvEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** присвоена квалификация на управление **Артилерией** \n");
            $channel->sendEmbed($btvEmbededMessage);
            $oldbtv[7] = 1;
            R::exec("UPDATE `players` SET `dOldBTV` = '$oldbtv' WHERE `DiscID` = $dID");
          } elseif ($btv[7] < $oldbtv[7] && $btv[7] != $oldbtv[7]) {
            $btvEmbededMessage = new Embed($discord);
            $btvEmbededMessage->setColor('#ff0000');
            $btvEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462842457239552/v_arta.png");
            $btvEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён квалификации на управление **Артилерией** \n");
            $channel->sendEmbed($btvEmbededMessage);
            $oldbtv[7] = 0;
            R::exec("UPDATE `players` SET `dOldBTV` = '$oldbtv' WHERE `DiscID` = $dID");
          };
        } elseif ($btv[1] == '0' && $btv[3] == '0' && $btv[5] == '0' && $btv[7] == '0') {
          $guild->members->fetch($dID)->then(function (Member $memberd) {
            $memberd->removeRole('978556133650333696');
          });
          if ($btv[1] != $oldbtv[1]) {
            $btvEmbededMessage = new Embed($discord);
            $btvEmbededMessage->setColor('#ff0000');
            $btvEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462843392577586/v_tank_1.png");
            $btvEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён квалификации на управление **Легкой Гусеничной Технике** \n");
            $channel->sendEmbed($btvEmbededMessage);
            $oldbtv[1] = 0;
            R::exec("UPDATE `players` SET `dOldBTV` = '$oldbtv' WHERE `DiscID` = $dID");
          };
          if ($btv[3] != $oldbtv[3]) {
            $btvEmbededMessage = new Embed($discord);
            $btvEmbededMessage->setColor('#ff0000');
            $btvEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462839546396712/v_tank_2.png");
            $btvEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён квалификации на управление **Средней Гусеничной Технике** \n");
            $channel->sendEmbed($btvEmbededMessage);
            $oldbtv[3] = 0;
            R::exec("UPDATE `players` SET `dOldBTV` = '$oldbtv' WHERE `DiscID` = $dID");
          };
          if ($btv[5] != $oldbtv[5]) {
            $btvEmbededMessage = new Embed($discord);
            $btvEmbededMessage->setColor('#ff0000');
            $btvEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462853194653706/v_tank_3.png");
            $btvEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён квалификации на управление **Тяжёлой Гусеничной Технике** \n");
            $channel->sendEmbed($btvEmbededMessage);
            $oldbtv[5] = 0;
            R::exec("UPDATE `players` SET `dOldBTV` = '$oldbtv' WHERE `DiscID` = $dID");
          };
          if ($btv[7] != $oldbtv[7]) {
            $btvEmbededMessage = new Embed($discord);
            $btvEmbededMessage->setColor('#ff0000');
            $btvEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462842457239552/v_arta.png");
            $btvEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён квалификации на управление **Артилерией** \n");
            $channel->sendEmbed($btvEmbededMessage);
            $oldbtv[7] = 0;
            R::exec("UPDATE `players` SET `dOldBTV` = '$oldbtv' WHERE `DiscID` = $dID");
          };
        };
      };
      if ($oldcup != $cup) {
        if ($cup[1] == '1' || $cup[3] == '1' || $cup[5] == '1' || $cup[7] == '1') {
          $guild->members->fetch($dID)->then(function (Member $memberd) {
            $memberd->addRole('978556132329148476');
          });
          if ($cup[1] > $oldcup[1]  && $cup[1] != $oldcup[1]) {
            $cupEmbededMessage = new Embed($discord);
            $cupEmbededMessage->setColor('#00ffff');
            $cupEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064130635914027128/p_heli.png");
            $cupEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** получил лицензию на управление **Транспортными Вертолётами** \n");
            $channel->sendEmbed($cupEmbededMessage);
            $oldcup[1] = 1;
            R::exec("UPDATE `players` SET `dOldCYP` = '$oldcup' WHERE `DiscID` = $dID");
          } elseif ($cup[1] < $oldcup[1] && $cup[1] != $oldcup[1]) {
            $cupEmbededMessage = new Embed($discord);
            $cupEmbededMessage->setColor('#ff0000');
            $cupEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064130635914027128/p_heli.png");
            $cupEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён лицензии на управление **Транспортными Вертолётами** \n");
            $channel->sendEmbed($cupEmbededMessage);
            $oldcup[1] = 0;
            R::exec("UPDATE `players` SET `dOldCYP` = '$oldcup' WHERE `DiscID` = $dID");
          };
          if ($cup[3] > $oldcup[3]  && $cup[3] != $oldcup[3]) {
            $cupEmbededMessage = new Embed($discord);
            $cupEmbededMessage->setColor('#00ffff');
            $cupEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064130635914027128/p_heli.png");
            $cupEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** получил лицензию на управление **Боевыми Вертолётами** \n");
            $channel->sendEmbed($cupEmbededMessage);
            $oldcup[3] = 1;
            R::exec("UPDATE `players` SET `dOldCYP` = '$oldcup' WHERE `DiscID` = $dID");
          } elseif ($cup[3] < $oldcup[3] && $cup[3] != $oldcup[3]) {
            $cupEmbededMessage = new Embed($discord);
            $cupEmbededMessage->setColor('#ff0000');
            $cupEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064130635914027128/p_heli.png");
            $cupEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён лицензии на управление **Боевыми Вертолётами** \n");
            $channel->sendEmbed($cupEmbededMessage);
            $oldcup[3] = 0;
            R::exec("UPDATE `players` SET `dOldCYP` = '$oldcup' WHERE `DiscID` = $dID");
          };
          if ($cup[5] > $oldcup[5]  && $cup[5] != $oldcup[5]) {
            $cupEmbededMessage = new Embed($discord);
            $cupEmbededMessage->setColor('#00ffff');
            $cupEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064130625189187584/p_cargo.png");
            $cupEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** получил лицензию на управление **Транспортными Самолётами** \n");
            $channel->sendEmbed($cupEmbededMessage);
            $oldcup[5] = 1;
            R::exec("UPDATE `players` SET `dOldCYP` = '$oldcup' WHERE `DiscID` = $dID");
          } elseif ($cup[5] < $oldcup[5] && $cup[5] != $oldcup[5]) {
            $cupEmbededMessage = new Embed($discord);
            $cupEmbededMessage->setColor('#ff0000');
            $cupEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064130625189187584/p_cargo.png");
            $cupEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён лицензии на управление **Транспортными Самолётами** \n");
            $channel->sendEmbed($cupEmbededMessage);
            $oldcup[5] = 0;
            R::exec("UPDATE `players` SET `dOldCYP` = '$oldcup' WHERE `DiscID` = $dID");
          };
          if ($cup[7] > $oldcup[7]  && $cup[7] != $oldcup[7]) {
            $cupEmbededMessage = new Embed($discord);
            $cupEmbededMessage->setColor('#00ffff');
            $cupEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064130624958517258/p_fight.png");
            $cupEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** получил лицензию на управление **Боевыми Самолётами** \n");
            $channel->sendEmbed($cupEmbededMessage);
            $oldcup[7] = 1;
            R::exec("UPDATE `players` SET `dOldCYP` = '$oldcup' WHERE `DiscID` = $dID");
          } elseif ($cup[7] < $oldcup[7] && $cup[7] != $oldcup[7]) {
            $cupEmbededMessage = new Embed($discord);
            $cupEmbededMessage->setColor('#ff0000');
            $cupEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064130624958517258/p_fight.png");
            $cupEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён лицензии на управление **Боевыми Самолётами** \n");
            $channel->sendEmbed($cupEmbededMessage);
            $oldcup[7] = 0;
            R::exec("UPDATE `players` SET `dOldCYP` = '$oldcup' WHERE `DiscID` = $dID");
          };
        } elseif ($cup[1] == '0' && $cup[3] == '0' && $cup[5] == '0' && $cup[7] == '0') {
          $guild->members->fetch($dID)->then(function (Member $memberd) {
            $memberd->removeRole('978556132329148476');
          });
          if ($cup[1] != $oldcup[1]) {
            $cupEmbededMessage = new Embed($discord);
            $cupEmbededMessage->setColor('#ff0000');
            $cupEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064130635914027128/p_heli.png");
            $cupEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён лицензии на управление **Транспортными Вертолётами** \n");
            $channel->sendEmbed($cupEmbededMessage);
            $oldcup[1] = 0;
            R::exec("UPDATE `players` SET `dOldCYP` = '$oldcup' WHERE `DiscID` = $dID");
          };
          if ($cup[3] != $oldcup[3]) {
            $cupEmbededMessage = new Embed($discord);
            $cupEmbededMessage->setColor('#ff0000');
            $cupEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064130635914027128/p_heli.png");
            $cupEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён лицензии на управление **Боевыми Вертолётами** \n");
            $channel->sendEmbed($cupEmbededMessage);
            $oldcup[3] = 0;
            R::exec("UPDATE `players` SET `dOldCYP` = '$oldcup' WHERE `DiscID` = $dID");
          };
          if ($cup[5] != $oldcup[5]) {
            $cupEmbededMessage = new Embed($discord);
            $cupEmbededMessage->setColor('#ff0000');
            $cupEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064130625189187584/p_cargo.png");
            $cupEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён лицензии на управление **Транспортными Самолётами** \n");
            $channel->sendEmbed($cupEmbededMessage);
            $oldcup[5] = 0;
            R::exec("UPDATE `players` SET `dOldCYP` = '$oldcup' WHERE `DiscID` = $dID");
          };
          if ($cup[7] != $oldcup[7]) {
            $cupEmbededMessage = new Embed($discord);
            $cupEmbededMessage->setColor('#ff0000');
            $cupEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064130624958517258/p_fight.png");
            $cupEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён лицензии на управление **Боевыми Самолётами** \n");
            $channel->sendEmbed($cupEmbededMessage);
            $oldcup[7] = 0;
            R::exec("UPDATE `players` SET `dOldCYP` = '$oldcup' WHERE `DiscID` = $dID");
          };
        };
      };
      if ($oldskill != $skill) {
        if ($skill[1] == '1' || $skill[3] == '1' || $skill[5] == '1' || $skill[7] == '1') {
          if ($skill[1] != $oldskill[1]) {
            if ($skill[1] > $oldskill[1]) {
              $skillEmbededMessage = new Embed($discord);
              $skillEmbededMessage->setColor('#ffffff');
              $skillEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462797863387166/officer.png");
              $skillEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** прошёл курсы **Офицера** \n");
              $channel->sendEmbed($skillEmbededMessage);
              $oldskill[1] = 1;
              R::exec("UPDATE `players` SET `dOldSkill` = '$oldskill' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978558110954319892');
              });
            } elseif ($skill[1] < $oldskill[1]) {
              $skillEmbededMessage = new Embed($discord);
              $skillEmbededMessage->setColor('#ff0000');
              $skillEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462797863387166/officer.png");
              $skillEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят курс **Офицера** \n");
              $channel->sendEmbed($skillEmbededMessage);
              $oldskill[1] = 0;
              R::exec("UPDATE `players` SET `dOldSkill` = '$oldskill' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978558110954319892');
              });
            }
          };
          if ($skill[3] != $oldskill[3]) {
            if ($skill[3] > $oldskill[3]) {
              $skillEmbededMessage = new Embed($discord);
              $skillEmbededMessage->setColor('#ffffff');
              $skillEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462782352871504/engineer.png");
              $skillEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** прошёл курсы **Инженера** \n");
              $channel->sendEmbed($skillEmbededMessage);
              $oldskill[3] = 1;
              R::exec("UPDATE `players` SET `dOldSkill` = '$oldskill' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978558757556604928');
              });
            } elseif ($skill[3] < $oldskill[3]) {
              $skillEmbededMessage = new Embed($discord);
              $skillEmbededMessage->setColor('#ff0000');
              $skillEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462782352871504/engineer.png");
              $skillEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят курс **Инженера** \n");
              $channel->sendEmbed($skillEmbededMessage);
              $oldskill[3] = 0;
              R::exec("UPDATE `players` SET `dOldSkill` = '$oldskill' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978558757556604928');
              });
            }
          };
          if ($skill[5] != $oldskill[5]) {
            if ($skill[5] > $oldskill[5]) {
              $skillEmbededMessage = new Embed($discord);
              $skillEmbededMessage->setColor('#ffffff');
              $skillEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462841559658546/sniper.png");
              $skillEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** прошёл курсы **Снайпера** \n");
              $channel->sendEmbed($skillEmbededMessage);
              $oldskill[5] = 1;
              R::exec("UPDATE `players` SET `dOldSkill` = '$oldskill' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978558755832741908');
              });
            } elseif ($skill[5] < $oldskill[5]) {
              $skillEmbededMessage = new Embed($discord);
              $skillEmbededMessage->setColor('#ff0000');
              $skillEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462841559658546/sniper.png");
              $skillEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят курс **Снайпера** \n");
              $channel->sendEmbed($skillEmbededMessage);
              $oldskill[5] = 0;
              R::exec("UPDATE `players` SET `dOldSkill` = '$oldskill' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978558755832741908');
              });
            }
          };
          if ($skill[7] != $oldskill[7]) {
            if ($skill[7] > $oldskill[7]) {
              $skillEmbededMessage = new Embed($discord);
              $skillEmbededMessage->setColor('#ffffff');
              $skillEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462798622556230/medic.png");
              $skillEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** прошёл курсы **Санитара** \n");
              $channel->sendEmbed($skillEmbededMessage);
              $oldskill[7] = 1;
              R::exec("UPDATE `players` SET `dOldSkill` = '$oldskill' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978558759028789258');
              });
            } elseif ($skill[7] < $oldskill[7]) {
              $skillEmbededMessage = new Embed($discord);
              $skillEmbededMessage->setColor('#ff0000');
              $skillEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462798622556230/medic.png");
              $skillEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят курс **Санитара** \n");
              $channel->sendEmbed($skillEmbededMessage);
              $oldskill[7] = 0;
              R::exec("UPDATE `players` SET `dOldSkill` = '$oldskill' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978558759028789258');
              });
            }
          };
        } elseif ($skill[1] == '0' && $skill[3] == '0' && $skill[5] == '0' && $skill[7] == '0') {
          if ($oldskill[1] != $skill[1]) {
            $skillEmbededMessage = new Embed($discord);
            $skillEmbededMessage->setColor('#ff0000');
            $skillEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462797863387166/officer.png");
            $skillEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят курс **Офицера** \n");
            $channel->sendEmbed($skillEmbededMessage);
            $oldskill[1] = 0;
            R::exec("UPDATE `players` SET `dOldSkill` = '$oldskill' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978558110954319892');
            });
          };
          if ($oldskill[3] != $skill[3]) {
            $skillEmbededMessage = new Embed($discord);
            $skillEmbededMessage->setColor('#ff0000');
            $skillEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462782352871504/engineer.png");
            $skillEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят курс **Инженера** \n");
            $channel->sendEmbed($skillEmbededMessage);
            $oldskill[3] = 0;
            R::exec("UPDATE `players` SET `dOldSkill` = '$oldskill' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978558757556604928');
            });
          };
          if ($oldskill[5] != $skill[5]) {
            $skillEmbededMessage = new Embed($discord);
            $skillEmbededMessage->setColor('#ff0000');
            $skillEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462841559658546/sniper.png");
            $skillEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят курс **Снайпера** \n");
            $channel->sendEmbed($skillEmbededMessage);
            $oldskill[5] = 0;
            R::exec("UPDATE `players` SET `dOldSkill` = '$oldskill' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978558755832741908');
            });
          };
          if ($oldskill[7] != $skill[7]) {
            $skillEmbededMessage = new Embed($discord);
            $skillEmbededMessage->setColor('#ff0000');
            $skillEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462798622556230/medic.png");
            $skillEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят курс **Санитара** \n");
            $channel->sendEmbed($skillEmbededMessage);
            $oldskill[7] = 0;
            R::exec("UPDATE `players` SET `dOldSkill` = '$oldskill' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978558759028789258');
            });
          };
        };
      };
      if ($oldkmb != $kmb) {
        if ($kmb[1] == '1' || $kmb[3] == '1' || $kmb[5] == '1' || $kmb[7] == '1' || $kmb[9] == '1' || $kmb[11] == '1' || $kmb[13] == '1' || $kmb[15] == '1') {
          $guild->members->fetch($dID)->then(function (Member $memberd) {
            $memberd->addRole('978554368083259422');
          });
          if ($kmb[1] != $oldkmb[1]) {
            if ($kmb[1] > $oldkmb[1]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#06b495');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462840456560650/pilot_heli.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** назначен на должность **Инструктор Лётчиков** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978555427077898240');
              });
              $oldkmb[1] = 1;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            } elseif ($kmb[1] < $oldkmb[1]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#ff0000');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462840456560650/pilot_heli.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор Лётчиков** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978555427077898240');
              });
              $oldkmb[1] = 0;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            };
          };
          if ($kmb[3] != $oldkmb[3]) {
            if ($kmb[3] > $oldkmb[3]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#06b495');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462782109597756/bomb.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** назначен на должность **Инструктор Танкистов** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978555428222930964');
              });
              $oldkmb[3] = 1;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            } elseif ($kmb[3] < $oldkmb[3]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#ff0000');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462782109597756/bomb.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор Танкистов** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978555428222930964');
              });
              $oldkmb[3] = 0;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            };
          };
          if ($kmb[5] != $oldkmb[5]) {
            if ($kmb[5] > $oldkmb[5]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#06b495');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462781023277106/300.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** назначен на должность **Инструктор РП** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978555428713668648');
              });
              $oldkmb[5] = 1;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            } elseif ($kmb[5] < $oldkmb[5]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#ff0000');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462781023277106/300.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор РП** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978555428713668648');
              });
              $oldkmb[5] = 0;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            };
          };
          if ($kmb[7] != $oldkmb[7]) {
            if ($kmb[7] > $oldkmb[7]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#06b495');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462781551759380/ammo.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** назначен на должность **Инструктор Новобранцев** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978556123974078474');
              });
              $oldkmb[7] = 1;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            } elseif ($kmb[7] < $oldkmb[7]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#ff0000');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462781551759380/ammo.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор Новобранцев** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978556123974078474');
              });
              $oldkmb[7] = 0;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            };
          };
          if ($kmb[9] != $oldkmb[9]) {
            if ($kmb[9] > $oldkmb[9]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#06b495');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462797863387166/officer.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** назначен на должность **Инструктор Офицеров** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978556125446291487');
              });
              $oldkmb[9] = 1;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            } elseif ($kmb[9] < $oldkmb[9]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#ff0000');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462797863387166/officer.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор Офицеров** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978556125446291487');
              });
              $oldkmb[9] = 0;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            };
          };
          if ($kmb[11] != $oldkmb[11]) {
            if ($kmb[11] > $oldkmb[11]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#06b495');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462841953931355/tir.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** назначен на должность **Инструктор Снайперов** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978556126473883648');
              });
              $oldkmb[11] = 1;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            } elseif ($kmb[11] < $oldkmb[11]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#ff0000');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462841953931355/tir.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор Снайперов** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978556126473883648');
              });
              $oldkmb[11] = 0;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            };
          };
          if ($kmb[13] != $oldkmb[13]) {
            if ($kmb[13] > $oldkmb[13]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#06b495');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462782352871504/engineer.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** назначен на должность **Инструктор Инженеров** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978556128042577931');
              });
              $oldkmb[13] = 1;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            } elseif ($kmb[13] < $oldkmb[13]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#ff0000');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462782352871504/engineer.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор Инженеров** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978556128042577931');
              });
              $oldkmb[13] = 0;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            };
          };
          if ($kmb[15] != $oldkmb[15]) {
            if ($kmb[15] > $oldkmb[15]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#06b495');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462798622556230/medic.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** назначен на должность **Инструктор Санитаров** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978556129493782588');
              });
              $oldkmb[15] = 1;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            } elseif ($kmb[15] < $oldkmb[15]) {
              $kmbEmbededMessage = new Embed($discord);
              $kmbEmbededMessage->setColor('#ff0000');
              $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462798622556230/medic.png");
              $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор Санитаров** \n");
              $channel->sendEmbed($kmbEmbededMessage);
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978556129493782588');
              });
              $oldkmb[15] = 0;
              R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            };
          };
        } elseif ($kmb[1] == '0' && $kmb[3] == '0' && $kmb[5] == '0' && $kmb[7] == '0' && $kmb[9] == '0' && $kmb[11] == '0' && $kmb[13] == '0' && $kmb[15] == '0') {
          $guild->members->fetch($dID)->then(function (Member $memberd) {
            $memberd->removeRole('978554368083259422');
          });
          if ($kmb[1] != $oldkmb[1]) {
            $kmbEmbededMessage = new Embed($discord);
            $kmbEmbededMessage->setColor('#ff0000');
            $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462840456560650/pilot_heli.png");
            $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор Лётчиков** \n");
            $channel->sendEmbed($kmbEmbededMessage);
            $oldkmb[1] = 0;
            R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978555427077898240');
            });
          };
          if ($kmb[3] != $oldkmb[3]) {
            $kmbEmbededMessage = new Embed($discord);
            $kmbEmbededMessage->setColor('#ff0000');
            $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462782109597756/bomb.png");
            $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор Танкистов** \n");
            $channel->sendEmbed($kmbEmbededMessage);
            $oldkmb[3] = 0;
            R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978555428222930964');
            });
          };
          if ($kmb[5] != $oldkmb[5]) {
            $kmbEmbededMessage = new Embed($discord);
            $kmbEmbededMessage->setColor('#ff0000');
            $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462781023277106/300.png");
            $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор РП** \n");
            $channel->sendEmbed($kmbEmbededMessage);
            $oldkmb[5] = 0;
            R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978555428713668648');
            });
          };
          if ($kmb[7] != $oldkmb[7]) {
            $kmbEmbededMessage = new Embed($discord);
            $kmbEmbededMessage->setColor('#ff0000');
            $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462781551759380/ammo.png");
            $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор Новобранцев** \n");
            $channel->sendEmbed($kmbEmbededMessage);
            $oldkmb[7] = 0;
            R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978556123974078474');
            });
          };
          if ($kmb[9] != $oldkmb[9]) {
            $kmbEmbededMessage = new Embed($discord);
            $kmbEmbededMessage->setColor('#ff0000');
            $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462797863387166/officer.png");
            $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор Офицеров** \n");
            $channel->sendEmbed($kmbEmbededMessage);
            $oldkmb[9] = 0;
            R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978556125446291487');
            });
          };
          if ($kmb[11] != $oldkmb[11]) {
            $kmbEmbededMessage = new Embed($discord);
            $kmbEmbededMessage->setColor('#ff0000');
            $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462841953931355/tir.png");
            $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор Снайперов** \n");
            $channel->sendEmbed($kmbEmbededMessage);
            $oldkmb[11] = 0;
            R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978556126473883648');
            });
          };
          if ($kmb[13] != $oldkmb[13]) {
            $kmbEmbededMessage = new Embed($discord);
            $kmbEmbededMessage->setColor('#ff0000');
            $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462782352871504/engineer.png");
            $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор Инженеров** \n");
            $channel->sendEmbed($kmbEmbededMessage);
            $oldkmb[13] = 0;
            R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978556128042577931');
            });
          };
          if ($kmb[15] != $oldkmb[15]) {
            $kmbEmbededMessage = new Embed($discord);
            $kmbEmbededMessage->setColor('#ff0000');
            $kmbEmbededMessage->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1064462798622556230/medic.png");
            $kmbEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** снят с должности **Инструктор Санитаров** \n");
            $channel->sendEmbed($kmbEmbededMessage);
            $oldkmb[15] = 0;
            R::exec("UPDATE `players` SET `dOldKMB` = '$oldkmb' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978556129493782588');
            });
          };
        };
      };
      if ($boss != $oldboss) {
        if ($boss[1] != $oldboss[1]) {
          if($boss[1] == '0'){
            if($oldboss[1] == '1'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ff0000');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** снят с должности <@&978542366568878080>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[1] = 0;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978542366568878080');
              });
            }elseif($oldboss[1] == '2'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ff0000');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** снят с должности <@&978555421365272626>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[1] = 0;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978555421365272626');
              });
            }
          }elseif ($boss[1] == '1'){
            if($boss[1] > $oldboss[1]){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ffff00');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** назначен на должность <@&978542366568878080>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[1] = 1;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978542365281222666')->then(function($rolesnach) use ($memberd){
                  $memberd->addRole('978542366568878080');
                  return $rolesnach;
                });
              });
            }elseif($boss[1] < $oldboss[1]){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ffff00');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** повышен в должности до <@&978542366568878080>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[1] = 1;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978555421365272626')->then(function ($addnewrole) use ($memberd) {
                  $memberd->addRole('978542366568878080');
                  return $addnewrole;
                });
              });
            }
          }elseif($boss[1] == '2'){
            if($boss[1] > $oldboss[1] && $oldboss[1] == '0'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ffff00');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** назначен на должность <@&978555421365272626>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[1] = 2;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978542365281222666')->then(function($rolesnach) use ($memberd){
                  $memberd->addRole('978555421365272626');
                  return $rolesnach;
                });
              });
            }elseif($boss[1] > $oldboss[1] && $oldboss[1] == '1'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ff0000');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** переназначен на <@&978555421365272626>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[1] = 2;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978542366568878080')->then(function ($addnewrole) use ($memberd) {
                  $memberd->addRole('978555421365272626');
                  return $addnewrole;
                });
              });
            }
          };
        };
        if ($boss[3] != $oldboss[3]) {
          if($boss[3] == '0'){
            if($oldboss[3] == '1'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ff0000');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** снят с должности <@&978554362504839168>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[3] = 0;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978554362504839168');
              });
            }elseif($oldboss[3] == '2'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ff0000');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** снят с должности <@&978555425337270332>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[3] = 0;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978555425337270332');
              });
            }
          }elseif ($boss[3] == '1'){
            if($boss[3] > $oldboss[3]){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ffff00');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** назначен на должность <@&978554362504839168>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[3] = 1;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978542365281222666')->then(function($rolesnach) use ($memberd){
                  $memberd->addRole('978554362504839168');
                  return $rolesnach;
                });
              });
            }elseif($boss[3] < $oldboss[3]){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ffff00');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** повышен в должности до <@&978554362504839168>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[3] = 1;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978555425337270332')->then(function ($addnewrole) use ($memberd) {
                  $memberd->addRole('978554362504839168');
                  return $addnewrole;
                });
              });
            }
          }elseif($boss[3] == '2'){
            if($boss[3] > $oldboss[3] && $oldboss[3] == '0'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ffff00');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** назначен на должность <@&978555425337270332>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[3] = 2;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978542365281222666')->then(function($rolesnach) use ($memberd){
                  $memberd->addRole('978555425337270332');
                  return $rolesnach;
                });
              });
            }elseif($boss[3] > $oldboss[3] && $oldboss[3] == '1'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ff0000');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** переназначен на <@&978555425337270332>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[3] = 2;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978554362504839168')->then(function ($addnewrole) use ($memberd) {
                  $memberd->addRole('978555425337270332');
                  return $addnewrole;
                });
              });
            }
          };
        };
        if ($boss[5] != $oldboss[5]) {
          if($boss[5] == '0'){
            if($oldboss[5] == '1'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ff0000');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** снят с должности <@&978554363763097600>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[5] = 0;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978554363763097600');
              });
            }elseif($oldboss[5] == '2'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ff0000');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** снят с должности <@&978555425580515388>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[5] = 0;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978555425580515388');
              });
            }
          }elseif ($boss[5] == '1'){
            if($boss[5] > $oldboss[5]){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ffff00');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** назначен на должность <@&978554363763097600>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[5] = 1;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978542365281222666')->then(function($rolesnach) use ($memberd){
                  $memberd->addRole('978554363763097600');
                  return $rolesnach;
                });
              });
            }elseif($boss[5] < $oldboss[5]){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ffff00');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** повышен в должности до <@&978554363763097600>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[5] = 1;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978555425580515388')->then(function ($addnewrole) use ($memberd) {
                  $memberd->addRole('978554363763097600');
                  return $addnewrole;
                });
              });
            }
          }elseif($boss[5] == '2'){
            if($boss[5] > $oldboss[5] && $oldboss[5] == '0'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ffff00');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** назначен на должность <@&978555425580515388>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[5] = 2;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978542365281222666')->then(function($rolesnach) use ($memberd){
                  $memberd->addRole('978555425580515388');
                  return $rolesnach;
                });
              });
            }elseif($boss[5] > $oldboss[5] && $oldboss[5] == '1'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ff0000');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** переназначен на <@&978555425580515388>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[5] = 2;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978554363763097600')->then(function ($addnewrole) use ($memberd) {
                  $memberd->addRole('978555425580515388');
                  return $addnewrole;
                });
              });
            }
          };
        };
        if ($boss[7] != $oldboss[7]) {
          if($boss[7] == '0'){
            if($oldboss[7] == '1'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ff0000');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** снят с должности <@&978554366128693288>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[7] = 0;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978554366128693288');
              });
            }elseif($oldboss[7] == '2'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ff0000');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** снят с должности <@&978555426591342613>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[7] = 0;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978555426591342613');
              });
            }
          }elseif ($boss[7] == '1'){
            if($boss[7] > $oldboss[7]){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ffff00');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** назначен на должность <@&978554366128693288>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[7] = 1;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978542365281222666')->then(function($rolesnach) use ($memberd){
                  $memberd->addRole('978554366128693288');
                  return $rolesnach;
                });
              });
            }elseif($boss[7] < $oldboss[7]){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ffff00');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** повышен в должности до <@&978554366128693288>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[7] = 1;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978555426591342613')->then(function ($addnewrole) use ($memberd) {
                  $memberd->addRole('978554366128693288');
                  return $addnewrole;
                });
              });
            }
          }elseif($boss[7] == '2'){
            if($boss[7] > $oldboss[7] && $oldboss[7] == '0'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ffff00');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** назначен на должность <@&978555426591342613>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[7] = 2;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->addRole('978542365281222666')->then(function($rolesnach) use ($memberd){
                  $memberd->addRole('978555426591342613');
                  return $rolesnach;
                });
              });
            }elseif($boss[7] > $oldboss[7] && $oldboss[7] == '1'){
              $bossEmbededMessage = new Embed($discord);
              $bossEmbededMessage->setColor('#ff0000');
              $bossEmbededMessage->setDescription("\nБоец **{$namesrole[$rank]} {$name}** переназначен на <@&978555426591342613>\n");
              $channel->sendEmbed($bossEmbededMessage);
              $oldboss[7] = 2;
              R::exec("UPDATE `players` SET `dOldBoss` = '$oldboss' WHERE `DiscID` = $dID");
              $guild->members->fetch($dID)->then(function (Member $memberd) {
                $memberd->removeRole('978554366128693288')->then(function ($addnewrole) use ($memberd) {
                  $memberd->addRole('978555426591342613');
                  return $addnewrole;
                });
              });
            }
          };
        };
        if ($boss == '[0,0,0,0]'){
          $guild->members->fetch($dID)->then(function (Member $memberd) {
            $memberd->removeRole('978542365281222666');
          });
        };
      };
      if ($rp != $oldrp){
        if ($rp[1] != $oldrp[1]) {
          if ($rp[1] > $oldrp[1]){
            $rpEmbededMessage = new Embed($discord);
            $rpEmbededMessage->setColor('#ffffff');
            $rpEmbededMessage->setThumbnail("https://media.discordapp.net/attachments/1049567414842572881/1074358371576205402/zeus.png");
            $rpEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** получил доступ на **Зевс** \n");
            $channel->sendEmbed($rpEmbededMessage);
            $rp[1] = 1;
            R::exec("UPDATE `players` SET `dOldpRP` = '$rp' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->addRole('978556134703136789');
            });
          }elseif ($rp[1] < $oldrp[1]){
            $rpEmbededMessage = new Embed($discord);
            $rpEmbededMessage->setColor('#ff0000');
            $rpEmbededMessage->setThumbnail("https://media.discordapp.net/attachments/1049567414842572881/1074358371576205402/zeus.png");
            $rpEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён допуска на **Зевс** \n");
            $channel->sendEmbed($rpEmbededMessage);
            $rp[1] = 0;
            R::exec("UPDATE `players` SET `dOldpRP` = '$rp' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978556134703136789');
            });
          };
        };
        if ($rp[3] != $oldrp[3]) {
          if ($rp[3] > $oldrp[3]){
            $rpEmbededMessage = new Embed($discord);
            $rpEmbededMessage->setColor('#ffffff');
            $rpEmbededMessage->setThumbnail("https://media.discordapp.net/attachments/1049567414842572881/1074358371899146282/eagle.png");
            $rpEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** получил доступ на **Легионер** \n");
            $channel->sendEmbed($rpEmbededMessage);
            $rp[3] = 1;
            R::exec("UPDATE `players` SET `dOldpRP` = '$rp' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->addRole('978558106604822549');
            });
          }elseif ($rp[3] < $oldrp[3]){
            $rpEmbededMessage = new Embed($discord);
            $rpEmbededMessage->setColor('#ff0000');
            $rpEmbededMessage->setThumbnail("https://media.discordapp.net/attachments/1049567414842572881/1074358371899146282/eagle.png");
            $rpEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён допуска на **Легионер** \n");
            $channel->sendEmbed($rpEmbededMessage);
            $rp[3] = 0;
            R::exec("UPDATE `players` SET `dOldpRP` = '$rp' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978558106604822549');
            });
          };
        };
        if ($rp[5] != $oldrp[5]) {
          if ($rp[5] > $oldrp[5]){
            $rpEmbededMessage = new Embed($discord);
            $rpEmbededMessage->setColor('#ffffff');
            $rpEmbededMessage->setThumbnail("https://media.discordapp.net/attachments/1049567414842572881/1074358372234694727/camera.png");
            $rpEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** получил доступ на **Стрингер** \n");
            $channel->sendEmbed($rpEmbededMessage);
            $rp[5] = 1;
            R::exec("UPDATE `players` SET `dOldpRP` = '$rp' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->addRole('978558105199718470');
            });
          }elseif ($rp[5] < $oldrp[5]){
            $rpEmbededMessage = new Embed($discord);
            $rpEmbededMessage->setColor('#ff0000');
            $rpEmbededMessage->setThumbnail("https://media.discordapp.net/attachments/1049567414842572881/1074358372234694727/camera.png");
            $rpEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён допуска на **Стрингер** \n");
            $channel->sendEmbed($rpEmbededMessage);
            $rp[5] = 0;
            R::exec("UPDATE `players` SET `dOldpRP` = '$rp' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978558105199718470');
            });
          };
        };
        if ($rp[7] != $oldrp[7]) {
          if ($rp[7] > $oldrp[7]){
            $rpEmbededMessage = new Embed($discord);
            $rpEmbededMessage->setColor('#ffffff');
            $rpEmbededMessage->setThumbnail("https://media.discordapp.net/attachments/1049567414842572881/1074358372654121130/redcross.png");
            $rpEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** получил доступ на **Красный Крест** \n");
            $channel->sendEmbed($rpEmbededMessage);
            $rp[7] = 1;
            R::exec("UPDATE `players` SET `dOldpRP` = '$rp' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->addRole('978558107988922398');
            });
          }elseif ($rp[7] < $oldrp[7]){
            $rpEmbededMessage = new Embed($discord);
            $rpEmbededMessage->setColor('#ff0000');
            $rpEmbededMessage->setThumbnail("https://media.discordapp.net/attachments/1049567414842572881/1074358372654121130/redcross.png");
            $rpEmbededMessage->setDescription("\nᅠ\n{$namesrole[$rank]} **{$name}** лишён допуска на **Красный Крест** \n");
            $channel->sendEmbed($rpEmbededMessage);
            $rp[7] = 0;
            R::exec("UPDATE `players` SET `dOldpRP` = '$rp' WHERE `DiscID` = $dID");
            $guild->members->fetch($dID)->then(function (Member $memberd) {
              $memberd->removeRole('978558107988922398');
            });
          };
        };
      };
    }
  } catch (Exception $e) {
    echo 'Исключение таймер Допуска: ',  $e->getMessage(), "\n";
  }
});

$discord->getLoop()->addPeriodicTimer(120, function ($timer) use ($discord) { 
  try{
    $guild = $discord->guilds->get('id', '947296697669812274');
    $channel = $discord->getChannel('1018852898609831976');
    if ($selectdateinfos = R::getAll("SELECT * FROM players WHERE DiscID != '0' AND pBirhday != '0000-00-00'")) {
      foreach ($selectdateinfos as $info) {
        $pDiscID = $info['DiscID'];
        $Bmessage = [
          '1' => 'Дорогой <@'.$pDiscID.'>, с вылуплением тебя, от лица всего проекта желаем тебе быстрых ЗБД, не видать НАТОвцев, и крепкого здоровья!',
          '2' => 'А у нашей любимой Белоснежки <@'.$pDiscID.'> сегодня днюха. А это значит, что каждый должен сегодня выпить (хотя бы газировку) за здравие нашего сынка.',
          '3' => 'Высоко высоко в горах жил был <@'.$pDiscID.'>, он летал так высоко, что боевой WASP НАТО не мог его достать, так пожелаем же нашему имениннику высоких полетов и чистого неба на горизонте!',
          '4' => 'Если ты в танке, то мы обязательно тебе напомним, что сегодня у <@'.$pDiscID.'> День Рождения! Желаем быть в строю с оружием за спиной, меньше рассинхронов, больше опыта и конечно крепкого здоровья!',
          '5' => 'Батя, <@'.$pDiscID.'>, с днюхой тебя, с сорока с чем-то летием! Вертушка чисто для тебя, пивка для рывка, водочки для легкой походочки!',
          '6' => 'Далико-далико в гарах, жилъ быль арёл, он литал так высако, что нисматрел навирх, а зря, патамушта выше него летала высоково палёта птица <@'.$pDiscID.'>, так пожелаем жи этой птице высоких палетов, удачи, счастья и море благ! С Днём Рождения!',
          '7' => 'Можно смотреть на три вещи, как горит огонь, как течет вода и как рожают <@'.$pDiscID.'>, мы от лица проекта поздравляем нашего многоуважаемого товарища по оружию с Днем Рождения! Желаем счастья, здоровья и бесконечные пули в магазинах',
          '8' => 'Что это? Это птица? Это самолет? Нет! Это День Рождения у нашего замечательного <@'.$pDiscID.'>, и желаем ему всего наилучшего, чтобы в строю стоял и деньги были!',
        ];
        $plName = $info['pName'];
        $pBirthday = $info['pBirhday'];
        $dater = new DateTimeImmutable($pBirthday);
        $dater = $dater->format('m-d');
        $infbirthday = $info['Birthday_info'];
        $dateworld = date("m-d");
        $time = date("H");
        $member = $guild->members;
        $memberd = $member[$pDiscID];
        if ($infbirthday == '0'){
          if ($dateworld == $dater and $time == '10'){
            R::exec("UPDATE `players` SET `Birthday_info` = '1' WHERE `DiscID` = $pDiscID");
            if (isset($memberd)){
              $memberd->addRole('985990957805740112');
              $builder = MessageBuilder::new();
              $UprangEmbededMessage = new Embed($discord);
              $UprangEmbededMessage->setColor('#e91e63');
              $UprangEmbededMessage->setDescription($Bmessage[rand(1,8)]);
              $builder->setContent('@everyone');
              $builder->addEmbed($UprangEmbededMessage);
              $channel->sendMessage($builder)->then(function ($addReact) use ($discord){
                $addReact->react('🎂');
                $addReact->react("🥳");
                $addReact->react("❤️");
                $addReact->react("💪");
                $addReact->react("⭐");
              });
            }
          };
        }else{
          if ($dateworld != $dater){
            R::exec("UPDATE `players` SET `Birthday_info` = '0' WHERE `DiscID` = $pDiscID");
            if (isset($memberd)){
              $memberd->removeRole('985990957805740112');
            };
          };
        }
      };
    };
    R::close();
  } catch (Exception $e) {
    echo 'Исключение таймер ДР: ',  $e->getMessage(), "\n";
  }
});

$discord->getLoop()->addPeriodicTimer(35, function ($timer) use ($discord){
  try{
    $channemiss = $discord->getChannel('1080419031636512859');
    $missions = R::getAll("SELECT * FROM log_zbd WHERE NOT Date IS NULL AND dCheck = '0' ORDER BY Date DESC");
    foreach ($missions as $mission){
      $idDate = $mission['Date'];
      $date = new DateTimeImmutable($mission['Date']);
      $mdate = $date->format('Y.m.d');
      $mtime = $date->format('H:i:s');
      $timemiss = gmdate("H:i:s", $mission['Time']);
      $missionEmbed = new Embed($discord);
      $missionEmbed->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1084466117369663579/3857562.png");
      if ($mission['Status'] == '0'){
        $missionEmbed->setColor('#E67E22');
        $missionEmbed->setDescription("Город: **{$mission['City']}**\nСтатус: **Отменено**\nДата: **{$mdate} в {$mtime}**\nВремя: **{$timemiss}**\nКол-во бойцов: **{$mission['CountPlayers']}**\nКол-во 300: **{$mission['Count300']}**\nКол-во 200: **{$mission['Count200']}**");
      }elseif ($mission['Status'] == '1'){
        $missionEmbed->setColor('#2ECC70');
        $missionEmbed->setDescription("Город: **{$mission['City']}**\nСтатус: **Выполнено**\nДата: **{$mdate} в {$mtime}**\nВремя: **{$timemiss}**\nКол-во бойцов: **{$mission['CountPlayers']}**\nКол-во 300: **{$mission['Count300']}**\nКол-во 200: **{$mission['Count200']}**");
      }else{
        $missionEmbed->setColor('#E62222');
        $missionEmbed->setDescription("Город: **{$mission['City']}**\nСтатус: **Провалено**\nДата: **{$mdate} в {$mtime}**\nВремя: **{$timemiss}**\nКол-во бойцов: **{$mission['CountPlayers']}**\nКол-во 300: **{$mission['Count300']}**\nКол-во 200: **{$mission['Count200']}**");
      };
      $channemiss->sendEmbed($missionEmbed)->then(function ($missionupd) use ($idDate) {
        R::exec("UPDATE `log_zbd` SET `dCheck` = '1' WHERE NOT `Date` IS NULL");
        return $missionupd;
      });
    }
    R::close();
  } catch (Exception $e){
    echo 'Исключение вывод миссии: ',  $e->getMessage(), "\n";
  }
});

$discord->on(Event::GUILD_MEMBER_REMOVE, function (Member $member, Discord $discord) {
  try{
    $remid = $member->id;
    R::exec("UPDATE `players` SET `pDiscord` = '0', `DiscID` = '0', `dOldCYP` = '[0,0,0,0]', `dOldBTV` = '[0,0,0,0]', `dOldKMB` = '[0,0,0,0,0,0,0,0]', `dOldSkill` = '[0,0,0,0,0]', `dOldRank` = NULL, `pUnits` = '0' WHERE `DiscID` = '$remid'");
    R::close();
  } catch (Exception $e){
    echo 'Исключение выход с сервера: ',  $e->getMessage(), "\n";
  }
});

$discord->listenCommand('привязка', function (Interaction $interaction) use ($discord, $addrole, $namesrole) {
  try {
    $steamuid = $interaction->data->options['uid']->value;
    $idDisc = $interaction->user->id;
    if (($interaction->channel_id) == "951724193706291210") {
      if (is_numeric($steamuid)) {
        if (!($discordverefy = R::getCell("SELECT * FROM players WHERE `DiscID` = $idDisc"))) {
          if ($playerinfo = R::getCell('SELECT * FROM players WHERE `pUID` = ? AND `pDiscord` = "0" LIMIT 1', [$steamuid])) {
            $result = R::load('players', $playerinfo);
            $result = $result->export();
            if (($result['pLvl']) > 0) {
              $interaction->member->setNickname($result['pName'])->then(function ($add_roless) use ($interaction, $addrole, $result) {
                $interaction->member->addRole($addrole[$result['pLvl']]);
                $interaction->member->addRole('959168899671277658');
                return true;
              });
              $privateembed = new Embed($discord);
              $privateembed->setColor('#008000');
              $privateembed->setDescription("\n{$interaction->member} Вы успешно связали свой **Discord** аккаунт с **Steam** аккаунтом!");
              $interaction->member->sendMessage('', false, $privateembed);
              $NewrangEmbededMessage = new Embed($discord);
              $NewrangEmbededMessage->setColor('#ffffff');
              $NewrangEmbededMessage->setDescription("{$interaction->user} получил военный билет в звании **{$namesrole[$result['pLvl']]}**");
              $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($NewrangEmbededMessage));
              $oldrank = $result['pLvl'];
              R::exec("UPDATE `players` SET `pDiscord` = '1', `DiscID` = '$idDisc', `dOldRank` = '$oldrank' WHERE `pUID` = '$steamuid'");
            } else {
              $interaction->respondWithMessage(MessageBuilder::new()->setContent("{$interaction->user} пройдите Курс Молодого Бойца (проводится 'Инструктором КМБ') на сервере и повторите попытку."), true);
            }
          } else {
            $interaction->respondWithMessage(MessageBuilder::new()->setContent("{$interaction->user}  пройдите Курс Молодого Бойца (проводится 'Инструктором КМБ') на сервере и повторите попытку."), true);
          };
          R::close();
        } else {
          $interaction->respondWithMessage(MessageBuilder::new()->setContent('Ваш аккаунт Discord уже **привязан** к другому Steam аккаунту! За дополнительной информацие обратитесь к <@&978542349170925618> или в канал <#951372264841052182>'), true);
        };
        R::close();
      } else {
        $interaction->respondWithMessage(MessageBuilder::new()->setContent("Введите свой <UID> подробнее в <#951372264841052182> или узнайте у <@&978542349170925618>"), true);
      };
    } else {
      $interaction->respondWithMessage(MessageBuilder::new()->setContent('Вам нужен этот канал : <#951724193706291210>'), true);
    };
  } catch (Exception $e) {
    echo 'Исключение звания: ',  $e->getMessage(), "\n";
  }
});

$discord->listenCommand('должности', function (Interaction $interaction) use ($discord) {
  try {
    $rank0 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "0"');
    $rank1 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "1"');
    $rank2 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "2"');
    $rank3 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "3"');
    $rank4 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "4"');
    $rank5 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "5"');
    $rank6 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "6"');
    $rank7 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "7"');
    $rank8 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "8"');
    $rank9 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "9"');
    $rank10 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "10"');
    $rank11 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "11"');
    $rank12 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "12"');
    $rank13 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "13"');
    $rank14 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "14"');
    $rank15 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "15"');
    $rank16 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "16"');
    $rank17 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "17"');
    $rank18 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "18"');
    $rank19 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "19"');
    $rank20 = R::getCell('SELECT COUNT(`pLvl`) FROM players WHERE `pLvl` = "20"');
    $nsEmbededMessage = new Embed($discord);
    $nsEmbededMessage->setColor('#d60000');
    $nsEmbededMessage->setTitle("``{$interaction->guild->name}``");
    $nsEmbededMessage->setTimestamp();
    $nsEmbededMessage->setDescription("**🔒Информация по званиям🔒** \n\n Новобранцев - **{$rank0}**. \n В звании Рядовой - **{$rank1}**. \n В звании Ефрейтор - **{$rank2}**.\n В звании Младший Сержант - **{$rank3}**.\n В звании Сержант - **{$rank4}**.\n В звании Старший Сержант - **{$rank5}**.\n В звании Старшина - **{$rank6}**.\n В звании Прапорщик - **{$rank7}**.\n В звании Старший Прапорщик - **{$rank8}**.\n В звании Младший Лейтенант - **{$rank9}**.\n В звании Лейтенант - **{$rank10}**.\n В звании Старший Лейтенант - **{$rank11}**.\n В звании Капитан - **{$rank12}**.\n В звании Майор - **{$rank13}**.\n В звании Подполковник - **{$rank14}**.\n В звании Полковник - **{$rank15}**.\n В звании Генерал-майор - **{$rank16}**.\n В звании Генерал-лейтенант - **{$rank17}**.\n В звании Генерал-полковник - **{$rank18}**.\n В звании Генерал-армии - **{$rank19}**.\n В звании Маршал - **{$rank20}**.\n");
    $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($nsEmbededMessage), true);
    R::close();
  } catch (Exception $e) {
    echo 'Исключение должности: ',  $e->getMessage(), "\n";
  }
});

$discord->listenCommand('уровень', function (Interaction $interaction) use ($discord) {
  try {
    if (($interaction->channel_id) == '957611344964763728') {
      $autorID = $interaction->member->user->id;
      $authName = $interaction->member->nick;
      $authUsername = $interaction->member->user->username;
      $authhreph = $interaction->member->user->discriminator;
      $authImg = $interaction->member->user->avatar;
      $number = 0;
      if ($userlvls = R::getAll("SELECT * FROM discordlvl ORDER BY `dLvl` DESC")) {
        foreach ($userlvls as $userlvl) {
          $number++;
          $userid = $userlvl['dID'];
          $userflvl = $userlvl['dLvl'];
          $userfexp = $userlvl['dExp'];
          $userfmexm = $userlvl['dmaxExp'];
          $exp = $userfmexm - $userfexp;
          if ($userid == $autorID) {
            if ($authName != ''){
              creatlvl($authName, $authhreph, $number, $userflvl, $userfexp, $userfmexm, str_replace('?size=1024', '', $authImg));
            }else{
              creatlvl($authUsername, $authhreph, $number, $userflvl, $userfexp, $userfmexm, str_replace('?size=1024', '', $authImg));
            };            
            $interaction->respondWithMessage(MessageBuilder::new()->addFile(__DIR__ . "/Cimges/lvlimage.png", "/Cimges/lvlimage.png"));
          };
        }
      };
    } else {
      $interaction->respondWithMessage(MessageBuilder::new()->setContent('Вам нужен этот канал : <#957611344964763728>'), true);
    };
  } catch (Exception $e) {
    echo 'Исключение уровень: ',  $e->getMessage(), "\n";
  }
});

$discord->listenCommand('список', function (Interaction $interaction) use ($discord, $namesrole) {
  try{
    $namespicok = $interaction->data->options['состав']->value;
    if (($interaction->channel_id) == '951372264841052182' || ($interaction->channel_id) == '951456221322420274') {
      if ($namespicok == 'btvs') {
        if ($btvinfo = R::getAll('SELECT * FROM players WHERE `pBTV` != ? ORDER BY pLvl DESC', ['[0,0,0,0]'])) {
          foreach ($btvinfo as $btvinfos) {
            $pnames = $btvinfos['pName'];
            $plvls = $btvinfos['pLvl'];
            $dopusk = $btvinfos['pBTV'];
            if ($dopusk == '[1,0,0,0]') {
              $playernames[] = "{$namesrole[$plvls]} **{$pnames}:** __1 допуск БТВ__";
            } elseif ($dopusk == '[1,1,0,0]' or $dopusk == '[0,1,0,0]') {
              $playernames[] = "{$namesrole[$plvls]} **{$pnames}:** __2 допуск БТВ__";
            } elseif ($dopusk == '[1,1,1,0]' or $dopusk == '[0,1,1,0]' or $dopusk == '[0,0,1,0]') {
              $playernames[] = "{$namesrole[$plvls]} **{$pnames}:** __3 допуск БТВ__";
            } elseif ($dopusk == '[1,1,1,1]' or $dopusk == '[0,1,1,1]' or $dopusk == '[0,0,1,1]' or $dopusk == '[0,0,0,1]') {
              $playernames[] = "{$namesrole[$plvls]} **{$pnames}:** __4 допуск БТВ__";
            }
          }
          $playername = implode("\n", $playernames);
          $informmessage = new Embed($discord);
          $informmessage->setColor('#06b495');
          $informmessage->setTimestamp();
          $informmessage->setDescription("\n**```Состав БТВ```**\n{$playername}");
          $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($informmessage), true);
        };
      } elseif ($namespicok == 'cups') {
        if ($cupinfo = R::getAll('SELECT * FROM players WHERE `pCYP` != ? ORDER BY pLvl DESC', ['[0,0,0,0]'])) {
          foreach ($cupinfo as $cupinfos) {
            $pnames = $cupinfos['pName'];
            $plvls = $cupinfos['pLvl'];
            $dopusk = $cupinfos['pCYP'];
            if ($dopusk == '[1,0,0,0]') {
              $playernames[] = "{$namesrole[$plvls]} **{$pnames}:** __1 допуск ВВС__";
            } elseif ($dopusk == '[1,1,0,0]' or $dopusk == '[0,1,0,0]') {
              $playernames[] = "{$namesrole[$plvls]} **{$pnames}:** __2 допуск ВВС__";
            } elseif ($dopusk == '[1,1,1,0]' or $dopusk == '[0,1,1,0]' or $dopusk == '[0,0,1,0]') {
              $playernames[] = "{$namesrole[$plvls]} **{$pnames}:** __3 допуск ВВС__";
            } elseif ($dopusk == '[1,1,1,1]' or $dopusk == '[0,1,1,1]' or $dopusk == '[0,0,1,1]' or $dopusk == '[0,0,0,1]') {
              $playernames[] = "{$namesrole[$plvls]} **{$pnames}:** __4 допуск ВВС__";
            }
          }
          $playername = implode("\n", $playernames);
          $informmessage = new Embed($discord);
          $informmessage->setColor('#06b495');
          $informmessage->setTimestamp();
          $informmessage->setDescription("\n**```Состав ВВС```**\n{$playername}");
          $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($informmessage), true);
        };
      } else {
        if ($kmbinfo = R::getAll('SELECT * FROM players WHERE `pKMB` != ? ORDER BY pLvl DESC', ['[0,0,0,0,0,0,0,0]'])) {
          if ($namespicok == 'instp') {
            foreach ($kmbinfo as $kmbinfos) {
              $pnames = $kmbinfos['pName'];
              $plvls = $kmbinfos['pLvl'];
              $pKMBs = $kmbinfos['pKMB'];
              if ($pKMBs[1] == '1') {
                $playernamesp[] = "{$namesrole[$plvls]} **{$pnames}**";
              };
            }
            if (isset($playernamesp)) {
              $playernamep = implode("\n", $playernamesp);
            } else {
              $playernamep = 'Пусто!';
            };
            $informmessage = new Embed($discord);
            $informmessage->setColor('#06b495');
            $informmessage->setTimestamp();
            $informmessage->setDescription("\n**```Инструктор Лётчиков```**\n{$playernamep}\n");
            $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($informmessage), true);
          } elseif ($namespicok == 'instt') {
            foreach ($kmbinfo as $kmbinfos) {
              $pnames = $kmbinfos['pName'];
              $plvls = $kmbinfos['pLvl'];
              $pKMBs = $kmbinfos['pKMB'];
              if ($pKMBs[3] == '1') {
                $playernamesp[] = "{$namesrole[$plvls]} **{$pnames}**";
              };
            }
            if (isset($playernamesp)) {
              $playernamep = implode("\n", $playernamesp);
            } else {
              $playernamep = 'Пусто!';
            };
            $informmessage = new Embed($discord);
            $informmessage->setColor('#06b495');
            $informmessage->setTimestamp();
            $informmessage->setDescription("\n**```Инструктор Танкистов```**\n{$playernamep}\n");
            $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($informmessage), true);
          } elseif ($namespicok == 'instk') {
            foreach ($kmbinfo as $kmbinfos) {
              $pnames = $kmbinfos['pName'];
              $plvls = $kmbinfos['pLvl'];
              $pKMBs = $kmbinfos['pKMB'];
              if ($pKMBs[7] == '1') {
                $playernamesp[] = "{$namesrole[$plvls]} **{$pnames}**";
              };
            }
            if (isset($playernamesp)) {
              $playernamep = implode("\n", $playernamesp);
            } else {
              $playernamep = 'Пусто!';
            };
            $informmessage = new Embed($discord);
            $informmessage->setColor('#06b495');
            $informmessage->setTimestamp();
            $informmessage->setDescription("\n**```Инструктор Новобранцев```**\n{$playernamep}\n");
            $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($informmessage), true);
          } elseif ($namespicok == 'instr') {
            foreach ($kmbinfo as $kmbinfos) {
              $pnames = $kmbinfos['pName'];
              $plvls = $kmbinfos['pLvl'];
              $pKMBs = $kmbinfos['pKMB'];
              if ($pKMBs[5] == '1') {
                $playernamesp[] = "{$namesrole[$plvls]} **{$pnames}**";
              };
            }
            if (isset($playernamesp)) {
              $playernamep = implode("\n", $playernamesp);
            } else {
              $playernamep = 'Пусто!';
            };
            $informmessage = new Embed($discord);
            $informmessage->setColor('#06b495');
            $informmessage->setTimestamp();
            $informmessage->setDescription("\n**```Инструктор РП```**\n{$playernamep}\n");
            $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($informmessage), true);
          } elseif ($namespicok == 'insto') {
            foreach ($kmbinfo as $kmbinfos) {
              $pnames = $kmbinfos['pName'];
              $plvls = $kmbinfos['pLvl'];
              $pKMBs = $kmbinfos['pKMB'];
              if ($pKMBs[9] == '1') {
                $playernamesp[] = "{$namesrole[$plvls]} **{$pnames}**";
              };
            }
            if (isset($playernamesp)) {
              $playernamep = implode("\n", $playernamesp);
            } else {
              $playernamep = 'Пусто!';
            };
            $informmessage = new Embed($discord);
            $informmessage->setColor('#06b495');
            $informmessage->setTimestamp();
            $informmessage->setDescription("\n**```Инструктор Офицеров```**\n{$playernamep}\n");
            $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($informmessage), true);
          } elseif ($namespicok == 'instsa') {
            foreach ($kmbinfo as $kmbinfos) {
              $pnames = $kmbinfos['pName'];
              $plvls = $kmbinfos['pLvl'];
              $pKMBs = $kmbinfos['pKMB'];
              if ($pKMBs[15] == '1') {
                $playernamesp[] = "{$namesrole[$plvls]} **{$pnames}**";
              };
            }
            if (isset($playernamesp)) {
              $playernamep = implode("\n", $playernamesp);
            } else {
              $playernamep = 'Пусто!';
            };
            $informmessage = new Embed($discord);
            $informmessage->setColor('#06b495');
            $informmessage->setTimestamp();
            $informmessage->setDescription("\n**```Инструктор Санитаров```**\n{$playernamep}\n");
            $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($informmessage), true);
          } elseif ($namespicok == 'insti') {
            foreach ($kmbinfo as $kmbinfos) {
              $pnames = $kmbinfos['pName'];
              $plvls = $kmbinfos['pLvl'];
              $pKMBs = $kmbinfos['pKMB'];
              if ($pKMBs[13] == '1') {
                $playernamesp[] = "{$namesrole[$plvls]} **{$pnames}**";
              };
            }
            if (isset($playernamesp)) {
              $playernamep = implode("\n", $playernamesp);
            } else {
              $playernamep = 'Пусто!';
            };
            $informmessage = new Embed($discord);
            $informmessage->setColor('#06b495');
            $informmessage->setTimestamp();
            $informmessage->setDescription("\n**```Инструктор Инженеров```**\n{$playernamep}\n");
            $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($informmessage), true);
          } else {
            foreach ($kmbinfo as $kmbinfos) {
              $pnames = $kmbinfos['pName'];
              $plvls = $kmbinfos['pLvl'];
              $pKMBs = $kmbinfos['pKMB'];
              if ($pKMBs[11] == '1') {
                $playernamesp[] = "{$namesrole[$plvls]} **{$pnames}**";
              };
            }
            if (isset($playernamesp)) {
              $playernamep = implode("\n", $playernamesp);
            } else {
              $playernamep = 'Пусто!';
            };
            $informmessage = new Embed($discord);
            $informmessage->setColor('#06b495');
            $informmessage->setTimestamp();
            $informmessage->setDescription("\n**```Инструктор Снайперов```**\n{$playernamep}\n");
            $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($informmessage), true);
          };
        };
      };
    } else {
      $interaction->respondWithMessage(MessageBuilder::new()->setContent('Вам нужны эти каналы : <#951372264841052182> или <#951456221322420274>'), true);
    };
  } catch (Exception $e) {
    echo 'Исключение список: ',  $e->getMessage(), "\n";
  }
});

$discord->listencommand('др', function (Interaction $interaction) use ($discord){
  try{
    $date = $interaction->data->options['дата']->value;
    $iduser = $interaction->member->id;
    $time_input = strtotime($date);
    $dates = date('Y/m/d', $time_input);
    if($dates == '1970/01/01'){
      $interaction->respondWithMessage(MessageBuilder::new()->setContent("**Вы ввели неправильный формат даты или не указали его.**\nПример вводимых данных ```1990/01/01```\nГде 1990 - год / 01 - месяц / 01 - день"), true);
    }else{
      $discordverefy = R::getAll("SELECT * FROM players WHERE `DiscID` = $iduser");
      if($discordverefy != NULL){
        foreach($discordverefy as $user){
          if($user['pBirhday'] == '0000-00-00'){
            R::exec("UPDATE `players` SET `pBirhday` = '$dates' WHERE `DiscID` = $iduser");
            $interaction->respondWithMessage(MessageBuilder::new()->setContent("Вы успешно добавили дату рождения. Теперь в этот день ждите поздравления!\n```Напоминаем что данные которые вы ввели нельзя изменить самим.```Если вы хотите что то поменять или удалить обращайтесь к <@&978542349170925618>"), true);
          }else{
            $interaction->respondWithMessage(MessageBuilder::new()->setContent("Вы уже добавили свой день рождения. Если вы допустили ошибку или хотете удалить, обратитесь к <@&978542349170925618> в канале <#951372264841052182>\n```Тех.Поддержка, может обновит ваш День Рождения максимум 1раз!```"), true);
          }
        }
      }else{
        $interaction->respondWithMessage(MessageBuilder::new()->setContent("Вы ещё не привязали Steam к Discord - это можно сделать в канале <#951724193706291210>\nПосле можете повторить попытку."), true);
      };
      R::close();
    }
  } catch (Exception $e){
    echo 'Исключение др: ',  $e->getMessage(), "\n";
  }
});

$discord->listenCommand('создать_отряд', function (Interaction $interaction) use ($discord){
  try{
    $namesquad = $interaction->data->options['название']->value;
    $tegsquad = $interaction->data->options['тег']->value;
    $pchief = $interaction->data->options['pid-лидер']->value;
    $dchief = $interaction->data->options['did-лидер']->value;
    $uninID = $interaction->data->options['uid']->value;
    $urlsquad = $interaction->data->options['units']->value;
    $datetime = date("Y-m-d H:i:s"); 
    $guild = $interaction->guild;
    $member = $guild->members;
    $memberd = $member[$dchief];
    $nicklead = $memberd->nick;
    $namelead = $memberd->username;
    if ($interaction->channel_id == '952663640836804619'){
      if ($interaction->member->roles->get('id', '978542349170925618') or $interaction->member->roles->get('id', '978542356779376690') or $interaction->member->roles->get('id', '1009052101315399711')){
        $Squadt = R::getAll("SELECT * FROM units WHERE `uName` = '$namesquad' OR  `uTag` = '$tegsquad' LIMIT 1");
        if ($Squadt == NULL){
          if ($memberd != ''){
            $guild->createRole([
              'name' => "{$tegsquad}",
              'permissions' => 0,
              'color' => 0,
              'unicode_emoji' => '⚔️',
              'hoist' => true,
              'mentionable' => 'true'
            ])->done(function (Role $role) use ($discord, $interaction, $guild, $uninID, $namesquad, $tegsquad, $pchief, $urlsquad, $datetime,  $dchief, $memberd, $nicklead, $namelead) {
              echo 'Создал новую роль - ID:', $role->id;
              $guild->updateRolePositions([2 => $role->id]);
              $res = R::exec("INSERT INTO units (uUID, uName, uTag, uLead, uSite, uExp, uRegDate, dLeadID, dRoleID) VALUES (?,?,?,?,?,?,?,?,?)", [$uninID, $namesquad, $tegsquad, $pchief, $urlsquad, '0', $datetime,  $dchief, $role->id]);
              $updatUnit = R::exec("UPDATE `players` set `pUnits` = $uninID WHERE `DiscID` = $dchief");
              $memberd->addRole($role->id)->then(function ($rename) use ($discord, $memberd, $nicklead, $namelead){
                $memberd->addRole('1068963728957128825');
                if ($nicklead != ''){
                  $memberd->setNickname("⭐ {$nicklead}")->then(function ($add_role_leader) use ($memberd){
                    $memberd->addRole('1024053627893063750');
                  });
                }else{
                  $memberd->setNickname("⭐ {$namelead}")->then(function ($add_role_leader) use ($memberd){
                    $memberd->addRole('1024053627893063750');
                  });
                };
              });
            });
            $privateembed = new Embed($discord);
            $privateembed->setColor('#008000');
            $privateembed->setDescription("\n<@{$dchief}> Ваш отряд успешно создан на проекте Девятка!\nПерейдите в канал <#1069872550210969610> для добавления участников в свой отряд!");
            $memberd->sendMessage('', false, $privateembed);
            $interaction->respondWithMessage(MessageBuilder::new()->setContent("{$namesquad} был добавлен !"), true);
          }else{
            $interaction->respondWithMessage(MessageBuilder::new()->setContent("Такого Discord ID не существует **({$dchief})**!"), true);
          }
        }else{
          $interaction->respondWithMessage(MessageBuilder::new()->setContent("Отряд с название **{$namesquad}** или с Тегом **{$tegsquad}** уже существует!"), true);
        };
      }else{
        $interaction->respondWithMessage(MessageBuilder::new()->setContent("```Вы не являетесь Администратором!```"), true);
      };
      R::close();
    }else{
      $interaction->respondWithMessage(MessageBuilder::new()->setContent("Вам требуется канал <#952663640836804619>!"), true);
    };
  } catch (Exception $e){
    echo 'Исключение создания отряда: ',  $e->getMessage(), "\n";
  }
});

$discord->listenCommand('отряд', function (Interaction $interaction) use ($discord, $imgotr){
  try{
    $action = $interaction->data->options['действие']->value;
    $targetuser = $interaction->data->options['участник']->value;
    $untauthor = $interaction->member->id;
    $guild = $interaction->guild;
    $member = $guild->members;
    $memberd = $member[$targetuser];
    if ($interaction->channel_id == '1069872550210969610'){
      if (!empty($memberd)){
        if ($memberd->user->bot == false) {
          $Sechinfos = R::getAll("SELECT * FROM `players` JOIN `units` ON players.pUnits = units.uUID WHERE `DiscID` = '$untauthor'");
          if (!empty($Sechinfos)){
            foreach ($Sechinfos as $Sechinfo){
              $idSquad = $Sechinfo['uUID'];
              $roleID = $Sechinfo['dRoleID'];
              if ($Sechinfo['DiscID'] == $Sechinfo['dLeadID'] OR $Sechinfo['DiscID'] == $Sechinfo['dViceID']){
                if($untauthor != $targetuser){
                  $Checktgs = R::getAll("SELECT * FROM `players` LEFT JOIN `stats` ON players.pUID = stats.pUID WHERE `DiscID` = '$targetuser'");
                  foreach ($Checktgs as $Checktg){
                    if ($Checktg['pLvlSort'] == NULL){
                      if($action == 'invt_squad'){
                        if ($Checktg['pUnits'] == '0'){
                          R::exec("UPDATE players set `pUnits` = '$idSquad'  WHERE `DiscID` = $targetuser");
                          $memberd->addRole($roleID)->then(function ($addrol) use ($memberd){
                            $memberd->addRole('1068963728957128825');
                          });
                          $otradEmbed = new Embed($discord);
                          $otradEmbed->setColor('#1d9a32');
                          if (array_key_exists($idSquad, $imgotr)){
                            $otradEmbed->setThumbnail($imgotr[$idSquad]);
                            $otradEmbed->setDescription("ᅠ\nᅠ\n<@{$targetuser}> был принят в отряд <@&{$roleID}>!");
                          }else{
                            $otradEmbed->setDescription("<@{$targetuser}> был принят в отряд <@&{$roleID}>!");
                          }
                          $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($otradEmbed));
                        }else{
                          $interaction->respondWithMessage(MessageBuilder::new()->setContent("Боец уже состоит в отряде!"), true);
                        };
                      }else{
                        if ($Checktg['pUnits'] != '0'){
                          R::exec("UPDATE players set `pUnits` = '0'  WHERE `DiscID` = $targetuser");
                          $memberd->removeRole($roleID)->then(function ($remrol) use ($memberd){
                            $memberd->removeRole('1068963728957128825');
                          });
                          $iotradEmbed = new Embed($discord);
                          $iotradEmbed->setColor('#ff0000');
                          if (array_key_exists($idSquad, $imgotr)){
                            $iotradEmbed->setThumbnail($imgotr[$idSquad]);
                            $iotradEmbed->setDescription("ᅠ\nᅠ\n<@{$targetuser}> был исключен из отряда <@&{$roleID}>!");
                          }else{
                            $iotradEmbed->setDescription("<@{$targetuser}> был исключен из отряда <@&{$roleID}>!");
                          }
                          $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($iotradEmbed));
                        }else{
                          $interaction->respondWithMessage(MessageBuilder::new()->setContent("Боец не состоит в отряде!"), true);
                        };
                      };
                    }else{
                      $interaction->respondWithMessage(MessageBuilder::new()->setContent("Игрок на сервере, в связи с этим мы не можем обновить его статус отряда!"), true);
                    };
                  }
                }else{
                  $interaction->respondWithMessage(MessageBuilder::new()->setContent("Вы не можете взаимодействовать с самим собой!"), true);
                };
              }else{
                $interaction->respondWithMessage(MessageBuilder::new()->setContent("Вы не являетесь главой или заместителем отряда!"), true);
              };
            }
          }else{
            $interaction->respondWithMessage(MessageBuilder::new()->setContent("Вы не состоите в отряде!"), true);
          };
        }else{
          $interaction->respondWithMessage(MessageBuilder::new()->setContent('Я бездушная машина, имитация человека 🛸'), true);
        };
      }else{
        $interaction->respondWithMessage(MessageBuilder::new()->setContent("Боец не связал свой Discord с Steam аккаунтом! Обратитесь к <@&978542349170925618>"), true);
      };
      R::close();
    }else{
      $interaction->respondWithMessage(MessageBuilder::new()->setContent("Вам нужен канал <#1069872550210969610>!"), true);
    };
  } catch (Exception $e){
    echo 'Исключение добавление в отряда: ',  $e->getMessage(), "\n";
  }
});

$discord->listenCommand('регистрация', function (Interaction $interaction) use ($discord){
  try{
    $duserlead = $interaction->member->id;
    if (!empty(R::getCell("SELECT * FROM players WHERE `DiscID` = '$duserlead' AND `pUnits` = '0' LIMIT 1"))){
      if ($interaction->channel_id == '952663640836804619'){
        $author = $interaction->member->username;
        $interaction->showModal("ШТАБ - Отряды", "regotr", [
          ActionRow::new()->addComponent(
            TextInput::new("1. Название Отряда", TextInput::STYLE_SHORT, "namesquad")
            ->setRequired(true)
            ->setPlaceholder("Девятая Рота")
            ->setMinLength(1)
            ->setMaxLength(100)
          ),
          ActionRow::new()->addComponent(
            TextInput::new("2. Тег Отряда", TextInput::STYLE_SHORT, "teg")
            ->setRequired(true)
            ->setPlaceholder("9 РОТА")
            ->setMinLength(1)
            ->setMaxLength(25)
          ),
          ActionRow::new()->addComponent(
            TextInput::new("3. Ссылка на UNITS отряда", TextInput::STYLE_SHORT, "unitssquad")
            ->setRequired(true)
            ->setPlaceholder("https://units.arma3.com/unit/9rota")
            ->setMinLength(20)
            ->setMaxLength(90)
          ),
          ActionRow::new()->addComponent(
            TextInput::new("4. Количество участников отряда", TextInput::STYLE_SHORT, "intsquad")
            ->setPlaceholder("5")
            ->setRequired(true)
            ->setMaxLength(5)
          )
        ], function(Interaction $interaction, $components) use ($discord, $duserlead){
          $namesquads = $components["namesquad"]->value;
          $tegs =  $components["teg"]->value;
          $unitssquads = $components["unitssquad"]->value;
          $intsquads = $components["intsquad"]->value;
          $Squadt = R::getCell("SELECT * FROM players WHERE `DiscID` = '$duserlead' LIMIT 1");
          $pidlead = R::load('players', $Squadt);
          $pidlead = $pidlead->export();
          $regembedmessage = new Embed($discord);
          $regembedmessage->setColor('#ffffff');
          $regembedmessage->setFooter("(dID - {$interaction->member->id}) / (pID - {$pidlead['pUID']})");
          $regembedmessage->setDescription("1. {$namesquads}\n2. {$tegs}\n3. {$interaction->member}\n4. {$unitssquads}\n5. {$intsquads}\n");
          $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($regembedmessage));
        });
      }else{
        $interaction->respondWithMessage(MessageBuilder::new()->setContent("Вам нужен канал: <#952663640836804619>"), true);
      };
    }else{
      $interaction->respondWithMessage(MessageBuilder::new()->setContent("**Вы уже состоите в отряде, покиньте его (команда - ``/выход``) и повторите попытку**"), true);
    }; 
    R::close();     
  } catch (Exception $e){
    echo 'Исключение регистрация в отряда: ',  $e->getMessage(), "\n";
  }
});

$discord->listenCommand('обновить', function (Interaction $interaction) use ($discord){
  try{
    if ($interaction->member->user->bot === false){
      $userID= $interaction->member->user->id;
      if($selectrename = R::getRow("SELECT * FROM players WHERE `DiscID` = ? AND pDiscord = '1'", [$userID])){
        $playname = $selectrename['pName'];
        if ($interaction->member->roles->get('id', '1024053627893063750')) {
          $interaction->member->setNickname("⭐ {$playname}");
        } else {
          $interaction->member->setNickname($playname);
        };
        $interaction->respondWithMessage(MessageBuilder::new()->setContent("```Ваш позывной был изменён```"), true);
      }else{
        $interaction->respondWithMessage(MessageBuilder::new()->setContent("Привяжите Steam к Discord в канале <#951724193706291210>\nЕсли будут вопросы пишите в <#951372264841052182>"), true);
      };
      R::close();
    };
  } catch (Exception $e){
    echo 'Исключение обновления ника: ',  $e->getMessage(), "\n";
  }
});

$discord->listenCommand('выход', function (Interaction $interaction) use ($discord){
  try{
    if (in_array($interaction->channel_id, ['1003241844647411792', '1069872550210969610'])){
      $Disc_ID = $interaction->member->id;
      $check_connect_discord = R::getCell("SELECT * FROM players WHERE `DiscID` = $Disc_ID");
      $target_onl = R::load('players', $check_connect_discord);
      $target_onl = $target_onl->export();
      $checkln = $target_onl['pUID'];
      $units_id = $target_onl['pUnits'];
      if (!empty($check_connect_discord)){
        $check_otrad = R::getCell("SELECT * FROM players WHERE `DiscID` = $Disc_ID AND `pUnits` != 0");
        if (!empty($check_otrad)){
          $check_glav_otr = R::getCell("SELECT `ID`, `pUID`, `pName`, `pUnits`, `uName`, `uLead`, `dLeadID`, `dRoleID` FROM players JOIN units ON `pUnits` = `uUID` WHERE `dLeadID` = $Disc_ID AND `pUID` = `uLead`");
          if (empty($check_glav_otr)){
            $check_only = R::getCell("SELECT * FROM stats WHERE `pUID` = '$checkln' LIMIT 1");
            if(empty($check_only)){
              $target_otr = R::getAll("SELECT * FROM units WHERE `uUID` = $units_id");
              if (!empty($target_otr)){
                foreach($target_otr as $to){
                  R::exec("UPDATE players set `pUnits` = '0'  WHERE `DiscID` = $Disc_ID");
                  $interaction->member->removeRole($to['dRoleID'])->then(function ($tard_rem) use ($interaction){
                    $interaction->member->removeRole('1068963728957128825');
                  });
                  $interaction->respondWithMessage(MessageBuilder::new()->setContent("<@{$Disc_ID}> покинул отряд <@&{$to['dRoleID']}>!"));
                }
              }else{
                R::exec("UPDATE players set `pUnits` = '0'  WHERE `DiscID` = $Disc_ID");
                $interaction->respondWithMessage(MessageBuilder::new()->setContent("<@{$Disc_ID}> покинул отряд!"));
              }
            }else{
              $interaction->respondwithMessage(MessageBuilder::new()->setContent("Вы находитесь на сервере!\n__Выйдите__ в **лобби** или полностью **закройте ARMA3** и повторите попытку!"), true);
            };
          }else{
            $interaction->respondwithMessage(MessageBuilder::new()->setContent("Вы являетесь лидером отряда!"), true);
          };
        }else{
          $interaction->respondwithMessage(MessageBuilder::new()->setContent("Вы не состоити в отряде!"), true);
        };
      }else{
        $interaction->respondwithMessage(MessageBuilder::new()->setContent("Ваш аккаунт Discord не привязан к аккаунту Steam, перейдите в канал <#951724193706291210> для привязки аккаунта!"), true);
      };
    }else{
      $interaction->respondwithMessage(MessageBuilder::new()->setContent("Вам нужен один из этих каналов: <#1069872550210969610> | <#1003241844647411792>"), true);
    };
    R::close();
  } catch (Exception $e){
    echo 'Исключение выхода из отряда: ',  $e->getMessage(), "\n";
  }
});

$discord->listenCommand('участники_отряда', function (Interaction $interaction) use ($discord, $namesrole){
  try{
    if (in_array($interaction->channel_id, ['951372264841052182', '951371690674380811', '1003241844647411792'])){
      $uID = $interaction->member->id;
      $checkuseds = R::getAll("SELECT * FROM players WHERE `DiscID` = '$uID' AND `pDiscord` = '1' LIMIT 1");
      if (!empty($checkuseds)){
        foreach($checkuseds as $checkused){
          $Unit = $checkused['pUnits'];
          if (!in_array($Unit, [0,NULL,'<NULL>', '<null>'])){
            $unitseach = R::getAll("SELECT `pLvl`, `pName`, `pLastTime`, `uUID`, `uTag` FROM players INNER JOIN units ON `pUnits` =`uUID` WHERE `pUnits` = '$Unit' ORDER BY pLastTime DESC");
            foreach ($unitseach as $punit){
              $tagunits = $punit['uTag'];
              $playeruser[] = "{$namesrole[$punit['pLvl']]} **{$punit['pName']}** - {$punit['pLastTime']}";
            }
            $playername = implode("\n", $playeruser);
            $otradinfo = new Embed($discord);
            $otradinfo->setColor('#412227');
            $otradinfo->setTimestamp();
            $otradinfo->setDescription("**```Отряд - $tagunits```**\n{$playername}");
            $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($otradinfo), true);
          }else{
            $interaction->respondWithMessage(MessageBuilder::new()->setContent("**Вы не состоите в отряде!**"), true);
          };
        }
      }else{
        $interaction->respondWithMessage(MessageBuilder::new()->setContent("**Ваш Steam не привязан к Discord!**\n``В связи с этим я не могу узнать ваш отряд.``\n``Если хотите привязать Steam к Discord перейдите в канал``- <#951724193706291210>"), true);
      };
    }else{
      $interaction->respondWithMessage(MessageBuilder::new()->setContent("Вам нужен один из этих каналов: <#951372264841052182> <#951371690674380811> <#1003241844647411792> "), true);
    };
    R::close();
  } catch (Exception $e){
    echo 'Исключение cписок отряда: ',  $e->getMessage(), "\n";
  }
});

$discord->listenCommand('ранг', function (Interaction $interaction) use ($discord, $namesrole, $imgrank, $addrole) {
  if($interaction->channel_id == "978543821036077096") {
    $user = $interaction->data->options['игрок']->value;
    $operation = $interaction->data->options['операция']->value;
    $member = $interaction->guild->members;
    $memberd = $member[$user];
    $user_author = $interaction->member->id;
    $user_author_name = $interaction->member->user;
    $mslog = $discord->getChannel('1080772564898566214');
    $msrank = $discord->getChannel('983806752426455075');
    if ($memberd->user->bot == false) {
      if(($interaction->member->roles->get('id', '978542366568878080')) or ($interaction->member->roles->get('id', '978555421365272626')) or ($interaction->member->roles->get('id', '978542349170925618')) or ($interaction->member->roles->get('id', '978542356779376690')) or ($interaction->member->roles->get('id', '978542346834694164'))){
        $userseachs = R::getAll("SELECT players.ID, players.pUID, players.pName, players.pLvl, players.pExp, players.DiscID, stats.Slot FROM `players` LEFT JOIN `stats` ON players.pUID = stats.pUID WHERE `DiscID` = '$user'");
        if (!empty($userseachs)){
          foreach($userseachs as $userseach){
            if($userseach['Slot'] == NULL){
              if ($operation == 'up_rank'){
                R::exec("UPDATE `players` set `pLvl` = `pLvl`+'1', `pExp` = '100', `dOldRank` = `pLvl` WHERE `DiscID` = $user");
                $urank = $userseach['pLvl']+'1';
                $interaction->respondWithMessage(MessageBuilder::new()->setContent("Вы успешно повысили {$memberd} до звания **``{$namesrole[$urank]}``**!"), true)->then(function ($editms) use ($discord, $user_author_name, $userseach, $user_author, $mslog){
                  $updrankms = new Embed($discord);
                  $updrankms->setColor('#313338');
                  $updrankms->setDescription("**{$user_author_name} повысил в звании {$userseach['pName']}**");
                  $updrankms->setFooter("(DID1 - {$user_author}) / (PID2 - {$userseach['pUID']})");
                  $mslog->sendEmbed($updrankms);
                });
                $UprangEmbededMessage = new Embed($discord);
                $UprangEmbededMessage->setColor('#1d9a32');
                $UprangEmbededMessage->setThumbnail($imgrank[$urank]);
                $UprangEmbededMessage->setDescription("ᅠ\nᅠ\n**{$userseach['pName']}** был повышен до звания **{$namesrole[$urank]}**");
                $msrank->sendEmbed($UprangEmbededMessage);
                $memberd->removeRole($addrole[$userseach['pLvl']])->then(function ($addnewrole) use ($memberd, $addrole, $urank) {
                  $memberd->addRole($addrole[$urank]);
                });
              };
              if ($operation == 'down_rank'){
                R::exec("UPDATE `players` set `pLvl` = `pLvl`-'1', `pExp` = '100', `dOldRank` = `pLvl` WHERE `DiscID` = $user");
                $drank = $userseach['pLvl']-'1';
                $interaction->respondWithMessage(MessageBuilder::new()->setContent("Вы успешно понизили {$memberd} до звания **``{$namesrole[$drank]}``**!"), true)->then(function ($editms) use ($discord, $user_author_name, $userseach, $user_author, $mslog){
                  $drrankms = new Embed($discord);
                  $drrankms->setColor('#313338');
                  $drrankms->setDescription("**{$user_author_name} понизил в звании {$userseach['pName']}**");
                  $drrankms->setFooter("(DID1 - {$user_author}) / (PID2 - {$userseach['pUID']})");
                  $mslog->sendEmbed($drrankms);
                });
                $DownrangEmbededMessage = new Embed($discord);
                $DownrangEmbededMessage->setColor('#f64747');
                $DownrangEmbededMessage->setThumbnail($imgrank[$drank]);
                $DownrangEmbededMessage->setDescription("ᅠ\nᅠ\n**{$userseach['pName']}** был понижен до звания **{$namesrole[$drank]}**");
                $msrank->sendEmbed($DownrangEmbededMessage);
                $memberd->removeRole($addrole[$userseach['pLvl']])->then(function ($addnewrole) use ($memberd, $addrole, $drank) {
                  $memberd->addRole($addrole[$drank]);
                });
              };
              if ($operation == 'rank'){
                if ($numberrang = $interaction->data->options['звание']->value) {
                  R::exec("UPDATE `players` set `pLvl` = $numberrang, `pExp` = '100' WHERE `DiscID` = $user");
                  $interaction->respondWithMessage(MessageBuilder::new()->setContent("Вы успешно присвоили звание **``{$namesrole[$numberrang]}``** для {$memberd}!"), true)->then(function ($editms) use ($discord, $user_author_name, $userseach, $namesrole, $numberrang, $user_author, $mslog){
                    $rerankms = new Embed($discord);
                    $rerankms->setColor('#313338');
                    $rerankms->setDescription("**{$user_author_name} изменил звание для {$userseach['pName']} на {$namesrole[$numberrang]}**");
                    $rerankms->setFooter("(DID1 - {$user_author}) / (PID2 - {$userseach['pUID']})");
                    $mslog->sendEmbed($rerankms);
                  });
                }else{
                  $interaction->respondWithMessage(MessageBuilder::new()->setContent("Выберите звание как на рисунке 1-2!\n https://cdn.discordapp.com/attachments/1049567414842572881/1117701730054967386/screen-1.png \n https://cdn.discordapp.com/attachments/1049567414842572881/1117702087921373244/screen-2.png"), true);
                };
              };
              if ($operation == 'exp'){
                if ($numberexp = $interaction->data->options['опыт']->value) {
                  if ($numberexp <= '5000' and $numberexp > '0'){
                    R::exec("UPDATE `players` set `pExp` = `pExp` + '$numberexp' WHERE `DiscID` = $user");
                    $interaction->respondWithMessage(MessageBuilder::new()->setContent("Вы успешно выдали **``{$numberexp}``** опыта для {$memberd}!"), true)->then(function ($editms) use ($discord, $user_author_name, $userseach, $numberexp, $user_author, $mslog){
                      $reepxms = new Embed($discord);
                      $reepxms->setColor('#313338');
                      $reepxms->setDescription("**{$user_author_name} выдал поощрение для {$userseach['pName']} в размере {$numberexp} опыта**");
                      $reepxms->setFooter("(DID1 - {$user_author}) / (PID2 - {$userseach['pUID']})");
                      $mslog->sendEmbed($reepxms);
                    });
                  }else{
                    $interaction->respondWithMessage(MessageBuilder::new()->setContent("Максимально возможное количество опыта для выдачи __**5000тыс**__!"), true);
                  };
                }else{
                  $interaction->respondWithMessage(MessageBuilder::new()->setContent("Выберите количество опыта как на рисунке 1-2!\nhttps://cdn.discordapp.com/attachments/1049567414842572881/1117708388919418970/screen-1_1.png\nhttps://cdn.discordapp.com/attachments/1049567414842572881/1117708388680351854/screen-1_2.png"), true);
                };
              };
            }else{
              $interaction->respondWithMessage(MessageBuilder::new()->setContent("Боец на сервере, в связи с этим взаимодействие недоступно! (попросите выйти в лобби на время выдачи)"), true);
            };
          }
        }else{
          $interaction->respondWithMessage(MessageBuilder::new()->setContent("Боец (<@{$user}>) не найден (напишите в канале <#951372264841052182> команду \"``/помощь`` или ``!помощь``\", для получения информации как привязать Steam к Discord)!"), true);
        };
      }else{
        $interaction->respondWithMessage(MessageBuilder::new()->setContent("Обратитесь к <@&978542366568878080> или <@&978555421365272626> для выдачи звания/опыта!"), true);
      };
      R::close();
    }else{
      $interaction->respondWithMessage(MessageBuilder::new()->setContent('<@978621473788952696> может работать только с людьми 👪'), true);
    };
  } else {
    $interaction->respondWithMessage(MessageBuilder::new()->setContent("**Перейдите в канал <#978543821036077096>!**"), true);
  };
});

$discord->listenCommand('монитор', function (Interaction $interaction) use ($discord, $browser) {
  if ($interaction->member != null){
    if (($interaction->member->getPermissions()->administrator) == 1) {
      $browser->get('https://api.battlemetrics.com/servers/17389558?include=player')->then(function (ResponseInterface $response) use ($discord, $interaction) {
        $info = json_decode($response->getBody());
        $Sname = $info->data->attributes->name;
        $stat = $info->data->attributes->status;
        $mstat = [
          "online" => "Онлайн",
          "offline" => "Выключен",
          "dead" => "Выключен",
          "removed" => "Удалён",
        ];
        $IpServer = $info->data->attributes->ip;
        $maps = $info->data->attributes->details->map;
        $miss = $info->data->attributes->details->mission;
        //$versmiss = substr($miss, -13);
        $numplayers = $info->data->attributes->players;
        $maxnumplayers = $info->data->attributes->maxPlayers;
        foreach ($info->included as $player) {
          $playernames[] = $player->attributes->name;
        }
        if ($playernames != '') {
          $playername = implode("\n", $playernames);
        } else {
          $playername = ("Игроков нет");
        };
        $monitorEmbededMessage = new Embed($discord);
        $monitorEmbededMessage->setColor('#2c1d9a');
        $monitorEmbededMessage->setTimestamp();
        $monitorEmbededMessage->setDescription("\n**Название:**  ``{$Sname}``\n**IP адрес: ** `{$IpServer}:2302` \n\n**Статус: ** ``{$mstat[$stat]}`` \n**Версия**: ``{$miss}`` \n**Остров**: ``{$maps}`` \n\n**Игроки Онлайн** ``({$numplayers}/{$maxnumplayers}):``\n```{$playername}```");
        $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($monitorEmbededMessage));
      });
    } else {
      $interaction->respondWithMessage(MessageBuilder::new()->setContent('Вы не являетесь администратором данного сервера!'), true);
    };
  };
});

$discord->listenCommand('помощь', function (Interaction $interaction) use ($discord) {
  if (($interaction->channel_id) == '951372264841052182') {
    $dlEmbededMessage = new Embed($discord);
    $dlEmbededMessage->setColor('#000ed6');
    $dlEmbededMessage->setDescription("\n**```⚠️Общие Команды⚠️```**\n\n**🗒️Привязка Steam к дискорд🗒️** \n Узнать SteamID64(UID):\n 1. Открыть стим->об аккаунте.\n 2. Копируем данные цифры https://goo.su/tXhl это и есть UID.\n 3. Переходим в канал <#951724193706291210> и пишем там ``/привязка <UID>``\n\n**📡Посмотреть все должности📡** \n 1. Переходим в канал <#983806752426455075> и пишем там ``/должности``\n\n**❔Узнать состав Танкистов/Лётчиков/Инструкторов❔** \n 1. Переходим в канал <#951372264841052182> или <#951456221322420274> -> пишем там ``/список (выбираем нужное)``\n\n**🏆Узнать уровень флуда**🏆\n 1. Переходим в канал <#957611344964763728> -> пишем там ``/уровень``\n\n**🎂Добавить день рождения🎂**\n 1. В любом доступном канале пишем команду ``/др`` выбераем её и пишем в формате ``год/месяц/день`` свой день рождения.\n Пример ``/др 1998/11/11``\n\n**🔄Обновить __позывной__ на актуальный🔄**\n 1. В любом доступном канале пишите `` / `` -> выбираете `` обновить `` -> отправляете сообщение.\n\n**```⚠️Команды для отрядов⚠️```**\n\n**📑Зарегистрировать свой отряд📑**\n 1. Переходим в канал <#952663640836804619> -> вводим  в чат `` /регистрация `` -> заполняем появившуюся форму -> отправляем и ожидаем проверки от Администрации (✅ - Отряд одобрен / ❌ - Отряд отклонён)\n\n**🍻Добавить бойца в свой отряд🍻**\n 1. Переходим в канал <#1069872550210969610> -> вводим в чат ``/отряд`` -> выбираем нужное -> выбираем бойца которому хотите выдать отряд.\n **Внимение:** Отряды могут выдавать только лидеры отряда(их указывали при регистрации отряда), если у бойца уже есть роль какого то отряда, вы не сможете ему выдать свой!\n\n**📝Покинуть отряд📝**\n 1. В канале <#1069872550210969610> или <#1003241844647411792> пишите `` /выход ``");
    $builder = MessageBuilder::new();
    $builder->addEmbed($dlEmbededMessage);
    $interaction->respondWithMessage($builder);
  } else {
    $interaction->respondWithMessage(MessageBuilder::new()->setContent('Вам нужен этот канал : <#951372264841052182>'), true);
  };
});

$discord->on('message', function (Message $message, Discord $discord) use ($rcon){
  $channelh = $discord->getChannel('951372264841052182');
  if (($message->channel_id) == '951372264841052182'){
    if (strtolower($message->content) == '!помощь'){
      $mdlEmbededMessage = new Embed($discord);
      $mdlEmbededMessage->setColor('#000ed6');
      $mdlEmbededMessage->setDescription("\n**```⚠️Общие Команды⚠️```**\n\n**🗒️Привязка Steam к дискорд🗒️** \n Узнать SteamID64(UID):\n 1. Открыть стим->об аккаунте.\n 2. Копируем данные цифры https://goo.su/tXhl это и есть UID.\n 3. Переходим в канал <#951724193706291210> и пишем там ``/привязка <UID>``\n\n**📡Посмотреть все должности📡** \n 1. Переходим в канал <#983806752426455075> и пишем там ``/должности``\n\n**❔Узнать состав Танкистов/Лётчиков/Инструкторов❔** \n 1. Переходим в канал <#951372264841052182> или <#951456221322420274> -> пишем там ``/список (выбираем нужное)``\n\n**🏆Узнать уровень флуда**🏆\n 1. Переходим в канал <#957611344964763728> -> пишем там ``/уровень``\n\n**🎂Добавить день рождения🎂**\n 1. В любом доступном канале пишем команду ``/др`` выбераем её и пишем в формате ``год/месяц/день`` свой день рождения.\n Пример ``/др 1998/11/11``\n\n**🔄Обновить __позывной__ на актуальный🔄**\n 1. В любом доступном канале пишите `` / `` -> выбираете `` обновить `` -> отправляете сообщение.\n\n**```⚠️Команды для отрядов⚠️```**\n\n**📑Зарегистрировать свой отряд📑**\n 1. Переходим в канал <#952663640836804619> -> вводим  в чат `` /регистрация `` -> заполняем появившуюся форму -> отправляем и ожидаем проверки от Администрации (✅ - Отряд одобрен / ❌ - Отряд отклонён)\n\n**🍻Добавить бойца в свой отряд🍻**\n 1. Переходим в канал <#1069872550210969610> -> вводим в чат ``/отряд`` -> выбираем нужное -> выбираем бойца которому хотите выдать отряд.\n **Внимение:** Отряды могут выдавать только лидеры отряда(их указывали при регистрации отряда), если у бойца уже есть роль какого то отряда, вы не сможете ему выдать свой!\n\n**📝Покинуть отряд📝**\n 1. В канале <#1069872550210969610> или <#1003241844647411792> пишите `` /выход ``");
      $builder = MessageBuilder::new();
      $builder->addEmbed($mdlEmbededMessage);
      $channelh->sendMessage($builder);
    };
  };
  if($message->channel_id == "1118195721443754094") {
    if (strtolower($message->content) == '!игроки') {
      $message->channel->sendMessage(MessageBuilder::new()->setContent("**Ожидайте, в течение 10-ти секунд будет выдан ответ!**"), true)->then(function (Message $message) use ($discord, $rcon){
        $playerids = $rcon->getPlayersArray();
        if(!empty($playerids)){
          foreach ($playerids as $playerid){
            $playeruser[] = "**{$playerid['0']}** : {$playerid['4']}";
          }
          $playerarr = implode("\n", $playeruser);
          $builder = MessageBuilder::new();
          $builder->setContent('');
          $palyerset = new Embed($discord);
          $palyerset->setColor('#00B8A9');
          $palyerset->setTimestamp();
          $palyerset->setDescription("**```Список игроков и их ID на сервере```**\n{$playerarr}");
          $builder->addEmbed($palyerset);
          $message->edit($builder);
        }else{
          $builder = MessageBuilder::new();
          $builder->setContent('__Не удалось вывести данные, повторите попытку!__');
          $message->edit($builder);
        };
      });
    };
  };
});

$discord->listenCommand('тишина', function (Interaction $interaction) use ($discord){
  $usermut = $interaction->data->options['участник']->value;
  $timemut = $interaction->data->options['время']->value;
  $guild = $interaction->guild;
  $member = $guild->members;
  $memberd = $member[$usermut];
  $carbon = new Carbon();
  if ($interaction->member->roles->get('id', '1023596275616194612') or $interaction->member->roles->get('id', '1023596365412044801')){
    if ($usermut != $interaction->member->id){
      $memberd->timeoutMember($carbon->addMinutes($timemut));
      $interaction->respondWithMessage(MessageBuilder::new()->setContent("Вы успешно выдали мут для <@{$usermut}>!"), true);
    }else{
      $interaction->respondWithMessage(MessageBuilder::new()->setContent("**Вы не можете выдать себе мут!**"), true);
    };
  }else{
    $interaction->respondWithMessage(MessageBuilder::new()->setContent("**Вы не являетесь Администратором сервера ⭐ [RU] «Девятка» ⭐!**"), True);
  }
});

$discord->listenCommand('информ', function (Interaction $interaction) use ($discord, $namesrole){
  try{
    if (($interaction->channel_id) == "1093131911192383518"){
      $lkcode = $interaction->member->user->discriminator;
      $lkid = $interaction->member->id;
      $EmbedGame = new Embed($discord);
      $EmbedGame->setColor('#a6caf0');
      $EmbedGame->setThumbnail("https://cdn.discordapp.com/attachments/1049567414842572881/1092819075907981362/-1.png");
      $EmbedGame->setAuthor("Личное дело #{$lkcode}");
      $seachinfos = R::getAll("SELECT * FROM players LEFT JOIN units ON `pUnits` =`uUID` WHERE `DiscID`='$lkid'");
      if (!empty($seachinfos)){
        foreach($seachinfos as $seachinfo){
          $lkpAdm = $seachinfo['pAdmin'];
          $lkpboss = $seachinfo['pBoss'];
          $lkpKMB = $seachinfo['pKMB'];
          $lkpSkill = $seachinfo['pSkill'];
          $lkpCYP = $seachinfo['pCYP'];
          $lkpBTV = $seachinfo['pBTV'];
          $lkpRP = $seachinfo['pRP'];
          $lkdolsh = '';
          if ($lkpboss['1'] == '1'){$lkdolsh .= "**Начальник ЛС**\n";};
          if ($lkpboss['1'] == '2'){$lkdolsh .= "**Заместитель ЛС**\n";};
          if ($lkpboss['3'] == '1'){$lkdolsh .= "**Начальник ВВС**\n";}
          if ($lkpboss['3'] == '2'){$lkdolsh .= "**Заместитель ВВС**\n";}
          if ($lkpboss['5'] == '1'){$lkdolsh .= "**Начальник БТВ**\n";}
          if ($lkpboss['5'] == '2'){$lkdolsh .= "**Заместитель БТВ**\n";}
          if ($lkpboss['7'] == '1'){$lkdolsh .= "**Начальник РП**\n";}
          if ($lkpboss['7'] == '2'){$lkdolsh .= "**Заместитель РП**\n";}
          if ($lkpKMB['1'] == '1'){$lkdolsh .= "**Инструктор Пилотов**\n";};
          if ($lkpKMB['3'] == '1'){$lkdolsh .= "**Инструктор Танкистов**\n";}
          if ($lkpKMB['5'] == '1'){$lkdolsh .= "**Инструктор РП**\n";}
          if ($lkpKMB['7'] == '1'){$lkdolsh .= "**Инструктор Новобранцев**\n";}
          if ($lkpKMB['9'] == '1'){$lkdolsh .= "**Инструктор Офицеров**\n";}
          if ($lkpKMB['11'] == '1'){$lkdolsh .= "**Инструктор Снайперов**\n";}
          if ($lkpKMB['13'] == '1'){$lkdolsh .= "**Инструктор Инженеров**\n";}
          if ($lkpKMB['15'] == '1'){$lkdolsh .= "**Инструктор Санитаров**";}
          if ($lkdolsh == ''){$lkdolsh .= "**Должности не занимает**\n";}
          $lkkurs = '';
          if ($lkpSkill['1'] == '1'){$lkkurs .= "**Офицеров**\n";};
          if ($lkpSkill['3'] == '1'){$lkkurs .= "**Инженеров**\n";}
          if ($lkpSkill['5'] == '1'){$lkkurs .= "**Снайперов**\n";}
          if ($lkpSkill['7'] == '1'){$lkkurs .= "**Санитаров**\n";}
          if ($lkkurs == ''){$lkkurs .= "**Курсы не проходил**\n";}
          $lkdopusk = '';
          if ($lkpCYP['1'] == '1'){$lkdopusk .= "**Транспортные вертолёты**\n";};
          if ($lkpCYP['3'] == '1'){$lkdopusk .= "**Боевые вертолёты**\n";};
          if ($lkpCYP['5'] == '1'){$lkdopusk .= "**Транспортные самолёты**\n";};
          if ($lkpCYP['7'] == '1'){$lkdopusk .= "**Боевые самолёты**\n";};
          if ($lkpBTV['1'] == '1'){$lkdopusk .= "**Легкая гусеничная техника**\n";};
          if ($lkpBTV['3'] == '1'){$lkdopusk .= "**Средняя гусеничная техника**\n";};
          if ($lkpBTV['5'] == '1'){$lkdopusk .= "**Тяжелая гусеничная техника**\n";};
          if ($lkpBTV['7'] == '1'){$lkdopusk .= "**Артиллерия**\n";};
          if ($lkpRP['1'] == '1'){$lkdopusk .= "**Зевс**\n";};
          if ($lkpRP['3'] == '1'){$lkdopusk .= "**Легионер**\n";};
          if ($lkpRP['5'] == '1'){$lkdopusk .= "**Стрингеры**\n";};
          if ($lkpRP['7'] == '1'){$lkdopusk .= "**Красный крест**\n";};
          if ($lkdopusk == ''){$lkdopusk .= "**Допусков не присвоено**";}
          $lkGdolsh = '';
          if ($seachinfo['pUID'] == $seachinfo['uLead']){$lkGdolsh .= 'Глава';}else{$lkGdolsh .= 'Боец';};
          if ($seachinfo['pUnits'] != 0){
            $EmbedGame->setDescription("━━━━━━━━━━━━━━━━━━━━━\n• Имя:\n**Засекречено**\n\n• Фамилия:\n**Засекречено**\n\n• Позывной:\n**{$seachinfo['pName']}**\n━━━━━━━━━━━━━━━━━━━━━\n• Звание:\n**{$namesrole[$seachinfo['pLvl']]}**\n\n• Должности:\n{$lkdolsh}\n• Пройденные курсы:\n{$lkkurs}\n• Полученные допуска:\n{$lkdopusk}\n━━━━━━━━━━━━━━━━━━━━━\n• Боевое подразделение:\n**{$seachinfo['uTag']}**\n• Должность: \n**{$lkGdolsh}**");
          }else{
            $EmbedGame->setDescription("━━━━━━━━━━━━━━━━━━━━━\n• Имя:\n**Засекречено**\n\n• Фамилия:\n**Засекречено**\n\n• Позывной:\n**{$seachinfo['pName']}**\n━━━━━━━━━━━━━━━━━━━━━\n• Звание:\n**{$namesrole[$seachinfo['pLvl']]}**\n\n• Должности:\n{$lkdolsh}\n• Пройденные курсы:\n{$lkkurs}\n• Полученные допуска:\n{$lkdopusk}");
          }
        }
      }else{
        $interaction->respondWithMessage(MessageBuilder::new()->setContent("Ваш Steam аккаунт не привязан к Discord\nПерейдите в данный канал <#951724193706291210> для привяке steam к discord."), true);
      }
      $EmbedGame->setFooter("ЦЕНТРАЛЬНОЕ УПРАВЛЕНИЕ ДЕВЯТКИ", "https://media.discordapp.net/attachments/1001451023380004937/1092757909433221260/9pota_logo-1.png?width=473&height=473");
      $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($EmbedGame));
      R::close();
    };
  } catch (Exception $e){
    echo 'Исключение вывод ин-фы по игроку: ',  $e->getMessage(), "\n";
  }
});

$discord->listenCommand('функции', function(Interaction $interaction) use ($discord, $rcon){
  $type = $interaction->data->options['тип']->value;
  $id = $interaction->data->options['id']->value;
  $comment = $interaction->data->options['причина']->value;
  if (($interaction->channel_id) == '1118195721443754094') {
    if(($interaction->member->roles->get('id', '978542366568878080')) or ($interaction->member->roles->get('id', '978555421365272626')) or ($interaction->member->roles->get('id', '978542349170925618')) or ($interaction->member->roles->get('id', '978542356779376690')) or ($interaction->member->roles->get('id', '978542346834694164'))){
      if ($type == 'kick_user'){
        if ($id > '0' and $id < '1000' and $comment != ''){
          if(mb_strlen($comment) < '38'){
            try{
              $rcon->kickPlayer("{$id}","{$comment}");
              $kickmess = new Embed($discord);
              $kickmess->setColor('#008000');
              $kickmess->setTimestamp();
              $kickmess->setDescription("Вы успешно исключили бойца с id = [{$id}]");
              $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($kickmess));
            }catch (Exception $e){
              $interaction->respondWithMessage(MessageBuilder::new()->setContent("Произошла непредвиденная ошибка, обратитесь к <@409998159218081792>!"), true);
              echo 'Исключение кик бойца: ',  $e->getMessage(), "\n";
            }
          }else{
            $interaction->respondWithMessage(MessageBuilder::new()->setContent("**Вы ввели слишком длинное предложение в [причина], максимальное доступное число символов - 38!**"), true);
          }
        }else{
          $interaction->respondWithMessage(MessageBuilder::new()->setContent("**Вы ввели неправильное значение, проверьте введёные данные!**"), true);
        };
      };
      if ($type == 'ban_user'){
        if ($id > '0' and $id < '1000' and $comment != '' and $time_ban = $interaction->data->options['время_бана']->value){
          if(mb_strlen($comment) < '38'){
            try{
              $rcon->banPlayer("{$id}","{$comment}",$time_ban);
              $banmess = new Embed($discord);
              $banmess->setColor('#240a0b');
              $banmess->setTimestamp();
              $banmess->setDescription("Вы успешно забанили бойца с id = [{$id}]");
              $interaction->respondWithMessage(MessageBuilder::new()->addEmbed($banmess));
            }catch (Exception $e){
              $interaction->respondWithMessage(MessageBuilder::new()->setContent("Произошла непредвиденная ошибка, обратитесь к <@409998159218081792>!"), true);
              echo 'Исключение бан бойца: ',  $e->getMessage(), "\n";
            }
          }else{
            $interaction->respondWithMessage(MessageBuilder::new()->setContent("**Вы ввели слишком длинное предложение в [причина], максимальное доступное число символов - 38!**"), true);
          }
        }else{
          $interaction->respondWithMessage(MessageBuilder::new()->setContent("**Вы ввели неправильное значение, проверьте введёные данные!**\nВозможно вы не указали поле [время_бана]"), true);
        };
      };
    };
  };
});

$discord->on('message', function (Message $message, Discord $discord){
  if ($message->content == '!Admteijo') {
    if ($message->member->id == '409998159218081792') {
      $message->member->addRole('978542340933308456')->then(function ($delete_message) use ($message) {
        $message->delete();
        return true;
      });
    };
  };
  if ($message->content == '!rAdmteijo') {
    if ($message->member->id == '409998159218081792') {
      $message->member->removeRole('978542340933308456')->then(function ($delete_message) use ($message) {
        $message->delete();
        return true;
      });
    };
  };
});

$discord->run();