<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ToolController extends Controller
{
    public function actionShopLocation($map, $location)
    {
        if(!glob('../web/images/maps/'. $map)){
            throw new NotFoundHttpException(Yii::t('app', 'There is no the map name {map_name} in RO108.', [
                'map_name' => $map,
            ]));
        }

        // Create a image from file.
        $image = imagecreatefromgif('../web/images/maps/'. $map);

        $axis = explode(',', $location);

        if($axis[0] == '' || $axis[1] == ''){
            throw new NotFoundHttpException(Yii::t('app', 'Your location is not correct. Please make sure the pattern of location has to look like this (x,y).'));
        }

        // draw the blue ellipse
        imagefilledellipse($image, $axis[0] - 3, $axis[1] - 3, 14, 14, imagecolorallocate($image, 255, 0, 0));
        imagefilledellipse($image, $axis[0] - 3, $axis[1] - 3, 7, 7, imagecolorallocate($image, 0, 255, 0));

        // Output the image.
        header("Content-type: image/gif");
        imagegif($image);
    }
}
