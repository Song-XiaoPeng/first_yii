<?php 
use lib\widgets\AreaDecorator;
use common\components\Block;
// @todo framework 改为可配置
$framework = 'nifty';
$dir = __DIR__ . '/' . $framework . '/';

AreaDecorator::begin([
    'viewFile'=>'@apps/system/views/layouts/' . $framework . '/layout.php',
    'content' => $content,
]);
$areas = ['cssFile', 'jsFile', 'header', 'pageTitle', 'pageContent', 'left', 'footer'];
foreach ($areas as $area) {
    $file = $dir . $area . '.php';
    if (is_file($file)) {
        $this->beginBlock($area); 
        include $dir . $area . '.php';
        $this->endBlock(); 
    }
}
AreaDecorator::end();
