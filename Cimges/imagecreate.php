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
  $imageIshod = __DIR__.'/images/lvlimage_4.png';
  $imageFont = __DIR__.'/font/ofont.ru_Bowler.ttf';
  $progressbar = [
    '1' => __DIR__.'/images/100%_2.png',
    '2' => __DIR__.'/images/80%_2.png',
    '3' => __DIR__.'/images/60%_2.png',
    '4' => __DIR__.'/images/40%_2.png',
    '5' => __DIR__.'/images/20%_2.png',
    '6' => __DIR__.'/images/10%_2.png',
    '7' => __DIR__.'/images/0%_2.png'
  ];
  $image_f = imageCreateTrueColor(934, 282);
  $black = imageColorAllocate($image_f, 0, 0, 0);
  imagecolortransparent($image_f, $black);
  $white = imagecolorallocate($image_f, 255, 255, 255);
  $silver = imagecolorallocate($image_f, 128, 128, 128);
  $gold = imagecolorallocate($image_f, 255, 215, 0);
  $red = imagecolorallocate($image_f, 255, 0, 0);
  resizeimg($imageuser);
  $logouser = imagecreatefrompng(__DIR__ . '/reszimage.png');
  imagecopy($image_f, $logouser, 0, 0, 0, 0, 282, 292);
  $fonlogo = imagecreatefrompng($imageIshod);
  imagecopy($image_f, $fonlogo, 0, 0, 0, 0, 934, 282);
  if (mb_strlen($nameuser,'UTF-8') > 15 AND mb_strlen($nameuser,'UTF-8') < 18){
    imagettftext($image_f, 20, 0, 266, 91, $white, $imageFont, $nameuser);
  }elseif(mb_strlen($nameuser,'UTF-8') > 17){
    imagettftext($image_f, 10, 0, 266, 91, $white, $imageFont, $nameuser);
  }else{
    imagettftext($image_f, 30, 0, 266, 91, $white, $imageFont, $nameuser);
  };
  if ($nameuser != $hrefuser){
    imagettftext($image_f, 22, 0, 266, 136, $silver, $imageFont, '# '.$hrefuser);
  }
  imagettftext($image_f, 26, 0, 266, 196, $gold, $imageFont, 'МЕСТО № '.$rankuser);
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
  logolvl($barlvl, $imageFont, $lvluser, $expuser, $max_expuser);
  $starimg = imagecreatefrompng(__DIR__ . '/barimage.png');
  imagecopy($image_f, $starimg, 678, 42, 0, 0, 200, 190);
  imagepng($image_f, __DIR__ . '/lvlimage.png', 9);
  imagedestroy($image_f);
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

function resizeimg($filename){
  $width = 282;
  $height = 282;
  $size = getimagesize($filename);
  $width_orig = $size['0'];
  $height_orig = $size['1'];
  $type_orig = $size['mime'];
  $image_p = imagecreatetruecolor($width, $height);
  $white = imagecolorallocate($image_p, 255, 255, 255);
  if ($type_orig == 'image/gif'){
    $image = imagecreatefromgif($filename);
  }elseif ($type_orig == 'image/png'){
    $image = imagecreatefrompng($filename);
  }else{
    $image = imagecreatefromwebp($filename);
  }
  imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
  imagepng($image_p, __DIR__ . '/reszimage.png', 9);
  imagedestroy($image_p);
}

function logolvl($bar, $Font, $lvluser, $expuser, $max_expuser){
  $image_i = imagecreatetruecolor(200, 190);
  imagealphablending($image_i, false);
  imagesavealpha($image_i, true);
  $white = imagecolorallocate($image_i, 255, 255, 255);
  imagecopy($image_i, $bar, 0, 0, 0, 0, 200, 190);
  $CENTER = 98;
  $lvl_box = imagettfbbox(22, 0, $Font, $lvluser);
  $left_1 = $CENTER-round(($lvl_box[2]-$lvl_box[0])/2);
  imagettftext($image_i, 22, 0, $left_1, 105, $white, $Font, $lvluser);
  $CENTER_2 = 100;
  $user_exp = "{$expuser} / {$max_expuser}";
  $lvl_box = imagettfbbox(6, 0, $Font, $user_exp);
  $left_2 = $CENTER_2-round(($lvl_box[2]-$lvl_box[0])/2);
  imagettftext($image_i, 6, 0, $left_2, 130, $white, $Font, $user_exp);
  imagepng($image_i, __DIR__ . '/barimage.png', 9);
  imagedestroy($image_i);
}
