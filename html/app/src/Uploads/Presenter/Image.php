<?php

namespace App\Uploads\Presenter;

use matpoppl\Paths\Paths;
use matpoppl\Thumbnail\Generator;
use matpoppl\Thumbnail\Request;
use matpoppl\Thumbnail\RectShape;
use matpoppl\Thumbnail\Result;

class Image
{
    /** @var Paths */
    private $paths;
    /** @var Generator */
    private $thumbGen;

    private $presets = [
        'thumb' => [
            'width' => 250,
            'height' => 250,
            'format' => 'webp',
            'quality' => 0.35,
            // @TODO
            // 'density|pixel_ratio|pr' => 2,
            // 'watermark' => 'PREDEFINED_ID',
        ],
    ];

    public function __construct(Paths $paths)
    {
        $this->paths = $paths;
        $this->thumbGen = new Generator();
    }

    /**
     *
     * @param string $path
     * @param string|array $preset
     * @return \matpoppl\Thumbnail\Result
     */
    public function prepare($path, $preset = null)
    {
        $settings = [];
        if (null === $preset) {
            $preset = key($this->presets);
        } elseif (is_array($preset)) {
            $settings = $preset;
            if (array_key_exists('preset', $settings)) {
                $preset = $settings['preset'];
                unset($settings['preset']);
            } else {
                $preset = null;
            }
        }

        if (array_key_exists($preset, $this->presets)) {
            $settings += $this->presets[$preset];
        }

        $settings['src'] = $this->paths->get($path);
        // @TODO better destination path
        $settings['dest'] = $this->paths->get('cache-' . $path)->asPathInfo();

        if ($preset) {
            $settings['dest']->dirname = $settings['dest']->dirname->append($preset);
        }

        /*
        if ($diff = array_diff_key($array1, $this->presets)) {
            $settings['dest']->suffix = '-' . implode('-', $diff);
        }
        */

        if (array_key_exists('format', $settings)) {
            $settings['dest']->extension = $settings['format'];
        }

        // OUTPUT

        $result = (object) [
            'path' => '' . $this->paths->get('doc-root')->relative('' . $settings['dest']),
            'width' => $settings['width'],
            'height' => $settings['height'],
        ];

        if (! file_exists('' . $settings['dest'])) {
            $thumb = $this->thumbGen->generate(Request::create($settings));
            $result->width = $thumb->width;
            $result->height = $thumb->height;
        }

        return $result;
    }
}
