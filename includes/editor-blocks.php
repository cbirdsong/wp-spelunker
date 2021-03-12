
<?php
$blocks = [];
$classic_posts = [];

$query = new WP_Query([
	'post_type' => 'any',
	'posts_per_page' => -1,
]);

foreach ($query->posts as $post) {
	if (!has_blocks($post) && !empty(get_the_content($post))) {
		$classic_posts['total'] += 1;
		$classic_posts['posts'][] = $post->ID;
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

<div class="spelunker-section">

	<?php foreach ($blocks as $name => $block): ?>
	<details class="spelunker-details">
		<summary class="spelunker-summary">
			<strong><?php echo $name; ?></strong>, 
			<small>
				<? if ($block['total'] > 1 && count($block['posts']) > 1): ?> 
					<strong><?php echo number_format($block['total']); ?></strong> <?php _e( 'times across', 'spelunker' ) ?> <strong><?php echo count($block['posts']); ?></strong> <?php _e( 'pages', 'spelunker' ) ?>
				<?php elseif ($block['total'] > 1): ?>
					<strong><?php echo number_format($block['total']); ?></strong> times on <strong>1</strong> <?php _e( 'page', 'spelunker' ) ?>
				<?php else: ?>
					<?php _e( 'once', 'spelunker' ) ?>
				<?php endif; ?>
			</small>
		</summary>
		<table class="widefat fixed striped | spelunker-table" cellspacing="0">
			<thead>
				<tr class="spelunker-row">
					<th class="manage-column | spelunker-column-title"><?php _e( 'Title', 'spelunker' ) ?></th>
					<?php if (count($block['posts']) > 1): ?> 
					<th class="manage-column | spelunker-column-count"><?php _e( 'Blocks', 'spelunker' ) ?></th>
					<?php endif; ?>
					<th class="manage-column | spelunker-column-count"><?php _e( 'Date', 'spelunker' ) ?></th>
					<th class="manage-column | spelunker-column-count"><?php _e( 'Type', 'spelunker' ) ?></th>
					<th class="manage-column | spelunker-column-edit" aria-label="Actions"></th>
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
						<td class="column-title column-primary | spelunker-column-title">
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
						<td class="spelunker-column-count"><?php echo $count; ?></td>
						<?php endif; ?>
						<td class="spelunker-column-date"><time datetime="<?php echo get_the_date('c', $post_id); ?>" itemprop="datePublished"><?php echo get_the_date('M j, Y', $post_id); ?></time></td>
						<td class="spelunker-column-type"><?php echo $type ?> <?php if (!empty($format)): ?><small>(<em><?php echo $format; ?></strong>)</em><?php endif; ?></td>
						<td class="spelunker-column-edit">
							<a href="/wp-admin/post.php?post=<?php echo $post_id; ?>&action=edit">
								<span class="dashicons dashicons-edit"></span>
								<?php _e( 'Edit', 'spelunker' ) ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</details>
	<?php endforeach; ?>
	
	<?php if ($classic_posts['total'] > 0): ?>
	<details class="spelunker-details spelunker-details--total">
		<summary class="spelunker-summary">
			<strong><?php _e( 'No blocks found', 'spelunker' ) ?>: </strong>
			<small>
				<strong><?php echo number_format($classic_posts['total']); ?></strong> <?php _e( 'pages', 'spelunker' ) ?>
			</small>
		</summary>
		<table class="widefat fixed striped | spelunker-table" cellspacing="0">
			<thead>
				<tr class="spelunker-row">
					<th class="manage-column | spelunker-column-title"><?php _e( 'Title', 'spelunker' ) ?></th>
					<th class="manage-column | spelunker-column-count"><?php _e( 'Date', 'spelunker' ) ?></th>
					<th class="manage-column | spelunker-column-count"><?php _e( 'Type', 'spelunker' ) ?></th>
					<th class="manage-column | spelunker-column-edit" aria-label="Actions"></th>
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
						<td class="spelunker-column-title column-title column-primary">
							<strong>
								<a class="row-title" href="<?php echo get_permalink($post_id); ?>">
									<? echo get_the_title($post_id); ?>
								</a>
								<?php if ($status != "publish"): ?>
									<span class="spelunker-status spelunker-status--is-<?php echo $status; ?>"><?php echo $status; ?></span>
								<?php endif; ?>
							</strong>
						</td>
						<td class="spelunker-column-date"><time datetime="<?php echo get_the_date('c', $post_id); ?>" itemprop="datePublished"><?php echo get_the_date('M j, Y', $post_id); ?></time></td>
						<td class="spelunker-column-type"><?php echo $type ?> <?php if (!empty($format)): ?><small>(<em><?php echo $format; ?></strong>)</em><?php endif; ?></td>
						<td class="spelunker-column-edit">
							<a href="/wp-admin/post.php?post=<?php echo $post_id; ?>&action=edit">
								<span class="dashicons dashicons-edit"></span>
								<?php _e( 'Edit', 'spelunker' ) ?>
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</details>
	<?php endif; ?>

</div>