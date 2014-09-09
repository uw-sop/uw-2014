<?php

//
// UW Dropdown Menus
//

if ( ! function_exists( 'uw_dropdowns') )
{
  function uw_dropdowns()
  {

    echo
        '<nav id="dawgdrops" aria-label="Main menu" role="navigation">
          <h3 class="assistive-text">Main menu</h3>';

          wp_nav_menu( array(
            'theme_location'  => UW_Dropdowns::LOCATION,
            'container_class' => 'dawgdrops-inner container',
            'menu_class'      => 'dawgdrops-nav',
            'fallback_cb'     => '',
            'walker'          => new UW_Dropdowns_Walker_Menu()
          ) );

    echo '</nav>';
  }

}

if ( ! function_exists('uw_sidebar_menu') ) :

  function uw_sidebar_menu()
  {
    echo sprintf( '<nav role="navigation" aria-label="relative navigation">%s</nav>', uw_list_pages() ) ;
  }

endif;

if ( ! function_exists( 'uw_mobile_menu' ) ) :

  function uw_mobile_menu()
  {
    echo sprintf( '<nav role="navigation" aria-label="relative navigation">%s</nav>', uw_list_pages( $mobile = true ) ) ;
  }

endif;


if ( ! function_exists( 'uw_list_pages') ) :

  function uw_list_pages( $mobile = false )
  {
    global $post;

    $parent = get_post( $post->post_parent );

    if ( ! $mobile && ! get_children( $post->ID ) && $parent->ID == $post->ID ) return;

    $toggle = $mobile ? '<span class="uw-mobile-menu-toggle">Menu</span>' : '';
    $class  = $mobile ? 'uw-mobile-menu' : 'uw-sidebar-menu';

    $siblings = get_pages( array (
      'parent' => $parent->post_parent,
      'post_type' => 'page',
      'exclude' => $parent->ID
    ) );

    $ids = ! is_front_page() ? array_map( function($sibling) { return $sibling->ID; }, $siblings ) : array();

    $pages = wp_list_pages(array(
      'title_li' => '<a href="'.get_bloginfo('url').'" title="Home" class="homelink">Home</a>',
      'child_of' => $parent->post_parent,
      'exclude_tree' => $ids,
      'depth' => 3,
      'echo'  => 0
    ));

    return $pages ? sprintf( '<ul class="%s first-level">%s%s</ul>', $class, $toggle, $pages ) : '';

  }

endif;

if( ! function_exists('get_uw_breadcrumbs') ) :

  function get_uw_breadcrumbs()
  {

    global $post;

    $ancestors = array_reverse( get_post_ancestors( $post->ID ) );

    if ( ! is_home() || ! is_front_page() )
      $ancestors[] = $post->ID;

    $html = '<li><a href="http://uw.edu" title="University of Washington">Home</a></li>';
    $html .= '<li' . ( ! $ancestors || is_front_page() ? ' class="current"' : '') . '><a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('title') . '">' . get_bloginfo('title') . '</a><li>';

    if ( ! is_front_page() )
    {
      foreach ( $ancestors as $index=>$ancestor )
      {
        $class      = $index+1 == count($ancestors) ? ' class="current" ' : '';
        $page       = get_post( $ancestor );
        $url        = get_permalink( $page->ID );
        $title_attr = esc_attr( $page->post_title );
        $html .= "<li $class><a href=\"$url\" title=\"{$title_attr}\">{$page->post_title}</a>";
      }
    }

    return "<nav class='uw-breadcrumbs' role='navigation' aria-label='breadcrumbs relative navigation'><ul>$html</ul></nav>";
  }

endif;

if( ! function_exists('uw_breadcrumbs') ) :

  function uw_breadcrumbs()
  {
    echo get_uw_breadcrumbs();
  }

endif;


if ( ! function_exists( 'uw_thumbnail_url' ) ) :
  function uw_thumbnail_url( $size = 'original' )
  {
    echo uw_get_thumbnail_url( $size );
  }
endif;

if ( ! function_exists( 'uw_get_thumbnail_url' ) ) :
  function uw_get_thumbnail_url( $size = 'original' )
  {
    $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'original', true);
    return $thumbnail[0];
  }
endif;

if ( ! function_exists( 'is_pdf' ) ):

  function is_pdf() {
    return get_post_mime_type() == 'application/pdf';
  }

endif;
