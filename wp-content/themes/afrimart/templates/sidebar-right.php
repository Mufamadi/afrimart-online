<?php if ( is_active_sidebar('right') ):
	$revo_right_span_class = 'col-lg-'.revo_options()->getCpanelValue('sidebar_right_expand');
	$revo_right_span_class .= ' col-md-'.revo_options()->getCpanelValue('sidebar_right_expand_md');
	$revo_right_span_class .= ' col-sm-'.revo_options()->getCpanelValue('sidebar_right_expand_sm');
?>
<aside id="right" class="sidebar <?php echo esc_attr($revo_right_span_class); ?>">
	<?php dynamic_sidebar('right'); ?>
</aside>
<?php endif; ?>