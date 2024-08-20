<?php

namespace Dissect\Lexer;

use Dissect\Lexer\TokenStream\ArrayTokenStream;
use Dissect\Lexer\TokenStream\TokenStream;
use Dissect\Parser\Parser;
use Dissect\Util\Util;

/**
 * Fast regex lexer, adapted from Doctrine.
 *
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author Jonathan Wage <jonwage@gmail.com>
 * @author Roman Borschel <roman@code-factory.org>
 * @author Jakub Lédl <jakubledl@gmail.com>
 */
abstract class RegexLexer implements Lexer
{
    /**
     * {@inheritDoc}
     */
    public function lex(string $string): TokenStream
    {
        static $regex;

        if (! isset($regex)) {
            $regex = '/('.implode(')|(', $this->getCatchablePatterns()).')|'
                .implode('|', $this->getNonCatchablePatterns()).'/i';
        }

        $string = strtr($string, ["\r\n" => "\n", "\r" => "\n"]);

        $flags = PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_OFFSET_CAPTURE;
        $matches = preg_split($regex, $string, -1, $flags);
        $tokens = [];
        $line = 1;
        $column = 1;

        foreach ($matches as $match) {
            [$value, $position] = $match;

            $type = $this->getType($value);

            if ($position > 0) {
                $line = Util::lineNumber($string, $position);
                $column = Util::columnNumber($string, $position, $line);
            }

            $tokens[] = new CommonToken($type, $value, $line, $column);
        }

        $tokens[] = new CommonToken(Parser::EOF_TOKEN_TYPE, '', $line, $column);

        return new ArrayTokenStream($tokens);
    }

    /**
     * The patterns corresponding to tokens.
     */
    abstract protected function getCatchablePatterns(): array;

    /**
     * The patterns corresponding to tokens to be skipped.
     */
    abstract protected function getNonCatchablePatterns(): array;

    /**
     * Retrieves the token type.
     *
     *
     * @return string $type
     */
    abstract protected function getType(string &$value): string;
}
