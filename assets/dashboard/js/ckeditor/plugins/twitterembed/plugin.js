/**
 * @license Copyright (c) 2003-2020, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 *
 * List of modifications:
 *   - Set providerUrl to Twitter API endpoint -> https://publish.twitter.com/oembed
 *   - Custom lang
 *   - Custom icons
 */

(function () {
  'use strict';

  CKEDITOR.plugins.add('twitterembed', {
    icons: 'twitterembed', // %REMOVE_LINE_CORE%
    hidpi: true, // %REMOVE_LINE_CORE%
    lang: 'de,en,fr',
    requires: 'embedbase',

    init: function (editor) {
      var widgetDefinition =
        CKEDITOR.plugins.embedBase.createWidgetBaseDefinition(editor);

      // Extend the base definition with additional properties.
      CKEDITOR.tools.extend(widgetDefinition, {
        // Use a dialog exposed by the embedbase plugin.
        dialog: 'embedBase',
        button: editor.lang.twitterembed.button,
        allowedContent: 'div[!data-oembed-url]',
        requiredContent: 'div[data-oembed-url]',
        providerUrl: new CKEDITOR.template(
          '//publish.twitter.com/oembed?url={url}&callback={callback}'
        ),

        // The filter element callback actually allows all divs with data-oembed-url,
        // so registering styles to the filter is virtually unnecessary because
        // classes won't be filtered out. However, registering them will make filter.check() work
        // which may be important in some cases.
        styleToAllowedContentRules: function (style) {
          // Retrieve classes defined in the style.
          var classes = style.getClassesArray();

          return {
            div: {
              propertiesOnly: true,
              classes: classes,
              attributes: '!data-oembed-url'
            }
          };
        },

        upcast: function (el, data) {
          if (el.name === 'div' && el.attributes['data-oembed-url']) {
            data.url = el.attributes['data-oembed-url'];

            return true;
          }
        },

        downcast: function (el) {
          el.attributes['data-oembed-url'] = this.data.url;
        }
      }, true);

      // Register the definition as 'embed' widget.
      editor.widgets.add('twitterembed', widgetDefinition);

      // Do not filter contents of the div[data-oembed-url] at all.
      editor.filter.addElementCallback(function (el) {
        if ('data-oembed-url' in el.attributes) {
          return CKEDITOR.FILTER_SKIP_TREE;
        }
      });
    }
  });
})();
