<?php
if (!defined('ABSPATH')) {
  exit;
}
$display_short_code = 'none';$created_short_code = '';$no_articles_found = 'none';
$image_url = BRIGHT_TALK_MAIN_IMG.'bright_talk_logo.png';
$created_pages = get_pages();


if(isset($_POST['bright_talk_selected_page'])){   
  $created_short_code = bright_talk_insert_article_into_the_page($_POST); 
  $display_short_code = 'block';
}

if(isset($_POST['bright_talk_topic'])){
  $data = bright_talk_fetch_all_the_articles($_POST);
  if($data->totalResults == 0) {
    $no_articles_found = 'block';
  }
}

function bright_talk_insert_article_into_the_page($to_insert)
{
  $created_pages = get_pages();$seleted_article = explode('|', $to_insert['new_article']);
  foreach ($created_pages as $key => $value) {
    if($value->ID == $to_insert['bright_talk_selected_page']){
      $published_date = strtotime($seleted_article[2]);
      $published_date = '<span class="dashicons dashicons-calendar-alt" style="font-size: 15px;"></span>'.date('M d Y', $published_date); 
      $content = '<div class="card" style="width: 100%;padding: 0px !important">
                    <img class="card-img-top" src="'.$seleted_article[5].'" alt="Card image cap" style="width: 100%;">
                    <div class="card-body" style="padding: 0.7em 2em 1em;">
                      <b class="card-title" style="color: #66cc00">'.$seleted_article[0].'</b>
                      </br></br>
                      <p class="text-muted">'.$seleted_article[1].' '.$published_date.'</p>
                      </br></br>
                      <p class="card-text">'.$seleted_article[3].'</p>
                      <a href="'.$seleted_article[4].'" class="btn btn-primary" style="bottom: 0px; right: 20px;border-color: #66cc00;background-color: #66cc00;color: white">More</a>
                    </div>
                  </div>';
      $post = array(
        'ID' => esc_sql($value->ID),
        'post_content' =>$value->post_content.$content,
      );
      $result = wp_update_post($post, true);
      return($value->guid);
    }
  }
}

function bright_talk_fetch_all_the_articles($all_articles)
{
  if($all_articles['bright_talk_topic']){
    $add_data ='q='.$all_articles['bright_talk_topic'];
    update_option('bright_talk_topic',$all_articles['bright_talk_topic']);
  }
  if(isset($all_articles['bright_talk_from_date'])){
    $add_data .='&from='.$all_articles['bright_talk_from_date'];
    update_option('bright_talk_from_date',$all_articles['bright_talk_from_date']);
  }
  if(isset($all_articles['bright_talk_to_date'])){
    $add_data .='&to='.$all_articles['bright_talk_to_date'];
    update_option('bright_talk_to_date',$all_articles['bright_talk_to_date']);
  }
  if(isset($all_articles['bright_talk_sort_by'])){
    $add_data .='&sortBy='.$all_articles['bright_talk_sort_by'];
    update_option('bright_talk_sort_by',$all_articles['bright_talk_sort_by']);
  }
  if(isset($all_articles['bright_talk_api_key'])){
    $add_data .='&apiKey='.$all_articles['bright_talk_api_key'];
    update_option('bright_talk_api_key',$all_articles['bright_talk_api_key']);
  }
  $request = wp_remote_get( 'http://newsapi.org/v2/everything?'.$add_data);
  $body = wp_remote_retrieve_body( $request );
  $data = json_decode( $body );
  return $data;
}


?>
<div class="container" style="width: 100%;padding: 0px;" align="center">
  <div class="row">
    <div class="col-sm-12">
      </br></br>
      <img src="<?php echo($image_url);?>" style="width: 10%" />
      </br></br>
      <p><?php echo('Fetch filtered news and insert into the webpage');?></p>
      <div class="alert alert-success" role="alert" style="display:<?php echo($display_short_code);?>">
        <?php echo('Article Inserted Successfully ');?><a href="<?php echo($created_short_code);?>">Vist page</a>
      </div>
      <form method="post" class="form-inline" action="">
        <div class="form-group">
          <input type="text" class="form-control" id="bright_talk_api_key" placeholder="API Key" name="bright_talk_api_key" value="<?php echo(get_option('bright_talk_api_key'));?>" required>
        </div>
        <div class="form-group">
          <input type="text" class="form-control" id="bright_talk_topic" placeholder="Topic" name="bright_talk_topic"  value="<?php echo(get_option('bright_talk_topic'));?>"  required>
        </div>
        <div class="form-group">
          <input type="date" class="form-control" id="bright_talk_from_date" placeholder="From Date" name="bright_talk_from_date"  value="<?php echo(get_option('bright_talk_from_date'));?>" >
        </div>
        <div class="form-group">
          <input type="date" class="form-control" id="bright_talk_to_date" placeholder="To Date" name="bright_talk_to_date"  value="<?php echo(get_option('bright_talk_to_date'));?>" >
        </div>
        <div class="form-group">
          <select class="form-control" id="bright_talk_topic" name="bright_talk_sort_by" selected>

            <option value="published" <?php if(get_option('bright_talk_sort_by') == 'published'){echo "selected";} ?>>Published</option>
            <option value="popularity" <?php if(get_option('bright_talk_sort_by') == 'popularity'){echo "selected";} ?>>Popularity</option>
          </select>
        </div>
        <button type="submit" class="btn btn-default" style="background-color: #66cc00;color: white">Retrive News</button>
      </form>
    </div>
  </div>
</div>
</br></br>
<div class="alert alert-danger" role="alert" style="display:<?php echo($no_articles_found);?>">
  <?php echo('No Articles Found ! ');?>
</div>
<?php
if(isset($data->articles)){?>
  <div class="container" style="width: 100%;padding: 0px;">
    <div class="row">
      <div class="col-sm-12"><?php
        foreach ($data->articles as $key => $value) {
          $published_date = strtotime($value->publishedAt);
          $published_date = '<span class="dashicons dashicons-calendar-alt" style="font-size: 15px;"></span>'.date('M d Y', $published_date); ?>
          <div class="col-sm-3">
            <div class="card" style="width: 18rem;min-height: 500px;padding: 0px !important">
              <img class="card-img-top" src="<?php echo($value->urlToImage);?>" alt="Card image cap" style="width: 100%;">
              <div class="card-body" style="padding: 0.7em 2em 1em;">
                <b class="card-title" style="color: #66cc00"><?php echo($value->title);?></b>
                </br></br>
                <p class="text-muted"><?php echo($value->author.' '.$published_date);?></p>
                </br></br>
                <p class="card-text"><?php echo($value->description);?></p>
                <a href="<?php echo($value->url);?>" class="btn btn-primary" style="bottom: 0px; right: 20px;border-color: #66cc00;background-color: #66cc00;color: white">More</a>
                <button data-toggle="modal" data-target="<?php echo('#'.$key);?>" class="btn btn-primary" style="bottom: 0px; right: 20px;border-color: #66cc00;background-color: #66cc00;color: white">Insert</button>
              </div>
            </div>
          </div>
          <div id="<?php echo($key);?>" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Insert Article to the page</h4>
                </div>
                <div class="modal-body">
                  <form method="post" action="">
                    <input type="hidden" name="new_article" value="<?php echo($value->title.'|'.$value->author.'|'.$value->publishedAt.'|'.$value->description.'|'.$value->url.'|'.$value->urlToImage);?>"/>
                    <div class="card" style="width: 100%;padding: 0px !important">
                      <img class="card-img-top" src="<?php echo($value->urlToImage);?>" alt="Card image cap" style="width: 100%;">
                      <div class="card-body" style="padding: 0.7em 2em 1em;">
                        <b class="card-title" style="color: #66cc00"><?php echo($value->title);?></b>
                        </br></br>
                        <p class="text-muted"><?php echo($value->author.' '.$published_date);?></p>
                        </br></br>
                        <p class="card-text"><?php echo($value->description);?></p>
                      </div>
                    </div>
                    </div>
                    <div class="modal-footer">
                     <b> Select the page  </b>
                      <select name="bright_talk_selected_page" required><?php foreach ($created_pages as $key => $value) {?>
                        <option value="<?php echo($value->ID);?>"><?php echo($value->post_title);?></option>
                      <?php } ?></select>
                      <button type="submit" class="btn btn-default">Insert Article</button>
                    </div>
                  </form>
              </div>
            </div>
          </div><?php  
        }?>
      </div>
    </div>
  </div><?php
}

?>
