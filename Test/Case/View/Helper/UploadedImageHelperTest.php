<?php
/**
 * Description of UploadedImageHelperTest
 *
 * @author David Yell <neon1024@gmail.com>
 */

App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('UploadedImageHelper', 'UploadedImage.View/Helper');
App::uses('CakeRequest', 'Network');

class UploadedImageHelperTest extends CakeTestCase {

    public $UploadedImage;
    public $View;

    public function setUp() {
        parent::setUp();
        $Controller = new Controller();
        $this->View = new View($Controller);
        $this->View->name = 'TestView';
    }

    public function providerSettings() {
        return array(
            array(
                'image' => '200x200.jpg',
                'field' => 'image',
                'dir' => 1,
                'label' => '<p>Current image</p>',
                'thumbWidth' => 200
            ),
            array(
                'image' => null,
                'field' => 'image',
                'dir' => 1,
                'label' => '<p>Current image</p>',
                'thumbWidth' => 200
            ),
        );
    }

    /**
     * @dataProvider providerSettings
     */
    public function testDisplay($image, $field, $dir, $label) {
        $imagePathPattern = CakePlugin::path('UploadedImage') . 'Test' . DS . 'Fixture' . DS . 'Images' . DS . 'testview' . DS . $field . DS . $dir . DS . $image;

        $this->View->request = new CakeRequest();
        $this->View->request->data['TestView']['image'] = $image;
        $this->View->request->data['TestView']['image_dir'] = 1;

        $this->UploadedImage = new UploadedImageHelper($this->View, array('filePathPattern' => $imagePathPattern));
        $result = $this->UploadedImage->display();

        $this->assertContains('<label for="image">Image</label>', $result);
        $this->assertContains('<input type="file" name="data[image]"  id="image"/>', $result);

        if ($image !== null) {
            $this->assertContains($imagePathPattern, $result);
        }
    }

}