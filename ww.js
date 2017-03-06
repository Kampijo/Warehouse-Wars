// Stage
// Note: Yet another way to declare a class, using .prototype.

function Stage(width, height, stageElementID){
	this.actors=[]; // all actors on this stage (monsters, player, boxes, ...)
	this.player=null; // a special actor, the player

	// the logical width and height of the stage
	this.width=width;
	this.height=height;

	// the element containing the visual representation of the stage
	this.stageElementID=stageElementID;

	// take a look at the value of these to understand why we capture them this way
	// an alternative would be to use 'new Image()'
	this.blankImageSrc=document.getElementById('blankImage').src;
	this.monsterImageSrc=document.getElementById('monsterImage').src;
	this.playerImageSrc=document.getElementById('playerImage').src;
	this.boxImageSrc=document.getElementById('boxImage').src;
	this.wallImageSrc=document.getElementById('wallImage').src;

	this.imgWidth = document.getElementById('boxImage').clientWidth;
	this.imgHeight = document.getElementById('boxImage').clientHeight;

	this.centerWidth = Math.floor(this.width/2);
	this.centerHeight = Math.floor(this.height/2);
}

// initialize an instance of the game
Stage.prototype.initialize=function(){
	// Create a table of blank images, give each image an ID so we can reference it later
	var s='<table style="border-collapse:collapse;">';

	for(i=0; i < this.height; i++){
		s+="<tr>";
		for(j=0; j < this.width; j++){
			var rand = Math.random();
			var type = "";
			if(i != this.centerHeight || j != this.centerWidth){
				if(i == 0 || j == 0 || i == this.height-1 || j == this.width-1){
					s+="<td><img id="+i+","+j+" src="+this.wallImageSrc+" width="+this.imgWidth+" height="+this.imgHeight+" /></td>";
				} else if(rand < 0.1){
					s+="<td><img id="+i+","+j+" src="+this.monsterImageSrc+" /></td>";
					type="monster";
					this.addActor({"type":type, "x":j, "y":i});
				} else if(rand < 0.3){
					s+="<td><img id="+i+","+j+" src="+this.boxImageSrc+" /></td>";
					type="box";
					this.addActor({"type":type, "x":j, "y":i});
				} else {
					s+="<td><img id="+i+","+j+" src="+this.blankImageSrc+" /></td>";
				}
			} else {
				type="player";
				s+="<td><img id="+i+","+j+" src="+this.playerImageSrc+" /></td>";
				this.addActor({"type":type, "x":j, "y":i});
			}
		}
		
		s+="</tr>";
	}

	s+="</table>";
	document.getElementById(this.stageElementID).innerHTML=s;


}
// Return the ID of a particular image, useful so we don't have to continually reconstruct IDs
Stage.prototype.getStageId=function(x,y){ return y+","+x; }

Stage.prototype.addActor=function(actor){
	this.actors.push(actor);
}

Stage.prototype.removeActor=function(actor){
	// Lookup javascript array manipulation (indexOf and splice).
	this.actors.splice(this.actors.indexOf(actor),1);
}

// Set the src of the image at stage location (x,y) to src
Stage.prototype.setImage=function(x, y, src){
	document.getElementById(this.getStageId(x,y)).src=src;
}

// Take one step in the animation of the game.  
Stage.prototype.step=function(){
	for(var i=0;i<this.actors.length;i++){
		var actor = this.actors[i];
		if(actor["type"]=="box"){
			this.setImage(actor["x"],actor["y"],this.boxImageSrc);
		} else if(actor["type"]=="monster"){
			this.setImage(actor["x"],actor["y"],this.monsterImageSrc);
		} else {
			this.setImage(actor["x"],actor["y"],this.playerImageSrc);
		}
	}
}

Stage.prototype.getRandomInt=function(min, max){
	return Math.floor(Math.random() * (max - min + 1)) + min;
}
Stage.prototype.randomMove=function(){
	move = this.getRandomInt(0,1);
	
	if(Math.random() < 0.5){
		move = -move;
	}
	return move;
}
Stage.prototype.checkMovement=function(x,y){
	for(var i = -1; i <= 1; i++){
		for(var j = -1; j <= 1; j++){
			if(i !=0 || j != 0){
				if(this.check(x+i,y+j) && (this.getActor(x+i,y+j) == null || this.getActor(x+i, y+j)["type"]=="player")){
					return true;
				}
			}
		}
	} 
	return false;
	
}
Stage.prototype.moveMonsters=function(){
	for(var i=0;i<this.actors.length;i++){
		if(this.actors[i]["type"]=="monster"){
			xDir = this.randomMove();
			yDir = this.randomMove();
			actor = this.actors[i];
			nextCell = this.getActor(actor["x"]+xDir,actor["y"]+yDir);
			if(this.checkMovement(actor["x"], actor["y"])){ 
				if(this.check(actor["x"]+xDir, actor["y"]+yDir) && (nextCell == null || nextCell["type"] == "player")){
    				this.setImage(actor["x"], actor["y"], this.blankImageSrc);
    				this.actors[i]["y"]+=yDir;
    				this.actors[i]["x"]+=xDir;

    				this.setImage(this.actors[i]["x"], this.actors[i]["y"], this.monsterImageSrc);
    				if(nextCell != null && nextCell["type"] == "player"){
    					clearInterval(interval);
						interval = null;
    					alert("GAME OVER");
    				}
                }
			} else {
				this.removeActor(actor);
				this.setImage(actor["x"], actor["y"], this.blankImageSrc);				
                console.log("MONSTER KILLED at "+actor["x"]+", "+actor["y"]);
			}	
		}
	}
}

// return the first actor at coordinates (x,y) return null if there is no such actor
// there should be only one actor at (x,y)!
Stage.prototype.getActor=function(x, y){
	for(var i = 0; i<this.actors.length;i++){
		if(this.actors[i]["x"]==x && this.actors[i]["y"]==y){
			return this.actors[i];
		}
	}
	return null;
}

Stage.prototype.check=function(y, x){
	if (x <= 0 || y <= 0) return false;
	if (x >= this.width-1 || y >= this.height-1) return false;
	return true;
}

Stage.prototype.movePlayer=function(direction){
		for(var i = 0; i<this.actors.length;i++){
			if(this.actors[i]["type"]=="player"){
				var actor = this.actors[i];
				var playerIndex = i;
				var xDir = 0;
				var yDir = 0;
				break;
			}
		}
		
			this.setImage(actor["x"], actor["y"], this.blankImageSrc);
			switch (direction){
				case 'N':
					if(this.check(actor["y"]-1, actor["x"])){
						yDir--;
					}
					break;
				case 'S':
					if(this.check(actor["y"]+1, actor["x"])){
						yDir++;
					}
					break;
				case 'W':
					if(this.check(actor["y"], actor["x"]-1)){
						xDir--;
					}
					break;
				case 'E':
					if(this.check(actor["y"], actor["x"]+1)){
						xDir++;
					}
					break;
				case 'NW':
					if(this.check(actor["y"]-1, actor["x"]-1)){
						xDir--;
						yDir--;
					}
					break;
				case 'NE':
					if(this.check(actor["y"]-1, actor["x"]+1)){
						xDir++;
						yDir--;
					}
					break;
				case 'SW':
					if(this.check(actor["y"]+1, actor["x"]-1)){
						xDir--;
						yDir++;
					}
					break;
				case 'SE':
					if(this.check(actor["y"]+1, actor["x"]+1)){
						xDir++;
						yDir++;
					}
					break;
			}
			if(xDir != 0 || yDir != 0){
				if(this.moveBoxes(actor["x"]+xDir, actor["y"]+yDir, direction)){
					this.actors[playerIndex]["x"]+=xDir;
					this.actors[playerIndex]["y"]+=yDir;
				}	 
			}	
}

Stage.prototype.moveBoxes=function(x, y, direction){

	var actor = this.getActor(x, y);
	var index = this.actors.indexOf(actor);
	if(actor == null){
		return true;
	}
	if(actor["type"] == "monster"){
		return false;
	}
	var xDir = 0;
	var yDir = 0;
		
	switch (direction){
			case 'N':
				if(this.check(actor["y"]-1, actor["x"])){
					yDir--;
				}
				break;
			case 'S':
				if(this.check(actor["y"]+1, actor["x"])){
					yDir++;
				}
				break;
			case 'W':
				if(this.check(actor["y"], actor["x"]-1)){
					xDir--;
				}
				break;
			case 'E':
				if(this.check(actor["y"], actor["x"]+1)){
					xDir++;
				}
				break;
			case 'NW':
				if(this.check(actor["y"]-1, actor["x"]-1)){
					xDir--;
					yDir--;
				}
				break;
			case 'NE':
				if(this.check(actor["y"]-1, actor["x"]+1)){
					xDir++;
					yDir--;
				}
				break;
			case 'SW':
				if(this.check(actor["y"]+1, actor["x"]-1)){
					xDir--;
					yDir++;
				}
				break;
			case 'SE':
				if(this.check(actor["y"]+1, actor["x"]+1)){
					xDir++;
					yDir++;
				}
				break;
			}

	if(xDir != 0 || yDir != 0){
	if(this.moveBoxes(actor["x"]+xDir, actor["y"]+yDir, direction)){
		this.actors[index]["x"]+=xDir;
		this.actors[index]["y"]+=yDir;
		return true;
	} else {
		return false;
	}
	} else {
		return false;
	}
	
}
// End Class Stage
