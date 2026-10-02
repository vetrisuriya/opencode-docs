<?php
/**
 * Component: Team Leaders
 *
 * Location: {theme}/components/team-leaders/team-leaders.php
 *
 * Usage (page template or another component):
 *
 *   get_template_part( 'components/team-leaders/team-leaders', null, [
 *       'title'   => 'Meet our leaders',
 *       'leaders' => [
 *           [
 *               'name'   => 'Tom Lindell',
 *               'role'   => 'Managing Director',
 *               'bio'    => 'Short bio text.',
 *               'before' => 123,                         // attachment ID or image URL (photo)
 *               'after'  => 124,                         // attachment ID or image URL (sketch)
 *               'links'  => [
 *                   [ 'type' => 'linkedin', 'url' => 'https://linkedin.com/in/...' ],
 *                   [ 'type' => 'x',        'url' => 'https://x.com/...' ],
 *                   [ 'type' => 'mail',     'url' => 'tom@example.com' ],
 *               ],
 *           ],
 *       ],
 *   ] );
 *
 * Or leave 'leaders' empty and let it read an ACF repeater (see README.md).
 *
 * Link types: linkedin, x, instagram, facebook, youtube, github, mail, web
 */

defined( 'ABSPATH' ) || exit;

/* ------------------------------------------------------------------
 * Helpers (guarded so the component can be included more than once)
 * ---------------------------------------------------------------- */

if ( ! function_exists( 'tl_leaders_assets' ) ) {
	/** Register + enqueue this component's CSS/JS (child-theme safe). */
	function tl_leaders_assets() {
		$dir = trailingslashit( apply_filters( 'tl_leaders_dir', 'components/team-leaders' ) );
		$css = get_theme_file_path( $dir . 'team-leaders.css' );
		$js  = get_theme_file_path( $dir . 'team-leaders.js' );

		if ( ! wp_style_is( 'tl-leaders', 'registered' ) ) {
			$deps = [];

			// Return false from this filter if the theme already loads Montserrat / Open Sans.
			if ( apply_filters( 'tl_leaders_load_google_fonts', true ) ) {
				wp_register_style(
					'tl-leaders-fonts',
					'https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700&family=Open+Sans:ital,wght@0,400;0,600;1,400&display=swap',
					[],
					null
				);
				$deps[] = 'tl-leaders-fonts';
			}

			wp_register_style(
				'tl-leaders',
				get_theme_file_uri( $dir . 'team-leaders.css' ),
				$deps,
				file_exists( $css ) ? filemtime( $css ) : null
			);
			wp_register_script(
				'tl-leaders',
				get_theme_file_uri( $dir . 'team-leaders.js' ),
				[],
				file_exists( $js ) ? filemtime( $js ) : null,
				true
			);
		}

		wp_enqueue_style( 'tl-leaders' );
		wp_enqueue_script( 'tl-leaders' );
	}
}

if ( ! function_exists( 'tl_leaders_img' ) ) {
	/** Output an <img> from an attachment ID, ACF image array or URL. */
	function tl_leaders_img( $src, $class ) {
		if ( is_array( $src ) ) {
			$src = $src['ID'] ?? ( $src['url'] ?? '' );
		}
		if ( empty( $src ) ) {
			return '';
		}
		if ( is_numeric( $src ) ) {
			return wp_get_attachment_image(
				(int) $src,
				'large',
				false,
				[ 'class' => $class, 'alt' => '', 'loading' => 'lazy', 'decoding' => 'async' ]
			);
		}
		return sprintf(
			'<img class="%s" src="%s" alt="" loading="lazy" decoding="async">',
			esc_attr( $class ),
			esc_url( $src )
		);
	}
}

if ( ! function_exists( 'tl_leaders_link_labels' ) ) {
	function tl_leaders_link_labels() {
		return [
			'linkedin'  => 'LinkedIn',
			'x'         => 'X',
			'instagram' => 'Instagram',
			'facebook'  => 'Facebook',
			'youtube'   => 'YouTube',
			'github'    => 'GitHub',
			'mail'      => 'Email',
			'web'       => 'Website',
		];
	}
}

/* ------------------------------------------------------------------
 * Data
 * ---------------------------------------------------------------- */

$args = wp_parse_args(
	isset( $args ) && is_array( $args ) ? $args : [],
	[
		'title'      => __( 'Meet our leaders', 'your-textdomain' ),
		'leaders'    => [],
		'acf_field'  => 'team_leaders', // ACF repeater name
		'acf_source' => false,          // false = current post, or a post ID, or 'option'
		'class'      => '',
	]
);

$leaders = $args['leaders'];

// Fallback: read from an ACF repeater when no array was passed in.
if ( empty( $leaders ) && function_exists( 'get_field' ) ) {
	foreach ( (array) get_field( $args['acf_field'], $args['acf_source'] ) as $row ) {
		$leaders[] = [
			'name'   => $row['name'] ?? '',
			'role'   => $row['role'] ?? '',
			'bio'    => $row['bio'] ?? '',
			'before' => $row['photo_before'] ?? '',
			'after'  => $row['photo_after'] ?? '',
			'links'  => (array) ( $row['links'] ?? [] ),
		];
	}
}

if ( empty( $leaders ) ) {
	return;
}

tl_leaders_assets();

$labels = tl_leaders_link_labels();
$uid    = wp_unique_id( 'tl-' );
$bio_ok = [
	'a'      => [ 'href' => [], 'target' => [], 'rel' => [] ],
	'strong' => [],
	'em'     => [],
	'br'     => [],
];
?>
<section class="tl <?php echo esc_attr( $args['class'] ); ?>" data-tl aria-labelledby="<?php echo esc_attr( $uid ); ?>-title">

	<?php if ( $args['title'] ) : ?>
		<h2 class="tl__title" id="<?php echo esc_attr( $uid ); ?>-title"><?php echo esc_html( $args['title'] ); ?></h2>
	<?php endif; ?>

	<div class="tl__grid">
		<?php
		foreach ( $leaders as $i => $leader ) :
			$name = $leader['name'] ?? '';
			if ( '' === $name ) {
				continue;
			}
			$role   = $leader['role'] ?? '';
			$bio    = $leader['bio'] ?? '';
			$before = $leader['before'] ?? '';
			$after  = $leader['after'] ?? '';
			$links  = (array) ( $leader['links'] ?? [] );
			?>
			<article class="tl-card">
				<button class="tl-card__toggle" type="button" aria-expanded="false">
					<span class="tl-card__img" aria-hidden="true">
						<?php
						echo tl_leaders_img( $before, 'tl-card__before' ); // phpcs:ignore WordPress.Security.EscapeOutput
						// No sketch uploaded yet? Reuse the photo with a grayscale fallback so the effect still shows.
						echo $after
							? tl_leaders_img( $after, 'tl-card__after' ) // phpcs:ignore WordPress.Security.EscapeOutput
							: tl_leaders_img( $before, 'tl-card__after tl-demo' ); // phpcs:ignore WordPress.Security.EscapeOutput
						?>
						<span class="tl-card__badge">+</span>
					</span>
					<span class="tl-card__name"><?php echo esc_html( $name ); ?></span>
					<?php if ( $role ) : ?>
						<span class="tl-card__role"><?php echo esc_html( $role ); ?></span>
					<?php endif; ?>
				</button>

				<div class="tl-card__details" hidden>
					<h3 class="tl-panel__name"><?php echo esc_html( $name ); ?></h3>
					<?php if ( $role ) : ?>
						<p class="tl-panel__role"><?php echo esc_html( $role ); ?></p>
					<?php endif; ?>
					<div>
						<?php if ( $bio ) : ?>
							<p class="tl-panel__bio"><?php echo wp_kses( $bio, $bio_ok ); ?></p>
						<?php endif; ?>

						<?php if ( $links ) : ?>
							<ul class="tl-panel__links">
								<?php
								foreach ( $links as $link ) :
									$type = $link['type'] ?? 'web';
									$url  = $link['url'] ?? '';
									if ( ! $url ) {
										continue;
									}
									if ( 'mail' === $type && is_email( $url ) ) {
										$url = 'mailto:' . $url;
									}
									$label    = ! empty( $link['label'] ) ? $link['label'] : ( $labels[ $type ] ?? $labels['web'] );
									$external = 'mail' !== $type;
									?>
									<li>
										<a class="tl-soc" data-type="<?php echo esc_attr( $type ); ?>" href="<?php echo esc_url( $url ); ?>"<?php echo $external ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
											<?php echo esc_html( $label ); ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
					</div>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</section>
