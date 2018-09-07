<?php

namespace TheWebmen\ElementalMedia\Model;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\DropdownField;
use TheWebmen\MediaField\Form\MediaField;

class ElementalMedia extends BaseElement
{
    private static $maps_api_key = false;

    private static $icon = 'font-icon-picture';

    private static $table_name = 'ElementalMedia';

    private static $title = 'Media';

    private static $description = 'Media block with a photo or video';

    private static $singular_name = 'Media';

    private static $plural_name = 'Medias';

    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Media');
    }

    private static $db = [
        'MediaType' => 'Varchar',
        'Video' => 'Varchar',
        'VideoRatio' => 'Varchar(10)',
        'VideoEmbedURL' => 'Varchar',
        'VideoType' => 'Varchar'
    ];

    private static $has_one = [
        'MediaImage' => Image::class
    ];

    private static $owns = [
        'MediaImage'
    ];

    private static $video_ratios = [
        '4x3' => '4x3'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('VideoEmbedURL');
        $fields->removeByName('VideoType');
        $fields->removeByName('VideoRatio');

        $fields->addFieldToTab('Root.Main', $mediaField = new MediaField($fields, 'MediaType', 'MediaImage', 'Video', 'MediaBlocks'));
        $mediaField->getVideoWrapper()->push(DropdownField::create('VideoRatio', 'Video ratio', self::$video_ratios)->setEmptyString('16x9 (Default)'));

        return $fields;
    }

    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        MediaField::saveEmbed($this, 'Video', 'VideoEmbedURL', 'VideoType');
    }

    public function getSummary()
    {
        return $this->MapLocation;
    }
}
