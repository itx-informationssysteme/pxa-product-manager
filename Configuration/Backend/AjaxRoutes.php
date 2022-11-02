<?php

use Pixelant\PxaProductManager\Controller\Ajax\AttributeIdentifierController;
return [
    'pxa-pm-attribute-identifier-convert' => [
        'path' => '/pxa-pm/attribute-identifier-convert',
        'target' => AttributeIdentifierController::class . '::attributeIdentifierConvertAction'
    ]
];
