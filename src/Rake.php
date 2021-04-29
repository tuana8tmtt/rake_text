<?php

namespace tuana8tmt\TextExtract;
use \tuana8tmt\TextExtract\StopWords\StopWords;

class Rake
{
    public $stop_words = [];
    public $sentences = [];
    public $phrases = [];
    public $words = [];
    public $scores = [];
    public $scores_limit = [];

    public function extract($text, $lang, $limit)
    {
        $this->get_stopword($lang);
        $this->split_sentences($text);
        $this->split_phrases($this->sentences, $this->stop_words->get());
        $this->split_word($this->phrases);
        $this->score($this->phrases);
        $this->get_score($limit);
    }
    public function get_score($limit){
        $dem = 0;
        foreach ($this->scores as $key=>$value){
            if ($dem == $limit && $limit != 0){
                break;
            }
            $this->scores_limit[$key] = $value;
            $dem++;
        }
    }
    public function get_stopword($lang)
    {
        $this->stop_words = new StopWords($lang);
    }

    public function split_sentences($text)
    {
        $sentences =  preg_split('/(?<=[.?!])\s+(?=[a-z])/i', $text);
        $this->sentences = $sentences;
    }

    public function split_phrases($sentences, $stopWords)
    {
        $phrases = array();

        foreach ($sentences as $string)
        {

            preg_match_all('/\b.*?\b/i', $string, $matchWords);
            $matchWords = $matchWords[0];

            foreach ( $matchWords as $key=>$item ) {


                if (in_array(strtolower($item), $stopWords)) {
                    $matchWords[$key] = '';
                }
                if($item == ''){
                    unset($matchWords[$key]);
                }
            }
            $check = 0;
            $phrase_temp = '';
            foreach ($matchWords as $phrase) {

                if($phrase == ''){
                    array_push($phrases, strtolower($phrase_temp));
                    $phrase_temp = '';
                    $check = 1;

                }
                if($phrase != '') {
                    $check = 0;
                }
                if($check == 0) {
                    $phrase_temp .= $phrase;
                }
            }
        }
        $this->phrases = array_filter($phrases, fn($value) => !is_null($value) && $value !== ' ');
        $this->phrases = array_values($this->phrases);
    }
    public function split_word($phrases){
        foreach ($phrases as $phrase) {
            $word_temp = explode(" ", $phrase);
            foreach ($word_temp as $word) {
                array_push($this->words, strtolower($word));
            }
        }

        $this->words = array_filter($this->words, fn($value) => !is_null($value) && $value !== '');
    }
    public function get_word($phrase){
            $words = [];
            $word_temp = explode(" ", $phrase);
            foreach ($word_temp as $word) {
                array_push($words, strtolower($word));
            }
            return $words;
    }
    public function score($phrases){
        $frequencies = array();
        foreach ($phrases as $phrase)
        {

            $words = $this->get_word($phrase);
            $words_count = count($words);
            $words_degree = $words_count - 1;
            foreach ($words as $w)
                {
                    if($w != '') {
                        $frequencies[$w] = (isset($frequencies[$w])) ? $frequencies[$w] : 0;
                        $frequencies[$w] += 1;
                        $degrees[$w] = (isset($degrees[$w])) ? $degrees[$w] : 0;
                        $degrees[$w] += $words_degree;
                    }
                }
        }

        foreach ($frequencies as $word => $freq)
        {
            $degrees[$word] += $freq;
        }
        foreach ($frequencies as $word => $freq)
        {

            $scores[$word] = (isset($scores[$word]))? $scores[$word] : 0;
            $scores[$word] = $degrees[$word] / $freq;
        }
        arsort($scores);
        $this->scores =  $scores;
    }
}