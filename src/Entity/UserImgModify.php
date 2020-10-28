<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;



class UserImgModify
{

    /**
     * @Assert\NotBlank(message="Insert a picture")
     * @Assert\Image(mimeTypes={"image/png","image/jpeg","image/gif"}, mimeTypesMessage="Only jpg, png ou gif")
     * @Assert\File(maxSize="1024k", maxSizeMessage="Your picture is too big")
     */
    private $newPicture;

 
    public function getNewPicture()
    {
        return $this->newPicture;
    }

    public function setNewPicture($newPicture)
    {
        $this->newPicture = $newPicture;

        return $this;
    }
}