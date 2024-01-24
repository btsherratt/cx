<?php

cx_require('lib', 'images.php');
cx_require('lib', 'url.php');
cx_require('third_party', 'parsedown', 'Parsedown.php');

class ExtendedParsedown extends Parsedown {
	public function __construct() {
		$this->InlineTypes['{'][]= 'BlogImage';
		$this->inlineMarkerList .= '{';
	}

	protected function inlineBlogImage($excerpt) {
		if (preg_match('/^{img:([^}]+)}/', $excerpt['text'], $matches)) {
			$image_id = $matches[1];
			$image = cx_images_find_image($image_id);

			if ($image == null) {
				return;
			}

			$permalink = '/data/images/' . $image->url;

			return array(
				'extent' => strlen($matches[0]) + 1, 
				'element' => array(
					'name' => 'img',
					'attributes' => array(
						'src' => cx_url_site($permalink),
						'alt' => $image->alt_text,
					),
				),
			);
		}
	}
}

function cx_markdown_generate_html($markdown) {
	static $Parsedown = new ExtendedParsedown();
	return $Parsedown->text($markdown);
}
