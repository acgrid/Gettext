<?php
include_once dirname(__DIR__).'/src/autoloader.php';

class TranslationsTest extends PHPUnit_Framework_TestCase
{
    public function testFind()
    {
        //Extract translations
        $translations = Gettext\Extractors\PhpCode::fromFile(__DIR__.'/files/phpcode.php');

        //Find by original
        $translation = $translations->find(null, 'text 2');

        $this->assertInstanceOf('Gettext\\Translation', $translation);
        $this->assertEquals('text 2', $translation->getOriginal());

        //Find by context
        $translation = $translations->find('context', 'text 1 with context');

        $this->assertInstanceOf('Gettext\\Translation', $translation);
        $this->assertEquals('text 1 with context', $translation->getOriginal());
        $this->assertEquals('context', $translation->getContext());

        //Find by plural
        $translation = $translations->find(null, 'text 10 with plural', 'The plural form');

        $this->assertInstanceOf('Gettext\\Translation', $translation);
        $this->assertEquals('text 10 with plural', $translation->getOriginal());
        $this->assertEquals('The plural form', $translation->getPlural());
        $this->assertTrue($translation->hasPlural());

        //No results
        $translation = $translations->find(null, 'text 1 with context');
        $this->assertFalse($translation);

        $translation = $translations->find('no-valid-context', 'text 1 with context');
        $this->assertFalse($translation);

        $translation = $translations->find('context', 'text 2');
        $this->assertFalse($translation);

        $translation = $translations->find(null, 'no valid text 2');
        $this->assertFalse($translation);

        $translation = $translations->find(null, 'text 10 with plural');
        $this->assertFalse($translation);
    }

    public function testGettersSetters()
    {
        //Extract translations
        $translations = Gettext\Extractors\Po::fromFile(__DIR__.'/files/po.po');

        $this->assertEquals('gettext generator test', $translations->getHeader('Project-Id-Version'));

        $translations->setHeader('POT-Creation-Date', '2012-08-07 13:03+0100');
        $this->assertEquals('2012-08-07 13:03+0100', $translations->getHeader('POT-Creation-Date'));
    }
}
