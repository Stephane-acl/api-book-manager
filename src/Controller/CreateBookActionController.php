<?php

namespace App\Controller;

use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;

final class CreateBookActionController
{

    public function __invoke(Request $request): Book
    {
        $uploadedFile = $request->files->get('file');

        // if (!$uploadedFile) {
        //     throw new BadRequestHttpException('"file" is required');
        // }

        $bookImage = new Book();

        $bookImage
            ->setTitle($request->request->get('title'))
            ->setLanguage($request->request->get('language'))
            ->setDateOfPublication($request->request->get('dateOfPublication'))
            ->setDescription($request->request->get('description'))
            ->setNbrPages($request->request->get('nbrPages'))
            ->setIsAvailable($request->request->get('isAvailable'));
        $bookImage->file = $uploadedFile;
        return $bookImage;
    }
}