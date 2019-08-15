var express = require('express');
var app = express();

app.set('view engine', 'pug');

app.use(express.static('public'));

app.get('/', function (req, res) {
	res.render('index')
})

app.get('/holiwis', function (req, res){
	res.send('qtuverga mmgvo')
})

app.listen(4000, function(err){
	if(err) return console.log('Hubo un error'), process.exit(1);
	console.log("Se ejecuta en el puerto 4000");
})