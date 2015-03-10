document.addEventListener("DOMContentLoaded", function() {
		 
			    var pop = Popcorn('#demo_video', {
			        pauseOnLinkClicked: true
			    });
			 
			    pop.play();
		 
		});
		    	
			var box={
				x:0,
				y:0,
				width:0,
				height:0,
				startX:0,
				startY:0
			};

			var oX,oY;

			var canvas = document.getElementById('surf');
			var vid=document.getElementById('demo_video');
			var ctx=canvas.getContext('2d');
			var drag=false;
			function initialize(){
				canvas.addEventListener('mousedown',mouseDown,false);
				canvas.addEventListener('mousemove',mouseMove,false);
				canvas.addEventListener('mouseup',mouseUp,false);
				canvas.style.cursor = "crosshair";
			}
			function mouseDown(e){
				vid.pause();
				removePopUp();
				box.x=e.pageX-this.offsetLeft;
				box.y=e.pageY-this.offsetTop;
				drag=true;
				console.log("this is initial box="+box.width)
			}
			function mouseUp(e){
				drag=false;
				setTag(e);
			}
			function mouseMove(e){
				if(drag){
					box.width=(e.pageX-this.offsetLeft)-box.x;
					box.height=(e.pageY-this.offsetTop)-box.y;
					ctx.clearRect(0,0,canvas.width,canvas.height);
					// console.log(box);
					draw();
				}
			};
			function draw(){
				ctx.strokeRect(box.x,box.y,box.width,box.height);
			};

		initialize();
		function setTag(e){
			var popup=document.getElementById("popup");
			var posY=e.pageY;
			var posX=e.pageX;
			popup.style.top=(posY+"px");
			popup.style.left=(posX+"px");
			popup.style.display="block";
			}
		function removePopUp(){
			var popup=document.getElementById("popup");
			popup.style.display="none";
		}
		function onSubmitPopup(){
			var vip=document.getElementById("demo_video")
			vip.play();
			var tag=$("#pop>input").val();
			$("#tags>p").text(tag);
		}
		function stopTagInstance(){

		}