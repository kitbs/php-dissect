<?php

namespace Dissect\Lexer;

use Dissect\Lexer\Exception\RecognitionException;
use Dissect\Lexer\TokenStream\ArrayTokenStream;
use Dissect\Lexer\TokenStream\TokenStream;
use Dissect\Parser\Parser;
use Dissect\Util\Util;

/**
 * A base class for a lexer. A superclass simply
 * has to implement the extractToken and shouldSkipToken methods. Both
 * SimpleLexer and StatefulLexer extend this class.
 *
 * @author Jakub Lédl <jakubledl@gmail.com>
 */
abstract class AbstractLexer implements Lexer
{
    protected int $line = 1;

    protected int $column = 1;

    /**
     * Returns the current line.
     *
     * @return int The current line.
     */
    protected function getCurrentLine(): int
    {
        return $this->line;
    }

    /**
     * Returns the current column.
     *
     * @return int The current column.
     */
    protected function getCurrentColumn(): int
    {
        return $this->column;
    }

    /**
     * Attempts to extract another token from the string.
     * Returns the token on success or null on failure.
     *
     * @param  string  $string  The string to extract the token from.
     * @return Token|null The extracted token or null.
     */
    abstract protected function extractToken(string $string): ?Token;

    /**
     * Should given token be skipped?
     *
     * @param  Token  $token  The token to evaluate.
     * @return bool Whether to skip the token.
     */
    abstract protected function shouldSkipToken(Token $token): bool;

    /**
     * {@inheritDoc}
     */
    public function lex(string $string): TokenStream
    {
        // normalize line endings
        $string = strtr($string, ["\r\n" => "\n", "\r" => "\n"]);

        $tokens = [];
        $position = 0;
        $originalString = $string;
        $originalLength = Util::stringLength($string);

        while (true) {
            $token = $this->extractToken($string);

            if ($token === null) {
                break;
            }

            if (! $this->shouldSkipToken($token)) {
                $tokens[] = $token;
            }

            $shift = Util::stringLength($token->getValue());

            $position += $shift;

            // update line + offset
            if ($position > 0) {
                $this->line = Util::lineNumber($originalString, $position);
                $this->column = Util::columnNumber($originalString, $position, $this->line);
            }

            $string = Util::substring($string, $shift);
        }

        if ($position !== $originalLength) {
            throw new RecognitionException($this->line, $this->column);
        }

        $tokens[] = new CommonToken(Parser::EOF_TOKEN_TYPE, '', $this->line, $this->column);

        return new ArrayTokenStream($tokens);
    }
}
