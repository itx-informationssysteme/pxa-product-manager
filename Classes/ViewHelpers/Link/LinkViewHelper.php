<?php
declare(strict_types=1);

namespace Pixelant\PxaProductManager\ViewHelpers\Link;

use Pixelant\PxaProductManager\Service\Link\LinkBuilderService;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * Class LinkViewHelper
 *
 * @package Pixelant\PxaProductManager\ViewHelpers\Link
 */
class LinkViewHelper extends AbstractTagBasedViewHelper
{

    /**
     * Tag to render
     *
     * @var string
     */
    protected $tagName = 'a';

    /**
     * @var LinkBuilderService
     */
    protected $linkBuilderService = null;

    /**
     * @param LinkBuilderService $linkBuilderService
     */
    public function injectLinkBuilderService(LinkBuilderService $linkBuilderService)
    {
        $this->linkBuilderService = $linkBuilderService;
    }

    /**
     * Register arguments
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerUniversalTagAttributes();

        $this->registerArgument('product', 'mixed', 'Product to link to single view', false, null);
        // @codingStandardsIgnoreStart
        $this->registerArgument('category', 'mixed', 'Category to link to list view or product single view', false, null);
        // @codingStandardsIgnoreEnd
        $this->registerArgument('pageUid', 'int', 'Target page uid', false, null);
        $this->registerArgument('excludeCategories', 'bool', 'Exclude categories from path', false, false);
        $this->registerArgument('target', 'string', 'Link target', false, null);
        $this->registerArgument('absolute', 'bool', 'Force absolute link', false, false);
        $this->registerArgument('urlOnly', 'bool', 'Return only the url, not markup', false, false);
    }

    /**
     * Render link tag
     *
     * @return string
     */
    public function render()
    {
        $pageUid = empty($this->arguments['pageUid']) ? (int)$GLOBALS['TSFE']->id : (int)$this->arguments['pageUid'];
        $product = $this->arguments['product'];
        $category = $this->arguments['category'];
        $target = $this->arguments['target'];
        $excludeCategories = $this->arguments['excludeCategories'];
        $absolute = $this->arguments['absolute'];
        $urlOnly = $this->arguments['urlOnly'];

        $content = (string)$this->renderChildren();

        if ($pageUid && ($product !== null || $category !== null)) {
            if ($product !== null) {
                $uri = $this->linkBuilderService->buildForProduct($pageUid, $product, $category, $excludeCategories, $absolute);
            } else {
                $uri = $this->linkBuilderService->buildForCategory($pageUid, $category, $absolute);
            }
            
            if ($urlOnly === true) {
                return $uri;
            }

            if ($urlOnly === true) {
                return $uri;
            }

            if (!empty($target)) {
                $this->tag->addAttribute('target', $target);
            }
            $this->tag->addAttribute('href', $uri);
            $this->tag->setContent($content);
            $this->tag->forceClosingTag(true);

            return $this->tag->render();
        }

        return $content;
    }
}
