<?php

namespace WeDevelop\ElementalMedia\Model;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\TextField;
use WeDevelop\MediaField\Form\MediaField;

/**
 * @property string $MediaType
 * @property string $Video
 * @property string $VideoRatio
 * @property string $VideoEmbedURL
 * @property string $VideoType
 * @property string $EmbedName
 * @property string $EmbedDescription
 * @property string $EmbedImage
 * @property string $EmbedCreated
 * @property string $MediaCaption
 */
class ElementalMedia extends BaseElement
{
    /** @config */
    private static string $icon = 'font-icon-picture';

    /** @config */
    private static string $table_name = 'ElementalMedia';

    /** @config */
    private static string $title = 'Media block';

    /** @config */
    private static string $description = 'Media block with a photo or video';

    /** @config */
    private static string $singular_name = 'Media block';

    /** @config */
    private static string $plural_name = 'Media blocks';

    public function getType(): string
    {
        return _t(__CLASS__ . '.BlockType', 'Media');
    }

    /** @config */
    private static array $db = [
        'MediaType' => 'Varchar',
        'Video' => 'Varchar',
        'VideoRatio' => 'Varchar(10)',
        'VideoEmbedURL' => 'Varchar',
        'VideoType' => 'Varchar',
        'EmbedName' => 'Varchar(255)',
        'EmbedDescription' => 'Text',
        'EmbedImage' => 'Varchar(255)',
        'EmbedCreated' => 'Varchar(255)',
        'MediaCaption' => 'Varchar(255)',
    ];

    /** @config */
    private static array $has_one = [
        'MediaImage' => Image::class,
        'MediaThumbnail' => Image::class,
    ];

    /** @config */
    private static array $owns = [
        'MediaImage',
        'MediaThumbnail',
    ];

    private static array $mediaRatios = [
        '' => 'Default',
        '1x1' => '1x1',
        '4x3' => '4x3',
        '16x9' => '16x9',
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'VideoEmbedURL',
            'VideoType',
            'VideoRatio',
            'EmbedName',
            'EmbedDescription',
            'EmbedImage',
            'EmbedCreated',
            'MediaThumbnail',
            'Image',
            'MediaCaption',
        ]);

        $fields->addFieldsToTab('Root.Main', [
            $mediaField = new MediaField($fields, 'MediaType', 'MediaImage', 'Video', 'MediaBlocks'),
        ]);

        $mediaField->getVideoWrapper()->push(DropdownField::create('VideoRatio', 'Video ratio', self::$mediaRatios)->setEmptyString('16x9 (Default)'));
        $mediaField->getVideoWrapper()->push(TextField::create('MediaCaption', 'Caption text'));
        $mediaField->getVideoWrapper()->push(
            UploadField::create('MediaThumbnail', 'Custom thumbnail')
            ->setDescription('This overwrites the default thumbnail provided by youtube or vimeo')
            ->displayIf('MediaType')->isEqualTo('video')->end()
        );

        $fields->addFieldsToTab('Root.VideoData', [
            ReadonlyField::create('EmbedDescription', 'Description'),
            ReadonlyField::create('EmbedImage', 'Image'),
            ReadonlyField::create('EmbedCreated', 'Created'),
        ]);

        return $fields;
    }

    public function onBeforeWrite(): void
    {
        parent::onBeforeWrite();

        if ($this->Video) {
            $this->Video = trim($this->Video);
            MediaField::saveEmbed($this, 'Video', 'VideoEmbedURL', 'VideoType' , 'EmbedName', 'EmbedDescription', 'EmbedImage', 'EmbedCreated');
        } else {
            $this->owner->EmbedName = '';
            $this->owner->EmbedDescription = '';
            $this->owner->EmbedImage = '';
            $this->owner->EmbedCreated = '';
        }
    }

    public function BulmaVideoRatio(): string
    {
        return $this->VideoRatio === '4x3' ? '4by3' : '16by9';
    }

    public function forTemplate($holder = true): ?string
    {
        Requirements::javascript('wedevelopnl/silverstripe-elemental-media:client/dist/main.js');

        return parent::forTemplate();
    }
}
