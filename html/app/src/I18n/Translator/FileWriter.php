<?php

namespace App\I18n\Translator;

class FileWriter
{
    /** @var string */
    private $pathname;

    /** @var bool */
    private $dirty = false;

    /** @var string[] */
    private $data = null;

    public function __construct($pathname)
    {
        $this->pathname = $pathname;
    }

    public function __deconstruct()
    {
        $this->close();
    }

    public function open()
    {
        $this->dirty = false;
        $this->data = require($this->pathname);
        return $this;
    }

    public function close()
    {
        if ($this->dirty) {
            file_put_contents($this->pathname, '<?php return ' . var_export($this->data, true) . ';');
            $this->dirty = false;
            $this->data = null;
        }
        return $this;
    }

    public function setData(array $data)
    {
        if (null === $this->data) {
            $this->open();
        }

        $this->dirty = true;
        $this->data = array_filter($data);

        return $this;
    }

    public function add(string $key, string $value)
    {
        if (null === $this->data) {
            $this->open();
        }

        $this->dirty = true;
        $this->data[$key] = $value;

        return $this;
    }

    public function remove(string $key, string $value)
    {
        if (null === $this->data) {
            $this->open();
        }

        $this->dirty = true;

        if (array_key_exists($key, $this->data)) {
            unset($this->data[$key]);
        }

        return $this;
    }
}
