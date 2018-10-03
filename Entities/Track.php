<?php

namespace App\Entities;

class Track
{

    /**
     * @var string Исполнитель
     */
    private $artist;

    /**
     * @var string Название трека
     */
    private $track;

    /**
     * @var string Изобарежние
     */
    private $image;

    public function __construct(string $artist, string $track, string $image = null)
    {
        $this->artist = $artist;
        $this->track = $track;
        $this->image = $image;
    }

    public function toArray(): array
    {
        return [
            'artist' => $this->artist,
            'track'  => $this->track,
            'image'  => $this->image,
        ];
    }
}