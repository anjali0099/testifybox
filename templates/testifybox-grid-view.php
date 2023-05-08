<div class="col-md-6 testimonial_css">
    <div class="card bg-light card_css" >
        <div class="card-body">
            <h5 class="card-title"><?php echo get_the_title(); ?></h5><hr>
            <?php echo get_the_content(); ?>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-4">
                        <img src="https://img.lovepik.com/element/40128/7461.png_1200.png" alt="Avatar" class="img_avatar">
                    </div>
                    <div class="col-md-8">
                        <small class="text-muted"><?php echo ( ($get_selected_option['name_check']) ? $name : '' ); ?><br>
                            <?php echo ( ($get_selected_option['email_check']) ? $email : '' );  ?><br>
                            <b> <?php echo ( ($get_selected_option['company_name_check']) ? $company_name : '' ); ?></b>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
