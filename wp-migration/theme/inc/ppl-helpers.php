<?php
// Get a page meta value with a fallback default.
function ppl_get( $key, $default = '' ) {
    $val = get_post_meta( get_the_ID(), $key, true );
    return $val !== '' ? $val : $default;
}

// Echo an escaped page meta value with a fallback default.
function ppl_e( $key, $default = '' ) {
    echo esc_html( ppl_get( $key, $default ) );
}

/**
 * Minimal Bootstrap 5 nav walker.
 * Adds nav-item to <li> and nav-link + custom classes to <a>.
 */
class PPL_Bootstrap_Nav_Walker extends Walker_Nav_Menu {
    public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
        $link_class = $args->link_class ?? 'nav-link';
        $atts = [
            'href'   => $item->url,
            'class'  => $link_class . ( in_array( 'current-menu-item', $item->classes, true ) ? ' active' : '' ),
            'target' => $item->target ?: '',
            'rel'    => $item->xfn ?: '',
        ];
        $attrs = '';
        foreach ( array_filter( $atts ) as $k => $v ) {
            $attrs .= ' ' . $k . '="' . esc_attr( $v ) . '"';
        }
        $output .= '<li class="nav-item"><a' . $attrs . '>' . esc_html( $item->title ) . '</a></li>';
    }
    public function end_el( &$output, $item, $depth = 0, $args = null ) {}
}

/**
 * Render a WP nav menu with Bootstrap classes, falling back to a plain list.
 */
function ppl_nav_menu( $location, $args = [] ) {
    if ( ! has_nav_menu( $location ) ) return;
    wp_nav_menu( array_merge( [
        'theme_location' => $location,
        'container'      => false,
        'walker'         => new PPL_Bootstrap_Nav_Walker(),
        'items_wrap'     => '<ul class="%2$s">%3$s</ul>',
    ], $args ) );
}
