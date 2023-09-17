<?php

namespace matpoppl\Thumbnail;

class GDAdapter
{
    private $formats = [
        'jpg' => \IMAGETYPE_JPEG,
        'jpeg' => \IMAGETYPE_JPEG,
        'png' => \IMAGETYPE_PNG,
        'gif' => \IMAGETYPE_GIF,
        'webp' => \IMAGETYPE_WEBP,
        'ico' => \IMAGETYPE_ICO,
    ];

    public function generate(Request $req)
    {
        $img = GDImage::openFile($req->src)->resize($req->width, $req->height);

        if ($req->format) {
            if (! array_key_exists($req->format, $this->formats)) {
                throw new \DomainException('Unsupported format `' . $req->format . '`');
            }

            $img->setType($this->formats[$req->format]);
        }

        return $img->output($req->dest, $req->quality)->getShape();
    }

    public function info(string $pathname)
    {
        $info = getimagesize($pathname);

        return [
            'width' => $info[0],
            'height' => $info[1],
            'mime' => $info['mime'],
            'extension' => image_type_to_extension($info[2], false),
            'channels' => $info['channels'],
            'bits' => $info['bits'],
        ];
    }
}
