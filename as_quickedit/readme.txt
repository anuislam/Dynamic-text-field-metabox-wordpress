How to install plugin.

1: Go to "Plugins > Add new > Upload Plugin" just upload plugin or active it.

2: Use this code for get data.

<?php echo get_post_meta( (int)$post->ID, 'MEDICAID', true ); ?>
<?php echo get_post_meta( (int)$post->ID, 'POOR', true ); ?>
<?php echo get_post_meta( (int)$post->ID, 'SNAP', true ); ?>
<?php echo get_post_meta( (int)$post->ID, 'State', true ); ?>
<?php echo get_post_meta( (int)$post->ID, 'TANF', true ); ?>
<?php echo get_post_meta( (int)$post->ID, 'UI', true ); ?>

Thanks you