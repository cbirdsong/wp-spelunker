<?php
$templates = wp_get_theme()->get_page_templates();

if (!empty($templates)): ?>
<div class="spelunker-section">
	<?php foreach ($templates as $name => $file):
	var_dump($name);
	var_dump($file);
		// $query = new WP_Query([
		// 	'post_type' => 'any',
		// 	'posts_per_page' => -1,
		// 	'meta_query' => [
		// 		[
		// 			'key' => '_wp_page_template',
		// 			'value' => $file,
		// 		],
		// 	],
		// ]);

		$page_count = sizeof($query->posts);
		?>
		<?php if ($page_count > 0): ?>
		<details class="spelunker-details">
			<summary class="spelunker-summary"><strong><?= $name; ?></strong> (<?= $file; ?>): <strong><?php echo number_format($page_count); ?></strong> <?php _e( 'pages', 'wp-spelunker' ) ?></summary>
			<table class="widefat fixed striped | spelunker-table" cellspacing="0">
				<thead>
					<tr class="spelunker-row">
						<th class="manage-column | spelunker-column-title"><?php _e( 'Title', 'wp-spelunker' ) ?></th>
						<th class="manage-column | spelunker-column-edit" aria-label="Actions"></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($query->posts as $post): 
					$post_id = $post->ID; 
					$status = get_post_status ( $post_id );
					?>
					<tr class="spelunker-row">
						<td class="column-title column-primary | spelunker-column-title">
							<strong>
								<a class="row-title" href="<?php echo get_permalink($post_id); ?>">
									<? echo get_the_title($post_id); ?>
								</a>
							</strong>
							<?php if ($status != "publish"): ?>
								<span class="spelunker-status spelunker-status--is-<?php echo $status; ?>"><?php echo $status; ?></span>
							<?php endif; ?>
						</td>
						<td class="spelunker-column-edit">
							<a href="/wp-admin/post.php?post=<?php echo $post_id; ?>&action=edit">
								<span class="dashicons dashicons-edit"></span>
								<?php _e( 'Edit', 'wp-spelunker' ) ?>
							</a>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</details> 
		<?php else: ?>
		<div class="spelunker-summary">
			<strong><?php echo $file; ?></strong>: <?php _e( 'Not in use.', 'wp-spelunker' ) ?>
		</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>
<?php else: ?>
	<strong><?php _e( 'No templates found.', 'wp-spelunker' ) ?></strong>
<?php endif; ?>