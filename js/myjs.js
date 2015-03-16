document.addEventListener("DOMContentLoaded", function () {

    var pop = Popcorn('#demo_video', {
        pauseOnLinkClicked: true
    });

    pop.play();

});

jQuery('#play_btn').click(function(){
    playVid();
});
jQuery('#editVid_btn').click(function(){
    editVid();
});
/**
 * mode - used to determine the event that should be present
 */
var mode={
    edit:false
};
/**
 * A collection of tag for a video
 * @type {Array}
 */
var Tags=[];
/**
 * Tag object
 * @type {{}}
 */
var Tag = {
        startTime:0,
        endTime:0,
        tagInfo:"",
        tagBox: {
            x:0,
            y:0,
            width:0,
            height:0
        },
        setTagBox:function(sbox){
            this.tagBox.x=sbox.x;
            this.tagBox.y=sbox.y;
            this.tagBox.width=sbox.width;
            this.tagBox.height=sbox.height;
        }
};
/**
 * This is a the box that is draw by the user to tag an area on the video
 * @type {{x: number, y: number, width: number, height: number, startX: number, startY: number}}
 */
var box = {
    x: 0,
    y: 0,
    width: 0,
    height: 0,
    startX: 0,
    startY: 0
};

var hotspot=document.createElement("div");
// hotspot.style.display='none';
var canvas = document.getElementById('surf');
var vid = document.getElementById('demo_video');
var ctx = canvas.getContext('2d');
var drag = false;
var isShownTag=false;
/**
 * A function that is used to initialize the canvas event listeners
 */
function initialize() {
    if(mode.edit){
        canvas.addEventListener('mousedown', mouseDown, false);
        canvas.addEventListener('mousemove', mouseMove, false);
        canvas.addEventListener('mouseup', mouseUp, false);
        canvas.style.cursor = "crosshair";
        return;
    }

    vid.addEventListener('timeupdate',tagRun,false);

}
/**
 * the mousedown event function for the canvas that creates the starting point of the tag rectangle
 * @param e
 */
function mouseDown(e) {
    vid.pause();
    removePopUp();
    box.x = e.pageX - this.offsetLeft;
    box.y = e.pageY - this.offsetTop;
    drag = true;
}
/**
 * the mouseup event function for the canvas that pops up the tag edit box
 * @param e
 */
function mouseUp(e) {
    drag = false;
    Tag.startTime=vid.currentTime;
    Tag.setTagBox(box);
    console.log(Tag.tagBox);
    showPop(e);
}
/**
 * a mouse move event on the canvas that draws the tag rectangle based on user's
 * mouse movement
 * @param e
 */
function mouseMove(e) {
    if (drag) {
        box.width = (e.pageX - this.offsetLeft) - box.x;
        box.height = (e.pageY - this.offsetTop) - box.y;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        // var body=document.querySelector("body");
        // body.removeChild('');
        //console.log(box);
        draw(box);
    }
}
/**
 * A function to actually draw the tag rectangle
 */
function draw(useBox) {
   

    ctx.strokeRect(useBox.x,useBox.y, useBox.width, useBox.height);
}

initialize();
/**
 * function to display the popup at the particular event position
 * @param e
 */
function showPop(e) {
    var popup = document.getElementById("popup");
    var posY = e.pageY;
    var posX = e.pageX;
    popup.style.top = (posY + "px");
    popup.style.left = (posX + "px");
    popup.style.display = "block";
    $('#popup .tagInfo').focus();

}
/**
 * function to remove popup
 */
function removePopUp() {
    var popup = document.getElementById("popup");
    popup.style.display = "none";
}
/**
 * A function to store tag info and continue video playback
 */
function onSubmitPopup() {
    // var vip=document.getElementById("demo_video")
    vid.play();
    Tag.tagInfo=$("#pop input").val();
    Tag.endTime=Tag.startTime+4;
    removePopUp();
    clearDraw();
}
function onMouseOutPopup() {
    // var vip=document.getElementById("demo_video")
    Tag.tagInfo=$("#popup .tagInfo").val();
    Tag.endTime=Tag.startTime+4;
    removePopUp();
    clearDraw();
    vid.play();
}
/**
 * start tag instance
 */
//ToDo set tag start instance
//ToDo save a tag object

/**
 * A function to retrieve tag end time
 */
function stopTagInstance() {
    //ToDO get stop time from video current Time and set the tag object stop time
    vid.pause();
    removePopUp();
    clearDraw();
    Tag.tagInfo=$("#tags p").text();
    Tag.endTime=vid.currentTime;
    //console.log(Tag);
    //console.log(Tag.tagBox);
}
/* clear canvas */
function clearDraw(){
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}
//ToDo save tag
/* preview edit video */
function preview(){
    removeDivTag;
    clearDraw();
    removeEvents();
    mode.edit=false;
    vid.load();
    initialize()
    vid.play();
     console.log("is in edit mode :"+mode.edit);

}
/* display tag when video is playing */
function displayTag(disTag){
    // draw(disTag.tagBox);
    var wrap=document.querySelector("body");
    hotspot.style.position="absolute";
    hotspot.style.height=disTag.tagBox.height+'px';
    hotspot.style.width=disTag.tagBox.width+'px';
    hotspot.style.left=disTag.tagBox.x+'px';
    hotspot.style.border='3px black solid';
    hotspot.style.top=disTag.tagBox.y+'px';
    hotspot.style.cursor="pointer";
    hotspot.style.display='block';
    hotspot.setAttribute('id','hotspot');
    console.log(hotspot);
    wrap.appendChild(hotspot);

}
function playVid(){
    if(vid.paused){
        vid.play();
        $('#play_btn').text('Pause');
    }
    else{
        vid.pause();
        $('#play_btn').text('Play');
    }
}
function onMouseOver(e){
    //console.log("mouse over fired");
    canvas.style.cursor="pointer";
    var tagRect=Tag.tagBox;
    var x= e.pageX-this.offsetLeft;
    var y= e.pageY-this.offsetTop;
    if(x>tagRect.x&& x< tagRect.x+tagRect.width&&y>tagRect.y&&y<tagRect.y+tagRect.height){
        vid.pause();
        ctx.strokeStyle='red';
        canvas.style.cursor="hand";
    }
}
function onMouseOut(e){

    canvas.style.cursor="pointer";
    var tagRect=Tag.tagBox;
    var x= e.pageX-this.offsetLeft;
    var y= e.pageY-this.offsetTop;
    if(x<tagRect.x&&
        x>(tagRect.x+tagRect.width)&&
        y<tagRect.y&&
        y>(tagRect.y+tagRect.height)
    ){
       canvas.style.cursor="default";
        vid.play();
    }
}
function clickRect(e){
    var tagRect=Tag.tagBox;
    var x= e.pageX-this.offsetLeft;
    var y= e.pageY-this.offsetTop;
    if(x>tagRect.x&& x< tagRect.x+tagRect.width&&y>tagRect.y&&tagRect.y+tagRect.height){
        alert(Tag.tagInfo);
    }
}
function tagRun(){
    //console.log(Tag);
    //console.log("time update fired = " +this.currentTime);
    if(!mode.edit){
        if(this.currentTime>=Tag.startTime&&this.currentTime<Tag.endTime){
                displayTag(Tag);
                console.log('displayTag called when in : '+mode);   
                // canvas.addEventListener('mouseover',onMouseOver,false);
                // canvas.addEventListener('mouseover',onMouseOut,false);    
                isShownTag=true;
         
        }
        else if(this.currentTime>=Tag.endTime){
            if(isShownTag){
                canvas.removeEventListener('click',clickRect,false);
                removeDivTag();
                clearDraw();
                isShownTag=false;
            }
        }
    }

}
function removeEvents(){
    canvas.removeEventListener('mousedown', mouseDown, false);
    canvas.removeEventListener('mousemove', mouseMove, false);
    canvas.removeEventListener('mouseup', mouseUp, false);
    canvas.removeEventListener('mouseover',onMouseOver,false);
    canvas.style.cursor="auto";
}
function editVid(){
    mode.edit=true;
    initialize();
    console.log("is in edit mode :"+mode.edit);
}
 // drawDivTag(tag){

 // }
 function removeDivTag(){
    var wrap=document.querySelector("body");
    wrap.removeChild(hotspot);
 }
