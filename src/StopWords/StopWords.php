<?php


namespace tuana8tmt\TextExtract\StopWords;


class StopWords
{
    public $stop_words = [];

    public function __construct($lang)
    {
        $stop_word = file_get_contents(__DIR__.'/'.$lang . '.txt');
        $stop_word = explode("\n", $stop_word);
        $this->stop_words = $stop_word;
    }

    public function get()
    {
        return $this->stop_words;
    }
}