let ImageViewer = {
    e: {
        /* Elements for the enlarged image screen */
        photo_container: document.getElementById("photo_cont"),
        image_content: document.getElementById("rr_image_display"),
        caption: document.getElementById("caption"),
        left_btn: document.getElementById("left"),
        right_btn: document.getElementById("right"),
        close_btn: document.getElementById("close"),

        /* Elements for the photo count */
        current_photo_count: document.getElementById("current_photo_number"),
        total_photo_count: document.getElementById("total_photo_count"),
        photo_location: 0,

        /* Elements to keep track of the current photo index and container */
        active_index: 0,
        current_photo_container: [],
    }
};

ImageViewer.evtCallbacks = {
    imageClicked: function(mouseEvent) {
        /* Setting the current photo container to the parent element of clicked target image */
        let photoContainer = mouseEvent.target.parentElement;
        this.e.current_photo_container = Array.from(photoContainer.getElementsByClassName('restroom_img'));

        /* Set the active index to the element that is equal in the current photo container */
        this.e.active_index = this.e.current_photo_container.indexOf(mouseEvent.target);

        /* Set the enlarged photo container to show */
        this.e.photo_container.style.display = "block";

        /* set the photo container image source to the current clicked source */
        this.e.image_content.src = mouseEvent.target.getAttribute("src");

        /* Set the Caption text to the current image alt */
        this.e.caption.innerHTML = mouseEvent.target.getAttribute("alt");

        /* Set the inital photo count elements */
        this.e.photo_location = this.e.active_index + 1;
        this.e.current_photo_count.innerHTML = this.e.photo_location;
        this.e.total_photo_count.innerHTML = this.e.current_photo_container.length;
    },

    closeClicked: function() {
        this.e.photo_container.style.display = "none";
    },

    leftClicked: function() {
        this.changeImageLeft();
    },

    rightClicked: function() {
        this.changeImageRight();
    }
};

ImageViewer.changeImageRight = function (mouseEvent) {
    /* Set the new photo location number to + 1 if the location now is less than the total length, reset the photo location to 1 if the current location + 1 is greater or equal to the photo container length*/
    if (this.e.active_index + 1 < this.e.current_photo_container.length) {
        this.e.current_photo_count.innerHTML = ++this.e.photo_location;

        this.e.active_index++;
        this.e.image_content.src = this.e.current_photo_container[this.e.active_index].getAttribute("src");
    } else {
        this.e.photo_location = 1;
        this.e.current_photo_count.innerHTML = this.e.photo_location;

        this.e.active_index = 0;
        this.e.image_content.src = this.e.current_photo_container[this.e.active_index].getAttribute("src");
    }
};

ImageViewer.changeImageLeft = function (mouseEvent) {
    if (this.e.active_index - 1 >= 0) {
        /* If the array location - 1 is not -1 then set the photo location to one down,  if the array location - 1 is -1 then set the photo location the total length in the container  */
        this.e.current_photo_count.innerHTML = --this.e.photo_location;

        /* If the array location - 1 goes past 0 and is not -1 then do array_location - 1 to get the previous image else go the last image in the current photo container */
        this.e.active_index--;
        this.e.image_content.src = this.e.current_photo_container[this.e.active_index].getAttribute("src");
    } else {
        this.e.photo_location = this.e.current_photo_container.length;
        this.e.current_photo_count.innerHTML = this.e.photo_location;

        this.e.active_index = this.e.current_photo_container.length - 1;
        this.e.image_content.src = this.e.current_photo_container[this.e.active_index].getAttribute("src");
    }
};

ImageViewer.addListeners = function() {
    let restroom_images = document.getElementsByClassName('restroom_img');

    for (let i = 0; i < restroom_images.length; i++) {
        restroom_images[i].addEventListener("click", this.evtCallbacks.imageClicked.bind(this));
    }

    this.e.close_btn.addEventListener("click", this.evtCallbacks.closeClicked.bind(this));

    document.addEventListener("keydown", function(keyevent) {
        switch (keyevent.keyCode) {
            case 27:
                this.e.photo_container.style.display = "none";
                break;
            case 37:
                this.changeImageLeft();
                break;
            case 39:
                this.changeImageRight();
                break;
        }
    }.bind(this));

    this.e.left_btn.addEventListener("click", this.evtCallbacks.leftClicked.bind(this));
    this.e.right_btn.addEventListener("click", this.evtCallbacks.rightClicked.bind(this));
};

ImageViewer.init = function() {
    this.addListeners();
};

ImageViewer.init();
