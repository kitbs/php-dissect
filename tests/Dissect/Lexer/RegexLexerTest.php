<?php

namespace Dissect\Lexer;

use Dissect\Parser\Parser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RegexLexerTest extends TestCase
{
    protected StubRegexLexer $lexer;

    protected function setUp(): void
    {
        $this->lexer = new StubRegexLexer;
    }

    #[Test]
    public function itShouldCallGetTypeToRetrieveTokenType()
    {
        $stream = $this->lexer->lex('5 + 6');

        $this->assertCount(4, $stream);
        $this->assertEquals('INT', $stream->get(0)->getType());
        $this->assertEquals('+', $stream->get(1)->getType());
        $this->assertEquals(Parser::EOF_TOKEN_TYPE, $stream->get(3)->getType());
    }

    #[Test]
    public function itShouldTrackLineNumbers()
    {
        $stream = $this->lexer->lex("5\n+\n\n5");

        $this->assertEquals(1, $stream->get(0)->getLine());
        $this->assertEquals(1, $stream->get(0)->getColumn());
        $this->assertEquals(2, $stream->get(1)->getLine());
        $this->assertEquals(1, $stream->get(1)->getColumn());
        $this->assertEquals(4, $stream->get(2)->getLine());
        $this->assertEquals(1, $stream->get(2)->getColumn());
    }

    #[Test]
    public function itShouldTrackLineAndColumnNumbers()
    {
        $stream = $this->lexer->lex('5 + 5');

        $this->assertEquals(1, $stream->get(0)->getLine());
        $this->assertEquals(1, $stream->get(0)->getColumn());
        $this->assertEquals(1, $stream->get(1)->getLine());
        $this->assertEquals(3, $stream->get(1)->getColumn());
        $this->assertEquals(1, $stream->get(2)->getLine());
        $this->assertEquals(5, $stream->get(2)->getColumn());
    }
}
