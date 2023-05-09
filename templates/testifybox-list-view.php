<div class="card bg-light card_css" style="margin-bottom: 25px;" >
    <div class="card-body">
        <h5 class="card-title"><?php echo get_the_title(); ?></h5><hr>
        <?php echo get_the_content(); ?>
        <div class="card-footer">
            <div class="row">
                <div class="col-md-4">
                    <?php
                        if ( get_the_post_thumbnail() ){
                            ?>
                                <?php echo get_the_post_thumbnail(  get_the_ID(), array(100,100), array('class' => "img_avatar") ) ; ?>
                            <?php
                        }else{
                            ?>
                                <img src="https://img.lovepik.com/element/40128/7461.png_1200.png" alt="Avatar" class="img_avatar">
                            <?php
                        }
                    ?>
                </div>
                <div class="col-md-8">
                    <?php
                        if ( $get_selected_option['name_check'] || $get_selected_option['email_check'] || $get_selected_option['company_name_check'] ) {
                            ?>
                                <small class="text-muted"><?php echo $name; ?><br>
                                    <?php echo $email;  ?><br>
                                    <b> <?php echo $company_name; ?></b>
                                </small>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>