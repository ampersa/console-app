<?php
/**
 * Console App Framework
 *
 * @author Adam Prickett <adam.prickett@gmail.com>
 * @license MIT
 * @copyright © Copyright Adam Prickett 2016.
 */

namespace System\Console;

trait CommandOutput
{
    protected $foregroundColours = [
        'black'     => '30',
        'red'       => '31',
        'green'     => '32',
        'yellow'    => '33',
        'blue'      => '34',
        'magenta'   => '35',
        'cyan'      => '36',
        'white'     => '37',
        'default'   => '39',
    ];

    protected $backgroundColours = [
        'black'     => '40',
        'red'       => '41',
        'green'     => '42',
        'yellow'    => '43',
        'blue'      => '44',
        'magenta'   => '45',
        'cyan'      => '46',
        'white'     => '47',
        'default'   => '49',
    ];

    protected $styles = [
        'bold'      => '1',
        'underscore'=> '4',
        'blink'     => '5',
    ];


    protected $stringStarter = "\033[";
    protected $stringTerminator = "\033[0m";

    /**
     * Output a line in with optional styling
     * @param  string $string
     * @param  array  $options
     * @return void
     */
    public function output($string, $options = [])
    {
        printf("\033[%sm%s%s".PHP_EOL, $this->parseSetCodes($this->foregroundColours['default'], $options), $string, $this->stringTerminator);
    }

    /**
     * Output an "info" line in with optional styling
     * @param  string $string
     * @param  array  $options
     * @return void
     */
    public function info($string, $options = [])
    {
        printf("\033[%sm%s%s".PHP_EOL, $this->parseSetCodes($this->foregroundColours['blue'], $options), $string, $this->stringTerminator);
    }

    /**
     * Output a "highlighted" line in with optional styling
     * @param  string $string
     * @param  array  $options
     * @return void
     */
    public function highlight($string, $options = [])
    {
        printf("\033[%sm%s%s".PHP_EOL, $this->parseSetCodes($this->foregroundColours['magenta'], $options), $string, $this->stringTerminator);
    }

    /**
     * Output a "warning" line in with optional styling
     * @param  string $string
     * @param  array  $options
     * @return void
     */
    public function warn($string, $options = [])
    {
        printf("\033[%sm%s%s".PHP_EOL, $this->parseSetCodes($this->foregroundColours['yellow'], $options), $string, $this->stringTerminator);
    }

    /**
     * Output a "danger" line in with optional styling
     * @param  string $string
     * @param  array  $options
     * @return void
     */
    public function danger($string, $options = [])
    {
        printf("\033[%sm%s%s".PHP_EOL, $this->parseSetCodes($this->foregroundColours['red'], $options), $string, $this->stringTerminator);
    }

    /**
     * Output an "error" line in with optional styling
     * @param  string $string
     * @param  array  $options
     * @return void
     */
    public function error($string, $options = [])
    {
        printf("\033[%sm%s%s".PHP_EOL, $this->parseSetCodes([$this->foregroundColours['white'], $this->backgroundColours['red']], $options), $string, $this->stringTerminator);
    }

    /**
     * Parse colour and styling codes into an escape code
     * @param  string|array $colour
     * @param  array        $options
     * @return string
     */
    protected function parseSetCodes($colour, array $options)
    {
        $output = [];
        $output[] = is_array($colour) ? implode(';', $colour) : $colour;
        
        foreach ($options as $opt) {
            if (isset($this->styles[$opt])) {
                $output[] = $this->styles[$opt];
            }
        }

        return implode(';', $output);
    }
}
