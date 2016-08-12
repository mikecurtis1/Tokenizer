<?php header('Content-Type: text/html; charset=utf-8'); ?>
<html>
<head>
<link 
    rel="stylesheet" 
    type="text/css" 
    href="http://fonts.googleapis.com/css?family=Roboto|Roboto+Condensed|Raleway|Tinos|Volkhov|Alice" />
<link rel="stylesheet" type="text/css" media="screen" href="main.css" >
<meta name="viewport" content="width=device-width, initial-scale=1" />
</head>
<body>
<h1>Tokenizer</h1>
<form action="index.php" method="get">
<textarea name="q"><?php echo htmlspecialchars($q); ?></textarea>
<div><input type="submit" value="submit" /></div>
</form>
<hr />
<pre>
TOKENS: 

<?php echo var_dump($tokens); ?>
</pre>
<hr />
<pre>
Tokenizer OBJECT: 

<?php echo var_dump($tokenizer); ?>
</pre>
</body>
</html>
