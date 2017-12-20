/*
 * final.java
 * 
 * Copyright 2016 Chelsey <Chelsey@CHELSEY-VAIO>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */
	

	
	var turn = 1;
function CurrentPlayer(){

	if (turn = 1){
		
		
		//spot to put the name.
		var name = document.getElementById("name");
		//var playerid = "does it work";
		var playerid = document.getElementById("$firstplayer");
		name.innerHTML = playerid; 
	}
	
	if (turn = 2){
		document.getElementById("secondplayer");
	}
	
	if (turn = 3){
		document.getElementById("thirdplayer");
	}
	
	if (turn = 4){
		document.getElementById("fourthplayer");
	}
	
	if (turn = 5){
		document.getElementById("fifthplayer");
	}
	
	if (turn = 6){
		document.getElementById("sixthplayer");
	}
	
	if (turn = 7){
		document.getElementById("seventhplayer");
	}
	
	if (turn = 8){
		document.getElementById("eighthplayer");
	}
	
	if (turn = 9){
		document.getElementById("ninthplayer");
	}
	
	if (turn = 10){
		document.getElementById("tenthplayer");
	}
	
	if (turn = 11){
		document.getElementById("eleventhplayer");
	}
	
	if (turn = 12){
		document.getElementById("twelfthplayer");
	}




}

function EndTurn(){
	turn = turn + 1;
	CurrentPlayer();
	
}
function PictureChange(){
	var image=document.getElementById("");
	image.src=""

}
