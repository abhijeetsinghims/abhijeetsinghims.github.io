<?php
$submission_id= !empty($_GET['submission_id']) ? absint($_GET['submission_id']) : false;
if(empty($submission_id))
    return;

$submission= erforms()->submission->get_submission($submission_id);
?>
<?php if(empty($submission['attachments'])) : ?>
        <div><?php echo __('No Attachments available','erforms'); ?></div>
<?php else:?>
        <?php foreach($submission['attachments'] as $attachment): ?>
                <?php 
                        $url = wp_get_attachment_url($attachment['f_val']);
                        if(wp_attachment_is_image($attachment['f_val'])): $image_attributes = wp_get_attachment_image_src($attachment['f_val']); ?>
                        <a target="_blank" href="<?php echo esc_url($url); ?>">
                            <img src="<?php echo esc_url($image_attributes[0]); ?>" width="<?php echo esc_attr($image_attributes[1]); ?>" height="<?php echo esc_attr($image_attributes[2]); ?>" />
                        </a>
                <?php else: ?>
                            <?php if(!empty($url)):
                                   $title= get_the_title($attachment['f_val']);
                            ?>
                                    <a target="_blank" href="<?php echo esc_url($url); ?>"><?php echo $title; ?>
                                        <img src="<?php echo ERFORMS_PLUGIN_URL.'/assets/admin/images/file-attachment.png'; ?>"/>
                                    </a>
                            <?php else: ?>
                                    <?php _e('Unable to fetch file.File might have deleted from from WordPress media section.','erforms'); ?>
                            <?php endif;?>
                            
                <?php endif; ?>
        <?php endforeach;?>
<?php endif; ?>

    