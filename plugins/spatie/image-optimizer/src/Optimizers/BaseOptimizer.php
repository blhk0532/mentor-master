<?php

declare(strict_types=1);

namespace Spatie\ImageOptimizer\Optimizers;

use Spatie\ImageOptimizer\Optimizer;

abstract class BaseOptimizer implements Optimizer
{
    public $options = [];

    public $imagePath = '';

    public $binaryPath = '';

    public $tmpPath = null;

    public function __construct($options = [])
    {
        $this->setOptions($options);
    }

    final public function binaryName(): string
    {
        return $this->binaryName;
    }

    final public function setBinaryPath(string $binaryPath)
    {
        if (mb_strlen($binaryPath) > 0 && mb_substr($binaryPath, -1) !== DIRECTORY_SEPARATOR) {
            $binaryPath = $binaryPath.DIRECTORY_SEPARATOR;
        }

        $this->binaryPath = $binaryPath;

        return $this;
    }

    final public function setImagePath(string $imagePath)
    {
        $this->imagePath = $imagePath;

        return $this;
    }

    final public function setOptions(array $options = [])
    {
        $this->options = $options;

        return $this;
    }

    final public function getCommand(): string
    {
        $optionString = implode(' ', $this->options);

        return "\"{$this->binaryPath}{$this->binaryName}\" {$optionString} ".escapeshellarg($this->imagePath);
    }

    final public function getTmpPath(): ?string
    {
        return $this->tmpPath;
    }
}
