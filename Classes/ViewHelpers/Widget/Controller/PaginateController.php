<?php
declare(strict_types=1);

namespace Pixelant\PxaProductManager\ViewHelpers\Widget\Controller;

use Psr\Http\Message\ResponseInterface;
/**
 * Class PaginateController
 *
 * @package Pixelant\PxaProductManager\ViewHelpers\Widget\Controller
 */
class PaginateController extends \TYPO3\CMS\Fluid\ViewHelpers\Widget\Controller\PaginateController
{
    /**
     * @param int $currentPage
     */
    public function indexAction($currentPage = 1): ResponseInterface
    {
        if ($currentPage > $this->numberOfPages) {
            $currentPage = $this->numberOfPages;
        }

        parent::indexAction($currentPage);
        return $this->htmlResponse();
    }
}
