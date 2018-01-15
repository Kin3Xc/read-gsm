const express = require('express');
const bodyParser = require('body-parser');
const app = express();

// configurar body parser para leer el cuepor de las peticiones
app.use(bodyParser.json({}));
app.use(bodyParser.urlencoded({extended: false}));

app.get('/', ( req, res )=>{
	res.status(200);
	res.send("Hola, estas conectado al servidor de pruebas");
});

app.get('/:latitude/:longitude', ( req, res )=>{
	console.log(req.params.latitude);
	console.log(req.params.longitude);
	res.status(200);
	res.json({mensaje:"Datos recibidos", data:req.params.dat});
});

app.post('/', ( req, res )=>{
	console.log(req.body);
	res.status(200);
	res.json({mensaje:"Datos recibidos", data:req.body});
});

// para servir archivos estaticos
// app.use(express.static('public'));

// levanta el server
app.listen(4201, function(){
	console.log('Estoy listo para recibir peticiones');
});