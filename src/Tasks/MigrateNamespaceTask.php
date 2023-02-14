<?php

namespace WeDevelop\ElementalMedia\Tasks;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Dev\BuildTask;
use WeDevelop\ElementalMedia\Model\ElementalMedia;

class MigrateNamespaceTask extends BuildTask
{
    protected $title = 'Elemental media namespace migration';

    protected $description = 'Migrate elemental media TheWebmen namespace to WeDevelop namespace';

    private static $segment = 'migrate-elemental-media-namespace';

    public function run($request)
    {
        $elements = BaseElement::get()
            ->where([
                'ClassName' => 'TheWebmen\ElementalMedia\Model\ElementalMedia'
            ]);

        $counter = 0;
        $totalElements = $elements->count();

        print_r(sprintf("Starting migration of %s elements\n\n", $totalElements));

        /** @var BaseElement $element */
        foreach ($elements as $element) {
            $isPublished = $element->isPublished();

            $element->setField('ClassName', ElementalMedia::class);
            $element->write();

            if ($isPublished) {
                $element->publishSingle();
            }

            $counter++;

            print_r(sprintf("Migrated %s of %s elements\n", $counter, $totalElements));
        }

        print_r(sprintf("\n\nMigration done for %s elements!", $counter));
    }
}
