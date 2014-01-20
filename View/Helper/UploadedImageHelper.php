<?php
/**
 * UploadedImageHelper
 * A helper to output form fields related to the Upload plugin. For editing
 * image and displaying them.
 *
 * @author David Yell <neon1024@gmail.com>
 * @related Plugin https://github.com/josegonzalez/upload
 */

App::uses('AppHelper', 'View/Helper');

class UploadedImageHelper extends AppHelper{

/**
 * Add in the helpers that we want to use
 *
 * @var array
 */
    public $helpers = array('Html', 'Form');

/**
 * Store the settings for the helper, as passed in from it's initialisation
 * in the controller. Defaults are already set, but can be overwritten when
 * defining the helper in your controller.
 *
 * @var array
 */
    public $settings = array(
        'field' => 'image',
        'dir' => 'image_dir',
        'label' => '<p>Current image</p>',
        'thumbWidth' => 200
    );

    public $pathPattern = '/files/{model}/{field}/{imageDir}/{imageFile}';

/**
 * Startup the helper
 *
 * @param View $View
 * @param array $settings
 */
    public function __construct(View $View, $settings = array()) {
        parent::__construct($View, $settings);

        if (!empty($settings)) {
            $this->settings = array_merge($this->settings, $settings);
        }
		
		if (!isset($settings['model'])) {
			$this->Model = Inflector::classify($View->name);
		} else {
			$this->Model = $settings['model'];
		}

        if (isset($settings['filePathPattern'])) {
            $this->pathPattern = $settings['filePathPattern'];
        }
    }

/**
 * Output the formatted image field links and show the existing image resized
 * but with a link to view the original
 */
    public function display() {
        if (isset($this->request->data[$this->Model][$this->settings['field']]) && !empty($this->request->data[$this->Model][$this->settings['field']]) && !is_array($this->request->data[$this->Model][$this->settings['field']])) {

            $imagePath = strtr($this->pathPattern,
                array(
                    '{model}' => Inflector::underscore($this->Model),
                    '{field}' => $this->settings['field'],
                    '{imageDir}' => $this->request->data[$this->Model][$this->settings['dir']],
                    '{imageFile}' => $this->request->data[$this->Model][$this->settings['field']]
                )
            );

            $image = $this->settings['label'];
            $image .= $this->Html->link(
                    $this->Html->image(
                            '../' . $imagePath,
                            array('width' => $this->getThumbnailSize($imagePath))
                        ),
                        $imagePath,
                        array('target' => '_blank', 'escape' => false, 'title' => 'Click to view original (opens in new window)')
                    );
        } else {
            $image = '';
        }

        $return = $this->Form->input($this->settings['field'], array('type' => 'file', 'before' => $image));
        $return .= $this->Form->input($this->settings['dir'], array('type' => 'hidden'));

        return $return;
    }

/**
 * Work out if the image being displayed is smaller than the configured
 * thumbnail size.
 *
 * @param string $file A path to an image file
 *
 * @return int A size in pixels
 */
    private function getThumbnailSize($file) {
        $dimensions = getimagesize(Router::fullbaseUrl() . $file);
        if ($dimensions[0] <= $this->settings['thumbWidth']) {
            return $dimensions[0] . 'px';
        }

        return $this->settings['thumbWidth'] . 'px';
    }

}