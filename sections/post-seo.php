<?php

$post_query = new WP_Query([
	'post_type' => 'any',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'orderby' => 'type'
]);
// var_dump($post_query);
if ( $post_query->have_posts() ):
?>
<div class="spelunker-section">

	<table class="wp-list-table widefat fixed striped | spelunker-table" cellspacing="0">
		<caption class="spelunker-caption">
			<?php _e( 'Post SEO Info', 'wp-spelunker' ) ?>
		</caption>
		<thead>
			<tr class="spelunker-row">
				<th class="spelunker-column-title | column-title column-primary"><?php _e( 'Title', 'wp-spelunker' ) ?></th>
				<th class="spelunker-column-type | column-post_type"><?php _e( 'Type', 'wp-spelunker' ) ?></th>
				<th class="spelunker-column-excerpt | column-excerpt"><?php _e( 'Excerpt', 'wp-spelunker' ) ?></th>
				<th class="spelunker-column-image | column-image"><?php _e( 'Featured Image', 'wp-spelunker' ) ?></th>
				<th class="spelunker-column-edit | manage-column" aria-label="Actions"></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			while ( $post_query->have_posts() ):
				$post_query->the_post();
				$post_id = get_the_ID();

				$type = get_post_type($post_id);
				$format = get_post_format($post_id);
				$status = get_post_status($post_id);
				?>
				<tr class="status-<? echo $status ?> | spelunker-row">
					<td class="spelunker-column-title | column-title column-primary">
						<strong>
							<a class="row-title" href="<?php echo get_permalink($post_id); ?>">
								<? echo get_the_title($post_id); ?>
							</a>
							<?php if ($status != "publish"): ?>
								<span class="spelunker-status spelunker-status--is-<?php echo $status; ?>"><?php echo $status; ?></span>
							<?php endif; ?>
						</strong>
					</td>
					<td class="spelunker-column-type | column-post_type"><?php echo $type ?> <?php if (!empty($format)): ?><small>(<em><?php echo $format; ?></strong>)</em><?php endif; ?></td>
					<td class="spelunker-column-excerpt | column-">
						<?php if (has_excerpt($post_id)) {
							the_excerpt($post_id);
						} else { ?>
							<span class="spelunker-warning spelunker-warning--has-missing-info">No excerpt found.</span>
						<?php } ?>
					</td>
					<td class="spelunker-column-image | column-image">
						<?php if (has_post_thumbnail()) { ?>
							<a href="/wp-admin/post.php?post=<?php echo get_post_thumbnail_id(); ?>&action=edit">
								<?php the_post_thumbnail('thumbnail'); ?>
							</a>
						<?php } else { ?>
							<span class="spelunker-warning spelunker-warning--has-missing-info">No featured image found.</span>
						<?php } ?>
					</td>
					<td class="spelunker-column-edit | manage-column">
						<a href="/wp-admin/post.php?post=<?php echo $post_id; ?>&action=edit">
							<span class="dashicons dashicons-edit"></span>
							<?php _e( 'Edit', 'wp-spelunker' ) ?>
						</a>
					</td>
				</tr>
			<?php endwhile; ?>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
	
</div>
<? endif; 
wp_reset_postdata(); ?>