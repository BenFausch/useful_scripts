// 88888888888888888888
// trigger file uploader, be sure to use wp_enqueue_media(); somewhere in the admin to add functionality
//add_action('admin_enqueue_scripts', 'wp_enqueue_media');


// include this in the wp-admin:
// <div class="uploader">
//   <input id="_unique_name" name="settings[_unique_name]" type="text" />
//   <input id="_unique_name_button" class="button" name="_unique_name_button" type="text" value="Upload" />
// </div>


      // Uploading files
var file_frame;
var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
var set_to_post_id = 10; // Set this

  jQuery('.upload_image_button1').live('click', function( event ){

    event.preventDefault();

    // If the media frame already exists, reopen it.
    if ( file_frame ) {
      // Set the post ID to what we want
      file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
      // Open frame
      file_frame.open();
      return;
    } else {
      // Set the wp.media post id so the uploader grabs the ID we want when initialised
      wp.media.model.settings.post.id = set_to_post_id;
    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: jQuery( this ).data( 'uploader_title' ),
      button: {
        text: jQuery( this ).data( 'uploader_button_text' ),
      },
      multiple: false  // Set to true to allow multiple files to be selected
    });

    // When an image is selected, run a callback.
    file_frame.on( 'select', function() {
      // We set multiple to false so only get one image from the uploader
      attachment = file_frame.state().get('selection').first().toJSON();

      // Do something with attachment.id and/or attachment.url here
      
      // Restore the main post ID
      wp.media.model.settings.post.id = wp_media_post_id;
    });

    // Finally, open the modal
    file_frame.open();
  });
  
  // Restore the main ID when the add media button is pressed
  jQuery('a.add_media').on('click', function() {
    wp.media.model.settings.post.id = wp_media_post_id;
  });

  // 8888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888
  // 8888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888

///this allows a checkbox to turn a set of radio buttons on or off (disabled) 

   jQuery(document).ready(function() {
                                    var bvRecap = jQuery("input[name='bv_recap']");
                                    var bvLocation = jQuery("input[name='bv_location']");
                                    var bvPublished = jQuery("input[name='bv_published']");
                                    var bvNewsletter = jQuery("input[name=bv_daily_newsletter]");

                                    function checkLocation() {
                                        //set defaults
                                        if (!bvRecap.is(":checked")) {
                                            jQuery("#recapNone").attr('checked', 'checked');
                                        };
                                        if (!bvLocation.is(":checked")) {
                                            jQuery("#locationUS").attr('checked', 'checked');
                                        };
                                        var value = jQuery("input[name=bv_location]:checked").val();
                                        //set daily newsletter values
                                        bvNewsletter.prop('value', 'BV - ' + value + ' Daily');
                                        // set recap newsletter values
                                        jQuery("#recapAM").prop('value', 'BV - ' + value + ' Recap AM');
                                        jQuery("#recapPM").prop('value', 'BV - ' + value + ' Recap PM');
                                    };

                                    function checkBoxes() {
                                        var inputs = jQuery('#emailBoxes input:checkbox');
                                        if (!(inputs).is(':checked')) {
                                            bvLocation.prop('disabled', true); 
                                        }
                                        jQuery('#emailBoxes input:checkbox').click(function() {
                                            if (jQuery(this).is(':checked')) { 
                                                bvLocation.prop('disabled', false);
                                            } else if (!(inputs).is(':checked')) { 
                                                bvLocation.prop('disabled', true); 
                                            }
                                        });
                                    };

                                    //init
                                    jQuery("input:radio[name=bv_location]").on('click', checkLocation);
                                    checkLocation();
                                    checkBoxes();
                        });
// 8888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888
// 8888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888888   