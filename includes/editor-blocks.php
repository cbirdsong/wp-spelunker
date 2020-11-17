
<?php
$blocks = [];
$query = new WP_Query([
	'post_type' => 'any',
	'posts_per_page' => -1,
]);

$blockless_posts = [];

foreach ($query->posts as $post) {
	if (!has_blocks($post)) {
		$blockless_posts['total'] += 1;
		$blockless_posts['posts'][] = $post->ID;
		continue;
	}

	$post_blocks = parse_blocks($post->post_content);

	foreach ($post_blocks as $block) {
		if (array_key_exists('blockName', $block) && isset($block['blockName'])) {
			$blocks[$block['blockName']]['total'] += 1;
			$blocks[$block['blockName']]['posts'][$post->ID] += 1;
		}
	}
}

ksort($blocks);
?>

<div class="scrutineer-section">

	<?php foreach ($blocks as $name => $block): ?>
	<details class="scrutineer-details">
		<summary class="scrutineer-summary">
			<strong><?php echo $name; ?></strong>, 
			<small>
				<? if ($block['total'] > 1 && count($block['posts']) > 1): ?> 
					<strong><?php echo number_format($block['total']); ?></strong> <?php _e( 'times across', 'scrutineer' ) ?> <strong><?php echo count($block['posts']); ?></strong> <?php _e( 'pages', 'scrutineer' ) ?>
				<?php elseif ($block['total'] > 1): ?>
					<strong><?php echo number_format($block['total']); ?></strong> times on <strong>1</strong> <?php _e( 'page', 'scrutineer' ) ?>
				<?php else: ?>
					<?php _e( 'once', 'scrutineer' ) ?>
				<?php endif; ?>
			</small>
		</summary>
		<table class="widefat fixed striped scrutineer-table" cellspacing="0">
			<thead>
				<tr class="scrutineer-row">
					<th class="manage-column scrutineer-column-title"><?php _e( 'Title', 'scrutineer' ) ?></th>
					<?php if (count($block['posts']) > 1): ?> 
					<th class="manage-column scrutineer-column-count"><?php _e( 'Blocks', 'scrutineer' ) ?></th>
					<?php endif; ?>
					<th class="manage-column scrutineer-column-count"><?php _e( 'Date', 'scrutineer' ) ?></th>
					<th class="manage-column scrutineer-column-count"><?php _e( 'Type', 'scrutineer' ) ?></th>
					<th class="manage-column scrutineer-column-edit" aria-label="Actions"></th>
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
					<tr class="scrutineer-row status-<? echo $status ?>">
						<td class="scrutineer-column-title column-title column-primary">
							<strong>
								<a class="row-title" href="<?php echo get_permalink($post_id); ?>">
									<? echo get_the_title($post_id); ?>
								</a>
								<?php if ($status != "publish"): ?>
									<span class="scrutineer-status scrutineer-status--is-<?php echo $status; ?>"><?php echo $status; ?></span>
								<?php endif; ?>
							</strong>
						</td>
						<?php if (count($block['posts']) > 1): ?> 
						<td class="scrutineer-column-count"><?php echo $count; ?></td>
						<?php endif; ?>
						<td class="scrutineer-column-date"><time datetime="<?php echo get_the_date('c', $post_id); ?>" itemprop="datePublished"><?php echo get_the_date('M j, Y', $post_id); ?></time></td>
						<td class="scrutineer-column-type"><?php echo $type ?> <?php if (!empty($format)): ?><small>(<em><?php echo $format; ?></strong>)</em><?php endif; ?></td>
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
	<?php endforeach; ?>
	
	<?php if ($blockless_posts['total'] > 0): ?>
	<details class="scrutineer-details scrutineer-details--total">
		<summary class="scrutineer-summary">
			<strong><?php _e( 'No blocks found', 'scrutineer' ) ?>: </strong>
			<small>
				<strong><?php echo number_format($blockless_posts['total']); ?></strong> <?php _e( 'pages', 'scrutineer' ) ?>
			</small>
		</summary>
		<table class="widefat fixed striped scrutineer-table" cellspacing="0">
			<thead>
				<tr class="scrutineer-row">
					<th class="manage-column scrutineer-column-title"><?php _e( 'Title', 'scrutineer' ) ?></th>
					<th class="manage-column scrutineer-column-count"><?php _e( 'Date', 'scrutineer' ) ?></th>
					<th class="manage-column scrutineer-column-count"><?php _e( 'Type', 'scrutineer' ) ?></th>
					<th class="manage-column scrutineer-column-edit" aria-label="Actions"></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$posts = $blockless_posts['posts'];
				sort($posts);
				foreach ($posts as $post_id): 
					$type = get_post_type($post_id);
					$format = get_post_format ( $post_id );
					$status = get_post_status ( $post_id );
					?>
					<tr class="scrutineer-row status-<? echo $status ?>">
						<td class="scrutineer-column-title column-title column-primary">
							<strong>
								<a class="row-title" href="<?php echo get_permalink($post_id); ?>">
									<? echo get_the_title($post_id); ?>
								</a>
								<?php if ($status != "publish"): ?>
									<span class="scrutineer-status scrutineer-status--is-<?php echo $status; ?>"><?php echo $status; ?></span>
								<?php endif; ?>
							</strong>
						</td>
						<td class="scrutineer-column-date"><time datetime="<?php echo get_the_date('c', $post_id); ?>" itemprop="datePublished"><?php echo get_the_date('M j, Y', $post_id); ?></time></td>
						<td class="scrutineer-column-type"><?php echo $type ?> <?php if (!empty($format)): ?><small>(<em><?php echo $format; ?></strong>)</em><?php endif; ?></td>
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
	<?php endif; ?>

</div>