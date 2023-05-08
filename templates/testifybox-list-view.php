<?php
    $get_selected_option = get_option( 'save_value' );
            
    if ( $query->have_posts() ){
        while ( $query->have_posts() ){
            $query->the_post();
            $value = get_post_meta( get_the_ID(), 'clientdetails', true );
            $full_name = get_post_meta( get_the_ID(), 'full_name', true );
            $email_address = get_post_meta( get_the_ID(), 'email_address', true );
            $company_name = get_post_meta( get_the_ID(), 'company_name', true );
            $rating = get_post_meta( get_the_ID(), 'rating', true );
                   
            $name = ( isset( $value['fname'] ) ? $value['fname'] : $full_name );
            $email = ( isset( $value['email'] ) ? $value['email'] : $email_address );
            $company_name = ( isset( $value['cname'] ) ? $value['cname'] : $company_name );
                
            if(!empty($value) || !empty($full_name) || !empty($email_address) || !empty($company_name) ){
            ?>
                <div class="card bg-light card_css" style="margin-bottom: 25px;" >
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
                                    <?php
                                        if ( $get_selected_option['rating_check'] ) {
                                            ?>
                                                <div class="stars">
                                                    <input class="star" type="radio" id="star1" name="rating" value="1" disabled="disabled" >
                                                    <label class="star" for="star1"></label>
                                                    <input class="star" type="radio" id="star2" name="rating" value="2" disabled="disabled" >
                                                    <label class="star" for="star2"></label>
                                                    <input class="star" type="radio" id="star3" name="rating" value="3" disabled="disabled" >
                                                    <label class="star" for="star3"></label>
                                                    <input class="star" type="radio" id="star4" name="rating" value="4" disabled="disabled" >
                                                    <label class="star" for="star4"></label>
                                                    <input class="star" type="radio" id="star5" name="rating" value="5" disabled="disabled" >
                                                    <label class="star" for="star5"></label>
                                                </div>
                                            <?php
                                        }
                                    ?>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
        }
    }else{
        echo 'No Testimonials Found.';
    }
?>