//event listeners to mouse and finger
document.addEventListener('touchstart', handleTouchStart, false);
document.addEventListener('touchmove', handleTouchMove, false);
document.addEventListener('mousedown', handleMouseStart, false);
document.addEventListener('mousemove', handleMouseMove, false);

//start x-coordinate and size of swipe you need to open menu
let x1 = null;
let swipe_size = null;

/**
 * specifies start x-coordinate
 * and size of swipe you need to open menu
 * @param event
 */
function handleTouchStart(event){
    x1 = event.touches[0].clientX;
    swipe_size = 0.8*window.innerWidth;
}

/**
 * if user press and move with finder
 * form left to right -> show menu
 * from right to left -> hide menu
 * @param event
 * @returns {boolean}
 */
function handleTouchMove(event){
    if(!x1){
        return false;
    }
    let x2 = event.touches[0].clientX;
    if((x2-x1)>swipe_size){
        document.getElementById('main_menu').style.display = "block";
        document.getElementById('block_main').style.display = "none";
    } else if((x2-x1)<-swipe_size){
        document.getElementById('main_menu').style.display="none";
        document.getElementById('block_main').style.display = "block";
    }
}

/**
 * specifies start x-coordinate
 * and size of mouseMove you need to open menu
 * @param event
 */
function handleMouseStart(event){
    x1 = event.pageX;
    swipe_size = 0.8*window.innerWidth;
}

/**
 * if user press and move with mouse
 * form left to right -> show menu
 * from right to left -> hide menu
 * @param event
 * @returns {boolean}
 */
function handleMouseMove(event){
    if(!x1){
        return false;
    }
    x2 = event.pageX;
    if((x2-x1)>swipe_size){
        document.getElementById('main_menu').style.display = "block";
        document.getElementById('block_main').style.display = "none";
        x1 = null;
    } else if((x2-x1)<-swipe_size){
        document.getElementById('main_menu').style.display="none";
        document.getElementById('block_main').style.display = "block";
        x1 = null;
    }
}

let check = 1;

/**
 * show or hide option menu if user dblclick on task
 */
function displayOptions(){
    let options = document.getElementsByClassName('options');
    let records = document.getElementsByClassName('records');
    if(check) {
        for (let i = 0; i < options.length; i++) {
            options[i].style.display = "block";
            records[i].style.display = "none";
        }
        check = 0;
    }
    else{
        for (let i = 0; i < options.length; i++) {
            options[i].style.display = "none";
            records[i].style.display = "block";
        }
        check = 1;
    }
}