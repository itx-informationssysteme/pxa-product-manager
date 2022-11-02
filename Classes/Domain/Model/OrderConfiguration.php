<?php
declare(strict_types=1);

namespace Pixelant\PxaProductManager\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\ORM\Cascade;
use TYPO3\CMS\Core\Http\ApplicationType;
use Pixelant\PxaProductManager\Utility\MainUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FrontendUser;
use TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;

/**
 * Class OrderConfiguration
 *
 * @package Pixelant\PxaProductManager\Domain\Model
 */
class OrderConfiguration extends AbstractEntity
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @Cascade("remove")
     * @var ObjectStorage<OrderFormField>
     */
    protected $formFields = null;

    /**
     * @var bool
     */
    protected $enabledEmailToUser = false;

    /**
     * @var bool
     */
    protected $enabledReplaceWithFeUserFields = false;

    /**
     * @var bool
     */
    protected $orderFormFieldProcessed = false;

    /**
     * @var string
     */
    protected $adminEmails = '';

    /**
     * @var FrontendUserRepository
     */
    protected $frontendUserRepository = null;

    /**
     * @var FrontendUser
     */
    protected $frontendUser = null;

    /**
     * Initialize
     */
    public function __construct()
    {
        $this->initStorageObjects();
    }

    /**
     * Object storage
     */
    protected function initStorageObjects()
    {
        $this->formFields = new ObjectStorage();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param FrontendUserRepository $frontendUserRepository
     */
    public function injectFrontendUserRepository(FrontendUserRepository $frontendUserRepository)
    {
        $this->frontendUserRepository = $frontendUserRepository;
    }

    /**
     * @param OrderFormField $orderFormField
     */
    public function addFormField(OrderFormField $orderFormField)
    {
        $this->formFields->attach($orderFormField);
    }

    /**
     * @param OrderFormField $orderFormField
     */
    public function removeFormField(OrderFormField $orderFormField)
    {
        $this->formFields->detach($orderFormField);
    }

    /**
     * @return bool
     */
    public function isEnabledEmailToUser(): bool
    {
        return $this->enabledEmailToUser;
    }

    /**
     * @param bool $enabledEmailToUser
     */
    public function setEnabledEmailToUser(bool $enabledEmailToUser)
    {
        $this->enabledEmailToUser = $enabledEmailToUser;
    }

    /**
     * @return array
     */
    public function getAdminEmailsArray(): array
    {
        return GeneralUtility::trimExplode("\n", $this->getAdminEmails(), true);
    }

    /**
     * @return string
     */
    public function getAdminEmails(): string
    {
        return $this->adminEmails;
    }

    /**
     * @param string $adminEmails
     */
    public function setAdminEmails(string $adminEmails)
    {
        $this->adminEmails = $adminEmails;
    }

    /**
     * Check if there is configured email field in list of fields and return it's value
     *
     * @return string
     */
    public function getUserEmailFromFormFields(): string
    {
        /** @var OrderFormField $formField */
        foreach ($this->getFormFields() as $formField) {
            if ($formField->isUserEmailField()) {
                return $formField->getValue();
            }
        }

        return '';
    }

    /**
     * @return ObjectStorage
     */
    public function getFormFields(): ObjectStorage
    {
        if (false === $this->orderFormFieldProcessed && defined('TYPO3') && ApplicationType::fromRequest($GLOBALS['TYPO3_REQUEST'])->isFrontend()) {
            $this->orderFormFieldProcessed = true;
            $this->prepareOrderFormFields();
        }

        return $this->formFields;
    }

    /**
     * @param ObjectStorage $formFields
     */
    public function setFormFields(ObjectStorage $formFields)
    {
        $this->formFields = $formFields;
    }

    /**
     * Set order fields value depending on form configuration
     *
     * @return OrderConfiguration
     */
    public function prepareOrderFormFields()
    {
        // Replace form fields with fe user values
        if ($this->isEnabledReplaceWithFeUserFields() && $this->getFrontendUser()) {
            /** @var OrderFormField $formField */
            foreach ($this->formFields as $formField) {
                $fieldName = GeneralUtility::underscoredToLowerCamelCase($formField->getName());

                if (ObjectAccess::isPropertyGettable($this->getFrontendUser(), $fieldName)) {
                    $formField->setValue(ObjectAccess::getProperty($this->getFrontendUser(), $fieldName));
                    $formField->setStatic(true);
                }
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabledReplaceWithFeUserFields(): bool
    {
        return $this->enabledReplaceWithFeUserFields;
    }

    /**
     * @param bool $enabledReplaceWithFeUserFields
     */
    public function setEnabledReplaceWithFeUserFields(bool $enabledReplaceWithFeUserFields)
    {
        $this->enabledReplaceWithFeUserFields = $enabledReplaceWithFeUserFields;
    }

    /**
     * @return FrontendUser
     */
    public function getFrontendUser()
    {
        if ($this->frontendUser === null && MainUtility::isFrontendLogin()) {
            $userUid = MainUtility::getTSFE()->fe_user->user['uid'];
            $this->frontendUser = $this->frontendUserRepository->findByUid($userUid);
        }

        return $this->frontendUser;
    }
}
