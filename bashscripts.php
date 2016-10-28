<!-- ****REMOVE SPACES IN ALL FILES IN CURRENT DIRECTORY -->
for f in *\ *; do mv "$f" "${f// /_}"; done

<!-- ****Pull/Push prod databases using a local sql update script
 also will run a wordpress plugin deactivation function if needed 
 requires a credentials file with vars i.e. localhost=localhost and ssh access-->

#!/bin/bash
#run: bash export-prod-to-dev-db.sh
. .credentials.sh

now=$(date +"%m-%d-%Y-%H-%M")
echo "Pulling Prod Database"

#pull prod db
mysqldump --host=localhost -uroot -proot -u $produser -p$prodpassword -h $prodhost $proddb > bvProdDump$now.sql 
echo "Database pulled! Importing Prod database to Develop."

#import db to dev
mysql --host=$devhost -u$devuser -p$devpassword $devdb < bvProdDump$now.sql
echo "Database imported! Updating Develop Database."

#update db to dev.YOURSITE
mysql --host=$devhost -u$devuser -p$devpassword $devdb < update-dev-db.sql
echo 'Develop Database Updated! Cleaning up files.'

#cleanup
rm bvProdDump$now.sql 
echo 'File cleanup complete.'

ssh ubuntu@dev.YOURSITE.com "
cd /var/www/vhost/YOURSITE-dev
sudo /usr/bin/php -r \"
require_once ( 'wp-blog-header.php');
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
deactivate_plugins('wp-ffpc/wp-ffpc.php');
?>
\"
echo 'wp-ffpc plugin deactivated'
echo 'cache flushed'
echo flush_all | nc 127.0.0.1 11211
"




<!-- ****imports a production/stage db, 
requires a credentials file with vars i.e. localhost=localhost
and ssh access
 -->
#!/bin/bash
# run: bash import-prod-db.sh
. .credentials.sh

now=$(date +"%m-%d-%Y-%H-%M")
echo "Pulling Prod Database"

#pull db
mysqldump --host=localhost -uroot -proot -u $produser -p$prodpassword -h $prodhost $proddb > bvProdDump$now.sql 
echo "Database pulled! Importing Database to local."

#import db
mysql --host=localhost -uroot -proot $localdb < bvProdDump$now.sql
echo "Database imported! Updating Local Database."

#update db to local.YOURSITE
mysql --host=localhost -uroot -proot $localdb < update-local-db.sql
echo 'Local Database Updated! Cleaning up files.'

#cleanup
rm bvProdDump$now.sql 
echo 'File cleanup complete.'

#deactivate wp-ffpc plugin
php -r "
require_once  '../wp-blog-header.php';
deactivate_plugins('/wp-ffpc/wp-ffpc.php');
"
echo 'wp-ffpc deactivated'


<!---**sql commands to update dev/prod wp db to local version, for use with the above scripts -->
\! echo "Updating wp_options";
UPDATE `YOURSITE_dev`.`wp_options` SET `option_value`='http://dev.YOURSITE.com' WHERE `option_id`='1';
UPDATE `YOURSITE_dev`.`wp_options` SET `option_value`='http://dev.YOURSITE.com' WHERE `option_id`='2';

\! echo "Updating wp_posts";
UPDATE `YOURSITE_dev`.`wp_posts` SET guid = replace (guid , 'http://www.YOURSITE.com' , 'http://dev.YOURSITE.com');

UPDATE `YOURSITE_dev`.`wp_posts` SET post_content = replace (post_content , 'http://www.YOURSITE.com' , 'http://dev.YOURSITE.com');

\! echo "Updating wp_postmeta";

UPDATE `YOURSITE_dev`.`wp_postmeta`  SET meta_value = replace (meta_value , 'http://www.YOURSITE.com' , 'http://dev.YOURSITE.com');



<?php

/*a simple script that
 gets all wordpress tags, 
 their article count, 
 and the most recent article publish date
*/
$tags = get_tags();
$output=[]; 
array_push($output, array('Name','Count','Last Modified')); 

    function get_most_recent($id){
        $args = array( 'numberposts' => '1', 'tax_query' => array(
               array( 'tag_id' => $id )
        ) );
        $recent_posts = wp_get_recent_posts( $args );
        $recent = $recent_posts[0]['post_date'];
        return $recent;
    }
    // echo( get_most_recent('29'));
$i=0;
foreach($tags as $tag){
    $name = htmlspecialchars_decode($tag->name);
    $id = $tag->term_id;
    $count = $tag->count;
 if($i<10){
     array_push($output, array($name, $count, get_most_recent($id)));
     $i++;
 }
 
}
// echo $output;
///



function outputCSV($data) {
        $outputBuffer = fopen("php://output", 'w');
        foreach($data as $val) {
            fputcsv($outputBuffer, $val);
        }
        fclose($outputBuffer);
    }

 $filename = "tim";

    header("Content-type: text/csv");
    header("Content-Disposition: attachment; filename={$filename}.csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    outputCSV($output);


////

?>