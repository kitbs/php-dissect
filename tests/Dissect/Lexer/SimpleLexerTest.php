<?php

namespace Dissect\Lexer;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SimpleLexerTest extends TestCase
{
    protected SimpleLexer $lexer;

    public function setUp(): void
    {
        $this->lexer = new SimpleLexer;

        $this->lexer
            ->token('A', 'a')
            ->token('(')
            ->token('B', 'b')
            ->token(')')
            ->token('C', 'c')
            ->regex('WS', "/[ \n\t\r]+/")

            ->skip('WS');
    }

    #[Test]
    public function simpleLexerShouldWalkThroughTheRecognizers()
    {
        $stream = $this->lexer->lex('a (b) c');

        $this->assertEquals(6, $stream->count()); // with EOF
        $this->assertEquals('(', $stream->get(1)->getType());
        $this->assertEquals(1, $stream->get(2)->getLine());
        $this->assertEquals(4, $stream->get(2)->getColumn());
        $this->assertEquals('C', $stream->get(4)->getType());
    }

    #[Test]
    public function simpleLexerShouldSkipSpecifiedTokens()
    {
        $stream = $this->lexer->lex('a (b) c');

        foreach ($stream as $token) {
            $this->assertNotEquals('WS', $token->getType());
        }
    }

    #[Test]
    public function simpleLexerShouldReturnTheBestMatch()
    {
        $this->lexer->token('CLASS', 'class');
        $this->lexer->regex('WORD', '/[a-z]+/');

        $stream = $this->lexer->lex('class classloremipsum');

        $this->assertEquals('CLASS', $stream->getCurrentToken()->getType());
        $this->assertEquals(1, $stream->getCurrentToken()->getColumn());
        $this->assertEquals('WORD', $stream->lookAhead(1)->getType());
        $this->assertEquals(7, $stream->lookAhead(1)->getColumn());
    }

    #[Test]
    public function itShouldTrackLineNumbers()
    {
        $stream = $this->lexer->lex("a\nb\n\nc");

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
        $stream = $this->lexer->lex('a b c');

        $this->assertEquals(1, $stream->get(0)->getLine());
        $this->assertEquals(1, $stream->get(0)->getColumn());
        $this->assertEquals(1, $stream->get(1)->getLine());
        $this->assertEquals(3, $stream->get(1)->getColumn());
        $this->assertEquals(1, $stream->get(2)->getLine());
        $this->assertEquals(5, $stream->get(2)->getColumn());
    }
}
