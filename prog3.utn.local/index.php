<?php
/**
* Index dinámico para Programación III - UTN
*/
$directory = '.';   // directory actual
$items = scandir($directory); //Acá está la lista de lo que hay en el directorio.
/*
echo $items;
printf("<p> %s", $items);
foreach($items as $unelemento) echo $unelemento . "<br>";
*/


// Filtros para ignorar archivos de sistema y configuración
$ignorados = ['.', '..', '.git', '.gitignore', 'index.php', 'ejer1','Readme.md', 'docker-compose.yml', 'configNginx',
'baseDatosWeb','CSS'];


$folders = [];
$files = [];

foreach ($items as $item) {
if (in_array($item, $ignorados) || str_starts_with($item, '_') ) continue;
if (is_dir($directory . '/' . $item)) {
$folders[] = $item;
} else {
$files[] = $item;
}
}

natcasesort($folders);
natcasesort($files);
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>UTN - Programación III</title>
<style>
:root { 
    --primary: #003f5c;
    --accent: #ffa600;
    --bg: #f4f7f6;
    --card: #ffffff;
}
body { font-family: 'Segoe UI', system-ui, sans-serif; background: var(--bg); margin: 0; padding:
20px; }
.container { max-width: 1000px; margin: 0 auto; }
header { background: var(--primary); color: white; padding: 30px; border-radius: 12px; text-align:
center; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }

.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px;

margin-top: 30px; }

.card { background: var(--card); padding: 20px; border-radius: 10px; text-decoration: none; color:

#333; display: flex; align-items: center; transition: 0.3s; border-left: 5px solid var(--accent);
box-shadow: 0 2px 5px rgba(0,0,0,0.05); }

.card:hover { transform: translateY(-8px); box-shadow: 0 8px 20px rgba(0,0,0,1); }
.icon { font-size: 24px; margin-right: 15px; }
h2 { border-left: 5px solid var(--primary); padding-left: 10px; color: var(--primary); margin-top:
40px; }
footer { margin-top: 50px; text-align: center; color: #888; font-size: 0.9em; }
.L1 {
        font-family:Verdana, Geneva, Tahoma, sans-serif;
        font-size: 20px;
    }
</style>
</head>
<body>
<div class="container">
<header>
<h1>Programación III</h1>
<p>Dashboard de Clases y Recursos - UTN</p>
</header>


<p ><a class="L1" href="phpMyAdmin5/">phpMyAdmin</a></p>
<h2>Carpetas de Clases</h2>
<div class="grid">
<?php foreach ($folders as $f): ?>

<a href="<?= $f ?>/" class="card">
<span class="icon">📁</span>
<div><strong><?= $f ?></strong><br><small>Directorio de clase</small></div>
</a>
<?php endforeach; ?>
</div>

<h2>Archivos Sueltos</h2>
<div class="grid">
<?php foreach ($files as $file): ?>
<a href="<?= $file ?>" class="card" style="border-left-color: #2196F3;">
<span class="icon">📄</span>
<div><strong><?= $file ?></strong></div>
</a>
<?php endforeach; ?>
</div>

<footer>Generado automáticamente por el Servidor Nginx/PHP local</footer>
</div>
</body>
</html>