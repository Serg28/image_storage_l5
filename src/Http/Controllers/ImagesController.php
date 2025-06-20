<?php namespace Linecore\ImageStorage;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

class ImagesController extends AbstractImageStorageFileController
{
    protected $model = Image::class;

    public function doOptimizeImage()
    {
        $size = request()->get('size');
        $id   = (array) request()->get('id');

        foreach ($id as $key => $value) {
            $image = $this->model->find($value);
            $image->optimizeImage($size);
        }

        return Response::json([
            'status' => true,
        ]);
    }
}
