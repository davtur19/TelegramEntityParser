<?php

function mbStringToArray($string, $encoding = 'UTF-8')
{
    $array = [];
    $strlen = mb_strlen($string, $encoding);
    while ($strlen) {
        $array[] = mb_substr($string, 0, 1, $encoding);
        $string = mb_substr($string, 1, $strlen, $encoding);
        $strlen = mb_strlen($string, $encoding);
    }
    return $array;
}

function parseTagOpen($textToParse, $entity, $oTag)
{
    $i = 0;
    $textParsed = '';
    $nullControl = false;
    $string = mbStringToArray($textToParse, 'UTF-16LE');
    foreach ($string as $s) {
        if ($s === "\0\0") {
            $nullControl = !$nullControl;
        } elseif (!$nullControl) {
            if ($i == $entity['offset']) {
                $textParsed = $textParsed . $oTag;
            }
            $i++;
        }
        $textParsed = $textParsed . $s;
    }
    return $textParsed;
}

function parseTagClose($textToParse, $entity, $cTag)
{
    $i = 0;
    $textParsed = '';
    $nullControl = false;
    $string = mbStringToArray($textToParse, 'UTF-16LE');
    foreach ($string as $s) {
        $textParsed = $textParsed . $s;
        if ($s === "\0\0") {
            $nullControl = !$nullControl;
        } elseif (!$nullControl) {
            $i++;
            if ($i == ($entity['offset'] + $entity['length'])) {
                $textParsed = $textParsed . $cTag;
            }
        }
    }
    return $textParsed;
}

function htmlEscape($textToParse)
{
    $i = 0;
    $textParsed = '';
    $nullControl = false;
    $string = mbStringToArray($textToParse, 'UTF-8');
    foreach ($string as $s) {
        if ($s === "\0") {
            $nullControl = !$nullControl;
        } elseif (!$nullControl) {
            $i++;
            $textParsed = $textParsed . str_replace(['&', '"', '<', '>'], ["&amp;", "&quot;", "&lt;", "&gt;"], $s);
        } else {
            $textParsed = $textParsed . $s;
        }
    }
    return $textParsed;
}


function entitiesToHtml($text, $entities)
{
    $textToParse = mb_convert_encoding($text, 'UTF-16BE', 'UTF-8');

    foreach ($entities as $entity) {
        $href = false;
        switch ($entity['type']) {
            case 'bold':
                $tag = 'b';
                break;
            case 'italic':
                $tag = 'i';
                break;
            case 'underline':
                $tag = 'ins';
                break;
            case 'strikethrough':
                $tag = 'strike';
                break;
            case 'code':
                $tag = 'code';
                break;
            case 'pre':
                $tag = 'pre';
                break;
            case 'text_link':
                $tag = '<a href="' . $entity['url'] . '">';
                $href = true;
                break;
            case 'text_mention':
                $tag = '<a href="tg://user?id=' . $entity['user']['id'] . '">';
                $href = true;
                break;
            default:
                continue 2;
        }

        if ($href) {
            $oTag = "\0{$tag}\0";
            $cTag = "\0</a>\0";
        } else {
            $oTag = "\0<{$tag}>\0";
            $cTag = "\0</{$tag}>\0";
        }
        $oTag = mb_convert_encoding($oTag, 'UTF-16BE', 'UTF-8');
        $cTag = mb_convert_encoding($cTag, 'UTF-16BE', 'UTF-8');

        $textToParse = parseTagOpen($textToParse, $entity, $oTag);
        $textToParse = parseTagClose($textToParse, $entity, $cTag);
    }

    if (isset($entity)) {
        $textToParse = mb_convert_encoding($textToParse, 'UTF-8', 'UTF-16BE');
        $textToParse = htmlEscape($textToParse);
        return str_replace("\0", '', $textToParse);
    }

    return htmlspecialchars($text);
}