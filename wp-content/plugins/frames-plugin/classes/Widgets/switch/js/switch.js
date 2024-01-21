class FrSwitcher {
	constructor(element, options) {
		this.element = element;
		this.options = options;
		this.contentSelector = this.options.contentSelector ? this.options.contentSelector : '.fr-switch-content';
		this.contentWrapper = document.querySelector(this.contentSelector);
		this.activeButton = this.options.defaultActive ? 2 : 1;



		this.init();
	}

	init() {

		if (!this.contentWrapper) {
			console.warn('Content wrapper not found for the following switch element:', this.element);
			// this.replaceElementWithWarning(this.element);
			this.setDefaultPressed();
			return;
		}



		this.addSwitcherClass();
		this.setDefaultPressed();
		this.element.addEventListener('click', () => this.toggle());
		this.element.addEventListener('keydown', (e) => this.handleKeydown(e));
		this.updateSwitcher();

		if (document.querySelector('.iframe.mounted')) {

			Array.from(this.contentWrapper.children).forEach(item => {
				item.addEventListener('click', (e) => {
					e.preventDefault();

					item.classList.toggle('fr-switch--active');
				});
			}
			);
		}
	}

	toggle() {
		const ariaPressed = this.element.getAttribute('aria-pressed');
		this.element.setAttribute('aria-pressed', ariaPressed === 'true' ? 'false' : 'true');
		this.activeButton = this.activeButton === 1 ? 2 : 1;
		this.updateSwitcher();
	}

	updateSwitcher() {
		const switcherChildren = Array.from(this.contentWrapper.children);
		switcherChildren.forEach((child, index) => {
			const action = (index + 1 === this.activeButton) ? 'add' : 'remove';
			child.classList[action]('fr-switch--active');
		});
	}

	addSwitcherClass() {
		if (this.contentWrapper.classList.contains('fr-switch-content')) return;
		this.contentWrapper.classList.add('fr-switch-content');
	}

	setDefaultPressed() {
		if (this.options.defaultActive) {
			this.element.setAttribute('aria-pressed', 'true');
		} else {
			this.element.setAttribute('aria-pressed', 'false');
		}
	}

	handleKeydown(e) {
		const key = e.which || e.keyCode;
		if (key === 37 || key === 39) { // Left or Right arrow
			e.preventDefault();
			this.toggle();
		}
	}

	// builder methods

	replaceElementWithWarning(toggle) {
		const warning = document.createElement('div');
		warning.className = 'fr-warning';
		warning.textContent = 'Please create a switcher element in the builder and link it to this switch in Switch Settings. Switcher should be a parent with an unique selector (f.e fr-switcher) and must have 2 direct children elements. We recommend to use Block element for every structure element.';
		// toggle.replaceWith(warning);
		// not replace but add after
		toggle.after(warning);
	}

	removeWarning() {
		if (this.element) {
			const warning = document.querySelector('.fr-warning');
			if (!warning) return;
			warning.remove();
		}
	}

}

function switch_script() {
	const switches = document.querySelectorAll('.fr-switch');

	switches.forEach(item => {
		if (!item.dataset.frSwitchOptions) {
			console.warn('Options not provided for the following switch element:', item);
			return;
		}

		const options = JSON.parse(item.dataset.frSwitchOptions);
		new FrSwitcher(item, options);
	});
}

document.addEventListener("DOMContentLoaded", function (e) {
	if (window.bricksIsFrontend) {
		switch_script();
	}
});
