<?php

$used_images = [];

$core_blocks = ['core/image', 'core/cover'];

$queries = [];
$queries[] = new WP_Query([
	'post_type' => 'any',
	'posts_per_page' => -1,
]);
$queries[] = new WP_Query([
	'post_type' => 'wp_block',
	'posts_per_page' => -1,
]);

foreach ($queries as $query) {
	foreach ($query->posts as $post) {

		if (has_post_thumbnail($post)) {
			$used_images[] = get_post_thumbnail_id($post);
		}

		if (has_blocks($post)) {
			$post_blocks = parse_blocks($post->post_content);

			foreach ($post_blocks as $block) {
				ksort($block);
				if (in_array($block['blockName'], $core_blocks)) {
					if (!empty($block['attrs']['id'])) {
						$used_images[] = $block['attrs']['id'];
					}
				}
			}
		}

	}
}


$unused_images = new WP_Query([
	'post_type' => 'attachment',
	'posts_per_page' => -1,
	// 'post__not_in' => $used_images,
]);

var_dump($unused_images);
?>


<div class="spelunker-section">

	<table class="wp-list-table widefat striped | spelunker-table" cellspacing="0">
		<thead>
			<tr class="spelunker-row">
				<th class="spelunker-column-image | column-"><?php _e( 'ID', 'wp-spelunker' ) ?></th>
				<th class="spelunker-column-image | column-"><?php _e( 'Image', 'wp-spelunker' ) ?></th>
				<th class="spelunker-column-title | column-title column-primary"><?php _e( 'Title', 'wp-spelunker' ) ?></th>
				<th class="spelunker-column-edit | manage-column" aria-label="Actions"></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach ($used_images as $image_id): 
				?>
				<tr class=" | spelunker-row">
					<td class="spelunker-column-id | column-comments">
						<?= $image_id ?>
					</td>
					<td class="spelunker-column-image | column-">
						<?= wp_get_attachment_image($image_id, 'thumbnail'); ?>
					</td>

					<td class="spelunker-column-title | column-title column-primary">
						<strong>
							<a class="row-title" href="<?php echo get_permalink($image_id); ?>">
								<? echo get_the_title($image_id); ?>
							</a>
						</strong>
						<?php if (!empty($image['featured'])): ?>
						<div>
							<small>Featured image of: </small>
							<?php foreach ($image['featured'] as $key => $post_id): ?>
								<a href="/wp-admin/post.php?post=<?php echo $post_id; ?>&action=edit"><?= $post_id ?></a><? if ($key !== array_key_last($image['featured'])) { echo ','; } ?>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>
						<?php if (!empty($image['blocks'])): ?>
						<div>
							<small>In content of: </small>
							<?php foreach ($image['blocks'] as $key => $post_id): ?>
								<a href="/wp-admin/post.php?post=<?php echo $post_id; ?>&action=edit"><?= $post_id ?></a><? if ($key !== array_key_last($image['blocks'])) { echo ','; } ?>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>
					</td>

					<td class="spelunker-column-edit | manage-column">
						<a href="/wp-admin/post.php?post=<?php echo $image_id; ?>&action=edit">
							<span class="dashicons dashicons-edit"></span>
							<?php _e( 'Edit', 'wp-spelunker' ) ?>
						</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

</div>