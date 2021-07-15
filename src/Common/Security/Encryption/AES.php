<?php

namespace Bumeran\Common\Security\Encryption;

use InvalidArgumentException;

/**
 * Class AES
 *
 * Based on http://aesencryption.net
 *
 * @package Bumeran\Common\Security\Encryption
 * @author Andy Ecca <andy.ecca@gmail.com>
 * @copyright (c) 2016, Orbis
 */
class AES
{
    protected $key;
    protected $cipher;
    protected $data;
    protected $mode;
    protected $iv;

    public function __construct(
        $data = null,
        $key = null,
        $blockSize = 128,
        $mode = MCRYPT_MODE_ECB,
        $iv = ""
    ) {
        $this->setData($data);
        $this->setKey($key);
        $this->setBlockSize($blockSize);
        $this->setMode($mode);
        $this->setIV($iv);
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @param int $blockSize
     * @return $this
     */
    public function setBlockSize($blockSize)
    {
        switch ($blockSize) {
            case 128:
                $this->cipher = MCRYPT_RIJNDAEL_128;
                break;

            case 192:
                $this->cipher = MCRYPT_RIJNDAEL_192;
                break;

            case 256:
                $this->cipher = MCRYPT_RIJNDAEL_256;
                break;

            default:
                throw new InvalidArgumentException(
                    "Block size '$blockSize' is not supported: use (128, 192, 256) instead"
                );
        }

        return $this;
    }

    /**
     * @param string $mode
     * @return $this
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @param string $iv
     * @return $this
     */
    public function setIV($iv)
    {
        $this->iv = $iv;

        return $this;
    }

    protected function getIV()
    {
        if (empty($this->iv)) {
            $this->iv = mcrypt_create_iv(mcrypt_get_iv_size($this->cipher, $this->mode), MCRYPT_RAND);
        }

        return $this->iv;
    }

    public function encrypt()
    {
        $this->validateParams();

        return trim(base64_encode(mcrypt_encrypt($this->cipher, $this->key, $this->data, $this->mode, $this->getIV())));
    }


    public function decrypt()
    {
        $this->validateParams();

        return trim(mcrypt_decrypt($this->cipher, $this->key, base64_decode($this->data), $this->mode, $this->getIV()));
    }

    private function validateParams()
    {
        if (! isset($this->data) || ! isset($this->key) || ! isset($this->cipher)) {
            throw new InvalidArgumentException('Missing paramaters');
        }
    }
}
