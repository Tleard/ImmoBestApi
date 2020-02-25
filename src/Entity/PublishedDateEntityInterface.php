<?php


namespace App\Entity;


use DateTimeInterface as DateTimeInterfaceAlias;

interface PublishedDateEntityInterface
{
    public function setPublished(DateTimeInterfaceAlias $published): PublishedDateEntityInterface;
}