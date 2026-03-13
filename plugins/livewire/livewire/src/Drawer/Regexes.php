<?php

declare(strict_types=1);

namespace Livewire\Drawer;

class Regexes
{
    public static $livewireOpeningTag = "
        <
            \s*
            livewire[-\:]([\w\-\:\.]*)
            (?<attributes>
                (?:
                    \s+
                    (?:
                        (?:
                            @(?:class)(\( (?: (?>[^()]+) | (?-1) )* \))
                        )
                        |
                        (?:
                            @(?:style)(\( (?: (?>[^()]+) | (?-1) )* \))
                        )
                        |
                        (?:
                            \{\{\s*\\\$attributes(?:[^}]+?)?\s*\}\}
                        )
                        |
                        (?:
                            (\:\\\$)(\w+)
                        )
                        |
                        (?:
                            [\w\-:.@%]+
                            (
                                =
                                (?:
                                    \\\"[^\\\"]*\\\"
                                    |
                                    \'[^\']*\'
                                    |
                                    [^\'\\\"=<>]+
                                )
                            )?
                        )
                    )
                )*
                \s*
            )
            (?<![\/=\-])
        >
    ";

    public static $livewireOpeningTagOrSelfClosingTag = "
        <
            \s*
            livewire[-\:]([\w\-\:\.]*)
            (?<attributes>
                (?:
                    \s+
                    (?:
                        (?:
                            @(?:class)(\( (?: (?>[^()]+) | (?-1) )* \))
                        )
                        |
                        (?:
                            @(?:style)(\( (?: (?>[^()]+) | (?-1) )* \))
                        )
                        |
                        (?:
                            \{\{\s*\\\$attributes(?:[^}]+?)?\s*\}\}
                        )
                        |
                        (?:
                            (\:\\\$)(\w+)
                        )
                        |
                        (?:
                            [\w\-:.@%]+
                            (
                                =
                                (?:
                                    \\\"[^\\\"]*\\\"
                                    |
                                    \'[^\']*\'
                                    |
                                    [^\'\\\"=<>]+
                                )
                            )?
                        )
                    )
                )*
                \s*
            )
        \/?>
    ";

    public static $livewireSelfClosingTag = "
        <
            \s*
                livewire[-\:]([\w\-\:\.]*)
                \s*
                (?<attributes>
                    (?:
                        \s+
                        (?:
                            (?:
                                @(?:class)(\( (?: (?>[^()]+) | (?-1) )* \))
                            )
                            |
                            (?:
                                @(?:style)(\( (?: (?>[^()]+) | (?-1) )* \))
                            )
                            |
                            (?:
                                \{\{\s*\\\$attributes(?:[^}]+?)?\s*\}\}
                            )
                            |
                            (?:
                                (\:\\\$)(\w+)
                            )
                            |
                            (?:
                                [\w\-:.@%]+
                                (
                                    =
                                    (?:
                                        \\\"[^\\\"]*\\\"
                                        |
                                        \'[^\']*\'
                                        |
                                        [^\'\\\"=<>]+
                                    )
                                )?
                            )
                        )
                    )*
                \s*
            )
        \/>
    ";

    public static $livewireClosingTag = '<\/\s*livewire[-\:][\w\-\:\.]*\s*>';

    public static $slotOpeningTag = "
        <
            \s*
            livewire[\-\:]slot
            (?:\:(?<inlineName>\w+(?:-\w+)*))?
            (?:\s+(:?)name=(?<name>(\"[^\"]+\"|\\\'[^\\\']+\\\'|[^\s>]+)))?
            (?<attributes>
                (?:
                    \s+
                    (?:
                        (?:
                            @(?:class)(\( (?: (?>[^()]+) | (?-1) )* \))
                        )
                        |
                        (?:
                            @(?:style)(\( (?: (?>[^()]+) | (?-1) )* \))
                        )
                        |
                        (?:
                            \{\{\s*\\\$attributes(?:[^}]+?)?\s*\}\}
                        )
                        |
                        (?:
                            (\:\\\$)(\w+)
                        )
                        |
                        (?:
                            [\w\-:.@%]+
                            (
                                =
                                (?:
                                    \\\"[^\\\"]*\\\"
                                    |
                                    \'[^\']*\'
                                    |
                                    [^\'\\\"=<>]+
                                )
                            )?
                        )
                    )
                )*
                \s*
            )
            (?<![\/=\-])
        >
    ";

    public static $slotClosingTag = '<\/\s*livewire[\-\:]slot[^>]*>';

    public static $bladeDirective = "\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?";

    public static function specificBladeDirective($directive)
    {
        return "(@?$directive(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))";
    }
}
