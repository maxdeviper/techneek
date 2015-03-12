document.addEventListener("DOMContentLoaded", function () {

    var pop = Popcorn('#demo_video', {
        pauseOnLinkClicked: true
    });

    pop.play();

});
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


var canvas = document.getElementById('surf');
var vid = document.getElementById('demo_video');
var ctx = canvas.getContext('2d');
var drag = false;
var isShownTag=false;
/**
 * A function that is used to initialize the canvas event listeners
 */
function initialize() {
    canvas.addEventListener('mousedown', mouseDown, false);
    canvas.addEventListener('mousemove', mouseMove, false);
    canvas.addEventListener('mouseup', mouseUp, false);
    vid.addEventListener('timeupdate',function(){
        console.log(Tag);
        console.log("time update fired = " +this.currentTime);
        if(this.currentTime>=Tag.startTime&&this.currentTime<Tag.endTime){
            displayTag(Tag);
            isShownTag=true;

        }
        else if(this.currentTime>=Tag.endTime){
            if(isShownTag){
                clearDraw();
                isShownTag=false;
            }
        }
    },false);
    canvas.style.cursor = "crosshair";
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
        console.log(box);
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
    console.log(Tag);
    console.log(Tag.tagBox);
}
/* clear canvas */
function clearDraw(){
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}
//ToDo save tag
/* reload video */
function reloadVideo(){
    clearDraw();
    vid.load();
}
/* display tag when video is playing */
function displayTag(disTag){
    draw(disTag.tagBox);

}
function continuePlay(){
    vid.play();
}
