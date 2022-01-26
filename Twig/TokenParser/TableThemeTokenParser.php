<?php
/*
 * This file is part of TableBundle.
 *
 * (c) David Coudrier <david.coudrier@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Nours\TableBundle\Twig\TokenParser;

use Nours\TableBundle\Twig\Node\TableThemeNode;
use Twig\Node\Expression\ArrayExpression;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

/**
 * Class TableThemeTokenParser
 * 
 * @author David Coudrier <david.coudrier@gmail.com>
 */
class TableThemeTokenParser extends AbstractTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function parse(Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $table = $this->parser->getExpressionParser()->parseExpression();

        $resources = new ArrayExpression(array(), $stream->getCurrent()->getLine());
        do {
            $resources->addElement($this->parser->getExpressionParser()->parseExpression());
        } while (!$stream->test(Token::BLOCK_END_TYPE));

        $stream->expect(Token::BLOCK_END_TYPE);

        return new TableThemeNode($table, $resources, $lineno, $this->getTag());
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'table_theme';
    }
}