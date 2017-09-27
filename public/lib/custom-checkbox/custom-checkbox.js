let CustomCheckbox = {
	CLASS_NAME: 'checkbox-button',
	buttons: [],

	init: function () {
		this.assignButtons();
		this.addListeners();
	},

	assignButtons: function () {
		const buttons = document.querySelectorAll('.' + this.CLASS_NAME);

		this.buttons = [];
		for (let i = 0; i < buttons.length; i++) {
			this.buttons.push(buttons[i]);
		}

		return this.buttons;
	},

	addListeners: function () {
		for (let i = 0; i < this.buttons.length; i++) {
			this.buttons[i].addEventListener('click', (e) => { this.buttonClickedHandler(e) });
		}
	},

	buttonClickedHandler: function (e) {
		let clickedButton = e.target;

		/* Find associated input checkbox */
		let checkboxes = document.querySelectorAll('input[type=checkbox]');

		for (let i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].getAttribute('name') == clickedButton.getAttribute('name')) {
				this.swapCheckboxValue(checkboxes[i]);
				this.setButtonStyle(checkboxes[i].checked, clickedButton);
			}
		}
	},

	swapCheckboxValue: function (checkbox) {
		switch (checkbox.checked) {
			case true:
				return checkbox.checked = false;
			case false:
				return checkbox.checked = true;
		}
	},

	setButtonStyle: function (checkedValue, button) {
		switch (checkedValue) {
			case true:
				button.className = this.CLASS_NAME + ' selected';
				return;
			case false:
				button.className = this.CLASS_NAME;
				return;
		}
	}
};

CustomCheckbox.init();