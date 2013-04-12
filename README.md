#UploadedImageHelper
This is a quick helper to output a form field for the [Jose Gonzalez Upload plugin](https://github.com/josegonzalez/upload) along with the current image displayed as a thumbnail, with a link to view it's original.

##Requirements
Cake 2.x  
Upload plugin - https://github.com/josegonzalez/upload

##Installation
Copy the helper file into your `app/View/Helper` folder, and then add it to your helpers array in your controller.  
`public $helpers = array('UploadedImage');`  

The helper take some settings, which you can find in the source `$settings` property. These can be overwritten by passing new settings into the helper when you add it to the controller.  
```php
public $helpers = array(
    'UploadedImage' => array(
        'field' => 'logo', // Name of the db field - should match the 'field' in your Model's Upload settings
        'dir' => 'logo_dir', // Name of the directory - should match 'dir' in your Model's Upload settings
    )
);
```
