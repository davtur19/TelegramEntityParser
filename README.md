# TelegramEntityParser
Parser for [nested entities](https://core.telegram.org/bots/api#formatting-options) of Telegram Bot API

## Requirements
PHP 7 and mbstring extension

It does not work properly on Windows!

## Usage
Use `entitiesToHtml($text, $entities);`

You can use `text` and `entity` directly from https://core.telegram.org/bots/api#message

## Notes
`mb_substr()` is very slow but it is necessary for parsing, for a faster `mb_substr()` you can try using [this](https://github.com/danog/MadelineProto/blob/a9c35bd9876bea46fe23f71dd5892ed831ab18da/src/danog/MadelineProto/TL/Conversion/BotAPI.php#L61).

Using both `UTF-16LE` and `UTF-16BE` is necessary for correct parsing, don't ask me why, it just works.

The `NULL` character is used internally in the functions, in any case Telegram will never send you that character.
