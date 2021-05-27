<?php

$all_blocks = ['core' => [], 'reusable' => []];

$classic_posts = [];

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
		if (!has_blocks($post) && !empty(get_the_content($post))) {
			$classic_posts['total'] += 1;
			$classic_posts['posts'][] = $post->ID;
			continue;
		}

		$post_blocks = parse_blocks($post->post_content);

		foreach ($post_blocks as $block) {
			ksort($block); // This only works with nested blocks for complicated stupid reasons that are solved by sorting the keys.

			$ref = '';
			array_walk_recursive( $block, function($value, $key) use (&$all_blocks, &$post, &$ref) {
				if ($key === 'ref' && !empty($value)) {
					$ref = $value;
				}
				if ($key === 'blockName' && !empty($value)) {
					$block_info = [];

					if ($value === 'core/block' && !empty($ref)) {
						$block_info['category'] = 'reusable';
						$block_info['name'] = get_the_title($ref);

						$all_blocks[$block_info['category']][$block_info['name']]['id'] = $ref;
					}
					elseif ($value === 'core/block' && empty($ref)) {
						$block_info['category'] = 'reusable';
						$block_info['name'] = '??? (resuable but nested inside another block)';
					}
					elseif (strpos($value, '/')) {
						$block_name_array = explode('/', $value);

						$block_info['category'] = array_shift($block_name_array);
						$block_info['name'] = implode('', $block_name_array);
					}
					else {
						$block_info['category'] = 'other';
						$block_info['name'] = $value;
					}

					$all_blocks[$block_info['category']][$block_info['name']]['total'] += 1;
					$all_blocks[$block_info['category']][$block_info['name']]['posts'][$post->ID] += 1;
				}
			}, $all_blocks);
			// var_dump($all_blocks);
		}
	}
}

// ksort($all_blocks);
?>

<div class="spelunker-section">

	<?php foreach ($all_blocks as $category => $blocks): ?>
		<h3><?= $category ?></h3>
		<?php //ksort($all_blocks); ?>
		<?php foreach ($blocks as $name => $block): ?>
		<details class="spelunker-details">
			<summary class="spelunker-summary">
				<strong><?php echo $name; ?></strong>, 
				<small>
					<? if ($block['total'] > 1 && count($block['posts']) > 1): ?> 
						<strong><?php echo number_format($block['total']); ?></strong> <?php _e( 'times across', 'wp-spelunker' ) ?> <strong><?php echo count($block['posts']); ?></strong> <?php _e( 'pages', 'wp-spelunker' ) ?>
					<?php elseif ($block['total'] > 1): ?>
						<strong><?php echo number_format($block['total']); ?></strong> times on <strong>1</strong> <?php _e( 'page', 'wp-spelunker' ) ?>
					<?php else: ?>
						<?php _e( 'once', 'wp-spelunker' ) ?>
					<?php endif; ?>
				</small>
			</summary>
			<table class="wp-list-table widefat fixed striped | spelunker-table" cellspacing="0">
				<caption class="spelunker-caption">
					<?php if (!empty($block['id'])) { ?><a href="/wp-admin/post.php?post=<?php echo $block['id']; ?>&action=edit"><em>#<?php echo $block['id']; ?></em></a><?php } ?>
					<strong><?php echo $name; ?></strong>
				</caption>
				<thead>
					<tr class="spelunker-row">
						<th class="spelunker-column-title | column-title column-primary"><?php _e( 'Title', 'wp-spelunker' ) ?></th>
						<?php if (count($block['posts']) > 1): ?> 
						<th class="spelunker-column-count | column-comments"><?php _e( 'Blocks', 'wp-spelunker' ) ?></th>
						<?php endif; ?>
						<th class="spelunker-column-date | column-date"><?php _e( 'Date', 'wp-spelunker' ) ?></th>
						<th class="spelunker-column-type | column-post_type"><?php _e( 'Type', 'wp-spelunker' ) ?></th>
						<th class="spelunker-column-edit | manage-column" aria-label="Actions"></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$posts = $block['posts'];
					ksort($posts);
					foreach ($posts as $post_id => $count): 
						$type = get_post_type($post_id);
						$format = get_post_format ($post_id);
						$status = get_post_status ($post_id);
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
							<?php if (count($block['posts']) > 1): ?> 
							<td class="spelunker-column-count | column-comments"><?php echo $count; ?></td>
							<?php endif; ?>
							<td class="spelunker-column-date | column-date"><time datetime="<?php echo get_the_date('c', $post_id); ?>" itemprop="datePublished"><?php echo get_the_date('M j, Y', $post_id); ?></time></td>
							<td class="spelunker-column-type | column-post_type"><?php echo $type ?> <?php if (!empty($format)): ?><small>(<em><?php echo $format; ?></strong>)</em><?php endif; ?></td>
							<td class="spelunker-column-edit | manage-column">
								<a href="/wp-admin/post.php?post=<?php echo $post_id; ?>&action=edit">
									<span class="dashicons dashicons-edit"></span>
									<?php _e( 'Edit', 'wp-spelunker' ) ?>
								</a>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
		</details>
		<?php endforeach; ?>
	<?php endforeach; ?>
	
	<?php if ($classic_posts['total'] > 0): ?>
	<details class="spelunker-details spelunker-details--total">
		<summary class="spelunker-summary">
			<strong><?php _e( 'No blocks found', 'wp-spelunker' ) ?>: </strong>
			<small>
				<strong><?php echo number_format($classic_posts['total']); ?></strong> <?php _e( 'pages', 'wp-spelunker' ) ?>
			</small>
		</summary>
		<table class="wp-list-table widefat fixed striped | spelunker-table" cellspacing="0">
			<thead>
				<tr class="spelunker-row">
					<th class="spelunker-column-title | column-title column-primary"><?php _e( 'Title', 'wp-spelunker' ) ?></th>
					<th class="spelunker-column-date | column-date"><?php _e( 'Date', 'wp-spelunker' ) ?></th>
					<th class="spelunker-column-type | column-post_type"><?php _e( 'Type', 'wp-spelunker' ) ?></th>
					<th class="spelunker-column-edit | manage-column" aria-label="Actions"></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$posts = $classic_posts['posts'];
				sort($posts);
				foreach ($posts as $post_id): 
					$type = get_post_type($post_id);
					$format = get_post_format ( $post_id );
					$status = get_post_status ( $post_id );
					?>
					<tr class="spelunker-row status-<? echo $status ?>">
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
						<td class="spelunker-column-date | column-date"><time datetime="<?php echo get_the_date('c', $post_id); ?>" itemprop="datePublished"><?php echo get_the_date('M j, Y', $post_id); ?></time></td>
						<td class="column-post_type | spelunker-column-type"><?php echo $type ?> <?php if (!empty($format)): ?><small>(<em><?php echo $format; ?></strong>)</em><?php endif; ?></td>
						<td class="spelunker-column-edit | manage-column">
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
	<?php endif; ?>

</div>