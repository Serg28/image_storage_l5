<?php namespace Linecore\ImageStorage;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;

abstract class AbstractImageStorageGalleryController extends AbstractImageStorageController
{
    public function doChangeGalleryOrder()
    {
        $idGallery = request()->get('idGallery');
        $idArray   = request()->get('idArray', []);

        $entity = $this->model->find($idGallery);

        $entity->changeGalleryOrder($idArray);

        return Response::json([
            'status' => true
        ]);
    }

    public function doDetachToGallery()
    {
        $idGallery = request()->get('idGallery');
        $idRelated = request()->get('id');

        $entity = $this->model->find($idGallery);

        $entity->detachToGallery($idRelated);

        return Response::json([
            'status' => true
        ]);
    }

    public function doSetGalleryPreview()
    {
        $idGallery = request()->get('idGallery');
        $idPreview = request()->get('idPreview');

        $entity = $this->model->find($idGallery);

        $entity->setPreview($idPreview);

        return Response::json([
            'status' => true
        ]);
    }

    public function doCreateGalleryWith()
    {
        $galleryName = request()->get('galleryName');
        $idArray     = request()->get('idArray', []);

        $fields      = ['title' => $galleryName];

        $entity = $this->model;

        $entity->setFields($fields);

        if(!$entity->save()){
            return Response::json(['status' => false, 'message' => $entity->getErrorMessage()]);
        }

        $entity->relateToGallery($idArray);

        return Response::json([
            'status' => true
        ]);
    }

    public function doAddArrayToGalleries()
    {
        $idGalleries = request()->get('idGalleries', []);
        $idArray     = request()->get('idArray', []);

        foreach ($idGalleries as $key => $id) {
            $entity = $this->model->find($id);
            $entity->relateToGallery($idArray);
        }

        return Response::json([
            'status' => true
        ]);
    }

}
