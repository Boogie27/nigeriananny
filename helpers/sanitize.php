<?php



function escape($string)
{
    return htmlentities($string, ENT_QUOTE, 'UTF-8');
}