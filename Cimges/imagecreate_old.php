<?php

date_default_timezone_set('Europe/Moscow');

function creatgraf(){
  $image = imageCreateTrueColor(600, 400);
  $rectangle = imageCreateTrueColor(100, 100);
  $black = imageColorAllocate($image, 0, 0, 0); // Создание цвета для изображения
  imagecolortransparent($image, $black); // прозрачный фон
  imagefilledrectangle($image, 0, 0, 600, 400, $black); // Рисование закрашенного прямоугольника (фон)
  $whitea = imagecolorallocatealpha($image, 255, 255, 255, 80); // Создание цвета для изображения
  $white = imageColorAllocate($image, 255, 255, 255); // Создание цвета для изображения
  $bluea = imagecolorallocatealpha($image, 92, 175, 240, 80);
  $blue = imagecolorallocatealpha($image, 92, 175, 240, 50);
  imagefilledrectangle($image, 290, 75, 210, 50, $bluea); // Рисование закрашенного прямоугольника (обозначение)
  $font = __DIR__ . '/timesnrcyrmt_bold.ttf';
  imagettftext($image, 15, 0, 300, 70, $blue, $font, 'Онлайн');
  imageLine($image, 30, 350, 560, 350, $white); //ось по X
  imagettftext($image, 8, 0, 40, 368, $white, $font, '00:00'); //цифры на оси x
  imagettftext($image, 8, 0, 80, 368, $white, $font, '02:00'); //цифры на оси x
  imagettftext($image, 8, 0, 120, 368, $white, $font, '04:00'); //цифры на оси x
  imagettftext($image, 8, 0, 160, 368, $white, $font, '06:00'); //цифры на оси x
  imagettftext($image, 8, 0, 200, 368, $white, $font, '08:00'); //цифры на оси x
  imagettftext($image, 8, 0, 240, 368, $white, $font, '10:00'); //цифры на оси x
  imagettftext($image, 8, 0, 280, 368, $white, $font, '12:00'); //цифры на оси x
  imagettftext($image, 8, 0, 320, 368, $white, $font, '14:00'); //цифры на оси x
  imagettftext($image, 8, 0, 360, 368, $white, $font, '16:00'); //цифры на оси x
  imagettftext($image, 8, 0, 400, 368, $white, $font, '18:00'); //цифры на оси x
  imagettftext($image, 8, 0, 440, 368, $white, $font, '20:00'); //цифры на оси x
  imagettftext($image, 8, 0, 480, 368, $white, $font, '22:00'); //цифры на оси x
  imagettftext($image, 8, 0, 520, 368, $white, $font, '24:00'); //цифры на оси x
  imageLine($image, 53, 100, 53, 350, $whitea); //засечки на оси x
  imageLine($image, 93, 100, 93, 350, $whitea); //засечки на оси x
  imageLine($image, 133, 100, 133, 350, $whitea); //засечки на оси x
  imageLine($image, 173, 100, 173, 350, $whitea); //засечки на оси x
  imageLine($image, 213, 100, 213, 350, $whitea); //засечки на оси x
  imageLine($image, 253, 100, 253, 350, $whitea); //засечки на оси x
  imageLine($image, 293, 100, 293, 350, $whitea); //засечки на оси x
  imageLine($image, 333, 100, 333, 350, $whitea); //засечки на оси x
  imageLine($image, 373, 100, 373, 350, $whitea); //засечки на оси x
  imageLine($image, 413, 100, 413, 350, $whitea); //засечки на оси x
  imageLine($image, 453, 100, 453, 350, $whitea); //засечки на оси x
  imageLine($image, 493, 100, 493, 350, $whitea); //засечки на оси x
  imageLine($image, 533, 100, 533, 350, $whitea); //засечки на оси x

  imageLine($image, 30, 100, 30, 350, $white); //ось по Y
  imagettftext($image, 10, 0, 15, 365, $white, $font, '0'); //цифры на оси y
  imagettftext($image, 10, 0, 10, 330, $white, $font, '10'); //цифры на оси y
  imagettftext($image, 10, 0, 10, 300, $white, $font, '20'); //цифры на оси y
  imagettftext($image, 10, 0, 10, 270, $white, $font, '30'); //цифры на оси y
  imagettftext($image, 10, 0, 10, 240, $white, $font, '40'); //цифры на оси y
  imagettftext($image, 10, 0, 10, 210, $white, $font, '50'); //цифры на оси y
  imagettftext($image, 10, 0, 10, 180, $white, $font, '60'); //цифры на оси y
  imagettftext($image, 10, 0, 10, 150, $white, $font, '70'); //цифры на оси y
  imagettftext($image, 10, 0, 10, 120, $white, $font, '80'); //цифры на оси y
  imageLine($image, 30, 330, 560, 330, $whitea); //засечки на оси y
  imageLine($image, 30, 300, 560, 300, $whitea); //засечки на оси y
  imageLine($image, 30, 270, 560, 270, $whitea); //засечки на оси y
  imageLine($image, 30, 240, 560, 240, $whitea); //засечки на оси y
  imageLine($image, 30, 210, 560, 210, $whitea); //засечки на оси y
  imageLine($image, 30, 180, 560, 180, $whitea); //засечки на оси y
  imageLine($image, 30, 150, 560, 150, $whitea); //засечки на оси y
  imageLine($image, 30, 120, 560, 120, $whitea); //засечки на оси y

  $key_x_player = [
    '0' => '349',
    '5' => '340',
    '10' => '330',
    '15' => '315',
    '20' => '300',
    '25' => '285',
    '30' => '270',
    '35' => '255',
    '40' => '240',
    '45' => '225',
    '50' => '210',
    '55' => '195',
    '60' => '180',
    '65' => '165',
    '70' => '150',
    '75' => '135',
    '80' => '120',
  ];

  $points = array(
    array('x' => 53, 'y' => 349), // 00:00
    array('x' => 93, 'y' => $key_x_player['15']), // 02:00
    array('x' => 133, 'y' => 268), // 04:00
    array('x' => 173, 'y' => 198), // 06:00
    array('x' => 213, 'y' => 349), // 08:00
    array('x' => 253, 'y' => 298), // 10:00
    array('x' => 293, 'y' => 298), // 12:00
    array('x' => 333, 'y' => 168), // 14:00
    array('x' => 373, 'y' => 198), // 16:00
    array('x' => 413, 'y' => 198), // 18:00
    array('x' => 453, 'y' => 298), // 20:00
    array('x' => 493, 'y' => 278), // 22:00
    array('x' => 533, 'y' => 298) // 24:00
  );
  $blue = imageColorAllocate($image, 92, 175, 240); //цвет графика
  imageSetThickness($image, 2); //толщина линий
  $num_points = count($points);
  for ($i = 0; $i <= $num_points - 2; $i++) {
    imageLine($image, $points[$i]['x'], $points[$i]['y'], $points[$i + 1]['x'], $points[$i + 1]['y'], $blue);
  }

  imagepng($image); // Вывод PNG изображения в браузер или файл
  imagepng($image, __DIR__ . '/image.png'); // Вывод PNG изображения в браузер или файл
  imagedestroy($image); // Уничтожение изображения
}

function creatlvl($nameuser, $hrefuser, $rankuser, $lvluser, $expuser, $max_expuser, $imageuser){
  $imageIshod = __DIR__.'/images/lvlimage.png';
  $imageFont = __DIR__.'/font/ofont.ru_Bowler.ttf';

  $progressbar = [
    '1' => __DIR__.'/images/100%.png',
    '2' => __DIR__.'/images/80%.png',
    '3' => __DIR__.'/images/60%.png',
    '4' => __DIR__.'/images/40%.png',
    '5' => __DIR__.'/images/20%.png',
    '6' => __DIR__.'/images/10%.png',
    '7' => __DIR__.'/images/0%.png'
  ];

  $image = imagecreatefrompng($imageIshod);

  $white = imagecolorallocate($image, 255, 255, 255);
  $silver = imagecolorallocate($image, 128, 128, 128);
  $gold = imagecolorallocate($image, 255, 215, 0);
  $red = imagecolorallocate($image, 255, 0, 0);

  $content = file_get_contents($imageuser);
  $finfo = new finfo(FILEINFO_MIME_TYPE);
  $type =  $finfo->buffer($content);
  if ($type == 'image/gif'){
    $logouser = imagecreatefromgif($imageuser);
  }elseif ($type == 'image/png'){
    $logouser = imagecreatefrompng($imageuser);
  }else{
    $logouser = imagecreatefromwebp($imageuser);
  }

  $barbox =  procexp($expuser, $max_expuser);
  if ($barbox < 9) {
    $barlvl = imagecreatefrompng($progressbar[1]);
  }elseif($barbox < 19){
    $barlvl = imagecreatefrompng($progressbar[2]);
  }elseif($barbox < 39){
    $barlvl = imagecreatefrompng($progressbar[3]);
  }elseif($barbox < 59){
    $barlvl = imagecreatefrompng($progressbar[4]);
  }elseif($barbox < 79){
    $barlvl = imagecreatefrompng($progressbar[5]);
  }elseif($barbox < 99){
    $barlvl = imagecreatefrompng($progressbar[6]);
  }else{
    $barlvl = imagecreatefrompng($progressbar[7]);
  };

  // $posHreph = 240;
  $posEXp = 648;
  $posM = 530;
  $posrank = 640;

  imagecopy($image, $logouser, 50, 80, 0, 0, 128, 128);

  if (mb_strlen($nameuser,'UTF-8') > 15){
    imagettftext($image, 10, 0, 200, 161, $white, $imageFont, $nameuser);
  }else{
    imagettftext($image, 20, 0, 200, 161, $white, $imageFont, $nameuser);
  };

  // if (mb_strlen($nameuser,'UTF-8') > 15){
  //   for ($replay = 1; $replay < mb_strlen($nameuser,'UTF-8'); $replay++){
  //     if ($replay == 1){
  //       $posHreph = ($posHreph + 10);
  //     }else{
  //       $posHreph = ($posHreph + 19);
  //     }
  //     $replay++;
  //   };
  //   imagettftext($image, 10, 0, 200, 161, $white, $imageFont, $nameuser);
  //   imagettftext($image, 5, 0, $posHreph, 161, $silver, $imageFont, '#'.$hrefuser);
  // }else{
  //   for ($replay = 1; $replay < mb_strlen($nameuser,'UTF-8'); $replay++){
  //     if ($replay == 1){
  //       $posHreph = ($posHreph + 20);
  //     }else{
  //       $posHreph = ($posHreph + 56);
  //     }
  //     $replay++;
  //   };
  //   imagettftext($image, 20, 0, 200, 161, $white, $imageFont, $nameuser);
  //   imagettftext($image, 10, 0, $posHreph, 161, $silver, $imageFont, '#'.$hrefuser);
  // };

  for ($replay = 2; $replay < strlen($expuser); $replay++){
    $posEXp = ($posEXp - 25);
    $replay++;
  };

  if (strlen($rankuser) > 2){
    for ($replay = 2; $replay < strlen($rankuser); $replay++){
      $posM = ($posM - 45);
      $posrank = ($posrank - 45);
      $replay++;
    };
  };

  imagettftext($image, 18, 0, $posM, 85, $gold, $imageFont, 'Место');
  imagettftext($image, 22, 0, $posrank, 85, $gold, $imageFont, '#'.$rankuser);
  imagettftext($image, 18, 0, 730, 85, $red, $imageFont, 'Уровень '.$lvluser);
  imagettftext($image, 18, 0, $posEXp, 161, $white, $imageFont, $expuser);
  imagettftext($image, 20, 0, 695, 161, $silver, $imageFont, '/ '.$max_expuser.' XP');
  imagecopy($image, $barlvl, 250, 190, 0, 0, 512, 43);

  imagepng($image, __DIR__ . '/lvlimage.png', 9); // Вывод PNG изображения в браузер или файл
  imagedestroy($image); // Уничтожение изображения
}

function procexp($expuser, $max_expuser){
  if ($expuser < $max_expuser) {
    $diff = $max_expuser - $expuser;
    $percent = round($diff / $max_expuser * 100, 0);
    return $percent;
  }else{
    return "-1";
  };
}