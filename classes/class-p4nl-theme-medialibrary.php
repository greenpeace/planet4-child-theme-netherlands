<?php
/**
 * Media Library customization
 *
 * @package P4NL_CT
 */

if ( ! class_exists( 'P4NL_Theme_MediaLibrary' ) ) {
	/**
	 * Class P4NL_Theme_MediaLibrary
	 */
	class P4NL_Theme_MediaLibrary {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->hooks();
		}

		/**
		 * Initiate our hooks
		 */
		public function hooks() {
			add_filter( 'manage_upload_columns', [$this, 'add_column_file_size'] );
			add_action( 'manage_media_custom_column', [$this, 'column_file_size'], 10, 2 );
			add_action( 'admin_print_styles-upload.php', [$this, 'filesize_column_filesize'] );
			// add_filter( 'mime_types', 'webp_upload_mimes' );
		}

		public function add_column_file_size( $columns ) {
			$columns['filesize'] = 'File Size';
			return $columns;
		}

		public function column_file_size( $column_name, $media_item ) {
			if ( 'filesize' !== $column_name || ! wp_attachment_is_image( $media_item ) ) {
				return;
			}
			$filesize = filesize( get_attached_file( $media_item ) );
			$filesize = size_format( $filesize, 1 );
			echo $filesize;
		}


		/**
		 * Adjust File Size column on Media Library page in WP admin
		 */
		public function filesize_column_filesize() {
			echo '<style>
                            .fixed .column-filesize {
                                width: 10%;
                            }
                        </style>';
		}

		private function webp_upload_mimes( $existing_mimes ) {
			// add webp to the list of mime types.
			$existing_mimes['webp'] = 'image/webp';

			// return the array back to the function with our added mime type.
			return $existing_mimes;
		}


	}
}
