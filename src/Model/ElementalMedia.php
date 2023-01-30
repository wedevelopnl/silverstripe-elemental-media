<?php

namespace WeDevelop\ElementalMedia\Model;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\TextField;
use SilverStripe\View\Requirements;
use UncleCheese\DisplayLogic\Forms\Wrapper;
use WeDevelop\MediaField\Form\MediaField;

/**
 * @property string $MediaType
 * @property string $MediaCaption
 * @property string $MediaRatio
 *
 * @property string $MediaVideoShortURL
 * @property string $MediaVideoFullURL
 * @property string $MediaVideoProvider
 *
 * @property string $MediaVideoEmbeddedName
 * @property string $MediaVideoEmbeddedDescription
 * @property string $MediaVideoEmbeddedThumbnail
 * @property string $MediaVideoEmbeddedCreated
 *
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

    /** @config */
    private static array $db = [
        'MediaType' => 'Varchar(5)',
        'MediaCaption' => 'Varchar(255)',
        'MediaRatio' => 'Varchar(10)',

        'MediaVideoFullURL' => 'Varchar(255)',
        'MediaVideoProvider' => 'Varchar(10)',
        'MediaVideoHasOverlay' => 'Boolean(false)',

        'MediaVideoEmbeddedName' => 'Varchar(255)',
        'MediaVideoEmbeddedURL' => 'Varchar(255)',
        'MediaVideoEmbeddedDescription' => 'Text',
        'MediaVideoEmbeddedThumbnail' => 'Varchar(255)',
        'MediaVideoEmbeddedCreated' => 'Varchar(255)',
    ];

    /** @config */
    private static array $has_one = [
        'MediaImage' => Image::class,
        'MediaVideoCustomThumbnail' => Image::class,
    ];

    /** @config */
    private static array $owns = [
        'MediaImage',
        'MediaVideoCustomThumbnail',
    ];

    private static array $mediaRatios = [
        '' => 'Auto (default)',
        '1x1' => '1x1',
        '4x3' => '4x3',
        '16x9' => '16x9',
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->removeByName([
            'MediaType',
            'MediaCaption',
            'MediaRatio',

            'MediaVideoShortURL',
            'MediaVideoFullURL',
            'MediaVideoProvider',
            'MediaVideoCustomThumbnail',
            'MediaVideoHasOverlay',

            'MediaVideoEmbeddedName',
            'MediaVideoEmbeddedURL',
            'MediaVideoEmbeddedDescription',
            'MediaVideoEmbeddedThumbnail',
            'MediaVideoEmbeddedCreated',
        ]);

        $mediaField = MediaField::create($fields);
        $mediaField->setTitle('Video settings');
        $mediaField->getVideoWrapper()->push(
            UploadField::create('MediaVideoCustomThumbnail', 'Custom video thumbnail')
                ->setFolderName('MediaUploads')
                ->setDescription('This overwrites the default thumbnail provided by youtube or vimeo'),
        );

        $fields->addFieldsToTab('Root.Main', [
            $mediaField,
            Wrapper::create([
                CheckboxField::create('MediaVideoHasOverlay', 'Show overlay on top of video thumbnail'),
            ])->displayIf('MediaType')->isEqualTo('video')->end(),
            TextField::create('MediaCaption', 'Caption text'),
            DropdownField::create('MediaRatio', 'Media ratio', self::$mediaRatios)
                ->setEmptyString('Auto (default)')
                ->setDescription('By default, \'Auto\' will make videos appear as 16x9 ratio, while images will be shown as they are'),
        ]);

        if ($this->MediaType === 'video') {
            $fields->addFieldsToTab('Root.VideoEmbeddedData', [
                ReadonlyField::create('MediaVideoEmbeddedURL', 'Shortened URL'),
                ReadonlyField::create('MediaVideoProvider', 'Video provider'),
                ReadonlyField::create('MediaVideoEmbeddedName', 'Embedded name'),
                ReadonlyField::create('MediaVideoEmbeddedDescription', 'Embedded description'),
                ReadonlyField::create('MediaVideoEmbeddedThumbnail', 'Embedded thumbnail URL'),
                FieldGroup::create([
                    LiteralField::create('MediaVideoEmbeddedThumbnailPreview', '<img src="' . $this->MediaVideoEmbeddedThumbnail . '">', 'Embedded thumbnail'),
                ])->setTitle('Video Thumbnail'),
                ReadonlyField::create('MediaVideoEmbeddedCreated', 'Embedded publication date'),
            ]);
        }

        return $fields;
    }

    public function BulmaRatio(): ?string
    {
        if (!$this->MediaRatio && $this->MediaType === 'video') {
            return 'is-16by9';
        }

        return $this->MediaRatio ? 'is-' . str_replace('x', 'by', $this->MediaRatio) : null;
    }

    public function onBeforeWrite(): void
    {
        parent::onBeforeWrite();

        if ($this->MediaType === 'video' && $this->MediaVideoFullURL) {
            $this->MediaVideoFullURL = trim($this->MediaVideoFullURL);
            MediaField::saveEmbed($this);
        }
    }

    public function forTemplate($holder = true): ?string
    {
        Requirements::javascript('wedevelopnl/silverstripe-elemental-media:client/dist/main.js');
        Requirements::css('wedevelopnl/silverstripe-elemental-media:client/dist/main.css');

        return parent::forTemplate();
    }

    public function getType(): string
    {
        return 'Media';
    }
}
