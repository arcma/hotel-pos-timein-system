<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class BCGcode
{
    public function __construct()
    {
        require_once('BCGcode/BCGFontFile.php');
        require_once('BCGcode/BCGColor.php');
        require_once('BCGcode/BCGDrawing.php');
        require_once('BCGcode/BCGcode128.barcode.php');
    }
}
