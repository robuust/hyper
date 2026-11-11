<?php
namespace verbb\hyper\fieldlayoutelements;

use verbb\hyper\base\LinkInterface;
use verbb\hyper\fields\HyperField;

use Craft;
use CraftCms\Cms\FieldLayout\FieldLayoutElementContext;
use CraftCms\Cms\Ui\Contracts\Control;
use CraftCms\Yii2Adapter\FieldLayout\LegacyLayoutElementHtml;
use craft\base\ElementInterface;
use craft\fieldlayoutelements\TextField;

/**
 * Renders the input owned by the selected Hyper link type.
 */
class LinkField extends TextField
{
    // Properties
    // =========================================================================

    public string $attribute = 'linkValue';
    public ?HyperField $field = null;
    public ?LinkInterface $link = null;
    public bool $mandatory = true;
    public bool $requirable = true;


    // Public Methods
    // =========================================================================

    public function __construct($config = [])
    {
        unset(
            $config['mandatory'],
            $config['autofocus'],
            $config['linkType'],
            $config['requirable'],
        );

        parent::__construct($config);
    }
    
    public function showAttribute(): bool
    {
        return true;
    }

    public function defaultLabel(?ElementInterface $element = null, bool $static = false): ?string
    {
        return Craft::t('hyper', 'Link');
    }


    // Protected Methods
    // =========================================================================

    /**
     * Render the link type's HTML instead of the native scalar text control.
     *
     * @param FieldLayoutElementContext $context the field layout rendering context
     * @return Control|null the compatible element selector or link input
     */
    protected function uiControl(FieldLayoutElementContext $context): ?Control
    {
        return app(LegacyLayoutElementHtml::class)
            ->capture($this, $context, $this->inputHtml(...), $this->attribute())
            ?->getControl();
    }

    protected function inputHtml(ElementInterface $element = null, bool $static = false): ?string
    {
        if ($this->link) {
            return $this->link->getInputHtml($this, $this->field);
        }

        return null;
    }
}
