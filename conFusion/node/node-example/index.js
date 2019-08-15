var rect = require("./rect.js");

function solveRect(x,y){
	console.log("For x = " + x +  " and y = " + y);
	rect(x, y, (err, rectangle) => {
		if(err){
			console.log("ERROR: " + err.message);
		}
		else{
			console.log("Area = " + rectangle.area() + " and Perimeter = " + rectangle.perimeter())
		}
	});
	console.log("After function");
}

solveRect(5,9);
solveRect(7,2);
solveRect(-5,4);
solveRect(8,0);