<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\RestructuredText\Parser\Productions;

use phpDocumentor\Guides\Nodes\ListItemNode;
use phpDocumentor\Guides\Nodes\ListNode;

final class ListRuleTest extends AbstractRuleTest
{
    /** @dataProvider startChars */
    public function testAppliesForAllPossibleStartChars($char): void
    {
        $input = $char . ' test';

        $context = $this->createContext($input);

        $rule = new ListRule();
        self::assertTrue($rule->applies($context));
    }

    /** @return string[][] */
    public function startChars(): array
    {
        return [
            ["*"],
            ["+"],
            ["-"],
            ["•"],
            ["‣"],
            ["⁃"],
        ];
    }

    public function testListDoesNotExceptEnumerated(): void
    {
        $input = '1 test';
        $context = $this->createContext($input);

        $rule = new ListRule();
        self::assertFalse($rule->applies($context));
    }

    public function testListItemContentCanBeOnANewLine(): void
    {
        $input = <<<INPUT
-
  test
INPUT;

        $context = $this->createContext($input);

        $rule = new ListRule();
        self::assertTrue($rule->applies($context));
    }

    public function testListItemMustBeIntendedThisListIsAnEmptyList(): void
    {
        $input = <<<INPUT
-
test
INPUT;

        $context = $this->createContext($input);

        $rule = new ListRule();
        self::assertTrue($rule->applies($context));
    }

    public function testSimpleListCreation(): void
    {
        $input = <<<INPUT
- first items
- second item

Not included
INPUT;

        $context = $this->createContext($input);

        $rule = new ListRule();
        $result = $rule->apply($context);

        self::assertRemainingEquals(
            <<<REST

Not included

    REST,
            $context->getDocumentIterator()
        );

//        self::assertEquals(
//            new ListNode(
//                [
//                    new ListItemNode('-', false),
//                    new ListItemNode('-', false)
//                ]
//            )
//        );
    }

    public function testListWithoutNewLineInParagraphResultsInWarning(): void
    {
        $input = <<<INPUT
- first items
- second item
Not included
INPUT;

        $context = $this->createContext($input);

        $rule = new ListRule();
        $result = $rule->apply($context);

        self::assertRemainingEquals(
            <<<REST
Not included

REST,
            $context->getDocumentIterator()
        );

//        self::assertEquals(
//            new ListNode(
//                [
//                    new ListItemNode('-', false),
//                    new ListItemNode('-', false)
//                ]
//            )
//        );
    }

    public function testListFistTekstOnNewLine(): void
    {
        $input = <<<INPUT
- 
  first items
- 
  second item
  other line

Not included
INPUT;

        $context = $this->createContext($input);

        $rule = new ListRule();
        $result = $rule->apply($context);

        self::assertRemainingEquals(
            <<<REST

Not included

REST,
            $context->getDocumentIterator()
        );

//        self::assertEquals(
//            new ListNode(
//                [
//                    new ListItemNode('-', false),
//                    new ListItemNode('-', false)
//                ]
//            )
//        );
    }
}
