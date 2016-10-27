<?php 
// 8888888888888888888888888888888888888888888888

/**
 * RSS Shortcode.
 *
 * @package breaking_views_2015
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
 * Breaking Views User Export
 * Admin screen that exports user data from Auth0
 *
 * @package breaking_views_2015
 */
get_header();

//array of user ids without 'Auth0|' 
$userArray = array('57dc24f48b383e6f5830f119','56f5657ea1cfa1ba113711e0');
                    

foreach($userArray as $user){

echo ('Updating user ID: '.$user."<br><br>");

//run curl to update user metadata
$cmd = ' curl -H "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhdWQiOiJKdFV3V01JMWZkOThEbUpsVmEyUjJDSDlsUDRLWDBSNSIsInNjb3BlcyI6eyJ1c2VycyI6eyJhY3Rpb25zIjpbInVwZGF0ZSJdfX0sImlhdCI6MTQ3Mzk1ODY5OSwianRpIjoiNDFkMDI0OTQ1NGIwYTJjZTI4NTc4ODYxNjBkODYxZWYifQ.Knz0MQVPIQvuRgf73ZHH5712KPKUbLOC_QQxPs8DMCE" -X PATCH  -H "Content-Type: application/json" -d \'{"user_metadata":{"account_type":"VIP", "vip":null}}\' https://breakingviews.auth0.com/api/v2/users/auth0%7C'.$user;

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