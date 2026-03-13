<?php

declare(strict_types=1);

namespace Spatie\ImageOptimizer;

interface Optimizer
{
    /**
     * Returns the name of the binary to be executed.
     */
    public function binaryName(): string;

    /**
     * Determines if the given image can be handled by the optimizer.
     */
    public function canHandle(Image $image): bool;

    /**
     * Set the path to the image that should be optimized.
     *
     *
     * @return $this
     */
    public function setImagePath(string $imagePath);

    /**
     * Set the options the optimizer should use.
     *
     *
     * @return $this
     */
    public function setOptions(array $options = []);

    /**
     * Get the command that should be executed.
     */
    public function getCommand(): string;

    /**
     * Get the temporary file's path.
     */
    public function getTmpPath(): ?string;
}
