<?php
/**
 * UploadedImageHelper
 * A helper to output form fields related to the Upload plugin. For editing
 * image and displaying them.
 *
 * @author David Yell <neon1024@gmail.com>
 * @related Plugin https://github.com/josegonzalez/upload
 */

App::uses('AppHelper', 'View');

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

        $this->Model = Inflector::singularize($View->name);
    }

/**
 * Output the formatted image field links and show the existing image resized
 * but with a link to view the original
 */
    public function display() {

        if (isset($this->request->data[$this->Model][$this->settings['field']]) && !empty($this->request->data[$this->Model][$this->settings['field']]) && !is_array($this->request->data[$this->Model][$this->settings['field']])) {
            $image = $this->settings['label'];
            $image .= $this->Html->link(
                    $this->Html->image('../files/' . strtolower($this->Model) . '/' . $this->settings['field'] . '/' . $this->request->data[$this->Model][$this->settings['dir']] . '/' . $this->request->data[$this->Model][$this->settings['field']], array('width' => $this->settings['thumbWidth'] . 'px')),
                    '/files/' . strtolower($this->Model) . '/' . $this->settings['field'] . '/' . $this->request->data[$this->Model][$this->settings['dir']] . '/' . $this->request->data[$this->Model][$this->settings['field']],
                    array('target' => '_blank', 'escape' => false, 'title' => 'Click to view original (opens in new window)')
                    );
        } else {
            $image = '';
        }

		echo $this->Form->input('logo', array('type' => 'file', 'before' => $image));
		echo $this->Form->input('logo_dir', array('type' => 'hidden'));
    }

}