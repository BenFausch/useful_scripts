<?php 
// 8888888888888888888888888888888888888888888888

/**
 * RSS Shortcode.
 *
 */

function get_rss( $atts) {
    $rss_atts = shortcode_atts( array(
        'url' => 'http://feeds.reuters.com/reuters/topNews',
        'max-posts' => '25'
        ), $atts );

    include_once( ABSPATH . WPINC . '/feed.php' );
    $rss = fetch_feed( $rss_atts['url'] );
    $maxitems = 0;
        if ( ! is_wp_error( $rss ) ) {
            $maxitems = $rss->get_item_quantity( 25 ); 
            $rss_items = $rss->get_items( 0, $maxitems );
        }

    echo "<ul>";
        if ( $maxitems == 0 ) {
            _e( 'No items', 'my-text-domain' );
        }else{
            foreach ( $rss_items as $item ) {
                echo '<li>';
                    echo '<a href="'. esc_url( $item->get_permalink() ) .'"';
                        echo 'title="'.esc_html( $item->get_title() ). '">';
                        echo esc_html( $item->get_title() );
                    echo '</a>';
                echo '</li>';
            }
        }
    echo "</ul>";
};

add_shortcode('rss', 'get_rss');





// 8888888888888888888888888888888888888888888888//
?>




<!-- /// 8888888888888888888888888888888888888888888888// -->
<script>
<!-- //Convert time in container to french 24 hour time -->
if($('html').attr('lang')=="fr-FR"){
    console.log('french!');
    
    $('.clinic-hours').each(function(){

    var hours = $(this).text();

    // console.log(hours);

    hours = hours.replace(/:/g,'h');
    hours = hours.replace(/-/g,'á');
    

    hours = hours.split('á');
    
    container = '';

    for(var i=0;i<hours.length;i++){

        if(hours[i].includes('PM')){
            var current = hours[i];

            current = current.replace(/PM/g,'');
            current = current.split('h');
            newst = current[0];
            newst = parseInt(newst);
                if(newst<12){
                    newst = (newst+12)+'hr'+current[1];
                    container+=newst;
                }else{
                    newst = (newst)+'hr'+current[1];
                    container+=newst;
                }
            }else{
                hours[i] = hours[i].replace(/AM/g,'');
                container+=hours[i]+' á ';
            }       
    }

$(this).html(container);

});


    // console.log(start);
    // console.log(end);

    
}
</script>
<!-- // 8888888888888888888888888888888888888888888888// -->


<!-- ///888888888888888888888888888888888888888// -->

<!-- ///AUTH0 USER METADATA UPDATE SCRIPT -->
<?php
   
/**
 * User Export
 * Admin screen that exports user data from Auth0
 *
 */
get_header();

//array of user ids without 'Auth0|' 
$userArray = array('userID1','userID2');
                    

foreach($userArray as $user){

echo ('Updating user ID: '.$user."<br><br>");


///////you'll need to get an authorization bearer token from the Auth0 api sandbox (token generator) to put in $cmd

//run curl to update user metadata
$cmd = ' curl -H "Authorization: Bearer INSERTBEARER HERE" -X PATCH  -H "Content-Type: application/json" -d \'{"user_metadata":{"account_type":"VIP", "vip":null}}\' https://%TEST%.auth0.com/api/v2/users/auth0%7C'.$user;

exec($cmd, $result);
//echo user email upon completion
$uid = bv_user_lookup_by_id($user);
$userEmail = $uid['result']['email'];
echo ($userEmail.' VIP status updated'."<br><br>");

//output results and delay
ob_flush();
   flush(); 
sleep(0.5);

}
echo('done!');

?>



<!-- ///888888888888888888888888888888888888888// -->




<!-- ///888888888888888888888888888888888888888// -->


<!-- get all posts based on metadata value-->


<?php
function get_meta_values( $meta_key,  $post_type = 'post' ) {

    $posts = get_posts(
        array(
            'post_type' => $post_type,
            'meta_key' => $meta_key,
            'posts_per_page' => -1,
        )
    );

    $meta_values = array();
    foreach( $posts as $post ) {
        $meta_values[] = get_post_meta( $post->ID, $meta_key, true );
    }

    return $meta_values;

}

$meta_values = get_meta_values( $meta_key, $post_type );


?>
<!-- ///888888888888888888888888888888888888888// -->

<!-- ///888888888888888888888888888888888888888// -->
<!-- ///schedules function to be run in 30 seconds, add to functions.php -->
<?php
function do_this_in_an_hour() {

error_log('cron working front page');
}
add_action( 'my_new_event','do_this_in_an_hour' );


wp_schedule_single_event( time() + 30, 'my_new_event' );

?>
<!-- ///888888888888888888888888888888888888888// -->


<!-- ///888888888888888888888888888888888888888// -->
<!--///THIS UPDATES AND COMBINES TAGS THAT ARE CLOSE DUPLICATES

wp_term_relationships has object_id which is a post id
term_taxonomy_id is the id from wp_terms

so, to update a tag in wp_terms

UPDATE `%TEST%`.`wp_terms` SET `name`='name' WHERE `term_id`='ID from slug';

ex.
UPDATE `%TEST%`.`wp_terms` SET `name`='Wind Power' WHERE `term_id`='290';


so then one of the duplicates needs to be deleted


DELETE FROM `%TEST%`.`wp_terms` WHERE `term_id`='408';


then all connections to it need to be changed in wp_term_relationships


UPDATE `%TEST%`.`wp_term_relationships` SET `term_taxonomy_id`='50' WHERE `term_taxonomy_id`='530';


This statement combines 2 ids(530,571) into a base id (50)-->
<?php
SELECT COUNT(*) FROM %TEST%.wp_term_relationships WHERE term_taxonomy_id=50
DELETE FROM `%TEST%`.`wp_terms` WHERE `term_id`='571';
DELETE FROM `%TEST%`.`wp_terms` WHERE `term_id`='530';
UPDATE IGNORE `%TEST%`.`wp_term_relationships` SET `term_taxonomy_id`='50' WHERE `term_taxonomy_id`='571';
UPDATE IGNORE `%TEST%`.`wp_term_relationships` SET `term_taxonomy_id`='50' WHERE `term_taxonomy_id`='530';
DELETE FROM `%TEST%`.`wp_term_relationships` WHERE `term_taxonomy_id`='530';
DELETE FROM `%TEST%`.`wp_term_relationships` WHERE `term_taxonomy_id`='571';
SELECT COUNT(*) FROM %TEST%.wp_term_relationships WHERE term_taxonomy_id=50



//This statement combines a child(530) into a parent(50)

SELECT COUNT(*) FROM %TEST%.wp_term_relationships WHERE term_taxonomy_id=50
DELETE FROM `%TEST%`.`wp_terms` WHERE `term_id`='530';
UPDATE IGNORE `%TEST%`.`wp_term_relationships` SET `term_taxonomy_id`='50' WHERE `term_taxonomy_id`='530';
DELETE FROM `%TEST%`.`wp_term_relationships` WHERE `term_taxonomy_id`='530';
SELECT COUNT(*) FROM %TEST%.wp_term_relationships WHERE term_taxonomy_id=50

?>

<!-- ///888888888888888888888888888888888888888// -->
<!-- ///888888888888888888888888888888888888888// -->