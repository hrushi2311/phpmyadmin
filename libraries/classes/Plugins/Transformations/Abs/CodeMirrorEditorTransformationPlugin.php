<?php
/**
 * Abstract class for syntax highlighted editors using CodeMirror
 */

declare(strict_types=1);

namespace PhpMyAdmin\Plugins\Transformations\Abs;

use PhpMyAdmin\FieldMetadata;
use PhpMyAdmin\Plugins\IOTransformationsPlugin;

use function htmlspecialchars;
use function strtolower;

/**
 * Provides common methods for all the CodeMirror syntax highlighted editors
 */
abstract class CodeMirrorEditorTransformationPlugin extends IOTransformationsPlugin
{
    /**
     * Does the actual work of each specific transformations plugin.
     *
     * @param string             $buffer  text to be transformed
     * @param array              $options transformation options
     * @param FieldMetadata|null $meta    meta information
     */
    public function applyTransformation($buffer, array $options = [], FieldMetadata|null $meta = null): string
    {
        return $buffer;
    }

    /**
     * Returns the html for input field to override default textarea.
     * Note: Return empty string if default textarea is required.
     *
     * @param array  $column             column details
     * @param int    $rowId              row number
     * @param string $columnNameAppendix the name attribute
     * @param array  $options            transformation options
     * @param string $value              Current field value
     * @param string $textDir            text direction
     * @param int    $tabIndex           tab index
     * @param int    $tabIndexForValue   offset for the values tabindex
     * @param int    $idIndex            id index
     *
     * @return string the html for input field
     */
    public function getInputHtml(
        array $column,
        int $rowId,
        string $columnNameAppendix,
        array $options,
        string $value,
        string $textDir,
        int $tabIndex,
        int $tabIndexForValue,
        int $idIndex,
    ): string {
        $html = '';
        if (! empty($value)) {
            $html = '<input type="hidden" name="fields_prev' . $columnNameAppendix
                . '" value="' . htmlspecialchars($value) . '">';
        }

        $class = 'transform_' . strtolower(static::getName()) . '_editor';

        return $html . '<textarea name="fields' . $columnNameAppendix . '"'
            . ' dir="' . $textDir . '" class="' . $class . '">'
            . htmlspecialchars($value) . '</textarea>';
    }
}
