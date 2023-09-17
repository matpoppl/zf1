<?php

namespace App\Uploads\Filename;

class FilenameValidator extends \Zend_Validate_StringLength
{
    public const TRANLIST_ERROR   = 'stringLengthInvalid';

    /** @var Transliterator */
    private $translit = null;

    public function __construct($options = array())
    {
        parent::__construct($options);

        $this->_messageTemplates += [
            self::TRANLIST_ERROR => 'Tranliteration error',
        ];
    }

    public function isValid($value)
    {
        $basename = basename($value);

        if (! $this->getTransliterator()->trans($basename)) {
            $this->_error(self::TRANLIST_ERROR);
            return false;
        }

        return parent::isValid($basename);
    }

    public function getTransliterator()
    {
        if (null === $this->translit) {
            $this->translit = new Transliterator();
        }

        return $this->translit;
    }
}
