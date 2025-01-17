/* global tinyMCE, _ */

var Scraper = function() {};

/**
 * Adapted from wp-seo-post-scraper-plugin-310.js:196-210
 *
 * @param {string} editorID TinyMCE identifier to look up.
 *
 * @returns {boolean} True if an editor exists for the supplied ID.
 */
var isTinyMCEAvailable = function( editorID ) {
	if ( typeof tinyMCE === "undefined" ||
		 typeof tinyMCE.editors === "undefined" ||
		 tinyMCE.editors.length === 0 ||
		 tinyMCE.get( editorID ) === null ||
		 tinyMCE.get( editorID ).isHidden() ) {
		return false;
	}

	return true;
};

/**
 * Adapted from wp-seo-shortcode-plugin-305.js:115-126
 *
 * @param {Object} field Field to get the content for.
 *
 * @returns {string} The content of the field.
 */
var getContentTinyMCE = function( field ) {
	var textarea = field.$el.find( "textarea" )[ 0 ];
	var editorID = textarea.id;
	var val = textarea.value;

	if ( isTinyMCEAvailable( editorID ) ) {
		val = tinyMCE.get( editorID ) && tinyMCE.get( editorID ).getContent() || "";
	}

	return val;
};

Scraper.prototype.scrape = function( fields ) {
	fields = _.map( fields, function( field ) {
        if ( field.type !== "wysiwyg" && field.type !== "component_title" && field.type !== "component_text") {
            return field;
		}

		field.content = getContentTinyMCE( field );

		return field;
	} );

	return fields;
};

module.exports = Scraper;
