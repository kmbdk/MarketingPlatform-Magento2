<?php

namespace EP\Emailplatform\Model\Config\Source;

use EP\Emailplatform\Helper\Config as Helper;

/**
 * Class SubscribeLayoutProcessor
 */
class SubscribeLayoutProcessor
{
    /**
     * @var Helper
     */
    protected $_helper;

    /**
     * @param Helper $helper
     */
    public function __construct(Helper $helper)
    {
        $this->_helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function process($jsLayout)
    {

        
        if ($this->_helper->getConfigGeneral('enabled') && $this->_helper->getConfigCheckout('active')) {
            
            $jsLayoutSubscribe = [
                'components' => [
                    'checkout' => [
                        'children' => [
                            'steps' => [
                                'children' => [
                                    'billing-step' => [
                                        'children' => [
                                            'payment' => [
                                                'children' => [
                                                    'customer-email' => [
                                                        'config' => [
                                                            'template' => 'EP_Emailplatform/form/element/email'
                                                        ],
                                                        'children' => [
                                                            'newsletter-subscribe' => [
                                                                'config' => [
                                                                    'checkoutLabel' => 'Subscribe to our newsletter',
                                                                    'checked' => false,
                                                                    'visible' => true,
                                                                    'changeable' => true,
                                                                    'template' => 'EP_Emailplatform/form/element/newsletter-subscribe'
                                                                ],
                                                                'component' => 'Magento_Ui/js/form/form',
                                                                'displayArea' => 'newsletter-subscribe',
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ],
                                    'shipping-step' => [
                                        'children' => [
                                            'shippingAddress' => [
                                                'children' => [
                                                    'customer-email' => [
                                                        'config' => [
                                                            'template' => 'EP_Emailplatform/form/element/email'
                                                        ],
                                                        'children' => [
                                                            'newsletter-subscribe' => [
                                                                'config' => [
                                                                    'checkoutLabel' => 'Subscribe to our newsletter',
                                                                    'checked' => false,
                                                                    'visible' => true,
                                                                    'changeable' => true,
                                                                    'template' => 'EP_Emailplatform/form/element/newsletter-subscribe'
                                                                ],
                                                                'component' => 'Magento_Ui/js/form/form',
                                                                'displayArea' => 'newsletter-subscribe',
                                                            ]
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];

            $jsLayout = array_merge_recursive($jsLayout, $jsLayoutSubscribe);
            
        }

        return $jsLayout;
    }
}
