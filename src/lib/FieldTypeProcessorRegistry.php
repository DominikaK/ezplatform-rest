<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest;

/**
 * FieldTypeProcessorRegistry.
 */
class FieldTypeProcessorRegistry
{
    /**
     * Registered processors.
     *
     * @var \EzSystems\EzPlatformRest\FieldTypeProcessor[]
     */
    private $processors = [];

    /**
     * @param \EzSystems\EzPlatformRest\FieldTypeProcessor[] $processors
     */
    public function __construct(array $processors = [])
    {
        foreach ($processors as $fieldTypeIdentifier => $processor) {
            $this->registerProcessor($fieldTypeIdentifier, $processor);
        }
    }

    /**
     * Registers $processor for $fieldTypeIdentifier.
     *
     * @param string $fieldTypeIdentifier
     * @param \EzSystems\EzPlatformRest\FieldTypeProcessor $processor
     */
    public function registerProcessor($fieldTypeIdentifier, FieldTypeProcessor $processor)
    {
        $this->processors[$fieldTypeIdentifier] = $processor;
    }

    /**
     * Returns if a processor is registered for $fieldTypeIdentifier.
     *
     * @param string $fieldTypeIdentifier
     *
     * @return bool
     */
    public function hasProcessor($fieldTypeIdentifier)
    {
        return isset($this->processors[$fieldTypeIdentifier]);
    }

    /**
     * Returns the processor for $fieldTypeIdentifier.
     *
     * @param string $fieldTypeIdentifier
     *
     * @throws \RuntimeException if not processor is registered for $fieldTypeIdentifier
     *
     * @return \EzSystems\EzPlatformRest\FieldTypeProcessor
     */
    public function getProcessor($fieldTypeIdentifier)
    {
        if (!$this->hasProcessor($fieldTypeIdentifier)) {
            throw new \RuntimeException(
                "No field type processor for '{$fieldTypeIdentifier}' found."
            );
        }

        return $this->processors[$fieldTypeIdentifier];
    }
}
