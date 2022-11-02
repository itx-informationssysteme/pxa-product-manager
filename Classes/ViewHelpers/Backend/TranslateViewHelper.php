<?php

namespace Pixelant\PxaProductManager\ViewHelpers\Backend;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

/**
 * Translate for BE layouts
 *
 * @package Pixelant\PxaProductManager\ViewHelpers\Backend
 */
class TranslateViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * Translate label
     *
     * @param array                     $arguments
     * @param \Closure                  $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return mixed|string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $key = $arguments['key'];
        $arguments = $arguments['arguments'];

        /** @var \TYPO3\CMS\Core\Localization\LanguageService $lang */
        $lang = $GLOBALS['LANG'];
        $label = $lang->sL('LLL:EXT:pxa_product_manager/Resources/Private/Language/locallang_be.xlf:' . $key) ?? '';

        if (!empty($arguments)) {
            $label = vsprintf($label, $arguments);
        }

        return $label;
    }

    /**
     * Initialize arguments
     *
     * @api
     */
    public function initializeArguments()
    {
        $this->registerArgument('key', 'string', 'Key to translate', true);
        $this->registerArgument('arguments', 'array', 'Translate arguments', false, []);
    }
}
