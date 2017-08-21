<?php 
use lib\widgets\AreaDecorator;
// @todo framework 改为可配置
$framework = 'nifty';
$dir = __DIR__ . '/' . $framework . '/';
?>

<?php AreaDecorator::begin([
    'viewFile'=>'@app/views/backend/layouts/' . $framework . '/login.php',
    'content' => $content,
])?>
<?php AreaDecorator::end();?>
