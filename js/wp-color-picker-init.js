// function from https://stackoverflow.com/a/5624139/3695983
function hexToRgb(hex) {
	var shorthandRegex = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
	hex = hex.replace(shorthandRegex, function (m, r, g, b) {
		return r + r + g + g + b + b;
	});

	var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
	return result ? {
		r: parseInt(result[1], 16),
		g: parseInt(result[2], 16),
		b: parseInt(result[3], 16)
	} : null;
}

function rgbToHex(r, g, b) {
	function componentToHex(c) {
		var hex = c.toString(16);
		return hex.length === 1 ? "0" + hex : hex;
	}

	var hexR = componentToHex(r);
	var hexG = componentToHex(g);
	var hexB = componentToHex(b);

	return "#" + hexR + hexG + hexB;
}

function rgbStringToHex(rgbString) {
	var match = rgbString.match(/^(rgb|rgba)\((\d+),\s*(\d+),\s*(\d+)(?:,\s*\d+\.\d+)?\)$/);

	var r = parseInt(match[2]);
	var g = parseInt(match[3]);
	var b = parseInt(match[4]);

	var hexColor = rgbToHex(r, g, b);

	return hexColor;
}

function createColorObject(string) {
	let match;

	// Check if the string matches an RGB/RGBA color format
	match = string.match(/^(rgb|rgba)\((\d+),\s*(\d+),\s*(\d+)(?:,\s*\d+\.\d+)?\)$/);
	if (match) {
		return {
			r: Number(match[2]),
			g: Number(match[3]),
			b: Number(match[4])
		};
	}

	return hexToRgb(string);
}

function stringToRgb(string) {
	const pattern = /\b\d+\b/g;
	const result = string.match(pattern);
	return {
		r: parseInt(result[0], 16),
		g: parseInt(result[1], 16),
		b: parseInt(result[2], 16)
	}
}

// function from https://stackoverflow.com/a/9733420/3695983                     
function luminance(r, g, b) {
	var a = [r, g, b].map(function (v) {
		v /= 255;
		return v <= 0.03928
			? v / 12.92
			: Math.pow((v + 0.055) / 1.055, 2.4);
	});
	return a[0] * 0.2126 + a[1] * 0.7152 + a[2] * 0.0722;
}

function getContrastColor(result) {

	let secondary_color = "#ffffff";
	console.log(result.r * 0.299 + result.g * 0.587 + result.b * 0.114);
	if ((result.r * 0.299 + result.g * 0.587 + result.b * 0.114) > 186) {
		secondary_color = "#000000";
	}

	return secondary_color;
}

function calculateContrastRatio(color1rgb, color2rgb) {

	// calculate the relative luminance
	const color1luminance = luminance(color1rgb.r, color1rgb.g, color1rgb.b);
	const color2luminance = luminance(color2rgb.r, color2rgb.g, color2rgb.b);

	// calculate the color contrast ratio
	const ratio = color1luminance > color2luminance
		? ((color2luminance + 0.05) / (color1luminance + 0.05))
		: ((color1luminance + 0.05) / (color2luminance + 0.05));

	const contrast_ratio = 1 / ratio;

	return contrast_ratio;
}

jQuery(document).ready(function ($) {

	var myOptions = {
		// you can declare a default color here,

		// or in the data-default-color attribute on the input
		// defaultColor: false,

		// a callback to fire whenever the color changes to a valid color
		change: function (event, ui) {
			console.log("Color Primary: " + event.target.value);

			console.log(event.target.value);
			const color_object = createColorObject(event.target.value);
			const black = {
				r: 0,
				b: 0,
				g: 0
			};
			const white = {
				r: 255,
				b: 255,
				g: 255
			};

			console.log("White: " + calculateContrastRatio(color_object, white) + ", Black: " + calculateContrastRatio(color_object, black))

			if (calculateContrastRatio(color_object, white) > calculateContrastRatio(color_object, black)) {
				document.getElementById('_merchant_secondary_color').value = "#ffffff";
			}
			else {
				document.getElementById('_merchant_secondary_color').value = "#000000";
			}
		},

		// a callback to fire when the input is emptied or an invalid color
		// clear: function() {},

		// hide the color picker controls on load
		// hide: true,

		// show a group of common colors beneath the square
		// or, supply an array of colors to customize further
		palettes: [
		]
	};

	/* Call the Color Picker */
	$(".color-picker").wpColorPicker(myOptions);

});
