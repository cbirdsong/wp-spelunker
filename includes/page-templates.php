<?php
$templates = wp_get_theme()->get_page_templates();
?>

<div class="scrutineer-section">
	<?php foreach ($templates as $file => $name):
		$query = new WP_Query([
			'post_type' => 'any',
			'posts_per_page' => -1,
			'meta_query' => [
				[
					'key' => '_wp_page_template',
					'value' => $file,
				],
			],
		]);

		$page_count = sizeof($query->posts);
		?>
		<?php if ($page_count > 0): ?>
		<details class="scrutineer-details">
			<summary class="scrutineer-summary"><strong><?php echo $file; ?></strong>: <strong><?php echo number_format($page_count); ?></strong> <?php _e( 'pages', 'scrutineer' ) ?></summary>
			<table class="widefat fixed striped scrutineer-table" cellspacing="0">
				<thead>
					<tr class="scrutineer-row">
						<th class="manage-column scrutineer-column-title"><?php _e( 'Title', 'scrutineer' ) ?></th>
						<th class="manage-column scrutineer-column-edit" aria-label="Actions"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($query->posts as $post): 
					$post_id = $post->ID; 
					$status = get_post_status ( $post_id );
					?>
					<tr class="scrutineer-row">
						<td class="scrutineer-column-title column-title column-primary">
							<strong>
								<a class="row-title" href="<?php echo get_permalink($post_id); ?>">
									<? echo get_the_title($post_id); ?>
								</a>
							</strong>
							<?php if ($status != "publish"): ?>
								<span class="scrutineer-status scrutineer-status--is-<?php echo $status; ?>"><?php echo $status; ?></span>
							<?php endif; ?>
						</td>
						<td class="scrutineer-column-edit">
							<a href="/wp-admin/post.php?post=<?php echo $post_id; ?>&action=edit">
								<span class="dashicons dashicons-edit"></span>
								<?php _e( 'Edit', 'scrutineer' ) ?>
							</a>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</details> 
		<?php else: ?>
		<div class="scrutineer-summary">
			<strong><?php echo $file; ?></strong>: <?php _e( 'Not in use.', 'scrutineer' ) ?>
		</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>