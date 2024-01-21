window.gsap.registerPlugin({
	name: "className",
	init(target, value, tween) {
		this.target = target;
		this.tween = tween;
		this.reverting = window.gsap.core.reverting || function () {};
		this.startClassList = target.getAttribute("class");
		this.endValue = value;
		this.newLocal = `(?:\\s|^)${this.endValue.substr(2)}(?![\\w-])`;
		this.end =
			this.endValue.charAt(1) === "="
				? this.startClassList.replace(new RegExp(this.newLocal), "") +
				  (this.endValue.charAt(0) === "+" ? ` ${this.endValue.substr(2)}` : "")
				: this.endValue;
		return !!target.style;
	},
	render(ratio, { target, tween, reverting, startClassList, end }) {
		target.setAttribute(
			"class",
			(tween.progress() === 1 || (!tween._time && reverting())) && tween.data !== "isFromStart"
				? startClassList
				: end,
		);
	},
});

//window.gsap.registerPlugin({
//	name: "className",
//	init(target, value, tween) {
//		const CSSPlugin = gsap.plugins.css;
//		const cssCore = CSSPlugin.core;
//		const _removeProperty = cssCore._removeProperty;
//		const _getAllStyles = function (target, uncache) {
//			const styles = {};
//			const computed = getComputedStyle(target);
//			let cache = target._gsap;
//			for (const p in computed) {
//				if (Number.isNaN(p) && p !== "cssText" && p !== "length") {
//					styles[p] = p;
//				}
//			}
//			uncache && cache && (cache.uncache = true);
//			gsap.getProperty(target, "x");
//			cache = target._gsap;
//			for (const p in cache) styles[p] = p;
//			return styles;
//		};

//		this.target = target;
//		this.tween = tween;
//		this.reverting = window.gsap.core.reverting || function () {};
//		this.startClassList = target.getAttribute("class");
//		this.endValue = value;
//		this.newLocal = `(?:\\s|^)${this.endValue.substr(2)}(?![\\w-])`;
//		this.end =
//			this.endValue.charAt(1) !== "="
//				? this.endValue
//				: this.startClassList.replace(new RegExp(this.newLocal), "") +
//				  (this.endValue.charAt(0) === "+" ? ` ${this.endValue.substr(2)}` : "");
//		this.inlineToRemoveAtEnd = {};
//		this.startVars = _getAllStyles(target);
//		this.transformRelated = /(transform|perspective)/i;
//		this.css = new CSSPlugin();
//		this.changingVars = {};
//		this.endVars = _getAllStyles(target, true);
//		target.setAttribute("class", this.startClassList);

//		for (const p in this.endVars) {
//			if (p !== this.startVars[p] && !this.transformRelated.test(p)) {
//				this.changingVars[p] = p;
//				if (!target.style[p] && target.style[p] !== "0") {
//					this.inlineToRemoveAtEnd[p] = 1;
//				}
//			}
//		}

//		this.css.init(target, this.changingVars, tween);
//		this._props = this.css._props;
//		return !!target.style;
//	},
//	render(
//		ratio,
//		{ target, tween, reverting, startClassList, end, _removeProperty, inlineToRemoveAtEnd, css },
//	) {
//		css.render(ratio, css);
//		if (!ratio || ratio === 1) {
//			target.setAttribute(
//				"class",
//				(tween.progress() === 1 || (!tween._time && reverting())) && tween.data !== "isFromStart"
//					? startClassList
//					: end,
//			);
//			for (const p in inlineToRemoveAtEnd) {
//				_removeProperty(target, p);
//			}
//		}
//	},
//});
