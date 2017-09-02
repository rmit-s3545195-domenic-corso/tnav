let ImageViewer = {
    e: {
        // Get the enlarged screen, caption, left, right, close elements
        photo_cont: document.getElementById("photo_cont"),
        image_content: document.getElementById("rr_image_display"),
        caption: document.getElementById("caption"),
        left_btn: document.getElementById("left"),
        right_btn: document.getElementById("right"),
        close_btn: document.getElementById("close"),
        restroom_image: document.getElementsByClassName('restroom_img')
    }
};

ImageViewer.evtCallbacks = {
    imageClicked: function(mouseEvent) {

        /* Set the enlarged photo container to show */
        this.e.photo_cont.style.display = "block";

        //set the photo container image source to the current clicked source
        this.e.image_content.src = mouseEvent.target.getAttribute("src");

        //Set the Caption text to the current image alt
        this.e.caption.innerHTML = mouseEvent.target.getAttribute("alt");
    },

    closeClicked: function() {
        this.e.photo_cont.style.display = "none";
    },

    leftClicked: function(mouseEvent) {
        let arrray_location = 0;

        for(let i = 0; i < this.e.restroom_image.length; i++)
        {
            if(this.e.image_content.getAttribute("src") == this.e.restroom_image[i].getAttribute("src")) {
                arrray_location = i;
            }
        }

        console.log(arrray_location - 1);
        if (arrray_location - 1 != -1) {
            this.e.image_content.src = this.e.restroom_image[arrray_location - 1].getAttribute("src");
        } else {
            this.e.image_content.src = this.e.restroom_image[this.e.restroom_image.length - 1].getAttribute("src");
        }
    },

    rightClicked: function(mouseEvent) {
        let arrray_location = 0;

        for(let i = 0; i < this.e.restroom_image.length; i++)
        {
            if(this.e.image_content.getAttribute("src") == this.e.restroom_image[i].getAttribute("src")) {
                arrray_location = i;
            }
        }

        console.log(arrray_location - 1);
        if (arrray_location + 1 < this.e.restroom_image.length) {
            this.e.image_content.src = this.e.restroom_image[arrray_location + 1].getAttribute("src");
        } else {
            this.e.image_content.src = this.e.restroom_image[0].getAttribute("src");
        }
    }
};

ImageViewer.addListeners = function() {
    // Add Event Listeners for specific elements
    for (let i = 0; i < this.e.restroom_image.length; i++) {
        this.e.restroom_image[i].addEventListener("click", this.evtCallbacks.imageClicked.bind(this));
    }

    this.e.close_btn.addEventListener("click", this.evtCallbacks.closeClicked.bind(this));

    this.e.left_btn.addEventListener("click", this.evtCallbacks.leftClicked.bind(this));

    this.e.right_btn.addEventListener("click", this.evtCallbacks.rightClicked.bind(this));
};

ImageViewer.init = function() {
    this.addListeners();
};

ImageViewer.init();
