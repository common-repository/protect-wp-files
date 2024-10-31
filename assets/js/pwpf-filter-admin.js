window.wp = window.wp || {};

(function($){

	var media = wp.media;

	media.view.AttachmentFilters.Taxonomy = media.view.AttachmentFilters.extend({

        tagName:   'select',
        id:        'protect-wordpress-files',

		createFilters: function() {
			var filters = {};
			var that = this;

			_.each( that.options.termList || {}, function( term, key ) {
				var term_id = term['term_id'];
				var term_name = $("<div/>").html(term['term_name']).text();
				filters[ term_id ] = {
					text: term_name,
					priority: key+2
				};
				filters[term_id]['props'] = {};
				filters[term_id]['props'][that.options.taxonomy] = term_id;
			});

			filters.all = {
				text: that.options.termListTitle,
				priority: 1
			};
			filters['all']['props'] = {};
			filters['all']['props'][that.options.taxonomy] = 'all';

			this.filters = filters;
		}
	});

	var curAttachmentsBrowser = media.view.AttachmentsBrowser;

	media.view.AttachmentsBrowser = media.view.AttachmentsBrowser.extend({

		createToolbar: function() {

			var filters = this.options.filters;

			curAttachmentsBrowser.prototype.createToolbar.apply(this,arguments);

			var that = this,
			i = 1;

			$.each(pwpf, function(taxonomy, values)
			{
				if ( values.term_list && filters )
				{
					that.toolbar.set( taxonomy+'-filter', new media.view.AttachmentFilters.Taxonomy({
						controller: that.controller,
                        model: that.collection.props,
                        priority: -80 + 10*i++,
						taxonomy: 'protect-wordpress-files',
						termList: values.term_list,
						termListTitle: values.list_title,
						className: 'protect-wordpress-files attachment-'+taxonomy+'-filter'
					}).render() );
				}
            });

		}
	});
	
	media.view.Attachment.Library = wp.media.view.Attachment.Library.extend({
		className: function (){ 
			return 'attachment ' + this.model.get( 'customClass' ); 
		},
		imageSize: function( size ) {
			var sizes = this.model.get('sizes'), matched = false;
	
			size = size || 'medium';
	
			// Use the provided image size if possible.
			if ( sizes ) {
				if ( sizes[ size ] ) {
					matched = sizes[ size ];
				} else if ( sizes.large ) {
					matched = sizes.large;
				} else if ( sizes.thumbnail ) {
					matched = sizes.thumbnail;
				} else if ( sizes.full ) {
					matched = sizes.full;
				}

				if(this.model.get( 'customClass' ) == 'private'){
					return {
						url:         matched.url + '/',
						width:       matched.width,
						height:      matched.height,
						orientation: matched.orientation
					};
				}
	
				if ( matched ) {
					return _.clone( matched );
				}

			}
	
			return {
				url:         this.model.get('url'),
				width:       this.model.get('width'),
				height:      this.model.get('height'),
				orientation: this.model.get('orientation')
			};

		},
	});

})( jQuery );